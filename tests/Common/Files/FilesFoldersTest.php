<?php

/**
 * Class FilesFoldersTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\Files\FilesFolders;

class FilesFoldersTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFoldersSuccess()
    {
        $folderBase = dirname(dirname(dirname(__FILE__))) . '/fixtures/NFe';
        $resp = FilesFolders::createFolders($folderBase);
        $this->assertTrue($resp);
        $resp = FilesFolders::removeFolder($folderBase);
        $this->assertTrue($resp);
    }

     /**
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage mkdir(): Permission denied
     */
    public function testCreateFoldersFail()
    {
        $folderBase = '/root';
        FilesFolders::createFolders($folderBase);
    }
    
    public function testListDirSuccess()
    {
        $files = array(
            dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_certKEY.pem',
            dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_priKEY.pem',
            dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/99999090910270_pubKEY.pem',
            dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/certificado_pubKEY.pem'
        );
        $folderBase = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/';
        $aList = FilesFolders::listDir($folderBase, '*.pem', true);
        $this->assertEquals($aList, $files);
    }
    
    /**
     * @expectedException NFePHP\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage O diretório não existe /qualquercoisa !!!
     */
    public function testListDirFail()
    {
        $aList = array();
        $folderBase = '/qualquercoisa';
        $files = FilesFolders::listDir($folderBase, '*.*', false);
        $this->assertEquals($aList, $files);
    }
            
    public function testWriteTest()
    {
        $htmlStandard = '<tr bgcolor="#FFFFCC">'
                . '<td>Test</td>'
                . '<td bgcolor="#00CC00">'
                . '<div align="center"> Permiss&atilde;o OK</div>'
                . '</td>'
                . '<td>O diret&oacute;rio deve ter permiss&atilde;o de escrita</td>'
                . '</tr>';
        $folderBase = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs';
        $respHtml = '';
        $resp = FilesFolders::writeTest($folderBase, 'Test', $respHtml);
        $this->assertTrue($resp);
        $this->assertEquals($htmlStandard, $respHtml);
    }
    
    public function testDeveRetornarAmbienteDeHomologacaoCasoNaoForInformadoNenhumParametro()
    {
        $files = new FilesFolders();
        $this->assertEquals('homologacao', $files->getAmbiente());
    }
    
    
    /**
     * @dataProvider ambientes
     */
    public function testDeveRetornarSempreAmbienteDeProducaoCasoNaoForInformadoUmParametroValido($ambientes)
    {
        $files = new FilesFolders();
        $this->assertEquals('producao', $files->getAmbiente($ambientes));
    }
    
    public function ambientes()
    {
        return array(
            array('3'),
            array('4'),
            array('nenhum ambiente'),
        );
    }
}
