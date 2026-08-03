<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorFacilityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FACILITY LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        /*
        |--------------------------------------------------------------------------
        | Vendor Check
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Facility Query
        |--------------------------------------------------------------------------
        */

        $query = Facility::where(
            'vendor_id',
            $vendor->id
        );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(
                'name',
                'like',
                "%{$search}%"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {

            $query->where(
                'type',
                $request->type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total' => Facility::where(
                'vendor_id',
                $vendor->id
            )->count(),

            'resort' => Facility::where(
                'vendor_id',
                $vendor->id
            )
                ->where('type', 'resort')
                ->count(),

            'room' => Facility::where(
                'vendor_id',
                $vendor->id
            )
                ->where('type', 'room')
                ->count(),

            'active' => Facility::where(
                'vendor_id',
                $vendor->id
            )
                ->where('status', true)
                ->count(),

        ];


        return view(
            'vendor.facilities.index',
            compact(
                'facilities',
                'stats',
                'vendor'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE FACILITY
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        return view(
            'vendor.facilities.create',
            compact('vendor')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE FACILITY
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:resort,room',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Facility
        |--------------------------------------------------------------------------
        */

        Facility::create([

            'vendor_id' => $vendor->id,

            'name' => $validated['name'],

            'icon' =>
                $validated['icon'] ?? null,

            'type' =>
                $validated['type'],

            'status' =>
                $request->boolean('status'),

        ]);


        return redirect()
            ->route('vendor.facilities.index')
            ->with(
                'success',
                'Facility created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT FACILITY
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $vendor = Auth::user()->vendor;


        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Only Own Facility
        |--------------------------------------------------------------------------
        */

        $facility = Facility::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);


        return view(
            'vendor.facilities.edit',
            compact(
                'facility',
                'vendor'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE FACILITY
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $vendor = Auth::user()->vendor;


        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Only Own Facility
        |--------------------------------------------------------------------------
        */

        $facility = Facility::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:resort,room',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $facility->update([

            'name' =>
                $validated['name'],

            'icon' =>
                $validated['icon'] ?? null,

            'type' =>
                $validated['type'],

            'status' =>
                $request->boolean('status'),

        ]);


        return redirect()
            ->route('vendor.facilities.index')
            ->with(
                'success',
                'Facility updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE FACILITY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $vendor = Auth::user()->vendor;


        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Only Own Facility
        |--------------------------------------------------------------------------
        */

        $facility = Facility::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $facility->delete();


        return redirect()
            ->route('vendor.facilities.index')
            ->with(
                'success',
                'Facility deleted successfully.'
            );
    }
}