<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LookupCategory;
use App\Models\LookupValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LookupController extends Controller
{
    public function index(): View
    {
        $categories = LookupCategory::with('values')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.lookups.index', compact('categories'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);
        $validated['slug'] = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $validated['name']));

        LookupCategory::create($validated);

        return redirect()->route('admin.lookups.index')->with('success', 'Lookup category created.');
    }

    public function updateCategory(Request $request, LookupCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);
        $validated['slug'] = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $validated['name']));

        $category->update($validated);

        return redirect()->route('admin.lookups.index')->with('success', 'Lookup category updated.');
    }

    public function destroyCategory(LookupCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.lookups.index')->with('success', 'Lookup category deleted.');
    }

    public function storeValue(Request $request, LookupCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'value' => ['required', 'string', 'max:120'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $category->values()->create($validated);

        return redirect()->route('admin.lookups.index')->with('success', 'Lookup value created.');
    }

    public function updateValue(Request $request, LookupValue $value): RedirectResponse
    {
        $validated = $request->validate([
            'value' => ['required', 'string', 'max:120'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $value->update($validated);

        return redirect()->route('admin.lookups.index')->with('success', 'Lookup value updated.');
    }

    public function destroyValue(LookupValue $value): RedirectResponse
    {
        $value->delete();

        return redirect()->route('admin.lookups.index')->with('success', 'Lookup value deleted.');
    }
}
