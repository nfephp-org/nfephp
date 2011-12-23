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
 * @name      PISST
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * PISST
 * Nível 4 :: R01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_PISST {
    var $vBC;       // R02 - valor da BC do PIS
    var $pPIS;      // R03 - alíquota do PIS
    var $qBCProd;   // R04 - quantidade vendida
    var $vAliqProd; // R05 - alíquota do PIS (em reais)
    var $vPIS;      // R06 - valor do PIS

    function __construct() {
    }

    function get_xml($dom) {
        $R01 = $dom->appendChild($dom->createElement('PISST'));
        if (isset($this->vBC) && isset($this->pPIS)) {
            $R02 = $R01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
            $R03 = $R01->appendChild($dom->createElement('pPIS',        number_format($this->pPIS, 2, ".", "")));
        } else {
            $R04 = $R01->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
            $R05 = $R01->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
        }
        $R06 = $R01->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
        return $R01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO PISST VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pPIS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro PISST: '.$qry->getMessage());
            return false;
        } else {
            $PISST_id = $con->lastInsertID("PISST", "PISST_id");
        }
    }
}
