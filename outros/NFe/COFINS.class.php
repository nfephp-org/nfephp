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
 * @name      COFINS
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * COFINS
 * Nível 4 :: S01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_COFINS {
    var $CST;       // S06 - código de situação tributária do COFINS
    var $vBC;       // S07 - valor da BC do COFINS
    var $pCOFINS;   // S08 - alíquota do COFINS (em percentual)
    var $qBCProd;   // S09 - quantidade vendida
    var $vAliqProd; // S10 - alíquota do COFINS (em reais)
    var $vCOFINS;   // S11 - valor do COFINS

    function __construct() {
    }

    function get_xml($dom) {
        $S01 = $dom->appendChild($dom->createElement('COFINS'));

        switch ($this->CST) {

            case '01' :
            case '02' :
                $S02 = $S01->appendChild($dom->createElement('COFINSAliq'));
                $S06 = $S02->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $S07 = $S02->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $S08 = $S02->appendChild($dom->createElement('pCOFINS',     number_format($this->pCOFINS, 2, ".", "")));
                $S11 = $S02->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
                break;

            case '03' :
                $S03 = $S01->appendChild($dom->createElement('COFINSQtde'));
                $S06 = $S03->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $S09 = $S03->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                $S10 = $S03->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                $S11 = $S03->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
                break;

            case '04' :
            case '06' :
            case '07' :
            case '08' :
            case '09' :
                $S04 = $S01->appendChild($dom->createElement('COFINSNT'));
                $S06 = $S04->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                break;

            case '99' :
                $S05 = $S01->appendChild($dom->createElement('COFINSOutr'));
                $S06 = $S05->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                if (isset($this->vBC) && isset($this->pCOFINS)) {
                    $S07 = $S05->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                    $S08 = $S05->appendChild($dom->createElement('pCOFINS',     number_format($this->pCOFINS, 2, ".", "")));
                } else {
                    $S09 = $S05->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                    $S10 = $S05->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                }
                $S11 = $S05->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
                break;

        } // fim switch

        return $S01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO COFINS VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pCOFINS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro COFINS: '.$qry->getMessage());
            return false;
        } else {
            $COFINS_id = $con->lastInsertID("COFINS", "COFINS_id");
        }
    }
}
