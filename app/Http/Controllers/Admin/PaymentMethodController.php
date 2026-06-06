<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::latest()->get();
        return view('admin.payment_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        PaymentMethod::create($request->all());
        return redirect()->route('admin.payment_methods.index')->with('success', 'Created');
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.payment_methods.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update($request->all());

        return back()->with('success', 'Updated');
    }

    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        return back()->with('success', 'Deleted');
    }
}