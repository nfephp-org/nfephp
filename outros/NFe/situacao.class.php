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
 * @name      situacao
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * situacao
 * Nível 0 :: 
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_situacao {
    var $situacao_id;
    var $descricao;

    function __construct() {
        global $con;
        $this->fetch($con, 5); // 5 = em digitação
    }

    function fetch($con, $situacao_id) {
        $sql = "SELECT * FROM situacao WHERE situacao_id = ".$situacao_id;
        $qry = $con->query($sql);
        if (!MDB2::isError($qry)) {
            $row = $qry->fetchRow(MDB2_FETCHMODE_ASSOC);
            $this->situacao_id = $row['situacao_id'];
            $this->descricao   = $row['descricao'];
        }
    }
}
