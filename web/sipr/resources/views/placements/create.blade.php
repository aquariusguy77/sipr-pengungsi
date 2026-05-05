@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="location" class="chip-icon" />Tambah Penempatan</span>
            <h3>Catat lokasi aktif dan status mutasi dengan alur yang lebih rapi.</h3>
            <p>Form ini dipakai untuk merekam hunian awal, perpindahan sementara, atau perubahan penempatan lain yang perlu dilacak di sistem.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Tautan Cepat</strong>
                    <span class="mini-badge success">CRUD aktif</span>
                </div>
                <p><a href="{{ route('placements.index') }}" style="color:#fff;">Kembali ke daftar penempatan</a></p>
            </div>
        </div>
    </section>

    @include('placements._form')
@endsection
