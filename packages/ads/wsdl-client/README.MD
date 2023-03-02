# ADS wsdl client package

Использование клиента

```php
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
