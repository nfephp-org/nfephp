<?php

/**
 * Class ReturnMDFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\MDFe\ReturnMDFe;

class ReturnMDFeTest extends PHPUnit_Framework_TestCase
{
    public $mdfe;
    
    public function testeInstanciar()
    {
        $this->mdfe = new ReturnMDFe();
    }
}
