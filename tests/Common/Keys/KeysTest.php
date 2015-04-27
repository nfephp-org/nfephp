<?php

/**
 * Class KeysTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 */
use NFePHP\Common\Keys\Keys;

class KeysTest extends PHPUnit_Framework_TestCase
{
    public function testBuildKey()
    {
        $cUF = '35';
        $ano = '12';
        $mes = '12';
        $cnpj = '68252816000146';
        $mod = '57';
        $serie = '1';
        $numero = '1616';
        $tpEmis = '1';
        $codigo = '00200847';
        $key = Keys::buildKey($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo);
        $chave = '35121268252816000146570010000016161002008470';
        $this->assertEquals($chave, $key);
    }
    
    public function testTestaChave()
    {
        $chave = '35121268252816000146570010000016161002008470';
        $resp = Keys::testaChave($chave);
        $this->assertTrue($resp);
        
        $chave = '';
        $resp = Keys::testaChave($chave);
        $this->assertFalse($resp);
        
        $chave = '35121268252816000146570010000016161002008474';
        $resp = Keys::testaChave($chave);
        $this->assertFalse($resp);
    }
    
    public function testCalculaDV()
    {
        $chave = '3512126825281600014657001000001616100200847';
        $cDV = '0';
        $calcDV = Keys::calculaDV($chave);
        $this->assertEquals($cDV, $calcDV);
    }
}
