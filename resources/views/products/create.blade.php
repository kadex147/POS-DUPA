@extends('layouts.app')

@section('title', 'Tambah Produk - Point of Sale')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6">Tambah Produk</h1>

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

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="soft-card p-4 lg:p-6">
        @csrf

        <!-- Responsive Grid: 1 column on mobile, 2 columns on desktop -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="input-soft w-full"
                       placeholder="Contoh: Dupa Sandalwood"
                       required 
                       autofocus>
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                <select id="category_id" 
                        name="category_id"
                        class="input-soft w-full"
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
        <div class="mb-4 lg:mb-6">
            <label for="price_display" class="block text-sm font-semibold text-gray-600 mb-2">Harga</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 font-bold text-base pointer-events-none z-10">Rp</span>
                <input type="text" 
                       id="price_display" 
                       value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                       class="input-soft w-full"
                       style="padding-left: 3.5rem !important;"
                       placeholder="0"
                       oninput="formatRupiah(this)"
                       required>
                <input type="hidden" id="price" name="price" value="{{ old('price', '') }}">
            </div>
        </div>

        <!-- Gambar Produk -->
        <div class="mb-4 lg:mb-6">
            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
            <div class="relative">
                <input type="file" 
                       id="image" 
                       name="image"
                       accept="image/*"
                       class="hidden"
                       onchange="previewImage(event)">
                
                <label for="image" class="btn-soft cursor-pointer inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Pilih Gambar
                </label>
                <span id="fileName" class="ml-3 text-sm text-gray-500">Belum ada file dipilih</span>
            </div>
            
            <!-- Image Preview -->
            <div id="imagePreview" class="mt-4 hidden">
                <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
                <div class="relative inline-block">
                    <img id="preview" src="" alt="Preview" class="w-32 h-32 lg:w-40 lg:h-40 object-cover rounded-2xl border-2 border-gray-200 shadow-lg">
                    <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-100">
            <a href="{{ route('products.index') }}" 
               class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 text-center font-semibold">
                Batal
            </a>
            <button type="submit" 
                    class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 font-semibold shadow-lg">
                Simpan Produk
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
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            fileName.textContent = input.files[0].name;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    
    input.value = '';
    preview.src = '';
    previewContainer.classList.add('hidden');
    fileName.textContent = 'Belum ada file dipilih';
}

function formatRupiah(input) {
    let value = input.value.replace(/\D/g, '');
    document.getElementById('price').value = value;
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
    }
    input.value = value;
}
</script>
@endpush
@endsection