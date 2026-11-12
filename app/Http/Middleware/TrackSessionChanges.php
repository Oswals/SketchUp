<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackSessionChanges
{
    public function handle(Request $request, Closure $next): Response
    {
        $sessionIdBefore = session()->getId();

        Log::info('🔐 SESSION AVANT', [
            'url' => $request->fullUrl(),
            'session_id' => $sessionIdBefore,
            'cookie_session' => $request->cookie(config('session.cookie')),
        ]);

        $response = $next($request);

        $sessionIdAfter = session()->getId();

        if ($sessionIdBefore !== $sessionIdAfter) {
            Log::error('⚠️⚠️⚠️ SESSION CHANGÉE !', [
                'url' => $request->fullUrl(),
                'before' => $sessionIdBefore,
                'after' => $sessionIdAfter,
            ]);
        }

        return $response;
    }
}