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
 * @name      avulsa
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * avulsa
 * Nível 2 :: D01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_avulsa {
    var $CNPJ;      // D02 - CNPJ do órgão emitente
    var $xOrgao;    // D03 - órgão emitente
    var $matr;      // D04 - matrícula do agente
    var $xAgente;   // D05 - nome do agente
    var $fone;      // D06 - telefone
    var $UF;        // D07 - sigla da UF
    var $nDAR;      // D08 - número do documento de arrecadação de receita
    var $dEmi;      // D09 - data de emissão do documento de arrecadação
    var $vDAR;      // D10 - valor total no documento de arrecadação de receita
    var $repEmi;    // D11 - repartição fiscal emitente
    var $dPag;      // D12 - data de pagamento do documento de arrecadação

    function __construct() {
    }

    function get_xml($dom) {
        $D01 = $dom->appendChild($dom->createElement('avulsa'));
        $D02 = $D01->appendChild($dom->createElement('CNPJ',    sprintf("%014s", $this->CNPJ)));
        $D03 = $D01->appendChild($dom->createElement('xOrgao',  $this->xOrgao));
        $D04 = $D01->appendChild($dom->createElement('matr',    $this->matr));
        $D05 = $D01->appendChild($dom->createElement('xAgente', $this->xAgente));
        $D06 = $D01->appendChild($dom->createElement('fone',    $this->fone));
        $D07 = $D01->appendChild($dom->createElement('UF',      $this->UF));
        $D08 = $D01->appendChild($dom->createElement('nDAR',    $this->nDAR));
        $D09 = $D01->appendChild($dom->createElement('dEmi',    $this->dEmi));
        $D10 = $D01->appendChild($dom->createElement('vDAR',    number_format($this->vDAR, 2, ".", "")));
        $D11 = $D01->appendChild($dom->createElement('repEmi',  $this->repEmi));
        $D12 = (!empty($this->dPag)) ? $D01->appendChild($dom->createElement('dPag', $this->dPag)) : '';
        return $D01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO avulsa VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->xOrgao);
        $sql.= ", ".$con->quote($this->matr);
        $sql.= ", ".$con->quote($this->xAgente);
        $sql.= ", ".$con->quote($this->fone);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->nDAR);
        $sql.= ", ".$con->quote($this->dEmi);
        $sql.= ", ".$con->quote($this->vDAR);
        $sql.= ", ".$con->quote($this->repEmi);
        $sql.= ", ".$con->quote($this->dPag);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro avulsa: '.$qry->getMessage());
            return false;
        } else {
            $avulsa_id = $con->lastInsertID("avulsa", "avulsa_id");
        }
    }
}
