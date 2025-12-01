<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->get()
            ->map(function($notification) {
                $notification->created_at_human = $notification->created_at->diffForHumans();
                return $notification;
            });
            
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        // Return JSON response for AJAX calls
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        // Redirect to the notification's URL if available
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }
        
        return back()->with('success', 'Đã đánh dấu là đã đọc');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count()
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}
