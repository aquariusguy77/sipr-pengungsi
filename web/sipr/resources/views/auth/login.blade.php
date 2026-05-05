@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="subtle-box" style="margin-top:0;margin-bottom:16px;border-style:solid;border-color:rgba(31,157,122,.25);">
            {{ session('status') }}
        </div>
    @endif

    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow"><x-icon name="shield" class="chip-icon" />Login SIPR</span>
            <h3>Masuk dengan akun Laravel atau sesi demo sesuai tahap implementasi proyek.</h3>
            <p>Mode akun Laravel dipakai saat tabel pengguna sudah aktif. Mode demo tetap tersedia untuk preview antarmuka, uji role, dan handoff awal tanpa mengubah alur halaman utama.</p>
        </div>
        <div class="hero-side">
            <div class="highlight-card">
                <div class="highlight-head">
                    <strong>Mode</strong>
                    <span class="mini-badge success">Role-ready</span>
                </div>
                <p>Pilih mode masuk yang sesuai. Jika akun Laravel belum tersedia, login demo tetap bisa dipakai untuk melihat pembatasan per role.</p>
            </div>
        </div>
    </section>

    <section class="panel" style="margin-top:24px;">
        @if ($errors->any())
            <div class="subtle-box" style="margin-top:0;margin-bottom:18px;border-style:solid;border-color:rgba(217,83,79,.24);background:linear-gradient(180deg,#fff8f8 0%,#fff 100%);">
                <h4 style="color:var(--danger);">Periksa kembali data login</h4>
                <ul style="color:var(--danger);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="double-grid" style="margin-top:0;">
                <div>
                    <label class="table-meta">Mode Login</label>
                    <select class="control" name="login_mode" required>
                        @foreach ($authModes as $key => $mode)
                            <option value="{{ $key }}" @selected(old('login_mode', $defaultAuthMode) === $key)>{{ $mode['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="table-meta">Nama</label>
                    <input class="control" type="text" name="name" value="{{ old('name', 'Supervisor Shift') }}">
                </div>
                <div>
                    <label class="table-meta">Email</label>
                    <input class="control" type="email" name="email" value="{{ old('email', 'sipr-demo@rudenim.local') }}">
                </div>
                <div>
                    <label class="table-meta">Password Laravel</label>
                    <input class="control" type="password" name="password" placeholder="Isi saat memakai akun Laravel">
                </div>
                <div>
                    <label class="table-meta">Peran</label>
                    <select class="control" name="role">
                        @foreach ($roles as $key => $role)
                            <option value="{{ $key }}" @selected(old('role', 'supervisor') === $key)>{{ $role['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="subtle-box">
                <h4>Petunjuk singkat</h4>
                <ul>
                    <li><strong>Login Demo</strong> memakai nama, email opsional, dan pilihan role.</li>
                    <li><strong>Akun Laravel</strong> memakai email dan password dari tabel <code>users</code>.</li>
                    <li>Saat mode akun Laravel berhasil, role aktif dibaca dari kolom <code>role</code> pengguna.</li>
                </ul>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                <button class="control" type="submit" style="cursor:pointer;background:linear-gradient(135deg,var(--blue),var(--green));color:#fff;border:none;">Masuk</button>
                <a class="control" href="{{ route('login') }}" style="display:grid;place-items:center;">Reset Form</a>
            </div>
        </form>
    </section>
@endsection
