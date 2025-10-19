@extends('layouts.app')

@section('title', 'Tambah Produk - Point of Sale')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Produk</h1>

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

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6">
        @csrf

        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                       required>
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select id="category_id" 
                        name="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Harga -->
        <div class="mb-6">
            <label for="price_display" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-700">Rp</span>
                <input type="text" 
                       id="price_display" 
                       value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                       placeholder="0"
                       oninput="formatRupiah(this)"
                       required>
                <input type="hidden" id="price" name="price" value="{{ old('price', '') }}">
            </div>
        </div>

        <!-- Gambar Produk -->
        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
            <input type="file" 
                   id="image" 
                   name="image"
                   accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500"
                   onchange="previewImage(event)">
            
            <!-- Image Preview -->
            <div id="imagePreview" class="mt-4 hidden">
                <img id="preview" src="" alt="Preview" class="w-32 h-32 object-cover rounded border border-gray-300">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('products.index') }}" 
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

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function formatRupiah(input) {
    // Hapus semua karakter non-digit
    let value = input.value.replace(/\D/g, '');
    
    // Set nilai asli ke hidden input
    document.getElementById('price').value = value;
    
    // Format dengan thousand separator
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
    }
    
    input.value = value;
}
</script>
@endpush
@endsection