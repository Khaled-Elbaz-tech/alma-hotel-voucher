<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(Request $request): View
    {
        $vouchers = Voucher::query()
            ->with('employee')
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('voucher_number', 'like', '%'.$request->search.'%')
                    ->orWhere('guest_name', 'like', '%'.$request->search.'%')
                    ->orWhere('hotel_name', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function show(Voucher $voucher): View
    {
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function destroy(Voucher $voucher): RedirectResponse
    {
        foreach (['hotel_photo_path', 'pdf_path', 'png_path'] as $pathField) {
            if ($voucher->{$pathField}) {
                Storage::disk('public')->delete($voucher->{$pathField});
            }
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher log deleted.');
    }
}
