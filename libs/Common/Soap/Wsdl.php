<?php

namespace NFePHP\Common\Soap;

/**
 * Classe usada para obter os arquivos WSDL, que são as especificações 
 * da comunicação SOAP com os webservices da SEFAZ e das Prefeituras;
 * @category   NFePHP
 * @package    NFePHP\Common\Soap
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm@gamil.com>
 * @link       http://github.com/nfephp-org/spedphp for the canonical source repository
 */

use NFePHP\Common\Soap;
use NFePHP\Common\Exception;
use \DOMDocument;

class Wsdl
{
    public $soapDebug='';
    public $error='';
    
    /**
     * updateWsdl
     * 
     * Atualiza todos os arquivos wsdl de todos os webservices
     * cadastrados no arquivo wsFile
     * @param string $wsdlDir Path para o diretorio dos arquivos WSDL
     * @param string $wsFile Path para o arquivo de cadastramento dos webservices
     * @param string $priKeyPath Path para o arquivo da chave privada
     * @param string $pubKeyPath Path para o arquivo da chave publica
     * @param string $pubKeyPath Path para o arquivo da chave publica
     * @return boolean|string False se houve algum erro no download, e lista dos wsdl baixados
     */
    public function updateWsdl($wsdlDir = '', $wsFile = '', $priKeyPath = '', $pubKeyPath = '', $certKeyPath = '')
    {
        $contagem = 1;
        $msg = '';
        //pega o conteúdo do xml com os endereços dos webservices
        $dom = new DOMDocument();
        $dom->load($wsFile);
        $xpath = new DOMXpath($dom);
        $elements = $xpath->query("/WS");
        if (!is_null($elements)) {
            foreach ($elements as $element) {
                echo "<br/>[". $element->nodeName. "]";
            }
        }

        if ($this->error != '') {
            return false;
        }
        return $msg;
    }//fim updateWsdl
    
    /**
     * downloadWsdl
     * Baixa o arquivos wsdl necessário para a comunicação usando 
     * SOAP nativo
     * @param string $url
     * @param string $priKeyPath
     * @param string $pubKeyPath
     * @param string $certKeyPath
     * @return string
     */
    public function downLoadWsdl($url, $priKeyPath, $pubKeyPath, $certKeyPath)
    {
        $soap = new Soap\CurlSoap($priKeyPath, $pubKeyPath, $certKeyPath);
        $resposta = $soap->getWsdl($url);
        if (!$resposta) {
            $this->soapDebug = $soap->soapDebug;
            return '';
        }
        return $resposta;
    }
}//fim WSDL
