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
 * @name      PIS
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * PIS
 * Nível 4 :: Q01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_PIS {
    var $CST;       // Q06 - código de situação tributária do PIS
    var $vBC;       // Q07 - valor da BC do PIS
    var $pPIS;      // Q08 - alíquota do PIS
    var $vPIS;      // Q09 - valor do PIS
    var $qBCProd;   // Q10 - quantidade vendida
    var $vAliqProd; // Q11 - alíquota do PIS (em reais)

    function __construct() {
    }

    function get_xml($dom) {

        $Q01 = $dom->appendChild($dom->createElement('PIS'));

        switch ($this->CST) {

            case '01' :
            case '02' :
                $Q02 = $Q01->appendChild($dom->createElement('PISAliq'));
                $Q06 = $Q02->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $Q07 = $Q02->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $Q08 = $Q02->appendChild($dom->createElement('pPIS',        number_format($this->pPIS, 2, ".", "")));
                $Q09 = $Q02->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
                break;

            case '03' :
                $Q03 = $Q01->appendChild($dom->createElement('PISQtde'));
                $Q06 = $Q03->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $Q10 = $Q03->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                $Q11 = $Q03->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                $Q09 = $Q03->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
                break;

            case '04' :
            case '06' :
            case '07' :
            case '08' :
            case '09' :
                $Q04 = $Q01->appendChild($dom->createElement('PISNT'));
                $Q06 = $Q04->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                break;

            case '99' :
                $Q05 = $Q01->appendChild($dom->createElement('PISOutr'));
                $Q06 = $Q05->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                if (isset($this->vBC) && isset($this->pPIS)) {
                    $Q07 = $Q05->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                    $Q08 = $Q05->appendChild($dom->createElement('pPIS',        number_format($this->pPIS, 2, ".", "")));
                } else {
                    $Q10 = $Q05->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                    $Q11 = $Q05->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                }
                $Q09 = $Q05->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
                break;        

        } // fim switch

        return $Q01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO PIS VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pPIS);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro PIS: '.$qry->getMessage());
            return false;
        } else {
            $PIS_id = $con->lastInsertID("PIS", "PIS_id");
        }
    }
}
