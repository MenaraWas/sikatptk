@extends('layouts.admin')

@section('title', 'Master Data PTK - Admin SIKAT PTK')

@section('content')
<div x-data="{ showAddModal: false, showImportModal: false }">
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Master Data PTK / Guru</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar Pendidik dan Tenaga Kependidikan untuk autocomplete form upload.</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="showImportModal = true" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl border border-teal-800 text-teal-800 hover:bg-teal-50 font-bold text-xs sm:text-sm flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import CSV Baru
            </button>

            <button @click="showAddModal = true" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl bg-teal-800 hover:bg-teal-900 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah PTK
            </button>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-6 flex items-center justify-between">
        <form method="GET" action="{{ route('admin.ptk.index') }}" class="flex items-center gap-3 w-full max-w-md">
            <input type="text" 
                   name="q" 
                   value="{{ $search }}" 
                   placeholder="Cari nama guru, NIP, atau status..." 
                   class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-xs sm:text-sm outline-none">
            <button type="submit" class="bg-teal-800 text-white px-4 py-2 rounded-xl text-xs font-bold">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.ptk.index') }}" class="text-xs text-slate-500 hover:text-slate-800">Reset</a>
            @endif
        </form>

        <span class="text-xs text-slate-500 hidden sm:inline">
            Total terdaftar: <strong>{{ $ptks->total() }} PTK</strong>
        </span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-10 text-center">No</th>
                        <th class="p-3.5">Nama Lengkap PTK</th>
                        <th class="p-3.5">NIP</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-center">Jenis Kelamin</th>
                        <th class="p-3.5">No WhatsApp</th>
                        <th class="p-3.5 text-center">Jml Kegiatan</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ptks as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $ptks->firstItem() + $loop->index }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-900">
                                {{ $p->nama }}
                            </td>
                            <td class="p-3.5 text-slate-600 font-mono text-xs">
                                {{ $p->nip ?: '-' }}
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $p->status_kepegawaian === 'PNS' ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $p->status_kepegawaian ?: 'Pegawai' }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center text-slate-600">
                                {{ $p->jenis_kelamin ?: '-' }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $p->no_hp ?: '-' }}
                            </td>
                            <td class="p-3.5 text-center font-bold text-teal-800">
                                {{ $p->kegiatans_count }}
                            </td>
                            <td class="p-3.5 text-center">
                                <form action="{{ route('admin.ptk.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus PTK ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                Tidak ada data PTK.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ptks->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $ptks->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah PTK -->
    <div x-show="showAddModal" 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative" @click.outside="showAddModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-5">
                <h3 class="text-base font-bold text-slate-800">Tambah Data PTK / Guru</h3>
                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.ptk.store') }}" method="POST">
                @csrf
                <div class="space-y-4 text-xs sm:text-sm">
                    <div>
                        <label class="block font-semibold text-slate-600 uppercase text-xs mb-1">Nama Lengkap & Gelar *</label>
                        <input type="text" name="nama" required class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-600 uppercase text-xs mb-1">NIP</label>
                            <input type="text" name="nip" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 uppercase text-xs mb-1">Status Kepegawaian</label>
                            <select name="status_kepegawaian" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none bg-white">
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="Non PNS">Non PNS / GTT</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-600 uppercase text-xs mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none bg-white">
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 uppercase text-xs mb-1">No WhatsApp / HP</label>
                            <input type="text" name="no_hp" class="w-full px-3 py-2 rounded-xl border border-slate-300 outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-teal-800 text-white text-xs font-bold">Simpan PTK</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Import CSV -->
    <div x-show="showImportModal" 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative" @click.outside="showImportModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-5">
                <h3 class="text-base font-bold text-slate-800">Import Data PTK dari CSV</h3>
                <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Unggah file format <strong>.CSV</strong> yang memuat kolom minimal: <code>nama, nip, no_hp, jenis_kelamin</code>.
            </p>

            <form action="{{ route('admin.ptk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 text-xs sm:text-sm">
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center">
                        <input type="file" name="csv_file" required accept=".csv,.txt" class="w-full text-xs">
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-teal-800 text-white text-xs font-bold">Unggah & Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
