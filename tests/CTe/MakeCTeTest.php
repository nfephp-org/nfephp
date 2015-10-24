<?php

/**
 * Class MakeCTeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\CTe\MakeCTe;

class MakeCTeTest extends PHPUnit_Framework_TestCase
{
    public $mdfe;
    
    public function testeInstanciar()
    {
        $this->mdfe = new MakeCTe();
    }
}
