<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class FirebaseStorageService
{
    public function __construct(
        protected FirebaseService $firebaseService
    ) {
    }

    public function config(): array
    {
        return $this->firebaseService->config()['firebase'];
    }

    public function storeDocument(UploadedFile $file, string $documentType): array
    {
        $safeType = Str::of($documentType)->slug('-')->value() ?: 'dokumen';
        $safeName = now()->format('YmdHis') . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $config = $this->config();
        $disk = $config['storage_disk'] ?? 'local';
        $prefix = trim((string) ($config['storage_prefix'] ?? 'documents'), '/');
        $fullName = $safeName . ($extension ? '.' . $extension : '');
        $path = $prefix . '/' . $safeType . '/' . $fullName;

        if ($disk === 'firebase-rest') {
            $uploaded = $this->storeViaFirebaseRest($file, $path);

            if ($uploaded !== null) {
                return [
                    'disk' => $disk,
                    'path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'firebase_document_key' => $this->documentKey($safeType, $fullName),
                    'preview_url' => $this->previewUrl($path),
                    'remote_response' => $uploaded,
                ];
            }
        } else {
            $file->storeAs($prefix . '/' . $safeType, $fullName, $disk);
        }

        return [
            'disk' => $disk,
            'path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'firebase_document_key' => $this->documentKey($safeType, $fullName),
            'preview_url' => $this->previewUrl($path),
        ];
    }

    public function documentKey(string $documentType, string $fileName): string
    {
        return Str::of($documentType . '-' . $fileName)
            ->lower()
            ->replaceMatches('/[^a-z0-9.\-_]+/', '-')
            ->trim('-')
            ->value() ?: ('document-' . Str::uuid());
    }

    public function previewUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $config = $this->config();
        $baseUrl = rtrim((string) ($config['storage_public_base_url'] ?? ''), '/');
        $bucket = trim((string) ($config['storage_bucket'] ?? ''), '/');

        if ($baseUrl === '') {
            return $bucket !== '' ? 'gs://' . $bucket . '/' . ltrim((string) $path, '/') : null;
        }

        return $baseUrl . '/' . ltrim((string) $path, '/');
    }

    protected function storeViaFirebaseRest(UploadedFile $file, string $path): ?array
    {
        $config = $this->config();
        $bucket = trim((string) ($config['storage_bucket'] ?? ''), '/');
        $token = (string) ($config['storage_bearer_token'] ?? '');

        if ($bucket === '' || $token === '') {
            return null;
        }

        $endpoint = 'https://storage.googleapis.com/upload/storage/v1/b/' . $bucket . '/o';

        try {
            $response = Http::timeout(20)
                ->withToken($token)
                ->withHeaders(['Content-Type' => $file->getMimeType() ?: 'application/octet-stream'])
                ->withBody(file_get_contents($file->getRealPath()) ?: '', $file->getMimeType() ?: 'application/octet-stream')
                ->post($endpoint . '?uploadType=media&name=' . urlencode($path));

            return $response->successful() ? ($response->json() ?: []) : null;
        } catch (Throwable) {
            return null;
        }
    }
}
