<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerChatController extends Controller
{
    /**
     * Get seller's chat conversations (improved UX)
     * Groups messages by sender and shows latest message
     */
    public function getConversations(Request $request): JsonResponse
    {
        $seller = Auth::user();

        $conversations = Message::where('receiver_id', $seller->id)
            ->select(DB::raw('DISTINCT sender_id'), 'product_id')
            ->with(['sender', 'product'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($msg) use ($seller) {
                $sender = $msg->sender;
                $lastMessage = Message::where('sender_id', $sender->id)
                    ->where('receiver_id', $seller->id)
                    ->where('product_id', $msg->product_id)
                    ->latest()
                    ->first();

                $unreadCount = Message::where('sender_id', $sender->id)
                    ->where('receiver_id', $seller->id)
                    ->where('product_id', $msg->product_id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'sender_id' => $sender->id,
                    'sender_name' => $sender->name,
                    'sender_avatar' => $sender->avatar_url ?? null,
                    'product_id' => $msg->product_id,
                    'product_name' => $msg->product->name ?? 'Unknown Product',
                    'product_image' => $msg->product->image_url ?? null,
                    'last_message' => $lastMessage->message ?? 'No message',
                    'last_message_time' => $lastMessage->created_at ?? null,
                    'unread_count' => $unreadCount,
                    'is_read' => $lastMessage->is_read ?? false,
                ];
            });

        return response()->json([
            'data' => $conversations,
            'unread_total' => Message::where('receiver_id', $seller->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    /**
     * Get chat history with a specific buyer
     */
    public function getChat(int $senderId, int $productId): JsonResponse
    {
        $seller = Auth::user();

        $messages = Message::where('product_id', $productId)
            ->where(function ($query) use ($senderId, $seller) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $seller->id)
                    ->orWhere('sender_id', $seller->id)
                    ->where('receiver_id', $senderId);
            })
            ->with(['sender', 'receiver', 'order'])
            ->orderBy('created_at')
            ->paginate(20);

        // Mark as read
        Message::where('sender_id', $senderId)
            ->where('receiver_id', $seller->id)
            ->where('product_id', $productId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'data' => $messages->map(fn ($msg) => $this->formatMessage($msg)),
        ]);
    }

    /**
     * Send message from seller
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string|min:1|max:5000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $seller = Auth::user();
        $product = \App\Models\Product::find($request->product_id);

        // Verify seller owns the product
        if ($product->seller_id !== $seller->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'sender_id' => $seller->id,
            'receiver_id' => $request->receiver_id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $this->formatMessage($message->load(['sender', 'receiver'])),
        ], 201);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(int $senderId, int $productId): JsonResponse
    {
        $seller = Auth::user();

        Message::where('sender_id', $senderId)
            ->where('receiver_id', $seller->id)
            ->where('product_id', $productId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['message' => 'Messages marked as read']);
    }

    /**
     * Get chat analytics for seller dashboard
     */
    public function getChatAnalytics(): JsonResponse
    {
        $seller = Auth::user();

        $stats = [
            'total_conversations' => Message::where('receiver_id', $seller->id)
                ->select(DB::raw('DISTINCT sender_id, product_id'))
                ->count(),
            'unread_messages' => Message::where('receiver_id', $seller->id)
                ->where('is_read', false)
                ->count(),
            'avg_response_time' => DB::table('messages as m1')
                ->join('messages as m2', function ($join) {
                    $join->on('m1.sender_id', '=', 'm2.receiver_id')
                        ->on('m1.receiver_id', '=', 'm2.sender_id')
                        ->on('m1.product_id', '=', 'm2.product_id');
                })
                ->where('m1.receiver_id', $seller->id)
                ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, m1.created_at, m2.created_at)) as avg_minutes'))
                ->first()
                ->avg_minutes ?? 0,
        ];

        return response()->json(['data' => $stats]);
    }

    private function formatMessage(Message $message): array
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name ?? 'Unknown',
            'sender_avatar' => $message->sender->avatar_url ?? null,
            'message' => $message->message,
            'is_from_seller' => $message->sender_id === Auth::id(),
            'is_read' => $message->is_read,
            'read_at' => $message->read_at,
            'created_at' => $message->created_at,
            'order_id' => $message->order_id,
        ];
    }
}
