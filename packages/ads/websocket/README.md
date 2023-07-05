# ADS websockets package

## Установка

Скачать пакет с помощью `git submodule`
```shell
git submodule add https://{login}:{password}@gitlab.com/ads-lk/websocket.git packages/ads/websocket
```

Добавить записи в `composer.json`,
```json
{
  "autoload": {
    "psr-4": {
      "Ads\\Websockets\\": "packages/ads/websocket/src/",
      "Ads\\Websockets\\Seeders\\": "packages/ads/websocket/database/seeders/"
    }
  }
}
```

* Установить пакет `composer require beyondcode/laravel-websockets`
* Необходимо добавить `Ads\Websockets\Providers\WebsocketServiceProvider` в `config/app.php`,
* после чего выполнить `php artisan vendor:publish --provider="Ads\Websockets\Providers\WebSocketServiceProvider" --tag="config"`
* после чего выполнить `php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"`
* после чего выполнить `php artisan migrate`

## Настройки supervisor

В директории `/etc/supervisor/conf.d/` создать новый текстовый файл `websockets.conf` и добавить соедующие строки:
```
[program:websockets]
command=php /var/www/html/artisan websockets:serve
numprocs=1
autostart=true
autorestart=true
user=www-data
stdout_logfile=/var/log/supervisor/websockets_info.log
stdout_logfile_maxbytes=10MB
stderr_logfile=/var/log/supervisor/websockets_error.log
stderr_logfile_maxbytes=10MB
```

В .env добавить настройки
* `VUE_APP_LARAVEL_WEBSOCKETS_PORT` - Порт для сокетов. По умолчанию 6001
* `VUE_APP_LARAVEL_WEBSOCKETS_ENABLE_STATISTICS` - Включить/выключить статистику сокетов. По умолчанию true
* `LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT` - Путь к SSL сертификату
* `LARAVEL_WEBSOCKETS_SSL_LOCAL_PK` - Путь к SSL ключу

[Больше информации о веб сокетах](https://beyondco.de/docs/laravel-websockets/getting-started/introduction)
