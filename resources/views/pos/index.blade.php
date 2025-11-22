@extends('layouts.app')

@section('title', 'POS - Point of Sale')

@section('content')
<div class="flex flex-col lg:flex-row gap-4 lg:gap-6 h-auto lg:h-[calc(100vh-140px)]">
    <!-- Left Section - Products -->
    <div class="flex-1 flex flex-col soft-card order-2 lg:order-1">
        <div class="p-4 lg:p-6 space-y-4">
            <!-- Search Bar dengan Soft Design -->
            <div class="search-soft">
                <svg class="search-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                <input type="text" 
                       id="searchProduct"
                       placeholder="Cari berdasarkan nama produk"
                       class="w-full focus:outline-none">
            </div>

            <!-- Category Tabs dengan Soft Design -->
            <div class="category-tabs-soft">
                <button onclick="filterCategory('')" 
                        class="{{ !request('category') ? 'active' : '' }}">
                     Semua
                </button>
                @foreach($categories as $category)
                <button onclick="filterCategory('{{ $category->id }}')" 
                        class="{{ request('category') == $category->id ? 'active' : '' }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        <div id="productsGrid" class="flex-1 overflow-y-auto px-4 lg:px-6 pb-4">
           <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-5 gap-3 lg:gap-4 auto-rows-max">
                @forelse($products as $product)
                <div class="product-card-soft cursor-pointer {{ $product->stock <= 0 ? 'opacity-60' : '' }}" 
                     onclick='{{ $product->stock > 0 ? "addToCart(" . json_encode($product) . ")" : "showOutOfStock()" }}'>
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="Produk" 
                                 class="w-full h-full object-cover {{ $product->stock <= 0 ? 'grayscale' : '' }}">
                        @else
                            <svg class="w-16 h-16 text-gray-300 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        
                        @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-t-2xl">
                                <span class="text-white font-bold text-sm bg-red-500 px-3 py-1 rounded-full">HABIS</span>
                            </div>
                        @else
                            <button onclick='event.stopPropagation(); addToCart(@json($product))' 
                                    class="add-button flex items-center justify-center hover:scale-110 active:scale-95 z-10">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    <div class="p-3 bg-white border-t border-gray-100 grow flex flex-col justify-between">
                        <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 leading-tight" title="{{ $product->name }}">{{ $product->name }}</h3>
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-xs">
                                <span class="font-semibold text-gray-600">Stock:</span> 
                                <span class="font-bold {{ $product->stock <= 0 ? 'text-red-600' : ($product->stock < 10 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $product->stock }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-gray-400 font-medium">Tidak ada produk</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination dengan Soft Design -->
        <div class="pagination-soft p-4 lg:px-6 border-t border-gray-100">
            <div class="flex items-center justify-center gap-3">
                @if($products->onFirstPage())
                    <button disabled class="text-gray-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endif

                <span class="current-page px-3 py-1">{{ $products->currentPage() }}</span>

                @if($products->hasMorePages())
                    <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="hover:bg-gray-100">
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
    </div>

    <!-- Right Section - Cart dengan Soft Design -->
    <div class="lg:w-96 w-full soft-card flex flex-col order-1 lg:order-2 h-[70vh] lg:h-auto shadow-lg">
        <!-- Header -->
        <div class="p-4 border-b border-gray-100 shrink-0 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-600 to-gray-800 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base lg:text-lg font-bold text-gray-800">{{ ucfirst(Auth::user()->role) }}</h2>
                    <p class="text-xs text-gray-500">{{ Auth::user()->username }}</p>
                </div>
            </div>
        </div>
        
        <!-- Cart Items -->
        <div id="cartItems" class="flex-1 overflow-y-auto p-3 lg:p-4 space-y-2 bg-gray-50">
            <div class="flex flex-col items-center justify-center h-full">
                <svg class="w-20 h-20 text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                <p class="text-center text-gray-400 font-medium">Keranjang kosong</p>
            </div>
        </div>

        <!-- Cart Footer -->
        <div class="border-t border-gray-100 p-4 space-y-3 shrink-0 bg-white">
            <div class="flex justify-between items-center text-lg font-bold">
                <span class="text-gray-700">Total:</span>
                <span id="cartTotal" class="text-xl text-orange-600">Rp 0</span>
            </div>

            <button onclick="openOrderModal()" 
                    id="btnBuatPesanan"
                    class="btn-soft w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white hover:from-orange-600 hover:to-orange-700 font-bold disabled:from-gray-300 disabled:to-gray-300 disabled:cursor-not-allowed text-sm lg:text-base shadow-lg disabled:shadow-none"
                    disabled>
                Pesan Sekarang
            </button>
            
            <button onclick="openClearCartModal()" 
                    class="btn-soft w-full py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold text-sm lg:text-base">
                Kosongkan Keranjang
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pesanan dengan Soft Design -->
<div id="orderModal" class="modal-soft fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="modal-content-soft bg-white p-4 lg:p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeOrderModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        
        <h3 class="text-lg lg:text-xl font-bold mb-4 text-gray-800">Buat Pesanan</h3>
        
        <div class="overflow-x-auto mb-4 soft-card bg-gray-50 p-3">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-xs lg:text-sm font-semibold text-gray-700">Nama</th>
                        <th class="text-center py-2 text-xs lg:text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="text-right py-2 text-xs lg:text-sm font-semibold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody id="modalCartItems" class="text-xs lg:text-sm">
                </tbody>
            </table>
        </div>
        
        <div class="border-t border-gray-200 pt-4 mb-4">
            <div class="flex justify-between font-bold text-base lg:text-lg">
                <span class="text-gray-700">Total:</span>
                <span id="modalTotal" class="text-green-600">Rp 0</span>
            </div>
        </div>
        
        <button onclick="confirmOrder()" 
                id="btnConfirmOrder"
                class="btn-soft w-full py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 font-bold text-sm lg:text-base shadow-lg">
            Konfirmasi Pesanan
        </button>
    </div>
</div>

<!-- Modal Konfirmasi Kosongkan Keranjang dengan Soft Design -->
<div id="clearCartModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 w-full max-w-md relative transform transition-all duration-300 scale-95 opacity-0" id="clearCartModalContent">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
        </div>
        
        <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Kosongkan Keranjang?</h3>
        <p class="text-center text-gray-600 text-sm mb-6">Semua produk dalam keranjang akan dihapus</p>
        
        <div class="flex gap-3">
            <button onclick="closeClearCartModal()" 
                    class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 active:scale-95">
                Batal
            </button>
            <button onclick="confirmClearCart()" 
                    class="flex-1 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 active:scale-95 shadow-lg">
                Ya, Kosongkan
            </button>
        </div>
    </div>
</div>

{{-- Include Transaction Success Modal Component --}}
@include('components.modal-print')

@push('scripts')
<script>
// Load cart from localStorage
let cart = JSON.parse(localStorage.getItem('posCart')) || [];

// Product stock data from server
const productStocks = @json($products->pluck('stock', 'id'));

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('posCart', JSON.stringify(cart));
}

function showOutOfStock() {
    showToast('Produk ini sedang habis', 'error');
}

function addToCart(product) {
    // Check if product has stock
    if (product.stock <= 0) {
        showToast('Stock produk habis', 'error');
        return;
    }
    
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        // Check if adding more would exceed stock
        if (existingItem.quantity >= product.stock) {
            showToast(`Stock tidak cukup. Tersedia: ${product.stock}`, 'error');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: parseFloat(product.price),
            image: product.image,
            stock: product.stock,
            quantity: 1
        });
    }
    
    saveCart();
    updateCart();
    showToast('Produk ditambahkan ke keranjang', 'success');
}

function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    const btnBuatPesanan = document.getElementById('btnBuatPesanan');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full">
                <svg class="w-20 h-20 text-gray-300 mb-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                <p class="text-center text-gray-400 font-medium">Keranjang kosong</p>
            </div>
        `;
        cartTotal.textContent = 'Rp 0';
        btnBuatPesanan.disabled = true;
        return;
    }
    
    let html = '';
    let total = 0;
    
    cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        html += `
            <div class="cart-item-soft">
                <div class="flex items-start gap-2 mb-2">
                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                        ${item.image ? 
                            `<img src="/storage/${item.image}" alt="Produk" class="w-full h-full object-cover">` :
                            `<svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>`
                        }
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-sm text-gray-800 truncate leading-tight">${item.name}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Rp ${item.price.toLocaleString('id-ID')}</p>
                        <p class="text-xs text-gray-500">Stock: ${item.stock}</p>
                    </div>
                    <button onclick="removeItem(${index})" 
                            class="text-red-500 hover:text-red-700 p-1 flex-shrink-0 hover:bg-red-50 rounded-lg transition-colors"
                            title="Hapus item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 bg-white rounded-lg p-1 border border-gray-200 shadow-sm">
                        <button onclick="decreaseQuantity(${index})" 
                                class="w-7 h-7 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg flex items-center justify-center hover:from-red-600 hover:to-red-700 transition-all active:scale-95 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input type="number" 
                               value="${item.quantity}" 
                               min="1"
                               max="${item.stock}"
                               onchange="updateQuantity(${index}, this.value)"
                               class="w-12 text-center text-sm font-bold border-0 focus:outline-none focus:ring-0 bg-transparent">
                        <button onclick="increaseQuantity(${index})" 
                                ${item.quantity >= item.stock ? 'disabled' : ''}
                                class="w-7 h-7 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg flex items-center justify-center hover:from-green-600 hover:to-green-700 transition-all active:scale-95 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">Rp ${subtotal.toLocaleString('id-ID')}</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItemsDiv.innerHTML = html;
    cartTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    btnBuatPesanan.disabled = false;
}

function removeItem(index) {
    cart.splice(index, 1);
    saveCart();
    updateCart();
}

function increaseQuantity(index) {
    if (cart[index].quantity >= cart[index].stock) {
        showToast(`Stock tidak cukup. Tersedia: ${cart[index].stock}`, 'error');
        return;
    }
    cart[index].quantity++;
    saveCart();
    updateCart();
}

function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
    } else {
        cart.splice(index, 1);
    }
    saveCart();
    updateCart();
}

function updateQuantity(index, quantity) {
    const qty = parseInt(quantity);
    if (qty > 0 && qty <= cart[index].stock) {
        cart[index].quantity = qty;
    } else if (qty > cart[index].stock) {
        showToast(`Stock tidak cukup. Tersedia: ${cart[index].stock}`, 'error');
        cart[index].quantity = cart[index].stock;
    } else {
        cart.splice(index, 1);
    }
    saveCart();
    updateCart();
}

function openClearCartModal() {
    if (cart.length === 0) {
        showToast('Keranjang sudah kosong', 'error');
        return;
    }
    
    const modal = document.getElementById('clearCartModal');
    const modalContent = document.getElementById('clearCartModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeClearCartModal() {
    const modal = document.getElementById('clearCartModal');
    const modalContent = document.getElementById('clearCartModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function confirmClearCart() {
    cart = [];
    saveCart();
    updateCart();
    closeClearCartModal();
    showToast('Keranjang berhasil dikosongkan', 'success');
}

function openOrderModal() {
    const modal = document.getElementById('orderModal');
    const modalCartItems = document.getElementById('modalCartItems');
    const modalTotal = document.getElementById('modalTotal');
    
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        html += `
            <tr class="border-b border-gray-100">
                <td class="py-2">${item.name}</td>
                <td class="text-center py-2 font-semibold">${item.quantity}</td>
                <td class="text-right py-2 font-semibold">Rp ${subtotal.toLocaleString('id-ID')}</td>
            </tr>
        `;
    });
    
    modalCartItems.innerHTML = html;
    modalTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function confirmOrder() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const items = cart.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.price,
        subtotal: item.price * item.quantity
    }));
    
    const btnConfirm = document.getElementById('btnConfirmOrder');
    btnConfirm.disabled = true;
    btnConfirm.textContent = 'Memproses...';
    
    fetch('/pos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            items: items,
            total: total
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.transaction) {
            closeOrderModal();
            
            if (typeof window.showPostTransactionModal === 'function') {
                window.showPostTransactionModal(data.transaction);
            } else {
                alert('Transaksi berhasil! Invoice: ' + data.invoice_number);
            }
            
            cart = [];
            saveCart();
            updateCart();
            
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Konfirmasi Pesanan';
            
            // Reload page to update stock display
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            
        } else {
            alert('Transaksi gagal: ' + (data.message || 'Terjadi kesalahan'));
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Konfirmasi Pesanan';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghubungi server');
        btnConfirm.disabled = false;
        btnConfirm.textContent = 'Konfirmasi Pesanan';
    });
}

function filterCategory(categoryId) {
    const url = new URL(window.location.href);
    const searchParam = url.searchParams.get('search');
    
    url.searchParams.delete('category');
    url.searchParams.delete('page');
    
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    }
    if (searchParam) {
        url.searchParams.set('search', searchParam);
    }
    
    window.location.href = url.toString();
}

// Search functionality with debounce
let searchTimeout;
document.getElementById('searchProduct').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
        const searchTerm = e.target.value;
        const url = new URL(window.location.href);
        const categoryParam = url.searchParams.get('category');
        
        url.searchParams.delete('search');
        url.searchParams.delete('page');
        
        if (searchTerm) {
            url.searchParams.set('search', searchTerm);
        }
        if (categoryParam) {
            url.searchParams.set('category', categoryParam);
        }
        
        window.location.href = url.toString();
    }, 500);
});

// Toast notification with type (success or error)
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    
    // Set background color based on type
    const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-slideInDown flex items-center gap-3`;
    
    // Add icon based on type
    const icon = type === 'error' 
        ? `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.707 8.707 7.293z" clip-rule="evenodd"/>
           </svg>`
        : `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
           </svg>`;
    
    toast.innerHTML = icon + `<span>${message}</span>`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchValue = urlParams.get('search');
    if (searchValue) {
        document.getElementById('searchProduct').value = searchValue;
    }
    
    updateCart();
});
</script>
@endpush
@endsection