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
        ],
        [
            'name' => 'editor',
            'label' => 'Редактор',
            'permissions' => [
                'cache_clear'
            ]
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->roles as $role) {
            if (!Role::whereName($role['name'])->exists()) {
                unset($role['permissions']);

                Role::create($role);
            }
        }

        foreach (Role::all() as $role) {
            if ($role->name === 'admin') {
                foreach ((new PermissionSeeder())->permissions as $item) {
                    if ($role->hasPermissionTo($item['name'])) {
                        continue;
                    }

                    $role->givePermissionTo(Permission::whereName($item['name'])->first());
                }
            } else {
                $permissions = array_filter($this->roles, fn ($r) => $r['name'] === $role->name);

                if (isset($permissions['permissions'])) {
                    foreach ($permissions['permissions'] as $permission) {
                        if ($role->hasPermissionTo($permission)) {
                            continue;
                        }

                        $role->givePermissionTo(Permission::whereName($permission)->first());
                    }
                }
            }
        }

    }
}
