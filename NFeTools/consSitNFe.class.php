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
 * @name      consSitNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * consSitNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_consSitNFe {
    public $versao;         // versao do layout
    public $tpAmb;          // 
    public $xServ;          // 
    public $chNFe;          // 
    public $XML;            // string XML

    public $retConsSitNFe;  // objeto de retorno

    function __construct() {
        $this->versao        = '1.07';
        $this->tpAmb         = _NFE_TPAMB;
        $this->xServ         = 'CONSULTAR';

        $this->retConsSitNFe = null;
    }

    function geraXML() {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $EP01 = $dom->appendChild($dom->createElement('consSitNFe'));

        $EP01_att1 = $EP01->appendChild($dom->createAttribute('versao'));
                     $EP01_att1->appendChild($dom->createTextNode($this->versao));

        $EP01_att2 = $EP01->appendChild($dom->createAttribute('xmlns'));
                     $EP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $EP03 = $EP01->appendChild($dom->createElement('tpAmb',     $this->tpAmb));
        $EP04 = $EP01->appendChild($dom->createElement('xServ',     $this->xServ));
        $EP05 = $EP01->appendChild($dom->createElement('chNFe',     $this->chNFe));

        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_CONSULTANF_URL, 'nfeConsultaNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeConsultaNFResult'])) {
            $this->retConsSitNFe = new retConsSitNFe();
            $this->retConsSitNFe->trataRetorno($result['nfeConsultaNFResult']);
            return $this->retConsSitNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CONSSITNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-ped-sit.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
