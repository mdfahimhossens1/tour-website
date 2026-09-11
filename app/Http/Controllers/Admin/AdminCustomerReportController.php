<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerReportController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Customer Reports
     * ----------------------------------------------------------
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */
        $customerId = $request->input('customer_id');
        $dateFrom   = $request->input('date_from');
        $dateTo     = $request->input('date_to');

        /*
        |--------------------------------------------------------------------------
        | Customer Query
        |--------------------------------------------------------------------------
        |
        | Customer users are identified by:
        |
        | roles.role_name = Customer
        |
        */
        $customerQuery = User::query()
            ->with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_name', 'Customer');
            });

        /*
        |--------------------------------------------------------------------------
        | Customer Options
        |--------------------------------------------------------------------------
        */
        $customerOptions = (clone $customerQuery)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Selected Customer
        |--------------------------------------------------------------------------
        */
        $selectedCustomer = null;

        if ($customerId) {
            $selectedCustomer = (clone $customerQuery)
                ->where('id', $customerId)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */
        $applyDateFilter = function ($query) use ($dateFrom, $dateTo) {

            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            return $query;
        };

        /*
        |--------------------------------------------------------------------------
        | Selected Customer Report
        |--------------------------------------------------------------------------
        */
        $selectedReport = null;

        /*
        |--------------------------------------------------------------------------
        | Selected Customer Bookings
        |--------------------------------------------------------------------------
        */
        $bookings = collect();

        if ($selectedCustomer) {

            $bookingQuery = $selectedCustomer->bookings()
                ->with([
                    'tour',
                    'vendor',
                    'commission',
                ])
                ->latest();

            /*
            | Apply Date Filter
            */
            $applyDateFilter($bookingQuery);

            /*
            |--------------------------------------------------------------------------
            | Booking Statistics
            |--------------------------------------------------------------------------
            */

            $totalBookings = (clone $bookingQuery)->count();

            $completedBookings = (clone $bookingQuery)
                ->where('booking_status', 'completed')
                ->count();

            $pendingBookings = (clone $bookingQuery)
                ->where('booking_status', 'pending')
                ->count();

            $processingBookings = (clone $bookingQuery)
                ->where('booking_status', 'processing')
                ->count();

            $confirmedBookings = (clone $bookingQuery)
                ->where('booking_status', 'confirmed')
                ->count();

            $cancelledBookings = (clone $bookingQuery)
                ->where('booking_status', 'cancelled')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Financial Statistics
            |--------------------------------------------------------------------------
            */

            $totalSpending = (clone $bookingQuery)
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $refundedAmount = (clone $bookingQuery)
                ->where('payment_status', 'refunded')
                ->sum('total_amount');

            $totalDiscount = (clone $bookingQuery)
                ->sum('discount');

            $totalTax = (clone $bookingQuery)
                ->sum('tax_amount');

            /*
            |--------------------------------------------------------------------------
            | Average Booking Value
            |--------------------------------------------------------------------------
            */

            $averageBookingValue = $totalBookings > 0
                ? ((float) $totalSpending / $totalBookings)
                : 0;

            /*
            |--------------------------------------------------------------------------
            | Selected Customer Report Data
            |--------------------------------------------------------------------------
            */

            $selectedReport = [
                'total_bookings'        => $totalBookings,
                'completed_bookings'    => $completedBookings,
                'pending_bookings'      => $pendingBookings,
                'processing_bookings'   => $processingBookings,
                'confirmed_bookings'    => $confirmedBookings,
                'cancelled_bookings'    => $cancelledBookings,

                'total_spending'        => round((float) $totalSpending, 2),
                'refunded_amount'       => round((float) $refundedAmount, 2),
                'total_discount'       => round((float) $totalDiscount, 2),
                'total_tax'             => round((float) $totalTax, 2),

                'average_booking_value' => round(
                    (float) $averageBookingValue,
                    2
                ),
            ];

            /*
            |--------------------------------------------------------------------------
            | Customer Booking List
            |--------------------------------------------------------------------------
            */

            $bookings = $bookingQuery
                ->paginate(15)
                ->withQueryString();
        }

        /*
        |--------------------------------------------------------------------------
        | All Customers Report
        |--------------------------------------------------------------------------
        */

        $reports = collect();

        if (!$selectedCustomer) {

            foreach ($customerOptions as $customer) {

                $bookingQuery = $customer->bookings();

                /*
                | Apply Date Filter
                */
                $applyDateFilter($bookingQuery);

                /*
                |--------------------------------------------------------------------------
                | Booking Counts
                |--------------------------------------------------------------------------
                */

                $totalBookings = (clone $bookingQuery)
                    ->count();

                $completedBookings = (clone $bookingQuery)
                    ->where('booking_status', 'completed')
                    ->count();

                $cancelledBookings = (clone $bookingQuery)
                    ->where('booking_status', 'cancelled')
                    ->count();

                /*
                |--------------------------------------------------------------------------
                | Spending
                |--------------------------------------------------------------------------
                */

                $paidBookings = (clone $bookingQuery)
                    ->where('payment_status', 'paid');

                $totalSpending = (clone $paidBookings)
                    ->sum('total_amount');

                /*
                |--------------------------------------------------------------------------
                | Average Booking Value
                |--------------------------------------------------------------------------
                */

                $averageBookingValue = $totalBookings > 0
                    ? ((float) $totalSpending / $totalBookings)
                    : 0;

                /*
                |--------------------------------------------------------------------------
                | Add Customer Report
                |--------------------------------------------------------------------------
                */

                $reports->push([
                    'customer'              => $customer,

                    'total_bookings'        => $totalBookings,

                    'completed_bookings'    => $completedBookings,

                    'cancelled_bookings'    => $cancelledBookings,

                    'total_spending'        => round(
                        (float) $totalSpending,
                        2
                    ),

                    'average_booking_value' => round(
                        (float) $averageBookingValue,
                        2
                    ),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Sort By Spending
            |--------------------------------------------------------------------------
            */

            $reports = $reports
                ->sortByDesc('total_spending')
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Overall Customer Statistics
        |--------------------------------------------------------------------------
        */

        $overallTotalCustomers = $customerOptions->count();

        $overallTotalBookings = 0;

        $overallTotalSpending = 0;

        foreach ($customerOptions as $customer) {

            $bookingQuery = $customer->bookings();

            /*
            | Apply Date Filter
            */
            $applyDateFilter($bookingQuery);

            /*
            | Total Bookings
            */
            $overallTotalBookings += (clone $bookingQuery)
                ->count();

            /*
            | Total Spending
            */
            $overallTotalSpending += (float) (clone $bookingQuery)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.customer',
            compact(
                'reports',
                'customerOptions',
                'selectedCustomer',
                'selectedReport',
                'bookings',
                'customerId',
                'dateFrom',
                'dateTo',
                'overallTotalCustomers',
                'overallTotalBookings',
                'overallTotalSpending'
            )
        );
    }
}