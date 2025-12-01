<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
            
            // Broadcast updated unread count
            broadcast(new \App\Events\UnreadCountUpdated(Auth::id(), Auth::user()->unreadNotifications->count()));
            
            // Redirect to the link specified in notification data
            if (isset($notification->data['link'])) {
                return redirect($notification->data['link']);
            }
        }
        return back();
    }
}
