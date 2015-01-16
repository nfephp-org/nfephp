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
 * @name      dest
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * dest
 * Nível 2 :: E01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_dest {
    var $CNPJ;      // E02 - CNPJ do emitente
    var $CPF;       // E02a- CPF do remetente
    var $xNome;     // E03 - razão social ou nome do emitente
    var $enderDest; // E05 - grupo do endereço do emitente
    var $IE;        // E17 - IE
    var $ISUF;      // E18 - Inscrição na SUFRAMA

    function __construct() {
        $this->enderDest = new enderDest;
    }

    function get_xml($dom) {
        $E01 = $dom->appendChild($dom->createElement('dest'));
        $E02 = (empty($this->CPF)) ? $E01->appendChild($dom->createElement('CNPJ', sprintf("%014s", $this->CNPJ))) : $E01->appendChild($dom->createElement('CPF', sprintf("%011s", $this->CPF)));
        //E03 - ou exclusivo com E02
        $E04 = $E01->appendChild($dom->createElement('xNome',       $this->xNome));
        $E05 = $E01->appendChild($this->enderDest->get_xml($dom));
        $E17 = $E01->appendChild($dom->createElement('IE',          $this->IE));
        $E18 = (!empty($this->ISUF)) ? $E01->appendChild($dom->createElement('ISUF',    $this->ISUF)) : '';
        return $E01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO dest VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->CPF);
        $sql.= ", ".$con->quote($this->xNome);
        $sql.= ", ".$con->quote($this->IE);
        $sql.= ", ".$con->quote($this->ISUF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro dest: '.$qry->getMessage());
            return false;
        } else {
            $dest_id = $con->lastInsertID("dest", "dest_id");
            $this->enderDest->insere($con, $dest_id);
        }
    }
}
