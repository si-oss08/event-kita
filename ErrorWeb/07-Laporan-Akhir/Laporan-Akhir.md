# Laporan Akhir — Platform Pendaftaran dan Manajemen Event (EventKita)

**Kelompok:** ErrorWeb  
**Proyek:** Platform Pendaftaran dan Manajemen Event (EventKita)  
**Teknologi:** Laravel, Blade, Tailwind CSS, MySQL  
**Dokumentasi:** Folder `ErrorWeb/` pada root repository  
**Tanggal:** November 2026

---

## Ringkasan Eksekutif

EventKita adalah aplikasi web untuk mengelola proses pendaftaran event dari sisi peserta dan penyelenggara. Aplikasi memungkinkan publik melihat event, peserta mendaftar dan memperoleh e-tiket, serta admin membuat event dan memverifikasi pendaftar.

Seluruh deliverable kelompok dikumpulkan dalam folder `ErrorWeb/` yang terdiri dari tujuh bagian: analisis kebutuhan, rancangan sistem, desain UI/UX, pengembangan aplikasi, laporan testing, aplikasi final, dan laporan akhir. Folder pengembangan berisi tautan ke [repositori GitHub](https://github.com/si-oss08/event-kita) karena kode aplikasi berada langsung di repository, folder aplikasi final berisi catatan hosting dan tautan [aplikasi live](https://eventkita.infinityfreeapp.com/), dan tidak dibuat video demonstrasi.

## Daftar Isi

1. Pendahuluan
2. Analisis Kebutuhan
3. Rancangan Sistem
4. Desain UI/UX
5. Hasil Pengujian
6. Pembahasan Temuan
7. Kesimpulan dan Saran
8. Lampiran Artefak

---

## 1. Pendahuluan

### 1.1 Latar Belakang

Pendaftaran event yang dilakukan secara manual sering menimbulkan duplikasi data, sulitnya verifikasi, dan minimnya informasi status bagi peserta. EventKita menyediakan satu platform terpusat untuk publikasi event, pendaftaran peserta, penerbitan e-tiket, dan verifikasi oleh admin.

### 1.2 Tujuan

- Membangun platform pendaftaran event berbasis web.
- Memisahkan area publik, peserta, dan admin.
- Menyediakan dokumentasi perencanaan dan rancangan yang lengkap.
- Menghasilkan desain antarmuka hitam putih yang konsisten.
- Melakukan pengujian dan mendokumentasikan hasilnya.

### 1.3 Ruang Lingkup

Termasuk: landing page, detail event, autentikasi peserta/admin, dashboard peserta, pendaftaran event, e-tiket, profil, dashboard admin, CRUD event, dan verifikasi pendaftar.

Tidak termasuk: pembayaran online, notifikasi email/SMS, aplikasi mobile, video demo, deployment, dan pengisian folder pengembangan dengan salinan kode.

## 2. Analisis Kebutuhan

### 2.1 Pemangku Kepentingan

| Aktor | Peran |
| --- | --- |
| Peserta | Melihat event, mendaftar, melihat tiket dan status |
| Admin | Mengelola event dan memverifikasi pendaftar |
| Tim ErrorWeb | Mengembangkan dan mendokumentasikan sistem |
| Penguji | Menilai kelengkapan artefak dan fungsi aplikasi |

### 2.2 Kebutuhan Fungsional Utama

1. Menampilkan event berstatus `publish`.
2. Menampilkan detail event berdasarkan slug.
3. Registrasi, login, logout, dan kelola profil peserta.
4. Pendaftaran event dengan pencegahan duplikasi.
5. E-tiket berbasis kode registrasi unik.
6. Login admin dengan guard terpisah.
7. Membuat dan memperbarui event.
8. Verifikasi pendaftaran menjadi `diterima` atau `ditolak`.

### 2.3 Kebutuhan Non-Fungsional

Antara lain: password di-hash, sesi diregenerasi, validasi server, keunikan data, foreign key dengan cascade, soft delete, desain monokrom, dan dokumentasi yang dapat dicetak.

Dokumen lengkap: [`../01-Analisis-Kebutuhan/Analisis-Kebutuhan.md`](../01-Analisis-Kebutuhan/Analisis-Kebutuhan.md).

## 3. Rancangan Sistem

### 3.1 Arsitektur

Aplikasi memakai pola controller–model–view Laravel. Route menentukan alur ke controller, controller melakukan validasi dan memanggil model, model mengakses database, lalu view dirender atau redirect diberikan.

### 3.2 DFD

- **DFD Konteks:** satu proses sistem dengan dua entitas eksternal, Peserta dan Admin.
- **DFD Level 0:** lima proses utama — Autentikasi & Akun, Kelola Event, Pendaftaran Event, Verifikasi Pendaftar, Informasi & E-Tiket — serta empat penyimpanan data `users`, `admins`, `events`, dan `registrasis`.

### 3.3 ERD

Empat entitas utama:

- `users` berelasi 1:N dengan `registrasis`.
- `admins` berelasi 1:N dengan `events`.
- `events` berelasi 1:N dengan `registrasis`.

### 3.4 Use Case dan Activity

Use case mencakup delapan aktivitas peserta dan tiga aktivitas admin. Activity diagram menggambarkan alur pendaftaran event, verifikasi pendaftar, kelola event, dan registrasi akun.

Diagram juga tersedia dalam format Draw.io yang dapat diedit: [`Rancangan-Sistem.drawio`](../02-Rancangan-Sistem/Rancangan-Sistem.drawio) berisi 9 halaman, dengan file per diagram tersedia di folder yang sama.

Dokumen lengkap: [`../02-Rancangan-Sistem/Rancangan-Sistem.md`](../02-Rancangan-Sistem/Rancangan-Sistem.md).

## 4. Desain UI/UX

Seluruh desain dibuat dalam format SVG dengan palet hitam, putih, dan abu-abu. Kanvas desktop 1280 × 900 px digunakan secara konsisten dengan bingkai browser, navbar, footer, kartu, tombol, dan field yang seragam.

Layar yang dibuat:

1. Style guide.
2. Landing page.
3. Login peserta.
4. Registrasi peserta.
5. Detail event.
6. Dashboard peserta.
7. E-tiket.
8. Profil peserta.
9. Login admin.
10. Dashboard admin.
11. Form event.
12. Verifikasi pendaftar.
13. Alur navigasi.

Dokumen lengkap: [`../03-Desain-UI-UX/README.md`](../03-Desain-UI-UX/README.md).

## 5. Hasil Pengujian

### 5.1 Smoke Test HTTP

Sembilan route diuji; seluruhnya menghasilkan status sesuai ekspektasi. Route terproteksi mengarahkan guest ke halaman login, dan slug tidak ditemukan menghasilkan 404.

### 5.2 Test Otomatis

Pest menjalankan dua test: satu lulus dan satu gagal. Kegagalan terjadi karena lingkungan test menggunakan SQLite `:memory:` tanpa migrasi, sehingga tabel `events` belum ada.

### 5.3 Validasi SVG dan Draw.io

Sebanyak 22 file SVG dan 10 file Draw.io divalidasi sebagai XML dan seluruhnya dinyatakan valid. File Draw.io terdiri dari satu file multi-halaman dan sembilan file per diagram.

### 5.4 Ringkasan

| Pengujian | Hasil |
| --- | --- |
| Smoke test | 9/9 lulus |
| Pest | 1 lulus, 1 gagal |
| SVG & Draw.io | 22/22 SVG + 10/10 Draw.io valid |
| Temuan perbaikan | 8 item |

Dokumen lengkap: [`../05-Laporan-Testing/Laporan-Testing.md`](../05-Laporan-Testing/Laporan-Testing.md) dan [`../05-Laporan-Testing/Hasil-Pengujian.md`](../05-Laporan-Testing/Hasil-Pengujian.md).

## 6. Pembahasan Temuan

| Temuan | Prioritas |
| --- | --- |
| `RefreshDatabase` belum aktif pada test feature | Tinggi |
| Statistik pendaftaran dan approval hard-coded `0` | Tinggi |
| Pendaftaran belum memvalidasi status, kuota, dan waktu event | Tinggi |
| Verifikasi belum dibatasi pada event milik admin sendiri | Tinggi |
| `is_active` admin belum diperiksa saat login | Tinggi |
| Guest admin diarahkan ke `/login`, bukan `/admin/login` | Sedang |
| Detail publik belum memfilter status `publish` | Sedang |
| Enum status event lebih luas dari pilihan form | Rendah |

Seluruh temuan hanya dicatat; tidak ada perubahan pada kode aplikasi dalam kegiatan dokumentasi ini.

## 7. Kesimpulan dan Saran

### Kesimpulan

- Struktur folder `ErrorWeb/` lengkap sesuai permintaan kelompok.
- Diagram sistem dan desain UI/UX telah dibuat dalam format SVG hitam putih dan valid; diagram rancangan sistem juga tersedia dalam format Draw.io yang dapat diedit.
- Dokumen analisis, rancangan, pengujian, aplikasi final, dan laporan akhir telah tersedia.
- Aplikasi dapat diakses pada route publik dan proteksi akses dasar berjalan.
- Test otomatis belum lulus karena konfigurasi database test belum siap.

### Saran

1. Aktifkan `RefreshDatabase` dan tambahkan test fitur untuk alur pendaftaran serta verifikasi.
2. Terapkan perbaikan berprioritas tinggi sebelum hosting produksi.
3. Samakan redirect guest admin ke `/admin/login`.
4. Hitung statistik admin dari data aktual.
5. Batasi verifikasi dan detail event sesuai kepemilikan/status.

## 8. Lampiran Artefak

| Folder | Isi |
| --- | --- |
| [`../README.md`](../README.md) | Ringkasan seluruh deliverable |
| [`../index.pdf`](../index.pdf) | Galeri dan navigasi artefak |
| [`../01-Analisis-Kebutuhan`](../01-Analisis-Kebutuhan/Analisis-Kebutuhan.md) | Analisis kebutuhan |
| [`../02-Rancangan-Sistem`](../02-Rancangan-Sistem/Rancangan-Sistem.md) | Diagram dan penjelasan sistem |
| [`../03-Desain-UI-UX`](../03-Desain-UI-UX/README.md) | Style guide dan 13 SVG UI/UX |
| [`../04-Pengembangan-Aplikasi`](../04-Pengembangan-Aplikasi/README.md) | Tautan repositori GitHub dan panduan pengembangan |
| [`../05-Laporan-Testing`](../05-Laporan-Testing/Laporan-Testing.md) | Laporan dan hasil pengujian |
| [`../06-Aplikasi-Final`](../06-Aplikasi-Final/README.md) | Catatan hosting dan tautan aplikasi live |
| [Repositori GitHub](https://github.com/si-oss08/event-kita) | Kode sumber aplikasi |
| [Aplikasi live](https://eventkita.infinityfreeapp.com/) | Hosting aplikasi final |
| [`07-Laporan-Akhir.md`](Laporan-Akhir.md) | Laporan akhir versi Markdown |
| [`Laporan-Akhir.pdf`](Laporan-Akhir.pdf) | Laporan akhir versi cetak |

---

**ErrorWeb — Platform Pendaftaran dan Manajemen Event (EventKita)**
