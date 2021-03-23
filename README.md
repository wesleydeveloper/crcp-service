# CRCP Service
Serviço de consulta CRCP - Central de Registros de Certificados Profissionais


## Instalação
`composer require wesleydeveloper/crcp-service`
## Uso

Necessita de uma chave da API [2Captcha](https://2captcha.com)
```php
<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require __DIR__ . './vendor/autoload.php';

use Wesleydeveloper\CRCPService\CRCPService;

$twoCaptchaKey = ''; // API KEY https://2captcha.com
$crcpService = new CRCPService($twoCaptchaKey);

$cpf = ''; // CPF para pesquisa

try{
    $checkCpf = $crcpService->check($cpf);
}catch (\Exception $e){
    die($e->getMessage());
}

var_dump($checkCpf, $crcpService->getResult());
```

["2Captcha"]: https://2captcha.com