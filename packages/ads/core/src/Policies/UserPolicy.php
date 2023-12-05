<?php

namespace Ads\Core\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function user_create(User $user, ?User $child = null): bool
    {
        if ($user->hasPermission('user_create')) {
            return true;
        }

        return $this->hierarchyPermission($user, $child, true);
    }

    public function user_show(User $user, ?User $child = null): bool
    {
        if ($user->hasPermission('user_show')) {
            return true;
        }

        return $this->hierarchyPermission($user, $child, true);
    }

    public function user_delete(User $user, User $child): bool
    {
        if ($user->hasPermission('user_delete')) {
            return $user->id !== $child->id;
        }

        return $this->hierarchyPermission($user, $child);
    }

    /**
     * Check access by user_hierarchy permission.
     *
     * @param User $user
     * @param User|null $child
     * @param bool $withSelf - если true, то в проверке будет учавствовать id авторизованного пользователя.
     * @return bool
     */
    private function hierarchyPermission(User $user, ?User $child, bool $withSelf = false): bool
    {
        // проверяем, может ли пользователь создавать подчиненных
        if (!$child) {
            return $user->hasPermission('user_hierarchy');
        }

        // проверяем, является ли переданный пользователь в подчинении у основного
        if ($user->hasPermission('user_hierarchy')) {
            return $child->parentIds($withSelf)->contains($user->id);
        }

        return false;
    }
}
