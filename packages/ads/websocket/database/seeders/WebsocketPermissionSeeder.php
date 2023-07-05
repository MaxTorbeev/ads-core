<?php

namespace Ads\Websockets\Seeders;

use Ads\Core\Seeders\PermissionSeeder;

class WebsocketPermissionSeeder extends PermissionSeeder
{
    public function getDefault(): array
    {
        return [
            [
                'name' => 'websocket_dashboard',
                'label' => 'Панель управления веб сокетами'
            ]
        ];
    }
}
