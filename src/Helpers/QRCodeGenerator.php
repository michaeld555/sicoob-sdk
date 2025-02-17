<?php

namespace Michaeld555\Helpers;

use chillerlan\QRCode\QRCode;

class QRCodeGenerator
{
    
    private string $data;

    public function __construct(string $url)
    {
        $this->data = $url;
    }

    public static function generate($url): string
    {
        return (new QRCode)->render($url);
    }

}