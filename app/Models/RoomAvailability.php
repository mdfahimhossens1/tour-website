<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RoomAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'date',
        'price',
        'total_rooms',
        'available_rooms',
        'is_closed',
        'is_sold_out',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
        'total_rooms' => 'integer',
        'available_rooms' => 'integer',
        'is_closed' => 'boolean',
        'is_sold_out' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Room Relationship
    |--------------------------------------------------------------------------
    */

    public function room()
    {
        return $this->belongsTo(Room::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Available Rooms
    |--------------------------------------------------------------------------
    |
    | rooms.total_rooms is the master quantity.
    |
    | available =
    | total rooms - active booked rooms
    |
    */

    public function calculateAvailableRooms(): int
    {
        $totalRooms = max(
            0,
            (int) optional($this->room)->total_rooms
        );

        if ($totalRooms <= 0) {
            return 0;
        }

        $date = Carbon::parse($this->date)
            ->format('Y-m-d');


        $bookedRooms = RoomBooking::where(
            'room_id',
            $this->room_id
        )
            ->whereIn(
                'booking_status',
                [
                    'pending',
                    'confirmed',
                    'checked_in',
                ]
            )
            ->where(
                'check_in',
                '<=',
                $date
            )
            ->where(
                'check_out',
                '>',
                $date
            )
            ->sum('room_count');


        return max(
            0,
            $totalRooms - (int) $bookedRooms
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize Availability
    |--------------------------------------------------------------------------
    */

    public function syncAvailability(): self
    {
        $totalRooms = max(
            0,
            (int) optional($this->room)->total_rooms
        );

        $availableRooms = $this->calculateAvailableRooms();

        $this->update([
            'total_rooms' => $totalRooms,

            'available_rooms' => min(
                $totalRooms,
                $availableRooms
            ),

            'is_sold_out' =>
                $availableRooms <= 0,
        ]);

        return $this->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Scope: Available
    |--------------------------------------------------------------------------
    */

    public function scopeAvailable($query)
    {
        return $query
            ->where('is_closed', false)
            ->where('available_rooms', '>', 0)
            ->where('is_sold_out', false);
    }
}