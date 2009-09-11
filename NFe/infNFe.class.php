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
 * @name      infNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * infNFe
 * Nível 1 :: A01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_infNFe {
    var $versao;        // A02 - versão do leiaute
    var $Id;            // A03 - identificador da TAG a ser assinada
    var $ide;           // B01 - grupo das informações de identificação da NFe
    var $emit;          // C01 - grupo de identificação do emitente da NFe
    var $avulsa;        // D01 - informações do fisco emitente
    var $dest;          // E01 - grupo de identificação do destinatário da NFe
    var $retirada;      // F01 - grupo de identificação do local de retirada
    var $entrega;       // G01 - grupo de identificação do local de entrega
    var $det;           // H01 - grupo do detalhamento de prod. e serv. da NFe
    var $total;         // W01 - grupo de valores totais da NFe
    var $transp;        // X01 - grupo de informação do transporte da NFe
    var $cobr;          // Y01 - grupo de cobrança
    var $infAdic;       // Z01 - grupo de informações adicionais
    var $exporta;       // ZA01- grupo de exportação
    var $compra;        // ZB01- grupo de compra
    var $Signature;     // ZC01- assinatura XML da NFe segundo padrão digital

    function __construct() {
        $this->versao       = '1.10';
        $this->ide          = new ide;
        $this->emit         = new emit;
        $this->avulsa       = null;
        $this->dest         = new dest;
        $this->retirada     = null;
        $this->entrega      = null;
        $this->det          = array();
        $this->total        = new total;
        $this->transp       = new transp;
        $this->cobr         = null;
        $this->infAdic      = null;
        $this->exporta      = null;
        $this->compra       = null;
        $this->Signature    = new Signature;
    }

    function add_avulsa($obj_avulsa) {
        if (!$this->avulsa) {
            $this->avulsa = $obj_avulsa;
            return true;
        } else {
            return false;
        }
    }

    function add_retirada($obj_retirada) {
        if (!$this->retirada) {
            $this->retirada = $obj_retirada;
            return true;
        } else {
            return false;
        }
    }

    function add_entrega($obj_entrega) {
        if (!$this->entrega) {
            $this->entrega = $obj_entrega;
            return true;
        } else {
            return false;
        }
    }

    function add_det($obj_det) {
        $this->det[] = $obj_det;
        return true;
    }

    function add_cobr($obj_cobr) {
        if (!$this->cobr) {
            $this->cobr = $obj_cobr;
            return true;
        } else {
            return false;
        }
    }

    function add_infAdic($obj_infAdic) {
        if (!$this->infAdic) {
            $this->infAdic = $obj_infAdic;
            return true;
        } else {
            return false;
        }
    }

    function add_exporta($obj_exporta) {
        if (!$this->exporta) {
            $this->exporta = $obj_exporta;
            return true;
        } else {
            return false;
        }
    }

    function add_compra($obj_compra) {
        if (!$this->compra) {
            $this->compra = $obj_compra;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Calcula digito verificador para chave de acesso de 43 dígitos
     * conforme manual, pág. 72
     */
    function calcula_dv($chave43) {
        $multiplicadores = array(2,3,4,5,6,7,8,9);
        $i = 42;
        while ($i >= 0) {
            for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
                $soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
                $i--;
            }
        }
        $resto = $soma_ponderada % 11;
        if ($resto == '0' || $resto == '1') {
            $this->ide->cDV = 0;
        } else {
            $this->ide->cDV = 11 - $resto;
        }
        return $this->ide->cDV;
    }

    function get_chave_acesso() {

        // 02 - cUF  - código da UF do emitente do Documento Fiscal
        $chave = sprintf("%02d", $this->ide->cUF);

        // 04 - AAMM - Ano e Mes de emissão da NF-e
        $chave.= sprintf("%04d", substr($this->ide->dEmi, 2, 2).substr($this->ide->dEmi, 5, 2));

        // 14 - CNPJ - CNPJ do emitente
        $chave.= sprintf("%014s", $this->emit->CNPJ);

        // 02 - mod  - Modelo do Documento Fiscal
        $chave.= sprintf("%02d", $this->ide->mod);

        // 03 - serie - Série do Documento Fiscal
        $chave.= sprintf("%03d", $this->ide->serie);

        // 09 - nNF  - Número do Documento Fiscal
        $chave.= sprintf("%09d", $this->ide->nNF);

        // 09 - cNF  - Código Numérico que compõe a Chave de Acesso
        $chave.= sprintf("%09d", $this->ide->cNF);

        // 01 - cDV  - Dígito Verificador da Chave de Acesso
        $chave.= $this->calcula_dv($chave);

        return $chave;
    }

    function get_xml($dom) {
        $A01 = $dom->appendChild($dom->createElement('infNFe'));
        $A02 = $A01->appendChild($dom->createAttribute('versao'));
               $A02->appendChild($dom->createTextNode($this->versao));
        $A03 = $A01->appendChild($dom->createAttribute('Id'));
               $A03->appendChild($dom->createTextNode($this->Id = "NFe".$this->get_chave_acesso()));

        $B01 = $A01->appendChild($this->ide->get_xml($dom));
        $C01 = $A01->appendChild($this->emit->get_xml($dom));
        $D01 = (is_object($this->avulsa))   ? $A01->appendChild($this->avulsa->get_xml($dom))   : null;
        $E01 = $A01->appendChild($this->dest->get_xml($dom));
        $F01 = (is_object($this->retirada)) ? $A01->appendChild($this->retirada->get_xml($dom)) : null;
        $G01 = (is_object($this->entrega))  ? $A01->appendChild($this->entrega->get_xml($dom))  : null;
        for ($i=0; $i<count($this->det); $i++) {
            $H01 = $A01->appendChild($this->det[$i]->get_xml($dom));
        }
        $W01 = $A01->appendChild($this->total->get_xml($dom));
        $X01 = $A01->appendChild($this->transp->get_xml($dom));
        $Y01 = (is_object($this->cobr))     ? $A01->appendChild($this->cobr->get_xml($dom))     : null;
        $Z01 = (is_object($this->infAdic))  ? $A01->appendChild($this->infAdic->get_xml($dom))  : null;
        $ZA01= (is_object($this->exporta))  ? $A01->appendChild($this->exporta->get_xml($dom))  : null;
        $ZB01= (is_object($this->compra))   ? $A01->appendChild($this->compra->get_xml($dom))   : null;
        // BUG: assinado posteriormente por NFe_utils
        //$ZC01= (is_object($this->Signature) ? $A01->appendChild($this->Signature->get_xml($dom)) : null;
        return $A01;
    }

    function insere($con, $NFe_id) {
        $sql = "INSERT INTO infNFe VALUES (NULL";
        $sql.= ", ".$con->quote($NFe_id);
        $sql.= ", ".$con->quote($this->versao);
        $sql.= ", ".$con->quote($this->Id = $this->get_chave_acesso());
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro infNFe: '.$qry->getMessage());
            return false;
        } else {
            $infNFe_id = $con->lastInsertID("infNFe", "infNFe_id");

            $this->ide->insere($con, $infNFe_id);
            $this->emit->insere($con, $infNFe_id);
            (is_object($this->avulsa)) ? $this->avulsa->insere($con, $infNFe_id) : null;
            $this->dest->insere($con, $infNFe_id);
            (is_object($this->retirada)) ? $this->retirada->insere($con, $infNFe_id) : null;
            (is_object($this->entrega)) ? $this->entrega->insere($con, $infNFe_id) : null;
            for ($i=0; $i<count($this->det); $i++) {
                $this->det[$i]->insere($con, $infNFe_id);
            }
            $this->total->insere($con, $infNFe_id);
            $this->transp->insere($con, $infNFe_id);
            (is_object($this->cobr)) ? $this->cobr->insere($con, $infNFe_id) : null;
            (is_object($this->infAdic)) ? $this->infAdic->insere($con, $infNFe_id) : null;
            (is_object($this->exporta)) ? $this->exporta->insere($con, $infNFe_id) : null;
            (is_object($this->compra)) ? $this->compra->insere($con, $infNFe_id) : null;
        }
    }
}
