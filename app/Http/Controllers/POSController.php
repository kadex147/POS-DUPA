<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- DITAMBAHKAN

class POSController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryFilter = $request->get('category');

        $query = Product::with('category');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'items' => 'required|array|min:1', // Pastikan ada minimal 1 item
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique invoice number
            $today = Carbon::now()->format('Ymd'); // Menggunakan Carbon
            $latestTransaction = Transaction::whereDate('created_at', Carbon::today()) // Menggunakan Carbon
                                        ->latest('id') // Lebih efisien daripada orderBy desc + first
                                        ->first();
            
            $sequence = $latestTransaction ? (int)substr($latestTransaction->invoice_number, -4) + 1 : 1;
            
            // Loop untuk memastikan keunikan jika ada transaksi bersamaan (jarang terjadi tapi aman)
            $invoiceNumber = '';
            do {
                $invoiceNumber = 'INV' . $today . str_pad($sequence, 4, '0', STR_PAD_LEFT);
                $sequence++; // Siapkan nomor berikutnya jika yang ini sudah ada
            } while (Transaction::where('invoice_number', $invoiceNumber)->exists());

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'total' => $validated['total'],
            ]);

            // Create transaction items
            foreach ($validated['items'] as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
                 // Idealnya kurangi stok produk di sini juga
                 // Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // --- PERUBAHAN DI SINI ---
            // Load relasi yang diperlukan untuk modal detail
            $transaction->load('user', 'items.product'); 
            
            // Kirim response sukses beserta data transaksi lengkap
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil', // Opsional: Tambahkan pesan
                'invoice_number' => $invoiceNumber,
                'transaction' => $transaction // <-- DATA LENGKAP DIKIRIM
            ]);
            // --- BATAS PERUBAHAN ---

        } catch (\Exception $e) {
            DB::rollback();
            // Log error untuk debugging
            // \Log::error('Transaction failed: ' . $e->getMessage()); 
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: Terjadi kesalahan internal.' // Pesan lebih umum ke user
                // 'message' => 'Transaksi gagal: ' . $e->getMessage() // Jangan tampilkan detail error ke user
            ], 500);
        }
    }
}