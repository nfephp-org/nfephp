<?php

/**
 * Class LotNumberTest
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */

class LotNumberTest extends PHPUnit_Framework_TestCase
{
   public function testGeraNumLote()
   {
        $numLote = (string) NFePHP\Common\LotNumber\LotNumber::geraNumLote(15);
        $num = strlen($numLote);
        $this->assertEquals($num, 15);
   }
}
