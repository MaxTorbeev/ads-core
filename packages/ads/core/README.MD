# ADS core package

## Установка

Если установка ведется через composer, то в composer.json:
```json
{
  "require": {
    "ads/core": "dev-master"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://{login}:{password}@gitlab.com/ads-lk/core.git"
    }
  ]
}
```

Если пакет требуется хранить с помощью контроля версий непосредственно в проекта, 
то клонировать из репозитория `https://{login}:{password}@gitlab.com/ads-lk/core.git` исходный код проекта в папку
packages/ads/core и добавить в autoload следующие записи:
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

* Необходимо добавить `Ads\Cache\Providers\CacheServiceProvider` в `config/app.php`,
* после чего выполнить `php artisan vendor:publish --provider="Ads\Core\Providers\CoreServiceProvider" --tag="config"`
* после чего выполнить `php artisan migrate`

В .env файл требуется указать перменную `ADS_CORE_DEFAULT_ADMIN_PASSWORD` - в которую требуется указать пароль для
администратора. Требуется это для первичного сидирования данных.

## Добавление Ролей и Прав доступа

Необходимо создать сиды c помощью команды php artisan 
```shell
php artisan make:seeder PermissionSeeder && php artisan make:seeder RoleSeeder &&  php artisan make:seeder UserSeeder
```
В `DatabaseSeeder` в метод `run()` добавить следующую запись
```php
$this->call(PermissionSeeder::class);
$this->call(RoleSeeder::class);
$this->call(UserSeeder::class);
```

* `PermissionSeeder`, наследуется от `Ads\Core\Seeders\PermissionSeeder`
  - Переопределив метод getDefault(), появляется возможность изменить или добавить новые права доступа
* `RoleSeeder`, наследуется от `Ads\Core\Seeders\RoleSeeder`
- Переопределив метод getDefault(), появляется возможность изменить или добавить роли
* `UserSeeder`, наследуется от `Ads\Core\Seeders\UserSeeder`
  - Переопределив метод getDefault(), появляется возможность изменить или добавить пользователей

## Пример переопределения getDefault()

```php
class UserSeeder extends CoreUserSeeder
{
    public function getDefault(): array
    {
        $users = parent::getDefault();

        $users[] = [
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'phone' => '277',
            'login' => 'editor',
            'password' => bcrypt(config('core.admin.password')),
            'roles' => [
                'editor'
            ]
        ];

        return $users;
    }
```

Шаблон написания имени ролей: {сущность}_{действие}, например user_create

Если переопределение метода `run()` не требуется, то его требуется удалить из финального класса.

## Выполнение миграций:

```shell
php artisan migrate:fresh --seed
```

# Пользователи и авторизация

Для начала работы потребуется подключить traits к модели Models/User.php:

```php
use Ads\Core\Traits\HasRole;
use Ads\Core\Traits\HasPassword;
use Ads\Core\Traits\HasPermission;
use Laravel\Sanctum\HasApiTokens;

use HasFactory, Notifiable, HasRole, HasPermission, HasPassword, HasApiTokens;
```

Добавить поля `name, phone, login, parent_id` в свойство `$fillable` в модели User.php
```php
protected $fillable = [
    'name',
    'phone',
    'login',
    'email',
    'password',
    'parent_id',
];
```

В `App\Providers\AuthServiceProvider` добавить:
```php
protected $policies = [
    User::class => Ads\Core\Policies\UserPolicy::class,
];
```

## Авторизация
* POST:`/api/core/auth/login` - Метод авторизации пользователя
  * Обязательно одно из трех полей: `email`, `login`, `phone`, также необходим `password`
* POST:`/api/core/auth/logout` - Метод logout

## CRUD пользовател
* GET:`/api/core/users` - Список всех пользователей
* POST:`/api/core/users` - Создание нового пользователя, доступные поля:
  * email - Электронная почта пользователя
  * phone - Номер телефона пользователя
  * login - Логин пользвателя
  * name - Имя пользователя
  * password - Пароль
  * password_confirmation - подтверждение пароля
* PATCH:`/api/core/users/:ID` - Редактирование пользователя, доступные поля:
  * email - Электронная почта пользователя
  * phone - Номер телефона пользователя
  * login - Логин пользвателя
  * name - Имя пользователя
  * password - Пароль
  * password_confirmation - подтверждение пароля
* DELETE:`/api/core/users/:ID` - Удаление пользователя


