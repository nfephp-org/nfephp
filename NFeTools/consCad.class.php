<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      consCad
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * consCad
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_consCad {
    public $versao;         // versao do layout
    public $xServ;          // 
    public $UF;             // 
    public $IE;             // 
    public $CNPJ;           // 
    public $CPF;            // 
    public $XML;            // string XML

    public $retConsCad;  // objeto de retorno

    function __construct() {
        $this->versao        = '1.01';
        $this->xServ         = 'CONS-CAD';

        $this->retConsCad = null;
    }

    function geraXML() {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $GP01 = $dom->appendChild($dom->createElement('consCad'));

        $GP01_att1 = $GP01->appendChild($dom->createAttribute('versao'));
                     $GP01_att1->appendChild($dom->createTextNode($this->versao));

        $GP01_att2 = $GP01->appendChild($dom->createAttribute('xmlns'));
                     $GP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $GP03 = $GP01->appendChild($dom->createElement('infCons'));
        $GP04 = $GP03->appendChild($dom->createElement('xServ', $this->xServ));
        $GP05 = $GP03->appendChild($dom->createElement('UF',    $this->UF));
        //$GP06 = $GP03->appendChild($dom->createElement('IE',    $this->IE));
        $GP07 = $GP03->appendChild($dom->createElement('CNPJ',  $this->CNPJ));
        //$GP08 = $GP03->appendChild($dom->createElement('CPF',   $this->CPF));

        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_CONSULTACADASTRO_URL, 'consultaCadastro', $this->geraXML(), $this->versao);

        if (!empty($result['consultaCadastroResult'])) {
            $this->retConsCad = new retConsCad();
            $this->retConsCad->trataRetorno($result['consultaCadastroResult']);
            return $this->retConsCad;
        } else {
            return false;
        }
    }

}
