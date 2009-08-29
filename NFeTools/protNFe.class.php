<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      protNFe
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * protNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_protNFe {
    public $versao;
    public $Id;
    public $tpAmb;
    public $verAplic;
    public $chNFe;
    public $dhRecbto;
    public $nProt;
    public $digVal;
    public $cStat;
    public $xMotivo;
    public $XML;

    function __construct() {
    }

    function gravaXML($path=_NFE_PROTNFE_PATH) {
        if ($this->cStat == 100) {
            $extensao = '-aut.xml';
        } else if ($this->cStat == 110) {
            $extensao = '-den.xml';
        } else {
            $extensao = '-rej.xml';
        }
        $filePath = $path.'/'.sprintf("%015s", $this->chNFe).$extensao;
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }
}
