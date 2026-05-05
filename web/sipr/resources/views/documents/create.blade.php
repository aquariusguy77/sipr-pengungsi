@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="folder" class="chip-icon" />Tambah Dokumen</span>
            <h3>Catat metadata berkas dan status verifikasi dokumen pengungsi.</h3>
            <p>Form ini disiapkan untuk menyimpan identitas file, jalur penyimpanan, dan status review agar mudah disambungkan ke Firebase Storage atau backend Laravel.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Tautan Cepat</strong>
                    <span class="mini-badge success">CRUD aktif</span>
                </div>
                <p><a href="{{ route('documents.index') }}" style="color:#fff;">Kembali ke daftar dokumen</a></p>
            </div>
        </div>
    </section>

    @include('documents._form')
@endsection
