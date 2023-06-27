<?php

namespace Ads\Core\Services\User;

use Ads\Core\Exceptions\User\UserPasswordInvalidException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService
{
    /**
     * Getting auth user.
     *
     * @return Authenticatable
     */
    public function user(): Authenticatable
    {
        return auth('sanctum')->user();
    }

    /**
     * Auth user by email and password
     *
     * @throws UserPasswordInvalidException
     */
    public function login(User $user, string $password): array
    {
        if ($user->passwordIsValid($password)) {
            $token = $this->auth($user);

            return array_merge(
                $user
                    ->setHidden(['created_at', 'updated_at', 'user_ws_id', 'parent_id', 'password', 'email_verified_at', 'remember_token'])
                    ->setAppends(['permissions'])
                    ->makeHidden('tokens')
                    ->toArray(),
                ['token' => $token],
            );
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

    public function logout(User|null $user = null): bool
    {
        if ($user) {
            return $user->tokens()->delete();
        }

        return $this->user()->tokens()->delete();
    }

    public function cryptPassword(string $password): string
    {
        return bcrypt($password);
    }
}
