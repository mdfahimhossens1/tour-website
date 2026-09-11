<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrialSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrialSettingController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display Trial Settings
     * ----------------------------------------------------------
     */
    public function index(): View
    {
        $trialSetting = TrialSetting::current();

        return view(
            'admin.trial-settings.index',
            compact('trialSetting')
        );
    }

    /**
     * ----------------------------------------------------------
     * Update Trial Settings
     * ----------------------------------------------------------
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trial_days' => [
                'required',
                'integer',
                'min:1',
                'max:3650',
            ],

            'expiration_action' => [
                'required',
                'in:cancel,suspend,downgrade',
            ],

            'grace_period_days' => [
                'required',
                'integer',
                'min:0',
                'max:365',
            ],

            'reminder_days_before' => [
                'required',
                'integer',
                'min:0',
                'max:365',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $trialSetting = TrialSetting::current();

        $trialSetting->update([
            'is_enabled' => $request->boolean('is_enabled'),

            'trial_days' => $validated['trial_days'],

            'requires_payment_method' =>
                $request->boolean('requires_payment_method'),

            'auto_start' =>
                $request->boolean('auto_start'),

            'expiration_action' =>
                $validated['expiration_action'],

            'grace_period_days' =>
                $validated['grace_period_days'],

            'send_expiry_reminder' =>
                $request->boolean('send_expiry_reminder'),

            'reminder_days_before' =>
                $validated['reminder_days_before'],

            'send_trial_started_notification' =>
                $request->boolean('send_trial_started_notification'),

            'send_trial_expiring_notification' =>
                $request->boolean('send_trial_expiring_notification'),

            'send_trial_expired_notification' =>
                $request->boolean('send_trial_expired_notification'),

            'description' =>
                $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.trial-settings.index')
            ->with(
                'success',
                'Trial settings updated successfully.'
            );
    }
}