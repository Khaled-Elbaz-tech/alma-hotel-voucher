<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $vouchers = Voucher::where('user_id', auth()->id())
            ->when($request->filled('client_type'), fn ($q) => $q->where('client_type', $request->client_type))
            ->when($request->filled('company'), fn ($q) => $q->where('client_company', 'like', '%'.$request->company.'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('voucher_type', $request->type))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $vouchers->getCollection()->each(fn (Voucher $v) => $v->ensureShareToken());

        return view('profile.index', compact('vouchers'));
    }
}
