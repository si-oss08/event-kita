<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrasiController extends Controller
{
    // ==========================================
    // FITUR UNTUK USER (PESERTA)
    // ==========================================

    // 1. Menampilkan Dashboard User (Riwayat Pendaftaran)
    public function index()
    {
        $registrasis = Registrasi::with('event')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('registrasis'));
    }

    // 2. Proses Mendaftar Event
    public function store(Request $request, $event_id)
    {
        $event = Event::whereKey($event_id)->first();

        // Event harus ada dan sudah dipublikasikan
        if (! $event || $event->status !== 'publish') {
            return back()->with('error', 'Event tidak ditemukan atau belum dibuka untuk pendaftaran.');
        }

        // Pendaftaran ditutup setelah tanggal selesai event
        if (now()->greaterThan($event->tanggal_selesai)) {
            return back()->with('error', 'Pendaftaran event ini sudah ditutup.');
        }

        // Cek apakah user sudah pernah daftar di event ini
        $sudahDaftar = Registrasi::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();

        if ($sudahDaftar) {
            return back()->with('error', 'Kamu sudah mendaftar di event ini!');
        }

        return DB::transaction(function () use ($event) {
            // Kunci baris event agar kuota tidak terlampaui saat banyak pendaftar bersamaan
            $event = Event::whereKey($event->id)->lockForUpdate()->firstOrFail();

            // Cek ulang di dalam transaksi untuk mencegah pendaftaran ganda bersamaan
            $sudahDaftar = Registrasi::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->exists();

            if ($sudahDaftar) {
                return back()->with('error', 'Kamu sudah mendaftar di event ini!');
            }

            $terisi = $event->registrasis()
                ->whereIn('status', ['pending', 'diterima', 'hadir'])
                ->count();

            if ($terisi >= $event->kuota) {
                return back()->with('error', 'Kuota event ini sudah penuh.');
            }

            // Buat kode registrasi unik (misal: EVT-ABCD1234)
            do {
                $kodeRegistrasi = 'EVT-'.strtoupper(Str::random(8));
            } while (Registrasi::withTrashed()->where('kode_registrasi', $kodeRegistrasi)->exists());

            Registrasi::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'kode_registrasi' => $kodeRegistrasi,
                'status' => 'pending',
                'tanggal_daftar' => now(),
            ]);

            return redirect()->route('user.dashboard')->with('success', 'Berhasil mendaftar event! Silakan cek tiketmu.');
        });
    }

    // 3. Menampilkan Halaman E-Tiket User
    public function showTicket($kode_registrasi)
    {
        $tiket = Registrasi::with('event')
            ->where('kode_registrasi', $kode_registrasi)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.ticket', compact('tiket'));
    }

    // ==========================================
    // FITUR UNTUK ADMIN (PENYELENGGARA)
    // ==========================================

    // 4. Menampilkan Daftar Pendaftar (Untuk Verifikasi)
    public function adminPendaftar()
    {
        // Admin hanya melihat pendaftar dari event miliknya sendiri
        $pendaftars = Registrasi::with(['user', 'event'])
            ->whereHas('event', fn ($query) => $query->where('admin_id', Auth::guard('admin')->id()))
            ->latest()
            ->get();

        return view('admin.pendaftar', compact('pendaftars'));
    }

    // 5. Memproses Terima/Tolak Pendaftaran
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        // Admin hanya boleh memverifikasi pendaftar dari event miliknya sendiri
        $registrasi = Registrasi::whereKey($id)
            ->whereHas('event', fn ($query) => $query->where('admin_id', Auth::guard('admin')->id()))
            ->firstOrFail();

        $registrasi->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui!');
    }
}
