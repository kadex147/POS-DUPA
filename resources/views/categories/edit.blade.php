@extends('layouts.app')

@section('title', 'Edit Kategori - Point of Sale')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6">Edit Kategori</h1>

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

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="soft-card p-4 lg:p-6">
        @csrf
        @method('PUT')

        <div class="mb-4 lg:mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $category->name) }}"
                   class="input-soft w-full"
                   required 
                   autofocus>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-100">
            <a href="{{ route('categories.index') }}" 
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