<?php

namespace Michaeld555\Validators;

use Exception;
use Valitron\Validator;

class CobValidator
{

    private array $body;

    public function __construct(array $body)
    {
        $this->body = $body;
    }

    public function validatePost()
    {

        $v = (new Validator($this->body));
        $v->rule('required', ['calendario', 'devedor', 'valor']);
        $v->rule('array', ['calendario', 'devedor', 'valor']);
        $v->rule('required', ['calendario.expiracao', 'devedor.nome', 'valor.original', 'chave', 'solicitacaoPagador']);
        $v->rule('numeric', 'valor.original');
        $v->rule('regex', 'valor.original', '/^\d{1,10}\.\d{2}$/');
        $v->rule('integer', 'valor.modalidadeAlteracao');
        $v->rule('min', 'valor.modalidadeAlteracao', 0);
        $v->rule('max', 'valor.modalidadeAlteracao', 1);
        $v->rule('required', 'chave');
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
        $v->rule('array', ['calendario', 'devedor', 'valor']);
        $v->rule('numeric', 'valor.original');
        $v->rule('regex', 'valor.original', '/^\d{1,10}\.\d{2}$/');
        $v->rule('integer', 'valor.modalidadeAlteracao');
        $v->rule('between', 'valor.modalidadeAlteracao', 0, 1);
        $v->rule('lengthMax', ['chave', 'solicitacaoPagador'], 77);
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