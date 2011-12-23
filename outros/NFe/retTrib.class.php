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
 * @name      retTrib
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retTrib
 * Nível 3 :: W23
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_retTrib {
    var $vRetPIS;       // W24 - valor retido do PIS
    var $vRetCOFINS;    // W25 - valor retido de COFINS
    var $vRetCSLL;      // W26 - valor retido de CSLL
    var $vBCIRRF;       // W27 - base de cálculo do IRRF
    var $vIRRF;         // W28 - valor retido do IRRF
    var $vBCRetPrev;    // W29 - base de cálculo da retenção da previdência 
    var $vRetPrev;      // W30 - valor da retenção da previdência social

    function __construct() {
    }

    function get_xml($dom) {
        $W23 = $dom->appendChild($dom->createElement('retTrib'));
        $W24 = (isset($this->vRetPIS))     ? $W23->appendChild($dom->createElement('vRetPIS',      number_format($this->vRetPIS, 2, ".", "")))      : null;
        $W25 = (isset($this->vRetCOFINS))  ? $W23->appendChild($dom->createElement('vRetCOFINS',   number_format($this->vRetCOFINS, 2, ".", "")))   : null;
        $W26 = (isset($this->vRetCSLL))    ? $W23->appendChild($dom->createElement('vRetCSLL',     number_format($this->vRetCSLL, 2, ".", "")))     : null;
        $W27 = (isset($this->vBCIRRF))     ? $W23->appendChild($dom->createElement('vBCIRRF',      number_format($this->vBCIRRF, 2, ".", "")))      : null;
        $W28 = (isset($this->vIRRF))       ? $W23->appendChild($dom->createElement('vIRRF',        number_format($this->vIRRF, 2, ".", "")))        : null;
        $W29 = (isset($this->vBCRetPrev))  ? $W23->appendChild($dom->createElement('vBCRetPrev',   number_format($this->vBCRetPrev, 2, ".", "")))   : null;
        $W30 = (isset($this->vRetPrev))    ? $W23->appendChild($dom->createElement('vRetPrev',     number_format($this->vRetPrev, 2, ".", "")))     : null;
        return $W23;
    }

    function insere($con, $total_id) {
        $sql = "INSERT INTO retTrib VALUES (NULL";
        $sql.= ", ".$con->quote($total_id);
        $sql.= ", ".$con->quote($this->vRetPIS);
        $sql.= ", ".$con->quote($this->vRetCOFINS);
        $sql.= ", ".$con->quote($this->vRetCSLL);
        $sql.= ", ".$con->quote($this->vBCIRRF);
        $sql.= ", ".$con->quote($this->vIRRF);
        $sql.= ", ".$con->quote($this->vBCRetPrev);
        $sql.= ", ".$con->quote($this->vRetPrev);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro retTrib: '.$qry->getMessage());
            return false;
        } else {
            $retTrib_id = $con->lastInsertID("retTrib", "retTrib_id");
        }
    }
}
