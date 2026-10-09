@extends('layouts.admin')

@section('title', 'Dashboard Administrator - SIKAT PTK')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800">Dashboard Pengelola</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Ringkasan aktivitas pelaporan kegiatan dan pelatihan PTK MAN 2 Bantul.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kegiatan</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($totalKegiatan) }}</span>
                <span class="text-xs text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg font-semibold">{{ $totalSubmission }} Pengiriman</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total JPL</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-emerald-700">{{ number_format($totalJpl) }}</span>
                <span class="text-xs text-slate-400 font-semibold">Jam Pelajaran</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perlu Diverifikasi</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-amber-600">{{ number_format($kegiatanPending) }}</span>
                <a href="{{ route('admin.kegiatan.index', ['status' => 'pending']) }}" class="text-xs text-amber-700 hover:underline font-bold">
                    Tinjau →
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Database GTK</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-black text-indigo-700">{{ number_format($totalPtk) }}</span>
                <a href="{{ route('admin.ptk.index') }}" class="text-xs text-indigo-600 hover:underline font-bold">
                    Kelola →
                </a>
            </div>
        </div>
    </div>

    <!-- Status Verifikasi Bar -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-8">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Status Verifikasi Berkas</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-between">
                <div>
                    <span class="text-xs text-amber-800 font-medium block">Menunggu Verifikasi</span>
                    <strong class="text-xl font-black text-amber-900">{{ $kegiatanPending }}</strong>
                </div>
                <span class="text-2xl">⏳</span>
            </div>

            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                <div>
                    <span class="text-xs text-emerald-800 font-medium block">Disetujui / Terverifikasi</span>
                    <strong class="text-xl font-black text-emerald-900">{{ $kegiatanTerverifikasi }}</strong>
                </div>
                <span class="text-2xl">✓</span>
            </div>

            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-between">
                <div>
                    <span class="text-xs text-rose-800 font-medium block">Perlu Revisi / Ditolak</span>
                    <strong class="text-xl font-black text-rose-900">{{ $kegiatanDitolak }}</strong>
                </div>
                <span class="text-2xl">✕</span>
            </div>
        </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">10 Pengunggahan Terbaru</h3>
                <p class="text-xs text-slate-400">Kegiatan yang baru saja diunggah oleh guru/pegawai</p>
            </div>
            <a href="{{ route('admin.kegiatan.index') }}" class="text-xs text-teal-800 font-bold hover:underline">
                Lihat Semua Data →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Kode / PTK</th>
                        <th class="p-3.5">Judul Pelatihan</th>
                        <th class="p-3.5 text-center">JPL</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentKegiatans as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3.5">
                                <span class="text-[11px] font-mono font-bold text-teal-800 block">{{ $item->submission?->batch_code }}</span>
                                <span class="font-bold text-slate-900">{{ $item->submission?->nama_pengisi ?? $item->ptk?->nama ?? '-' }}</span>
                            </td>
                            <td class="p-3.5 max-w-xs">
                                <p class="font-medium text-slate-800 truncate">{{ $item->judul_pelatihan }}</p>
                                <span class="text-xs text-slate-500">{{ $item->penyelenggara }}</span>
                            </td>
                            <td class="p-3.5 text-center font-bold text-slate-800">
                                {{ $item->jumlah_jpl }} Jam
                            </td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                @if($item->status_verifikasi === 'terverifikasi')
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Terverifikasi</span>
                                @elseif($item->status_verifikasi === 'ditolak')
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800">Ditolak</span>
                                @else
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Pending</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                <a href="{{ route('admin.kegiatan.show', $item) }}" class="text-xs text-teal-700 hover:text-teal-900 font-bold px-2.5 py-1 rounded bg-teal-50 hover:bg-teal-100">
                                    Detail / Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-xs">
                                Belum ada berkas kegiatan yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
