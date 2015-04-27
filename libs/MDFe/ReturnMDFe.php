<?php

namespace NFePHP\MDFe;

/**
 * Classe auxiliar com funções de DOM extendidas
 * @category   NFePHP
 * @package    NFePHP\Common\Dom\ReturnMDfe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;

class ReturnMDFe
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
        $dom = new Dom('1.0', 'utf-8');
        $dom->loadXMLString($xmlResp);
        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'MDFeRecepcao':
                return self::zReadRecepcaoLote($dom);
                break;
            case 'MDFeRetRecepcao':
                return self::zReadRetRecepcao($dom);
                break;
            case 'MDFeConsultaSituacao':
                return self::zReadConsultaMDF($dom);
                break;
            case 'MDFeStatusServico':
                return self::zReadStatusServico($dom);
                break;
            case 'MDFeRecepcaoEvento':
                return self::zReadRecepcaoEvento($dom);
                break;
            case 'MDFeConsNaoEnc':
                return self::zReadConsNaoEnc($dom);
                break;
        }
        return array();
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
        $tag = $dom->getNode('retEnviMDFe');
        if (empty($tag)) {
            return $aResposta;
        }
        $infRec = $dom->getNode('infRec');
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $dom->getValue($tag, 'tpAmb'),
            'cUF' => $dom->getValue($tag, 'cUF'),
            'cStat' => $dom->getValue($tag, 'cStat'),
            'verAplic' => $dom->getValue($tag, 'verAplic'),
            'xMotivo' => $dom->getValue($tag, 'xMotivo'),
            'dhRecbto' => $dom->getValue($infRec, 'dhRecbto'),
            'tMed' => $dom->getValue($infRec, 'tMed'),
            'nRec' => $dom->getValue($infRec, 'nRec')
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
        $tag = $dom->getNode('retConsReciMDFe');
        if (empty($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat'=>true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $dom->getValue($tag, 'tpAmb'),
            'verAplic' => $dom->getValue($tag, 'verAplic'),
            'cStat' => $dom->getValue($tag, 'cStat'),
            'xMotivo' => $dom->getValue($tag, 'xMotivo'),
            'nRec' => $dom->getValue($tag, 'nRec'),
            'cUF' => $dom->getValue($tag, 'tpAmb'),
            'aProt' => self::zGetProt($dom, $tag)
        );
        return $aResposta;
    }
    
    /**
     * zReadConsultaMDF
     * @param DOMDocument $dom
     * @return string
     */
    protected static function zReadConsultaMDF($dom)
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
            'aEvent' => array()
        );
        $tag = $dom->getNode('retConsSitMDFe');
        if (! isset($tag)) {
            return $aResposta;
        }
        $aEvent = array();
        $procEventoMDFe = $tag->getElementsByTagName('procEventoMDFe');
        if (isset($procEventoMDFe)) {
            foreach ($procEventoMDFe as $evento) {
                $aEvent[] = self::zGetEvent($dom, $evento);
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $dom->getValue($tag, 'tpAmb'),
            'verAplic' => $dom->getValue($tag, 'verAplic'),
            'cStat' => $dom->getValue($tag, 'cStat'),
            'xMotivo' => $dom->getValue($tag, 'xMotivo'),
            'cUF' => $dom->getValue($tag, 'cUF'),
            'aProt' => self::zGetProt($dom, $tag),
            'aEvent' => $aEvent
        );
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
        $tag = $dom->getNode('consStatServMDFe');
        if (empty($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $dom->getValue($tag, 'cStat'),
            'verAplic' => $dom->getValue($tag, 'verAplic'),
            'xMotivo' => $dom->getValue($tag, 'xMotivo'),
            'dhRecbto' => $dom->getValue($tag, 'dhRecbto'),
            'tMed' => $dom->getValue($tag, 'tMed'),
            'cUF' => $dom->getValue($tag, 'cUF'),
            'dhRetorno' => $dom->getValue($tag, 'dhRetorno'),
            'xObs' => $dom->getValue($tag, 'xObs')
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
            'aEvent' => array()
        );
        $tag = $dom->getNode('retEvento');
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'id' => $dom->getValue($tag, 'id'),
            'tpAmb' => $dom->getValue($tag, 'tpAmb'),
            'verAplic' => $dom->getValue($tag, 'verAplic'),
            'cOrgao' => $dom->getValue($tag, 'cOrgao'),
            'cStat' => $dom->getValue($tag, 'cStat'),
            'xMotivo' => $dom->getValue($tag, 'xMotivo'),
            'aEvent' => self::zGetEvent($dom, $tag)
        );
        return $aResposta;
    }
    
    /**
     * zReadConsNaoEnc
     * @param DOMDocument $dom
     * @return boolean
     */
    protected static function zReadConsNaoEnc($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'verAplic' => '',
            'tpAmb' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'MDFe' => array()
        );
        $tag = $dom->getNode('retConsMDFeNaoEnc');
        if (empty($tag)) {
            return $aResposta;
        }
        $lista = $tag->getElementsByTagName('infMDFe');
        $aMDFe = array();
        if (isset($lista)) {
            foreach ($lista as $infMDFe) {
                $aMDFe[] = array(
                    'chMDFe' => $dom->getValue($infMDFe, 'chMDFe'),
                    'nProt' => $dom->getValue($infMDFe, 'chMDFe')
                );
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'verAplic' => $dom->getValue($tag, 'verAplic'),
            'tpAmb' => $dom->getValue($tag, 'tpAmb'),
            'cStat' => $dom->getValue($tag, 'cStat'),
            'xMotivo' => $dom->getValue($tag, 'xMotivo'),
            'cUF' => $dom->getValue($tag, 'cUF'),
            'MDFe' => $aMDFe
        );
        return $aResposta;
    }

    /**
     * zGetProt
     * @param DOMDocument $dom
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetProt($dom, $tag)
    {
        $aProt = array();
        $protMDFe = $tag->getElementsByTagName('protMDFe')->item(0);
        $infProt = $dom->getNode('infProt');
        if (empty($infProt)) {
            return $aProt;
        }
        $aProt = array(
            'versao' => $protMDFe->getAttribute('versao'),
            'tpAmb' => $dom->getValue($infProt, 'tpAmb'),
            'verAplic' => $dom->getValue($infProt, 'verAplic'),
            'chMDFe' => $dom->getValue($infProt, 'chMDFe'),
            'dhRecbto' => $dom->getValue($infProt, 'dhRecbto'),
            'nProt' => $dom->getValue($infProt, 'nProt'),
            'digVal' => $dom->getValue($infProt, 'digVal'),
            'cStat' => $dom->getValue($infProt, 'cStat'),
            'xMotivo' => $dom->getValue($infProt, 'xMotivo')
        );
        return $aProt;
    }
    
    /**
     * zGetEvent
     * @param DOMDocument $dom
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetEvent($dom, $tag)
    {
        $aEvent = array();
        $infEvento = $tag->getElementsByTagName('infEvento')->item(0);
        if (! empty($infEvento)) {
            $aEvent = array(
                'chMDFe' => $dom->getValue($infEvento, 'chMDFe'),
                'tpEvento' => $dom->getValue($infEvento, 'tpEvento'),
                'xEvento' => $dom->getValue($infEvento, 'xEvento'),
                'nSeqEvento' => $dom->getValue($infEvento, 'nSeqEvento'),
                'dhRegEvento' => $dom->getValue($infEvento, 'dhRegEvento'),
                'nProt' => $dom->getValue($infEvento, 'nProt')
            );
        }
        return $aEvent;
    }
}
