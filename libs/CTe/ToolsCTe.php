<?php

namespace NFePHP\CTe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\CTe\ToolsCTe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Base\BaseTools;
use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\LotNumber\LotNumber;
use NFePHP\Common\Strings\Strings;
use NFePHP\Common\Files;
use NFePHP\Common\Exception;
use NFePHP\Common\Dom\Dom;
use \DOMDocument;
use NFePHP\CTe\ReturnCTe;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class ToolsCTe extends BaseTools
{
    
    public function printCTe()
    {
        
    }
    
    public function mailCTe()
    {
        
    }
    
    /**
     * assina
     * @param string $xml
     * @param boolean $saveFile
     * @return string
     * @throws Exception\RuntimeException
     */
    public function assina($xml = '', $saveFile = false)
    {
        return $this->assinaDoc($xml, 'mdfe', 'infMDFe', $saveFile);
    }

    
    public function sefazEnvia()
    {
        
    }
    
    public function sefazConsultaRecibo()
    {
        
    }
    
    public function sefazConsultaChave()
    {
        
    }
    
    public function sefazStatus()
    {
        
    }
    
    public function sefazCancela()
    {
        
    }
}
