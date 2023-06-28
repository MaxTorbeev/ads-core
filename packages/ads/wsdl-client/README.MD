# ADS wsdl client package

## Установка

Добавить в composer.json:
```json
{
  "require": {
    "ads/wsdl": "dev-master"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://{login}:{password}@gitlab.com/ads-lk/wsdl.git"
    }
  ]
}
```

Необходимо добавить `Ads\WsdlClient\Providers\WsdlClientServiceProvider` в `config/app.php`,
после чего выполнить `php artisan migrate`

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

* Метод `setUser(UserWs $userWs)` - Указывает модель UserWs пользователя, в которой должны находиться основные настройки
  для
  соединения с WSDL по протоколу SOAP.
* Метод `setWsdl(string $wsdl)` - Строковое название объекта, к которому требуется обратиться
* Метод `request(string $method, array $params = [])` - Метод отправки запроса. В значении `$method` указывать строковое
  название метода SOAP, в значении `$params` - параметры запроса.
