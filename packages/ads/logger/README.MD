# ADS logger package

* Необходимо добавить `Ads\Logger\Providers\LoggerServiceProvider` в `config/app.php`,
* после чего выполнить `php artisan vendor:publish`
* после чего выполнить `php artisan migrate`

## Логирование запросов
В `middleware` должен быть добавлен `Route::middleware([Ads\Logger\Middleware\ApiLoggerMiddleware;::class])` 

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
