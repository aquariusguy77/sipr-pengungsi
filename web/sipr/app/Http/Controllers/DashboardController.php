<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use App\Services\SiprDataService;

class DashboardController extends Controller
{
    public function __construct(
        protected SiprDataService $siprDataService,
        protected FirebaseService $firebaseService
    ) {
    }

    public function index()
    {
        return view('dashboard.index', array_merge($this->baseViewData(), [
            'pageHeading' => 'Dashboard Operasional',
            'pageDescription' => 'Pemantauan ringkas data pengungsi, dokumen, verifikasi, dan aktivitas pembaruan.',
            'stats' => $this->siprDataService->stats(),
            'activities' => $this->siprDataService->recentActivities(),
            'refugees' => $this->siprDataService->refugees()->take(5),
            'integrationConfig' => $this->firebaseService->config(),
        ]));
    }
}
