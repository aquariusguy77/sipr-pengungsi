@extends('layouts.app')

@section('content')
    <section class="panel section-anchor" id="pengaturan">
        <div class="setting-head">
            <div class="setting-copy">
                <span class="section-tag"><x-icon name="settings" class="chip-icon" />Pengaturan</span>
                <h3>Konfigurasi sistem dan hak akses</h3>
                <p>Hak akses dan akun tetap berada di dalam pengaturan, sesuai spesifikasi SIPR dan tanpa menu Pengguna di navigasi utama.</p>
            </div>
            <span class="badge">Konfigurasi Laravel-ready</span>
        </div>

        <div class="triple-grid settings-grid">
            <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(31,111,178,.10);color:var(--blue)"><x-icon name="dashboard" class="section-icon" /></div><div><strong>Master Data</strong><p>Pengelolaan referensi kebangsaan, kategori dokumen, lokasi, dan status operasional.</p></div></article>
            <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(31,157,122,.12);color:var(--green)"><x-icon name="users" class="section-icon" /></div><div><strong>Hak Akses &amp; Akun</strong><p>Peran aktif saat ini: {{ $currentRole['label'] }} melalui sumber {{ strtoupper($currentRole['source']) }}.</p></div></article>
            <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(238,141,45,.12);color:var(--orange)"><x-icon name="sync" class="section-icon" /></div><div><strong>Backup &amp; Restore</strong><p>Jadwal cadangan lokal, restore data, dan catatan pemulihan untuk lingkungan MVP intranet.</p></div></article>
            <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(24,79,130,.12);color:var(--blue-deep)"><x-icon name="shield" class="section-icon" /></div><div><strong>Keamanan</strong><p>Pengaturan sesi, catatan login, kontrol perubahan sensitif, dan persetujuan supervisor.</p></div></article>
            <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(31,157,122,.10);color:var(--green)"><x-icon name="alert" class="section-icon" /></div><div><strong>Notifikasi</strong><p>Peringatan dokumen belum lengkap, perubahan kritis, dan jadwal rekap pelaporan.</p></div></article>
            <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(23,50,77,.08);color:var(--text)"><x-icon name="file" class="section-icon" /></div><div><strong>Informasi Sistem</strong><p>Firebase RTDB: <code>{{ $integrationConfig['firebase']['database_url'] }}</code> • Bucket: {{ $integrationConfig['firebase']['storage_bucket'] }}</p></div></article>
        </div>
    </section>

    <section class="double-grid">
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="shield" class="chip-icon" />Role Dasar</span>
                    <h3>Stub hak akses per peran</h3>
                    <p class="section-intro">Disiapkan untuk memudahkan penyusunan policy dan alur otorisasi Laravel.</p>
                </div>
            </div>
            <div class="list-group">
                @foreach ($roles as $key => $role)
                    <article class="list-item">
                        <strong>{{ $role['label'] }}</strong>
                        <p>{{ implode(', ', $role['abilities']) }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="history" class="chip-icon" />Flow Kerja</span>
                    <h3>Alur peran operasional</h3>
                    <p class="section-intro">Alur ini menjaga pembagian kerja tetap sesuai peran Admin, Petugas Pendataan, dan Supervisor.</p>
                </div>
            </div>
            <div class="timeline">
                @foreach ($roleFlow as $item)
                    <article class="timeline-item">
                        <div class="timeline-mark"><x-icon name="users" class="section-icon" /></div>
                        <div>
                            <strong>{{ $item['step'] }}</strong>
                            <p>{{ $item['description'] }}</p>
                            <div class="timeline-meta">{{ $item['actor'] }}</div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="panel" style="margin-top:24px;">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="sync" class="chip-icon" />Node Firebase</span>
                <h3>Pemetaan struktur data RTDB</h3>
                <p class="section-intro">Path ini dipakai sebagai acuan sinkronisasi Laravel dan struktur baca langsung dari frontend.</p>
            </div>
        </div>
        <div class="list-group">
            @foreach ($integrationConfig['firebase']['node_map'] as $node => $definition)
                <article class="list-item">
                    <strong>{{ $node }}</strong>
                    <p>Path: <code>{{ $definition['path'] }}</code></p>
                    <p style="margin-top:8px;">Field: {{ implode(', ', $definition['fields']) }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
