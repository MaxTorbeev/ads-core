<?php

namespace Ads\Core\Seeders;

use Ads\Core\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function getDefault(): array
    {
        return [
            [
                'name' => 'user_show',
                'label' => 'Просмотр пользователей'
            ],
            [
                'name' => 'user_create',
                'label' => 'Создание/редактирование пользователя'
            ],
            [
                'name' => 'user_delete',
                'label' => 'Удаление пользователя'
            ],
            [
                'name' => 'can_create_main_user',
                'label' => 'Удаление пользователя'
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getDefault() as $role) {
            if (!Permission::whereName($role['name'])->exists()) {
                Permission::create($role);
            }
        }
    }
}
