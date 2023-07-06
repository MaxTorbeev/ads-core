<?php

namespace Ads\Core\Seeders;

use Ads\Core\Models\Permission;
use Ads\Core\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function getDefault(): array
    {
        return [
            [
                'name' => 'admin',
                'label' => 'Администратор',
                'permissions' => array_map(fn ($item) => $item['name'], (new PermissionSeeder())->getDefault())
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getDefault() as $role) {
            if (!Role::whereName($role['name'])->exists()) {
                unset($role['permissions']);

                Role::create($role);
            }
        }

        foreach (Role::all() as $role) {
            $defaultRoles = array_filter($this->getDefault(), fn($r) => $r['name'] === $role->name);

            foreach ($defaultRoles as $defaultRole) {
                foreach ($defaultRole['permissions'] as $permission) {

                    if ($role->permissions->map(fn ($p) => $p->name)->contains($permission)) {
                        continue;
                    }

                    $role->givePermissionTo(Permission::whereName($permission)->first());
                }
            }
        }
    }
}
