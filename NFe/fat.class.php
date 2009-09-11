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
 * @name      fat
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * fat
 * Nível 3 :: Y02
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_fat {
    var $nFat;      // Y03 - número da fatura
    var $vOrig;     // Y04 - valor original da fatura
    var $vDesc;     // Y05 - valor do desconto
    var $vLiq;      // Y06 - valor líquido da fatura

    function __construct() {
    }

    function get_xml($dom) {
        $Y02 = $dom->appendChild($dom->createElement('fat'));
        $Y03 = (!isset($this->nFat)) ? $Y02->appendChild($dom->createElement('nFat',    $this->nFat))    : null;
        $Y04 = ($this->vOrig > 0)    ? $Y02->appendChild($dom->createElement('vOrig',   number_format($this->vOrig, 2, ".", "")))   : null;
        $Y05 = ($this->vDesc > 0)    ? $Y02->appendChild($dom->createElement('vDesc',   number_format($this->vDesc, 2, ".", "")))   : null;
        $Y06 = ($this->vLiq > 0)     ? $Y02->appendChild($dom->createElement('vLiq',    number_format($this->vLiq, 2, ".", "")))    : null;
        return $Y02;
    }

    function insere($con, $cobr_id) {
        $sql = "INSERT INTO fat VALUES (NULL";
        $sql.= ", ".$con->quote($cobr_id);
        $sql.= ", ".$con->quote($this->nFat);
        $sql.= ", ".$con->quote($this->vOrig);
        $sql.= ", ".$con->quote($this->vDesc);
        $sql.= ", ".$con->quote($this->vLiq);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro fat: '.$qry->getMessage());
            return false;
        } else {
            $fat_id = $con->lastInsertID("fat", "fat_id");
        }
    }
}
