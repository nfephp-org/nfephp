<?php

/**
 * Class IdentifyNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\NFe\IdentifyNFe;

class IdentifyNFeTest extends PHPUnit_Framework_TestCase
{
    public function testeIdentificaNFe()
    {
        $aResp = array();
        $filePath = dirname(dirname(__FILE__)) . '/fixtures/xml/NFe/35101158716523000119550010000000011003000000-nfeSigned.xml';
        $schem = IdentifyNFe::identificar($filePath, $aResp);
        $this->assertEquals($schem, 'nfe');
    }
}
