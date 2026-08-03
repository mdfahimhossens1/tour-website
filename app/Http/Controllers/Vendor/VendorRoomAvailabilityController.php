<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorRoomAvailabilityController extends Controller
{
    /**
     * Display availability records for a room.
     */
    public function index(Room $room)
    {
        $this->authorizeRoom($room);

        $availabilities = $room->availabilities()
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view(
            'vendor.room-availabilities.index',
            compact(
                'room',
                'availabilities'
            )
        );
    }


    /**
     * Show create availability form.
     */
    public function create(Room $room)
    {
        $this->authorizeRoom($room);

        return view(
            'vendor.room-availabilities.create',
            compact('room')
        );
    }


    /**
     * Store availability.
     */
    public function store(
        Request $request,
        Room $room
    ) {
        $this->authorizeRoom($room);

        $validated = $request->validate([

            'date' => [
                'required',
                'date',

                Rule::unique(
                    'room_availabilities',
                    'date'
                )->where(function ($query) use ($room) {

                    return $query->where(
                        'room_id',
                        $room->id
                    );

                }),
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_rooms' => [
                'required',
                'integer',
                'min:0',
            ],

            'available_rooms' => [
                'required',
                'integer',
                'min:0',
                'lte:total_rooms',
            ],

            'is_closed' => [
                'nullable',
                'boolean',
            ],

            'is_sold_out' => [
                'nullable',
                'boolean',
            ],

        ], [

            'date.unique' =>
                'Availability for this room already exists for this date.',

            'available_rooms.lte' =>
                'Available rooms cannot be greater than total rooms.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_closed'] =
            $request->boolean('is_closed');

        $validated['is_sold_out'] =
            $request->boolean('is_sold_out');


        /*
        |--------------------------------------------------------------------------
        | Closed / Sold Out
        |--------------------------------------------------------------------------
        */

        if (
            $validated['is_closed'] ||
            $validated['is_sold_out']
        ) {

            $validated['available_rooms'] = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Create Availability
        |--------------------------------------------------------------------------
        */

        $room->availabilities()->create(
            $validated
        );


        return redirect()
            ->route(
                'vendor.room-availabilities.index',
                [
                    'room' => $room->id
                ]
            )
            ->with(
                'success',
                'Room availability added successfully.'
            );
    }


    /**
     * Show edit availability form.
     */
    public function edit(
        RoomAvailability $availability
    ) {
        $availability->load('room');

        $this->authorizeRoom(
            $availability->room
        );

        return view(
            'vendor.room-availabilities.edit',
            compact('availability')
        );
    }


    /**
     * Update availability.
     */
    public function update(
        Request $request,
        RoomAvailability $availability
    ) {
        $availability->load('room');

        $this->authorizeRoom(
            $availability->room
        );


        $validated = $request->validate([

            'date' => [
                'required',
                'date',

                Rule::unique(
                    'room_availabilities',
                    'date'
                )->where(function ($query) use ($availability) {

                    return $query->where(
                        'room_id',
                        $availability->room_id
                    );

                })->ignore(
                    $availability->id
                ),
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_rooms' => [
                'required',
                'integer',
                'min:0',
            ],

            'available_rooms' => [
                'required',
                'integer',
                'min:0',
                'lte:total_rooms',
            ],

            'is_closed' => [
                'nullable',
                'boolean',
            ],

            'is_sold_out' => [
                'nullable',
                'boolean',
            ],

        ], [

            'date.unique' =>
                'Availability for this room already exists for this date.',

            'available_rooms.lte' =>
                'Available rooms cannot be greater than total rooms.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_closed'] =
            $request->boolean('is_closed');

        $validated['is_sold_out'] =
            $request->boolean('is_sold_out');


        /*
        |--------------------------------------------------------------------------
        | Closed / Sold Out
        |--------------------------------------------------------------------------
        */

        if (
            $validated['is_closed'] ||
            $validated['is_sold_out']
        ) {

            $validated['available_rooms'] = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Update Availability
        |--------------------------------------------------------------------------
        */

        $availability->update(
            $validated
        );


        return redirect()
            ->route(
                'vendor.room-availabilities.index',
                [
                    'room' => $availability->room_id
                ]
            )
            ->with(
                'success',
                'Room availability updated successfully.'
            );
    }


    /**
     * Delete availability.
     */
    public function destroy(
        RoomAvailability $availability
    ) {
        $availability->load('room');

        $this->authorizeRoom(
            $availability->room
        );


        $roomId =
            $availability->room_id;


        $availability->delete();


        return redirect()
            ->route(
                'vendor.room-availabilities.index',
                [
                    'room' => $roomId
                ]
            )
            ->with(
                'success',
                'Room availability deleted successfully.'
            );
    }


    /**
     * Make sure room belongs to logged-in vendor.
     */
    private function authorizeRoom(
        Room $room
    ): void {

        $vendor =
            Auth::user()->vendor;


        if (!$vendor) {

            abort(
                403,
                'Vendor profile not found.'
            );

        }


        $room->loadMissing(
            'resort'
        );


        abort_unless(

            $room->resort &&
            $room->resort->vendor_id === $vendor->id,

            403,

            'You are not authorized to manage this room.'

        );
    }
}
