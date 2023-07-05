<?php

namespace Database\Seeders;

use Ads\Cache\Seeders\CachePermissionSeeder;
use Ads\Cache\Seeders\CacheRoleSeeder;
use Ads\Core\Seeders\PermissionSeeder;
use Ads\Core\Seeders\RoleSeeder;
use Ads\Core\Seeders\UserSeeder;
use Ads\Websockets\Seeders\WebsocketPermissionSeeder;
use Ads\Websockets\Seeders\WebsocketRoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CachePermissionSeeder::class);
        $this->call(CacheRoleSeeder::class);
        $this->call(WebsocketPermissionSeeder::class);
        $this->call(WebsocketRoleSeeder::class);
    }
}
