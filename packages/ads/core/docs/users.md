# Пользователи и авторизация

Для начала работы потребуется подключить traits к модели Models/User.php:
Ads\Core\Traits\HasRole;
Ads\Core\Traits\HasPassword;
Ads\Core\Traits\HasPermission;
Laravel\Sanctum\HasApiTokens;

