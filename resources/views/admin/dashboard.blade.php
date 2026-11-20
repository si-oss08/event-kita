@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">

    <!-- Sidebar Admin -->
    <aside class="w-full md:w-64">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 md:sticky md:top-24 transition-colors duration-300">
            <div class="flex items-center gap-2.5 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center rounded-lg font-bold text-sm shadow-lg shadow-indigo-500/30">
                    EK
                </div>
                <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Admin Panel</h2>
            </div>
            <nav class="space-y-1.5 text-sm font-medium text-slate-600 dark:text-slate-300">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.events.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Event
                </a>
                <a href="{{ route('admin.pendaftar') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Pendaftar
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-700">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="w-full md:w-3/4">
        <div class="mb-6">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Overview</span>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">Dashboard Admin</h1>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 p-4 rounded-xl mb-6 border border-emerald-200 dark:border-emerald-500/20 font-medium flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-indigo-50 to-white dark:from-slate-800 dark:to-slate-800 p-6 rounded-2xl border border-indigo-100 dark:border-slate-700 shadow-sm hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Events</p>
                        <h3 class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $totalEvents }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-white dark:from-slate-800 dark:to-slate-800 p-6 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Registrations</p>
                        <h3 class="text-4xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalRegistrations }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-white dark:from-slate-800 dark:to-slate-800 p-6 rounded-2xl border border-amber-100 dark:border-slate-700 shadow-sm hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Pending Approvals</p>
                        <h3 class="text-4xl font-extrabold text-amber-500 dark:text-amber-400">{{ $pendingApprovals }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Events Table -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden transition-colors duration-300">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center gap-4">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Event Terbaru</h2>
                <a href="{{ route('admin.events.create') }}" class="text-sm bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl font-semibold shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all">+ Buat Baru</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[540px]">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="p-5 border-b border-slate-100 dark:border-slate-700">Judul Event</th>
                            <th class="p-5 border-b border-slate-100 dark:border-slate-700">Tanggal</th>
                            <th class="p-5 border-b border-slate-100 dark:border-slate-700">Status</th>
                            <th class="p-5 border-b border-slate-100 dark:border-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($events as $event)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="p-5 font-bold text-slate-800 dark:text-white">{{ $event->judul }}</td>
                            <td class="p-5 text-sm text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="p-5">
                                @php
                                    $badgeColor = match ($event->status) {
                                        'publish' => 'bg-emerald-500 shadow-emerald-500/30',
                                        'selesai' => 'bg-blue-500 shadow-blue-500/30',
                                        'batal' => 'bg-rose-500 shadow-rose-500/30',
                                        default => 'bg-slate-400 shadow-slate-400/30',
                                    };
                                @endphp
                                <span class="{{ $badgeColor }} text-white px-3 py-1 rounded-full text-xs font-extrabold shadow-sm">{{ strtoupper($event->status) }}</span>
                            </td>
                            <td class="p-5">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/40 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-slate-500 dark:text-slate-400">
                                <div class="text-4xl mb-2">📅</div>
                                Belum ada event.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
