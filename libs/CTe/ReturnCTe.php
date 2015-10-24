<?php

namespace NFePHP\CTe;

/**
 * Classe auxiliar com funções de DOM extendidas
 * @category   NFePHP
 * @package    NFePHP\Common\Dom\ReturnCTe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;

class ReturnCTe
{
    /**
     * readReturnSefaz
     * Trata o retorno da SEFAZ devolvendo o resultado em um array
     * @param string $method
     * @param string $xmlResp
     * @param mixed $parametro
     * @return array
     */
    public static function readReturnSefaz($method, $xmlResp)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlResp);
        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'cteRecepcaoLote':
                return self::zReadRecepcaoLote($dom);
                break;
            case 'cteRetRecepcao':
                return self::zReadRetRecepcao($dom);
                break;
            case 'consultaCadastro2':
                return self::zReadConsultaCadastro2($dom);
                break;
            case 'cteConsultaCT':
                return self::zReadConsultaCT($dom);
                break;
            case 'cteInutilizacaoCT':
                return self::zReadInutilizacaoCT($dom);
                break;
            case 'cteStatusServicoCT':
                //NOTA: irá ser desativado
                return self::zReadStatusServico($dom);
                break;
            case 'cteRecepcaoEvento':
                return self::zReadRecepcaoEvento($dom);
                break;
        }
        return array();
    }

    /**
     * zReadConsultaCadastro2
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadConsultaCadastro2($dom)
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
     * zReadRecepcaoLote
     * @param DOMDocument $dom
     * @return boolean
     */
    protected static function zReadRecepcaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cUF' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'nRec' => ''
        );
        $tag = $dom->getElementsByTagName('retEnviCte')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $dhRecbto = '';
        $nRec = '';
        $tMed = '';
        $infRec = $tag->getElementsByTagName('infRec')->item(0);
        if (!empty($infRec)) {
            $dhRecbto = $infRec->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $nRec = $infRec->getElementsByTagName('nRec')->item(0)->nodeValue;
            $tMed = $infRec->getElementsByTagName('tMed')->item(0)->nodeValue;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'tMed' => $tMed,
            'nRec' => $nRec
        );
        return $aResposta;
    }
    
    /**
     * zReadRetRecepcao
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadRetRecepcao($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat'=>false,
            'versao' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'nRec' => '',
            'aProt' => array()
        );
        $tag = $dom->getElementsByTagName('retConsReciCTe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat'=>true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'nRec' => $tag->getElementsByTagName('nRec')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'aProt' => self::zGetProt($tag)
        );
        return $aResposta;
    }
    
    /**
     * zReadConsultaCT
     * @param DOMDocument $dom
     * @return string
     */
    protected static function zReadConsultaCT($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'aProt' => array(),
            'aCanc' => array(),
            'aEvent' => array()
        );
        $tag = $dom->getElementsByTagName('retConsSitCTe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aEvent = array();
        $procEventoCTe = $tag->getElementsByTagName('procEventoCTe');
        if (isset($procEventoCTe)) {
            foreach ($procEventoCTe as $evento) {
                $aEvent[] = self::zGetEvent($evento);
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'aProt' => self::zGetProt($tag),
            'aCanc' => self::zGetCanc($tag),
            'evento' => $aEvent
        );
        return $aResposta;
    }
    
    /**
     * zReadInutilizacaoCT
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadInutilizacaoCT($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'ano' => '',
            'CNPJ' => '',
            'mod' => '',
            'serie' => '',
            'nCTIni' => '',
            'nCTFin' => '',
            'dhRecbto' => '',
            'nProt' => ''
        );
        $tag = $dom->getElementsByTagName('retInutCTe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta['bStat'] = true;
        $aResposta['versao'] = $tag->getAttribute('versao');
        $aResposta['tpAmb'] = $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $aResposta['verAplic'] = $tag->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $aResposta['cStat'] = $tag->getElementsByTagName('cStat')->item(0)->nodeValue;
        $aResposta['xMotivo'] = $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $aResposta['cUF'] = $tag->getElementsByTagName('cUF')->item(0)->nodeValue;
        $infInut = $tag->getElementsByTagName('infInut')->item(0);
        if (! empty($infInut)) {
            $aResposta['dhRecbto'] = $infInut->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aResposta['ano'] = $infInut->getElementsByTagName('ano')->item(0)->nodeValue;
            $aResposta['CNPJ'] = $infInut->getElementsByTagName('CNPJ')->item(0)->nodeValue;
            $aResposta['mod'] = $infInut->getElementsByTagName('mod')->item(0)->nodeValue;
            $aResposta['serie'] = $infInut->getElementsByTagName('serie')->item(0)->nodeValue;
            $aResposta['nCTIni'] = $infInut->getElementsByTagName('nCTIni')->item(0)->nodeValue;
            $aResposta['nCTFin'] = $infInut->getElementsByTagName('nCTFin')->item(0)->nodeValue;
            $aResposta['nProt'] = $infInut->getElementsByTagName('nProt')->item(0)->nodeValue;
        }
        return $aResposta;
    }
    
    /**
     * zReadStatusServico
     * @param DOMDocument $dom
     * @return string|boolean
     */
    protected static function zReadStatusServico($dom)
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
            'cUF' => '',
            'dhRetorno' => '',
            'xObs' => ''
        );
        $tag = $dom->getElementsByTagName('consStatServCTe')->item(0);
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
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'dhRetorno' => $tag->getElementsByTagName('dhRetorno')->item(0)->nodeValue,
            'xObs' => $tag->getElementsByTagName('xObs')->item(0)->nodeValue
        );
        return $aResposta;
    }
    
    /**
     * zReadRecepcaoEvento
     * @param DOMDocument $dom
     * @return string
     */
    protected static function zReadRecepcaoEvento($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'verAplic' => '',
            'tpAmb' => '',
            'id' => '',
            'cOrgao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'evento' => array()
        );
        $tag = $dom->getElementsByTagName('retEvento')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'id' => $tag->getElementsByTagName('id')->item(0)->nodeValue,
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cOrgao' => $tag->getElementsByTagName('cOrgao')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'evento' => self::zGetEvent($tag)
        );
        return $aResposta;
    }
    
    /**
     * zGetProt
     * @param DOMDocument $tag
     * @return type
     */
    private static function zGetProt($tag)
    {
        $aProt = array();
        $infProt = $tag->getElementsByTagName('infProt');
        if (! empty($infProt)) {
            $aProt['tpAmb'] = $infProt->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aProt['verAplic'] = $infProt->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aProt['chCTe'] = $infProt->getElementsByTagName('chCTe')->item(0)->nodeValue;
            $aProt['dhRecbto'] = $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aProt['nProt'] = $infProt->getElementsByTagName('nProt')->item(0)->nodeValue;
            $aProt['digVal'] = $infProt->getElementsByTagName('digVal')->item(0)->nodeValue;
            $aProt['cStat'] = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aProt['xMotivo'] = $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        }
        return $aProt;
    }
    
    /**
     * zGetEvent
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetEvent($tag)
    {
        $aEvent = array();
        $infEvento = $tag->getElementsByTagName('infEvento')->item(0);
        if (isset($infEvento)) {
            $aEvent = array(
                'chCTe' => $infEvento->getElementsByTagName('chCTe')->item(0)->nodeValue,
                'tpEvento' => $infEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
                'xEvento' => $infEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
                'nSeqEvento' => $infEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
                'dhRegEvento' => $infEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
                'nProt' => $infEvento->getElementsByTagName('nProt')->item(0)->nodeValue
            );
        }
        return $aEvent;
    }

    /**
     * zGetCanc
     * @param DOMDocument $tag
     * @return type
     */
    private static function zGetCanc($tag)
    {
        $aCanc = array();
        $infCanc = $tag->getElementsByTagName('infCanc');
        if (! empty($infCanc)) {
            $aProt['tpAmb'] = $infCanc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aProt['verAplic'] = $infCanc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aProt['cStat'] = $infCanc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aProt['xMotivo'] = $infCanc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $aProt['cUF'] = $infCanc->getElementsByTagName('cUF')->item(0)->nodeValue;
            $aProt['chCTe'] = $infCanc->getElementsByTagName('chCTe')->item(0)->nodeValue;
            $aProt['dhRecbto'] = $infCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aProt['nProt'] = $infCanc->getElementsByTagName('nProt')->item(0)->nodeValue;
        }
        return $aCanc;
    }
}
