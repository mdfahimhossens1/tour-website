<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
  public function index()
  {
    $today = Carbon::today();

    // KPIs
    $totalSales = (float) Order::where('payment_status','paid')->sum('grand_total');
    $todaySales = (float) Order::where('payment_status','paid')->whereDate('created_at', $today)->sum('grand_total');

    $totalOrders = Order::count();
    $todayOrders = Order::whereDate('created_at', $today)->count();

    $totalCustomers = User::count();
    $todayCustomers = User::whereDate('created_at', $today)->count();

    $totalProducts = Product::count();
    $activeProducts = Product::where('is_active',1)->count();

    // Charts: Sales by month (current year)
    $salesRows = Order::selectRaw("MONTH(created_at) as m, SUM(grand_total) as total")
      ->whereYear('created_at', now()->year)
      ->where('payment_status','paid')
      ->groupBy('m')
      ->orderBy('m')
      ->get();

    $salesChartLabels = [];
    $salesChartValues = [];

    // fill 12 months to keep chart stable
    $map = $salesRows->pluck('total','m')->toArray();
    for($m=1; $m<=12; $m++){
      $salesChartLabels[] = Carbon::createFromDate(now()->year, $m, 1)->format('M');
      $salesChartValues[] = (float) ($map[$m] ?? 0);
    }

    // Order status (doughnut)
    $orderStatus = [
      'pending' => Order::where('status','pending')->count(),
      'processing' => Order::where('status','processing')->count(),
      'completed' => Order::where('status','completed')->count(),
      'cancelled' => Order::where('status','cancelled')->count(),
    ];
$countryCounts = DB::table('visitor_sessions')
    ->selectRaw("
        COALESCE(country_code,'XX') as code,
        COALESCE(country_name,'Unknown') as name,
        COUNT(*) as total
    ")
    ->whereNotNull('last_seen_at')
    ->where('last_seen_at', '>=', now()->subDays(30))
    ->groupBy('code','name')
    ->orderByDesc('total')
    ->get();

$worldMapData = [];
foreach ($countryCounts as $c) {
    // jsVectorMap expects ISO2 country codes like BD, US
    $worldMapData[$c->code] = (int) $c->total;
}

$topCountries = $countryCounts->take(6);
    // Top products (by sold qty)
    $topProducts = OrderItem::selectRaw('product_id, product_name as name, sku, SUM(qty) as sold_qty, SUM(line_total) as revenue')
      ->groupBy('product_id','product_name','sku')
      ->orderByDesc('sold_qty')
      ->limit(5)
      ->get();

    // Recent orders
    $recentOrders = Order::orderByDesc('id')->limit(8)->get();

    // Low stock
    $lowStockProducts = Product::whereColumn('stock','<=','low_stock_threshold')
      ->orderBy('stock')
      ->limit(8)
      ->get();

    // Activity feed (simple)
    $activityFeed = collect();

    foreach($recentOrders->take(5) as $o){
      $activityFeed->push((object)[
        'type' => 'order',
        'title' => "Order #{$o->id} ({$o->status})",
        'meta' => "Total ৳ ".number_format($o->grand_total),
        'at' => $o->created_at,
      ]);
    }

    // Traffic placeholders (তুমি যদি visitor_sessions use করো, পরে connect করে দেব)
    $uniqueVisitorsToday = 0;
    $pageViewsToday = 0;

    // Online logged in users (আপনার আগের logic থাকলে সেটাই রাখো)
    $onlineLoggedInCount = 0;

    return view('admin.dashboard.home', compact(
      'totalSales','todaySales',
      'totalOrders','todayOrders',
      'totalCustomers','todayCustomers',
      'totalProducts','activeProducts',
      'salesChartLabels','salesChartValues',
      'orderStatus',
      'topProducts','recentOrders','lowStockProducts','activityFeed',
      'uniqueVisitorsToday','pageViewsToday','onlineLoggedInCount',
      'worldMapData','topCountries'
    ));
  }
}
