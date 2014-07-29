<?php

/**
 * Class ToolsNFePHPTest
 * @author Antonio Spinelli <tonicospinelli85@gmail.com>
 */
class ToolsNFePHPTest extends PHPUnit_Framework_TestCase
{

    protected $configTest = array(
        'ambiente' => 2,
        'empresa' => 'NFePHP Community Test',
        'UF' => 'SP',
        'cnpj' => '12345678900001',
        'certName' => 'certificado_teste.pfx',
        'keyPass' => 'associacao',
        'passPhrase' => '',
        'arquivosDir' => './folder',
        'arquivoURLxml' => 'nfe_ws3_mod55.xml',
        'baseurl' => '',
        'danfeLogo' => '',
        'danfeLogoPos' => '',
        'danfeFormato' => '',
        'danfePapel' => '',
        'danfeCanhoto' => '',
        'danfeFonte' => '',
        'danfePrinter' => '',
        'schemes' => '',
        'certsDir' => '',
        'proxyIP' => '',
        'proxyPORT' => '',
        'proxyUSER' => '',
        'proxyPASS' => '',
        'mailFROM' => '',
        'mailHOST' => '',
        'mailUSER' => '',
        'mailPASS' => '',
        'mailPROTOCOL' => '',
        'mailFROMmail' => '',
        'mailFROMname' => '',
        'mailREPLYTOmail' => '',
        'mailREPLYTOname' => '',
    );

    public function setUp()
    {
        $this->configTest['arquivosDir'] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'folder';

        if (!is_dir($this->configTest['arquivosDir'])) {
            mkdir($this->configTest['arquivosDir'], 0777);
        }
    }

    public function tearDown()
    {
        if (!is_writable($this->configTest['arquivosDir'])) {
            chmod($this->configTest['arquivosDir'], 0777);
            rmdir($this->configTest['arquivosDir']);
        }
    }


    public function testConsigoInstanciarToolsNfePhp()
    {
        $tool = new ToolsNFePHP();
        $this->assertInstanceOf('ToolsNFePHP', $tool);
    }

    public function testPassandoConfiguracao()
    {
        $tool = new ToolsNFePHP($this->configTest);
        $this->assertInstanceOf('ToolsNFePHP', $tool);
    }

    public function testListandoODiretorioCorrente()
    {
        $tool = new ToolsNFePHP($this->configTest);

        $lista = $tool->listDir('./', '*');
        $this->assertFalse($lista);

        $lista = $tool->listDir(__DIR__ . DIRECTORY_SEPARATOR, '*');
        $this->assertEquals(__FILE__, __DIR__ . DIRECTORY_SEPARATOR . reset($lista));

        $lista = $tool->listDir(__DIR__ . DIRECTORY_SEPARATOR, '*', true);
        $this->assertEquals(__FILE__, reset($lista));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Falha! sem permissão de leitura no diretorio escolhido.
     */
    public function testListandoODiretorioEGerandoExeption()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);

        $lista = $tool->listDir('./', '*');
    }

    public function testAtivandoContingenciaSvcanESvcrs()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->ativaContingencia();
        $this->assertTrue($tool->enableSVCAN);
        $this->assertFalse($tool->enableSVCRS);

        $tool->ativaContingencia(ToolsNFePHP::CONTINGENCIA_SVCRS);
        $this->assertTrue($tool->enableSVCRS);
        $this->assertFalse($tool->enableSVCAN);

        $tool->ativaContingencia(ToolsNFePHP::CONTINGENCIA_SVCAN);
        $this->assertTrue($tool->enableSVCAN);
        $this->assertFalse($tool->enableSVCRS);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Arquivo não localizado!!
     */
    public function testVerifyNfeArquivoNaoEncontrado()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $xmlNFe = PATH_ROOT . '/35101158716523000119550010000000011003000000-nfe.xml';
        $tool->verifyNFe($xmlNFe);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Assinatura não confere!! O conteúdo do XML não confere com o Digest Value.
     */
    public function testVerifyNfeProblemaComAssinatura()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $xmlNFe = PATH_ROOT . 'exemplos/xml/35101158716523000119550010000000011003000000-nfe.xml';
        $tool->verifyNFe($xmlNFe);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Erro cStat está vazio.
     */
    public function testVerifyNfeExceptionCstatVazio()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $xmlNFe = PATH_ROOT . 'exemplos/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $tool->verifyNFe($xmlNFe);
    }

    public function testVerifyNfeAutorizadoOUso()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infProt>'
            . '<dhRecbto>' . date('Y-m-d\TH:i:s') . '</dhRecbto>'
            . '<cStat>100</cStat>'
            . '<xMotivo>Autorizado o Uso</xMotivo>'
            . '<nProt>311100000046263</nProt>'
            . '<digVal>DGTa0m6/dOui5S46nfHyqifBZ1U=</digVal>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlNFe = PATH_ROOT . 'exemplos/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $retorno = $tool->verifyNFe($xmlNFe);
        $this->assertTrue($retorno);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage NF não aprovada no SEFAZ!! cStat =110 - Uso Denegado
     */
    public function testVerifyNfeUsoDenegado()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infProt>'
            . '<cStat>110</cStat>'
            . '<xMotivo>Uso Denegado</xMotivo>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlNFe = PATH_ROOT . 'exemplos/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $retorno = $tool->verifyNFe($xmlNFe);
        $this->assertFalse($retorno);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage NF não aprovada no SEFAZ!! cStat =101 - Cancelamento de NF-e Homologado
     */
    public function testVerifyNfeCancelamentoDeNfeHomologado()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infProt>'
            . '<cStat>101</cStat>'
            . '<xMotivo>Cancelamento de NF-e Homologado</xMotivo>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlNFe = PATH_ROOT . 'exemplos/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $retorno = $tool->verifyNFe($xmlNFe);
        $this->assertFalse($retorno);
    }

    public function testAdicionaProtocolo()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);
        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $xmlProtNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-protNFe.xml';
        $nfeProtocolada = $tool->addProt($xmlNFe, $xmlProtNFe);

        $expectedDOM = new DOMDocument('1.0', 'UTF-8');
        $expectedDOM->load(__DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfeProt.xml');

        $actualDOM = new DOMDocument('1.0', 'UTF-8');
        $actualDOM->loadXML($nfeProtocolada);

        $this->assertEquals($expectedDOM, $actualDOM);
    }

    public function testAdicionaProtocoloEventoCancelamento()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);
        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $xmlProtNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-retEvento.xml';
        $nfeProtocolada = $tool->addProt($xmlNFe, $xmlProtNFe);

        $expectedDOM = new DOMDocument('1.0', 'UTF-8');
        $expectedDOM->load(__DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfeRetEvento.xml');

        $actualDOM = new DOMDocument('1.0', 'UTF-8');
        $actualDOM->loadXML($nfeProtocolada);

        $this->assertEquals($expectedDOM, $actualDOM);
    }

    public function testAdicionaProtocoloCancelamento()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);
        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $xmlProtNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-retCancNFe.xml';
        $nfeProtocolada = $tool->addProt($xmlNFe, $xmlProtNFe);

        $expectedDOM = new DOMDocument('1.0', 'UTF-8');
        $expectedDOM->load(__DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfeCancNFe.xml');

        $actualDOM = new DOMDocument('1.0', 'UTF-8');
        $actualDOM->loadXML($nfeProtocolada);

        $this->assertEquals($expectedDOM, $actualDOM);
    }

    public function testValidXml()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $xsdFile = __DIR__. '/../../schemes/PL_008d/nfe_v3.10.xsd';
        $this->assertTrue($tool->validXML($xmlNFe, $xsdFile));
    }
}
