<?php

/**
 * Class ConvertNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\MDFe\ConvertMDFe;

class ConvertMDFeTest extends PHPUnit_Framework_TestCase
{
    public $mdfe;
    
    public function testeInstanciar()
    {
        $this->mdfe = new ConvertMDFe();
    }
}
