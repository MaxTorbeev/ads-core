# ADS cache package

## Установка

Скачать пакет с помощью `git submodule`
```shell
git submodule add https://{login}:{password}@gitlab.com/ads-lk/cache.git packages/ads/cache
```

Добавить записи в `composer.json`,
```json
{
  "autoload": {
    "psr-4": {
      "Ads\\Cache\\": "packages/ads/cache/src/",
      "Ads\\Cache\\Seeders\\": "packages/ads/cache/database/seeders/"
    }
  }
}
```


* Выполнить `composer require malkusch/lock`
* Выполнить `composer du`
* Необходимо добавить `Ads\Cache\Providers\CacheServiceProvider` в `config/app.php`,
* После чего выполнить `php artisan vendor:publish --provider="Ads\Cache\Providers\CacheServiceProvider" --tag="config"`
* После чего выполнить `php artisan migrate`
* Добавить кэш драйвер `CACHE_DRIVER=database`

В `database/seeders/DatabaseSeeder.php`, в метод `run()` добавить:
```php
$this->call(CachePermissionSeeder::class);
$this->call(CacheRoleSeeder::class);
```

Выполнить `php artisan db:seed`

## Кэширование значения:

```php
(new CacheService())
    ->setPrefix($userWs->login)
    ->setUser($user)
    ->setEntity('users.list')
    ->setParams(['filter' => ['id' => 1]])
    ->remember( fn () => User::all());
```

* Метод `setPrefix(string $prefix)` - Добавление префикса к ключу кэша, к примеру там может храниться логин userWs 
* Метод `setUser(User $user)` - Метод указывает к какому пользователю будет привязано кэшированное значение
* Метод `setEntity(string $entity)` - Указывает index ключа.
* Метод `setParams()` - Указывает параметры кэшируемоего значения для достижения уникальности.
* Метод `remember(Closure $closure, ?int $expirationTime = null)` - где `$closure` - замыкание, с возвращаемым
  значением, которое будет закэшировано. `$expirationTime` - срок жизни кэша.


## Очистить кэш:

### С помощью метода api
POST:`/api/cache/flush` - Требуется роль с методом `cache_clear`

### Программным способом:

```php
(new CacheService())
    ->setPrefix($userWs->login)
    ->setUser($user)
    ->setEntity('users.list')
    ->setParams(['filter' => ['id' => 1]])->forget($user->login, 'users.list')
    ->forget();
```
* Метод `setPrefix(string $prefix)` - Добавление префикса к ключу кэша, к примеру там может храниться логин userWs
* Метод `setUser(User $user)` - Метод указывает к какому пользователю будет привязано кэшированное значение
* Метод `setEntity(string $entity)` - Указывает index ключа.
* Метод `setParams()` - Указывает параметры кэшируемоего значения для достижения уникальности.
* Метод `forget()` служит для очистки значений из кэша.

## Получить значение из кэша

```php
(new CacheService())
    ->setPrefix($userWs->login)
    ->setUser($user)
    ->setEntity('users.list')
    ->setParams(['filter' => ['id' => 1]])->forget($user->login, 'users.list')
    ->get();
```
