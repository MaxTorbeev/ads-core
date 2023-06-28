<?php

namespace Ads\Core\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function getDefault(): array
    {
        return [
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'phone' => '79606773455',
                'login' => 'admin',
                'password' => bcrypt(config('core.admin.password')),
                'roles' => [
                    'admin'
                ]
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = $this->getDefault();

        foreach ($users as $userData) {
            $roles = $userData['roles'];

            unset($userData['roles']);

            $user = User::whereLogin($userData['login'])->exists()
                ? User::whereLogin($userData['login'])->first()
                : User::create($userData);

            $user->assignRoles($roles);
        }
    }
}
