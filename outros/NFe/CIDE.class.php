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
 * @name      CIDE
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * CIDE
 * Nível 5 :: L105
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe_CIDE {
    var $qBCprod;   // L106 - BC da CIDE
    var $vAliqProd; // L107 - valor da alíquota da CIDE
    var $vCIDE;     // L108 - valor da CIDE

    function __construct() {
    }

    function get_xml($dom) {
        $L105 = $dom->appendChild($dom->createElement('CIDE'));
        $L106 = $L105->appendChild($dom->createElement('qBCprod',   $this->qBCprod));
        $L107 = $L105->appendChild($dom->createElement('vAliqProd', $this->vAliqProd));
        $L108 = $L105->appendChild($dom->createElement('vCIDE',     $this->vCIDE));
        return $L105;
    }
}
