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
 * @name      dup
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * dup
 * Nível 3 :: Y07
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_dup {
    var $nDup;      // Y08 - número da duplicata
    var $dVenc;     // Y09 - data de vencimento
    var $vDup;      // Y10 - valor da duplicata

    function __construct() {
    }

    function get_xml($dom) {
        $Y07 = $dom->appendChild($dom->createElement('dup'));
        $Y08 = (isset($this->nDup))  ? $Y07->appendChild($dom->createElement('nDup',    $this->nDup))    : null;
        $Y09 = (!empty($this->dVenc)) ? $Y07->appendChild($dom->createElement('dVenc',   $this->dVenc))   : null;
        $Y10 = ($this->vDup > 0)      ? $Y07->appendChild($dom->createElement('vDup',    number_format($this->vDup, 2, ".", "")))    : null;
        return $Y07;
    }

    function insere($con, $cobr_id) {
        $sql = "INSERT INTO dup VALUES (NULL";
        $sql.= ", ".$con->quote($cobr_id);
        $sql.= ", ".$con->quote($this->nDup);
        $sql.= ", ".$con->quote($this->dVenc);
        $sql.= ", ".$con->quote($this->vDup);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro dup: '.$qry->getMessage());
            return false;
        } else {
            $dup_id = $con->lastInsertID("dup", "dup_id");
        }
    }
}
