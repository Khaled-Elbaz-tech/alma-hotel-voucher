<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$month, $company, $type] = $this->filters($request);

        $vouchers = $this->query($month, $company, $type)
            ->with('employee')
            ->orderByDesc('created_at')
            ->limit(500)
            ->get();

        return view('admin.reports.index', [
            'vouchers' => $vouchers,
            'month' => $month,
            'company' => $company,
            'type' => $type,
            'companies' => $this->companies(),
            'types' => Voucher::TYPES,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        [$month, $company, $type] = $this->filters($request);

        $rows = $this->query($month, $company, $type)
            ->with('employee')
            ->orderByDesc('created_at')
            ->limit(5000)
            ->get();

        $fileName = 'alma-company-report-'.$month.($company ? '-'.str_replace(' ', '-', $company) : '').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel renders Arabic correctly
            fputcsv($out, ['Voucher No', 'Type', 'Guest', 'Company', 'Supplier', 'Consultant', 'Issued', 'Check-in / Pickup', 'Check-out / Drop-off', 'Confirmation Ref']);
            foreach ($rows as $v) {
                fputcsv($out, [
                    $v->voucher_number,
                    $v->typeLabel(),
                    $v->guest_name,
                    $v->client_company ?: ($v->client_type === 'cash' ? '(Cash)' : ''),
                    $v->supplier,
                    $v->employee?->name,
                    $v->created_at?->format('Y-m-d'),
                    $v->check_in_date?->format('Y-m-d'),
                    $v->check_out_date?->format('Y-m-d'),
                    $v->confirmation_reference,
                ]);
            }
            fclose($out);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filters(Request $request): array
    {
        $month = $request->input('month', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', (string) $month)) {
            $month = now()->format('Y-m');
        }

        return [$month, $request->input('company'), $request->input('type')];
    }

    private function query(string $month, ?string $company, ?string $type)
    {
        [$year, $mon] = explode('-', $month);

        return Voucher::query()
            ->whereYear('created_at', (int) $year)
            ->whereMonth('created_at', (int) $mon)
            ->when($company, fn ($q) => $q->where('client_company', $company))
            ->when($type, fn ($q) => $q->where('voucher_type', $type));
    }

    private function companies()
    {
        return Voucher::where('client_type', 'credit')
            ->whereNotNull('client_company')
            ->where('client_company', '!=', '')
            ->distinct()
            ->orderBy('client_company')
            ->pluck('client_company');
    }
}
