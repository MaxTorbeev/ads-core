# ADS wsdl client package

## Установка

Необходимо добавить `Ads\WsdClient\Providers\WsdlClientServiceProvider` в `config/app.php`,
после чего выполнить `php artisan migrate`

## Использование клиента

```php
    use Ads\WsdClient\Services\Clients\WsdlClient;
    
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
