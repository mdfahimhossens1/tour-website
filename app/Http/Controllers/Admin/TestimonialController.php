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
            'name' => 'required',
            'message' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time().'_testimonial.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/testimonials'),
                $imageName
            );
        }

        Testimonial::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'message' => $request->message,
            'rating' => $request->rating,
            'image' => $imageName,
            'status' => 1
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success','Testimonial created successfully');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->image && file_exists(public_path('uploads/testimonials/'.$testimonial->image))) {
            unlink(public_path('uploads/testimonials/'.$testimonial->image));
        }

        $testimonial->delete();

        return back()->with('success','Deleted successfully');
    }
}