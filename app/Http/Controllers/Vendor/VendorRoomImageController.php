<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorRoomImageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Gallery
    |--------------------------------------------------------------------------
    */

    public function index(Room $room)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $room->resort->vendor_id == $vendor->id,
            403
        );

        $images = $room->images()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view(
            'vendor.room-images.index',
            compact(
                'room',
                'images'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Image
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Room $room
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $room->resort->vendor_id == $vendor->id,
            403
        );

        $request->validate([

            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'is_cover' => [
                'nullable',
                'boolean',
            ],

        ]);

        DB::transaction(function () use (
            $request,
            $room
        ) {

            if ($request->boolean('is_cover')) {

                $room->images()
                    ->update([
                        'is_cover' => false
                    ]);
            }

            $path = $request
                ->file('image')
                ->store(
                    'rooms/gallery',
                    'public'
                );

            $room->images()->create([

                'image' => $path,

                'is_cover' => $request->boolean('is_cover'),

                'sort_order' =>
                    ($room->images()->max('sort_order') ?? 0) + 1,

            ]);
        });

        return back()->with(
            'success',
            'Image uploaded successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Set Cover
    |--------------------------------------------------------------------------
    */

    public function setCover(
        RoomImage $image
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $image->room->resort->vendor_id == $vendor->id,
            403
        );

        DB::transaction(function () use ($image) {

            $image->room
                ->images()
                ->update([
                    'is_cover' => false
                ]);

            $image->update([
                'is_cover' => true
            ]);
        });

        return back()->with(
            'success',
            'Cover image updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Order
    |--------------------------------------------------------------------------
    */

    public function updateOrder(
        Request $request,
        RoomImage $image
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $image->room->resort->vendor_id == $vendor->id,
            403
        );

        $request->validate([

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

        ]);

        $image->update([

            'sort_order' =>
                $request->sort_order,

        ]);

        return back()->with(
            'success',
            'Image order updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Image
    |--------------------------------------------------------------------------
    */

    public function destroy(
        RoomImage $image
    ) {

        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $image->room->resort->vendor_id == $vendor->id,
            403
        );

        if (
            Storage::disk('public')->exists(
                $image->image
            )
        ) {

            Storage::disk('public')
                ->delete(
                    $image->image
                );
        }

        $image->delete();

        return back()->with(
            'success',
            'Image deleted successfully.'
        );
    }
}