<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bkash,nagad,bank,manual',
            'number' => 'nullable|string|max:30',
            'status' => 'required|boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()
            ->route('admin.payment_methods.index')
            ->with('success', 'Created');
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bkash,nagad,bank,manual',
            'number' => 'nullable|string|max:30',
            'status' => 'required|boolean',
        ]);

        $method->update($validated);

        return back()->with('success', 'Updated');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        // safer than delete in payment systems
        $method->update(['status' => 0]);

        return back()->with('success', 'Deactivated');
    }
}