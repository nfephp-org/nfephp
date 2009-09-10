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
 * @name      retConsReciNFe
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * retConsReciNFe
 *
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFeTools_retConsReciNFe {
    public $versao;     // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $nRec;       // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $protNFe;    // array de protocolos de NFe's processadas
    public $XML;        // string XML

    function __construct() {
        $this->protNFe = array();
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsReciNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->nRec         = $dom->getElementsByTagName('nRec')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

        foreach ($dom->getElementsByTagName('protNFe') as $key => $protNFe) {

            $domProt = new DOMDocument('1.0', 'utf-8');
            $domProt->formatOutput = false;
            $domProt->appendChild($domProt->importNode($protNFe, true));

            $this->protNFe[$key] = new protNFe();
            $this->protNFe[$key]->versao = $protNFe->getAttribute('versao');
            $infProt = $domProt->getElementsByTagName('infProt')->item(0);
            $this->protNFe[$key]->Id        = $infProt->getElementsByTagName('Id')->item(0)->nodeValue;
            $this->protNFe[$key]->tpAmb     = $infProt->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $this->protNFe[$key]->verAplic  = $infProt->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->protNFe[$key]->chNFe     = $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $this->protNFe[$key]->dhRecbto  = $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $this->protNFe[$key]->nProt     = $infProt->getElementsByTagName('nProt')->item(0)->nodeValue;
            $this->protNFe[$key]->digVal    = $infProt->getElementsByTagName('digVal')->item(0)->nodeValue;
            $this->protNFe[$key]->cStat     = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->protNFe[$key]->xMotivo   = $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $this->protNFe[$key]->XML       = $domProt->saveXML();
        }

    }

    function gravaXML($path=_NFE_RETCONSRECINFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->nRec).'-pro-rec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}
