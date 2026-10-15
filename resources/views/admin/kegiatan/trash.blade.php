@extends('layouts.admin')

@section('title', 'Data Terhapus - Admin SIKAT PTK')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Data Terhapus</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kegiatan yang dihapus tidak dihitung di rekap. Pulihkan jika salah hapus, atau hapus permanen untuk membersihkan berkasnya.
            </p>
        </div>
        <a href="{{ route('admin.kegiatan.index') }}"
           class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition">
            Kembali ke Data Kegiatan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-10 text-center">No</th>
                        <th class="p-3.5">Kode / PTK</th>
                        <th class="p-3.5">Judul Kegiatan</th>
                        <th class="p-3.5 text-center">JPL</th>
                        <th class="p-3.5 text-center">Dihapus Pada</th>
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
                            </td>
                            <td class="p-3.5 max-w-sm">
                                <p class="font-semibold text-slate-800 leading-snug">{{ $k->judul_pelatihan }}</p>
                                <span class="text-[11px] text-slate-400">{{ $k->penyelenggara }}</span>
                            </td>
                            <td class="p-3.5 text-center font-bold text-slate-900 whitespace-nowrap">{{ $k->jumlah_jpl }} JPL</td>
                            <td class="p-3.5 text-center whitespace-nowrap text-slate-600">
                                {{ $k->deleted_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <form action="{{ route('admin.kegiatan.restore', $k->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-semibold text-xs">
                                            Pulihkan
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.kegiatan.force-destroy', $k->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus PERMANEN? Data dan berkas sertifikat akan hilang dan tidak bisa dipulihkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold text-xs">
                                            Hapus Permanen
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400">
                                Tidak ada data yang dihapus.
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