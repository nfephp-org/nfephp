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
 * @name      NFeTools
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * NFeTools
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools {
    function __construct() {
    }


    function assinaNFe($sXML) {
        $assinatura = new assinatura();
        $xml_assinado = $assinatura->assinaXML($sXML, 'infNFe');
        return $xml_assinado;
    }

    // criar obj validacao
    function validaXML($sXML, $xsdFile) {

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $xml = $dom->loadXML($sXML);

        $erromsg = '';

        if (!$dom->schemaValidate($xsdFile)) {

            $aIntErrors = libxml_get_errors();

            $flagOK = FALSE;

            foreach ($aIntErrors as $intError){
                switch ($intError->level) {
                    case LIBXML_ERR_WARNING:
                        $erromsg .= " Atenção $intError->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $erromsg .= " Erro $intError->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $erromsg .= " Erro fatal $intError->code: ";
                        break;
                }
                $erromsg .= $intError->message . ';';
            }
        } else {
            $flagOK = TRUE;
            $this->errorStatus = FALSE;
            $this->errorMsg = '';
        }

        if (!$flagOK){
            $this->errorStatus = TRUE;
            $this->errorMsg = $erromsg;
        }
        return $flagOK;

    }

    function enviaNFe($aNFe, $idLote) {

        $envNFe = new envNFe();

        foreach ($aNFe as $NFe) {
            $envNFe->addNFe($NFe);
        }
        $envNFe->idLote = $idLote;

        $envNFe->sendSOAP();
        
        return $envNFe;
    }

    function retornoNFe($nRec) {

        $consReciNFe = new consReciNFe();

        $consReciNFe->nRec = $nRec;

        $consReciNFe->sendSOAP();

        return $consReciNFe;

    }

    function enviaDPEC($chNFe,$IE,$verProc,$destCNPJ,$destCPF,$vNF,$vICMS,$vST){

        $envDPEC = new envDPEC();

        $envDPEC->versao = "1.00";
        $envDPEC->chNFe;      // id da NFe emitida em contingência DPEC - esta contido no xml da NFe 44 digitos
        $envDPEC->IE;         // IE do emitente
        $envDPEC->verProc;    //versão do programa que gera a NFe - esta dento do xml da NFe
        $envDPEC->destCNPJ;   //CNPJ do destinatario
        $envDPEC->destCPF;    // CFP do destinatario
        $envDPEC->destUF;     // sigla da UF do destinatario
        $envDPEC->vNF;        // valor da NF
        $envDPEC->vICMS;      //valor do ICMS
        $envDPEC->vST;         // valor total do ICMS retido com Substituicao tributaria      
        
        //$envDPEC->tpAmb;      //
        //$envDPEC->id;         // ID = DPEC + CNPJ
        //$envDPEC->CNPJ;       // CNPJ do emitente OBITIDO DO chNFe
        //$envDPEC->cUF;        //codigo numerico da UF do emitente OBITIDO DO chNFe
        
        $envDPEC->sendSOAP();
        
        return $envDPEC;
    }

    function imprimeNFe($xml, $formato="P", $path_logomarca="", $protocolo="", $data_hora=""){
        include_once ('danfe.class.php');
        $danfe = new danfe($xml, $formato);
        $danfe->protocolo = $protocolo;
        $danfe->data_hora = $data_hora;
        if (!empty($path_logomarca)) {
            $danfe->logomarca = $path_logomarca;
        }
        return $danfe->gera();
    }

    function cancelaNFe($chNFe, $nProt, $xJust) {
        $cancNFe = new cancNFe();
        $cancNFe->nProt = $nProt;
        $cancNFe->xJust = $xJust;
        $cancNFe->chNFe = $chNFe;
        $cancNFe->sendSOAP();
        return $cancNFe;
    }

    function inutilizaNFe($ano, $CNPJ, $mod, $serie, $nNFIni, $nNFFin, $xJust) {
        $inutNFe = new inutNFe();
        $inutNFe->ano       = $ano;
        $inutNFe->CNPJ      = $CNPJ;
        $inutNFe->mod       = $mod;
        $inutNFe->serie     = $serie;
        $inutNFe->nNFIni    = $nNFIni;
        $inutNFe->nNFFin    = $nNFFin;
        $inutNFe->xJust     = $xJust;
        $inutNFe->sendSOAP();
        return $inutNFe;
    }

    function consultaNFe() {
    }

    function consultaCadastro() {
    }

    function distribuiNFe($NFe, $protNFe) {
        $procNFe = new procNFe();
        $procNFe->NFe       = $NFe;
        $procNFe->protNFe   = $protNFe;
        $procNFe->geraXML();
        return $procNFe;
    }

    function statusServico() {
        $pedStatus = new consStatServ();
        $pedStatus->sendSOAP();
        if ($pedStatus->retConsStatServ) {
            $PedFilePath = $pedStatus->gravaXML();
            $RetFilePath = $pedStatus->retConsStatServ->gravaXML();
        }
        return $pedStatus;
    }
}
