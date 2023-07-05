# ADS logger package

## Установка

Скачать пакет с помощью `git submodule`
```shell
git submodule add https://{login}:{password}@gitlab.com/ads-lk/logger.git packages/ads/logger
```

Добавить записи в `composer.json`,
```json
{
  "autoload": {
    "psr-4": {
      "Ads\\Logger\\": "packages/ads/logger/src/",
      "Ads\\Logger\\Seeders\\": "packages/ads/logger/database/seeders/"
    }
  }
}
```

* Необходимо добавить `Ads\Logger\Providers\LoggerServiceProvider` в `config/app.php`,
* после чего выполнить `php artisan vendor:publish --provider="Ads\Logger\Providers\LoggerServiceProvider" --tag="config"`
* после чего выполнить `php artisan migrate`

## Логирование запросов
В `app/Http/Kernel.php` `api` должен быть добавлен `Ads\Logger\Middleware\ApiLoggerMiddleware::class` 

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

Где `user.show` - название роута, так же можно указать и путь. В массиве `request` и `response` указываются поля, которые
будут игнорированы при логировании, если в этих ключах указать `false`, то ни одно поле записано не будет.
