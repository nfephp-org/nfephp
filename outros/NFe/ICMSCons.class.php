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
 * @name      ICMSCons
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * ICMSCons
 * Nível 5 :: L117
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_ICMSCons {
    var $vBCICMSSTCons; // L118 - BC do ICMS ST da UF de consumo
    var $vICMSSTCons;   // L119 - valor do ICMS ST da UF de consumo
    var $UFcons;        // L120 - sigla da UF de consumo

    function __construct() {
    }

    function get_xml($dom) {
        $L117 = $dom->appendChild($dom->createElement('ICMSCons'));
        $L118 = $L114->appendChild($dom->createElement('vBCICMSSTCons', $this->vBCICMSSTCons));
        $L119 = $L114->appendChild($dom->createElement('vICMSSTCons',   $this->vICMSSTCons));
        $L120 = $L114->appendChild($dom->createElement('UFcons',        $this->UFcons));
        return $L117;
    }
}
