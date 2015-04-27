<?php

/**
 * Class AsnTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\Certificate\Asn;

class AsnTest extends PHPUnit_Framework_TestCase
{
    public function testConsigoPegarCNPJ()
    {
        $certificado = dirname(dirname(dirname(__FILE__))) . '/fixtures/certs/certificado_pubKEY.pem';
        $certPem = file_get_contents($certificado);
        $cnpj = Asn::getCNPJCert($certPem);
        $this->assertEquals($cnpj, '99999090910270');
    }
}
