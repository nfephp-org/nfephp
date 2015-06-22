<?php

/**
 * Class MailCTeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\CTe\MailCTe;

class MailCTeTest extends PHPUnit_Framework_TestCase
{
    public $mail;
    
    public function testeInstanciar()
    {
        $configJson = file_get_contents(dirname(dirname(__FILE__)) . '/fixtures/config/fakeconfig.json');
        $json = json_decode($configJson);
        $aMail = (array) $json->aMailConf;
        $this->mail = new MailCTe($aMail);
    }
}
