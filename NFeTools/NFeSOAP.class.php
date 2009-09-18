<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação 
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; sem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 * @package   NFePHP
 * @name      NFeSOAP
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * NFeSOAP
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
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
        
        //testar de URL é DPEC
        if (strtoupper(substr($metodo,0,3)) == 'SCE') {
            //a URL pertence a DPEC, alterar o padrão de comunicação
            $soapMsg['sceCabecMsg'] = $this->getCabec($versao);
            $soapMsg['sceDadosMsg'] = $mensagem;
        } else {
            $soapMsg['nfeCabecMsg'] = $this->getCabec($versao);
            $soapMsg['nfeDadosMsg'] = $mensagem;
        }

        $result = $client->call($metodo, $soapMsg);

        return $result;

    }

}
