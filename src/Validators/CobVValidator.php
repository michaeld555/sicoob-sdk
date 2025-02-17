<?php

namespace Michaeld555\Validators;

use Exception;
use Valitron\Validator;

class CobVValidator
{

    private array $body;

    private string $moneyFormat = '/^\d{1,10}\.\d{2}$/';

    public function __construct(array $body)
    {
        $this->body = $body;
    }

    public function validatePut()
    {

        $v = (new Validator($this->body));
        $v->rule('required', ['calendario', 'devedor', 'valor']);
        $v->rule('array', ['calendario', 'devedor', 'valor']);
        $v->rule('required', ['calendario.dataDeVencimento', 'calendario.validadeAposVencimento', 'devedor.nome', 'valor.original', 'chave']);
        $v->rule('numeric', 'valor.original');
        $v->rule('regex', 'valor.original', $this->moneyFormat);
        $v->rule('regex', 'valor.multa.valorPerc', $this->moneyFormat);
        $v->rule('regex', 'valor.juros.valorPerc', $this->moneyFormat);
        $v->rule('regex', 'valor.abatimento.valorPerc', $this->moneyFormat);
        $v->rule('regex', 'valor.desconto.descontoDataFixa.*.valorPerc', $this->moneyFormat);
        $v->rule('dateFormat', 'valor.desconto.descontoDataFixa.*.data', 'Y-m-d');
        $v->rule('required', 'chave', 77);
        $v->rule('lengthMax', 'chave', 77);
        $v->rule('lengthMax', 'solicitacaoPagador', 140);
        $v->rule('lengthMax', 'infoAdicionais.*.nome', 50);
        $v->rule('lengthMax', 'infoAdicionais.*.valor', 200);
        $v->rule('requiredWithout', 'devedor.cpf', 'devedor.cnpj');
        $v->rule('requiredWithout', 'devedor.cnpj', 'devedor.cpf');

        if(!$v->validate()) {

            $errors = $v->errors();

            foreach($errors as $key => $value) {
                $errors[$key] = implode(', ', $value);
            }

            throw new Exception('Erro de validação: ' . implode(', ', $errors));

        }

        return true;

    }

    public function validatePatch()
    {

        $v = (new Validator($this->body));
        $v->rule('required', ['calendario', 'devedor', 'valor']);
        $v->rule('array', ['calendario', 'devedor', 'valor']);
        $v->rule('required', ['calendario.dataDeVencimento', 'calendario.validadeAposVencimento', 'devedor.nome', 'valor.original', 'chave']);
        $v->rule('numeric', 'valor.original');
        $v->rule('regex', 'valor.original', $this->moneyFormat);
        $v->rule('regex', 'valor.multa.valorPerc', $this->moneyFormat);
        $v->rule('regex', 'valor.juros.valorPerc', $this->moneyFormat);
        $v->rule('regex', 'valor.abatimento.valorPerc', $this->moneyFormat);
        $v->rule('regex', 'valor.desconto.descontoDataFixa.*.valorPerc', $this->moneyFormat);
        $v->rule('dateFormat', 'valor.desconto.descontoDataFixa.*.data', 'Y-m-d');
        $v->rule('required', 'chave', 77);
        $v->rule('lengthMax', 'chave', 77);
        $v->rule('lengthMax', 'solicitacaoPagador', 140);
        $v->rule('lengthMax', 'infoAdicionais.*.nome', 50);
        $v->rule('lengthMax', 'infoAdicionais.*.valor', 200);
        $v->rule('requiredWithout', 'devedor.cpf', 'devedor.cnpj');
        $v->rule('requiredWithout', 'devedor.cnpj', 'devedor.cpf');

        if(!$v->validate()) {

            $errors = $v->errors();

            foreach($errors as $key => $value) {
                $errors[$key] = implode(', ', $value);
            }

            throw new Exception('Erro de validação: ' . implode(', ', $errors));

        }

        return true;

    }

}