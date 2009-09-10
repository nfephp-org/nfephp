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
 * @name      inutNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * inutNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_inutNFe {
    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $xServ;      // 
    public $cUF;        // 
    public $ano;        // 
    public $CNPJ;       // 
    public $mod;        // 
    public $serie;      // 
    public $nNFIni;     // 
    public $nNFFin;     // 
    public $xJust;      // 
    public $XML;        // string XML

    public $retInutNFe; // objeto de retorno

    function __construct() {
        $this->versao   = '1.07';
        $this->tpAmb    = _NFE_TPAMB;
        $this->xServ    = 'INUTILIZAR';
        $this->cUF      = _NFE_CUF;

        $this->retInutNFe = null;
    }

    function geraXML() {

        $this->Id = 'ID'.$this->cUF.$this->CNPJ.$this->mod.$this->serie.$this->nNFIni.$this->nNFFin;

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $DP01 = $dom->appendChild($dom->createElement('inutNFe'));

        $DP01_att1 = $DP01->appendChild($dom->createAttribute('versao'));
                     $DP01_att1->appendChild($dom->createTextNode($this->versao));

        $DP01_att2 = $DP01->appendChild($dom->createAttribute('xmlns'));
                     $DP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $DP03 = $DP01->appendChild($dom->createElement('infInut'));
        $DP04 = $DP03->setAttribute('Id', $this->Id);
        $DP05 = $DP03->appendChild($dom->createElement('tpAmb',     $this->tpAmb));
        $DP06 = $DP03->appendChild($dom->createElement('xServ',     $this->xServ));
        $DP07 = $DP03->appendChild($dom->createElement('cUF',       $this->cUF));
        $DP08 = $DP03->appendChild($dom->createElement('ano',       $this->ano));
        $DP09 = $DP03->appendChild($dom->createElement('CNPJ',      $this->CNPJ));
        $DP10 = $DP03->appendChild($dom->createElement('mod',       $this->mod));
        $DP11 = $DP03->appendChild($dom->createElement('serie',     $this->serie));
        $DP12 = $DP03->appendChild($dom->createElement('nNFIni',    $this->nNFIni));
        $DP13 = $DP03->appendChild($dom->createElement('nNFFin',    $this->nNFFin));
        $DP14 = $DP03->appendChild($dom->createElement('xJust',     $this->xJust));

        $xml = $dom->saveXML();

        $assinatura = new assinatura();
        $this->XML = $assinatura->assinaXML($xml, 'infInut');

        return $this->XML;        
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_INUTILIZACAO_URL, 'nfeInutilizacaoNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeInutilizacaoNFResult'])) {
            $this->retInutNFe = new retInutNFe();
            $this->retInutNFe->trataRetorno($result['nfeInutilizacaoNFResult']);
            return $this->retInutNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_INUTNFE_PATH) {
        $nome = $this->ano.$this->CNPJ.$this->mod.sprintf("%03s", $this->serie).sprintf("%09s", $this->nNFIni).sprintf("%09s", $this->nNFFin);
        $filePath = $path.'/'.$nome.'-ped-inu.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
