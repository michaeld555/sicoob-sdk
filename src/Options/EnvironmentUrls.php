<?php

namespace Michaeld555\Options;

class EnvironmentUrls
{

    public const auth_url = 'https://auth.sicoob.com.br/auth/realms/cooperado/protocol/openid-connect/token';

    public const production_pix_url = 'https://api.sicoob.com.br/pix/api/v2';
    
    public const sandbox_pix_url = 'https://sandbox.sicoob.com.br/sicoob/sandbox/pix/api/v2';

    public const production_boleto_url = 'https://api.sicoob.com.br/cobranca-bancaria/v3';
    
    public const sandbox_boleto_url = 'https://sandbox.sicoob.com.br/sicoob/sandbox/cobranca-bancaria/v3';

}
