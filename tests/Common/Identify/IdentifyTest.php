<?php

/**
 * Class IdentifyTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\Identify\Identify;

class IdentifyTest extends PHPUnit_Framework_TestCase
{
    public function testSetListSchemesId()
    {
        $this->assertNotFalse(true);
    }
    
    public function testIdentificacao()
    {
        $aList = array(
            'consReciNFe' => 'consReciNFe',
            'consSitNFe' => 'consSitNFe',
            'consStatServ' => 'consStatServ',
            'distDFeInt' => 'distDFeInt',
            'enviNFe' => 'enviNFe',
            'inutNFe' => 'inutNFe',
            'NFe' => 'nfe',
            'procInutNFe' => 'procInutNFe',
            'procNFe' => 'procNFe',
            'resEvento' => 'resEvento',
            'resNFe' => 'resNFe',
            'retConsReciNFe' => 'retConsReciNFe',
            'retConsSitNFe' => 'retConsSitNFe',
            'retConsStatServ' => 'retConsStatServ',
            'retDistDFeInt' => 'retDistDFeInt',
            'retEnviNFe' => 'retEnviNFe',
            'retInutNFe' => 'retInutNFe'
        );
        $aResp = array();
        Identify::setListSchemesId($aList);
        $xml = dirname(dirname(dirname(__FILE__))) .
            '/fixtures/xml/35150158716523000119550010000000071000000076-protNFe.xml';
        $schem = Identify::identificacao($xml, $aResp);
        $this->assertEquals($schem, 'nfe');
    }
}
