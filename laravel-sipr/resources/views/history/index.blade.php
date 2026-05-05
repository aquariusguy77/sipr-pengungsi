@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="history" class="chip-icon" />Riwayat Perubahan</span>
            <h3>Pusat audit trail dan jejak ekspor yang lebih mudah dipantau supervisor.</h3>
            <p>Halaman ini menggabungkan perubahan data, aktivitas terkini, kartu laporan, dan log unduhan agar tim operasional bisa membaca konteks perubahan tanpa berpindah modul.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Ringkasan</strong>
                    <span class="mini-badge warn">{{ count($reportLogs) }} log unduhan</span>
                </div>
                <p>{{ $history->count() }} riwayat perubahan dan {{ $activities->count() }} aktivitas terbaru siap ditinjau dari satu tempat.</p>
            </div>
        </div>
    </section>

    <section class="double-grid">
        <div class="panel section-anchor" id="riwayat">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="history" class="chip-icon" />Riwayat Perubahan</span>
                    <h3>Audit trail perubahan data</h3>
                    <p class="section-intro">Field berubah, alasan, pelaksana, dan waktu pembaruan dipusatkan di halaman ini.</p>
                </div>
            </div>

            <div class="timeline">
                @foreach ($history as $item)
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
                    <span class="section-tag"><x-icon name="sync" class="chip-icon" />Aktivitas Terkini</span>
                    <h3>Perubahan 24 jam terakhir</h3>
                    <p class="section-intro">Panel ini membantu supervisor melihat pembaruan paling baru tanpa berpindah halaman.</p>
                </div>
            </div>

            <div class="timeline">
                @foreach ($activities as $item)
                    <article class="timeline-item">
                        <div class="timeline-mark"><x-icon name="alert" class="section-icon" /></div>
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
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="report" class="chip-icon" />Laporan</span>
                    <h3>Rekap dan placeholder ekspor</h3>
                    <p class="section-intro">Jenis laporan utama tetap tersedia di halaman gabungan ini agar operator tidak perlu berpindah menu.</p>
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
        </div>

        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="history" class="chip-icon" />Log Unduhan</span>
                    <h3>Riwayat ekspor laporan</h3>
                    <p class="section-intro">Tabel ini siap disambungkan ke `report_logs` untuk pelacakan unduhan laporan.</p>
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
        </div>
    </section>

    <section class="triple-grid">
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="alert" class="chip-icon" />Perubahan Tertinggi</span>
                    <h3>Fokus review</h3>
                </div>
            </div>
            <div class="list-group">
                <article class="list-item"><strong>{{ $history->first()['title'] ?? 'Belum ada data' }}</strong><p>{{ $history->first()['detail'] ?? 'Belum ada audit trail yang perlu ditinjau.' }}</p></article>
            </div>
        </div>
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="sync" class="chip-icon" />Aktivitas</span>
                    <h3>Update 24 jam</h3>
                </div>
            </div>
            <div class="list-group">
                <article class="list-item"><strong>{{ $activities->first()['title'] ?? 'Belum ada aktivitas' }}</strong><p>{{ $activities->first()['detail'] ?? 'Belum ada aktivitas baru.' }}</p></article>
            </div>
        </div>
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="report" class="chip-icon" />Laporan</span>
                    <h3>Jenis tersedia</h3>
                </div>
            </div>
            <div class="list-group">
                <article class="list-item"><strong>{{ $reports[0]['name'] ?? 'Belum ada laporan' }}</strong><p>{{ $reports[0]['note'] ?? 'Belum ada jenis laporan yang ditampilkan.' }}</p></article>
            </div>
        </div>
    </section>
@endsection
