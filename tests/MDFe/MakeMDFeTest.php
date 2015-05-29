<?php

/**
 * Class MakeMDFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\MDFe\MakeMDFe;

class MakeMDFeTest extends PHPUnit_Framework_TestCase
{
    public $mdfe;
    
    public function testeInstanciar()
    {
        $this->mdfe = new MakeMDFe();
    }
}
