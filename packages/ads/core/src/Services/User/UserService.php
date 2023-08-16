<?php

namespace Ads\Core\Services\User;

use Ads\Core\Exceptions\User\UserCanNotUpdatedException;
use Ads\Core\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private AuthService $authService
    )
    {
    }

    public function index(array $params = []): Collection
    {
        if ($this->authService->user()->hasPermission('user_show')) {
            return User::all();
        }

        return $this->authService->user()->children;
    }

    public function show(User $user): User
    {
        return $user;
    }

    /**
     * Store new user.
     *
     * @param array $params
     * @return User
     */
    public function store(array $params = []): User
    {
        $user = $this->authService->user();

        if (!array_key_exists('parent_id', $params)) {
            $params['parent_id'] = $user->id;
        }

        $params['password'] = $this->authService->cryptPassword($params['password']);

        return User::create($params);
    }

    /**
     * @throws UserCanNotUpdatedException
     */
    public function update(User $user, array $params): User
    {
        if ($params['password'] ?? false) {
            $params['password'] = $this->authService->cryptPassword($params['password']);
        }

        if ($user->update($params)) {
            return $user->refresh();
        }

        throw new UserCanNotUpdatedException();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Получить модель пользователя по реквизитам
     *
     * @params array<string $field , string $value> $credentials - массив, где ключ - это поле в базе данных,
     * значение - значение в этом поле.
     * @throws UserNotFoundException
     */
    public function userByCredential(array $credentials): User
    {
        return User::where(function (Builder $query) use ($credentials) {
            foreach ($credentials as $field => $value) {
                if (!in_array($field, ['phone', 'email', 'login'])) {
                    continue;
                }

                $query = $query->orWhere($field, $value);
            }

            return $query;
        })->first() ?? throw new UserNotFoundException();
    }

    /**
     * Getting auth user with permissions, roles and other info.
     *
     * @return User
     */
    public function info(): User
    {
        return $this->authService
            ->user()
            ->setAppends(['permissions'])
            ->load('roles');
    }
}
