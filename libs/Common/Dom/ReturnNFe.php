<?php
namespace Common\Dom;

/**
 * Classe auxiliar com funções de DOM extendidas
 * @category   NFePHP
 * @package    NFePHP\Common\Dom\ReturnNFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;

class ReturnNFe
{
    /**
     * zReadAutorizacaoLote
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadAutorizacaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'cUF' => '',
            'nRec' => '',
            'prot' => array()
        );
        $tag = $dom->getElementsByTagName('retEnviNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $dhRecbto = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $nRec = $tag->getElementsByTagName('nRec')->item(0)->nodeValue;
        $tMed = $tag->getElementsByTagName('tMed')->item(0)->nodeValue;
        $aProt[] = self::zProt($tag);
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'tMed' => $tMed,
            'cUF' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'nRec' => $nRec,
            'prot' => $aProt
        );
        return $aResposta;
    }
    
    /**
     * zReadRetAutorizacaoLote
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadRetAutorizacaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat'=>false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'cUF' => '',
            'nRec' => '',
            'aProt' => array()
        );
        $tag = $dom->getElementsByTagName('retConsReciNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aProt = array();
        $dhRecbto = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $tagProt = $tag->getElementsByTagName('protNFe');
        foreach ($tagProt as $protocol) {
            $aProt[] = self::zProt($protocol);
        }
        $aResposta = array(
            'bStat'=>true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'cUF' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'nRec' => $tag->getElementsByTagName('nRec')->item(0)->nodeValue,
            'aProt' => $aProt
        );
        return $aResposta;
    }
    
    /**
     * zReadConsultaCadastro2
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadConsultaCadastro2($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'version' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'UF' => '',
            'IE' => '',
            'CNPJ' => '',
            'CPF' => '',
            'dhCons' => '',
            'cUF' => '',
            'aCad' => array()
        );
        $tag = $dom->getElementsByTagName('retConsCad')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infCons = $tag->getElementsByTagName('infCons')->item(0);
        $aResposta = array(
            'bStat' => true,
            'version' => $tag->getAttribute('versao'),
            'cStat' => $infCons->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $infCons->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'UF' => $infCons->getElementsByTagName('UF')->item(0)->nodeValue,
            'IE' => $infCons->getElementsByTagName('IE')->item(0)->nodeValue,
            'CNPJ' => $infCons->getElementsByTagName('CNPJ')->item(0)->nodeValue,
            'CPF' => $infCons->getElementsByTagName('CPF')->item(0)->nodeValue,
            'dhCons' => $infCons->getElementsByTagName('dhCons')->item(0)->nodeValue,
            'cUF' => $infCons->getElementsByTagName('cUF')->item(0)->nodeValue,
            'aCad' => array()
        );
        $infCad = $tag->getElementsByTagName('infCad');
        if (isset($infCad)) {
            foreach ($infCad as $cad) {
                $ender = $cad->getElementsByTagName('ender')->item(0);
                $aCad[] = array(
                    'IE' => $cad->getElementsByTagName('IE')->item(0)->nodeValue,
                    'CNPJ' => $cad->getElementsByTagName('CNPJ')->item(0)->nodeValue,
                    'UF' => $cad->getElementsByTagName('UF')->item(0)->nodeValue,
                    'cSit' => $cad->getElementsByTagName('cSit')->item(0)->nodeValue,
                    'indCredNFe' => $cad->getElementsByTagName('indCredNFe')->item(0)->nodeValue,
                    'indCredCTe' => $cad->getElementsByTagName('indCredCTe')->item(0)->nodeValue,
                    'xNome' => $cad->getElementsByTagName('xNome')->item(0)->nodeValue,
                    'xRegApur' => $cad->getElementsByTagName('xRegApur')->item(0)->nodeValue,
                    'CNAE' => $cad->getElementsByTagName('CNAE')->item(0)->nodeValue,
                    'dIniAtiv' => $cad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue,
                    'dUltSit' => $cad->getElementsByTagName('dUltSit')->item(0)->nodeValue,
                    'xLgr' => $ender->getElementsByTagName('xLgr')->item(0)->nodeValue,
                    'nro' => $ender->getElementsByTagName('nro')->item(0)->nodeValue,
                    'xCpl' => $ender->getElementsByTagName('xCpl')->item(0)->nodeValue,
                    'xBairro' => $ender->getElementsByTagName('xBairro')->item(0)->nodeValue,
                    'cMun' => $ender->getElementsByTagName('cMun')->item(0)->nodeValue,
                    'xMun' => $ender->getElementsByTagName('xMun')->item(0)->nodeValue,
                    'CEP' => $ender->getElementsByTagName('CEP')->item(0)->nodeValue
                );
            }
            $aResposta['aCad'] = $aCad;
        }
        return $aResposta;
    }

    /**
     * zReadConsultaNF2
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadConsultaNF2($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'cUF' => '',
            'chNFe' => '',
            'protNFe' => array(),
            'retCancNFe' => array(),
            'procEventoNFe' => array()
        );
        $tag = $dom->getElementsByTagName('retConsSitNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infProt = $tag->getElementsByTagName('infProt')->item(0);
        $infCanc = $tag->getElementsByTagName('infCanc')->item(0);
        $procEventoNFe = $tag->getElementsByTagName('procEventoNFe');
        $aProt = array();
        $aCanc = array();
        $aEvent = array();
        if (isset($infProt)) {
            $aProt['tpAmb'] = $infProt->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aProt['verAplic'] = $infProt->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aProt['chNFe'] = $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $aProt['dhRecbto'] = $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aProt['nProt'] = $infProt->getElementsByTagName('nProt')->item(0)->nodeValue;
            $aProt['digVal'] = $infProt->getElementsByTagName('digVal')->item(0)->nodeValue;
            $aProt['cStat'] = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aProt['xMotivo'] = $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        }
        if (isset($infCanc)) {
            $aCanc['tpAmb'] = $infCanc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aCanc['verAplic'] = $infCanc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aCanc['cStat'] = $infCanc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aCanc['xMotivo'] = $infCanc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $aCanc['cUF'] = $infCanc->getElementsByTagName('cUF')->item(0)->nodeValue;
            $aCanc['chNFe'] = $infCanc->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $aCanc['dhRecbto'] = $infCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aCanc['nProt'] = $infCanc->getElementsByTagName('nProt')->item(0)->nodeValue;
        }
        if (isset($procEventoNFe)) {
            foreach ($procEventoNFe as $kEli => $evento) {
                $infEvento = $evento->getElementsByTagName('infEvento');
                foreach ($infEvento as $iEv) {
                    if ($iEv->getElementsByTagName('detEvento')->item(0) != "") {
                        continue;
                    }
                    foreach ($iEv->childNodes as $tnodes) {
                        $aEvent[$kEli][$tnodes->nodeName] = $tnodes->nodeValue;
                    }
                }
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'chNFe' => $tag->getElementsByTagName('chNFe')->item(0)->nodeValue,
            'protNFe' => $aProt,
            'retCancNFe' => $aCanc,
            'procEventoNFe' => $aEvent
        );
        return $aResposta;
    }
    
    /**
     * zReadInutilizacaoNF2
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadInutilizacaoNF2($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'dhRecbto' => '',
            'ano' => '',
            'CNPJ' => '',
            'mod' => '',
            'serie' => '',
            'nNFIni' => '',
            'nNFFin' => '',
            'nProt' => ''
        );
        $tag = $dom->getElementsByTagName('retInutNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infInut = $tag->getElementsByTagName('infInut')->item(0);
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $infInut->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $infInut->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cStat' => $infInut->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $infInut->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'cUF' => $infInut->getElementsByTagName('cUF')->item(0)->nodeValue,
            'dhRecbto' => $infInut->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'ano' => $infInut->getElementsByTagName('ano')->item(0)->nodeValue,
            'CNPJ' => $infInut->getElementsByTagName('CNPJ')->item(0)->nodeValue,
            'mod' => $infInut->getElementsByTagName('mod')->item(0)->nodeValue,
            'serie' => $infInut->getElementsByTagName('serie')->item(0)->nodeValue,
            'nNFIni' => $infInut->getElementsByTagName('nNFIni')->item(0)->nodeValue,
            'nNFFin' => $infInut->getElementsByTagName('nNFFin')->item(0)->nodeValue,
            'nProt' => $infInut->getElementsByTagName('nProt')->item(0)->nodeValue
        );
        return $aResposta;
    }
    
    /**
     * zReadStatusServico
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadStatusServico($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'cUF' => ''
        );
        $tag = $dom->getElementsByTagName('retConsStatServ')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'tMed' => $tag->getElementsByTagName('tMed')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue
        );
        return $aResposta;
    }

    /**
     * zReadRecepcaoEvento
     * @param DOMDocument $dom
     * @return array
     */
    public static function zReadRecepcaoEvento($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'idLote' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cOrgao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'evento' => array()
        );
        $tag = $dom->getElementsByTagName('retEnvEvento')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infEvento = $tag->getElementsByTagName('infEvento')->item(0);
        $aEvent = array();
        if (isset($infEvento)) {
            $aEvent = array(
                'tpAmb' => $infEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue,
                'verAplic' => $infEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
                'cOrgao' => $infEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue,
                'cStat' => $infEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
                'chNFe' => $infEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'tpEvento' => $infEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
                'xEvento' => $infEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
                'nSeqEvento' => $infEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
                'CNPJDest' => $infEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue,
                'emailDest' => $infEvento->getElementsByTagName('emailDest')->item(0)->nodeValue,
                'dhRegEvento' => $infEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
                'nProt' => $infEvento->getElementsByTagName('nProt')->item(0)->nodeValue
            );
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'idLote' => $tag->getElementsByTagName('idLote')->item(0)->nodeValue,
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cOrgao' => $tag->getElementsByTagName('cOrgao')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'evento' => $aEvent
        );
        return $aResposta;
    }
    
    /**
     * zReadDistDFeInteresse
     * @param DOMDocument $dom
     * @param boolean $descompactar
     * @return array
     */
    public static function zReadDistDFeInteresse($dom, $descompactar = false)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'dhResp' => '',
            'ultNSU' => 0,
            'maxNSU' => 0,
            'docZip' => array()
        );
        $tag = $dom->getElementsByTagName('retDistDFeInt')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aDocZip = array();
        $docs = $tag->getElementsByTagName('docZip');
        foreach ($docs as $doc) {
            $xml = $doc->nodeValue;
            if ($descompactar) {
                $xml = gzdecode(base64_decode($xml));
            }
            $aDocZip[] = array(
              'NSU' => $doc->getAttribute('NSU'),
              'schema' => $doc->getAttribute('schema'),
              'docZip' => $xml
            );
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhResp' => $tag->getElementsByTagName('dhResp')->item(0)->nodeValue,
            'ultNSU' => $tag->getElementsByTagName('ultNSU')->item(0)->nodeValue,
            'maxNSU' => $tag->getElementsByTagName('maxNSU')->item(0)->nodeValue,
            'docZip' => $aDocZip
        );
        return $aResposta;
    }
    
    protected static function zProt($tag)
    {   
        $aProt = array();
        $infProt = $tag->getElementsByTagName('infProt')->item(0);
        if (! empty($infProt)) {
            $aProt = array(
                'chNFe' => $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'dhRecbto' => $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
                'nProt' => $infProt->getElementsByTagName('nProt')->item(0)->nodeValue,
                'digVal' => $infProt->getElementsByTagName('digVal')->item(0)->nodeValue,
                'cStat' => $infProt->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue
            );
        }
        return $aProt;
    }
}
