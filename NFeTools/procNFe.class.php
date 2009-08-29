<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      procNFe
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * procNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_procNFe {
    public $versao;     // versao do layout
    public $NFe;        // 
    public $protNFe;    // 
    public $XML;        // string XML

    function __construct() {
        $this->versao   = '1.10';
    }

    function geraXML() {

        $NFe     = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $this->NFe);
        $protNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $this->protNFe);

        // NÃO USADO DOM DEVIDO AO BUG NO PHP
        $nfeProc = '<nfeProc versao="'.$this->versao.'" xmlns="http://www.portalfiscal.inf.br/nfe">';
        $nfeProc.= $NFe;
        $nfeProc.= $protNFe;
        $nfeProc.= '</nfeProc>';

        return $this->XML = str_replace("\n", "", $nfeProc);
    }

    function gravaXML($path=_NFE_PROCNFE_PATH) {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($this->NFe);
        $infNFe = $dom->getElementsByTagName('infNFe')->item(0);
        $chNFe = str_replace('NFe', '', $infNFe->getAttribute('Id'));

        $filePath = $path.'/'.$chNFe.'-proc-nfe.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
