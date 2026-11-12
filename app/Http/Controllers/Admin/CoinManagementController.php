<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class CoinManagementController extends Controller
{
    /**
     * Affiche la liste des transactions de coins (READ ONLY)
     */
    public function transactions(Request $request)
    {
        $query = CoinTransaction::with(['user', 'conversion'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(20);

        // Statistiques globales
        $stats = [
            'total_distributed' => CoinTransaction::where('type', 'credit')->sum('coins'),
            'total_used' => abs(CoinTransaction::where('type', 'debit')->sum('coins')),
            'total_in_circulation' => User::sum('coin_balance'),
            'average_per_user' => round(User::avg('coin_balance'), 2),
            'total_transactions' => CoinTransaction::count(),
        ];

        // Liste des utilisateurs pour le filtre
        $users = User::select('id', 'username', 'email')
            ->orderBy('username')
            ->get();

        return view('admin.coins.transactions', compact('transactions', 'stats', 'users'));
    }

    /**
     * Statistiques pour le dashboard
     */
    public static function getDashboardStats()
    {
        return [
            'total_in_circulation' => User::sum('coin_balance'),
            'total_distributed' => CoinTransaction::where('type', 'credit')->sum('coins'),
            'total_used' => abs(CoinTransaction::where('type', 'debit')->sum('coins')),
            'transactions_today' => CoinTransaction::whereDate('created_at', today())->count(),
            'average_per_user' => round(User::avg('coin_balance'), 2),
        ];
    }
}