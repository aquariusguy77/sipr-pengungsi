SIPR Rudenim Surabaya - Paket Laravel

Ringkasan
- Paket ini berisi fondasi frontend dan backend ringan untuk Sistem Informasi Pendataan Pengungsi Rudenim Surabaya.
- Fokus saat ini mencakup Dashboard, Data Pengungsi, Penempatan, Dokumen, Riwayat Perubahan, Laporan, dan Pengaturan.
- Struktur sudah disiapkan agar mudah diteruskan ke developer Laravel berikutnya tanpa mengubah pola navigasi utama.
- Navigasi utama tetap mengikuti spesifikasi SIPR: Dashboard, Data Pengungsi, Penempatan, Dokumen, Riwayat Perubahan, Laporan, dan Pengaturan.

Isi Paket
- routes web
- controller per modul
- request validation
- model, policy, provider, service
- migration, seeder, factory
- Blade views utama, daftar, form, dan detail
- layout Blade modular untuk dashboard SIPR
- halaman login demo untuk simulasi role berbasis session
- stub registrasi middleware untuk proyek target
- konfigurasi environment contoh untuk mode storage lokal atau Firebase REST
- catatan integrasi drop-in per folder untuk proyek Laravel target
- profil environment siap pakai untuk preview dan produksi
- manifest isi arsip pecahan untuk proses merge bertahap

Fitur yang Sudah Siap
- Dashboard operasional tanpa bagian aksi cepat, tetap sesuai spesifikasi.
- CRUD dasar untuk Data Pengungsi.
- CRUD dasar untuk Penempatan.
- CRUD dasar untuk Dokumen.
- Validasi input lebih ketat pada modul Pengungsi, Penempatan, dan Dokumen.
- Seeder demo dengan data pengungsi, penempatan, dokumen, audit trail, dan laporan.
- Role aktif demo dapat dibaca dari user login Laravel bila tersedia, atau fallback ke environment.
- Halaman login demo dapat menyimpan role aktif ke session untuk uji akses cepat.
- Fondasi upload dokumen sudah dipisah ke service penyimpanan agar mudah diarahkan ke Firebase Storage.
- Integrasi Firebase Realtime Database dan Firebase Storage masih berupa placeholder best-effort.
- Route penting sudah memakai pembatasan akses berbasis middleware role.
- Halaman utama kini mewajibkan sesi login demo atau auth Laravel sebelum modul dibuka.
- Tombol dan menu penting kini disembunyikan sesuai role agar UI sejalan dengan guard route.
- Login sekarang mendukung mode hybrid: akun Laravel atau sesi demo.
- Sample data dan pembacaan Firebase kini bisa dimatikan lewat environment agar mode produksi lebih bersih.

Modul yang Perlu Diuji
- Dashboard:
  menampilkan statistik, aktivitas terbaru, status integrasi, dan sambungan ke CRUD pengungsi.
- Data Pengungsi:
  daftar, filter, detail, tambah, edit, hapus, dan wizard form 4 langkah.
- Penempatan:
  daftar, detail, tambah, edit, hapus, dan validasi tanggal/status.
- Dokumen:
  daftar, detail, tambah, edit, hapus, dan validasi metadata file/status verifikasi.
- Riwayat Perubahan:
  daftar audit trail dan status perubahan penting oleh petugas maupun supervisor.
- Laporan:
  rekap operasional, prioritas verifikasi, placeholder ekspor, dan log unduhan.
- Pengaturan:
  hak akses dan akun, konfigurasi integrasi, keamanan, dan informasi sistem.

Langkah Instalasi Saat Environment PHP Tersedia
1. Salin isi folder ini ke project Laravel target.
2. Salin atau merge folder `app`, `database`, `resources/views`, dan `routes` ke project Laravel target.
3. Pastikan konfigurasi database Laravel sudah aktif.
4. Pastikan model `User` proyek memakai kolom `role` atau gunakan migration tambahan yang disertakan paket ini.
5. Jalankan composer install bila project baru.
6. Siapkan file environment berdasarkan .env.example paket ini.
7. Secara default `SIPR_ACTIVE_ROLE` dibiarkan kosong agar modul meminta login demo atau auth Laravel.
8. Pilih `FIREBASE_STORAGE_DISK=local` bila ingin mulai dari upload lokal, atau ganti ke `firebase-rest` saat bearer token Firebase Storage sudah siap.
9. Jalankan php artisan migrate --seed.
10. Pastikan App\Providers\AuthServiceProvider terdaftar.
11. Bila proyek target ingin alias middleware yang lebih rapi, ikuti panduan di file bootstrap-middleware.stub.txt.
12. Jalankan php artisan serve.
13. Gunakan file INSTALLATION-CHECKLIST.txt sebagai daftar cek saat handoff ke tim implementasi.
14. Gunakan file DROP-IN-INTEGRATION-NOTES.txt untuk merge per folder ke project target.
15. Gunakan `ENV-PROFILE-PREVIEW.txt` atau `ENV-PROFILE-PRODUCTION.txt` sebagai acuan saklar environment cepat.
16. Gunakan `EXPORT-MANIFEST.txt` bila Anda memakai arsip pecahan hasil ekspor.

Uji Coba Cepat
1. Buka dashboard.
2. Buka halaman login demo dan mulai sesi sebagai Admin, Petugas Pendataan, lalu Supervisor secara bergantian.
3. Masuk ke menu Data Pengungsi lalu coba tambah data baru dengan format ID seperti RDS-24036.
4. Ubah salah satu data pengungsi lalu cek flash message setelah simpan.
5. Hapus satu data pengungsi lalu pastikan konfirmasi tampil saat role Admin aktif.
6. Masuk ke menu Penempatan lalu coba tambah dan ubah data.
7. Masuk ke menu Dokumen lalu coba tambah dan ubah metadata dokumen.
8. Buka Riwayat Perubahan dan Laporan untuk memastikan akses Supervisor berjalan.
9. Buka Pengaturan untuk memastikan hanya Admin yang bisa masuk.
10. Coba kirim form yang tidak valid untuk memastikan error summary muncul.
11. Isi `SIPR_ACTIVE_ROLE` di environment bila ingin simulasi role cepat tanpa session login.

Catatan Teknis
- Saat database belum siap, beberapa service masih punya fallback sample data.
- Menu Pengguna tidak dibuat sebagai navigasi utama.
- Kebutuhan akun dan hak akses tetap ditempatkan di Pengaturan.
- Entry view modular tambahan ada di resources/views/sipr/dashboard.blade.php.
- Layout utama modular ada di resources/views/layouts/sipr.blade.php.

Pemetaan Firebase RTDB
- /refugees:
  internal_id, name, nationality, unhcr_number, status, location, document_status, notes, registered_at, updated_at
- /documents:
  refugee_id, document_type, file_name, file_path, firebase_document_key, verification_status, uploaded_at, uploaded_by, notes
- /placements:
  refugee_id, location_name, entered_at, exited_at, placement_status, notes
- /audit_trails:
  refugee_id, field_name, old_value, new_value, action_label, performed_by_name, reason, performed_at
- /reports:
  name, note, filters, downloaded_at, downloaded_by
- /users:
  name, role, email, status

Variabel Environment yang Disarankan
- APP_NAME
- APP_URL
- DB_CONNECTION
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- FIREBASE_DATABASE_URL
- FIREBASE_API_KEY
- FIREBASE_AUTH_DOMAIN
- FIREBASE_STORAGE_BUCKET
- FIREBASE_STORAGE_DISK
- FIREBASE_STORAGE_PREFIX
- FIREBASE_STORAGE_PUBLIC_BASE_URL
- FIREBASE_STORAGE_BEARER_TOKEN
- FIREBASE_DATABASE_SECRET
- SIPR_ACTIVE_ROLE
- SIPR_LOGIN_MODE
- SIPR_DEMO_LOGIN_ENABLED
- SIPR_LARAVEL_AUTH_ENABLED
- SIPR_SAMPLE_DATA_ENABLED
- SIPR_FIREBASE_READ_ENABLED

Catatan Handoff
- CRUD Pengungsi sudah paling matang dan bisa menjadi pola acuan untuk modul lain.
- Penempatan dan Dokumen sudah mengikuti pola konfirmasi hapus, flash message, dan error summary yang seragam.
- Role aktif demo saat ini bisa disimulasikan lewat variabel `SIPR_ACTIVE_ROLE` dengan nilai `admin`, `petugas`, atau `supervisor`.
- Jika autentikasi Laravel sudah aktif dan user punya kolom `role`, paket ini akan memprioritaskan role dari user login dibanding fallback environment.
- Middleware `EnsureSiprAuthenticated` menutup akses modul bila belum ada login Laravel atau sesi demo.
- Middleware route `EnsureSiprAbility` sudah disiapkan agar pembatasan akses bisa ditegakkan di level route, bukan hanya controller.
- File `bootstrap-middleware.stub.txt` disertakan sebagai panduan singkat bila proyek target ingin mendaftarkan alias middleware sendiri.
- File `INSTALLATION-CHECKLIST.txt` disertakan untuk mempercepat proses pasang dan uji role di project target.
- File `DROP-IN-INTEGRATION-NOTES.txt` memetakan folder mana yang perlu di-merge lebih dulu pada project target.
- File `ENV-PROFILE-PREVIEW.txt` dan `ENV-PROFILE-PRODUCTION.txt` membantu memilih mode environment tanpa menyetel banyak variabel secara manual.
- File `EXPORT-MANIFEST.txt` menjelaskan isi tiap arsip pecahan dan urutan merge yang aman.
- Model `App\Models\User` stub dan migration penambahan kolom `role` sudah disertakan untuk proyek yang belum menyiapkannya.
- Halaman login demo tersedia untuk memulai sesi role berbasis session sebelum auth Laravel penuh dipasang.
- Dokumen sekarang sudah bisa memakai upload file lokal lebih dulu, lalu diarahkan ke disk/storage prefix yang ditentukan environment.
- Service `FirebaseStorageService` disiapkan sebagai titik pindah ke Firebase Storage atau cloud storage final.
- `FirebaseStorageService` sekarang juga punya mode `firebase-rest` berbasis bearer token untuk pendekatan integrasi yang lebih dekat ke Firebase Storage.
- Filter Penempatan dan Dokumen sudah lebih dekat ke query database saat tabel tersedia, lalu otomatis fallback ke sample data bila belum siap.
- Navigasi sidebar sudah dipisah sesuai spesifikasi antara Riwayat Perubahan dan Laporan.
- Pengaturan sekarang dibatasi untuk role yang punya hak kelola sistem.
- Langkah berikut yang wajar adalah menyambungkan auth Laravel sungguhan, pagination server-side penuh, upload ke Firebase Storage, dan sinkronisasi Firebase yang lebih kuat.
