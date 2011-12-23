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
 * @name      envNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * envNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_envNFe {
    public $versao;     // versao do layout
    public $idLote;     // id do lote
    public $aNFe;       // array de NFe's
    public $XML;        // string XML

    public $retEnvNFe;  // 

    function __construct() {
        $this->versao   = '1.10';

        $this->retEnvNFe = null;
    }

    function addNFe($XML) {
        if (count($this->aNFe) >= 50) {
            return false;
        }
        $this->aNFe[] = $XML;
    }

    function geraXML() {

        /* USAR ASSIM NO FUTURO COM PHP >= 5.3
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $FP01 = $dom->appendChild($dom->createElement('enviNFe'));

        $FP01_att1 = $FP01->appendChild($dom->createAttribute('versao'));
                     $FP01_att1->appendChild($dom->createTextNode($this->versao));

        $FP01_att2 = $FP01->appendChild($dom->createAttribute('xmlns'));
                     $FP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $FP01_att3 = $FP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $FP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $FP01_att4 = $FP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $FP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $FP03 = $FP01->appendChild($dom->createElement('idLote', $this->idLote));

        // BUG no PHP < 5.3: http://bugs.php.net/bug.php?id=46185
        // cria uma tag xmlns:default indesejada no elemento <NFe>
        foreach ($this->aNFe as $NFe) {
            $ddd = new DOMDocument('1.0', 'utf-8');
            $ddd->formatOutput = false;
            $ddd->loadXML($NFe);
            $FP01->appendChild($dom->importNode($ddd->getElementsByTagName('NFe')->item(0), true));
        }

        return $this->XML = $dom->saveXML();
        */


        // workaround
        $sNFe = implode('', $this->aNFe);
        $sNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $sNFe);

        $xml = '<enviNFe versao="'.$this->versao.'" xmlns="http://www.portalfiscal.inf.br/nfe" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xml.= '<idLote>'.$this->idLote.'</idLote>';
        $xml.= str_replace("\n", "", $sNFe);
        $xml.= '</enviNFe>';

        return $this->XML = $xml;
    }

    function sendSOAP() {

        if (!is_array($this->aNFe) || !count($this->aNFe)) {
            return false;
        }

        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_RECEPCAO_URL, 'nfeRecepcaoLote', $this->geraXML(), $this->versao);

        if (!empty($result['nfeRecepcaoLoteResult'])) {
            $this->retEnvNFe = new retEnvNFe();
            $this->retEnvNFe->trataRetorno($result['nfeRecepcaoLoteResult']);
            $this->retEnvNFe->idLote = $this->idLote;
            return $this->retEnvNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_ENVNFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->idLote).'-env-lot.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
