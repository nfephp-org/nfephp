<?php

namespace NFePHP\Extras;

/**
 * Classe complementar
 * necessária para extender a classe base Exception
 * Usada no tratamento de erros da API
 *
 * @version 1.0.0
 * @package NFePHP
 * @name    nfephpException
 */

use Exception;

class NfephpException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = $this->getMessage()."\n";
        return $errorMsg;
    }
}
