<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ExportService;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfWrapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class StackFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_api_status_response(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertJson([
            'status' => 'online',
        ]);
    }

    public function test_database_and_redis_export_cache_defaults_are_configured(): void
    {
        $this->assertSame('mysql', config('database.connections.mysql.driver'));
        $this->assertSame('predis', config('database.redis.client'));
        $this->assertArrayHasKey('export', config('cache.stores'));
        $this->assertSame('export', config('export.cache_store'));
    }

    public function test_export_service_caches_snappy_pdf_output_and_supports_refresh(): void
    {
        config(['cache.stores.export.driver' => 'array']);
        Cache::store('export')->flush();

        $pdfMock = Mockery::mock(PdfWrapper::class);
        $pdfMock->shouldReceive('setOption')->twice()->andReturnSelf();
        $pdfMock->shouldReceive('output')->once()->andReturn('%PDF-1.4 sample-v1');

        SnappyPdf::shouldReceive('loadView')
            ->once()
            ->with('exports.sample', Mockery::type('array'))
            ->andReturn($pdfMock);

        $user = User::factory()->create();

        $firstResponse = $this->actingAs($user)->get('/exports/sample-pdf');
        $firstResponse->assertOk();
        $firstResponse->assertHeader('Content-Type', 'application/pdf');
        $firstResponse->assertHeader('X-Export-Cache', 'MISS');
        $this->assertSame('%PDF-1.4 sample-v1', $firstResponse->getContent());

        // Second request must be served from cache without calling SnappyPdf again
        $secondResponse = $this->actingAs($user)->get('/exports/sample-pdf');
        $secondResponse->assertOk();
        $secondResponse->assertHeader('X-Export-Cache', 'HIT');
        $this->assertSame('%PDF-1.4 sample-v1', $secondResponse->getContent());
    }

    public function test_export_service_can_invalidate_cache_entry(): void
    {
        config(['cache.stores.export.driver' => 'array']);
        Cache::store('export')->flush();

        $pdfMock = Mockery::mock(PdfWrapper::class);
        $pdfMock->shouldReceive('output')->twice()->andReturn('%PDF-1.4 v1', '%PDF-1.4 v2');

        SnappyPdf::shouldReceive('loadView')
            ->twice()
            ->with('exports.sample', ['title' => 'Test', 'items' => []])
            ->andReturn($pdfMock);

        $service = app(ExportService::class);
        $first = $service->generatePdf('unit-test', 'exports.sample', ['title' => 'Test', 'items' => []]);
        $this->assertSame('MISS', $first['cache_status']);
        $this->assertSame('%PDF-1.4 v1', $first['content']);

        $service->forget('unit-test', ['title' => 'Test', 'items' => []]);

        $second = $service->generatePdf('unit-test', 'exports.sample', ['title' => 'Test', 'items' => []]);
        $this->assertSame('MISS', $second['cache_status']);
        $this->assertSame('%PDF-1.4 v2', $second['content']);
    }
}
