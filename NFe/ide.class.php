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
 * @name      ide
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * ide
 * Nível 2 :: B01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_ide {
    var $cUF;       // B02 - código da UF do emitente
    var $cNF;       // B03 - código numérico que compõe a chave de acesso;
    var $natOp;     // B04 - descrição da natureza da operação
    var $indPag;    // B05 - indicador da forma de pagamento
    var $mod;       // B06 - código do modelo do documento fiscal
    var $serie;     // B07 - série do documento fiscal
    var $nNF;       // B08 - número do documento fiscal
    var $dEmi;      // B09 - data de emissão do documento fiscal
    var $dSaiEnt;   // B10 - data da saída ou da entrada da mercadoria/produto
    var $tpNF;      // B11 - tipo do documento fiscal (0-entrada / 1-saida)
    var $cMunFG;    // B12 - código do município de ocorrência do fato gerador
    var $NFref;     // B12a- informação das NF/NFe referenciadas
    var $tpImp;     // B21 - formato de impressão do DANFE
    var $tpEmis;    // B22 - forma de emissão da NFe
    var $cDV;       // B23 - dígito verificador da chave de acesso
    var $tpAmb;     // B24 - identificação do ambiente
    var $finNFe;    // B25 - finalidade de emissão da NFe
    var $procEmi;   // B26 - processo de emissão da NFe
    var $verProc;   // B27 - versão do processo de emissão da NFe

    function __construct() {
        $this->mod      = 55;               // NFe
        $this->NFref    = array();
        $this->procEmi  = 0;                // emissão de NFe com aplicativo do contribuinte

    }

    // NFe ou NF
    function add_NFref($obj_NFref) {
        $this->NFref[] = $obj_NFref;
        return true;
    }

    function get_xml($dom) {
        $B01 = $dom->appendChild($dom->createElement('ide'));
        $B02 = $B01->appendChild($dom->createElement('cUF',     $this->cUF));
        $B03 = $B01->appendChild($dom->createElement('cNF',     sprintf("%09d", $this->cNF)));
        $B04 = $B01->appendChild($dom->createElement('natOp',   $this->natOp));
        $B05 = $B01->appendChild($dom->createElement('indPag',  $this->indPag));
        $B06 = $B01->appendChild($dom->createElement('mod',     $this->mod));
        $B07 = $B01->appendChild($dom->createElement('serie',   $this->serie));
        $B08 = $B01->appendChild($dom->createElement('nNF',     $this->nNF));
        $B09 = $B01->appendChild($dom->createElement('dEmi',    $this->dEmi));
        $B10 = (!empty($this->dSaiEnt)) ? $B01->appendChild($dom->createElement('dSaiEnt', $this->dSaiEnt)) : '';
        $B11 = $B01->appendChild($dom->createElement('tpNF',    $this->tpNF));
        $B12 = $B01->appendChild($dom->createElement('cMunFG',  $this->cMunFG));
        for ($i=0; $i<count($this->NFref); $i++) {
            $B12a= $B01->appendChild($this->NFref[$i]->get_xml($dom));
        }
        $B21 = $B01->appendChild($dom->createElement('tpImp',   $this->tpImp));
        $B22 = $B01->appendChild($dom->createElement('tpEmis',  $this->tpEmis));
        $B23 = $B01->appendChild($dom->createElement('cDV',     $this->cDV));
        $B24 = $B01->appendChild($dom->createElement('tpAmb',   $this->tpAmb));
        $B25 = $B01->appendChild($dom->createElement('finNFe',  $this->finNFe));
        $B26 = $B01->appendChild($dom->createElement('procEmi', $this->procEmi));
        $B27 = $B01->appendChild($dom->createElement('verProc', $this->verProc));
        return $B01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO ide VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->cUF);
        $sql.= ", ".$con->quote($this->cNF);
        $sql.= ", ".$con->quote($this->natOp);
        $sql.= ", ".$con->quote($this->indPag);
        $sql.= ", ".$con->quote($this->mod);
        $sql.= ", ".$con->quote($this->serie);
        $sql.= ", ".$con->quote($this->nNF);
        $sql.= ", ".$con->quote($this->dEmi);
        $sql.= ", ".$con->quote($this->dSaiEnt);
        $sql.= ", ".$con->quote($this->tpNF);
        $sql.= ", ".$con->quote($this->cMunFG);
        $sql.= ", ".$con->quote($this->tpImp);
        $sql.= ", ".$con->quote($this->tpEmis);
        $sql.= ", ".$con->quote($this->cDV);
        $sql.= ", ".$con->quote($this->tpAmb);
        $sql.= ", ".$con->quote($this->finNFe);
        $sql.= ", ".$con->quote($this->procEmi);
        $sql.= ", ".$con->quote($this->verProc);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ide: '.$qry->getMessage());
            return false;
        } else {
            $ide_id = $con->lastInsertID("ide", "ide_id");
            for ($i=0; $i<count($this->NFref); $i++) {
                $this->NFref[$i]->insere($con, $ide_id);
            }
        }
    }
}
