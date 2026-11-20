# Pengembangan Aplikasi — EventKita

Folder ini berisi tautan ke kode sumber aplikasi EventKita. Kode tidak disalin ke dalam folder ini karena aplikasi Laravel berada langsung di root repository.

## Repositori GitHub

| Keterangan | Tautan |
| --- | --- |
| Repositori | [si-oss08/event-kita](https://github.com/si-oss08/event-kita) |
| URL clone | `https://github.com/si-oss08/event-kita.git` |
| Branch utama | `main` |

## Struktur Kode di Repository

- `app/` — controller, model, middleware, dan logika aplikasi.
- `routes/` — route publik, peserta, dan admin.
- `resources/views/` — tampilan Blade dengan Tailwind CSS.
- `database/` — migration dan seeder.
- `public/` — aset publik aplikasi.
- `tests/` — pengujian otomatis.

## Cara Menjalankan Secara Lokal

1. Clone repository: `git clone https://github.com/si-oss08/event-kita.git`.
2. Jalankan `composer install` dan `npm install`.
3. Salin `.env.example` menjadi `.env`, lalu sesuaikan koneksi database.
4. Jalankan `php artisan key:generate` dan `php artisan migrate --seed`.
5. Jalankan `npm run dev` dan `php artisan serve`.
