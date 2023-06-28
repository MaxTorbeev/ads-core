# ADS cache package

## Установка

```json
{
  "require": {
    "ads/cache": "dev-master"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://{login}:{password}@gitlab.com/ads-lk/cache.git"
    }
  ]
}
```

* Необходимо добавить `Ads\Cache\Providers\CacheServiceProvider` в `config/app.php`,
* после чего выполнить `php artisan vendor:publish --provider="Ads\Cache\Providers\CacheServiceProvider" --tag="config"`
* после чего выполнить `php artisan migrate`

## Кэширование значения:

```php
(new CacheService())
    ->setEntity('users.list')
    ->setParams(['filter' => ['id' => 1]])
    ->remember( fn () User::all());
```

* Метод `setEntity()` - Указывает index ключа.
* Метод `setParams()` - Указывает параметры кэшируемоего значения для достижения уникальности.
* Метод `remember(Closure $closure, ?int $expirationTime = null)` - где `$closure` - замыкание, с возвращаемым
  значением,
  которое будет закэшировано. `$expirationTime` - срок жизни кэша.

## Очистить кэш:

### С помощью метода api
POST:`/api/cache/flush` - Требуется роль с методом `cache_clear`

### Программным способом:

```php
(new CacheService())->forget($user->login, 'users.list')
```

* Метод `forget(string $prefix = null, string $postfix = null)` служит для очистки значений из кэша. В $prefix можно
  указать логин пользователя, а в $postfix уточняющий ключ.
    * Например, если указать в $prefix только логин, то будет
      очищен весь кэш этого пользователя, если указать еще и $postfix, то будет очищен этот ключ у этого пользователя.
    * Если не указывать параметры, то будет сброшен весь кэш.
