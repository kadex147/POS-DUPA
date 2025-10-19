<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '7days');

        $endDate = Carbon::now();
        $startDate = Carbon::now();
        $subLabel = '';

        if ($period == '7days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $subLabel = 'Menampilkan data 7 hari terakhir (per hari)';
        } elseif ($period == '30days') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $subLabel = 'Menampilkan data 30 hari terakhir (per hari)';
        } elseif ($period == '12months') {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $subLabel = 'Menampilkan data 12 bulan terakhir (per bulan)';
        }
        
        $dateRangeString = '';
        if ($startDate->isSameYear($endDate)) {
            if ($startDate->isSameMonth($endDate)) {
                $dateRangeString = $startDate->format('j') . ' - ' . $endDate->format('j M Y');
            } else {
                $dateRangeString = $startDate->format('j M') . ' - ' . $endDate->format('j M Y');
            }
        } else {
            $dateRangeString = $startDate->format('j M Y') . ' - ' . $endDate->format('j M Y');
        }

        $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total') ?? 0;
        
        // --- INI BAGIAN YANG DIUBAH ---
        // Menghitung jumlah semua item yang terjual berdasarkan rentang tanggal
        $totalProductsSold = TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->sum('transaction_items.quantity');
        $totalProductsSold = $totalProductsSold ?? 0; // Pastikan 0 jika null
        // --- BATAS PERUBAHAN ---

        $totalOrders = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();

        $incomeData = $this->getIncomeByPeriod($period);

        $topProducts = TransactionItem::select(
            'products.name',
            'products.id',
            DB::raw('SUM(transaction_items.quantity) as total_quantity'),
            DB::raw('SUM(transaction_items.subtotal) as total_sales')
        )
        ->join('products', 'transaction_items.product_id', '=', 'products.id')
        ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
        ->whereBetween('transactions.created_at', [$startDate, $endDate])
        ->groupBy('products.id', 'products.name')
        ->orderBy('total_quantity', 'desc')
        ->limit(10) 
        ->get();

        
        // ==========================================================
        // ===== PERUBAHAN YANG DIPERLUKAN ADA DI BARIS DI BAWAH INI =====
        // ==========================================================
        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10); // <-- Diubah dari limit(10)->get()

        return view('dashboard', [
            'totalIncome' => $totalIncome,
            'totalProductsSold' => $totalProductsSold, // <-- Variabel diganti
            'totalOrders' => $totalOrders,
            'incomeData' => $incomeData,
            'topProducts' => $topProducts,
            'recentTransactions' => $recentTransactions,
            'period' => $period,
            'subLabel' => $subLabel,
            'dateRangeString' => $dateRangeString
        ]);
    }

    private function getIncomeByPeriod($period)
    {
        // ... (Fungsi ini tidak berubah, biarkan apa adanya)
        switch ($period) {
            case '7days':
                $transactions = Transaction::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total')
                )
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('date')
                ->get()
                ->keyBy('date');

                $result = collect();
                $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dateStr = $date->format('Y-m-d');
                    $dayName = $days[$date->dayOfWeek];
                    
                    $result->push((object)[
                        'label' => $dayName . ', ' . $date->format('d'),
                        'date' => $dateStr,
                        'total' => $transactions->has($dateStr) ? $transactions[$dateStr]->total : 0
                    ]);
                }
                
                return $result;

            case '30days':
                $transactions = Transaction::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total')
                )
                ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                ->groupBy('date')
                ->get()
                ->keyBy('date');

                $result = collect();
                
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dateStr = $date->format('Y-m-d');
                    
                    $result->push((object)[
                        'label' => $date->format('d'),
                        'date' => $dateStr,
                        'total' => $transactions->has($dateStr) ? $transactions[$dateStr]->total : 0
                    ]);
                }
                
                return $result;

            case '12months':
                $transactions = Transaction::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(total) as total')
                )
                ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('month')
                ->get()
                ->keyBy('month');

                $result = collect();
                Carbon::setLocale('id');
                
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $monthStr = $date->format('Y-m');
                    $monthName = $date->translatedFormat('M');
                    
                    $result->push((object)[
                        'label' => $monthName,
                        'month' => $monthStr,
                        'total' => $transactions->has($monthStr) ? $transactions[$monthStr]->total : 0
                    ]);
                }
                
                return $result;

            default:
                return collect();
        }
    }

    // --- FUNGSI BARU UNTUK MODAL DETAIL ---
    public function getTransactionDetails(Transaction $transaction)
    {
        // Ambil data transaksi beserta relasi 'user' (kasir) 
        // dan 'items' (barang) beserta relasi 'product' (nama produk)
        $transaction->load('user', 'items.product');

        // Kembalikan sebagai JSON
        return response()->json($transaction);
    }
}