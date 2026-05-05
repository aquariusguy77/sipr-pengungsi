<?php

namespace App\Http\Controllers;

use App\Services\RoleAccessService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected RoleAccessService $roleAccessService
    ) {
    }

    public function showLogin(): View|RedirectResponse
    {
        if ($this->roleAccessService->isSignedIn()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login', array_merge($this->baseViewData(), [
            'pageHeading' => 'Masuk ke SIPR',
            'pageDescription' => 'Gunakan akun Laravel bila sudah tersedia, atau pakai login demo untuk menguji pembatasan akses per role.',
            'roles' => $this->roleAccessService->roles(),
            'authModes' => $this->roleAccessService->authModes(),
            'defaultAuthMode' => $this->roleAccessService->defaultAuthMode(),
        ]));
    }

    public function login(Request $request): RedirectResponse
    {
        $availableModes = $this->roleAccessService->authModes();
        $mode = (string) $request->input('login_mode', 'demo');

        if (! array_key_exists($mode, $availableModes)) {
            return back()
                ->withErrors(['login_mode' => 'Mode login tidak dikenali.'])
                ->withInput();
        }

        if ($mode === 'auth') {
            $validated = $request->validate([
                'email' => ['required', 'email', 'max:120'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            if (! Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                return back()
                    ->withErrors(['email' => 'Email atau password tidak cocok.'])
                    ->withInput($request->except('password'));
            }

            $request->session()->forget('sipr_user');
            $request->session()->regenerate();

            return redirect()
                ->route('dashboard.index')
                ->with('status', 'Login Laravel berhasil. Role aktif diambil dari akun pengguna.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:120'],
            'role' => ['required', 'in:admin,petugas,supervisor'],
        ]);

        Auth::logout();
        $request->session()->put('sipr_user', [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
        ]);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard.index')
            ->with('status', 'Sesi demo berhasil dimulai sebagai ' . ($this->roleAccessService->roles()[$validated['role']]['label'] ?? $validated['role']) . '.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget('sipr_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Sesi demo sudah diakhiri.');
    }
}
