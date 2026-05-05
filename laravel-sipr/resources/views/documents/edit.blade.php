@extends('layouts.app')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="folder" class="chip-icon" />Ubah Dokumen</span>
            <h3>{{ $document->document_type ?? 'Data Dokumen' }}</h3>
            <p>Perbarui metadata file, referensi penyimpanan, dan status verifikasi pada alur yang sama.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Tautan Cepat</strong>
                    <span class="mini-badge warn">Mode edit</span>
                </div>
                <p><a href="{{ route('documents.show', $document) }}" style="color:#fff;">Lihat detail dokumen</a></p>
                <p style="margin-top:12px;"><a href="{{ route('documents.index') }}" style="color:#fff;">Kembali ke daftar dokumen</a></p>
            </div>
        </div>
    </section>

    @include('documents._form')
@endsection
