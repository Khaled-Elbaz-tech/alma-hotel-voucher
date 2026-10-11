<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => CompanySetting::firstOrFail(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = CompanySetting::firstOrFail();
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:500'],
            'brand_primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_dark_red' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_gold' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_surface_light' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_surface_dark' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'voucher_theme' => ['required', 'in:classic,modern'],
            'tagline' => ['required', 'string', 'max:120'],
            'logo' => ['nullable', 'mimes:svg,png,jpg,jpeg,webp', 'max:5120'],
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('company', 'public');
        }

        $setting->update(array_diff_key($validated, ['logo' => []]));

        return redirect()->route('admin.settings.edit')->with('success', 'Company settings updated.');
    }
}
