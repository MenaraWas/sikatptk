@extends('layouts.app')

@section('title', 'Form Upload Kegiatan PTK (Maks. 10 Kegiatan)')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
     x-data="sikatForm({{ Js::from($ptks) }})" 
     x-cloak>

    <!-- Hero / Title Header Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-teal-900 via-teal-800 to-emerald-900 rounded-3xl shadow-xl text-white p-6 sm:p-10 mb-8">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-teal-700/60 backdrop-blur-sm border border-teal-500/30 px-3.5 py-1.5 rounded-full text-xs font-medium text-teal-200 mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Formulir Terbuka Pendidik & Tenaga Kependidikan
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Upload Kegiatan & Pelatihan PTK
            </h2>
            <p class="mt-2 text-teal-100 text-sm sm:text-base max-w-2xl leading-relaxed">
                Silakan pilih nama Anda dan lengkapi rincian kegiatan pengembangan diri atau pelatihan. Anda dapat mengunggah hingga <strong>maksimal 10 kegiatan</strong> dalam satu kali pengiriman form.
            </p>
        </div>
        
        <!-- Decorative badge / watermarks -->
        <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none hidden sm:block">
            <img src="{{ asset('images/logo-man2.png') }}" class="w-64 h-64 object-contain brightness-0 invert" alt="">
        </div>
    </div>

    <!-- The Main Form -->
    <form action="{{ route('submission.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          @submit="isSubmitting = true" 
          id="uploadPtkForm">
        @csrf

        <!-- ========================================== -->
        <!-- BAGIAN 1: IDENTITAS GURU / TENAGA PENDIDIK -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 mb-8 transition hover:shadow-md">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                    1
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Identitas Pendidik & Tenaga Kependidikan (PTK)</h3>
                    <p class="text-xs text-slate-500">Pilih nama Anda dari daftar database kepegawaian atau input data baru jika belum terdaftar.</p>
                </div>
            </div>

            <!-- Autocomplete / Searchable Guru Dropdown -->
            <div class="mb-5 relative" @click.outside="showDropdown = false">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Cari & Pilih Nama Guru / Pegawai <span class="text-rose-500">*</span>
                </label>
                
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           @focus="showDropdown = true"
                           @input="showDropdown = true"
                           placeholder="Ketik nama atau NIP untuk mencari..."
                           class="w-full px-4 py-3 pl-11 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                    
                    <div class="absolute left-3.5 top-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <template x-if="selectedPtk">
                        <button type="button" 
                                @click="clearSelection()" 
                                class="absolute right-3 top-3 text-xs text-slate-400 hover:text-rose-600 px-2 py-0.5 rounded-md hover:bg-slate-100 transition">
                            ✕ Ganti
                        </button>
                    </template>
                </div>

                <!-- Dropdown list -->
                <div x-show="showDropdown" 
                     class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-2xl border border-slate-200 max-h-64 overflow-y-auto divide-y divide-slate-100"
                     style="display: none;">
                    <template x-for="p in filteredPtks" :key="p.id">
                        <div @click="selectPtk(p)" 
                             class="p-3 hover:bg-teal-50 cursor-pointer transition flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800" x-text="p.nama"></p>
                                <p class="text-xs text-slate-500">
                                    <span x-text="p.status_kepegawaian ? p.status_kepegawaian + ' | ' : ''"></span>
                                    NIP: <span x-text="p.nip || 'Belum ada NIP'"></span>
                                </p>
                            </div>
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-md" x-text="p.jenis_kelamin || '-'"></span>
                        </div>
                    </template>
                    <div x-show="filteredPtks.length === 0" class="p-4 text-center text-xs text-slate-500">
                        Nama tidak ditemukan dalam daftar. Anda bisa mengetik nama secara manual di bawah ini.
                    </div>
                </div>

                <!-- Hidden Input PTK ID -->
                <input type="hidden" name="ptk_id" :value="selectedPtk ? selectedPtk.id : ''">
            </div>

            <!-- Form Fields Identitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nama Lengkap Pengisi -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Nama Lengkap (dengan Gelar) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_pengisi" 
                           x-model="formData.nama_pengisi" 
                           required 
                           placeholder="Contoh: Drs. MUBTADIIN M.Pd.I."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                </div>

                <!-- NIP -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        NIP (Nomor Induk Pegawai)
                    </label>
                    <input type="text" 
                           name="nip" 
                           x-model="formData.nip" 
                           placeholder="Kosongkan atau beri tanda strip (-) jika Non-PNS"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center space-x-6 pt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="jenis_kelamin" 
                                   value="Laki-Laki" 
                                   x-model="formData.jenis_kelamin" 
                                   required 
                                   class="text-teal-700 focus:ring-teal-700 h-4 w-4">
                            <span class="ml-2 text-sm text-slate-700">Laki-Laki</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="jenis_kelamin" 
                                   value="Perempuan" 
                                   x-model="formData.jenis_kelamin" 
                                   required 
                                   class="text-teal-700 focus:ring-teal-700 h-4 w-4">
                            <span class="ml-2 text-sm text-slate-700">Perempuan</span>
                        </label>
                    </div>
                </div>

                <!-- Nomor HP / WhatsApp -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Nomor WhatsApp / HP Aktif
                    </label>
                    <input type="text" 
                           name="no_hp" 
                           x-model="formData.no_hp" 
                           placeholder="Contoh: 08123456789 atau 628123456789"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- BAGIAN 2: DAFTAR KEGIATAN (MAKS. 10 ITEM)  -->
        <!-- ========================================== -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Kegiatan & Pelatihan</h3>
                        <p class="text-xs text-slate-500">Maksimal 10 kegiatan dalam 1 kali upload</p>
                    </div>
                </div>

                <!-- Counter Badge & Add Button -->
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1.5 rounded-xl text-xs font-semibold"
                          :class="items.length >= 10 ? 'bg-amber-100 text-amber-800' : 'bg-teal-50 text-teal-800 border border-teal-200'">
                        Jumlah: <strong x-text="items.length"></strong> / 10 Kegiatan
                    </span>

                    <button type="button" 
                            @click="addItem()" 
                            :disabled="items.length >= 10"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white transition flex items-center gap-1.5 shadow-sm"
                            :class="items.length >= 10 ? 'bg-slate-300 cursor-not-allowed text-slate-500' : 'bg-teal-700 hover:bg-teal-800 hover:shadow'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        + Tambah Kegiatan
                    </button>
                </div>
            </div>

            <!-- Loop Item Kegiatan (1 to 10) -->
            <div class="space-y-6">
                <template x-for="(item, index) in items" :key="item.uid">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition hover:border-teal-300">
                        <!-- Header Card Item -->
                        <div class="bg-slate-50 px-5 py-3 border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg bg-teal-800 text-white flex items-center justify-center text-xs font-bold" 
                                      x-text="index + 1"></span>
                                <h4 class="text-sm font-bold text-slate-800">
                                    Kegiatan #<span x-text="index + 1"></span>
                                </h4>
                                <template x-if="item.judul">
                                    <span class="text-xs text-slate-500 font-normal truncate max-w-xs sm:max-w-md hidden sm:inline" 
                                          x-text="'- ' + item.judul"></span>
                                </template>
                            </div>

                            <button type="button" 
                                    @click="removeItem(index)" 
                                    x-show="items.length > 1"
                                    class="text-xs text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-2.5 py-1 rounded-lg transition flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </div>

                        <!-- Body Card Item -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Jenis Pelatihan -->
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Jenis Pelatihan <span class="text-rose-500">*</span>
                                    </label>
                                    <select :name="'kegiatan[' + index + '][jenis_pelatihan]'" 
                                            x-model="item.jenis"
                                            required
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none bg-white">
                                        <option value="">-- Pilih Jenis Pelatihan --</option>
                                        @foreach($jenisPelatihanList as $jp)
                                            <option value="{{ $jp }}">{{ $jp }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Penyelenggara -->
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Penyelenggara <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           :name="'kegiatan[' + index + '][penyelenggara]'"
                                           x-model="item.penyelenggara"
                                           required
                                           placeholder="Contoh: Balai Diklat Keagamaan Semarang / Pusdiklat Kemenag"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                                </div>

                                <!-- Judul Pelatihan (Span 2 cols) -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Judul Pelatihan (Diklat / Workshop / Seminar / Kursus Singkat) <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea :name="'kegiatan[' + index + '][judul_pelatihan]'"
                                              x-model="item.judul"
                                              required
                                              rows="2"
                                              placeholder="Tuliskan judul lengkap pelatihan atau kegiatan yang diikuti..."
                                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none"></textarea>
                                </div>

                                <!-- Tanggal Pelaksanaan -->
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" 
                                           :name="'kegiatan[' + index + '][tanggal_pelaksanaan]'"
                                           x-model="item.tanggal"
                                           @change="syncTahun(item)"
                                           required
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                                </div>

                                <!-- Tahun Pelaksanaan & Jumlah JPL (Sub Grid) -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                            Tahun <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="number" 
                                               :name="'kegiatan[' + index + '][tahun_pelaksanaan]'"
                                               x-model="item.tahun"
                                               required
                                               min="2010"
                                               max="2035"
                                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                            Jumlah JPL <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="number" 
                                               :name="'kegiatan[' + index + '][jumlah_jpl]'"
                                               x-model="item.jpl"
                                               required
                                               min="1"
                                               max="1000"
                                               placeholder="Contoh: 32"
                                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none">
                                    </div>
                                </div>

                                <!-- Upload Bukti Sertifikat (Span 2 cols) -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                        Upload Bukti Sertifikat Pelatihan <span class="text-rose-500">*</span>
                                    </label>
                                    
                                    <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-5 hover:border-teal-500 transition bg-slate-50/50 text-center">
                                        <input type="file" 
                                               :name="'kegiatan[' + index + '][file_sertifikat]'"
                                               @change="handleFileChange($event, item)"
                                               required
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                            <template x-if="!item.fileName">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mb-1">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                    </div>
                                                    <p class="text-sm font-semibold text-slate-700">
                                                        Klik atau Tarik file sertifikat ke sini
                                                    </p>
                                                    <p class="text-xs text-slate-500 mt-1">
                                                        Format: <strong>PDF, JPG, JPEG, PNG</strong> (Maksimal 10 MB)
                                                    </p>
                                                </div>
                                            </template>

                                            <!-- File Preview after selection -->
                                            <template x-if="item.fileName">
                                                <div class="flex items-center gap-3 bg-white border border-teal-200 px-4 py-2.5 rounded-xl shadow-sm pointer-events-auto">
                                                    <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center text-xs font-bold">
                                                        📄
                                                    </span>
                                                    <div class="text-left">
                                                        <p class="text-xs font-bold text-slate-800 truncate max-w-xs" x-text="item.fileName"></p>
                                                        <p class="text-[11px] text-slate-500" x-text="item.fileSize"></p>
                                                    </div>
                                                    <span class="ml-2 text-[11px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded font-semibold">
                                                        ✓ Terpilih
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Tombol Tambah Kegiatan di Bagian Bawah -->
            <div class="mt-6 flex justify-center">
                <button type="button" 
                        @click="addItem()" 
                        :disabled="items.length >= 10"
                        class="px-6 py-3 rounded-2xl text-sm font-bold border-2 transition flex items-center gap-2 shadow-sm"
                        :class="items.length >= 10 
                            ? 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed' 
                            : 'border-teal-700 text-teal-800 bg-white hover:bg-teal-50 hover:shadow-md'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Kegiatan Lainnya (Masih bisa <strong x-text="10 - items.length"></strong> kegiatan lagi)</span>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- BAGIAN 3: KONFIRMASI & SUBMIT              -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                    3
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Ringkasan & Pengiriman</h3>
                    <p class="text-xs text-slate-500">Periksa kembali data sebelum mengirimkan berkas.</p>
                </div>
            </div>

            <!-- Catatan Pengirim (Opsional) -->
            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Catatan Tambahan untuk Petugas / Admin (Opsional)
                </label>
                <textarea name="catatan" 
                          rows="2" 
                          placeholder="Tuliskan keterangan jika ada catatan khusus terkait berkas yang diunggah..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 focus:border-teal-700 text-sm transition outline-none"></textarea>
            </div>

            <!-- Checkbox Persetujuan -->
            <div class="mb-6 p-4 rounded-xl bg-teal-50/70 border border-teal-100">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" 
                           x-model="agreed" 
                           required 
                           class="mt-1 text-teal-700 focus:ring-teal-700 rounded h-4 w-4">
                    <span class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                        Saya menyatakan dengan sebenarnya bahwa data kegiatan dan berkas bukti sertifikat yang saya unggah adalah <strong>sah, benar, dan dapat dipertanggungjawabkan</strong>.
                    </span>
                </label>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <div class="text-xs text-slate-500 text-center sm:text-left">
                    Mengunggah <strong class="text-slate-800 text-sm" x-text="items.length"></strong> berkas kegiatan.
                </div>

                <button type="submit" 
                        :disabled="!agreed || isSubmitting"
                        class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold text-sm text-white transition flex items-center justify-center gap-3 shadow-lg"
                        :class="(!agreed || isSubmitting) ? 'bg-slate-400 cursor-not-allowed shadow-none' : 'bg-gradient-to-r from-teal-800 to-emerald-700 hover:from-teal-900 hover:to-emerald-800 hover:shadow-teal-900/30'">
                    
                    <template x-if="isSubmitting">
                        <div class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Sedang Mengunggah Berkas...</span>
                        </div>
                    </template>

                    <template x-if="!isSubmitting">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Kirim Berkas SIKAT PTK</span>
                        </div>
                    </template>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function sikatForm(ptkData) {
    return {
        allPtks: ptkData,
        searchQuery: '',
        showDropdown: false,
        selectedPtk: null,
        agreed: false,
        isSubmitting: false,

        formData: {
            nama_pengisi: '',
            nip: '',
            jenis_kelamin: '',
            no_hp: '',
        },

        items: [
            {
                uid: Date.now(),
                jenis: '',
                judul: '',
                penyelenggara: '',
                tanggal: '',
                tahun: new Date().getFullYear(),
                jpl: '',
                fileName: '',
                fileSize: '',
            }
        ],

        get filteredPtks() {
            if (!this.searchQuery) {
                return this.allPtks.slice(0, 15);
            }
            const q = this.searchQuery.toLowerCase();
            return this.allPtks.filter(p => {
                return p.nama.toLowerCase().includes(q) || (p.nip && p.nip.includes(q));
            }).slice(0, 20);
        },

        selectPtk(ptk) {
            this.selectedPtk = ptk;
            this.searchQuery = ptk.nama;
            this.formData.nama_pengisi = ptk.nama;
            this.formData.nip = ptk.nip || '';
            this.formData.jenis_kelamin = ptk.jenis_kelamin || '';
            this.formData.no_hp = ptk.no_hp || '';
            this.showDropdown = false;
        },

        clearSelection() {
            this.selectedPtk = null;
            this.searchQuery = '';
            this.formData.nama_pengisi = '';
            this.formData.nip = '';
            this.formData.jenis_kelamin = '';
            this.formData.no_hp = '';
        },

        addItem() {
            if (this.items.length >= 10) {
                alert('Maksimal hanya dapat menambahkan 10 kegiatan dalam 1 kali upload.');
                return;
            }
            this.items.push({
                uid: Date.now() + Math.random(),
                jenis: '',
                judul: '',
                penyelenggara: '',
                tanggal: '',
                tahun: new Date().getFullYear(),
                jpl: '',
                fileName: '',
                fileSize: '',
            });
        },

        removeItem(index) {
            if (this.items.length <= 1) {
                alert('Minimal harus mengisi 1 kegiatan.');
                return;
            }
            this.items.splice(index, 1);
        },

        syncTahun(item) {
            if (item.tanggal) {
                const parts = item.tanggal.split('-');
                if (parts.length > 0 && parts[0]) {
                    item.tahun = parts[0];
                }
            }
        },

        handleFileChange(event, item) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran berkas melebihi batas maksimal 10 MB. Silakan pilih berkas yang lebih kecil.');
                    event.target.value = '';
                    item.fileName = '';
                    item.fileSize = '';
                    return;
                }
                item.fileName = file.name;
                const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
                item.fileSize = sizeInMb + ' MB';
            } else {
                item.fileName = '';
                item.fileSize = '';
            }
        }
    }
}
</script>
@endpush
