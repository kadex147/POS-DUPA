<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Point Of Sale</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 0.5cm; size: A4 portrait; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 p-8 font-sans" onload="window.print()">

    <!-- Header Laporan -->
    <div class="border-b-2 border-gray-800 pb-4 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-wide">Point Of Sale</h1>
                <p class="text-sm text-gray-600">Jl. Raya Bisnis No. 123, Jakarta</p>
                <p class="text-sm text-gray-600">Telp: (021) 555-0123</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-semibold text-gray-800">Laporan Transaksi</h2>
                <p class="text-sm text-gray-500 mt-1">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                <p class="text-sm font-bold text-gray-800 mt-1 uppercase">{{ $subLabel }}</p>
                <p class="text-xs text-gray-500">Periode: {{ $dateRangeString }}</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6 border border-gray-200 rounded-lg p-4 bg-gray-50">
        <div class="text-center border-r border-gray-200">
            <p class="text-xs text-gray-500 uppercase font-semibold">Total Pendapatan</p>
            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="text-center border-r border-gray-200">
            <p class="text-xs text-gray-500 uppercase font-semibold">Total Transaksi</p>
            <p class="text-lg font-bold text-gray-900">{{ $totalOrders }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase font-semibold">Produk Terjual</p>
            <p class="text-lg font-bold text-gray-900">{{ $totalProductsSold }} Item</p>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="overflow-hidden border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r">No</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r">Tanggal</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r">Invoice</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r">Kasir</th>
                    <th scope="col" class="px-4 py-2 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $index => $transaction)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                    <td class="px-4 py-2 text-xs text-gray-700 border-r text-center w-12">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 text-xs text-gray-700 border-r whitespace-nowrap">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2 text-xs font-medium text-gray-900 border-r">{{ $transaction->invoice_number }}</td>
                    <td class="px-4 py-2 text-xs text-gray-600 border-r">{{ $transaction->user->username ?? '-' }}</td>
                    <td class="px-4 py-2 text-xs font-bold text-gray-900 text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td colspan="4" class="px-4 py-2 text-right text-xs text-gray-800 uppercase">Grand Total</td>
                    <td class="px-4 py-2 text-right text-xs text-gray-900">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer Tanda Tangan (Opsional) -->
    <div class="mt-12 flex justify-end">
        <div class="text-center pr-8">
            <p class="text-sm text-gray-600 mb-16">Mengetahui,</p>
            <p class="text-sm font-bold text-gray-800 border-t border-gray-400 pt-2 px-8 inline-block">Manager Toko</p>
        </div>
    </div>

    <!-- Tombol Kembali (Hanya tampil di layar, hilang saat print) -->
    <div class="fixed bottom-6 right-6 no-print">
        <button onclick="window.close()" class="bg-gray-800 text-white px-6 py-3 rounded-full shadow-lg hover:bg-gray-700 transition font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Tutup
        </button>
    </div>

</body>
</html>