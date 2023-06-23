<?php

namespace Ads\Core\Services\User;

use Ads\Core\Exceptions\User\UserPasswordInvalidException;
use App\Models\User;
use Ads\Core\Exceptions\User\UserNotFoundException;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthServiceProvider
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
     * @throws UserNotFoundException
     * @throws UserPasswordInvalidException
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->passwordIsValid($user, $password)) {
            $token = $this->auth($user);

            return array_merge(
                $user->makeHidden('tokens')->toArray(),
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


}
