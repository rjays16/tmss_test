<?php

return [
    'enabled' => env('CDN_ENABLED', true),
    
    'base_url' => env('CDN_BASE_URL', ''),
    
    'cache_ttl' => env('CDN_CACHE_TTL', 3600),
    
    'headers' => [
        'cache_control' => env('CDN_CACHE_CONTROL', 'public, max-age=3600'),
        'vary' => env('CDN_VARY', 'Accept-Language'),
    ],
];
