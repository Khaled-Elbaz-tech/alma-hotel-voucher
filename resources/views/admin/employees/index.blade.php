@extends('layouts.admin')
@section('page-title', 'Employees')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading d-flex align-items-center justify-content-between">
        <div><p class="eyebrow mb-1">People directory</p><h2>Employees</h2></div>
        <a class="btn btn-alma btn-sm" href="{{ route('admin.employees.create') }}">Add employee</a>
    </div>
    <form class="admin-filter mb-3" method="GET" action="{{ route('admin.employees.index') }}">
        <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search employees…">
        <button class="btn btn-outline-dark btn-sm" type="submit">Search</button>
    </form>
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Employee</th><th>Job title</th><th>Contact</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>@forelse ($employees as $employee)<tr><td><strong>{{ $employee->name }}</strong></td><td>{{ $employee->job_title }}</td><td>{{ $employee->email ?? '—' }}<br><span class="muted">{{ $employee->phone ?? '' }}</span></td><td><span class="status-badge {{ $employee->is_active ? 'active' : '' }}">{{ $employee->is_active ? 'Active' : 'Inactive' }}</span></td><td>{{ $employee->sort_order }}</td><td><div class="table-actions"><a class="text-link" href="{{ route('admin.employees.edit', $employee) }}">Edit</a><form method="POST" action="{{ route('admin.employees.destroy', $employee) }}">@csrf @method('DELETE')<button class="link-button danger" type="submit">Delete</button></form></div></td></tr>@empty<tr><td colspan="6" class="empty-row">No employees found.</td></tr>@endforelse</tbody>
        </table>
    </div>
    <div class="mt-3">{{ $employees->links() }}</div>
</div>
@endsection
