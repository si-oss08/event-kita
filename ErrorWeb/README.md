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
| Status | Dokumen dan desain selesai; aplikasi final di-hosting sendiri oleh tim |

## Peta Struktur

```text
ErrorWeb/
├── README.md                         Ringkasan seluruh deliverable
├── index.html                        Galeri & navigasi semua artefak
├── 01-Analisis-Kebutuhan/
│   └── Analisis-Kebutuhan.md         Latar belakang, tujuan, kebutuhan
├── 02-Rancangan-Sistem/
│   ├── Rancangan-Sistem.md           Penjelasan rancangan & diagram
│   ├── Rancangan-Sistem.drawio       Semua diagram dalam 1 file multi-halaman
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
│   └── .gitkeep                      Sengaja dikosongkan
├── 05-Laporan-Testing/
│   ├── Laporan-Testing.md            Metode, kasus uji, temuan
│   └── Hasil-Pengujian.md            Rekap hasil pengujian
├── 06-Aplikasi-Final/
│   └── README.md                     Catatan hosting & akses aplikasi
└── 07-Laporan-Akhir/
    ├── Laporan-Akhir.md              Laporan akhir lengkap
    └── Laporan-Akhir.html            Versi cetak / print-ready
```

## Ringkasan Fitur Aplikasi

1. Halaman publik: daftar event berstatus `publish` dan detail event berdasarkan slug.
2. Autentikasi peserta: registrasi, login, logout, dan kelola profil.
3. Area peserta: dashboard riwayat pendaftaran, pendaftaran event, dan e-tiket.
4. Autentikasi admin: login dan logout menggunakan guard `admin`.
5. Area admin: dashboard, pembuatan serta pembaruan event, dan verifikasi pendaftar.

## Cara Melihat Artefak

- Buka `index.html` melalui browser untuk galeri seluruh SVG dan tautan dokumen.
- Dokumen Markdown dapat dibaca langsung di editor atau GitHub.
- Versi HTML tersedia untuk pencetakan pada `07-Laporan-Akhir/Laporan-Akhir.html` dan dokumen pendukung lainnya.

## Catatan Penting

- Folder `04-Pengembangan-Aplikasi/` sengaja tidak diisi karena kode aplikasi berada langsung di root repository Laravel.
- Tidak ada video demonstrasi yang dibuat.
- Folder `06-Aplikasi-Final/` hanya berisi catatan karena hosting aplikasi dilakukan sendiri oleh tim.
- Seluruh desain UI/UX dibuat dalam format SVG berwarna hitam putih.
- Diagram pada `02-Rancangan-Sistem/` tersedia dalam dua format: SVG untuk dilihat/dicetak dan `.drawio` untuk diedit di diagrams.net.
