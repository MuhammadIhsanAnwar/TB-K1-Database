<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getConversations()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'order'])
            ->groupBy('sender_id', 'receiver_id')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($conversations);
    }

    public function getMessages($conversationId)
    {
        $messages = Message::where(function($query) use ($conversationId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $conversationId)
                ->orWhere('sender_id', $conversationId)
                ->where('receiver_id', Auth::id());
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at')
        ->get();

        // Mark as read
        Message::where('receiver_id', Auth::id())
            ->where('sender_id', $conversationId)
            ->whereNull('read_at')
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $receiverId)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
            'order_id' => $validated['order_id'] ?? null,
        ]);

        return response()->json($message->load(['sender', 'receiver']), 201);
    }

    public function markAsRead($conversationId)
    {
        Message::where('receiver_id', Auth::id())
            ->where('sender_id', $conversationId)
            ->whereNull('read_at')
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    public function getUnreadCount()
    {
        $unreadCount = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }
}
