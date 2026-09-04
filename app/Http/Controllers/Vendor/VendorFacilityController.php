<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class VendorFacilityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $query = Facility::query();


        /*
        |--------------------------------------------------------------------------
        | GLOBAL + OLD VENDOR FACILITIES
        |--------------------------------------------------------------------------
        |
        | Admin created facilities:
        | vendor_id = NULL
        |
        | Old vendor created facilities:
        | vendor_id = vendor id
        |
        */

        $vendor = auth()->user()->vendor;


        $query->where(function ($q) use ($vendor) {

            /*
            | Global Admin Facilities
            */

            $q->whereNull('vendor_id');


            /*
            | Existing Vendor Facilities
            */

            if ($vendor) {

                $q->orWhere(
                    'vendor_id',
                    $vendor->id
                );
            }

        });


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
                '%' . $search . '%'
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

        if (
            $request->has('status') &&
            $request->status !== ''
        ) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $baseQuery = Facility::query()
            ->where(function ($q) use ($vendor) {

                $q->whereNull('vendor_id');

                if ($vendor) {

                    $q->orWhere(
                        'vendor_id',
                        $vendor->id
                    );
                }

            });


        $stats = [

            'total' => (clone $baseQuery)->count(),

            'resort' => (clone $baseQuery)
                ->where('type', 'resort')
                ->count(),

            'room' => (clone $baseQuery)
                ->where('type', 'room')
                ->count(),

            'active' => (clone $baseQuery)
                ->where('status', true)
                ->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();


        return view(
            'vendor.facilities.index',
            compact(
                'facilities',
                'stats'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Facility $facility)
    {
        /*
        |--------------------------------------------------------------------------
        | Vendor can edit:
        | 1. Global Admin Facility
        | 2. His old own facility
        |--------------------------------------------------------------------------
        */

        $vendor = auth()->user()->vendor;


        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            is_null($facility->vendor_id) ||
            $facility->vendor_id == $vendor->id,
            403,
            'You are not authorized to edit this facility.'
        );


        return view(
            'vendor.facilities.edit',
            compact('facility')
        );
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

        $vendor = auth()->user()->vendor;


        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            is_null($facility->vendor_id) ||
            $facility->vendor_id == $vendor->id,
            403,
            'You are not authorized to update this facility.'
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
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Vendor cannot change vendor_id.
        |
        */

        unset(
            $validated['vendor_id']
        );


        $facility->update($validated);


        return redirect()
            ->route(
                'vendor.facilities.index'
            )
            ->with(
                'success',
                'Facility updated successfully.'
            );
    }
}