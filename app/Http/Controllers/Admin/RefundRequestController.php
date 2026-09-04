<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REFUND REQUESTS
    |--------------------------------------------------------------------------
    */

    /**
     * Display all refund requests.
     */
    public function index(Request $request)
    {
        $query = RefundRequest::with([
            'booking.tour',
            'booking.tourDate',
            'user',
            'payment',
        ])->latest();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->whereHas('booking', function ($bookingQuery) use ($search) {

                    $bookingQuery->where(
                        'booking_code',
                        'like',
                        "%{$search}%"
                    );

                })

                ->orWhereHas('user', function ($userQuery) use ($search) {

                    $userQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $refundRequests = $query
            ->paginate(20)
            ->withQueryString();


        return view(
            'admin.bookings.refund-requests',
            compact('refundRequests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display refund request details.
     */
    public function show($id)
    {
        $refundRequest = RefundRequest::with([
            'booking.tour',
            'booking.tourDate',
            'booking.vendor',
            'booking.travelers',
            'user',
            'payment',
        ])->findOrFail($id);


        return view(
            'admin.bookings.refund-request-view',
            compact('refundRequest')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    /**
     * Approve a pending refund request.
     */
    public function approve(Request $request, $id)
    {
        $refundRequest = RefundRequest::with([
            'booking',
            'payment',
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Pending Request Can Be Approved
        |--------------------------------------------------------------------------
        */

        if ($refundRequest->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending refund requests can be approved.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Refund Amount
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'admin_note' => 'nullable|string|max:5000',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check Booking
        |--------------------------------------------------------------------------
        */

        $booking = $refundRequest->booking;

        if (!$booking) {

            return back()->with(
                'error',
                'The associated booking could not be found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Refund Amount Validation
        |--------------------------------------------------------------------------
        */

        if ($refundRequest->refund_amount <= 0) {

            return back()->with(
                'error',
                'Invalid refund amount.'
            );
        }


        if ($refundRequest->refund_amount > $booking->total_amount) {

            return back()->with(
                'error',
                'Refund amount cannot be greater than the booking total amount.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Approve
        |--------------------------------------------------------------------------
        */

        $refundRequest->update([
            'status' => 'approved',
            'admin_note' => $request->admin_note,
        ]);


        return back()->with(
            'success',
            'Refund request approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    /**
     * Reject a pending refund request.
     */
    public function reject(Request $request, $id)
    {
        $refundRequest = RefundRequest::with([
            'booking',
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Pending Request Can Be Rejected
        |--------------------------------------------------------------------------
        */

        if ($refundRequest->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending refund requests can be rejected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'admin_note' => 'required|string|max:5000',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Reject Request
        |--------------------------------------------------------------------------
        */

        $refundRequest->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'processed_at' => now(),
        ]);


        return back()->with(
            'success',
            'Refund request rejected successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE REFUND
    |--------------------------------------------------------------------------
    */

    /**
     * Mark an approved refund as completed.
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:5000',
        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Lock Refund Request
            |--------------------------------------------------------------------------
            */

            $refundRequest = RefundRequest::with([
                'booking',
                'payment',
            ])
                ->lockForUpdate()
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Only Approved Request Can Be Completed
            |--------------------------------------------------------------------------
            */

            if ($refundRequest->status !== 'approved') {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Only approved refund requests can be completed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::lockForUpdate()
                ->find($refundRequest->booking_id);


            if (!$booking) {

                DB::rollBack();

                return back()->with(
                    'error',
                    'The associated booking could not be found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Refund Amount Validation
            |--------------------------------------------------------------------------
            */

            if ($refundRequest->refund_amount <= 0) {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Invalid refund amount.'
                );
            }


            if ($refundRequest->refund_amount > $booking->total_amount) {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Refund amount cannot be greater than the booking total amount.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $payment = null;

            if ($refundRequest->payment_id) {

                $payment = Payment::lockForUpdate()
                    ->find($refundRequest->payment_id);
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            if ($payment) {

                /*
                | Do not refund an already refunded payment
                */

                if ($payment->status === 'refunded') {

                    DB::rollBack();

                    return back()->with(
                        'error',
                        'This payment has already been refunded.'
                    );
                }


                $payment->status = 'refunded';
                $payment->save();
            }


            /*
            |--------------------------------------------------------------------------
            | Booking Payment Status
            |--------------------------------------------------------------------------
            */

            $booking->payment_status = 'refunded';

            /*
            | Booking should remain cancelled.
            | Refund completion does not mean booking is completed.
            */

            if ($booking->booking_status !== 'cancelled') {

                $booking->booking_status = 'cancelled';
            }

            $booking->save();


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
                ->latest('id')
                ->first();


            if ($transaction) {

                $transaction->status = 'refunded';

                $transaction->note = trim(
                    ($transaction->note ? $transaction->note . "\n" : '') .
                    'Refund completed. Refund amount: ' .
                    number_format(
                        $refundRequest->refund_amount,
                        2
                    )
                );

                $transaction->save();
            }


            /*
            |--------------------------------------------------------------------------
            | Refund Request
            |--------------------------------------------------------------------------
            */

            $refundRequest->status = 'completed';

            if ($request->filled('admin_note')) {

                $refundRequest->admin_note = $request->admin_note;
            }

            $refundRequest->processed_at = now();

            $refundRequest->save();


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return back()->with(
                'success',
                'Refund completed successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()->with(
                'error',
                'Something went wrong while completing the refund.'
            );
        }
    }
}