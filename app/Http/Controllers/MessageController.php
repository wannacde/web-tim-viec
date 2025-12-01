<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get unread count
        $unreadCount = Message::where('receiver_id', $userId)
                             ->where('is_read', false)
                             ->count();
        
        // Get notifications
        $notifications = Auth::user()->notifications()->latest()->get();
        $unreadNotificationCount = Auth::user()->unreadNotifications()->count();
        
        // Get unique users with latest message
        $contacts = DB::table('messages')
            ->select('users.id', 'users.name', 'users.email', 'users.role', 
                    DB::raw('MAX(messages.created_at) as last_message_time'),
                    DB::raw('(SELECT message FROM messages m2 WHERE (m2.sender_id = users.id AND m2.receiver_id = ' . $userId . ') OR (m2.sender_id = ' . $userId . ' AND m2.receiver_id = users.id) ORDER BY m2.created_at DESC LIMIT 1) as last_message'))
            ->join('users', function($join) use ($userId) {
                $join->on('users.id', '=', 'messages.sender_id')
                     ->orOn('users.id', '=', 'messages.receiver_id');
            })
            ->where(function($query) use ($userId) {
                $query->where('messages.sender_id', $userId)
                      ->orWhere('messages.receiver_id', $userId);
            })
            ->where('users.id', '!=', $userId)
            ->groupBy('users.id', 'users.name', 'users.email', 'users.role')
            ->orderBy('last_message_time', 'desc')
            ->get();

        // Prepare users data for SPA
        $users = $this->getContacts($userId)->map(function($contact) use ($userId) {
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'avatar_url' => $contact->avatar ? asset('storage/' . $contact->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->name),
                'last_message_content' => $contact->last_message ?? '',
                'last_message_time' => $contact->last_message_time ? \Carbon\Carbon::parse($contact->last_message_time)->diffForHumans() : '',
                'unread_count' => Message::where('sender_id', $contact->id)->where('receiver_id', $userId)->where('is_read', false)->count()
            ];
        });

        return view('messages.index', compact('users', 'unreadCount', 'notifications', 'unreadNotificationCount'));
    }

    public function show(Request $request, User $user)
    {
        $currentUserId = Auth::id();
        
        // Get conversation messages
        $messages = Message::where(function($query) use ($currentUserId, $user) {
            $query->where('sender_id', $currentUserId)
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($currentUserId, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $currentUserId);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get()
          ->map(function($message) {
              return [
                  'id' => $message->id,
                  'sender_id' => $message->sender_id,
                  'message' => $message->message,
                  'time' => $message->created_at->format('H:i')
              ];
          });

        // Mark messages as read
        Message::where('sender_id', $user->id)
               ->where('receiver_id', $currentUserId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json($messages);
        }

        // Get contacts for sidebar (fallback for non-AJAX)
        $contacts = $this->getContacts($currentUserId);
        return view('messages.show', compact('messages', 'user', 'contacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'job_id' => 'nullable|exists:jobs,id'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'job_id' => $request->job_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        $message->load('sender');
        broadcast(new \App\Events\MessageSent($message))->toOthers();
        
        // Update unread count for receiver
        $unreadCount = \App\Models\Message::where('receiver_id', $request->receiver_id)
                                          ->where('is_read', false)
                                          ->count();
        broadcast(new \App\Events\UnreadCountUpdated($request->receiver_id, $unreadCount));

        return redirect()->route('messages.show', $request->receiver_id)
                        ->with('success', 'Message sent successfully!');
    }

    public function markAllRead(Request $request)
    {
        // Mark all notifications as read
        Auth::user()->unreadNotifications->markAsRead();
        
        // Mark all messages as read
        Message::where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    private function getContacts($userId)
    {
        return DB::table('messages')
            ->select('users.id', 'users.name', 'users.email', 'users.role', 'users.avatar',
                    DB::raw('MAX(messages.created_at) as last_message_time'),
                    DB::raw('(SELECT message FROM messages m2 WHERE (m2.sender_id = users.id AND m2.receiver_id = ' . $userId . ') OR (m2.sender_id = ' . $userId . ' AND m2.receiver_id = users.id) ORDER BY m2.created_at DESC LIMIT 1) as last_message'))
            ->join('users', function($join) use ($userId) {
                $join->on('users.id', '=', 'messages.sender_id')
                     ->orOn('users.id', '=', 'messages.receiver_id');
            })
            ->where(function($query) use ($userId) {
                $query->where('messages.sender_id', $userId)
                      ->orWhere('messages.receiver_id', $userId);
            })
            ->where('users.id', '!=', $userId)
            ->groupBy('users.id', 'users.name', 'users.email', 'users.role', 'users.avatar')
            ->orderBy('last_message_time', 'desc')
            ->get();
    }
}