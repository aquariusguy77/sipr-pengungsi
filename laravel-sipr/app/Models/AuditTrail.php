<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'refugee_id',
        'field_name',
        'old_value',
        'new_value',
        'action_label',
        'performed_by_name',
        'performed_by',
        'reason',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function refugee(): BelongsTo
    {
        return $this->belongsTo(Refugee::class);
    }

    public static function recentActivity(): array
    {
        return [
            ['refugee_id' => 4, 'title' => 'Perubahan lokasi aktif', 'detail' => 'Yousef Rahman dipindahkan ke Transit 1 untuk evaluasi lanjutan.', 'actor' => 'Supervisor', 'time' => '04 Mei 2026 • 14:42', 'field_name' => 'location', 'old_value' => 'Hunian D-01', 'new_value' => 'Transit 1', 'action_label' => 'Perubahan lokasi aktif', 'performed_by_name' => 'Supervisor', 'performed_by' => null, 'reason' => 'Evaluasi lanjutan', 'performed_at' => '2026-05-04 14:42:00'],
            ['refugee_id' => 2, 'title' => 'Unggah dokumen baru', 'detail' => 'Mahmoud Kareem menambahkan scan kartu UNHCR untuk pemeriksaan ulang.', 'actor' => 'Petugas Pendataan', 'time' => '04 Mei 2026 • 13:08', 'field_name' => 'document_upload', 'old_value' => null, 'new_value' => 'scan kartu UNHCR', 'action_label' => 'Unggah dokumen baru', 'performed_by_name' => 'Petugas Pendataan', 'performed_by' => null, 'reason' => 'Pemeriksaan ulang', 'performed_at' => '2026-05-04 13:08:00'],
            ['refugee_id' => 1, 'title' => 'Verifikasi selesai', 'detail' => 'Dokumen keluarga Amina Hassan dinyatakan lengkap oleh admin.', 'actor' => 'Admin', 'time' => '04 Mei 2026 • 09:26', 'field_name' => 'verification_status', 'old_value' => 'Perlu Verifikasi', 'new_value' => 'Lengkap', 'action_label' => 'Verifikasi selesai', 'performed_by_name' => 'Admin', 'performed_by' => null, 'reason' => 'Dokumen valid', 'performed_at' => '2026-05-04 09:26:00'],
        ];
    }

    public static function sampleHistory(): array
    {
        return [
            ['refugee_id' => 5, 'title' => 'Status berubah: Verifikasi ke Aktif', 'detail' => 'Layla Aziz disetujui setelah lampiran administrasi dinyatakan sah.', 'actor' => 'Supervisor', 'time' => '03 Mei 2026 • 16:45', 'field_name' => 'status', 'old_value' => 'Verifikasi', 'new_value' => 'Aktif', 'action_label' => 'Status berubah', 'performed_by_name' => 'Supervisor', 'performed_by' => null, 'reason' => 'Lampiran sah', 'performed_at' => '2026-05-03 16:45:00'],
            ['refugee_id' => 2, 'title' => 'Field lokasi aktif diperbarui', 'detail' => 'Mahmoud Kareem dipindahkan dari Hunian B-01 ke Hunian B-02.', 'actor' => 'Petugas Pendataan', 'time' => '03 Mei 2026 • 11:12', 'field_name' => 'location', 'old_value' => 'Hunian B-01', 'new_value' => 'Hunian B-02', 'action_label' => 'Perubahan lokasi', 'performed_by_name' => 'Petugas Pendataan', 'performed_by' => null, 'reason' => 'Mutasi internal', 'performed_at' => '2026-05-03 11:12:00'],
            ['refugee_id' => 6, 'title' => 'Catatan audit ditambahkan', 'detail' => 'Admin menambahkan alasan perubahan nomor registrasi internal.', 'actor' => 'Admin', 'time' => '02 Mei 2026 • 17:30', 'field_name' => 'internal_id', 'old_value' => 'RDS-24027A', 'new_value' => 'RDS-24027', 'action_label' => 'Perbaikan identitas', 'performed_by_name' => 'Admin', 'performed_by' => null, 'reason' => 'Penyesuaian register', 'performed_at' => '2026-05-02 17:30:00'],
        ];
    }
}
