<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }
        th {
            text-align: left;
        }
        td.right, th.right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
        }
        .totals p {
            margin: 3px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h2>Receipt</h2>

    <p><strong>Receipt ID:</strong> {{ $purchase->receipt_code }}</p>
    <p><strong>Customer:</strong> {{ $purchase->customer->name ?? 'Non Member' }}</p>
    <p><strong>Date:</strong> {{ $purchase->created_at ? $purchase->created_at->format('d-m-Y H:i') : '-' }}</p>
    <p><strong>Cashier:</strong> {{ $purchase->user?->name ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="right">Qty</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->purchaseProducts as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="right">{{ $item->qty }}</td>
                <td class="right">{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Total:</strong> Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</p>
        <p><strong>Payment:</strong> Rp {{ number_format($purchase->total_payment, 0, ',', '.') }}</p>
        <p><strong>Discount / Points Used:</strong> Rp {{ number_format($purchase->used_points ?? 0, 0, ',', '.') }}</p>
        <p><strong>Change:</strong> Rp {{ number_format($purchase->change, 0, ',', '.') }}</p>
    </div>

    <p class="footer">--- Thank you ---</p>
</body>
</html>
