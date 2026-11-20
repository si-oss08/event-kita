# Hasil Pengujian — EventKita

Dokumen ini merupakan rekap ringkas dari [`Laporan-Testing.md`](Laporan-Testing.md).

## Ringkasan Eksekutif

| Pengujian | Hasil |
| --- | --- |
| Smoke test HTTP | 9/9 sesuai ekspektasi |
| Pest / PHPUnit | 18/18 lulus, 0 gagal |
| Assertions | 44 |
| Validasi SVG & Draw.io | 22/22 SVG + 10/10 Draw.io valid |
| Review kode | 8 temuan, seluruhnya ditindaklanjuti |

## Detail Smoke Test

| Route | Status | Redirect | Kesimpulan |
| --- | --- | --- | --- |
| `GET /` | 200 | — | Lulus |
| `GET /login` | 200 | — | Lulus |
| `GET /register` | 200 | — | Lulus |
| `GET /admin/login` | 200 | — | Lulus |
| `GET /dashboard` | 302 | `/login` | Lulus |
| `GET /profile` | 302 | `/login` | Lulus |
| `GET /admin/dashboard` | 302 | `/admin/login` | Lulus |
| `GET /admin/pendaftar` | 302 | `/admin/login` | Lulus |
| `GET /event/tidak-ada` | 404 | — | Lulus |

## Detail Pest

```text
Tests:      18 passed (44 assertions)
Duration:   ±1,2 detik
```

Cakupan test fitur:

- Halaman utama hanya menampilkan event `publish`.
- Detail event draft menghasilkan 404.
- Guest area peserta diarahkan ke `/login` dan guest area admin ke `/admin/login`.
- Admin aktif dapat login, admin nonaktif ditolak.
- Pendaftaran berhasil, duplikasi ditolak, kuota penuh ditolak, event selesai ditolak, event draft ditolak.
- Statistik dashboard admin sesuai data aktual.
- Admin hanya melihat dan memverifikasi pendaftar dari event miliknya.

## Temuan yang Sudah Diperbaiki

1. `RefreshDatabase` diaktifkan dan test fitur ditambahkan.
2. Statistik `Total Registrations` dan `Pending Approvals` dihitung dari data aktual.
3. Pendaftaran memvalidasi status publish, kuota, tanggal tutup, dan duplikasi.
4. Verifikasi dan daftar pendaftar dibatasi pada event milik admin yang login.
5. `is_active` admin diperiksa saat login.
6. Redirect guest admin diarahkan ke `/admin/login`.
7. Detail publik hanya menampilkan event `publish`.
8. Pilihan status event disamakan dengan enum database.

## Kesimpulan

Aplikasi dapat diakses melalui route publik, proteksi akses berjalan, dan seluruh test otomatis lulus. Temuan pengujian awal telah diperbaiki dan diverifikasi ulang sebelum aplikasi dinyatakan siap dikumpulkan.
