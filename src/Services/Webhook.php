<?php

namespace Michaeld555\Services;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Michaeld555\Formater\RequestMaker;
use Michaeld555\Helpers\TxidGenerator;
use Michaeld555\Validators\WebhookValidator;
use stdClass;

class Webhook
{

    private RequestMaker $req;

    private ?string $chave;

    public function __construct(RequestMaker $req, ?string $chave = null)
    {

        if(empty($chave)){
            $chave = TxidGenerator::generate();
        }

        $this->req = $req;
        $this->chave = $chave;

    }


    public function criar(array $body): string | GuzzleException | array | stdClass | null
    {

        $webhookValidator = new WebhookValidator($body);

        $webhookValidator->validatePut();

        try {

            return $this->req->requisicao("/webhook/{$this->chave}", 'PUT', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function atualizar (array $body): string | GuzzleException | array | stdClass | null
    {

        if(empty($this->chave)) {
            throw new Exception('A chave é obrigatória para atualizar webhook');
        }

        $webhookValidator = new WebhookValidator($body);

        $webhookValidator->validatePut();

        try {

            return $this->req->requisicao("/webhook/{$this->chave}", 'PUT', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function consultar(?array $options = []): string | GuzzleException | array | stdClass
    {

        $query = "?".http_build_query($options);

        if(!empty($this->txid)){
            $query = "/{$this->chave}";
        }

        try {

            return $this->req->requisicao("/webhook{$query}", 'GET');

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function deletar(): string | GuzzleException | array | stdClass | null
    {

        if(empty($this->chave)) {
            throw new Exception('A chave é obrigatória para deletar webhook');
        }

        try {
            
            return $this->req->requisicao("/webhook/{$this->chave}", 'DELETE');
            
        } catch (GuzzleException $e) {

            return $e;

        }

    }
}
