<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Penjual</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #6030C1; color: white; }
    </style>
</head>
<body>
    <h2>Laporan Pendapatan Penjual</h2>
    <p>Tanggal: {{ now()->format('d M Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Produk</th>
                <th>Pembeli</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>#{{ $item->order->order_number }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->order->user->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->formatted_subtotal }}</td>
                    <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
