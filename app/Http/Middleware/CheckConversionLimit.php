<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckConversionLimit
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to convert files.');
        }

        // Vérifier le solde
        if (auth()->user()->coin_balance <= 0) {
            // Si la requête vient de la page home
            if ($request->is('/') || $request->is('home')) {
                return back()->with('insufficient_balance_home', true);
            }
            
            // Si la requête vient de la page profile/conversion
            return back()->with('insufficient_balance_profile', true);
        }

        return $next($request);
    }
    
}