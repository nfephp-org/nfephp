<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      retEnvNFe
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retEnvNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_retEnvNFe {
    public $versao;     // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $infRec;     // 
    public $nRec;       // 
    public $dhRecbto;   // 
    public $tMed;       // 
    public $XML;        // string XML
    public $idLote;     // id do lote para gravar recibo com nome adequado

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retEnviNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->infRec       = $dom->getElementsByTagName('infRec')->item(0)->nodeValue;
        $this->nRec         = $dom->getElementsByTagName('nRec')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->tMed         = $dom->getElementsByTagName('tMed')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETENVNFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->idLote).'-rec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
