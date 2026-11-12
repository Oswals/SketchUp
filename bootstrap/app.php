<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Ajouter le middleware de log à toutes les requêtes web
        $middleware->web(append: [
            \App\Http\Middleware\TrackSessionChanges::class, // ← AJOUTEZ CECI

        ]);


        $middleware->alias([
        'auth.conversion' => \App\Http\Middleware\CheckAuthForConversion::class,
        'check.conversion' => \App\Http\Middleware\CheckConversionLimit::class,
        'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
