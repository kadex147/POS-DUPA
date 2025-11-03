@extends('layouts.app')

@section('title', 'Edit User - Point of Sale')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6">Edit User</h1>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert-soft alert-error-soft mb-4 lg:mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="soft-card p-4 lg:p-6">
        @csrf
        @method('PUT')

        <!-- Responsive Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username', $user->username) }}"
                       class="input-soft w-full"
                       required 
                       autofocus>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}"
                       class="input-soft w-full"
                       required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password 
                    <span class="text-xs text-gray-500 font-normal">(Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="input-soft w-full"
                       placeholder="Minimal 8 karakter">
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                <input type="text" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone', $user->phone) }}"
                       class="input-soft w-full"
                       placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <!-- Responsive Grid for Radio Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
            <!-- Role -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Role</label>
                <div class="space-y-3">
                    <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-gray-400 transition-all cursor-pointer {{ old('role', $user->role) === 'admin' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Admin</span>
                    </label>
                    <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-gray-400 transition-all cursor-pointer {{ old('role', $user->role) === 'kasir' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="role" 
                               value="kasir" 
                               {{ old('role', $user->role) === 'kasir' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Kasir</span>
                    </label>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                <div class="space-y-3">
                    <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-gray-400 transition-all cursor-pointer {{ old('status', $user->status) === 'aktif' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="status" 
                               value="aktif" 
                               {{ old('status', $user->status) === 'aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Aktif</span>
                    </label>
                    <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 hover:border-gray-400 transition-all cursor-pointer {{ old('status', $user->status) === 'tidak_aktif' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="status" 
                               value="tidak_aktif" 
                               {{ old('status', $user->status) === 'tidak_aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Tidak Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-100">
            <a href="{{ route('users.index') }}" 
               class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 text-center font-semibold">
                Batal
            </a>
            <button type="submit" 
                    class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 font-semibold shadow-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection