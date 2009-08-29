<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package   NFePHP
 * @name      NFeSOAP
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * NFeSOAP
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 */
class NFeTools_NFeSOAP {
    private $certificado;

    function __construct() {
        $this->certificado = new certificado();
    }

    function getCabec($versao) {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $raiz = $dom->appendChild($dom->createElement('cabecMsg'));

        $raiz_att1 = $raiz->appendChild($dom->createAttribute('versao'));
        $raiz_att1->appendChild($dom->createTextNode('1.02'));

        $raiz_att2 = $raiz->appendChild($dom->createAttribute('xmlns'));
        $raiz_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $raiz->appendChild($dom->createElement('versaoDados', $versao));

        return $this->XML = $dom->saveXML();
    }

    function send($URL, $metodo, $mensagem, $versao) {

        include_once (_NFE_LIB_PATH.'/nusoap/nusoap.php');

        $client = new nusoap_client($URL."?WSDL", true);
        $client->authtype         = 'certificate';
        $client->soap_defencoding = 'UTF-8';

        $client->certRequest['sslkeyfile']  = $this->certificado->privateKeyFile;
        $client->certRequest['sslcertfile'] = $this->certificado->publicKeyFile;
        $client->certRequest['passphrase']  = $this->certificado->passPhrase;
        $client->certRequest['verifypeer']  = false;
        $client->certRequest['verifyhost']  = false;
        $client->certRequest['trace']       = 1;

        $soapMsg['nfeCabecMsg'] = $this->getCabec($versao);
        $soapMsg['nfeDadosMsg'] = $mensagem;

        $result = $client->call($metodo, $soapMsg);

        return $result;

    }

}
