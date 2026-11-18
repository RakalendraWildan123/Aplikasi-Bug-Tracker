<?php

return [
    'cache_store' => env('EXPORT_CACHE_STORE', 'export'),
    'cache_ttl' => (int) env('EXPORT_CACHE_TTL', 600),
    'cache_prefix' => env('EXPORT_CACHE_PREFIX', 'exports:'),
];
