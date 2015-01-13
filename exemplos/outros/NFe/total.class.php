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
 * @name      total
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * total
 * Nível 2 :: W01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_total {
    var $ICMSTot;   // W02 - grupo de valores totais referentes ao ICMS
    var $ISSQNtot;  // W17 - grupo de valores totais referentes ao ISSQN
    var $retTrib;   // W23 - grupo de retenções de tributos

    function __construct() {
        $this->ICMSTot  = new ICMSTot;
        $this->ISSQNtot = null;
        $this->retTrib  = null;
    }

    function add_ISSQNtot($obj_ISSQNtot) {
        if (!$this->ISSQNtot) {
            $this->ISSQNtot = $obj_ISSQNtot;
            return true;
        } else {
            return false;
        }
    }

    function add_retTrib($obj_retTrib) {
        if (!$this->retTrib) {
            $this->retTrib = $obj_retTrib;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $W01 = $dom->appendChild($dom->createElement('total'));
        $W02 = $W01->appendChild($this->ICMSTot->get_xml($dom));
        $W17 = (is_object($this->ISSQNtot)) ? $W01->appendChild($this->ISSQNtot->get_xml($dom)) : null;
        $W23 = (is_object($this->retTrib)) ? $W01->appendChild($this->retTrib->get_xml($dom)) : null;
        return $W01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO total VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro total: '.$qry->getMessage());
            return false;
        } else {
            $total_id = $con->lastInsertID("total", "total_id");
            $this->ICMSTot->insere($con, $total_id);
            (is_object($this->ISSQNtot)) ? $this->ISSQNtot->insere($con, $total_id) : null;
            (is_object($this->retTrib)) ? $this->retTrib->insere($con, $total_id) : null;
        }
    }
}
