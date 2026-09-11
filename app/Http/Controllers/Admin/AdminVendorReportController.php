<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminVendorReportController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Vendor Reports
     * ----------------------------------------------------------
     *
     * Supports:
     *
     * 1. All Vendor Reports
     * 2. Vendor Filter
     * 3. Date From / Date To
     * 4. Vendor Details
     * 5. Booking Details
     *
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Filter Values
        |--------------------------------------------------------------------------
        */

        $vendorId = $request->integer('vendor_id');

        $dateFrom = $request->input('date_from');

        $dateTo = $request->input('date_to');


        /*
        |--------------------------------------------------------------------------
        | Vendor Options
        |--------------------------------------------------------------------------
        */

        $vendorOptions = Vendor::query()
            ->orderBy('business_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Vendor Reports
        |--------------------------------------------------------------------------
        */

        $vendorsQuery = Vendor::query()
            ->orderBy('business_name');


        /*
        |--------------------------------------------------------------------------
        | Vendor Filter
        |--------------------------------------------------------------------------
        */

        if ($vendorId) {
            $vendorsQuery->where('id', $vendorId);
        }


        $vendors = $vendorsQuery->get();


        /*
        |--------------------------------------------------------------------------
        | Build Reports
        |--------------------------------------------------------------------------
        */

        $reports = $vendors->map(function ($vendor) use (
            $dateFrom,
            $dateTo
        ) {

            /*
            |--------------------------------------------------------------------------
            | Booking Query
            |--------------------------------------------------------------------------
            */

            $bookingQuery = Booking::query()
                ->where('vendor_id', $vendor->id);


            /*
            |--------------------------------------------------------------------------
            | Date From
            |--------------------------------------------------------------------------
            */

            if ($dateFrom) {
                $bookingQuery->whereDate(
                    'created_at',
                    '>=',
                    $dateFrom
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Date To
            |--------------------------------------------------------------------------
            */

            if ($dateTo) {
                $bookingQuery->whereDate(
                    'created_at',
                    '<=',
                    $dateTo
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Booking Statistics
            |--------------------------------------------------------------------------
            */

            $totalBookings = (clone $bookingQuery)->count();


            $pendingBookings = (clone $bookingQuery)
                ->where('booking_status', 'pending')
                ->count();


            $processingBookings = (clone $bookingQuery)
                ->where('booking_status', 'processing')
                ->count();


            $confirmedBookings = (clone $bookingQuery)
                ->where('booking_status', 'confirmed')
                ->count();


            $completedBookings = (clone $bookingQuery)
                ->where('booking_status', 'completed')
                ->count();


            $cancelledBookings = (clone $bookingQuery)
                ->where('booking_status', 'cancelled')
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Total Sales
            |--------------------------------------------------------------------------
            |
            | Cancelled bookings are excluded.
            |
            */

            $totalSales = (clone $bookingQuery)
                ->where('booking_status', '!=', 'cancelled')
                ->sum('total_amount');


            /*
            |--------------------------------------------------------------------------
            | Vendor Earnings
            |--------------------------------------------------------------------------
            */

            $vendorEarnings = (clone $bookingQuery)
                ->whereHas('commission')
                ->with('commission')
                ->get()
                ->sum(function ($booking) {

                    return (float) (
                        $booking->commission->vendor_earning ?? 0
                    );
                });


            /*
            |--------------------------------------------------------------------------
            | Admin Commission
            |--------------------------------------------------------------------------
            */

            $adminCommission = (clone $bookingQuery)
                ->whereHas('commission')
                ->with('commission')
                ->get()
                ->sum(function ($booking) {

                    return (float) (
                        $booking->commission->admin_earning ?? 0
                    );
                });


            /*
            |--------------------------------------------------------------------------
            | Return Report
            |--------------------------------------------------------------------------
            */

            return [
                'vendor' => $vendor,

                'total_bookings' => $totalBookings,

                'pending_bookings' => $pendingBookings,

                'processing_bookings' => $processingBookings,

                'confirmed_bookings' => $confirmedBookings,

                'completed_bookings' => $completedBookings,

                'cancelled_bookings' => $cancelledBookings,

                'total_sales' => $totalSales,

                'vendor_earnings' => $vendorEarnings,

                'admin_commission' => $adminCommission,
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Selected Vendor
        |--------------------------------------------------------------------------
        */

        $selectedVendor = null;

        $selectedReport = null;

        $bookings = null;


        if ($vendorId) {

            $selectedVendor = Vendor::findOrFail($vendorId);


            /*
            |--------------------------------------------------------------------------
            | Selected Vendor Booking Query
            |--------------------------------------------------------------------------
            */

            $selectedBookingQuery = Booking::query()
                ->with([
                    'user',
                    'tour',
                    'tourDate',
                    'commission',
                ])
                ->where(
                    'vendor_id',
                    $selectedVendor->id
                )
                ->latest();


            /*
            |--------------------------------------------------------------------------
            | Date From
            |--------------------------------------------------------------------------
            */

            if ($dateFrom) {

                $selectedBookingQuery->whereDate(
                    'created_at',
                    '>=',
                    $dateFrom
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Date To
            |--------------------------------------------------------------------------
            */

            if ($dateTo) {

                $selectedBookingQuery->whereDate(
                    'created_at',
                    '<=',
                    $dateTo
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Statistics Query
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | These calculations happen before pagination.
            | So totals will not be limited to the current page.
            |
            */

            $selectedStatsQuery = clone $selectedBookingQuery;


            /*
            |--------------------------------------------------------------------------
            | Total Bookings
            |--------------------------------------------------------------------------
            */

            $selectedTotalBookings =
                (clone $selectedStatsQuery)->count();


            /*
            |--------------------------------------------------------------------------
            | Pending
            |--------------------------------------------------------------------------
            */

            $selectedPendingBookings =
                (clone $selectedStatsQuery)
                    ->where('booking_status', 'pending')
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Processing
            |--------------------------------------------------------------------------
            */

            $selectedProcessingBookings =
                (clone $selectedStatsQuery)
                    ->where('booking_status', 'processing')
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Confirmed
            |--------------------------------------------------------------------------
            */

            $selectedConfirmedBookings =
                (clone $selectedStatsQuery)
                    ->where('booking_status', 'confirmed')
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Completed
            |--------------------------------------------------------------------------
            */

            $selectedCompletedBookings =
                (clone $selectedStatsQuery)
                    ->where('booking_status', 'completed')
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Cancelled
            |--------------------------------------------------------------------------
            */

            $selectedCancelledBookings =
                (clone $selectedStatsQuery)
                    ->where('booking_status', 'cancelled')
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Total Sales
            |--------------------------------------------------------------------------
            */

            $selectedTotalSales =
                (clone $selectedStatsQuery)
                    ->where('booking_status', '!=', 'cancelled')
                    ->sum('total_amount');


            /*
            |--------------------------------------------------------------------------
            | Commission Query
            |--------------------------------------------------------------------------
            */

            $commissionBookings =
                (clone $selectedStatsQuery)
                    ->whereHas('commission')
                    ->with('commission')
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | Vendor Earnings
            |--------------------------------------------------------------------------
            */

            $selectedVendorEarnings =
                $commissionBookings->sum(function ($booking) {

                    return (float) (
                        $booking->commission->vendor_earning ?? 0
                    );
                });


            /*
            |--------------------------------------------------------------------------
            | Admin Commission
            |--------------------------------------------------------------------------
            */

            $selectedAdminCommission =
                $commissionBookings->sum(function ($booking) {

                    return (float) (
                        $booking->commission->admin_earning ?? 0
                    );
                });


            /*
            |--------------------------------------------------------------------------
            | Selected Report
            |--------------------------------------------------------------------------
            */

            $selectedReport = [
                'total_bookings' =>
                    $selectedTotalBookings,

                'pending_bookings' =>
                    $selectedPendingBookings,

                'processing_bookings' =>
                    $selectedProcessingBookings,

                'confirmed_bookings' =>
                    $selectedConfirmedBookings,

                'completed_bookings' =>
                    $selectedCompletedBookings,

                'cancelled_bookings' =>
                    $selectedCancelledBookings,

                'total_sales' =>
                    $selectedTotalSales,

                'vendor_earnings' =>
                    $selectedVendorEarnings,

                'admin_commission' =>
                    $selectedAdminCommission,
            ];


            /*
            |--------------------------------------------------------------------------
            | Paginated Booking List
            |--------------------------------------------------------------------------
            */

            $bookings = $selectedBookingQuery
                ->paginate(20)
                ->withQueryString();
        }


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.vendor',
            compact(
                'reports',
                'vendorOptions',
                'selectedVendor',
                'selectedReport',
                'bookings',
                'vendorId',
                'dateFrom',
                'dateTo'
            )
        );
    }
}