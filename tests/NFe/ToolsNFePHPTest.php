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
        'arquivosDir' => '',
        'arquivoURLxml' => '',
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

    public function testConsigoInstanciarToolsNfePhp()
    {
        $tool = new ToolsNFePHP();
        $this->assertInstanceOf('ToolsNFePHP', $tool);
    }

    public function testPassandoConfiguracao()
    {
        $tool = new ToolsNFePHP($config);
        $this->assertInstanceOf('ToolsNFePHP', $tool);
    }

    public function testListDir()
    {
        $tool = new ToolsNFePHP();
    }
}
 