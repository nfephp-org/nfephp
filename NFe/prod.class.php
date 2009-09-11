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
 * @name      prod
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * prod
 * Nível 3 :: I01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_prod {
    var $cProd;     // I02 - código do produto ou serviço
    var $cEAN;      // I03 - GTIN (Global Trade Item Number)do produto
    var $xProd;     // I04 - descrição do produto ou serviço
    var $NCM;       // I05 - código NCM
    var $EXTIPI;    // I06 - EX_TIPI
    var $genero;    // I07 - gênero do produto ou serviço
    var $CFOP;      // I08 - código fiscal de operações e prestações
    var $uCom;      // I09 - unidade comercial
    var $qCom;      // I10 - quantidade comercial
    var $vUnCom;    // I10a- valor unitário de comercialização
    var $vProd;     // I11 - valor total bruto dos produtos ou serviços
    var $cEANTrib;  // I12 - GTIN da unidade tributável
    var $uTrib;     // I13 - unidade tributável
    var $qTrib;     // I14 - quantidade tributável
    var $vUnTrib;   // I14a- valor unitário de tributação
    var $vFrete;    // I15 - valor total do frete
    var $vSeg;      // I16 - valor total do seguro
    var $vDesc;     // I17 - valor do desconto
    var $DI;        // I18 - declaração de importação
    var $veicProd;  // J01 - grupo do detalhamento de veículos novos
    var $med;       // K01 - grupo do detalhamento de medicamentos
    var $arma;      // L01 - grupo do detalhamento do armamento
    var $comb;      // L101- grupo de informações para combustíveis líquidos

    function __construct() {
        $this->DI       = array();
        $this->veicProd = null;
        $this->med      = array();
        $this->arma     = array();
        $this->comb     = null;
    }

    function add_DI($obj_DI) {
        $this->DI[] = $obj_DI;
        return true;
    }

    function add_veicProd($obj_veicProd) {
        if (!$this->veicProd) {
            $this->veicProd = $obj_veicProd;
            return true;
        } else {
            return false;
        }
    }

    function add_med($obj_med) {
        $this->med[] = $obj_med;
        return true;
    }

    function add_arma($obj_arma) {
        $this->arma[] = $obj_arma;
        return true;
    }

    function add_comb($obj_comb) {
        if (!$this->comb) {
            $this->comb = $obj_comb;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $I01 = $dom->appendChild($dom->createElement('prod'));
        $I02 = $I01->appendChild($dom->createElement('cProd',       $this->cProd));
        $I03 = $I01->appendChild($dom->createElement('cEAN',        $this->cEAN));
        $I04 = $I01->appendChild($dom->createElement('xProd',       $this->xProd));
        $I05 = (!empty($this->NCM))     ? $I01->appendChild($dom->createElement('NCM',     $this->NCM))     : null;
        $I06 = (!empty($this->EXTIPI))  ? $I01->appendChild($dom->createElement('EXTIPI',  $this->EXTIPI))  : null;
        $I07 = (!empty($this->genero))  ? $I01->appendChild($dom->createElement('genero',  $this->genero))  : null;
        $I08 = $I01->appendChild($dom->createElement('CFOP',        $this->CFOP));
        $I09 = $I01->appendChild($dom->createElement('uCom',        $this->uCom));
        $I10 = $I01->appendChild($dom->createElement('qCom',        number_format($this->qCom, 4, ".", "")));
        $I10a= $I01->appendChild($dom->createElement('vUnCom',      number_format($this->vUnCom, 4, ".", "")));
        $I11 = $I01->appendChild($dom->createElement('vProd',       number_format($this->vProd, 2, ".", "")));
        $I12 = $I01->appendChild($dom->createElement('cEANTrib',    $this->cEANTrib));
        $I13 = $I01->appendChild($dom->createElement('uTrib',       $this->uTrib));
        $I14 = $I01->appendChild($dom->createElement('qTrib',       number_format($this->qTrib, 4, ".", "")));
        $I14a= $I01->appendChild($dom->createElement('vUnTrib',     number_format($this->vUnTrib, 4, ".", "")));
        $I15 = ($this->vFrete > 0)  ? $I01->appendChild($dom->createElement('vFrete',  number_format($this->vFrete, 2, ".", "")))  : null;
        $I16 = ($this->vSeg > 0)    ? $I01->appendChild($dom->createElement('vSeg',    number_format($this->vSeg, 2, ".", "")))    : null;
        $I17 = ($this->vDesc > 0)   ? $I01->appendChild($dom->createElement('vDesc',   number_format($this->vDesc, 2, ".", "")))   : null;
        for ($i=0; $i<count($this->DI); $i++) {
            $I18 = $I01->appendChild($this->DI[$i]->get_xml($dom));
        }
        $J01 = (is_object($this->veicProd)) ? $I01->appendChild($this->veicProd->get_xml($dom)) : null;
        for ($i=0; $i<count($this->med); $i++) {
            $K01 = $I01->appendChild($this->med[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->arma); $i++) {
            $L01 = $I01->appendChild($this->arma[$i]->get_xml($dom));
        }
        $L101= (is_object($this->comb)) ? $I01->appendChild($this->comb->get_xml($dom)) : null;
        return $I01;
    }

    function insere($con, $det_id) {
        $sql = "INSERT INTO prod VALUES (NULL";
        $sql.= ", ".$con->quote($det_id);
        $sql.= ", ".$con->quote($this->cProd);
        $sql.= ", ".$con->quote($this->cEAN);
        $sql.= ", ".$con->quote($this->xProd);
        $sql.= ", ".$con->quote($this->NCM);
        $sql.= ", ".$con->quote($this->EXTIPI);
        $sql.= ", ".$con->quote($this->genero);
        $sql.= ", ".$con->quote($this->CFOP);
        $sql.= ", ".$con->quote($this->uCom);
        $sql.= ", ".$con->quote($this->qCom);
        $sql.= ", ".$con->quote($this->vUnCom);
        $sql.= ", ".$con->quote($this->vProd);
        $sql.= ", ".$con->quote($this->cEANTrib);
        $sql.= ", ".$con->quote($this->uTrib);
        $sql.= ", ".$con->quote($this->qTrib);
        $sql.= ", ".$con->quote($this->vUnTrib);
        $sql.= ", ".$con->quote($this->vFrete);
        $sql.= ", ".$con->quote($this->vSeg);
        $sql.= ", ".$con->quote($this->vDesc);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro prod: '.$qry->getMessage());
            return false;
        } else {
            $prod_id = $con->lastInsertID("prod", "prod_id");

            for ($i=0; $i<count($this->DI); $i++) {
                $this->DI[$i]->insere($con, $prod_id);
            }
            (is_object($this->veicProd)) ? $this->veicProd->insere($con, $prod_id) : null;
            for ($i=0; $i<count($this->med); $i++) {
                $this->med[$i]->insere($con, $prod_id);
            }
            for ($i=0; $i<count($this->arma); $i++) {
                $this->arma[$i]->insere($con, $prod_id);
            }
            (is_object($this->comb)) ? $this->comb->insere($con, $prod_id) : null;
        }
    }
}
