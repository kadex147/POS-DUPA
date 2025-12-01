<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek token di database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors([
                'email' => 'Token reset password tidak valid.'
            ])->onlyInput('email');
        }

        // Cek apakah token sudah expired (24 jam)
        $tokenCreatedAt = Carbon::parse($passwordReset->created_at);
        if (Carbon::now()->diffInHours($tokenCreatedAt) > 24) {
            // Hapus token yang expired
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors([
                'email' => 'Token reset password sudah kadaluarsa. Silakan minta link baru.'
            ])->onlyInput('email');
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah berhasil reset
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}