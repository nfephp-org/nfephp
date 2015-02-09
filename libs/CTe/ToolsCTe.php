<?php
namespace CTe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\CTe\Tools
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Base\BaseTools;
use Common\Certificate\Pkcs12;
use Common\DateTime\DateTime;
use Common\LotNumber\LotNumber;
use Common\Soap\CurlSoap;
use Common\Strings\Strings;
use Common\Files;
use Common\Exception;
use Common\Dom\Dom;

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
