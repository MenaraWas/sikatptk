<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin SIKAT PTK') - MAN 2 Bantul</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-man2.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kemenag: {
                            800: '#007a7c',
                            900: '#0b5254',
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
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Admin Navbar -->
    <header class="bg-teal-900 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo-man2.png') }}" alt="MAN 2 Bantul" class="h-9 w-auto bg-white p-1 rounded-lg">
                    <div class="border-l border-teal-700 pl-3">
                        <span class="font-bold text-sm tracking-wide block">SIKAT PTK ADMIN</span>
                        <span class="text-[10px] text-teal-300">MAN 2 BANTUL</span>
                    </div>
                </div>

                <!-- Navigation menu -->
                <nav class="hidden md:flex items-center space-x-1 text-xs font-semibold">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-teal-800 text-white' : 'text-teal-100 hover:bg-teal-800/60' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.kegiatan.index') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.kegiatan.*') ? 'bg-teal-800 text-white' : 'text-teal-100 hover:bg-teal-800/60' }}">
                        Data Kegiatan
                    </a>
                    <a href="{{ route('admin.ptk.index') }}" 
                       class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.ptk.*') ? 'bg-teal-800 text-white' : 'text-teal-100 hover:bg-teal-800/60' }}">
                        Master PTK (Guru)
                    </a>
                    <a href="{{ route('submission.create') }}" target="_blank"
                       class="px-3.5 py-2 rounded-xl text-teal-200 hover:text-white hover:bg-teal-800/60 transition flex items-center gap-1">
                        Form Publik ↗
                    </a>
                </nav>

                <!-- User & Logout -->
                <div class="flex items-center space-x-3 text-xs">
                    <span class="text-teal-200 hidden sm:inline">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-teal-800 hover:bg-rose-700 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Admin Content Area -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <div class="text-sm font-semibold">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <div class="text-sm font-semibold">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Admin Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} MAN 2 Bantul - Panel Pengelola SIKAT PTK
    </footer>

    @stack('scripts')
</body>
</html>
