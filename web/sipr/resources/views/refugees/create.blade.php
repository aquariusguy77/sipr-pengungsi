@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="users" class="chip-icon" />Tambah Data Pengungsi</span>
            <h3>Registrasi data baru dengan alur yang lebih rapi dan tervalidasi.</h3>
            <p>Gunakan wizard ini untuk memasukkan identitas, administrasi, penempatan awal, dan penanda integrasi dokumen sebelum data masuk ke daftar operasional.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Tautan Cepat</strong>
                    <span class="mini-badge success">Blade aktif</span>
                </div>
                <p><a href="{{ route('refugees.index') }}" style="color:#fff;">Kembali ke daftar pengungsi</a></p>
            </div>
        </div>
    </section>

    @include('refugees._form')
@endsection
