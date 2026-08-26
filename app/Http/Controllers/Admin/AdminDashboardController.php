<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Destination;
use App\Models\Commission;

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

        $todayUsers = User::whereDate(
            'created_at',
            $today
        )->count();

        $totalTours = Tour::count();

        $activeTours = Tour::where(
            'status',
            1
        )->count();

        $featuredTours = Tour::where(
            'is_featured',
            1
        )->count();

        $totalDestinations = Destination::count();


        /*
        |--------------------------------------------------------------------------
        | TOUR BOOKINGS
        |--------------------------------------------------------------------------
        */

        $totalTourBookings = Booking::count();

        $todayTourBookings = Booking::whereDate(
            'created_at',
            $today
        )->count();

        $pendingTourBookings = Booking::where(
            'booking_status',
            'pending'
        )->count();

        $confirmedTourBookings = Booking::where(
            'booking_status',
            'confirmed'
        )->count();

        $cancelledTourBookings = Booking::where(
            'booking_status',
            'cancelled'
        )->count();

        $completedTourBookings = Booking::where(
            'booking_status',
            'completed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | ROOM BOOKINGS
        |--------------------------------------------------------------------------
        */

        $totalRoomBookings = RoomBooking::count();

        $todayRoomBookings = RoomBooking::whereDate(
            'created_at',
            $today
        )->count();

        $pendingRoomBookings = RoomBooking::where(
            'booking_status',
            'pending'
        )->count();

        $confirmedRoomBookings = RoomBooking::where(
            'booking_status',
            'confirmed'
        )->count();

        $cancelledRoomBookings = RoomBooking::where(
            'booking_status',
            'cancelled'
        )->count();

        $completedRoomBookings = RoomBooking::where(
            'booking_status',
            'completed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL BOOKINGS
        |--------------------------------------------------------------------------
        |
        | Tour Booking + Room Booking
        |
        */

        $totalBookings =
            $totalTourBookings
            +
            $totalRoomBookings;


        $todayBookings =
            $todayTourBookings
            +
            $todayRoomBookings;


        $pendingBookings =
            $pendingTourBookings
            +
            $pendingRoomBookings;


        $confirmedBookings =
            $confirmedTourBookings
            +
            $confirmedRoomBookings;


        $cancelledBookings =
            $cancelledTourBookings
            +
            $cancelledRoomBookings;


        $completedBookings =
            $completedTourBookings
            +
            $completedRoomBookings;


        /*
        |--------------------------------------------------------------------------
        | TOUR COMMISSION
        |--------------------------------------------------------------------------
        */

        $tourCommission = Commission::whereHas(
            'booking',
            function ($query) {
                $query->where(
                    'booking_status',
                    'confirmed'
                );
            }
        )->sum('admin_earning');


        /*
        |--------------------------------------------------------------------------
        | ROOM COMMISSION
        |--------------------------------------------------------------------------
        */

        $roomCommission = RoomBooking::where(
            'booking_status',
            'confirmed'
        )->sum('admin_commission');


        /*
        |--------------------------------------------------------------------------
        | TOTAL ADMIN COMMISSION
        |--------------------------------------------------------------------------
        */

        $totalCommission =
            (float) $tourCommission
            +
            (float) $roomCommission;


        /*
        |--------------------------------------------------------------------------
        | TOUR VENDOR PAYOUT
        |--------------------------------------------------------------------------
        */

        $tourVendorPayout = Commission::whereHas(
            'booking',
            function ($query) {
                $query->where(
                    'booking_status',
                    'confirmed'
                );
            }
        )->sum('vendor_earning');


        /*
        |--------------------------------------------------------------------------
        | ROOM VENDOR PAYOUT
        |--------------------------------------------------------------------------
        */

        $roomVendorPayout = RoomBooking::where(
            'booking_status',
            'confirmed'
        )->sum('vendor_earning');


        /*
        |--------------------------------------------------------------------------
        | TOTAL VENDOR PAYOUT
        |--------------------------------------------------------------------------
        */

        $totalVendorPayout =
            (float) $tourVendorPayout
            +
            (float) $roomVendorPayout;


        /*
        |--------------------------------------------------------------------------
        | TOUR REVENUE
        |--------------------------------------------------------------------------
        */

        $tourRevenue = Booking::where(
            'booking_status',
            'confirmed'
        )->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | ROOM REVENUE
        |--------------------------------------------------------------------------
        */

        $roomRevenue = RoomBooking::where(
            'booking_status',
            'confirmed'
        )->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | TOTAL REVENUE
        |--------------------------------------------------------------------------
        */

        $totalRevenue =
            (float) $tourRevenue
            +
            (float) $roomRevenue;


        /*
        |--------------------------------------------------------------------------
        | TODAY REVENUE
        |--------------------------------------------------------------------------
        */

        $todayRevenue = Payment::where(
            'status',
            'paid'
        )
        ->whereDate(
            'created_at',
            $today
        )
        ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $pendingPayments = Payment::where(
            'status',
            'pending'
        )->count();

        $failedPayments = Payment::where(
            'status',
            'failed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | ROOM PAYMENT TOTALS
        |--------------------------------------------------------------------------
        */

        $roomPaidRevenue = Payment::where(
            'status',
            'paid'
        )
        ->where(
            'paymentable_type',
            RoomBooking::class
        )
        ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | REVIEWS
        |--------------------------------------------------------------------------
        */

        $totalReviews = Review::count();

        $pendingReviews = Review::where(
            'is_approved',
            false
        )->count();

        $approvedReviews = Review::where(
            'is_approved',
            true
        )->count();


        /*
        |--------------------------------------------------------------------------
        | BOOKING CHART - MONTHLY
        |--------------------------------------------------------------------------
        */

        $bookingRows = Booking::selectRaw(
            "
            MONTH(created_at) as month,
            COUNT(*) as total
            "
        )
        ->whereYear(
            'created_at',
            now()->year
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();


        $roomBookingRows = RoomBooking::selectRaw(
            "
            MONTH(created_at) as month,
            COUNT(*) as total
            "
        )
        ->whereYear(
            'created_at',
            now()->year
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();


        $bookingChartLabels = [];

        $bookingChartValues = [];

        $bookingMap = $bookingRows
            ->pluck(
                'total',
                'month'
            )
            ->toArray();


        $roomBookingMap = $roomBookingRows
            ->pluck(
                'total',
                'month'
            )
            ->toArray();


        for ($m = 1; $m <= 12; $m++) {

            $bookingChartLabels[] = Carbon::createFromDate(
                now()->year,
                $m,
                1
            )->format('M');


            $tourCount = (int) (
                $bookingMap[$m] ?? 0
            );


            $roomCount = (int) (
                $roomBookingMap[$m] ?? 0
            );


            $bookingChartValues[] =
                $tourCount
                +
                $roomCount;
        }


        /*
        |--------------------------------------------------------------------------
        | REVENUE CHART - MONTHLY
        |--------------------------------------------------------------------------
        */

        $revenueRows = Payment::selectRaw(
            "
            MONTH(created_at) as month,
            SUM(amount) as total
            "
        )
        ->whereYear(
            'created_at',
            now()->year
        )
        ->where(
            'status',
            'paid'
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();


        $revenueChartLabels = [];

        $revenueChartValues = [];

        $revenueMap = $revenueRows
            ->pluck(
                'total',
                'month'
            )
            ->toArray();


        for ($m = 1; $m <= 12; $m++) {

            $revenueChartLabels[] = Carbon::createFromDate(
                now()->year,
                $m,
                1
            )->format('M');


            $revenueChartValues[] =
                (float) (
                    $revenueMap[$m] ?? 0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | BOOKING STATUS CHART
        |--------------------------------------------------------------------------
        */

        $bookingStatusChart = [

            'pending' =>
                $pendingBookings,

            'confirmed' =>
                $confirmedBookings,

            'cancelled' =>
                $cancelledBookings,

            'completed' =>
                $completedBookings,

        ];


        /*
        |--------------------------------------------------------------------------
        | ROOM BOOKING STATUS CHART
        |--------------------------------------------------------------------------
        */

        $roomBookingStatusChart = [

            'pending' =>
                $pendingRoomBookings,

            'confirmed' =>
                $confirmedRoomBookings,

            'cancelled' =>
                $cancelledRoomBookings,

            'completed' =>
                $completedRoomBookings,

        ];


        /*
        |--------------------------------------------------------------------------
        | TOP TOURS
        |--------------------------------------------------------------------------
        */

        $topTours = Booking::selectRaw(
            "
            tour_id,
            COUNT(*) as total_booking,
            SUM(total_amount) as revenue
            "
        )
        ->with(
            'tour:id,title,featured_image'
        )
        ->groupBy('tour_id')
        ->orderByDesc('total_booking')
        ->limit(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT TOUR BOOKINGS
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
        | RECENT ROOM BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentRoomBookings = RoomBooking::with([
            'user:id,name',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
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


        /*
        | Tour Booking Activity
        */

        foreach (
            $recentBookings->take(5)
            as $booking
        ) {

            $activityFeed->push(
                (object) [

                    'type' => 'booking',

                    'title' =>
                        'New Tour Booking #' .
                        $booking->booking_code,

                    'meta' =>
                        optional(
                            $booking->user
                        )->name,

                    'at' =>
                        $booking->created_at,

                ]
            );
        }


        /*
        | Room Booking Activity
        */

        foreach (
            $recentRoomBookings->take(5)
            as $roomBooking
        ) {

            $activityFeed->push(
                (object) [

                    'type' => 'room_booking',

                    'title' =>
                        'New Room Booking #' .
                        $roomBooking->booking_code,

                    'meta' =>
                        optional(
                            $roomBooking->user
                        )->name,

                    'at' =>
                        $roomBooking->created_at,

                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sort Activity Feed
        |--------------------------------------------------------------------------
        */

        $activityFeed = $activityFeed
            ->sortByDesc('at')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | VISITOR ANALYTICS
        |--------------------------------------------------------------------------
        */

        $worldMapData = [];

        $topCountries = collect();


        if (
            DB::getSchemaBuilder()
                ->hasTable('visitor_sessions')
        ) {

            $countryCounts = DB::table(
                'visitor_sessions'
            )
            ->selectRaw(
                "
                COALESCE(country_code,'XX') as code,
                COALESCE(country_name,'Unknown') as name,
                COUNT(*) as total
                "
            )
            ->whereNotNull(
                'last_seen_at'
            )
            ->where(
                'last_seen_at',
                '>=',
                now()->subDays(30)
            )
            ->groupBy(
                'code',
                'name'
            )
            ->orderByDesc(
                'total'
            )
            ->get();


            foreach (
                $countryCounts
                as $country
            ) {

                $worldMapData[
                    $country->code
                ] = (int) $country->total;
            }


            $topCountries =
                $countryCounts->take(6);
        }


        /*
        |--------------------------------------------------------------------------
        | ONLINE USERS
        |--------------------------------------------------------------------------
        */

        $onlineUsers = 0;


        /*
        |--------------------------------------------------------------------------
        | RETURN ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard.home',
            compact(

                /*
                | Users
                */

                'totalUsers',
                'todayUsers',


                /*
                | Tours
                */

                'totalTours',
                'activeTours',
                'featuredTours',
                'totalDestinations',


                /*
                | All Bookings
                */

                'totalBookings',
                'todayBookings',
                'pendingBookings',
                'confirmedBookings',
                'cancelledBookings',
                'completedBookings',


                /*
                | Tour Bookings
                */

                'totalTourBookings',
                'todayTourBookings',
                'pendingTourBookings',
                'confirmedTourBookings',
                'cancelledTourBookings',
                'completedTourBookings',


                /*
                | Room Bookings
                */

                'totalRoomBookings',
                'todayRoomBookings',
                'pendingRoomBookings',
                'confirmedRoomBookings',
                'cancelledRoomBookings',
                'completedRoomBookings',


                /*
                | Revenue
                */

                'totalRevenue',
                'tourRevenue',
                'roomRevenue',
                'todayRevenue',


                /*
                | Commission
                */

                'totalCommission',
                'tourCommission',
                'roomCommission',


                /*
                | Vendor Payout
                */

                'totalVendorPayout',
                'tourVendorPayout',
                'roomVendorPayout',


                /*
                | Room Payment
                */

                'roomPaidRevenue',


                /*
                | Payments
                */

                'pendingPayments',
                'failedPayments',


                /*
                | Reviews
                */

                'totalReviews',
                'pendingReviews',
                'approvedReviews',


                /*
                | Charts
                */

                'bookingChartLabels',
                'bookingChartValues',

                'revenueChartLabels',
                'revenueChartValues',

                'bookingStatusChart',

                'roomBookingStatusChart',


                /*
                | Tour Data
                */

                'topTours',
                'recentBookings',


                /*
                | Room Data
                */

                'recentRoomBookings',


                /*
                | Users
                */

                'latestUsers',


                /*
                | Reviews
                */

                'recentReviews',


                /*
                | Activity
                */

                'activityFeed',


                /*
                | Visitor
                */

                'worldMapData',
                'topCountries',


                /*
                | Online
                */

                'onlineUsers'
            )
        );
    }
}