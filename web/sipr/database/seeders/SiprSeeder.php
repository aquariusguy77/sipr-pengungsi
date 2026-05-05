<?php

namespace Database\Seeders;

use App\Models\AuditTrail;
use App\Models\Placement;
use App\Models\Refugee;
use App\Models\RefugeeDocument;
use App\Models\ReportLog;
use Illuminate\Database\Seeder;

class SiprSeeder extends Seeder
{
    public function run(): void
    {
        $demoRefugees = array_merge(Refugee::sampleData(), [
            ['internal_id' => 'RDS-24032', 'name' => 'Fatima Noor', 'nationality' => 'Ethiopia', 'unhcr_number' => 'UNHCR-ETH-2210', 'status' => 'Aktif', 'location' => 'Hunian B-04', 'document_status' => 'Lengkap', 'updated_at_label' => '05 Mei 2026, 08:45', 'notes' => 'Data keluarga sudah tervalidasi penuh.', 'registered_at' => '2026-04-23 08:45:00'],
            ['internal_id' => 'RDS-24033', 'name' => 'Omar Jalal', 'nationality' => 'Palestina', 'unhcr_number' => 'UNHCR-PSE-1028', 'status' => 'Verifikasi', 'location' => 'Hunian C-02', 'document_status' => 'Perlu Verifikasi', 'updated_at_label' => '05 Mei 2026, 09:20', 'notes' => 'Menunggu unggahan surat pendukung tambahan.', 'registered_at' => '2026-04-24 10:20:00'],
            ['internal_id' => 'RDS-24034', 'name' => 'Nadia Karim', 'nationality' => 'Irak', 'unhcr_number' => 'UNHCR-IRQ-8821', 'status' => 'Mutasi', 'location' => 'Transit 2', 'document_status' => 'Belum Lengkap', 'updated_at_label' => '05 Mei 2026, 10:15', 'notes' => 'Dalam evaluasi perpindahan lokasi sementara.', 'registered_at' => '2026-04-25 11:15:00'],
            ['internal_id' => 'RDS-24035', 'name' => 'Hassan Ali', 'nationality' => 'Somalia', 'unhcr_number' => 'UNHCR-SOM-6632', 'status' => 'Aktif', 'location' => 'Hunian D-01', 'document_status' => 'Lengkap', 'updated_at_label' => '05 Mei 2026, 11:05', 'notes' => 'Siap masuk rekap mingguan.', 'registered_at' => '2026-04-26 09:05:00'],
        ]);

        foreach ($demoRefugees as $item) {
            Refugee::updateOrCreate(
                ['internal_id' => $item['internal_id']],
                [
                    'name' => $item['name'],
                    'nationality' => $item['nationality'],
                    'unhcr_number' => $item['unhcr_number'],
                    'status' => $item['status'],
                    'location' => $item['location'],
                    'notes' => $item['notes'],
                    'registered_at' => $item['registered_at'],
                ]
            );
        }

        $demoPlacements = array_merge(Placement::sampleData(), [
            ['refugee_id' => 7, 'location_name' => 'Hunian B-04', 'entered_at' => '2026-04-23', 'exited_at' => null, 'placement_status' => 'Aktif', 'notes' => 'Stabil dan siap rekap mingguan.'],
            ['refugee_id' => 8, 'location_name' => 'Hunian C-02', 'entered_at' => '2026-04-24', 'exited_at' => null, 'placement_status' => 'Verifikasi', 'notes' => 'Menunggu verifikasi dokumen tambahan.'],
            ['refugee_id' => 9, 'location_name' => 'Transit 2', 'entered_at' => '2026-04-25', 'exited_at' => null, 'placement_status' => 'Mutasi', 'notes' => 'Evaluasi perpindahan sementara.'],
            ['refugee_id' => 10, 'location_name' => 'Hunian D-01', 'entered_at' => '2026-04-26', 'exited_at' => null, 'placement_status' => 'Aktif', 'notes' => 'Kondisi stabil.'],
        ]);

        foreach ($demoPlacements as $item) {
            Placement::updateOrCreate(
                ['refugee_id' => $item['refugee_id'], 'location_name' => $item['location_name']],
                [
                    'entered_at' => $item['entered_at'],
                    'exited_at' => $item['exited_at'],
                    'placement_status' => $item['placement_status'],
                    'notes' => $item['notes'],
                ]
            );
        }

        $demoDocuments = array_merge(RefugeeDocument::sampleData(), [
            ['refugee_id' => 7, 'document_type' => 'Identitas Utama', 'file_name' => 'fatima-identity.pdf', 'file_path' => 'folder/pengungsi/identitas/fatima-identity.pdf', 'drive_file_id' => 'drive-007', 'verification_status' => 'Lengkap', 'uploaded_at' => '2026-05-05 08:15:00', 'uploaded_by' => null, 'notes' => 'Valid dan lengkap.'],
            ['refugee_id' => 8, 'document_type' => 'Administrasi Internal', 'file_name' => 'omar-admin.pdf', 'file_path' => 'folder/pengungsi/administrasi/omar-admin.pdf', 'drive_file_id' => 'drive-008', 'verification_status' => 'Perlu Verifikasi', 'uploaded_at' => '2026-05-05 09:05:00', 'uploaded_by' => null, 'notes' => 'Perlu pemeriksaan lanjutan supervisor.'],
            ['refugee_id' => 9, 'document_type' => 'Lampiran Tambahan', 'file_name' => 'nadia-supporting.pdf', 'file_path' => 'folder/pengungsi/lampiran/nadia-supporting.pdf', 'drive_file_id' => 'drive-009', 'verification_status' => 'Belum Lengkap', 'uploaded_at' => '2026-05-05 10:22:00', 'uploaded_by' => null, 'notes' => 'Masih kurang surat pendukung keluarga.'],
            ['refugee_id' => 10, 'document_type' => 'Riwayat Penempatan', 'file_name' => 'hassan-placement.pdf', 'file_path' => 'folder/pengungsi/penempatan/hassan-placement.pdf', 'drive_file_id' => 'drive-010', 'verification_status' => 'Lengkap', 'uploaded_at' => '2026-05-05 11:12:00', 'uploaded_by' => null, 'notes' => 'Sudah cocok dengan data lokasi aktif.'],
        ]);

        foreach ($demoDocuments as $item) {
            RefugeeDocument::updateOrCreate(
                ['refugee_id' => $item['refugee_id'], 'document_type' => $item['document_type']],
                [
                    'file_name' => $item['file_name'],
                    'file_path' => $item['file_path'],
                    'drive_file_id' => $item['drive_file_id'],
                    'verification_status' => $item['verification_status'],
                    'uploaded_at' => $item['uploaded_at'],
                    'uploaded_by' => $item['uploaded_by'],
                    'notes' => $item['notes'],
                ]
            );
        }

        $demoAudits = array_merge(AuditTrail::recentActivity(), AuditTrail::sampleHistory(), [
            ['refugee_id' => 7, 'field_name' => 'document_status', 'old_value' => 'Perlu Verifikasi', 'new_value' => 'Lengkap', 'action_label' => 'Verifikasi selesai', 'performed_by_name' => 'Admin', 'performed_by' => null, 'reason' => 'Berkas identitas lengkap', 'performed_at' => '2026-05-05 08:40:00'],
            ['refugee_id' => 8, 'field_name' => 'status', 'old_value' => 'Aktif', 'new_value' => 'Verifikasi', 'action_label' => 'Status berubah', 'performed_by_name' => 'Supervisor', 'performed_by' => null, 'reason' => 'Menunggu pemeriksaan dokumen tambahan', 'performed_at' => '2026-05-05 09:40:00'],
            ['refugee_id' => 9, 'field_name' => 'location', 'old_value' => 'Hunian C-01', 'new_value' => 'Transit 2', 'action_label' => 'Perubahan lokasi', 'performed_by_name' => 'Petugas Pendataan', 'performed_by' => null, 'reason' => 'Evaluasi penempatan sementara', 'performed_at' => '2026-05-05 10:35:00'],
            ['refugee_id' => 10, 'field_name' => 'refugee', 'old_value' => null, 'new_value' => 'RDS-24035', 'action_label' => 'Data pengungsi ditambahkan', 'performed_by_name' => 'Admin', 'performed_by' => null, 'reason' => 'Input data baru', 'performed_at' => '2026-05-05 11:20:00'],
        ]);

        foreach ($demoAudits as $item) {
            AuditTrail::updateOrCreate(
                [
                    'refugee_id' => $item['refugee_id'],
                    'action_label' => $item['action_label'],
                    'performed_at' => $item['performed_at'],
                ],
                [
                    'field_name' => $item['field_name'],
                    'old_value' => $item['old_value'],
                    'new_value' => $item['new_value'],
                    'performed_by_name' => $item['performed_by_name'],
                    'performed_by' => $item['performed_by'],
                    'reason' => $item['reason'],
                ]
            );
        }

        foreach ([
            ['report_type' => 'Rekap Data Aktif', 'filter_summary' => 'Periode Mei 2026 • Semua lokasi', 'downloaded_by' => null, 'downloaded_by_name' => 'Admin', 'downloaded_at' => '2026-05-04 09:10:00'],
            ['report_type' => 'Laporan Dokumen', 'filter_summary' => 'Status Perlu Verifikasi', 'downloaded_by' => null, 'downloaded_by_name' => 'Supervisor', 'downloaded_at' => '2026-05-03 14:22:00'],
            ['report_type' => 'Audit Trail', 'filter_summary' => 'Perubahan kritis 7 hari terakhir', 'downloaded_by' => null, 'downloaded_by_name' => 'Admin', 'downloaded_at' => '2026-05-02 16:18:00'],
        ] as $item) {
            ReportLog::updateOrCreate(
                ['report_type' => $item['report_type'], 'downloaded_at' => $item['downloaded_at']],
                $item
            );
        }
    }
}
