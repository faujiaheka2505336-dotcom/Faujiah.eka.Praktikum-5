<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // attempt() mencocokkan email dan password (di-hash) dengan tabel users,
        // lalu membuat sesi login. Argumen kedua: fitur "Ingat saya".
        if (Auth::attempt($credentials, $request->boolean('ingat'))) {
            // Ganti ID sesi setelah login untuk mencegah pembajakan sesi (session fixation).
            $request->session()->regenerate();

            // intended(): kembali ke halaman yang tadi ingin dibuka sebelum diarahkan ke login.
            return redirect()->intended(route('berita.index'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Batalkan sesi dan buat ulang token CSRF.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('berita.index')
            ->with('success', 'Anda sudah keluar.');
    }
}
