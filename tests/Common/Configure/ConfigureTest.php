<?php

/**
 * Class ConfigureTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\Configure\Configure;

class ConfigureTest extends PHPUnit_Framework_TestCase
{
    public function testeInstanciar()
    {
        $cnpj = '99999090910270';
        $pathCertsFiles = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/certificado_teste.pfx';
        $certPfxName = 'certificado_teste.pfx';
        $certPassword = 'associacao';
        $aResp = Configure::checkCerts($cnpj, $pathCertsFiles, $certPfxName, $certPassword);
    }
}
