@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 px-4 sm:px-6 lg:px-8 pb-16">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white">&larr; Kembali</a>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Create Event</h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 text-sm border border-red-100 dark:border-red-500/20">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Event Title</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Description</label>
                <textarea name="deskripsi" rows="4" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Location</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Start Date & Time</label>
                    <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">End Date & Time</label>
                    <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Quota (Peserta)</label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required min="1">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Price (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', 0) }}" class="w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required min="0">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Poster Upload (Opsional)</label>
                <input type="file" name="poster" accept="image/*" class="w-full border border-slate-300 dark:border-slate-600 px-4 py-2 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-300 dark:hover:file:bg-indigo-500/20">
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Format: JPG, PNG. Maksimal: 2MB.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Status</label>
                <select name="status" class="w-full border border-slate-300 dark:border-slate-600 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-white dark:bg-slate-900 text-slate-900 dark:text-white transition-all">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish (Tampilkan ke User)</option>
                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai (Event Telah Berlangsung)</option>
                    <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal (Event Dibatalkan)</option>
                </select>
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold px-8 py-3 rounded-lg hover:shadow-lg hover:shadow-indigo-500/40 transition-all shadow-md">Save Event</button>
                <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold px-8 py-3 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
