<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIKAT PTK') - MAN 2 Bantul</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-man2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Tailwind CSS via Vite / CDN Fallback for complete styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kemenag: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#007a7c', // MAN 2 Bantul primary teal/green
                            900: '#0b5254',
                        },
                        accent: {
                            gold: '#ffcd57',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-teal-700 selection:text-white">

    <!-- Top bar Info -->
    <div class="bg-teal-900 text-teal-100 text-xs py-1.5 px-4">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center space-x-4">
                <span>📍 Sabdodadi, Bantul, D.I. Yogyakarta</span>
                <span class="hidden md:inline">📞 (0274) 367158</span>
                <span class="hidden sm:inline">✉️ man2bantul@gmail.com</span>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <a href="{{ route('admin.login') }}" class="text-teal-200 hover:text-white flex items-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Portal Pengelola / Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                        <img src="{{ asset('images/logo-man2.png') }}" alt="Logo MAN 2 Bantul" class="h-12 w-auto object-contain transition group-hover:scale-105">
                        <div class="border-l border-slate-200 pl-3">
                            <h1 class="text-lg font-bold text-teal-900 leading-tight">SIKAT PTK</h1>
                            <p class="text-xs text-slate-500 font-medium">Sistem Informasi Peningkatan Kompetensi PTK</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('submission.create') }}" 
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 {{ request()->routeIs('submission.create') ? 'bg-teal-800 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Upload Kegiatan
                    </a>
                    
                    <a href="{{ route('rekap.index') }}" 
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 {{ request()->routeIs('rekap.index') ? 'bg-teal-800 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Lihat Data SIKAT PTK
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-start gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <div>
                        <h4 class="font-bold text-sm">Berhasil!</h4>
                        <p class="text-sm mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 flex items-start gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <div>
                        <h4 class="font-bold text-sm">Terjadi Kesalahan!</h4>
                        <p class="text-sm mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 text-amber-900 shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-sm mb-1 text-amber-800">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        Mohon periksa kembali formulir Anda:
                    </div>
                    <ul class="list-disc list-inside text-xs sm:text-sm space-y-1 text-amber-800">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Kolom 1 -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/logo-man2.png') }}" alt="Logo MAN 2 Bantul" class="h-10 w-auto bg-white p-1 rounded-lg">
                        <span class="text-white font-bold text-lg">MAN 2 BANTUL</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Madrasah Technopreneur Terampil Berkarakter. Mengembangkan kompetensi tenaga pendidik dan kependidikan secara berkelanjutan.
                    </p>
                </div>

                <!-- Kolom 2: Kontak -->
                <div>
                    <h3 class="text-white font-semibold text-sm mb-3 uppercase tracking-wider">Pusat Informasi</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-2">
                        Jl. Parangtritis KM 10,5 Sabdodadi, Bantul, D.I. Yogyakarta 55715
                    </p>
                    <p class="text-xs text-slate-400">Telepon: (0274) 367158</p>
                    <p class="text-xs text-slate-400">Email: man2bantul@gmail.com</p>
                </div>

                <!-- Kolom 3: Navigasi -->
                <div>
                    <h3 class="text-white font-semibold text-sm mb-3 uppercase tracking-wider">Tautan SIKAT PTK</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('submission.create') }}" class="hover:text-white transition">Formulir Upload Kegiatan PTK</a></li>
                        <li><a href="{{ route('rekap.index') }}" class="hover:text-white transition">Lihat Data & Rekapitulasi Kegiatan</a></li>
                        <li><a href="https://man2bantul.id" target="_blank" class="hover:text-white transition">Website Resmi MAN 2 Bantul</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-white transition">Login Admin Pengelola</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} MAN 2 Bantul. Sistem Informasi Peningkatan Kompetensi PTK (SIKAT PTK).</p>
                <p class="mt-2 sm:mt-0">Open Form Multi-Upload (Maksimal 10 Kegiatan per Pengiriman)</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
