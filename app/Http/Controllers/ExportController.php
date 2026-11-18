<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    public function __construct(
        private readonly ExportService $exportService
    ) {}

    public function samplePdf(Request $request): Response
    {
        $payload = [
            'title' => 'Laporan Smoke Test - Aplikasi Bug Tracker',
            'items' => [
                ['code' => 'BUG-101', 'title' => 'Setup Laravel 12 & MySQL', 'status' => 'Done'],
                ['code' => 'BUG-102', 'title' => 'Vue 3 Standalone + Tailwind CSS + HTML5 DnD', 'status' => 'In Progress'],
                ['code' => 'BUG-103', 'title' => 'Redis Export Cache + Snappy PDF Engine', 'status' => 'Review'],
            ],
        ];

        $result = $this->exportService->generatePdf(
            key: 'sample-report',
            view: 'exports.sample',
            data: $payload,
            options: ['encoding' => 'utf-8', 'page-size' => 'A4'],
            forceRefresh: $request->boolean('refresh')
        );

        return response($result['content'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="sample-report.pdf"',
            'X-Export-Cache' => $result['cache_status'],
        ]);
    }
}
