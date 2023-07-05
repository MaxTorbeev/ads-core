<?php

namespace Ads\Websockets\Seeders;

use Ads\Core\Seeders\RoleSeeder;

class WebsocketRoleSeeder extends RoleSeeder
{
    public function getDefault(): array
    {
        return [
            [
                'name' => 'admin',
                'label' => 'Администратор',
                'permissions' => [
                    'cache_clear'
                ]
            ]
        ];
    }
}
