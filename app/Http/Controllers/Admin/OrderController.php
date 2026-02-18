<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
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

    private function list(string $type)
    {
        $this->abortIfNotStaff();

        $q = Order::query()->orderByDesc('id');

        if ($type === 'pending') {
            $q->where('status', 'pending');
        } elseif ($type === 'completed') {
            $q->where('status', 'completed');
        }

        $orders = $q->paginate(20);

        return view('admin.orders.index', compact('orders', 'type'));
    }

    public function index(){ 
        return $this->list('all'); 
    }
    public function pending(){ 
        return $this->list('pending'); 
    }
    public function completed(){ 
        return $this->list('completed'); 
    }

    public function show($order_number)
    {
        $this->abortIfNotStaff();

        $order = Order::with('items')
            ->where('order_number', $order_number)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

}
