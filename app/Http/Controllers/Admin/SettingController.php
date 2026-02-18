<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index(){
        return view('admin.settings.index');
    }

    // -------- General --------
    public function general()
    {
        $data = [
            'site_name'       => Setting::get('site_name', 'ShopOps'),
            'currency'        => Setting::get('currency', 'BDT'),
            'currency_symbol' => Setting::get('currency_symbol', '৳'),
            'support_email'   => Setting::get('support_email'),
            'support_phone'   => Setting::get('support_phone'),
            'shop_address'    => Setting::get('shop_address'),
        ];

        return view('admin.settings.general', compact('data'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name'       => 'required|string|max:120',
            'currency'        => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'support_email'   => 'nullable|email|max:120',
            'support_phone'   => 'nullable|string|max:30',
            'shop_address'    => 'nullable|string|max:255',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('currency', $request->currency);
        Setting::set('currency_symbol', $request->currency_symbol);
        Setting::set('support_email', $request->support_email);
        Setting::set('support_phone', $request->support_phone);
        Setting::set('shop_address', $request->shop_address);

        return back()->with('success', 'General settings updated');
    }

    // -------- Payment --------
    public function payment()
    {
        // fixed list (match your orders.payment_method values)
        $methods = ['Cash', 'bKash', 'Nagad', 'Rocket', 'Card'];

        $enabled = json_decode(Setting::get('payment_methods_enabled', '[]'), true) ?: [];
        $instructions = [];
        foreach ($methods as $m) {
            $key = 'payment_instruction_' . strtolower($m);
            $instructions[$m] = Setting::get($key);
        }

        $data = [
            'methods'       => $methods,
            'enabled'       => $enabled,
            'instructions'  => $instructions,
            'manual_verify' => (int) Setting::get('payment_manual_verify', 0), // 1 হলে manual approval
        ];

        return view('admin.settings.payment', compact('data'));
    }

    public function updatePayment(Request $request)
    {
        $methods = ['Cash', 'bKash', 'Nagad', 'Rocket', 'Card'];

        $request->validate([
            'enabled' => 'nullable|array',
            'enabled.*' => 'in:' . implode(',', $methods),
            'manual_verify' => 'nullable|in:0,1',
            'instruction' => 'nullable|array',
        ]);

        $enabled = $request->enabled ?? [];

        Setting::set('payment_methods_enabled', $enabled);
        Setting::set('payment_manual_verify', $request->manual_verify ?? 0);

        // instructions save
        $inst = $request->instruction ?? [];
        foreach ($methods as $m) {
            $key = 'payment_instruction_' . strtolower($m);
            Setting::set($key, $inst[$m] ?? null);
        }

        return back()->with('success', 'Payment settings updated');
    }

    // -------- Inventory --------
    public function inventory()
    {
        $data = [
            'default_low_stock_threshold' => (int) Setting::get('default_low_stock_threshold', 5),
            'out_of_stock_action' => Setting::get('out_of_stock_action', 'disable'), // disable/hide
            'allow_backorder' => (int) Setting::get('allow_backorder', 0), // 0/1
            'stock_reduce_on' => Setting::get('stock_reduce_on', 'paid'), // placed/paid/completed
        ];

        return view('admin.settings.inventory', compact('data'));
    }

    public function updateInventory(Request $request)
    {
        $request->validate([
            'default_low_stock_threshold' => 'required|integer|min:0|max:999999',
            'out_of_stock_action' => 'required|in:disable,hide',
            'allow_backorder' => 'nullable|in:0,1',
            'stock_reduce_on' => 'required|in:placed,paid,completed',
        ]);

        Setting::set('default_low_stock_threshold', (int) $request->default_low_stock_threshold);
        Setting::set('out_of_stock_action', $request->out_of_stock_action);
        Setting::set('allow_backorder', $request->allow_backorder ?? 0);
        Setting::set('stock_reduce_on', $request->stock_reduce_on);

        return back()->with('success', 'Inventory settings updated');
    }
}
