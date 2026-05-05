<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-mark">
            <svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['shield'] }}"/></svg>
        </div>
        <div>
            <h1>{{ $appConfig['systemName'] }}</h1>
            <p>Sistem Informasi Pendataan Pengungsi</p>
        </div>
    </div>

    <p class="sidebar-note">
        Fondasi antarmuka internal untuk pengelolaan data pengungsi, dokumen, audit trail, dan laporan operasional.
    </p>

    <span class="nav-label">Navigasi Utama</span>
    <nav class="menu" aria-label="Menu utama SIPR">
        @foreach ($menuItems as $index => $item)
            <a class="menu-link {{ $index === 0 ? 'active' : '' }}" href="#{{ $item['id'] }}">
                <svg class="menu-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap[$item['icon']] }}"/></svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <div class="status-card">
            <strong>Status Lingkungan</strong>
            <p class="status-note">MVP lokal Rudenim Surabaya. Integrasi SIMKIM belum aktif dan disiapkan sebagai placeholder.</p>
        </div>
        <button class="logout-button" type="button">
            <svg class="menu-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['logout'] }}"/></svg>
            <span>Keluar</span>
        </button>
    </div>
</aside>
