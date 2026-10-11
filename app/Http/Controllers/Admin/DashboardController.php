<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Voucher;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'employeeCount' => Employee::count(),
            'activeEmployeeCount' => Employee::where('is_active', true)->count(),
            'voucherCount' => Voucher::count(),
            'recentVouchers' => Voucher::with('employee')->latest()->take(8)->get(),
        ]);
    }
}
