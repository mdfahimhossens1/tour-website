<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Default last 30 days
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : now()->subDays(29)->startOfDay();
        $to   = $request->filled('to')   ? Carbon::parse($request->to)->endOfDay()     : now()->endOfDay();

        $status         = $request->status;          // orders.status
        $payment_status = $request->payment_status;  // orders.payment_status
        $payment_method = $request->payment_method;  // orders.payment_method

        // ✅ Base Orders Query
        $ordersQ = DB::table('orders')
            ->whereBetween('created_at', [$from, $to]);

        // default: successful sales only
        if (!empty($status)) {
            $ordersQ->where('status', $status);
        } else {
            $ordersQ->whereIn('status', ['paid', 'completed']);
        }

        if (!empty($payment_status)) {
            $ordersQ->where('payment_status', $payment_status);
        }

        if (!empty($payment_method)) {
            $ordersQ->where('payment_method', $payment_method);
        }

        // ✅ KPIs
        $kpi = (clone $ordersQ)->selectRaw("
            COUNT(*) as total_orders,
            COALESCE(SUM(grand_total),0) as revenue,
            COALESCE(AVG(grand_total),0) as avg_order_value,
            COALESCE(SUM(subtotal),0) as subtotal_total,
            COALESCE(SUM(discount),0) as discount_total,
            COALESCE(SUM(shipping),0) as shipping_total,
            COALESCE(SUM(tax),0) as tax_total
        ")->first();

        // ✅ Daily Summary
        $daily = (clone $ordersQ)
            ->selectRaw("DATE(created_at) as day, COUNT(*) as orders, COALESCE(SUM(grand_total),0) as revenue")
            ->groupByRaw("DATE(created_at)")
            ->orderBy('day')
            ->get();

        // ✅ Total items sold (from order_items)
        $totalItemsSold = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->whereBetween('o.created_at', [$from, $to])
            ->when(!empty($status), fn($q) => $q->where('o.status', $status),
                fn($q) => $q->whereIn('o.status', ['paid','completed'])
            )
            ->when(!empty($payment_status), fn($q) => $q->where('o.payment_status', $payment_status))
            ->when(!empty($payment_method), fn($q) => $q->where('o.payment_method', $payment_method))
            ->sum('oi.qty');

        // ✅ Top products (using order_items snapshot columns)
        $topProducts = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->whereBetween('o.created_at', [$from, $to])
            ->when(!empty($status), fn($q) => $q->where('o.status', $status),
                fn($q) => $q->whereIn('o.status', ['paid','completed'])
            )
            ->when(!empty($payment_status), fn($q) => $q->where('o.payment_status', $payment_status))
            ->when(!empty($payment_method), fn($q) => $q->where('o.payment_method', $payment_method))
            ->selectRaw("
                oi.product_id,
                oi.product_name,
                oi.sku,
                SUM(oi.qty) as sold_qty,
                COALESCE(SUM(oi.line_total),0) as sales_total
            ")
            ->groupBy('oi.product_id', 'oi.product_name', 'oi.sku')
            ->orderByDesc('sold_qty')
            ->limit(10)
            ->get();

        // ✅ Recent orders list
        $recentOrders = (clone $ordersQ)
            ->select('id','order_number','customer_name','grand_total','status','payment_status','payment_method','created_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        // ✅ filter dropdown data
        $methods = DB::table('orders')->select('payment_method')->whereNotNull('payment_method')->distinct()->pluck('payment_method');

        return view('admin.reports.sales', compact(
            'from','to','status','payment_status','payment_method',
            'kpi','daily','topProducts','totalItemsSold','recentOrders','methods'
        ));
    }
}
