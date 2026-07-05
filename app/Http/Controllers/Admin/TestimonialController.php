<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'designation' => 'nullable|string|max:255',
        'message' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imageName = null;

    if ($request->hasFile('image')) {

        $folder = public_path('uploads/testimonials');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $image = $request->file('image');

        $imageName = 'testimonial_' . time() . '.' . $image->getClientOriginalExtension();

        $image->move($folder, $imageName);
    }

    Testimonial::create([
        'name' => $request->name,
        'designation' => $request->designation,
        'message' => $request->message,
        'rating' => $request->rating,
        'image' => $imageName,
        'status' => true,
    ]);

    return redirect()
        ->route('admin.testimonials.index')
        ->with('success', 'Testimonial created successfully.');
}

public function destroy($id)
{
    $testimonial = Testimonial::findOrFail($id);

    if ($testimonial->image) {

        $path = public_path('uploads/testimonials/' . $testimonial->image);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    $testimonial->delete();

    return back()->with('success', 'Deleted successfully.');
}

public function edit($id)
{
    $testimonial = Testimonial::findOrFail($id);

    return view('admin.testimonials.edit', compact('testimonial'));
}

public function update(Request $request, $id)
{
    $testimonial = Testimonial::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'designation' => 'nullable|string|max:255',
        'message' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imageName = $testimonial->image;

    if ($request->hasFile('image')) {

        // old image delete
        if ($testimonial->image) {

            $oldPath = public_path('uploads/testimonials/' . $testimonial->image);

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // upload new image
        $folder = public_path('uploads/testimonials');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $image = $request->file('image');

        $imageName = 'testimonial_' . time() . '.' . $image->getClientOriginalExtension();

        $image->move($folder, $imageName);
    }

    $testimonial->update([
        'name' => $request->name,
        'designation' => $request->designation,
        'message' => $request->message,
        'rating' => $request->rating,
        'image' => $imageName,
    ]);

    return redirect()
        ->route('admin.testimonials.index')
        ->with('success', 'Testimonial updated successfully.');
}
}