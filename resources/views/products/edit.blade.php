@extends('layouts.app')

@section('title', 'Edit Produk - Point of Sale')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6">Edit Produk</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 lg:mb-6 text-sm lg:text-base">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $product->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                       required>
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select id="category_id" 
                        name="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4 lg:mb-6">
            <label for="price_display" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-700 text-sm lg:text-base">Rp</span>
                <input type="text" 
                       id="price_display" 
                       value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : number_format($product->price, 0, ',', '.') }}"
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                       placeholder="0"
                       oninput="formatRupiah(this)"
                       required>
                <input type="hidden" id="price" name="price" value="{{ old('price', $product->price) }}">
            </div>
        </div>

        <div class="mb-4 lg:mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
            <input type="file" 
                   id="image" 
                   name="image"
                   accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500 text-sm lg:text-base"
                   onchange="previewImage(event)">
            
            @if($product->image)
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="Gambar Produk" 
                         class="w-24 h-24 lg:w-32 lg:h-32 object-cover rounded border border-gray-300">
                </div>
            @else
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                    <div class="w-24 h-24 lg:w-32 lg:h-32 bg-gray-200 rounded flex items-center justify-center border border-gray-300">
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            @endif
            
            <div id="imagePreview" class="mt-4 hidden">
                <p class="text-sm text-gray-600 mb-2">Preview Gambar Baru:</p>
                <img id="preview" src="" alt="Preview" class="w-24 h-24 lg:w-32 lg:h-32 object-cover rounded border border-gray-300">
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-end">
            <a href="{{ route('products.index') }}" 
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

// Inisialisasi format rupiah saat halaman dimuat (untuk nilai yang sudah ada)
document.addEventListener('DOMContentLoaded', function() {
    const priceDisplay = document.getElementById('price_display');
    if (priceDisplay.value) {
        formatRupiah(priceDisplay);
    }
});
</script>
@endpush
@endsection