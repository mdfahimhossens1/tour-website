<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\ResortBooking;
use App\Models\Resort;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomAvailability;
use App\Models\User;
use App\Models\Vendor;
use App\Models\ResortBookingGuest;

class ResortBookingController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Booking List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $bookings = ResortBooking::with([
            'user',
            'vendor',
            'resort',
            'room',
            'guests'
        ])
        ->latest()
        ->paginate(20);

        $users = User::orderBy('name')->get();

        $resorts = Resort::where('status',1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.resort-bookings.index',
            compact(
                'bookings',
                'users',
                'resorts'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Page (optional)
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $users = User::orderBy('name')->get();

        $resorts = Resort::where('status',1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.resort-bookings.create',
            compact(
                'users',
                'resorts'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Code Generator
    |--------------------------------------------------------------------------
    */

    private function generateBookingCode()
    {
        $last = ResortBooking::latest()->first();

        $number = $last
            ? $last->id + 1
            : 1;

        return 'RB-'
            . date('Y')
            . '-'
            . str_pad(
                $number,
                6,
                '0',
                STR_PAD_LEFT
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Get Room Price
    |--------------------------------------------------------------------------
    */

    private function getRoomPrice(Room $room, $date)
    {
        /*
        |------------------------------------------
        | 1. Check Availability Price
        |------------------------------------------
        */

        $availability = RoomAvailability::where(
                'room_id',
                $room->id
            )
            ->whereDate(
                'date',
                $date
            )
            ->first();

        if ($availability && $availability->price > 0) {
            return $availability->price;
        }

        /*
        |------------------------------------------
        | 2. Check Special Room Price
        |------------------------------------------
        */

        $roomPrice = RoomPrice::where(
                'room_id',
                $room->id
            )
            ->whereDate(
                'from_date',
                '<=',
                $date
            )
            ->whereDate(
                'to_date',
                '>=',
                $date
            )
            ->latest()
            ->first();

        if ($roomPrice) {
            return $roomPrice->price;
        }

        /*
        |------------------------------------------
        | 3. Discount Price
        |------------------------------------------
        */

        if (
            !empty($room->discount_price)
            &&
            $room->discount_price > 0
        ) {
            return $room->discount_price;
        }

        /*
        |------------------------------------------
        | 4. Default Room Price
        |------------------------------------------
        */

        return $room->price;
    }

    /*
    |--------------------------------------------------------------------------
    | Store Booking
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'resort_id'     => 'required|exists:resorts,id',
            'room_id'       => 'required|exists:rooms,id',

            'check_in'      => 'required|date',
            'check_out'     => 'required|date|after:check_in',

            'adults'        => 'required|integer|min:1',
            'children'      => 'nullable|integer|min:0',

            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',

            'special_request' => 'nullable|string',
            'guests' => 'nullable|array',
            'guests.*.name' => 'required_with:guests|string|max:255',
            'guests.*.age' => 'nullable|integer|min:0',
            'guests.*.gender' => 'nullable|in:male,female,other',
            'guests.*.phone' => 'nullable|string|max:20',
            'guests.*.nid' => 'nullable|string|max:100',
            'guests.*.passport' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            $room = Room::with('resort.vendor')->findOrFail($request->room_id);
            $vendor = $room->resort->vendor;

            /*
            |--------------------------------------------------------------------------
            | Nights
            |--------------------------------------------------------------------------
            */

            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $totalNights = $checkIn->diffInDays($checkOut);

            if ($totalNights <= 0) {
                return back()->with('error', 'Invalid booking date.');
            }

            /*
            |--------------------------------------------------------------------------
            | Availability Check
            |--------------------------------------------------------------------------
            */

            $currentDate = $checkIn->copy();
            $subtotal = 0;

            while ($currentDate < $checkOut) {
                $availability = RoomAvailability::where(
                    'room_id',
                    $room->id
                )
                ->whereDate('date', $currentDate)
                ->first();

                if (!$availability) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Availability not found for ' . $currentDate->format('d M Y')
                    );
                }

                if ($availability->is_closed) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Room Closed on ' . $currentDate->format('d M Y')
                    );
                }

                if ($availability->is_sold_out) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Room Sold Out on ' . $currentDate->format('d M Y')
                    );
                }

                if ($availability->available_rooms <= 0) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'No Room Available on ' . $currentDate->format('d M Y')
                    );
                }

                $subtotal += $this->getRoomPrice($room, $currentDate);
                $currentDate->addDay();
            }

            /*
            |--------------------------------------------------------------------------
            | Discount & Tax
            |--------------------------------------------------------------------------
            */

            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? 0;
            $total = ($subtotal - $discount) + $tax;

            /*
            |--------------------------------------------------------------------------
            | Commission
            |--------------------------------------------------------------------------
            */

            $commissionRate = $vendor->commission_rate ?? 10;
            $adminCommission = ($total * $commissionRate) / 100;
            $vendorEarning = $total - $adminCommission;

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = ResortBooking::create([
                'user_id'            => $request->user_id,
                'vendor_id'          => $vendor->id,
                'resort_id'          => $room->resort_id,
                'room_id'            => $room->id,
                'booking_code'       => $this->generateBookingCode(),
                'check_in'           => $checkIn,
                'check_out'          => $checkOut,
                'total_nights'       => $totalNights,
                'adults'             => $request->adults,
                'children'           => $request->children ?? 0,
                'room_price'         => $subtotal / $totalNights,
                'subtotal'           => $subtotal,
                'discount'           => $discount,
                'tax'                => $tax,
                'total_amount'       => $total,
                'commission_rate'    => $commissionRate,
                'admin_commission'   => $adminCommission,
                'vendor_earning'     => $vendorEarning,
                'payment_status'     => 'pending',
                'booking_status'     => 'pending',
                'special_request'    => $request->special_request,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Save Guests
            |--------------------------------------------------------------------------
            */

            if ($request->has('guests') && is_array($request->guests)) {
                foreach ($request->guests as $guest) {
                    if (empty($guest['name'])) {
                        continue;
                    }

                    ResortBookingGuest::create([
                        'resort_booking_id' => $booking->id,
                        'name'       => $guest['name'],
                        'age'        => $guest['age'] ?? null,
                        'gender'     => $guest['gender'] ?? null,
                        'phone'      => $guest['phone'] ?? null,
                        'nid'        => $guest['nid'] ?? null,
                        'passport'   => $guest['passport'] ?? null,
                    ]);

                }
            }
                    $booking->payments()->create([
                'trx_id' => (string) \Illuminate\Support\Str::uuid(),
                'payment_method' => 'cash',
                'amount' => $booking->total_amount,
                'status' => 'pending',
            ]);
            /*
            |--------------------------------------------------------------------------
            | Reduce Availability
            |--------------------------------------------------------------------------
            */

            $currentDate = $checkIn->copy();

            while ($currentDate < $checkOut) {
                $availability = RoomAvailability::where(
                    'room_id',
                    $room->id
                )
                ->whereDate('date', $currentDate)
                ->first();

                $availability->available_rooms--;

                if ($availability->available_rooms <= 0) {
                    $availability->available_rooms = 0;
                    $availability->is_sold_out = true;
                }

                $availability->save();
                $currentDate->addDay();
            }

            DB::commit();

            return redirect()
                ->route('admin.resort-bookings.index')
                ->with('success', 'Resort Booking Created Successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Show Booking
    |--------------------------------------------------------------------------
    */

    public function show(ResortBooking $resortBooking)
    {
        $resortBooking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests'
        ]);

        return response()->json([
            'success' => true,
            'data'    => $resortBooking
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(ResortBooking $resortBooking)
    {
        $resortBooking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests'  // 👈 এটা যোগ করলাম
        ]);

        return response()->json([
            'success' => true,
            'data'    => $resortBooking
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Booking
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, ResortBooking $resortBooking)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'room_id'       => 'required|exists:rooms,id',
            'check_in'      => 'required|date',
            'check_out'     => 'required|date|after:check_in',
            'adults'        => 'required|integer|min:1',
            'children'      => 'nullable|integer|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'payment_status'=> 'required|in:pending,paid,failed,refunded',
            'booking_status'=> 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'special_request'=>'nullable|string',
            'edit_guests' => 'nullable|array',
            'edit_guests.*.name' => 'required_with:edit_guests|string|max:255',
            'edit_guests.*.age' => 'nullable|integer|min:0',
            'edit_guests.*.gender' => 'nullable|in:male,female,other',
            'edit_guests.*.phone' => 'nullable|string|max:20',
            'edit_guests.*.nid' => 'nullable|string|max:100',
            'edit_guests.*.passport' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | Restore Old Availability
            |--------------------------------------------------------------------------
            */

            $oldDate = Carbon::parse($resortBooking->check_in);
            $oldOut = Carbon::parse($resortBooking->check_out);

            while ($oldDate < $oldOut) {
                $availability = RoomAvailability::where(
                    'room_id',
                    $resortBooking->room_id
                )
                ->whereDate('date', $oldDate)
                ->first();

                if ($availability) {
                    $availability->available_rooms++;
                    $availability->is_sold_out = false;
                    $availability->save();
                }

                $oldDate->addDay();
            }

            /*
            |--------------------------------------------------------------------------
            | New Room
            |--------------------------------------------------------------------------
            */

            $room = Room::with('resort.vendor')
                ->findOrFail($request->room_id);

            $vendor = $room->resort->vendor;

            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $nights = $checkIn->diffInDays($checkOut);

            if ($nights <= 0) {
                DB::rollBack();
                return back()->with('error', 'Invalid booking date.');
            }

            $subtotal = 0;
            $current = $checkIn->copy();

            while ($current < $checkOut) {
                $availability = RoomAvailability::where(
                    'room_id',
                    $room->id
                )
                ->whereDate('date', $current)
                ->first();

                if (!$availability || $availability->is_closed || $availability->available_rooms <= 0) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Room unavailable on ' . $current->format('d M Y')
                    );
                }

                $subtotal += $this->getRoomPrice($room, $current);
                $current->addDay();
            }

            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? 0;
            $total = ($subtotal - $discount) + $tax;

            $commissionRate = $vendor->commission_rate ?? 10;
            $adminCommission = ($total * $commissionRate) / 100;
            $vendorEarning = $total - $adminCommission;

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $resortBooking->update([
                'user_id'          => $request->user_id,
                'vendor_id'        => $vendor->id,
                'resort_id'        => $room->resort_id,
                'room_id'          => $room->id,
                'check_in'         => $checkIn,
                'check_out'        => $checkOut,
                'total_nights'     => $nights,
                'adults'           => $request->adults,
                'children'         => $request->children ?? 0,
                'room_price'       => $subtotal / $nights,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'tax'              => $tax,
                'total_amount'     => $total,
                'commission_rate'  => $commissionRate,
                'admin_commission' => $adminCommission,
                'vendor_earning'   => $vendorEarning,
                'payment_status'   => $request->payment_status,
                'booking_status'   => $request->booking_status,
                'special_request'  => $request->special_request,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Guests - Delete existing and create new
            |--------------------------------------------------------------------------
            */

            // Delete all existing guests
            $booking->guests()->delete();

            // Create new guests
            if ($request->has('edit_guests') && is_array($request->edit_guests)) {
                foreach ($request->edit_guests as $guest) {
                    if (empty($guest['name'])) {
                        continue;
                    }

                    ResortBookingGuest::create([
                        'resort_booking_id' => $booking->id,
                        'name'       => $guest['name'],
                        'age'        => $guest['age'] ?? null,
                        'gender'     => $guest['gender'] ?? null,
                        'phone'      => $guest['phone'] ?? null,
                        'nid'        => $guest['nid'] ?? null,
                        'passport'   => $guest['passport'] ?? null,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Reduce New Availability
            |--------------------------------------------------------------------------
            */

            $current = $checkIn->copy();

            while ($current < $checkOut) {
                $availability = RoomAvailability::where(
                    'room_id',
                    $room->id
                )
                ->whereDate('date', $current)
                ->first();

                $availability->available_rooms--;

                if ($availability->available_rooms <= 0) {
                    $availability->available_rooms = 0;
                    $availability->is_sold_out = true;
                }

                $availability->save();
                $current->addDay();
            }

            DB::commit();

            return redirect()
                ->route('admin.resort-bookings.index')
                ->with('success', 'Booking Updated Successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Booking
    |--------------------------------------------------------------------------
    */

    public function destroy(ResortBooking $resortBooking)
    {
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | Restore Room Availability
            |--------------------------------------------------------------------------
            */

            $currentDate = Carbon::parse($resortBooking->check_in);
            $checkOut = Carbon::parse($resortBooking->check_out);

            while ($currentDate < $checkOut) {
                $availability = RoomAvailability::where('room_id', $resortBooking->room_id)
                    ->whereDate('date', $currentDate)
                    ->first();

                if ($availability) {
                    $availability->available_rooms++;

                    if ($availability->available_rooms > 0) {
                        $availability->is_sold_out = false;
                    }

                    $availability->save();
                }

                $currentDate->addDay();
            }

            /*
            |--------------------------------------------------------------------------
            | Delete Guests
            |--------------------------------------------------------------------------
            */

            $resortBooking->guests()->delete();

            /*
            |--------------------------------------------------------------------------
            | Delete Booking
            |--------------------------------------------------------------------------
            */

            $resortBooking->delete();

            DB::commit();

            return redirect()
                ->route('admin.resort-bookings.index')
                ->with('success', 'Booking Deleted Successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Get Rooms By Resort
    |--------------------------------------------------------------------------
    */

    public function getRooms($resortId)
    {
        $rooms = Room::where('resort_id', $resortId)
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'price',
                'discount_price'
            ]);

        // Return in proper format for Blade
        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Room Price by Date
    |--------------------------------------------------------------------------
    */

    public function getPrice(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date'
        ]);

        $room = Room::findOrFail($request->room_id);
        $date = Carbon::parse($request->date);

        /*
        |--------------------------------------------------------------------------
        | 1. Check Room Availability Price (Highest Priority)
        |--------------------------------------------------------------------------
        */
        $availability = RoomAvailability::where('room_id', $room->id)
            ->whereDate('date', $date)
            ->first();

        if ($availability && $availability->price > 0) {
            return response()->json([
                'success' => true,
                'price' => $availability->price,
                'source' => 'availability'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Check Room Price from RoomPrice Table
        |--------------------------------------------------------------------------
        */
        $roomPrice = RoomPrice::where('room_id', $room->id)
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->orderByRaw("
                CASE 
                    WHEN type = 'festival' THEN 1
                    WHEN type = 'holiday' THEN 2
                    WHEN type = 'seasonal' THEN 3
                    WHEN type = 'weekend' THEN 4
                    WHEN type = 'normal' THEN 5
                    ELSE 6
                END
            ")
            ->first();

        if ($roomPrice && $roomPrice->price > 0) {
            return response()->json([
                'success' => true,
                'price' => $roomPrice->price,
                'source' => 'room_price',
                'type' => $roomPrice->type
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Check Discount Price
        |--------------------------------------------------------------------------
        */
        if (!empty($room->discount_price) && $room->discount_price > 0) {
            return response()->json([
                'success' => true,
                'price' => $room->discount_price,
                'source' => 'discount'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Default Room Price
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'success' => true,
            'price' => $room->price ?? 0,
            'source' => 'default'
        ]);
    }

    /**
     * Helper method to check if date is holiday
     */
    private function checkIfHoliday($date)
    {
        // You can implement this based on your holiday table
        // Example: return Holiday::whereDate('date', $date)->exists();
        return false;
    }

    /**
     * Helper method to check if date is festival
     */
    private function checkIfFestival($date)
    {
        // You can implement this based on your festival table
        // Example: return Festival::whereDate('date', $date)->exists();
        return false;
    }

}