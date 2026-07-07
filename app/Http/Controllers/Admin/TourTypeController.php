<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\TourType;

class TourTypeController extends Controller
{
    public function index()
    {
        $tourTypes = TourType::latest()->get();

        return view(
            'admin.tour-type.index',
            compact('tourTypes')
        );
    }

    public function create()
    {
        return view('admin.tour-type.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|max:255|unique:tour_types,name',

            'icon' => 'nullable|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'short_description' => 'nullable',

            'sort_order' => 'nullable|integer',

        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = 'tour_type_' . time() . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('uploads/tour-types'),
                $imageName
            );
        }

        TourType::create([

            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'icon' => $request->icon,

            'image' => $imageName,

            'short_description' => $request->short_description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status ?? 1,

        ]);

        return redirect()
            ->route('admin.tour-types.index')
            ->with(
                'success',
                'Tour Type Created Successfully'
            );
    }

    public function edit($id)
    {
        $tourType = TourType::findOrFail($id);

        return view(
            'admin.tour-type.edit',
            compact('tourType')
        );
    }

    public function update(Request $request, $id)
    {
        $tourType = TourType::findOrFail($id);

        $request->validate([

            'name' => 'required|max:255|unique:tour_types,name,' . $id,

            'icon' => 'nullable|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'sort_order' => 'nullable|integer',

        ]);

        $imageName = $tourType->image;

        if ($request->hasFile('image')) {

            if (
                $tourType->image &&
                file_exists(public_path('uploads/tour-types/' . $tourType->image))
            ) {

                unlink(public_path('uploads/tour-types/' . $tourType->image));
            }

            $image = $request->file('image');

            $imageName = 'tour_type_' . time() . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('uploads/tour-types'),
                $imageName
            );
        }

        $tourType->update([

            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'icon' => $request->icon,

            'image' => $imageName,

            'short_description' => $request->short_description,

            'sort_order' => $request->sort_order ?? 0,

            'status' => $request->status ?? 1,

        ]);

        return redirect()
            ->route('admin.tour-types.index')
            ->with(
                'success',
                'Tour Type Updated Successfully'
            );
    }

    public function destroy($id)
    {
        $tourType = TourType::findOrFail($id);

        if (
            $tourType->image &&
            file_exists(public_path('uploads/tour-types/' . $tourType->image))
        ) {

            unlink(public_path('uploads/tour-types/' . $tourType->image));
        }

        $tourType->delete();

        return redirect()
            ->route('admin.tour-types.index')
            ->with(
                'success',
                'Tour Type Deleted Successfully'
            );
    }

    // Modal data for AJAX
    public function modalData($id)
    {
        $tourType = TourType::findOrFail($id);
        return response()->json(['tourType' => $tourType]);
    }
}