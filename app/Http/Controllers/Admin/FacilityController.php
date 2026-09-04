<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $facilities = Facility::latest()
            ->paginate(20);

        return view(
            'admin.facilities.index',
            compact('facilities')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:facilities,name',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:room,resort',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Admin Created = Global Facility
        |--------------------------------------------------------------------------
        */

        $validated['vendor_id'] = null;


        Facility::create($validated);


        return back()->with(
            'success',
            'Facility Created Successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Facility $facility)
    {
        return response()->json($facility);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Facility $facility
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:facilities,name,' . $facility->id,
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:room,resort',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Keep Facility Global
        |--------------------------------------------------------------------------
        */

        $validated['vendor_id'] = null;


        $facility->update($validated);


        return back()->with(
            'success',
            'Facility Updated Successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Facility $facility)
    {
        $facility->delete();


        return back()->with(
            'success',
            'Facility Deleted Successfully.'
        );
    }
}