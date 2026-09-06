<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'site_name'],
            ['value' => $validated['site_name']]
        );

        Setting::updateOrCreate(
            ['key' => 'site_description'],
            ['value' => $validated['site_description'] ?? null]
        );

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}