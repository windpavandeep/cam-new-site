<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSettingController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $settings = ContactSetting::current();

        return view('dashboard.contact.settings', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'email' => ['nullable', 'string', 'max:255', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:2000'],
            'hours' => ['nullable', 'string', 'max:1000'],
        ]);

        $settings = ContactSetting::current();
        $settings->fill($validated);
        $settings->save();

        return redirect()->route('dashboard.contact-settings.edit')->with('success', 'Contact details saved.');
    }
}
