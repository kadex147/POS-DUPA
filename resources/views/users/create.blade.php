@extends('layouts.app')

@section('title', 'Tambah User - Point of Sale')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6">Tambah User</h1>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 lg:mb-6 text-sm lg:text-base">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
        @csrf

        <!-- Responsive Grid: 1 column on mobile, 2 columns on desktop -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                       required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                       required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                       required>
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                <input type="text" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base">
            </div>
        </div>

        <!-- Responsive Grid for Radio Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Role</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               {{ old('role') === 'admin' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-sm lg:text-base text-gray-700">Admin</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="role" 
                               value="kasir" 
                               {{ old('role') === 'kasir' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-sm lg:text-base text-gray-700">Kasir</span>
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
                               {{ old('status') === 'aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-sm lg:text-base text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="status" 
                               value="tidak_aktif" 
                               {{ old('status') === 'tidak_aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="ml-2 text-sm lg:text-base text-gray-700">Tidak Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end">
            <a href="{{ route('users.index') }}" 
               class="w-full sm:w-auto px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition text-center text-sm lg:text-base">
                Batal
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm lg:text-base">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection