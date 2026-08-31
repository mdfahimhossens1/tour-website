<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResortBooking;
use App\Models\Resort;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomAvailability;
use App\Models\User;
use App\Models\ResortBookingGuest;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResortBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $bookings = ResortBooking::with([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ])
            ->latest()
            ->paginate(20);

        $users = User::orderBy('name')->get();

        $resorts = Resort::where('status', 1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.resort-bookings.index',
            compact(
                'bookings',
                'users',
                'resorts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $users = User::orderBy('name')->get();

        $resorts = Resort::where('status', 1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.resort-bookings.create',
            compact(
                'users',
                'resorts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE BOOKING CODE
    |--------------------------------------------------------------------------
    */

    private function generateBookingCode()
    {
        do {
            $code =
                'RB-' .
                date('Y') .
                '-' .
                strtoupper(Str::random(6));

        } while (
            ResortBooking::where(
                'booking_code',
                $code
            )->exists()
        );

        return $code;
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROOM PRICE
    |--------------------------------------------------------------------------
    */

    private function getRoomPrice(Room $room, $date)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Daily Availability Price
        |--------------------------------------------------------------------------
        */

        $availability = RoomAvailability::where(
            'room_id',
            $room->id
        )
            ->whereDate(
                'date',
                $date
            )
            ->first();

        if (
            $availability &&
            $availability->price !== null &&
            $availability->price > 0
        ) {
            return (float) $availability->price;
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Special Room Price
        |--------------------------------------------------------------------------
        */

        $roomPrice = RoomPrice::where(
            'room_id',
            $room->id
        )
            ->whereDate(
                'from_date',
                '<=',
                $date
            )
            ->whereDate(
                'to_date',
                '>=',
                $date
            )
            ->orderByRaw("
                CASE
                    WHEN type = 'festival' THEN 1
                    WHEN type = 'holiday' THEN 2
                    WHEN type = 'seasonal' THEN 3
                    WHEN type = 'weekend' THEN 4
                    WHEN type = 'normal' THEN 5
                    ELSE 6
                END
            ")
            ->first();

        if (
            $roomPrice &&
            $roomPrice->price !== null &&
            $roomPrice->price > 0
        ) {
            return (float) $roomPrice->price;
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Discount Price
        |--------------------------------------------------------------------------
        */

        if (
            $room->discount_price !== null &&
            $room->discount_price > 0
        ) {
            return (float) $room->discount_price;
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Default Room Price
        |--------------------------------------------------------------------------
        */

        return (float) (
            $room->price ?? 0
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE AVAILABILITY
    |--------------------------------------------------------------------------
    */

    private function restoreAvailability(
        $roomId,
        $checkIn,
        $checkOut,
        $roomCount
    ) {
        $currentDate = Carbon::parse(
            $checkIn
        )->startOfDay();

        $checkOut = Carbon::parse(
            $checkOut
        )->startOfDay();

        while (
            $currentDate->lt($checkOut)
        ) {
            $availability = RoomAvailability::where(
                'room_id',
                $roomId
            )
                ->whereDate(
                    'date',
                    $currentDate
                )
                ->lockForUpdate()
                ->first();

            if ($availability) {
                $totalRooms = (int) (
                    $availability->total_rooms ?? 0
                );

                $availableRooms =
                    (int) (
                        $availability->available_rooms ?? 0
                    );

                $newAvailable =
                    $availableRooms +
                    (int) $roomCount;

                if ($totalRooms > 0) {
                    $newAvailable = min(
                        $totalRooms,
                        $newAvailable
                    );
                }

                $availability->available_rooms =
                    $newAvailable;

                $availability->is_sold_out =
                    $newAvailable <= 0;

                $availability->save();
            }

            $currentDate->addDay();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK & REDUCE AVAILABILITY
    |--------------------------------------------------------------------------
    */

    private function checkAndReduceAvailability(
        Room $room,
        $checkIn,
        $checkOut,
        $roomCount
    ) {
        $currentDate = Carbon::parse(
            $checkIn
        )->startOfDay();

        $checkOut = Carbon::parse(
            $checkOut
        )->startOfDay();

        while (
            $currentDate->lt($checkOut)
        ) {
            $availability = RoomAvailability::where(
                'room_id',
                $room->id
            )
                ->whereDate(
                    'date',
                    $currentDate
                )
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Availability Not Found
            |--------------------------------------------------------------------------
            */

            if (!$availability) {
                throw new \Exception(
                    'Availability not found for ' .
                    $currentDate->format('d M Y')
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Closed
            |--------------------------------------------------------------------------
            */

            if ($availability->is_closed) {
                throw new \Exception(
                    'Room closed on ' .
                    $currentDate->format('d M Y')
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Sold Out
            |--------------------------------------------------------------------------
            */

            $availableRooms = (int) (
                $availability->available_rooms ?? 0
            );

            if (
                $availableRooms < $roomCount
            ) {
                throw new \Exception(
                    'Only ' .
                    $availableRooms .
                    ' room(s) available on ' .
                    $currentDate->format('d M Y')
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Reduce
            |--------------------------------------------------------------------------
            */

            $newAvailable =
                $availableRooms -
                $roomCount;

            $availability->available_rooms =
                max(
                    0,
                    $newAvailable
                );

            $availability->is_sold_out =
                $newAvailable <= 0;

            $availability->save();


            $currentDate->addDay();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE BOOKING TOTAL
    |--------------------------------------------------------------------------
    */

    private function calculateBookingAmount(
        Room $room,
        $checkIn,
        $checkOut,
        $roomCount,
        $discount = 0,
        $tax = 0
    ) {
        $checkIn = Carbon::parse(
            $checkIn
        )->startOfDay();

        $checkOut = Carbon::parse(
            $checkOut
        )->startOfDay();

        $nights = $checkIn->diffInDays(
            $checkOut
        );

        if ($nights <= 0) {
            throw new \Exception(
                'Invalid booking date.'
            );
        }

        $subtotal = 0;

        $currentDate =
            $checkIn->copy();

        while (
            $currentDate->lt($checkOut)
        ) {
            $dailyPrice =
                $this->getRoomPrice(
                    $room,
                    $currentDate
                );

            if ($dailyPrice <= 0) {
                throw new \Exception(
                    'Room price not found for ' .
                    $currentDate->format('d M Y')
                );
            }

            $subtotal +=
                $dailyPrice *
                $roomCount;

            $currentDate->addDay();
        }


        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        $discount = min(
            max(
                0,
                (float) $discount
            ),
            $subtotal
        );


        /*
        |--------------------------------------------------------------------------
        | Tax
        |--------------------------------------------------------------------------
        */

        $tax = max(
            0,
            (float) $tax
        );


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $total = max(
            0,
            round(
                ($subtotal - $discount) + $tax,
                2
            )
        );


        return [
            'nights' => $nights,
            'subtotal' => round(
                $subtotal,
                2
            ),
            'discount' => round(
                $discount,
                2
            ),
            'tax' => round(
                $tax,
                2
            ),
            'total' => $total,
            'room_price' => round(
                $subtotal /
                (
                    $nights *
                    $roomCount
                ),
                2
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'user_id' =>
                'required|exists:users,id',

            'resort_id' =>
                'required|exists:resorts,id',

            'room_id' =>
                'required|exists:rooms,id',

            'room_count' =>
                'required|integer|min:1',

            'check_in' =>
                'required|date',

            'check_out' =>
                'required|date|after:check_in',

            'adults' =>
                'required|integer|min:1',

            'children' =>
                'nullable|integer|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'special_request' =>
                'nullable|string',

            'guests' =>
                'nullable|array',

            'guests.*.name' =>
                'required_with:guests|string|max:255',

            'guests.*.age' =>
                'nullable|integer|min:0',

            'guests.*.gender' =>
                'nullable|in:male,female,other',

            'guests.*.phone' =>
                'nullable|string|max:20',

            'guests.*.nid' =>
                'nullable|string|max:100',

            'guests.*.passport' =>
                'nullable|string|max:100',
        ]);


        try {
            return DB::transaction(
                function () use ($request) {

                    $roomCount =
                        (int) $request->room_count;


                    /*
                    |--------------------------------------------------------------------------
                    | Room
                    |--------------------------------------------------------------------------
                    */

                    $room = Room::with(
                        'resort.vendor'
                    )->findOrFail(
                        $request->room_id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Resort Check
                    |--------------------------------------------------------------------------
                    */

                    if (
                        (int) $room->resort_id !==
                        (int) $request->resort_id
                    ) {
                        throw new \Exception(
                            'Selected room does not belong to this resort.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Room Type Count
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $room->room_count !== null &&
                        $roomCount >
                        (int) $room->room_count
                    ) {
                        throw new \Exception(
                            'Only ' .
                            $room->room_count .
                            ' room(s) available for this room type.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Vendor
                    |--------------------------------------------------------------------------
                    */

                    $vendor =
                        $room->resort->vendor;

                    if (!$vendor) {
                        throw new \Exception(
                            'Vendor not found for this resort.'
                        );
                    }

                    if (
                        $vendor->status !==
                        'approved'
                    ) {
                        throw new \Exception(
                            'Vendor is not approved.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Dates
                    |--------------------------------------------------------------------------
                    */

                    $checkIn =
                        Carbon::parse(
                            $request->check_in
                        )->startOfDay();

                    $checkOut =
                        Carbon::parse(
                            $request->check_out
                        )->startOfDay();


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Amount
                    |--------------------------------------------------------------------------
                    */

                    $calculation =
                        $this->calculateBookingAmount(
                            $room,
                            $checkIn,
                            $checkOut,
                            $roomCount,
                            $request->discount ?? 0,
                            $request->tax ?? 0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Check + Reduce Availability
                    |--------------------------------------------------------------------------
                    */

                    $this->checkAndReduceAvailability(
                        $room,
                        $checkIn,
                        $checkOut,
                        $roomCount
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Commission
                    |--------------------------------------------------------------------------
                    */

                    $commissionRate =
                        (float) (
                            $vendor->commission_rate ?? 10
                        );

                    $commission =
                        CommissionService::calculate(
                            $calculation['total'],
                            $commissionRate
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Create Booking
                    |--------------------------------------------------------------------------
                    */

                    $booking =
                        ResortBooking::create([

                            'user_id' =>
                                $request->user_id,

                            'vendor_id' =>
                                $vendor->id,

                            'resort_id' =>
                                $room->resort_id,

                            'room_id' =>
                                $room->id,

                            'room_count' =>
                                $roomCount,

                            'booking_code' =>
                                $this->generateBookingCode(),

                            'check_in' =>
                                $checkIn,

                            'check_out' =>
                                $checkOut,

                            'total_nights' =>
                                $calculation['nights'],

                            'adults' =>
                                $request->adults,

                            'children' =>
                                $request->children ?? 0,

                            'room_price' =>
                                $calculation['room_price'],

                            'subtotal' =>
                                $calculation['subtotal'],

                            'discount' =>
                                $calculation['discount'],

                            'tax' =>
                                $calculation['tax'],

                            'total_amount' =>
                                $calculation['total'],

                            'commission_rate' =>
                                $commissionRate,

                            'admin_commission' =>
                                $commission['admin'],

                            'vendor_earning' =>
                                $commission['vendor'],

                            'payment_status' =>
                                'pending',

                            'booking_status' =>
                                'pending',

                            'special_request' =>
                                $request->special_request,
                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Guests
                    |--------------------------------------------------------------------------
                    */

                    if (
                        is_array(
                            $request->guests
                        )
                    ) {
                        foreach (
                            $request->guests as $guest
                        ) {
                            if (
                                empty(
                                    $guest['name']
                                )
                            ) {
                                continue;
                            }

                            ResortBookingGuest::create([
                                'resort_booking_id' =>
                                    $booking->id,

                                'name' =>
                                    $guest['name'],

                                'age' =>
                                    $guest['age'] ?? null,

                                'gender' =>
                                    $guest['gender'] ?? null,

                                'phone' =>
                                    $guest['phone'] ?? null,

                                'nid' =>
                                    $guest['nid'] ?? null,

                                'passport' =>
                                    $guest['passport'] ?? null,
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Initial Payment
                    |--------------------------------------------------------------------------
                    */

                    $booking->payments()->create([
                        'trx_id' =>
                            'PAY-' .
                            strtoupper(
                                Str::random(16)
                            ),

                        'payment_method' =>
                            'cash',

                        'amount' =>
                            $calculation['total'],

                        'status' =>
                            'pending',
                    ]);


                    return redirect()
                        ->route(
                            'admin.resort-bookings.index'
                        )
                        ->with(
                            'success',
                            'Resort Booking Created Successfully.'
                        );
                }
            );
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ResortBooking $resortBooking
    ) {
        $resortBooking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        return response()->json([
            'success' => true,
            'data' => $resortBooking,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAILS
    |--------------------------------------------------------------------------
    */

    public function details(
        ResortBooking $booking
    ) {
        $booking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        ResortBooking $resortBooking
    ) {
        $resortBooking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        return response()->json([
            'success' => true,
            'data' => $resortBooking,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ResortBooking $resortBooking
    ) {
        $request->validate([
            'user_id' =>
                'required|exists:users,id',

            'room_id' =>
                'required|exists:rooms,id',

            'room_count' =>
                'required|integer|min:1',

            'check_in' =>
                'required|date',

            'check_out' =>
                'required|date|after:check_in',

            'adults' =>
                'required|integer|min:1',

            'children' =>
                'nullable|integer|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'payment_status' =>
                'required|in:pending,paid,failed,refunded',

            'booking_status' =>
                'required|in:pending,confirmed,checked_in,checked_out,cancelled',

            'special_request' =>
                'nullable|string',

            'edit_guests' =>
                'nullable|array',

            'edit_guests.*.name' =>
                'required_with:edit_guests|string|max:255',

            'edit_guests.*.age' =>
                'nullable|integer|min:0',

            'edit_guests.*.gender' =>
                'nullable|in:male,female,other',

            'edit_guests.*.phone' =>
                'nullable|string|max:20',

            'edit_guests.*.nid' =>
                'nullable|string|max:100',

            'edit_guests.*.passport' =>
                'nullable|string|max:100',
        ]);


        try {
            return DB::transaction(
                function () use (
                    $request,
                    $resortBooking
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock Booking
                    |--------------------------------------------------------------------------
                    */

                    $booking =
                        ResortBooking::lockForUpdate()
                            ->findOrFail(
                                $resortBooking->id
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | Old Booking Information
                    |--------------------------------------------------------------------------
                    */

                    $oldRoomId =
                        $booking->room_id;

                    $oldRoomCount =
                        max(
                            1,
                            (int) (
                                $booking->room_count ?? 1
                            )
                        );

                    $oldCheckIn =
                        Carbon::parse(
                            $booking->check_in
                        )->startOfDay();

                    $oldCheckOut =
                        Carbon::parse(
                            $booking->check_out
                        )->startOfDay();


                    /*
                    |--------------------------------------------------------------------------
                    | If Old Booking Was Not Cancelled
                    | Restore Its Availability First
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $booking->booking_status !==
                        'cancelled'
                    ) {
                        $this->restoreAvailability(
                            $oldRoomId,
                            $oldCheckIn,
                            $oldCheckOut,
                            $oldRoomCount
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | New Room
                    |--------------------------------------------------------------------------
                    */

                    $roomCount =
                        (int) $request->room_count;

                    $room = Room::with(
                        'resort.vendor'
                    )->findOrFail(
                        $request->room_id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Vendor
                    |--------------------------------------------------------------------------
                    */

                    $vendor =
                        $room->resort->vendor;

                    if (!$vendor) {
                        throw new \Exception(
                            'Vendor not found.'
                        );
                    }

                    if (
                        $vendor->status !==
                        'approved'
                    ) {
                        throw new \Exception(
                            'Vendor is not approved.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Room Count
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $room->room_count !== null &&
                        $roomCount >
                        (int) $room->room_count
                    ) {
                        throw new \Exception(
                            'Only ' .
                            $room->room_count .
                            ' room(s) available for this room type.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | New Dates
                    |--------------------------------------------------------------------------
                    */

                    $checkIn =
                        Carbon::parse(
                            $request->check_in
                        )->startOfDay();

                    $checkOut =
                        Carbon::parse(
                            $request->check_out
                        )->startOfDay();


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate
                    |--------------------------------------------------------------------------
                    */

                    $calculation =
                        $this->calculateBookingAmount(
                            $room,
                            $checkIn,
                            $checkOut,
                            $roomCount,
                            $request->discount ?? 0,
                            $request->tax ?? 0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Cancelled Booking Should NOT Consume Room
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $request->booking_status !==
                        'cancelled'
                    ) {
                        $this->checkAndReduceAvailability(
                            $room,
                            $checkIn,
                            $checkOut,
                            $roomCount
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Commission
                    |--------------------------------------------------------------------------
                    */

                    $commissionRate =
                        (float) (
                            $vendor->commission_rate ?? 10
                        );

                    $commission =
                        CommissionService::calculate(
                            $calculation['total'],
                            $commissionRate
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Update Booking
                    |--------------------------------------------------------------------------
                    */

                    $booking->update([

                        'user_id' =>
                            $request->user_id,

                        'vendor_id' =>
                            $vendor->id,

                        'resort_id' =>
                            $room->resort_id,

                        'room_id' =>
                            $room->id,

                        'room_count' =>
                            $roomCount,

                        'check_in' =>
                            $checkIn,

                        'check_out' =>
                            $checkOut,

                        'total_nights' =>
                            $calculation['nights'],

                        'adults' =>
                            $request->adults,

                        'children' =>
                            $request->children ?? 0,

                        'room_price' =>
                            $calculation['room_price'],

                        'subtotal' =>
                            $calculation['subtotal'],

                        'discount' =>
                            $calculation['discount'],

                        'tax' =>
                            $calculation['tax'],

                        'total_amount' =>
                            $calculation['total'],

                        'commission_rate' =>
                            $commissionRate,

                        'admin_commission' =>
                            $commission['admin'],

                        'vendor_earning' =>
                            $commission['vendor'],

                        'payment_status' =>
                            $request->payment_status,

                        'booking_status' =>
                            $request->booking_status,

                        'special_request' =>
                            $request->special_request,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Guests
                    |--------------------------------------------------------------------------
                    */

                    $booking
                        ->guests()
                        ->delete();

                    if (
                        is_array(
                            $request->edit_guests
                        )
                    ) {
                        foreach (
                            $request->edit_guests as $guest
                        ) {
                            if (
                                empty(
                                    $guest['name']
                                )
                            ) {
                                continue;
                            }

                            ResortBookingGuest::create([
                                'resort_booking_id' =>
                                    $booking->id,

                                'name' =>
                                    $guest['name'],

                                'age' =>
                                    $guest['age'] ?? null,

                                'gender' =>
                                    $guest['gender'] ?? null,

                                'phone' =>
                                    $guest['phone'] ?? null,

                                'nid' =>
                                    $guest['nid'] ?? null,

                                'passport' =>
                                    $guest['passport'] ?? null,
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Payment
                    |--------------------------------------------------------------------------
                    */

                    $payment =
                        $booking
                            ->payments()
                            ->latest()
                            ->first();

                    $paymentStatus =
                        match (
                            $request->payment_status
                        ) {
                            'paid' =>
                                'paid',

                            'failed' =>
                                'failed',

                            'refunded' =>
                                'refunded',

                            default =>
                                'pending',
                        };


                    if ($payment) {

                        $payment->update([

                            'amount' =>
                                $calculation['total'],

                            'status' =>
                                $paymentStatus,

                            'paid_at' =>
                                $paymentStatus ===
                                'paid'
                                    ? now()
                                    : null,
                        ]);

                    } else {

                        $booking
                            ->payments()
                            ->create([

                                'trx_id' =>
                                    'PAY-' .
                                    strtoupper(
                                        Str::random(16)
                                    ),

                                'payment_method' =>
                                    'cash',

                                'amount' =>
                                    $calculation['total'],

                                'status' =>
                                    $paymentStatus,

                                'paid_at' =>
                                    $paymentStatus ===
                                    'paid'
                                        ? now()
                                        : null,
                            ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Success
                    |--------------------------------------------------------------------------
                    */

                    return redirect()
                        ->route(
                            'admin.resort-bookings.index'
                        )
                        ->with(
                            'success',
                            'Booking Updated Successfully.'
                        );
                }
            );
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE BOOKING STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        ResortBooking $booking
    ) {
        $request->validate([
            'status' =>
                'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);


        try {
            DB::transaction(
                function () use (
                    $request,
                    $booking
                ) {

                    $booking =
                        ResortBooking::lockForUpdate()
                            ->findOrFail(
                                $booking->id
                            );

                    $oldStatus =
                        $booking->booking_status;

                    $newStatus =
                        $request->status;


                    /*
                    |--------------------------------------------------------------------------
                    | No Change
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $oldStatus ===
                        $newStatus
                    ) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Cancel Booking
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $newStatus ===
                        'cancelled' &&
                        $oldStatus !==
                        'cancelled'
                    ) {

                        $this->restoreAvailability(
                            $booking->room_id,
                            $booking->check_in,
                            $booking->check_out,
                            max(
                                1,
                                (int) (
                                    $booking->room_count ?? 1
                                )
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Re-activate Cancelled Booking
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $oldStatus ===
                        'cancelled' &&
                        $newStatus !==
                        'cancelled'
                    ) {

                        $room =
                            Room::findOrFail(
                                $booking->room_id
                            );

                        $this->checkAndReduceAvailability(
                            $room,
                            $booking->check_in,
                            $booking->check_out,
                            max(
                                1,
                                (int) (
                                    $booking->room_count ?? 1
                                )
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Update
                    |--------------------------------------------------------------------------
                    */

                    $booking->update([
                        'booking_status' =>
                            $newStatus,
                    ]);
                }
            );


            return back()->with(
                'success',
                'Booking status updated successfully.'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function paymentStatus(
        Request $request,
        ResortBooking $booking
    ) {
        $request->validate([
            'status' =>
                'required|in:pending,paid,failed,refunded',

            'payment_method' =>
                'nullable|string|max:100',
        ]);


        try {
            DB::transaction(
                function () use (
                    $request,
                    $booking
                ) {

                    $booking =
                        ResortBooking::lockForUpdate()
                            ->findOrFail(
                                $booking->id
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | Booking Payment Status
                    |--------------------------------------------------------------------------
                    */

                    $booking->update([
                        'payment_status' =>
                            $request->status,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Latest Payment
                    |--------------------------------------------------------------------------
                    */

                    $payment =
                        $booking
                            ->payments()
                            ->latest()
                            ->first();


                    if ($payment) {

                        $payment->update([

                            'status' =>
                                $request->status,

                            'payment_method' =>
                                $request->payment_method
                                    ??
                                    $payment->payment_method,

                            'paid_at' =>
                                $request->status ===
                                'paid'
                                    ? now()
                                    : null,
                        ]);

                    } else {

                        $booking
                            ->payments()
                            ->create([

                                'trx_id' =>
                                    'PAY-' .
                                    strtoupper(
                                        Str::random(16)
                                    ),

                                'payment_method' =>
                                    $request->payment_method
                                        ?? 'cash',

                                'amount' =>
                                    $booking->total_amount,

                                'status' =>
                                    $request->status,

                                'paid_at' =>
                                    $request->status ===
                                    'paid'
                                        ? now()
                                        : null,
                            ]);
                    }
                }
            );


            return back()->with(
                'success',
                'Payment status updated successfully.'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ResortBooking $resortBooking
    ) {
        try {

            DB::transaction(
                function () use (
                    $resortBooking
                ) {

                    $booking =
                        ResortBooking::lockForUpdate()
                            ->findOrFail(
                                $resortBooking->id
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | Restore Availability
                    |--------------------------------------------------------------------------
                    |
                    | If already cancelled, availability was already restored.
                    |
                    */

                    if (
                        $booking->booking_status !==
                        'cancelled'
                    ) {

                        $this->restoreAvailability(
                            $booking->room_id,
                            $booking->check_in,
                            $booking->check_out,
                            max(
                                1,
                                (int) (
                                    $booking->room_count ?? 1
                                )
                            )
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Guests
                    |--------------------------------------------------------------------------
                    */

                    $booking
                        ->guests()
                        ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | Payments
                    |--------------------------------------------------------------------------
                    */

                    $booking
                        ->payments()
                        ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | Booking
                    |--------------------------------------------------------------------------
                    */

                    $booking->delete();
                }
            );


            return redirect()
                ->route(
                    'admin.resort-bookings.index'
                )
                ->with(
                    'success',
                    'Booking Deleted Successfully.'
                );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROOMS
    |--------------------------------------------------------------------------
    */

    public function getRooms(
        $resortId
    ) {
        $rooms =
            Room::where(
                'resort_id',
                $resortId
            )
                ->where(function ($query) {

                    $query
                        ->where(
                            'status',
                            1
                        )
                        ->orWhere(
                            'status',
                            'active'
                        );
                })
                ->orderBy(
                    'name'
                )
                ->get([
                    'id',
                    'name',
                    'room_type_id',
                    'room_count',
                    'price',
                    'discount_price',
                ]);


        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET PRICE
    |--------------------------------------------------------------------------
    */

    public function getPrice(
        Request $request
    ) {
        $request->validate([
            'room_id' =>
                'required|exists:rooms,id',

            'date' =>
                'required|date',
        ]);


        $room =
            Room::findOrFail(
                $request->room_id
            );


        $date =
            Carbon::parse(
                $request->date
            );


        $price =
            $this->getRoomPrice(
                $room,
                $date
            );


        return response()->json([
            'success' => true,
            'price' => $price,
        ]);
    }
}