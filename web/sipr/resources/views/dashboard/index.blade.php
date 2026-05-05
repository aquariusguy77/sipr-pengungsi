@extends('layouts.app')

@php
    $toneClass = fn (string $tone) => match ($tone) {
        'green' => 'tone-green',
        'orange' => 'tone-orange',
        'deep' => 'tone-deep',
        default => 'tone-blue',
    };
@endphp

@section('content')
    @if (session('status'))
        <div class="subtle-box" style="margin-top:0;margin-bottom:16px;border-style:solid;border-color:rgba(31,157,122,.25);">
            {{ session('status') }}
        </div>
    @endif

    <section class="hero-panel section-anchor" id="dashboard">
        <div class="hero-copy">
            <span class="eyebrow">
                <x-icon name="shield" class="chip-icon" />
                Sistem Informasi Pendataan Pengungsi
            </span>
            <h3>Pusat kendali data pengungsi yang rapi, cepat dibaca, dan siap dihubungkan ke backend Laravel.</h3>
            <p>
                Halaman dashboard ini merangkum statistik utama, aktivitas terbaru, dan status kesiapan integrasi agar operator internal
                dapat memahami situasi operasional dengan cepat tanpa bagian aksi cepat.
            </p>
            <div class="hero-meta">
                <div><strong>247</strong><span>data aktif lintas lokasi penempatan</span></div>
                <div><strong>31</strong><span>dokumen menunggu verifikasi</span></div>
                <div><strong>12</strong><span>perubahan kritis menunggu persetujuan</span></div>
            </div>
        </div>

        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Prioritas Hari Ini</strong>
                    <span class="mini-badge warn">Verifikasi 9 berkas</span>
                </div>
                <p>Fokus pada data dengan dokumen identitas belum lengkap, perubahan lokasi aktif, dan histori unggah terbaru dari petugas pendataan.</p>
            </div>
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Placeholder Integrasi</strong>
                    <span class="mini-badge success">Siap disambungkan</span>
                </div>
                <p>Firebase Realtime Database dan Firebase Storage sudah dipetakan di service terpisah agar mudah dipindahkan ke lapisan backend.</p>
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Ringkasan statistik">
        @foreach ($stats as $stat)
            <article class="stat-card">
                <div class="stat-head">
                    <div><h4>{{ $stat['label'] }}</h4></div>
                    <div class="stat-icon-wrap {{ $toneClass($stat['tone']) }}">
                        <x-icon :name="$stat['icon']" class="stat-icon" />
                    </div>
                </div>
                <strong>{{ $stat['value'] }}</strong>
                <p class="metric-note">{{ $stat['note'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="double-grid">
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="history" class="chip-icon" />Aktivitas Terbaru</span>
                    <h3>Perubahan data terakhir</h3>
                    <p class="section-intro">Audit trail singkat untuk perubahan yang perlu diamati supervisor.</p>
                </div>
            </div>

            <div class="timeline">
                @foreach ($activities as $item)
                    <article class="timeline-item">
                        <div class="timeline-mark"><x-icon name="history" class="section-icon" /></div>
                        <div>
                            <strong>{{ $item['title'] }}</strong>
                            <p>{{ $item['detail'] }}</p>
                            <div class="timeline-meta">{{ $item['actor'] }} • {{ $item['time'] }}</div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="settings" class="chip-icon" />Status Integrasi</span>
                    <h3>Kesiapan backend dan sumber data</h3>
                    <p class="section-intro">Endpoint dan sumber data masih berupa placeholder yang siap dipakai di service Laravel.</p>
                </div>
            </div>

            <div class="list-group">
                <article class="list-item">
                    <strong>Firebase Realtime Database</strong>
                    <p><code>{{ $integrationConfig['firebase']['database_url'] }}</code></p>
                </article>
                <article class="list-item">
                    <strong>Struktur Path Data</strong>
                    <p>{{ $integrationConfig['firebase']['paths']['refugees'] }} • {{ $integrationConfig['firebase']['paths']['documents'] }}</p>
                </article>
                <article class="list-item">
                    <strong>Firebase Storage</strong>
                    <p>Bucket placeholder: <code>{{ $integrationConfig['firebase']['storage_bucket'] }}</code></p>
                </article>
            </div>
        </div>
    </section>

    <section class="double-grid">
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="users" class="chip-icon" />Data Pengungsi</span>
                    <h3>Data terbaru dan sambungan CRUD</h3>
                    <p class="section-intro">Dashboard ini langsung mengarah ke daftar utama, form tambah data, dan perubahan data terakhir tanpa membuat blok aksi cepat terpisah.</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a class="badge success" href="{{ route('refugees.index') }}">Buka daftar</a>
                    @if ($canManageRefugees)
                        <a class="badge" href="{{ route('refugees.create') }}">Tambah data</a>
                    @endif
                </div>
            </div>

            <div class="list-group">
                @foreach ($refugees as $refugee)
                    <article class="list-item">
                        <div class="split-header">
                            <div>
                                <h3>{{ $refugee['name'] }}</h3>
                                <p>{{ $refugee['internal_id'] }} • {{ $refugee['nationality'] }} • {{ $refugee['location'] }}</p>
                            </div>
                            <span class="badge {{ in_array($refugee['status'], ['Aktif', 'Lengkap'], true) ? 'success' : ($refugee['status'] === 'Verifikasi' ? 'warn' : 'danger') }}">{{ $refugee['status'] }}</span>
                        </div>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:12px;">
                            <a class="table-meta" href="{{ route('refugees.show', $refugee['id']) }}">Lihat detail</a>
                            @if ($canManageRefugees)
                                <a class="table-meta" href="{{ route('refugees.edit', $refugee['id']) }}">Ubah data</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="dashboard" class="chip-icon" />Alur Form</span>
                    <h3>Status wizard pengungsi</h3>
                    <p class="section-intro">Form create dan edit sekarang memakai partial Blade bertahap, validasi lebih ketat, dan siap disambungkan ke backend Laravel atau Firebase.</p>
                </div>
            </div>

            <div class="step-grid" style="margin-top:0;">
                <div class="step-card active"><span class="step-index">1</span><strong>Identitas</strong><p>ID internal, nama, kebangsaan, dan UNHCR divalidasi lebih ketat.</p></div>
                <div class="step-card"><span class="step-index">2</span><strong>Administrasi</strong><p>Status, tanggal registrasi, dan catatan diberi batasan input yang jelas.</p></div>
                <div class="step-card"><span class="step-index">3</span><strong>Penempatan</strong><p>Lokasi aktif wajib dipilih agar daftar operasional tetap konsisten.</p></div>
                <div class="step-card"><span class="step-index">4</span><strong>Dokumen</strong><p>Endpoint placeholder store, update, dan delete sudah ditandai di dalam wizard.</p></div>
            </div>
        </div>
    </section>
@endsection
