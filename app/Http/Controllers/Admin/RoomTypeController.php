<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::latest()->paginate(20);

        return view('admin.room_types.index', compact('roomTypes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:room_types,name',
            'icon' => 'nullable|max:255',
        ]);

        RoomType::create([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return back()->with('success', 'Room Type Created Successfully.');
    }

    public function show(RoomType $roomType)
    {
        //
    }

    public function edit(RoomType $roomType)
    {
        //
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|max:255|unique:room_types,name,' . $roomType->id,
            'icon' => 'nullable|max:255',
        ]);

        $roomType->update([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return back()->with('success', 'Room Type Updated Successfully.');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return back()->with('success', 'Room Type Deleted Successfully.');
    }
}