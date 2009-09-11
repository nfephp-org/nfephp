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
 * @name      transp
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * transp
 * Nível 2 :: W01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_transp {
    var $modFrete;      // X02 - modalidade do frete
    var $transporta;    // X03 - grupo transportador
    var $retTransp;     // X11 - grupo de retenção do ICMS do transporte
    var $veicTransp;    // X18 - grupo veículo
    var $reboque;       // X22 - grupo reboque
    var $vol;           // X26 - grupo volumes

    function __construct() {
        $this->transporta   = null;
        $this->retTransp    = null;
        $this->veicTransp   = null;
        $this->reboque      = array();
        $this->vol          = array();
    }

    function add_transporta($obj_transporta) {
        if (!$this->transporta) {
            $this->transporta = $obj_transporta;
            return true;
        } else {
            return false;
        }
    }
    
    function add_retTransp($obj_retTransp) {
        if (!$this->retTransp) {
            $this->retTransp = $obj_retTransp;
            return true;
        } else {
            return false;
        }
    }
    
    function add_veicTransp($obj_veicTransp) {
        if (!$this->veicTransp) {
            $this->veicTransp = $obj_veicTransp;
            return true;
        } else {
            return false;
        }
    }
    
    function add_reboque($obj_reboque) {
        if (count($this->reboque) < 2) {
            $this->reboque[] = $obj_reboque;
            return true;
        } else {
            return false;
        }
    }

    function add_vol($obj_vol) {
        $this->vol[] = $obj_vol;
        return true;
    }

    function get_xml($dom) {
        $X01 = $dom->appendChild($dom->createElement('transp'));
        $X02 = $X01->appendChild($dom->createElement('modFrete', $this->modFrete));
        $X03 = (is_object($this->transporta)) ? $X01->appendChild($this->transporta->get_xml($dom)) : null;
        $X11 = (is_object($this->retTransp))  ? $X01->appendChild($this->retTransp->get_xml($dom))  : null;
        $X18 = (is_object($this->veicTransp)) ? $X01->appendChild($this->veicTransp->get_xml($dom)) : null;
        for ($i=0; $i<count($this->reboque); $i++) {
            $X22 = $X01->appendChild($this->reboque[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->vol); $i++) {
            $X26 = $X01->appendChild($this->vol[$i]->get_xml($dom));
        }
        return $X01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO transp VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->modFrete);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro transp: '.$qry->getMessage());
            return false;
        } else {
            $transp_id = $con->lastInsertID("transp", "transp_id");
            (is_object($this->transporta)) ? $this->transporta->insere($con, $transp_id) : null;
            (is_object($this->retTransp)) ? $this->retTransp->insere($con, $transp_id) : null;
            (is_object($this->veicTransp)) ? $this->veicTransp->insere($con, $transp_id) : null;
            for ($i=0; $i<count($this->reboque); $i++) {
                $this->reboque[$i]->insere($con, $transp_id);
            }
            for ($i=0; $i<count($this->vol); $i++) {
                $this->vol[$i]->insere($con, $transp_id);
            }
        }
    }
}
