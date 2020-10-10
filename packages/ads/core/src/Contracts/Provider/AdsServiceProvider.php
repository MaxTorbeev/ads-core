<?php

namespace Ads\Core\Contracts\Provider;

interface AdsServiceProvider
{
    /**
     * Defining Observers
     */
    public function observers(): void;

    /**
     * Booting package resources.
     */
    public function initialization(): void;
}
