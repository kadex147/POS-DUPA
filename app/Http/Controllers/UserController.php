<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,kasir',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        // Hitung jumlah admin AKTIF di sistem (bukan total admin)
        $totalActiveAdmins = User::where('role', 'admin')
                                 ->where('status', 'aktif')
                                 ->count();
        
        // Cek apakah user ini adalah satu-satunya admin AKTIF
        $isLastActiveAdmin = ($user->role === 'admin' && $user->status === 'aktif' && $totalActiveAdmins <= 1);
        
        return view('users.edit', compact('user', 'isLastActiveAdmin'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id . '|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,kasir',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        // Hitung jumlah admin AKTIF di sistem (bukan total admin)
        $totalActiveAdmins = User::where('role', 'admin')
                                 ->where('status', 'aktif')
                                 ->count();
        
        $isLastActiveAdmin = ($user->role === 'admin' && $user->status === 'aktif' && $totalActiveAdmins <= 1);

        // VALIDASI 1: Cek apakah admin AKTIF terakhir ingin mengubah role ke kasir
        if ($user->role === 'admin' && $user->status === 'aktif' && $validated['role'] === 'kasir') {
            // Jika hanya ada 1 admin AKTIF (yaitu user ini), tidak boleh diubah
            if ($isLastActiveAdmin) {
                return redirect()->back()
                    ->withErrors(['role' => 'Tidak dapat mengubah role. Minimal harus ada 1 admin aktif di sistem.'])
                    ->withInput();
            }
        }

        // VALIDASI 2: Cek apakah admin AKTIF terakhir ingin menonaktifkan dirinya
        if ($isLastActiveAdmin && $validated['status'] === 'tidak_aktif') {
            return redirect()->back()
                ->withErrors(['status' => 'Tidak dapat menonaktifkan status. Minimal harus ada 1 admin aktif di sistem.'])
                ->withInput();
        }

        // VALIDASI 3: Cek apakah admin tidak aktif ingin mengubah role ke kasir
        // (Admin tidak aktif boleh diubah ke kasir selama masih ada admin aktif lain)
        if ($user->role === 'admin' && $user->status === 'tidak_aktif' && $validated['role'] === 'kasir') {
            // Tidak perlu validasi khusus, admin tidak aktif bebas diubah ke kasir
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // VALIDASI: Cek apakah user yang akan dihapus adalah admin AKTIF
        if ($user->role === 'admin' && $user->status === 'aktif') {
            // Hitung jumlah admin AKTIF di sistem
            $totalActiveAdmins = User::where('role', 'admin')
                                     ->where('status', 'aktif')
                                     ->count();
            
            // Jika hanya ada 1 admin AKTIF, tidak boleh dihapus
            if ($totalActiveAdmins <= 1) {
                return redirect()->back()
                    ->withErrors(['delete' => 'Tidak dapat menghapus user. Minimal harus ada 1 admin aktif di sistem.']);
            }
        }
        
        // Admin tidak aktif boleh dihapus kapan saja
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}