<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display all commissions
     * ----------------------------------------------------------
     *
     * Supports:
     *
     * 1. Tour Booking Commission
     * 2. Room Booking Commission
     * 3. Transport Booking Commission
     *
     * All commissions are stored in the commissions table.
     */
    public function index(Request $request)
    {
        /*
        |----------------------------------------------------------------------
        | Search
        |----------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->input('search', '')
        );


        /*
        |----------------------------------------------------------------------
        | Commission Query
        |----------------------------------------------------------------------
        */

        $query = Commission::query()
            ->with([

                /*
                |------------------------------------------------------------------
                | Tour Booking
                |------------------------------------------------------------------
                */

                'booking.user',
                'booking.vendor',
                'booking.tour',
                'booking.tourDate',
                'booking.transaction',


                /*
                |------------------------------------------------------------------
                | Room Booking
                |------------------------------------------------------------------
                */

                'roomBooking.user',
                'roomBooking.vendor',
                'roomBooking.resort',
                'roomBooking.room',
                'roomBooking.guests',
                'roomBooking.payments',


                /*
                |------------------------------------------------------------------
                | Transport Booking
                |------------------------------------------------------------------
                */

                'transportBooking.user',
                'transportBooking.vendor',
                'transportBooking.vehicle',
                'transportBooking.payments',
                'transportBooking.latestPayment',

            ])
            ->latest('id');


        /*
        |----------------------------------------------------------------------
        | Search Filter
        |----------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($query) use ($search) {

                /*
                |------------------------------------------------------------------
                | TOUR COMMISSION SEARCH
                |------------------------------------------------------------------
                */

                $query->whereHas(
                    'booking',
                    function ($bookingQuery) use ($search) {

                        $bookingQuery
                            ->where(
                                'booking_code',
                                'like',
                                "%{$search}%"
                            )

                            /*
                            | Customer
                            */

                            ->orWhereHas(
                                'user',
                                function ($userQuery) use ($search) {

                                    $userQuery
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'email',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            )

                            /*
                            | Vendor
                            */

                            ->orWhereHas(
                                'vendor',
                                function ($vendorQuery) use ($search) {

                                    $vendorQuery->where(
                                        'business_name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            )

                            /*
                            | Tour
                            */

                            ->orWhereHas(
                                'tour',
                                function ($tourQuery) use ($search) {

                                    $tourQuery->where(
                                        'title',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            );
                    }
                )


                /*
                |------------------------------------------------------------------
                | ROOM COMMISSION SEARCH
                |------------------------------------------------------------------
                */

                ->orWhereHas(
                    'roomBooking',
                    function ($roomBookingQuery) use ($search) {

                        $roomBookingQuery
                            ->where(
                                'booking_code',
                                'like',
                                "%{$search}%"
                            )

                            /*
                            | Customer
                            */

                            ->orWhereHas(
                                'user',
                                function ($userQuery) use ($search) {

                                    $userQuery
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'email',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            )

                            /*
                            | Vendor
                            */

                            ->orWhereHas(
                                'vendor',
                                function ($vendorQuery) use ($search) {

                                    $vendorQuery->where(
                                        'business_name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            )

                            /*
                            | Resort
                            */

                            ->orWhereHas(
                                'resort',
                                function ($resortQuery) use ($search) {

                                    $resortQuery->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            )

                            /*
                            | Room
                            */

                            ->orWhereHas(
                                'room',
                                function ($roomQuery) use ($search) {

                                    $roomQuery->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            );
                    }
                )


                /*
                |------------------------------------------------------------------
                | TRANSPORT COMMISSION SEARCH
                |------------------------------------------------------------------
                */

                ->orWhereHas(
                    'transportBooking',
                    function ($transportQuery) use ($search) {

                        $transportQuery
                            ->where(
                                'booking_code',
                                'like',
                                "%{$search}%"
                            )

                            /*
                            | Pickup Location
                            */

                            ->orWhere(
                                'pickup_location',
                                'like',
                                "%{$search}%"
                            )

                            /*
                            | Dropoff Location
                            */

                            ->orWhere(
                                'dropoff_location',
                                'like',
                                "%{$search}%"
                            )

                            /*
                            | Customer
                            */

                            ->orWhereHas(
                                'user',
                                function ($userQuery) use ($search) {

                                    $userQuery
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'email',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            )

                            /*
                            | Vendor
                            */

                            ->orWhereHas(
                                'vendor',
                                function ($vendorQuery) use ($search) {

                                    $vendorQuery->where(
                                        'business_name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            )

                            /*
                            | Vehicle
                            */

                            ->orWhereHas(
                                'vehicle',
                                function ($vehicleQuery) use ($search) {

                                    $vehicleQuery
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'registration_number',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'brand',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'model',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );
                    }
                );
            });
        }


        /*
        |----------------------------------------------------------------------
        | Pagination
        |----------------------------------------------------------------------
        */

        $commissions = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |----------------------------------------------------------------------
        | Combined Statistics
        |----------------------------------------------------------------------
        */

        $totalSales = (float) Commission::sum(
            'total_amount'
        );

        $adminEarning = (float) Commission::sum(
            'admin_earning'
        );

        $vendorEarning = (float) Commission::sum(
            'vendor_earning'
        );

        $totalCommissions = Commission::count();


        /*
        |----------------------------------------------------------------------
        | Tour Statistics
        |----------------------------------------------------------------------
        */

        $tourQuery = Commission::query()
            ->whereNotNull('booking_id');


        $tourSales = (float) (clone $tourQuery)
            ->sum('total_amount');

        $tourAdminEarning = (float) (clone $tourQuery)
            ->sum('admin_earning');

        $tourVendorEarning = (float) (clone $tourQuery)
            ->sum('vendor_earning');

        $tourCommissions = (clone $tourQuery)
            ->count();


        /*
        |----------------------------------------------------------------------
        | Room Statistics
        |----------------------------------------------------------------------
        */

        $roomQuery = Commission::query()
            ->whereNotNull('room_booking_id');


        $roomSales = (float) (clone $roomQuery)
            ->sum('total_amount');

        $roomAdminEarning = (float) (clone $roomQuery)
            ->sum('admin_earning');

        $roomVendorEarning = (float) (clone $roomQuery)
            ->sum('vendor_earning');

        $roomCommissions = (clone $roomQuery)
            ->count();


        /*
        |----------------------------------------------------------------------
        | Transport Statistics
        |----------------------------------------------------------------------
        */

        $transportQuery = Commission::query()
            ->whereNotNull('transport_booking_id');


        $transportSales = (float) (clone $transportQuery)
            ->sum('total_amount');

        $transportAdminEarning = (float) (clone $transportQuery)
            ->sum('admin_earning');

        $transportVendorEarning = (float) (clone $transportQuery)
            ->sum('vendor_earning');

        $transportCommissions = (clone $transportQuery)
            ->count();


        /*
        |----------------------------------------------------------------------
        | Statistics Array
        |----------------------------------------------------------------------
        */

        $stats = [

            /*
            | Combined
            */

            'total_sales' => round(
                $totalSales,
                2
            ),

            'admin_earning' => round(
                $adminEarning,
                2
            ),

            'vendor_earning' => round(
                $vendorEarning,
                2
            ),

            'total_commissions' =>
                $totalCommissions,


            /*
            | Tour
            */

            'tour_sales' => round(
                $tourSales,
                2
            ),

            'tour_admin_earning' => round(
                $tourAdminEarning,
                2
            ),

            'tour_vendor_earning' => round(
                $tourVendorEarning,
                2
            ),

            'tour_commissions' =>
                $tourCommissions,


            /*
            | Room
            */

            'room_sales' => round(
                $roomSales,
                2
            ),

            'room_admin_earning' => round(
                $roomAdminEarning,
                2
            ),

            'room_vendor_earning' => round(
                $roomVendorEarning,
                2
            ),

            'room_commissions' =>
                $roomCommissions,


            /*
            | Transport
            */

            'transport_sales' => round(
                $transportSales,
                2
            ),

            'transport_admin_earning' => round(
                $transportAdminEarning,
                2
            ),

            'transport_vendor_earning' => round(
                $transportVendorEarning,
                2
            ),

            'transport_commissions' =>
                $transportCommissions,
        ];


        /*
        |----------------------------------------------------------------------
        | Return View
        |----------------------------------------------------------------------
        */

        return view(
            'admin.commissions.index',
            compact(
                'commissions',
                'stats'
            )
        );
    }


    /**
     * ----------------------------------------------------------
     * Show single commission
     * ----------------------------------------------------------
     */
    public function show($id)
    {
        /*
        |----------------------------------------------------------------------
        | Load Commission
        |----------------------------------------------------------------------
        */

        $commission = Commission::with([

            /*
            | Tour Booking
            */

            'booking.user',
            'booking.vendor',
            'booking.tour',
            'booking.tourDate',
            'booking.transaction',


            /*
            | Room Booking
            */

            'roomBooking.user',
            'roomBooking.vendor',
            'roomBooking.resort',
            'roomBooking.room',
            'roomBooking.guests',
            'roomBooking.payments',


            /*
            | Transport Booking
            */

            'transportBooking.user',
            'transportBooking.vendor',
            'transportBooking.vehicle',
            'transportBooking.payments',
            'transportBooking.latestPayment',

        ])->findOrFail($id);


        /*
        |----------------------------------------------------------------------
        | Determine Commission Type
        |----------------------------------------------------------------------
        */

        if ($commission->booking_id) {

            $commission->source_type = 'tour';

        } elseif ($commission->room_booking_id) {

            $commission->source_type = 'room';

        } elseif ($commission->transport_booking_id) {

            $commission->source_type = 'transport';

        } else {

            $commission->source_type = null;
        }


        /*
        |----------------------------------------------------------------------
        | Return View
        |----------------------------------------------------------------------
        */

        return view(
            'admin.commissions.show',
            compact('commission')
        );
    }
}