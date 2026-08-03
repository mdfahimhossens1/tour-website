<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resort;
use App\Models\ResortImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResortImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $images = ResortImage::with('resort')
        ->latest()
        ->paginate(20);

    $resorts = Resort::orderBy('name')->get();

    return view(
        'admin.resort_images.index',
        compact('images', 'resorts')
    );
}

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $resorts = Resort::orderBy('name')->get();

    return view(
        'admin.resort_images.create',
        compact('resorts')
    );
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([

    'resort_id' => 'required|exists:resorts,id',

    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',

    'is_cover' => 'nullable|boolean',

    'sort_order' => 'nullable|integer',

]);

$image = null;

if ($request->hasFile('image')) {

    $image = $request
        ->file('image')
        ->store('resorts/gallery', 'public');
}

ResortImage::create([

    'resort_id' => $request->resort_id,

    'image' => $image,

    'is_cover' => $request->boolean('is_cover'),

    'sort_order' => $request->sort_order ?? 0,

]);

return redirect()
    ->route('admin.resort-images.index')
    ->with(
        'success',
        'Resort Gallery Image Added Successfully.'
    );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    $image = ResortImage::findOrFail($id);

    $resorts = Resort::orderBy('name')->get();

    return view(
        'admin.resort_images.edit',
        compact('image', 'resorts')
    );
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $request->validate([

    'resort_id' => 'required|exists:resorts,id',

    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

    'is_cover' => 'nullable|boolean',

    'sort_order' => 'nullable|integer',

]);

$imagePath = $image->image;

if ($request->hasFile('image')) {

    if (
        $imagePath &&
        Storage::disk('public')->exists($imagePath)
    ) {

        Storage::disk('public')->delete($imagePath);

    }

    $imagePath = $request
        ->file('image')
        ->store('resorts/gallery', 'public');
}

$image->update([

    'resort_id' => $request->resort_id,

    'image' => $imagePath,

    'is_cover' => $request->boolean('is_cover'),

    'sort_order' => $request->sort_order ?? 0,

]);

return redirect()
    ->route('admin.resort-images.index')
    ->with(
        'success',
        'Gallery Image Updated Successfully.'
    );
    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $image = ResortImage::findOrFail($id);

    if (
        $image->image &&
        Storage::disk('public')->exists($image->image)
    ) {

        Storage::disk('public')->delete($image->image);

    }

    $image->delete();

    return redirect()
        ->route('admin.resort-images.index')
        ->with(
            'success',
            'Gallery Image Deleted Successfully.'
        );
}
}
