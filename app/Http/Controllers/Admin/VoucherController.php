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
            ->when($request->filled('type'), fn ($query) => $query->where('voucher_type', $request->type))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('voucher_number', 'like', '%'.$request->search.'%')
                    ->orWhere('guest_name', 'like', '%'.$request->search.'%')
                    ->orWhere('hotel_name', 'like', '%'.$request->search.'%')
                    ->orWhere('rental_company', 'like', '%'.$request->search.'%')
                    ->orWhere('company_name', 'like', '%'.$request->search.'%')
                    ->orWhere('confirmation_reference', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->to))
            ->when($request->filled('client_type'), fn ($query) => $query->where('client_type', $request->client_type))
            ->when($request->filled('company'), fn ($query) => $query->where('client_company', 'like', '%'.$request->company.'%'))
            ->when($request->filled('supplier'), fn ($query) => $query->where('supplier', 'like', '%'.$request->supplier.'%'))
            ->when($request->filled('employee'), fn ($query) => $query->where('user_id', $request->employee))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $employees = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('admin.vouchers.index', [
            'vouchers' => $vouchers,
            'types' => Voucher::TYPES,
            'employees' => $employees,
        ]);
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
