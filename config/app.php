<?php

return [

    'name' => env('APP_NAME', 'O\'G Délice'),  // ✅ était env('Restaurant Delice', 'Laravel') — clé invalide

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => 'Africa/Dakar',  // ✅ UTC → Africa/Dakar (GMT+0, pas de décalage mais dates correctes)

    'locale' => env('APP_LOCALE', 'fr'),        // ✅ en → fr

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'fr'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'fr_FR'),  // ✅ pour les seeders

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    // ✅ aliases corrigé — Str déjà auto-loadé par Laravel, pas besoin de le déclarer ici
    // Le bloc 'aliases' incomplet qui était en dehors du tableau a été supprimé

];
