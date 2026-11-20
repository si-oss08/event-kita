# Aplikasi Final — EventKita

Folder ini sengaja **hanya berisi catatan**. Aplikasi final sudah di-hosting, sehingga tidak disimpan file hasil build, arsip, atau salinan kode di dalam folder ini.

## Status

| Keterangan | Isi |
| --- | --- |
| Sumber aplikasi | Root repository Laravel (`app/`, `routes/`, `resources/`, `database/`) |
| Repositori GitHub | [si-oss08/event-kita](https://github.com/si-oss08/event-kita) |
| Hosting | [eventkita.infinityfreeapp.com](https://eventkita.infinityfreeapp.com/) |
| Isi folder | Catatan ini saja |
| Video demo | Tidak dibuat |
| Perubahan kode pada dokumentasi ini | Tidak ada |

## Tautan Aplikasi

- Aplikasi live: [https://eventkita.infinityfreeapp.com/](https://eventkita.infinityfreeapp.com/)
- Kode sumber: [https://github.com/si-oss08/event-kita](https://github.com/si-oss08/event-kita)

## Catatan Deployment

Saat aplikasi dipasang di server/hosting, langkah umum yang perlu dilakukan:

1. Sesuaikan `.env` dengan kredensial database produksi.
2. Jalankan `composer install --optimize-autoloader --no-dev`.
3. Generate application key dengan `php artisan key:generate`.
4. Jalankan migrasi dengan `php artisan migrate --force`.
5. Hubungkan storage dengan `php artisan storage:link` agar poster event dapat diakses.
6. Build aset frontend dengan `npm install && npm run build`.
7. Buat akun admin minimal satu sebagai penyelenggara.
8. Pastikan direktori `storage/` dan `bootstrap/cache/` dapat ditulis oleh server.

## Route yang Perlu Diuji Setelah Hosting

- `GET /` — halaman utama.
- `GET /event/{slug}` — detail event.
- `GET /login` dan `GET /register` — autentikasi peserta.
- `GET /dashboard`, `GET /tiket/{kode}`, `GET /profile` — area peserta.
- `GET /admin/login` — autentikasi admin.
- `GET /admin/dashboard`, `GET /admin/events/create`, `GET /admin/pendaftar` — area admin.

## Catatan Sebelum Produksi

Seluruh temuan pada [`../05-Laporan-Testing/Laporan-Testing.md`](../05-Laporan-Testing/Laporan-Testing.md) telah diperbaiki dan diverifikasi dengan 18 test otomatis (44 assertion). Sebelum dipakai publik, cukup pastikan konfigurasi `.env`, migrasi, dan tautan storage sudah sesuai dengan server hosting.
