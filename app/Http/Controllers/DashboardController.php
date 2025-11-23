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
     * Get transaction details (untuk modal print)
     */
    public function getTransactionDetails($id)
    {
        $transaction = Transaction::with(['user', 'items.product'])->findOrFail($id);
        
        return response()->json($transaction);
    }
    
    /**
     * Hapus transaksi dan kembalikan stock produk
     */
    public function deleteTransaction($id)
    {
        try {
            DB::beginTransaction();
            
            // Cari transaksi dengan error handling
            $transaction = Transaction::with('items.product')->find($id);
            
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }
            
            // Kembalikan stock untuk setiap item
            foreach ($transaction->items as $item) {
                if ($item->product) {
                    // Kembalikan stock
                    $item->product->increment('stock', $item->quantity);
                }
            }
            
            // Hapus transaction items
            $transaction->items()->delete();
            
            // Hapus transaksi
            $transaction->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus dan stock dikembalikan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error deleting transaction: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}