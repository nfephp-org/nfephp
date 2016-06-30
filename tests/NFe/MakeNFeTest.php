<?php

/**
 * Class MakeNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\NFe\MakeNFe;

class MakeNFeTest extends PHPUnit_Framework_TestCase
{
    public $nfe;
    
    public function testeInstanciar()
    {
        $this->nfe = new MakeNFe();
    }
}
