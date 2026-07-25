<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Resort;
use App\Models\RoomAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomAvailabilityController extends Controller
{
    /**
     * Display Listing
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
     * Show Single Availability
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
     * Get Rooms By Resort (AJAX)
     */
    public function getRooms($resortId)
    {
        $rooms = Room::where('resort_id', $resortId)
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'total_rooms',
                'price'
            ]);

        return response()->json($rooms);
    }

    /**
     * Validation
     */
    private function validateData(Request $request)
    {
        return $request->validate([

            'room_id' => [
                'required',
                'exists:rooms,id'
            ],

            'date' => [
                'required',
                'date'
            ],

            'price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'total_rooms' => [
                'required',
                'integer',
                'min:1'
            ],

            'available_rooms' => [
                'required',
                'integer',
                'min:0'
            ],

            'is_closed' => [
                'nullable',
                'boolean'
            ],

            'is_sold_out' => [
                'nullable',
                'boolean'
            ],

        ]);
    }

    /**
     * Store New Availability
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::beginTransaction();

        try {

            // একই Room + একই Date দুইবার যেন না হয়
            $exists = RoomAvailability::where('room_id', $data['room_id'])
                ->whereDate('date', $data['date'])
                ->exists();

            if ($exists) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Availability already exists for this room on this date.');

            }

            // Available Room Validation
            if ($data['available_rooms'] > $data['total_rooms']) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Available rooms cannot be greater than total rooms.');

            }

            // Auto Sold Out
            $data['is_sold_out'] = ($data['available_rooms'] == 0);

            // Closed Checkbox Handle
            $data['is_closed'] = $request->boolean('is_closed');

            RoomAvailability::create([

                'room_id'          => $data['room_id'],
                'date'             => $data['date'],
                'price'            => $data['price'],
                'total_rooms'      => $data['total_rooms'],
                'available_rooms'  => $data['available_rooms'],
                'is_closed'        => $data['is_closed'],
                'is_sold_out'      => $data['is_sold_out'],

            ]);

            DB::commit();

            return redirect()
                ->route('admin.room-availabilities.index')
                ->with('success', 'Room availability created successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }
/**
 * Update Availability
 */
public function update(Request $request, RoomAvailability $roomAvailability)
{
    $data = $this->validateData($request);

    DB::beginTransaction();

    try {

        // Duplicate Check (নিজের Record বাদ দিয়ে)
        $exists = RoomAvailability::where('room_id', $data['room_id'])
            ->whereDate('date', $data['date'])
            ->where('id', '!=', $roomAvailability->id)
            ->exists();

        if ($exists) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Availability already exists for this room on this date.');

        }

        // Validation
        if ($data['available_rooms'] > $data['total_rooms']) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Available rooms cannot be greater than total rooms.');

        }

        // Auto Sold Out
        $data['is_sold_out'] = ($data['available_rooms'] == 0);

        // Closed
        $data['is_closed'] = $request->boolean('is_closed');

        $roomAvailability->update([

            'room_id'          => $data['room_id'],
            'date'             => $data['date'],
            'price'            => $data['price'],
            'total_rooms'      => $data['total_rooms'],
            'available_rooms'  => $data['available_rooms'],
            'is_closed'        => $data['is_closed'],
            'is_sold_out'      => $data['is_sold_out'],

        ]);

        DB::commit();

        return redirect()
            ->route('admin.room-availabilities.index')
            ->with('success', 'Room availability updated successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $e->getMessage());

    }
}
/**
 * Delete Availability
 */
public function destroy(RoomAvailability $roomAvailability)
{
    DB::beginTransaction();

    try {

        $roomAvailability->delete();

        DB::commit();

        return redirect()
            ->route('admin.room-availabilities.index')
            ->with('success', 'Room availability deleted successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect()
            ->back()
            ->with('error', $e->getMessage());

    }
}
}