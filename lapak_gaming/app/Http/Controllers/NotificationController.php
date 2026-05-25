<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'all');
        $filter = in_array($filter, ['all', 'transaction', 'event_reward', 'general'], true) ? $filter : 'all';

        $baseQuery = MarketplaceNotification::query()
            ->where('user_id', $user?->id);

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'transaction' => $this->applyCategoryFilter((clone $baseQuery), 'transaction')->count(),
            'event_reward' => $this->applyCategoryFilter((clone $baseQuery), 'event_reward')->count(),
            'general' => $this->applyCategoryFilter((clone $baseQuery), 'general')->count(),
        ];

        $notifications = $this->applyCategoryFilter($baseQuery, $filter)
            ->latest()
            ->paginate(15)
            ->withQueryString();
        
        return view('notifications.index', compact('notifications', 'filter', 'counts'));
    }

    public function poll(Request $request): JsonResponse
    {
        $notifications = MarketplaceNotification::query()
            ->with('broadcast')
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
        if ((int) $notification->user_id !== (int) $request->user()->id) {
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

    public function destroy(Request $request, MarketplaceNotification $notification): JsonResponse|RedirectResponse
    {
        if ((int) $notification->user_id !== (int) $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }

            return back()->with('error', 'Not found');
        }

        $notification->delete();

        if (! $request->expectsJson()) {
            return back()->with('success', 'Notifikasi berhasil dihapus.');
        }

        return response()->json(['success' => true]);
    }

    public function destroyAll(Request $request): JsonResponse|RedirectResponse
    {
        MarketplaceNotification::query()
            ->where('user_id', $request->user()->id)
            ->delete();

        if (! $request->expectsJson()) {
            return back()->with('success', 'Semua notifikasi berhasil dihapus.');
        }

        return response()->json(['success' => true]);
    }

    private function applyCategoryFilter($query, string $filter)
    {
        if ($filter === 'all') {
            return $query;
        }

        if ($filter === MarketplaceNotification::CATEGORY_EVENT_REWARD) {
            return $query->where(function ($q): void {
                $q->where('type', 'admin-event_reward')
                    ->orWhereHas('broadcast', fn ($q2) => $q2->where('type', 'admin-event_reward'));
            });
        }

        if ($filter === MarketplaceNotification::CATEGORY_TRANSACTION) {
            return $query->where(function ($q): void {
                $q->where('type', 'transaction')
                    ->orWhere('type', 'like', 'order-%')
                    ->orWhere('type', 'like', 'payment-%')
                    ->orWhere('type', 'like', 'wallet-%')
                    ->orWhereIn('type', ['deposit', 'withdraw', 'escrow_hold'])
                    ->orWhereHas('broadcast', fn ($q2) => $q2->where('type', 'transaction')
                        ->orWhere('type', 'like', 'order-%')
                        ->orWhere('type', 'like', 'payment-%')
                        ->orWhere('type', 'like', 'wallet-%')
                        ->orWhereIn('type', ['deposit', 'withdraw', 'escrow_hold']));
            });
        }

        return $query->where(function ($q): void {
            $q->where('type', '!=', 'admin-event_reward')
                ->where('type', 'not like', 'order-%')
                ->where('type', 'not like', 'payment-%')
                ->where('type', 'not like', 'wallet-%')
                ->whereNotIn('type', ['transaction', 'deposit', 'withdraw', 'escrow_hold'])
                ->orWhereHas('broadcast', fn ($q2) => $q2->where('type', '!=', 'admin-event_reward')
                    ->where('type', 'not like', 'order-%')
                    ->where('type', 'not like', 'payment-%')
                    ->where('type', 'not like', 'wallet-%')
                    ->whereNotIn('type', ['transaction', 'deposit', 'withdraw', 'escrow_hold']));
        });
    }
}
