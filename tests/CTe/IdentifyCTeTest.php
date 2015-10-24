<?php

/**
 * Class IdentifyCTeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\CTe\IdentifyCTe;

class IdentifyCTeTest extends PHPUnit_Framework_TestCase
{
    public function testeIdentificaCTe()
    {
        $aResp = array();
        $filePath = dirname(dirname(__FILE__)) . '/fixtures/xml/CTe/0008-cte.xml';
        $schem = IdentifyCTe::identificar($filePath, $aResp);
        $this->assertEquals($schem, 'mdfe');
    }
}
