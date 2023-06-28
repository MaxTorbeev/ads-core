# ADS websockets package

Добавить в composer.json:
```json
"require": {
    "ads/websockets": "dev-master"
},
"repositories": [
    {
        "type":"git",
        "url": "https://{login}:{password}@gitlab.com/ads-lk/websocket.git"
    }
]
```

* Необходимо добавить `Ads\Websockets\Providers\WebSocketServiceProvider` в `config/app.php`,
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

[Больше информации о веб сокетах](https://beyondco.de/docs/laravel-websockets/getting-started/introduction)
