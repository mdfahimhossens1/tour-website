<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('tour')
            ->latest()
            ->paginate(20);

        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        // যদি tour select করতে চাও future এ
        $tours = \App\Models\Tour::all();

        return view('admin.gallery.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'image'   => 'required|image',
            'type'    => 'nullable|in:image,video',
        ]);

        $imageName = time().'_gallery.'.$request->image->extension();

        $request->image->move(
            public_path('uploads/gallery'),
            $imageName
        );

        Gallery::create([
            'tour_id' => $request->tour_id,
            'image'   => $imageName,
            'type'    => $request->type ?? 'image',
        ]);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery uploaded successfully');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if (
            $gallery->image &&
            file_exists(public_path('uploads/gallery/'.$gallery->image))
        ) {
            unlink(public_path('uploads/gallery/'.$gallery->image));
        }

        $gallery->delete();

        return back()->with('success', 'Deleted successfully');
    }
}