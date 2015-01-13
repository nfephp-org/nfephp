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
 * @name      arma
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * arma
 * Nível 4 :: L01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_arma {
    var $tpArma;    // L02 - indicador do tipo de arama de fogo
    var $nSerie;    // L03 - número de série da arma
    var $nCano;     // L04 - número de série do cano
    var $descr;     // L05 - descrição completa da arma

    function __construct() {
    }

    function get_xml($dom) {
        $L01 = $dom->appendChild($dom->createElement('arma'));
        $L02 = $L01->appendChild($dom->createElement('tpArma',  $this->tpArma));
        $L03 = $L01->appendChild($dom->createElement('nSerie',  $this->nSerie));
        $L04 = $L01->appendChild($dom->createElement('nCano',   $this->nCano));
        $L05 = $L01->appendChild($dom->createElement('descr',   $this->descr));
        return $L01;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO arma VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->tpArma);
        $sql.= ", ".$con->quote($this->nSerie);
        $sql.= ", ".$con->quote($this->nCano);
        $sql.= ", ".$con->quote($this->descr);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro arma: '.$qry->getMessage());
            return false;
        } else {
            $arma_id = $con->lastInsertID("arma", "arma_id");
        }
    }
}
