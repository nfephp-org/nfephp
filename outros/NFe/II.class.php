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
 * @name      II
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * II
 * Nível 4 :: P01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_II {
    var $vBC;       // P02 - valor da BC do imposto de importação
    var $vDespAdu;  // P03 - valor das despesas aduaneiras
    var $vII;       // P04 - valor do imposto de importação
    var $vIOF;      // P05 - valor do imposto sobre operações financeiras

    function __construct() {
    }

    function get_xml($dom) {
        $P01 = $dom->appendChild($dom->createElement('II'));
        $P02 = $P01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
        $P03 = $P01->appendChild($dom->createElement('vDespAdu',    number_format($this->vDespAdu, 2, ".", "")));
        $P04 = $P01->appendChild($dom->createElement('vII',         number_format($this->vII, 2, ".", "")));
        $P05 = $P01->appendChild($dom->createElement('vIOF',        number_format($this->vIOF, 2, ".", "")));
        return $P01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO II VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vDespAdu);
        $sql.= ", ".$con->quote($this->vII);
        $sql.= ", ".$con->quote($this->vIOF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro II: '.$qry->getMessage());
            return false;
        } else {
            $II_id = $con->lastInsertID("II", "II_id");
        }
    }
}
