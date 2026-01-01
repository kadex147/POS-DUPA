@extends('layouts.app')

@section('title', 'Dashboard - Point of Sale')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards - Responsive Grid dengan Soft Design -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Total Pemasukan -->
        <div class="stats-card-soft">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 lg:w-16 lg:h-16 bg-linear-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
                    <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-xs lg:text-sm text-gray-500 mb-1 font-medium">Total Pemasukan</h3>
                    <p class="text-xl lg:text-2xl font-bold text-gray-800 truncate">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Produk Terjual -->
        <div class="stats-card-soft">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 lg:w-16 lg:h-16 bg-linear-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
                    <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 8a3 3 0 013-3h8a3 3 0 013 3v10a3 3 0 01-3 3H8a3 3 0 01-3-3V8zm3 0a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1V9a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M9 11a1 1 0 011-1h4a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-xs lg:text-sm text-gray-500 mb-1 font-medium">Jumlah Produk Terjual</h3>
                    <p class="text-xl lg:text-2xl font-bold text-gray-800">{{ $totalProductsSold }}</p>
                </div>
            </div>
        </div>

        <!-- Jumlah Pesanan -->
        <div class="stats-card-soft sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 lg:w-16 lg:h-16 bg-linear-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
                    <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-xs lg:text-sm text-gray-500 mb-1 font-medium">Jumlah Pesanan</h3>
                    <p class="text-xl lg:text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Responsive Grid -->
     <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 lg:gap-6">
        <!-- Income Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800">Pendapatan</h3>
                    
                    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                        <!-- Tombol Filter Existing -->
                        <a href="{{ route('dashboard', ['period' => '7days']) }}" 
                           class="btn-soft px-3 py-1.5 text-xs lg:text-sm {{ $period === '7days' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            7 Hari
                        </a>
                        <a href="{{ route('dashboard', ['period' => '30days']) }}" 
                           class="btn-soft px-3 py-1.5 text-xs lg:text-sm {{ $period === '30days' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            30 Hari
                        </a>
                        <a href="{{ route('dashboard', ['period' => '12months']) }}" 
                           class="btn-soft px-3 py-1.5 text-xs lg:text-sm {{ $period === '12months' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            12 Bulan
                        </a>
                        <button onclick="toggleCustomFilter()" 
                                class="btn-soft px-3 py-1.5 text-xs lg:text-sm {{ $period === 'custom' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Custom
                        </button>
                        
                        <!-- TOMBOL CETAK BARU -->
                        <!-- Menggunakan request()->query() agar filter custom ikut terbawa -->
                        <a href="{{ route('dashboard.print', request()->query()) }}" target="_blank"
                           class="btn-soft px-3 py-1.5 text-xs lg:text-sm bg-blue-600 text-white hover:bg-blue-700 flex items-center gap-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak
                        </a>
                    </div>
                </div>
                <!-- Custom Filter Form dengan Soft Design -->
                <div id="customFilterForm" class="mb-4 {{ $period === 'custom' ? '' : 'hidden' }}">
                    <form method="GET" action="{{ route('dashboard') }}" class="soft-card bg-gray-50 p-4 border border-gray-100">
                        <input type="hidden" name="period" value="custom">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                            <!-- Pilih Tahun -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tahun</label>
                                <select name="year" class="input-soft w-full text-sm" required>
                                    @php
                                        $currentYear = date('Y');
                                        $selectedYear = request('year', $currentYear);
                                        $oldestYear = \App\Models\Transaction::min(\DB::raw('YEAR(created_at)')) ?? $currentYear;
                                    @endphp
                                    @for($y = $currentYear; $y >= $oldestYear; $y--)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Pilih Bulan -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Bulan</label>
                                <select name="month" class="input-soft w-full text-sm" required>
                                    @php
                                        $months = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        $selectedMonth = request('month', date('n'));
                                    @endphp
                                    @foreach($months as $num => $name)
                                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-soft w-full bg-gray-600 text-white hover:bg-gray-700 text-sm font-semibold py-2.5">
                            Tampilkan Data
                        </button>
                    </form>
                </div>

                <div class="h-64 lg:h-80"> 
                    <canvas id="incomeChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500">{{ $subLabel }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1">({{ $dateRangeString }})</p>
                </div>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-4 lg:p-8">
                <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4">Produk Terlaris</h3>
                <div class="h-80 lg:h-96"> 
                    @if($topProducts->isEmpty())
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-400 text-center">Belum ada data produk terjual</p>
                        </div>
                    @else
                        <canvas id="topProductsChart"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Mobile: Card View dengan Header dan Pagination - HANYA TAMPIL DI MOBILE -->
<div class="block lg:hidden mb-6">
    <!-- Header Riwayat Transaksi Mobile -->
    <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-800">Riwayat Transaksi</h3>
    </div>

    <!-- Cards -->
    <div class="space-y-3 mb-4">
        @forelse($recentTransactions as $transaction)
        <div class="cart-item-soft">
            <div class="flex justify-between items-start mb-2">
                <a href="#" onclick="showTransactionDetails(event, {{ $transaction->id }})" 
                   class="text-blue-600 hover:text-blue-800 font-semibold text-sm hover:underline">
                    {{ $transaction->invoice_number }}
                </a>
                <span class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">{{ $transaction->user->username }}</span>
                <span class="font-bold text-green-600">+ Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100">
                <button onclick="confirmDeleteTransaction({{ $transaction->id }}, '{{ addslashes($transaction->invoice_number) }}')" 
                        class="text-red-600 hover:text-red-800 text-xs font-semibold flex items-center gap-1 hover:bg-red-50 px-2 py-1 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-8 bg-white rounded-xl border border-gray-100">
            <p class="text-gray-400 text-sm">Belum ada transaksi</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Mobile -->
    @if($recentTransactions->hasPages())
    <div class="pagination-soft bg-white p-4 rounded-xl border border-gray-100">
        <div class="flex items-center justify-center gap-3">
            @if($recentTransactions->onFirstPage())
                <button disabled class="text-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            @else
                <a href="{{ $recentTransactions->appends(request()->query())->previousPageUrl() }}" class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 p-1 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @endif

            <span class="current-page px-3 py-1 bg-gray-50 border border-gray-200 rounded-md text-sm font-medium text-gray-700">{{ $recentTransactions->currentPage() }}</span>

            @if($recentTransactions->hasMorePages())
                <a href="{{ $recentTransactions->appends(request()->query())->nextPageUrl() }}" class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 p-1 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
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

 <!-- Desktop: Recent Transactions Table - HANYA TAMPIL DI DESKTOP -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Header Tabel -->
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Riwayat Transaksi</h3>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-linear-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Invoice</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kasir</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="#" onclick="showTransactionDetails(event, {{ $transaction->id }})" 
                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $transaction->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                            {{ $transaction->user->username ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-bold text-green-600">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <button onclick="confirmDeleteTransaction({{ $transaction->id }}, '{{ $transaction->invoice_number }}')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all inline-block">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination dengan Soft Design -->
        <div class="pagination-soft p-4 lg:px-6 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-center gap-3">
                @if($recentTransactions->onFirstPage())
                    <button disabled class="text-gray-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $recentTransactions->appends(request()->query())->previousPageUrl() }}" class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 p-1 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endif

                <span class="current-page px-3 py-1 bg-white border border-gray-200 rounded-md text-sm font-medium text-gray-700 shadow-sm">{{ $recentTransactions->currentPage() }}</span>

                @if($recentTransactions->hasMorePages())
                    <a href="{{ $recentTransactions->appends(request()->query())->nextPageUrl() }}" class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 p-1 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
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
</div>

<!-- Modal Konfirmasi Hapus Transaksi -->
<div id="deleteTransactionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 w-full max-w-md relative transform transition-all duration-300 scale-95 opacity-0" id="deleteTransactionModalContent">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-linear-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        
        <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Hapus Transaksi?</h3>
        <p class="text-center text-gray-600 text-sm mb-2">
            Invoice: <span id="deleteInvoiceNumber" class="font-bold"></span>
        </p>
        <p class="text-center text-gray-500 text-xs mb-6">Tindakan ini tidak dapat dibatalkan</p>
        
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 active:scale-95">
                Batal
            </button>
            <button onclick="deleteTransaction()" 
                    id="btnConfirmDelete"
                    class="flex-1 py-3 bg-linear-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 active:scale-95 shadow-lg">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
// Global variable untuk ID transaksi yang akan dihapus
let transactionToDelete = null;

/**
 * Konfirmasi hapus transaksi
 */
function confirmDeleteTransaction(transactionId, invoiceNumber) {
    transactionToDelete = transactionId;
    document.getElementById('deleteInvoiceNumber').textContent = invoiceNumber;
    
    const modal = document.getElementById('deleteTransactionModal');
    const modalContent = document.getElementById('deleteTransactionModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

/**
 * Tutup modal konfirmasi hapus
 */
function closeDeleteModal() {
    const modal = document.getElementById('deleteTransactionModal');
    const modalContent = document.getElementById('deleteTransactionModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        transactionToDelete = null;
    }, 300);
}

/**
 * Hapus transaksi
 */
function deleteTransaction() {
    if (!transactionToDelete) return;
    
    const btnConfirm = document.getElementById('btnConfirmDelete');
    btnConfirm.disabled = true;
    btnConfirm.textContent = 'Menghapus...';
    
    fetch(`/transactions/${transactionToDelete}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeleteModal();
            showToast('Transaksi berhasil dihapus', 'success');
            
            // Reload halaman setelah 1 detik
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert('Gagal menghapus transaksi: ' + (data.message || 'Terjadi kesalahan'));
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Ya, Hapus';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus transaksi');
        btnConfirm.disabled = false;
        btnConfirm.textContent = 'Ya, Hapus';
    });
}

/**
 * Toast notification
 */
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-slideInDown flex items-center gap-3`;
    
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
</script>

{{-- Include Modal Print Component --}}
@include('components.modal-print')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Toggle Custom Filter Form
    function toggleCustomFilter() {
        const form = document.getElementById('customFilterForm');
        form.classList.toggle('hidden');
    }

    // Fungsi untuk menampilkan detail transaksi
    function showTransactionDetails(event, transactionId) {
        event.preventDefault();
        fetch(`/transaction-details/${transactionId}`)
            .then(response => response.json())
            .then(data => {
                window.openPrintModal(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail transaksi.');
            });
    }

    // Chart data
    const incomeData = @json($incomeData);
    const topProductsData = @json($topProducts);
    const currentPeriod = @json($period);

    // Income Chart dengan styling yang lebih soft
    const ctxIncome = document.getElementById('incomeChart').getContext('2d');
    const labels = incomeData.map(item => item.label);
    const data = incomeData.map(item => item.total);
    const rawMaxIncome = data.length > 0 ? Math.max(...data) : 0;
    const maxIncome = rawMaxIncome > 0 ? rawMaxIncome : 1000;

    const incomeChart = new Chart(ctxIncome, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                borderColor: 'rgb(107, 114, 128)',
                backgroundColor: 'rgba(107, 114, 128, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: 'rgb(107, 114, 128)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                pointHitRadius: 70,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    displayColors: false,
                    borderRadius: 8,
                    callbacks: {
                        title: function(context) {
                            const index = context[0].dataIndex;
                            const item = incomeData[index];
                            if (!item) return '';
                            
                            if (currentPeriod === '7days' || currentPeriod === '30days') {
                                const parts = item.date.split('-');
                                const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
                                return new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(dateObj);
                            }
                            if (currentPeriod === '12months') {
                                const parts = item.month.split('-');
                                const dateObj = new Date(parts[0], parts[1] - 1, 1);
                                return new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' }).format(dateObj);
                            }
                            if (currentPeriod === 'custom') {
                                const parts = item.date.split('-');
                                const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
                                return new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(dateObj);
                            }
                            return context[0].label;
                        },
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: maxIncome,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.03)' }
                },
                x: {
                    ticks: {
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // Top Products Chart
    const ctxProducts = document.getElementById('topProductsChart');
    if (ctxProducts && topProductsData.length > 0) {
        const pieLabels = topProductsData.map(item => item.name);
        const pieData = topProductsData.map(item => parseFloat(item.total_quantity));
        const dataSum = pieData.reduce((a, b) => a + b, 0);
        const pieColors = topProductsData.map((_, i) => `hsl(${(i * (360 / topProductsData.length))}, 70%, 60%)`);

        const topProductsChart = new Chart(ctxProducts.getContext('2d'), {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: pieData,
                    backgroundColor: pieColors,
                    hoverOffset: 8,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 12 },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const dataset = data.datasets[0];
                                    const chartData = dataset.data;
                                    return data.labels.map((label, i) => {
                                        const meta = chart.getDatasetMeta(0);
                                        const style = meta.controller.getStyle(i);
                                        const value = chartData[i];
                                        return {
                                            text: `${label}: ${value}`,
                                            fillStyle: style.backgroundColor,
                                            strokeStyle: style.borderColor,
                                            lineWidth: style.borderWidth,
                                            hidden: !chart.getDataVisibility(i),
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        },
                        title: {
                            display: true,
                            text: '10 Produk Terlaris',
                            padding: 10,
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const percentage = ((value / dataSum) * 100).toFixed(1) + '%';
                                return `${label}: ${value} terjual (${percentage})`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Show custom filter if period is custom
    @if($period === 'custom')
        document.getElementById('customFilterForm').classList.remove('hidden');
    @endif
</script>

@endsection