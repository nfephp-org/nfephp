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
 * @name      ISSQNtot
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * ISSQNtot
 * Nível 3 :: W17
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_ISSQNtot {
    var $vServ;     // W18 - valor total dos serviços não tributados pelo ICMS
    var $vBC;       // W19 - base de cálculo do ISS
    var $vISS;      // W20 - valor total do ISS
    var $vPIS;      // W21 - valor do PIS sobre serviços
    var $vCOFINS;   // W22 - valor do COFINS sobre serviços

    function __construct() {
    }

    function get_xml($dom) {
        $W17 = $dom->appendChild($dom->createElement('ISSQNtot'));
        $W18 = (isset($this->vServ))   ? $W17->appendChild($dom->createElement('vServ',   number_format($this->vServ, 2, ".", "")))   : null;
        $W19 = (isset($this->vBC))     ? $W17->appendChild($dom->createElement('vBC',     number_format($this->vBC, 2, ".", "")))     : null;
        $W20 = (isset($this->vISS))    ? $W17->appendChild($dom->createElement('vISS',    number_format($this->vISS, 2, ".", "")))    : null;
        $W21 = (isset($this->vPIS))    ? $W17->appendChild($dom->createElement('vPIS',    number_format($this->vPIS, 2, ".", "")))    : null;
        $W22 = (isset($this->vCOFINS)) ? $W17->appendChild($dom->createElement('vCOFINS', number_format($this->vCOFINS, 2, ".", ""))) : null;
        return $W17;
    }

    function insere($con, $total_id) {
        $sql = "INSERT INTO ISSQNtot VALUES (NULL";
        $sql.= ", ".$con->quote($total_id);
        $sql.= ", ".$con->quote($this->vServ);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vISS);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ISSQNtot: '.$qry->getMessage());
            return false;
        } else {
            $ISSQNtot_id = $con->lastInsertID("ISSQNtot", "ISSQNtot_id");
        }
    }
}
