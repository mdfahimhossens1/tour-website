<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomPrice;
use App\Models\Room;
use App\Models\Resort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomPriceController extends Controller
{
    /**
     * Display a listing.
     */
    public function index()
    {
        $roomPrices = RoomPrice::with([
            'room',
            'room.resort'
        ])
        ->latest()
        ->paginate(20);

        $resorts = Resort::where('status',1)
            ->orderBy('name')
            ->get();

        $rooms = Room::where('status',1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.room_prices.index',
            compact(
                'roomPrices',
                'resorts',
                'rooms'
            )
        );
    }

    /**
     * Store
     */
public function store(Request $request)
{
    $request->validate([

        'room_id' => 'required|exists:rooms,id',

        'from_date' => 'required|date',

        'to_date' => 'required|date|after_or_equal:from_date',

        'price' => 'required|numeric|min:0',

        'discount_type' => 'nullable|in:percentage,amount',

        'discount_value' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'type' => 'required|in:normal,weekend,holiday,festival,seasonal',

    ]);

    if ($request->discount_type === 'percentage' &&
        $request->discount_value > 100) {

        return back()
            ->withInput()
            ->withErrors([
                'discount_value' => 'Percentage discount cannot exceed 100%.'
            ]);
    }

    if ($request->discount_type === 'amount' &&
        $request->discount_value > $request->price) {

        return back()
            ->withInput()
            ->withErrors([
                'discount_value' => 'Discount amount cannot be greater than regular price.'
            ]);
    }

    DB::beginTransaction();

    try {

        RoomPrice::create([

            'room_id' => $request->room_id,

            'from_date' => $request->from_date,

            'to_date' => $request->to_date,

            'price' => $request->price,

            'discount_type' => $request->discount_type,

            'discount_value' => $request->discount_type
                ? $request->discount_value
                : null,

            'type' => $request->type,

        ]);

        DB::commit();

        return redirect()
            ->route('vendor.room-prices.index', $request->room_id)
            ->with('success', 'Room Price Created Successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
    /**
     * Show single Room Price (AJAX)
     */
    public function show(RoomPrice $roomPrice)
    {
        
        return response()->json([
            'success' => true,
            'data' => $roomPrice->load([
                'room',
                'room.resort',
            ]),
        ]);
    }

public function update(Request $request, RoomPrice $roomPrice)
{
    $request->validate([

        'room_id' => 'required|exists:rooms,id',

        'from_date' => 'required|date',

        'to_date' => 'required|date|after_or_equal:from_date',

        'price' => 'required|numeric|min:0',

        'discount_type' => 'nullable|in:percentage,amount',

        'discount_value' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'type' => 'required|in:normal,weekend,holiday,festival,seasonal',

    ]);

    if ($request->discount_type === 'percentage' &&
        $request->discount_value > 100) {

        return back()
            ->withInput()
            ->withErrors([
                'discount_value' => 'Percentage discount cannot exceed 100%.'
            ]);
    }

    if ($request->discount_type === 'amount' &&
        $request->discount_value > $request->price) {

        return back()
            ->withInput()
            ->withErrors([
                'discount_value' => 'Discount amount cannot be greater than regular price.'
            ]);
    }

    DB::beginTransaction();

    try {

        $roomPrice->update([

            'room_id' => $request->room_id,

            'from_date' => $request->from_date,

            'to_date' => $request->to_date,

            'price' => $request->price,

            'discount_type' => $request->discount_type,

            'discount_value' => $request->discount_type
                ? $request->discount_value
                : null,

            'type' => $request->type,

        ]);

        DB::commit();

        return redirect()
            ->route('vendor.room-prices.index', $request->room_id)
            ->with('success', 'Room Price Updated Successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    /**
     * Delete Room Price
     */
    public function destroy(RoomPrice $roomPrice)
    {
        DB::beginTransaction();

        try {

            $roomPrice->delete();

            DB::commit();

            return redirect()
                ->route('admin.room-prices.index')
                ->with('success', 'Room Price Deleted Successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get Rooms By Resort (AJAX)
     */
    public function getRoomsByResort($resortId)
    {
        $rooms = Room::where('resort_id', $resortId)
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'price'
            ]);

        return response()->json($rooms);
    }
}
