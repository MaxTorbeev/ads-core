<?php

namespace Ads\Core\Services\User;

use Ads\Core\Exceptions\User\UserPasswordInvalidException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService
{
    /**
     * Check auth user
     *
     * @return bool
     */
    public function check(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Getting auth user.
     *
     * @return Authenticatable|User
     */
    public function user(): Authenticatable|User
    {
        return auth('sanctum')->user();
    }

    /**
     * Auth user by email and password
     *
     * @throws UserPasswordInvalidException
     */
    public function login(User $user, string $password): User
    {
        if ($user->passwordIsValid($password)) {
            $token = $this->auth($user);

            return $user
                ->setAppends(['permissions'])
                ->setAttribute('token', $token);
        }

        throw new UserPasswordInvalidException('Неверный email или пароль');
    }

    /**
     * Auth user and getting access_token.
     *
     * @param User|Authenticatable $user
     * @return string
     */
    public function auth(User|Authenticatable $user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }

    /**
     * Logout user.
     *
     * @param User|null $user
     * @return bool
     */
    public function logout(User|null $user = null): bool
    {
        if ($user) {
            return $user->tokens()->delete();
        }

        return $this->user()->tokens()->delete();
    }

    /**
     * Crypt password by bcrypt.
     *
     * @param string $password
     * @return string
     */
    public function cryptPassword(string $password): string
    {
        return bcrypt($password);
    }
}
