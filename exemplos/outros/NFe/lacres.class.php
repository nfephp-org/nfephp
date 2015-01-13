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
 * @name      lacres
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * lacres
 * Nível 4 :: X33
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_lacres {
    var $nLacre;    // X33 - grupo de lacres

    function __construct() {
    }

    function get_xml($dom) {
        $X33 = $dom->appendChild($dom->createElement('lacres'));
        $X34 = $X33->appendChild($dom->createElement('nLacre', $this->nLacre));
        return $X33;
    }

    function insere($con, $vol_id) {
        $sql = "INSERT INTO lacres VALUES (NULL";
        $sql.= ", ".$con->quote($vol_id);
        $sql.= ", ".$con->quote($this->nLacre);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro lacres: '.$qry->getMessage());
            return false;
        } else {
            $lacres_id = $con->lastInsertID("lacres", "lacres_id");
        }
    }
}
