<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{
    /**
     * Mengambil daftar percakapan (Inbox Sidebar
     */

   public function inbox()
{
    $user = Auth::user();

    $conversations = Conversation::with([
            'buyer',
            'seller',
            'messages'
        ])
        ->where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
        })
        ->orderByDesc('last_message_at')
        ->paginate(20);

    return view('chat.inbox', compact('conversations'));
}

public function show(Conversation $conversation)
{
    $user = Auth::user();

    // Proteksi akses
    if (
        $conversation->buyer_id !== $user->id &&
        $conversation->seller_id !== $user->id
    ) {
        abort(403);
    }

    // Ambil sidebar conversations
    $conversations = Conversation::with([
            'buyer',
            'seller',
            'messages'
        ])
        ->where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
        })
        ->orderByDesc('last_message_at')
        ->paginate(20);

    // Ambil messages chat aktif
    $messages = $conversation->messages()
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

    // Reset unread counter
    if ($conversation->buyer_id === $user->id) {
        $conversation->update([
            'unread_buyer' => 0,
            'buyer_last_seen_at' => now(),
        ]);
    } else {
        $conversation->update([
            'unread_seller' => 0,
            'seller_last_seen_at' => now(),
        ]);
    }

    return view('chat.show', compact(
        'conversation',
        'conversations',
        'messages'
    ));
}

public function product()
{
    return $this->belongsTo(Product::class);
}

public function order()
{
    return $this->belongsTo(Order::class);
}
    public function getConversations()
    {
        $user = Auth::user();
        $conversations = Conversation::with(['buyer', 'seller'])
            ->where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->orderBy('last_message_at', 'desc')
            ->get();

        return response()->json(
            $conversations->map(fn($c) => $c->toSummary($user->id))
        );
    }

    /**
     * Mengambil pesan dalam satu percakapan (Infinite Scroll Support)
     */
    public function getMessages(Conversation $conversation)
    {
        // Proteksi: Hanya partisipan yang boleh akses
        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with(['sender', 'receiver'])
            ->latest()
            ->paginate(30);

        return response()->json([
            'messages' => collect($messages->items())->map(fn($m) => $m->toChat(Auth::id())),
            'next_page' => $messages->nextPageUrl()
        ]);
    }

    /**
     * Kirim Pesan Baru
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message'         => 'nullable|string',
            'attachment'      => 'nullable|file|max:5120', // Max 5MB
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = Auth::user();
        $receiverId = ($user->id === $conversation->buyer_id) ? $conversation->seller_id : $conversation->buyer_id;

        // Handle Attachment jika ada
        $attachmentPath = null;
        $attachmentType = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat_attachments', 'public');
            $attachmentType = explode('/', $file->getMimeType())[0]; // image, video, application
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'receiver_id'     => $receiverId,
            'sender_role'     => $user->role ?? 'user',
            'message'         => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // Broadcast Realtime ke lawan bicara
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message->toChat($user->id));
    }

    /**
     * Fitur WhatsApp: Edit Pesan
     */
    public function editMessage(Request $request, Message $message)
    {
        if ($message->sender_id !== Auth::id()) return response()->json(['error' => 'Forbidden'], 403);

        $request->validate(['message' => 'required|string']);

        $message->update([
            'message' => $request->message,
            'edited_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => $message->toChat(Auth::id())]);
    }

    /**
     * Fitur WhatsApp: Delete Pesan
     */
    public function deleteMessage(Request $request, Message $message)
    {
        $type = $request->input('type'); // 'me' atau 'everyone'

        if ($type === 'everyone') {
            if ($message->sender_id !== Auth::id()) return response()->json(['error' => 'Forbidden'], 403);
            $message->update(['deleted_for_everyone_at' => now()]);
        } else {
            if ($message->sender_id === Auth::id()) {
                $message->update(['deleted_for_sender_at' => now()]);
            } else {
                $message->update(['deleted_for_receiver_at' => now()]);
            }
        }

        return response()->json(['success' => true]);
    }
}
