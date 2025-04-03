<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});
