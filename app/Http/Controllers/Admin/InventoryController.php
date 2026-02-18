<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\InventoryLog;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::orderByDesc('id')->get();
        return view('admin.inventory.index', compact('products'));
    }

    public function updateStock(Request $request, $id)
    {
        $validated = $request->validate([
            'type'     => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note'     => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {

                // ✅ Lock row to avoid race condition
                $product = Product::where('id', $id)->lockForUpdate()->firstOrFail();

                $qty  = (int) $validated['quantity'];
                $type = $validated['type'];

                // ✅ Prevent negative stock
                if ($type === 'out' && $product->stock < $qty) {
                    throw new \Exception('Not enough stock');
                }

                // ✅ Update stock
                $product->stock = $type === 'in'
                    ? $product->stock + $qty
                    : $product->stock - $qty;

                $product->save();

                // ✅ Save log
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type'       => $type,
                    'quantity'   => $qty,
                    'note'       => $validated['note'] ?? null,
                ]);
            });

            return back()->with('success', 'Stock updated successfully');

        } catch (\Exception $e) {
            // Friendly error
            $msg = $e->getMessage() === 'Not enough stock'
                ? 'Not enough stock'
                : 'Something went wrong. Please try again.';

            return back()->with('error', $msg);
        }
    }
    
    public function logs()
    {
        $logs = InventoryLog::with('product:id,name,sku')
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.inventory.logs', compact('logs'));
    }

}
