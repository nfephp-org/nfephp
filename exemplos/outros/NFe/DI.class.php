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
 * @name      DI
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * DI
 * Nível 4 :: I18
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_DI {
    var $nDI;           // I19 - número do documento de importação DI/DSI/DA
    var $dDi;           // I20 - data da registro da DI/DSI/DA
    var $xLocDesemb;    // I21 - local de desembaraço
    var $UFDesemb;      // I22 - UF onde ocorreu o desembaraço aduaneiro
    var $dDesemb;       // I23 - data do desembaraço aduaneiro
    var $cExportador;   // I24 - código do exportador
    var $adi;           // I25 - adições

    function __construct() {
        $this->adi = array();
    }

    function add_adi($obj_adi) {
        $this->adi[] = $obj_adi;
        return true;
    }

    function get_xml($dom) {
        $I18 = $dom->appendChild($dom->createElement('DI'));
        $I19 = $I18->appendChild($dom->createElement('nDI',         $this->nDi));
        $I20 = $I18->appendChild($dom->createElement('dDi',         $this->dDi));
        $I21 = $I18->appendChild($dom->createElement('xLocDesemb',  $this->xLocDesemb));
        $I22 = $I18->appendChild($dom->createElement('UFDesemb',    $this->UFDesemb));
        $I23 = $I18->appendChild($dom->createElement('dDesemb',     $this->dDesemb));
        $I24 = $I18->appendChild($dom->createElement('cExportador', $this->cExportador));
        for ($i=0; $i<count($this->adi); $i++) {
            $I25 = $I18->appendChild($this->adi[$i]->get_xml($dom));
        }
        return $I18;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO DI VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->nDI);
        $sql.= ", ".$con->quote($this->dDi);
        $sql.= ", ".$con->quote($this->xLocDesemb);
        $sql.= ", ".$con->quote($this->UFDesemb);
        $sql.= ", ".$con->quote($this->dDesemb);
        $sql.= ", ".$con->quote($this->cExportador);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro DI: '.$qry->getMessage());
            return false;
        } else {
            $DI_id = $con->lastInsertID("DI", "DI_id");
            for ($i=0; $i<count($this->adi); $i++) {
                $this->adi[$i]->insere($con, $DI_id);
            }
        }
    }
}
