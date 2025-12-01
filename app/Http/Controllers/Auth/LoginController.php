<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'], 
            'password' => ['required', 'string'],
        ]);

        // 1. Coba login dengan kredensial
        if (Auth::attempt($credentials)) {
            
            // 2. Ambil user yang baru saja login
            $user = Auth::user(); 

            // 3. Cek statusnya (Sesuai kode asli Anda)
            if ($user->status !== 'aktif') {
                Auth::logout(); 
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda tidak aktif. Silakan hubungi administrator.', 
                ])->onlyInput('username');
            }

            // 4. Jika aktif, lanjutkan
            $request->session()->regenerate();
            
            // --- BAGIAN YANG DIPERBAIKI ---
            
            // Jika user adalah KASIR, arahkan ke Dashboard Kasir (Bukan POS)
            if ($user->role === 'kasir') {
                // SEBELUMNYA: return redirect()->route('pos.index');
                // SEKARANG:
                return redirect()->route('kasir.dashboard');
            }
            
            // Jika user adalah ADMIN, arahkan ke Dashboard Admin
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            }

            // Default redirect (jika role tidak dikenali)
            return redirect()->route('dashboard'); 
        }

        // Jika Auth::attempt gagal (kredensial salah)
        return back()->withErrors([
            'username' => 'Username atau password salah.', 
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}