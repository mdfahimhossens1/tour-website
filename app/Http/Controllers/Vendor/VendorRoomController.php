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
     * Display vendor's own rooms.
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
        | Room Types
        |--------------------------------------------------------------------------
        */

        $roomTypes = RoomType::orderBy(
            'name'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Vendor Room Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = Facility::where(
                'vendor_id',
                $vendor->id
            )
            ->where(
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
     * Store new room.
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
            | Facilities
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

        $resort = Resort::where(
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

        $slug = !empty(
            $validated['slug']
        )
            ? $validated['slug']
            : $validated['name'];


        $validated['slug'] =
            $this->generateUniqueSlug(
                $slug
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
                'status',
                true
            );


        /*
        |--------------------------------------------------------------------------
        | Remove Images From Room Data
        |--------------------------------------------------------------------------
        |
        | Images are stored in room_images table,
        | not in rooms table.
        |
        */

        unset(
            $validated['images']
        );


        /*
        |--------------------------------------------------------------------------
        | Room Facilities
        |--------------------------------------------------------------------------
        */

        $facilityIds =
            $validated['facilities'] ?? [];


        unset(
            $validated['facilities']
        );


        /*
        |--------------------------------------------------------------------------
        | Uploaded Images
        |--------------------------------------------------------------------------
        */

        $images = $request->file(
            'images',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Create Room + Images + Facilities
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $facilityIds,
            $vendor,
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
            | Save Multiple Room Images
            |--------------------------------------------------------------------------
            */

            if (!empty($images)) {

                foreach (
                    $images as $index => $image
                ) {

                    $imagePath =
                        $image->store(
                            'rooms',
                            'public'
                        );


                    $room->images()->create([

                        'image' =>
                            $imagePath,

                        /*
                        | First uploaded image = Cover
                        */

                        'is_cover' =>
                            $index === 0,

                        'sort_order' =>
                            $index,

                    ]);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Validate Facilities Belong To Vendor
            |--------------------------------------------------------------------------
            */

            $validFacilityIds =
                Facility::where(
                        'vendor_id',
                        $vendor->id
                    )
                    ->where(
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


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'vendor.rooms.index'
            )
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


        /*
        |--------------------------------------------------------------------------
        | Vendor Own Room
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
        'facilities',
        'images' => function ($query) {

            $query->orderBy('sort_order');

        },
    ])
    ->findOrFail($room);

$resort = $room->resort;

$resorts = Resort::where(
        'vendor_id',
        $vendor->id
    )
    ->orderBy('name')
    ->get();

$roomTypes = RoomType::orderBy('name')->get();

$facilities = Facility::where(
        'vendor_id',
        $vendor->id
    )
    ->where('type', 'room')
    ->where('status', true)
    ->orderBy('name')
    ->get();

return view(
    'vendor.rooms.edit',
    compact(
        'room',
        'resort',
        'resorts',
        'roomTypes',
        'facilities'
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
        | Vendor Own Room
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
            ->findOrFail(
                $room
            );


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


            /*
            |--------------------------------------------------------------------------
            | New Multiple Images
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
            | Facilities
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

        $slug = !empty(
            $validated['slug']
        )
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
                'status',
                true
            );


        /*
        |--------------------------------------------------------------------------
        | Remove Images From Room Data
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['images']
        );


        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        $facilityIds =
            $validated['facilities'] ?? [];


        unset(
            $validated['facilities']
        );


        /*
        |--------------------------------------------------------------------------
        | New Uploaded Images
        |--------------------------------------------------------------------------
        */

        $images = $request->file(
            'images',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Update Room + Images + Facilities
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $room,
            $validated,
            $facilityIds,
            $vendor,
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

                /*
                | Get current maximum sort order
                */

                $lastSortOrder =
                    $room->images()
                        ->max(
                            'sort_order'
                        );


                $lastSortOrder =
                    $lastSortOrder ?? -1;


                /*
                | Check whether room already
                | has a cover image
                */

                $hasCover =
                    $room->images()
                        ->where(
                            'is_cover',
                            true
                        )
                        ->exists();


                foreach (
                    $images as $index => $image
                ) {

                    $imagePath =
                        $image->store(
                            'rooms',
                            'public'
                        );


                    $sortOrder =
                        $lastSortOrder +
                        $index +
                        1;


                    /*
                    | If room has no cover,
                    | first new image becomes cover.
                    */

                    $isCover =
                        !$hasCover &&
                        $index === 0;


                    $room->images()->create([

                        'image' =>
                            $imagePath,

                        'is_cover' =>
                            $isCover,

                        'sort_order' =>
                            $sortOrder,

                    ]);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Validate Facilities
            |--------------------------------------------------------------------------
            */

            $validFacilityIds =
                Facility::where(
                        'vendor_id',
                        $vendor->id
                    )
                    ->where(
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


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'vendor.rooms.index'
            )
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


        /*
        |--------------------------------------------------------------------------
        | Vendor Own Room
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
            ->findOrFail(
                $room
            );


        DB::transaction(function () use ($room) {

            /*
            |--------------------------------------------------------------------------
            | Delete All Room Images
            |--------------------------------------------------------------------------
            */

            foreach (
                $room->images as $roomImage
            ) {

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
            | Delete Room Images Records
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
            | Delete Old Featured Image
            |--------------------------------------------------------------------------
            |
            | This is kept for backward compatibility
            | in case old rooms still have featured_image.
            |
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
            ->route(
                'vendor.rooms.index'
            )
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

        $originalSlug = Str::slug(
            $slug
        );


        if (
            empty($originalSlug)
        ) {

            $originalSlug = 'room';

        }


        $uniqueSlug =
            $originalSlug;


        $counter = 1;


        while (

            Room::where(
                'slug',
                $uniqueSlug
            )
            ->when(
                $ignoreId,
                function ($query) use (
                    $ignoreId
                ) {

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