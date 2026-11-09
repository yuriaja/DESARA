# DESARA — Modul Pelayanan Administrasi Warga

## Instalasi
1. Salin proyek ke `C:\laragon\www\desara-backend` (jangan menimpa data proyek yang sudah diubah tanpa membandingkannya).
2. Pastikan PHP >=8.3, jalankan `composer install` dan atur `.env` serta database.
3. Jalankan `php artisan key:generate` jika APP_KEY belum ada.
4. Jalankan `php artisan migrate`.
5. Jalankan `php artisan db:seed --class=LayananDesaSeeder`.
6. Jalankan `php artisan serve`, buka `/masuk-warga`, daftar warga, lalu buka `/warga`.
7. Admin/petugas: buat akun terpisah secara aman, lalu set role `petugas` atau `admin` oleh administrator via Tinker: `\App\Models\User::where('email','email-petugas@desa.test')->update(['role'=>'petugas']);` (akun tersebut harus sudah ada; jangan menggunakan akun warga untuk persetujuan produksi).
8. Masuk sebagai petugas dan akses `/petugas/pengajuan`.

## Yang sudah disediakan
- 7 kategori, 50 katalog awal; 7 layanan contoh aktif dengan field berbeda.
- Autentikasi session Laravel; registrasi warga, login, logout dan pemisahan peran.
- Pengajuan disimpan pada database; riwayat status dan unggahan private.
- Petugas meninjau, meminta perbaikan, menyetujui/menolak, dan unggah PDF final.
- Portal warga baru via Blade; aplikasi admin lama di `/app` masih dipertahankan.

## Batasan dan hal yang wajib disiapkan sebelum digunakan publik
- Katalog, form, dan persyaratan 7 layanan adalah **contoh awal**; validasi dengan SOP Desa Milangasri.
- 43 layanan masih nonaktif. Admin UI untuk mengubah kategori/field/persyaratan belum dibuat; konfigurasi seeder/database perlu dikelola developer.
- Tanda tangan elektronik, penomoran surat resmi, dan generator PDF untuk pengajuan warga **belum otomatis**. Petugas mengunggah dokumen resmi yang sudah disahkan melalui prosedur desa.
- Login admin lama (`public/app/js/app.js`) menggunakan data browser; **belum diamankan/disatukan** dengan login Laravel. Jangan gunakan login demo itu untuk data nyata.
- Warga bisa mendaftar dengan NIK sendiri, tetapi belum ada verifikasi identitas oleh petugas. Jangan gunakan untuk penerbitan resmi sampai verifikasi identitas tersedia.
- Belum ada audit log mendalam, antivirus file, manajemen pengaturan layanan UI, notifikasi, duplicate-request token, tes menyeluruh, atau penerapan perlindungan data produksi.
- Upload berkas private (disk `local`) tidak boleh disajikan langsung lewat web. Pastikan kebijakan backup, retensi, HTTPS, dan akses petugas.

## Pengujian manual
- Buat warga; periksa 7 kartu; pilih layanan aktif; isi form lalu submit; lihat riwayat.
- Pastikan warga A tidak bisa membuka pengajuan warga B (403).
- Login petugas; periksa pengajuan; coba minta perbaikan, perbaiki sebagai warga; unggah PDF final dan set Selesai.
- Pastikan surat nonaktif menolak pengajuan, tipe lampiran salah ditolak, dan PDF final hanya bisa diunduh pemilik atau petugas.
