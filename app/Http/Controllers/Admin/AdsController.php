<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdsController extends Controller
{
    /**
     * Advertisement List
     */
    public function index()
    {
        $ads = Ads::latest()->paginate(20);

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Create Advertisement
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store Advertisement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'link' => 'nullable|url|max:2048',

            'position' => [
                'required',
                'in:home_top,home_middle,packages_top,tour_details,blog',
            ],

            'start_date' => 'nullable|date',

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => 'nullable|boolean',
        ]);

        $imageName = null;

        /*
        |--------------------------------------------------------------------------
        | Upload Advertisement Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $uploadPath = public_path('uploads/ads');

            if (!File::exists($uploadPath)) {
                File::makeDirectory(
                    $uploadPath,
                    0755,
                    true
                );
            }

            $imageName = time() . '_' . uniqid() . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                $uploadPath,
                $imageName
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Advertisement
        |--------------------------------------------------------------------------
        */

        Ads::create([
            'title' => $validated['title'],

            'image' => $imageName,

            'link' => $validated['link'] ?? null,

            'position' => $validated['position'],

            'start_date' => $validated['start_date'] ?? null,

            'end_date' => $validated['end_date'] ?? null,

            'status' => $request->has('status')
                ? (int) $request->status
                : 1,
        ]);

        return redirect()
            ->route('admin.ads.index')
            ->with(
                'success',
                'Advertisement Added Successfully'
            );
    }

    /**
     * Edit Advertisement
     */
    public function edit($id)
    {
        $ad = Ads::findOrFail($id);

        return view(
            'admin.ads.edit',
            compact('ad')
        );
    }

    /**
     * Update Advertisement
     */
    public function update(
        Request $request,
        $id
    ) {
        $ad = Ads::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'link' => 'nullable|url|max:2048',

            'position' => [
                'required',
                'in:home_top,home_middle,packages_top,tour_details,blog',
            ],

            'start_date' => 'nullable|date',

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => 'nullable|boolean',
        ]);

        $imageName = $ad->image;

        /*
        |--------------------------------------------------------------------------
        | Replace Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $oldImage = public_path(
                'uploads/ads/' . $ad->image
            );

            if (
                !empty($ad->image) &&
                File::exists($oldImage)
            ) {
                File::delete($oldImage);
            }

            $image = $request->file('image');

            $uploadPath = public_path('uploads/ads');

            if (!File::exists($uploadPath)) {
                File::makeDirectory(
                    $uploadPath,
                    0755,
                    true
                );
            }

            $imageName = time() . '_' . uniqid() . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                $uploadPath,
                $imageName
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Advertisement
        |--------------------------------------------------------------------------
        */

        $ad->update([
            'title' => $validated['title'],

            'image' => $imageName,

            'link' => $validated['link'] ?? null,

            'position' => $validated['position'],

            'start_date' => $validated['start_date'] ?? null,

            'end_date' => $validated['end_date'] ?? null,

            'status' => $request->has('status')
                ? (int) $request->status
                : 0,
        ]);

        return redirect()
            ->route('admin.ads.index')
            ->with(
                'success',
                'Advertisement Updated Successfully'
            );
    }

    /**
     * Delete Advertisement
     */
    public function destroy($id)
    {
        $ad = Ads::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (!empty($ad->image)) {

            $imagePath = public_path(
                'uploads/ads/' . $ad->image
            );

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $ad->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Advertisement Deleted Successfully'
            );
    }
}