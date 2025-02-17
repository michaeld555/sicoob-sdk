<?php

namespace Michaeld555\Helpers;

use Ramsey\Uuid\Uuid;

class TxidGenerator
{

    public static function generate(): string
    {
        return strtoupper(preg_replace('/[^0-9A-Za-z]/', '', Uuid::uuid4()->toString()));
    }

}