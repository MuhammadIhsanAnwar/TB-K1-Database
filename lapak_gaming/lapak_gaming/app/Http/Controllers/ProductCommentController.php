<?php

namespace App\Http\Controllers;

use App\Models\ProductComment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductCommentController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        $comments = $product->comments()
            ->mainComments()
            ->approved()
            ->with(['user', 'replies.user', 'likes'])
            ->withCount(['likes', 'replies'])
            ->orderByDesc('likes_count')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'data' => $comments->map(fn ($comment) => $this->formatComment($comment)),
        ]);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'rating' => 'nullable|integer|between:1,5',
            'parent_comment_id' => 'nullable|exists:product_comments,id',
        ]);

        $user = Auth::user();

        // Check if user is verified buyer (has purchased this product)
        $isVerifiedBuyer = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_id', $product->id)
            ->where('orders.buyer_id', $user->id)
            ->where('orders.status', 'completed')
            ->exists();

        $comment = $product->comments()->create([
            'user_id' => $user->id,
            'parent_comment_id' => $request->input('parent_comment_id'),
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'is_verified_buyer' => $isVerifiedBuyer,
            'status' => 'approved',
        ]);

        // Parent comment replies are counted on demand from the replies relationship.

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => $this->formatComment($comment->load(['user', 'likes'])),
        ], 201);
    }

    public function update(Request $request, ProductComment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $this->formatComment($comment),
        ]);
    }

    public function destroy(ProductComment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }

    public function toggleLike(ProductComment $comment): JsonResponse
    {
        $userId = Auth::id();
        $liked = $comment->toggleLike($userId);

        return response()->json([
            'liked' => $liked,
            'likes_count' => $comment->likes()->count(),
        ]);
    }

    public function approve(ProductComment $comment): JsonResponse
    {
        $this->authorize('approve', $comment);

        $comment->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Comment approved',
            'data' => $comment,
        ]);
    }

    public function reject(ProductComment $comment): JsonResponse
    {
        $this->authorize('approve', $comment);

        $comment->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Comment rejected',
        ]);
    }

    private function formatComment(ProductComment $comment): array
    {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'rating' => $comment->rating,
            'is_verified_buyer' => $comment->is_verified_buyer,
            'likes_count' => $comment->likes_count,
            'replies_count' => $comment->replies_count,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'avatar' => $comment->user->avatar_url ?? null,
            ],
            'is_seller_reply' => $comment->isSellerReply(),
            'is_liked_by_user' => $comment->isLikedByUser(),
            'can_edit' => Auth::id() === $comment->user_id,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'replies' => $comment->replies->map(fn ($reply) => $this->formatComment($reply)),
        ];
    }
}
