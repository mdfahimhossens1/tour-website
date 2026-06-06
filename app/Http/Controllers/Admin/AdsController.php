<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdsController extends Controller
{
    public function index()
    {
        $ads = Ads::latest()->paginate(20);;

        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp',
            'link'        => 'nullable',
            'position'    => 'required',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time().'_'.$image->getClientOriginalName();

            $image->move(
                public_path('uploads/ads'),
                $imageName
            );
        }

        Ads::create([
            'title'      => $request->title,
            'image'      => $imageName,
            'link'       => $request->link,
            'position'   => $request->position,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $request->status ?? 1,
        ]);

        return redirect()
            ->route('admin.ads.index')
            ->with('success','Advertisement Added Successfully');
    }

    public function edit($id)
    {
        $ad = Ads::findOrFail($id);

        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $ad = Ads::findOrFail($id);

        $request->validate([
            'title'      => 'required|max:255',
            'position'   => 'required',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        $imageName = $ad->image;

        if ($request->hasFile('image')) {

            if (
                !empty($ad->image) &&
                File::exists(public_path('uploads/ads/'.$ad->image))
            ) {
                File::delete(
                    public_path('uploads/ads/'.$ad->image)
                );
            }

            $image = $request->file('image');

            $imageName = time().'_'.$image->getClientOriginalName();

            $image->move(
                public_path('uploads/ads'),
                $imageName
            );
        }

        $ad->update([
            'title'      => $request->title,
            'image'      => $imageName,
            'link'       => $request->link,
            'position'   => $request->position,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $request->status ?? 0,
        ]);

        return redirect()
            ->route('admin.ads.index')
            ->with('success','Advertisement Updated Successfully');
    }

    public function destroy($id)
    {
        $ad = Ads::findOrFail($id);

        if (
            !empty($ad->image) &&
            File::exists(public_path('uploads/ads/'.$ad->image))
        ) {
            File::delete(
                public_path('uploads/ads/'.$ad->image)
            );
        }

        $ad->delete();

        return redirect()
            ->back()
            ->with('success','Advertisement Deleted Successfully');
    }
}