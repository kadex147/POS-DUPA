@extends('layouts.app')

@section('title', 'Data Produk - Point of Sale')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-800">List Produk</h1>
        <a href="{{ route('products.create') }}" 
           class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 text-center text-sm lg:text-base font-semibold shadow-lg">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </span>
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-soft alert-success-soft flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Mobile: Card View -->
    <div class="block lg:hidden space-y-3">
        @forelse($products as $product)
        <div class="soft-card">
            <div class="flex gap-4 p-4">
                <!-- Image -->
                <div class="flex-shrink-0">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="Produk" 
                             class="w-24 h-24 object-cover rounded-xl border-2 border-gray-100 shadow-sm">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-200 shadow-sm">
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-gray-800 text-base mb-1 truncate">{{ $product->name }}</h3>
                    <p class="badge-soft badge-primary text-xs mb-2 inline-block">{{ $product->category->name }}</p>
                    <p class="text-base font-bold text-orange-600 mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" 
                           class="btn-soft px-4 py-2 bg-gray-600 text-white hover:bg-gray-700 text-sm font-medium">
                            Edit
                        </a>
                        <button onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" 
                                class="btn-soft px-4 py-2 bg-red-500 text-white hover:bg-red-600 text-sm font-medium">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="soft-card p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-gray-400 font-medium">Tidak ada data produk</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop: Table View -->
    <div class="hidden lg:block soft-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kategori</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Harga</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gambar Produk</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="badge-soft badge-primary">{{ $product->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="Produk" 
                                     class="w-16 h-16 object-cover rounded-xl border-2 border-gray-100 shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-200 shadow-sm">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" 
                                   class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>
                                <button onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" 
                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-gray-400 font-medium">Tidak ada data produk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination-soft soft-card p-4">
        <div class="flex items-center justify-center gap-3">
            @if($products->onFirstPage())
                <button disabled class="text-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @endif

            <span class="current-page px-3 py-1">{{ $products->currentPage() }}</span>

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @else
                <button disabled class="text-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Modal Konfirmasi Hapus Produk -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 w-full max-w-md relative transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
        <!-- Icon Warning -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
        </div>
        
        <!-- Title & Description -->
        <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Hapus Produk?</h3>
        <p class="text-center text-gray-600 text-sm mb-1">Anda yakin ingin menghapus produk:</p>
        <p class="text-center font-bold text-gray-800 mb-6" id="productName"></p>
        
        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 active:scale-95">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 active:scale-95 shadow-lg">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal(productId, productName) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    const form = document.getElementById('deleteForm');
    const nameElement = document.getElementById('productName');
    
    // Set form action
    form.action = `/products/${productId}`;
    
    // Set product name
    nameElement.textContent = productName;
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection