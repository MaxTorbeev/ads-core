# ADS wsdl client package

## Установка

Скачать пакет с помощью `git submodule`

```shell
git submodule add https://{login}:{password}@gitlab.com/ads-lk/wsld.git packages/ads/wsld
```

Добавить записи в `composer.json`,

```json
{
  "autoload": {
    "psr-4": {
      "Ads\\WsdlClient\\": "packages/ads/wsld/src/",
      "Ads\\WsdlClient\\Seeders\\": "packages/ads/wsld/database/seeders/"
    }
  }
}
```

Необходимо добавить `Ads\WsdlClient\Providers\WsdlClientServiceProvider` в `config/app.php`,
после чего выполнить `php artisan migrate`

Добавить в App\Models\User.php трэйт Ads\WsdlClient\Traits\HasUserWs

## Использование клиента

```php
    use Ads\WsdlClient\Services\Clients\WsdlClient;
    
    public function index(WsdlClient $client): array|stdClass
    {
        $userWs = auth()->user()->userWs
    
        $tickets = $client
            ->setUser($userWs)
            ->setWsdl('itil.1cws')
            ->request('getListTicket', [
                'Email' => 'admin@example.com'
            ]);
            
        return $tickets;
    }
```

* Метод `setUser(UserWs $userWs)` - Указывает модель UserWs пользователя, в которой должны находиться основные
  настройки для соединения с WSDL по протоколу SOAP. Если не указывать setUser, то пользователь WSDL будет взят от
  авторизированного пользователя.
* Метод `setWsdl(string $wsdl)` - Строковое название объекта, к которому требуется обратиться
* Метод `request(string $method, array $params = [])` - Метод отправки запроса. В значении `$method` указывать строковое
  название метода SOAP, в значении `$params` - параметры запроса.
