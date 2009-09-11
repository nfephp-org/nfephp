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
 * @name      compra
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * compra
 * Nível 2 :: ZB01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_compra {
    var $xNEmp; // ZB02 - nota de empenho
    var $xPed;  // ZB03 - pedido
    var $xCont; // ZB04 - contrato

    function __construct() {
    }

    function get_xml($dom) {
        $ZB01 = $dom->appendChild($dom->createElement('compra'));
        $ZB02 = (!empty($this->xNEmp))  ? $ZB01->appendChild($dom->createElement('xNEmp',  $this->xNEmp)) : null;
        $ZB02 = (!empty($this->xPed))   ? $ZB01->appendChild($dom->createElement('xPed',   $this->xPed))  : null;
        $ZB02 = (!empty($this->xCont))  ? $ZB01->appendChild($dom->createElement('xCont',  $this->xCont)) : null;
        return $ZB01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO compra VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->xNEmp);
        $sql.= ", ".$con->quote($this->xPed);
        $sql.= ", ".$con->quote($this->xCont);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro compra: '.$qry->getMessage());
            return false;
        } else {
            $compra_id = $con->lastInsertID("compra", "compra_id");
        }
    }
}
