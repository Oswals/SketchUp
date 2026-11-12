<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * Liste de tous les utilisateurs avec filtres
     */
    public function index(Request $request)
    {
        $query = User::withCount('conversions')
            ->with(['conversions' => function($q) {
                $q->latest()->limit(5);
            }]);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('min_coins')) {
            $query->where('coin_balance', '>=', $request->min_coins);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20)->withQueryString();

        // Stats pour les cartes
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 1)->count(),
            'pending' => User::where('status', 2)->count(),
            'disabled' => User::where('status', 0)->count(),
            'total_coins' => User::sum('coin_balance'),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load([
            'conversions' => fn($q) => $q->latest()->limit(10),
            'coinTransactions' => fn($q) => $q->latest()->limit(20)
        ]);

        // Statistiques de l'utilisateur
        $userStats = [
            'total_conversions' => $user->conversions()->count(),
            'completed_conversions' => $user->conversions()->where('status', 'completed')->count(),
            'failed_conversions' => $user->conversions()->where('status', 'failed')->count(),
            'total_coins_used' => abs($user->coinTransactions()->where('type', 'debit')->sum('coins')),
            'total_coins_received' => $user->coinTransactions()->where('type', 'credit')->sum('coins'),
            'last_login' => $user->updated_at, // ✅ Utiliser updated_at comme approximation
            'member_since' => $user->created_at,
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:0,1,2',
            'role' => 'required|in:user,admin',
            'coin_balance' => 'required|integer|min:0',
            'password' => 'nullable|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // Mise à jour des champs
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->status = $validated['status'];
            $user->role = $validated['role'];
            
            // Gestion du solde de coins
            if ($validated['coin_balance'] != $user->coin_balance) {
                $difference = $validated['coin_balance'] - $user->coin_balance;
                $type = $difference > 0 ? 'credit' : 'debit';
                
                CoinTransaction::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'coins' => abs($difference),
                    'balance_after' => $validated['coin_balance'],
                    'description' => 'Ajustement admin: ' . ($difference > 0 ? '+' : '') . $difference . ' coins',
                ]);
                
                $user->coin_balance = $validated['coin_balance'];
            }

            // Mise à jour du mot de passe si fourni
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
                $user->clean_password = true;
            }

            $user->save();
            DB::commit();

            return redirect()->route('admin.users.show', $user)
                ->with('success', '✅ Utilisateur mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un utilisateur (soft delete)
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', '✅ Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Activer un utilisateur
     */
    public function activate(User $user)
    {
        $user->update(['status' => 1]);
        return back()->with('success', '✅ Utilisateur activé');
    }

    /**
     * Désactiver un utilisateur
     */
    public function deactivate(User $user)
    {
        $user->update(['status' => 0]);
        return back()->with('success', '⚠️ Utilisateur désactivé');
    }

    /**
     * Ajouter des coins à un utilisateur
     */
    public function addCoins(Request $request, User $user)
    {
        $validated = $request->validate([
            'coins' => 'required|integer',
            'description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $coins = $validated['coins'];
            $type = $coins > 0 ? 'credit' : 'debit';
            $newBalance = $user->coin_balance + $coins;

            if ($newBalance < 0) {
                return back()->with('error', '❌ Solde insuffisant');
            }

            CoinTransaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'coins' => abs($coins),
                'balance_after' => $newBalance,
                'description' => $validated['description'] ?? 'Ajustement admin',
            ]);

            $user->increment('coin_balance', $coins);
            DB::commit();

            return back()->with('success', '✅ ' . abs($coins) . ' coins ' . ($coins > 0 ? 'ajoutés' : 'retirés'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }
}