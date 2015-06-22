<?php

/**
 * Class ConvertNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\CTe\ConvertCTe;

class ConvertCTeTest extends PHPUnit_Framework_TestCase
{
    public $mdfe;
    
    public function testeInstanciar()
    {
        $this->mdfe = new ConvertCTe();
    }
}
