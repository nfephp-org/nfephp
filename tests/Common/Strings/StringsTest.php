<?php

/**
 * Class StringsTest
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */

class StringsTest extends PHPUnit_Framework_TestCase
{
    public function testCleanString()
    {
        $txtSujo = "Esse é um código cheio de @$#$! , - . ; : / COISAS e 12093876486";
        $txtLimpo = "Esse e um codigo cheio de @ , - . ; : / COISAS e 12093876486";
        $resp = NFePHP\Common\Strings\Strings::cleanString($txtSujo);
        $this->assertEquals($txtLimpo, $resp);
    }
    
    public function testClearXml()
    {
        $xmlSujo = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/NFe/xml-sujo.xml');
        $xmlLimpo1 = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/NFe/xml-limpo1.xml');
        $xmlLimpo2 = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fixtures/xml/NFe/xml-limpo2.xml');
        
        $resp1 = NFePHP\Common\Strings\Strings::clearXml($xmlSujo, false);
        $resp2 = NFePHP\Common\Strings\Strings::clearXml($xmlSujo, true);
        $this->assertEquals($xmlLimpo1, $resp1);
        $this->assertEquals($xmlLimpo2, $resp2);
    }
    
    public function testClearProt()
    {
        $xmlSujo = '';
        $xmlLimpo = '';
        $resp1 = NFePHP\Common\Strings\Strings::clearProt($xmlSujo);
        $this->assertEquals($xmlLimpo, $resp1);
    }
    
    public function testClearMsg()
    {
        //$str = new Common\Strings\Strings();
        $txtSujo = "AKJKJ >    < \n JKJS \t lkdlkd \r default:";
        $txtLimpo = "AKJKJ ><  JKJS  lkdlkd  ";
        $txt = NFePHP\Common\Strings\Strings::clearMsg($txtSujo);
        $this->assertEquals($txt, $txtLimpo);
    }
}
