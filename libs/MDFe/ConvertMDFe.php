<?php

namespace NFePHP\MDFe;

/**
 * Classe para a conversão dos manifestos de carga do formato TXT conforme padrão
 * do emissor gratuito para o formato XML.
 * @category   NFePHP
 * @package    NFePHP\MDFe\ConvertMDFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 * 
 * NOTA: O emissor gratuito de MDFe não importa nem gera arquivos TXT e portanto 
 * não existe um formato geral estabelecido para essa função. 
 * Dito isso, o formato que está estabelecido e contido nessa classe
 * não se aplica a nenhum outro sistema além desse e pode não estar 
 * adequado a todas as possibilidades.
 */

use NFePHP\Common\Strings\Strings;
use NFePHP\Common\Exception;
use NFePHP\MDFe\MakeMDFe;

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
