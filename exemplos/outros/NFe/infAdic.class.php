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
 * @name      infAdic
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * infAdic
 * Nível 2 :: Z01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_infAdic {
    var $infAdFisco;    // Z02 - informações adicionais de interesse do fisco
    var $infCpl;        // Z03 - informações de interesse do contribuinte
    var $obsCont;       // Z04 - grupo de campo de uso livre do contribuinte
    var $obsFisco;      // Z07 - grupo de campo de uso livre do fisco
    var $procRef;       // Z10 - grupo do processo

    function __construct() {
        $this->obsCont  = array();
        $this->obsFisco = array();
        $this->procRef  = array();
    }

    function add_obsCont($obj_obsCont) {
        if (count($this->obsCont) < 10) {
            $this->obsCont[] = $obj_obsCont;
            return true;
        } else {
            return false;
        }
    }

    function add_obsFisco($obj_obsFisco) {
        if (count($this->obsFisco) < 10) {
            $this->obsFisco[] = $obj_obsFisco;
            return true;
        } else {
            return false;
        }
    }

    function add_procRef($obj_procRef) {
        $this->procRef[] = $obj_procRef;
        return true;
    }

    function get_xml($dom) {
        $Z01 = $dom->appendChild($dom->createElement('infAdic'));
        $Z02 = (!empty($this->infAdFisco))  ? $Z01->appendChild($dom->createElement('infAdFisco',  $this->infAdFisco)) : null;
        $Z03 = (!empty($this->infCpl))      ? $Z01->appendChild($dom->createElement('infCpl',      $this->infCpl))     : null;
        for ($i=0; $i<count($this->obsCont); $i++) {
            $Z04 = $Z01->appendChild($this->obsCont[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->obsFisco); $i++) {
            $Z07 = $Z01->appendChild($this->obsFisco[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->procRef); $i++) {
            $Z10 = $Z01->appendChild($this->procRef[$i]->get_xml($dom));
        }
        return $Z01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO infAdic VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->infAdFisco);
        $sql.= ", ".$con->quote($this->infCpl);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro infAdic: '.$qry->getMessage());
            return false;
        } else {
            $infAdic_id = $con->lastInsertID("infAdic", "infAdic_id");

            for ($i=0; $i<count($this->obsCont); $i++) {
                $this->obsCont[$i]->insere($con, $infAdic_id);
            }
            for ($i=0; $i<count($this->obsFisco); $i++) {
                $this->obsFisco[$i]->insere($con, $infAdic_id);
            }
            for ($i=0; $i<count($this->procRef); $i++) {
                $this->procRef[$i]->insere($con, $infAdic_id);
            }
        }
    }
}
