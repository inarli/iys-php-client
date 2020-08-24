## PHP Client for (İleti Yönetim Sistemi) iys.org.tr

### Example:

```php
$apiUri = 'https://api.sandbox.iys.org.tr';
$iysBrandCode = 'YOUR_BRAND_CODE';
$iysCode = 'YOUR_IYS_CODE';

// test account
$email = 'YOUR_MAIL';
$password = 'YOUR_PASSWORD';

$iys = new Iys\IysApi($apiUri,$iysCode,$iysBrandCode);

// login to iys system
$login = $iys->authentication->login($email, $password);
if ($login instanceof \Iys\Auth\Response\Token){
    $iys->setToken($login->getAccessToken());
    $iys->setRefreshToken($login->getRefreshToken());
}

// create a consent
$phoneNumber = '+905050000000';
$email = 'dummy@email.com';
$date = date('Y-m-d H:i:s');
$consentModel = $iys->consentManagement->generateConsent($email, Iys\ConsentManagement\Enum\ConsentType::EMAIL, Iys\ConsentManagement\Enum\ConsentSource::WEB, Iys\ConsentManagement\Enum\ConsentStatus::APPROVE, $date, Iys\ConsentManagement\Enum\RecipientType::INDIVIDUAL);
$result = $iys->consentManagement->createSingleConsent($consentModel);

// All request returns a HttpResponse object.
// Typically response looks like this

Iys\HttpResponse {#37
     -statusCode: 200
     -errors: null
     -errorCode: null
     -message: null
     -data: array:2 [
       "transactionId" => "e9815a44f852aec77648c0f5e2eec67fda0fdb23b2ff1de72be1a4e80ce04f14"
       "creationDate" => "2020-08-24 11:46:44"
     ]
   }
```
### Covered services
- [x] Authentication Service
- [x] Consent Management Service

## Todos
- [ ] Tests
- [ ] Brand Management Service
- [ ] Retailer Management Service
- [ ] Retailer Management Service
- [ ] Agreement Management Service
- [ ] Info Services


