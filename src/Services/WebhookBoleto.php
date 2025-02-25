<?php

namespace Michaeld555\Services;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Michaeld555\Formater\RequestBoletoMaker;
use stdClass;

class WebhookBoleto
{

    private RequestBoletoMaker $req;

    private ?string $idWebhook;

    public function __construct(RequestBoletoMaker $req, ?string $idWebhook = null)
    {

        $this->req = $req;
        $this->idWebhook = $idWebhook;

    }

    public function criar(array $body): string | GuzzleException | array | stdClass
    {

        try {

            return $this->req->requisicao('/webhooks', 'POST', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function consultar(): string | GuzzleException | array | stdClass
    {

        try {

            return $this->req->requisicao("/webhooks", 'GET');

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function atualizar(array $body): string | GuzzleException | array | stdClass | null
    {

        if(empty($this->idWebhook)) {
            throw new Exception('O id do webhook é obrigatório para atualizar');
        }

        try {

            return $this->req->requisicao("/webhooks/{$this->idWebhook}", 'PATCH', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function deletar(): string | GuzzleException | array | stdClass | null
    {

        if(empty($this->idWebhook)) {
            throw new Exception('O id do webhook é obrigatório para deletar');
        }

        try {
            
            return $this->req->requisicao("/webhooks/{$this->idWebhook}", 'DELETE');
            
        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function reativar(array $body): string | GuzzleException | array | stdClass | null
    {

        if(empty($this->idWebhook)) {
            throw new Exception('O id do webhook é obrigatório para reativar');
        }

        try {

            return $this->req->requisicao("/webhooks/{$this->idWebhook}/reativar", 'PATCH', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function solicitacoes(?array $options = []): string | GuzzleException | array | stdClass
    {

        if(empty($this->idWebhook)) {
            throw new Exception('O id do webhook é obrigatório para consultar as solicitacoes');
        }

        $query = "?".http_build_query($options);

        try {

            return $this->req->requisicao("/webhooks/{$this->idWebhook}/solicitacoes{$query}", 'GET');

        } catch (GuzzleException $e) {

            return $e;

        }

    }

}
