@extends('layouts.admin')

@section('title', 'Ganti Password - Admin SIKAT PTK')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-800">Ganti Password</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Gunakan password yang kuat dan tidak dipakai di tempat lain.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.password.update') }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password" required autocomplete="current-password"
                       class="pw-field w-full px-4 py-3 rounded-xl border @error('current_password') border-rose-400 @else border-slate-300 @enderror focus:ring-2 focus:ring-teal-700 text-sm outline-none">
                @error('current_password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1.5">Password Baru</label>
                <input type="password" name="password" required autocomplete="new-password"
                       class="pw-field w-full px-4 py-3 rounded-xl border @error('password') border-rose-400 @else border-slate-300 @enderror focus:ring-2 focus:ring-teal-700 text-sm outline-none">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-slate-400 mt-1">Minimal 10 karakter, mengandung huruf dan angka.</p>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1.5">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="pw-field w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-teal-700 text-sm outline-none">
            </div>

            <label class="inline-flex items-center text-xs text-slate-600 mb-6 cursor-pointer">
                <input type="checkbox" id="toggle-pw" class="text-teal-700 rounded h-4 w-4">
                <span class="ml-2">Tampilkan password</span>
            </label>

            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:underline">Batal</a>
                <button type="submit"
                        class="bg-teal-800 hover:bg-teal-900 text-white font-bold text-xs sm:text-sm px-6 py-3 rounded-xl transition">
                    Simpan Password Baru
                </button>
            </div>
        </form>

        <p class="text-[11px] text-slate-400 mt-6 border-t border-slate-100 pt-4">
            Setelah password diganti, perangkat lain yang sedang login dengan akun ini akan dikeluarkan.
        </p>
    </div>

<script>
    document.getElementById('toggle-pw').addEventListener('change', function () {
        document.querySelectorAll('.pw-field').forEach(function (el) {
            el.type = this.checked ? 'text' : 'password';
        }.bind(this));
    });
</script>
@endsection