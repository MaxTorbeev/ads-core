# ADS cache package

## Установка

Необходимо добавить `Ads\Cache\Providers\CacheServiceProvider` в `config/app.php`,
после чего выполнить `php artisan vendor:publish`

## Кэширование значения:

```php
(new CacheService())
    ->setEntity('users.list')
    ->setParams(['filter' => ['id' => 1]])
    ->remember( fn () User::all());
```

* Метод `setEntity()` - Указывает index ключа.
* Метод `setParams()` - Указывает параметры кэшируемоего значения для достижения уникаьлности.
* Метод `setParams()` - Указывает параметры кэшируемоего значения для достижения уникаьлности.
* Метод `remember(Closure $closure, ?int $expirationTime = null)` - где `$closure` - замыкание, с возвращаемым
  значением,
  которое будет закэшировано. `$expirationTime` - срок жизни кэша.

## Очистить кэш:

```php
(new CacheService())->forget($user->login, 'users.list')
```

* Метод `forget(string $prefix = null, string $postfix = null)` служит для очистки значений из кэша. В $prefix можно
  указать логин пользователя, а в $postfix уточняющий ключ.
    * Например, если указать в $prefix только логин, то будет
      очищен весь кэш этого пользователя, если указать еще и $postfix, то будет очищен этот ключ у этого пользователя.
    * Если не указывать параметры, то будет сброшен весь кэш.

## Залпнированные задачи

Для того, что бы очищать логи в запланированных задачах, требуется указать
класс `Ads\Cache\ScheduledActions\LogsScheduleAction` в `app/Console/Kernel.php`

```php
$schedule->call(new LogsScheduleAction('clearOutdatedLogs'))->dailyAt('00:00');
```

Для определения срока хранения кэша, трубуется указать следующие .env конфиги:

* `ADS_LOGGER_SUCCESS_STORE_DAYS` - срок хранения запросов, при обработки которых, не возникло ошибок. По умолчанию 14
* `ADS_LOGGER_ERROR_STORE_DAYS` - срок хранения запросов, при обработки которых, возникли ошибки. По умолчанию 14

## Исключение из логирование

В конфиг `config/ads-logger.php`, в ключ `except` необходимо добавить:

```php
'user.show' => [
    'request' => [
        'password',
    ],
    'response' => [
        'password',
    ],
]
```

Где `user.show` - название роута, так же можно указать и путь. В массие `request` и `response` указываются поля, которые
будут игнорированы при логировании, если в этих ключах указать `false`, то ни одно поле записано не будет.
