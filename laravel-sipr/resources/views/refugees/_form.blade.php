@php
    $selectedStatus = old('status', $refugee->status ?? '');
    $selectedNationality = old('nationality', $refugee->nationality ?? '');
    $selectedLocation = old('location', $refugee->location ?? '');
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <section class="panel">
        @if ($errors->any())
            <div class="subtle-box" style="margin-top:0;margin-bottom:18px;border-style:solid;border-color:rgba(217,83,79,.24);background:linear-gradient(180deg,#fff8f8 0%,#fff 100%);">
                <h4 style="color:var(--danger);">Periksa kembali data pengungsi</h4>
                <ul style="color:var(--danger);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="subtle-box" style="margin-top:0;margin-bottom:18px;border-style:solid;border-color:rgba(31,157,122,.25);">
                {{ session('status') }}
            </div>
        @endif

        <div class="section-head">
            <div>
                <span class="section-tag"><x-icon name="dashboard" class="chip-icon" />Form Data Pengungsi</span>
                <h3>Wizard input 4 langkah</h3>
                <p class="section-intro">Form ini sekarang dibagi menjadi empat tahap: identitas, administrasi, penempatan, dan dokumen.</p>
            </div>
            <span class="badge">{{ $formMethod === 'POST' ? 'Mode create' : 'Mode edit' }}</span>
        </div>

        @include('refugees._wizard_tabs')
        @include('refugees._step_identitas')
        @include('refugees._step_administrasi')
        @include('refugees._step_penempatan')
        @include('refugees._step_dokumen')

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
            <button type="button" class="control wizard-prev" style="display:grid;place-items:center;">Kembali</button>
            <button type="button" class="control wizard-next" style="cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--green));color:#fff;border:none;">Lanjut</button>
            <button class="control wizard-submit" type="submit" style="display:none;cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--green));color:#fff;border:none;">Simpan Data</button>
            <a class="control" href="{{ route('refugees.index') }}" style="display:grid;place-items:center;">Batal</a>
        </div>
    </section>
</form>

<script>
    (() => {
        const tabs = Array.from(document.querySelectorAll('.wizard-tab'));
        const panels = Array.from(document.querySelectorAll('.wizard-panel'));
        const prevButton = document.querySelector('.wizard-prev');
        const nextButton = document.querySelector('.wizard-next');
        const submitButton = document.querySelector('.wizard-submit');
        let currentStep = 1;

        const renderStep = () => {
            tabs.forEach((tab, index) => {
                tab.classList.toggle('active', index + 1 === currentStep);
            });

            panels.forEach((panel) => {
                const active = Number(panel.dataset.stepPanel) === currentStep;
                panel.style.display = active ? 'block' : 'none';
                panel.classList.toggle('active', active);
            });

            if (prevButton) {
                prevButton.style.display = currentStep === 1 ? 'none' : 'grid';
            }

            if (nextButton) {
                nextButton.style.display = currentStep === 4 ? 'none' : 'inline-flex';
            }

            if (submitButton) {
                submitButton.style.display = currentStep === 4 ? 'inline-flex' : 'none';
            }
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                currentStep = Number(tab.dataset.step);
                renderStep();
            });
        });

        prevButton?.addEventListener('click', () => {
            currentStep = Math.max(1, currentStep - 1);
            renderStep();
        });

        nextButton?.addEventListener('click', () => {
            currentStep = Math.min(4, currentStep + 1);
            renderStep();
        });

        renderStep();
    })();
</script>
