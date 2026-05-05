<?php

namespace App\Http\Controllers;

use App\Services\SiprDataService;

class HistoryController extends Controller
{
    public function __construct(
        protected SiprDataService $siprDataService
    ) {
    }

    public function index()
    {
        $this->ensureAbility('review-changes');

        return view('history.index', array_merge($this->baseViewData(), [
            'pageHeading' => 'Riwayat & Laporan',
            'pageDescription' => 'Audit trail perubahan data, verifikasi, rekap laporan, dan log unduhan operasional.',
            'history' => $this->siprDataService->history(),
            'activities' => $this->siprDataService->recentActivities(),
            'reports' => $this->siprDataService->reports(),
            'reportLogs' => $this->siprDataService->reportLogs(),
        ]));
    }
}
