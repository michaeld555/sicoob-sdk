<?php

namespace Michaeld555\Services;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Michaeld555\Formater\RequestBoletoMaker;
use stdClass;

class Boleto
{

    private RequestBoletoMaker $req;

    private ?string $numeroBoleto;

    public function __construct(RequestBoletoMaker $req, ?string $numeroBoleto = null)
    {

        $this->req = $req;
        $this->numeroBoleto = $numeroBoleto;

    }

    public function incluir(array $body): string | GuzzleException | array | stdClass
    {

        try {

            return $this->req->requisicao('/boletos', 'POST', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function consultar(array $options): string | GuzzleException | array | stdClass
    {

        $query = "?".http_build_query($options);

        try {

            return $this->req->requisicao("/boletos{$query}", 'GET');

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function segundaVia(array $options): string | GuzzleException | array | stdClass
    {

        $query = "?".http_build_query($options);

        try {

            return $this->req->requisicao("/boletos/segunda-via{$query}", 'GET');

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function atualizar(array $body): string | GuzzleException | array | stdClass | null
    {

        if(empty($this->numeroBoleto)) {
            throw new Exception('O numero do boleto é obrigatório para atualizar');
        }

        try {

            return $this->req->requisicao("/boletos/{$this->numeroBoleto}", 'PATCH', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

    public function baixar(array $body): string | GuzzleException | array | stdClass
    {

        if(empty($this->numeroBoleto)) {
            throw new Exception('O numero do boleto é obrigatório para marcar a baixa');
        }


        try {

            return $this->req->requisicao("/boletos/{$this->numeroBoleto}/baixar", 'POST', $body);

        } catch (GuzzleException $e) {

            return $e;

        }

    }

}
