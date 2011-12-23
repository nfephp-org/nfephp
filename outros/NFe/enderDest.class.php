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
 * @name      enderDest
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * enderDest
 * Nível 3 :: E05
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_enderDest {
    var $xLgr;      // E06 - logradouro do destinatario
    var $nro;       // E07 - número do destinatario
    var $xCpl;      // E08 - complemento do destinatario
    var $xBairro;   // E09 - bairro do destinatario
    var $cMun;      // E10 - código do município do destinatario
    var $xMun;      // E11 - nome do município do destinatario
    var $UF;        // E12 - sigla da UF do destinatario
    var $CEP;       // E13 - código do CEP do destinatario
    var $cPais;     // E14 - código do País (1058 - Brasil) do destinatario
    var $xPais;     // E15 - nome do País (Brasil ou BRASIL) do destinatario
    var $fone;      // E16 - telefone do destinatario

    function __construct() {
        $this->cPais    = 1058;
        $this->xPais    = "BRASIL";
    }

    function get_xml($dom) {
        $E05 = $dom->appendChild($dom->createElement('enderDest'));
        $E06 = $E05->appendChild($dom->createElement('xLgr',    $this->xLgr));
        $E07 = $E05->appendChild($dom->createElement('nro',     $this->nro));
        $E08 = (!empty($this->xCpl))  ? $E05->appendChild($dom->createElement('xCpl',    $this->xCpl)) : null;
        $E09 = $E05->appendChild($dom->createElement('xBairro', $this->xBairro));
        $E10 = $E05->appendChild($dom->createElement('cMun',    $this->cMun));
        $E11 = $E05->appendChild($dom->createElement('xMun',    $this->xMun));
        $E12 = $E05->appendChild($dom->createElement('UF',      $this->UF));
        $E13 = (!empty($this->CEP))   ? $E05->appendChild($dom->createElement('CEP',     sprintf("%08s", $this->CEP)))   : null;
        $E14 = (!empty($this->cPais)) ? $E05->appendChild($dom->createElement('cPais',   $this->cPais)) : null;
        $E15 = (!empty($this->xPais)) ? $E05->appendChild($dom->createElement('xPais',   $this->xPais)) : null;
        $E16 = (!empty($this->fone))  ? $E05->appendChild($dom->createElement('fone',    $this->fone))  : null;
        return $E05;
    }

    function insere($con, $dest_id) {
        $sql = "INSERT INTO enderDest VALUES (NULL";
        $sql.= ", ".$con->quote($dest_id);
        $sql.= ", ".$con->quote($this->xLgr);
        $sql.= ", ".$con->quote($this->nro);
        $sql.= ", ".$con->quote($this->xCpl);
        $sql.= ", ".$con->quote($this->xBairro);
        $sql.= ", ".$con->quote($this->cMun);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->CEP);
        $sql.= ", ".$con->quote($this->cPais);
        $sql.= ", ".$con->quote($this->xPais);
        $sql.= ", ".$con->quote($this->fone);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro enderDest: '.$qry->getMessage());
            return false;
        } else {
            $enderDest_id = $con->lastInsertID("enderDest", "enderDest_id");
        }
    }
}
