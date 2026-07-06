<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where('payment_status', 'paid');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        return view('admin.reports.revenue', compact('orders', 'totalRevenue', 'totalOrders', 'request'));
    }

    public function export(Request $request, string $type)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where('payment_status', 'paid');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        $filename = 'laporan-pendapatan-' . now()->format('Ymd-His');

        if ($type === 'csv') {
            $path = $this->exportCsv($orders, $filename);
            return response()->download($path)->deleteFileAfterSend(true);
        }

        if ($type === 'excel') {
            $path = $this->exportExcel($orders, $filename);
            return response()->download($path)->deleteFileAfterSend(true);
        }

        $path = $this->exportPdf($orders, $filename);
        return response()->download($path)->deleteFileAfterSend(true);
    }

    protected function exportCsv($orders, $filename)
    {
        $tempPath = storage_path('app/public/exports/' . $filename . '.csv');
        $directory = dirname($tempPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($tempPath, 'w');
        fputcsv($handle, ['Order ID', 'Customer', 'Tanggal', 'Total', 'Status', 'Pembayaran']);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->user->name,
                $order->created_at->format('Y-m-d H:i'),
                $order->total_amount,
                $order->status,
                $order->payment_status,
            ]);
        }

        fclose($handle);

        return $tempPath;
    }

    protected function exportExcel($orders, $filename)
    {
        $tempPath = storage_path('app/public/exports/' . $filename . '.xls');
        $directory = dirname($tempPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $content = "Order ID\tCustomer\tTanggal\tTotal\tStatus\tPembayaran\n";
        foreach ($orders as $order) {
            $content .= implode("\t", [
                $order->order_number,
                str_replace(["\r", "\n", "\t"], ' ', $order->user->name),
                $order->created_at->format('Y-m-d H:i'),
                $order->total_amount,
                $order->status,
                $order->payment_status,
            ]) . "\n";
        }

        file_put_contents($tempPath, $content);

        return $tempPath;
    }

    protected function exportPdf($orders, $filename)
    {
        $html = view('admin.reports.revenue-pdf', compact('orders'))->render();
        $tempPath = storage_path('app/public/exports/' . $filename . '.pdf');
        $directory = dirname($tempPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($tempPath, $html);

        return $tempPath;
    }
}
