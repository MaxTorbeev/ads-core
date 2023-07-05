<?php

namespace Ads\Cache\Seeders;

use Ads\Core\Seeders\RoleSeeder;

class CacheRoleSeeder extends RoleSeeder
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
