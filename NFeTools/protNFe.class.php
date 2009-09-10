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
 * @name      protNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * protNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_protNFe {
    public $versao;
    public $Id;
    public $tpAmb;
    public $verAplic;
    public $chNFe;
    public $dhRecbto;
    public $nProt;
    public $digVal;
    public $cStat;
    public $xMotivo;
    public $XML;

    function __construct() {
    }

    function gravaXML($path=_NFE_PROTNFE_PATH) {
        if ($this->cStat == 100) {
            $extensao = '-aut.xml';
        } else if ($this->cStat == 110) {
            $extensao = '-den.xml';
        } else {
            $extensao = '-rej.xml';
        }
        $filePath = $path.'/'.sprintf("%015s", $this->chNFe).$extensao;
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }
}
