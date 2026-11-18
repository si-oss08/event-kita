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
| TC-07 | `GET /admin/dashboard` tanpa login | 302 ke halaman login | 302 ke `/login` | Lulus dengan catatan |
| TC-08 | `GET /admin/pendaftar` tanpa login | 302 ke halaman login | 302 ke `/login` | Lulus dengan catatan |
| TC-09 | `GET /event/tidak-ada` | 404 | 404 | Lulus |

Ringkasan smoke test: **9 dari 9 request menghasilkan status sesuai ekspektasi**. Dua di antaranya diberi catatan karena admin diarahkan ke `/login`, bukan `/admin/login`.

## 5. Pengujian Otomatis Pest

Perintah:

```powershell
php artisan test --colors=never
```

Hasil:

| Metrik | Nilai |
| --- | --- |
| Total test | 2 |
| Lulus | 1 |
| Gagal | 1 |
| Assertions | 2 |

Detail:

| Test | Status | Keterangan |
| --- | --- | --- |
| `tests/Unit/ExampleTest.php` — `that true is true` | Lulus | Assertion dasar berhasil |
| `tests/Feature/ExampleTest.php` — `the application returns a successful response` | Gagal | Response 500, bukan 200 |

Penyebab kegagalan:

```text
Expected response status code [200] but received 500.
SQLSTATE[HY000]: General error: 1 no such table: events
(Connection: sqlite, Database: :memory:)
SQL: select * from "events" where "status" = publish and "events"."deleted_at" is null order by "created_at" desc
```

Analisis: `phpunit.xml` menggunakan SQLite `:memory:`, tetapi trait `RefreshDatabase` pada `tests/Pest.php` masih dikomentangi. Akibatnya, tabel belum dibuat ketika `EventController@index` menjalankan query.

Rekomendasi: aktifkan `RefreshDatabase` pada seluruh test feature:

```php
pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');
```

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

## 7. Temuan Perbaikan

| ID | Temuan | Dampak | Prioritas | Rekomendasi |
| --- | --- | --- | --- | --- |
| F-01 | Guest admin diarahkan ke `/login`, bukan `/admin/login` | Pengalaman login admin kurang konsisten | Sedang | Tambahkan `redirectTo()` pada middleware `Authenticate` atau sesuaikan nama route login |
| F-02 | Kartu `Total Registrations` dan `Pending Approvals` di dashboard admin bernilai tetap `0` | Statistik tidak mencerminkan data sebenarnya | Tinggi | Hitung dari `Registrasi` yang dikelompokkan per admin |
| F-03 | `RegistrasiController@store` tidak memeriksa keberadaan event, status publish, kuota, atau tanggal | Peserta dapat mendaftar ke event draft/null/penuh | Tinggi | Validasi event sebelum membuat registrasi dan hitung jumlah pendaftar |
| F-04 | `verifikasi()` memakai `Registrasi::findOrFail($id)` tanpa batasan `admin_id` | Admin dapat memverifikasi pendaftar event milik admin lain | Tinggi | Filter registrasi melalui relasi `event.admin_id` milik admin bersangkutan |
| F-05 | `is_active` tidak diperiksa saat login admin | Admin nonaktif masih bisa masuk | Tinggi | Tambahkan pengecekan `is_active` pada provider atau `adminLogin` |
| F-06 | `EventController@show` tidak memfilter `status = publish` | Event draft dapat diakses jika slug diketahui | Sedang | Tambahkan `where('status', 'publish')` pada query detail publik |
| F-07 | Migrasi `events` mengizinkan `selesai` dan `batal`, tetapi form hanya `draft`/`publish` | Inkonsistensi status data | Rendah | Samakan pilihan form dengan enum atau batasi enum sesuai kebutuhan |
| F-08 | Test feature gagal karena skema test belum dimigrasi | CI/test tidak hijau | Tinggi | Aktifkan `RefreshDatabase` di `tests/Pest.php` |

## 8. Kesimpulan Pengujian

- Seluruh route yang diuji merespons sesuai ekspektasi dasar.
- Proteksi akses peserta dan admin berfungsi: route terproteksi mengarahkan guest ke halaman login.
- Halaman detail yang tidak ditemukan menghasilkan 404 dengan benar.
- Seluruh 22 artefak SVG dan 10 file Draw.io valid sebagai XML dan dapat dipakai sebagai dokumentasi desain.
- Test otomatis belum lulus karena konfigurasi database test belum dimigrasi.
- Terdapat delapan temuan perbaikan, dengan empat di antaranya berprioritas tinggi.

Pengujian ini **tidak mengubah kode aplikasi**. Seluruh temuan dicatat sebagai rekomendasi untuk perbaikan berikutnya.

## 9. Daftar Dokumen Terkait

- [`Hasil-Pengujian.md`](Hasil-Pengujian.md) — rekap ringkas hasil pengujian.
- [`../07-Laporan-Akhir/Laporan-Akhir.md`](../07-Laporan-Akhir/Laporan-Akhir.md) — laporan akhir proyek.
