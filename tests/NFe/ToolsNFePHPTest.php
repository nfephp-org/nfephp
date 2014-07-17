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
}
