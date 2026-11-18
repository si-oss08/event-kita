# Hasil Pengujian — EventKita

Dokumen ini merupakan rekap ringkas dari [`Laporan-Testing.md`](Laporan-Testing.md).

## Ringkasan Eksekutif

| Pengujian | Hasil |
| --- | --- |
| Smoke test HTTP | 9/9 sesuai ekspektasi |
| Pest / PHPUnit | 1 lulus, 1 gagal dari 2 test |
| Validasi SVG & Draw.io | 22/22 SVG + 10/10 Draw.io valid |
| Review kode | 8 temuan perbaikan |

## Detail Smoke Test

| Route | Status | Redirect | Kesimpulan |
| --- | --- | --- | --- |
| `GET /` | 200 | — | Lulus |
| `GET /login` | 200 | — | Lulus |
| `GET /register` | 200 | — | Lulus |
| `GET /admin/login` | 200 | — | Lulus |
| `GET /dashboard` | 302 | `/login` | Lulus |
| `GET /profile` | 302 | `/login` | Lulus |
| `GET /admin/dashboard` | 302 | `/login` | Lulus, perlu perbaikan arah redirect |
| `GET /admin/pendaftar` | 302 | `/login` | Lulus, perlu perbaikan arah redirect |
| `GET /event/tidak-ada` | 404 | — | Lulus |

## Detail Pest

```text
Tests:      1 failed, 1 passed
Assertions: 2
Error:      SQLSTATE[HY000]: General error: 1 no such table: events
```

Penyebab: test memakai SQLite `:memory:` tanpa migrasi karena `RefreshDatabase` masih dinonaktifkan di `tests/Pest.php`.

## Temuan Prioritas Tinggi

1. `RefreshDatabase` belum aktif pada test feature.
2. Statistik `Total Registrations` dan `Pending Approvals` hard-coded `0`.
3. Pendaftaran event tidak memvalidasi status/kuota/waktu event.
4. Verifikasi pendaftar tidak dibatasi pada event milik admin yang login.
5. `is_active` admin tidak diperiksa saat login.

## Kesimpulan

Aplikasi dapat diakses melalui route publik dan proteksi akses dasar berjalan. Namun, test otomatis belum hijau dan terdapat beberapa celah logika bisnis yang perlu diperbaiki sebelum produksi. Seluruh temuan sudah dicatat tanpa mengubah kode aplikasi.
