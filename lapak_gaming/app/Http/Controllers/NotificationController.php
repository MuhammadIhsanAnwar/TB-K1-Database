<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = MarketplaceNotification::query()
            ->where('user_id', $user?->id)
            ->latest()
            ->paginate(15);
        
        return view('notifications.index', compact('notifications'));
    }

    public function poll(Request $request): JsonResponse
    {
        $notifications = MarketplaceNotification::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'unread_count' => $notifications->where('is_read', false)->count(),
            'items' => $notifications,
        ]);
    }

    public function markRead(Request $request, MarketplaceNotification $notification): JsonResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }

        $notification->forceFill([
            'is_read' => true,
            'read_at' => now(),
        ])->save();

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        MarketplaceNotification::query()
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}