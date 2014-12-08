<?php

/**
 * Class FilesZipTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use Common\Files\FilesZip;

class FilesZipTest extends PHPUnit_Framework_TestCase
{
    public function testUnZipTmpFile()
    {
        $pathxml = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/'
            . '11101284613439000180550010000004881093997017-nfe.xml';
        $pathzip = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/'
            . '11101284613439000180550010000004881093997017-nfe.gz';
        $base = file_get_contents($pathxml);
        $resp = FilesZip::unZipTmpFile($pathzip);
        $this->assertEquals($resp, $base);
    }
    
    public function testUnGZip()
    {
        $pathxml = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/'
            . '11101284613439000180550010000004881093997017-nfe.xml';
        $pathzip = dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/'
            . '11101284613439000180550010000004881093997017-nfe.gz';
        $base = file_get_contents($pathxml);
        $resp = FilesZip::unGZip($pathzip);
        $this->assertEquals($resp, $base);
    }
}
