<?php

/**
 * Class ToolsNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
use NFePHP\NFe\ToolsNFe;
use NFePHP\Common\Exception\RuntimeException;

class ToolsNFeTest extends PHPUnit_Framework_TestCase
{
    public function testeInstanciarCertificadoVencido()
    {
        $config = dirname(dirname(__FILE__)) . '/fixtures/config/fakeconfig.json';
        $json = file_get_contents($config);
        $data = json_decode($json, true);
        $data['certPfxName'] = __DIR__."/../../certs/certificado_teste.pfx";
        $data['pathCertsFiles'] = null;
        $config = json_encode($data);

        $this->setExpectedException(RuntimeException::class, 'Data de validade vencida! [Valido até 02/10/10]');
        new ToolsNFe($config);
    }
}
