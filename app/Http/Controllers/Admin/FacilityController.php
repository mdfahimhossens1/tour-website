<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::latest()->paginate(20);

        return view(
            'admin.facilities.index',
            compact('facilities')
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|max:255|unique:facilities,name',

            'icon' => 'nullable|max:255',

            'type' => 'required|in:room,resort',

            'status' => 'required|boolean',

        ]);

        Facility::create($request->all());

        return back()->with(
            'success',
            'Facility Created Successfully.'
        );
    }

    public function edit(Facility $facility)
    {
        return response()->json($facility);
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([

            'name' => 'required|max:255|unique:facilities,name,' . $facility->id,

            'icon' => 'nullable|max:255',

            'type' => 'required|in:room,resort',

            'status' => 'required|boolean',

        ]);

        $facility->update($request->all());

        return back()->with(
            'success',
            'Facility Updated Successfully.'
        );
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return back()->with(
            'success',
            'Facility Deleted Successfully.'
        );
    }
}