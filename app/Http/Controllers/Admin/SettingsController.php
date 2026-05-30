<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    // =========================
    // MANAGE PAGE (MASTER)
    // =========================
    public function index()
    {
        return view('admin.settings.index');
    }

    // =========================
    // GENERAL SETTINGS
    // =========================
    public function general()
    {
        $settings = Setting::whereIn('key', [
            'site_name',
            'site_email',
            'phone',
            'address',
            'logo',
            'favicon'
        ])->pluck('value', 'key');

        return view('admin.settings.general', compact('settings'));
    }

    public function generalUpdate(Request $request)
    {
        $request->validate([
            'site_name' => 'required',
        ]);

        foreach ($request->except('_token') as $key => $value) {

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'General settings updated');
    }

    // =========================
    // PAYMENT SETTINGS
    // =========================
    public function payment()
    {
        $settings = Setting::whereIn('key', [
            'paypal_status',
            'stripe_status',
            'bkash_number',
            'nagad_number'
        ])->pluck('value', 'key');

        return view('admin.settings.payment', compact('settings'));
    }

    public function paymentUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Payment settings updated');
    }
}