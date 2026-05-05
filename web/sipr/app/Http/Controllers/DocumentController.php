<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefugeeDocumentUpsertRequest;
use App\Models\RefugeeDocument;
use App\Services\FirebaseRealtimeDatabaseService;
use App\Services\FirebaseService;
use App\Services\FirebaseStorageService;
use App\Services\SiprDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class DocumentController extends Controller
{
    public function __construct(
        protected SiprDataService $siprDataService,
        protected FirebaseService $firebaseService,
        protected FirebaseRealtimeDatabaseService $firebaseDatabase,
        protected FirebaseStorageService $firebaseStorage
    ) {
    }

    public function index()
    {
        $filters = [
            'keyword' => trim((string) request('keyword', '')),
            'status' => (string) request('status', ''),
            'type' => (string) request('type', ''),
            'per_page' => max(5, min((int) request('per_page', 10), 20)),
        ];
        $documents = $this->siprDataService->paginatedFilteredDocuments($filters);

        return view('documents.index', array_merge($this->baseViewData(), [
            'pageHeading' => 'Dokumen',
            'pageDescription' => 'Status verifikasi, kategori berkas, dan placeholder penyimpanan dokumen.',
            'documents' => $documents,
            'integrationConfig' => $this->firebaseService->config(),
            'activeFilters' => $filters,
            'statusOptions' => $this->siprDataService->documentStatusOptions(),
            'documentTypes' => $this->siprDataService->documentTypeOptions(),
        ]));
    }

    public function create()
    {
        $this->ensureAbility('manage-documents');

        return view('documents.create', array_merge($this->baseViewData(), [
            'pageHeading' => 'Tambah Dokumen',
            'pageDescription' => 'Input metadata dokumen pengungsi dan status verifikasinya.',
            'document' => new RefugeeDocument(),
            'formAction' => route('documents.store'),
            'formMethod' => 'POST',
            'refugees' => $this->siprDataService->refugeeSelectOptions(),
            'documentTypes' => $this->siprDataService->documentTypeOptions(),
            'statusOptions' => $this->siprDataService->documentStatusOptions(),
        ]));
    }

    public function show(RefugeeDocument $document)
    {
        return view('documents.show', array_merge($this->baseViewData(), [
            'pageHeading' => 'Detail Dokumen',
            'pageDescription' => 'Informasi file, status verifikasi, penyimpanan, dan keterkaitan pengungsi.',
            'document' => $document,
            'documentView' => $this->siprDataService->sampleDocumentById((int) $document->id),
            'refugeeView' => $this->siprDataService->sampleRefugeeById((int) $document->refugee_id),
            'integrationConfig' => $this->firebaseService->config(),
        ]));
    }

    public function store(RefugeeDocumentUpsertRequest $request): RedirectResponse
    {
        $this->ensureAbility('manage-documents');

        $payload = $this->resolveDocumentPayload($request);
        $document = RefugeeDocument::query()->create($payload);
        $this->firebaseDatabase->syncDocument(array_merge($payload, ['id' => $document->id]), $document->id);
        $this->firebaseDatabase->pushAuditTrail([
            'refugee_id' => $document->refugee_id,
            'field_name' => 'document',
            'new_value' => $document->document_type,
            'action_label' => 'Dokumen ditambahkan',
            'performed_by_name' => $this->currentActorName(),
            'reason' => 'Unggah dokumen baru',
        ]);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Data dokumen berhasil ditambahkan.');
    }

    public function edit(RefugeeDocument $document)
    {
        $this->ensureAbility('manage-documents');

        return view('documents.edit', array_merge($this->baseViewData(), [
            'pageHeading' => 'Ubah Dokumen',
            'pageDescription' => 'Perbarui metadata, lokasi file, dan status verifikasi dokumen.',
            'document' => $document,
            'formAction' => route('documents.update', $document),
            'formMethod' => 'PUT',
            'refugees' => $this->siprDataService->refugeeSelectOptions(),
            'documentTypes' => $this->siprDataService->documentTypeOptions(),
            'statusOptions' => $this->siprDataService->documentStatusOptions(),
        ]));
    }

    public function update(RefugeeDocumentUpsertRequest $request, RefugeeDocument $document): RedirectResponse
    {
        $this->ensureAbility('manage-documents');

        $original = $document->getOriginal();
        $payload = $this->resolveDocumentPayload($request, $document);
        $document->update($payload);
        $this->firebaseDatabase->syncDocument(array_merge($payload, ['id' => $document->id]), $document->id);
        $this->firebaseDatabase->pushAuditTrail([
            'refugee_id' => $document->refugee_id,
            'field_name' => 'document',
            'old_value' => $original['document_type'] ?? null,
            'new_value' => $document->document_type,
            'action_label' => 'Dokumen diperbarui',
            'performed_by_name' => $this->currentActorName(),
            'reason' => 'Pembaruan metadata dokumen',
        ]);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Data dokumen berhasil diperbarui.');
    }

    public function destroy(RefugeeDocument $document): RedirectResponse
    {
        $this->ensureAbility('full-access');

        $payload = $document->toArray();
        $document->delete();
        $this->firebaseDatabase->deleteDocument($payload, $payload['id'] ?? null);
        $this->firebaseDatabase->pushAuditTrail([
            'refugee_id' => $payload['refugee_id'] ?? null,
            'field_name' => 'document',
            'old_value' => $payload['document_type'] ?? null,
            'action_label' => 'Dokumen dihapus',
            'performed_by_name' => $this->currentActorName(),
            'reason' => 'Penghapusan dokumen',
        ]);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Data dokumen berhasil dihapus.');
    }

    protected function resolveDocumentPayload(RefugeeDocumentUpsertRequest $request, ?RefugeeDocument $document = null): array
    {
        $payload = $request->payload();
        $uploadedFile = $request->file('uploaded_file');

        if ($uploadedFile instanceof UploadedFile) {
            $stored = $this->firebaseStorage->storeDocument($uploadedFile, (string) ($payload['document_type'] ?? 'dokumen'));
            $payload['file_name'] = $uploadedFile->getClientOriginalName();
            $payload['file_path'] = $stored['path'];
            $payload['drive_file_id'] = $payload['drive_file_id'] ?: $stored['firebase_document_key'];
        } elseif ($document !== null) {
            $payload['file_name'] = $payload['file_name'] ?: $document->file_name;
            $payload['file_path'] = $payload['file_path'] ?: $document->file_path;
            $payload['drive_file_id'] = $payload['drive_file_id'] ?: $document->drive_file_id;
        }

        return $payload;
    }
}
