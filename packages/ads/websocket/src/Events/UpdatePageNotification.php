<?php

namespace Ads\Websockets\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdatePageNotification implements ShouldBroadcastNow
{
    public array $notification;

    public function __construct()
    {
        $this->notification = [
            'alert' => 'warning',
            'message' => 'На портале произведены технические работы. Просьба обновить страницу.'
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification.refresh';
    }

    /**
     * @inheritDoc
     */
    public function broadcastOn(): array|Channel|PrivateChannel|string
    {
        return new PrivateChannel('general');
    }
}
