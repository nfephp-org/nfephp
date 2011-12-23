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
 * @name      exporta
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * exporta
 * Nível 2 :: ZA01
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_exporta {
    var $UFEmbarq;      // ZA02 - sigla da UF do embarque dos produtos
    var $xLocEmbarq;    // ZA03 - local onde ocorrerá o embarque dos produtos

    function __construct() {
    }

    function get_xml($dom) {
        $ZA01 = $dom->appendChild($dom->createElement('exporta'));
        $ZA02 = $ZA01->appendChild($dom->createElement('UFEmbarq',    $this->UFEmbarq));
        $ZA03 = $ZA01->appendChild($dom->createElement('xLocEmbarq',  $this->xLocEmbarq));
        return $ZA01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO exporta VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->UFEmbarq);
        $sql.= ", ".$con->quote($this->xLocEmbarq);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro exporta: '.$qry->getMessage());
            return false;
        } else {
            $exporta_id = $con->lastInsertID("exporta", "exporta_id");
        }
    }
}
