@extends('layouts.admin')
@section('page-title', 'Add user')
@section('content')
<div class="admin-card narrow-card">
    <div class="admin-card-heading"><p class="eyebrow mb-1">System access</p><h2>Add user</h2></div>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Name</label><input class="form-control" id="name" name="name" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label" for="email">Email</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required></div>
            <div class="col-md-6"><label class="form-label" for="password">Password</label><input class="form-control" id="password" name="password" type="password" required><small class="text-muted">Min 8 characters</small></div>
            <div class="col-md-6"><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required></div>
            <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}><label class="form-check-label" for="is_admin">Administrator</label></div></div>
            <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active user</label></div></div>
        </div>
        @if($errors->any())<div class="alert alert-danger mt-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div class="form-actions admin-form-actions"><a class="btn btn-outline-dark" href="{{ route('admin.users.index') }}">Cancel</a><button class="btn btn-alma" type="submit">Save user</button></div>
    </form>
</div>
@endsection
