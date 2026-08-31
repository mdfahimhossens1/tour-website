<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Resort;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorRoomController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * DISPLAY VENDOR'S ROOMS
     * --------------------------------------------------------------------------
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        $rooms = Room::with([
            'resort',
            'roomType',
            'facilities',
            'images',
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
            ->orderBy('name')
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
     * --------------------------------------------------------------------------
     * SHOW CREATE ROOM FORM
     * --------------------------------------------------------------------------
     */
    public function create()
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        /*
        |--------------------------------------------------------------------------
        | Vendor's Resorts
        |--------------------------------------------------------------------------
        */

        $resorts = Resort::where(
            'vendor_id',
            $vendor->id
        )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Global Room Types
        |--------------------------------------------------------------------------
        */

        $roomTypes = RoomType::orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Global Facilities
        |--------------------------------------------------------------------------
        |
        | Facilities are created by Admin.
        | Vendor can only select facilities for their rooms.
        |
        */

        $facilities = Facility::where(
            'type',
            'room'
        )
            ->where(
                'status',
                true
            )
            ->orderBy('name')
            ->get();


        return view(
            'vendor.rooms.create',
            compact(
                'resorts',
                'roomTypes',
                'facilities'
            )
        );
    }


    /**
     * --------------------------------------------------------------------------
     * STORE NEW ROOM
     * --------------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'resort_id' => [
                'required',
                'exists:resorts,id',
            ],

            'room_type_id' => [
                'required',
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

            /*
            |--------------------------------------------------------------------------
            | Multiple Room Images
            |--------------------------------------------------------------------------
            */

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            /*
            |--------------------------------------------------------------------------
            | Settings
            |--------------------------------------------------------------------------
            */

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Global Facilities
            |--------------------------------------------------------------------------
            */

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'integer',
                'exists:facilities,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Resort Belongs To Vendor
        |--------------------------------------------------------------------------
        */

        Resort::where(
            'vendor_id',
            $vendor->id
        )
            ->findOrFail(
                $validated['resort_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug = !empty($validated['slug'])
            ? $validated['slug']
            : $validated['name'];

        $validated['slug'] = $this->generateUniqueSlug(
            $slug
        );


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_featured'] = $request->boolean(
            'is_featured'
        );

        $validated['status'] = $request->boolean(
            'status',
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Extract Facilities
        |--------------------------------------------------------------------------
        */

        $facilityIds = $validated['facilities'] ?? [];

        unset($validated['facilities']);


        /*
        |--------------------------------------------------------------------------
        | Extract Images
        |--------------------------------------------------------------------------
        */

        $images = $request->file(
            'images',
            []
        );

        unset($validated['images']);


        /*
        |--------------------------------------------------------------------------
        | Create Room + Images + Facilities
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $facilityIds,
            $images
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create Room
            |--------------------------------------------------------------------------
            */

            $room = Room::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Save Multiple Images
            |--------------------------------------------------------------------------
            */

            foreach ($images as $index => $image) {

                $imagePath = $image->store(
                    'rooms',
                    'public'
                );

                $room->images()->create([

                    'image' => $imagePath,

                    /*
                    | First image becomes cover
                    */

                    'is_cover' => $index === 0,

                    'sort_order' => $index,

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Global Room Facilities
            |--------------------------------------------------------------------------
            |
            | Only active facilities with type = room
            | can be attached.
            |
            */

            $validFacilityIds = Facility::where(
                'type',
                'room'
            )
                ->where(
                    'status',
                    true
                )
                ->whereIn(
                    'id',
                    $facilityIds
                )
                ->pluck('id')
                ->toArray();


            /*
            |--------------------------------------------------------------------------
            | Sync Facilities
            |--------------------------------------------------------------------------
            */

            $room->facilities()->sync(
                $validFacilityIds
            );

        });


        return redirect()
            ->route('vendor.rooms.index')
            ->with(
                'success',
                'Room created successfully.'
            );
    }


    /**
     * --------------------------------------------------------------------------
     * SHOW EDIT ROOM FORM
     * --------------------------------------------------------------------------
     */
    public function edit($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        /*
        |--------------------------------------------------------------------------
        | Find Vendor's Own Room
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
            ->with([
                'resort',
                'roomType',
                'facilities',
                'images' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Vendor Resorts
        |--------------------------------------------------------------------------
        */

        $resorts = Resort::where(
            'vendor_id',
            $vendor->id
        )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Global Room Types
        |--------------------------------------------------------------------------
        */

        $roomTypes = RoomType::orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Global Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = Facility::where(
            'type',
            'room'
        )
            ->where(
                'status',
                true
            )
            ->orderBy('name')
            ->get();


        return view(
            'vendor.rooms.edit',
            compact(
                'room',
                'resorts',
                'roomTypes',
                'facilities'
            )
        );
    }


    /**
     * --------------------------------------------------------------------------
     * UPDATE ROOM
     * --------------------------------------------------------------------------
     */
    public function update(Request $request, $room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        /*
        |--------------------------------------------------------------------------
        | Find Vendor's Own Room
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
            ->with('images')
            ->findOrFail($room);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'resort_id' => [
                'required',
                'exists:resorts,id',
            ],

            'room_type_id' => [
                'required',
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

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
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

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'integer',
                'exists:facilities,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify New Resort Belongs To Vendor
        |--------------------------------------------------------------------------
        */

        Resort::where(
            'vendor_id',
            $vendor->id
        )
            ->findOrFail(
                $validated['resort_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug = !empty($validated['slug'])
            ? $validated['slug']
            : $validated['name'];

        $validated['slug'] = $this->generateUniqueSlug(
            $slug,
            $room->id
        );


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_featured'] = $request->boolean(
            'is_featured'
        );

        $validated['status'] = $request->boolean(
            'status',
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Extract Facilities & Images
        |--------------------------------------------------------------------------
        */

        $facilityIds = $validated['facilities'] ?? [];
        unset($validated['facilities']);

        $images = $request->file(
            'images',
            []
        );
        unset($validated['images']);


        /*
        |--------------------------------------------------------------------------
        | Update Room + Images + Facilities
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $room,
            $validated,
            $facilityIds,
            $images
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update Room
            |--------------------------------------------------------------------------
            */

            $room->update(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Add New Images
            |--------------------------------------------------------------------------
            */

            if (!empty($images)) {

                $lastSortOrder = $room->images()
                    ->max('sort_order') ?? -1;

                $hasCover = $room->images()
                    ->where(
                        'is_cover',
                        true
                    )
                    ->exists();

                foreach ($images as $index => $image) {

                    $imagePath = $image->store(
                        'rooms',
                        'public'
                    );

                    $room->images()->create([

                        'image' => $imagePath,

                        'is_cover' =>
                            !$hasCover &&
                            $index === 0,

                        'sort_order' =>
                            $lastSortOrder +
                            $index +
                            1,

                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Global Room Facilities
            |--------------------------------------------------------------------------
            */

            $validFacilityIds = Facility::where(
                'type',
                'room'
            )
                ->where(
                    'status',
                    true
                )
                ->whereIn(
                    'id',
                    $facilityIds
                )
                ->pluck('id')
                ->toArray();


            /*
            |--------------------------------------------------------------------------
            | Sync Facilities
            |--------------------------------------------------------------------------
            */

            $room->facilities()->sync(
                $validFacilityIds
            );

        });


        return redirect()
            ->route('vendor.rooms.index')
            ->with(
                'success',
                'Room updated successfully.'
            );
    }


    /**
     * --------------------------------------------------------------------------
     * DELETE ROOM
     * --------------------------------------------------------------------------
     */
    public function destroy($room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);


        /*
        |--------------------------------------------------------------------------
        | Find Vendor's Own Room
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
            ->with('images')
            ->findOrFail($room);


        DB::transaction(function () use ($room) {

            /*
            |--------------------------------------------------------------------------
            | Delete Physical Images
            |--------------------------------------------------------------------------
            */

            foreach ($room->images as $roomImage) {

                if (
                    $roomImage->image &&
                    Storage::disk('public')->exists(
                        $roomImage->image
                    )
                ) {
                    Storage::disk('public')->delete(
                        $roomImage->image
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Image Records
            |--------------------------------------------------------------------------
            */

            $room->images()->delete();


            /*
            |--------------------------------------------------------------------------
            | Detach Facilities
            |--------------------------------------------------------------------------
            */

            $room->facilities()->detach();


            /*
            |--------------------------------------------------------------------------
            | Backward Compatibility:
            | Delete Old Featured Image
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


            /*
            |--------------------------------------------------------------------------
            | Delete Room
            |--------------------------------------------------------------------------
            */

            $room->delete();

        });


        return redirect()
            ->route('vendor.rooms.index')
            ->with(
                'success',
                'Room deleted successfully.'
            );
    }


    /**
     * --------------------------------------------------------------------------
     * GENERATE UNIQUE ROOM SLUG
     * --------------------------------------------------------------------------
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
