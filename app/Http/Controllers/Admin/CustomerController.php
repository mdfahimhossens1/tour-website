<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private function myRole(): string
    {
        return strtolower(optional(auth()->user()->role)->role_name ?? 'user');
    }

    private function abortIfNotStaff(): void
    {
        if (!in_array($this->myRole(), ['super admin','admin','manager'])) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->abortIfNotStaff();

        // Customers list from users table + order stats
        $customers = User::query()
            ->select('users.*')
            ->with('role')
            ->withCount('orders')
            ->withSum(['orders as orders_paid_sum' => function($q){
                $q->where('payment_status', 'paid');
            }], 'grand_total')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $this->abortIfNotStaff();

        $customer = User::with('role')->findOrFail($id);

        $orders = Order::where('user_id', $customer->id)
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.customers.show', compact('customer','orders'));
    }
}
