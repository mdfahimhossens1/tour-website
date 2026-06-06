<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);

        return view(
            'admin.contact_messages.index',
            compact('messages')
        );
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        $message->update([
            'is_read' => true
        ]);

        return view(
            'admin.contact_messages.show',
            compact('message')
        );
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Message deleted successfully'
        );
    }
}