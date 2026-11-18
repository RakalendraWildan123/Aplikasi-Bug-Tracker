<?php

namespace App\Services;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;

class ExportService
{
    public function generatePdf(string $key, string $view, array $data = [], array $options = [], bool $forceRefresh = false): array
    {
        $cacheKey = $this->buildCacheKey($key, $data, $options);
        $store = $this->cacheStore();

        if ($forceRefresh) {
            $store->forget($cacheKey);
        }

        $cached = $store->get($cacheKey);

        if (is_string($cached)) {
            return [
                'content' => base64_decode($cached, true) ?: $cached,
                'cache_status' => 'HIT',
                'cache_key' => $cacheKey,
            ];
        }

        $pdf = SnappyPdf::loadView($view, $data);

        foreach ($options as $option => $value) {
            $pdf->setOption($option, $value);
        }

        $output = $pdf->output();
        $ttl = (int) config('export.cache_ttl', 600);

        $store->put($cacheKey, base64_encode($output), now()->addSeconds($ttl));

        return [
            'content' => $output,
            'cache_status' => 'MISS',
            'cache_key' => $cacheKey,
        ];
    }

    public function forget(string $key, array $data = [], array $options = []): bool
    {
        return $this->cacheStore()->forget($this->buildCacheKey($key, $data, $options));
    }

    public function buildCacheKey(string $key, array $data = [], array $options = []): string
    {
        $prefix = (string) config('export.cache_prefix', 'exports:');
        $signature = hash('sha256', json_encode(['data' => $data, 'options' => $options]));

        return $prefix.$key.':'.$signature;
    }

    protected function cacheStore(): CacheRepository
    {
        return Cache::store((string) config('export.cache_store', 'export'));
    }
}
