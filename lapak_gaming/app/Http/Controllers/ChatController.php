<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    // ─── INBOX ────────────────────────────────────────────────────────────────

    public function inbox(Request $request): View
    {
        $user = $request->user();
        $conversations = Conversation::query()
            ->where(fn($q) => $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id))
            ->with(['buyer', 'seller', 'product', 'order'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('chat.inbox', compact('conversations'));
    }

    // ─── SHOW Conversation ────────────────────────────────────────────────────

    protected function authorizeParticipant(Conversation $conversation, User $user): void
    {
        abort_unless(
            (int) $conversation->buyer_id === (int) $user->id || (int) $conversation->seller_id === (int) $user->id,
            403
        );
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $user = $request->user();
        $this->authorizeParticipant($conversation, $user);
        $conversation->load(['buyer', 'seller', 'product', 'order']);
        $conversation->markReadFor($user->id);
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $user->id)->whereNull('read_at')
            ->update(['read_at' => now(), 'is_read' => true]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender')->oldest()->get();

        $sidebarConversations = Conversation::query()
            ->where(fn($q) => $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id))
            ->with(['buyer', 'seller', 'product'])
            ->orderByDesc('last_message_at')->limit(30)->get();

        return view('chat.show', compact('conversation', 'messages', 'sidebarConversations'));
    }

    // ─── SEND Message ─────────────────────────────────────────────────────────

    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();
        $this->authorizeParticipant($conversation, $user);
        $data = $request->validate(['message' => ['required', 'string', 'max:2000']]);
        $receiverId = (int) $user->id === (int) $conversation->buyer_id
            ? $conversation->seller_id : $conversation->buyer_id;

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'order_id'        => $conversation->order_id,
            'product_id'      => $conversation->product_id,
            'sender_id'       => $user->id,
            'receiver_id'     => $receiverId,
            'message'         => $data['message'],
        ]);
        $conversation->updateLastMessage($message);
        $conversation->incrementUnread($user->id);

        return response()->json(['message' => $this->fmt($message, $user->id)]);
    }

    // ─── POLL ────────────────────────────────────────────────────────────────

    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();
        $this->authorizeParticipant($conversation, $user);
        $since = (int) $request->query('since', 0);
        $messages = Message::where('conversation_id', $conversation->id)
            ->when($since, fn($q) => $q->where('id', '>', $since))
            ->with('sender')->oldest()->get();

        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $user->id)->whereNull('read_at')
            ->update(['read_at' => now(), 'is_read' => true]);
        $conversation->markReadFor($user->id);

        return response()->json([
            'messages' => $messages->map(fn($m) => $this->fmt($m, $user->id)),
            'last_id'  => $messages->last()?->id ?? $since,
        ]);
    }

    // ─── PRODUCT CHAT ────────────────────────────────────────────────────────

    public function product(Request $request, Product $product): RedirectResponse
    {
        $product->load('seller');
        $user = $request->user();
        abort_unless($product->seller_id, 404);

        if ((int) $user->id === (int) $product->seller_id) {
            $buyerId = (int) $request->query('buyer', 0);

            if ($buyerId > 0) {
                $conversation = Conversation::where('product_id', $product->id)
                    ->where('seller_id', $user->id)
                    ->where('buyer_id', $buyerId)
                    ->first();

                if ($conversation) {
                    return redirect()->route('chat.show', $conversation);
                }

                return redirect()->route('chat.inbox')
                    ->with('alert', [
                        'type' => 'error',
                        'title' => 'Percakapan tidak ditemukan',
                        'text' => 'Silakan pilih kembali pelanggan yang ingin diajak chat.',
                    ]);
            }

            return redirect()->route('chat.inbox');
        }

        $conversation = Conversation::findOrCreateForProduct($user->id, $product->seller_id, $product->id);
        return redirect()->route('chat.show', $conversation);
    }

    // ─── ORDER CHAT ──────────────────────────────────────────────────────────

    public function orderChat(Request $request, Order $order): RedirectResponse
    {
        $user = $request->user();
        abort_unless($order->buyer_id === $user->id || $order->seller_id === $user->id, 403);
        $conversation = Conversation::findOrCreateForOrder(
            $order->buyer_id, $order->seller_id, $order->id
        );
        return redirect()->route('chat.show', $conversation);
    }

    // ─── POLL INBOX (for sidebar badge) ──────────────────────────────────────

    public function pollInbox(Request $request): JsonResponse
    {
        $user = $request->user();
        $unread = Conversation::where('buyer_id', $user->id)->sum('unread_buyer')
                + Conversation::where('seller_id', $user->id)->sum('unread_seller');
        return response()->json(['total_unread' => (int) $unread]);
    }

    // ─── LEGACY ROUTES (backward compatibility) ───────────────────────────────

    public function index(Request $request, Order $order): RedirectResponse
    {
        abort_unless((int) $order->buyer_id === (int) $request->user()->id || (int) $order->seller_id === (int) $request->user()->id, 403);
        $conversation = Conversation::findOrCreateForOrder($order->buyer_id, $order->seller_id, $order->id);
        return redirect()->route('chat.show', $conversation);
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        abort_unless((int) $order->buyer_id === (int) $request->user()->id || (int) $order->seller_id === (int) $request->user()->id, 403);
        $data = $request->validate(['message' => ['required', 'string', 'max:2000']]);
        $conversation = Conversation::findOrCreateForOrder($order->buyer_id, $order->seller_id, $order->id);
        $receiverId = $order->buyer_id === $request->user()->id ? $order->seller_id : $order->buyer_id;
        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'order_id'  => $order->id,
            'sender_id' => $request->user()->id,
            'receiver_id'=> $receiverId,
            'message'   => $data['message'],
        ]);
        $conversation->updateLastMessage($msg);
        $conversation->incrementUnread($request->user()->id);
        return redirect()->route('chat.show', $conversation);
    }

    public function storeProduct(Request $request, Product $product): RedirectResponse
    {
        $product->load('seller');
        $user = $request->user();
        abort_unless($product->seller_id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'receiver_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ((int) $user->id === (int) $product->seller_id) {
            $buyerId = (int) ($data['receiver_id'] ?? 0);
            abort_unless($buyerId > 0 && $buyerId !== $user->id, 403);
        } else {
            $buyerId = $user->id;
        }

        $conversation = Conversation::findOrCreateForProduct($buyerId, $product->seller_id, $product->id);
        $receiverId = (int) $user->id === (int) $buyerId ? $product->seller_id : $buyerId;

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'product_id'      => $product->id,
            'sender_id'       => $user->id,
            'receiver_id'     => $receiverId,
            'message'         => $data['message'],
        ]);

        $conversation->updateLastMessage($msg);
        $conversation->incrementUnread($user->id);

        return redirect()->route('chat.show', $conversation);
    }

    public function pollProduct(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();
        abort_unless($product->seller_id, 404);
        $buyerId = $user->id === $product->seller_id
            ? (int) $request->query('buyer', 0) : $user->id;
        if ($buyerId <= 0) return response()->json(['messages' => [], 'last_id' => 0]);
        $conversation = Conversation::where('product_id', $product->id)
            ->where('buyer_id', $buyerId)->where('seller_id', $product->seller_id)->first();
        if (! $conversation) return response()->json(['messages' => [], 'last_id' => 0]);
        $since = (int) $request->query('since', 0);
        $messages = Message::where('conversation_id', $conversation->id)
            ->when($since, fn($q) => $q->where('id', '>', $since))
            ->with('sender')->oldest()->get();

        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $user->id)->whereNull('read_at')
            ->update(['read_at' => now(), 'is_read' => true]);
        $conversation->markReadFor($user->id);

        return response()->json([
            'messages' => $messages->map(fn($m) => $this->fmt($m, $user->id)),
            'last_id'  => $messages->last()?->id ?? $since,
        ]);
    }

    private function fmt($m, int $authId): array
    {
        return [
            'id'          => $m->id,
            'sender_id'   => $m->sender_id,
            'is_mine'     => $m->sender_id === $authId,
            'message'     => $m->message,
            'sender_name' => $m->sender?->name,
            'avatar'      => $m->sender?->avatar_url,
            'time'        => $m->created_at->format('H:i'),
            'date'        => $m->created_at->format('d M Y'),
            'is_read'     => (bool) $m->is_read,
        ];
    }
}
