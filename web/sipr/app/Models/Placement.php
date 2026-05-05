<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;

    protected $fillable = [
        'refugee_id',
        'location_name',
        'entered_at',
        'exited_at',
        'placement_status',
        'notes',
    ];

    protected $casts = [
        'entered_at' => 'date',
        'exited_at' => 'date',
    ];

    public function refugee(): BelongsTo
    {
        return $this->belongsTo(Refugee::class);
    }

    public static function sampleData(): array
    {
        return [
            ['id' => 1, 'refugee_id' => 1, 'title' => 'Hunian A', 'detail' => '84 penghuni aktif • 3 mutasi minggu ini', 'note' => 'Fokus pemeriksaan pada perpanjangan dokumen keluarga campuran.', 'location_name' => 'Hunian A-03', 'entered_at' => '2026-04-11', 'exited_at' => null, 'placement_status' => 'Aktif', 'notes' => 'Stabil.'],
            ['id' => 2, 'refugee_id' => 2, 'title' => 'Hunian B', 'detail' => '57 penghuni aktif • 1 mutasi masuk', 'note' => 'Ada 4 data dengan unggahan identitas baru menunggu verifikasi.', 'location_name' => 'Hunian B-02', 'entered_at' => '2026-04-13', 'exited_at' => null, 'placement_status' => 'Aktif', 'notes' => 'Perlu verifikasi data dokumen.'],
            ['id' => 3, 'refugee_id' => 3, 'title' => 'Hunian C', 'detail' => '69 penghuni aktif • stabil', 'note' => 'Tidak ada perpindahan besar dalam 7 hari terakhir.', 'location_name' => 'Hunian C-05', 'entered_at' => '2026-04-15', 'exited_at' => null, 'placement_status' => 'Aktif', 'notes' => 'Stabil.'],
            ['id' => 4, 'refugee_id' => 4, 'title' => 'Transit & Observasi', 'detail' => '37 penghuni aktif • 2 evaluasi penempatan', 'note' => 'Perlu sinkronisasi catatan supervisor dan petugas pendataan.', 'location_name' => 'Transit 1', 'entered_at' => '2026-04-17', 'exited_at' => null, 'placement_status' => 'Mutasi', 'notes' => 'Evaluasi lanjutan.'],
        ];
    }
}
