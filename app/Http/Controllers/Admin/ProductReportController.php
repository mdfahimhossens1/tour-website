<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : now()->subDays(29)->startOfDay();
        $to   = $request->filled('to')   ? Carbon::parse($request->to)->endOfDay()     : now()->endOfDay();

        $status         = $request->status;
        $payment_status = $request->payment_status;

        // ✅ KPI (products)
        $totalProducts  = DB::table('products')->count();
        $activeProducts = DB::table('products')->where('is_active', 1)->count();
        $outOfStock     = DB::table('products')->where('stock', '<=', 0)->count();
        $lowStock       = DB::table('products')->whereColumn('stock', '<=', 'low_stock_threshold')->count();

        // ✅ Orders filter (for joining order_items)
        $ordersFilter = function($q) use ($from, $to, $status, $payment_status) {
            $q->whereBetween('o.created_at', [$from, $to]);

            if (!empty($status)) {
                $q->where('o.status', $status);
            } else {
                $q->whereIn('o.status', ['paid','completed']);
            }

            if (!empty($payment_status)) {
                $q->where('o.payment_status', $payment_status);
            }
        };

        // ✅ Top products (best sellers)
        $topProducts = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where($ordersFilter)
            ->selectRaw("
                oi.product_id,
                oi.product_name,
                oi.sku,
                SUM(oi.qty) as sold_qty,
                COALESCE(SUM(oi.line_total),0) as sales_total,
                COALESCE(AVG(oi.unit_price),0) as avg_price
            ")
            ->groupBy('oi.product_id','oi.product_name','oi.sku')
            ->orderByDesc('sold_qty')
            ->limit(15)
            ->get();

        // ✅ Product performance (all products + sales in range)
        $productPerformance = DB::table('products as p')
            ->leftJoin('order_items as oi', 'oi.product_id', '=', 'p.id')
            ->leftJoin('orders as o', 'o.id', '=', 'oi.order_id')
            ->where(function($q) use ($ordersFilter) {
                // keep products even if no sales
                $q->whereNull('o.id')->orWhere($ordersFilter);
            })
            ->selectRaw("
                p.id, p.name, p.sku, p.stock, p.low_stock_threshold, p.is_active,
                COALESCE(SUM(CASE WHEN o.id IS NULL THEN 0 ELSE oi.qty END),0) as sold_qty,
                COALESCE(SUM(CASE WHEN o.id IS NULL THEN 0 ELSE oi.line_total END),0) as sales_total
            ")
            ->groupBy('p.id','p.name','p.sku','p.stock','p.low_stock_threshold','p.is_active')
            ->orderByDesc('sales_total')
            ->paginate(25)
            ->withQueryString();

        // ✅ Low stock list
        $lowStockProducts = DB::table('products')
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->orderBy('stock')
            ->limit(20)
            ->get(['id','name','sku','stock','low_stock_threshold','is_active']);

        return view('admin.reports.products', compact(
            'from','to','status','payment_status',
            'totalProducts','activeProducts','outOfStock','lowStock',
            'topProducts','productPerformance','lowStockProducts'
        ));
    }
}
