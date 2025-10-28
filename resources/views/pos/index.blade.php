@extends('layouts.app')

@section('title', 'POS - Point of Sale')

@section('content')
<div class="flex flex-col lg:flex-row gap-4 lg:gap-6 h-auto lg:h-[calc(100vh-140px)]">
    <!-- Left Section - Products -->
    <div class="flex-1 flex flex-col bg-white rounded-lg p-4 lg:p-6 order-2 lg:order-1">
        <div class="mb-4">
            <div class="relative">
                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                <input type="text" 
                       id="searchProduct"
                       placeholder="Cari berdasarkan nama produk"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-500">
            </div>
        </div>

        <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
            <button onclick="filterCategory('')" 
                    class="category-tab {{ !request('category') ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700' }} px-4 py-2 rounded-lg whitespace-nowrap hover:bg-gray-600 hover:text-white transition">
                 Semua
            </button>
            @foreach($categories as $category)
            <button onclick="filterCategory('{{ $category->id }}')" 
                    class="category-tab {{ request('category') == $category->id ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700' }} px-4 py-2 rounded-lg whitespace-nowrap hover:bg-gray-600 hover:text-white transition">
                {{ $category->name }}
            </button>
            @endforeach
        </div>

        <div id="productsGrid" class="flex-1 overflow-y-auto pb-4">
           <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-5 gap-3 lg:gap-4 auto-rows-max">
                @forelse($products as $product)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition flex flex-col">
                    <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden relative shrink-0">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="Produk" 
                                 class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        
                        <button onclick='addToCart(@json($product))' 
                                class="absolute bottom-2 right-2 w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:bg-green-600 transition shadow-lg z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-3 bg-white border-t border-gray-100 grow flex flex-col justify-between">
                        <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 leading-tight" title="{{ $product->name }}">{{ $product->name }}</h3>
                        <p class="text-sm font-bold text-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-400">
                    Tidak ada produk
                </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4 flex justify-center border-t pt-4">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>

<!-- Right Section - Cart -->
<div class="lg:w-96 w-full bg-white rounded-lg shadow-lg flex flex-col order-1 lg:order-2 h-[70vh] lg:h-auto">
    <div class="p-4 border-b shrink-0">
        <h2 class="text-lg lg:text-xl font-bold text-gray-800">{{ ucfirst(Auth::user()->role) }}</h2>
        <p class="text-xs lg:text-sm text-gray-500">{{ Auth::user()->username }}</p>
    </div>
    
    <div id="cartItems" class="flex-1 overflow-y-auto p-3 lg:p-4 space-y-2">
        <p class="text-center text-gray-400 py-8">Keranjang kosong</p>
    </div>

    <div class="border-t p-4 space-y-3 shrink-0 bg-white">
        <div class="flex justify-between items-center text-lg font-bold">
            <span>Total:</span>
            <span id="cartTotal" class="text-xl text-orange-600">Rp 0</span>
        </div>

        <button onclick="openOrderModal()" 
                id="btnBuatPesanan"
                class="w-full py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-semibold disabled:bg-gray-300 disabled:cursor-not-allowed text-sm lg:text-base"
                disabled>
            Pesan Sekarang
        </button>
        
        <button onclick="clearCart()" 
                class="w-full py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-sm lg:text-base">
            Kosongkan Keranjang
        </button>
    </div>
</div>

<!-- Modal Konfirmasi Pesanan -->
<div id="orderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-4 lg:p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeOrderModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        
        <h3 class="text-lg lg:text-xl font-bold mb-4 text-gray-800">Buat Pesanan</h3>
        
        <div class="overflow-x-auto mb-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 text-xs lg:text-sm">Nama</th>
                        <th class="text-center py-2 text-xs lg:text-sm">Jumlah</th>
                        <th class="text-right py-2 text-xs lg:text-sm">Total Harga</th>
                    </tr>
                </thead>
                <tbody id="modalCartItems" class="text-xs lg:text-sm">
                </tbody>
            </table>
        </div>
        
        <div class="border-t pt-4 mb-4">
            <div class="flex justify-between font-bold text-base lg:text-lg">
                <span>Total:</span>
                <span id="modalTotal">Rp 0</span>
            </div>
        </div>
        
        <button onclick="confirmOrder()" 
                id="btnConfirmOrder"
                class="w-full py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold text-sm lg:text-base">
            Konfirmasi Pesanan
        </button>
    </div>
</div>

<!-- Modal Transaksi Berhasil dengan Opsi Print -->
<div id="postTransactionDetailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-4 lg:p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
        <button onclick="closePostTransactionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        
        <!-- Success Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h3 class="text-lg lg:text-xl font-bold mb-4 text-green-600 text-center">Transaksi Berhasil!</h3> 
        
        <div class="mb-4 space-y-2 text-sm bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Invoice:</span>
                <span id="postDetailModalInvoice" class="font-mono font-semibold"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Tanggal:</span>
                <span id="postDetailModalTanggal"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Kasir:</span>
                <span id="postDetailModalKasir"></span>
            </div>
        </div>

        <div class="overflow-x-auto mb-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-2 text-xs lg:text-sm px-2">Nama</th>
                        <th class="text-center py-2 text-xs lg:text-sm px-2">Jml</th>
                        <th class="text-right py-2 text-xs lg:text-sm px-2">Total</th>
                    </tr>
                </thead>
                <tbody id="postDetailModalItemsBody" class="text-xs lg:text-sm divide-y">
                </tbody>
            </table>
        </div>
        
        <div class="border-t pt-4 mb-4">
            <div class="flex justify-between font-bold text-base lg:text-lg">
                <span>TOTAL:</span>
                <span id="postDetailModalTotal" class="text-green-600">Rp 0</span>
            </div>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="space-y-2">
            <button onclick="printReceipt()" 
                    class="w-full py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition font-semibold flex items-center justify-center gap-2 text-sm lg:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Struk
            </button>
            
            <button onclick="closePostTransactionModal()" 
                    class="w-full py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-sm lg:text-base">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Hidden Receipt Content untuk Print -->
<div id="receiptContent" style="display: none;">
    <div style="width: 300px; font-family: 'Courier New', monospace; font-size: 12px; padding: 20px; background: white;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 15px;">
            <h2 style="margin: 0; font-size: 18px; font-weight: bold;">{{ config('app.store_name', 'Dupa Radha Kresna') }}</h2>
            <p style="margin: 5px 0; font-size: 10px;">{{ config('app.store_address', 'Jl. Manggis II, Bjr. Candi Baru, Gianyar, Bali') }}</p>
            <p style="margin: 5px 0; font-size: 10px;">Telp: {{ config('app.store_phone', '0821 4510 7268') }}</p>
        </div>

        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

        <!-- Transaction Info -->
        <div style="font-size: 10px; margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                <span>Invoice:</span>
                <span id="receipt-invoice"></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                <span>Tanggal:</span>
                <span id="receipt-date"></span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Kasir:</span>
                <span id="receipt-cashier"></span>
            </div>
        </div>

        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

        <!-- Items Header -->
        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 10px; margin-bottom: 5px;">
            <span style="flex: 2;">Item</span>
            <span style="flex: 1; text-align: center;">Jml</span>
            <span style="flex: 1; text-align: right;">Harga</span>
            <span style="flex: 1; text-align: right;">Subtotal</span>
        </div>

        <!-- Items List -->
        <div id="receipt-items" style="font-size: 10px;"></div>

        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

        <!-- Total -->
        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 14px; margin: 10px 0;">
            <span>TOTAL:</span>
            <span id="receipt-total">Rp 0</span>
        </div>

        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

        <!-- Footer -->
        <div style="text-align: center; font-size: 10px; margin-top: 15px;">
            <p style="margin: 3px 0;">Terima kasih atas kunjungan Anda!</p>
            <p style="margin: 3px 0;">--- Barang yang sudah dibeli ---</p>
            <p style="margin: 3px 0;">--- tidak dapat dikembalikan ---</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Load cart from localStorage
let cart = JSON.parse(localStorage.getItem('posCart')) || [];
let currentTransactionData = null; // Store current transaction data

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('posCart', JSON.stringify(cart));
}

function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: parseFloat(product.price),
            image: product.image,
            quantity: 1
        });
    }
    
    saveCart();
    updateCart();
}

function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    const btnBuatPesanan = document.getElementById('btnBuatPesanan');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = '<p class="text-center text-gray-400 py-8">Keranjang kosong</p>';
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
            <div class="bg-gray-50 rounded-lg p-2 hover:bg-gray-100 transition">
                <!-- Product Header -->
                <div class="flex items-start gap-2 mb-2">
                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center flex-shrink-0 overflow-hidden">
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
                    </div>
                    <button onclick="removeItem(${index})" 
                            class="text-red-500 hover:text-red-700 p-1 flex-shrink-0"
                            title="Hapus item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Quantity Controls -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 bg-white rounded-lg p-1 border border-gray-200">
                        <button onclick="decreaseQuantity(${index})" 
                                class="w-7 h-7 bg-red-500 text-white rounded flex items-center justify-center hover:bg-red-600 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input type="number" 
                               value="${item.quantity}" 
                               min="1"
                               onchange="updateQuantity(${index}, this.value)"
                               class="w-12 text-center text-sm font-semibold border-0 focus:outline-none focus:ring-0">
                        <button onclick="increaseQuantity(${index})" 
                                class="w-7 h-7 bg-green-500 text-white rounded flex items-center justify-center hover:bg-green-600 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
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

// Tambahkan fungsi baru untuk remove item
function removeItem(index) {
    if (confirm('Hapus item ini dari keranjang?')) {
        cart.splice(index, 1);
        saveCart();
        updateCart();
    }
}

function increaseQuantity(index) {
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
    if (qty > 0) {
        cart[index].quantity = qty;
    } else {
        cart.splice(index, 1);
    }
    saveCart();
    updateCart();
}

function clearCart() {
    if (cart.length > 0 && confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        saveCart();
        updateCart();
    }
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
            <tr class="border-b">
                <td class="py-2">${item.name}</td>
                <td class="text-center py-2">${item.quantity}</td>
                <td class="text-right py-2">Rp ${subtotal.toLocaleString('id-ID')}</td>
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
            // Simpan data transaksi
            currentTransactionData = data.transaction;
            
            // Tutup modal konfirmasi
            closeOrderModal();
            
            // Tampilkan modal sukses
            showPostTransactionModal(data.transaction);
            
            // Kosongkan keranjang
            cart = [];
            saveCart();
            updateCart();
            
            // Reset button
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Konfirmasi Pesanan';
            
        } else if (data.success) {
            alert('Transaksi berhasil! Invoice: ' + data.invoice_number);
            cart = [];
            saveCart();
            updateCart();
            closeOrderModal();
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Konfirmasi Pesanan';
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

function showPostTransactionModal(transactionData) {
    // Format data
    const formattedDate = new Date(transactionData.created_at).toLocaleString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    const formattedTotal = 'Rp ' + parseFloat(transactionData.total).toLocaleString('id-ID');

    // Isi modal
    document.getElementById('postDetailModalInvoice').textContent = transactionData.invoice_number;
    document.getElementById('postDetailModalTanggal').textContent = formattedDate;
    document.getElementById('postDetailModalKasir').textContent = transactionData.user.username;
    document.getElementById('postDetailModalTotal').textContent = formattedTotal;

    const itemsBody = document.getElementById('postDetailModalItemsBody');
    itemsBody.innerHTML = '';

    transactionData.items.forEach(item => {
        const itemTotal = 'Rp ' + parseFloat(item.subtotal).toLocaleString('id-ID');
        const row = `
            <tr class="border-gray-100">
                <td class="py-2 px-2">${item.product.name}</td>
                <td class="text-center py-2 px-2">${item.quantity}</td>
                <td class="text-right py-2 px-2">${itemTotal}</td>
            </tr>
        `;
        itemsBody.innerHTML += row;
    });

// Tampilkan modal
const modal = document.getElementById('postTransactionDetailModal');
modal.classList.remove('hidden');
modal.classList.add('flex');
}

function closePostTransactionModal() {
    const modal = document.getElementById('postTransactionDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentTransactionData = null;
}

function printReceipt() {
    if (!currentTransactionData) {
        alert('Data transaksi tidak tersedia');
        return;
    }

    // Format tanggal
    const date = new Date(currentTransactionData.created_at);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const formattedDate = `${day}/${month}/${year}, ${hours}:${minutes}`;

    // Generate items HTML
    let itemsHTML = '';
    currentTransactionData.items.forEach(item => {
        const itemName = item.product.name;
        const qty = item.quantity;
        const price = parseFloat(item.price);
        const subtotal = parseFloat(item.subtotal);
        
        itemsHTML += `
<div style="margin-bottom: 3px;">
  <div style="font-size: 13px;">${itemName}</div>
  <table width="100%" style="font-size: 12px;">
    <tr>
      <td width="65%">${qty} x ${price.toLocaleString('id-ID')}</td>
      <td width="35%" align="right">${subtotal.toLocaleString('id-ID')}</td>
    </tr>
  </table>
</div>
        `;
    });

    // Generate HTML untuk print
    const printHTML = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - ${currentTransactionData.invoice_number}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: bold;
            padding: 2mm;
            width: 50mm;
            background: white;
            color: #000;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #000;
        }
        
        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .header p {
            font-size: 10px;
            margin: 1px 0;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        
        .info {
            font-size: 10px;
            margin: 5px 0;
        }
        
        .info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info td {
            padding: 1px 0;
        }
        
        .info td:first-child {
            width: 30%;
        }
        
        .info td:last-child {
            width: 70%;
            text-align: right;
        }
        
        .items {
            margin: 5px 0;
        }
        
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items td {
            padding: 0;
        }
        
        .total-section {
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .total-section table {
            width: 100%;
            font-size: 13px;
        }
        
        .total-section td {
            padding: 2px 0;
        }
        
        .footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 5px;
            border-top: 1px dashed #000;
            font-size: 10px;
            line-height: 1.3;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 3mm;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>Dupa Radha Kresna</h1>
    <p>Jl. Manggis II, Bjr Candi Baru Gianyar, Bali</p>
    <p>Telp: 0821 4510 7268</p>
</div>

<div class="divider"></div>

<!-- Info Transaksi -->
<div class="info">
    <table>
        <tr>
            <td>Invoice:</td>
            <td>${currentTransactionData.invoice_number}</td>
        </tr>
        <tr>
            <td>Tanggal:</td>
            <td>${formattedDate}</td>
        </tr>
        <tr>
            <td>Kasir:</td>
            <td>${currentTransactionData.user.username}</td>
        </tr>
    </table>
</div>

<div class="divider"></div>

<!-- Items -->
<div class="items">
    ${itemsHTML}
</div>

<!-- Total -->
<div class="total-section">
    <table>
        <tr>
            <td>TOTAL:</td>
            <td align="right">Rp ${parseFloat(currentTransactionData.total).toLocaleString('id-ID')}</td>
        </tr>
    </table>
</div>

<!-- Footer -->
<div class="footer">
    <p>Terima kasih atas</p>
    <p>kunjungan Anda!</p>
    <p style="margin-top: 5px;">--- Barang yang ---</p>
    <p> sudah dibeli </p>
    <p>--- tidak dapat ---</p>
    <p>dikembalikan</p>
</div>

</body>
</html>
    `;

    // Buka window print
    const printWindow = window.open('', '', 'width=200,height=600');
    printWindow.document.write(printHTML);
    printWindow.document.close();
    
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 300);
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