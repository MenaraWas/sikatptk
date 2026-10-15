@extends('layouts.admin')

@section('title', 'Detail Kegiatan PTK - Admin SIKAT PTK')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-5">
        <a href="{{ route('admin.kegiatan.index') }}" class="text-xs font-semibold text-teal-800 hover:underline flex items-center gap-1">
            ← Kembali ke Daftar Kegiatan
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <!-- Header -->
        <div class="bg-slate-50 border-b border-slate-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-mono font-bold text-teal-800 bg-teal-100/70 px-2.5 py-1 rounded-md">
                    {{ $kegiatan->submission?->batch_code ?? 'BATCH-' . $kegiatan->id }}
                </span>
                <h2 class="text-xl font-bold text-slate-900 mt-2">{{ $kegiatan->judul_pelatihan }}</h2>
                <p class="text-xs text-slate-500">Penyelenggara: <strong>{{ $kegiatan->penyelenggara }}</strong></p>
            </div>

            <!-- Current Status Badge -->
            <div>
                @if($kegiatan->status_verifikasi === 'terverifikasi')
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800">
                        ✓ Disetujui / Terverifikasi
                    </span>
                @elseif($kegiatan->status_verifikasi === 'ditolak')
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-rose-100 text-rose-800">
                        ✕ Perlu Revisi / Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-amber-100 text-amber-800">
                        ⏳ Menunggu Verifikasi
                    </span>
                @endif
            </div>
        </div>

        <!-- Body Detail -->
        <div class="p-6 sm:p-8 space-y-6">
            <!-- Data PTK -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Informasi Guru / Pengunggah</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-400 block text-xs">Nama:</span>
                        <strong class="text-slate-800 font-semibold">{{ $kegiatan->submission?->nama_pengisi ?? $kegiatan->ptk?->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs">NIP:</span>
                        <strong class="text-slate-800">{{ $kegiatan->submission?->nip ?? $kegiatan->ptk?->nip ?? 'Non-PNS' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs">No WhatsApp:</span>
                        <strong class="text-slate-800">{{ $kegiatan->submission?->no_hp ?? $kegiatan->ptk?->no_hp ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Rincian Pelatihan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm border-b border-slate-100 pb-6">
                <div>
                    <span class="text-slate-400 block text-xs">Jenis Pelatihan:</span>
                    <strong class="text-slate-800">{{ $kegiatan->jenis_pelatihan }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Jumlah JPL:</span>
                    <strong class="text-slate-800">{{ $kegiatan->jumlah_jpl }} Jam Pelajaran</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Tanggal Pelaksanaan:</span>
                    <strong class="text-slate-800">{{ $kegiatan->tanggal_pelaksanaan->isoFormat('D MMMM Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Tahun Pelaksanaan:</span>
                    <strong class="text-slate-800">{{ $kegiatan->tahun_pelaksanaan }}</strong>
                </div>
            </div>

            <!-- Berkas Sertifikat Preview & Download -->
            <div class="border-b border-slate-100 pb-6">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Berkas Bukti Sertifikat</h4>
                
                @if($kegiatan->file_sertifikat)
                    <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-teal-50/60 rounded-2xl border border-teal-100 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-teal-700 text-white flex items-center justify-center text-lg">📄</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $kegiatan->file_original_name }}</p>
                                <p class="text-[11px] text-slate-500">{{ $kegiatan->file_size_human }} &bull; {{ $kegiatan->file_mime }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.kegiatan.preview', $kegiatan) }}" 
                               target="_blank" 
                               class="px-4 py-2 rounded-xl bg-white border border-teal-200 text-teal-800 text-xs font-bold hover:bg-teal-50 transition">
                                Buka di Tab Baru ↗
                            </a>
                            <a href="{{ route('admin.kegiatan.download', $kegiatan) }}" 
                               class="px-4 py-2 rounded-xl bg-teal-800 text-white text-xs font-bold hover:bg-teal-900 transition flex items-center gap-1">
                                Unduh Berkas
                            </a>
                        </div>
                    </div>

                    <!-- PDF / Image Inline Embed via secure route (tidak bergantung pada symlink) -->
                    <div class="mt-4 border border-slate-200 rounded-2xl overflow-hidden bg-slate-900">
                        @if(str_contains($kegiatan->file_mime, 'pdf'))
                            <iframe src="{{ route('admin.kegiatan.preview', $kegiatan) }}" class="w-full h-96 border-none" title="Preview PDF"></iframe>
                        @elseif(str_contains($kegiatan->file_mime, 'image'))
                            <img src="{{ route('admin.kegiatan.preview', $kegiatan) }}" alt="Sertifikat" class="max-h-96 mx-auto object-contain p-2">
                        @endif
                    </div>
                @else
                    <p class="text-xs text-rose-500 font-semibold">Tidak ada berkas yang diunggah.</p>
                @endif
            </div>

            <!-- Form Ubah Status Verifikasi -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Tindakan Verifikasi Petugas</h4>
                <form action="{{ route('admin.kegiatan.status', $kegiatan) }}" method="POST" class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-2">Pilih Status</label>
                        <div class="flex flex-wrap gap-4 text-xs font-semibold">
                            <label class="inline-flex items-center cursor-pointer p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50">
                                <input type="radio" name="status_verifikasi" value="terverifikasi" {{ $kegiatan->status_verifikasi === 'terverifikasi' ? 'checked' : '' }} class="text-emerald-600">
                                <span class="ml-2 text-emerald-800">✓ Setujui (Terverifikasi)</span>
                            </label>

                            <label class="inline-flex items-center cursor-pointer p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-rose-50">
                                <input type="radio" name="status_verifikasi" value="ditolak" {{ $kegiatan->status_verifikasi === 'ditolak' ? 'checked' : '' }} class="text-rose-600">
                                <span class="ml-2 text-rose-800">✕ Tolak (Perlu Revisi)</span>
                            </label>

                            <label class="inline-flex items-center cursor-pointer p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-amber-50">
                                <input type="radio" name="status_verifikasi" value="pending" {{ $kegiatan->status_verifikasi === 'pending' ? 'checked' : '' }} class="text-amber-600">
                                <span class="ml-2 text-amber-800">⏳ Pending (Menunggu)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Catatan Verifikasi / Alasan Penolakan (Opsional)</label>
                        <textarea name="catatan_verifikasi" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs sm:text-sm outline-none bg-white" placeholder="Misal: Sertifikat sah 32 JPL atau Berkas sertifikat blur mohon upload ulang...">{{ old('catatan_verifikasi', $kegiatan->catatan_verifikasi) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-teal-800 hover:bg-teal-900 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition">
                            Simpan Perubahan Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Zona Hapus -->
            @php($jumlahDalamPengiriman = $kegiatan->submission?->kegiatans()->count() ?? 1)
            <div class="rounded-2xl border border-rose-200 bg-rose-50/50 p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-700 mb-1">Hapus Data</h4>
                <p class="text-xs text-slate-500 mb-4">
                    Data dipindahkan ke menu Data Terhapus dan tidak lagi dihitung di rekap. Bisa dipulihkan kapan saja.
                </p>

                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('admin.kegiatan.destroy', $kegiatan) }}" method="POST"
                          onsubmit="return confirm('Hapus kegiatan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold transition">
                            Hapus Kegiatan Ini
                        </button>
                    </form>

                    @if($kegiatan->submission && $jumlahDalamPengiriman > 1)
                        <form action="{{ route('admin.kegiatan.submission.destroy', $kegiatan->submission) }}" method="POST"
                              onsubmit="return confirm('Hapus SEMUA {{ $jumlahDalamPengiriman }} kegiatan dalam pengiriman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 rounded-xl bg-white border border-rose-300 text-rose-700 hover:bg-rose-50 text-xs font-bold transition">
                                Hapus Seluruh Pengiriman ({{ $jumlahDalamPengiriman }} kegiatan)
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection