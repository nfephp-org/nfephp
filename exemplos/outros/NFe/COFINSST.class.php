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
 * @name      COFINSST
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * COFINSST
 * Nível 4 :: T01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_COFINSST {
    var $vBC;       // T02 - valor da BC do COFINS
    var $pCOFINS;   // T03 - alíquota do COFINS (em percentual)
    var $qBCProd;   // T04 - quantidade vendida
    var $vAliqProd; // T05 - alíquota do COFINS (em reias)
    var $vCOFINS;   // T06 - valor do COFINS

    function __construct() {
    }

    function get_xml($dom) {
        $T01 = $dom->appendChild($dom->createElement('COFINSST'));
        if (isset($this->vBC) && isset($this->pCOFINS)) {
            $T02 = $T01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
            $T03 = $T01->appendChild($dom->createElement('pCOFINS',     number_format($this->pCOFINS, 2, ".", "")));
        } else {
            $T04 = $T01->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
            $T05 = $T01->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
        }
        $T06 = $T01->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
        return $T01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO COFINSST VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pCOFINS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro COFINSST: '.$qry->getMessage());
            return false;
        } else {
            $COFINSST_id = $con->lastInsertID("COFINSST", "COFINSST_id");
        }
    }
}
