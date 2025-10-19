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
            // Ganti 'username' ke 'email' jika Anda login pakai email
            'username' => ['required', 'string'], 
            'password' => ['required', 'string'],
        ]);

        // 1. Coba login dengan kredensial
        if (Auth::attempt($credentials)) {
            
            // --- PERUBAHAN DIMULAI DI SINI ---
            
            // 2. Ambil user yang baru saja login
            $user = Auth::user(); 

            // 3. Cek statusnya
            if ($user->status !== 'aktif') {
                // 4. Jika tidak aktif, logout lagi
                Auth::logout(); 
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 5. Kembalikan ke login dengan pesan error spesifik
                return back()->withErrors([
                    // Ganti 'username' ke 'email' jika perlu
                    'username' => 'Akun Anda tidak aktif. Silakan hubungi administrator.', 
                ])->onlyInput('username'); // Ganti 'username' ke 'email' jika perlu
            }

            // --- BATAS PERUBAHAN ---

            // 6. Jika aktif, lanjutkan seperti biasa
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            if ($user->role === 'kasir') { // Lebih aman pakai $user->role di sini
                return redirect()->route('pos.index');
            }
            
            // Default redirect untuk role lain (misal: admin)
            return redirect()->route('dashboard'); 
        }

        // Jika Auth::attempt gagal (kredensial salah)
        return back()->withErrors([
            // Ganti 'username' ke 'email' jika perlu
            'username' => 'Username atau password salah.', 
        ])->onlyInput('username'); // Ganti 'username' ke 'email' jika perlu
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}