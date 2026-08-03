<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class VendorRoomTypeController extends Controller
{
    /**
     * Display all room types.
     */
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')
            ->latest()
            ->paginate(15);

        return view('vendor.room-types.index', compact('roomTypes'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('vendor.room-types.create');
    }

    /**
     * Store room type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        RoomType::create($validated);

        return redirect()
            ->route('vendor.room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(RoomType $roomType)
    {
        return view('vendor.room-types.edit', compact('roomType'));
    }

    /**
     * Update room type.
     */
    public function update(
        Request $request,
        RoomType $roomType
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $roomType->update($validated);

        return redirect()
            ->route('vendor.room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    /**
     * Delete room type.
     */
    public function destroy(RoomType $roomType)
    {
        if ($roomType->rooms()->exists()) {
            return back()->with(
                'error',
                'This room type is currently assigned to one or more rooms. Please change those rooms first.'
            );
        }

        $roomType->delete();

        return redirect()
            ->route('vendor.room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
}