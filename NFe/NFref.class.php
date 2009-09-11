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
 * @name      NFref
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * NFref
 * Nível 3 :: B12a
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_NFref {
    var $refNFe;    // B13 - chave de acesso das NFe referenciadas
    var $refNF;     // B14 - informações das NF referenciadas

    function __construct($tipo = 'NFe') {
        if ($tipo == 'NF') {
            $this->refNF  = new refNF;
        }
    }

    function get_xml($dom) {
        $B12a= $dom->appendChild($dom->createElement('NFref'));
        if (!empty($this->refNFe)) {
            $B13 = $B12a->appendChild($dom->createElement('refNFe', $this->refNFe));
        } else if (is_object($this->refNF)) {
            $B14 = $B12a->appendChild($this->refNF->get_xml($dom));
        }
        return $B12a;
    }

    function insere($con, $ide_id) {
        if (!empty($this->refNFe)) {
            $sql = "INSERT INTO refNFe VALUES (NULL";
            $sql.= ", ".$con->quote($ide_id);
            $sql.= ", ".$con->quote($this->refNFe);
            $sql.= ")";

            $qry = $con->query($sql);

            if (MDB2::isError($qry)) {
                set_error('Erro refNFe: '.$qry->getMessage());
                return false;
            } else {
                $refNFe_id = $con->lastInsertID("refNFe", "refNFe_id");
            }

        } else if (is_object($this->refNF)) {
            $this->refNF->insere($con, $ide_id);
        }
    }
}
