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

     /**
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage mkdir(): Permission denied
     */
    public function testCreateFoldersFail()
    {
        $folderBase = '/root';
        $folders = new FilesFolders();
        $folders->createFolders($folderBase);
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
        $aList = $folders->listDir($folderBase, '*.pem', true);
        $this->assertEquals($aList, $files);
    }
    
    /**
     * @expectedException Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage O diretório não existe!!!
     */
    public function testListDirFail()
    {
        $aList = array();
        $folderBase = '/qualquercoisa';
        $folders = new FilesFolders();
        $files = $folders->listDir($folderBase, '*.*', false);
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
        $folders = new FilesFolders();
        $respHtml = '';
        $resp = $folders->writeTest($folderBase, 'Test', $respHtml);
        $this->assertTrue($resp);
        $this->assertEquals($htmlStandard, $respHtml);
    }
}
