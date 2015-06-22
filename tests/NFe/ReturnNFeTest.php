<?php

/**
 * Class ReturnNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\NFe\ReturnNFe;

class ReturnNFeTest extends PHPUnit_Framework_TestCase
{
    public $nfe;
    
    public function testeInstanciar()
    {
        $this->nfe = new ReturnNFe();
    }
}
