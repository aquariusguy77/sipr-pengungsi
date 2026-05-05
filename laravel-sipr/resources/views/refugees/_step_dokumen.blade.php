<div class="wizard-panel" data-step-panel="4" style="margin-top:18px;display:none;">
    <div class="double-grid" style="margin-top:0;">
        <div>
            <label class="table-meta">Status Kelengkapan Dokumen</label>
            <select class="control" disabled>
                @foreach ($documentStatusOptions as $item)
                    <option>{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="table-meta">Target Penyimpanan</label>
            <input class="control" type="text" value="Firebase Storage / folder pengungsi" disabled>
        </div>
    </div>
    <div class="subtle-box">
        Unggah dan validasi dokumen penuh dikelola di modul Dokumen. Langkah ini dipertahankan di wizard agar alurnya tetap terasa lengkap bagi operator.
    </div>
    <div class="subtle-box">
        <strong>Placeholder endpoint backend</strong>
        <ul>
            <li><code>{{ $backendEndpoints['store'] }}</code> untuk simpan data baru.</li>
            <li><code>{{ $backendEndpoints['update'] }}</code> untuk pembaruan data berdasarkan ID.</li>
            <li><code>{{ $backendEndpoints['destroy'] }}</code> untuk penghapusan data dengan konfirmasi.</li>
        </ul>
    </div>
</div>
