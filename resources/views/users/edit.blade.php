@extends('layouts.app')

@section('title', 'Edit User - Point of Sale')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit User</h1>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username', $user->username) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                       required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                       required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah)</span></label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500">
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                <input type="text" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone', $user->phone) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Role</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-gray-700">Admin</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="role" 
                               value="kasir" 
                               {{ old('role', $user->role) === 'kasir' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-gray-700">Kasir</span>
                    </label>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="status" 
                               value="aktif" 
                               {{ old('status', $user->status) === 'aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="status" 
                               value="tidak_aktif" 
                               {{ old('status', $user->status) === 'tidak_aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-gray-700">Tidak</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('users.index') }}" 
               class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection