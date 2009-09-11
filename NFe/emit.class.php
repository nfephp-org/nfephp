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
 * @name      emit
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * emit
 * Nível 2 :: C01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_emit {
    var $CNPJ;      // C02 - CNPJ do emitente
    var $CPF;       // C02a- CPF do remetente
    var $xNome;     // C03 - razão social ou nome do emitente
    var $xFant;     // C04 - nome fantasia
    var $enderEmit; // C05 - grupo do endereço do emitente
    var $IE;        // C17 - IE
    var $IEST;      // C18 - IE do substituto tributário
    var $IM;        // C19 - Inscrição Municipal
    var $CNAE;      // C20 - CNAE fiscal

    function __construct() {
        $this->enderEmit = new enderEmit;
    }

    function get_xml($dom) {
        $C01 = $dom->appendChild($dom->createElement('emit'));
        $C02 = (empty($this->CPF)) ? $C01->appendChild($dom->createElement('CNPJ', sprintf("%014s", $this->CNPJ))) : $C01->appendChild($dom->createElement('CPF', sprintf("%011s", $this->CPF)));
        //C02a - ou exclusivo com C02
        $C03 = $C01->appendChild($dom->createElement('xNome',       $this->xNome));
        $C04 = $C01->appendChild($dom->createElement('xFant',       $this->xFant));
        $C05 = $C01->appendChild($this->enderEmit->get_xml($dom));
        $C17 = $C01->appendChild($dom->createElement('IE',          $this->IE));
        $C18 = (!empty($this->IEST)) ? $C01->appendChild($dom->createElement('IEST',    $this->IEST)) : '';
        $C19 = (!empty($this->IM)) ? $C01->appendChild($dom->createElement('IM',        $this->IM)) : '';
        $C20 = (!empty($this->CNAE) && !empty($this->IM)) ? $C01->appendChild($dom->createElement('CNAE',    $this->CNAE)) : '';
        return $C01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO emit VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->CPF);
        $sql.= ", ".$con->quote($this->xNome);
        $sql.= ", ".$con->quote($this->xFant);
        $sql.= ", ".$con->quote($this->IE);
        $sql.= ", ".$con->quote($this->IEST);
        $sql.= ", ".$con->quote($this->IM);
        $sql.= ", ".$con->quote($this->CNAE);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro emit: '.$qry->getMessage());
            return false;
        } else {
            $emit_id = $con->lastInsertID("emit", "emit_id");
            $this->enderEmit->insere($con, $emit_id);
        }
    }
}
