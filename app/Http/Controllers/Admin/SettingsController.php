<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show the admin settings page.
     */
    public function index()
    {
        $user = auth()->user();
        $settings = Setting::first();

        return view('admin.settings', compact('user', 'settings'));
    }

    /**
     * Save settings from either modal.
     */
    public function update(Request $request)
    {
        $settings = Setting::first() ?? new Setting();

        $validated = $request->validate([
            // Library info (all optional)
            'library_name'       => ['nullable', 'string', 'max:255'],
            'operation_hours'    => ['nullable', 'string', 'max:255'],
            'library_contact'    => ['nullable', 'string', 'max:255'],
            'library_email'      => ['nullable', 'email', 'max:255'],
            'library_address'    => ['nullable', 'string'],

            // Request settings (optional here, because library form won't send them)
            'max_chapters_per_request' => ['nullable', 'integer', 'min:1', 'max:50'],
            'request_expiry_days'      => ['nullable', 'integer', 'min:1', 'max:30'],

            // Footer (optional if you add later)
            'footer_text'        => ['nullable', 'string', 'max:255'],

            // Booleans can be nullable here; we handle logic below
            'notify_on_request'        => ['nullable', 'boolean'],
            'notify_on_complete'       => ['nullable', 'boolean'],
            'notify_on_expiry'         => ['nullable', 'boolean'],
        ]);

        // If a field is missing in this request, keep the old value:

        // Numeric fields
        $validated['max_chapters_per_request'] =
            $validated['max_chapters_per_request']
            ?? $settings->max_chapters_per_request
            ?? 1;

        $validated['request_expiry_days'] =
            $validated['request_expiry_days']
            ?? $settings->request_expiry_days
            ?? 7;

        // Checkbox fields (if not in request, keep previous)
        $validated['notify_on_request'] = $request->has('notify_on_request')
            ? $request->boolean('notify_on_request')
            : ($settings->notify_on_request ?? false);

        $validated['notify_on_complete'] = $request->has('notify_on_complete')
            ? $request->boolean('notify_on_complete')
            : ($settings->notify_on_complete ?? false);

        $validated['notify_on_expiry'] = $request->has('notify_on_expiry')
            ? $request->boolean('notify_on_expiry')
            : ($settings->notify_on_expiry ?? false);

        // Fill and save
        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
