@extends('layouts.app')

@section('title', 'Dashboard - Point of Sale')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Total Pemasukan -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
            <div class="flex items-center gap-3 lg:gap-4">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-xs lg:text-sm text-gray-500 mb-1">Total Pemasukan</h3>
                    <p class="text-lg lg:text-2xl font-semibold text-gray-800 truncate">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Produk Terjual -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
            <div class="flex items-center gap-3 lg:gap-4">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 8a3 3 0 013-3h8a3 3 0 013 3v10a3 3 0 01-3 3H8a3 3 0 01-3-3V8zm3 0a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1V9a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M9 11a1 1 0 011-1h4a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-xs lg:text-sm text-gray-500 mb-1">Jumlah Produk Terjual</h3>
                    <p class="text-lg lg:text-2xl font-semibold text-gray-800">{{ $totalProductsSold }}</p>
                </div>
            </div>
        </div>

        <!-- Jumlah Pesanan -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-3 lg:gap-4">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-xs lg:text-sm text-gray-500 mb-1">Jumlah Pesanan</h3>
                    <p class="text-lg lg:text-2xl font-semibold text-gray-800">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Responsive Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 lg:gap-6">
        <!-- Income Chart -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Pendapatan</h3>
                
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('dashboard', ['period' => '7days']) }}" 
                       class="px-3 py-1 text-xs lg:text-sm rounded {{ $period === '7days' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                        7 Hari
                    </a>
                    <a href="{{ route('dashboard', ['period' => '30days']) }}" 
                       class="px-3 py-1 text-xs lg:text-sm rounded {{ $period === '30days' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                        30 Hari
                    </a>
                    <a href="{{ route('dashboard', ['period' => '12months']) }}" 
                       class="px-3 py-1 text-xs lg:text-sm rounded {{ $period === '12months' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                        12 Bulan
                    </a>
                </div>
            </div>

            <div class="h-64 lg:h-80"> 
                <canvas id="incomeChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500">{{ $subLabel }}</p>
                <p class="text-xs text-gray-600 font-semibold mt-1">({{ $dateRangeString }})</p>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
            <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-4">Produk Terlaris</h3>
            <div class="h-96 lg:h-124"> 
                @if($topProducts->isEmpty())
                    <p class="text-gray-400 text-center py-8">Belum ada data produk terjual</p>
                @else
                    <canvas id="topProductsChart"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table - Responsive -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 lg:p-6">
        <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-4">Riwayat Transaksi Terbaru</h3>
        
        <!-- Mobile: Card View -->
        <div class="block lg:hidden space-y-3">
            @forelse($recentTransactions as $transaction)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <a href="#" onclick="showTransactionDetails(event, {{ $transaction->id }})" 
                       class="text-blue-600 hover:text-blue-800 hover:underline font-medium text-sm">
                        {{ $transaction->invoice_number }}
                    </a>
                    <span class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">{{ $transaction->user->username }}</span>
                    <span class="font-semibold text-green-600">+ Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8 text-sm">Belum ada transaksi</p>
            @endforelse
        </div>

        <!-- Desktop: Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Invoice</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kasir</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="#" onclick="showTransactionDetails(event, {{ $transaction->id }})" 
                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $transaction->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $transaction->user->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-green-600">
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

        <!-- Pagination -->
        <div class="bg-gray-50 px-4 lg:px-6 py-4 border-t border-gray-200 -mx-4 lg:-mx-6 -mb-4 lg:-mb-6 mt-4 rounded-b-lg">
            <div class="flex items-center justify-center gap-4">
                @if($recentTransactions->onFirstPage())
                    <button disabled class="p-2 text-gray-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $recentTransactions->previousPageUrl() }}" class="p-2 hover:bg-gray-100 rounded">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endif

                <span class="text-sm text-gray-700">{{ $recentTransactions->currentPage() }}</span>

                @if($recentTransactions->hasMorePages())
                    <a href="{{ $recentTransactions->nextPageUrl() }}" class="p-2 hover:bg-gray-100 rounded">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @else
                    <button disabled class="p-2 text-gray-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="transactionDetailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-4 lg:p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeTransactionDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
        <h3 class="text-lg lg:text-xl font-bold mb-4 text-gray-800">Detail Transaksi</h3>
        <div class="mb-4 space-y-1 text-sm text-gray-600">
            <div class="flex justify-between">
                <span class="font-semibold">Invoice:</span>
                <span id="detailModalInvoice" class="font-mono"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Tanggal:</span>
                <span id="detailModalTanggal"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Kasir:</span>
                <span id="detailModalKasir"></span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full mb-4">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 text-sm">Nama</th>
                        <th class="text-center py-2 text-sm">Jumlah</th>
                        <th class="text-right py-2 text-sm">Total Harga</th>
                    </tr>
                </thead>
                <tbody id="detailModalItemsBody" class="text-sm divide-y"></tbody>
            </table>
        </div>
        <div class="border-t pt-4 mb-4">
            <div class="flex justify-between font-bold text-base lg:text-lg">
                <span>Total:</span>
                <span id="detailModalTotal">Rp 0</span>
            </div>
        </div>
        <button onclick="closeTransactionDetailModal()" 
                class="w-full py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold text-sm lg:text-base">
            Tutup
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Modal functions
    function showTransactionDetails(event, transactionId) {
        event.preventDefault();
        fetch(`/transaction-details/${transactionId}`)
            .then(response => response.json())
            .then(data => {
                const formattedDate = new Date(data.created_at).toLocaleString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                const formattedTotal = 'Rp ' + parseFloat(data.total).toLocaleString('id-ID');

                document.getElementById('detailModalInvoice').textContent = data.invoice_number;
                document.getElementById('detailModalTanggal').textContent = formattedDate;
                document.getElementById('detailModalKasir').textContent = data.user.username;
                document.getElementById('detailModalTotal').textContent = formattedTotal;

                const itemsBody = document.getElementById('detailModalItemsBody');
                itemsBody.innerHTML = '';

                data.items.forEach(item => {
                    const itemTotal = 'Rp ' + parseFloat(item.subtotal).toLocaleString('id-ID');
                    const row = `
                        <tr class="border-gray-100">
                            <td class="py-2">${item.product.name}</td>
                            <td class="text-center py-2">${item.quantity}</td>
                            <td class="text-right py-2">${itemTotal}</td>
                        </tr>
                    `;
                    itemsBody.innerHTML += row;
                });

                document.getElementById('transactionDetailModal').classList.remove('hidden');
                document.getElementById('transactionDetailModal').classList.add('flex');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail transaksi.');
            });
    }

    function closeTransactionDetailModal() {
        const modal = document.getElementById('transactionDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Chart data
    const incomeData = @json($incomeData);
    const topProductsData = @json($topProducts);
    const currentPeriod = @json($period);

    // Income Chart
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
                borderColor: 'rgb(55, 65, 81)',
                backgroundColor: 'rgba(55, 65, 81, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: 'rgb(55, 65, 81)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
                pointHitRadius: 30,
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
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
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
                    hoverOffset: 4
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
</script>

@endsection