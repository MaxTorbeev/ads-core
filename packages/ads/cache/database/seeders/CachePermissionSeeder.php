<?php

namespace Ads\Cache\Seeders;

use Ads\Core\Models\Permission;
use Ads\Core\Seeders\PermissionSeeder;
use Illuminate\Database\Seeder;

class CachePermissionSeeder extends PermissionSeeder
{
    public function getDefault(): array
    {
        return [
            [
                'name' => 'cache_clear',
                'label' => 'Очистка кэша'
            ]
        ];
    }
}
