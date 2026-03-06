<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ChatController;

Route::middleware('api')->group(function () {
    // Public API Routes
    Route::get('/search', [SearchController::class, 'search'])->name('api.search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('api.search.suggestions');

    // Authenticated API Routes
    Route::middleware('auth:sanctum')->group(function () {
        // Chat
        Route::prefix('chat')->group(function () {
            Route::get('/conversations', [ChatController::class, 'getConversations'])->name('api.chat.conversations');
            Route::get('/{conversationId}', [ChatController::class, 'getMessages'])->name('api.chat.messages');
            Route::post('/{receiverId}', [ChatController::class, 'sendMessage'])->name('api.chat.send');
            Route::put('/{conversationId}/read', [ChatController::class, 'markAsRead'])->name('api.chat.read');
            Route::get('/unread/count', [ChatController::class, 'getUnreadCount'])->name('api.chat.unread');
        });
    });
});
