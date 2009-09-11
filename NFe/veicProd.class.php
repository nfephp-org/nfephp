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
 * @name      veicProd
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * veicProd
 * Nível 4 :: J01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_veicProd {
    var $tpOp;      // J02 - tipo da operação
    var $chassi;    // J03 - chassi do veículo
    var $cCor;      // J04 - cor
    var $xCor;      // J05 - descrição da cor
    var $pot;       // J06 - potência do motor
    var $CM3;       // J07 - CM3 (potência)
    var $pesoL;     // J08 - peso líquido
    var $pesoB;     // J09 - peso bruto
    var $nSerie;    // J10 - serial
    var $tpComb;    // J11 - tipo de combustível
    var $nMotor;    // J12 - número do motor
    var $CMKG;      // J13 - CMKG
    var $dist;      // J14 - distância entre eixos
    var $RENAVAM;   // J15 - RENAVAM
    var $anoMod;    // J16 - ano modelo de fabricação
    var $anoFab;    // J17 - ano de fabricação
    var $tpPint;    // J18 - tipo de pintura
    var $tpVeic;    // J19 - tipo de veículo
    var $espVeic;   // J20 - espécie de veículo
    var $VIN;       // J21 - condição do VIN
    var $condVeic;  // J22 - condição do veículo
    var $cMod;      // J23 - código marca modelo

    function __construct() {
    }

    function get_xml($dom) {
        $J01 = $dom->appendChild($dom->createElement('veicProd'));
        $J02 = $J01->appendChild($dom->createElement('tpOp',        $this->tpOp));
        $J03 = $J01->appendChild($dom->createElement('chassi',      $this->chassi));
        $J04 = $J01->appendChild($dom->createElement('cCor',        $this->cCor));
        $J05 = $J01->appendChild($dom->createElement('xCor',        $this->xCor));
        $J06 = $J01->appendChild($dom->createElement('pot',         $this->pot));
        $J07 = $J01->appendChild($dom->createElement('CM3',         $this->CM3));
        $J08 = $J01->appendChild($dom->createElement('pesoL',       $this->pesoL));
        $J09 = $J01->appendChild($dom->createElement('pesoB',       $this->pesoB));
        $J10 = $J01->appendChild($dom->createElement('nSerie',      $this->nSerie));
        $J11 = $J01->appendChild($dom->createElement('tpComb',      $this->tpComb));
        $J12 = $J01->appendChild($dom->createElement('nMotor',      $this->nMotor));
        $J13 = $J01->appendChild($dom->createElement('CMKG',        $this->CMKG));
        $J14 = $J01->appendChild($dom->createElement('dist',        $this->dist));
        $J15 = (!empty($this->RENAVAM)) ? $J01->appendChild($dom->createElement('RENAVAM',     $this->RENAVAM)) : null;
        $J16 = $J01->appendChild($dom->createElement('anoMod',      $this->anoMod));
        $J17 = $J01->appendChild($dom->createElement('anoFab',      $this->anoFab));
        $J18 = $J01->appendChild($dom->createElement('tpPint',      $this->tpPint));
        $J19 = $J01->appendChild($dom->createElement('tpVeic',      $this->tpVeic));
        $J20 = $J01->appendChild($dom->createElement('espVeic',     $this->espVeic));
        $J21 = $J01->appendChild($dom->createElement('VIN',         $this->VIN));
        $J22 = $J01->appendChild($dom->createElement('condVeic',    $this->condVeic));
        $J23 = $J01->appendChild($dom->createElement('cMod',        $this->cMod));
        return $J01;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO veicProd VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->tpOp);
        $sql.= ", ".$con->quote($this->chassi);
        $sql.= ", ".$con->quote($this->cCor);
        $sql.= ", ".$con->quote($this->xCor);
        $sql.= ", ".$con->quote($this->pot);
        $sql.= ", ".$con->quote($this->CM3);
        $sql.= ", ".$con->quote($this->pesoL);
        $sql.= ", ".$con->quote($this->pesoB);
        $sql.= ", ".$con->quote($this->nSerie);
        $sql.= ", ".$con->quote($this->tpComb);
        $sql.= ", ".$con->quote($this->nMotor);
        $sql.= ", ".$con->quote($this->CMKG);
        $sql.= ", ".$con->quote($this->dist);
        $sql.= ", ".$con->quote($this->RENAVAM);
        $sql.= ", ".$con->quote($this->anoMod);
        $sql.= ", ".$con->quote($this->anoFab);
        $sql.= ", ".$con->quote($this->tpPint);
        $sql.= ", ".$con->quote($this->tpVeic);
        $sql.= ", ".$con->quote($this->espVeic);
        $sql.= ", ".$con->quote($this->VIN);
        $sql.= ", ".$con->quote($this->condVeic);
        $sql.= ", ".$con->quote($this->cMod);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro veicProd: '.$qry->getMessage());
            return false;
        } else {
            $veicProd_id = $con->lastInsertID("veicProd", "veicProd_id");
        }
    }
}
