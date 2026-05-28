<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;

class ChatController extends Controller
{
    /**
     * Mengambil daftar percakapan (Inbox Sidebar
     */

   public function inbox()
{
    $user = Auth::user();

    // CHAT SEBAGAI BUYER
    $buyerChats = Conversation::with([
            'buyer',
            'seller',
            'messages',
            'product',
            'order'
        ])
        ->where('buyer_id', $user->id)
        ->orderByDesc('last_message_at')
        ->get();

    // CHAT SEBAGAI SELLER
    $sellerChats = Conversation::with([
            'buyer',
            'seller',
            'messages',
            'product',
            'order'
        ])
        ->where('seller_id', $user->id)
        ->orderByDesc('last_message_at')
        ->get();

    return view('chat.inbox', compact(
        'buyerChats',
        'sellerChats'
    ));
}

public function index($id)
{
    return redirect()->back();
}

public function orderChat(\App\Models\Order $order)
{
    $buyerId = $order->buyer_id;
    $sellerId = $order->seller_id;

    // Cari conversation yang sudah ada
    $conversation = Conversation::where(function ($q) use ($buyerId, $sellerId) {
        $q->where('buyer_id', $buyerId)
          ->where('seller_id', $sellerId);
    })->first();

    // Kalau belum ada, buat baru
    if (!$conversation) {
        $conversation = Conversation::create([
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
        ]);
    }

    // Langsung buka halaman chat modern
    return redirect()->route('chat.show', $conversation->id);
}

public function show(Request $request, Conversation $conversation)
{
    $user = auth()->user();

    $role = $request->get('role', 'buyer');

    $conversation->markReadFor($user->id);

    if ($role === 'seller') {

        $sidebarConversations = Conversation::with([
                'buyer',
                'seller',
                'messages'
            ])
            ->where('seller_id', $user->id)
            ->latest('last_message_at')
            ->get();

    } else {

        $sidebarConversations = Conversation::with([
                'buyer',
                'seller',
                'messages'
            ])
            ->where('buyer_id', $user->id)
            ->latest('last_message_at')
            ->get();
    }
    $messages = $conversation->messages()
    ->with('sender')
    ->whereNull('deleted_for_everyone_at')

    ->where(function ($q) {
        $q->whereNull('deleted_for_sender_at')
          ->orWhere('sender_id', '!=', auth()->id());
    })

    ->where(function ($q) {
        $q->whereNull('deleted_for_receiver_at')
          ->orWhere('receiver_id', '!=', auth()->id());
    })

    ->orderBy('created_at')
    ->get();

    return view('chat.show', [
        'conversation' => $conversation,
        'messages' => $messages,
        'sidebarConversations' => $sidebarConversations,
        'role' => $role,
    ]);
}

public function poll(Conversation $conversation)
{
    if (
        Auth::id() !== $conversation->buyer_id &&
        Auth::id() !== $conversation->seller_id
    ) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Mark as read in real-time as user is active inside the chat room
    $conversation->markReadFor(Auth::id());

    $messages = $conversation->messages()
    ->with('sender')
    ->whereNull('deleted_for_everyone_at')

    ->where(function ($q) {
        $q->whereNull('deleted_for_sender_at')
          ->orWhere('sender_id', '!=', auth()->id());
    })

    ->where(function ($q) {
        $q->whereNull('deleted_for_receiver_at')
          ->orWhere('receiver_id', '!=', auth()->id());
    })

    ->orderBy('created_at', 'asc')
    ->take(50)
    ->get();

    return response()->json([
        'messages' => $messages->map(
            fn($m) => $m->toChat(Auth::id())
        )
    ]);
}

    public function pollInbox(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['total_unread' => 0]);
        }
        $unread = Conversation::where('buyer_id', $user->id)->sum('unread_buyer')
                + Conversation::where('seller_id', $user->id)->sum('unread_seller');
        return response()->json(['total_unread' => (int) $unread]);
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
            $conversations->map(fn (Conversation $conversation) => $conversation->toSummary($user->id))
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
        ->whereNull('deleted_for_everyone_at')

        ->where(function ($q) {
            $q->whereNull('deleted_for_sender_at')
            ->orWhere('sender_id', '!=', auth()->id());
        })

        ->where(function ($q) {
            $q->whereNull('deleted_for_receiver_at')
            ->orWhere('receiver_id', '!=', auth()->id());
        })

        ->orderBy('created_at', 'asc')
        ->paginate(30);

        return response()->json([
            'messages' => collect($messages->items())->map(fn($m) => $m->toChat(Auth::id())),
            'next_page' => $messages->nextPageUrl()
        ]);
    }

    /**
     * Kirim Pesan Baru
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message'    => 'nullable|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $messageText = trim($request->input('message', ''));

        if ($messageText === '' && !$request->hasFile('attachment')) {
            return response()->json([
                'message' => 'Pesan atau foto harus diisi.'
            ], 422);
        }

        $user = Auth::user();

        // Proteksi participant
        // if (
        //     $user->id !== $conversation->buyer_id &&
        //     $user->id !== $conversation->seller_id
        // ) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }

        $receiverId = $user->id === $conversation->buyer_id
            ? $conversation->seller_id
            : $conversation->buyer_id;

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            $attachmentPath = $file->store(
                'foto-chat',
                'public_app_public'
            );

            $attachmentType = explode(
                '/',
                $file->getMimeType()
            )[0];
        }

        $message = Message::create([
        'conversation_id' => $conversation->id,
        'sender_id'       => $user->id,
        'receiver_id'     => $receiverId,
        'message'         => $messageText,
        'attachment_path' => $attachmentPath,
        'attachment_type' => $attachmentType,
    ]);

    $conversation->update([
        'last_message_at' => now(),
        'last_message'    => $messageText ?: '[Lampiran]'
    ]);

    // NOTE: Do not create global marketplace notifications for normal chat messages.
    // Chat UI handles its own in-app badges and realtime events. Creating
    // MarketplaceNotification for every chat message caused them to appear
    // in the global notifications dropdown which is undesired.

    broadcast(new MessageSent($message))->toOthers();

    return response()->json(
        $message->toChat($user->id)
    );
    }
    /**
     * Fitur WhatsApp: Edit Pesan
     */
    public function editMessage(Request $request, Message $message)
    {
        if ((int) $message->sender_id !== (int) Auth::id()) {
            return response()->json([
                'error' => 'Forbidden'
            ], 403);
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        if ($message->created_at->diffInMinutes(now()) > 5) {

        return response()->json([
            'success' => false,
            'message' => 'Pesan hanya bisa diedit dalam 5 menit.'
        ], 403);
    }

        $message->update([
            'message' => $request->message,
            'edited_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->toChat(Auth::id())
        ]);
    }

    /**
     * Fitur WhatsApp: Delete Pesan
     */
    public function deleteMessage(Request $request, Message $message)
    {
        if ((int) Auth::id() !== (int) $message->sender_id &&
        (int) Auth::id() !== (int) $message->receiver_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $type = $request->input('type'); // 'me' atau 'everyone'

        if ($type === 'everyone') {
            if ((int) $message->sender_id !== (int) Auth::id()) return response()->json(['error' => 'Forbidden'], 403);
            $message->update(['deleted_for_everyone_at' => now()]);
        } else {
            if ((int) $message->sender_id === (int) Auth::id()) {
                $message->update(['deleted_for_sender_at' => now()]);
            } else {
                $message->update(['deleted_for_receiver_at' => now()]);
            }
        }

        return response()->json(['success' => true]);
    }

   
    public function product(Product $product)
{
    $buyerId = Auth::id();

    // FIX
    $sellerId = $product->seller_id;

    // Prevent self chat
    if ((int)$buyerId === (int)$sellerId) {
        return redirect()->back()
            ->with('error', 'Anda tidak bisa mengirim pesan ke diri sendiri.');
    }

    $conversation = Conversation::where(function ($query) use ($buyerId, $sellerId) {
        $query->where('buyer_id', $buyerId)
              ->where('seller_id', $sellerId);
    })->first();

    if (!$conversation) {
        $conversation = Conversation::create([
            'buyer_id'       => $buyerId,
            'seller_id'      => $sellerId,
            'last_message_at'=> now(),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $buyerId,
            'receiver_id'     => $sellerId,
            'message'         => 'Halo, saya tertarik dengan produk: ' . $product->name,
        ]);

        // Intentionally not creating MarketplaceNotification for product-initiated
        // chat. Product chats should not appear in global notifications.
    }

    return redirect()->route('chat.show', $conversation->id);
   }

    /**
     * Tandai percakapan sudah dibaca untuk user yang sedang login
     */
    public function markAsRead(Conversation $conversation)
    {
        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->markReadFor(Auth::id());

        return response()->json([
            'success' => true,
            'unread_count' => $conversation->unreadFor(Auth::id())
        ]);
    }
} 

