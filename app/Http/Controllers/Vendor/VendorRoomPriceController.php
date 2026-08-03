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
     * Display prices of a room.
     */
    public function index($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

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
        ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Prices
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
     * Show create price form.
     */
    public function create($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


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
        ->findOrFail($room);


        return view(
            'vendor.room-prices.create',
            compact('room')
        );
    }


    /**
     * Store new room price.
     */
    public function store(
        Request $request,
        $room
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


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
        ->findOrFail($room);


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

            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
                'lte:price',
            ],

            'type' => [
                'required',
                'in:normal,weekend,holiday,festival,seasonal',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Room ID
        |--------------------------------------------------------------------------
        */

        $validated['room_id'] = $room->id;


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        RoomPrice::create($validated);


        return redirect()
            ->route(
                'vendor.room-prices.index',
                $room
            )
            ->with(
                'success',
                'Room price added successfully.'
            );
    }


    /**
     * Show edit price form.
     */
    public function edit(
        $room,
        $price
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


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
        ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Price Belongs To Room
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
     * Update room price.
     */
    public function update(
        Request $request,
        $room,
        $price
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


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
        ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Price Belongs To Room
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

            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
                'lte:price',
            ],

            'type' => [
                'required',
                'in:normal,weekend,holiday,festival,seasonal',
            ],

        ]);


        $price->update($validated);


        return redirect()
            ->route(
                'vendor.room-prices.index',
                $room
            )
            ->with(
                'success',
                'Room price updated successfully.'
            );
    }


    /**
     * Delete room price.
     */
    public function destroy(
        $room,
        $price
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


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
        ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Price Belongs To Room
        |--------------------------------------------------------------------------
        */

        $price = RoomPrice::where(
            'room_id',
            $room->id
        )
        ->findOrFail($price);


        $price->delete();


        return redirect()
            ->route(
                'vendor.room-prices.index',
                $room
            )
            ->with(
                'success',
                'Room price deleted successfully.'
            );
    }
}