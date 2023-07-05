<?php

namespace Ads\Cache\Services\Cache;

use Ads\Cache\Services\Mutex\MutexService;
use Ads\Core\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CacheService
{
    private ?User $user = null;

    private string|null $prefix = null;

    private string $entity;

    private ?array $params = null;

    public static function fromRequest(Request $request): self
    {
        $cache = new self();

        if ($request->has('user')) {
            $cache = $cache->setUser($request->get('user'));
        }

        if ($request->has('entity')) {
            $cache = $cache->setEntity($request->get('entity'));
        }

        if ($request->has('prefix')) {
            $cache = $cache->setPrefix($request->get('prefix'));
        }

        return $cache;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setUser(int|User|Authenticatable $user): self
    {
        if (is_int($user)) {
            $this->user = User::withTrashed()->where('id', $user)->first() ?? new UserNotFoundException(403);
        } else {
            $this->user = $user;
        }

        return $this;
    }

    public function setParams(mixed $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function remember(Closure $closure, ?int $expirationTime = 604800): mixed
    {
        (new MutexService($this->getCacheName()))
            ->lock(
                fn() => !$this->check(),
                fn() => cache()->remember($this->getCacheName(), $expirationTime, $closure)
            );

        return $this->get();
    }

    public function forget(): bool
    {
        $query = DB::table('cache');

        foreach ($this->cacheKeyMap() as $item) {
            $query = $query->where('key', 'LIKE', $item);
        }

        return $query->delete();
    }

    public function check(): bool
    {
        return cache()->has($this->getCacheName());
    }

    public function get(): mixed
    {
        return cache()->get($this->getCacheName());
    }

    public function set($data, ?int $expirationTime = null): bool
    {
        return cache()->set($this->getCacheName(), $data, $expirationTime);
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
     * Build cache keys map.
     *
     * @return array
     */
    private function cacheKeyMap(): array
    {
        $params = $this->params ? md5(json_encode($this->params)) : null;

        $userKey = ($this->user?->email || $this->user?->phone || $this->user?->login)
            ? md5($this->user?->email . $this->user?->phone . $this->user?->login)
            : null;

        return array_filter([$this->prefix, $userKey, $this->entity, $params], fn($item) => boolval($item));
    }

    /**
     * Get cache name
     *
     * @return string
     */
    private function getCacheName(): string
    {
        return implode('.', $this->cacheKeyMap());
    }
}
