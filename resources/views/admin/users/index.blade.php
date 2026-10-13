@extends('layouts.admin')
@section('page-title', 'Users')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading d-flex align-items-center justify-content-between">
        <div><p class="eyebrow mb-1">System access</p><h2>Users</h2></div>
        <a class="btn btn-alma btn-sm" href="{{ route('admin.users.create') }}">Add user</a>
    </div>
    <form class="admin-filter mb-3" method="GET" action="{{ route('admin.users.index') }}">
        <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search users…">
        <button class="btn btn-outline-dark btn-sm" type="submit">Search</button>
    </form>
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
            <tbody>@forelse ($users as $user)<tr><td><strong>{{ $user->name }}</strong></td><td>{{ $user->email }}</td><td>{{ $user->is_admin ? 'Admin' : 'Employee' }}</td><td><span class="status-badge {{ $user->is_active ? 'active' : '' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td><td><div class="table-actions"><a class="text-link" href="{{ route('admin.users.edit', $user) }}">Edit</a><form method="POST" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('DELETE')<button class="link-button danger" type="submit" onclick="return confirm('Delete this user?')">Delete</button></form></div></td></tr>@empty<tr><td colspan="5" class="empty-row">No users found.</td></tr>@endforelse</tbody>
        </table>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
