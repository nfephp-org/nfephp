<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      retConsCad
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retConsCad
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_retConsCad {
    public $versao;     // versao do layout
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $UF;         // 
    public $XML;        // 

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsSitNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->UF           = $dom->getElementsByTagName('UF')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

}
