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
 * @name      imposto
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * imposto
 * Nível 3 :: M01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_imposto {
    var $ICMS;      // N01 - grupo de ICMS da operação própria e ST
    var $IPI;       // O01 - grupo de IPI
    var $II;        // P01 - grupo de imposto de importação
    var $PIS;       // Q01 - grupo do PIS
    var $PISST;     // R01 - grupo de PIS substituição tributária
    var $COFINS;    // S01 - grupo de COFINS
    var $COFINSST;  // T01 - grupo de COFINS substituição tributária
    var $ISSQN;     // U01 - grupo do ISSQN

    function __construct() {
        $this->ICMS     = new ICMS;
        $this->IPI      = null;
        $this->II       = null;
        $this->PIS      = new PIS;
        $this->PISST    = null;
        $this->COFINS   = new COFINS;
        $this->COFINSST = null;
        $this->ISSQN    = null;
    }

    function add_IPI($obj_IPI) {
        if (!$this->IPI) {
            $this->IPI = $obj_IPI;
            return true;
        } else {
            return false;
        }
    }

    function add_II($obj_II) {
        if (!$this->II) {
            $this->II = $obj_II;
            return true;
        } else {
            return false;
        }
    }

    function add_PISST($obj_PISST) {
        if (!$this->PISST) {
            $this->PISST = $obj_PISST;
            return true;
        } else {
            return false;
        }
    }

    function add_COFINSST($obj_COFINSST) {
        if (!$this->COFINSST) {
            $this->COFINSST = $obj_COFINSST;
            return true;
        } else {
            return false;
        }
    }

    function add_ISSQN($obj_ISSQN) {
        if (!$this->ISSQN) {
            $this->ISSQN = $obj_ISSQN;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $M01 = $dom->appendChild($dom->createElement('imposto'));
        $N01 = $M01->appendChild($this->ICMS->get_xml($dom));
        $O01 = (is_object($this->IPI)) ? $M01->appendChild($this->IPI->get_xml($dom)) : null;
        $P01 = (is_object($this->II)) ? $M01->appendChild($this->II->get_xml($dom)) : null;
        $Q01 = $M01->appendChild($this->PIS->get_xml($dom));
        $R01 = (is_object($this->PISST)) ? $M01->appendChild($this->PISST->get_xml($dom)) : null;
        $S01 = $M01->appendChild($this->COFINS->get_xml($dom));
        $T01 = (is_object($this->COFINSST)) ? $M01->appendChild($this->COFINSST->get_xml($dom)) : null;
        $U01 = (is_object($this->ISSQN)) ? $M01->appendChild($this->ISSQN->get_xml($dom)) : null;
        return $M01;
    }

    function insere($con, $det_id) {
        $sql = "INSERT INTO imposto VALUES (NULL";
        $sql.= ", ".$con->quote($det_id);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro imposto: '.$qry->getMessage());
            return false;
        } else {
            $imposto_id = $con->lastInsertID("imposto", "imposto_id");
            $this->ICMS->insere($con, $imposto_id);
            $this->PIS->insere($con, $imposto_id);
            $this->COFINS->insere($con, $imposto_id);
            (is_object($this->IPI)) ? $this->IPI->insere($con, $imposto_id) : null;
            (is_object($this->II)) ? $this->II->insere($con, $imposto_id) : null;
            (is_object($this->PISST)) ? $this->PISST->insere($con, $imposto_id) : null;
            (is_object($this->COFINSST)) ? $this->COFINSST->insere($con, $imposto_id) : null;
            (is_object($this->ISSQN)) ? $this->ISSQN->insere($con, $imposto_id) : null;
        }
    }
}
