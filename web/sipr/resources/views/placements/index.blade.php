@extends('layouts.app')

@section('content')
    <section class="panel section-anchor" id="penempatan">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="location" class="chip-icon" />Penempatan</span>
                <h3>Ringkasan hunian dan mutasi</h3>
                <p class="section-intro">Tiap blok mewakili unit penempatan aktif yang nantinya dapat diisi dari tabel `placements`.</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <span class="badge">{{ $placements->count() }} zona aktif</span>
                @if ($canManagePlacements)
                    <a class="badge success" href="{{ route('placements.create') }}">Tambah Penempatan</a>
                @endif
            </div>
        </div>

        @if (session('status'))
            <div class="subtle-box" style="margin-top:0;margin-bottom:16px;border-style:solid;border-color:rgba(31,157,122,.25);">
                {{ session('status') }}
            </div>
        @endif

        <form class="filters" method="GET" action="{{ route('placements.index') }}">
            <input class="control" type="text" name="keyword" value="{{ $activeFilters['keyword'] }}" placeholder="Cari nama area / lokasi">
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
            <a class="control" href="{{ route('placements.index') }}" style="display:grid;place-items:center;">Reset</a>
        </form>

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
                    @if (!empty($placement['id']))
                        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:10px;align-items:center;">
                            <a class="table-meta" href="{{ route('placements.show', $placement['id']) }}">Lihat detail</a>
                            @if ($canManagePlacements)
                                <a class="table-meta" href="{{ route('placements.edit', $placement['id']) }}">Ubah penempatan</a>
                            @endif
                            @if ($canDeletePlacements)
                                <form method="POST" action="{{ route('placements.destroy', $placement['id']) }}" onsubmit="return confirm('Hapus penempatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-meta" style="background:none;border:none;padding:0;cursor:pointer;color:var(--danger);">Hapus penempatan</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="table-toolbar" style="margin-top:16px;">
            <p>Menampilkan {{ $placements->firstItem() ?? 0 }}-{{ $placements->lastItem() ?? 0 }} dari {{ $placements->total() }} data.</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @if ($placements->onFirstPage())
                    <span class="badge">Sebelumnya</span>
                @else
                    <a class="badge" href="{{ $placements->previousPageUrl() }}">Sebelumnya</a>
                @endif
                <span class="badge">Halaman {{ $placements->currentPage() }} / {{ $placements->lastPage() }}</span>
                @if ($placements->hasMorePages())
                    <a class="badge" href="{{ $placements->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="badge">Berikutnya</span>
                @endif
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="history" class="chip-icon" />Arah Pengembangan</span>
                <h3>Skema data penempatan</h3>
                <p class="section-intro">Migration sudah menyiapkan relasi `refugee_id`, tanggal masuk, tanggal keluar, dan status penempatan.</p>
            </div>
        </div>
        <div class="subtle-box" style="margin-top:0;">
            <ul>
                <li>Riwayat mutasi dapat ditampilkan dari banyak record penempatan per pengungsi.</li>
                <li>Status seperti aktif, transit, dan selesai dapat dipetakan di level record penempatan.</li>
                <li>Catatan supervisor bisa disimpan pada kolom `notes` untuk audit operasional.</li>
                <li>Form create dan edit kini memakai validasi tanggal masuk, tanggal keluar, dan status penempatan yang lebih ketat.</li>
            </ul>
        </div>
    </section>
@endsection
