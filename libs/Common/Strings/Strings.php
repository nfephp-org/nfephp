<?php

namespace Common\Strings;

class Strings
{
    /**
     * cleanString
     * Remove todos dos caracteres espceiais do texto e os acentos
     * @param string $texto
     * @return  string Texto sem caractere especiais
     */
    public static function cleanString($texto)
    {
        $aFind = array('&','á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü',
            'ç','Á','À','Ã','Â','É','Ê','Í','Ó','Ô','Õ','Ú','Ü','Ç');
        $aSubs = array('e','a','a','a','a','e','e','i','o','o','o','u','u',
            'c','A','A','A','A','E','E','I','O','O','O','U','U','C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/]/", "", $novoTexto);
        return $novoTexto;
    }
    
    /**
     * pClearXml
     * Remove \r \n \s \t 
     * @param string $xml
     * @param boolean $remEnc remover encoding do xml
     * @return string
     */
    public static function clearXml($xml = '', $remEnc = false)
    {
        $aFind = array('xmlns:default="http://www.w3.org/2000/09/xmldsig#"', 'default:', ':default', "\n", "\r", "\s", "\t", );
        if ($remEnc) {
            $aFind[] = '<?xml version="1.0"?>';
            $aFind[] = '<?xml version="1.0" encoding="utf-8"?>';
            $aFind[] = '<?xml version="1.0" encoding="UTF-8"?>';
        }
        $retXml = str_replace($aFind, "", $xml);
        return $retXml;
    }
}
