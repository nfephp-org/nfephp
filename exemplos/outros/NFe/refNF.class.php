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
 * @name      refNF
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * refNF
 * Nível 4 :: B14
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_refNF {
    var $cUF;       // B15 - código da UF do emitente do documento fiscal
    var $AAMM;      // B16 - ano e mês de emissão da NFe
    var $CNPJ;      // B17 - CNPJ do emitente
    var $mod;       // B18 - modelo do documento fiscal
    var $serie;     // B19 - série do documento fiscal
    var $nNF;       // B20 - número do documento fiscal

    function __construct() {
    }

    function get_xml($dom) {
        $B14 = $dom->appendChild($dom->createElement('refNF'));
        $B15 = $B14->appendChild($dom->createElement('cUF',   $this->cUF));
        $B16 = $B14->appendChild($dom->createElement('AAMM',  $this->AAMM));
        $B17 = $B14->appendChild($dom->createElement('CNPJ',  sprintf("%014s", $this->CNPJ)));
        $B18 = $B14->appendChild($dom->createElement('mod',   $this->mod));
        $B19 = $B14->appendChild($dom->createElement('serie', $this->serie));
        $B20 = $B14->appendChild($dom->createElement('nNF',   $this->nNF));
        return $B14;
    }

    function insere($con, $ide_id) {
        $sql = "INSERT INTO refNF VALUES (NULL";
        $sql.= ", ".$con->quote($ide_id);
        $sql.= ", ".$con->quote($this->cUF);
        $sql.= ", ".$con->quote($this->AAMM);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->mod);
        $sql.= ", ".$con->quote($this->serie);
        $sql.= ", ".$con->quote($this->nNF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro refNF: '.$qry->getMessage());
            return false;
        } else {
            $refNF_id = $con->lastInsertID("refNF", "refNF_id");
        }
    }
}
