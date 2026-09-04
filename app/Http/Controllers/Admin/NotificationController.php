<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display all notifications
     * ----------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = Notification::query()
            ->with('user')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Read Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {

            if ($request->status === 'unread') {

                $query->where('is_read', false);

            } elseif ($request->status === 'read') {

                $query->where('is_read', true);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('type')) {

            $query->where('type', $request->type);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $notifications = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalNotifications = Notification::count();

        $unreadNotifications = Notification::where(
            'is_read',
            false
        )->count();

        $readNotifications = Notification::where(
            'is_read',
            true
        )->count();

        $todayNotifications = Notification::whereDate(
            'created_at',
            today()
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Notification Types
        |--------------------------------------------------------------------------
        */
        $types = Notification::query()
            ->select('type')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view(
            'admin.notifications.index',
            compact(
                'notifications',
                'totalNotifications',
                'unreadNotifications',
                'readNotifications',
                'todayNotifications',
                'types'
            )
        );
    }


    /**
     * ----------------------------------------------------------
     * Show notification details
     * ----------------------------------------------------------
     */
    public function show($id)
    {
        $notification = Notification::with('user')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Automatically mark as read
        |--------------------------------------------------------------------------
        */
        if (!$notification->is_read) {

            $notification->update([
                'is_read' => true,
            ]);
        }

        return view(
            'admin.notifications.show',
            compact('notification')
        );
    }


    /**
     * ----------------------------------------------------------
     * Mark notification as read
     * ----------------------------------------------------------
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->update([
            'is_read' => true,
        ]);

        return back()->with(
            'success',
            'Notification marked as read successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Mark notification as unread
     * ----------------------------------------------------------
     */
    public function markAsUnread($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->update([
            'is_read' => false,
        ]);

        return back()->with(
            'success',
            'Notification marked as unread successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Mark all notifications as read
     * ----------------------------------------------------------
     */
    public function markAllAsRead()
    {
        Notification::where(
            'is_read',
            false
        )->update([
            'is_read' => true,
        ]);

        return back()->with(
            'success',
            'All notifications marked as read successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Delete notification
     * ----------------------------------------------------------
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->delete();

        return back()->with(
            'success',
            'Notification deleted successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Delete all read notifications
     * ----------------------------------------------------------
     */
    public function destroyRead()
    {
        Notification::where(
            'is_read',
            true
        )->delete();

        return back()->with(
            'success',
            'All read notifications deleted successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Topbar Notifications
     * ----------------------------------------------------------
     */
    public function topbar()
    {
        /*
        |--------------------------------------------------------------------------
        | Current authenticated user
        |--------------------------------------------------------------------------
        */
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Safety check
        |--------------------------------------------------------------------------
        */
        if (!$userId) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'unread_count' => 0,
                'notifications' => [],
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Unread count
        |--------------------------------------------------------------------------
        */
        $unreadCount = Notification::query()
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Latest notifications
        |--------------------------------------------------------------------------
        */
        $notifications = Notification::query()
            ->where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JSON response
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'success' => true,

            'unread_count' => $unreadCount,

            'notifications' => $notifications->map(function ($notification) {

                return [
                    'id' => $notification->id,

                    'title' => $notification->title,

                    'message' => $notification->message,

                    'type' => $notification->type,

                    'is_read' => (bool) $notification->is_read,

                    'created_at' => $notification->created_at
                        ? $notification->created_at->format(
                            'd M Y, h:i A'
                        )
                        : null,

                    'time_ago' => $notification->created_at
                        ? $notification->created_at->diffForHumans()
                        : null,

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | Generate URL from Laravel route
                    |--------------------------------------------------------------------------
                    */
                    'url' => route(
                        'admin.notifications.show',
                        $notification->id
                    ),
                ];

            })->values(),
        ]);
    }


    /**
     * ----------------------------------------------------------
     * Clear Current User Notifications
     * ----------------------------------------------------------
     */
    public function clearAll()
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Safety check
        |--------------------------------------------------------------------------
        */
        if (!$userId) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete current user's notifications only
        |--------------------------------------------------------------------------
        */
        Notification::query()
            ->where('user_id', $userId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared successfully.',
        ]);
    }
}