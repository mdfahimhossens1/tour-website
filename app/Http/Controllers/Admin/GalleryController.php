<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Tour;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('tour')
            ->latest()
            ->paginate(20);

        // একই page-এর upload modal-এ tours লাগবে
        $tours = Tour::orderBy('title')->get();

        return view('admin.gallery.index', compact('galleries', 'tours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'type'    => 'required|in:image,video',
            'media'   => 'required|file|max:51200',
        ]);

        // Type অনুযায়ী validation
        if ($request->type === 'image') {
            $request->validate([
                'media' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
        } else {
            $request->validate([
                'media' => 'mimes:mp4,mov,avi,webm|max:51200',
            ]);
        }

        $folder = public_path('uploads/gallery');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $file = $request->file('media');

        $fileName = time() . '_' . uniqid() . '.' . $file->extension();

        $file->move($folder, $fileName);

        Gallery::create([
            'tour_id' => $request->tour_id,
            'image'   => $fileName, // database column image থাকলেও এখানে video-ও store হবে
            'type'    => $request->type,
        ]);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery media uploaded successfully');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        $filePath = public_path('uploads/gallery/' . $gallery->image);

        if ($gallery->image && file_exists($filePath)) {
            unlink($filePath);
        }

        $gallery->delete();

        return back()->with('success', 'Deleted successfully');
    }
}