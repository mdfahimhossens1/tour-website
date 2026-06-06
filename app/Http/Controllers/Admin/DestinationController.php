<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * ALL DESTINATIONS
     */
    public function index()
    {
        $destinations = Destination::latest()->get();

        return view('admin.destination.index', compact('destinations'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('admin.destinations.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|unique:destinations,name',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $imageName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/destinations'), $imageName);
        }

        Destination::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $imageName,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination Added Successfully');
    }

    /**
     * EDIT
     */
    public function edit($slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();

        return view('admin.destination.edit', compact('destination'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name'  => 'required|unique:destinations,name,' . $destination->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $imageName = $destination->image;

        if ($request->hasFile('image')) {

            // পুরনো image delete করো
            if ($destination->image && file_exists(public_path('uploads/destinations/' . $destination->image))) {
                unlink(public_path('uploads/destinations/' . $destination->image));
            }

            $file = $request->file('image');

            $imageName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/destinations'), $imageName);
        }

        $destination->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $imageName,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination Updated Successfully');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);

        if ($destination->image && file_exists(public_path('uploads/destinations/' . $destination->image))) {
            unlink(public_path('uploads/destinations/' . $destination->image));
        }

        $destination->delete();

        return back()->with('success', 'Destination Deleted Successfully');
    }
}