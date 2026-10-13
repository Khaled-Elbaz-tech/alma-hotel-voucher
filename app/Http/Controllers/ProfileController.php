<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $vouchers = Voucher::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('profile.index', compact('vouchers'));
    }
}
