# CRCP Service
Serviço de consulta CRCP - Central de Registros de Certificados Profissionais


## Instalação
`composer require wesleydeveloper/crcp-service`
## Uso

Necessita de uma chave da API [2Captcha](https://2captcha.com)
```php
<?php

require __DIR__ . './vendor/autoload.php';

use Wesleydeveloper\CRCPService\CRCPService;

$twoCaptchaKey = ''; // API KEY https://2captcha.com
$crcpService = new CRCPService($twoCaptchaKey);

$cpf = ''; // CPF para pesquisa

try{
    $crcpService->check($cpf);
}catch (\Exception $e){
    die($e->getMessage());
}
/*
 $crcpService->check($cpf) retorna um beloano
 $crcpService->getResult() retorna um array
*/
```

["2Captcha"]: https://2captcha.com