<?php

/**
 * Class ToolsNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\NFe\ToolsNFe;

class ToolsNFeTest extends PHPUnit_Framework_TestCase
{
    public $nfe;
    
    /**
     * @expectedException NFePHP\Common\Exception\InvalidArgumentException
     */
    public function testeInstanciar()
    {
        $configJson = dirname(dirname(__FILE__)) . '/fixtures/config/fakeconfig.json';
        $this->nfe = new ToolsNFe($configJson);
    }
}
