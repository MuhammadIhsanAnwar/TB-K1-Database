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
        try {
            $notifications = Auth::user()->notifications()->latest()->paginate(15);
        } catch (\Exception $e) {
            $notifications = collect([]);
        }
        
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
}