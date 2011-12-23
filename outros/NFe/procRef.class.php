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
 * @name      procRef
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * procRef
 * Nível 3 :: Z10
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_procRef {
    var $nProc;     // Z11 - identificador do processo ou ato concessório
    var $indProc;   // Z12 - indicador da origem do processo

    function __construct() {
    }

    function get_xml($dom) {
        $Z10 = $dom->appendChild($dom->createElement('procRef'));
        $Z11 = $Z10->appendChild($dom->createElement('nProc',   $this->nProc));
        $Z12 = $Z10->appendChild($dom->createElement('indProc', $this->indProc));
        return $Z10;
    }

    function insere($con, $infAdic_id) {
        $sql = "INSERT INTO procRef VALUES (NULL";
        $sql.= ", ".$con->quote($infAdic_id);
        $sql.= ", ".$con->quote($this->nProc);
        $sql.= ", ".$con->quote($this->indProc);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro procRef: '.$qry->getMessage());
            return false;
        } else {
            $procRef_id = $con->lastInsertID("procRef", "procRef_id");
        }
    }
}
