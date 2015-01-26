<?php

/**
 * Class CurlSoapTest
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */

use Common\Soap\CurlSoap;

class CurlSoapTest extends PHPUnit_Framework_TestCase
{
    public function testSetProxy()
    {
        $priKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_priKEY.pem';
        $pubKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_pubKEY.pem';
        $certKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem';
        $timeout = '10';
        
        $proxyIP = '192.168.1.1';
        $proxyPORT = '3168';
        $proxyUSER = 'usuario';
        $proxyPASS = 'senha';

        $aProxy['ip'] = $proxyIP;
        $aProxy['port'] = $proxyPORT;
        $aProxy['username'] = $proxyUSER;
        $aProxy['password'] = $proxyPASS;
        
        $soap = new CurlSoap($priKey, $pubKey, $certKey, $timeout);
        $soap->setProxy($proxyIP, $proxyPORT, $proxyUSER, $proxyPASS);
        $resp = $soap->getProxy();
        $this->assertEquals($resp, $aProxy);
    }
    
    public function testExceptionAoPassarCertificados()
    {
        $priKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_priKEY.pem';
        $pubKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/0000_pubKEY.pem';
        $certKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem';
        $timeout = '10';
        try {
            $soap = new CurlSoap($priKey, $pubKey, $certKey, $timeout);
        } catch (InvalidArgumentException $expected) {
            return;
        }
        $this->fail('Teste dos certificados - A excessão esperada não foi disparada.');
    }
    
    public function testGetWsdl()
    {
        $priKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_priKEY.pem';
        $pubKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_pubKEY.pem';
        $certKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem';
        $timeout = '10';
        $args = array($priKey, $pubKey, $certKey, $timeout);
        //cria uma função "FAJUTA" de comunicação com a SEFAZ
        $soap = $this->getMockBuilder('Common\Soap\CurlSoap')
            ->setConstructorArgs($args)
            ->setMethods(array('zCommCurl'))
            ->getMock();
        //estabelece retorno da chamada curl FAJUTA como se fosse uma resposta real
        $fileretornosefaz = dirname(dirname(dirname(__FILE__))) .
            '/fixtures/xml/retornoSefazGetWsdl_SP_nfeStatusServicoNF2.xml';
        $retornosefaz = file_get_contents($fileretornosefaz);
        $soap->expects($this->any())->method('zCommCurl')->will($this->returnValue($retornosefaz));
        //busca resposta
        $urlservice = 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatusservico2.asmx';
        $wsdl = $soap->getWsdl($urlservice);
        //resultado padrão
        $wsdlfile = dirname(dirname(dirname(__FILE__))) .
            '/fixtures/xml/retornoGetWsdl_SP_nfeStatusServicoNF2.asmx';
        $wsdlstd = file_get_contents($wsdlfile);
        //teste
        $this->assertEquals($wsdl, $wsdlstd);
    }
    
    public function testSendForbidden()
    {
        $priKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_priKEY.pem';
        $pubKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_pubKEY.pem';
        $certKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem';
        $timeout = '10';
        $args = array($priKey, $pubKey, $certKey, $timeout);
        //cria uma função "FAJUTA" de comunicação com a SEFAZ
        $soap = $this->getMockBuilder('Common\Soap\CurlSoap')
            ->setConstructorArgs($args)
            ->setMethods(array('zCommCurl'))
            ->getMock();
        //estabelece retorno da chamada curl FAJUTA como se fosse uma resposta real
        $fileretornosefaz = dirname(dirname(dirname(__FILE__))) .
            '/fixtures/xml/forbidden.xml';
        $retornosefaz = file_get_contents($fileretornosefaz);
        $soap->expects($this->any())->method('zCommCurl')->will($this->returnValue($retornosefaz));
        //busca resposta
        $urlservice = 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatusservico2.asmx';
        $namespace = "http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2";
        $header = '<nfeCabecMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2"><cUF>35</cUF><versaoDados>3.10</versaoDados></nfeCabecMsg>';
        $method = "nfeStatusServicoNF2";
        $body = '<nfeDadosMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2"><consStatServ xmlns="http://www.portalfiscal.inf.br/nfe" versao="3.10"><tpAmb>2</tpAmb><cUF>35</cUF><xServ>STATUS</xServ></consStatServ></nfeDadosMsg>';
        try {
            $resp = $soap->send($urlservice, $namespace, $header, $body, $method);
        } catch (RuntimeException $expected) {
            return;
        }
        $this->fail('HTTP/1.1 403 Forbidden');
    }

    public function testSendSuccess()
    {
        $priKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_priKEY.pem';
        $pubKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_pubKEY.pem';
        $certKey = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem';
        $timeout = '10';
        $args = array($priKey, $pubKey, $certKey, $timeout);
        //cria uma função "FAJUTA" de comunicação com a SEFAZ
        $soap = $this->getMockBuilder('Common\Soap\CurlSoap')
            ->setConstructorArgs($args)
            ->setMethods(array('zCommCurl'))
            ->getMock();
        //estabelece retorno da chamada curl FAJUTA como se fosse uma resposta real
        $fileretornosefaz = dirname(dirname(dirname(__FILE__))) .
            '/fixtures/xml/retornoSefaz_success_statusservico.xml';
        $retornosefaz = file_get_contents($fileretornosefaz);
        $soap->expects($this->any())->method('zCommCurl')->will($this->returnValue($retornosefaz));
        //busca resposta
        $urlservice = 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatusservico2.asmx';
        $namespace = "http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2";
        $header = '<nfeCabecMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2"><cUF>35</cUF><versaoDados>3.10</versaoDados></nfeCabecMsg>';
        $method = "nfeStatusServicoNF2";
        $body = '<nfeDadosMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2"><consStatServ xmlns="http://www.portalfiscal.inf.br/nfe" versao="3.10"><tpAmb>2</tpAmb><cUF>35</cUF><xServ>STATUS</xServ></consStatServ></nfeDadosMsg>';
        $respStd = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Header><nfeCabecMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2"><cUF>35</cUF><versaoDados>3.10</versaoDados></nfeCabecMsg></soap:Header><soap:Body><nfeStatusServicoNF2Result xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2"><retConsStatServ versao="3.10" xmlns="http://www.portalfiscal.inf.br/nfe"><tpAmb>2</tpAmb><verAplic>SP_NFE_PL_008d</verAplic><cStat>107</cStat><xMotivo>Serviço em Operação</xMotivo><cUF>35</cUF><dhRecbto>2014-12-01T15:28:29-02:00</dhRecbto><tMed>1</tMed></retConsStatServ></nfeStatusServicoNF2Result></soap:Body></soap:Envelope>';
        $resp = $soap->send($urlservice, $namespace, $header, $body, $method);
        $this->assertEquals($resp, $respStd);
    }
}
