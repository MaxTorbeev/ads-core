<?php

namespace Database\Seeders;

use Ads\Core\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public array $permissions = [
        [
            'name' => 'show_user',
            'label' => 'Просмотр пользователей'
        ],
        [
            'name' => 'create_user',
            'label' => 'Создание/редактирование пользователя'
        ],
        [
            'name' => 'delete_user',
            'label' => 'Удаление пользователя'
        ],
        [
            'name' => 'cache_clear',
            'label' => 'Очистка кэша'
        ]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions as $role) {
            if (!Permission::whereName($role['name'])->exists()) {
                Permission::create($role);
            }
        }
    }
}
