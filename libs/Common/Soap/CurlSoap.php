<?php

namespace Common\Soap;

/**
 * Classe auxiliar para envio das mensagens SOAP usando cURL
 * @category   NFePHP
 * @package    NFePHP\Common\Soap
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux dot rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
use Common\Exception;

class CurlSoap
{
    /**
     * soapDebug
     * @var string
     */
    public $soapDebug = '';
    /**
     * soapTimeout
     * @var integer
     */
    public $soapTimeout = 10;
    /**
     * lastMsg
     * @var string
     */
    public $lastMsg = '';

    /**
     * errorCurl
     * @var string
     */
    private $errorCurl = '';
    /**
     * infoCurl
     * @var array
     */
    private $infoCurl = array();
    /**
     * pubKeyPath
     * @var string 
     */
    private $pubKeyPath = '';
    /**
     * priKeyPath
     * @var string
     */
    private $priKeyPath = '';
    /**
     * certKeyPath
     * @var string
     */
    private $certKeyPath = '';
    /**
     * proxyIP
     * @var string
     */
    private $proxyIP = '';
    /**
     * proxyPORT
     * @var string
     */
    private $proxyPORT = '';
    /**
     * proxyUSER
     * @var string
     */
    private $proxyUSER = '';
    /**
     * proxyPASS
     * @var string 
     */
    private $proxyPASS = '';
    
    /**
     * __construct
     * 
     * @param string $priKeyPath path para a chave privada
     * @param string $pubKeyPath path para a chave publica
     * @param string $certKeyPath path para o certificado
     * @param string $timeout tempo de espera da resposta do webservice
     */
    public function __construct($priKeyPath = '', $pubKeyPath = '', $certKeyPath = '', $timeout = 10)
    {
        $this->priKeyPath = $priKeyPath;
        $this->pubKeyPath = $pubKeyPath;
        $this->certKeyPath = $certKeyPath;
        $this->soapTimeout = $timeout;
        if (! is_file($priKeyPath) || ! is_file($pubKeyPath) || ! is_file($certKeyPath) || ! is_numeric($timeout)) {
            throw new Exception\InvalidArgumentException(
                "Somente o path dos certificado devem ser passados."
                . " Alguns dos certificados não foram encontrados ou o timeout pode não ser numérico."
            );
        }
    }
    
    /**
     * setProxy
     * Seta o uso do proxy
     * @param string $ipNumber numero IP do proxy server
     * @param string $port numero da porta usada pelo proxy
     * @param string $user nome do usuário do proxy
     * @param string $pass senha de acesso ao proxy
     * @return boolean
     */
    public function setProxy($ipNumber, $port, $user = '', $pass = '')
    {
        $this->proxyIP = $ipNumber;
        $this->proxyPORT = $port;
        $this->proxyUSER = $user;
        $this->proxyPASS = $pass;
    }//fim setProxy
    
    /**
     * getProxy
     * Retorna os dados de configuração do Proxy em um array
     * @return array
     */
    public function getProxy()
    {
        $aProxy['ip'] = $this->proxyIP;
        $aProxy['port'] = $this->proxyPORT;
        $aProxy['username'] = $this->proxyUSER;
        $aProxy['password'] = $this->proxyPASS;
        return $aProxy;
    }
    
    /**
     * Envia mensagem ao webservice
     * @param string $urlsevice
     * @param string $namespace
     * @param string $header
     * @param string $body
     * @param string $method
     * @return boolean|string
     */
    public function send($urlservice, $namespace, $header, $body, $method)
    {
        //monta a mensagem ao webservice
        $data = '<?xml version="1.0" encoding="utf-8"?>'.'<soap12:Envelope ';
        $data .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $data .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
        $data .= 'xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">';
        $data .= '<soap12:Header>'.$header.'</soap12:Header>';
        $data .= '<soap12:Body>'.$body.'</soap12:Body>';
        $data .= '</soap12:Envelope>';
        $data = $this->zLimpaMsg($data);
        $this->lastMsg = $data;
        //tamanho da mensagem
        $tamanho = strlen($data);
        //estabelecimento dos parametros da mensagem
        $parametros = array(
            'Content-Type: application/soap+xml;charset=utf-8;action="'.$namespace."/".$method.'"',
            'SOAPAction: "'.$method.'"',
            "Content-length: $tamanho");
        //solicita comunicação via cURL
        $resposta = $this->zCommCurl($urlservice, $data, $parametros);
        if (empty($resposta)) {
            $msg = "Não houve retorno do Curl.\n $this->errorCurl";
            throw new Exception\RuntimeException($msg);
        }
        //obtem o bloco html da resposta
        $xPos = stripos($resposta, "<");
        $blocoHtml = substr($resposta, 0, $xPos);
        if ($this->infoCurl["http_code"] != '200') {
            //se não é igual a 200 houve erro
            $msg = $blocoHtml;
            throw new Exception\RuntimeException($msg);
        }
        //obtem o tamanho do xml
        $num = strlen($resposta);
        //localiza a primeira marca de tag
        $xPos = stripos($resposta, "<");
        //se não exixtir não é um xml
        if ($xPos !== false) {
            $xml = substr($resposta, $xPos, $num-$xPos);
        } else {
            $xml = '';
        }
        if ($xml == '') {
            $msg = "Não houve retorno de um xml verifique soapDebug!!";
            throw new Exception\RuntimeException($msg);
        }
        return $xml;
    } //fim send

    /**
     * getWsdl
     * Baixa o arquivo wsdl do webservice
     * @param string $urlsefaz
     * @return boolean|string
     */
    public function getWsdl($urlservice)
    {
        $aURL = explode('?', $urlservice);
        if (count($aURL) == 1) {
            $urlservice .= '?wsdl';
        }
        $resposta = $this->zCommCurl($urlservice);
        //verifica se foi retornado o wsdl
        $nPos = strpos($resposta, '<wsdl:def');
        if ($nPos === false) {
            $nPos = strpos($resposta, '<definit');
        }
        if ($nPos === false) {
            //não retornou um wsdl
            return false;
        }
        $wsdl = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".trim(substr($resposta, $nPos));
        return $wsdl;
    }

    /**
     * zCommCurl
     * Realiza da comunicação via cURL
     * @param string $url
     * @param string $data
     * @param string $parametros
     * @return string
     */
    protected function zCommCurl($url, $data = '', $parametros = array())
    {
        //incializa cURL
        $oCurl = curl_init();
        //setting da seção soap
        if ($this->proxyIP != '') {
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPORT);
            if ($this->proxyPASS != '') {
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUSER.':'.$this->proxyPASS);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            } //fim if senha proxy
        }//fim if aProxy
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_PORT, 443);
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($oCurl, CURLOPT_HEADER, 1);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_SSLCERT, $this->certKeyPath);
        curl_setopt($oCurl, CURLOPT_SSLKEY, $this->priKeyPath);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        if ($data != '') {
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($parametros)) {
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parametros);
        }
        //inicia a conexão
        $resposta = curl_exec($oCurl);
        //obtem as informações da conexão
        $info = curl_getinfo($oCurl);
        //carrega os dados para debug
        $this->zDebug($info, $data, $resposta);
        $this->errorCurl = curl_error($oCurl);
        //fecha a conexão
        curl_close($oCurl);
        //retorna
        return $resposta;
    }
    
    /**
     * zDebug
     * @param array $info
     * @param string $data
     * @param string $resposta
     */
    private function zDebug($info = array(), $data = '', $resposta = '')
    {
        $this->infoCurl["url"] = $info["url"];
        $this->infoCurl["content_type"] = $info["content_type"];
        $this->infoCurl["http_code"] = $info["http_code"];
        $this->infoCurl["header_size"] = $info["header_size"];
        $this->infoCurl["request_size"] = $info["request_size"];
        $this->infoCurl["filetime"] = $info["filetime"];
        $this->infoCurl["ssl_verify_result"] = $info["ssl_verify_result"];
        $this->infoCurl["redirect_count"] = $info["redirect_count"];
        $this->infoCurl["total_time"] = $info["total_time"];
        $this->infoCurl["namelookup_time"] = $info["namelookup_time"];
        $this->infoCurl["connect_time"] = $info["connect_time"];
        $this->infoCurl["pretransfer_time"] = $info["pretransfer_time"];
        $this->infoCurl["size_upload"] = $info["size_upload"];
        $this->infoCurl["size_download"] = $info["size_download"];
        $this->infoCurl["speed_download"] = $info["speed_download"];
        $this->infoCurl["speed_upload"] = $info["speed_upload"];
        $this->infoCurl["download_content_length"] = $info["download_content_length"];
        $this->infoCurl["upload_content_length"] = $info["upload_content_length"];
        $this->infoCurl["starttransfer_time"] = $info["starttransfer_time"];
        $this->infoCurl["redirect_time"] = $info["redirect_time"];
        //coloca as informações em uma variável
        $txtInfo ="";
        foreach ($info as $key => $content) {
            if (is_string($content)) {
                $txtInfo .= strtoupper($key).'='.$content."\n";
            }
        }
        //carrega a variavel debug
        $this->soapDebug = $data."\n\n".$txtInfo."\n".$resposta;
    }
    
    /**
     * zLimpaMsg
     * 
     * @param string $msg
     * @return string
     */
    private function zLimpaMsg($msg)
    {
        $nmsg = str_replace(array("\n","\r","\t"), array('','',''), $msg);
        $nnmsg = str_replace('> ', '>', $nmsg);
        if (strpos($nnmsg, '> ')) {
            $this->zLimpaMsg((string) $nnmsg);
        }
        return $nnmsg;
    }
}//fim da classe CurlSoap
