<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::withCount('usages')->latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:vouchers,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')
                       ->with('success', 'Voucher berhasil ditambahkan!');
    }

    public function show(Voucher $voucher)
    {
        $voucher->load(['usages.user', 'usages.order']);
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')
                       ->with('success', 'Voucher berhasil diupdate!');
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->usages()->count() > 0) {
            return back()->with('error', 'Voucher tidak dapat dihapus karena sudah pernah digunakan!');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
                       ->with('success', 'Voucher berhasil dihapus!');
    }

    public function toggleStatus(Voucher $voucher)
    {
        $voucher->update([
            'is_active' => !$voucher->is_active
        ]);

        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Voucher berhasil {$status}!");
    }
}
