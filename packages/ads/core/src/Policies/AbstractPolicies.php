<?php

namespace Ads\Core\Policies;

use Illuminate\Contracts\Auth\Access\Gate;

abstract class AbstractPolicies
{
    public function authorize($ability, $arguments) {
//        return app(Gate::class)->authorize($ability, $arguments);
    }
}
