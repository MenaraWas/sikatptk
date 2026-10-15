<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminPasswordController extends Controller
{
    public function edit()
    {
        return view('admin.password');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(10)->letters()->numbers(),
            ],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sama.',
            'password.different' => 'Kata sandi baru harus berbeda dari kata sandi saat ini.',
            'password.min' => 'Kata sandi baru minimal 10 karakter.',
            'password.letters' => 'Kata sandi baru harus mengandung huruf.',
            'password.numbers' => 'Kata sandi baru harus mengandung angka.',
        ]);

        $user = $request->user();

        // Cast 'hashed' pada model User sudah meng-hash password, jadi jangan Hash::make lagi
        $user->forceFill([
            'password' => $validated['password'],
            'remember_token' => Str::random(60),
        ])->save();

        // Keluarkan sesi lain milik akun ini (misalnya kalau password lama sempat bocor)
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kata sandi berhasil diubah.');
    }
}