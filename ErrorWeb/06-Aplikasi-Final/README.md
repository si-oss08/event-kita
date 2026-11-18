# Aplikasi Final — EventKita

Folder ini sengaja **hanya berisi catatan**. Hosting aplikasi dilakukan sendiri oleh tim, sehingga tidak disimpan file hasil build, arsip, atau salinan kode di dalam folder ini.

## Status

| Keterangan | Isi |
| --- | --- |
| Sumber aplikasi | Root repository Laravel (`app/`, `routes/`, `resources/`, `database/`) |
| Hosting | Dilakukan sendiri oleh tim |
| Isi folder | Catatan ini saja |
| Video demo | Tidak dibuat |
| Perubahan kode pada dokumentasi ini | Tidak ada |

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

Sebelum aplikasi dipakai publik, perbaiki temuan pada [`../05-Laporan-Testing/Laporan-Testing.md`](../05-Laporan-Testing/Laporan-Testing.md), terutama yang berprioritas tinggi.
