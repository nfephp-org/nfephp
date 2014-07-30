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
        'schemes' => 'PL_008d',
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
    public function testExceptionAoListarArquivos()
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
    public function testExceptionArquivoNaoLocalizadoNoMetodoVerifyNfe()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $xmlNFe = './35101158716523000119550010000000011003000000-nfe.xml';
        $tool->verifyNFe($xmlNFe);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Assinatura não confere!! O conteúdo do XML não confere com o Digest Value.
     */
    public function testExceptionAssinaturaNaoConfereComDigestValueNoMetodoVerifyNfe()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $xmlNFe = PATH_ROOT . 'exemplos/xml/35101158716523000119550010000000011003000000-nfe.xml';
        $tool->verifyNFe($xmlNFe);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Erro cStat está vazio.
     */
    public function testExceptionCampoCstatVazioNoMetodoVerifyNfe()
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
            . '<cStat></cStat>'
            . '<xMotivo>Autorizado o Uso</xMotivo>'
            . '<nProt>311100000046263</nProt>'
            . '<digVal>DGTa0m6/dOui5S46nfHyqifBZ1U=</digVal>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlNFe = PATH_ROOT . 'exemplos/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $tool->verifyNFe($xmlNFe);
    }

    public function testMensagemDeAutorizadoOUsoNoMetodoVerifyNfe()
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
    public function testMensagemDeUsoDenegadoNoMetodoVerify()
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
    public function testMensagemDeCancelamentoDeNfeHomologadoNoMetodoVerify()
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

    public function testAdicionaProtocoloAutorizadoOUsoDaNfe()
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

    public function testAdicionaProtocoloEventoCancelamentoRegistrado()
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

    public function testAdicionaProtocoloCancelamentoDeNfeHomologado()
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

    public function testValidarArquivoXmlDeNfeSemProtocoloComSchemaPl008d310()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $xsdFile = __DIR__ . '/../../schemes/PL_008d/nfe_v3.10.xsd';
        $this->assertTrue($tool->validXML($xmlNFe, $xsdFile));
    }

    public function testValidarArquivoXmlDeNfeSemProtocoloSemInformarSchema()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml';
        $this->assertTrue($tool->validXML($xmlNFe, ''));
    }

    public function testValidarConteudoXmlDeNfeSemProtocoloSemInformarSchema()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = file_get_contents(__DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml');
        $this->assertTrue($tool->validXML($xmlNFe, ''));
    }

    public function testValidarArquivoXmlDeNfeComProtocoloSemInformarSchema()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = file_get_contents(__DIR__ . './../fixtures/xml/11101284613439000180550010000004881093997017-nfeProt.xml');
        $this->assertTrue($tool->validXML($xmlNFe, ''));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Você deve passar o conteudo do xml assinado como parâmetro ou o caminho completo até o arquivo.
     */
    public function testExceptionAoValidarArquivoNaoExiste()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $this->assertTrue($tool->validXML('', ''));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Elemento 'dhEmi': [Erro 'Layout'] O valor '2014-02-02T08:00:00'
     */
    public function testExceptionAoValidarArquivoComFormatoInvalido()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfeError.xml';
        $this->assertTrue($tool->validXML($xmlNFe, ''));
    }

    public function testArquivoComFormatoInvalido()
    {
        $tool = new ToolsNFePHP($this->configTest);

        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfeError.xml';
        $this->assertFalse($tool->validXML($xmlNFe, ''));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Erro na localização do schema xsd.
     */
    public function testExceptionAoValidarArquivoSchemaNaoLocalizado()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-retEvento.xml';
        $this->assertTrue($tool->validXML($xmlNFe, ''));
    }

    public function testAssinarArquivoXml()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/35101158716523000119550010000000011003000000-nfe.xml';
        $xmlNFe = $tool->signXML($xmlNFe, 'infNFe');

        $expectedDOM = new DOMDocument('1.0', 'UTF-8');
        $expectedDOM->load(__DIR__ . '/../fixtures/xml/35101158716523000119550010000000011003000000-nfeSigned.xml');

        $actualDOM = new DOMDocument('1.0', 'UTF-8');
        $actualDOM->loadXML($xmlNFe);

        $this->assertEquals($expectedDOM, $actualDOM);
    }

    public function testAssinarConteudoXml()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = file_get_contents(__DIR__ . '/../fixtures/xml/35101158716523000119550010000000011003000000-nfe.xml');
        $xmlNFe = $tool->signXML($xmlNFe, 'infNFe');

        $expectedDOM = new DOMDocument('1.0', 'UTF-8');
        $expectedDOM->load(__DIR__ . '/../fixtures/xml/35101158716523000119550010000000011003000000-nfeSigned.xml');

        $actualDOM = new DOMDocument('1.0', 'UTF-8');
        $actualDOM->loadXML($xmlNFe);

        $this->assertEquals($expectedDOM, $actualDOM);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage A tag < infoNFe > não existe no XML!!
     */
    public function testExceptionAoAssinarArquivoXmlTagNaoEncontrada()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/35101158716523000119550010000000011003000000-nfe.xml';
        $tool->signXML($xmlNFe, 'infoNFe');
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Uma tag deve ser indicada para que seja assinada!!
     */
    public function testExceptionAoAssinarArquivoXmlTagNaoInformada()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $xmlNFe = __DIR__ . '/../fixtures/xml/35101158716523000119550010000000011003000000-nfe.xml';
        $tool->signXML($xmlNFe, '');
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Um xml deve ser passado para que seja assinado!!
     */
    public function testExceptionAoAssinarArquivoXmlArquivoOuConteudoNaoInformado()
    {
        $tool = new ToolsNFePHP($this->configTest, 1, true);

        $tool->signXML('', 'infNFe');
    }
}
