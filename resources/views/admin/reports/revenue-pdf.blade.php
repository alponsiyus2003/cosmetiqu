<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #6030C1; color: white; }
    </style>
</head>
<body>
    <h2>Laporan Pendapatan Penjualan</h2>
    <p>Tanggal: {{ now()->format('d M Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Pembeli</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->order_number }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $order->formatted_total }}</td>
                    <td>{{ $order->status_label }}</td>
                    <td>{{ $order->payment_status_label }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
