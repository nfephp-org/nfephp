<?php

namespace MDFe;

/**
 * Classe para a conversão dos manifestos de carga do formato TXT conforme padrão
 * do emissor gratuito para o formato XML.
 * @category   NFePHP
 * @package    NFePHP\MDFe\ConvertMDFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Strings\Strings;
use Common\Exception;
use MDFe\MakeMDFe;

class ConvertMDFe
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
     * Converte uma ou multiplos MDFe em formato txt em xml
     * @param mixed $txt Path para txt, txt ou array de txt
     * @return array
     */
    public function txt2xml($txt)
    {
        
    }
}
