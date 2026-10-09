@extends('layouts.app')

@section('title', 'Rekapitulasi Data SIKAT PTK - MAN 2 Bantul')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-teal-800 bg-teal-50 px-3 py-1 rounded-full mb-2">
                <span>📊 Rekapitulasi Terbuka</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
                Data Kegiatan & Pelatihan PTK
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Rekapitulasi kegiatan pengembangan keprofesian berkelanjutan Pendidik dan Tenaga Kependidikan MAN 2 Bantul.
            </p>
        </div>

        <a href="{{ route('submission.create') }}" 
           class="inline-flex items-center gap-2 bg-teal-800 hover:bg-teal-900 text-white font-bold text-xs sm:text-sm px-5 py-3 rounded-xl shadow-md transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            + Upload Kegiatan Baru
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl font-bold">
                📋
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Kegiatan Tercatat</p>
                <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalKegiatan) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold">
                ⏱️
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Akumulasi JPL</p>
                <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalJpl) }} <span class="text-xs font-semibold text-slate-400">Jam</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-800 flex items-center justify-center text-xl font-bold">
                👥
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Guru & Pegawai Terdata</p>
                <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalGuruBerpartisipasi) }} <span class="text-xs font-semibold text-slate-400">Orang</span></h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('rekap.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Text -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Pencarian</label>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ $search }}" 
                           placeholder="Cari nama guru, judul pelatihan, atau penyelenggara..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-xs sm:text-sm outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Filter Tahun -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tahun Pelaksanaan</label>
                <select name="tahun" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-xs sm:text-sm outline-none bg-white">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tombol -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-teal-800 hover:bg-teal-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Data
                </button>
                @if($search || $tahun || $jenis)
                    <a href="{{ route('rekap.index') }}" class="px-3 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-semibold flex items-center justify-center" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4">Nama Pendidik / Tenaga Kependidikan</th>
                        <th class="p-4">Judul Kegiatan & Pelatihan</th>
                        <th class="p-4">Penyelenggara</th>
                        <th class="p-4 text-center">Pelaksanaan</th>
                        <th class="p-4 text-center">JPL</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kegiatans as $k)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-center font-bold text-slate-400">
                                {{ $kegiatans->firstItem() + $loop->index }}
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-slate-900">{{ $k->submission?->nama_pengisi ?? $k->ptk?->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-500">NIP: {{ $k->submission?->nip ?? $k->ptk?->nip ?? 'Non-PNS' }}</p>
                            </td>
                            <td class="p-4 max-w-sm">
                                <p class="font-semibold text-slate-800 leading-snug">{{ $k->judul_pelatihan }}</p>
                                <span class="inline-block mt-1 text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">
                                    {{ $k->jenis_pelatihan }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">{{ $k->penyelenggara }}</td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <p class="font-medium text-slate-800">{{ $k->tanggal_pelaksanaan->format('d/m/Y') }}</p>
                                <span class="text-[11px] text-slate-400">Tahun {{ $k->tahun_pelaksanaan }}</span>
                            </td>
                            <td class="p-4 text-center font-bold text-slate-900 whitespace-nowrap">
                                {{ $k->jumlah_jpl }} JPL
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                @if($k->status_verifikasi === 'terverifikasi')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">
                                        ✓ Terverifikasi
                                    </span>
                                @elseif($k->status_verifikasi === 'ditolak')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-100 text-rose-800">
                                        ✕ Perlu Revisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">
                                        ⏳ Menunggu Verifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-500">
                                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    📂
                                </div>
                                <h4 class="font-bold text-base text-slate-700">Belum ada data kegiatan</h4>
                                <p class="text-xs text-slate-400 mt-1">Data kegiatan yang cocok dengan kriteria pencarian belum ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kegiatans->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $kegiatans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
