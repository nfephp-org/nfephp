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
 * @name      obsCont
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * obsCont
 * Nível 3 :: Z04
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_obsCont {
    var $xCampo;    // Z05 - identificação do campo
    var $xTexto;    // Z06 - conteúdo do campo

    function __construct() {
    }

    function get_xml($dom) {
        $Z04 = $dom->appendChild($dom->createElement('obsCont'));
        $Z05 = $Z04->appendChild($dom->createElement('xCampo', $this->xCampo));
        $Z06 = $Z04->appendChild($dom->createElement('xTexto', $this->xTexto));
        return $Z04;
    }

    function insere($con, $infAdic_id) {
        $sql = "INSERT INTO obsCont VALUES (NULL";
        $sql.= ", ".$con->quote($infAdic_id);
        $sql.= ", ".$con->quote($this->xCampo);
        $sql.= ", ".$con->quote($this->xTexto);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro obsCont: '.$qry->getMessage());
            return false;
        } else {
            $obsCont_id = $con->lastInsertID("obsCont", "obsCont_id");
        }
    }
}
