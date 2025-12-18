<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display messages page for users.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get the admin user (assuming there's at least one admin)
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            return redirect()->route('dashboard')->with('error', 'No admin found. Please contact support.');
        }
        
        // Get conversation messages
        $messages = Message::where(function($query) use ($user, $admin) {
            $query->where('sender_id', $user->user_id)
                  ->where('receiver_id', $admin->user_id);
        })->orWhere(function($query) use ($user, $admin) {
            $query->where('sender_id', $admin->user_id)
                  ->where('receiver_id', $user->user_id);
        })->orderBy('created_at', 'asc')->get();
        
        // Mark messages as read
        Message::where('receiver_id', $user->user_id)
               ->where('read', false)
               ->update(['read' => true, 'read_at' => now()]);
        
        return view('messages.index', compact('messages', 'admin'));
    }

    /**
     * Display messages page for admin.
     */
    public function adminIndex()
    {
        $admin = Auth::user();
        
        // Get all users who have conversations with admin
        $conversations = Message::where('sender_id', $admin->user_id)
            ->orWhere('receiver_id', $admin->user_id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($admin) {
                return $message->sender_id == $admin->user_id 
                    ? $message->receiver_id 
                    : $message->sender_id;
            });
        
        // Get selected user ID from request
        $selectedUserId = request('user_id');
        $selectedUser = null;
        $messages = collect();
        
        if ($selectedUserId) {
            $selectedUser = User::where('user_id', $selectedUserId)->firstOrFail();
            
            // Get conversation messages
            $messages = Message::where(function($query) use ($admin, $selectedUser) {
                $query->where('sender_id', $admin->user_id)
                      ->where('receiver_id', $selectedUser->user_id);
            })->orWhere(function($query) use ($admin, $selectedUser) {
                $query->where('sender_id', $selectedUser->user_id)
                      ->where('receiver_id', $admin->user_id);
            })->orderBy('created_at', 'asc')->get();
            
            // Mark messages as read
            Message::where('receiver_id', $admin->user_id)
                   ->where('sender_id', $selectedUser->user_id)
                   ->where('read', false)
                   ->update(['read' => true, 'read_at' => now()]);
        }
        
        // Get all users with their booking information
        $users = User::where('role', 'user')
            ->withCount([
                'bookings as total_bookings',
                'bookings as pending_bookings' => function($query) {
                    $query->where('status', 'pending');
                },
                'bookings as confirmed_bookings' => function($query) {
                    $query->where('status', 'confirmed');
                }
            ])
            ->with(['bookings' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->get()
            ->map(function($user) use ($admin) {
                // Check if user has unread messages from admin
                $unreadCount = Message::where('sender_id', $user->user_id)
                    ->where('receiver_id', $admin->user_id)
                    ->where('read', false)
                    ->count();
                
                $user->unread_count = $unreadCount;
                
                // Check if user has existing conversation
                $hasConversation = Message::where(function($query) use ($admin, $user) {
                    $query->where('sender_id', $admin->user_id)
                          ->where('receiver_id', $user->user_id);
                })->orWhere(function($query) use ($admin, $user) {
                    $query->where('sender_id', $user->user_id)
                          ->where('receiver_id', $admin->user_id);
                })->exists();
                
                $user->has_conversation = $hasConversation;
                
                return $user;
            })
            ->sortByDesc(function($user) {
                // Sort by: has unread messages first, then by latest booking, then by name
                return [
                    $user->unread_count > 0 ? 1 : 0,
                    $user->bookings->first() ? $user->bookings->first()->created_at->timestamp : 0,
                    $user->name
                ];
            })
            ->values();
        
        return view('admin.messages.index', compact('conversations', 'users', 'selectedUser', 'messages'));
    }

    /**
     * Send a message.
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,user_id',
            'message' => 'required|string|max:1000',
        ]);
        
        $message = Message::create([
            'sender_id' => Auth::user()->user_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
        
        $message->load(['sender', 'receiver']);
        
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Get new messages (for polling).
     */
    public function getMessages(Request $request)
    {
        $user = Auth::user();
        $lastMessageId = $request->input('last_message_id', 0);
        
        // Determine the other user in the conversation
        $otherUserId = $request->input('other_user_id');
        
        if (!$otherUserId) {
            // For regular users, get admin
            $admin = User::where('role', 'admin')->first();
            $otherUserId = $admin ? $admin->user_id : null;
        }
        
        if (!$otherUserId) {
            return response()->json(['messages' => []]);
        }
        
        // Get new messages
        $messages = Message::where('message_id', '>', $lastMessageId)
            ->where(function($query) use ($user, $otherUserId) {
                $query->where(function($q) use ($user, $otherUserId) {
                    $q->where('sender_id', $user->user_id)
                      ->where('receiver_id', $otherUserId);
                })->orWhere(function($q) use ($user, $otherUserId) {
                    $q->where('sender_id', $otherUserId)
                      ->where('receiver_id', $user->user_id);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark received messages as read
        Message::where('receiver_id', $user->user_id)
               ->where('sender_id', $otherUserId)
               ->where('read', false)
               ->update(['read' => true, 'read_at' => now()]);
        
        return response()->json([
            'messages' => $messages,
            'last_message_id' => $messages->max('message_id') ?? $lastMessageId,
        ]);
    }

    /**
     * Get unread message count.
     */
    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::user()->user_id)
            ->where('read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}
