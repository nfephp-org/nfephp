<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      cancNFe
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * cancNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_cancNFe {
    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $xServ;      // 
    public $chNFe;      // 
    public $nProt;      // 
    public $xJust;      // 
    public $XML;        // string XML

    public $retCancNFe; // objeto de retorno

    function __construct() {
        $this->versao   = '1.07';
        $this->tpAmb    = _NFE_TPAMB;
        $this->xServ    = 'CANCELAR';

        $this->retCancNFe = null;
    }

    function geraXML() {

        $this->Id = 'ID'.$this->chNFe;

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $CP01 = $dom->appendChild($dom->createElement('cancNFe'));

        $CP01_att1 = $CP01->appendChild($dom->createAttribute('versao'));
                     $CP01_att1->appendChild($dom->createTextNode($this->versao));

        $CP01_att2 = $CP01->appendChild($dom->createAttribute('xmlns'));
                     $CP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $CP03 = $CP01->appendChild($dom->createElement('infCanc'));
        $CP04 = $CP03->setAttribute('Id', $this->Id);
        $CP05 = $CP03->appendChild($dom->createElement('tpAmb', $this->tpAmb));
        $CP06 = $CP03->appendChild($dom->createElement('xServ', $this->xServ));
        $CP07 = $CP03->appendChild($dom->createElement('chNFe', $this->chNFe));
        $CP08 = $CP03->appendChild($dom->createElement('nProt', $this->nProt));
        $CP09 = $CP03->appendChild($dom->createElement('xJust', $this->xJust));

        $xml = $dom->saveXML();

        $assinatura = new assinatura();
        $this->XML = $assinatura->assinaXML($xml, 'infCanc');

        return $this->XML;        
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_CANCELAMENTO_URL, 'nfeCancelamentoNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeCancelamentoNFResult'])) {
            $this->retCancNFe = new retCancNFe();
            $this->retCancNFe->trataRetorno($result['nfeCancelamentoNFResult']);
            return $this->retCancNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CANCNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-ped-can.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
