<?php

namespace Ads\Cache\Services\Mutex;

use malkusch\lock\exception\LockAcquireException;
use malkusch\lock\exception\LockReleaseException;
use malkusch\lock\mutex\FlockMutex;
use Closure;
use malkusch\lock\mutex\Mutex;

class MutexService
{
    /**
     * @var FlockMutex|Mutex instance;
     */
    protected FlockMutex|Mutex $mutex;

    /**
     * Mutex constructor.
     *
     * @param string $name  Уникальное название.
     */
    public function __construct(string $name = 'unique')
    {
        $this->mutex = new FlockMutex(fopen(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name, 'a'));
    }

    public function lock(Closure $check, Closure $then)
    {
        try {
            return $this
                ->mutex
                ->check($check)
                ->then($then);
        } catch (LockAcquireException|LockReleaseException $e) {
            return null;
        }
    }
}
