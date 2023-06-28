<?php

namespace Ads\Cache\Services;

use Closure;
use Ads\WsdlClient\Models\UserWs;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CacheService
{
    private User $user;

    private UserWs $userWs;

    private string $entity;

    private array $tags = [];

    private ?array $params = null;

    public function setUser(User|Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setParams(mixed $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function setUserWs(User|Authenticatable|UserWs $user): self
    {
        if ($user instanceof UserWs) {
            $this->userWs = $user;
        } else {
            $this->userWs = $user->userWs;
        }

        return $this;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function remember(Closure $closure, ?int $expirationTime = null)
    {
        if (!empty($this->tags)) {
            return \cache()
                ->tags($this->tags)
                ->{$this->rememberMethod($expirationTime)}($this->getCacheName(), $expirationTime, $closure);
        }

        return \cache()->{$this->rememberMethod($expirationTime)}($this->getCacheName(), $closure);
    }

    public function forget(string $prefix = null, string $postfix = null): bool
    {
        $result = false;

        $query = DB::table('cache');

        if ($prefix) {
            $query = $query->where('key', 'LIKE', "%$prefix%");
        }

        if ($postfix) {
            $query = $query->where('key', 'LIKE', "%$postfix%");
        }

        return $query->delete() || $result;
    }

    public function flush(): bool
    {
        try {
            Artisan::call('cache:clear');

            return true;
        } catch (\Exception $exception) {
            abort(403, 'Не удалось очистить кэш.');
        }
    }

    /**
     * Get remember method. Remember or RememberForever.
     *
     * @param $expirationTime
     * @return string
     */
    private function rememberMethod($expirationTime): string
    {
        return $expirationTime
            ? 'remember'
            : 'rememberForever';
    }

    /**
     * Get cache name
     *
     * @return string
     */
    private function getCacheName(): string
    {
        $userid = $this->user?->email ?? $this->userWs?->login ?? null;

        return $userid
            ? 'user.' . $userid . '.' . $this->entity . ($this->params ? '.' . md5(json_encode($this->params)) : '')
            : $this->entity . ($this->params ? '.' . md5(json_encode($this->params)) : '');
    }
}
