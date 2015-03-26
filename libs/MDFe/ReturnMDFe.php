<?php
namespace MDFe;

/**
 * Classe auxiliar com funções de DOM extendidas
 * @category   NFePHP
 * @package    NFePHP\Common\Dom\ReturnMDfe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;

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
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlResp);
        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'mdfeRecepcaoLote':
                return self::zReadRecepcaoLote($dom);
                break;
            case 'mdfeRetRecepcao':
                return self::zReadRetRecepcao($dom);
                break;
            case 'mdfeConsultaMDF':
                return self::zReadConsultaMDF($dom);
                break;
            case 'mdfeStatusServicoMDF':
                //NOTA: irá ser desativado
                return self::zReadStatusServico($dom);
                break;
            case 'mdfeRecepcaoEvento':
                return self::zReadRecepcaoEvento($dom);
                break;
            case 'mdfeConsNaoEnc':
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
        $tag = $dom->getElementsByTagName('retEnviMDFe')->item(0);
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
        $tag = $dom->getElementsByTagName('retConsReciMDFe')->item(0);
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
        $tag = $dom->getElementsByTagName('retConsSitMDFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aEvent = array();
        $procEventoMDFe = $tag->getElementsByTagName('procEventoMDFe');
        if (isset($procEventoMDFe)) {
            foreach ($procEventoMDFe as $evento) {
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
        $tag = $dom->getElementsByTagName('consStatServMDFe')->item(0);
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
            'aEvent' => array()
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
            'aEvent' => self::zGetEvent($tag)
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
        $tag = $dom->getElementsByTagName('retConsMDFeNaoEnc')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $lista = $tag->getElementsByTagName('infMDFe');
        $aMDFe = array();
        if (isset($lista)) {
            foreach ($lista as $infMDFe) {
                $aMDFe[] = array(
                    'chMDFe' =>  $infMDFe->getElementsByTagName('chMDFe')->item(0)->nodeValue,
                    'nProt' => $infMDFe->getElementsByTagName('chMDFe')->item(0)->nodeValue
                );
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'MDFe' => $aMDFe
        );
        return $aResposta;
    }

    /**
     * zGetProt
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetProt($tag)
    {
        $aProt = array();
        $infProt = $tag->getElementsByTagName('infProt')->item(0);
        if (isset($infProt)) {
            $aProt['tpAmb'] = $infProt->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aProt['verAplic'] = $infProt->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aProt['chMDFe'] = $infProt->getElementsByTagName('chMDFe')->item(0)->nodeValue;
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
        if (! empty($infEvento)) {
            $aEvent = array(
                'chMDFe' => $infEvento->getElementsByTagName('chMDFe')->item(0)->nodeValue,
                'tpEvento' => $infEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
                'xEvento' => $infEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
                'nSeqEvento' => $infEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
                'dhRegEvento' => $infEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
                'nProt' => $infEvento->getElementsByTagName('nProt')->item(0)->nodeValue
            );
        }
        return $aEvent;
    }
}
