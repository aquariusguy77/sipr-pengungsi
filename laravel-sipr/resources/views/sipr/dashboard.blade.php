@php
    $appConfig = [
        'systemName' => 'SIPR Rudenim Surabaya',
        'environment' => 'local-mvp',
        'firebase' => [
            'databaseUrl' => 'https://ralf-803d6-default-rtdb.asia-southeast1.firebasedatabase.app/',
            'projectId' => 'ralf-803d6',
            'apiKey' => 'firebase-web-api-key-placeholder',
            'authDomain' => 'ralf-803d6.firebaseapp.com',
            'storageBucket' => 'ralf-803d6.firebasestorage.app',
            'paths' => [
                'refugees' => '/refugees',
                'documents' => '/documents',
                'placements' => '/placements',
                'auditTrails' => '/audit_trails',
                'reports' => '/reports',
                'users' => '/users',
            ],
            'nodeMap' => [
                'refugees' => ['internal_id', 'name', 'nationality', 'unhcr_number', 'status', 'location', 'document_status', 'notes', 'registered_at', 'updated_at'],
                'documents' => ['refugee_id', 'document_type', 'file_name', 'file_path', 'firebase_document_key', 'verification_status', 'uploaded_at', 'uploaded_by', 'notes'],
                'placements' => ['refugee_id', 'location_name', 'entered_at', 'exited_at', 'placement_status', 'notes'],
                'auditTrails' => ['refugee_id', 'field_name', 'old_value', 'new_value', 'action_label', 'performed_by_name', 'reason', 'performed_at'],
                'reports' => ['name', 'note', 'filters', 'downloaded_at', 'downloaded_by'],
                'users' => ['name', 'role', 'email', 'status'],
            ],
        ],
    ];

    $stats = [
        ['label' => 'Total Data Aktif', 'value' => 247, 'note' => 'Naik 6 data dibanding minggu lalu dengan distribusi lintas hunian aktif.', 'icon' => 'users', 'tone' => 'blue'],
        ['label' => 'Dokumen Lengkap', 'value' => 186, 'note' => 'Berkas dengan identitas, administrasi, dan lampiran pendukung yang telah tervalidasi.', 'icon' => 'file', 'tone' => 'green'],
        ['label' => 'Perlu Verifikasi', 'value' => 31, 'note' => 'Data dengan dokumen baru, pembaruan penting, atau catatan review dari supervisor.', 'icon' => 'alert', 'tone' => 'orange'],
        ['label' => 'Aktivitas Terbaru', 'value' => 19, 'note' => 'Catatan perubahan dalam 24 jam terakhir dari admin, petugas pendataan, dan supervisor.', 'icon' => 'history', 'tone' => 'deep', 'featured' => true],
    ];

    $refugees = [
        ['id' => 'RDS-24001', 'name' => 'Amina Hassan', 'nationality' => 'Somalia', 'status' => 'Aktif', 'location' => 'Hunian A-03', 'documentStatus' => 'Lengkap', 'updatedAt' => '04 Mei 2026, 14:30'],
        ['id' => 'RDS-24008', 'name' => 'Mahmoud Kareem', 'nationality' => 'Irak', 'status' => 'Verifikasi', 'location' => 'Hunian B-02', 'documentStatus' => 'Perlu Verifikasi', 'updatedAt' => '04 Mei 2026, 13:10'],
        ['id' => 'RDS-24011', 'name' => 'Samira Nabil', 'nationality' => 'Afghanistan', 'status' => 'Aktif', 'location' => 'Hunian C-05', 'documentStatus' => 'Lengkap', 'updatedAt' => '04 Mei 2026, 11:48'],
        ['id' => 'RDS-24016', 'name' => 'Yousef Rahman', 'nationality' => 'Myanmar', 'status' => 'Mutasi', 'location' => 'Transit 1', 'documentStatus' => 'Belum Lengkap', 'updatedAt' => '04 Mei 2026, 10:05'],
        ['id' => 'RDS-24021', 'name' => 'Layla Aziz', 'nationality' => 'Sudan', 'status' => 'Aktif', 'location' => 'Hunian A-01', 'documentStatus' => 'Lengkap', 'updatedAt' => '03 Mei 2026, 16:42'],
        ['id' => 'RDS-24027', 'name' => 'Karim Saeed', 'nationality' => 'Yaman', 'status' => 'Verifikasi', 'location' => 'Hunian D-02', 'documentStatus' => 'Perlu Verifikasi', 'updatedAt' => '03 Mei 2026, 15:27'],
    ];

    $placements = [
        ['title' => 'Hunian A', 'detail' => '84 penghuni aktif • 3 mutasi minggu ini', 'note' => 'Fokus pemeriksaan pada perpanjangan dokumen keluarga campuran.'],
        ['title' => 'Hunian B', 'detail' => '57 penghuni aktif • 1 mutasi masuk', 'note' => 'Ada 4 data dengan unggahan identitas baru menunggu verifikasi.'],
        ['title' => 'Hunian C', 'detail' => '69 penghuni aktif • stabil', 'note' => 'Tidak ada perpindahan besar dalam 7 hari terakhir.'],
        ['title' => 'Transit & Observasi', 'detail' => '37 penghuni aktif • 2 evaluasi penempatan', 'note' => 'Perlu sinkronisasi catatan supervisor dan petugas pendataan.'],
    ];

    $documents = [
        ['name' => 'Identitas Utama', 'meta' => 'Paspor, UNHCR, atau surat pengenal lain', 'status' => 'Lengkap', 'storage' => 'folder/pengungsi/identitas'],
        ['name' => 'Administrasi Internal', 'meta' => 'Form registrasi, catatan pemeriksaan, approval', 'status' => 'Perlu Verifikasi', 'storage' => 'folder/pengungsi/administrasi'],
        ['name' => 'Riwayat Penempatan', 'meta' => 'Mutasi, tanggal masuk, lampiran pendukung', 'status' => 'Lengkap', 'storage' => 'folder/pengungsi/penempatan'],
        ['name' => 'Lampiran Tambahan', 'meta' => 'Dokumen keluarga, surat kesehatan, catatan khusus', 'status' => 'Belum Lengkap', 'storage' => 'folder/pengungsi/lampiran'],
    ];

    $activities = [
        ['title' => 'Perubahan lokasi aktif', 'detail' => 'Yousef Rahman dipindahkan ke Transit 1 untuk evaluasi lanjutan.', 'actor' => 'Supervisor', 'time' => '04 Mei 2026 • 14:42'],
        ['title' => 'Unggah dokumen baru', 'detail' => 'Mahmoud Kareem menambahkan scan kartu UNHCR untuk pemeriksaan ulang.', 'actor' => 'Petugas Pendataan', 'time' => '04 Mei 2026 • 13:08'],
        ['title' => 'Verifikasi selesai', 'detail' => 'Dokumen keluarga Amina Hassan dinyatakan lengkap oleh admin.', 'actor' => 'Admin', 'time' => '04 Mei 2026 • 09:26'],
    ];

    $history = [
        ['title' => 'Status berubah: Verifikasi ke Aktif', 'detail' => 'Layla Aziz disetujui setelah lampiran administrasi dinyatakan sah.', 'actor' => 'Supervisor', 'time' => '03 Mei 2026 • 16:45'],
        ['title' => 'Field lokasi aktif diperbarui', 'detail' => 'Mahmoud Kareem dipindahkan dari Hunian B-01 ke Hunian B-02.', 'actor' => 'Petugas Pendataan', 'time' => '03 Mei 2026 • 11:12'],
        ['title' => 'Catatan audit ditambahkan', 'detail' => 'Admin menambahkan alasan perubahan nomor registrasi internal.', 'actor' => 'Admin', 'time' => '02 Mei 2026 • 17:30'],
    ];

    $reports = [
        ['name' => 'Rekap Data Aktif', 'note' => 'Total pengungsi aktif per lokasi, kebangsaan, dan status terbaru.'],
        ['name' => 'Laporan Dokumen', 'note' => 'Kelengkapan dokumen, daftar verifikasi, dan histori unggah.'],
        ['name' => 'Audit Trail', 'note' => 'Ringkasan perubahan data beserta pelaksana dan waktu pembaruan.'],
        ['name' => 'Prioritas Verifikasi', 'note' => 'Data yang perlu tindak lanjut supervisor dan admin.'],
    ];

    $nationalities = collect($refugees)->pluck('nationality')->unique()->values();
    $statuses = collect($refugees)->pluck('status')->unique()->values();
    $locations = collect($refugees)->pluck('location')->unique()->values();

    $badgeClass = function (string $value): string {
        return match ($value) {
            'Lengkap', 'Aktif' => 'success',
            'Perlu Verifikasi', 'Verifikasi' => 'warn',
            default => 'danger',
        };
    };

    $statToneClass = function (string $tone): string {
        return match ($tone) {
            'green' => 'tone-green',
            'orange' => 'tone-orange',
            'deep' => 'tone-deep',
            default => 'tone-blue',
        };
    };

    $iconMap = [
        'dashboard' => 'M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z',
        'users' => 'M16 11a4 4 0 1 0-4-4a4 4 0 0 0 4 4Zm-8 0a3 3 0 1 0-3-3a3 3 0 0 0 3 3Zm8 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4ZM8 13c-.29 0-.62.02-.97.05C5.5 13.27 3 14.03 3 15.5V19h4v-2c0-1.18.62-2.22 1.72-3.05A7.3 7.3 0 0 0 8 13Z',
        'location' => 'M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9A2.5 2.5 0 0 1 12 11.5Z',
        'folder' => 'M10 4 12 6h8a2 2 0 0 1 2 2v8.5A3.5 3.5 0 0 1 18.5 20h-13A3.5 3.5 0 0 1 2 16.5v-9A3.5 3.5 0 0 1 5.5 4H10Zm10 6H4v6.5A1.5 1.5 0 0 0 5.5 18h13a1.5 1.5 0 0 0 1.5-1.5V10Z',
        'history' => 'M13 3a9 9 0 1 0 8.95 10h-2.02A7 7 0 1 1 13 5v3l5-4l-5-4v3Zm-1 5v5.59l4.3 2.55l1-1.73L14 12.41V8h-2Z',
        'report' => 'M5 3h11l5 5v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm8 1.5V9h4.5L13 4.5ZM7 13h10v-2H7v2Zm0 4h10v-2H7v2Z',
        'settings' => 'm19.14 12.94.04-.94-.04-.94 2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.34 7.34 0 0 0-1.63-.94l-.36-2.54A.5.5 0 0 0 13.9 2h-3.8a.5.5 0 0 0-.49.42l-.36 2.54c-.58.23-1.13.54-1.63.94l-2.39-.96a.5.5 0 0 0-.6.22L2.71 8.48a.5.5 0 0 0 .12.64l2.03 1.58L4.82 12l.04.94-2.03 1.58a.5.5 0 0 0-.12.64l1.92 3.32a.5.5 0 0 0 .6.22l2.39-.96c.5.4 1.05.71 1.63.94l.36 2.54a.5.5 0 0 0 .49.42h3.8a.5.5 0 0 0 .49-.42l.36-2.54c.58-.23 1.13-.54 1.63-.94l2.39.96a.5.5 0 0 0 .6-.22l1.92-3.32a.5.5 0 0 0-.12-.64l-2.03-1.58ZM12 15.5A3.5 3.5 0 1 1 15.5 12A3.5 3.5 0 0 1 12 15.5Z',
        'search' => 'm21 20.3-4.35-4.35a7.5 7.5 0 1 0-1.4 1.4L19.6 21 21 20.3ZM5 10.5a5.5 5.5 0 1 1 5.5 5.5A5.5 5.5 0 0 1 5 10.5Z',
        'shield' => 'M12 2 4 5v6c0 5.55 3.84 10.74 8 12c4.16-1.26 8-6.45 8-12V5l-8-3Zm-1 14-3-3 1.41-1.41L11 13.17l4.59-4.58L17 10l-6 6Z',
        'sync' => 'M12 6V3L8 7l4 4V8c2.76 0 5 2.24 5 5c0 .74-.16 1.45-.46 2.08l1.46 1.46A6.93 6.93 0 0 0 19 13c0-3.87-3.13-7-7-7Zm-5.54.92L5 8.38A6.93 6.93 0 0 0 5 11c0 3.87 3.13 7 7 7v3l4-4l-4-4v3c-2.76 0-5-2.24-5-5c0-.74.16-1.45.46-2.08Z',
        'file' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Zm1 7V3.5L19.5 9H15Zm-7 4h8v-2H8v2Zm0 4h8v-2H8v2Z',
        'alert' => 'M1 21h22L12 2 1 21Zm12-3h-2v-2h2v2Zm0-4h-2v-4h2v4Z',
        'download' => 'M5 20h14v-2H5v2Zm7-18v10.17l3.59-3.58L17 10l-5 5l-5-5l1.41-1.41L11 12.17V2h1Z',
        'menu' => 'M3 6h18v2H3V6Zm0 5h18v2H3v-2Zm0 5h18v2H3v-2Z',
        'logout' => 'M10.09 15.59 11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59ZM19 3H9a2 2 0 0 0-2 2v4h2V5h10v14H9v-4H7v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Z',
    ];

    $menuItems = [
        ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ['id' => 'data-pengungsi', 'label' => 'Data Pengungsi', 'icon' => 'users'],
        ['id' => 'penempatan', 'label' => 'Penempatan', 'icon' => 'location'],
        ['id' => 'dokumen', 'label' => 'Dokumen', 'icon' => 'folder'],
        ['id' => 'riwayat-laporan', 'label' => 'Riwayat & Laporan', 'icon' => 'history'],
        ['id' => 'pengaturan', 'label' => 'Pengaturan', 'icon' => 'settings'],
    ];

    $pageTitle = 'Dashboard Operasional - ' . $appConfig['systemName'];
@endphp

@extends('layouts.sipr')

@section('content')
    <div class="page-body">
        <section class="hero-panel section-anchor" id="dashboard">
            <div class="hero-copy">
                <span class="eyebrow">
                    <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['shield'] }}"/></svg>
                    Sistem Informasi Pendataan Pengungsi
                </span>
                <h3>Pusat kendali data pengungsi yang rapi, cepat dibaca, dan siap dihubungkan ke backend Laravel.</h3>
                <p>
                    Tampilan utama ini disusun untuk kebutuhan operasional internal Rudenim Surabaya dengan fokus pada pendataan, verifikasi dokumen,
                    riwayat pembaruan, penempatan, dan laporan. Area integrasi Firebase Realtime Database dan Firebase Storage sudah ditandai untuk tahap pengembangan backend.
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
                    <p>Konfigurasi Firebase Realtime Database, struktur path data, dan referensi penyimpanan file ditempatkan di controller atau service layer Laravel.</p>
                </div>
            </div>
        </section>

        <section class="dashboard-grid" aria-label="Ringkasan statistik">
            @foreach ($stats as $stat)
                <article class="stat-card{{ !empty($stat['featured']) ? ' featured' : '' }}">
                    <div class="stat-head">
                        <div><h4>{{ $stat['label'] }}</h4></div>
                        <div class="stat-icon-wrap {{ $statToneClass($stat['tone']) }}">
                            <svg class="stat-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap[$stat['icon']] }}"/></svg>
                        </div>
                    </div>
                    <strong>{{ $stat['value'] }}</strong>
                    <p class="metric-note">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="content-grid">
            <div class="panel section-anchor" id="data-pengungsi">
                <div class="section-head">
                    <div>
                        <span class="section-tag">
                            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['users'] }}"/></svg>
                            Data Pengungsi
                        </span>
                        <h3>Daftar dan pencarian operasional</h3>
                        <p class="section-intro">Tabel contoh berikut menggambarkan pencarian berdasarkan nama, ID internal, kebangsaan, status, lokasi, dan kelengkapan dokumen.</p>
                    </div>
                    <span class="badge">Blade + dummy data</span>
                </div>

                <div class="table-toolbar">
                    <p>Filter operasional untuk kebutuhan input, validasi, dan tindak lanjut.</p>
                    <span class="mini-badge success">Siap dipindah ke controller</span>
                </div>

                <div class="filters">
                    <input class="control" type="text" placeholder="Cari nama / ID internal">
                    <select class="control">
                        <option>Semua kebangsaan</option>
                        @foreach ($nationalities as $nationality)
                            <option>{{ $nationality }}</option>
                        @endforeach
                    </select>
                    <select class="control">
                        <option>Semua status</option>
                        @foreach ($statuses as $status)
                            <option>{{ $status }}</option>
                        @endforeach
                    </select>
                    <select class="control">
                        <option>Semua lokasi</option>
                        @foreach ($locations as $location)
                            <option>{{ $location }}</option>
                        @endforeach
                    </select>
                    <select class="control">
                        <option>Semua kelengkapan</option>
                        <option>Lengkap</option>
                        <option>Perlu Verifikasi</option>
                        <option>Belum Lengkap</option>
                    </select>
                </div>

                <div style="overflow:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Pengungsi</th>
                                <th>Kebangsaan</th>
                                <th>Status</th>
                                <th>Lokasi Aktif</th>
                                <th>Dokumen</th>
                                <th>Pembaruan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refugees as $refugee)
                                <tr>
                                    <td>
                                        <strong>{{ $refugee['name'] }}</strong><br>
                                        <span class="table-meta">{{ $refugee['id'] }}</span>
                                    </td>
                                    <td>{{ $refugee['nationality'] }}</td>
                                    <td><span class="badge {{ $badgeClass($refugee['status']) }}">{{ $refugee['status'] }}</span></td>
                                    <td>{{ $refugee['location'] }}</td>
                                    <td><span class="badge {{ $badgeClass($refugee['documentStatus']) }}">{{ $refugee['documentStatus'] }}</span></td>
                                    <td>{{ $refugee['updatedAt'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <div class="section-head">
                    <div>
                        <span class="section-tag">
                            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['history'] }}"/></svg>
                            Aktivitas Terbaru
                        </span>
                        <h3>Perubahan data terakhir</h3>
                        <p class="section-intro">Audit trail singkat untuk perubahan yang perlu diamati supervisor.</p>
                    </div>
                </div>

                <div class="timeline">
                    @foreach ($activities as $item)
                        <article class="timeline-item">
                            <div class="timeline-mark">
                                <svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['history'] }}"/></svg>
                            </div>
                            <div>
                                <strong>{{ $item['title'] }}</strong>
                                <p>{{ $item['detail'] }}</p>
                                <div class="timeline-meta">{{ $item['actor'] }} • {{ $item['time'] }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="double-grid">
            <div class="panel section-anchor" id="penempatan">
                <div class="section-head">
                    <div>
                        <span class="section-tag">
                            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['location'] }}"/></svg>
                            Penempatan
                        </span>
                        <h3>Ringkasan hunian dan mutasi</h3>
                        <p class="section-intro">Area ini menampilkan contoh sebaran lokasi aktif, tanggal masuk, dan riwayat mutasi penempatan.</p>
                    </div>
                    <span class="badge">Operasional harian</span>
                </div>

                <div class="list-group">
                    @foreach ($placements as $placement)
                        <article class="list-item">
                            <div class="split-header">
                                <div>
                                    <h3>{{ $placement['title'] }}</h3>
                                    <p>{{ $placement['detail'] }}</p>
                                </div>
                                <span class="mini-badge success">Aktif</span>
                            </div>
                            <p style="margin-top:12px;">{{ $placement['note'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="section-head">
                    <div>
                        <span class="section-tag">
                            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['dashboard'] }}"/></svg>
                            Form Data Pengungsi
                        </span>
                        <h3>Alur input bertahap</h3>
                        <p class="section-intro">Disiapkan sebagai fondasi form wizard Laravel untuk identitas, administrasi, penempatan, dan unggah dokumen.</p>
                    </div>
                    <span class="badge">Simpan draft tersedia</span>
                </div>

                <div class="step-grid">
                    <div class="step-card active"><span class="step-index">1</span><strong>Identitas</strong><p>Nama, ID internal, kebangsaan, nomor UNHCR, status, dan catatan awal.</p></div>
                    <div class="step-card"><span class="step-index">2</span><strong>Administrasi</strong><p>Tanggal registrasi, petugas input, status verifikasi, dan kebutuhan validasi dasar.</p></div>
                    <div class="step-card"><span class="step-index">3</span><strong>Penempatan</strong><p>Lokasi aktif, riwayat mutasi, tanggal masuk, dan status perpindahan.</p></div>
                    <div class="step-card"><span class="step-index">4</span><strong>Unggah Dokumen</strong><p>Placeholder file pendukung dengan referensi Firebase Storage dan status verifikasi.</p></div>
                </div>

                <div class="subtle-box">
                    <h4>Catatan implementasi backend Laravel</h4>
                    <ul>
                        <li>View ini bisa dipasang sebagai `resources/views/sipr/dashboard.blade.php`.</li>
                        <li>Data dummy dapat dipindahkan ke controller lalu dikirim dengan `return view(..., compact(...))`.</li>
                        <li>Integrasi Firebase Realtime Database dan Firebase Storage sebaiknya diletakkan di service class terpisah.</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="panel section-anchor" id="dokumen">
            <div class="section-head">
                <div>
                    <span class="section-tag">
                        <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['folder'] }}"/></svg>
                        Dokumen
                    </span>
                    <h3>Detail berkas, status verifikasi, dan referensi penyimpanan</h3>
                    <p class="section-intro">Komponen berikut mewakili daftar dokumen, status review, serta placeholder alur unggah dan metadata penyimpanan.</p>
                </div>
                <span class="badge">Firebase Storage placeholder</span>
            </div>

            <div class="split">
                <div>
                    <div class="doc-grid">
                        @foreach ($documents as $document)
                            <article class="doc-card">
                                <strong>{{ $document['name'] }}</strong>
                                <p>{{ $document['meta'] }}</p>
                                <p style="margin-top:12px;"><span class="badge {{ $badgeClass($document['status']) }}">{{ $document['status'] }}</span></p>
                                <p style="margin-top:12px;">Penyimpanan: <code>{{ $document['storage'] }}</code></p>
                            </article>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="subtle-box" style="margin-top:0;">
                        <h4>Placeholder integrasi dokumen</h4>
                        <ul>
                            <li>Endpoint unggah diarahkan ke controller Laravel sebagai lapisan validasi dan logging.</li>
                            <li>Path file Storage, URL unduhan, dan key dokumen RTDB disimpan sebagai metadata dokumen.</li>
                            <li>Riwayat verifikasi dicatat ke audit trail agar mudah ditelusuri supervisor.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="double-grid section-anchor" id="riwayat-laporan">
            <div class="panel">
                <div class="section-head">
                    <div>
                        <span class="section-tag">
                            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['history'] }}"/></svg>
                            Riwayat Perubahan
                        </span>
                        <h3>Audit trail perubahan data</h3>
                        <p class="section-intro">Menampilkan field yang berubah, nilai lama dan baru, pelaksana, serta alasan perubahan.</p>
                    </div>
                    <span class="badge">Supervisor review</span>
                </div>

                <div class="timeline">
                    @foreach ($history as $item)
                        <article class="timeline-item">
                            <div class="timeline-mark">
                                <svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['history'] }}"/></svg>
                            </div>
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
                        <span class="section-tag">
                            <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['report'] }}"/></svg>
                            Laporan
                        </span>
                        <h3>Rekap, ekspor, dan log unduhan</h3>
                        <p class="section-intro">Area laporan disiapkan untuk rekap data aktif, dokumen, audit trail, dan daftar prioritas verifikasi.</p>
                    </div>
                    <span class="badge">PDF / Excel placeholder</span>
                </div>

                <div class="report-grid">
                    @foreach ($reports as $report)
                        <article class="report-card">
                            <strong>{{ $report['name'] }}</strong>
                            <p>{{ $report['note'] }}</p>
                            <p style="margin-top:12px;"><span class="mini-badge warn">Ekspor placeholder</span></p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="panel section-anchor" id="pengaturan">
            <div class="setting-head">
                <div class="setting-copy">
                    <span class="section-tag">
                        <svg class="chip-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['settings'] }}"/></svg>
                        Pengaturan
                    </span>
                    <h3>Konfigurasi sistem dan hak akses</h3>
                    <p>Modul akun dan hak akses ditempatkan di dalam pengaturan, sesuai revisi spesifikasi dan tanpa menu Pengguna di navigasi utama.</p>
                </div>
                <span class="badge">Siap dipecah ke Blade component</span>
            </div>

            <div class="triple-grid settings-grid">
                <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(31,111,178,.10);color:var(--blue)"><svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['dashboard'] }}"/></svg></div><div><strong>Master Data</strong><p>Pengelolaan referensi kebangsaan, kategori dokumen, lokasi, dan status operasional.</p></div></article>
                <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(31,157,122,.12);color:var(--green)"><svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['users'] }}"/></svg></div><div><strong>Hak Akses &amp; Akun</strong><p>Admin, petugas pendataan, dan supervisor dikelola di area ini beserta pembatasan peran.</p></div></article>
                <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(238,141,45,.12);color:var(--orange)"><svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['sync'] }}"/></svg></div><div><strong>Backup &amp; Restore</strong><p>Jadwal cadangan lokal, restore data, dan catatan pemulihan untuk lingkungan MVP intranet.</p></div></article>
                <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(24,79,130,.12);color:var(--blue-deep)"><svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['shield'] }}"/></svg></div><div><strong>Keamanan</strong><p>Pengaturan sesi, catatan login, kontrol perubahan sensitif, dan persetujuan supervisor.</p></div></article>
                <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(31,157,122,.10);color:var(--green)"><svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['alert'] }}"/></svg></div><div><strong>Notifikasi</strong><p>Peringatan dokumen belum lengkap, perubahan kritis, dan jadwal rekap pelaporan.</p></div></article>
                <article class="setting-card"><div class="section-icon-wrap" style="width:48px;height:48px;background:rgba(23,50,77,.08);color:var(--text)"><svg class="section-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="{{ $iconMap['file'] }}"/></svg></div><div><strong>Informasi Sistem</strong><p>Versi aplikasi, status konfigurasi backend, dan dokumentasi teknis integrasi yang masih placeholder.</p></div></article>
            </div>
        </section>

        <footer class="footer">
            <strong>{{ $appConfig['systemName'] }}</strong> • Fondasi antarmuka Laravel untuk Sistem Informasi Pendataan Pengungsi • 2026 • Rumah Detensi Imigrasi Surabaya
        </footer>
    </div>
@endsection
