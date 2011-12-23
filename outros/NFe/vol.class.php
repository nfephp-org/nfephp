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
 * @name      vol
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * vol
 * Nível 3 :: X26
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_vol {
    var $qVol;      // X27 - quantidade de volumes transportados
    var $esp;       // X28 - espécie dos volumes transportados
    var $marca;     // X29 - marca dos volumes transportados
    var $nVol;      // X30 - numeração dos volumes transportados
    var $pesoL;     // X31 - peso líquido (em kg)
    var $pesoB;     // X32 - peso bruto (em kg)
    var $lacres;    // X33 - grupo de lacres

    function __construct() {
        $this->lacres = array();
    }

    function add_lacres($obj_lacres) {
        $this->lacres[] = $obj_lacres;
        return true;
    }

    function get_xml($dom) {
        $X26 = $dom->appendChild($dom->createElement('vol'));
        $X27 = (!empty($this->qVol))    ? $X26->appendChild($dom->createElement('qVol',    $this->qVol))  : null;
        $X27 = (!empty($this->esp))     ? $X26->appendChild($dom->createElement('esp',     $this->esp))   : null;
        $X27 = (!empty($this->marca))   ? $X26->appendChild($dom->createElement('marca',   $this->marca)) : null;
        $X27 = (!empty($this->nVol))    ? $X26->appendChild($dom->createElement('nVol',    $this->nVol))  : null;
        $X27 = (!empty($this->pesoL))   ? $X26->appendChild($dom->createElement('pesoL',   number_format($this->pesoL, 3, ".", ""))) : null;
        $X27 = (!empty($this->pesoB))   ? $X26->appendChild($dom->createElement('pesoB',   number_format($this->pesoB, 3, ".", ""))) : null;
        for ($i=0; $i<count($this->lacres); $i++) {
            $X33 = $X26->appendChild($this->lacres[$i]->get_xml($dom));
        }
        return $X26;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO vol VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->qVol);
        $sql.= ", ".$con->quote($this->esp);
        $sql.= ", ".$con->quote($this->marca);
        $sql.= ", ".$con->quote($this->nVol);
        $sql.= ", ".$con->quote($this->pesoL);
        $sql.= ", ".$con->quote($this->pesoB);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro vol: '.$qry->getMessage());
            return false;
        } else {
            $vol_id = $con->lastInsertID("vol", "vol_id");
            for ($i=0; $i<count($this->lacres); $i++) {
                $this->lacres[$i]->insere($con, $vol_id);
            }
        }
    }
}
