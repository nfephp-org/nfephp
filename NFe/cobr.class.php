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
 * @name      cobr
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * cobr
 * Nível 2 :: Y01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_cobr {
    var $fat;       // Y02 - grupo de fatura
    var $dup;       // Y07 - grupo de duplicata

    function __construct() {
        $this->fat = array();
        $this->dup = array();
    }

    function add_fat($obj_fat) {
        $this->fat = $obj_fat;
        return true;
    }

    function add_dup($obj_dup) {
        $this->dup[] = $obj_dup;
        return true;
    }

    function get_xml($dom) {
        $Y01 = $dom->appendChild($dom->createElement('cobr'));
        $Y02 = (is_object($this->fat)) ? $Y01->appendChild($this->fat->get_xml($dom)) : null;
        for ($i=0; $i<count($this->dup); $i++) {
            $Y07 = $Y01->appendChild($this->dup[$i]->get_xml($dom));
        }
        return $Y01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO cobr VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro cobr: '.$qry->getMessage());
            return false;
        } else {
            $cobr_id = $con->lastInsertID("cobr", "cobr_id");
            (is_object($this->fat)) ? $this->fat->insere($con, $cobr_id) : null;
            for ($i=0; $i<count($this->dup); $i++) {
                $this->dup[$i]->insere($con, $cobr_id);
            }
        }
    }
}
