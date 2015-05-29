<?php

/**
 * Class IdentifyMDFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\MDFe\IdentifyMDFe;

class IdentifyMDFeTest extends PHPUnit_Framework_TestCase
{
    public function testeIdentificaMDFe()
    {
        $aResp = array();
        $filePath = dirname(dirname(__FILE__)) . '/fixtures/xml/MDFe/MDFe41140581452880000139580010000000281611743166.xml';
        $schem = IdentifyMDFe::identificar($filePath, $aResp);
        $this->assertEquals($schem, 'mdfe');
    }
}
