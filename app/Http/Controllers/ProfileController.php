<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les conversions de l'utilisateur
        $conversions = Conversion::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('auth.profile', compact('user', 'conversions'));
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
        ], [
            'username.required' => 'Le nom d\'utilisateur est obligatoire.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
            'avatar.max' => 'L\'image ne doit pas dépasser 2MB.',
        ]);

        // Gestion de l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Enregistrer le nouvel avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Mettre à jour l'utilisateur
        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', '✅ Profil mis à jour avec succès !');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile.index')
                ->with('error', '❌ Le mot de passe actuel est incorrect.')
                ->withInput();
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('profile.index')
            ->with('success', '✅ Mot de passe modifié avec succès !');
    }

    /**
     * Supprimer le compte utilisateur
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Le mot de passe est obligatoire pour supprimer votre compte.',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->route('profile.index')
                ->with('error', '❌ Mot de passe incorrect. Suppression annulée.');
        }

        // Supprimer l'avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Supprimer toutes les conversions
        $conversions = Conversion::where('user_id', $user->id)->get();
        foreach ($conversions as $conversion) {
            // Supprimer le fichier physique
            $conversion->deleteFile(); // Utilise la méthode du Model Conversion
            $conversion->delete();
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', '✅ Votre compte a été supprimé avec succès.');
    }



}