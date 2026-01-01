<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah user ini adalah satu-satunya admin AKTIF di sistem
     */
    public function isLastActiveAdmin(): bool
    {
        if ($this->role !== 'admin' || $this->status !== 'aktif') {
            return false;
        }
        
        $totalActiveAdmins = self::where('role', 'admin')
                                 ->where('status', 'aktif')
                                 ->count();
        return $totalActiveAdmins <= 1;
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah kasir
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Hitung total admin AKTIF di sistem
     */
    public static function countActiveAdmins(): int
    {
        return self::where('role', 'admin')
                   ->where('status', 'aktif')
                   ->count();
    }
    
    /**
     * Hitung total admin di sistem (aktif + tidak aktif)
     */
    public static function countAdmins(): int
    {
        return self::where('role', 'admin')->count();
    }
}