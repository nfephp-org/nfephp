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
 * @name      ISSQN
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * ISSQN
 * Nível 4 :: U01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_ISSQN {
    var $vBC;       // U02 - valor da BC do ISSQN
    var $vAliq;     // U03 - alíquota do ISSQN
    var $vISSQN;    // U04 - valor do ISSQN
    var $cMunFG;    // U05 - código do município do fato gerador do ISSQN
    var $cListServ; // U06 - código da lista de serviços

    function __construct() {
    }

    function get_xml($dom) {
        $U01 = $dom->appendChild($dom->createElement('ISSQN'));
        $U02 = $U01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
        $U03 = $U01->appendChild($dom->createElement('vAliq',       number_format($this->vAliq, 2, ".", "")));
        $U04 = $U01->appendChild($dom->createElement('vISSQN',      number_format($this->vISSQN, 2, ".", "")));
        $U05 = $U01->appendChild($dom->createElement('cMunFG',      $this->cMunFG));
        $U06 = $U01->appendChild($dom->createElement('cListServ',   $this->cListServ));
        return $U01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO ISSQN VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vAliq);
        $sql.= ", ".$con->quote($this->vISSQN);
        $sql.= ", ".$con->quote($this->cMunFG);
        $sql.= ", ".$con->quote($this->cListServ);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ISSQN: '.$qry->getMessage());
            return false;
        } else {
            $ISSQN_id = $con->lastInsertID("ISSQN", "ISSQN_id");
        }
    }
}
