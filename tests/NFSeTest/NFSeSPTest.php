<?php

/**
 * Creates XMLs and Webservices communication
 *
 * Original names of Brazil specific abbreviations have been kept:
 * - CNPJ = Federal Tax Number
 * - CPF = Personal/Individual Taxpayer Registration Number
 * - CCM = Taxpayer Register (for service providers who pay ISS for local town/city hall)
 * - ISS = Service Tax
 *
 * @package   NFePHPaulista
 * @author    Reinaldo Nolasco Sanches <reinaldo@mandic.com.br>
 * @copyright Copyright (c) 2010, Reinaldo Nolasco Sanches
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NFSeSPTest extends PHPUnit_Framework_TestCase
{
    const CERTIFICATE_KEY = '99999090910270_certKEY.pem';
    const PASSPHRASE = '99999090910270_certKEY.pem';

    public function getSoapClientMock()
    {
        $mockBuider = $this->getMockBuilder('SoapClient')->disableOriginalConstructor();
        $mockBuider->setMethods(array('EnvioRPS', 'EnvioLoteRPS','TesteEnvioLoteRPS'));
        $mock = $mockBuider->getMock();
        $mock->expects($this->any())->method('EnvioRPS')->will($this->returnCallback(array('NFSeTest_Provider_SendRps', 'response')));
        $mock->expects($this->any())->method('EnvioLoteRPS')->will($this->returnCallback(array('NFSeTest_Provider_SendBatchRps', 'response')));
        $mock->expects($this->any())->method('TesteEnvioLoteRPS')->will($this->returnCallback(array('NFSeTest_Provider_SendBatchRps', 'response')));
        return $mock;
    }

    /**
     * @param array $methods
     * @return PHPUnit_Framework_MockObject_MockObject|NFSeSP
     */
    public function getNFSeSPMock(array $methods = array())
    {
        $config = array(
            'cnpjPrestador' => '00111222000112',
            'ccmPrestador' => '123',
            'passphrase' => 'associacao',
            'pkcs12' => __DIR__ . '/../fixtures/certs/certificado_teste.pfx',
            'certDir' => __DIR__ . '/../fixtures/certs',
            'privateKey' => '99999090910270_priKEY.pem',
            'publicKey' => '99999090910270_pubKEY.pem',
            'key' => '99999090910270_certKEY.pem',
            'ignoreCertExpired' => true,
            'connectionSoap' => $this->getSoapClientMock(),
        );

        $mock = $this->getMockBuilder('NFSeSP')
            ->setConstructorArgs(array($config))
            ->setMethods(array_merge(array('start'), $methods))
            ->getMock();

        $mock->expects($this->any())->method('start')->will($this->returnValue(null));
        return $mock;
    }

    private function getNFSeRPS()
    {
        $rps = new NFeRPS();
        $rps->contractorRPS = new ContractorRPS();
        return $rps;
    }

    public function testShouldInstanciateNfseSp()
    {
        $nfse = $this->getNFSeSPMock();
        $this->assertInstanceOf('NFSeSP', $nfse);
    }

    public function testSuccessOnSendARpsToReplaceForNfe()
    {
        $rps = $this->getNFSeRPS();
        $rps->numero = 123;
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->sendRPS($rps);
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);
        $this->assertEquals("123", $returned->ChaveNFeRPS->ChaveRPS->NumeroRPS);
    }

    public function testSendABatchOfRpsToReplaceForNfe()
    {
        $rps123 = $this->getNFSeRPS();
        $rps123->numero = 123;
        $rps321 = $this->getNFSeRPS();
        $rps321->numero = 321;

        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->sendRPSBatch(
            array('inicio' => date('Y-m-d H:s:i'), 'fim' => date('Y-m-d H:s:i')),
            array('servicos' => 0.0, 'deducoes' => 0.00),
            array($rps123, $rps321)
        );

        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);
        $this->assertEquals(2, count($returned->ChaveNFeRPS->ChaveRPS));
        foreach (array("123", "321") as $key => $number) {
            $this->assertEquals($number, $returned->ChaveNFeRPS->ChaveRPS[$key]->NumeroRPS);
        }
    }

    public function testSendABatchOfRpsToReplaceForNfeForTestOnly()
    {
        $rps = $this->getNFSeRPS();
        $rps->numero = 123;
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->sendRPSBatchTest(
            array('inicio' => date('Y-m-d H:s:i', strtotime('-1 day')), 'fim' => date('Y-m-d H:s:i')),
            array('servicos' => 0.0, 'deducoes' => 0.00),
            array($rps)
        );
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);
        $this->assertEquals(1, count($returned->ChaveNFeRPS->ChaveRPS));
        $this->assertEquals("123", $returned->ChaveNFeRPS->ChaveRPS[0]->NumeroRPS);
    }
}
