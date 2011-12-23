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
 * @name      IPI
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * IPI
 * Nível 4 :: O01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_IPI {
    var $cIEnq;     // O02 - classe de IPI para cigarros e bebidas
    var $CNPJProd;  // O03 - CNPJ do produtor quando diferente do emitente
    var $cSelo;     // O04 - código do selo de controle do IPI
    var $qSelo;     // O05 - quantidade de selo de controle
    var $cEnq;      // O06 - código de enquadramento legal do IPI
    var $CST;       // O09 - código da situação tributária do IPI
    var $vBC;       // O10 - valor da BC do IPI
    var $qUnid;     // O11 - quantidade total na unidade padrão para tributação
    var $vUnid;     // O12 - valor por unidade tributável
    var $pIPI;      // O13 - alíquota do IPI
    var $vIPI;      // O14 - valor do IPI

    function __construct() {
    }

    function get_xml($dom) {

        $O01 = $dom->appendChild($dom->createElement('IPI'));
        $O02 = (!empty($this->cIEnq))    ? $O01->appendChild($dom->createElement('cIEnq',       $this->cIEnq))      : null;
        $O03 = (!empty($this->CNPJProd)) ? $O01->appendChild($dom->createElement('CNPJProd',    sprintf("%014s", $this->CNPJProd)))   : null;
        $O04 = (!empty($this->cSelo))    ? $O01->appendChild($dom->createElement('cSelo',       $this->cSelo))      : null;
        $O05 = (isset($this->qSelo))    ? $O01->appendChild($dom->createElement('qSelo',       $this->qSelo))      : null;
        $O06 = $O01->appendChild($dom->createElement('cEnq',        $this->cEnq));

        switch ($this->CST) {

            case '00' :
            case '49' :
            case '50' :
            case '99' :
                // O07 - grupo do CST 00,49,50 e 99
                $O07 = $O01->appendChild($dom->createElement('IPITrib'));
                $O09 = $O07->appendChild($dom->createElement('CST',     sprintf("%02d", $this->CST)));
                if (isset($this->vBC) && isset($this->pIPI)) {
                    $O10 = $O07->appendChild($dom->createElement('vBC',     number_format($this->vBC, 2, ".", "")));
                    $O13 = $O07->appendChild($dom->createElement('pIPI',    number_format($this->pIPI, 2, ".", "")));
                } else {
                    $O11 = $O07->appendChild($dom->createElement('qUnid',   number_format($this->qUnid, 4, ".", "")));
                    $O12 = $O07->appendChild($dom->createElement('vUnid',   number_format($this->vUnid, 4, ".", "")));
                }
                $O14 = $O07->appendChild($dom->createElement('vIPI',    number_format($this->vIPI, 2, ".", "")));
                break;

            case '01' :
            case '02' :
            case '03' :
            case '04' :
            case '05' :
            case '51' :
            case '52' :
            case '53' :
            case '54' :
            case '55' :
                // O08 - grupo do CST 01,02,03,04,05,51,52,53,54 e 55
                $O08 = $O01->appendChild($dom->createElement('IPINT'));
                $O09 = $O08->appendChild($dom->createElement('CST',     sprintf("%02d", $this->CST)));
                break;
        }

        return $O01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO IPI VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->cIEnq);
        $sql.= ", ".$con->quote($this->CNPJProd);
        $sql.= ", ".$con->quote($this->cSelo);
        $sql.= ", ".$con->quote($this->qSelo);
        $sql.= ", ".$con->quote($this->cEnq);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->qUnid);
        $sql.= ", ".$con->quote($this->vUnid);
        $sql.= ", ".$con->quote($this->pIPI);
        $sql.= ", ".$con->quote($this->vIPI);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro IPI: '.$qry->getMessage());
            return false;
        } else {
            $IPI_id = $con->lastInsertID("IPI", "IPI_id");
        }
    }
}
