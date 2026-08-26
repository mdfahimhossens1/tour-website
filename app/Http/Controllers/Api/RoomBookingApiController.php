<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomBookingGuest;
use App\Models\Payment;
use App\Models\RoomAvailability;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Carbon\Carbon;

class RoomBookingApiController extends Controller
{
    /**
     * ============================================================
     * Create Room Booking
     * ============================================================
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Request Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],

            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'check_out' => [
                'required',
                'date',
                'after:check_in',
            ],

            'room_count' => [
                'required',
                'integer',
                'min:1',
            ],

            'adults' => [
                'required',
                'integer',
                'min:1',
            ],

            'children' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'special_request' => [
                'nullable',
                'string',
                'max:5000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Vendor Payment Method
            |--------------------------------------------------------------------------
            */

            'payment_method_id' => [
                'required',
                'integer',
                'exists:vendor_payment_methods,id',
            ],

            'payment_phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            /*
            |--------------------------------------------------------------------------
            | Guests
            |--------------------------------------------------------------------------
            */

            'guests' => [
                'required',
                'array',
                'min:1',
            ],

            'guests.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'guests.*.age' => [
                'nullable',
                'integer',
                'min:0',
                'max:120',
            ],

            'guests.*.gender' => [
                'nullable',
                'in:male,female,other',
            ],

            'guests.*.phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'guests.*.nid' => [
                'nullable',
                'string',
                'max:100',
            ],

            'guests.*.passport' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);


        try {

            $booking = DB::transaction(function () use (
                $validated,
                $request
            ) {

                /*
                |--------------------------------------------------------------------------
                | Authenticated User
                |--------------------------------------------------------------------------
                */

                $user = $request->user();

                if (!$user) {
                    abort(
                        401,
                        'Please login before booking a room.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Get Room
                |--------------------------------------------------------------------------
                */

                $room = Room::with([
                    'resort',
                    'prices',
                ])
                    ->where('id', $validated['room_id'])
                    ->lockForUpdate()
                    ->first();


                if (!$room) {
                    abort(404, 'Room not found.');
                }


                if (!$room->status) {
                    abort(
                        422,
                        'This room is currently unavailable.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Resort Check
                |--------------------------------------------------------------------------
                */

                if (!$room->resort) {
                    abort(
                        422,
                        'This room is not associated with a resort.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Validate Vendor Payment Method
                |--------------------------------------------------------------------------
                |
                | নিশ্চিত করছি selected payment method:
                | 1. Exists
                | 2. Active
                | 3. Belongs to this room's vendor
                |
                */

                $paymentMethod = VendorPaymentMethod::query()
                    ->where(
                        'id',
                        $validated['payment_method_id']
                    )
                    ->where(
                        'vendor_id',
                        $room->resort->vendor_id
                    )
                    ->active()
                    ->first();


                if (!$paymentMethod) {
                    abort(
                        422,
                        'The selected payment method is invalid or unavailable for this vendor.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Basic Room Information
                |--------------------------------------------------------------------------
                */

                $roomCount = (int) $validated['room_count'];

                $adults = (int) $validated['adults'];

                $children = (int) (
                    $validated['children'] ?? 0
                );

                $totalRooms = (int) (
                    $room->total_rooms ?? 0
                );


                if ($totalRooms <= 0) {
                    abort(
                        422,
                        'No rooms are configured for this room.'
                    );
                }


                if ($roomCount > $totalRooms) {
                    abort(
                        422,
                        "Only {$totalRooms} room(s) are configured for this room."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Adult Capacity
                |--------------------------------------------------------------------------
                */

                $maxAdultPerRoom = (int) (
                    $room->max_adult ?? 0
                );

                if ($maxAdultPerRoom > 0) {

                    $maxAdults =
                        $maxAdultPerRoom * $roomCount;

                    if ($adults > $maxAdults) {
                        abort(
                            422,
                            "Maximum {$maxAdults} adult(s) allowed for selected rooms."
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Child Capacity
                |--------------------------------------------------------------------------
                */

                $maxChildPerRoom = (int) (
                    $room->max_child ?? 0
                );

                if ($maxChildPerRoom > 0) {

                    $maxChildren =
                        $maxChildPerRoom * $roomCount;

                    if ($children > $maxChildren) {
                        abort(
                            422,
                            "Maximum {$maxChildren} child(ren) allowed for selected rooms."
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Booking Dates
                |--------------------------------------------------------------------------
                */

                $checkIn = Carbon::parse(
                    $validated['check_in']
                )->startOfDay();

                $checkOut = Carbon::parse(
                    $validated['check_out']
                )->startOfDay();

                $totalNights = $checkIn->diffInDays(
                    $checkOut
                );

                if ($totalNights <= 0) {
                    abort(422, 'Invalid booking dates.');
                }


                /*
                |--------------------------------------------------------------------------
                | Generate Required Dates
                |--------------------------------------------------------------------------
                */

                $requiredDates = [];

                $currentDate = $checkIn->copy();

                while ($currentDate->lt($checkOut)) {

                    $requiredDates[] =
                        $currentDate->toDateString();

                    $currentDate->addDay();
                }


                /*
                |--------------------------------------------------------------------------
                | Lock Daily Availability Records
                |--------------------------------------------------------------------------
                */

                $availabilityRecords = collect();

                foreach ($requiredDates as $date) {

                    $availability = RoomAvailability::query()
                        ->where('room_id', $room->id)
                        ->whereDate('date', $date)
                        ->lockForUpdate()
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Auto Create Missing Availability
                    |--------------------------------------------------------------------------
                    */

                    if (!$availability) {

                        $bookedRoomsForDate = RoomBooking::query()
                            ->where('room_id', $room->id)
                            ->whereIn(
                                'booking_status',
                                [
                                    'pending',
                                    'confirmed',
                                    'checked_in',
                                ]
                            )
                            ->where(
                                'check_in',
                                '<=',
                                $date
                            )
                            ->where(
                                'check_out',
                                '>',
                                $date
                            )
                            ->sum('room_count');


                        $availabilityTotalRooms = $totalRooms;

                        $availabilityAvailableRooms = max(
                            0,
                            $availabilityTotalRooms -
                            (int) $bookedRoomsForDate
                        );


                        $availability = RoomAvailability::create([

                            'room_id' =>
                                $room->id,

                            'date' =>
                                $date,

                            'price' =>
                                null,

                            'total_rooms' =>
                                $availabilityTotalRooms,

                            'available_rooms' =>
                                $availabilityAvailableRooms,

                            'is_closed' =>
                                false,

                            'is_sold_out' =>
                                $availabilityAvailableRooms <= 0,
                        ]);


                        $availability = RoomAvailability::query()
                            ->where('id', $availability->id)
                            ->lockForUpdate()
                            ->first();
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Availability Validation
                    |--------------------------------------------------------------------------
                    */

                    if ((int) $availability->total_rooms <= 0) {
                        abort(
                            422,
                            "No rooms are configured in availability for {$date}."
                        );
                    }


                    if ((bool) $availability->is_closed) {
                        abort(
                            422,
                            "This room is closed on {$date}."
                        );
                    }


                    if ((bool) $availability->is_sold_out) {
                        abort(
                            422,
                            "This room is sold out on {$date}."
                        );
                    }


                    $availableRooms = (int) (
                        $availability->available_rooms ?? 0
                    );


                    if ($availableRooms < $roomCount) {
                        abort(
                            422,
                            "Only {$availableRooms} room(s) are available on {$date}."
                        );
                    }


                    $availabilityRecords->push(
                        $availability
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Room Prices
                |--------------------------------------------------------------------------
                */

                $lastOccupiedDate = $checkOut
                    ->copy()
                    ->subDay()
                    ->toDateString();


                $prices = $room->prices()
                    ->where(
                        'from_date',
                        '<=',
                        $lastOccupiedDate
                    )
                    ->where(
                        'to_date',
                        '>=',
                        $checkIn->toDateString()
                    )
                    ->orderBy('id')
                    ->get();


                if ($prices->isEmpty()) {
                    abort(
                        422,
                        'No room price is available for the selected dates.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Calculate Nightly Price
                |--------------------------------------------------------------------------
                */

                $nightlyPriceTotal = 0;

                $currentDate = $checkIn->copy();

                while ($currentDate->lt($checkOut)) {

                    $priceRecord = $prices
                        ->filter(function (
                            $price
                        ) use ($currentDate) {

                            $from = Carbon::parse(
                                $price->from_date
                            )->startOfDay();

                            $to = Carbon::parse(
                                $price->to_date
                            )->startOfDay();

                            return $currentDate->betweenIncluded(
                                $from,
                                $to
                            );
                        })
                        ->sortByDesc(function ($price) {

                            return match (
                                $price->type ?? null
                            ) {
                                'festival' => 5,
                                'holiday' => 4,
                                'seasonal' => 3,
                                'weekend' => 2,
                                default => 1,
                            };
                        })
                        ->first();


                    if (!$priceRecord) {
                        abort(
                            422,
                            'Room price is not available for ' .
                            $currentDate->format('Y-m-d') .
                            '.'
                        );
                    }


                    $nightPrice =
                        $priceRecord->discount_price !== null
                            ? (float) $priceRecord->discount_price
                            : (float) $priceRecord->price;


                    if ($nightPrice <= 0) {
                        abort(
                            422,
                            'Invalid room price for ' .
                            $currentDate->format('Y-m-d') .
                            '.'
                        );
                    }


                    $nightlyPriceTotal += $nightPrice;

                    $currentDate->addDay();
                }


                /*
                |--------------------------------------------------------------------------
                | Calculate Amounts
                |--------------------------------------------------------------------------
                */

                $roomPrice =
                    $totalNights > 0
                        ? $nightlyPriceTotal / $totalNights
                        : 0;


                $subtotal =
                    $nightlyPriceTotal *
                    $roomCount;


                $discount = min(
                    (float) (
                        $validated['discount'] ?? 0
                    ),
                    $subtotal
                );


                $tax = (float) (
                    $validated['tax'] ?? 0
                );


                $totalAmount = max(
                    0,
                    $subtotal - $discount + $tax
                );


                /*
                |--------------------------------------------------------------------------
                | Commission
                |--------------------------------------------------------------------------
                */

                $commissionRate = 10.00;

                $adminCommission = round(
                    $totalAmount *
                    ($commissionRate / 100),
                    2
                );

                $vendorEarning = round(
                    $totalAmount -
                    $adminCommission,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Generate Booking Code
                |--------------------------------------------------------------------------
                */

                do {

                    $bookingCode =
                        'RB-' .
                        now()->format('Ymd') .
                        '-' .
                        strtoupper(
                            Str::random(6)
                        );

                } while (
                    RoomBooking::where(
                        'booking_code',
                        $bookingCode
                    )->exists()
                );


                /*
                |--------------------------------------------------------------------------
                | Create Booking
                |--------------------------------------------------------------------------
                */

                $booking = RoomBooking::create([

                    'user_id' => $user->id,

                    'vendor_id' =>
                        $room->resort->vendor_id,

                    'resort_id' =>
                        $room->resort_id,

                    'room_id' =>
                        $room->id,

                    'booking_code' =>
                        $bookingCode,

                    'room_count' =>
                        $roomCount,

                    'check_in' =>
                        $checkIn->toDateString(),

                    'check_out' =>
                        $checkOut->toDateString(),

                    'total_nights' =>
                        $totalNights,

                    'adults' =>
                        $adults,

                    'children' =>
                        $children,

                    'room_price' =>
                        round($roomPrice, 2),

                    'subtotal' =>
                        round($subtotal, 2),

                    'discount' =>
                        round($discount, 2),

                    'tax' =>
                        round($tax, 2),

                    'total_amount' =>
                        round($totalAmount, 2),

                    'commission_rate' =>
                        $commissionRate,

                    'admin_commission' =>
                        $adminCommission,

                    'vendor_earning' =>
                        $vendorEarning,

                    'payment_status' =>
                        'pending',

                    'booking_status' =>
                        'pending',

                    'special_request' =>
                        $validated['special_request']
                        ?? null,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Create Guests
                |--------------------------------------------------------------------------
                */

                foreach (
                    $validated['guests']
                    as $guest
                ) {

                    RoomBookingGuest::create([

                        'room_booking_id' =>
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


                /*
                |--------------------------------------------------------------------------
                | Create Payment
                |--------------------------------------------------------------------------
                */

                $paymentData = [

                    'booking_code' =>
                        $booking->booking_code,

                    /*
                     * কোন vendor payment method ব্যবহার হয়েছে
                     */

                    'vendor_payment_method_id' =>
                        $paymentMethod->id,

                    'vendor_payment_method_name' =>
                        $paymentMethod->name,

                    'vendor_payment_method_type' =>
                        $paymentMethod->type,
                ];


                if (
                    !empty(
                        $validated['payment_phone']
                    )
                ) {

                    $paymentData['phone'] =
                        $validated['payment_phone'];
                }


                $paymentTrxId =
                    'PAY-' .
                    now()->format('YmdHis') .
                    '-' .
                    strtoupper(
                        Str::random(6)
                    );


                Payment::create([

                    'trx_id' =>
                        $paymentTrxId,

                    /*
                     * Payment table-এ type save হবে
                     * যেমন: bkash, nagad, bank, manual
                     */

                    'payment_method' =>
                        $paymentMethod->type,

                    'amount' =>
                        round($totalAmount, 2),

                    'status' =>
                        'pending',

                    'payment_data' =>
                        $paymentData,

                    'paid_at' =>
                        null,

                    'paymentable_id' =>
                        $booking->id,

                    'paymentable_type' =>
                        RoomBooking::class,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Update Room Availability
                |--------------------------------------------------------------------------
                */

                foreach (
                    $availabilityRecords
                    as $availability
                ) {

                    $currentAvailable =
                        (int) (
                            $availability->available_rooms
                            ?? 0
                        );


                    $newAvailableRooms =
                        $currentAvailable -
                        $roomCount;


                    if ($newAvailableRooms < 0) {
                        abort(
                            422,
                            'Room availability changed. Please try again.'
                        );
                    }


                    $availability->available_rooms =
                        $newAvailableRooms;


                    $availability->is_sold_out =
                        $newAvailableRooms <= 0;


                    $availability->save();
                }


                /*
                |--------------------------------------------------------------------------
                | Return Booking With Relations
                |--------------------------------------------------------------------------
                */

                return $booking->load([

                    'user',

                    'vendor',

                    'resort',

                    'room',

                    'guests',

                    'payments',
                ]);
            });


            return response()->json([

                'success' => true,

                'message' =>
                    'Room booking created successfully.',

                'data' =>
                    $booking,

            ], 201);


        } catch (ValidationException $e) {

            throw $e;

        } catch (HttpException $e) {

            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'success' => false,

                'message' =>
                    'Unable to create room booking.',

                'error' =>
                    config('app.debug')
                        ? $e->getMessage()
                        : null,

            ], 500);
        }
    }
}