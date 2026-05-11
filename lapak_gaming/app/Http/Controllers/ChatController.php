<?php

namespace App\Http\Controllers;

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
    private function conversationQuery(int $currentUserId, int $peerUserId, int $productId)
    {
        return Message::query()
            ->whereNull('order_id')
            ->where('product_id', $productId)
            ->where(function ($query) use ($currentUserId, $peerUserId): void {
                $query->where(function ($q) use ($currentUserId, $peerUserId): void {
                    $q->where('sender_id', $currentUserId)->where('receiver_id', $peerUserId);
                })->orWhere(function ($q) use ($currentUserId, $peerUserId): void {
                    $q->where('sender_id', $peerUserId)->where('receiver_id', $currentUserId);
                });
            });
    }

    public function product(Request $request, Product $product): View
    {
        $product->load('seller');
        $user = $request->user();

        abort_unless($product->seller_id, 404);

        $partner = null;
        $participants = collect();

        if ($user->id === $product->seller_id) {
            $participantIds = Message::query()
                ->whereNull('order_id')
                ->where('product_id', $product->id)
                ->where(function ($query) use ($user): void {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->get()
                ->flatMap(fn (Message $message) => [$message->sender_id, $message->receiver_id])
                ->filter(fn (int $id) => $id !== $user->id)
                ->unique()
                ->values();

            $participants = User::query()->whereIn('id', $participantIds)->get()->keyBy('id');

            $requestedPartnerId = (int) $request->query('buyer', 0);
            $partner = $participants->get($requestedPartnerId) ?? $participants->first();
        } else {
            $partner = $product->seller;
        }

        $messages = collect();
        if ($partner) {
            $messages = $this->conversationQuery($user->id, $partner->id, $product->id)
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->values();
        }

        return view('chat.product', [
            'product' => $product,
            'partner' => $partner,
            'participants' => $participants,
            'messages' => $messages,
        ]);
    }

    public function storeProduct(Request $request, Product $product): RedirectResponse
    {
        $product->load('seller');
        $user = $request->user();

        abort_unless($product->seller_id, 404);

        $receiverId = null;

        if ($user->id === $product->seller_id) {
            $receiverId = (int) $request->input('receiver_id', 0);
            abort_if($receiverId <= 0, 422, 'Pilih pembeli tujuan terlebih dahulu.');
        } else {
            $receiverId = $product->seller_id;
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'order_id' => null,
            'product_id' => $product->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $data['message'],
        ]);

        $params = [];
        if ($user->id === $product->seller_id) {
            $params['buyer'] = $receiverId;
        }

        return redirect()->route('chat.product', array_merge(['product' => $product->id], $params));
    }

    public function pollProduct(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();
        abort_unless($product->seller_id, 404);

        $peerId = 0;
        if ($user->id === $product->seller_id) {
            $peerId = (int) $request->query('buyer', 0);
        } else {
            $peerId = (int) $product->seller_id;
        }

        if ($peerId <= 0) {
            return response()->json(['messages' => [], 'unread_count' => 0]);
        }

        $messages = $this->conversationQuery($user->id, $peerId, $product->id)
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'messages' => $messages,
            'unread_count' => Message::query()
                ->whereNull('order_id')
                ->where('product_id', $product->id)
                ->where('receiver_id', $user->id)
                ->where('sender_id', $peerId)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    public function index(Request $request, Order $order): View
    {
        abort_unless($order->buyer_id === $request->user()->id || $order->seller_id === $request->user()->id, 403);

        return view('chat.index', [
            'order' => $order->load(['buyer', 'seller']),
            'messages' => Message::query()->where('order_id', $order->id)->latest()->take(50)->get()->reverse()->values(),
        ]);
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->buyer_id === $request->user()->id || $order->seller_id === $request->user()->id, 403);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $receiverId = $order->buyer_id === $request->user()->id ? $order->seller_id : $order->buyer_id;

        Message::create([
            'order_id' => $order->id,
            'sender_id' => $request->user()->id,
            'receiver_id' => $receiverId,
            'message' => $data['message'],
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }

    public function poll(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->buyer_id === $request->user()->id || $order->seller_id === $request->user()->id, 403);

        $messages = Message::query()->where('order_id', $order->id)->latest()->take(50)->get()->reverse()->values();

        return response()->json([
            'messages' => $messages,
            'unread_count' => Message::query()->where('order_id', $order->id)->where('receiver_id', $request->user()->id)->where('is_read', false)->count(),
        ]);
    }
}