# ADS core package

## Установка

* Необходимо добавить `Ads\Cache\Providers\CacheServiceProvider` в `config/app.php`,
* после чего выполнить `php artisan vendor:publish --provider="Ads\Core\Providers\CoreServiceProvider" --tag="config"`
* после чего выполнить `php artisan migrate`

В .env файл требуется указать перменную `ADS_CORE_DEFAULT_ADMIN_PASSWORD` - в которую требуется указать пароль для
администратора. Требуется это для первичного сидирования данных.

## Добавление Ролей и Прав доступа

В `DatabaseSeeder` в метод `run()` добавить следующую запись
```php
$this->call(Ads\Core\Seeders\PermissionSeeder::class);
$this->call(Ads\Core\Seeders\RoleSeeder::class);
$this->call(Ads\Core\Seeders\UserSeeder::class);
```

Шаблон написания имени ролей: {сущность}_{действие}, например user_create

# Пользователи и авторизация

Для начала работы потребуется подключить traits к модели Models/User.php:

```php
use Ads\Core\Traits\HasRole;
use Ads\Core\Traits\HasPassword;
use Ads\Core\Traits\HasPermission;
use Laravel\Sanctum\HasApiTokens;

use HasRole, HasPermission, HasPassword, HasApiTokens;
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

## Выполнение миграций и сидирование:

```shell
php artisan migrate:fresh --seed
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


