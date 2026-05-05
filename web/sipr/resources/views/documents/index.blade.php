@extends('layouts.app')

@php
    $badgeClass = fn (string $value) => match ($value) {
        'Lengkap', 'Aktif' => 'success',
        'Perlu Verifikasi', 'Verifikasi' => 'warn',
        default => 'danger',
    };
@endphp

@section('content')
    <section class="panel section-anchor" id="dokumen">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="folder" class="chip-icon" />Dokumen</span>
                <h3>Status verifikasi dan penyimpanan</h3>
                <p class="section-intro">Tampilan modul dokumen difokuskan untuk membaca kelengkapan berkas dan kesiapan integrasi penyimpanan.</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <span class="badge">{{ $documents->count() }} kategori dokumen</span>
                @if ($canManageDocuments)
                    <a class="badge success" href="{{ route('documents.create') }}">Tambah Dokumen</a>
                @endif
            </div>
        </div>

        @if (session('status'))
            <div class="subtle-box" style="margin-top:0;margin-bottom:16px;border-style:solid;border-color:rgba(31,157,122,.25);">
                {{ session('status') }}
            </div>
        @endif

        <form class="filters" method="GET" action="{{ route('documents.index') }}">
            <input class="control" type="text" name="keyword" value="{{ $activeFilters['keyword'] }}" placeholder="Cari dokumen / file / pengungsi">
            <select class="control" name="type">
                <option value="">Semua jenis</option>
                @foreach ($documentTypes as $item)
                    <option value="{{ $item }}" @selected($activeFilters['type'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select class="control" name="status">
                <option value="">Semua status</option>
                @foreach ($statusOptions as $item)
                    <option value="{{ $item }}" @selected($activeFilters['status'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select class="control" name="per_page">
                <option value="5" @selected((string) $activeFilters['per_page'] === '5')>5 per halaman</option>
                <option value="10" @selected((string) $activeFilters['per_page'] === '10')>10 per halaman</option>
                <option value="15" @selected((string) $activeFilters['per_page'] === '15')>15 per halaman</option>
                <option value="20" @selected((string) $activeFilters['per_page'] === '20')>20 per halaman</option>
            </select>
            <button class="control" type="submit" style="cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--green));color:#fff;border:none;">Terapkan</button>
            <a class="control" href="{{ route('documents.index') }}" style="display:grid;place-items:center;">Reset</a>
        </form>

        <div class="split">
            <div>
                <div class="doc-grid" style="margin-top:0;">
                    @foreach ($documents as $document)
                        <article class="doc-card">
                            <strong>{{ $document['name'] }}</strong>
                            <p>{{ $document['meta'] }}</p>
                            <p style="margin-top:12px;"><span class="badge {{ $badgeClass($document['status']) }}">{{ $document['status'] }}</span></p>
                            <p style="margin-top:12px;">Penyimpanan: <code>{{ $document['storage'] }}</code></p>
                            @if (!empty($document['id']))
                                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:10px;align-items:center;">
                                    <a class="table-meta" href="{{ route('documents.show', $document['id']) }}">Lihat detail</a>
                                    @if ($canManageDocuments)
                                        <a class="table-meta" href="{{ route('documents.edit', $document['id']) }}">Ubah dokumen</a>
                                    @endif
                                    @if ($canDeleteDocuments)
                                        <form method="POST" action="{{ route('documents.destroy', $document['id']) }}" onsubmit="return confirm('Hapus dokumen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-meta" style="background:none;border:none;padding:0;cursor:pointer;color:var(--danger);">Hapus dokumen</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="subtle-box" style="margin-top:0;">
                    <h4>Placeholder integrasi</h4>
                    <ul>
                        <li>Realtime Database: <code>{{ $integrationConfig['firebase']['database_url'] }}</code></li>
                        <li>Path dokumen: <code>{{ $integrationConfig['firebase']['paths']['documents'] }}</code></li>
                        <li>Bucket file placeholder: <code>{{ $integrationConfig['firebase']['storage_bucket'] }}</code></li>
                        <li>Form create dan edit kini memvalidasi nama file, tanggal unggah, dan status verifikasi dengan aturan lebih ketat.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-toolbar" style="margin-top:16px;">
            <p>Menampilkan {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} dari {{ $documents->total() }} data.</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @if ($documents->onFirstPage())
                    <span class="badge">Sebelumnya</span>
                @else
                    <a class="badge" href="{{ $documents->previousPageUrl() }}">Sebelumnya</a>
                @endif
                <span class="badge">Halaman {{ $documents->currentPage() }} / {{ $documents->lastPage() }}</span>
                @if ($documents->hasMorePages())
                    <a class="badge" href="{{ $documents->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="badge">Berikutnya</span>
                @endif
            </div>
        </div>
    </section>
@endsection
