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
 * @name      reboque
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * reboque
 * Nível 3 :: X22
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_reboque {
    var $placa;     // X23 - placa do veículo
    var $UF;        // X24 - sigla da UF
    var $RNTC;      // X25 - registro nacional de transportador de carga (ANTT)

    function __construct() {
    }

    function get_xml($dom) {
        $X22 = $dom->appendChild($dom->createElement('reboque'));
        $X23 = $X22->appendChild($dom->createElement('placa',   $this->placa));
        $X24 = $X22->appendChild($dom->createElement('UF',      $this->UF));
        $X25 = (!empty($this->RNTC)) ? $X22->appendChild($dom->createElement('RNTC', $this->RNTC)) : null;
        return $X22;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO reboque VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->placa);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->RNTC);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro reboque: '.$qry->getMessage());
            return false;
        } else {
            $reboque_id = $con->lastInsertID("reboque", "reboque_id");
        }
    }
}
