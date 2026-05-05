@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="location" class="chip-icon" />Ubah Penempatan</span>
            <h3>{{ $placement->location_name ?? 'Data Penempatan' }}</h3>
            <p>Perbarui lokasi aktif, tanggal masuk, tanggal keluar, dan status penempatan tanpa keluar dari alur yang sama.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Tautan Cepat</strong>
                    <span class="mini-badge warn">Mode edit</span>
                </div>
                <p><a href="{{ route('placements.show', $placement) }}" style="color:#fff;">Lihat detail penempatan</a></p>
                <p style="margin-top:12px;"><a href="{{ route('placements.index') }}" style="color:#fff;">Kembali ke daftar penempatan</a></p>
            </div>
        </div>
    </section>

    @include('placements._form')
@endsection
