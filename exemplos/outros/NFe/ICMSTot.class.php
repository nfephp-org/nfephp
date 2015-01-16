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
 * @name      ICMSTot
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * ICMSTot
 * Nível 3 :: W02
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_ICMSTot {
    var $vBC;       // W03 - base de cáculo para ICMS
    var $vICMS;     // W04 - valor total do ICMS
    var $vBCST;     // W05 - base de cálculo para ICMS ST
    var $vST;       // W06 - valor total do ICMS ST
    var $vProd;     // W07 - valor total dos produtos e serviços
    var $vFrete;    // W08 - valor total do frete
    var $vSeg;      // W09 - valor total do seguro
    var $vDesc;     // W10 - valor total do desconto
    var $vII;       // W11 - valor total do II
    var $vIPI;      // W12 - valor total do IPI
    var $vPIS;      // W13 - valor total do PIS
    var $vCOFINS;   // W14 - valor total do COFINS
    var $vOutro;    // W15 - outras despesas acessórias
    var $vNF;       // W16 - valor total da NFe

    function __construct() {
    }

    function get_xml($dom) {
        $W02 = $dom->appendChild($dom->createElement('ICMSTot'));
        $W03 = $W02->appendChild($dom->createElement('vBC',     number_format($this->vBC, 2, ".", "")));
        $W04 = $W02->appendChild($dom->createElement('vICMS',   number_format($this->vICMS, 2, ".", "")));
        $W05 = $W02->appendChild($dom->createElement('vBCST',   number_format($this->vBCST, 2, ".", "")));
        $W06 = $W02->appendChild($dom->createElement('vST',     number_format($this->vST, 2, ".", "")));
        $W07 = $W02->appendChild($dom->createElement('vProd',   number_format($this->vProd, 2, ".", "")));
        $W08 = $W02->appendChild($dom->createElement('vFrete',  number_format($this->vFrete, 2, ".", "")));
        $W09 = $W02->appendChild($dom->createElement('vSeg',    number_format($this->vSeg, 2, ".", "")));
        $W10 = $W02->appendChild($dom->createElement('vDesc',   number_format($this->vDesc, 2, ".", "")));
        $W11 = $W02->appendChild($dom->createElement('vII',     number_format($this->vII, 2, ".", "")));
        $W12 = $W02->appendChild($dom->createElement('vIPI',    number_format($this->vIPI, 2, ".", "")));
        $W13 = $W02->appendChild($dom->createElement('vPIS',    number_format($this->vPIS, 2, ".", "")));
        $W14 = $W02->appendChild($dom->createElement('vCOFINS', number_format($this->vCOFINS, 2, ".", "")));
        $W15 = $W02->appendChild($dom->createElement('vOutro',  number_format($this->vOutro, 2, ".", "")));
        $W16 = $W02->appendChild($dom->createElement('vNF',     number_format($this->vNF, 2, ".", "")));
        return $W02;
    }

    function insere($con, $total_id) {
        $sql = "INSERT INTO ICMSTot VALUES (NULL";
        $sql.= ", ".$con->quote($total_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vICMS);
        $sql.= ", ".$con->quote($this->vBCST);
        $sql.= ", ".$con->quote($this->vST);
        $sql.= ", ".$con->quote($this->vProd);
        $sql.= ", ".$con->quote($this->vFrete);
        $sql.= ", ".$con->quote($this->vSeg);
        $sql.= ", ".$con->quote($this->vDesc);
        $sql.= ", ".$con->quote($this->vII);
        $sql.= ", ".$con->quote($this->vIPI);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ", ".$con->quote($this->vOutro);
        $sql.= ", ".$con->quote($this->vNF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ICMSTot: '.$qry->getMessage());
            return false;
        } else {
            $ICMSTot_id = $con->lastInsertID("ICMSTot", "ICMSTot_id");
        }
    }
}
