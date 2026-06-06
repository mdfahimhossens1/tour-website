<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()
            ->paginate(20);

        return view(
            'admin.subscribers.index',
            compact('subscribers')
        );
    }

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