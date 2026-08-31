<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourType;

class TourPackageController extends Controller
{
    /**
     * ---------------------------------------------------------
     * INDEX
     * ---------------------------------------------------------
     */
    public function index()
    {
        $tours = Tour::with(['destination', 'tourType'])
            ->latest()
            ->get();

        $destinations = Destination::latest()->get();

        $tourTypes = TourType::latest()->get();

        return view('admin.tour.index', compact(
            'tours',
            'destinations',
            'tourTypes'
        ));
    }


    /**
     * ---------------------------------------------------------
     * CREATE
     * ---------------------------------------------------------
     */
    public function create()
    {
        $destinations = Destination::latest()->get();
        $tourTypes = TourType::latest()->get();

        return view('admin.tour.create', compact(
            'destinations',
            'tourTypes'
        ));
    }


    /**
     * ---------------------------------------------------------
     * STORE
     * ---------------------------------------------------------
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'destination_id' => 'required|exists:destinations,id',

            'tour_type_id' => 'required|exists:tour_types,id',

            'title' => 'required|string|max:255',

            'price' => 'required|numeric|min:0',

            'discount_price' => 'nullable|numeric|min:0',

            'duration' => 'nullable|string|max:255',

            'location' => 'nullable|string|max:255',

            'max_seat' => 'nullable|integer|min:0',

            'featured_image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'short_description' => 'nullable|string',

            'description' => 'nullable|string',

            'included' => 'nullable|string',

            'excluded' => 'nullable|string',

            'tour_plan' => 'nullable|string',

            'map_iframe' => 'nullable|string',

            'hotel_name' => 'nullable|string',

            'food_menu' => 'nullable|string',

            'backpack_price' => 'required|numeric|min:0',

            'moderate_price' => 'required|numeric|min:0',

            'luxury_price' => 'required|numeric|min:0',

            'ai_highlights' => 'nullable|string',

            'is_featured' => 'nullable|boolean',

            'status' => 'nullable|boolean',
        ]);


        /**
         * IMAGE
         */
        $imageName = null;

        if ($request->hasFile('featured_image')) {

            $image = $request->file('featured_image');

            $imageName =
                'tour_' .
                time() .
                '_' .
                uniqid() .
                '.' .
                $image->getClientOriginalExtension();

            $uploadPath = public_path('uploads/tours');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move(
                $uploadPath,
                $imageName
            );
        }


        /**
         * UNIQUE SLUG
         */
        $slug = Str::slug($request->title);

        if (
            Tour::where('slug', $slug)->exists()
        ) {
            $slug .= '-' . uniqid();
        }


        /**
         * CREATE
         */
        Tour::create([

            'destination_id' => $request->destination_id,

            'tour_type_id' => $request->tour_type_id,

            'title' => $request->title,

            'slug' => $slug,

            'short_description' =>
                $request->short_description,

            'description' =>
                $request->description,

            'price' =>
                $request->price,

            'discount_price' =>
                $request->discount_price,

            'duration' =>
                $request->duration,

            'location' =>
                $request->location,

            'featured_image' =>
                $imageName,

            'included' =>
                $request->included,

            'excluded' =>
                $request->excluded,

            'tour_plan' =>
                $request->tour_plan,

            'max_seat' =>
                $request->max_seat ?? 0,

            'map_iframe' =>
                $request->map_iframe,

            'hotel_name' =>
                $request->hotel_name,

            'food_menu' =>
                $request->food_menu,

            'backpack_price' =>
                $request->backpack_price,

            'moderate_price' =>
                $request->moderate_price,

            'luxury_price' =>
                $request->luxury_price,

            'ai_highlights' =>
                $request->ai_highlights,

            'is_featured' =>
                $request->is_featured ?? 0,

            'status' =>
                $request->status ?? 1,

        ]);


        return redirect()
            ->route('admin.tours.index')
            ->with(
                'success',
                'Tour Package Added Successfully'
            );
    }


    /**
     * ---------------------------------------------------------
     * SHOW
     * ---------------------------------------------------------
     */
    public function show($slug)
    {
        $tour = Tour::with([
            'destination',
            'tourType'
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        return view(
            'admin.tour.view',
            compact('tour')
        );
    }


    /**
     * ---------------------------------------------------------
     * EDIT PAGE
     * ---------------------------------------------------------
     */
    public function edit($slug)
    {
        $tour = Tour::with([
            'destination',
            'tourType'
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        $destinations =
            Destination::latest()->get();

        $tourTypes =
            TourType::latest()->get();

        return view(
            'admin.tour.edit',
            compact(
                'tour',
                'destinations',
                'tourTypes'
            )
        );
    }


    /**
     * ---------------------------------------------------------
     * UPDATE
     *
     * IMPORTANT:
     * Modal update is ID based.
     * ---------------------------------------------------------
     */
    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);


        $request->validate([

            'destination_id' =>
                'required|exists:destinations,id',

            'tour_type_id' =>
                'required|exists:tour_types,id',

            'title' =>
                'required|string|max:255',

            'price' =>
                'required|numeric|min:0',

            'discount_price' =>
                'nullable|numeric|min:0',

            'duration' =>
                'nullable|string|max:255',

            'location' =>
                'nullable|string|max:255',

            'max_seat' =>
                'nullable|integer|min:0',

            'featured_image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'short_description' =>
                'nullable|string',

            'description' =>
                'nullable|string',

            'included' =>
                'nullable|string',

            'excluded' =>
                'nullable|string',

            'tour_plan' =>
                'nullable|string',

            'map_iframe' =>
                'nullable|string',

            'hotel_name' =>
                'nullable|string',

            'food_menu' =>
                'nullable|string',

            'backpack_price' =>
                'required|numeric|min:0',

            'moderate_price' =>
                'required|numeric|min:0',

            'luxury_price' =>
                'required|numeric|min:0',

            'ai_highlights' =>
                'nullable|string',

            'is_featured' =>
                'nullable|boolean',

            'status' =>
                'nullable|boolean',
        ]);


        /**
         * IMAGE
         */
        $imageName = $tour->featured_image;

        if ($request->hasFile('featured_image')) {

            /**
             * Delete old image
             */
            if (
                $tour->featured_image &&
                file_exists(
                    public_path(
                        'uploads/tours/' .
                        $tour->featured_image
                    )
                )
            ) {
                unlink(
                    public_path(
                        'uploads/tours/' .
                        $tour->featured_image
                    )
                );
            }


            /**
             * Upload new image
             */
            $image =
                $request->file('featured_image');

            $imageName =
                'tour_' .
                time() .
                '_' .
                uniqid() .
                '.' .
                $image->getClientOriginalExtension();

            $uploadPath =
                public_path('uploads/tours');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move(
                $uploadPath,
                $imageName
            );
        }


        /**
         * SLUG
         *
         * Don't create duplicate slug if title
         * hasn't changed.
         */
        $slug = $tour->slug;

        if ($tour->title !== $request->title) {

            $newSlug =
                Str::slug($request->title);

            $existing =
                Tour::where('slug', $newSlug)
                    ->where('id', '!=', $tour->id)
                    ->exists();

            if ($existing) {
                $newSlug .= '-' . uniqid();
            }

            $slug = $newSlug;
        }


        /**
         * UPDATE
         */
        $tour->update([

            'destination_id' =>
                $request->destination_id,

            'tour_type_id' =>
                $request->tour_type_id,

            'title' =>
                $request->title,

            'slug' =>
                $slug,

            'short_description' =>
                $request->short_description,

            'description' =>
                $request->description,

            'price' =>
                $request->price,

            'discount_price' =>
                $request->discount_price,

            'duration' =>
                $request->duration,

            'location' =>
                $request->location,

            'featured_image' =>
                $imageName,

            'included' =>
                $request->included,

            'excluded' =>
                $request->excluded,

            'tour_plan' =>
                $request->tour_plan,

            'max_seat' =>
                $request->max_seat ?? 0,

            'map_iframe' =>
                $request->map_iframe,

            'hotel_name' =>
                $request->hotel_name,

            'food_menu' =>
                $request->food_menu,

            'backpack_price' =>
                $request->backpack_price,

            'moderate_price' =>
                $request->moderate_price,

            'luxury_price' =>
                $request->luxury_price,

            'ai_highlights' =>
                $request->ai_highlights,

            'is_featured' =>
                $request->is_featured ?? 0,

            'status' =>
                $request->status ?? 1,

        ]);


        return redirect()
            ->route('admin.tours.index')
            ->with(
                'success',
                'Tour Updated Successfully'
            );
    }


    /**
     * ---------------------------------------------------------
     * DELETE
     * ---------------------------------------------------------
     */
    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);


        if (
            $tour->featured_image &&
            file_exists(
                public_path(
                    'uploads/tours/' .
                    $tour->featured_image
                )
            )
        ) {
            unlink(
                public_path(
                    'uploads/tours/' .
                    $tour->featured_image
                )
            );
        }


        $tour->delete();


        return redirect()
            ->route('admin.tours.index')
            ->with(
                'success',
                'Tour Deleted Successfully'
            );
    }


    /**
     * ---------------------------------------------------------
     * MODAL DATA
     * ---------------------------------------------------------
     */
    public function modalData($id)
    {
        $tour = Tour::with([
            'destination',
            'tourType'
        ])->findOrFail($id);


        $destinations =
            Destination::orderBy('name')->get();

        $tourTypes =
            TourType::orderBy('name')->get();


        return response()->json([

            'tour' => [

                'id' =>
                    $tour->id,

                'title' =>
                    $tour->title,

                'slug' =>
                    $tour->slug,

                'destination_id' =>
                    $tour->destination_id,

                'destination_name' =>
                    optional(
                        $tour->destination
                    )->name ?? 'N/A',

                'tour_type_id' =>
                    $tour->tour_type_id,

                'tour_type_name' =>
                    optional(
                        $tour->tourType
                    )->name ?? 'N/A',

                'price' =>
                    $tour->price,

                'discount_price' =>
                    $tour->discount_price,

                'duration' =>
                    $tour->duration,

                'location' =>
                    $tour->location,

                'max_seat' =>
                    $tour->max_seat,

                'is_featured' =>
                    $tour->is_featured,

                'status' =>
                    $tour->status,

                'approval_status' =>
                    $tour->approval_status,

                'featured_image' =>
                    $tour->featured_image,

                'short_description' =>
                    $tour->short_description,

                'description' =>
                    $tour->description,

                'included' =>
                    $tour->included,

                'excluded' =>
                    $tour->excluded,

                'hotel_name' =>
                    $tour->hotel_name,

                'food_menu' =>
                    $tour->food_menu,

                'backpack_price' =>
                    $tour->backpack_price,

                'moderate_price' =>
                    $tour->moderate_price,

                'luxury_price' =>
                    $tour->luxury_price,

                'ai_highlights' =>
                    $tour->ai_highlights,

                'tour_plan' =>
                    $tour->tour_plan,

                'map_iframe' =>
                    $tour->map_iframe,
            ],


            'destinations' =>
                $destinations->map(
                    fn ($d) => [
                        'id' =>
                            $d->id,
                        'name' =>
                            $d->name,
                    ]
                )->values(),


            'tourTypes' =>
                $tourTypes->map(
                    fn ($t) => [
                        'id' =>
                            $t->id,
                        'name' =>
                            $t->name,
                    ]
                )->values(),
        ]);
    }


    /**
     * ---------------------------------------------------------
     * APPROVE
     * ---------------------------------------------------------
     */
    public function approve($id)
    {
        $tour = Tour::findOrFail($id);

        $tour->update([

            'approval_status' =>
                'approved',

            'status' =>
                1,

            'approved_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Tour approved successfully'
        );
    }


    /**
     * ---------------------------------------------------------
     * REJECT
     * ---------------------------------------------------------
     */
    public function reject($id)
    {
        $tour = Tour::findOrFail($id);

        $tour->update([

            'approval_status' =>
                'rejected',

            'status' =>
                0,
        ]);


        return back()->with(
            'success',
            'Tour rejected successfully'
        );
    }
}