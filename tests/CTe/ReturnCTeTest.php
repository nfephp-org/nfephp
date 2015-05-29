<?php

/**
 * Class ReturnCTeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\CTe\ReturnCTe;

class ReturnCTeTest extends PHPUnit_Framework_TestCase
{
    public $mdfe;
    
    public function testeInstanciar()
    {
        $this->mdfe = new ReturnCTe();
    }
}
