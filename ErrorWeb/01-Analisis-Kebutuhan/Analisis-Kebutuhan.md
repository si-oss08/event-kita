# Analisis Kebutuhan — EventKita

## 1. Latar Belakang

Penyelenggaraan event—baik seminar, workshop, maupun acara komunitas—memerlukan mekanisme pendaftaran yang rapi, transparan, dan mudah diakses. Proses yang masih dilakukan secara manual seperti melalui formulir kertas atau penyebaran tautan tanpa pengelolaan data sering menimbulkan duplikasi pendaftaran, kesulitan verifikasi, serta kurangnya informasi status bagi peserta.

EventKita hadir sebagai platform web untuk mengelola siklus pendaftaran event: mulai dari publikasi event, registrasi akun peserta, pendaftaran event, penerbitan e-tiket, hingga verifikasi pendaftar oleh admin. Aplikasi dibangun menggunakan Laravel dan dokumentasi proyeknya dikumpulkan dalam folder `ErrorWeb/`.

## 2. Rumusan Masalah

1. Peserta belum memiliki satu tempat untuk melihat event yang tersedia dan melakukan pendaftaran.
2. Penyelenggara memerlukan sarana untuk membuat, mengubah, dan mempublikasikan event.
3. Proses verifikasi pendaftar belum terstruktur sehingga status peserta sulit dipantau.
4. Peserta memerlukan bukti pendaftaran digital berupa e-tiket.
5. Diperlukan dokumentasi perencanaan, rancangan sistem, desain antarmuka, pengujian, dan laporan akhir sebagai artefak kelompok.

## 3. Tujuan

### Tujuan Umum

Menyediakan platform pendaftaran dan manajemen event berbasis web yang memungkinkan peserta mendaftar secara mandiri dan admin mengelola event serta memverifikasi pendaftar dalam satu sistem.

### Tujuan Khusus

1. Menampilkan daftar event berstatus `publish` kepada publik.
2. Menyediakan halaman detail event lengkap dengan informasi tanggal, lokasi, kuota, harga, dan poster.
3. Menyediakan registrasi dan login untuk peserta.
4. Memungkinkan peserta mendaftar event, melihat riwayat, dan mengunduh/melihat e-tiket.
5. Menyediakan login khusus admin dengan guard terpisah.
6. Memungkinkan admin membuat dan mengubah event.
7. Memungkinkan admin menerima atau menolak pendaftaran disertai catatan.
8. Menghasilkan dokumentasi lengkap sesuai struktur folder yang diminta kelompok.

## 4. Ruang Lingkup

### Termasuk

- Halaman landing dan detail event.
- Autentikasi peserta dan admin.
- Dashboard peserta dan profil.
- Pendaftaran event dan e-tiket.
- Dashboard admin, pembuatan dan pembaruan event.
- Verifikasi pendaftar.
- Dokumentasi analisis, rancangan sistem, desain UI/UX, pengujian, dan laporan akhir.

### Tidak Termasuk

- Pembayaran online.
- Pengiriman email/SMS notifikasi.
- Aplikasi mobile native.
- Video demonstrasi.
- Deployment/hosting aplikasi (dilakukan sendiri oleh tim).
- Pengisian folder `04-Pengembangan-Aplikasi/` dengan salinan kode tambahan.

## 5. Pemangku Kepentingan

| Pemangku Kepentingan | Peran | Kebutuhan Utama |
| --- | --- | --- |
| Peserta | Pengguna akhir | Melihat event, mendaftar, melihat tiket & status |
| Admin / Penyelenggara | Pengelola sistem | Membuat event, melihat pendaftar, verifikasi |
| Tim ErrorWeb | Pengembang & pendokumentasi | Artefak perencanaan, desain, pengujian, laporan |
| Dosen / Penguji | Evaluator | Dokumen sistematis dan hasil pengujian |

## 6. Kebutuhan Fungsional

| ID | Kebutuhan | Aktor | Prioritas |
| --- | --- | --- | --- |
| FR-01 | Sistem menampilkan daftar event berstatus `publish` di halaman utama | Publik | Tinggi |
| FR-02 | Sistem menampilkan detail event berdasarkan `slug` | Publik | Tinggi |
| FR-03 | Peserta dapat membuat akun dengan nama, email, password, dan konfirmasi password | Peserta | Tinggi |
| FR-04 | Peserta dapat login menggunakan email dan password | Peserta | Tinggi |
| FR-05 | Peserta dapat logout | Peserta | Tinggi |
| FR-06 | Peserta dapat melihat riwayat pendaftarannya | Peserta | Tinggi |
| FR-07 | Peserta dapat mendaftar pada sebuah event | Peserta | Tinggi |
| FR-08 | Sistem menolak pendaftaran ganda pada event yang sama | Peserta | Tinggi |
| FR-09 | Sistem menghasilkan kode registrasi unik berformat `EVT-XXXXXXXX` | Sistem | Tinggi |
| FR-10 | Peserta dapat melihat e-tiket berdasarkan kode registrasi | Peserta | Tinggi |
| FR-11 | Peserta dapat mengubah nama dan/atau password profil | Peserta | Sedang |
| FR-12 | Admin dapat login dengan username dan password melalui guard `admin` | Admin | Tinggi |
| FR-13 | Admin dapat logout | Admin | Tinggi |
| FR-14 | Admin dapat melihat dashboard berisi total event dan daftar event terbaru miliknya | Admin | Tinggi |
| FR-15 | Admin dapat membuat event baru dengan validasi data dan unggahan poster | Admin | Tinggi |
| FR-16 | Admin dapat mengubah event miliknya sendiri | Admin | Tinggi |
| FR-17 | Admin dapat melihat seluruh pendaftar beserta data user dan event | Admin | Tinggi |
| FR-18 | Admin dapat mengubah status pendaftaran menjadi `diterima` atau `ditolak` | Admin | Tinggi |
| FR-19 | Admin dapat menyimpan catatan saat menolak pendaftaran | Admin | Sedang |
| FR-20 | Sistem mencatat waktu login terakhir admin | Sistem | Sedang |

## 7. Kebutuhan Non-Fungsional

| ID | Kebutuhan | Penjelasan |
| --- | --- | --- |
| NFR-01 | Keamanan autentikasi | Password disimpan menggunakan hash; sesi diregenerasi setelah login/logout |
| NFR-02 | Pemisahan akses | Area peserta memakai guard `web`, area admin memakai guard `admin` |
| NFR-03 | Validasi input | Seluruh form divalidasi di server menggunakan aturan Laravel |
| NFR-04 | Keunikan data | Email user, username/email admin, slug event, dan kode registrasi bersifat unik |
| NFR-05 | Konsistensi data | Foreign key dengan `onDelete('cascade')` antara admin–event, user–registrasi, event–registrasi |
| NFR-06 | Penghapusan lunak | Model `Admin`, `Event`, dan `Registrasi` menggunakan `SoftDeletes` |
| NFR-07 | Antarmuka konsisten | Desain UI/UX hitam putih, tipografi dan komponen mengikuti style guide |
| NFR-08 | Responsivitas | Tampilan dirancang untuk desktop 1280 px pada artefak SVG dan mengikuti kelas responsif Tailwind di aplikasi |
| NFR-09 | Dokumentasi | Setiap tahap memiliki artefak yang dapat dibaca dan dicetak |
| NFR-10 | Pemeliharaan | Kode dan dokumentasi menggunakan struktur folder yang jelas dan nomenklatur konsisten |

## 8. Aturan Bisnis

1. Event hanya tampil di halaman publik jika `status = publish`.
2. Peserta wajib login sebelum melakukan pendaftaran.
3. Satu peserta hanya dapat mendaftar sekali per event.
4. Status awal pendaftaran adalah `pending`.
5. Kode registrasi dibuat otomatis dan tidak boleh duplikat.
6. E-tiket hanya dapat diakses oleh pemilik kode registrasi yang sedang login.
7. Admin hanya dapat mengedit dan memperbarui event dengan `admin_id` miliknya sendiri.
8. Status pendaftaran yang dapat dipilih admin adalah `diterima` atau `ditolak`.
9. Tanggal selesai event tidak boleh lebih awal dari tanggal mulai.
10. Kuota dan harga event bernilai minimal `1` dan `0` secara berturut-turut.

## 9. Asumsi dan Kendala

### Asumsi

- Database MySQL tersedia dengan nama `event_kita` untuk lingkungan lokal.
- Admin dan peserta sudah memiliki akun sebelum pengujian fitur area terproteksi.
- Poster event berupa gambar `jpeg`, `png`, atau `jpg` dengan ukuran maksimal 2 MB.
- Tim melakukan hosting aplikasi pada InfinityFree, sehingga folder aplikasi final hanya berisi catatan dan tautan [aplikasi live](https://eventkita.infinityfreeapp.com/).

### Kendala

- Pengujian otomatis bawaan menggunakan SQLite `:memory:` sementara konfigurasi aplikasi memakai MySQL; tanpa migrasi pada test database, test feature akan gagal.
- Verifikasi visual langsung melalui screenshot tidak tersedia pada lingkungan agent, sehingga pengecekan desain dilakukan melalui validasi XML SVG dan pemeriksaan struktur.
- Folder `04-Pengembangan-Aplikasi/` berisi tautan [repositori GitHub](https://github.com/si-oss08/event-kita) karena kode sumber berada langsung di root repository.

## 10. Daftar Halaman

| No | Halaman | Route | Akses |
| --- | --- | --- | --- |
| 1 | Landing Page | `GET /` | Publik |
| 2 | Detail Event | `GET /event/{slug}` | Publik |
| 3 | Login Peserta | `GET /login` | Publik |
| 4 | Registrasi Peserta | `GET /register` | Publik |
| 5 | Dashboard Peserta | `GET /dashboard` | Auth `web` |
| 6 | E-Tiket | `GET /tiket/{kode_registrasi}` | Auth `web` |
| 7 | Profil Peserta | `GET /profile`, `PUT /profile` | Auth `web` |
| 8 | Login Admin | `GET /admin/login` | Publik |
| 9 | Dashboard Admin | `GET /admin/dashboard` | Auth `admin` |
| 10 | Form Event | `GET /admin/events/create` | Auth `admin` |
| 11 | Verifikasi Pendaftar | `GET /admin/pendaftar` | Auth `admin` |

## 11. Kebutuhan Data Ringkas

| Entitas | Data Utama |
| --- | --- |
| `users` | `id`, `name`, `email`, `password`, `email_verified_at` |
| `admins` | `id`, `nama_admin`, `username`, `email`, `password`, `is_active`, `last_login_at` |
| `events` | `id`, `admin_id`, `judul`, `slug`, `deskripsi`, `lokasi`, `tanggal_mulai`, `tanggal_selesai`, `kuota`, `harga`, `poster`, `status` |
| `registrasis` | `id`, `user_id`, `event_id`, `kode_registrasi`, `status`, `catatan`, `tanggal_daftar` |

## 12. Prioritas Pengembangan

- **Must have:** FR-01 sampai FR-10, FR-12 sampai FR-15, FR-17, FR-18.
- **Should have:** FR-11, FR-16, FR-19, FR-20.
- **Could have:** Peningkatan validasi kuota, notifikasi, dan statistik lanjutan.
- **Won't have (saat ini):** Pembayaran, notifikasi email, aplikasi mobile, video demo.
