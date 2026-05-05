<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefugeeFilterRequest;
use App\Http\Requests\RefugeeUpsertRequest;
use App\Models\Refugee;
use App\Services\FirebaseRealtimeDatabaseService;
use App\Services\SiprDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RefugeeController extends Controller
{
    public function __construct(
        protected SiprDataService $siprDataService,
        protected FirebaseRealtimeDatabaseService $firebaseDatabase
    ) {
    }

    public function index(RefugeeFilterRequest $request)
    {
        $filters = $request->filters();
        $refugees = $this->siprDataService->paginatedFilteredRefugees($filters);

        return view('refugees.index', array_merge($this->baseViewData(), [
            'pageHeading' => 'Data Pengungsi',
            'pageDescription' => 'Pencarian, filter, dan daftar operasional data pengungsi untuk petugas, admin, dan supervisor.',
            'refugees' => $refugees,
            'filterOptions' => $this->siprDataService->refugeeFilterOptions(),
            'activeFilters' => $filters,
        ]));
    }

    public function create(): View
    {
        $this->ensureAbility('manage-refugees');

        return view('refugees.create', array_merge($this->baseViewData(), $this->formViewData([
            'pageHeading' => 'Tambah Data Pengungsi',
            'pageDescription' => 'Form awal untuk identitas, status, lokasi aktif, dan catatan registrasi.',
            'refugee' => new Refugee(),
            'formAction' => route('refugees.store'),
            'formMethod' => 'POST',
        ])));
    }

    public function show(Refugee $refugee): View
    {
        return view('refugees.show', array_merge($this->baseViewData(), [
            'pageHeading' => 'Detail Pengungsi',
            'pageDescription' => 'Profil ringkas, penempatan, dokumen, dan riwayat perubahan untuk satu data pengungsi.',
            'refugee' => $refugee,
            'placement' => $this->siprDataService->samplePlacementByRefugeeId((int) $refugee->id),
            'document' => $this->siprDataService->sampleDocumentByRefugeeId((int) $refugee->id),
            'history' => collect($this->siprDataService->history())->where('refugee_id', (int) $refugee->id)->values(),
        ]));
    }

    public function store(RefugeeUpsertRequest $request): RedirectResponse
    {
        $this->ensureAbility('manage-refugees');

        $refugee = Refugee::query()->create($request->payload());
        $this->firebaseDatabase->syncRefugee(array_merge($request->payload(), ['id' => $refugee->id]), $refugee->id);
        $this->firebaseDatabase->pushAuditTrail([
            'refugee_id' => $refugee->id,
            'field_name' => 'refugee',
            'new_value' => $refugee->internal_id,
            'action_label' => 'Data pengungsi ditambahkan',
            'performed_by_name' => $this->currentActorName(),
            'reason' => 'Input data baru',
        ]);

        return redirect()
            ->route('refugees.index')
            ->with('status', 'Data pengungsi berhasil ditambahkan.');
    }

    public function edit(Refugee $refugee): View
    {
        $this->ensureAbility('manage-refugees');

        return view('refugees.edit', array_merge($this->baseViewData(), $this->formViewData([
            'pageHeading' => 'Ubah Data Pengungsi',
            'pageDescription' => 'Perbarui identitas, status, lokasi aktif, dan catatan registrasi.',
            'refugee' => $refugee,
            'formAction' => route('refugees.update', $refugee),
            'formMethod' => 'PUT',
        ])));
    }

    public function update(RefugeeUpsertRequest $request, Refugee $refugee): RedirectResponse
    {
        $this->ensureAbility('manage-refugees');

        $original = $refugee->getOriginal();
        $refugee->update($request->payload());
        $this->firebaseDatabase->syncRefugee(array_merge($request->payload(), ['id' => $refugee->id]), $refugee->id);
        $this->firebaseDatabase->pushAuditTrail([
            'refugee_id' => $refugee->id,
            'field_name' => 'refugee',
            'old_value' => $original['internal_id'] ?? null,
            'new_value' => $refugee->internal_id,
            'action_label' => 'Data pengungsi diperbarui',
            'performed_by_name' => $this->currentActorName(),
            'reason' => 'Pembaruan data operasional',
        ]);

        return redirect()
            ->route('refugees.index')
            ->with('status', 'Data pengungsi berhasil diperbarui.');
    }

    public function destroy(Refugee $refugee): RedirectResponse
    {
        $this->ensureAbility('full-access');

        $payload = $refugee->toArray();
        $refugee->delete();
        $this->firebaseDatabase->deleteRefugee($payload, $payload['id'] ?? null);
        $this->firebaseDatabase->pushAuditTrail([
            'refugee_id' => $payload['id'] ?? null,
            'field_name' => 'refugee',
            'old_value' => $payload['internal_id'] ?? null,
            'action_label' => 'Data pengungsi dihapus',
            'performed_by_name' => $this->currentActorName(),
            'reason' => 'Penghapusan data',
        ]);

        return redirect()
            ->route('refugees.index')
            ->with('status', 'Data pengungsi berhasil dihapus.');
    }

    protected function formViewData(array $overrides = []): array
    {
        $filterOptions = $this->siprDataService->refugeeFilterOptions();

        return array_merge([
            'statusOptions' => $filterOptions['statuses'],
            'nationalityOptions' => $filterOptions['nationalities'],
            'locationOptions' => $filterOptions['locations'],
            'documentStatusOptions' => $filterOptions['documentStatuses'],
            'backendEndpoints' => [
                'store' => route('refugees.store'),
                'update' => route('refugees.index') . '/{id}',
                'destroy' => route('refugees.index') . '/{id}',
            ],
        ], $overrides);
    }
}
