<?php


namespace Wesleydeveloper\CRCPService;

use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use TwoCaptcha\Exception\ApiException;
use TwoCaptcha\Exception\NetworkException;
use TwoCaptcha\Exception\TimeoutException;
use TwoCaptcha\Exception\ValidationException;
use TwoCaptcha\TwoCaptcha;

class CRCPService
{
    private const BASE_URI = 'https://www.crcp.org.br';

    /**
     * @var Client;
     */
    private Client $client;

    /**
     * @var TwoCaptcha
     */
    private TwoCaptcha $twoCaptcha;

    /**
     * @var array
     */
    private array $params;

    /**
     * @var array
     */
    private array $result;

    /**
     * @var array
     */
    private array $keys;

    public function __construct(string $twoCaptchaKey)
    {
        $this->twoCaptcha = new TwoCaptcha($twoCaptchaKey);
        $this->client = new Client();
        $this->params = [];
        $this->result = [];
        $this->keys = [
            'certificadora',
            'nome',
            'nDoCertificado',
            'certificacao',
            'aprovacao',
            'validade'
        ];
    }

    /**
     * @param string $cpf
     * @return bool
     * @throws ApiException
     * @throws NetworkException
     * @throws TimeoutException
     * @throws ValidationException
     */
    public function check(string $cpf): bool
    {
        try{
            $this->params['Cpf'] = $cpf;
            $this->resolveCaptcha();
            $crawler = $this->client->request('POST', self::BASE_URI . '/Certificado/Consulta', $this->params);
            $this->serializeResponse($crawler);
            return count($this->result) > 0;
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getSiteKey(): string
    {
        try {
            $crawler = $this->client->request('GET', self::BASE_URI);
            $siteKey = $crawler->filter('.g-recaptcha')->attr('data-sitekey');
            if(is_null($siteKey)) throw new Exception('Site key is null');
            return $siteKey;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws ApiException
     * @throws NetworkException
     * @throws TimeoutException
     * @throws ValidationException
     */
    private function resolveCaptcha(): void
    {
        try {
            set_time_limit(610);
            $reCaptcha = $this->twoCaptcha->recaptcha([
                'sitekey' => $this->getSiteKey(),
                'url' => self::BASE_URI
            ]);
            $this->params['g-recaptcha-response'] = $reCaptcha->code;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function serializeResponse(Crawler $crawler): void
    {
        $crawler->filter('.table')->each(function ($table){
            $table->filter('tbody tr')->each(function ($tr){
                $tr->filter('td')->each(function ($td, $i){
                    $value = trim($td->text());
                    $key = !empty($this->keys[$i]) ? $this->keys[$i] : $i;
                    $this->result[$key] = !empty($value) ? $value : '';
                });
            });
        });
    }
}