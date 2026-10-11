@extends('layouts.admin')
@section('page-title', 'Lookup values')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading"><p class="eyebrow mb-1">Voucher configuration</p><h2>Dynamic lookup values</h2><p class="text-muted mb-0">These categories power the dropdowns in the consultant workspace.</p></div>
    <div class="lookup-intro"><div><strong>Brand-controlled options</strong><span>Edit values here to keep every voucher consistent.</span></div><button class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#newCategoryForm">Add category</button></div>
    <div class="collapse mb-4" id="newCategoryForm">
        <form class="inline-form" method="POST" action="{{ route('admin.lookups.categories.store') }}">
            @csrf
            <input class="form-control" name="name" placeholder="Category name" required>
            <input class="form-control" name="sort_order" type="number" value="0" required>
            <button class="btn btn-alma" type="submit">Add category</button>
        </form>
    </div>
    <div class="lookup-grid">@forelse ($categories as $category)
        <div class="lookup-card">
            <div class="lookup-card-heading"><div><span class="lookup-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><h3>{{ $category->name }}</h3><small>{{ $category->slug }}</small></div></div><div class="table-actions"><button class="link-button" type="button" data-bs-toggle="collapse" data-bs-target="#category-{{ $category->id }}">Edit</button><form method="POST" action="{{ route('admin.lookups.categories.destroy', $category) }}">@csrf @method('DELETE')<button class="link-button danger" type="submit">Delete</button></form></div></div>
            <div class="collapse" id="category-{{ $category->id }}"><form class="inline-form mt-3" method="POST" action="{{ route('admin.lookups.categories.update', $category) }}">@csrf @method('PUT')<input class="form-control" name="name" value="{{ $category->name }}" required><input class="form-control" name="sort_order" type="number" value="{{ $category->sort_order }}" required><button class="btn btn-alma btn-sm" type="submit">Save category</button></form></div>
            <div class="lookup-values">@forelse ($category->values as $value)<div class="lookup-value"><form class="inline-form value-edit-form" method="POST" action="{{ route('admin.lookups.values.update', $value) }}">@csrf @method('PUT')<input class="form-control" name="value" value="{{ $value->value }}" required><input class="form-control value-order" name="sort_order" type="number" value="{{ $value->sort_order }}" required><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="active-value-{{ $value->id }}" value="1" {{ $value->is_active ? 'checked' : '' }}><label class="form-check-label" for="active-value-{{ $value->id }}">Active</label></div><button class="btn btn-alma btn-sm" type="submit">Save</button></form><form method="POST" action="{{ route('admin.lookups.values.destroy', $value) }}">@csrf @method('DELETE')<button class="link-button danger" type="submit">Delete</button></form></div>@empty<tr><td colspan="3" class="empty-row">No values in this category.</td></tr>@endforelse</div>
            <form class="inline-form value-form" method="POST" action="{{ route('admin.lookups.values.store', $category) }}">
                @csrf
                <input class="form-control" name="value" placeholder="New value" required>
                <input class="form-control" name="sort_order" type="number" value="{{ $category->values->count() + 1 }}" required>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="active-{{ $category->id }}" value="1" checked><label class="form-check-label" for="active-{{ $category->id }}">Active</label></div>
                <button class="btn btn-outline-dark btn-sm" type="submit">Add value</button>
            </form>
        </div>
    @empty<tr><td colspan="4" class="empty-row">No lookup categories found.</td></tr>@endforelse</div>
</div>
@endsection
