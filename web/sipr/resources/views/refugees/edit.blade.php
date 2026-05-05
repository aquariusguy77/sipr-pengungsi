@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="users" class="chip-icon" />Ubah Data Pengungsi</span>
            <h3>{{ $refugee->name ?? 'Data Pengungsi' }}</h3>
            <p>Perbarui identitas, status, lokasi aktif, dan catatan registrasi tanpa keluar dari alur wizard yang sama.</p>
            <div class="hero-meta">
                <div><strong>{{ $refugee->internal_id ?? '-' }}</strong><span>ID internal</span></div>
                <div><strong>{{ $refugee->status ?? '-' }}</strong><span>status data</span></div>
                <div><strong>{{ $refugee->location ?? '-' }}</strong><span>lokasi aktif</span></div>
            </div>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Tautan Cepat</strong>
                    <span class="mini-badge warn">Mode edit</span>
                </div>
                <p><a href="{{ route('refugees.show', $refugee) }}" style="color:#fff;">Lihat detail pengungsi</a></p>
                <p style="margin-top:12px;"><a href="{{ route('refugees.index') }}" style="color:#fff;">Kembali ke daftar pengungsi</a></p>
            </div>
        </div>
    </section>

    @include('refugees._form')
@endsection
