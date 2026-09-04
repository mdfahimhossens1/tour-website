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
     * ----------------------------------------------------------
     * Display room availability
     * ----------------------------------------------------------
     */
    public function index(Room $room)
    {
        $this->authorizeRoom($room);

        $room->load([
            'resort',
            'roomType',
        ]);

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
     * ----------------------------------------------------------
     * Show create form
     * ----------------------------------------------------------
     */
    public function create(Room $room)
    {
        $this->authorizeRoom($room);

        $room->load([
            'resort',
            'roomType',
        ]);

        return view(
            'vendor.room-availabilities.create',
            compact('room')
        );
    }


    /**
     * ----------------------------------------------------------
     * Store availability
     * ----------------------------------------------------------
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
                'after_or_equal:today',

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
                'max:99999999.99',
            ],

            'is_closed' => [
                'nullable',
                'boolean',
            ],
        ], [
            'date.unique' =>
                'Availability for this room already exists for this date.',

            'date.after_or_equal' =>
                'Availability can only be created for today or a future date.',

            'price.numeric' =>
                'Price must be a valid number.',

            'price.min' =>
                'Price cannot be negative.',
        ]);


        /*
        |----------------------------------------------------------
        | Availability values
        |----------------------------------------------------------
        |
        | total_rooms comes from Room.
        | available_rooms is calculated from bookings.
        |
        */

        $validated['room_id'] = $room->id;

        $validated['total_rooms'] =
            max(0, (int) $room->total_rooms);

        $validated['is_closed'] =
            $request->boolean('is_closed');

        /*
        |----------------------------------------------------------
        | Create record
        |----------------------------------------------------------
        */

        $availability = RoomAvailability::create(
            $validated
        );


        /*
        |----------------------------------------------------------
        | Calculate available rooms
        |----------------------------------------------------------
        */

        $availability->syncAvailability();


        return redirect()
            ->route(
                'vendor.room-availabilities.index',
                [
                    'room' => $room->id,
                ]
            )
            ->with(
                'success',
                'Room availability added successfully.'
            );
    }


    /**
     * ----------------------------------------------------------
     * Show edit form
     * ----------------------------------------------------------
     */
    public function edit(
        RoomAvailability $availability
    ) {
        $availability->load([
            'room.resort',
            'room.roomType',
        ]);

        $this->authorizeRoom(
            $availability->room
        );

        return view(
            'vendor.room-availabilities.edit',
            compact('availability')
        );
    }


    /**
     * ----------------------------------------------------------
     * Update availability
     * ----------------------------------------------------------
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
                'after_or_equal:today',

                Rule::unique(
                    'room_availabilities',
                    'date'
                )
                    ->where(function ($query) use ($availability) {
                        return $query->where(
                            'room_id',
                            $availability->room_id
                        );
                    })
                    ->ignore($availability->id),
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'is_closed' => [
                'nullable',
                'boolean',
            ],
        ], [
            'date.unique' =>
                'Availability for this room already exists for this date.',

            'date.after_or_equal' =>
                'Availability date cannot be in the past.',
        ]);


        /*
        |----------------------------------------------------------
        | Values controlled by system
        |----------------------------------------------------------
        */

        $validated['is_closed'] =
            $request->boolean('is_closed');

        $validated['total_rooms'] =
            max(
                0,
                (int) $availability->room->total_rooms
            );


        /*
        |----------------------------------------------------------
        | Update
        |----------------------------------------------------------
        */

        $availability->update(
            $validated
        );


        /*
        |----------------------------------------------------------
        | Recalculate availability
        |----------------------------------------------------------
        */

        $availability->syncAvailability();


        return redirect()
            ->route(
                'vendor.room-availabilities.index',
                [
                    'room' =>
                        $availability->room_id,
                ]
            )
            ->with(
                'success',
                'Room availability updated successfully.'
            );
    }


    /**
     * ----------------------------------------------------------
     * Delete availability
     * ----------------------------------------------------------
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
                    'room' => $roomId,
                ]
            )
            ->with(
                'success',
                'Room availability deleted successfully.'
            );
    }


    /**
     * ----------------------------------------------------------
     * Authorize vendor room
     * ----------------------------------------------------------
     */
    private function authorizeRoom(
        Room $room
    ): void {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            abort(
                403,
                'Vendor profile not found.'
            );
        }

        $room->loadMissing('resort');

        abort_unless(
            $room->resort &&
            (int) $room->resort->vendor_id ===
            (int) $vendor->id,
            403,
            'You are not authorized to manage this room.'
        );
    }
}