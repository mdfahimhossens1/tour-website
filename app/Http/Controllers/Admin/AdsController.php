<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdsController extends Controller
{
    /**
     * Allowed advertisement positions.
     */
    private const POSITIONS = [
        'home_top',
        'home_middle',
        'packages_top',
        'tour_details',
        'blog',
        'blog_inline_1',
        'blog_inline_2',
    ];

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
        $validated = $this->validateAd($request);

        $imageName = $this->uploadImage($request);

        Ads::create([
            'title' => $validated['title'],
            'image' => $imageName,
            'link' => $validated['link'] ?? null,
            'position' => $validated['position'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Advertisement Added Successfully');
    }

    /**
     * Edit Advertisement
     */
    public function edit($id)
    {
        $ad = Ads::findOrFail($id);

        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update Advertisement
     */
    public function update(Request $request, $id)
    {
        $ad = Ads::findOrFail($id);

        $validated = $this->validateAd($request, true);

        $imageName = $ad->image;

        if ($request->hasFile('image')) {
            $this->deleteImage($ad->image);
            $imageName = $this->uploadImage($request);
        }

        $ad->update([
            'title' => $validated['title'],
            'image' => $imageName,
            'link' => $validated['link'] ?? null,
            'position' => $validated['position'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Advertisement Updated Successfully');
    }

    /**
     * Delete Advertisement
     */
    public function destroy($id)
    {
        $ad = Ads::findOrFail($id);

        $this->deleteImage($ad->image);

        $ad->delete();

        return redirect()
            ->back()
            ->with('success', 'Advertisement Deleted Successfully');
    }

    /**
     * Validate Advertisement Request
     */
    private function validateAd(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'link' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'position' => [
                'required',
                'string',
                'in:' . implode(',', self::POSITIONS),
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    /**
     * Upload Advertisement Image
     */
    private function uploadImage(Request $request): string
    {
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

        $image->move($uploadPath, $imageName);

        return $imageName;
    }

    /**
     * Delete Advertisement Image
     */
    private function deleteImage(?string $imageName): void
    {
        if (empty($imageName)) {
            return;
        }

        $imagePath = public_path(
            'uploads/ads/' . $imageName
        );

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }
}