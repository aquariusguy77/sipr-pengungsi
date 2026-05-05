<?php

namespace App\Services;

class FirebaseService
{
    public function appConfig(): array
    {
        return [
            'auth' => [
                'login_mode' => env('SIPR_LOGIN_MODE', 'hybrid'),
                'demo_enabled' => filter_var(env('SIPR_DEMO_LOGIN_ENABLED', true), FILTER_VALIDATE_BOOL),
                'laravel_auth_enabled' => filter_var(env('SIPR_LARAVEL_AUTH_ENABLED', true), FILTER_VALIDATE_BOOL),
            ],
            'data' => [
                'sample_data_enabled' => filter_var(env('SIPR_SAMPLE_DATA_ENABLED', true), FILTER_VALIDATE_BOOL),
                'firebase_read_enabled' => filter_var(env('SIPR_FIREBASE_READ_ENABLED', true), FILTER_VALIDATE_BOOL),
            ],
        ];
    }

    public function config(): array
    {
        return [
            'app' => $this->appConfig(),
            'firebase' => [
                'database_url' => env('FIREBASE_DATABASE_URL', 'https://ralf-803d6-default-rtdb.asia-southeast1.firebasedatabase.app/'),
                'project_id' => 'ralf-803d6',
                'api_key' => env('FIREBASE_API_KEY', 'firebase-web-api-key-placeholder'),
                'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'ralf-803d6.firebaseapp.com'),
                'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'ralf-803d6.firebasestorage.app'),
                'storage_disk' => env('FIREBASE_STORAGE_DISK', 'local'),
                'storage_prefix' => env('FIREBASE_STORAGE_PREFIX', 'documents'),
                'storage_public_base_url' => env('FIREBASE_STORAGE_PUBLIC_BASE_URL'),
                'storage_bearer_token' => env('FIREBASE_STORAGE_BEARER_TOKEN'),
                'database_secret' => env('FIREBASE_DATABASE_SECRET'),
                'paths' => [
                    'refugees' => '/refugees',
                    'documents' => '/documents',
                    'placements' => '/placements',
                    'audit_trails' => '/audit_trails',
                    'reports' => '/reports',
                    'users' => '/users',
                ],
                'node_map' => [
                    'refugees' => [
                        'path' => '/refugees',
                        'fields' => ['internal_id', 'name', 'nationality', 'unhcr_number', 'status', 'location', 'document_status', 'notes', 'registered_at', 'updated_at'],
                    ],
                    'documents' => [
                        'path' => '/documents',
                        'fields' => ['refugee_id', 'document_type', 'file_name', 'file_path', 'firebase_document_key', 'verification_status', 'uploaded_at', 'uploaded_by', 'notes'],
                    ],
                    'placements' => [
                        'path' => '/placements',
                        'fields' => ['refugee_id', 'location_name', 'entered_at', 'exited_at', 'placement_status', 'notes'],
                    ],
                    'audit_trails' => [
                        'path' => '/audit_trails',
                        'fields' => ['refugee_id', 'field_name', 'old_value', 'new_value', 'action_label', 'performed_by_name', 'reason', 'performed_at'],
                    ],
                    'reports' => [
                        'path' => '/reports',
                        'fields' => ['name', 'note', 'filters', 'downloaded_at', 'downloaded_by'],
                    ],
                    'users' => [
                        'path' => '/users',
                        'fields' => ['name', 'role', 'email', 'status'],
                    ],
                ],
            ],
        ];
    }
}
