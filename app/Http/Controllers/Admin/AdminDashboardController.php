<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Destination;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | BASIC COUNTS
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $todayUsers = User::whereDate('created_at', $today)
            ->count();

        $totalTours = Tour::count();

        $activeTours = Tour::where('status', 1)
            ->count();

        $featuredTours = Tour::where('is_featured', 1)
            ->count();

        $totalDestinations = Destination::count();

        $totalBookings = Booking::count();

        $totalCommission = Booking::where('booking_status','confirmed')
            ->sum('admin_commission');

        $totalVendorPayout = Booking::where('booking_status','confirmed')
            ->sum('vendor_earning');

        $todayBookings = Booking::whereDate('created_at', $today)
            ->count();

        $pendingBookings = Booking::where('booking_status', 'pending')
            ->count();

        $confirmedBookings = Booking::where('booking_status', 'confirmed')
            ->count();

        $cancelledBookings = Booking::where('booking_status', 'cancelled')
            ->count();

        $completedBookings = Booking::where('booking_status', 'completed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

$totalRevenue = Booking::where('booking_status','confirmed')
    ->sum('total_amount');

        $todayRevenue = Payment::where('status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $pendingPayments = Payment::where('status', 'pending')
            ->count();

        $failedPayments = Payment::where('status', 'failed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | REVIEWS
        |--------------------------------------------------------------------------
        */

        $totalReviews = Review::count();

        $pendingReviews = Review::where('status', 0)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS CHART (Monthly)
        |--------------------------------------------------------------------------
        */

        $bookingRows = Booking::selectRaw("
                MONTH(created_at) as month,
                COUNT(*) as total
            ")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bookingChartLabels = [];

        $bookingChartValues = [];

        $bookingMap = $bookingRows
            ->pluck('total', 'month')
            ->toArray();

        for ($m = 1; $m <= 12; $m++) {

            $bookingChartLabels[] = Carbon::createFromDate(
                now()->year,
                $m,
                1
            )->format('M');

            $bookingChartValues[] = (int) ($bookingMap[$m] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | REVENUE CHART (Monthly)
        |--------------------------------------------------------------------------
        */

        $revenueRows = Payment::selectRaw("
                MONTH(created_at) as month,
                SUM(amount) as total
            ")
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $revenueChartLabels = [];

        $revenueChartValues = [];

        $revenueMap = $revenueRows
            ->pluck('total', 'month')
            ->toArray();

        for ($m = 1; $m <= 12; $m++) {

            $revenueChartLabels[] = Carbon::createFromDate(
                now()->year,
                $m,
                1
            )->format('M');

            $revenueChartValues[] = (float) ($revenueMap[$m] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | BOOKING STATUS CHART
        |--------------------------------------------------------------------------
        */

        $bookingStatusChart = [
            'pending' => $pendingBookings,
            'confirmed' => $confirmedBookings,
            'cancelled' => $cancelledBookings,
            'completed' => $completedBookings,
        ];

        /*
        |--------------------------------------------------------------------------
        | TOP TOURS
        |--------------------------------------------------------------------------
        */

        $topTours = Booking::selectRaw("
                tour_id,
                COUNT(*) as total_booking,
                SUM(total_amount) as revenue
            ")
            ->with('tour:id,title,featured_image')
            ->groupBy('tour_id')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentBookings = Booking::with([
                'user:id,name',
                'tour:id,title',
                'tourDate:id,start_date'
            ])
            ->latest()
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | LATEST USERS
        |--------------------------------------------------------------------------
        */

        $latestUsers = User::latest()
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT REVIEWS
        |--------------------------------------------------------------------------
        */

        $recentReviews = Review::with([
                'user:id,name',
                'tour:id,title'
            ])
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY FEED
        |--------------------------------------------------------------------------
        */

        $activityFeed = collect();

        foreach ($recentBookings->take(5) as $booking) {

            $activityFeed->push((object) [

                'type' => 'booking',

                'title' => 'New Booking #' . $booking->booking_code,

                'meta' => optional($booking->user)->name,

                'at' => $booking->created_at,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VISITOR ANALYTICS (OPTIONAL)
        |--------------------------------------------------------------------------
        */

        $worldMapData = [];

        $topCountries = collect();

        if (DB::getSchemaBuilder()->hasTable('visitor_sessions')) {

            $countryCounts = DB::table('visitor_sessions')
                ->selectRaw("
                    COALESCE(country_code,'XX') as code,
                    COALESCE(country_name,'Unknown') as name,
                    COUNT(*) as total
                ")
                ->whereNotNull('last_seen_at')
                ->where(
                    'last_seen_at',
                    '>=',
                    now()->subDays(30)
                )
                ->groupBy('code', 'name')
                ->orderByDesc('total')
                ->get();

            foreach ($countryCounts as $country) {

                $worldMapData[$country->code] = (int) $country->total;
            }

            $topCountries = $countryCounts->take(6);
        }

        /*
        |--------------------------------------------------------------------------
        | ONLINE USERS PLACEHOLDER
        |--------------------------------------------------------------------------
        */

        $onlineUsers = 0;

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard.home', compact(

            // Users
            'totalUsers',
            'todayUsers',

            // Tours
            'totalTours',
            'activeTours',
            'featuredTours',
            'totalDestinations',

            // Bookings
            'totalBookings',
            'todayBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'completedBookings',

            // Revenue
            'totalRevenue',
            'todayRevenue',
            'pendingPayments',
            'failedPayments',

            // Reviews
            'totalReviews',
            'pendingReviews',

            // Charts
            'bookingChartLabels',
            'bookingChartValues',

            'revenueChartLabels',
            'revenueChartValues',

            'bookingStatusChart',

            // Data tables
            'topTours',
            'recentBookings',
            'latestUsers',
            'recentReviews',

            // Activity
            'activityFeed',

            // Visitor
            'worldMapData',
            'topCountries',

            // Online
            'onlineUsers'
        ));
    }
}