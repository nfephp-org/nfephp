<?php

namespace NFePHP\Common\LotNumber;

/**
 * Classe auxiliar para tratar os lotes usados na comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\Common\LotNumber
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class LotNumber
{
      /**
     * geraNumLote
     * Gera numero de lote com base em microtime
     * @param integer $numdigits numero de digitos para o lote
     * @return string 
     */
    public static function geraNumLote($numdigits = 15)
    {
        return substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, $numdigits);
    }
}
