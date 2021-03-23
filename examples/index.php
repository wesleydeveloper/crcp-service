<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Wesleydeveloper\CRCPService\CRCPService;

$twoCaptchaKey = ''; // API KEY https://2captcha.com
$crcpService = new CRCPService('d44a1bd74d7519fe844d14cbfd2472be');

$cpf = ''; // CPF para pesquisa

try{
    $checkCpf = $crcpService->check('060.361.294-67');
}catch (\Exception $e){
    die($e->getMessage());
}

var_dump($checkCpf, $crcpService->getResult());
