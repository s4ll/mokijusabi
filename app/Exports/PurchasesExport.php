<?php
namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;

    public function __construct($filter = 'all')
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        // Mengambil data purchases beserta hubungan customer dan produk
        $query = Purchase::with(['customer', 'user', 'purchaseProducts.product']);

        // Terapkan filter berdasarkan dropdown
        switch ($this->filter) {
            case 'daily':
                $query->whereDate('created_at', today());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'all':
            default:
                // No filter
                break;
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Customer Name',
            'Phone Number',
            'Customer Points',
            'Products Purchased',
            'Total Price',
            'Total Payment',
            'Total Discount',
            'Change',
            'Purchase Date',
        ];
    }

    public function map($purchase): array
    {
        static $no = 0;
        $no++;

        // Menampilkan data yang ingin diekspor
        return [
            $no,
            // Customer Name (jika tidak ada customer, tampilkan 'non-member')
            $purchase->customer->name ?? 'non-member',

            // Phone Number (jika tidak ada, tampilkan '-')
            $purchase->customer->phone_number ?? '-',

            // Customer Points (jika tidak ada, tampilkan 0)
            $purchase->customer->points ?? 0,

            $purchase->purchaseProducts->map(function ($purchaseProduct) {
                return $purchaseProduct->product->name . ' (' . $purchaseProduct->qty . ')';
            })->implode(', '),

            // Total Price
            'Rp ' . number_format($purchase->total_price, 0, ',', '.'),

            // Total Payment
            'Rp ' . number_format($purchase->total_payment, 0, ',', '.'),

            // Total Discount / Used Points
            'Rp ' . number_format($purchase->total_discount ?? $purchase->used_points, 0, ',', '.'),

            // Change (Kembalian)
            'Rp ' . number_format($purchase->change, 0, ',', '.'),

            // Tanggal Pembelian
            $purchase->created_at->format('d-m-Y'),
        ];
    }
}

