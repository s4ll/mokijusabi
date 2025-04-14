<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $user = Auth::user();
    
        $data = [
            'userRole' => $user->role,
            'totalPurchases' => null,
            'lastUpdate' => null,
            'pieChartData' => [],
            'columnChartData' => [],
        ];
    
        if ($user->role === 'admin') {
            // Pie Chart (masih pakai purchase_products karena pie-nya soal jumlah qty produk)
            $totalQty = PurchaseProduct::sum('qty');
            $data['pieChartData'] = Product::withSum('purchaseProducts', 'qty')
                ->get()
                ->map(function ($product) use ($totalQty) {
                    $percentage = $totalQty > 0 ? ($product->purchase_products_sum_qty / $totalQty) * 100 : 0;
                    return [
                        'label' => $product->name,
                        'value' => round($percentage, 2),
                    ];
                })
                ->filter(fn($item) => $item['value'] > 0)
                ->values();
    
            // Column Chart (jumlah transaksi per hari) -> PAKAI purchases
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
    
            // Ambil jumlah transaksi per hari
            $rawSales = Purchase::selectRaw('DATE(created_at) as date, COUNT(*) as total_sales')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date'); // Map by tanggal
    
            // Inisialisasi data 7 hari (isi 0 kalau tidak ada)
            $chartData = collect();
            for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                $formatted = $date->format('Y-m-d');
                $chartData->push([
                    'x' => $date->format('D'), // Senin -> Mon
                    'y' => isset($rawSales[$formatted]) ? $rawSales[$formatted]->total_sales : 0,
                ]);
            }
    
            $data['columnChartData'] = $chartData;
        }
    
        if ($user->role === 'employee') {
            $data['totalPurchases'] = Purchase::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count();
    
            $data['lastUpdate'] = Purchase::where('user_id', $user->id)
                ->latest()
                ->first();
        }
    
        return view('pages.dashboard', $data);
    }
}
