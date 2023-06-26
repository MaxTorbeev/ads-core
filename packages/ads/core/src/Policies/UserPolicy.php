<?php

namespace Ads\Core\Policies;

use App\Models\User;

class UserPolicy extends AbstractPolicies
{
    public function view(User $user): bool
    {
        return $this->authorize('', $user);
    }
}
