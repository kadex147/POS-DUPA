<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Point Of Sale</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: white;
            color: #111827;
            padding: 2rem;
        }
        
        /* Header */
        .header-section {
            border-bottom: 2px solid #1f2937;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
        }
        
        .header-left h1 {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .header-left p {
            font-size: 0.875rem;
            color: #4b5563;
            margin-top: 0.25rem;
        }
        
        .header-right {
            text-align: right;
        }
        
        .header-right h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .header-right p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .header-right p.bold {
            color: #1f2937;
            font-weight: bold;
            font-size: 0.875rem;
        }
        
        .header-right p.small {
            font-size: 0.75rem;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            background: #f9fafb;
        }
        
        .stat-item {
            text-align: center;
            border-right: 1px solid #e5e7eb;
        }
        
        .stat-item:last-child {
            border-right: none;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .stat-value {
            font-size: 1.125rem;
            font-weight: bold;
            color: #111827;
            margin-top: 0.25rem;
        }
        
        /* Table */
        .table-container {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f3f4f6;
        }
        
        th {
            padding: 0.5rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-right: 1px solid #e5e7eb;
        }
        
        th:last-child {
            border-right: none;
            text-align: right;
        }
        
        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        td {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            color: #374151;
            border-right: 1px solid #e5e7eb;
        }
        
        td:last-child {
            border-right: none;
            text-align: right;
        }
        
        td.center {
            text-align: center;
            width: 50px;
        }
        
        td.bold {
            font-weight: bold;
            color: #111827;
        }
        
        td.no-wrap {
            white-space: nowrap;
        }
        
        .empty-state {
            padding: 2rem 1rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }
        
        tfoot {
            background: #f3f4f6;
            font-weight: bold;
        }
        
        tfoot td {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
        
        tfoot td:first-child {
            text-align: right;
            text-transform: uppercase;
            color: #1f2937;
        }
        
        tfoot td:last-child {
            color: #111827;
        }
        
        /* Footer */
        .footer-section {
            margin-top: 3rem;
            display: flex;
            justify-content: flex-end;
        }
        
        .signature-box {
            text-align: center;
            padding-right: 2rem;
        }
        
        .signature-box p.label {
            font-size: 0.875rem;
            color: #4b5563;
            margin-bottom: 4rem;
        }
        
        .signature-box p.name {
            font-size: 0.875rem;
            font-weight: bold;
            color: #1f2937;
            border-top: 1px solid #9ca3af;
            padding-top: 0.5rem;
            padding-left: 2rem;
            padding-right: 2rem;
            display: inline-block;
        }
        
        /* Button */
        .close-button {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: #1f2937;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .close-button:hover {
            background: #374151;
        }
        
        @media print {
            @page {
                margin: 0.5cm;
                size: A4 portrait;
            }
            
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .close-button {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Header Laporan -->
    <div class="header-section">
        <div class="header-left">
            <h1>Dupa Radha Kresna</h1>
            <p>Jl. Manggis II, Bjr Candi Baru Gianyar, Bali</p>
            <p>Telp: 0821 4510 7268</p>
        </div>
        <div class="header-right">
            <h2>Laporan Transaksi Kasir</h2>
            <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
            <p class="bold">Kasir: {{ $kasirName }}</p>
            <p class="bold">{{ $subLabel }}</p>
            <p class="small">Periode: {{ $dateRangeString }}</p>
        </div>
    </div>

    <!-- Ringkasan Stats -->
    <div class="stats-grid">
        <div class="stat-item">
            <p class="stat-label">Total Pemasukan</p>
            <p class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="stat-item">
            <p class="stat-label">Total Transaksi</p>
            <p class="stat-value">{{ $totalOrders }}</p>
        </div>
        <div class="stat-item">
            <p class="stat-label">Produk Terjual</p>
            <p class="stat-value">{{ $totalProductsSold }} Item</p>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Invoice</th>
                    <th>Kasir</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $transaction)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="no-wrap">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td class="bold">{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->user->username ?? '-' }}</td>
                    <td class="bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Grand Total</td>
                    <td>Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer Tanda Tangan -->
    <div class="footer-section">
        <div class="signature-box">
            <p class="label">Mengetahui,</p>
            <p class="name">{{ $kasirName }}</p>
        </div>
    </div>

    <!-- Tombol Tutup -->
    <button onclick="window.close()" class="close-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        Tutup
    </button>

    <script>
        // Langsung print tanpa delay karena semua CSS sudah inline
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 100);
        });
    </script>

</body>
</html>