<?php

namespace Database\Seeders;

use Ads\Core\Seeders\PermissionSeeder;
use Ads\Core\Seeders\RoleSeeder;
use Ads\Core\Seeders\UserSeeder;
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
    }
}
