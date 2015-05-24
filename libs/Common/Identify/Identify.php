<?php

namespace NFePHP\Common\Identify;

/**
 * Classe auxiliar para a identificação dos documentos eletrônicos
 * @category   NFePHP
 * @package    NFePHP\Common\Identify
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\Common\Files\FilesFolders;

class Identify
{
    /**
     * Lista com a identificação das TAGs principais que identificam o documento
     * e o respectivo arquivo xsd
     * @var array 
     */
    protected static $schemesId = array();
    
    /**
     * setListSchemesId
     * @param array $aList
     */
    public static function setListSchemesId($aList = array())
    {
        if (count($aList) > 0) {
            self::$schemesId = $aList;
        }
    }
    
    /**
     * identificacao
     * Identifica o documento 
     * @param type $xml
     * @return string
     */
    public static function identificacao($xml = '', &$aResp = array())
    {
        if ($xml == '') {
            return '';
        } elseif (is_file($xml)) {
            $xml = FilesFolders::readFile($xml);
        }
        $dom = new Dom('1.0', 'utf-8');
        $dom->loadXMLString($xml);
        $key = '';
        $schId = (string) self::zSearchNode($dom, $key);
        if ($schId == '') {
            return '';
        }
        $chave = '';
        $tpAmb = '';
        $dhEmi = '';
        if ($schId == 'nfe' || $schId == 'cte' || $schId == 'mdfe') {
            switch ($schId) {
                case 'nfe':
                    $tag = 'infNFe';
                    break;
                case 'cte':
                    $tag = 'infCTe';
                    break;
                case 'mdfe':
                    $tag = 'infMDFe';
                    break;
            }
            $chave = $dom->getChave($tag);
            $tpAmb = $dom->getNodeValue('tpAmb');
            $dhEmi = $dom->getNodeValue('dhEmi');
        }
        $aResp['Id'] =  $schId;
        $aResp['tag'] =  $key;
        $aResp['dom'] = $dom;
        $aResp['chave'] = $chave;
        $aResp['tpAmb'] = $tpAmb;
        $aResp['dhEmi'] = $dhEmi;
        return $schId;
    }
    
    /**
     * zSearchNode
     * @param DOMDocument $dom
     * @return string
     */
    protected static function zSearchNode($dom, &$key)
    {
        foreach (self::$schemesId as $key => $schId) {
            $node = $dom->getElementsByTagName($key)->item(0);
            if (! empty($node)) {
                return $schId;
            }
        }
        return '';
    }
}
