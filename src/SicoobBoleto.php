<?php

namespace Michaeld555;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Michaeld555\Formater\RequestBoletoMaker;
use Michaeld555\Options\EnvironmentUrls;
use Michaeld555\Services\Boleto;
use Michaeld555\Services\WebhookBoleto;

class SicoobBoleto
{

    private string $base_url;

    private bool $isProduction;

    private string $client_id;

    private string $permissions;

    private string $token;

    private string $sandboxToken;

    private int $expires_in;

    private array $certificatePub;

    private array $certificatePriv;
    
    private RequestBoletoMaker $requestMaker;

    public function __construct(
        bool $isProduction = true,
        string $client_id,
        string $certificatePubPath,
        ?string $certificatePubPass,
        string $certificatePrivPath,
        ?string $certificatePrivPass,
        ?string $permissions = null,
        ?string $sandboxToken = null
    ) {

        if(empty($client_id)) {
            throw new Exception('Client ID é obrigatório');
        }

        if(!$isProduction && empty($sandboxToken)) {
            throw new Exception('Sandbox Token é obrigatório');
        }

        if($isProduction && empty($certificatePubPath)) {
            throw new Exception('Caminho do certificado público é obrigatório');
        }

        if($isProduction && empty($certificatePrivPath)) {
            throw new Exception('Caminho do certificado privado é obrigatório');
        }

        $this->isProduction = $isProduction;

        $this->base_url = EnvironmentUrls::auth_url;

        $this->client_id = $client_id;

        $this->permissions = !empty($permissions) ? $permissions : 'boletos_inclusao boletos_consulta boletos_alteracao webhooks_alteracao webhooks_consulta webhooks_inclusao';
        
        $this->certificatePub = [$certificatePubPath, $certificatePubPass];
        
        $this->certificatePriv = [$certificatePrivPath, $certificatePrivPass];
        
        $this->token = $isProduction ? $this->gerarToken() : $sandboxToken;
        
        $this->requestMaker = new RequestBoletoMaker($this, !$isProduction);
        
        $this->expires_in = 0;

    }

    public function gerarToken(): string | GuzzleException
    {

        if($this->isProduction && !empty($this->token)) {
            return $this->token;
        }

        $client = new \GuzzleHttp\Client();

        try {

            $response = $client->request('POST', $this->base_url, [
                'form_params' => [
                    'client_id' => $this->client_id,
                    'grant_type' => 'client_credentials',
                    'scope' => $this->permissions
                ],
                'cert' => $this->certificatePub,
                'ssl_key' => $this->certificatePriv,
            ]);

            $response = json_decode($response->getBody()->getContents());

            $this->expires_in = time() + $response->expires_in;

            $this->token = $response->access_token;

            return $this->token;

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function getToken(): string
    {

        if(empty($this->token)) {
            $this->gerarToken();
        }

        if($this->isProduction && $this->expires_in < time()) {
            $this->gerarToken();
        }

        return $this->token;
        
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getExpiresIn(): int
    {
        return $this->expires_in;
    }

    public function getPermissions(): string
    {
        return $this->permissions;
    }

    public function getBaseUrl(): string
    {
        return $this->base_url;
    }

    public function getIsProduction(): bool
    {
        return $this->isProduction;
    }

    public function getCertificatePub(): array
    {
        return $this->certificatePub;
    }

    public function getCertificatePriv(): array
    {
        return $this->certificatePriv;
    }

    public function getRequestMaker(): RequestBoletoMaker {
        return $this->requestMaker;
    }

    public function boleto(string $numeroBoleto = null, bool $debug = false): Boleto
    {
        return new Boleto($this->getRequestMaker(), $numeroBoleto);
    }

    public function webhook(string $idWebhook = null, bool $debug = false): WebhookBoleto
    {
        return new WebhookBoleto($this->getRequestMaker(), $idWebhook);
    }

}
