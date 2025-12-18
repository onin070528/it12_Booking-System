<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Verify notification belongs to user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->notifications()->where('read', false)->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount()
    {
        /** @var User $user */
        $user = Auth::user();
        $count = $user->notifications()->where('read', false)->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for sidebar/dropdown).
     */
    public function getRecent()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Display a listing of notifications for admin.
     */
    public function adminIndex()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}
