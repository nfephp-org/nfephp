<?php

/**
 * Class Pkcs12Test
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */

class StringsTest extends PHPUnit_Framework_TestCase
{
    public function testCleanString()
    {
        $str = new Common\Strings\Strings();
        $txtSujo = "Esse é um código cheio de @$#$! , - . ; : / COISAS e 12093876486";
        $txtLimpo = "Esse e um codigo cheio de @ , - . ; : / COISAS e 12093876486";
        $resp = $str->cleanString($txtSujo);
        $this->assertEquals($txtLimpo, $resp);
    }
    
    public function testClearXml()
    {
        $str = new Common\Strings\Strings();
        $xmlSujo = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/xml-sujo.xml');
        $xmlLimpo1 = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/xml-limpo1.xml');
        $xmlLimpo2 = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/xml-limpo2.xml');
        
        $resp1 = $str->clearXml($xmlSujo, false);
        $resp2 = $str->clearXml($xmlSujo, true);
        $this->assertEquals($xmlLimpo1, $resp1);
        $this->assertEquals($xmlLimpo2, $resp2);
    }
}
