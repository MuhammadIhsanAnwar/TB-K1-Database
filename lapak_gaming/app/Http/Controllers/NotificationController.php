<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
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
}