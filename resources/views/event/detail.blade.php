@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 px-4 sm:px-6 lg:px-8 pb-16">
    <!-- Breadcrumb / Back -->
    <a href="{{ route('home') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white text-sm font-medium mb-6 inline-flex items-center gap-2">
        &larr; Kembali ke Daftar Event
    </a>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-300">
        <div class="flex flex-col">

            <!-- Atas: Gambar (banner horizontal) -->
            <div class="relative w-full aspect-[16/9] md:aspect-[21/9] bg-slate-100 dark:bg-slate-800">
                @if($event->poster)
                    <img src="{{ asset('storage/' . $event->poster) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $event->judul }}">
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-gradient-to-br from-indigo-50 via-slate-100 to-purple-50 dark:from-slate-800 dark:via-slate-800 dark:to-slate-700">
                        <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center shadow-sm">
                            <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Poster Belum Tersedia</p>
                    </div>
                @endif
            </div>

            <!-- Bawah: Detail -->
            <div class="w-full p-6 md:p-10">
                <!-- Judul & Harga -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white leading-tight">{{ $event->judul }}</h1>
                    <p class="text-indigo-600 dark:text-indigo-400 font-bold text-xl whitespace-nowrap">
                        {{ $event->harga == 0 ? 'Gratis' : 'Rp ' . number_format($event->harga, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Info Event -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 text-slate-700 dark:text-slate-300">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📅</span>
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">Tanggal & Waktu</p>
                            <p class="text-sm">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('l, d F Y - H:i') }} WIB</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Selesai: {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d F Y - H:i') }} WIB</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📍</span>
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">Lokasi</p>
                            <p class="text-sm">{{ $event->lokasi }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">👥</span>
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">Kuota Tersedia</p>
                            <p class="text-sm">{{ $sisaKuota }} dari {{ $event->kuota }} Peserta</p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mb-8">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">Tentang Event</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed max-w-3xl">{{ $event->deskripsi }}</p>
                </div>

                <!-- Tombol Pendaftaran -->
                <div class="max-w-md">
                    @if($sudahDaftar)
                        <div class="bg-green-50 dark:bg-emerald-500/10 text-green-700 dark:text-emerald-400 p-4 rounded-xl text-center border border-green-200 dark:border-emerald-500/20">
                            <p class="font-bold">✅ Kamu sudah terdaftar di event ini.</p>
                            <a href="{{ route('user.dashboard') }}" class="text-sm underline mt-1 block hover:text-green-800 dark:hover:text-emerald-300">Lihat Tiketmu di Dashboard</a>
                        </div>
                    @elseif($pendaftaranDitutup)
                        <div class="bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 p-4 rounded-xl text-center border border-slate-200 dark:border-slate-600">
                            <p class="font-bold">Pendaftaran event ini sudah ditutup.</p>
                        </div>
                    @elseif($sisaKuota <= 0)
                        <div class="bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 p-4 rounded-xl text-center border border-amber-200 dark:border-amber-500/20">
                            <p class="font-bold">Kuota event ini sudah penuh.</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('event.daftar', $event->id) }}">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-4 rounded-xl hover:shadow-lg hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200 shadow-lg shadow-indigo-500/20 text-lg">
                                Daftar Sekarang
                            </button>
                        </form>
                        <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-3">Pastikan kamu sudah login sebelum mendaftar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
