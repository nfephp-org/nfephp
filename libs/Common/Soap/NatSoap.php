<?php

namespace NFePHP\Common\Soap;

/**
 * Classe auxiliar para envio das mensagens SOAP usando SOAP nativo do PHP
 * @category   NFePHP
 * @package    NFePHP\Common\Soap
 * @copyright  Copyright (c) 2008-2014
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux dot rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Soap\CorrectedSoapClient;
use NFePHP\Common\Exception;

class NatSoap
{
    /**
     *
     * @var string
     */
    public $soapDebug = '';
    /**
     *
     * @var integer 
     */
    public $soapTimeout = 10;
    /**
     *
     * @var array 
     */
    public $aError = array();
    /**
     *
     * @var string 
     */
    public $pathWsdl = '';
    
    protected $enableSVAN = false;
    protected $enableSVRS = false;
    protected $enableSVCAN = false;
    protected $enableSVCRS = false;
    protected $enableSCAN = false; //será desativado em 12/2014
   
    private $certKEY;
    private $pubKEY;
    private $priKEY;
       
    /**
     * 
     * @param string $publicKey
     * @param string $privateKey
     * @param string $certificateKey
     * @param string $pathWsdl
     * @param integer $timeout
     * @return boolean
     */
    public function __construct($publicKey = '', $privateKey = '', $certificateKey = '', $pathWsdl = '', $timeout = 10)
    {
        try {
            if ($certificateKey == '' || $privateKey == '' || $publicKey == '') {
                $msg = 'O path para as chaves deve ser passado na instânciação da classe.';
                throw new Exception\InvalidArgumentException($msg);
            }
            if ($pathWsdl == '') {
                $msg = 'O path para os arquivos WSDL deve ser passado na instânciação da classe.';
                throw new Exception\InvalidArgumentException($msg);
            }
            $this->pubKEY = $publicKey;
            $this->priKEY = $privateKey;
            $this->certKEY = $certificateKey;
            $this->pathWsdl = $pathWsdl;
            $this->soapTimeout = $timeout;
        } catch (Exception\RuntimeException $e) {
            $this->aError[] = $e->getMessage();
            return false;
        }
    }//fim __construct
    
    /**
     * Estabelece comunicaçao com servidor SOAP 1.1 ou 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1 
     *
     * @param string $urlsefaz
     * @param string $namespace
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param integer $ambiente  tipo de ambiente 1 - produção e 2 - homologação
     * @param string $UF unidade da federação, necessário para diferenciar AM, MT e PR
     * @return mixed false se houve falha ou o retorno em xml do SEFAZ
     */
    public function send(
        $siglaUF = '',
        $namespace = '',
        $cabecalho = '',
        $dados = '',
        $metodo = '',
        $tpAmb = '2'
    ) {
        try {
            if (!class_exists("SoapClient")) {
                $msg = "A classe SOAP não está disponível no PHP, veja a configuração.";
                throw new Exception\RuntimeException($msg);
            }
            $soapFault = '';
            //ativa retorno de erros soap
            use_soap_error_handler(true);
            //versão do SOAP
            $soapver = SOAP_1_2;
            if ($tpAmb == 1) {
                $ambiente = 'producao';
            } else {
                $ambiente = 'homologacao';
            }
            $usef = "_$metodo.asmx";
            $urlsefaz = "$this->pathWsdl/$ambiente/$siglaUF$usef";
            if ($this->enableSVAN) {
                //se for SVAN montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SVAN$usef";
            }
            if ($this->enableSCAN) {
                //se for SCAN montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SCAN$usef";
            }
            if ($this->enableSVRS) {
                //se for SVRS montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SVRS$usef";
            }
            if ($this->enableSVCAN) {
                //se for SVCAN montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SVCAN$usef";
            }
            if ($this->enableSVCRS) {
                //se for SVCRS montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SVCRS$usef";
            }
            if ($this->soapTimeout == 0) {
                $tout = 999999;
            } else {
                $tout = $this->soapTimeout;
            }
            //completa a url do serviço para baixar o arquivo WSDL
            $sefazURL = $urlsefaz.'?WSDL';
            $this->soapDebug = $urlsefaz;
            $options = array(
                'encoding'      => 'UTF-8',
                'verifypeer'    => false,
                'verifyhost'    => true,
                'soap_version'  => $soapver,
                'style'         => SOAP_DOCUMENT,
                'use'           => SOAP_LITERAL,
                'local_cert'    => $this->certKEY,
                'trace'         => true,
                'compression'   => 0,
                'exceptions'    => true,
                'connection_timeout' => $tout,
                'cache_wsdl'    => WSDL_CACHE_NONE
            );
            //instancia a classe soap
            $oSoapClient = new CorrectedSoapClient($sefazURL, $options);
            //monta o cabeçalho da mensagem
            $varCabec = new SoapVar($cabecalho, XSD_ANYXML);
            $header = new SoapHeader($namespace, 'nfeCabecMsg', $varCabec);
            //instancia o cabeçalho
            $oSoapClient->__setSoapHeaders($header);
            //monta o corpo da mensagem soap
            $varBody = new SoapVar($dados, XSD_ANYXML);
            //faz a chamada ao metodo do webservices
            $resp = $oSoapClient->__soapCall($metodo, array($varBody));
            if (is_soap_fault($resp)) {
                $soapFault = "SOAP Fault: (faultcode: {$resp->faultcode}, faultstring: {$resp->faultstring})";
            }
            $resposta = $oSoapClient->__getLastResponse();
            $this->soapDebug .= "\n" . $soapFault;
            $this->soapDebug .= "\n" . $oSoapClient->__getLastRequestHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastRequest();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastResponseHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastResponse();
        } catch (Exception\RuntimeException $e) {
            $this->aError[] = $e->getMessage();
            return false;
        }
        return $resposta;
    } //fim nfeSOAP
}//fim da classe NatSoap
