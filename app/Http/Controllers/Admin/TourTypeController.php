<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourTypeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $tourTypes = TourType::latest()->get();

        return view(
            'admin.tour-type.index',
            compact('tourTypes')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.tour-type.create');
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
                'unique:tour_types,name',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'nullable',
                'in:0,1',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $uploadPath = public_path('uploads/tour-types');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageName =
                'tour_type_' .
                time() .
                '_' .
                Str::random(6) .
                '.' .
                $image->getClientOriginalExtension();

            $image->move(
                $uploadPath,
                $imageName
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Tour Type
        |--------------------------------------------------------------------------
        */

        TourType::create([

            'name' => $validated['name'],

            'slug' => Str::slug($validated['name']),

            'icon' => $validated['icon'] ?? null,

            'image' => $imageName,

            'short_description' =>
                $validated['short_description'] ?? null,

            'sort_order' =>
                $validated['sort_order'] ?? 0,

            'status' =>
                isset($validated['status'])
                    ? (int) $validated['status']
                    : 1,
        ]);


        return redirect()
            ->route('admin.tour-types.index')
            ->with(
                'success',
                'Tour Type Created Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $tourType = TourType::findOrFail($id);

        return view(
            'admin.tour-type.edit',
            compact('tourType')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $tourType = TourType::findOrFail($id);


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
                'unique:tour_types,name,' . $tourType->id,
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'nullable',
                'in:0,1',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Existing Image
        |--------------------------------------------------------------------------
        */

        $imageName = $tourType->image;


        /*
        |--------------------------------------------------------------------------
        | New Image Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $oldImagePath = public_path(
                'uploads/tour-types/' . $tourType->image
            );

            if (
                $tourType->image &&
                file_exists($oldImagePath)
            ) {
                unlink($oldImagePath);
            }


            $image = $request->file('image');

            $uploadPath = public_path('uploads/tour-types');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }


            $imageName =
                'tour_type_' .
                time() .
                '_' .
                Str::random(6) .
                '.' .
                $image->getClientOriginalExtension();


            $image->move(
                $uploadPath,
                $imageName
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Tour Type
        |--------------------------------------------------------------------------
        */

        $tourType->update([

            'name' => $validated['name'],

            'slug' => Str::slug($validated['name']),

            'icon' => $validated['icon'] ?? null,

            'image' => $imageName,

            'short_description' =>
                $validated['short_description'] ?? null,

            'sort_order' =>
                $validated['sort_order'] ?? 0,

            'status' =>
                isset($validated['status'])
                    ? (int) $validated['status']
                    : 1,
        ]);


        return redirect()
            ->route('admin.tour-types.index')
            ->with(
                'success',
                'Tour Type Updated Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $tourType = TourType::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if ($tourType->image) {

            $imagePath = public_path(
                'uploads/tour-types/' . $tourType->image
            );

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Record
        |--------------------------------------------------------------------------
        */

        $tourType->delete();


        return redirect()
            ->route('admin.tour-types.index')
            ->with(
                'success',
                'Tour Type Deleted Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MODAL DATA
    |--------------------------------------------------------------------------
    */

    public function modalData($id)
    {
        $tourType = TourType::findOrFail($id);

        return response()->json([
            'success' => true,
            'tourType' => $tourType,
        ]);
    }
}