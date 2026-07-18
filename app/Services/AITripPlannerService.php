<?php

namespace App\Services;

use App\Models\AiTripPlan;

class AITripPlannerService
{
    /**
     * Generate AI Trip Plan
     */
    public function generate(array $data): AiTripPlan
    {
        /*
        |--------------------------------------------------------------------------
        | Day Wise Itinerary
        |--------------------------------------------------------------------------
        */

        $itinerary = [];

        for ($day = 1; $day <= $data['days']; $day++) {

            $activities = [];

if ($day == 1) {

    $fromLocation = $data['from_location'] ?? 'Your Location';

    $activities = [
        "Start journey from {$fromLocation}",
        "Arrive at {$data['destination']}",
        "Hotel Check-in",
        "Lunch",
        "Local sightseeing",
        "Dinner",
    ];

} elseif ($day == $data['days']) {

                $activities = [
                    "Breakfast",
                    "Shopping",
                    "Check-out",
                    "Return journey",
                ];

            } else {

                $activities = [
                    "Breakfast",
                    "Visit popular attractions",
                    "Photography",
                    "Lunch",
                    "Adventure / Local Activities",
                    "Dinner",
                ];

            }

            $itinerary[] = [

                'day' => $day,

                'title' => "Day {$day}",

                'activities' => $activities,

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Budget Breakdown
        |--------------------------------------------------------------------------
        */

        $transport = round($data['budget'] * 0.30, 2);

        $hotel = round($data['budget'] * 0.40, 2);

        $food = round($data['budget'] * 0.20, 2);

        $others = round($data['budget'] * 0.10, 2);

        /*
        |--------------------------------------------------------------------------
        | Tips
        |--------------------------------------------------------------------------
        */

        $tips = [

            "Carry your National ID or Passport.",

            "Keep emergency cash.",

            "Book hotels in advance.",

            "Check weather before travelling.",

            "Keep your mobile fully charged.",

        ];

        /*
        |--------------------------------------------------------------------------
        | AI Response JSON
        |--------------------------------------------------------------------------
        */

        $response = [

            'summary' =>

                "A {$data['days']}-day trip has been prepared for {$data['travelers']} traveler(s) to {$data['destination']}.",

            'budget' => [

                'transport' => $transport,

                'hotel' => $hotel,

                'food' => $food,

                'others' => $others,

                'total' => $data['budget'],

            ],

            'itinerary' => $itinerary,

            'tips' => $tips,

        ];

        /*
        |--------------------------------------------------------------------------
        | Save Database
        |--------------------------------------------------------------------------
        */

        return AiTripPlan::create([

            'user_id' => auth()->id(),

            'from_location' => $data['from_location'] ?? null,

            'destination' => $data['destination'],

            'days' => $data['days'],

            'travelers' => $data['travelers'],

            'budget' => $data['budget'],

            'travel_type' => $data['travel_type'] ?? null,

            'interests' => $data['interests'] ?? [],

            'hotel_type' => $data['hotel_type'] ?? null,

            'transport' => $data['transport'] ?? null,

            'extra_note' => $data['extra_note'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Prompt (Future OpenAI)
            |--------------------------------------------------------------------------
            */

            'prompt' => null,

            /*
            |--------------------------------------------------------------------------
            | Human Readable
            |--------------------------------------------------------------------------
            */

            'response' => json_encode(
                $response,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ),

            /*
            |--------------------------------------------------------------------------
            | JSON Response
            |--------------------------------------------------------------------------
            */

            'response_json' => $response,

        ]);
    }
}