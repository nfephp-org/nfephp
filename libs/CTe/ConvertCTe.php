<?php

namespace NFePHP\CTe;

/**
 * Classe para a conversão de Conhecimentos de transporte do formato TXT conforme padrão
 * do emissor gratuito para o formato XML.
 * @category   NFePHP
 * @package    NFePHP\CTe\ConvertCTe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Strings\Strings;
use NFePHP\Common\Exception;
use NFePHP\CTe\MakeCTe;

class ConvertCTe
{
    protected $limparString = true;

    /**
     * contruct
     * Método contrutor da classe
     * @param boolean $limparString Ativa flag para limpar os caracteres especiais e acentos
     * @return none
     */
    public function __construct($limparString = true)
    {
        $this->limparString = $limparString;
    }
    
    /**
     * txt2xml
     * Converte uma ou multiplos CTe em formato txt em xml
     * @param mixed $txt Path para txt, txt ou array de txt
     * @return array
     */
    public function txt2xml($txt)
    {
        
    }
}
