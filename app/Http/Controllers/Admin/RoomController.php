<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Resort;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $rooms = Room::with([
            'resort',
            'roomType',
            'facilities',
            'images',
        ])
            ->latest()
            ->paginate(20);

        $resorts = Resort::where('status', 1)
            ->orderBy('name')
            ->get();

        $roomTypes = RoomType::orderBy('name')
            ->get();

        $facilities = Facility::where('status', 1)
            ->where('type', 'room')
            ->orderBy('name')
            ->get();

        return view(
            'admin.rooms.index',
            compact(
                'rooms',
                'resorts',
                'roomTypes',
                'facilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

public function create()
{
    $resorts = Resort::where('status', 1)
        ->orderBy('name')
        ->get();

    $roomTypes = RoomType::orderBy('name')
        ->get();

    $facilities = Facility::where('status', 1)
        ->where('type', 'room')
        ->orderBy('name')
        ->get();

    return view(
        'admin.rooms.create', // ✅ সঠিক create blade
        compact(
            'resorts',
            'roomTypes',
            'facilities'
        )
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

            'room_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
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
                'required',
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
                'max:100',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'max:4096',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'nullable',
                'image',
                'max:4096',
            ],

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'exists:facilities,id',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
            ],
        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Resort Validation
            |--------------------------------------------------------------------------
            */

            $resort = Resort::findOrFail(
                $validated['resort_id']
            );


            /*
            |--------------------------------------------------------------------------
            | Room
            |--------------------------------------------------------------------------
            */

            $room = new Room();

            $room->resort_id =
                $validated['resort_id'];

            $room->room_type_id =
                $validated['room_type_id'] ?? null;

            $room->name =
                $validated['name'];

            $room->slug =
                $this->generateUniqueSlug(
                    $validated['name']
                );

            $room->room_no =
                $validated['room_no'] ?? null;

            $room->description =
                $validated['description'] ?? null;

            $room->price =
                $validated['price'];

            $room->discount_price =
                $validated['discount_price'] ?? null;

            $room->extra_bed_price =
                $validated['extra_bed_price'] ?? null;

            $room->total_rooms =
                $validated['total_rooms'];

            $room->max_adult =
                $validated['max_adult'];

            $room->max_child =
                $validated['max_child'];

            $room->beds =
                $validated['beds'];

            $room->bathrooms =
                $validated['bathrooms'];

            $room->size =
                $validated['size'] ?? null;

            $room->size_unit =
                $validated['size_unit'] ?? null;

            $room->view_type =
                $validated['view_type'] ?? null;

            $room->is_featured =
                $request->boolean('is_featured');

            $room->status =
                $request->boolean('status');


            /*
            |--------------------------------------------------------------------------
            | Featured Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('featured_image')) {

                $room->featured_image =
                    $request
                        ->file('featured_image')
                        ->store(
                            'rooms',
                            'public'
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Save Room
            |--------------------------------------------------------------------------
            */

            $room->save();


            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            $room->facilities()->sync(
                $validated['facilities'] ?? []
            );


            /*
            |--------------------------------------------------------------------------
            | Gallery Images
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('images')) {

                foreach (
                    $request->file('images')
                    as $image
                ) {

                    RoomImage::create([

                        'room_id' =>
                            $room->id,

                        'image' =>
                            $image->store(
                                'rooms/gallery',
                                'public'
                            ),
                    ]);
                }
            }


            DB::commit();

            return redirect()
                ->route('admin.rooms.index')
                ->with(
                    'success',
                    'Room Added Successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Room $room)
    {
        $room->load([
            'resort',
            'roomType',
            'facilities',
            'images',
            'prices',
            'availabilities',
        ]);

        return view(
            'admin.rooms.show',
            compact('room')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Room $room)
    {
        $room->load([
            'resort',
            'roomType',
            'facilities',
            'images',
        ]);

        $resorts = Resort::where('status', 1)
            ->orderBy('name')
            ->get();

        $roomTypes = RoomType::orderBy('name')
            ->get();

        $facilities = Facility::where('status', 1)
            ->where('type', 'room')
            ->orderBy('name')
            ->get();

        return view(
            'admin.rooms.edit',
            compact(
                'room',
                'resorts',
                'roomTypes',
                'facilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Room $room
    ) {
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

            'room_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_price' => [
                'nullable',
                'numeric',
                'min:0',
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
                'required',
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
                'max:100',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'max:4096',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'nullable',
                'image',
                'max:4096',
            ],

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'exists:facilities,id',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
            ],
        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Room
            |--------------------------------------------------------------------------
            */

            $room->resort_id =
                $validated['resort_id'];

            $room->room_type_id =
                $validated['room_type_id'] ?? null;

            $room->name =
                $validated['name'];

            $room->slug =
                $this->generateUniqueSlug(
                    $validated['name'],
                    $room->id
                );

            $room->room_no =
                $validated['room_no'] ?? null;

            $room->description =
                $validated['description'] ?? null;

            $room->price =
                $validated['price'];

            $room->discount_price =
                $validated['discount_price'] ?? null;

            $room->extra_bed_price =
                $validated['extra_bed_price'] ?? null;

            $room->total_rooms =
                $validated['total_rooms'];

            $room->max_adult =
                $validated['max_adult'];

            $room->max_child =
                $validated['max_child'];

            $room->beds =
                $validated['beds'];

            $room->bathrooms =
                $validated['bathrooms'];

            $room->size =
                $validated['size'] ?? null;

            $room->size_unit =
                $validated['size_unit'] ?? null;

            $room->view_type =
                $validated['view_type'] ?? null;

            $room->is_featured =
                $request->boolean('is_featured');

            $room->status =
                $request->boolean('status');


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


                $room->featured_image =
                    $request
                        ->file('featured_image')
                        ->store(
                            'rooms',
                            'public'
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Save
            |--------------------------------------------------------------------------
            */

            $room->save();


            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            $room->facilities()->sync(
                $validated['facilities'] ?? []
            );


            /*
            |--------------------------------------------------------------------------
            | New Gallery Images
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('images')) {

                foreach (
                    $request->file('images')
                    as $image
                ) {

                    RoomImage::create([

                        'room_id' =>
                            $room->id,

                        'image' =>
                            $image->store(
                                'rooms/gallery',
                                'public'
                            ),
                    ]);
                }
            }


            DB::commit();

            return redirect()
                ->route('admin.rooms.index')
                ->with(
                    'success',
                    'Room Updated Successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Room $room)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Featured Image
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
            | Gallery Images
            |--------------------------------------------------------------------------
            */

            $room->load('images');

            foreach (
                $room->images as $image
            ) {

                if (
                    $image->image &&
                    Storage::disk('public')->exists(
                        $image->image
                    )
                ) {

                    Storage::disk('public')->delete(
                        $image->image
                    );
                }

                $image->delete();
            }


            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            $room->facilities()->detach();


            /*
            |--------------------------------------------------------------------------
            | Room
            |--------------------------------------------------------------------------
            */

            $room->delete();


            DB::commit();

            return redirect()
                ->route('admin.rooms.index')
                ->with(
                    'success',
                    'Room Deleted Successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE GALLERY IMAGE
    |--------------------------------------------------------------------------
    */

    public function deleteGalleryImage($id)
    {
        try {

            $image = RoomImage::findOrFail($id);


            if (
                $image->image &&
                Storage::disk('public')->exists(
                    $image->image
                )
            ) {

                Storage::disk('public')->delete(
                    $image->image
                );
            }


            $image->delete();


            return response()->json([

                'success' => true,

                'message' =>
                    'Image Deleted Successfully.',

            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage(),

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Room $room)
    {
        try {

            $room->status =
                !$room->status;

            $room->save();


            return response()->json([

                'success' => true,

                'status' =>
                    (bool) $room->status,

                'message' =>
                    'Status Updated Successfully.',

            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage(),

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROOMS BY RESORT
    |--------------------------------------------------------------------------
    */

    public function getRoomsByResort($id)
    {
        $rooms = Room::where(
            'resort_id',
            $id
        )
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'room_type_id',
                'room_no',
                'total_rooms',
                'price',
                'discount_price',
            ]);


        return response()->json([

            'success' => true,

            'rooms' => $rooms,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UNIQUE SLUG
    |--------------------------------------------------------------------------
    */

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {

        $baseSlug =
            Str::slug($name);

        $slug =
            $baseSlug;

        $counter = 1;


        while (
            Room::where('slug', $slug)
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

            $slug =
                $baseSlug .
                '-' .
                $counter++;

        }


        return $slug;
    }
}