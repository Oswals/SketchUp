<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Conversion;
use App\Models\CoinTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
  public function index()
    {
        // 📊 STATISTIQUES GÉNÉRALES
        $stats = [
            // Utilisateurs
            'total_users' => User::count(),
            'active_users' => User::where('status', 1)->count(),
            'pending_users' => User::where('status', 2)->count(),
            'disabled_users' => User::where('status', 0)->count(),
            'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'new_users_week' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()])->count(),
            
            // Conversions
            'total_conversions' => Conversion::count(),
            'conversions_completed' => Conversion::where('status', 'completed')->count(),
            'conversions_failed' => Conversion::where('status', 'failed')->count(),
            'conversions_today' => Conversion::whereDate('created_at', Carbon::today())->count(),
            'conversions_week' => Conversion::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()])->count(),
            
            // Coins
            'total_coins_distributed' => CoinTransaction::where('type', 'credit')->sum('coins'),
            'total_coins_used' => abs(CoinTransaction::where('type', 'debit')->sum('coins')),
            'coins_in_circulation' => User::sum('coin_balance'),
        ];

        // Calculs dérivés
        $stats['conversion_success_rate'] = $stats['total_conversions'] > 0 
            ? round(($stats['conversions_completed'] / $stats['total_conversions']) * 100, 2) 
            : 0;

        // 📈 GRAPHIQUE: Conversions des 7 derniers jours
        $conversionsChart = Conversion::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
            )
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 📈 GRAPHIQUE: Inscriptions des 7 derniers jours
        $usersChart = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 🏆 TOP 5 Utilisateurs (plus de conversions)
        $topUsers = User::withCount(['conversions' => function($query) {
                $query->where('status', 'completed');
            }])
            ->having('conversions_count', '>', 0)
            ->orderBy('conversions_count', 'desc')
            ->take(5)
            ->get();

        // 🔴 CONVERSIONS ÉCHOUÉES RÉCENTES
        $recentFailedConversions = Conversion::with('user')
            ->where('status', 'failed')
            ->latest()
            ->take(5)
            ->get();

        // ⏳ UTILISATEURS EN ATTENTE
        $pendingUsers = User::where('status', 2)
            ->latest()
            ->take(5)
            ->get();

        // 📊 VERSIONS SKETCHUP LES PLUS UTILISÉES
        $popularVersions = Conversion::select(
                'target_version',
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'completed')
            ->groupBy('target_version')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // 🕐 TEMPS MOYEN DE CONVERSION
        $avgConversionTime = Conversion::where('status', 'completed')
            ->whereNotNull('conversion_duration')
            ->avg('conversion_duration') ?? 0;

        // 📦 POIDS TOTAL CONVERTI (en MB)
        $totalSizeConverted = Conversion::where('status', 'completed')
            ->sum('converted_size') / (1024 * 1024);

        // 📊 STATS COINS (pour les graphiques)
        $coinStats = [
            'total_in_circulation' => User::sum('coin_balance'),
            'total_distributed' => CoinTransaction::where('type', 'credit')->sum('coins'),
            'total_used' => abs(CoinTransaction::where('type', 'debit')->sum('coins')),
        ];

        return view('admin.index', compact(
            'stats',
            'conversionsChart',
            'usersChart',
            'topUsers',
            'recentFailedConversions',
            'pendingUsers',
            'popularVersions',
            'avgConversionTime',
            'totalSizeConverted',
            'coinStats'
        ));
    }
}