@extends('layouts.app')

@section('title', 'Tambah Kategori - Point of Sale')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kategori</h1>

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

    <form action="{{ route('categories.store') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                   required 
                   autofocus>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('categories.index') }}" 
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