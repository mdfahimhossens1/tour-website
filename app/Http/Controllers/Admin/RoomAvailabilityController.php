<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomAvailability;
use App\Models\Resort;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $roomAvailabilities = RoomAvailability::with([
        'room.resort'
    ])
    ->latest()
    ->paginate(15);

    $resorts = Resort::where('status', 1)
        ->orderBy('name')
        ->get();

    return view(
        'admin.room_availabilities.index',
        compact(
            'roomAvailabilities',
            'resorts'
        )
    );
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $data = $this->validateData($request);

    DB::beginTransaction();

    try {

        $room = Room::findOrFail($data['room_id']);

        // Available Room Validation
        if ($data['available_rooms'] > $room->total_rooms) {

            return back()
                ->withInput()
                ->with('error', 'Available rooms cannot be greater than total rooms.');

        }

        // Duplicate Date Check
        $exists = RoomAvailability::where('room_id', $data['room_id'])
            ->whereDate('date', $data['date'])
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->with('error', 'Availability already exists for this room on this date.');

        }

        RoomAvailability::create([

            'room_id' => $data['room_id'],

            'date' => $data['date'],

            'available_rooms' => $data['available_rooms'],

            'status' => $data['status'],

        ]);

        DB::commit();

        return redirect()
            ->route('admin.room-availabilities.index')
            ->with('success', 'Room availability created successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());

    }
}

    /**
     * Display the specified resource.
     */
public function show(RoomAvailability $roomAvailability)
{
    $roomAvailability->load([
        'room.resort'
    ]);

    return response()->json([
        'success' => true,
        'data' => $roomAvailability
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

public function getRooms($resortId)
{
    $rooms = Room::where('resort_id', $resortId)
        ->where('status', 1)
        ->orderBy('name')
        ->get([
            'id',
            'name',
            'total_rooms'
        ]);

    return response()->json($rooms);
}


}
