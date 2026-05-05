<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\Placement;
use App\Models\Refugee;
use App\Models\RefugeeDocument;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Throwable;

class SiprDataService
{
    public function __construct(
        protected FirebaseRealtimeDatabaseService $firebaseDatabase,
        protected FirebaseService $firebaseService
    ) {
    }

    protected function resolveCollection(callable $eloquentResolver, callable $fallbackResolver): Collection
    {
        try {
            $records = $eloquentResolver();

            if ($records instanceof Collection && $records->isNotEmpty()) {
                return $records;
            }
        } catch (Throwable) {
            // Fallback ke sample data ketika database belum siap atau tabel belum tersedia.
        }

        return $this->sampleDataEnabled() ? $fallbackResolver() : collect();
    }

    protected function resolveFirebaseCollection(string $node, callable $normalizer): Collection
    {
        if (! $this->firebaseReadEnabled()) {
            return collect();
        }

        return $this->firebaseDatabase->fetchCollection($node, $normalizer);
    }

    public function sampleDataEnabled(): bool
    {
        return (bool) ($this->firebaseService->appConfig()['data']['sample_data_enabled'] ?? true);
    }

    public function firebaseReadEnabled(): bool
    {
        return (bool) ($this->firebaseService->appConfig()['data']['firebase_read_enabled'] ?? true);
    }

    public function stats(): array
    {
        return [
            ['label' => 'Total Data Aktif', 'value' => 247, 'note' => 'Naik 6 data dibanding minggu lalu dengan distribusi lintas hunian aktif.', 'icon' => 'users', 'tone' => 'blue'],
            ['label' => 'Dokumen Lengkap', 'value' => 186, 'note' => 'Berkas dengan identitas, administrasi, dan lampiran pendukung yang telah tervalidasi.', 'icon' => 'file', 'tone' => 'green'],
            ['label' => 'Perlu Verifikasi', 'value' => 31, 'note' => 'Data dengan dokumen baru, pembaruan penting, atau catatan review dari supervisor.', 'icon' => 'alert', 'tone' => 'orange'],
            ['label' => 'Aktivitas Terbaru', 'value' => 19, 'note' => 'Catatan perubahan dalam 24 jam terakhir dari admin, petugas pendataan, dan supervisor.', 'icon' => 'history', 'tone' => 'deep'],
        ];
    }

    public function refugees(): Collection
    {
        $firebaseRecords = $this->resolveFirebaseCollection('refugees', function (string $key, array $payload) {
            return [
                'id' => $payload['id'] ?? $key,
                'internal_id' => $payload['internal_id'] ?? strtoupper($key),
                'name' => $payload['name'] ?? 'Tanpa nama',
                'nationality' => $payload['nationality'] ?? '-',
                'unhcr_number' => $payload['unhcr_number'] ?? null,
                'status' => $payload['status'] ?? 'Verifikasi',
                'location' => $payload['location'] ?? '-',
                'document_status' => $payload['document_status'] ?? ($payload['verification_status'] ?? 'Belum Lengkap'),
                'updated_at_label' => $this->formatTimestampLabel($payload['updated_at'] ?? null),
                'notes' => $payload['notes'] ?? null,
                'registered_at' => $payload['registered_at'] ?? null,
            ];
        });

        if ($firebaseRecords->isNotEmpty()) {
            return $firebaseRecords;
        }

        return $this->resolveCollection(
            fn () => Refugee::query()
                ->with('documents')
                ->orderBy('name')
                ->get()
                ->map(function (Refugee $refugee) {
                    return [
                        'id' => $refugee->id,
                        'internal_id' => $refugee->internal_id,
                        'name' => $refugee->name,
                        'nationality' => $refugee->nationality,
                        'unhcr_number' => $refugee->unhcr_number,
                        'status' => $refugee->status,
                        'location' => $refugee->location,
                        'document_status' => optional($refugee->documents->sortByDesc('uploaded_at')->first())->verification_status ?? 'Belum Lengkap',
                        'updated_at_label' => optional($refugee->updated_at)->format('d M Y, H:i') ?? '-',
                        'notes' => $refugee->notes,
                        'registered_at' => optional($refugee->registered_at)?->format('Y-m-d H:i:s'),
                    ];
                }),
            fn () => collect(Refugee::sampleData())
        );
    }

    public function filteredRefugees(array $filters): Collection
    {
        return $this->resolveCollection(
            fn () => Refugee::query()
                ->with('documents')
                ->when(filled($filters['keyword'] ?? null), function ($query) use ($filters) {
                    $keyword = '%' . trim((string) $filters['keyword']) . '%';
                    $query->where(function ($inner) use ($keyword) {
                        $inner->where('name', 'like', $keyword)
                            ->orWhere('internal_id', 'like', $keyword);
                    });
                })
                ->when(filled($filters['nationality'] ?? null), fn ($query) => $query->where('nationality', $filters['nationality']))
                ->when(filled($filters['status'] ?? null), fn ($query) => $query->where('status', $filters['status']))
                ->when(filled($filters['location'] ?? null), fn ($query) => $query->where('location', $filters['location']))
                ->orderBy('name')
                ->get()
                ->map(function (Refugee $refugee) use ($filters) {
                    $documentStatus = optional($refugee->documents->sortByDesc('uploaded_at')->first())->verification_status ?? 'Belum Lengkap';

                    return [
                        'id' => $refugee->id,
                        'internal_id' => $refugee->internal_id,
                        'name' => $refugee->name,
                        'nationality' => $refugee->nationality,
                        'unhcr_number' => $refugee->unhcr_number,
                        'status' => $refugee->status,
                        'location' => $refugee->location,
                        'document_status' => $documentStatus,
                        'updated_at_label' => optional($refugee->updated_at)->format('d M Y, H:i') ?? '-',
                        'notes' => $refugee->notes,
                        'registered_at' => optional($refugee->registered_at)?->format('Y-m-d H:i:s'),
                    ];
                })
                ->filter(function (array $refugee) use ($filters) {
                    return ($filters['document_status'] ?? '') === '' || $refugee['document_status'] === $filters['document_status'];
                })
                ->values(),
            fn () => $this->refugees()->filter(function (array $refugee) use ($filters) {
                $keyword = strtolower($filters['keyword'] ?? '');
                $matchesKeyword = $keyword === ''
                    || str_contains(strtolower($refugee['name']), $keyword)
                    || str_contains(strtolower($refugee['internal_id']), $keyword);

                $matchesNationality = ($filters['nationality'] ?? '') === '' || $refugee['nationality'] === $filters['nationality'];
                $matchesStatus = ($filters['status'] ?? '') === '' || $refugee['status'] === $filters['status'];
                $matchesLocation = ($filters['location'] ?? '') === '' || $refugee['location'] === $filters['location'];
                $matchesDocument = ($filters['document_status'] ?? '') === '' || $refugee['document_status'] === $filters['document_status'];

                return $matchesKeyword && $matchesNationality && $matchesStatus && $matchesLocation && $matchesDocument;
            })->values()
        );
    }

    public function paginatedFilteredRefugees(array $filters): LengthAwarePaginator
    {
        $items = $this->sortedCollection(
            $this->filteredRefugees($filters),
            (string) ($filters['sort'] ?? 'name'),
            (string) ($filters['direction'] ?? 'asc'),
            [
                'updated_at' => 'updated_at_label',
                'name' => 'name',
                'internal_id' => 'internal_id',
                'nationality' => 'nationality',
                'status' => 'status',
                'location' => 'location',
            ]
        );

        return $this->paginateCollection($items, (int) ($filters['per_page'] ?? 10));
    }

    public function sampleRefugeeById(int $id): ?array
    {
        return $this->refugees()->firstWhere('id', $id);
    }

    public function refugeeFilterOptions(): array
    {
        $refugees = $this->refugees();

        return [
            'nationalities' => $refugees->pluck('nationality')->unique()->values(),
            'statuses' => $refugees->pluck('status')->unique()->values(),
            'locations' => $refugees->pluck('location')->unique()->values(),
            'documentStatuses' => collect(['Lengkap', 'Perlu Verifikasi', 'Belum Lengkap']),
        ];
    }

    public function refugeeSelectOptions(): Collection
    {
        return $this->refugees()
            ->map(fn (array $refugee) => [
                'id' => $refugee['id'] ?? null,
                'name' => $refugee['name'] ?? 'Tanpa nama',
                'internal_id' => $refugee['internal_id'] ?? '-',
            ])
            ->filter(fn (array $refugee) => filled($refugee['id']))
            ->values();
    }

    public function placements(): Collection
    {
        $firebaseRecords = $this->resolveFirebaseCollection('placements', function (string $key, array $payload) {
            return [
                'id' => $payload['id'] ?? $key,
                'refugee_id' => $payload['refugee_id'] ?? null,
                'title' => $payload['title'] ?? $payload['location_name'] ?? 'Penempatan',
                'detail' => trim(($payload['location_name'] ?? '-') . ' • ' . ($payload['placement_status'] ?? '-')),
                'note' => $payload['notes'] ?? 'Belum ada catatan penempatan.',
                'location_name' => $payload['location_name'] ?? '-',
                'entered_at' => $payload['entered_at'] ?? null,
                'exited_at' => $payload['exited_at'] ?? null,
                'placement_status' => $payload['placement_status'] ?? 'Aktif',
                'notes' => $payload['notes'] ?? null,
            ];
        });

        if ($firebaseRecords->isNotEmpty()) {
            return $firebaseRecords;
        }

        return $this->resolveCollection(
            fn () => Placement::query()
                ->with('refugee')
                ->orderByDesc('entered_at')
                ->get()
                ->map(function (Placement $placement) {
                    return [
                        'id' => $placement->id,
                        'refugee_id' => $placement->refugee_id,
                        'title' => $placement->refugee?->name ?? 'Penempatan',
                        'detail' => trim(($placement->location_name ?? '-') . ' • ' . ($placement->placement_status ?? '-')),
                        'note' => $placement->notes ?? 'Belum ada catatan penempatan.',
                        'location_name' => $placement->location_name,
                        'entered_at' => optional($placement->entered_at)?->format('Y-m-d'),
                        'exited_at' => optional($placement->exited_at)?->format('Y-m-d'),
                        'placement_status' => $placement->placement_status,
                        'notes' => $placement->notes,
                    ];
                }),
            fn () => collect(Placement::sampleData())
        );
    }

    public function filteredPlacements(array $filters = []): Collection
    {
        return $this->resolveCollection(
            fn () => Placement::query()
                ->with('refugee')
                ->when(filled($filters['keyword'] ?? null), function ($query) use ($filters) {
                    $keyword = '%' . trim((string) $filters['keyword']) . '%';
                    $query->where(function ($inner) use ($keyword) {
                        $inner->where('location_name', 'like', $keyword)
                            ->orWhere('placement_status', 'like', $keyword)
                            ->orWhereHas('refugee', fn ($refugeeQuery) => $refugeeQuery->where('name', 'like', $keyword));
                    });
                })
                ->when(filled($filters['status'] ?? null), fn ($query) => $query->where('placement_status', $filters['status']))
                ->orderByDesc('entered_at')
                ->get()
                ->map(function (Placement $placement) {
                    return [
                        'id' => $placement->id,
                        'refugee_id' => $placement->refugee_id,
                        'title' => $placement->refugee?->name ?? 'Penempatan',
                        'detail' => trim(($placement->location_name ?? '-') . ' • ' . ($placement->placement_status ?? '-')),
                        'note' => $placement->notes ?? 'Belum ada catatan penempatan.',
                        'location_name' => $placement->location_name,
                        'entered_at' => optional($placement->entered_at)?->format('Y-m-d'),
                        'exited_at' => optional($placement->exited_at)?->format('Y-m-d'),
                        'placement_status' => $placement->placement_status,
                        'notes' => $placement->notes,
                    ];
                }),
            fn () => $this->placements()->filter(function (array $placement) use ($filters) {
                $keyword = strtolower(trim((string) ($filters['keyword'] ?? '')));
                $matchesKeyword = $keyword === ''
                    || str_contains(strtolower((string) ($placement['title'] ?? '')), $keyword)
                    || str_contains(strtolower((string) ($placement['location_name'] ?? '')), $keyword);
                $matchesStatus = ($filters['status'] ?? '') === '' || ($placement['placement_status'] ?? '') === ($filters['status'] ?? '');

                return $matchesKeyword && $matchesStatus;
            })->values()
        );
    }

    public function paginatedFilteredPlacements(array $filters = []): LengthAwarePaginator
    {
        return $this->paginateCollection(
            $this->filteredPlacements($filters),
            (int) ($filters['per_page'] ?? 10)
        );
    }

    public function samplePlacementByRefugeeId(int $refugeeId): ?array
    {
        return $this->placements()->firstWhere('refugee_id', $refugeeId);
    }

    public function samplePlacementById(int $id): ?array
    {
        return $this->placements()->firstWhere('id', $id);
    }

    public function documents(): Collection
    {
        $firebaseRecords = $this->resolveFirebaseCollection('documents', function (string $key, array $payload) {
            return [
                'id' => $payload['id'] ?? $key,
                'refugee_id' => $payload['refugee_id'] ?? null,
                'name' => $payload['document_type'] ?? 'Dokumen',
                'meta' => trim(($payload['refugee_name'] ?? 'Tanpa pengungsi') . ' • ' . ($payload['file_name'] ?? '-')),
                'status' => $payload['verification_status'] ?? 'Belum Lengkap',
                'storage' => $payload['file_path'] ?? '-',
                'document_type' => $payload['document_type'] ?? null,
                'file_name' => $payload['file_name'] ?? null,
                'file_path' => $payload['file_path'] ?? null,
                'drive_file_id' => $payload['firebase_document_key'] ?? $payload['drive_file_id'] ?? $key,
                'verification_status' => $payload['verification_status'] ?? null,
                'uploaded_at' => $payload['uploaded_at'] ?? null,
                'uploaded_by' => $payload['uploaded_by'] ?? null,
                'notes' => $payload['notes'] ?? null,
            ];
        });

        if ($firebaseRecords->isNotEmpty()) {
            return $firebaseRecords;
        }

        return $this->resolveCollection(
            fn () => RefugeeDocument::query()
                ->with('refugee')
                ->orderByDesc('uploaded_at')
                ->get()
                ->map(function (RefugeeDocument $document) {
                    return [
                        'id' => $document->id,
                        'refugee_id' => $document->refugee_id,
                        'name' => $document->document_type,
                        'meta' => ($document->refugee?->name ?? 'Tanpa pengungsi') . ' • ' . ($document->file_name ?? '-'),
                        'status' => $document->verification_status,
                        'storage' => $document->file_path ?? '-',
                        'document_type' => $document->document_type,
                        'file_name' => $document->file_name,
                        'file_path' => $document->file_path,
                        'drive_file_id' => $document->drive_file_id,
                        'verification_status' => $document->verification_status,
                        'uploaded_at' => optional($document->uploaded_at)?->format('Y-m-d H:i:s'),
                        'uploaded_by' => $document->uploaded_by,
                        'notes' => $document->notes,
                    ];
                }),
            fn () => collect(RefugeeDocument::sampleData())
        );
    }

    public function filteredDocuments(array $filters = []): Collection
    {
        return $this->resolveCollection(
            fn () => RefugeeDocument::query()
                ->with('refugee')
                ->when(filled($filters['keyword'] ?? null), function ($query) use ($filters) {
                    $keyword = '%' . trim((string) $filters['keyword']) . '%';
                    $query->where(function ($inner) use ($keyword) {
                        $inner->where('document_type', 'like', $keyword)
                            ->orWhere('file_name', 'like', $keyword)
                            ->orWhereHas('refugee', fn ($refugeeQuery) => $refugeeQuery->where('name', 'like', $keyword));
                    });
                })
                ->when(filled($filters['status'] ?? null), fn ($query) => $query->where('verification_status', $filters['status']))
                ->when(filled($filters['type'] ?? null), fn ($query) => $query->where('document_type', $filters['type']))
                ->orderByDesc('uploaded_at')
                ->get()
                ->map(function (RefugeeDocument $document) {
                    return [
                        'id' => $document->id,
                        'refugee_id' => $document->refugee_id,
                        'name' => $document->document_type,
                        'meta' => ($document->refugee?->name ?? 'Tanpa pengungsi') . ' • ' . ($document->file_name ?? '-'),
                        'status' => $document->verification_status,
                        'storage' => $document->file_path ?? '-',
                        'document_type' => $document->document_type,
                        'file_name' => $document->file_name,
                        'file_path' => $document->file_path,
                        'drive_file_id' => $document->drive_file_id,
                        'verification_status' => $document->verification_status,
                        'uploaded_at' => optional($document->uploaded_at)?->format('Y-m-d H:i:s'),
                        'uploaded_by' => $document->uploaded_by,
                        'notes' => $document->notes,
                    ];
                }),
            fn () => $this->documents()->filter(function (array $document) use ($filters) {
                $keyword = strtolower(trim((string) ($filters['keyword'] ?? '')));
                $matchesKeyword = $keyword === ''
                    || str_contains(strtolower((string) ($document['name'] ?? '')), $keyword)
                    || str_contains(strtolower((string) ($document['meta'] ?? '')), $keyword)
                    || str_contains(strtolower((string) ($document['file_name'] ?? '')), $keyword);
                $matchesStatus = ($filters['status'] ?? '') === '' || ($document['verification_status'] ?? $document['status'] ?? '') === ($filters['status'] ?? '');
                $matchesType = ($filters['type'] ?? '') === '' || ($document['document_type'] ?? $document['name'] ?? '') === ($filters['type'] ?? '');

                return $matchesKeyword && $matchesStatus && $matchesType;
            })->values()
        );
    }

    public function paginatedFilteredDocuments(array $filters = []): LengthAwarePaginator
    {
        return $this->paginateCollection(
            $this->filteredDocuments($filters),
            (int) ($filters['per_page'] ?? 10)
        );
    }

    public function sampleDocumentByRefugeeId(int $refugeeId): ?array
    {
        return $this->documents()->firstWhere('refugee_id', $refugeeId);
    }

    public function sampleDocumentById(int $id): ?array
    {
        return $this->documents()->firstWhere('id', $id);
    }

    public function recentActivities(): Collection
    {
        $firebaseRecords = $this->resolveFirebaseCollection('audit_trails', function (string $key, array $payload) {
            return [
                'id' => $payload['id'] ?? $key,
                'refugee_id' => $payload['refugee_id'] ?? null,
                'title' => $payload['action_label'] ?? 'Perubahan data',
                'detail' => trim(($payload['field_name'] ?? 'Perubahan data') . ' • ' . ($payload['reason'] ?? 'Tanpa alasan')),
                'actor' => $payload['performed_by_name'] ?? 'Sistem',
                'time' => $this->formatTimestampLabel($payload['performed_at'] ?? null, 'd M Y • H:i'),
                'performed_at_raw' => $payload['performed_at'] ?? null,
            ];
        })->sortByDesc(fn (array $item) => $item['performed_at_raw'] ?? '')->take(6)->values();

        if ($firebaseRecords->isNotEmpty()) {
            return $firebaseRecords->map(function (array $item) {
                unset($item['performed_at_raw']);
                return $item;
            })->values();
        }

        return $this->resolveCollection(
            fn () => AuditTrail::query()
                ->orderByDesc('performed_at')
                ->limit(6)
                ->get()
                ->map(function (AuditTrail $item) {
                    return [
                        'id' => $item->id,
                        'refugee_id' => $item->refugee_id,
                        'title' => $item->action_label,
                        'detail' => trim(($item->field_name ?? 'Perubahan data') . ' • ' . ($item->reason ?? 'Tanpa alasan')),
                        'actor' => $item->performed_by_name,
                        'time' => optional($item->performed_at)->format('d M Y • H:i') ?? '-',
                    ];
                }),
            fn () => collect(AuditTrail::recentActivity())
        );
    }

    public function history(): Collection
    {
        $firebaseRecords = $this->resolveFirebaseCollection('audit_trails', function (string $key, array $payload) {
            return [
                'id' => $payload['id'] ?? $key,
                'refugee_id' => $payload['refugee_id'] ?? null,
                'title' => $payload['action_label'] ?? 'Perubahan data',
                'detail' => trim(($payload['field_name'] ?? 'Perubahan data') . ' dari ' . ($payload['old_value'] ?? '-') . ' ke ' . ($payload['new_value'] ?? '-')),
                'actor' => $payload['performed_by_name'] ?? 'Sistem',
                'time' => $this->formatTimestampLabel($payload['performed_at'] ?? null, 'd M Y • H:i'),
                'performed_at_raw' => $payload['performed_at'] ?? null,
            ];
        })->sortByDesc(fn (array $item) => $item['performed_at_raw'] ?? '')->values();

        if ($firebaseRecords->isNotEmpty()) {
            return $firebaseRecords->map(function (array $item) {
                unset($item['performed_at_raw']);
                return $item;
            })->values();
        }

        return $this->resolveCollection(
            fn () => AuditTrail::query()
                ->orderByDesc('performed_at')
                ->get()
                ->map(function (AuditTrail $item) {
                    return [
                        'id' => $item->id,
                        'refugee_id' => $item->refugee_id,
                        'title' => $item->action_label,
                        'detail' => trim(($item->field_name ?? 'Perubahan data') . ' dari ' . ($item->old_value ?? '-') . ' ke ' . ($item->new_value ?? '-')),
                        'actor' => $item->performed_by_name,
                        'time' => optional($item->performed_at)->format('d M Y • H:i') ?? '-',
                    ];
                }),
            fn () => collect(AuditTrail::sampleHistory())
        );
    }

    public function reports(): array
    {
        return [
            ['name' => 'Rekap Data Aktif', 'note' => 'Total pengungsi aktif per lokasi, kebangsaan, dan status terbaru.'],
            ['name' => 'Laporan Dokumen', 'note' => 'Kelengkapan dokumen, daftar verifikasi, dan histori unggah.'],
            ['name' => 'Audit Trail', 'note' => 'Ringkasan perubahan data beserta pelaksana dan waktu pembaruan.'],
            ['name' => 'Prioritas Verifikasi', 'note' => 'Data yang perlu tindak lanjut supervisor dan admin.'],
        ];
    }

    public function reportLogs(): array
    {
        return [
            ['type' => 'Rekap Data Aktif', 'filters' => 'Periode Mei 2026 • Semua lokasi', 'actor' => 'Admin', 'downloaded_at' => '04 Mei 2026 • 09:10'],
            ['type' => 'Laporan Dokumen', 'filters' => 'Status Perlu Verifikasi', 'actor' => 'Supervisor', 'downloaded_at' => '03 Mei 2026 • 14:22'],
            ['type' => 'Audit Trail', 'filters' => 'Perubahan kritis 7 hari terakhir', 'actor' => 'Admin', 'downloaded_at' => '02 Mei 2026 • 16:18'],
        ];
    }

    public function placementStatusOptions(): Collection
    {
        return collect(['Aktif', 'Mutasi', 'Selesai', 'Transit']);
    }

    public function documentStatusOptions(): Collection
    {
        return collect(['Lengkap', 'Perlu Verifikasi', 'Belum Lengkap']);
    }

    protected function sortedCollection(Collection $items, string $sort, string $direction, array $sortMap): Collection
    {
        $sortKey = $sortMap[$sort] ?? reset($sortMap);
        $sorted = $items->sortBy(fn (array $item) => strtolower((string) ($item[$sortKey] ?? '')));

        return $direction === 'desc' ? $sorted->reverse()->values() : $sorted->values();
    }

    protected function paginateCollection(Collection $items, int $perPage): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = max(5, min($perPage, 20));
        $slice = $items->forPage($page, $perPage)->values();

        return new LengthAwarePaginator($slice, $items->count(), $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }

    protected function formatTimestampLabel(?string $value, string $fallbackFormat = 'd M Y, H:i'): string
    {
        if (blank($value)) {
            return '-';
        }

        try {
            return Carbon::parse($value)->translatedFormat($fallbackFormat);
        } catch (Throwable) {
            return (string) $value;
        }
    }

    public function documentTypeOptions(): Collection
    {
        return collect([
            'Identitas Utama',
            'Administrasi Internal',
            'Riwayat Penempatan',
            'Lampiran Tambahan',
        ]);
    }
}
