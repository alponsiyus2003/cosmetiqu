<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->user()->id;

        $query = OrderItem::with(['order.user', 'product'])
            ->where('seller_id', $sellerId)
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            });

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $items = $query->latest()->get();
        $totalRevenue = $items->sum('subtotal');
        $totalOrders = $items->count();

        return view('penjual.reports.revenue', compact('items', 'totalRevenue', 'totalOrders', 'request'));
    }

    public function export(Request $request, string $type)
    {
        $sellerId = auth()->user()->id;

        $query = OrderItem::with(['order.user', 'product'])
            ->where('seller_id', $sellerId)
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            });

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $items = $query->latest()->get();
        $filename = 'laporan-pendapatan-penjual-' . now()->format('Ymd-His');

        if ($type === 'csv') {
            $path = $this->exportCsv($items, $filename);
            return response()->download($path)->deleteFileAfterSend(true);
        }

        if ($type === 'excel') {
            $path = $this->exportExcel($items, $filename);
            return response()->download($path)->deleteFileAfterSend(true);
        }

        $path = $this->exportPdf($items, $filename);
        return response()->download($path)->deleteFileAfterSend(true);
    }

    protected function exportCsv($items, $filename)
    {
        $tempPath = storage_path('app/public/exports/' . $filename . '.csv');
        $directory = dirname($tempPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($tempPath, 'w');
        fputcsv($handle, ['Order ID', 'Produk', 'Customer', 'Qty', 'Subtotal', 'Tanggal']);

        foreach ($items as $item) {
            fputcsv($handle, [
                $item->order->order_number,
                $item->product->name,
                $item->order->user->name,
                $item->quantity,
                $item->subtotal,
                $item->created_at->format('Y-m-d H:i'),
            ]);
        }

        fclose($handle);

        return $tempPath;
    }

    protected function exportExcel($items, $filename)
    {
        $tempPath = storage_path('app/public/exports/' . $filename . '.xls');
        $directory = dirname($tempPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $content = "Order ID\tProduk\tCustomer\tQty\tSubtotal\tTanggal\n";
        foreach ($items as $item) {
            $content .= implode("\t", [
                $item->order->order_number,
                str_replace(["\r", "\n", "\t"], ' ', $item->product->name),
                str_replace(["\r", "\n", "\t"], ' ', $item->order->user->name),
                $item->quantity,
                $item->subtotal,
                $item->created_at->format('Y-m-d H:i'),
            ]) . "\n";
        }

        file_put_contents($tempPath, $content);

        return $tempPath;
    }

    protected function exportPdf($items, $filename)
    {
        $html = view('penjual.reports.revenue-pdf', compact('items'))->render();
        $tempPath = storage_path('app/public/exports/' . $filename . '.pdf');
        $directory = dirname($tempPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($tempPath, $html);

        return $tempPath;
    }
}
