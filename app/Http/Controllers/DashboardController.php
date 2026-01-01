<?php

namespace App\Http\Controllers;

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
        
        // === INCOME DATA ===
        $incomeData = collect();
        $subLabel = '';
        $dateRangeString = '';
        
        if ($period === '7days') {
            // Data 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->startOfDay();
                $total = Transaction::whereDate('created_at', $date)->sum('total');
                
                $incomeData->push([
                    'label' => $date->format('d M'),
                    'date' => $date->format('Y-m-d'),
                    'total' => (float) $total
                ]);
            }
            
            $subLabel = '7 Hari Terakhir';
            $dateRangeString = Carbon::now()->subDays(6)->format('d M Y') . ' - ' . Carbon::now()->format('d M Y');
            
        } elseif ($period === '30days') {
            // Data 30 hari terakhir
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->startOfDay();
                $total = Transaction::whereDate('created_at', $date)->sum('total');
                
                $incomeData->push([
                    'label' => $date->format('d M'),
                    'date' => $date->format('Y-m-d'),
                    'total' => (float) $total
                ]);
            }
            
            $subLabel = '30 Hari Terakhir';
            $dateRangeString = Carbon::now()->subDays(29)->format('d M Y') . ' - ' . Carbon::now()->format('d M Y');
            
        } elseif ($period === '12months') {
            // Data 12 bulan terakhir
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i)->startOfMonth();
                $endDate = Carbon::now()->subMonths($i)->endOfMonth();
                
                $total = Transaction::whereBetween('created_at', [$date, $endDate])->sum('total');
                
                $incomeData->push([
                    'label' => $date->format('M Y'),
                    'month' => $date->format('Y-m'),
                    'total' => (float) $total
                ]);
            }
            
            $subLabel = '12 Bulan Terakhir';
            $dateRangeString = Carbon::now()->subMonths(11)->format('M Y') . ' - ' . Carbon::now()->format('M Y');
            
        } elseif ($period === 'custom') {
            // Custom filter berdasarkan tahun dan bulan
            $year = (int) $request->get('year', date('Y'));
            $month = (int) $request->get('month', date('n'));
            
            // Hitung jumlah hari dalam bulan
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            // Generate data per hari dalam bulan
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day)->startOfDay();
                $total = Transaction::whereDate('created_at', $date)->sum('total');
                
                $incomeData->push([
                    'label' => $day,
                    'date' => $date->format('Y-m-d'),
                    'total' => (float) $total
                ]);
            }
            
            // Format label dan range tanggal
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            $subLabel = 'Data Harian ' . $monthNames[$month] . ' ' . $year;
            $startDate = Carbon::create($year, $month, 1);
            $endDate = Carbon::create($year, $month, $daysInMonth);
            $dateRangeString = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }
        
        // === STATISTICS ===
        // Hitung statistik berdasarkan periode filter
        if ($period === 'custom') {
            $year = (int) $request->get('year', date('Y'));
            $month = (int) $request->get('month', date('n'));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, $daysInMonth)->endOfDay();
            
            $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
            $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('quantity');
            $totalOrders = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
            
        } elseif ($period === '7days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            
            $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
            $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('quantity');
            $totalOrders = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
            
        } elseif ($period === '30days') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            
            $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
            $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('quantity');
            $totalOrders = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
            
        } elseif ($period === '12months') {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            
            $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
            $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('quantity');
            $totalOrders = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
            
        } else {
            // Default: all time
            $totalIncome = Transaction::sum('total');
            $totalProductsSold = TransactionItem::sum('quantity');
            $totalOrders = Transaction::count();
        }
        
        // === TOP PRODUCTS ===
        // Top 10 produk terlaris berdasarkan periode
        if ($period === 'custom') {
            $year = (int) $request->get('year', date('Y'));
            $month = (int) $request->get('month', date('n'));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, $daysInMonth)->endOfDay();
            
            $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'total_quantity' => $item->total_quantity
                    ];
                });
                
        } elseif ($period === '7days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            
            $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'total_quantity' => $item->total_quantity
                    ];
                });
                
        } elseif ($period === '30days') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            
            $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'total_quantity' => $item->total_quantity
                    ];
                });
                
        } elseif ($period === '12months') {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            
            $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'total_quantity' => $item->total_quantity
                    ];
                });
                
        } else {
            // All time
            $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'total_quantity' => $item->total_quantity
                    ];
                });
        }
        
        // === RECENT TRANSACTIONS ===
        $recentTransactions = Transaction::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dashboard', compact(
            'incomeData',
            'totalIncome',
            'totalProductsSold',
            'totalOrders',
            'topProducts',
            'recentTransactions',
            'period',
            'subLabel',
            'dateRangeString'
        ));
    }

    /**
     * Dashboard khusus untuk Kasir
     * - Fixed 7 hari terakhir (no filter)
     * - Hanya menampilkan transaksi milik kasir sendiri
     */
    public function kasirDashboard()
    {
        $userId = auth()->id();
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // === INCOME DATA (7 hari terakhir) ===
        $incomeData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $total = Transaction::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->sum('total');
            
            $incomeData->push([
                'label' => $date->format('d M'),
                'date' => $date->format('Y-m-d'),
                'total' => (float) $total
            ]);
        }
        
        $subLabel = '7 Hari Terakhir';
        $dateRangeString = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        
        // === STATISTICS (hanya transaksi kasir sendiri) ===
        $totalIncome = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
        
        $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($userId, $startDate, $endDate) {
            $q->where('user_id', $userId)
              ->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');
        
        $totalOrders = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // === TOP PRODUCTS (dari transaksi kasir sendiri) ===
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('transaction', function($q) use ($userId, $startDate, $endDate) {
                $q->where('user_id', $userId)
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->with('product')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->product->name,
                    'total_quantity' => $item->total_quantity
                ];
            });
        
        // === RECENT TRANSACTIONS (hanya transaksi kasir sendiri) ===
        $recentTransactions = Transaction::with(['user', 'items.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('kasir.dashboard', compact(
            'incomeData',
            'totalIncome',
            'totalProductsSold',
            'totalOrders',
            'topProducts',
            'recentTransactions',
            'subLabel',
            'dateRangeString'
        ));
    }

    /**
     * Get transaction details untuk kasir
     * Memastikan kasir hanya bisa melihat transaksi milik sendiri
     */
    public function kasirTransactionDetails($id)
    {
        $transaction = Transaction::with(['user', 'items.product'])
            ->where('id', $id)
            ->where('user_id', auth()->id()) // Validasi kepemilikan
            ->firstOrFail();
        
        return response()->json($transaction);
    }

    /**
     * Cetak Laporan Kasir (7 hari terakhir, fixed period)
     * Hanya menampilkan transaksi milik kasir yang login
     */
    public function printKasirReport()
    {
        $userId = auth()->id();
        $kasirName = auth()->user()->name;
        
        // Fixed: 7 hari terakhir
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $subLabel = '7 Hari Terakhir';
        $dateRangeString = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        
        // Ambil Data Transaksi milik kasir sendiri
        $transactions = Transaction::with(['user', 'items.product'])
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Hitung Summary
        $totalIncome = $transactions->sum('total');
        $totalOrders = $transactions->count();
        
        // Hitung total produk terjual
        $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($userId, $startDate, $endDate) {
            $q->where('user_id', $userId)
              ->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');
        
        // Ambil top products kasir
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('transaction', function($q) use ($userId, $startDate, $endDate) {
                $q->where('user_id', $userId)
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->with('product')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->product->name,
                    'total_quantity' => $item->total_quantity
                ];
            });
        
        return view('print.kasir-report', compact(
            'transactions',
            'subLabel',
            'dateRangeString',
            'totalIncome',
            'totalOrders',
            'totalProductsSold',
            'topProducts',
            'kasirName'
        ));
    }

    /**
     * Cetak Laporan Berdasarkan Filter
     */
    public function printReport(Request $request)
    {
        $period = $request->get('period', '7days');
        
        // Setup variabel default
        $subLabel = '';
        $dateRangeString = '';
        $startDate = null;
        $endDate = null;

        // Tentukan Range Tanggal (Logika SAMA dengan index)
        if ($period === '7days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $subLabel = 'Laporan 7 Hari Terakhir';
            $dateRangeString = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
            
        } elseif ($period === '30days') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $subLabel = 'Laporan 30 Hari Terakhir';
            $dateRangeString = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
            
        } elseif ($period === '12months') {
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $subLabel = 'Laporan 12 Bulan Terakhir';
            $dateRangeString = $startDate->format('M Y') . ' - ' . $endDate->format('M Y');
            
        } elseif ($period === 'custom') {
            $year = (int) $request->get('year', date('Y'));
            $month = (int) $request->get('month', date('n'));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, $daysInMonth)->endOfDay();
            
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            $subLabel = 'Laporan Bulan ' . $monthNames[$month] . ' ' . $year;
            $dateRangeString = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }

        // Ambil Data Transaksi Sesuai Filter
        // Note: Kita ambil get() bukan paginate() agar tercetak semua
        $transactions = Transaction::with(['user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc') // Urutkan dari terlama ke terbaru untuk laporan
            ->get();

        // Hitung Summary
        $totalIncome = $transactions->sum('total');
        $totalOrders = $transactions->count();
        
        // Hitung total produk terjual (perlu query terpisah atau loop)
        $totalProductsSold = TransactionItem::whereHas('transaction', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');

        return view('print.report', compact(
            'transactions',
            'subLabel',
            'dateRangeString',
            'totalIncome',
            'totalOrders',
            'totalProductsSold'
        ));
    }

    // REMOVED: getTransactionDetails() - Now in POSController::show()
    // REMOVED: deleteTransaction() - Now in POSController::destroy()
}