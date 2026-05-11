<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyConsentController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\SellerChatController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Policy Consent Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/policy-consent/accept', [PolicyConsentController::class, 'accept']);
    Route::post('/policy-consent/decline', [PolicyConsentController::class, 'decline']);
    Route::get('/policy-consent/user', [PolicyConsentController::class, 'getUserConsents']);
    Route::get('/policy-consent/{policyType}', [PolicyConsentController::class, 'getLatestConsent']);
});

// Admin - Get consent statistics (accessible to admins only)
Route::middleware(['auth:sanctum'])->get('/policy-consent/stats', [PolicyConsentController::class, 'getConsentStats']);

// Product Comments Routes
Route::get('/products/{product}/comments', [ProductCommentController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/{product}/comments', [ProductCommentController::class, 'store']);
    Route::patch('/comments/{comment}', [ProductCommentController::class, 'update']);
    Route::delete('/comments/{comment}', [ProductCommentController::class, 'destroy']);
    Route::post('/comments/{comment}/like', [ProductCommentController::class, 'toggleLike']);
    Route::post('/comments/{comment}/approve', [ProductCommentController::class, 'approve']);
    Route::post('/comments/{comment}/reject', [ProductCommentController::class, 'reject']);
});

// Seller Chat Routes (improved UX)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/seller/chat/conversations', [SellerChatController::class, 'getConversations']);
    Route::get('/seller/chat/{senderId}/{productId}', [SellerChatController::class, 'getChat']);
    Route::post('/seller/chat/send', [SellerChatController::class, 'sendMessage']);
    Route::post('/seller/chat/{senderId}/{productId}/mark-read', [SellerChatController::class, 'markAsRead']);
    Route::get('/seller/chat/analytics', [SellerChatController::class, 'getChatAnalytics']);
});