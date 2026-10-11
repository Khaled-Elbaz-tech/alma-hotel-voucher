@extends('layouts.admin')
@section('page-title', 'Edit employee')
@section('content')
<div class="admin-card narrow-card">
    <div class="admin-card-heading"><p class="eyebrow mb-1">People directory</p><h2>Edit employee</h2></div>
    <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Name</label><input class="form-control" id="name" name="name" value="{{ old('name', $employee->name) }}" required></div>
            <div class="col-md-6"><label class="form-label" for="job_title">Job title</label><input class="form-control" id="job_title" name="job_title" value="{{ old('job_title', $employee->job_title) }}" required></div>
            <div class="col-md-6"><label class="form-label" for="phone">Phone / mobile</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"></div>
            <div class="col-md-6"><label class="form-label" for="email">Email</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email', $employee->email) }}"></div>
            <div class="col-md-6"><label class="form-label" for="sort_order">Sort order</label><input class="form-control" id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $employee->sort_order) }}" required></div>
            <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active employee</label></div></div>
        </div>
        <div class="form-actions admin-form-actions"><a class="btn btn-outline-dark" href="{{ route('admin.employees.index') }}">Cancel</a><button class="btn btn-alma" type="submit">Save changes</button></div>
    </form>
</div>
@endsection
