<?php
/**
 * Class DomTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\Dom\Dom;

class DomTest extends PHPUnit_Framework_TestCase
{
    public $dom;
    
    public function __construct()
    {
        $this->dom = new Dom();
        $this->dom->formatOutput = false;
        $this->dom->preserveWhiteSpace = false;
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage DOMDocument::loadXML(): Start tag expected, '<' not found in Entity, line: 1
     * 
     * @expectedException NFePHP\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage O arquivo indicado não é um XML!
     */
    public function testFailLoadXMLString()
    {
        $this->dom->loadXMLString('para acionar exception');
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage DOMDocument::loadXML(): Start tag expected, '<' not found in Entity, line: 1
     */
    public function testFailLoadXMLFile()
    {
        $filePath = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem';
        $this->dom->loadXMLFile($filePath);
    }
    
    public function testLoadXMLFile()
    {
        $filePath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/35101158716523000119550010000000011003000000-nfeSigned.xml';
        $xml = file_get_contents($filePath);
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml);
        $xml = $dom->saveXML();
        $this->dom->loadXMLFile($filePath);
        $xml1 = $this->dom->saveXML();
        $this->assertEquals($xml, $xml1);
    }
    
    public function testGetChave()
    {
        $chave = '35101158716523000119550010000000011003000000';
        $filePath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/35101158716523000119550010000000011003000000-nfeSigned.xml';
        $this->dom->loadXMLFile($filePath);
        $chave1 = $this->dom->getChave('infNFe');
        $this->assertEquals($chave, $chave1);
    }
    
    public function testGetNodeValue()
    {
        $nodevalue = 'EMITIDO NOS TERMOS DO ARTIGO 400-C DO DECRETO 48042/03 SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637';
        $filePath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/35101158716523000119550010000000011003000000-nfeSigned.xml';
        $this->dom->loadXMLFile($filePath);
        $nodevalue1 = $this->dom->getNodeValue('infAdFisco');
        $this->assertEquals($nodevalue, $nodevalue1);
    }
    
    public function testGetNode()
    {
        $node = '<ide><cUF>35</cUF><cNF>00300000</cNF><natOp>VENDA</natOp><indPag>0</indPag><mod>55</mod><serie>1</serie><nNF>1</nNF><dEmi>2010-11-02</dEmi><tpNF>1</tpNF><cMunFG>3550308</cMunFG><tpImp>1</tpImp><tpEmis>1</tpEmis><cDV>0</cDV><tpAmb>2</tpAmb><finNFe>1</finNFe><procEmi>3</procEmi><verProc>2.0.3</verProc></ide>';
        $filePath = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/35101158716523000119550010000000011003000000-nfeSigned.xml';
        $this->dom->loadXMLFile($filePath);
        $node1 = $this->dom->saveXML($this->dom->getNode('ide'));
        $this->assertEquals($node, $node1);
    }
    
    
    public function testAddChild()
    {
        $xml = '<test></test>';
        $this->dom->loadXMLString($xml);
        $parent = $this->dom->getNode('test');
        $name = 'num';
        $content = '1';
        $obrigatorio = true;
        $descricao = 'teste';
        $force = false;
        $xml = "<?xml version=\"1.0\"?><test><num>1</num></test>";
        $this->dom->addChild($parent, $name, $content, $obrigatorio, $descricao, $force);
        $xml1 = str_replace("\n", "", $this->dom->saveXML());
        $this->assertEquals($xml, $xml1);
    }
    
    public function testFailAddChild()
    {
        $name = 'num';
        $descricao = 'teste';
        $erros[] = array(
            "tag" => $name,
            "desc" => $descricao,
            "erro" => "Preenchimento Obrigatório!"
        );
        $xml = '<test></test>';
        $this->dom->loadXMLString($xml);
        $parent = $this->dom->getNode('test');
        $content = '';
        $obrigatorio = true;
        $force = false;
        $this->dom->addChild($parent, $name, $content, $obrigatorio, $descricao, $force);
        $erros1 = $this->dom->erros;
        $this->assertEquals($erros, $erros1);
    }

    /**
     * @expectedException NFePHP\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage falha de teste
     */
    public function testFailAppChild()
    {
        $parent = '';
        $this->dom->appChild($parent, '', 'falha de teste');
        
    }

    public function testAppChild()
    {
        $xml = '<test></test>';
        $this->dom->loadXMLString($xml);
        $child = $this->dom->createElement('num', '1');
        $parent = $this->dom->getNode('test');
        $this->dom->appChild($parent, $child, '');
        $xml = '<?xml version="1.0"?><test><num>1</num></test>';
        $xml1 = str_replace("\n", "", $this->dom->saveXML());
        $this->assertEquals($xml, $xml1);
    }
}
