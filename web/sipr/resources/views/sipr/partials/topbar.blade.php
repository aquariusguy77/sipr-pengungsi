<header class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu">
            <svg class="menu-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['menu'] }}"/></svg>
        </button>
        <div class="page-title">
            <h2>Dashboard Operasional</h2>
            <p>Pemantauan ringkas data pengungsi, dokumen, verifikasi, dan aktivitas pembaruan.</p>
        </div>
    </div>

    <div class="topbar-right">
        <label class="toolbar-search" aria-label="Pencarian cepat">
            <svg class="menu-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['search'] }}"/></svg>
            <input type="search" placeholder="Cari nama, ID internal, dokumen, atau lokasi">
        </label>
        <span class="status-pill">
            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['sync'] }}"/></svg>
            Sinkronisasi lokal 15 menit lalu
        </span>
        <div class="user-chip">
            <div class="avatar">RS</div>
            <div>
                <strong>Supervisor Shift</strong>
                <small>Rudenim Surabaya</small>
            </div>
        </div>
    </div>
</header>
