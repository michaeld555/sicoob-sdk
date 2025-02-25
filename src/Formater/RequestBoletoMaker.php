<?php

namespace Michaeld555\Formater;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Michaeld555\Options\EnvironmentUrls;
use Michaeld555\SicoobBoleto;
use stdClass;


class RequestBoletoMaker
{

    private bool $debug;

    private bool $sandbox;

    private string $base_url;

    private SicoobBoleto $sicoob;

    private array $certificatePub;

    private array $certificatePriv;

    public function __construct(SicoobBoleto $sicoob, bool $debug = false)
    {

        if($sicoob->getIsProduction()) {

            if(empty($sicoob->getCertificatePub())) {
                throw new Exception('Caminho do certificado público é obrigatório');
            }

            if(empty($sicoob->getCertificatePriv())) {
                throw new Exception('Caminho do certificado privado é obrigatório');
            }

        }

        $this->sicoob = $sicoob;

        $this->base_url = !$sicoob->getIsProduction() ? EnvironmentUrls::sandbox_boleto_url : EnvironmentUrls::production_boleto_url;

        $this->debug = $debug;

        $this->sandbox = !$sicoob->getIsProduction();

        $this->certificatePub = $sicoob->getCertificatePub();

        $this->certificatePriv = $sicoob->getCertificatePriv();

    }

    public function requisicao(string $uri, string $metodo, ?array $corpo = null): string | GuzzleException | array | stdClass | null
    {

        try {

            $client = new \GuzzleHttp\Client();

            $response = $client->request($metodo, $this->base_url . $uri, [
                'debug' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->sicoob->getToken(),
                    'client_id' => $this->sicoob->getClientId(),
                    'Content-Type' => 'application/json'
                ],
                'json' => $corpo,
                'cert' => $this->certificatePub,
                'ssl_key' => $this->certificatePriv
            ]);

            return json_decode($response->getBody()->getContents());

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            if($e->hasResponse()) {
                
                $res = json_decode($e->getResponse()->getBody()->getContents());

                if(!empty($res->detail)) return [
                    'message' => $res->detail,
                    'violacoes' => isset($res->violacoes) ? $res->violacoes : null
                ];

            }

            return $e->getMessage();

        } catch (\Exception $e) {

            return $e;

        }

    }

}
