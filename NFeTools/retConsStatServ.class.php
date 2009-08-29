<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      retConsStatServ
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retConsStatServ
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_retConsStatServ {
    public $versao;     // versao do layout
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $dhRecbto;   // 
    public $tMed;       // 
    public $dhRetorno;  // 
    public $xObs;       // 
    public $XML;        // string XML

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsStatServ')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->tMed         = $dom->getElementsByTagName('tMed')->item(0)->nodeValue;
        $this->dhRetorno    = $dom->getElementsByTagName('dhRetorno')->item(0)->nodeValue;
        $this->xObs         = $dom->getElementsByTagName('xObs')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETCONSSTATNFE_PATH) {
        if (!empty($this->XML)) {
            $filePath = $path.'/'.str_replace(array('-',':'), '', $this->dhRecbto).'-sta.xml';
            file_put_contents($filePath, $this->XML);
            return $filePath;
        } else {
            return false;
        }
    }

}
