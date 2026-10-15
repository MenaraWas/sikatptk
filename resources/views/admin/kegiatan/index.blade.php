@extends('layouts.admin')

@section('title', 'Kelola Data Kegiatan PTK - Admin SIKAT PTK')

@section('content')
<div>
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Data Kegiatan PTK</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola dan verifikasi berkas bukti sertifikat kegiatan.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.kegiatan.trash') }}"
               class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm flex items-center gap-2 transition">
                Data Terhapus
                @if($jumlahTerhapus > 0)
                    <span class="bg-rose-100 text-rose-700 text-[11px] px-2 py-0.5 rounded-full">{{ $jumlahTerhapus }}</span>
                @endif
            </a>
            <a href="{{ route('admin.kegiatan.export', request()->query()) }}" 
               class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel / CSV
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.kegiatan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Text -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Cari Kegiatan / PTK</label>
                <input type="text" 
                       name="q" 
                       value="{{ $search }}" 
                       placeholder="Nama guru, judul, atau kode batch..." 
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-xs sm:text-sm outline-none">
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Status Verifikasi</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-xs sm:text-sm outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="terverifikasi" {{ $status == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi (Disetujui)</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak (Perlu Revisi)</option>
                </select>
            </div>

            <!-- Filter Tahun -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tahun Pelaksanaan</label>
                <select name="tahun" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-xs sm:text-sm outline-none bg-white">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-teal-800 hover:bg-teal-900 text-white font-bold py-2 px-4 rounded-xl text-xs sm:text-sm transition">
                    Filter
                </button>
                @if($search || $status || $tahun || $ptkId)
                    <a href="{{ route('admin.kegiatan.index') }}" class="px-3 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-semibold" title="Reset">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-10 text-center">No</th>
                        <th class="p-3.5">Kode / PTK</th>
                        <th class="p-3.5">Judul Kegiatan & Pelatihan</th>
                        <th class="p-3.5">Penyelenggara</th>
                        <th class="p-3.5 text-center">Pelaksanaan</th>
                        <th class="p-3.5 text-center">JPL</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kegiatans as $k)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $kegiatans->firstItem() + $loop->index }}
                            </td>
                            <td class="p-3.5 whitespace-nowrap">
                                <span class="text-[11px] font-mono font-bold text-teal-800 block">{{ $k->submission?->batch_code ?? '-' }}</span>
                                <span class="font-bold text-slate-900 block">{{ $k->submission?->nama_pengisi ?? $k->ptk?->nama ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $k->submission?->nip ?? $k->ptk?->nip ?? 'Non-PNS' }}</span>
                            </td>
                            <td class="p-3.5 max-w-sm">
                                <p class="font-semibold text-slate-800 leading-snug">{{ $k->judul_pelatihan }}</p>
                                <span class="inline-block mt-1 text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">
                                    {{ $k->jenis_pelatihan }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600">{{ $k->penyelenggara }}</td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                <p class="font-medium text-slate-800">{{ $k->tanggal_pelaksanaan->format('d/m/Y') }}</p>
                                <span class="text-[11px] text-slate-400">Tahun {{ $k->tahun_pelaksanaan }}</span>
                            </td>
                            <td class="p-3.5 text-center font-bold text-slate-900 whitespace-nowrap">
                                {{ $k->jumlah_jpl }} JPL
                            </td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                @if($k->status_verifikasi === 'terverifikasi')
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">✓ Terverifikasi</span>
                                @elseif($k->status_verifikasi === 'ditolak')
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-100 text-rose-800">✕ Ditolak</span>
                                @else
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">⏳ Pending</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.kegiatan.show', $k) }}" 
                                       class="px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 hover:bg-teal-100 font-semibold text-xs" 
                                       title="Tinjau & Verifikasi">
                                        Periksa
                                    </a>
                                    
                                    <a href="{{ route('admin.kegiatan.download', $k) }}" 
                                       class="px-2 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs" 
                                       title="Unduh Sertifikat">
                                        ⬇
                                    </a>

                                    <form action="{{ route('admin.kegiatan.destroy', $k) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus kegiatan ini? Data dipindahkan ke Data Terhapus dan masih bisa dipulihkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold text-xs"
                                                title="Hapus kegiatan">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-slate-400">
                                Tidak ada data kegiatan yang sesuai filter.
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