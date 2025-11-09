@extends('layouts.app')

@section('title', 'Data Kategori - Point of Sale')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-800">List Kategori</h1>
        <a href="{{ route('categories.create') }}" 
           class="btn-soft w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 text-center text-sm lg:text-base font-semibold shadow-lg">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kategori
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
        @forelse($categories as $category)
        <div class="soft-card p-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-base flex-1 min-w-0 truncate">
                    {{ $category->name }}
                </h3>
                <div class="flex items-center gap-2 ml-3 shrink-0">
                    <a href="{{ route('categories.edit', $category->id) }}" 
                       class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    </a>
                    <button onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="soft-card p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
            </svg>
            <p class="text-gray-400 font-medium">Tidak ada data kategori</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop: Table View -->
    <div class="hidden lg:block soft-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('categories.edit', $category->id) }}" 
                                   class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>
                                <button onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')" 
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
                        <td colspan="2" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <p class="text-gray-400 font-medium">Tidak ada data kategori</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="pagination-soft soft-card p-4">
        <div class="flex items-center justify-center gap-3">
            @if($categories->onFirstPage())
                <button disabled class="text-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            @else
                <a href="{{ $categories->previousPageUrl() }}" class="hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @endif

            <span class="current-page px-3 py-1">{{ $categories->currentPage() }}</span>

            @if($categories->hasMorePages())
                <a href="{{ $categories->nextPageUrl() }}" class="hover:bg-gray-100">
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

<!-- Modal Konfirmasi Hapus Kategori -->
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
        <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Hapus Kategori?</h3>
        <p class="text-center text-gray-600 text-sm mb-1">Anda yakin ingin menghapus kategori:</p>
        <p class="text-center font-bold text-gray-800 mb-6" id="categoryName"></p>
        
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
function openDeleteModal(categoryId, categoryName) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    const form = document.getElementById('deleteForm');
    const nameElement = document.getElementById('categoryName');
    
    // Set form action
    form.action = `/categories/${categoryId}`;
    
    // Set category name
    nameElement.textContent = categoryName;
    
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