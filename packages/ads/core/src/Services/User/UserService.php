<?php

namespace Ads\Core\Services\User;

use Ads\Core\Exceptions\User\UserCanNotUpdatedException;
use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function index(array $params = []): Collection
    {
        return User::all();
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function store(array $params = []): User
    {
        return User::create($params);
    }

    /**
     * @throws UserCanNotUpdatedException
     */
    public function update(User $user, array $params): User
    {
        if ($user->update($params)) {
            return $user->refresh();
        }

        throw new UserCanNotUpdatedException();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
