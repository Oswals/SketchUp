<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | Utilisation de 'database' pour une meilleure persistance des sessions
    | lors des rechargements de page et pour éviter les pertes de session.
    |
    | Supported: "file", "cookie", "database", "apc",
    |            "memcached", "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Durée de vie de la session augmentée à 1440 minutes (24 heures)
    | pour éviter que les utilisateurs perdent leurs conversions trop rapidement.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 1440),

    /*
    |--------------------------------------------------------------------------
    | Expire On Close
    |--------------------------------------------------------------------------
    |
    | Défini à FALSE pour que la session persiste même après fermeture du navigateur.
    | Les utilisateurs pourront revenir plus tard et retrouver leurs conversions.
    |
    */

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | Cette option permet de crypter toutes les données de session avant stockage.
    | Recommandé en production pour la sécurité.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | Emplacement des fichiers de session (utilisé uniquement si driver = 'file').
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | Connexion utilisée pour stocker les sessions en base de données.
    | NULL = connexion par défaut de l'application.
    |
    */

    'connection' => env('SESSION_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | Table utilisée pour stocker les sessions en base de données.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | Store de cache utilisé pour les drivers basés sur le cache.
    |
    */

    'store' => env('SESSION_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | Probabilité de nettoyage automatique des sessions expirées.
    | [2, 100] = 2% de chance à chaque requête.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Nom du cookie de session. Par défaut basé sur APP_NAME.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | Chemin pour lequel le cookie sera disponible.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Domaine pour lequel le cookie sera disponible.
    | NULL = domaine actuel uniquement.
    |
    */

    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | Si TRUE, le cookie ne sera envoyé que via HTTPS.
    | En développement local (HTTP), mettre à FALSE.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Si TRUE, JavaScript ne pourra pas accéder au cookie (protection XSS).
    | Recommandé de laisser à TRUE.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | Protection contre les attaques CSRF.
    | 'lax' = bon compromis entre sécurité et fonctionnalité.
    |
    | Supported: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Cookies partitionnés pour le contexte cross-site.
    | Généralement FALSE sauf besoins spécifiques.
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];