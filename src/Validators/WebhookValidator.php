<?php

namespace Michaeld555\Validators;

use Exception;
use Valitron\Validator;

class WebhookValidator
{

    private array $body;

    public function __construct(array $body)
    {
        $this->body = $body;
    }

    public function validatePut()
    {

        $v = (new Validator($this->body));
        $v->rule('required', ['webhookUrl']);
        $v->rule('lengthMax', 'webhookUrl', 200);

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