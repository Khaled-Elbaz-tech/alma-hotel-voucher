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
        $byType = Voucher::selectRaw('voucher_type, COUNT(*) as c')
            ->groupBy('voucher_type')->pluck('c', 'voucher_type');

        $monthly = collect();
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $monthly->push([
                'label' => $m->format('M Y'),
                'count' => Voucher::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count(),
            ]);
        }

        return view('admin.dashboard', [
            'employeeCount' => Employee::count(),
            'activeEmployeeCount' => Employee::where('is_active', true)->count(),
            'voucherCount' => Voucher::count(),
            'recentVouchers' => Voucher::with('employee')->latest()->take(8)->get(),
            'vouchersThisMonth' => Voucher::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'creditCount' => Voucher::where('client_type', 'credit')->count(),
            'cashCount' => Voucher::where('client_type', 'cash')->count(),
            'byType' => $byType,
            'monthly' => $monthly,
            'topEmployees' => Voucher::selectRaw('employee_id, COUNT(*) as c')->with('employee')
                ->groupBy('employee_id')->orderByDesc('c')->take(5)->get(),
            'topCompanies' => Voucher::where('client_type', 'credit')->whereNotNull('client_company')->where('client_company', '!=', '')
                ->selectRaw('client_company, COUNT(*) as c')->groupBy('client_company')->orderByDesc('c')->take(5)->get(),
        ]);
    }
}
