<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * User Submit Payment
     */
    public function store(PaymentRequest $request)
    {
        $payment = $this->paymentService->submitPayment(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment submitted successfully',
            'data' => new PaymentResource($payment),
        ]);
    }

    /**
     * Admin Approve Payment
     */
    public function approve($id)
    {
        $payment = Payment::findOrFail($id);

        $this->paymentService->approvePayment($payment);

        return response()->json([
            'success' => true,
            'message' => 'Payment approved successfully',
        ]);
    }

    /**
     * Admin Reject Payment
     */
    public function reject($id)
    {
        $payment = Payment::findOrFail($id);

        $this->paymentService->rejectPayment($payment);

        return response()->json([
            'success' => true,
            'message' => 'Payment rejected',
        ]);
    }
}