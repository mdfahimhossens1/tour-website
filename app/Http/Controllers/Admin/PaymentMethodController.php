<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::latest()->get();

        return view('admin.payment_methods.index', compact('methods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bkash,nagad,bank,manual',
            'account_number' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()
            ->route('admin.payment_methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bkash,nagad,bank,manual',
            'account_number' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // API/Secret field blank থাকলে আগের value রেখে দেবে
        if (empty($validated['api_key'])) {
            unset($validated['api_key']);
        }

        if (empty($validated['secret_key'])) {
            unset($validated['secret_key']);
        }

        $method->update($validated);

        return redirect()
            ->route('admin.payment_methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        // Payment history-এর জন্য database থেকে delete না করে inactive করছি
        $method->update(['status' => false]);

        return redirect()
            ->route('admin.payment_methods.index')
            ->with('success', 'Payment method deactivated successfully.');
    }
}