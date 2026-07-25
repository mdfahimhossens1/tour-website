<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Booking;
use App\Models\ResortBooking;

class PaymentController extends Controller
{
    /**
     * Payment Page
     */
    public function create(Request $request)
    {
        $paymentableType = $request->paymentable_type;
        $paymentableId   = $request->paymentable_id;

        if ($paymentableType == 'booking') {
            $booking = Booking::findOrFail($paymentableId);

            $amount = $booking->total_amount;
        } else {

            $booking = ResortBooking::findOrFail($paymentableId);

            $amount = $booking->total_amount;
        }

        $methods = PaymentMethod::where('status',1)->get();

        return view('admin.payments.create', compact(
            'booking',
            'amount',
            'methods',
            'paymentableType'
        ));
    }

    /**
     * Save Payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'paymentable_type' => 'required',
            'paymentable_id'   => 'required',
            'payment_method'   => 'required',
        ]);

        if ($request->paymentable_type == 'booking') {

            $booking = Booking::findOrFail($request->paymentable_id);

        } else {

            $booking = ResortBooking::findOrFail($request->paymentable_id);

        }

        $payment = new Payment();

        $payment->trx_id = strtoupper(Str::random(12));

        $payment->payment_method = $request->payment_method;

        $payment->amount = $booking->total_amount;

        $payment->status = 'pending';

        $payment->booking_id = $booking->id;

        $booking->payments()->save($payment);

        return redirect()
            ->route('admin.payments.show',$payment->id)
            ->with('success','Payment Created Successfully');
    }

    /**
     * Payment Details
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin.payments.show',compact('payment'));
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,paid,failed'
    ]);

    $payment = Payment::findOrFail($id);

    $payment->status = $request->status;

    if ($request->status == 'paid') {
        $payment->paid_at = now();
    }

    $payment->save();

    // Update Booking Payment Status
    $payment->paymentable->update([
        'payment_status' => $request->status
    ]);

    return back()->with('success','Payment Updated Successfully');
}
}