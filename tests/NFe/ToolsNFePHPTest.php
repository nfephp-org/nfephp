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
        $this->assertFalse($tool->enableSVCAN);
        $this->assertFalse($tool->enableSVCRS);

        $tool->ativaContingencia('BA');
        $this->assertTrue($tool->enableSVCRS);
        $this->assertFalse($tool->enableSVCAN);

        $tool->ativaContingencia('SP');
        $this->assertTrue($tool->enableSVCAN);
        $this->assertFalse($tool->enableSVCRS);
        
        $tool->desativaContingencia();
        $this->assertFalse($tool->enableSVCAN);
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
     * @expectedExceptionMessage Erro inesperado, cStat esta vazio!
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

    public function testStatusServicoEmOperacao()
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
            . '<tpAmb>2</tpAmb>'
            . '<verAplic>2</verAplic>'
            . '<cUF>2</cUF>'
            . '<cStat>107</cStat>'
            . '<tMed>teste</tMed>'
            . '<dhRecbto>2014-07-29T21:52:10-03:00</dhRecbto>'
            . '<dhRetorno>2014-07-29T21:52:12-03:00</dhRetorno>'
            . '<xMotivo>Em operação</xMotivo>'
            . '<xObs></xObs>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlStatus = $tool->statusServico('SP', 2);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xmlStatus);

        $this->assertEquals('107', $dom->getElementsByTagName('cStat')->item(0)->nodeValue);
    }

    public function testStatusServicoParalisdoSemPrvisao()
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
            . '<tpAmb>2</tpAmb>'
            . '<verAplic>2</verAplic>'
            . '<cUF>2</cUF>'
            . '<cStat>109</cStat>'
            . '<tMed>teste</tMed>'
            . '<dhRecbto>2014-07-29T21:52:10-03:00</dhRecbto>'
            . '<dhRetorno>2014-07-29T21:52:12-03:00</dhRetorno>'
            . '<xMotivo>Em operação</xMotivo>'
            . '<xObs></xObs>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlStatus = $tool->statusServico('SP', 2);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xmlStatus);

        $this->assertEquals('109', $dom->getElementsByTagName('cStat')->item(0)->nodeValue);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Não houve retorno Soap verifique a mensagem de erro e o debug!!
     */
    public function testExceptionStatusServicoErroNoRetorno()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body></xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $tool->statusServico('SP', 2);
    }

    public function testConsultarCadastroComUmaOcorrencia()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infCons>'
            . '<cStat>111</cStat>'
            . '<xMotivo>Consulta cadastro com uma ocorrência</xMotivo>'
            . '<infCad>'
            . '<CNPJ>1234567890001</CNPJ>'
            . '<CPF>12312312312</CPF>'
            . '<IE>123123</IE>'
            . '<UF>SP</UF>'
            . '<cSit></cSit>'
            . '<indCredNFe></indCredNFe>'
            . '<indCredCTe></indCredCTe>'
            . '<xNome></xNome>'
            . '<xRegApur></xRegApur>'
            . '<CNAE></CNAE>'
            . '<dIniAtiv></dIniAtiv>'
            . '<dUltSit></dUltSit>'
            . '<ender>'
            . '<xLgr></xLgr>'
            . '<nro></nro>'
            . '<xCpl></xCpl>'
            . '<xBairro></xBairro>'
            . '<cMun></cMun>'
            . '<xMun></xMun>'
            . '<CEP></CEP>'
            . '</ender>'
            . '</infCad>'
            . '</infCons>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $resultado = $tool->consultaCadastro('SP', '1234567890001', '123123', '123123123');

        $this->assertTrue(is_array($resultado));
        $this->assertArrayHasKey('cStat', $resultado);
        $this->assertEquals('111', $resultado['cStat']);
        $this->assertArrayHasKey('xMotivo', $resultado);
        $this->assertEquals('Consulta cadastro com uma ocorrência', $resultado['xMotivo']);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Versão do arquivo XML não suportada
     */
    public function testExceptionAoConsultarCadastroErroVersaoDoArquivoNaoSuportada()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infCons>'
            . '<cStat>239</cStat>'
            . '<xMotivo>Versão do arquivo XML não suportada</xMotivo>'
            . '<versaoDados>3.10</versaoDados>'
            . '<infCad>'
            . '<CNPJ>1234567890001</CNPJ>'
            . '<CPF>12312312312</CPF>'
            . '<IE>123123</IE>'
            . '<UF>SP</UF>'
            . '<cSit></cSit>'
            . '<indCredNFe></indCredNFe>'
            . '<indCredCTe></indCredCTe>'
            . '<xNome></xNome>'
            . '<xRegApur></xRegApur>'
            . '<CNAE></CNAE>'
            . '<dIniAtiv></dIniAtiv>'
            . '<dUltSit></dUltSit>'
            . '<ender>'
            . '<xLgr></xLgr>'
            . '<nro></nro>'
            . '<xCpl></xCpl>'
            . '<xBairro></xBairro>'
            . '<cMun></cMun>'
            . '<xMun></xMun>'
            . '<CEP></CEP>'
            . '</ender>'
            . '</infCad>'
            . '</infCons>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $resultado = $tool->consultaCadastro('SP', '1234567890001', '123123', '123123123');

        $this->assertTrue(is_array($resultado));
        $this->assertArrayHasKey('cStat', $resultado);
        $this->assertEquals('239', $resultado['cStat']);
        $this->assertArrayHasKey('xMotivo', $resultado);
        $this->assertEquals('Versão do arquivo XML não suportada', $resultado['xMotivo']);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Rejeição: CNPJ do emitente inválido
     */
    public function testExceptionAoConsultarCadastroRejeicaoCnpjDoEmitenteInvalido()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infCons>'
            . '<cStat>207</cStat>'
            . '<xMotivo>Rejeição: CNPJ do emitente inválido</xMotivo>'
            . '</infCons>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $resultado = $tool->consultaCadastro('SP', '1234567890001', '123123', '123123123');

        $this->assertTrue(is_array($resultado));
        $this->assertArrayHasKey('cStat', $resultado);
        $this->assertEquals('111', $resultado['cStat']);
        $this->assertArrayHasKey('xMotivo', $resultado);
        $this->assertEquals('Consulta cadastro com uma ocorrência', $resultado['xMotivo']);
    }

    public function testAutorizaNfeLoteRecebidoComSucesso()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlProtocolo = '<?xml version="1.0" encoding="utf-8"?>'
            . '<response xmlns:xs="http://www.w3.org/2003/05/soap-envelope">'
            . '<xs:Body>'
            . '<infCons>'
            . '<cStat>103</cStat>'
            . '<xMotivo>Lote recebido com sucesso</xMotivo>'
            . '<tpAmb>2</tpAmb>'
            . '<verAplic>123</verAplic>'
            . '<cUF>SP</cUF>'
            . '</infCons>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlNFe = file_get_contents(__DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml');
        $retorno = array();

        $tool->autoriza($xmlNFe, '123', $retorno, 0);

        $this->assertTrue(is_array($retorno));
        $this->assertArrayHasKey('bStat', $retorno);
        $this->assertTrue($retorno['bStat']);
        $this->assertArrayHasKey('cStat', $retorno);
        $this->assertEquals('103', $retorno['cStat']);
        $this->assertArrayHasKey('xMotivo', $retorno);
        $this->assertEquals('Lote recebido com sucesso', $retorno['xMotivo']);
    }

    public function testAutorizaNfeLoteProcessadoPodendoTerOuNaoProtnfe()
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
            . '<cStat>104</cStat>'
            . '<xMotivo>Lote processado, podendo ter ou não o protNFe</xMotivo>'
            . '<tpAmb>2</tpAmb>'
            . '<verAplic>123</verAplic>'
            . '<cUF>SP</cUF>'
            . '<protNFe versao="3.10">'
            . '<chNFe>11101284613439000180550010000004881093997017</chNFe>'
            . '<nProt>311100000046263</nProt>'
            . '</protNFe>'
            . '</infProt>'
            . '</xs:Body>'
            . '</response>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlProtocolo));

        $xmlNFe = file_get_contents(__DIR__ . '/../fixtures/xml/11101284613439000180550010000004881093997017-nfe.xml');
        $retorno = array();

        $tool->autoriza($xmlNFe, '123', $retorno, 1, false);

        $this->assertTrue(is_array($retorno));

        $this->assertArrayHasKey('bStat', $retorno);
        $this->assertTrue($retorno['bStat']);

        $this->assertArrayHasKey('cStat', $retorno);
        $this->assertEquals('104', $retorno['cStat']);

        $this->assertArrayHasKey('xMotivo', $retorno);
        $this->assertEquals('Lote processado, podendo ter ou não o protNFe', $retorno['xMotivo']);

        $this->assertArrayHasKey('protNFe', $retorno);
        $this->assertArrayHasKey('infProt', $retorno['protNFe']);
        $this->assertArrayHasKey('nProt', $retorno['protNFe']['infProt']);
        $this->assertEquals('311100000046263', $retorno['protNFe']['infProt']['nProt']);
    }

    public function testInutilizacaoNfeComSucesso()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlRetornoInutilizacao = '<?xml version="1.0" encoding="utf-8"?>'
            . '<retInutNFe versao="3.10">'
            . '<infInut>'
            . '<tpAmb>2</tpAmb>'
            . '<verAplic>2.0</verAplic>'
            . '<cStat>102</cStat>'
            . '<xMotivo>Inutilizacao de numero homologado</xMotivo>'
            . '<cUF>SP</cUF>'
            . '<ano>14</ano>'
            . '<CNPJ>11222333444455</CNPJ>'
            . '<mod>65</mod>'
            . '<serie>1</serie>'
            . '<nNFIni>1</nNFIni>'
            . '<nNFFin>10</nNFFin>'
            . '<dhRecbto>' . date('Y-m-d\TH:i:s') . '</dhRecbto>'
            . '<nProt>123</nProt>'
            . '</infInut>'
            . '</retInutNFe>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlRetornoInutilizacao));

        $retorno = array();

        $tool->inutNF(14, 1, 1, 10, str_repeat('Testando ', 10), '', $retorno);

        $this->assertTrue(is_array($retorno));

        $this->assertArrayHasKey('bStat', $retorno);
        $this->assertTrue($retorno['bStat']);

        $this->assertArrayHasKey('cStat', $retorno);
        $this->assertEquals('102', $retorno['cStat']);

        $this->assertArrayHasKey('xMotivo', $retorno);
        $this->assertEquals('Inutilizacao de numero homologado', $retorno['xMotivo']);

        $this->assertArrayHasKey('nProt', $retorno);
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage Não foi passado algum dos parametos necessários ANO= inicio= fim= justificativa=.
     */
    public function testExceptionAoInutilizarNfeNenhumParametroInformado()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF();
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage A justificativa deve ter pelo menos 15 digitos!!
     */
    public function testExceptionAoInutilizarNfeJustificativaMuitoCurta()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(14, 1, 1, 2, 'Teste');
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage A justificativa deve ter no máximo 255 digitos!!
     */
    public function testExceptionAoInutilizarNfeJustificativaMuitoLonga()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(14, 1, 1, 2, str_repeat('.', 256));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage O ano tem mais de 2 digitos. Corrija e refaça o processo!!
     */
    public function testExceptionAoInutilizarNfeAnoComMaisDe2Digitos()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(2014, 1, 1, 2, str_repeat('.', 20));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage O ano tem menos de 2 digitos. Corrija e refaça o processo!!
     */
    public function testExceptionAoInutilizarNfeAnoComMenosDe2Digitos()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(4, 1, 1, 2, str_repeat('.', 20));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage O campo serie está errado: 1111. Corrija e refaça o processo!!
     */
    public function testExceptionAoInutilizarNfeCampoDaSerieErrado()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(14, 1111, 1, 2, str_repeat('.', 20));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage O campo numero inicial está errado: 1112223334. Corrija e refaça o processo!!
     */
    public function testExceptionAoInutilizarNfeNumeroInicialErrado()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(14, 1, 1112223334, 2, str_repeat('.', 20));
    }

    /**
     * @expectedException nfephpException
     * @expectedExceptionMessage O campo numero final está errado: 1112223334. Corrija e refaça o processo!!
     */
    public function testExceptionAoInutilizarNfeNumeroFinalErrado()
    {
        $tool = new ToolsNFePHP($this->configTest, 2, true);
        $tool->inutNF(14, 1, 1, 1112223334, str_repeat('.', 20));
    }


    public function testConsultaRelacaoDocumentosDestinadosAUmCnpj()
    {
        $mockBuilder = $this->getMockBuilder('ToolsNFePHP');
        $mockBuilder->setConstructorArgs(array($this->configTest, 1, true));
        $mockBuilder->setMethods(array('pSendSOAP'));
        /** @var ToolsNFePHP $tool */
        $tool = $mockBuilder->getMock();
        $xmlRetornoInutilizacao = '<?xml version="1.0" encoding="utf-8"?>'
            . '<retConsNFeDest versao="3.10">'
            . '<tpAmb>2</tpAmb>'
            . '<verAplic>2.0</verAplic>'
            . '<cStat>138</cStat>'
            . '<xMotivo>Documento localizado para o Destinatário</xMotivo>'
            . '<ultNSU>0</ultNSU>'
            . '<indCont>1</indCont>'
            . '<ret>'
            . '<resNFe>'
            . '<NSU>123</NSU>'
            . '<chNFe>123</chNFe>'
            . '<CNPJ>123</CNPJ>'
            . '<xNome>123</xNome>'
            . '<dhEmi>2014-08-31T09:00:00</dhEmi>'
            . '<tpNF>0</tpNF>'
            . '<dhRecbto>2014-08-31T09:00:00</dhRecbto>'
            . '<cSitNFe>1</cSitNFe>'
            . '<cSitConf>1</cSitConf>'
            . '</resNFe>'
            . '<resCanc>'
            . '<NSU>321</NSU>'
            . '<chNFe>321</chNFe>'
            . '<CNPJ>321</CNPJ>'
            . '<xNome>123</xNome>'
            . '<dhEmi>2014-08-31T09:00:00</dhEmi>'
            . '<tpNF>0</tpNF>'
            . '<vNF>100.00</vNF>'
            . '<dhRecbto>2014-08-31T09:00:00</dhRecbto>'
            . '<cSitNFe>0</cSitNFe>'
            . '<cSitConf>0</cSitConf>'
            . '</resCanc>'
            . '<resCCe>'
            . '<NSU>321</NSU>'
            . '<chNFe>321</chNFe>'
            . '<tpEvento>0</tpEvento>'
            . '<nSeqEvento>0</nSeqEvento>'
            . '<dhEvento>2014-08-31T09:00:00</dhEvento>'
            . '<descEvento>teste</descEvento>'
            . '<xCorrecao>0</xCorrecao>'
            . '<dhRecbto>2014-08-31T09:00:00</dhRecbto>'
            . '<tpNF>0</tpNF>'
            . '</resCCe>'
            . '</ret>'
            . '</retConsNFeDest>';
        $tool->expects($this->any())->method('pSendSOAP')->will($this->returnValue($xmlRetornoInutilizacao));

        $retorno = array();

        $tool->getListNFe(true, 0, 0, 0, '', $retorno);

        $this->assertTrue(is_array($retorno));
        $this->assertArrayHasKey('indCont', $retorno);
        $this->assertArrayHasKey('ultNSU', $retorno);
        $this->assertArrayHasKey('NFe', $retorno);

        foreach (array('NFe', 'Canc') as $tipo) {
            $this->assertCount(1, $retorno[$tipo]);
            $nfe = reset($retorno[$tipo]);
            $this->assertArrayHasKey('chNFe', $nfe);
            $this->assertArrayHasKey('NSU', $nfe);
            $this->assertArrayHasKey('CNPJ', $nfe);
            $this->assertArrayHasKey('xNome', $nfe);
            $this->assertArrayHasKey('dhEmi', $nfe);
            $this->assertArrayHasKey('dhRecbto', $nfe);
            $this->assertArrayHasKey('tpNF', $nfe);
            $this->assertArrayHasKey('cSitNFe', $nfe);
            $this->assertArrayHasKey('cSitconf', $nfe);
        }

        $this->assertCount(1, $retorno['CCe']);
        $cce = reset($retorno['CCe']);
        $this->assertArrayHasKey('chNFe', $cce);
        $this->assertArrayHasKey('NSU', $cce);
        $this->assertArrayHasKey('tpEvento', $cce);
        $this->assertArrayHasKey('nSeqEvento', $cce);
        $this->assertArrayHasKey('dhEvento', $cce);
        $this->assertArrayHasKey('dhRecbto', $cce);
        $this->assertArrayHasKey('descEvento', $cce);
        $this->assertArrayHasKey('xCorrecao', $cce);
        $this->assertArrayHasKey('tpNF', $cce);
    }
}
