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
 * @name      envDPECNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto.machado@superig.com.br>
 * 
 * @todo Incluir condições para envio de várias NFe simultaneamente pelo DPEC
 */

class NFeTools_envDPEC {
    public $versao;     // versao do layout
    public $tpAmb;      // tipo de ambiente
    public $id;         // ID = DPEC + CNPJ
    public $chNFe;      // id da NFe emitida em contingência DPEC - esta contido no xml da NFe 44 digitos
    public $CNPJ;       // CNPJ do emitente
    public $IE;         // IE do emitente
    public $cUF;        //codigo numerico da UF do emitente
    public $verProc;    //versão do programa que gera a NFe - esta dento do xml da NFe
    public $destCNPJ;   //CNPJ do destinatario
    public $destCPF;    // CFP do destinatario
    public $destUF;     // sigla da UF do destinatario
    public $vNF;        // valor da NF
    public $vICMS;      //valor do ICMS
    public $vST;        //Valor Total do ICMS retido por Subsituição Tributária
    public $XML;        // string XML
    public $retDPEC;    //retorno do SEFAZ

    function __construct() {
        $this->versao   = '1.10';
        $this->tpAmb    = _NFE_TPAMB;
        $this->retEnvDPEC = null;
    }

    function geraXML() {
        $this->id = 'DPEC'.$this->CNPJ;
        $this->cUF = substr($this->chNFe,0,2);
        $this->CNPJ = substr($this->chNFe,6,14);

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $BP01 = $dom->appendChild($dom->createElement('envDPEC'));

        $BP01_att1 = $BP01->appendChild($dom->createAttribute('versao'));
                     $BP01_att1->appendChild($dom->createTextNode($this->versao));

        $BP01_att2 = $BP01->appendChild($dom->createAttribute('xmlns'));
                     $BP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $BP01_att3 = $BP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $BP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $BP01_att4 = $BP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $BP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $BP02 = $BP01->appendChild($dom->createElement('infDPEC'));

        $BP02_att1 = $BP02->appendChild($dom->createAttribute('Id'));
                     $BP02_att1->appendChild($dom->createTextNode($this->id));

        $BP03 = $BP02->appendChild($dom->createElement('ideDec'));
        $BP04 = $BP03->appendChild($dom->createElement('cUF',$this->cUF));
        $BP04 = $BP03->appendChild($dom->createElement('tpAmb',$this->tpAmb));
        $BP04 = $BP03->appendChild($dom->createElement('verProc',$this->verProc));
        $BP04 = $BP03->appendChild($dom->createElement('CNPJ',$this->CNPJ));
        $BP04 = $BP03->appendChild($dom->createElement('IE',$this->IE));
        $BP04 = $BP03->appendChild($dom->createElement('resNFe'));
        $BP05 = $BP04->appendChild($dom->createElement('chNFe',$this->chNFe));
        if ($this->destCNPJ != ''){
            $BP06 = $BP05->appendChild($dom->createElement('CNPJ',$this->destCNPJ));
        } else {
            $BP06 = $BP05->appendChild($dom->createElement('CPF',$this->destCPF));
        }
        $BP06 = $BP05->appendChild($dom->createElement('CPF',$this->destUF));
        $BP06 = $BP05->appendChild($dom->createElement('vNF',$this->vNF));
        $BP06 = $BP05->appendChild($dom->createElement('vICMS',$this->vICMS));
        $BP06 = $BP05->appendChild($dom->createElement('vST',$this->vST));

        $xml = $dom->saveXML();

        $assinatura = new assinatura();
        $this->XML = $assinatura->assinaXML($xml, 'infDPEC');
        return $this->XML;

    }

    function sendSOAP() {

        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_ENVDPEC_URL, 'sceRecepcaoDPEC', $this->geraXML(), $this->versao);

        if (!empty($result['sceRecepcaoDPECResponse'])) {
            $this->retEnvDPEC = new retEnvDPEC();
            $this->retEnvDPEC->trataRetorno($result['sceRecepcaoDPECResponse']);
            return $this->retEnvDPEC;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_ENVDEPC_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-env-dpec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

?>
