<?php

/**
 * Class ConvertNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\NFe\ConvertNFe;

class ConvertNFeTest extends PHPUnit_Framework_TestCase
{
    public $nfe;
    
    public function testeInstanciar()
    {
        $this->nfe = new ConvertNFe();
    }
}
