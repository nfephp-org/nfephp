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
 * @name      retInutNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retInutNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_retInutNFe {
    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $ano;        // 
    public $CNPJ;       // 
    public $mod;        // 
    public $serie;      // 
    public $nNFIni;     // 
    public $nNFFin;     // 
    public $dhRecbto;   // 
    public $nProt;      // 
    public $XML;        // string XML

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retInutNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->ano          = $dom->getElementsByTagName('ano')->item(0)->nodeValue;
        $this->CNPJ         = $dom->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $this->mod          = $dom->getElementsByTagName('mod')->item(0)->nodeValue;
        $this->serie        = $dom->getElementsByTagName('serie')->item(0)->nodeValue;
        $this->nNFIni       = $dom->getElementsByTagName('nNFIni')->item(0)->nodeValue;
        $this->nNFFin       = $dom->getElementsByTagName('nNFFin')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->nProt        = $dom->getElementsByTagName('nProt')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETINUTNFE_PATH) {
        $nome = $this->ano.$this->CNPJ.$this->mod.sprintf("%03s", $this->serie).sprintf("%09s", $this->nNFIni).sprintf("%09s", $this->nNFFin);
        $filePath = $path.'/'.$nome.'-inu.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
