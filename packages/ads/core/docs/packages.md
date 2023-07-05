# Пакетная система проекта

Вся кодовая база хранится в папка packages/ads, делется на логические модули, ядро (core), кэширование (cache) и так далее.
Структурно, директории пакета должны повторять директории, за исключением директории `app`, она будет называться `src`. 
Пример:
```
├── src
│   ├── Http
│   │   ├── Controller
│   │   │  ├── AuthController.php
│   │   ├── Requests
│   │   │   ├── AuthRequest.php
│   ├── Providers
│   │   ├── PackageServiceProvider.php
│   ├── Models
│   ├── Services
│   ├── Exceptions
│   ├── Supports
│   ├── Traits
├── database
├── configs
├── resources
└── .gitignore
└── README.md
```

Для добавления пакета в, плотребуется указать его путь в `composer.json` в:

```json
 {
  "autoload": {
    "psr-4": {
      "Ads\\Core\\": "packages/ads/core/src/",
      "Ads\\Core\\Database\\Seeders": "packages/ads/core/database/seeders"
    }
  }
}
```

После добавления записи в `composer.json` выполнить `composer du`

## Добавление миграций

Миграции следуюет добавлять с помощью команды:

```shell
php artisan make:migration create_any_table --path=packages/ads/{package_name}/database/migrations
```

## Поддерживаемы проекты
* [Кэширование](https://gitlab.com/ads-lk/cache)
* [Логирование](https://gitlab.com/ads-lk/logging)
* [WSDL клиент](https://gitlab.com/ads-lk/wsdl)
* [Вебсокеты](https://gitlab.com/ads-lk/websocket)
