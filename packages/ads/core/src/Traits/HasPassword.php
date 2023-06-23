<?php

namespace Ads\Core\Traits;

use Illuminate\Support\Facades\Hash;

trait HasPassword
{
    /**
     * Check password validity.
     *
     * @param string $password
     * @return bool
     */
    public function passwordIsValid(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
