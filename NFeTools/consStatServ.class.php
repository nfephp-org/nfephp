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
 * @name      consStatServ
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * consStatServ
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_consStatServ {
    public $versao;     // versao do layout
    public $tpAmb;      // 
    public $cUF;        // 
    public $xServ;      // 
    public $XML;        // string XML

    public $retConsStatServ;    // objeto de retorno

    function __construct() {
        $this->versao   = '1.07';
        $this->tpAmb    = _NFE_TPAMB;
        $this->cUF      = _NFE_CUF;
        $this->xServ    = 'STATUS';

        $this->retConsStatServ = null;
    }

    function geraXML() {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $FP01 = $dom->appendChild($dom->createElement('consStatServ'));

        $FP01_att1 = $FP01->appendChild($dom->createAttribute('versao'));
                     $FP01_att1->appendChild($dom->createTextNode($this->versao));

        $FP01_att2 = $FP01->appendChild($dom->createAttribute('xmlns'));
                     $FP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $FP01_att3 = $FP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $FP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $FP01_att4 = $FP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $FP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $FP03 = $FP01->appendChild($dom->createElement('tpAmb', $this->tpAmb));
        $FP04 = $FP01->appendChild($dom->createElement('cUF',   $this->cUF));
        $FP05 = $FP01->appendChild($dom->createElement('xServ', $this->xServ));
        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_STATUSSERVICO_URL, 'nfeStatusServicoNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeStatusServicoNFResult'])) {
            $this->retConsStatServ = new retConsStatServ();
            $this->retConsStatServ->trataRetorno($result['nfeStatusServicoNFResult']);
            return $this->retConsStatServ;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CONSSTATNFE_PATH) {
        if (!empty($this->retConsStatServ->XML)) {
            $filePath = $path.'/'.str_replace(array('-',':'), '', $this->retConsStatServ->dhRecbto).'-ped-sta.xml';
            file_put_contents($filePath, $this->XML);
            return $filePath;
        } else {
            return false;
        }
    }

}
