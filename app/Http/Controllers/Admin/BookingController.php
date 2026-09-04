<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\Coupon;
use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\RefundRequest;
use App\Services\CommissionService;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ALL BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function all(Request $request)
    {
        $query = Booking::with([
            'user',
            'tour',
            'tourDate',
            'vendor',
            'payments',
        ])->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_code',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('user', function ($userQuery) use ($search) {

                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");

                })

                ->orWhereHas('tour', function ($tourQuery) use ($search) {

                    $tourQuery->where(
                        'title',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_status')) {

            $query->where(
                'booking_status',
                $request->booking_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Vendor Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('vendor_id')) {

            $query->where(
                'vendor_id',
                $request->vendor_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Vendors
        |--------------------------------------------------------------------------
        */

        $vendors = Vendor::orderBy('business_name')
            ->get();

        return view(
            'admin.bookings.index',
            compact(
                'bookings',
                'vendors'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE BOOKING
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'tour_date_id' => 'required|exists:tour_dates,id',
            'person_count' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string|max:100',
            'special_request' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {

            $user = auth()->user();

            /*
            |--------------------------------------------------------------------------
            | Tour
            |--------------------------------------------------------------------------
            */

            $tour = Tour::findOrFail(
                $request->tour_id
            );

            /*
            |--------------------------------------------------------------------------
            | Tour Date
            |--------------------------------------------------------------------------
            */

            $tourDate = TourDate::where('id', $request->tour_date_id)
                ->where('tour_id', $tour->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Booking
            |--------------------------------------------------------------------------
            |
            | Same user cannot book same tour again while
            | pending or confirmed.
            |
            */

            $alreadyBooked = Booking::where('user_id', $user->id)
                ->where('tour_id', $tour->id)
                ->whereIn('booking_status', [
                    'pending',
                    'confirmed',
                ])
                ->exists();

            if ($alreadyBooked) {

                return response()->json([
                    'success' => false,
                    'message' => 'You have already booked this tour.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Person Count
            |--------------------------------------------------------------------------
            */

            $persons = (int) $request->person_count;

            if ($persons <= 0) {

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid person count.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Seat Check
            |--------------------------------------------------------------------------
            */

            if ($tourDate->available_seat < $persons) {

                return response()->json([
                    'success' => false,
                    'message' => 'Not enough seats available.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            $unitPrice = (
                $tourDate->special_price !== null &&
                (float) $tourDate->special_price > 0
            )
                ? (float) $tourDate->special_price
                : (float) $tour->price;

            $subtotal = round(
                $unitPrice * $persons,
                2
            );

            /*
            |--------------------------------------------------------------------------
            | Default Pricing
            |--------------------------------------------------------------------------
            */

            $discount = 0;

            $couponCode = null;

            /*
            |--------------------------------------------------------------------------
            | Coupon
            |--------------------------------------------------------------------------
            */

            if ($request->filled('coupon_code')) {

                $coupon = Coupon::where(
                    'code',
                    strtoupper(trim($request->coupon_code))
                )
                    ->where('status', 1)
                    ->lockForUpdate()
                    ->first();

                if ($coupon) {

                    $valid = true;

                    /*
                    |--------------------------------------------------------------------------
                    | Start Date
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->start_date &&
                        now()->lt($coupon->start_date)
                    ) {

                        $valid = false;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | End Date
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->end_date &&
                        now()->gt($coupon->end_date)
                    ) {

                        $valid = false;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Maximum Usage
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->max_usage &&
                        $coupon->used_count >= $coupon->max_usage
                    ) {

                        $valid = false;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Discount
                    |--------------------------------------------------------------------------
                    */

                    if ($valid) {

                        if (
                            $coupon->type === 'percentage' ||
                            $coupon->type === 'percent'
                        ) {

                            $discount =
                                ($subtotal * (float) $coupon->value) / 100;

                        } else {

                            $discount =
                                (float) $coupon->value;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Discount Cannot Exceed Subtotal
                        |--------------------------------------------------------------------------
                        */

                        $discount = min(
                            round($discount, 2),
                            $subtotal
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Increment Coupon Usage
                        |--------------------------------------------------------------------------
                        */

                        $coupon->increment(
                            'used_count'
                        );

                        $couponCode = $coupon->code;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Tax Calculation
            |--------------------------------------------------------------------------
            |
            | Tax is calculated AFTER coupon discount.
            |
            | Example:
            |
            | Subtotal       = 10,000
            | Discount       = 1,000
            | Taxable Amount = 9,000
            |
            */

            $taxableAmount = max(
                0,
                round(
                    $subtotal - $discount,
                    2
                )
            );

            /*
            |--------------------------------------------------------------------------
            | Calculate Tax
            |--------------------------------------------------------------------------
            */

            $taxService = app(
                TaxService::class
            );

            $taxCalculation = $taxService->calculateForBooking(
                $taxableAmount
            );

            /*
            |--------------------------------------------------------------------------
            | Tax Amount
            |--------------------------------------------------------------------------
            */

            $taxAmount = round(
                $taxCalculation['tax_amount'],
                2
            );

            /*
            |--------------------------------------------------------------------------
            | Final Booking Total
            |--------------------------------------------------------------------------
            |
            | Tax is included in the final amount.
            |
            */

            $total = round(
                $taxCalculation['total_amount'],
                2
            );

            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            |
            | Vendor is NOT required for booking.
            |
            | If tour has vendor, vendor_id is stored.
            |
            */

            $vendorId = $tour->vendor_id ?? null;

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::create([

                'user_id' =>
                    $user->id,

                'vendor_id' =>
                    $vendorId,

                'tour_id' =>
                    $tour->id,

                'tour_date_id' =>
                    $tourDate->id,

                'booking_code' =>
                    'BK-' . strtoupper(
                        Str::random(8)
                    ),

                'person_count' =>
                    $persons,

                'unit_price' =>
                    $unitPrice,

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'coupon_code' =>
                    $couponCode,

                'tax_amount' =>
                    $taxAmount,

                'total_amount' =>
                    $total,

                'payment_status' =>
                    'pending',

                'booking_status' =>
                    'pending',

                'special_request' =>
                    $request->special_request,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reduce Available Seats
            |--------------------------------------------------------------------------
            */

            $tourDate->decrement(
                'available_seat',
                $persons
            );

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            |
            | Payment amount includes tax.
            |
            */

            $booking->payments()->create([

                'trx_id' =>
                    'PAY-' . strtoupper(
                        Str::random(16)
                    ),

                'payment_method' =>
                    null,

                'amount' =>
                    $total,

                'status' =>
                    'pending',

                'paid_at' =>
                    null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Transaction
            |--------------------------------------------------------------------------
            |
            | Transaction amount includes tax.
            |
            */

            Transaction::create([

                'user_id' =>
                    $booking->user_id,

                'booking_id' =>
                    $booking->id,

                'transaction_id' =>
                    'TXN-' . strtoupper(
                        Str::random(16)
                    ),

                'payment_method' =>
                    null,

                'amount' =>
                    $total,

                'status' =>
                    'pending',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Booking created successfully.',

                'booking' =>
                    new BookingResource(
                        $booking->load([
                            'user',
                            'tour',
                            'tourDate',
                            'payments',
                        ])
                    ),

            ], 201);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | PENDING BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function pending()
    {
        $bookings = Booking::with([
            'user',
            'tour',
            'tourDate',
            'payments',
        ])
            ->where(
                'booking_status',
                'pending'
            )
            ->latest()
            ->get();

        return view(
            'admin.bookings.pending',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMED BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function confirmed()
    {
        $bookings = Booking::with([
            'user',
            'tour',
            'tourDate',
            'payments',
        ])
            ->where(
                'booking_status',
                'confirmed'
            )
            ->latest()
            ->get();

        return view(
            'admin.bookings.confirmed',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW BOOKING
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'tour',
            'tourDate',
            'vendor',
            'commission',
            'transaction',
            'payments',
        ])
            ->findOrFail($id);

        return view(
            'admin.bookings.view',
            compact('booking')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRM BOOKING
    |--------------------------------------------------------------------------
    */

    public function confirm($id)
    {
        return DB::transaction(function () use ($id) {

            $booking = Booking::with([
                'tour',
                'user',
                'vendor',
                'payments',
            ])
                ->lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Already Confirmed
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'confirmed'
            ) {

                return back()->with(
                    'error',
                    'This booking is already confirmed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Cancelled
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'cancelled'
            ) {

                return back()->with(
                    'error',
                    'Cancelled booking cannot be confirmed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Vendor / Commission
            |--------------------------------------------------------------------------
            |
            | Vendor is optional.
            |
            | IMPORTANT:
            | Tax is NOT included in commission calculation.
            |
            */

            $vendor = $booking->vendor;

            if ($vendor) {

                $commissionRate =
                    (float) (
                        $vendor->commission_rate ?? 0
                    );

                /*
                |--------------------------------------------------------------------------
                | Validate Commission Rate
                |--------------------------------------------------------------------------
                */

                if (
                    $commissionRate < 0 ||
                    $commissionRate > 100
                ) {

                    return back()->with(
                        'error',
                        'Invalid vendor commission rate.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Create Commission
                |--------------------------------------------------------------------------
                */

                if (
                    !$booking->commission()->exists()
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Commission Base Amount
                    |--------------------------------------------------------------------------
                    |
                    | Customer pays:
                    |
                    | Booking Total = Service Amount + Tax
                    |
                    | But vendor/admin commission is calculated
                    | only from service amount.
                    |
                    */

                    $commissionBaseAmount = max(
                        0,
                        round(
                            (float) $booking->total_amount
                            - (float) ($booking->tax_amount ?? 0),
                            2
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Commission
                    |--------------------------------------------------------------------------
                    */

                    $calculation =
                        CommissionService::calculate(
                            $commissionBaseAmount,
                            $commissionRate
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Create Commission
                    |--------------------------------------------------------------------------
                    */

                    Commission::create([

                        'booking_id' =>
                            $booking->id,

                        'total_amount' =>
                            $commissionBaseAmount,

                        'commission_rate' =>
                            $commissionRate,

                        'admin_earning' =>
                            $calculation['admin'],

                        'vendor_earning' =>
                            $calculation['vendor'],

                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $payment = $booking->payments()
                ->latest()
                ->lockForUpdate()
                ->first();

            if ($payment) {

                $payment->update([

                    'status' =>
                        'paid',

                    /*
                    |--------------------------------------------------------------------------
                    | Never replace existing payment method
                    |--------------------------------------------------------------------------
                    */

                    'payment_method' =>
                        $payment->payment_method
                        ?? 'manual',

                    'paid_at' =>
                        $payment->paid_at
                        ?? now(),

                    /*
                    |--------------------------------------------------------------------------
                    | Make sure trx_id exists
                    |--------------------------------------------------------------------------
                    */

                    'trx_id' =>
                        $payment->trx_id
                        ?? 'PAY-' . strtoupper(
                            Str::random(16)
                        ),
                ]);

            } else {

                /*
                |--------------------------------------------------------------------------
                | Create Payment If Missing
                |--------------------------------------------------------------------------
                */

                $payment =
                    $booking->payments()->create([

                        'trx_id' =>
                            'PAY-' . strtoupper(
                                Str::random(16)
                            ),

                        'payment_method' =>
                            'manual',

                        'amount' =>
                            $booking->total_amount,

                        'status' =>
                            'paid',

                        'paid_at' =>
                            now(),
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            $transaction = Transaction::where(
                'booking_id',
                $booking->id
            )
                ->lockForUpdate()
                ->first();

            if ($transaction) {

                $transaction->update([

                    'status' =>
                        'success',

                    'paid_at' =>
                        $transaction->paid_at
                        ?? now(),

                    'payment_method' =>
                        $payment->payment_method
                        ?? 'manual',
                ]);

            } else {

                Transaction::create([

                    'user_id' =>
                        $booking->user_id,

                    'booking_id' =>
                        $booking->id,

                    'transaction_id' =>
                        'TXN-' . strtoupper(
                            Str::random(16)
                        ),

                    'payment_method' =>
                        $payment->payment_method
                        ?? 'manual',

                    'amount' =>
                        $booking->total_amount,

                    'status' =>
                        'success',

                    'paid_at' =>
                        now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Confirm Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'booking_status' =>
                    'confirmed',

                'payment_status' =>
                    'paid',
            ]);

            return back()->with(
                'success',
                'Booking confirmed successfully.'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESSING BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function processing()
    {
        $bookings = Booking::with([
            'user',
            'tour',
            'tourDate',
            'vendor',
            'payments',
        ])
            ->where(
                'booking_status',
                'processing'
            )
            ->latest()
            ->get();

        return view(
            'admin.bookings.processing',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MOVE BOOKING TO PROCESSING
    |--------------------------------------------------------------------------
    */

    public function moveToProcessing($id)
    {
        return DB::transaction(function () use ($id) {

            $booking = Booking::lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Already Processing
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'processing'
            ) {

                return back()->with(
                    'error',
                    'This booking is already processing.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Only Pending Booking
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status !== 'pending'
            ) {

                return back()->with(
                    'error',
                    'Only pending bookings can be moved to processing.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([
                'booking_status' =>
                    'processing',
            ]);

            return back()->with(
                'success',
                'Booking moved to processing successfully.'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETED BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function completed()
    {
        $bookings = Booking::with([
            'user',
            'tour',
            'tourDate',
            'vendor',
            'payments',
            'transaction',
            'commission',
        ])
            ->where(
                'booking_status',
                'completed'
            )
            ->latest()
            ->get();

        return view(
            'admin.bookings.completed',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MOVE BOOKING TO COMPLETED
    |--------------------------------------------------------------------------
    */

    public function complete($id)
    {
        return DB::transaction(function () use ($id) {

            $booking = Booking::lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Already Completed
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'completed'
            ) {

                return back()->with(
                    'error',
                    'This booking is already completed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Cancelled Booking
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'cancelled'
            ) {

                return back()->with(
                    'error',
                    'Cancelled booking cannot be completed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Only Confirmed / Processing
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $booking->booking_status,
                    [
                        'confirmed',
                        'processing',
                    ]
                )
            ) {

                return back()->with(
                    'error',
                    'Only confirmed or processing bookings can be completed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Complete Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'booking_status' =>
                    'completed',
            ]);

            return back()->with(
                'success',
                'Booking completed successfully.'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | CANCELLED BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function cancelled()
    {
        $bookings = Booking::with([
            'user',
            'tour',
            'tourDate',
            'vendor',
            'payments',
            'transaction',
        ])
            ->where(
                'booking_status',
                'cancelled'
            )
            ->latest()
            ->get();

        return view(
            'admin.bookings.cancelled',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL BOOKING
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {

            /*
            |--------------------------------------------------------------------------
            | Lock Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::with([
                'tourDate',
                'payments',
            ])
                ->lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Already Cancelled
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'cancelled'
            ) {

                return back()->with(
                    'error',
                    'This booking is already cancelled.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Completed Booking
            |--------------------------------------------------------------------------
            */

            if (
                $booking->booking_status === 'completed'
            ) {

                return back()->with(
                    'error',
                    'Completed booking cannot be cancelled.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Check Paid Booking
            |--------------------------------------------------------------------------
            */

            $isPaid =
                $booking->payment_status === 'paid';

            /*
            |--------------------------------------------------------------------------
            | Restore Seats
            |--------------------------------------------------------------------------
            */

            $tourDate = TourDate::lockForUpdate()
                ->find($booking->tour_date_id);

            if ($tourDate) {

                $tourDate->increment(
                    'available_seat',
                    $booking->person_count
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Restore Coupon Usage
            |--------------------------------------------------------------------------
            */

            if ($booking->coupon_code) {

                $coupon = Coupon::where(
                    'code',
                    $booking->coupon_code
                )
                    ->lockForUpdate()
                    ->first();

                if (
                    $coupon &&
                    $coupon->used_count > 0
                ) {

                    $coupon->decrement(
                        'used_count'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CANCEL PAID BOOKING
            |--------------------------------------------------------------------------
            |
            | Paid booking:
            |
            | booking_status = cancelled
            | payment_status = paid
            |
            | Then create refund request.
            |
            */

            if ($isPaid) {

                $booking->update([

                    'booking_status' =>
                        'cancelled',

                    'payment_status' =>
                        'paid',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Find Paid Payment
                |--------------------------------------------------------------------------
                */

                $payment = $booking->payments()
                    ->where(
                        'status',
                        'paid'
                    )
                    ->latest()
                    ->lockForUpdate()
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Refund Request
                |--------------------------------------------------------------------------
                */

                $existingRefund =
                    RefundRequest::where(
                        'booking_id',
                        $booking->id
                    )
                        ->whereIn(
                            'status',
                            [
                                'pending',
                                'approved',
                                'completed',
                            ]
                        )
                        ->lockForUpdate()
                        ->first();

                /*
                |--------------------------------------------------------------------------
                | Create Refund Request
                |--------------------------------------------------------------------------
                */

                if (!$existingRefund) {

                    RefundRequest::create([

                        'booking_id' =>
                            $booking->id,

                        'user_id' =>
                            $booking->user_id,

                        'payment_id' =>
                            $payment?->id,

                        /*
                        |--------------------------------------------------------------------------
                        | Refund includes tax because customer
                        | paid the tax as part of total amount.
                        |--------------------------------------------------------------------------
                        */

                        'refund_amount' =>
                            $booking->total_amount,

                        'reason' =>
                            'Booking cancelled by admin.',

                        'status' =>
                            'pending',

                        'admin_note' =>
                            null,

                        'requested_at' =>
                            now(),

                        'processed_at' =>
                            null,
                    ]);
                }

                return back()->with(
                    'success',
                    'Booking cancelled successfully. A refund request has been created.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CANCEL UNPAID BOOKING
            |--------------------------------------------------------------------------
            |
            | Pending / unpaid booking:
            |
            | booking_status = cancelled
            | payment_status = failed
            |
            */

            $booking->update([

                'booking_status' =>
                    'cancelled',

                'payment_status' =>
                    'failed',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Mark Pending Payments Failed
            |--------------------------------------------------------------------------
            */

            $booking->payments()
                ->where(
                    'status',
                    'pending'
                )
                ->update([

                    'status' =>
                        'failed',
                ]);

            /*
            |--------------------------------------------------------------------------
            | Mark Pending Transaction Failed
            |--------------------------------------------------------------------------
            */

            Transaction::where(
                'booking_id',
                $booking->id
            )
                ->where(
                    'status',
                    'pending'
                )
                ->update([

                    'status' =>
                        'failed',
                ]);

            return back()->with(
                'success',
                'Booking cancelled successfully.'
            );
        });
    }
}