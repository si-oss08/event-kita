# Laporan Testing — EventKita

## 1. Tujuan Pengujian

Pengujian dilakukan untuk memverifikasi bahwa aplikasi EventKita dapat diakses, route berjalan sesuai rancangan, proteksi akses berfungsi, dan artefak desain valid. Laporan ini juga mendokumentasikan temuan perbaikan yang ditemukan selama pemeriksaan.

## 2. Lingkungan Pengujian

| Item | Keterangan |
| --- | --- |
| Sistem operasi | Windows |
| Framework | Laravel 13.x |
| Bahasa | PHP 8.3+ |
| Database runtime | MySQL — `event_kita` |
| Database test | SQLite `:memory:` (dari `phpunit.xml`) |
| Test runner | Pest 5.x |
| Web server | `php artisan serve --host=127.0.0.1 --port=8000` |
| Browser/HTTP client | `curl.exe` |
| Validasi SVG | PowerShell `[xml](Get-Content -Raw ...)` |

## 3. Metode Pengujian

1. **Smoke test HTTP** — melakukan request GET ke route publik dan terproteksi tanpa login, lalu mencatat status code dan redirect.
2. **Pengujian otomatis** — menjalankan test bawaan Laravel/Pest dengan `php artisan test --colors=never`.
3. **Validasi artefak** — memeriksa seluruh file SVG sebagai XML yang well-formed.
4. **Review kode statis** — memeriksa controller, model, migrasi, route, dan view untuk menemukan ketidaksesuaian dengan rancangan.

## 4. Kasus Uji Smoke Test HTTP

Perintah contoh:

```powershell
curl.exe -s -o NUL -w "%{http_code} %{redirect_url}" http://127.0.0.1:8000/dashboard
```

| ID | Route | Ekspektasi | Hasil | Status |
| --- | --- | --- | --- | --- |
| TC-01 | `GET /` | 200 | 200 | Lulus |
| TC-02 | `GET /login` | 200 | 200 | Lulus |
| TC-03 | `GET /register` | 200 | 200 | Lulus |
| TC-04 | `GET /admin/login` | 200 | 200 | Lulus |
| TC-05 | `GET /dashboard` tanpa login | 302 ke `/login` | 302 ke `/login` | Lulus |
| TC-06 | `GET /profile` tanpa login | 302 ke `/login` | 302 ke `/login` | Lulus |
| TC-07 | `GET /admin/dashboard` tanpa login | 302 ke `/admin/login` | 302 ke `/admin/login` | Lulus |
| TC-08 | `GET /admin/pendaftar` tanpa login | 302 ke `/admin/login` | 302 ke `/admin/login` | Lulus |
| TC-09 | `GET /event/tidak-ada` | 404 | 404 | Lulus |

Ringkasan smoke test: **9 dari 9 request menghasilkan status sesuai ekspektasi**. Guest area admin kini diarahkan ke `/admin/login` setelah perbaikan redirect.

## 5. Pengujian Otomatis Pest

Perintah:

```powershell
php artisan test --colors=never
```

Hasil terbaru:

| Metrik | Nilai |
| --- | --- |
| Total test | 18 |
| Lulus | 18 |
| Gagal | 0 |
| Assertions | 44 |

Test fitur baru mencakup alur utama aplikasi:

| Area | Kasus uji |
| --- | --- |
| Halaman publik | Hanya event `publish` yang tampil; detail event draft menghasilkan 404 |
| Autentikasi | Guest area peserta diarahkan ke `/login`; guest area admin diarahkan ke `/admin/login` |
| Login admin | Admin aktif dapat masuk; admin dengan `is_active = false` ditolak |
| Pendaftaran | Pendaftaran berhasil, duplikasi ditolak, kuota penuh ditolak, event selesai ditolak, event draft ditolak |
| Dashboard admin | Statistik total event, total pendaftaran, dan pending approval sesuai data aktual |
| Verifikasi | Admin hanya melihat dan memverifikasi pendaftar dari event miliknya sendiri |

Catatan riwayat: pada pengujian awal, 1 dari 2 test bawaan gagal dengan pesan `no such table: events` karena trait `RefreshDatabase` belum aktif dan skema database test belum dimigrasi. Kondisi tersebut sudah diperbaiki dengan mengaktifkan `RefreshDatabase` di `tests/Pest.php`:

```php
pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');
```

Seluruh 18 test kini lulus dan suite dapat dijalankan ulang dengan perintah di atas.

## 6. Validasi SVG dan Draw.io

Perintah:

```powershell
Get-ChildItem -Recurse -Include *.svg,*.drawio .\ErrorWeb | ForEach-Object {
    [xml](Get-Content -Raw $_.FullName) | Out-Null
}
```

| Folder | Jumlah SVG | Jumlah Draw.io | Hasil |
| --- | --- | --- | --- |
| `02-Rancangan-Sistem` | 9 | 10 | Semua valid sebagai XML |
| `03-Desain-UI-UX` | 13 | 0 | Semua valid sebagai XML |
| Total | 22 | 10 | Semua valid |

Catatan: 10 file Draw.io terdiri dari satu file multi-halaman `Rancangan-Sistem.drawio` (9 tab) dan sembilan file per diagram. File-file tersebut menggunakan format XML tidak terkompresi agar mudah diedit di diagrams.net.

Catatan lanjutan: verifikasi visual berupa screenshot tidak dapat dilakukan pada lingkungan agent, sehingga pemeriksaan difokuskan pada validitas XML, struktur elemen, dan konsistensi koordinat.

## 7. Temuan dan Tindak Lanjut

| ID | Temuan | Prioritas | Status | Perbaikan |
| --- | --- | --- | --- | --- |
| F-01 | Guest admin diarahkan ke `/login`, bukan `/admin/login` | Sedang | Selesai | `redirectGuestsTo()` di `bootstrap/app.php` membedakan area admin |
| F-02 | Kartu `Total Registrations` dan `Pending Approvals` bernilai tetap `0` | Tinggi | Selesai | Statistik dihitung dari `Registrasi` pada event milik admin |
| F-03 | Pendaftaran tidak memeriksa event, status publish, kuota, atau tanggal | Tinggi | Selesai | Validasi event, tanggal tutup, kuota, dan duplikasi di `RegistrasiController@store` |
| F-04 | Verifikasi tidak dibatasi pada event milik admin | Tinggi | Selesai | `verifikasi()` dan daftar pendaftar difilter melalui relasi `event.admin_id` |
| F-05 | `is_active` tidak diperiksa saat login admin | Tinggi | Selesai | Login admin memakai kredensial + `is_active = true` |
| F-06 | Detail publik tidak memfilter `status = publish` | Sedang | Selesai | Query detail menambahkan `where('status', 'publish')` |
| F-07 | Enum status event lebih luas dari pilihan form | Rendah | Selesai | Form dan validasi kini mendukung `draft`, `publish`, `selesai`, dan `batal` |
| F-08 | Test feature gagal karena skema test belum dimigrasi | Tinggi | Selesai | `RefreshDatabase` aktif dan 16 test fitur baru ditambahkan |

## 8. Kesimpulan Pengujian

- Seluruh route yang diuji merespons sesuai ekspektasi dasar.
- Proteksi akses peserta dan admin berfungsi: route terproteksi mengarahkan guest ke halaman login yang sesuai.
- Halaman detail event draft/tidak ditemukan menghasilkan 404.
- Seluruh 22 artefak SVG dan 10 file Draw.io valid sebagai XML dan dapat dipakai sebagai dokumentasi desain.
- Test otomatis lulus seluruhnya: **18 test, 44 assertion, 0 gagal**.
- Delapan temuan pengujian telah ditindaklanjuti dan diverifikasi ulang.

Perbaikan dilakukan setelah pengujian awal dan tidak mengubah cakupan fitur aplikasi. Seluruh perubahan telah diverifikasi dengan test otomatis dan pemeriksaan manual pada route utama.

## 9. Daftar Dokumen Terkait

- [`Hasil-Pengujian.md`](Hasil-Pengujian.md) — rekap ringkas hasil pengujian.
- [`../07-Laporan-Akhir/Laporan-Akhir.md`](../07-Laporan-Akhir/Laporan-Akhir.md) — laporan akhir proyek.
