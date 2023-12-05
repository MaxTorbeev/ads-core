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
        $auth = $this->authService->user();

        if ($this->authService->user()->hasPermission('user_show')) {
            return User::query()->whereNot('id', $auth->id)->get();
        }

        return User::query()
            ->whereIn('id', $auth->childrenIds())
            ->get();
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
        return $this->createOrUpdate($params);
    }

    /**
     * Update exists user
     *
     * @param User $user
     * @param array $params
     * @return User
     */
    public function update(User $user, array $params): User
    {
        return $this->createOrUpdate($params, $user);
    }

    /**
     * Create or update user.
     *
     * @param array $params
     * @param User|null $user
     * @return User
     * @throws UserCanNotUpdatedException
     */
    private function createOrUpdate(array $params, ?User $user = null): User
    {
        $auth = $this->authService->user();

        if ($auth->hasPermission('user_create')) {
            // Если у авторизированного пользователя есть permission user_create,
            // то новому пользователю будет указан parent_id
            $params['parent_id'] = $params['parent_id'] ?? null;
        } else {
            // Если у авторизированного пользователя нет permission user_create,
            // То новому пользователю будет указан parent_id равный id создателю
            $params['parent_id'] = $auth->id;
        }

        if (!empty($params['password'])) {
            $params['password'] = $this->authService->cryptPassword($params['password']);
        }

        $this->checkTrashedUser($params);

        if (is_null($user)) {
            $user = User::create($params);
        } else {

            if ($params['parent_id'] && $user->canAttemptParentId($params['parent_id'])) {
                throw new UserCanNotUpdatedException('Указан неверный идентификатор родительского пользователя');
            }

            $user->update($params);
            $user->refresh();
        }

        if (isset($params['roles'])) {
            $user->syncRoles($params['roles']);
        }

        return $user;
    }

    /**
     * Check trashed users and set unique fields.
     *
     * @param $params
     * @return void
     */
    public function checkTrashedUser($params): void
    {
        $uniqueField = ['login', 'email', 'phone'];

        foreach ($uniqueField as $field) {
            if (!isset($params[$field])) {
                continue;
            }

            $value = $params[$field];

            if ($user = $this->getUserByFieldAndNotNull($field, $value, true)) {
                do {
                    // Поле phone - будет очищено, если будет совпадение.
                    // К остальным в начало будет добавлен _
                    $value = $field !== 'phone'
                        ? '_' . $value
                        : null;

                } while ($this->getUserByFieldAndNotNull($field, $value));

                $user->{$field} = $value;

                $user->save();
            }
        }
    }

    private function getUserByFieldAndNotNull(string $field, mixed $value, bool $isOnlyTrashed = false): mixed
    {
        $user = $isOnlyTrashed
            ? User::onlyTrashed()
            : User::withTrashed();

        return $user->where($field, $value)->whereNotNull($field)->first();
    }

    /**
     * Delete user.
     *
     * @throws UserCanNotUpdatedException
     */
    public function delete(User $user): bool
    {
        // Дополнительная проверка, во избежании удалении собственного пользователя
        // или пользователя вне своей иерархии
        if ($this->authService->user()->can('user_delete', $user)) {
            return $user->delete();
        }

        throw new UserCanNotUpdatedException('Не удалось удалить пользователя');
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
        return $this->authService->user()->setAppends(['permissions', 'roles']);
    }
}
