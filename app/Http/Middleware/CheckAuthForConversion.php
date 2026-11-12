<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthForConversion
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            Log::info('🔒 Accès non authentifié détecté pour: ' . $request->path());
            
            // Sauvegarder l'URL de destination
            session(['url.intended' => url()->current()]);
            
            return redirect()->route('login')
                ->with('info', 'Veuillez vous connecter pour accéder à vos conversions.');
        }

        return $next($request);
    }
}