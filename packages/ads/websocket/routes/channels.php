<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('admin.{id}', function ($user, $id) {
    if (!$user->hasPermission('websocket_dashboard'))
        abort(403);

    return (int)$user->id === (int)$id;
});

Broadcast::channel('general', function () {
    return true;
});
