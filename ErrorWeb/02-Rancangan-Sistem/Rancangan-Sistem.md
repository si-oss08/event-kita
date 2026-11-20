# Rancangan Sistem — EventKita

Dokumen ini menjelaskan rancangan sistem EventKita berdasarkan implementasi Laravel pada repository. Seluruh diagram disajikan dalam dua format: SVG hitam putih untuk dilihat/dicetak dan `.drawio` untuk diedit di diagrams.net. File [`Rancangan-Sistem.drawio`](Rancangan-Sistem.drawio) berisi semua diagram dalam satu file multi-halaman; cukup unggah satu file tersebut ke diagrams.net (**File → Open From → Device**) dan kesembilan diagram langsung dimuat sebagai 9 halaman/tab. Tersedia juga file `.drawio` per diagram.

## 1. Arsitektur Umum

EventKita menggunakan arsitektur web tiga lapis sederhana:

| Lapisan | Komponen | Tanggung Jawab |
| --- | --- | --- |
| Tampilan | Blade + Tailwind CSS | Halaman publik, area peserta, area admin |
| Kontrol | `AuthController`, `EventController`, `RegistrasiController` | Memproses request, validasi, pengalihan respons |
| Model | `User`, `Admin`, `Event`, `Registrasi` | Relasi Eloquent dan akses database |
| Data | MySQL `event_kita` | Menyimpan akun, event, dan pendaftaran |

Alur umum: browser mengirim request → route Laravel → middleware autentikasi → controller → model → database → view/redirect.

## 2. Struktur Modul

| Modul | Route Utama | Controller |
| --- | --- | --- |
| Publik | `/`, `/event/{slug}` | `EventController@index`, `EventController@show` |
| Autentikasi Peserta | `/login`, `/register`, `/logout` | `AuthController` |
| Area Peserta | `/dashboard`, `/daftar-event/{id}`, `/tiket/{kode}`, `/profile` | `RegistrasiController`, `AuthController` |
| Autentikasi Admin | `/admin/login`, `/admin/logout` | `AuthController` |
| Area Admin | `/admin/dashboard`, `/admin/events/*`, `/admin/pendaftar` | `EventController`, `RegistrasiController` |

## 3. DFD Konteks

Diagram: [`01-DFD-Konteks.svg`](01-DFD-Konteks.svg)

DFD konteks memetakan seluruh sistem sebagai satu proses bernomor `0`.

| Entitas Eksternal | Aliran Masuk ke Sistem | Aliran Keluar dari Sistem |
| --- | --- | --- |
| Peserta | Data registrasi akun, kredensial login, data pendaftaran event | Informasi event, e-tiket, status pendaftaran |
| Admin | Data event, keputusan verifikasi | Daftar pendaftar, rekap event, notifikasi hasil |

## 4. DFD Level 0

Diagram: [`02-DFD-Level-0.svg`](02-DFD-Level-0.svg)

Sistem didekomposisi menjadi lima proses:

| Proses | Nama | Penjelasan |
| --- | --- | --- |
| P1 | Autentikasi & Akun | Registrasi, login, logout, dan pembaruan profil |
| P2 | Kelola Event | Membuat dan memperbarui event oleh admin |
| P3 | Pendaftaran Event | Membuat registrasi peserta dengan status awal `pending` |
| P4 | Verifikasi Pendaftar | Mengubah status menjadi `diterima` atau `ditolak` |
| P5 | Informasi & E-Tiket | Menampilkan daftar event, detail, riwayat, dan tiket |

Penyimpanan data:

| ID | Tabel | Isi |
| --- | --- | --- |
| D1 | `users` | Akun peserta |
| D2 | `admins` | Akun admin |
| D3 | `events` | Data event |
| D4 | `registrasis` | Data pendaftaran |

## 5. ERD

Diagram: [`03-ERD.svg`](03-ERD.svg)

### Entitas dan Atribut

- **users**: `id` (PK), `name`, `email` (unique), `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`.
- **admins**: `id` (PK), `nama_admin`, `username` (unique), `email` (unique), `password`, `is_active`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`.
- **events**: `id` (PK), `admin_id` (FK), `judul`, `slug` (unique), `deskripsi`, `lokasi`, `tanggal_mulai`, `tanggal_selesai`, `kuota`, `harga`, `poster`, `status`, `created_at`, `updated_at`, `deleted_at`.
- **registrasis**: `id` (PK), `user_id` (FK), `event_id` (FK), `kode_registrasi` (unique), `status`, `catatan`, `tanggal_daftar`, `created_at`, `updated_at`, `deleted_at`.

### Relasi

| Relasi | Kardinalitas | Foreign Key |
| --- | --- | --- |
| Admin → Event | 1 : N | `events.admin_id` → `admins.id` |
| User → Registrasi | 1 : N | `registrasis.user_id` → `users.id` |
| Event → Registrasi | 1 : N | `registrasis.event_id` → `events.id` |

Semua foreign key menggunakan `onDelete('cascade')`. Model `Admin`, `Event`, dan `Registrasi` menggunakan soft delete.

## 6. Class Diagram

Diagram: [`04-Class-Diagram.svg`](04-Class-Diagram.svg)

### Controller

- `AuthController`: `showLogin`, `showRegister`, `register`, `login`, `logout`, `showAdminLogin`, `adminLogin`, `adminLogout`, `showProfile`, `updateProfile`.
- `EventController`: `index`, `show`, `dashboard`, `create`, `store`, `edit`, `update`.
- `RegistrasiController`: `index`, `store`, `showTicket`, `adminPendaftar`, `verifikasi`.

### Model

- `User` → `registrasis()`.
- `Admin` → `events()`.
- `Event` → `admin()`, `registrasis()`.
- `Registrasi` → `user()`, `event()`.

Garis putus-putus pada diagram menunjukkan dependensi controller ke model, sedangkan garis solid menunjukkan asosiasi antar model.

## 7. Use Case Diagram

Diagram: [`05-Use-Case-Diagram.svg`](05-Use-Case-Diagram.svg)

### Aktor Peserta

- Registrasi akun.
- Login user.
- Lihat daftar event.
- Lihat detail event.
- Daftar event.
- Lihat e-tiket.
- Riwayat pendaftaran.
- Kelola profil.

### Aktor Admin

- Login admin.
- Kelola event (create/update).
- Verifikasi pendaftar.

Catatan: `Daftar Event`, `Lihat E-Tiket`, `Riwayat Pendaftaran`, dan `Kelola Profil` mensyaratkan peserta sudah login.

## 8. Activity Diagram

### 8.1 Pendaftaran Event

Diagram: [`06-Aktivitas-Pendaftaran-Event.svg`](06-Aktivitas-Pendaftaran-Event.svg)

Alur: buka detail event → cek status login → login/registrasi jika belum → klik daftar → sistem membuat kode unik → simpan registrasi `pending` → tampilkan dashboard dan e-tiket. Jika peserta sudah pernah mendaftar, sistem menampilkan pesan error.

### 8.2 Verifikasi Pendaftar

Diagram: [`07-Aktivitas-Verifikasi-Pendaftar.svg`](07-Aktivitas-Verifikasi-Pendaftar.svg)

Alur: login admin → buka menu verifikasi → lihat daftar pendaftar → pilih pendaftar → putuskan terima/tolak → simpan status dan catatan → tampilkan notifikasi.

### 8.3 Kelola Event

Diagram: [`08-Aktivitas-Kelola-Event.svg`](08-Aktivitas-Kelola-Event.svg)

Alur: login admin → buka dashboard → pilih tambah/edit event → isi form → validasi server → unggah poster opsional → simpan event (`draft`/`publish`) → tampilkan notifikasi.

### 8.4 Registrasi Akun

Diagram: [`09-Aktivitas-Registrasi-Akun.svg`](09-Aktivitas-Registrasi-Akun.svg)

Alur: buka halaman registrasi → isi data → validasi → simpan user → login otomatis → arahkan ke dashboard.

## 9. Aturan Validasi Server

| Form | Aturan |
| --- | --- |
| Registrasi | `name` required string maks. 255; `email` required email unik; `password` required min 8 dan `confirmed` |
| Login Peserta | `email` required email; `password` required |
| Profil | `name` required string maks. 255; `password` nullable min 8 dan `confirmed` |
| Login Admin | `username` required; `password` required |
| Event | `judul`, `deskripsi`, `lokasi`, `tanggal_mulai`, `tanggal_selesai`, `kuota`, `harga`, `status` wajib; `tanggal_selesai` `after_or_equal:tanggal_mulai`; `kuota` `min:1`; `harga` `min:0`; `status` `in:draft,publish`; poster opsional `image|mimes:jpeg,png,jpg|max:2048` |
| Verifikasi | `status` required `in:diterima,ditolak`; `catatan` nullable string |

## 10. Mekanisme Keamanan

1. Guard `web` untuk peserta dan guard `admin` untuk penyelenggara.
2. Middleware `auth` dan `auth:admin` melindungi route area masing-masing.
3. `session()->regenerate()` setelah login untuk mencegah session fixation.
4. Password disimpan dengan `Hash::make()`.
5. E-tiket dibatasi oleh `user_id` yang sedang login.
6. Edit/update event dibatasi oleh pencocokan `admin_id`.
7. Model menggunakan soft delete agar data tidak hilang permanen.

## 11. File Draw.io

Diagram dapat diedit langsung menggunakan diagrams.net / draw.io:

- [`Rancangan-Sistem.drawio`](Rancangan-Sistem.drawio) — semua 9 diagram dalam satu file dengan 9 tab/halaman.
- [`01-DFD-Konteks.drawio`](01-DFD-Konteks.drawio)
- [`02-DFD-Level-0.drawio`](02-DFD-Level-0.drawio)
- [`03-ERD.drawio`](03-ERD.drawio)
- [`04-Class-Diagram.drawio`](04-Class-Diagram.drawio)
- [`05-Use-Case-Diagram.drawio`](05-Use-Case-Diagram.drawio)
- [`06-Aktivitas-Pendaftaran-Event.drawio`](06-Aktivitas-Pendaftaran-Event.drawio)
- [`07-Aktivitas-Verifikasi-Pendaftar.drawio`](07-Aktivitas-Verifikasi-Pendaftar.drawio)
- [`08-Aktivitas-Kelola-Event.drawio`](08-Aktivitas-Kelola-Event.drawio)
- [`09-Aktivitas-Registrasi-Akun.drawio`](09-Aktivitas-Registrasi-Akun.drawio)

File Draw.io menggunakan format XML tidak terkompresi (`compressed="false"`), sehingga dapat dibuka kembali dan dimodifikasi tanpa kehilangan shape.

## 12. Daftar Artefak Diagram

| File | Jenis |
| --- | --- |
| [`01-DFD-Konteks.svg`](01-DFD-Konteks.svg) | DFD Konteks |
| [`02-DFD-Level-0.svg`](02-DFD-Level-0.svg) | DFD Level 0 |
| [`03-ERD.svg`](03-ERD.svg) | Entity Relationship Diagram |
| [`04-Class-Diagram.svg`](04-Class-Diagram.svg) | Class Diagram |
| [`05-Use-Case-Diagram.svg`](05-Use-Case-Diagram.svg) | Use Case Diagram |
| [`06-Aktivitas-Pendaftaran-Event.svg`](06-Aktivitas-Pendaftaran-Event.svg) | Activity Diagram pendaftaran |
| [`07-Aktivitas-Verifikasi-Pendaftar.svg`](07-Aktivitas-Verifikasi-Pendaftar.svg) | Activity Diagram verifikasi |
| [`08-Aktivitas-Kelola-Event.svg`](08-Aktivitas-Kelola-Event.svg) | Activity Diagram kelola event |
| [`09-Aktivitas-Registrasi-Akun.svg`](09-Aktivitas-Registrasi-Akun.svg) | Activity Diagram registrasi akun |
| [`12-Alur-Navigasi.svg`](../03-Desain-UI-UX/12-Alur-Navigasi.svg) | Alur navigasi UI/UX |
