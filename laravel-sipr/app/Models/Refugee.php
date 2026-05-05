<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Refugee extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_id',
        'name',
        'nationality',
        'unhcr_number',
        'status',
        'location',
        'notes',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RefugeeDocument::class);
    }

    public function auditTrails(): HasMany
    {
        return $this->hasMany(AuditTrail::class);
    }

    public static function sampleData(): array
    {
        return [
            ['id' => 1, 'internal_id' => 'RDS-24001', 'name' => 'Amina Hassan', 'nationality' => 'Somalia', 'unhcr_number' => 'UNHCR-SOM-8812', 'status' => 'Aktif', 'location' => 'Hunian A-03', 'document_status' => 'Lengkap', 'updated_at_label' => '04 Mei 2026, 14:30', 'notes' => 'Dokumen keluarga lengkap.', 'registered_at' => '2026-04-11 09:30:00'],
            ['id' => 2, 'internal_id' => 'RDS-24008', 'name' => 'Mahmoud Kareem', 'nationality' => 'Irak', 'unhcr_number' => 'UNHCR-IRQ-1023', 'status' => 'Verifikasi', 'location' => 'Hunian B-02', 'document_status' => 'Perlu Verifikasi', 'updated_at_label' => '04 Mei 2026, 13:10', 'notes' => 'Menunggu verifikasi scan kartu UNHCR.', 'registered_at' => '2026-04-13 10:15:00'],
            ['id' => 3, 'internal_id' => 'RDS-24011', 'name' => 'Samira Nabil', 'nationality' => 'Afghanistan', 'unhcr_number' => 'UNHCR-AFG-5511', 'status' => 'Aktif', 'location' => 'Hunian C-05', 'document_status' => 'Lengkap', 'updated_at_label' => '04 Mei 2026, 11:48', 'notes' => 'Siap masuk rekap bulanan.', 'registered_at' => '2026-04-15 08:10:00'],
            ['id' => 4, 'internal_id' => 'RDS-24016', 'name' => 'Yousef Rahman', 'nationality' => 'Myanmar', 'unhcr_number' => 'UNHCR-MMR-2290', 'status' => 'Mutasi', 'location' => 'Transit 1', 'document_status' => 'Belum Lengkap', 'updated_at_label' => '04 Mei 2026, 10:05', 'notes' => 'Perpindahan sementara untuk evaluasi lanjutan.', 'registered_at' => '2026-04-17 13:25:00'],
            ['id' => 5, 'internal_id' => 'RDS-24021', 'name' => 'Layla Aziz', 'nationality' => 'Sudan', 'unhcr_number' => 'UNHCR-SDN-4477', 'status' => 'Aktif', 'location' => 'Hunian A-01', 'document_status' => 'Lengkap', 'updated_at_label' => '03 Mei 2026, 16:42', 'notes' => 'Status aktif setelah persetujuan supervisor.', 'registered_at' => '2026-04-19 11:40:00'],
            ['id' => 6, 'internal_id' => 'RDS-24027', 'name' => 'Karim Saeed', 'nationality' => 'Yaman', 'unhcr_number' => 'UNHCR-YEM-7130', 'status' => 'Verifikasi', 'location' => 'Hunian D-02', 'document_status' => 'Perlu Verifikasi', 'updated_at_label' => '03 Mei 2026, 15:27', 'notes' => 'Menunggu lampiran tambahan.', 'registered_at' => '2026-04-21 14:05:00'],
        ];
    }
}
