<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resort;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorRoomController extends Controller
{
    /**
     * Display vendor's own rooms.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        $rooms = Room::with([
                'resort',
                'roomType'
            ])
            ->whereHas('resort', function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );

            })
            ->latest()
            ->paginate(15);


        $resorts = Resort::where(
                'vendor_id',
                $vendor->id
            )
            ->latest()
            ->get();


        return view(
            'vendor.rooms.index',
            compact(
                'rooms',
                'resorts'
            )
        );
    }


    /**
     * Show create room form.
     */
public function create()
{
    $vendor = Auth::user()->vendor;

    abort_unless($vendor, 403);

    $resorts = Resort::where('vendor_id', $vendor->id)
        ->where('status', 1)
        ->orderBy('name')
        ->get();

    $roomTypes = RoomType::orderBy('name')->get();

    return view(
        'vendor.rooms.create',
        compact('resorts', 'roomTypes')
    );
}


    /**
     * Store new room.
     */
public function store(Request $request)
{
    $vendor = Auth::user()->vendor;

    abort_unless($vendor, 403);

    $validated = $request->validate([

        'resort_id' => [
            'required',
            'exists:resorts,id',
        ],

        'room_type_id' => [
            'nullable',
            'exists:room_types,id',
        ],

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'slug' => [
            'nullable',
            'string',
            'max:255',
        ],

        'room_no' => [
            'nullable',
            'string',
            'max:255',
        ],

        'description' => [
            'nullable',
            'string',
        ],

        'extra_bed_price' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'total_rooms' => [
            'required',
            'integer',
            'min:1',
        ],

        'max_adult' => [
            'required',
            'integer',
            'min:1',
        ],

        'max_child' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'beds' => [
            'required',
            'integer',
            'min:1',
        ],

        'bathrooms' => [
            'required',
            'integer',
            'min:1',
        ],

        'size' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'size_unit' => [
            'nullable',
            'string',
            'max:50',
        ],

        'view_type' => [
            'nullable',
            'string',
            'max:255',
        ],

        'featured_image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:4096',
        ],

        'is_featured' => [
            'nullable',
            'boolean',
        ],

        'status' => [
            'nullable',
            'boolean',
        ],

    ]);

    /*
    |--------------------------------------------------------------------------
    | Verify Resort Belongs To Vendor
    |--------------------------------------------------------------------------
    */

    $resort = Resort::where('vendor_id', $vendor->id)
        ->where('status', 1)
        ->findOrFail($validated['resort_id']);

    /*
    |--------------------------------------------------------------------------
    | Generate Slug
    |--------------------------------------------------------------------------
    */

    $slug = !empty($validated['slug'])
        ? $validated['slug']
        : $validated['name'];

    $validated['slug'] = $this->generateUniqueSlug($slug);

    /*
    |--------------------------------------------------------------------------
    | Checkbox Values
    |--------------------------------------------------------------------------
    */

    $validated['is_featured'] =
        $request->boolean('is_featured');

    $validated['status'] =
        $request->boolean('status', true);

    /*
    |--------------------------------------------------------------------------
    | Featured Image
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('featured_image')) {

        $validated['featured_image'] =
            $request
                ->file('featured_image')
                ->store('rooms', 'public');
    }

    /*
    |--------------------------------------------------------------------------
    | Create Room
    |--------------------------------------------------------------------------
    */

    Room::create($validated);

    return redirect()
        ->route('vendor.rooms.index')
        ->with(
            'success',
            'Room created successfully.'
        );
}


    /**
     * Show edit room form.
     */
    public function edit($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        $room = Room::whereHas(
                'resort',
                function ($query) use ($vendor) {

                    $query->where(
                        'vendor_id',
                        $vendor->id
                    );

                }
            )
            ->findOrFail($room);


        $resort = $room->resort;

        $roomTypes = RoomType::orderBy('name')
            ->get();


        return view(
            'vendor.rooms.edit',
            compact(
                'room',
                'resort',
                'roomTypes'
            )
        );
    }


    /**
     * Update room.
     */
    public function update(
        Request $request,
        $room
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Room
        |--------------------------------------------------------------------------
        */

        $room = Room::whereHas(
                'resort',
                function ($query) use ($vendor) {

                    $query->where(
                        'vendor_id',
                        $vendor->id
                    );

                }
            )
            ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'room_type_id' => [
                'nullable',
                'exists:room_types,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],

            'room_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'extra_bed_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_rooms' => [
                'required',
                'integer',
                'min:1',
            ],

            'max_adult' => [
                'required',
                'integer',
                'min:1',
            ],

            'max_child' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'beds' => [
                'required',
                'integer',
                'min:1',
            ],

            'bathrooms' => [
                'required',
                'integer',
                'min:1',
            ],

            'size' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'size_unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'view_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = !empty($validated['slug'])
            ? $validated['slug']
            : $validated['name'];


        $validated['slug'] =
            $this->generateUniqueSlug(
                $slug,
                $room->id
            );


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_featured'] =
            $request->boolean(
                'is_featured'
            );

        $validated['status'] =
            $request->boolean(
                'status'
            );


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            if (
                $room->featured_image &&
                Storage::disk('public')->exists(
                    $room->featured_image
                )
            ) {

                Storage::disk('public')->delete(
                    $room->featured_image
                );
            }


            $validated['featured_image'] =
                $request
                    ->file('featured_image')
                    ->store(
                        'rooms',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $room->update($validated);


        return redirect()
            ->route('vendor.rooms.index')
            ->with(
                'success',
                'Room updated successfully.'
            );
    }


    /**
     * Delete room.
     */
    public function destroy($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        $room = Room::whereHas(
                'resort',
                function ($query) use ($vendor) {

                    $query->where(
                        'vendor_id',
                        $vendor->id
                    );

                }
            )
            ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Delete Featured Image
        |--------------------------------------------------------------------------
        */

        if (
            $room->featured_image &&
            Storage::disk('public')->exists(
                $room->featured_image
            )
        ) {

            Storage::disk('public')->delete(
                $room->featured_image
            );
        }


        $room->delete();


        return redirect()
            ->route('vendor.rooms.index')
            ->with(
                'success',
                'Room deleted successfully.'
            );
    }


    /**
     * Generate unique room slug.
     */
    private function generateUniqueSlug(
        string $slug,
        ?int $ignoreId = null
    ): string {

        $originalSlug = Str::slug($slug);


        if (empty($originalSlug)) {

            $originalSlug = 'room';
        }


        $uniqueSlug = $originalSlug;

        $counter = 1;


        while (
            Room::where(
                'slug',
                $uniqueSlug
            )
            ->when(
                $ignoreId,
                function ($query) use ($ignoreId) {

                    $query->where(
                        'id',
                        '!=',
                        $ignoreId
                    );

                }
            )
            ->exists()
        ) {

            $uniqueSlug =
                $originalSlug .
                '-' .
                $counter;

            $counter++;
        }


        return $uniqueSlug;
    }
}