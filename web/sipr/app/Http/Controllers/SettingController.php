<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use App\Services\RoleAccessService;

class SettingController extends Controller
{
    public function __construct(
        protected FirebaseService $firebaseService,
        protected RoleAccessService $roleAccessService
    ) {
    }

    public function index()
    {
        $this->ensureAbility('manage-settings');

        return view('settings.index', array_merge($this->baseViewData(), [
            'pageHeading' => 'Pengaturan',
            'pageDescription' => 'Konfigurasi sistem, hak akses dan akun, notifikasi, keamanan, dan backup.',
            'integrationConfig' => $this->firebaseService->config(),
            'roles' => $this->roleAccessService->roles(),
            'roleFlow' => $this->roleAccessService->flow(),
        ]));
    }
}
