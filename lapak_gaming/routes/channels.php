
<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel khusus untuk per percakapan
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    if (!$conversation) return false;

    // Hanya buyer atau seller yang terdaftar di percakapan ini yang bisa join
    return (int) $user->id === (int) $conversation->buyer_id || 
           (int) $user->id === (int) $conversation->seller_id;
});
