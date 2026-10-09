@extends('layouts.app')

@section('title', 'Tanda Terima Pengiriman Kegiatan PTK - ' . $submission->batch_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Card Bukti Tanda Terima (Printable) -->
    <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden print:shadow-none print:border-none" id="printableReceipt">
        
        <!-- Header Tanda Terima -->
        <div class="bg-gradient-to-r from-teal-800 to-emerald-800 text-white p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 print:bg-white print:text-black print:border-b-2 print:border-teal-800">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl print:hidden">
                    🎉
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-teal-200 print:text-teal-900">
                        Bukti Pengiriman Berkas
                    </span>
                    <h2 class="text-2xl font-black">SIKAT PTK - MAN 2 BANTUL</h2>
                    <p class="text-xs text-teal-100 print:text-slate-600">Sistem Informasi Peningkatan Kompetensi Pendidik & Tenaga Kependidikan</p>
                </div>
            </div>

            <!-- Kode Batch -->
            <div class="bg-white/15 px-4 py-3 rounded-2xl text-center sm:text-right border border-white/20 print:border-slate-300">
                <p class="text-[11px] text-teal-200 print:text-slate-600 font-medium">KODE PENGAJUAN</p>
                <p class="text-lg font-mono font-bold tracking-wide text-accent-gold print:text-teal-950">
                    {{ $submission->batch_code }}
                </p>
            </div>
        </div>

        <!-- Body Tanda Terima -->
        <div class="p-6 sm:p-8">
            <!-- Alert Status -->
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-start gap-3.5 print:hidden">
                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900">Pengiriman Berhasil Tersimpan!</h3>
                    <p class="text-xs text-emerald-700 mt-1">
                        Terima kasih, berkas kegiatan Anda telah berhasil diterima oleh sistem dan siap diverifikasi oleh pengelola kepegawaian madrasah.
                    </p>
                </div>
            </div>

            <!-- Data PTK -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 mb-8">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 border-b border-slate-200 pb-2">
                    Data Pendidik & Tenaga Kependidikan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-500 block text-xs">Nama Lengkap:</span>
                        <strong class="text-slate-900 font-semibold text-base">{{ $submission->nama_pengisi }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">NIP:</span>
                        <strong class="text-slate-800">{{ $submission->nip ?: 'Non-PNS' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">Jenis Kelamin:</span>
                        <strong class="text-slate-800">{{ $submission->jenis_kelamin }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">Waktu Pengiriman:</span>
                        <strong class="text-slate-800">{{ $submission->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Kegiatan yang Diunggah -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Rincian Kegiatan ({{ $submission->kegiatans->count() }} Kegiatan)
                    </h4>
                    <span class="text-xs font-bold text-teal-800 bg-teal-50 px-2.5 py-1 rounded-lg">
                        Total JPL: {{ $submission->kegiatans->sum('jumlah_jpl') }} Jam
                    </span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[11px]">
                            <tr>
                                <th class="p-3 w-10 text-center">#</th>
                                <th class="p-3">Judul Kegiatan & Pelatihan</th>
                                <th class="p-3">Penyelenggara</th>
                                <th class="p-3">Pelaksanaan</th>
                                <th class="p-3 text-center">JPL</th>
                                <th class="p-3 print:hidden">Bukti Berkas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($submission->kegiatans as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 text-center font-bold text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="p-3 font-semibold text-slate-800">
                                        <p>{{ $item->judul_pelatihan }}</p>
                                        <span class="inline-block mt-1 text-[11px] font-normal px-2 py-0.5 rounded bg-teal-50 text-teal-700">
                                            {{ $item->jenis_pelatihan }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-slate-600">{{ $item->penyelenggara }}</td>
                                    <td class="p-3 text-slate-600 whitespace-nowrap">
                                        {{ $item->tanggal_pelaksanaan->format('d/m/Y') }}<br>
                                        <span class="text-[11px] text-slate-400">Tahun {{ $item->tahun_pelaksanaan }}</span>
                                    </td>
                                    <td class="p-3 text-center font-bold text-slate-800">{{ $item->jumlah_jpl }} Jam</td>
                                    <td class="p-3 print:hidden whitespace-nowrap">
                                        @if($item->file_sertifikat)
                                            <a href="{{ asset('storage/' . $item->file_sertifikat) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center gap-1 text-xs text-teal-700 hover:text-teal-900 font-semibold bg-teal-50 hover:bg-teal-100 px-2.5 py-1.5 rounded-lg transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Lihat Berkas
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Petugas Signature for Print -->
            <div class="hidden print:flex justify-between items-end mt-12 text-xs">
                <div>
                    <p>Dicetak pada: {{ now()->isoFormat('D MMMM Y HH:mm') }}</p>
                    <p class="text-[10px] text-slate-500">Sistem SIKAT PTK MAN 2 Bantul</p>
                </div>
                <div class="text-center w-48">
                    <p class="mb-14">Pengunggah / PTK,</p>
                    <p class="font-bold underline">{{ $submission->nama_pengisi }}</p>
                    <p>NIP. {{ $submission->nip ?: '-' }}</p>
                </div>
            </div>

            <!-- Action Buttons (Non-print) -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100 print:hidden">
                <a href="{{ route('submission.create') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-50 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"></path></svg>
                    Kirim Kegiatan Lainnya
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('rekap.index') }}" 
                       class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-800 font-semibold text-xs sm:text-sm hover:bg-slate-200 transition flex items-center gap-2">
                        Lihat Data Rekap
                    </a>

                    <button type="button" 
                            onclick="window.print()" 
                            class="px-6 py-2.5 rounded-xl bg-teal-800 text-white font-bold text-xs sm:text-sm hover:bg-teal-900 transition flex items-center gap-2 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Bukti Tanda Terima
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
