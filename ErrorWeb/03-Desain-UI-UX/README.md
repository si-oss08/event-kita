# Desain UI/UX — EventKita

Folder ini berisi seluruh desain antarmuka EventKita dalam format **SVG hitam putih**. Desain dibuat sebagai wireframe high-fidelity dengan komponen konsisten agar mudah dicetak, dibagikan, dan dijadikan acuan implementasi.

## 1. Prinsip Desain

1. **Monokromatik** — hanya menggunakan hitam, putih, dan gradasi abu-abu.
2. **Konsistensi** — navbar, kartu, tombol, field, badge, dan footer memakai bentuk yang sama di semua halaman.
3. **Hierarki jelas** — judul tebal, subjudul lebih ringan, label kecil, dan aksi utama berupa tombol solid hitam.
4. **Kepadatan informasi** — data penting ditampilkan dalam kartu dan tabel agar mudah dipindai.
5. **Arah pengguna jelas** — alur navigasi diberikan terpisah pada `12-Alur-Navigasi.svg`.
6. **Print-friendly** — SVG dapat dibuka dan dicetak tanpa kehilangan detail.

## 2. Style Guide

Artefak: [`00-Style-Guide.svg`](00-Style-Guide.svg)

| Elemen | Spesifikasi |
| --- | --- |
| Warna utama | `#111111` (hitam tinta) |
| Teks utama | `#222222` |
| Teks sekunder | `#555555` |
| Teks pendukung | `#8a8a8a` |
| Garis | `#c9c9c9`, `#9a9a9a` |
| Permukaan | `#ffffff`, `#f4f4f4`, `#e9e9e9`, `#dcdcdc` |
| Font | Inter / Segoe UI / Arial |
| Font kode | Cascadia Mono / Consolas / monospace |
| Radius umum | 8–12 px |
| Ukuran kanvas | 1280 × 900 px |
| Bingkai browser | 40,84 → 1240,852 |

### Komponen

- **Tombol primer**: isi hitam, teks putih, radius 9 px.
- **Tombol sekunder**: isi putih, garis hitam, teks hitam.
- **Field**: label 10,5 px tebal, kotak tinggi 40 px, placeholder abu-abu muda.
- **Badge**: bentuk pil, isi putih atau hitam, teks 8,5 px tebal.
- **Kartu**: garis abu-abu, sudut membulat, bayangan disimulasikan dengan garis.
- **Navbar**: logo `EK`, tautan Home/Events, serta area auth kanan.
- **Footer**: latar hitam, logo putih, teks abu-abu muda.

## 3. Daftar Layar

| No | File | Halaman | Fokus Utama |
| --- | --- | --- | --- |
| 1 | [`00-Style-Guide.svg`](00-Style-Guide.svg) | Style guide | Warna, tipografi, komponen |
| 2 | [`01-Landing-Page.svg`](01-Landing-Page.svg) | Landing page | Hero, statistik, daftar event publish |
| 3 | [`02-Login-Peserta.svg`](02-Login-Peserta.svg) | Login peserta | Form email & password |
| 4 | [`03-Registrasi-Peserta.svg`](03-Registrasi-Peserta.svg) | Registrasi peserta | Form nama, email, password, konfirmasi |
| 5 | [`04-Detail-Event.svg`](04-Detail-Event.svg) | Detail event | Poster, informasi, tombol daftar |
| 6 | [`05-Dashboard-Peserta.svg`](05-Dashboard-Peserta.svg) | Dashboard peserta | Riwayat pendaftaran & status |
| 7 | [`06-E-Tiket.svg`](06-E-Tiket.svg) | E-tiket | Kode registrasi, detail event, status |
| 8 | [`07-Profil-Peserta.svg`](07-Profil-Peserta.svg) | Profil peserta | Ubah nama & password |
| 9 | [`08-Login-Admin.svg`](08-Login-Admin.svg) | Login admin | Form username & password |
| 10 | [`09-Dashboard-Admin.svg`](09-Dashboard-Admin.svg) | Dashboard admin | Statistik, event terbaru, sidebar |
| 11 | [`10-Form-Event.svg`](10-Form-Event.svg) | Form event | Input event & unggah poster |
| 12 | [`11-Verifikasi-Pendaftar.svg`](11-Verifikasi-Pendaftar.svg) | Verifikasi pendaftar | Tabel pendaftar & aksi terima/tolak |
| 13 | [`12-Alur-Navigasi.svg`](12-Alur-Navigasi.svg) | Alur navigasi | Peta halaman publik, peserta, admin |

## 4. Alur Navigasi

Artefak: [`12-Alur-Navigasi.svg`](12-Alur-Navigasi.svg)

### Publik

`Landing Page` → `Detail Event` → `Login Peserta` → `Dashboard Peserta`.

Tautan alternatif dari landing page menuju `Registrasi Peserta`, dan dari landing page juga tersedia akses ke `Login Admin`.

### Peserta

`Dashboard Peserta` → `E-Tiket`, `Profil Peserta`, atau kembali ke daftar event untuk mendaftar lagi.

### Admin

`Login Admin` → `Dashboard Admin` → `Kelola Event` atau `Verifikasi Pendaftar`.

## 5. Catatan Implementasi

- Desain dibuat untuk lebar desktop 1280 px; implementasi Blade memakai utilitas responsif Tailwind untuk layar lebih kecil.
- Status pada desain menggunakan representasi teks/badge: `pending`, `diterima`, `ditolak`, `publish`, `draft`.
- Tombol dan field pada SVG merupakan panduan visual, bukan komponen interaktif.
- Seluruh SVG telah divalidasi sebagai XML sehingga aman dibuka di browser maupun editor vektor.

## 6. Validasi Artefak

Semua file SVG di folder ini dan folder `02-Rancangan-Sistem` telah melalui pemeriksaan:

```powershell
Get-ChildItem -Recurse -Filter *.svg .\ErrorWeb | ForEach-Object {
    [xml](Get-Content -Raw $_.FullName) | Out-Null
}
```

Hasil: seluruh SVG valid sebagai XML.
