@php
    $selectedRefugee = old('refugee_id', $document->refugee_id ?? '');
    $selectedType = old('document_type', $document->document_type ?? '');
    $selectedStatus = old('verification_status', $document->verification_status ?? '');
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <section class="panel">
        @if ($errors->any())
            <div class="subtle-box" style="margin-top:0;margin-bottom:18px;border-style:solid;border-color:rgba(217,83,79,.24);background:linear-gradient(180deg,#fff8f8 0%,#fff 100%);">
                <h4 style="color:var(--danger);">Periksa kembali data dokumen</h4>
                <ul style="color:var(--danger);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="subtle-box" style="margin-top:0;margin-bottom:18px;border-style:solid;border-color:rgba(31,157,122,.25);">
                {{ session('status') }}
            </div>
        @endif

        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="folder" class="chip-icon" />Form Dokumen</span>
                <h3>Metadata dokumen dan verifikasi</h3>
                <p class="section-intro">Gunakan form ini untuk mengelola metadata berkas, referensi penyimpanan, dan status verifikasi dokumen pengungsi.</p>
            </div>
            <span class="badge">{{ $formMethod === 'POST' ? 'Mode create' : 'Mode edit' }}</span>
        </div>
        <div class="double-grid" style="margin-top:0;">
            <div>
                <label class="table-meta">Pengungsi</label>
                <select class="control" name="refugee_id" required>
                    <option value="">Pilih pengungsi</option>
                    @foreach ($refugees as $item)
                        <option value="{{ $item['id'] }}" @selected((string) $selectedRefugee === (string) $item['id'])>{{ $item['name'] }} - {{ $item['internal_id'] }}</option>
                    @endforeach
                </select>
                @error('refugee_id')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Jenis Dokumen</label>
                <select class="control" name="document_type" required>
                    <option value="">Pilih jenis</option>
                    @foreach ($documentTypes as $item)
                        <option value="{{ $item }}" @selected($selectedType === $item)>{{ $item }}</option>
                    @endforeach
                </select>
                @error('document_type')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Nama File</label>
                <input class="control" type="text" name="file_name" value="{{ old('file_name', $document->file_name ?? '') }}" required>
                @error('file_name')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Unggah File</label>
                <input class="control" type="file" name="uploaded_file" accept=".pdf,.jpg,.jpeg,.png">
                @error('uploaded_file')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Path File</label>
                <input class="control" type="text" name="file_path" value="{{ old('file_path', $document->file_path ?? '') }}">
                @error('file_path')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Firebase Document Key</label>
                <input class="control" type="text" name="drive_file_id" value="{{ old('drive_file_id', $document->drive_file_id ?? '') }}">
                @error('drive_file_id')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Status Verifikasi</label>
                <select class="control" name="verification_status" required>
                    <option value="">Pilih status</option>
                    @foreach ($statusOptions as $item)
                        <option value="{{ $item }}" @selected($selectedStatus === $item)>{{ $item }}</option>
                    @endforeach
                </select>
                @error('verification_status')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="table-meta">Tanggal Unggah</label>
                <input class="control" type="date" name="uploaded_at" value="{{ old('uploaded_at', optional($document->uploaded_at ?? null)->format('Y-m-d')) }}" required>
                @error('uploaded_at')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>
        <div style="margin-top:18px;">
            <label class="table-meta">Catatan</label>
            <textarea class="control" name="notes" rows="4" style="width:100%;resize:vertical;" maxlength="1000">{{ old('notes', $document->notes ?? '') }}</textarea>
            @error('notes')<div class="table-meta" style="color:var(--danger);margin-top:6px;">{{ $message }}</div>@enderror
        </div>
        <div class="subtle-box">
            <h4>Fondasi upload dokumen</h4>
            <ul>
                <li>Upload sementara diarahkan ke disk lokal Laravel pada folder <code>storage/app/private/documents</code> atau disk lokal yang aktif.</li>
                <li>Path file dan nama file akan otomatis diisi ulang saat file baru diunggah.</li>
                <li>Langkah berikutnya tinggal mengganti penyimpanan lokal ini ke Firebase Storage atau proxy backend yang diinginkan.</li>
            </ul>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
            <button class="control" type="submit" style="cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--green));color:#fff;border:none;">Simpan Dokumen</button>
            <a class="control" href="{{ route('documents.index') }}" style="display:grid;place-items:center;">Batal</a>
        </div>
    </section>
</form>
