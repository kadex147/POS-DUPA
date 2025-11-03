@extends('layouts.app')

@section('title', 'Dashboard - Point of Sale')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards - Responsive Grid dengan Soft Design -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Total Pemasukan -->
        <div class="stats-card-soft">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
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
                <div class="w-14 h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
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
                <div class="w-14 h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
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
        <div class="soft-card">
            <div class="p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800">Pendapatan</h3>
                    
                    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
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
        <div class="soft-card">
            <div class="p-4 lg:p-6">
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

    <!-- Recent Transactions Table - Responsive dengan Soft Design -->
    <div class="soft-card">
        <div class="p-4 lg:p-6">
            <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4">Riwayat Transaksi Terbaru</h3>
            
            <!-- Mobile: Card View -->
            <div class="block lg:hidden space-y-3">
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
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-400 text-sm">Belum ada transaksi</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop: Table View -->
            <div class="hidden lg:block table-soft">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Invoice</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kasir</th>
                            <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" onclick="showTransactionDetails(event, {{ $transaction->id }})" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                    {{ $transaction->invoice_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $transaction->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $transaction->user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">
                                + Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination dengan Soft Design -->
            @if($recentTransactions->hasPages())
            <div class="pagination-soft mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-center gap-3">
                    @if($recentTransactions->onFirstPage())
                        <button disabled class="text-gray-300 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @else
                        <a href="{{ $recentTransactions->previousPageUrl() }}" class="hover:bg-gray-50">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endif

                    <span class="current-page px-3 py-1">{{ $recentTransactions->currentPage() }}</span>

                    @if($recentTransactions->hasMorePages())
                        <a href="{{ $recentTransactions->nextPageUrl() }}" class="hover:bg-gray-50">
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
    </div>
</div>

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
                            font: { size: 14, weight: 'bold' }
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