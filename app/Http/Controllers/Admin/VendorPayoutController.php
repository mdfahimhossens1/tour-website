<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorPayout;
use App\Models\Commission;
use App\Models\RefundRequest;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorPayoutController extends Controller
{
    /**
     * ---------------------------------------------------------
     * All Vendor Payouts
     * ---------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = VendorPayout::with([
            'vendor',
            'commission',
            'booking',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'payout_code',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'reference_id',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('booking', function ($booking) use ($search) {

                    $booking->where(
                        'booking_code',
                        'like',
                        "%{$search}%"
                    );
                })

                ->orWhereHas('vendor', function ($vendor) use ($search) {

                    $vendor->where(
                        'business_name',
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
        | Payment Method
        |--------------------------------------------------------------------------
        */
        if ($request->filled('payment_method')) {

            $query->where(
                'payment_method',
                $request->payment_method
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
        $payouts = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $pendingAmount = VendorPayout::where(
            'status',
            'pending'
        )->sum('amount');

        $processingAmount = VendorPayout::where(
            'status',
            'processing'
        )->sum('amount');

        $completedAmount = VendorPayout::where(
            'status',
            'completed'
        )->sum('amount');

        $thisMonthAmount = VendorPayout::where(
            'status',
            'completed'
        )
            ->whereMonth(
                'paid_at',
                now()->month
            )
            ->whereYear(
                'paid_at',
                now()->year
            )
            ->sum('amount');

        $totalVendorsPaid = VendorPayout::where(
            'status',
            'completed'
        )
            ->distinct('vendor_id')
            ->count('vendor_id');

        return view(
            'admin.vendor-payouts.index',
            compact(
                'payouts',
                'pendingAmount',
                'processingAmount',
                'completedAmount',
                'thisMonthAmount',
                'totalVendorsPaid'
            )
        );
    }


    /**
     * ---------------------------------------------------------
     * Pending Payouts
     * ---------------------------------------------------------
     */
    public function pending(Request $request)
    {
        $request->merge([
            'status' => 'pending',
        ]);

        return $this->index($request);
    }


    /**
     * ---------------------------------------------------------
     * Processing Payouts
     * ---------------------------------------------------------
     */
    public function processing(Request $request)
    {
        $request->merge([
            'status' => 'processing',
        ]);

        return $this->index($request);
    }


    /**
     * ---------------------------------------------------------
     * Completed Payouts
     * ---------------------------------------------------------
     */
    public function completed(Request $request)
    {
        $request->merge([
            'status' => 'completed',
        ]);

        return $this->index($request);
    }


    /**
     * ---------------------------------------------------------
     * Failed Payouts
     * ---------------------------------------------------------
     */
    public function failed(Request $request)
    {
        $request->merge([
            'status' => 'failed',
        ]);

        return $this->index($request);
    }


    /**
     * ---------------------------------------------------------
     * Rejected Payouts
     * ---------------------------------------------------------
     */
    public function rejected(Request $request)
    {
        $request->merge([
            'status' => 'rejected',
        ]);

        return $this->index($request);
    }


    /**
     * ---------------------------------------------------------
     * Show Payout Details
     * ---------------------------------------------------------
     */
    public function show($id)
    {
        $payout = VendorPayout::with([
            'vendor',
            'commission',
            'booking.tour',
            'booking.tourDate',
        ])->findOrFail($id);

        return view(
            'admin.vendor-payouts.show',
            compact('payout')
        );
    }


    /**
     * ---------------------------------------------------------
     * Create Payout From Commission
     * ---------------------------------------------------------
     */
    public function create($commissionId)
    {
        $commission = Commission::with([
            'booking.vendor',
            'booking.tour',
            'booking.tourDate',
        ])->findOrFail($commissionId);

        $booking = $commission->booking;


        /*
        |--------------------------------------------------------------------------
        | Booking must exist
        |--------------------------------------------------------------------------
        */
        if (!$booking) {

            return back()->with(
                'error',
                'Related booking was not found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vendor must exist
        |--------------------------------------------------------------------------
        */
        if (!$booking->vendor_id) {

            return back()->with(
                'error',
                'This booking does not have a vendor.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Only completed bookings can generate payout
        |--------------------------------------------------------------------------
        */
        if ($booking->booking_status !== 'completed') {

            return back()->with(
                'error',
                'Vendor payout can only be generated after booking completion.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment must be paid
        |--------------------------------------------------------------------------
        */
        if ($booking->payment_status !== 'paid') {

            return back()->with(
                'error',
                'Vendor payout cannot be generated because the booking payment is not paid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vendor earning must be greater than zero
        |--------------------------------------------------------------------------
        */
        $vendorEarning = round(
            (float) $commission->vendor_earning,
            2
        );

        if ($vendorEarning <= 0) {

            return back()->with(
                'error',
                'Vendor earning amount is not available for payout.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check completed refund
        |--------------------------------------------------------------------------
        */
        $hasCompletedRefund = RefundRequest::where(
            'booking_id',
            $booking->id
        )
            ->where(
                'status',
                'completed'
            )
            ->exists();

        if ($hasCompletedRefund) {

            return back()->with(
                'error',
                'Payout cannot be generated for a refunded booking.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate payout
        |--------------------------------------------------------------------------
        */
        $existingPayout = VendorPayout::where(
            'commission_id',
            $commission->id
        )
            ->whereIn(
                'status',
                [
                    'pending',
                    'processing',
                    'completed',
                ]
            )
            ->exists();

        if ($existingPayout) {

            return back()->with(
                'error',
                'A payout already exists for this commission.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Vendor Payout Tax
        |--------------------------------------------------------------------------
        |
        | Vendor earning is the taxable base.
        |
        | Example:
        |
        | Vendor earning = 10,000
        | Tax            = 500
        | Net payout     = 9,500
        |
        */
        $taxService = app(
            TaxService::class
        );

        $taxCalculation = $taxService
            ->calculateForVendorPayout(
                $vendorEarning
            );


        /*
        |--------------------------------------------------------------------------
        | Tax Amount
        |--------------------------------------------------------------------------
        */
        $taxAmount = round(
            (float) $taxCalculation['tax_amount'],
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Final Net Vendor Payout
        |--------------------------------------------------------------------------
        */
        $netPayoutAmount = round(
            max(
                0,
                $vendorEarning - $taxAmount
            ),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */
        if ($netPayoutAmount <= 0) {

            return back()->with(
                'error',
                'Vendor payout amount becomes zero after tax calculation.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Payout
        |--------------------------------------------------------------------------
        */
        $payout = VendorPayout::create([

            'vendor_id' => $booking->vendor_id,

            'commission_id' => $commission->id,

            'booking_id' => $booking->id,

            'payout_code' => 'PO-' . strtoupper(
                Str::random(10)
            ),

            /*
            |--------------------------------------------------------------------------
            | Final amount vendor will receive
            |--------------------------------------------------------------------------
            */
            'amount' => $netPayoutAmount,

            /*
            |--------------------------------------------------------------------------
            | Total tax deducted from vendor earning
            |--------------------------------------------------------------------------
            */
            'tax_amount' => $taxAmount,

            'status' => 'pending',
        ]);


        return redirect()
            ->route(
                'admin.vendor-payouts.show',
                $payout->id
            )
            ->with(
                'success',
                'Vendor payout created successfully.'
            );
    }


    /**
     * ---------------------------------------------------------
     * Move Pending -> Processing
     * ---------------------------------------------------------
     */
    public function process($id)
    {
        $payout = VendorPayout::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */
        if ($payout->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending payouts can be moved to processing.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Safety check for refunded booking
        |--------------------------------------------------------------------------
        */
        if ($payout->booking_id) {

            $hasCompletedRefund = RefundRequest::where(
                'booking_id',
                $payout->booking_id
            )
                ->where(
                    'status',
                    'completed'
                )
                ->exists();

            if ($hasCompletedRefund) {

                return back()->with(
                    'error',
                    'This booking has already been refunded.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Move To Processing
        |--------------------------------------------------------------------------
        */
        $payout->update([
            'status' => 'processing',

            'processed_at' => now(),
        ]);


        return back()->with(
            'success',
            'Payout moved to processing.'
        );
    }


    /**
     * ---------------------------------------------------------
     * Complete / Pay Payout
     * ---------------------------------------------------------
     */
    public function pay(
        Request $request,
        $id
    ) {

        $request->validate([

            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'reference_id' => [
                'required',
                'string',
                'max:191',
            ],

            'admin_note' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        DB::transaction(function () use (
            $request,
            $id
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Payout
            |--------------------------------------------------------------------------
            */
            $payout = VendorPayout::lockForUpdate()
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Status Check
            |--------------------------------------------------------------------------
            */
            if (!in_array(
                $payout->status,
                [
                    'pending',
                    'processing',
                ]
            )) {

                throw new \Exception(
                    'This payout cannot be completed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Check booking refund again
            |--------------------------------------------------------------------------
            */
            if ($payout->booking_id) {

                $hasCompletedRefund = RefundRequest::where(
                    'booking_id',
                    $payout->booking_id
                )
                    ->where(
                        'status',
                        'completed'
                    )
                    ->exists();

                if ($hasCompletedRefund) {

                    throw new \Exception(
                        'This booking has already been refunded.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Complete Payout
            |--------------------------------------------------------------------------
            */
            $payout->update([

                'status' => 'completed',

                'payment_method' => $request->payment_method,

                'reference_id' => $request->reference_id,

                'admin_note' => $request->admin_note,

                'paid_at' => now(),

                'processed_at' =>
                    $payout->processed_at ?? now(),
            ]);
        });


        return back()->with(
            'success',
            'Vendor payout completed successfully.'
        );
    }


    /**
     * ---------------------------------------------------------
     * Reject Payout
     * ---------------------------------------------------------
     */
    public function reject(
        Request $request,
        $id
    ) {

        $request->validate([

            'admin_note' => [
                'required',
                'string',
                'max:2000',
            ],

        ]);


        $payout = VendorPayout::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */
        if (!in_array(
            $payout->status,
            [
                'pending',
                'processing',
            ]
        )) {

            return back()->with(
                'error',
                'This payout cannot be rejected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Reject
        |--------------------------------------------------------------------------
        */
        $payout->update([

            'status' => 'rejected',

            'admin_note' => $request->admin_note,

        ]);


        return back()->with(
            'success',
            'Vendor payout rejected.'
        );
    }


    /**
     * ---------------------------------------------------------
     * Mark Payout As Failed
     * ---------------------------------------------------------
     */
    public function fail(
        Request $request,
        $id
    ) {

        $request->validate([

            'admin_note' => [
                'required',
                'string',
                'max:2000',
            ],

        ]);


        $payout = VendorPayout::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */
        if ($payout->status !== 'processing') {

            return back()->with(
                'error',
                'Only processing payouts can be marked as failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Mark As Failed
        |--------------------------------------------------------------------------
        */
        $payout->update([

            'status' => 'failed',

            'admin_note' => $request->admin_note,

        ]);


        return back()->with(
            'success',
            'Vendor payout marked as failed.'
        );
    }
}