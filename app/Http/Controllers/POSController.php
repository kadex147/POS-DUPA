<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function index(Request $request)
    {
        // Deteksi mobile device
        $isMobile = $this->isMobileDevice($request);
        
        // Set limit berdasarkan device
        $perPage = $isMobile ? 10 : 15;
        
        $query = Product::query();
        
        // Filter berdasarkan kategori jika ada
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Filter berdasarkan pencarian jika ada
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Join dengan transaction_items untuk menghitung total pesanan
        // Urutkan berdasarkan total quantity yang pernah dipesan (DESC)
        $query->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->select(
                'products.*',
                DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_ordered')
            )
            ->groupBy(
                'products.id',
                'products.category_id',
                'products.name',
                'products.price',
                'products.image',
                'products.created_at',
                'products.updated_at'
            )
            ->orderBy('total_ordered', 'DESC')
            ->orderBy('products.name', 'ASC');
        
        $products = $query->paginate($perPage);
        $categories = Category::orderBy('name')->get();
        
        return view('pos.index', compact('products', 'categories'));
    }
    
    /**
     * Deteksi apakah request dari mobile device
     */
    private function isMobileDevice(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        // Pattern untuk deteksi mobile
        $mobilePatterns = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 
            'BlackBerry', 'Windows Phone', 'webOS'
        ];
        
        foreach ($mobilePatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generate invoice number
            $lastTransaction = Transaction::whereDate('created_at', today())->latest()->first();
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(($lastTransaction ? $lastTransaction->id + 1 : 1), 4, '0', STR_PAD_LEFT);
            
            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'total' => $request->total,
            ]);
            
            // Create transaction items
            foreach ($request->items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
            
            DB::commit();
            
            // Load relationships untuk response
            $transaction->load(['items.product', 'user']);
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'invoice_number' => $invoiceNumber,
                'transaction' => $transaction
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}