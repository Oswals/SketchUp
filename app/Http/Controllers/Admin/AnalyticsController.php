<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Conversion;
use App\Models\CoinTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Page principale des statistiques
     */
    public function index(Request $request)
    {
        // Période sélectionnée (par défaut: 30 derniers jours)
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays((int)$period);

        // ===== STATISTIQUES GÉNÉRALES =====
        $stats = [
            // Utilisateurs
            'total_users' => User::count(),
            'active_users' => User::where('status', 1)->count(),
            'new_users_period' => User::where('created_at', '>=', $startDate)->count(),
            'users_with_conversions' => User::has('conversions')->count(),
            
            // Conversions
            'total_conversions' => Conversion::count(),
            'conversions_period' => Conversion::where('created_at', '>=', $startDate)->count(),
            'success_rate' => $this->getSuccessRate(),
            'avg_conversion_time' => $this->getAverageConversionTime(),
            
            // Coins
            'total_coins_distributed' => CoinTransaction::where('type', 'credit')->sum('coins'),
            'total_coins_used' => abs(CoinTransaction::where('type', 'debit')->sum('coins')),
            'coins_in_circulation' => User::sum('coin_balance'),
            
            // Fichiers
            'total_size_processed' => $this->getTotalSizeProcessed(),
            'avg_file_size' => $this->getAverageFileSize(),
        ];

        // ===== GRAPHIQUES =====
        
        // Conversions par jour (30 derniers jours)
        $conversionsChart = $this->getConversionsChart($period);
        
        // Inscriptions par jour
        $usersChart = $this->getUsersChart($period);
        
        // Utilisation des coins
        $coinsChart = $this->getCoinsChart($period);
        
        // Conversions par version
        $versionStats = $this->getVersionStats();
        
        // Heures de pointe
        $peakHours = $this->getPeakHours();
        
        // Top utilisateurs
        $topUsers = $this->getTopUsers(10);
        
        // Taux de réussite par version
        $successRateByVersion = $this->getSuccessRateByVersion();
        
        // Répartition des statuts utilisateurs
        $userStatusDistribution = $this->getUserStatusDistribution();

        return view('admin.analytics', compact(
            'stats',
            'conversionsChart',
            'usersChart',
            'coinsChart',
            'versionStats',
            'peakHours',
            'topUsers',
            'successRateByVersion',
            'userStatusDistribution',
            'period'
        ));
    }

    /**
     * Taux de réussite global
     */
    private function getSuccessRate()
    {
        $total = Conversion::count();
        if ($total === 0) return 0;
        
        $completed = Conversion::where('status', 'completed')->count();
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Temps moyen de conversion
     */
    private function getAverageConversionTime()
    {
        return round(
            Conversion::where('status', 'completed')
                ->whereNotNull('conversion_duration')
                ->avg('conversion_duration') ?? 0,
            2
        );
    }

    /**
     * Taille totale traitée (en MB)
     */
    private function getTotalSizeProcessed()
    {
        return round(
            Conversion::where('status', 'completed')->sum('converted_size') / (1024 * 1024),
            2
        );
    }

    /**
     * Taille moyenne des fichiers (en MB)
     */
    private function getAverageFileSize()
    {
        return round(
            Conversion::avg('original_size') / (1024 * 1024),
            2
        );
    }

    /**
     * Graphique des conversions
     */
    private function getConversionsChart($days)
    {
        return Conversion::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
            )
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Graphique des inscriptions
     */
    private function getUsersChart($days)
    {
        return User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Graphique utilisation des coins
     */
    private function getCoinsChart($days)
    {
        return CoinTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN coins ELSE 0 END) as credited'),
                DB::raw('ABS(SUM(CASE WHEN type = "debit" THEN coins ELSE 0 END)) as debited')
            )
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Stats par version
     */
    private function getVersionStats()
    {
        return Conversion::select(
                'target_version',
                DB::raw('COUNT(*) as count'),
                DB::raw('ROUND(AVG(conversion_duration), 2) as avg_time')
            )
            ->where('status', 'completed')
            ->groupBy('target_version')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Heures de pointe
     */
    private function getPeakHours()
    {
        return Conversion::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    /**
     * Top utilisateurs
     */
    private function getTopUsers($limit = 10)
    {
        return User::withCount(['conversions' => function($query) {
                $query->where('status', 'completed');
            }])
            ->having('conversions_count', '>', 0)
            ->orderBy('conversions_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Taux de réussite par version
     */
    private function getSuccessRateByVersion()
    {
        return Conversion::select(
                'target_version',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('ROUND((SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as success_rate')
            )
            ->groupBy('target_version')
            ->orderBy('success_rate', 'desc')
            ->get();
    }

    /**
     * Répartition des statuts utilisateurs
     */
    private function getUserStatusDistribution()
    {
        return User::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function($item) {
                $statusNames = [
                    0 => 'Désactivés',
                    1 => 'Actifs',
                    2 => 'En attente'
                ];
                return [$statusNames[$item->status] ?? 'Inconnu' => $item->count];
            });
    }

    /**
     * Export des statistiques en CSV
     */
    public function exportCSV(Request $request)
    {
        $period = $request->get('period', '30');
        $conversions = Conversion::where('created_at', '>=', Carbon::now()->subDays($period))
            ->with('user')
            ->get();

        $filename = 'conversions_export_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($conversions) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['ID', 'Utilisateur', 'Email', 'Fichier', 'Version Source', 'Version Cible', 'Statut', 'Durée (s)', 'Date']);
            
            // Data
            foreach ($conversions as $conversion) {
                fputcsv($file, [
                    $conversion->id,
                    $conversion->user?->username ?? 'Invité',
                    $conversion->user?->email ?? 'N/A',
                    $conversion->original_filename,
                    $conversion->source_version,
                    $conversion->target_version,
                    $conversion->status,
                    $conversion->conversion_duration,
                    $conversion->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}