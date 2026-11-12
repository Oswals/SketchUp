<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConversionManagementController extends Controller
{
    /**
     * Liste de toutes les conversions avec filtres
     */
    public function index(Request $request)
    {
        $query = Conversion::with(['user', 'coinTransaction']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('source_version')) {
            $query->where('source_version', $request->source_version);
        }

        if ($request->filled('target_version')) {
            $query->where('target_version', $request->target_version);
        }

        if ($request->filled('downloaded')) {
            $query->where('downloaded', $request->downloaded === 'yes' ? 1 : 0);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $conversions = $query->paginate(20)->withQueryString();

        // Statistiques générales
        $stats = [
            'total' => Conversion::count(),
            'completed' => Conversion::where('status', 'completed')->count(),
            'failed' => Conversion::where('status', 'failed')->count(),
            'processing' => Conversion::where('status', 'processing')->count(),
            'pending' => Conversion::where('status', 'pending')->count(),
            'success_rate' => Conversion::count() > 0 
                ? round((Conversion::where('status', 'completed')->count() / Conversion::count()) * 100, 2) 
                : 0,
            'avg_duration' => round(Conversion::where('status', 'completed')
                ->whereNotNull('conversion_duration')
                ->avg('conversion_duration') ?? 0, 2),
            'total_size_mb' => round(Conversion::where('status', 'completed')
                ->sum('converted_size') / 1048576, 2),
        ];

        // Versions les plus populaires
        $popularVersions = Conversion::selectRaw('target_version, COUNT(*) as count')
            ->groupBy('target_version')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.conversions.index', compact('conversions', 'stats', 'popularVersions'));
    }

    /**
     * Afficher les détails d'une conversion
     */
    public function show(Conversion $conversion)
    {
        // ✅ Charger les relations nécessaires
        $conversion->load(['user', 'coinTransaction']);

        return view('admin.conversions.show', compact('conversion'));
    }

    /**
     * Supprimer une conversion
     */
    public function destroy(Conversion $conversion)
    {
        try {
            // Supprimer les fichiers si ils existent
            if ($conversion->original_file_path && Storage::exists($conversion->original_file_path)) {
                Storage::delete($conversion->original_file_path);
                Log::info('🗑️ Fichier original supprimé', ['path' => $conversion->original_file_path]);
            }

            if ($conversion->converted_file_path && Storage::exists($conversion->converted_file_path)) {
                Storage::delete($conversion->converted_file_path);
                Log::info('🗑️ Fichier converti supprimé', ['path' => $conversion->converted_file_path]);
            }

            $conversion->delete();

            Log::info('✅ Conversion supprimée', ['id' => $conversion->id]);

            return redirect()->route('admin.conversions.index')
                ->with('success', '✅ Conversion supprimée avec succès');
        } catch (\Exception $e) {
            Log::error('❌ Erreur suppression conversion', [
                'id' => $conversion->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Relancer une conversion échouée
     */
    public function retry(Conversion $conversion)
    {
        if ($conversion->status !== 'failed') {
            return back()->with('error', '❌ Seules les conversions échouées peuvent être relancées');
        }

        try {
            // Réinitialiser le statut
            $conversion->update([
                'status' => 'pending',
                'error_message' => null,
                'conversion_duration' => null,
            ]);

            Log::info('🔄 Conversion relancée', ['id' => $conversion->id]);

            // TODO: Redéclencher le job de conversion
            // dispatch(new ConvertSketchupJob($conversion));

            return back()->with('success', '✅ Conversion relancée avec succès');
        } catch (\Exception $e) {
            Log::error('❌ Erreur relance conversion', [
                'id' => $conversion->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }
}