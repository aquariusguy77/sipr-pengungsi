<?php

namespace App\Http\Controllers;

use App\Services\SiprDataService;

class ReportController extends Controller
{
    public function __construct(
        protected SiprDataService $siprDataService
    ) {
    }

    public function index()
    {
        $this->ensureAbility('view-reports');

        return view('reports.index', array_merge($this->baseViewData(), [
            'pageHeading' => 'Laporan',
            'pageDescription' => 'Rekap data aktif, ekspor, prioritas verifikasi, dan log unduhan.',
            'reports' => $this->siprDataService->reports(),
            'reportLogs' => $this->siprDataService->reportLogs(),
        ]));
    }
}
