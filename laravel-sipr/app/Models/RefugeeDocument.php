<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class RefugeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'refugee_id',
        'document_type',
        'file_name',
        'file_path',
        'drive_file_id',
        'verification_status',
        'uploaded_at',
        'uploaded_by',
        'notes',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function refugee(): BelongsTo
    {
        return $this->belongsTo(Refugee::class);
    }

    public static function sampleData(): array
    {
        return [
            ['id' => 1, 'refugee_id' => 1, 'name' => 'Identitas Utama', 'meta' => 'Paspor, UNHCR, atau surat pengenal lain', 'status' => 'Lengkap', 'storage' => 'folder/pengungsi/identitas', 'document_type' => 'Identitas Utama', 'file_name' => 'amina-passport.pdf', 'file_path' => 'folder/pengungsi/identitas/amina-passport.pdf', 'drive_file_id' => 'drive-001', 'verification_status' => 'Lengkap', 'uploaded_at' => '2026-05-01 09:00:00', 'uploaded_by' => null, 'notes' => 'Terverifikasi admin.'],
            ['id' => 2, 'refugee_id' => 2, 'name' => 'Administrasi Internal', 'meta' => 'Form registrasi, catatan pemeriksaan, approval', 'status' => 'Perlu Verifikasi', 'storage' => 'folder/pengungsi/administrasi', 'document_type' => 'Administrasi Internal', 'file_name' => 'mahmoud-admin.pdf', 'file_path' => 'folder/pengungsi/administrasi/mahmoud-admin.pdf', 'drive_file_id' => 'drive-002', 'verification_status' => 'Perlu Verifikasi', 'uploaded_at' => '2026-05-02 10:10:00', 'uploaded_by' => null, 'notes' => 'Menunggu supervisor.'],
            ['id' => 3, 'refugee_id' => 3, 'name' => 'Riwayat Penempatan', 'meta' => 'Mutasi, tanggal masuk, lampiran pendukung', 'status' => 'Lengkap', 'storage' => 'folder/pengungsi/penempatan', 'document_type' => 'Riwayat Penempatan', 'file_name' => 'samira-placement.pdf', 'file_path' => 'folder/pengungsi/penempatan/samira-placement.pdf', 'drive_file_id' => 'drive-003', 'verification_status' => 'Lengkap', 'uploaded_at' => '2026-05-02 11:20:00', 'uploaded_by' => null, 'notes' => 'Lengkap.'],
            ['id' => 4, 'refugee_id' => 4, 'name' => 'Lampiran Tambahan', 'meta' => 'Dokumen keluarga, surat kesehatan, catatan khusus', 'status' => 'Belum Lengkap', 'storage' => 'folder/pengungsi/lampiran', 'document_type' => 'Lampiran Tambahan', 'file_name' => 'yousef-extra.pdf', 'file_path' => 'folder/pengungsi/lampiran/yousef-extra.pdf', 'drive_file_id' => 'drive-004', 'verification_status' => 'Belum Lengkap', 'uploaded_at' => '2026-05-03 08:45:00', 'uploaded_by' => null, 'notes' => 'Masih kurang surat pendukung.'],
        ];
    }
}
