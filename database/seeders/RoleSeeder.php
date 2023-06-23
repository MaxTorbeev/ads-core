<?php

namespace Database\Seeders;

use Ads\Core\Models\Permission;
use Ads\Core\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public array $roles = [
        [
            'name' => 'admin',
            'label' => 'Администратор',
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->roles as $role) {
            if (!Role::whereName($role['name'])->exists()) {
                $role = Role::create($role);

                foreach ((new PermissionSeeder())->permissions as $item) {
                    if ($role->hasPermissionTo($item['name'])) {
                        continue;
                    }

                    $role->givePermissionTo(Permission::whereName($item['name'])->first());
                }
            }
        }
    }
}
