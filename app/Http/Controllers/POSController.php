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
                'products.stock',
                'products.image',
                'products.created_at',
                'products.updated_at'
            )
            // Urutkan berdasarkan stock > 0 terlebih dahulu, kemudian total_ordered, lalu nama
            ->orderByRaw('CASE WHEN products.stock > 0 THEN 0 ELSE 1 END')
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
            
            // Check stock availability for all items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product->isInStock($item['quantity'])) {
                    throw new \Exception("Stock tidak cukup untuk produk: {$product->name}. Stock tersedia: {$product->stock}");
                }
            }
            
            // Generate invoice number
            $lastTransaction = Transaction::whereDate('created_at', today())->latest()->first();
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(($lastTransaction ? $lastTransaction->id + 1 : 1), 4, '0', STR_PAD_LEFT);
            
            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'total' => $request->total,
            ]);
            
            // Create transaction items and reduce stock
            foreach ($request->items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
                
                // Reduce stock
                $product = Product::find($item['product_id']);
                $product->reduceStock($item['quantity']);
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

    /**
     * Display transaction detail (show)
     * Route: GET /transactions/{id}
     */
    public function show($id)
    {
        try {
            $transaction = Transaction::with(['items.product', 'user'])
                ->findOrFail($id);

            // Authorization: Admin bisa lihat semua, Kasir hanya milik sendiri
            if (auth()->user()->role !== 'admin' && $transaction->user_id !== auth()->id()) {
                return response()->json([
                    'error' => 'Unauthorized',
                ], 403);
            }

            return response()->json($transaction);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Delete transaction and restore product stock (destroy)
     * Moved from DashboardController - Best Practice: All transaction CRUD in one controller
     * Route: DELETE /transactions/{id}
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Find transaction with items
            $transaction = Transaction::with('items.product')->find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            // Authorization: Only Admin can delete
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Hanya Admin yang dapat menghapus transaksi.'
                ], 403);
            }

            // Restore stock for each item
            foreach ($transaction->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Delete transaction items
            $transaction->items()->delete();

            // Delete transaction
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

    /**
     * Check product stock availability
     */
    public function checkStock(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json([
                'available' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }
        
        return response()->json([
            'available' => $product->isInStock($quantity),
            'stock' => $product->stock,
            'message' => $product->isInStock($quantity) 
                ? 'Stock tersedia' 
                : "Stock tidak cukup. Tersedia: {$product->stock}"
        ]);
    }
}
