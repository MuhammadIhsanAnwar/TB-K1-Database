<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
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