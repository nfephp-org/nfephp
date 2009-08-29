<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      consReciNFe
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * consReciNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_consReciNFe {
    public $versao;     // versao do layout
    public $tpAmb;      // 
    public $nRec;       // 
    public $XML;        // string XML

    public $retConsReciNFe;

    function __construct() {
        $this->versao   = '1.10';
        $this->tpAmb    = _NFE_TPAMB;

        $this->retConsReciNFe = null;
    }

    function geraXML() {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $BP01 = $dom->appendChild($dom->createElement('consReciNFe'));

        $BP01_att1 = $BP01->appendChild($dom->createAttribute('versao'));
                     $BP01_att1->appendChild($dom->createTextNode($this->versao));

        $BP01_att2 = $BP01->appendChild($dom->createAttribute('xmlns'));
                     $BP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $BP01_att3 = $BP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $BP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $BP01_att4 = $BP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $BP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $BP03 = $BP01->appendChild($dom->createElement('tpAmb', $this->tpAmb));
        $BP04 = $BP01->appendChild($dom->createElement('nRec',  $this->nRec));

        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {

        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_RETRECEPCAO_URL, 'nfeRetRecepcao', $this->geraXML(), $this->versao);

        if (!empty($result['nfeRetRecepcaoResult'])) {
            $this->retConsReciNFe = new retConsReciNFe();
            $this->retConsReciNFe->trataRetorno($result['nfeRetRecepcaoResult']);
            return $this->retConsReciNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CONSRECINFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->nRec).'-ped-rec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
