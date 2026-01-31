<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Permit both localhost and 127.0.0.1 for landing and SPA dev servers
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://127.0.0.1:8002,http://localhost:8002,http://localhost:5174,http://localhost:5175')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
