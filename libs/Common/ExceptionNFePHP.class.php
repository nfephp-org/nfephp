<?php

/**
 * Classe complementar
 * necessária para extender a classe base Exception
 * Usada no tratamento de erros da API
 *
 * @version 1.0.0
 * @package NFePHP
 * @name nfephpException
 *
 */

class nfephpException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = $this->getMessage()."\n";
        return $errorMsg;
    }
}
