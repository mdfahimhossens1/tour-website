<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Room Relations
    |--------------------------------------------------------------------------
    */

    private function roomRelations(): array
    {
        return [
            'roomType',
            'prices',
            'facilities',
            'images',
            'resort',
            'resort.destination',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | All Rooms
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Room::with(
            $this->roomRelations()
        );


        /*
        |--------------------------------------------------------------------------
        | Active Rooms Only
        |--------------------------------------------------------------------------
        */

        $query->where(function ($q) {
            $q->where('status', 'active')
              ->orWhere('status', 1)
              ->orWhere('status', '1');
        });


        /*
        |--------------------------------------------------------------------------
        | Resort Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('resort_id')) {

            $query->where(
                'resort_id',
                $request->resort_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Room Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('room_type_id')) {

            $query->where(
                'room_type_id',
                $request->room_type_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Featured Rooms
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('featured')) {

            $query->where(function ($q) {

                $q->where('is_featured', true)
                  ->orWhere('is_featured', 1);

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Keyword Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'name',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'room_no',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'view_type',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhereHas('roomType', function ($roomType) use ($keyword) {

                    $roomType->where(
                        'name',
                        'like',
                        '%' . $keyword . '%'
                    );

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $rooms = $query
            ->latest()
            ->paginate(
                $request->integer('per_page', 12)
            );


        return response()->json([

            'success' => true,

            'data' => RoomResource::collection($rooms),

            'pagination' => [

                'current_page' => $rooms->currentPage(),

                'last_page' => $rooms->lastPage(),

                'per_page' => $rooms->perPage(),

                'total' => $rooms->total(),

            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Featured Rooms
    |--------------------------------------------------------------------------
    */

    public function featured(Request $request)
    {
        $rooms = Room::with(
            $this->roomRelations()
        )

        ->where(function ($q) {

            $q->where('status', 'active')
              ->orWhere('status', 1)
              ->orWhere('status', '1');

        })

        ->where(function ($q) {

            $q->where('is_featured', true)
              ->orWhere('is_featured', 1);

        })

        ->latest()

        ->take(
            $request->integer('limit', 8)
        )

        ->get();


        return response()->json([

            'success' => true,

            'data' => RoomResource::collection($rooms),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Room Details
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $room = Room::with(
            $this->roomRelations()
        )

        ->where(function ($q) {

            $q->where('status', 'active')
              ->orWhere('status', 1)
              ->orWhere('status', '1');

        })

        ->findOrFail($id);


        return response()->json([

            'success' => true,

            'data' => new RoomResource($room),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Room Details By Slug
    |--------------------------------------------------------------------------
    */

    public function showBySlug($slug)
    {
        $room = Room::with(
            $this->roomRelations()
        )

        ->where('slug', $slug)

        ->where(function ($q) {

            $q->where('status', 'active')
              ->orWhere('status', 1)
              ->orWhere('status', '1');

        })

        ->firstOrFail();


        return response()->json([

            'success' => true,

            'data' => new RoomResource($room),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Rooms By Resort
    |--------------------------------------------------------------------------
    */

    public function byResort($resortId)
    {
        $rooms = Room::with(
            $this->roomRelations()
        )

        ->where('resort_id', $resortId)

        ->where(function ($q) {

            $q->where('status', 'active')
              ->orWhere('status', 1)
              ->orWhere('status', '1');

        })

        ->latest()

        ->get();


        return response()->json([

            'success' => true,

            'data' => RoomResource::collection($rooms),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Search Rooms
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $query = Room::with(
            $this->roomRelations()
        );


        /*
        |--------------------------------------------------------------------------
        | Active
        |--------------------------------------------------------------------------
        */

        $query->where(function ($q) {

            $q->where('status', 'active')
              ->orWhere('status', 1)
              ->orWhere('status', '1');

        });


        /*
        |--------------------------------------------------------------------------
        | Resort
        |--------------------------------------------------------------------------
        */

        if ($request->filled('resort_id')) {

            $query->where(
                'resort_id',
                $request->resort_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Room Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('room_type_id')) {

            $query->where(
                'room_type_id',
                $request->room_type_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Adults
        |--------------------------------------------------------------------------
        */

        if ($request->filled('adults')) {

            $query->where(
                'max_adult',
                '>=',
                (int) $request->adults
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Children
        |--------------------------------------------------------------------------
        */

        if ($request->filled('children')) {

            $query->where(
                'max_child',
                '>=',
                (int) $request->children
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Beds
        |--------------------------------------------------------------------------
        */

        if ($request->filled('beds')) {

            $query->where(
                'beds',
                '>=',
                (int) $request->beds
            );
        }


        /*
        |--------------------------------------------------------------------------
        | View Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('view_type')) {

            $query->where(
                'view_type',
                $request->view_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Keyword
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'name',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'room_no',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'view_type',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhereHas('roomType', function ($roomType) use ($keyword) {

                    $roomType->where(
                        'name',
                        'like',
                        '%' . $keyword . '%'
                    );

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        $rooms = $query
            ->latest()
            ->paginate(
                $request->integer('per_page', 12)
            );


        return response()->json([

            'success' => true,

            'data' => RoomResource::collection($rooms),

            'pagination' => [

                'current_page' => $rooms->currentPage(),

                'last_page' => $rooms->lastPage(),

                'per_page' => $rooms->perPage(),

                'total' => $rooms->total(),

            ],

        ]);
    }
}