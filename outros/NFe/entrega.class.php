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
 * @name      entrega
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * entrega
 * Nível 2 :: G01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_entrega {
    var $CNPJ;      // G02 - CNPJ  do local da entrega
    var $xLgr;      // G03 - logradouro do local da entrega
    var $nro;       // G04 - número do local da entrega
    var $xCpl;      // G05 - complemento do local da entrega
    var $xBairro;   // G06 - bairro do local da entrega
    var $cMun;      // G07 - código do município do local da entrega
    var $xMun;      // G08 - nome do município do local da entrega
    var $UF;        // G09 - sigla da UF do local da entrega

    function __construct() {
    }

    function get_xml($dom) {
        $G01 = $dom->appendChild($dom->createElement('entrega'));
        $G02 = $G01->appendChild($dom->createElement('CNPJ',       sprintf("%014s", $this->CNPJ)));
        $G03 = $G01->appendChild($dom->createElement('xLgr',       $this->xLgr));
        $G04 = $G01->appendChild($dom->createElement('nro',        $this->nro));
        $G05 = (!empty($this->xCpl)) ? $G01->appendChild($dom->createElement('xCpl',       $this->xCpl)) : '';
        $G06 = $G01->appendChild($dom->createElement('xBairro',    $this->xBairro));
        $G07 = $G01->appendChild($dom->createElement('cMun',       $this->cMun));
        $G08 = $G01->appendChild($dom->createElement('xMun',       $this->xMun));
        $G09 = $G01->appendChild($dom->createElement('UF',         $this->UF));
        return $G01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO entrega VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->xLgr);
        $sql.= ", ".$con->quote($this->nro);
        $sql.= ", ".$con->quote($this->xCpl);
        $sql.= ", ".$con->quote($this->xBairro);
        $sql.= ", ".$con->quote($this->cMun);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro entrega: '.$qry->getMessage());
            return false;
        } else {
            $entrega_id = $con->lastInsertID("entrega", "entrega_id");
        }
    }
}
