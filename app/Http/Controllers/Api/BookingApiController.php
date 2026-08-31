<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;

class BookingApiController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Create Booking
     */
    public function store(BookingRequest $request)
    {
        try {

            $result = $this->bookingService->create(
                $request->validated(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully.',
                'data' => new BookingResource(
                    $result['booking']->load([
                        'tour',
                        'tourDate',
                        'transaction',
                        'payments',
                    ])
                ),
                'discount' => $result['discount'],
                'coupon' => $result['coupon'],
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        }
    }
}