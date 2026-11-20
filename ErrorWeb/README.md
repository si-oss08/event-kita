# ErrorWeb — EventKita

Folder ini berisi kumpulan deliverable kelompok **ErrorWeb** untuk proyek **Platform Pendaftaran dan Manajemen Event (EventKita)**. Seluruh artefak disimpan di dalam repository Laravel yang sudah ada, tepatnya pada direktori `ErrorWeb/`.

## Identitas Proyek

| Keterangan | Isi |
| --- | --- |
| Kelompok | ErrorWeb |
| Nama proyek | Platform Pendaftaran dan Manajemen Event (EventKita) |
| Jenis aplikasi | Aplikasi web Laravel |
| Tema desain | Hitam putih / grayscale |
| Lokasi dokumentasi | `ErrorWeb/` pada root repository |
| Status | Dokumen, desain, dan aplikasi selesai; 18/18 test lulus; aplikasi final di-hosting di InfinityFree |

## Tautan Proyek

| Keterangan | Tautan |
| --- | --- |
| Repositori GitHub | [github.com/si-oss08/event-kita](https://github.com/si-oss08/event-kita) |
| Aplikasi live | [eventkita.infinityfreeapp.com](https://eventkita.infinityfreeapp.com/) |

## Peta Struktur

```text
ErrorWeb/
├── README.md                         Ringkasan seluruh deliverable
├── README.pdf                        Versi cetak ringkasan
├── index.pdf                         Galeri & navigasi semua artefak
├── 01-Analisis-Kebutuhan/
│   ├── Analisis-Kebutuhan.md         Latar belakang, tujuan, kebutuhan
│   └── Analisis-Kebutuhan.pdf        Versi cetak
├── 02-Rancangan-Sistem/
│   ├── Rancangan-Sistem.md           Penjelasan rancangan & diagram
│   ├── Rancangan-Sistem.pdf          Versi cetak
│   ├── Rancangan-Sistem.drawio       Semua diagram dalam 1 file, 9 halaman/tab
│   ├── 01-DFD-Konteks.svg / .drawio
│   ├── 02-DFD-Level-0.svg / .drawio
│   ├── 03-ERD.svg / .drawio
│   ├── 04-Class-Diagram.svg / .drawio
│   ├── 05-Use-Case-Diagram.svg / .drawio
│   ├── 06-Aktivitas-Pendaftaran-Event.svg / .drawio
│   ├── 07-Aktivitas-Verifikasi-Pendaftar.svg / .drawio
│   ├── 08-Aktivitas-Kelola-Event.svg / .drawio
│   └── 09-Aktivitas-Registrasi-Akun.svg / .drawio
├── 03-Desain-UI-UX/
│   ├── README.md                     Panduan desain & daftar layar
│   ├── README.pdf                    Versi cetak
│   ├── 00-Style-Guide.svg
│   ├── 01-Landing-Page.svg
│   ├── 02-Login-Peserta.svg
│   ├── 03-Registrasi-Peserta.svg
│   ├── 04-Detail-Event.svg
│   ├── 05-Dashboard-Peserta.svg
│   ├── 06-E-Tiket.svg
│   ├── 07-Profil-Peserta.svg
│   ├── 08-Login-Admin.svg
│   ├── 09-Dashboard-Admin.svg
│   ├── 10-Form-Event.svg
│   ├── 11-Verifikasi-Pendaftar.svg
│   └── 12-Alur-Navigasi.svg
├── 04-Pengembangan-Aplikasi/
│   ├── README.md                     Tautan repositori GitHub
│   └── README.pdf                    Versi cetak
├── 05-Laporan-Testing/
│   ├── Laporan-Testing.md            Metode, kasus uji, temuan
│   ├── Laporan-Testing.pdf           Versi cetak
│   ├── Hasil-Pengujian.md            Rekap hasil pengujian
│   └── Hasil-Pengujian.pdf           Versi cetak
├── 06-Aplikasi-Final/
│   ├── README.md                     Catatan hosting & akses aplikasi
│   └── README.pdf                    Versi cetak
└── 07-Laporan-Akhir/
    ├── Laporan-Akhir.md              Laporan akhir lengkap
    └── Laporan-Akhir.pdf             Versi cetak / print-ready
```

## Ringkasan Fitur Aplikasi

1. Halaman publik: daftar event berstatus `publish` dan detail event berdasarkan slug.
2. Autentikasi peserta: registrasi, login, logout, dan kelola profil.
3. Area peserta: dashboard riwayat pendaftaran, pendaftaran event, dan e-tiket.
4. Autentikasi admin: login dan logout menggunakan guard `admin`.
5. Area admin: dashboard, pembuatan serta pembaruan event, dan verifikasi pendaftar.

## Cara Melihat Artefak

- Buka `index.pdf` untuk melihat galeri seluruh SVG dan tautan dokumen.
- Dokumen Markdown dapat dibaca langsung di editor atau GitHub.
- Setiap dokumen tersedia dalam versi PDF siap cetak, termasuk `07-Laporan-Akhir/Laporan-Akhir.pdf`.
- Untuk mengedit diagram, unggah `02-Rancangan-Sistem/Rancangan-Sistem.drawio` ke diagrams.net melalui **File → Open From → Device**; seluruh 9 diagram langsung dimuat sebagai 9 halaman/tab. File `.drawio` per diagram juga tetap tersedia.

## Catatan Penting

- Folder `04-Pengembangan-Aplikasi/` berisi tautan repositori GitHub karena kode aplikasi berada langsung di root repository Laravel.
- Tidak ada video demonstrasi yang dibuat.
- Folder `06-Aplikasi-Final/` berisi catatan hosting dan tautan aplikasi live di InfinityFree.
- Seluruh temuan pengujian berprioritas tinggi dan sedang telah diperbaiki; hasil terbaru ada di `05-Laporan-Testing/`.
- Seluruh desain UI/UX dibuat dalam format SVG berwarna hitam putih.
- Diagram pada `02-Rancangan-Sistem/` tersedia dalam tiga format: SVG untuk dilihat/dicetak, `.drawio` per diagram, dan satu file `Rancangan-Sistem.drawio` multi-halaman untuk mengedit semuanya sekaligus.
