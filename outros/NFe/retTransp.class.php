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
 * @name      retTransp
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retTransp
 * Nível 3 :: X11
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_retTransp {
    var $vServ;     // X12 - valor do serviço
    var $vBCRet;    // X13 - BC da retenção do ICMS
    var $pICMSRet;  // X14 - alíquota da retenção
    var $vICMSRet;  // X15 - valor do ICMS retido
    var $CFOP;      // X16 - CFOP
    var $cMunFG;    // X17 - código do município do fato gerador do ICMS do transp

    function __construct() {
    }

    function get_xml($dom) {
        $X11 = $dom->appendChild($dom->createElement('retTransp'));
        $X12 = $X11->appendChild($dom->createElement('vServ',    number_format($this->vServ, 2, ".", "")));
        $X13 = $X11->appendChild($dom->createElement('vBCRet',   number_format($this->vBCRet, 2, ".", "")));
        $X14 = $X11->appendChild($dom->createElement('pICMSRet', number_format($this->pICMSRet, 2, ".", "")));
        $X15 = $X11->appendChild($dom->createElement('vICMSRet', number_format($this->vICMSRet, 2, ".", "")));
        $X16 = $X11->appendChild($dom->createElement('CFOP',     $this->CFOP));
        $X17 = $X11->appendChild($dom->createElement('cMunFG',   $this->cMunFG));
        return $X11;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO retTransp VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->vServ);
        $sql.= ", ".$con->quote($this->vBCRet);
        $sql.= ", ".$con->quote($this->pICMSRet);
        $sql.= ", ".$con->quote($this->vICMSRet);
        $sql.= ", ".$con->quote($this->CFOP);
        $sql.= ", ".$con->quote($this->cMunFG);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro retTransp: '.$qry->getMessage());
            return false;
        } else {
            $retTransp_id = $con->lastInsertID("retTransp", "retTransp_id");
        }
    }
}
