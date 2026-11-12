<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Log::info('🔄 RedirectIfAuthenticated: Utilisateur déjà connecté', [
                    'user_id' => Auth::id(),
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role,
                ]);
                // ✅ Redirection selon le rôle
                if (Auth::user()->isAdmin()) {
                    return redirect()->route('admin.index');
                }
                // ✅ Si conversion en attente
                if (session()->has('pending_conversion')) {
                    return redirect()->route('conversion.processPending');
                }
                // ✅ Redirection par défaut vers le dashboard
                return redirect()->route('home');
            }
        }
        return $next($request);
    }
} 