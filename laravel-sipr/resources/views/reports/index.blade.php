@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="report" class="chip-icon" />Laporan</span>
            <h3>Rekap operasional dan log ekspor yang siap diteruskan ke backend pelaporan.</h3>
            <p>Halaman ini difokuskan untuk daftar jenis laporan, log unduhan, dan arah integrasi ekspor agar proses pelaporan harian lebih mudah dilanjutkan developer berikutnya.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Status</strong>
                    <span class="mini-badge success">{{ count($reports) }} jenis laporan</span>
                </div>
                <p>{{ count($reportLogs) }} catatan unduhan sudah tersedia sebagai fondasi audit pelaporan.</p>
            </div>
        </div>
    </section>

    <section class="panel section-anchor" id="laporan">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="report" class="chip-icon" />Laporan</span>
                <h3>Rekap dan placeholder ekspor</h3>
                <p class="section-intro">Modul ini menampilkan jenis laporan utama dan log unduhan untuk audit operasional.</p>
            </div>
        </div>

        <div class="report-grid" style="margin-top:0;">
            @foreach ($reports as $report)
                <article class="report-card">
                    <strong>{{ $report['name'] }}</strong>
                    <p>{{ $report['note'] }}</p>
                    <p style="margin-top:12px;"><span class="mini-badge warn"><x-icon name="download" class="chip-icon" />Ekspor placeholder</span></p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="panel">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="history" class="chip-icon" />Log Unduhan</span>
                <h3>Riwayat ekspor laporan</h3>
                <p class="section-intro">Tabel ini siap disambungkan ke tabel `report_logs` untuk pelacakan unduhan.</p>
            </div>
        </div>

        <div style="overflow:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Jenis Laporan</th>
                        <th>Filter</th>
                        <th>Pelaksana</th>
                        <th>Waktu Unduh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportLogs as $log)
                        <tr>
                            <td>{{ $log['type'] }}</td>
                            <td>{{ $log['filters'] }}</td>
                            <td>{{ $log['actor'] }}</td>
                            <td>{{ $log['downloaded_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="double-grid">
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="download" class="chip-icon" />Arah Ekspor</span>
                    <h3>Placeholder keluaran</h3>
                </div>
            </div>
            <div class="list-group">
                <article class="list-item"><strong>PDF</strong><p>Siap untuk rekap formal supervisor dan pimpinan.</p></article>
                <article class="list-item"><strong>Excel</strong><p>Siap untuk olah data lanjutan dan kontrol operasional.</p></article>
            </div>
        </div>
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="history" class="chip-icon" />Catatan Integrasi</span>
                    <h3>Pengembangan berikutnya</h3>
                </div>
            </div>
            <div class="subtle-box" style="margin-top:0;">
                <ul>
                    <li>Jenis laporan saat ini masih berbasis placeholder dan sample log.</li>
                    <li>Langkah wajar berikutnya adalah generator PDF/Excel dan penyimpanan log unduhan otomatis.</li>
                    <li>Modul ini sudah siap menerima filter periode, lokasi, dan status dokumen saat query backend ditambahkan.</li>
                </ul>
            </div>
        </div>
    </section>
@endsection
