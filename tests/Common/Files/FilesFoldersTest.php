<?php

/**
 * Class FilesFoldersTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use Common\Files\FilesFolders;

class FilesFoldersTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFoldersSuccess()
    {
        $folderBase = dirname(dirname(dirname(__FILE__))) . '/fixtures/NFe';
        $folders = new FilesFolders();
        $resp = $folders->createFolders($folderBase);
        $this->assertTrue($resp);
        $resp = $folders->removeFolder($folderBase);
        $this->assertTrue($resp);
    }
    
    public function testCreateFoldersFail()
    {
        $folderBase = '/root';
        $folders = new FilesFolders();
        try {
            $resp = $folders->createFolders($folderBase);
        } catch (RuntimeException $expected) {
            return;
        }
        $this->fail('Teste de Criação de Diretório - A excessão esperada não foi disparada.');
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
        $folders = new FilesFolders();
        $aList = $folders->listDir($folderBase, '*.pem', 'false');
        $this->assertEquals($aList, $files);
    }
    
    public function testListDirFail()
    {
        $folderBase = '/root';
        $folders = new FilesFolders();
        try {
            $aList = $folders->listDir($folderBase, '*.*', 'false');
        } catch (RuntimeException $expected) {
            return;
        }
        $this->fail('Teste da Listagem do Diretório - A excessão esperada não foi disparada.');
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
        $folders = new FilesFolders();
        $respHtml = '';
        $resp = $folders->writeTest($folderBase, 'Test', $respHtml);
        $this->assertTrue($resp);
        $this->assertEquals($htmlStandard, $respHtml);
    }
}
