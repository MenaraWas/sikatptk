<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - SIKAT PTK MAN 2 Bantul</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-man2.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 p-8 sm:p-10">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-man2.png') }}" alt="MAN 2 Bantul" class="h-16 w-auto mx-auto mb-4">
            <h2 class="text-xl font-bold text-slate-800">Panel Administrator</h2>
            <p class="text-xs text-slate-500 mt-1">SIKAT PTK - MAN 2 Bantul</p>
        </div>

        @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1.5">Email Administrator</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-sm outline-none">
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1.5">Kata Sandi</label>
                <input type="password" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-sm outline-none">
            </div>

            <!-- Remember me -->
            <div class="flex items-center justify-between mb-6 text-xs text-slate-600">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="text-teal-700 rounded h-4 w-4">
                    <span class="ml-2">Ingat saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-teal-800 hover:bg-teal-900 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition shadow-lg shadow-teal-900/20">
                Masuk ke Panel
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-5">
            <a href="{{ route('submission.create') }}" class="text-xs text-teal-700 hover:underline font-semibold">
                ← Kembali ke Halaman Form Terbuka
            </a>
        </div>
    </div>

</body>
</html>