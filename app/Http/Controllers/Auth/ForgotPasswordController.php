<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem.',
        ]);

        // Cek apakah user aktif
        $user = User::where('email', $request->email)->first();
        
        if ($user->status !== 'aktif') {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->onlyInput('email');
        }

        // Generate token
        $token = Str::random(64);

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Kirim email
        try {
            Mail::send('auth.emails.reset-password', [
                'token' => $token,
                'email' => $request->email,
                'user' => $user
            ], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password - Point Of Sale');
            });

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau spam folder.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Gagal mengirim email. Silakan coba lagi atau hubungi administrator.',
            ])->onlyInput('email');
        }
    }
}