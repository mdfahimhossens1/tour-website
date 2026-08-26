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
    | BOOKING CODE
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
    | ROOM PRICE
    |--------------------------------------------------------------------------
    */

    private function getRoomPrice(Room $room, $date)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Availability Price
        |--------------------------------------------------------------------------
        */

        $availability = RoomAvailability::where(
            'room_id',
            $room->id
        )
            ->whereDate('date', $date)
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
        | 4. Default Price
        |--------------------------------------------------------------------------
        */

        return (float) ($room->price ?? 0);
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


        return DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Room Count
            |--------------------------------------------------------------------------
            */

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
            | Resort Validation
            |--------------------------------------------------------------------------
            */

            if (
                (int) $room->resort_id !==
                (int) $request->resort_id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Selected room does not belong to this resort.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Room Count Validation
            |--------------------------------------------------------------------------
            */

            if (
                isset($room->room_count) &&
                $roomCount > (int) $room->room_count
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
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

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Vendor not found for this resort.'
                    );
            }


            if (
                $vendor->status !== 'approved'
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
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

            $totalNights =
                $checkIn->diffInDays(
                    $checkOut
                );


            if ($totalNights <= 0) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Invalid booking date.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Availability + Price
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            $currentDate =
                $checkIn->copy();


            while (
                $currentDate->lt($checkOut)
            ) {

                $availability =
                    RoomAvailability::where(
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
                | Availability Exists
                |--------------------------------------------------------------------------
                */

                if (!$availability) {

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Availability not found for ' .
                            $currentDate->format('d M Y')
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Closed
                |--------------------------------------------------------------------------
                */

                if (
                    $availability->is_closed
                ) {

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Room closed on ' .
                            $currentDate->format('d M Y')
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Sold Out
                |--------------------------------------------------------------------------
                */

                if (
                    $availability->is_sold_out
                ) {

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Room sold out on ' .
                            $currentDate->format('d M Y')
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Enough Rooms?
                |--------------------------------------------------------------------------
                */

                if (
                    $availability->available_rooms <
                    $roomCount
                ) {

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Only ' .
                            $availability->available_rooms .
                            ' room(s) available on ' .
                            $currentDate->format('d M Y')
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Daily Price
                |--------------------------------------------------------------------------
                */

                $dailyPrice =
                    $this->getRoomPrice(
                        $room,
                        $currentDate
                    );


                /*
                |--------------------------------------------------------------------------
                | Subtotal
                |--------------------------------------------------------------------------
                */

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

            $discount = max(
                0,
                (float) (
                    $request->discount ?? 0
                )
            );


            $discount = min(
                $discount,
                $subtotal
            );


            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */

            $tax = max(
                0,
                (float) (
                    $request->tax ?? 0
                )
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


            /*
            |--------------------------------------------------------------------------
            | Commission
            |--------------------------------------------------------------------------
            */

            $commissionRate =
                (float) (
                    $vendor->commission_rate ?? 10
                );


            $calculation =
                CommissionService::calculate(
                    $total,
                    $commissionRate
                );


            /*
            |--------------------------------------------------------------------------
            | Booking
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
                        $totalNights,

                    'adults' =>
                        $request->adults,

                    'children' =>
                        $request->children ?? 0,

                    /*
                    |--------------------------------------------------------------------------
                    | Per Room Per Night Price
                    |--------------------------------------------------------------------------
                    */

                    'room_price' =>
                        round(
                            $subtotal /
                            (
                                $totalNights *
                                $roomCount
                            ),
                            2
                        ),

                    'subtotal' =>
                        $subtotal,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'total_amount' =>
                        $total,

                    'commission_rate' =>
                        $commissionRate,

                    'admin_commission' =>
                        $calculation['admin'],

                    'vendor_earning' =>
                        $calculation['vendor'],

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
                $request->has('guests') &&
                is_array($request->guests)
            ) {

                foreach (
                    $request->guests as $guest
                ) {

                    if (
                        empty($guest['name'])
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

            $booking->payments()->create([

                'trx_id' =>
                    'PAY-' .
                    strtoupper(
                        Str::random(16)
                    ),

                'payment_method' =>
                    'cash',

                'amount' =>
                    $total,

                'status' =>
                    'pending',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Reduce Availability
            |--------------------------------------------------------------------------
            */

            $currentDate =
                $checkIn->copy();


            while (
                $currentDate->lt($checkOut)
            ) {

                $availability =
                    RoomAvailability::where(
                        'room_id',
                        $room->id
                    )
                        ->whereDate(
                            'date',
                            $currentDate
                        )
                        ->lockForUpdate()
                        ->first();


                if (!$availability) {

                    throw new \Exception(
                        'Availability not found for ' .
                        $currentDate->format('d M Y')
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Reduce By Room Count
                |--------------------------------------------------------------------------
                */

                $availability->available_rooms =
                    max(
                        0,
                        $availability->available_rooms -
                        $roomCount
                    );


                $availability->is_sold_out =
                    $availability->available_rooms <= 0;


                $availability->save();


                $currentDate->addDay();
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
                    'Resort Booking Created Successfully.'
                );
        });
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

            'success' =>
                true,

            'data' =>
                $resortBooking,

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

            'success' =>
                true,

            'data' =>
                $resortBooking,

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

                $resortBooking =
                    ResortBooking::lockForUpdate()
                        ->findOrFail(
                            $resortBooking->id
                        );


                /*
                |--------------------------------------------------------------------------
                | OLD Room Count
                |--------------------------------------------------------------------------
                */

                $oldRoomCount =
                    max(
                        1,
                        (int) (
                            $resortBooking->room_count ?? 1
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Restore OLD Availability
                |--------------------------------------------------------------------------
                */

                $oldDate =
                    Carbon::parse(
                        $resortBooking->check_in
                    )->startOfDay();


                $oldOut =
                    Carbon::parse(
                        $resortBooking->check_out
                    )->startOfDay();


                while (
                    $oldDate->lt($oldOut)
                ) {

                    $availability =
                        RoomAvailability::where(
                            'room_id',
                            $resortBooking->room_id
                        )
                            ->whereDate(
                                'date',
                                $oldDate
                            )
                            ->lockForUpdate()
                            ->first();


                    if ($availability) {

                        $availability->available_rooms =
                            min(
                                $availability->total_rooms,
                                $availability->available_rooms +
                                $oldRoomCount
                            );


                        $availability->is_sold_out =
                            $availability->available_rooms <= 0;


                        $availability->save();
                    }


                    $oldDate->addDay();
                }


                /*
                |--------------------------------------------------------------------------
                | New Room Count
                |--------------------------------------------------------------------------
                */

                $roomCount =
                    (int) $request->room_count;


                /*
                |--------------------------------------------------------------------------
                | New Room
                |--------------------------------------------------------------------------
                */

                $room =
                    Room::with(
                        'resort.vendor'
                    )->findOrFail(
                        $request->room_id
                    );


                /*
                |--------------------------------------------------------------------------
                | Room Count Validation
                |--------------------------------------------------------------------------
                */

                if (
                    isset($room->room_count) &&
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
                        'Vendor not found.'
                    );
                }


                if (
                    $vendor->status !== 'approved'
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


                $nights =
                    $checkIn->diffInDays(
                        $checkOut
                    );


                if ($nights <= 0) {

                    throw new \Exception(
                        'Invalid booking date.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | New Availability + Price
                |--------------------------------------------------------------------------
                */

                $subtotal = 0;

                $current =
                    $checkIn->copy();


                while (
                    $current->lt($checkOut)
                ) {

                    $availability =
                        RoomAvailability::where(
                            'room_id',
                            $room->id
                        )
                            ->whereDate(
                                'date',
                                $current
                            )
                            ->lockForUpdate()
                            ->first();


                    if (!$availability) {

                        throw new \Exception(
                            'Availability not found on ' .
                            $current->format('d M Y')
                        );
                    }


                    if (
                        $availability->is_closed
                    ) {

                        throw new \Exception(
                            'Room closed on ' .
                            $current->format('d M Y')
                        );
                    }


                    if (
                        $availability->is_sold_out
                    ) {

                        throw new \Exception(
                            'Room sold out on ' .
                            $current->format('d M Y')
                        );
                    }


                    if (
                        $availability->available_rooms <
                        $roomCount
                    ) {

                        throw new \Exception(
                            'Only ' .
                            $availability->available_rooms .
                            ' room(s) available on ' .
                            $current->format('d M Y')
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Daily Price
                    |--------------------------------------------------------------------------
                    */

                    $dailyPrice =
                        $this->getRoomPrice(
                            $room,
                            $current
                        );


                    $subtotal +=
                        $dailyPrice *
                        $roomCount;


                    $current->addDay();
                }


                /*
                |--------------------------------------------------------------------------
                | Discount
                |--------------------------------------------------------------------------
                */

                $discount =
                    min(
                        max(
                            0,
                            (float) (
                                $request->discount ?? 0
                            )
                        ),
                        $subtotal
                    );


                /*
                |--------------------------------------------------------------------------
                | Tax
                |--------------------------------------------------------------------------
                */

                $tax =
                    max(
                        0,
                        (float) (
                            $request->tax ?? 0
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Total
                |--------------------------------------------------------------------------
                */

                $total =
                    max(
                        0,
                        round(
                            ($subtotal - $discount) +
                            $tax,
                            2
                        )
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


                $calculation =
                    CommissionService::calculate(
                        $total,
                        $commissionRate
                    );


                /*
                |--------------------------------------------------------------------------
                | Update Booking
                |--------------------------------------------------------------------------
                */

                $resortBooking->update([

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
                        $nights,

                    'adults' =>
                        $request->adults,

                    'children' =>
                        $request->children ?? 0,

                    /*
                    |--------------------------------------------------------------------------
                    | Per Room Per Night
                    |--------------------------------------------------------------------------
                    */

                    'room_price' =>
                        round(
                            $subtotal /
                            (
                                $nights *
                                $roomCount
                            ),
                            2
                        ),

                    'subtotal' =>
                        $subtotal,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'total_amount' =>
                        $total,

                    'commission_rate' =>
                        $commissionRate,

                    'admin_commission' =>
                        $calculation['admin'],

                    'vendor_earning' =>
                        $calculation['vendor'],

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

                $resortBooking
                    ->guests()
                    ->delete();


                if (
                    $request->has('edit_guests') &&
                    is_array($request->edit_guests)
                ) {

                    foreach (
                        $request->edit_guests as $guest
                    ) {

                        if (
                            empty($guest['name'])
                        ) {
                            continue;
                        }


                        ResortBookingGuest::create([

                            'resort_booking_id' =>
                                $resortBooking->id,

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
                    $resortBooking
                        ->payments()
                        ->latest()
                        ->first();


                $paymentStatus =
                    $request->payment_status === 'paid'
                        ? 'paid'
                        : (
                            $request->payment_status === 'failed'
                                ? 'failed'
                                : 'pending'
                        );


                if ($payment) {

                    $payment->update([

                        'amount' =>
                            $total,

                        'status' =>
                            $paymentStatus,

                        'paid_at' =>
                            $request->payment_status === 'paid'
                                ? now()
                                : null,

                    ]);

                } else {

                    $resortBooking
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
                                $total,

                            'status' =>
                                $paymentStatus,

                            'paid_at' =>
                                $request->payment_status === 'paid'
                                    ? now()
                                    : null,
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Reduce NEW Availability
                |--------------------------------------------------------------------------
                */

                $current =
                    $checkIn->copy();


                while (
                    $current->lt($checkOut)
                ) {

                    $availability =
                        RoomAvailability::where(
                            'room_id',
                            $room->id
                        )
                            ->whereDate(
                                'date',
                                $current
                            )
                            ->lockForUpdate()
                            ->first();


                    if (!$availability) {

                        throw new \Exception(
                            'Availability not found on ' .
                            $current->format('d M Y')
                        );
                    }


                    $availability->available_rooms =
                        max(
                            0,
                            $availability->available_rooms -
                            $roomCount
                        );


                    $availability->is_sold_out =
                        $availability->available_rooms <= 0;


                    $availability->save();


                    $current->addDay();
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
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ResortBooking $resortBooking
    ) {

        return DB::transaction(
            function () use (
                $resortBooking
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Booking
                |--------------------------------------------------------------------------
                */

                $resortBooking =
                    ResortBooking::lockForUpdate()
                        ->findOrFail(
                            $resortBooking->id
                        );


                /*
                |--------------------------------------------------------------------------
                | Room Count
                |--------------------------------------------------------------------------
                */

                $roomCount =
                    max(
                        1,
                        (int) (
                            $resortBooking->room_count ?? 1
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Dates
                |--------------------------------------------------------------------------
                */

                $currentDate =
                    Carbon::parse(
                        $resortBooking->check_in
                    )->startOfDay();


                $checkOut =
                    Carbon::parse(
                        $resortBooking->check_out
                    )->startOfDay();


                /*
                |--------------------------------------------------------------------------
                | Restore Availability
                |--------------------------------------------------------------------------
                */

                while (
                    $currentDate->lt($checkOut)
                ) {

                    $availability =
                        RoomAvailability::where(
                            'room_id',
                            $resortBooking->room_id
                        )
                            ->whereDate(
                                'date',
                                $currentDate
                            )
                            ->lockForUpdate()
                            ->first();


                    if ($availability) {

                        $availability->available_rooms =
                            min(
                                $availability->total_rooms,
                                $availability->available_rooms +
                                $roomCount
                            );


                        $availability->is_sold_out =
                            $availability->available_rooms <= 0;


                        $availability->save();
                    }


                    $currentDate->addDay();
                }


                /*
                |--------------------------------------------------------------------------
                | Guests
                |--------------------------------------------------------------------------
                */

                $resortBooking
                    ->guests()
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | Payments
                |--------------------------------------------------------------------------
                */

                $resortBooking
                    ->payments()
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | Booking
                |--------------------------------------------------------------------------
                */

                $resortBooking->delete();


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
                        'Booking Deleted Successfully.'
                    );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROOMS
    |--------------------------------------------------------------------------
    */

    public function getRooms($resortId)
    {
        $rooms =
            Room::where(
                'resort_id',
                $resortId
            )
                ->where(function ($query) {

                    $query->where(
                        'status',
                        1
                    )
                        ->orWhere(
                            'status',
                            'active'
                        );
                })
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'room_type_id',
                    'room_count',
                    'price',
                    'discount_price',
                ]);


        return response()->json([

            'success' =>
                true,

            'data' =>
                $rooms,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET PRICE
    |--------------------------------------------------------------------------
    */

    public function getPrice(Request $request)
    {
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

            'success' =>
                true,

            'price' =>
                $price,

        ]);
    }
}