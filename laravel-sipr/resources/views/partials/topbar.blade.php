<header class="topbar">
    @php
        $initials = collect(explode(' ', trim($currentUser['name'])))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    @endphp
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu">
            <x-icon name="menu" class="menu-icon" />
        </button>
        <div class="page-title">
            <h2>{{ $pageHeading }}</h2>
            <p>{{ $pageDescription }}</p>
        </div>
    </div>

    <div class="topbar-right">
        <label class="toolbar-search" aria-label="Pencarian cepat">
            <x-icon name="search" class="menu-icon" />
            <input type="search" placeholder="Cari nama, ID internal, dokumen, atau lokasi" @disabled(! $isSignedIn)>
        </label>
        <span class="status-pill">
            <x-icon name="sync" class="chip-icon" />
            {{ $isSignedIn ? 'Sinkronisasi lokal 15 menit lalu' : 'Menunggu sesi login aktif' }}
        </span>
        <span class="badge">{{ $currentRole['label'] }} • {{ strtoupper($currentRole['source']) }}</span>
        <div class="user-chip">
            <div class="avatar">{{ $initials !== '' ? $initials : 'TM' }}</div>
            <div>
                <strong>{{ $currentUser['name'] }}</strong>
                <small>{{ $isSignedIn ? $currentRole['label'] : 'Belum login' }}</small>
            </div>
        </div>
    </div>
</header>
