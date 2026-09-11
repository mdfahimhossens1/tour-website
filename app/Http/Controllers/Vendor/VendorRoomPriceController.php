<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class VendorRoomPriceController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display prices of a room
     * ----------------------------------------------------------
     */
    public function index($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            }
        )
        ->with([
            'resort',
            'roomType',
        ])
        ->where(
            'slug',
            $room
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Room Prices
        |--------------------------------------------------------------------------
        */

        $prices = RoomPrice::where(
            'room_id',
            $room->id
        )
        ->orderBy('from_date')
        ->orderBy('to_date')
        ->get();


        return view(
            'vendor.room-prices.index',
            compact(
                'room',
                'prices'
            )
        );
    }


    /**
     * ----------------------------------------------------------
     * Show create price form
     * ----------------------------------------------------------
     */
    public function create($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            }
        )
        ->with([
            'resort',
            'roomType',
        ])
        ->where(
            'slug',
            $room
        )
        ->firstOrFail();


        return view(
            'vendor.room-prices.create',
            compact('room')
        );
    }


    /**
     * ----------------------------------------------------------
     * Store new room price
     * ----------------------------------------------------------
     */
    public function store(
        Request $request,
        $room
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            }
        )
        ->where(
            'slug',
            $room
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'from_date' => [
                'required',
                'date',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_type' => [
                'nullable',
                'in:percentage,amount',
            ],

            'discount_value' => [
                'nullable',
                'required_with:discount_type',
                'numeric',
                'min:0',
            ],

            'type' => [
                'required',
                'in:normal,weekend,holiday,festival,seasonal',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Percentage Discount Validation
        |--------------------------------------------------------------------------
        */

        if (
            $request->discount_type === 'percentage' &&
            (float) $request->discount_value > 100
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot be more than 100%.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Fixed Amount Discount Validation
        |--------------------------------------------------------------------------
        */

        if (
            $request->discount_type === 'amount' &&
            (float) $request->discount_value > (float) $request->price
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Fixed amount discount cannot be greater than the regular price.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Room ID
        |--------------------------------------------------------------------------
        */

        $validated['room_id'] = $room->id;


        /*
        |--------------------------------------------------------------------------
        | Clean Discount Data
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['discount_type'])
        ) {

            $validated['discount_type'] = null;
            $validated['discount_value'] = null;

        }


        /*
        |--------------------------------------------------------------------------
        | Create Price
        |--------------------------------------------------------------------------
        */

        RoomPrice::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'vendor.room-prices.index',
                [
                    'room' => $room->slug,
                ]
            )
            ->with(
                'success',
                'Room price added successfully.'
            );
    }


    /**
     * ----------------------------------------------------------
     * Show edit price form
     * ----------------------------------------------------------
     */
    public function edit(
        $room,
        $price
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            }
        )
        ->with([
            'resort',
            'roomType',
        ])
        ->where(
            'slug',
            $room
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Price Belongs To This Room
        |--------------------------------------------------------------------------
        */

        $price = RoomPrice::where(
            'room_id',
            $room->id
        )
        ->findOrFail($price);


        return view(
            'vendor.room-prices.edit',
            compact(
                'room',
                'price'
            )
        );
    }


    /**
     * ----------------------------------------------------------
     * Update room price
     * ----------------------------------------------------------
     */
    public function update(
        Request $request,
        $room,
        $price
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            }
        )
        ->where(
            'slug',
            $room
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Price Belongs To This Room
        |--------------------------------------------------------------------------
        */

        $price = RoomPrice::where(
            'room_id',
            $room->id
        )
        ->findOrFail($price);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'from_date' => [
                'required',
                'date',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_type' => [
                'nullable',
                'in:percentage,amount',
            ],

            'discount_value' => [
                'nullable',
                'required_with:discount_type',
                'numeric',
                'min:0',
            ],

            'type' => [
                'required',
                'in:normal,weekend,holiday,festival,seasonal',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Percentage Discount Validation
        |--------------------------------------------------------------------------
        */

        if (
            $request->discount_type === 'percentage' &&
            (float) $request->discount_value > 100
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot be more than 100%.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Fixed Amount Discount Validation
        |--------------------------------------------------------------------------
        */

        if (
            $request->discount_type === 'amount' &&
            (float) $request->discount_value > (float) $request->price
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Fixed amount discount cannot be greater than the regular price.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Clean Discount Data
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['discount_type'])
        ) {

            $validated['discount_type'] = null;
            $validated['discount_value'] = null;

        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $price->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'vendor.room-prices.index',
                [
                    'room' => $room->slug,
                ]
            )
            ->with(
                'success',
                'Room price updated successfully.'
            );
    }


    /**
     * ----------------------------------------------------------
     * Delete room price
     * ----------------------------------------------------------
     */
    public function destroy(
        $room,
        $price
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            }
        )
        ->where(
            'slug',
            $room
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Price Belongs To This Room
        |--------------------------------------------------------------------------
        */

        $price = RoomPrice::where(
            'room_id',
            $room->id
        )
        ->findOrFail($price);


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $price->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'vendor.room-prices.index',
                [
                    'room' => $room->slug,
                ]
            )
            ->with(
                'success',
                'Room price deleted successfully.'
            );
    }
}