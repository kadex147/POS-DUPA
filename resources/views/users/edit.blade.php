@extends('layouts.app')

@section('title', 'Edit User - Point of Sale')

@section('content')
<style>
    /* Force gray color for radio buttons - override browser default */
    input[type="radio"][name="role"]:checked,
    input[type="radio"][name="status"]:checked {
        accent-color: #4b5563 !important; /* gray-600 */
        background-color: #4b5563 !important;
        border-color: #4b5563 !important;
    }
    
    input[type="radio"][name="role"]:focus,
    input[type="radio"][name="status"]:focus {
        ring-color: #6b7280 !important; /* gray-500 */
        box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.2) !important;
    }
    
    input[type="radio"][name="role"],
    input[type="radio"][name="status"] {
        border-color: #d1d5db !important; /* gray-300 */
    }
</style>

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
                
                @if($isLastActiveAdmin)
                    <!-- Warning jika user adalah satu-satunya admin AKTIF -->
                    <div class="alert-soft alert-warning-soft mb-3 text-xs">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Anda adalah satu-satunya admin aktif. Role tidak dapat diubah.</span>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 rounded-xl border-2 transition-all {{ $isLastActiveAdmin ? 'border-gray-300 bg-gray-50 cursor-not-allowed opacity-60' : 'border-gray-200 hover:border-gray-400 cursor-pointer' }} {{ old('role', $user->role) === 'admin' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                               {{ $isLastActiveAdmin ? 'disabled' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500 {{ $isLastActiveAdmin ? 'cursor-not-allowed' : '' }}">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Admin</span>
                    </label>
                    <label class="flex items-center p-3 rounded-xl border-2 transition-all {{ $isLastActiveAdmin ? 'border-gray-300 bg-gray-50 cursor-not-allowed opacity-60' : 'border-gray-200 hover:border-gray-400 cursor-pointer' }} {{ old('role', $user->role) === 'kasir' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="role" 
                               value="kasir" 
                               {{ old('role', $user->role) === 'kasir' ? 'checked' : '' }}
                               {{ $isLastActiveAdmin ? 'disabled' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500 {{ $isLastActiveAdmin ? 'cursor-not-allowed' : '' }}">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Kasir</span>
                    </label>
                </div>
                
                @if($isLastActiveAdmin)
                    <!-- Hidden input untuk memastikan role tetap admin -->
                    <input type="hidden" name="role" value="admin">
                @endif
            </div>

            <!-- Status -->
           <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                
                @if($isLastActiveAdmin)
                    <div class="alert-soft alert-warning-soft mb-3 text-xs">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Anda adalah satu-satunya admin aktif. Status tidak dapat diubah.</span>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 rounded-xl border-2 transition-all {{ $isLastActiveAdmin ? 'border-gray-300 bg-gray-50 cursor-not-allowed opacity-60' : 'border-gray-200 hover:border-gray-400 cursor-pointer' }} {{ old('status', $user->status) === 'aktif' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="status" 
                               value="aktif" 
                               {{ old('status', $user->status) === 'aktif' ? 'checked' : '' }}
                               {{ $isLastActiveAdmin ? 'disabled' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500 {{ $isLastActiveAdmin ? 'cursor-not-allowed' : '' }}">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Aktif</span>
                    </label>

                    <label class="flex items-center p-3 rounded-xl border-2 transition-all {{ $isLastActiveAdmin ? 'border-gray-300 bg-gray-50 cursor-not-allowed opacity-60' : 'border-gray-200 hover:border-gray-400 cursor-pointer' }} {{ old('status', $user->status) === 'tidak_aktif' ? 'bg-gray-50 border-gray-600' : '' }}">
                        <input type="radio" 
                               name="status" 
                               value="tidak_aktif" 
                               {{ old('status', $user->status) === 'tidak_aktif' ? 'checked' : '' }}
                               {{ $isLastActiveAdmin ? 'disabled' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500 {{ $isLastActiveAdmin ? 'cursor-not-allowed' : '' }}">
                        <span class="ml-3 text-sm lg:text-base text-gray-700 font-medium">Tidak Aktif</span>
                    </label>
                </div>
                
                @if($isLastActiveAdmin)
                    <input type="hidden" name="status" value="aktif">
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-100">
            <a href="{{ route('users.index') }}" 
               class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 text-center font-semibold">
                Batal
            </a>
            <button type="submit" 
                    class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-linear-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 font-semibold shadow-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection