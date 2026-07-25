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

public function index()
{
    $rooms = Room::with([
        'resort',
        'roomType',
        'facilities',
        'images'
    ])
    ->latest()
    ->paginate(20);

    $resorts = Resort::where('status',1)->get();

    $roomTypes = RoomType::latest()->get();

    $facilities = Facility::where('status',1)
                    ->where('type','room')
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

public function store(Request $request)
{
    $request->validate([

        'resort_id'        => 'required|exists:resorts,id',

        'room_type_id'     => 'nullable|exists:room_types,id',

        'name'             => 'required|max:255',

        'room_no'          => 'nullable|max:100',

        'description'      => 'nullable',

        'price'            => 'required|numeric',

        'discount_price'   => 'nullable|numeric',

        'extra_bed_price'  => 'nullable|numeric',

        'total_rooms'      => 'required|integer|min:1',

        'max_adult'        => 'required|integer|min:1',

        'max_child'        => 'required|integer|min:0',

        'beds'             => 'required|integer|min:1',

        'bathrooms'        => 'required|integer|min:1',

        'size'             => 'nullable|numeric',

        'size_unit'        => 'nullable|max:50',

        'view_type'        => 'nullable|max:100',

        'featured_image'   => 'nullable|image|max:4096',

        'images.*'         => 'nullable|image|max:4096',

        'facilities'       => 'nullable|array',

        'status'           => 'required|boolean',

    ]);

    DB::beginTransaction();

    try{

        $room = new Room();

        $room->resort_id = $request->resort_id;

        $room->room_type_id = $request->room_type_id;

        $room->name = $request->name;

        $room->slug = Str::slug($request->name).'-'.time();

        $room->room_no = $request->room_no;

        $room->description = $request->description;

        $room->price = $request->price;

        $room->discount_price = $request->discount_price;

        $room->extra_bed_price = $request->extra_bed_price;

        $room->total_rooms = $request->total_rooms;

        $room->max_adult = $request->max_adult;

        $room->max_child = $request->max_child;

        $room->beds = $request->beds;

        $room->bathrooms = $request->bathrooms;

        $room->size = $request->size;

        $room->size_unit = $request->size_unit;

        $room->view_type = $request->view_type;

        $room->is_featured = $request->has('is_featured');

        $room->status = $request->status;

        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if($request->hasFile('featured_image')){

            $room->featured_image =
                $request->file('featured_image')
                ->store('rooms','public');

        }

        $room->save();

        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        if($request->filled('facilities')){

            $room->facilities()->sync(
                $request->facilities
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Gallery Images
        |--------------------------------------------------------------------------
        */

        if($request->hasFile('images')){

            foreach($request->file('images') as $image){

                RoomImage::create([

                    'room_id'=>$room->id,

                    'image'=>$image
                        ->store('rooms/gallery','public'),

                ]);

            }

        }

        DB::commit();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success','Room Added Successfully');

    }

    catch(\Exception $e){

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                $e->getMessage()
            );

    }
}

public function update(Request $request, Room $room)
{
    $request->validate([

        'resort_id'        => 'required|exists:resorts,id',

        'room_type_id'     => 'nullable|exists:room_types,id',

        'name'             => 'required|max:255',

        'room_no'          => 'nullable|max:100',

        'description'      => 'nullable',

        'price'            => 'required|numeric',

        'discount_price'   => 'nullable|numeric',

        'extra_bed_price'  => 'nullable|numeric',

        'total_rooms'      => 'required|integer|min:1',

        'max_adult'        => 'required|integer|min:1',

        'max_child'        => 'required|integer|min:0',

        'beds'             => 'required|integer|min:1',

        'bathrooms'        => 'required|integer|min:1',

        'size'             => 'nullable|numeric',

        'size_unit'        => 'nullable|max:50',

        'view_type'        => 'nullable|max:100',

        'featured_image'   => 'nullable|image|max:4096',

        'images.*'         => 'nullable|image|max:4096',

        'facilities'       => 'nullable|array',

        'status'           => 'required|boolean',

    ]);

    DB::beginTransaction();

    try {

        $room->update([

            'resort_id'        => $request->resort_id,
            'room_type_id'     => $request->room_type_id,
            'name'             => $request->name,
            'slug'             => Str::slug($request->name).'-'.$room->id,
            'room_no'          => $request->room_no,
            'description'      => $request->description,
            'price'            => $request->price,
            'discount_price'   => $request->discount_price,
            'extra_bed_price'  => $request->extra_bed_price,
            'total_rooms'      => $request->total_rooms,
            'max_adult'        => $request->max_adult,
            'max_child'        => $request->max_child,
            'beds'             => $request->beds,
            'bathrooms'        => $request->bathrooms,
            'size'             => $request->size,
            'size_unit'        => $request->size_unit,
            'view_type'        => $request->view_type,
            'is_featured'      => $request->has('is_featured'),
            'status'           => $request->status,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            if ($room->featured_image && Storage::disk('public')->exists($room->featured_image)) {

                Storage::disk('public')->delete($room->featured_image);

            }

            $room->featured_image = $request->file('featured_image')
                ->store('rooms', 'public');

            $room->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        $room->facilities()->sync($request->facilities ?? []);

        /*
        |--------------------------------------------------------------------------
        | Gallery Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                RoomImage::create([

                    'room_id' => $room->id,

                    'image' => $image
                        ->store('rooms/gallery', 'public'),

                ]);

            }

        }

        DB::commit();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room Updated Successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());

    }
}

public function destroy(Room $room)
{
    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Delete Featured Image
        |--------------------------------------------------------------------------
        */

        if ($room->featured_image &&
            Storage::disk('public')->exists($room->featured_image)) {

            Storage::disk('public')->delete($room->featured_image);

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Gallery Images
        |--------------------------------------------------------------------------
        */

        foreach ($room->images as $image) {

            if (Storage::disk('public')->exists($image->image)) {

                Storage::disk('public')->delete($image->image);

            }

            $image->delete();

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Facilities
        |--------------------------------------------------------------------------
        */

        $room->facilities()->detach();

        /*
        |--------------------------------------------------------------------------
        | Delete Room
        |--------------------------------------------------------------------------
        */

        $room->delete();

        DB::commit();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room Deleted Successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->with('error', $e->getMessage());

    }
}

public function show(Room $room)
{
    $room->load([
        'resort',
        'roomType',
        'facilities',
        'images',
        'prices',
        'availabilities'
    ]);

    return response()->json([
        'success' => true,
        'room' => $room,
    ]);
}

public function deleteGalleryImage($id)
{
    try {

        $image = RoomImage::findOrFail($id);

        if (
            $image->image &&
            Storage::disk('public')->exists($image->image)
        ) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image Deleted Successfully'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ],500);

    }
}

public function toggleStatus(Room $room)
{
    try{

        $room->status = !$room->status;

        $room->save();

        return response()->json([

            'success'=>true,

            'status'=>$room->status,

            'message'=>'Status Updated Successfully'

        ]);

    }catch(\Exception $e){

        return response()->json([

            'success'=>false,

            'message'=>$e->getMessage()

        ],500);

    }
}

public function getRoomsByResort($id)
{

    $rooms = Room::where('resort_id',$id)
            ->where('status',1)
            ->orderBy('name')
            ->get();

    return response()->json([

        'success'=>true,

        'rooms'=>$rooms

    ]);

}

}