<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
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

    public function markRead(Request $request, MarketplaceNotification $notification): JsonResponse|RedirectResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }

            return back()->with('error', 'Not found');
        }

        $notification->forceFill([
            'is_read' => true,
            'read_at' => now(),
        ])->save();

        if (! $request->expectsJson()) {
            return back()->with('success', 'Notifikasi ditandai telah dibaca.');
        }

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request): JsonResponse|RedirectResponse
    {
        MarketplaceNotification::query()
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (! $request->expectsJson()) {
            return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
        }

        return response()->json(['success' => true]);
    }
}