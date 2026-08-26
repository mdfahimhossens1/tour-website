<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberApiController extends Controller
{
    /**
     * Admin: Subscriber List
     */
    public function index()
    {
        $subscribers = Subscriber::latest()
            ->paginate(20);

        return view(
            'admin.subscribers.index',
            compact('subscribers')
        );
    }

    /**
     * API: Subscribe Email
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Existing Subscriber
        |--------------------------------------------------------------------------
        */

        $existingSubscriber = Subscriber::where(
            'email',
            $validated['email']
        )->first();

        if ($existingSubscriber) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already subscribed.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Subscriber
        |--------------------------------------------------------------------------
        */

        $subscriber = Subscriber::create([
            'email' => $validated['email'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed!',
            'data' => [
                'id' => $subscriber->id,
                'email' => $subscriber->email,
                'created_at' => $subscriber->created_at,
            ],
        ], 201);
    }

    /**
     * Admin: Delete Subscriber
     */
    public function destroy($id)
    {
        Subscriber::findOrFail($id)
            ->delete();

        return back()->with(
            'success',
            'Deleted Successfully'
        );
    }
}