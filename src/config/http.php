<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    */
    'cors' => [
        'paths' => ['api/*', 'sanctum/csrf-cookie'],
        'allowed_methods' => ['*'],
        'allowed_origins' => ['*'], // besser: deine echte Domain eintragen
        'allowed_origins_patterns' => [],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => false,
    ],

];
