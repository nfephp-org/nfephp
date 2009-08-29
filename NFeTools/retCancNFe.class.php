<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      retCancNFe
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retCancNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_retCancNFe {
    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $chNFe;      // 
    public $dhRecbto;   // 
    public $nProt;      // 
    public $XML;        // string XML

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retCancNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->chNFe        = $dom->getElementsByTagName('chNFe')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->nProt        = $dom->getElementsByTagName('nProt')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETCANCNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-can.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
