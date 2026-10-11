<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Employee::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->search.'%'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('admin.employees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'job_title' => ['required', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created.');
    }

    public function edit(Employee $employee): View
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'job_title' => ['required', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted.');
    }
}
