<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => bcrypt(rand(100, 1000)),
                'roles' => [
                    'admin'
                ]
            ]
        ];

        foreach ($users as $userData) {
            $roles = $userData['roles'];

            unset($userData['roles']);

            $user = User::whereEmail($userData['email'])->exists()
                ? User::whereEmail($userData['email'])->first()
                : User::create($userData);

            $user->assignRoles($roles);
        }
    }
}
