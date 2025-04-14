<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Purchase::with(['customer', 'user']);

        // Filter role user
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereHas('user', function ($q3) use ($search) {
                    $q3->where('name', 'like', "%$search%");
                });
            });
        }

        // Filter berdasarkan waktu
        if ($request->filled('filter_by')) {
            $now = Carbon::now();

            switch ($request->filter_by) {
                case 'daily':
                    $query->whereDate('created_at', $now->toDateString());
                    break;

                case 'weekly':
                    $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
                    $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

                    $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                    break;

                case 'monthly':
                    $query->whereMonth('created_at', $now->month)
                        ->whereYear('created_at', $now->year);
                    break;
            }
        }

        // Sort logic
        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');

        if ($sortBy == 'sale_date') {
            $query->orderBy('created_at', $order);
        } elseif ($sortBy == 'total_price') {
            $query->orderBy('total_price', $order);
        } elseif ($sortBy == 'created_by') {
            $query->orderBy(User::select('name')
                ->whereColumn('users.id', 'purchases.user_id'), $order);
        } else {
            $query->latest();
        }

        $purchases = $query->paginate(10);

        return view('pages.purchase', compact('purchases'));
    }

    public function create()
    {
        $products = Product::where('stock', '>' , 0)->get();
        return view('components.purchase.create', compact('products'));
    }

    public function createStore(Request $request)
    {
        $request->validate([
            'qtys' => 'required|array',
            'qtys.*' => 'integer|min:0'
        ]);

        $qtys = $request->input('qtys', []);
        $selectedProducts = [];
        $total_price = 0;

        foreach ($qtys as $productId => $qty) {
            if ($qty > 0) {
                $product = Product::findOrFail($productId);
                if ($product->stock < $qty) {
                    return back()->with('error', "Stok produk {$product->name} tidak mencukupi");
                }

                $total = $product->price * $qty;
                $selectedProducts[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $qty,
                    'total' => $total // Pastikan kolom 'total' digunakan
                ];
                $total_price += $total;
            }
        }

        if (empty($selectedProducts)) {
            return back()->with('error', 'Pilih setidaknya satu produk');
        }

        session([
            'cart' => [
                'products' => $selectedProducts,
                'total_price' => $total_price,
                'is_member' => false,
                'phone' => null
            ]
        ]);

        return redirect()->route('purchase.cart');
    }

    public function cart(Purchase $purchase)
    {
        $cart = session('cart', []);
        if (empty($cart['products'])) {
            return redirect()->route('purchase.create')->with('error', 'Keranjang kosong');
        }
        return view('components.purchase.cart', compact('cart'));
    }

    public function cartStore(Request $request)
    {
        $request->validate([
            'is_member' => 'required|boolean',
            'phone' => 'required_if:is_member,1|nullable|string',
            'payment' => 'required|string'
        ]);

        $cart = session('cart', []);
        $cart['is_member'] = $request->is_member;
        $cart['phone'] = $request->is_member ? $request->phone : null;

        // Convert Rp. 10.000 to 10000
        $paymentRaw = preg_replace('/\D/', '', $request->payment);
        $paymentAmount = intval($paymentRaw);

        // Simpan sementara
        $cart['payment'] = $paymentAmount;
        session(['cart' => $cart]);

        if ($paymentAmount < $cart['total_price']) {
            return back()->withErrors(['payment' => 'Jumlah pembayaran kurang dari total belanja.'])->withInput();
        }

        return redirect()->route('purchase.order');
    }

    public function order(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart['products'])) {
            return redirect()->route('purchase.create')->with('error', 'Keranjang kosong');
        }

        $customer = null;
        $earnedPoints = 0;
        $availablePoints = 0;
        $canUsePoints = false;
        $totalAvailablePoints = 0;

        if ($cart['is_member']) {
            // Ambil customer dari DB (jika sudah ada)
            $customer = Customer::where('phone', $cart['phone'])->first();

            // Hitung point yang akan didapat dari pembelian
            $earnedPoints = floor($cart['total_price'] * 0.01);

            if ($customer) {
                $availablePoints = $customer->points;
                $hasPreviousPurchase = Purchase::where('customer_id', $customer->id)->exists();

                $canUsePoints = $availablePoints > 0 && $hasPreviousPurchase;
            } else {
                // Buat customer baru (belum masuk DB sampai order disimpan)
                $customer = new Customer([
                    'name' => '',
                    'phone' => $cart['phone'],
                    'is_member' => true,
                    'points' => 0
                ]);
            }

            $totalAvailablePoints = $availablePoints + $earnedPoints;
        }

        return view('components.purchase.order', compact(
            'cart',
            'customer',
            'earnedPoints',
            'availablePoints',
            'canUsePoints',
            'totalAvailablePoints'
        ));
    }


    public function orderStore(Request $request)
    {
        $request->validate([
            'member_name' => 'required_if:is_member,true',
            'used_points' => 'nullable|boolean' // dari checkbox
        ]);

        $cart = session('cart', []);
        if (empty($cart['products'])) {
            return redirect()->route('purchase.create')->with('error', 'Keranjang kosong');
        }

        $customer = null;
        $usedPoints = 0;
        $earnedPoints = floor($cart['total_price'] * 0.01);

        if ($cart['is_member']) {
            $customer = Customer::updateOrCreate(
                ['phone' => $cart['phone']],
                [
                    'name' => $request->member_name,
                    'is_member' => true
                ]
            );

            // Total poin (lama + yang baru didapat)
            $totalAvailablePoints = $customer->points + $earnedPoints;

            // Hanya gunakan poin jika checkbox dicentang
            $usedPoints = $request->used_points ? $totalAvailablePoints : 0;
        }

        // Hitung grand total
        $grandTotal = $cart['total_price'] - $usedPoints;
        $paymentAmount = $cart['payment'];
        $change = $paymentAmount - $grandTotal;

        $purchase = Purchase::create([
            'receipt_code' => 'INV-' . strtoupper(Str::random(5)),
            'user_id' => auth()->id(),
            'customer_id' => $customer?->id,
            'used_points' => $usedPoints,
            'total_price' => $cart['total_price'],
            'total_payment' => $paymentAmount,
            'change' => $change,
        ]);

        // Simpan produk
        foreach ($cart['products'] as $item) {
            PurchaseProduct::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total' => $item['total']
            ]);

            $product = Product::find($item['product_id']);
            $product->stock -= $item['qty'];
            $product->save();
        }

        // Update poin member
        if ($customer) {
            // Simpan total points terbaru (setelah penggunaan dan tambahan)
            $customer->points = ($customer->points + $earnedPoints) - $usedPoints;
            $customer->save();
        }

        session(['last_purchase_id' => $purchase->id]);
        session()->forget('cart');

        return redirect()->route('purchase.detail');
    }


    public function detail()
    {
        $purchaseId = session('last_purchase_id');
        if (!$purchaseId) {
            return redirect()->route('purchase.index')->with('error', 'Tidak ada transaksi terakhir');
        }

        $purchase = Purchase::with(['user', 'customer', 'purchaseProducts.product'])
            ->findOrFail($purchaseId);

        return view('components.purchase.detail', compact('purchase'));
    }

    public function export(Request $request)
    {
        $filter = $request->query('filter_by', 'all');
        return Excel::download(new PurchasesExport($filter), 'purchases.xlsx');
    }

    public function downloadReceipt(Purchase $purchase)
    {
        $purchase->load(['customer', 'user', 'purchaseProducts.product']);

        $pdf = Pdf::loadView('pdf.receipt', compact('purchase'));
        return $pdf->download('receipt_' . $purchase->id . '.pdf');
    }
}
