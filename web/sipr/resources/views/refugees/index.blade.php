@extends('layouts.app')

@php
    $badgeClass = fn (string $value) => match ($value) {
        'Lengkap', 'Aktif' => 'success',
        'Perlu Verifikasi', 'Verifikasi' => 'warn',
        default => 'danger',
    };
@endphp

@section('content')
    <section class="panel section-anchor" id="data-pengungsi">
        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="users" class="chip-icon" />Data Pengungsi</span>
                <h3>Daftar dan pencarian operasional</h3>
                <p class="section-intro">Filter di bawah ini memakai request `GET` Laravel sehingga siap dihubungkan ke query database atau pagination.</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <span class="badge">{{ $refugees->count() }} data tampil</span>
                @if ($canManageRefugees)
                    <a class="badge success" href="{{ route('refugees.create') }}">Tambah Data</a>
                @endif
            </div>
        </div>

        @if (session('status'))
            <div class="subtle-box" style="margin-top:0;margin-bottom:16px;border-style:solid;border-color:rgba(31,157,122,.25);">
                {{ session('status') }}
            </div>
        @endif

        <form class="filters" method="GET" action="{{ route('refugees.index') }}">
            <input class="control" type="text" name="keyword" value="{{ $activeFilters['keyword'] }}" placeholder="Cari nama / ID internal">
            <select class="control" name="nationality">
                <option value="">Semua kebangsaan</option>
                @foreach ($filterOptions['nationalities'] as $item)
                    <option value="{{ $item }}" @selected($activeFilters['nationality'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select class="control" name="status">
                <option value="">Semua status</option>
                @foreach ($filterOptions['statuses'] as $item)
                    <option value="{{ $item }}" @selected($activeFilters['status'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select class="control" name="location">
                <option value="">Semua lokasi</option>
                @foreach ($filterOptions['locations'] as $item)
                    <option value="{{ $item }}" @selected($activeFilters['location'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select class="control" name="document_status">
                <option value="">Semua kelengkapan</option>
                @foreach ($filterOptions['documentStatuses'] as $item)
                    <option value="{{ $item }}" @selected($activeFilters['document_status'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select class="control" name="sort">
                <option value="name" @selected($activeFilters['sort'] === 'name')>Urut Nama</option>
                <option value="internal_id" @selected($activeFilters['sort'] === 'internal_id')>Urut ID Internal</option>
                <option value="nationality" @selected($activeFilters['sort'] === 'nationality')>Urut Kebangsaan</option>
                <option value="status" @selected($activeFilters['sort'] === 'status')>Urut Status</option>
                <option value="location" @selected($activeFilters['sort'] === 'location')>Urut Lokasi</option>
                <option value="updated_at" @selected($activeFilters['sort'] === 'updated_at')>Urut Pembaruan</option>
            </select>
            <select class="control" name="direction">
                <option value="asc" @selected($activeFilters['direction'] === 'asc')>A-Z / Lama-Baru</option>
                <option value="desc" @selected($activeFilters['direction'] === 'desc')>Z-A / Baru-Lama</option>
            </select>
            <select class="control" name="per_page">
                <option value="5" @selected((string) $activeFilters['per_page'] === '5')>5 per halaman</option>
                <option value="10" @selected((string) $activeFilters['per_page'] === '10')>10 per halaman</option>
                <option value="15" @selected((string) $activeFilters['per_page'] === '15')>15 per halaman</option>
                <option value="20" @selected((string) $activeFilters['per_page'] === '20')>20 per halaman</option>
            </select>
            <button class="control" type="submit" style="cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--green));color:#fff;border:none;">Terapkan Filter</button>
            <a class="control" href="{{ route('refugees.index') }}" style="display:grid;place-items:center;">Reset</a>
        </form>

        <div style="overflow:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Pengungsi</th>
                        <th>Kebangsaan</th>
                        <th>Status</th>
                        <th>Lokasi Aktif</th>
                        <th>Dokumen</th>
                        <th>Pembaruan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($refugees as $refugee)
                        <tr>
                            <td>
                                <strong>{{ $refugee['name'] }}</strong><br>
                                <span class="table-meta">{{ $refugee['internal_id'] }}</span>
                                @if (!empty($refugee['id']))
                                    <br><a class="table-meta" href="{{ route('refugees.show', $refugee['id']) }}">Lihat detail</a>
                                    @if ($canManageRefugees)
                                        <br><a class="table-meta" href="{{ route('refugees.edit', $refugee['id']) }}">Ubah data</a>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $refugee['nationality'] }}</td>
                            <td><span class="badge {{ $badgeClass($refugee['status']) }}">{{ $refugee['status'] }}</span></td>
                            <td>{{ $refugee['location'] }}</td>
                            <td><span class="badge {{ $badgeClass($refugee['document_status']) }}">{{ $refugee['document_status'] }}</span></td>
                            <td>{{ $refugee['updated_at_label'] }}</td>
                            <td>
                                @if (!empty($refugee['id']))
                                    <div style="display:grid;gap:8px;justify-items:start;">
                                        @if ($canManageRefugees)
                                            <a class="table-meta" href="{{ route('refugees.edit', $refugee['id']) }}">Edit</a>
                                        @endif
                                        @if ($canDeleteRefugees)
                                            <form method="POST" action="{{ route('refugees.destroy', $refugee['id']) }}" onsubmit="return confirm('Hapus data {{ addslashes($refugee['name']) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="table-meta" style="border:none;background:transparent;padding:0;cursor:pointer;">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Tidak ada data yang cocok dengan filter saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-toolbar" style="margin-top:16px;">
            <p>Menampilkan {{ $refugees->firstItem() ?? 0 }}-{{ $refugees->lastItem() ?? 0 }} dari {{ $refugees->total() }} data.</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @if ($refugees->onFirstPage())
                    <span class="badge">Sebelumnya</span>
                @else
                    <a class="badge" href="{{ $refugees->previousPageUrl() }}">Sebelumnya</a>
                @endif

                <span class="badge">Halaman {{ $refugees->currentPage() }} / {{ $refugees->lastPage() }}</span>

                @if ($refugees->hasMorePages())
                    <a class="badge" href="{{ $refugees->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="badge">Berikutnya</span>
                @endif
            </div>
        </div>
    </section>

    <section class="double-grid">
        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="dashboard" class="chip-icon" />Form Bertahap</span>
                    <h3>Alur input yang siap dikembangkan</h3>
                    <p class="section-intro">Halaman daftar ini dipasangkan dengan alur form untuk identitas, administrasi, penempatan, dan unggah dokumen.</p>
                </div>
            </div>
            <div class="step-grid">
                <div class="step-card active"><span class="step-index">1</span><strong>Identitas</strong><p>Data dasar, UNHCR, dan kebangsaan.</p></div>
                <div class="step-card"><span class="step-index">2</span><strong>Administrasi</strong><p>Registrasi, petugas, dan verifikasi minimum.</p></div>
                <div class="step-card"><span class="step-index">3</span><strong>Penempatan</strong><p>Lokasi aktif dan histori mutasi.</p></div>
                <div class="step-card"><span class="step-index">4</span><strong>Dokumen</strong><p>Unggah berkas dan status validasi.</p></div>
            </div>
        </div>

        <div class="panel">
            <div class="section-head">
                <div>
                    <span class="section-tag"><x-icon name="alert" class="chip-icon" />Catatan Filter</span>
                    <h3>Perilaku request saat ini</h3>
                    <p class="section-intro">Validasi filter diletakkan pada `RefugeeFilterRequest` agar mudah ditingkatkan ke query Eloquent.</p>
                </div>
            </div>
            <div class="subtle-box" style="margin-top:0;">
                <ul>
                    <li>Filter memakai method `GET` agar URL dapat dibagikan atau disimpan.</li>
                    <li>Controller saat ini memfilter dummy collection, lalu siap diganti ke builder database.</li>
                    <li>Reset filter diarahkan kembali ke route `refugees.index` tanpa parameter.</li>
                </ul>
            </div>
        </div>
    </section>
@endsection
