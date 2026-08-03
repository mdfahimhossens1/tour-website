<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorRoomImageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Room Images
    |--------------------------------------------------------------------------
    */
    public function index(Room $room)
    {
        $this->authorizeRoom($room);

        $images = $room->images()
            ->orderBy('sort_order')
            ->get();

        return view(
            'vendor.room-images.index',
            compact('room', 'images')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store New Image
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, Room $room)
    {
        $this->authorizeRoom($room);

        $validated = $request->validate([
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


        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $path = $request
            ->file('image')
            ->store('rooms/gallery', 'public');


        /*
        |--------------------------------------------------------------------------
        | Sort Order
        |--------------------------------------------------------------------------
        */

        $sortOrder = ($room->images()->max('sort_order') ?? 0) + 1;


        /*
        |--------------------------------------------------------------------------
        | Cover Image
        |--------------------------------------------------------------------------
        */

        $isCover = $request->boolean('is_cover');


        if ($isCover) {

            $room->images()->update([
                'is_cover' => false,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Image
        |--------------------------------------------------------------------------
        */

        $room->images()->create([
            'image' => $path,
            'is_cover' => $isCover,
            'sort_order' => $sortOrder,
        ]);


        return redirect()
            ->route(
                'vendor.room-images.index',
                $room
            )
            ->with(
                'success',
                'Room image uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Set Cover Image
    |--------------------------------------------------------------------------
    */
    public function setCover(RoomImage $roomImage)
    {
        $roomImage->load('room');

        $this->authorizeRoom(
            $roomImage->room
        );


        /*
        | Remove Previous Cover
        */

        $roomImage->room
            ->images()
            ->update([
                'is_cover' => false,
            ]);


        /*
        | Set New Cover
        */

        $roomImage->update([
            'is_cover' => true,
        ]);


        return back()
            ->with(
                'success',
                'Room cover image updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Sort Order
    |--------------------------------------------------------------------------
    */
    public function updateOrder(
        Request $request,
        RoomImage $roomImage
    ) {
        $roomImage->load('room');

        $this->authorizeRoom(
            $roomImage->room
        );


        $validated = $request->validate([
            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);


        $roomImage->update([
            'sort_order' => $validated['sort_order'],
        ]);


        return back()
            ->with(
                'success',
                'Image order updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Image
    |--------------------------------------------------------------------------
    */
    public function destroy(RoomImage $roomImage)
    {
        $roomImage->load('room');

        $this->authorizeRoom(
            $roomImage->room
        );


        /*
        | Delete Physical File
        */

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


        /*
        | Delete Database Record
        */

        $roomImage->delete();


        return back()
            ->with(
                'success',
                'Room image deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Vendor Room Authorization
    |--------------------------------------------------------------------------
    */
    private function authorizeRoom(Room $room): void
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        | Load Resort
        */

        $room->loadMissing('resort');


        /*
        | Make Sure Room Belongs To Vendor's Resort
        */

        abort_unless(
            $room->resort &&
            $room->resort->vendor_id === $vendor->id,
            403,
            'Unauthorized access.'
        );
    }
}
