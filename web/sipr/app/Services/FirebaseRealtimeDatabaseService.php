<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class FirebaseRealtimeDatabaseService
{
    public function __construct(
        protected FirebaseService $firebaseService
    ) {
    }

    public function config(): array
    {
        return $this->firebaseService->config()['firebase'];
    }

    public function nodeMap(): array
    {
        return $this->config()['node_map'] ?? [];
    }

    public function path(string $node): string
    {
        return $this->config()['paths'][$node] ?? ('/' . trim($node, '/'));
    }

    public function enabled(): bool
    {
        return filled($this->config()['database_url'] ?? null)
            && filter_var(config('app.env') === 'production' ? env('SIPR_FIREBASE_READ_ENABLED', false) : env('SIPR_FIREBASE_READ_ENABLED', true), FILTER_VALIDATE_BOOL);
    }

    public function fetchNode(string $path): mixed
    {
        if (! $this->enabled()) {
            return null;
        }

        try {
            $response = Http::timeout(8)
                ->withQueryParameters($this->query())
                ->get($this->endpoint($path));

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        } catch (Throwable) {
            return null;
        }
    }

    public function fetchCollection(string $node, callable $normalizer): Collection
    {
        $snapshot = $this->fetchNode($this->path($node));

        if (! is_array($snapshot)) {
            return collect();
        }

        return collect($snapshot)
            ->map(fn ($payload, $key) => $normalizer($key, is_array($payload) ? $payload : []))
            ->filter()
            ->values();
    }

    public function putNode(string $path, array $payload): bool
    {
        return $this->write('put', $path, $payload);
    }

    public function patchNode(string $path, array $payload): bool
    {
        return $this->write('patch', $path, $payload);
    }

    public function pushNode(string $node, array $payload): bool
    {
        return $this->write('post', $this->path($node), $payload);
    }

    public function deleteNode(string $path): bool
    {
        return $this->write('delete', $path);
    }

    public function refugeeKey(array $payload, int|string|null $fallbackId = null): string
    {
        return $this->sanitizeKey($payload['internal_id'] ?? $fallbackId ?? Str::uuid()->toString());
    }

    public function documentKey(array $payload, int|string|null $fallbackId = null): string
    {
        $base = $payload['drive_file_id']
            ?? $payload['firebase_document_key']
            ?? $payload['file_name']
            ?? $payload['document_type']
            ?? $fallbackId
            ?? Str::uuid()->toString();

        return $this->sanitizeKey($base);
    }

    public function placementKey(array $payload, int|string|null $fallbackId = null): string
    {
        $base = ($payload['refugee_id'] ?? 'refugee') . '-' . ($payload['location_name'] ?? $fallbackId ?? Str::uuid()->toString());

        return $this->sanitizeKey($base);
    }

    public function syncRefugee(array $payload, int|string|null $fallbackId = null): bool
    {
        $key = $this->refugeeKey($payload, $fallbackId);

        return $this->putNode($this->path('refugees') . '/' . $key, [
            'internal_id' => $payload['internal_id'] ?? $key,
            'name' => $payload['name'] ?? null,
            'nationality' => $payload['nationality'] ?? null,
            'unhcr_number' => $payload['unhcr_number'] ?? null,
            'status' => $payload['status'] ?? null,
            'location' => $payload['location'] ?? null,
            'document_status' => $payload['document_status'] ?? ($payload['verification_status'] ?? null),
            'notes' => $payload['notes'] ?? null,
            'registered_at' => $payload['registered_at'] ?? null,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function deleteRefugee(array $payload, int|string|null $fallbackId = null): bool
    {
        return $this->deleteNode($this->path('refugees') . '/' . $this->refugeeKey($payload, $fallbackId));
    }

    public function syncPlacement(array $payload, int|string|null $fallbackId = null): bool
    {
        $key = $this->placementKey($payload, $fallbackId);

        return $this->putNode($this->path('placements') . '/' . $key, [
            'refugee_id' => $payload['refugee_id'] ?? null,
            'location_name' => $payload['location_name'] ?? null,
            'entered_at' => $payload['entered_at'] ?? null,
            'exited_at' => $payload['exited_at'] ?? null,
            'placement_status' => $payload['placement_status'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function deletePlacement(array $payload, int|string|null $fallbackId = null): bool
    {
        return $this->deleteNode($this->path('placements') . '/' . $this->placementKey($payload, $fallbackId));
    }

    public function syncDocument(array $payload, int|string|null $fallbackId = null): bool
    {
        $key = $this->documentKey($payload, $fallbackId);

        return $this->putNode($this->path('documents') . '/' . $key, [
            'refugee_id' => $payload['refugee_id'] ?? null,
            'document_type' => $payload['document_type'] ?? null,
            'file_name' => $payload['file_name'] ?? null,
            'file_path' => $payload['file_path'] ?? null,
            'firebase_document_key' => $key,
            'verification_status' => $payload['verification_status'] ?? null,
            'uploaded_at' => $payload['uploaded_at'] ?? null,
            'uploaded_by' => $payload['uploaded_by'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function deleteDocument(array $payload, int|string|null $fallbackId = null): bool
    {
        return $this->deleteNode($this->path('documents') . '/' . $this->documentKey($payload, $fallbackId));
    }

    public function pushAuditTrail(array $payload): bool
    {
        return $this->pushNode('audit_trails', [
            'refugee_id' => $payload['refugee_id'] ?? null,
            'field_name' => $payload['field_name'] ?? null,
            'old_value' => $payload['old_value'] ?? null,
            'new_value' => $payload['new_value'] ?? null,
            'action_label' => $payload['action_label'] ?? 'Perubahan data',
            'performed_by_name' => $payload['performed_by_name'] ?? 'Sistem',
            'reason' => $payload['reason'] ?? null,
            'performed_at' => $payload['performed_at'] ?? now()->toIso8601String(),
        ]);
    }

    protected function endpoint(string $path): string
    {
        $base = rtrim($this->config()['database_url'], '/');
        $trimmed = trim($path, '/');

        return $trimmed === '' ? $base . '/.json' : $base . '/' . $trimmed . '.json';
    }

    protected function query(): array
    {
        $secret = $this->config()['database_secret'] ?? null;

        return filled($secret) ? ['auth' => $secret] : [];
    }

    protected function write(string $method, string $path, ?array $payload = null): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        try {
            $request = Http::timeout(8)->withQueryParameters($this->query());
            $response = match ($method) {
                'put' => $request->put($this->endpoint($path), $payload ?? []),
                'patch' => $request->patch($this->endpoint($path), $payload ?? []),
                'post' => $request->post($this->endpoint($path), $payload ?? []),
                'delete' => $request->delete($this->endpoint($path)),
                default => null,
            };

            return $response !== null && $response->successful();
        } catch (Throwable) {
            return false;
        }
    }

    protected function sanitizeKey(string|int $value): string
    {
        $normalized = Str::of((string) $value)
            ->lower()
            ->replaceMatches('/[.#$\\[\\]\\/]/', '-')
            ->replace(' ', '-')
            ->trim('-');

        return $normalized->isNotEmpty() ? (string) $normalized : (string) Str::uuid();
    }
}
