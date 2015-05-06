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
        $mockBuider->setMethods(
            array(
                'EnvioRPS', 'EnvioLoteRPS',
                'TesteEnvioLoteRPS', 'CancelamentoNFe',
                'ConsultaNFe', 'ConsultaNFeRecebidas',
                'ConsultaNFeEmitidas', 'ConsultaLote',
                'InformacoesLote', 'ConsultaCNPJ'
            )
        );

        $mock = $mockBuider->getMock();
        $mock->expects($this->any())->method('EnvioRPS')->will($this->returnCallback(array('NFSeTest_Provider_SendRps', 'response')));
        $mock->expects($this->any())->method('EnvioLoteRPS')->will($this->returnCallback(array('NFSeTest_Provider_SendBatchRps', 'response')));
        $mock->expects($this->any())->method('TesteEnvioLoteRPS')->will($this->returnCallback(array('NFSeTest_Provider_SendBatchRps', 'response')));
        $mock->expects($this->any())->method('CancelamentoNFe')->will($this->returnCallback(array('NFSeTest_Provider_CancelNFe', 'response')));
        $mock->expects($this->any())->method('ConsultaNFe')->will($this->returnCallback(array('NFSeTest_Provider_QueryNFe', 'response')));
        $mock->expects($this->any())->method('ConsultaNFeRecebidas')->will($this->returnCallback(array('NFSeTest_Provider_QueryNFePeriod', 'response')));
        $mock->expects($this->any())->method('ConsultaNFeEmitidas')->will($this->returnCallback(array('NFSeTest_Provider_QueryNFePeriod', 'response')));
        $mock->expects($this->any())->method('ConsultaLote')->will($this->returnCallback(array('NFSeTest_Provider_QueryNFePeriod', 'response')));
        $mock->expects($this->any())->method('InformacoesLote')->will($this->returnCallback(array('NFSeTest_Provider_QueryBatchInfo', 'response')));
        $mock->expects($this->any())->method('ConsultaCNPJ')->will($this->returnCallback(array('NFSeTest_Provider_QueryCnpj', 'response')));
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
            'rpsDirectory' => __DIR__,
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

    public function testCancelNfe()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->cancelNFe(array('123'));
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);
        $this->assertEquals('123', $returned->NotasCanceladas->Nota->NumeroNota);
    }

    public function testQueryNfe()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryNFe(123, null, null);
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);

        $this->assertEquals('123', $returned->NFe->ChaveNFe->NumeroNFe);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->NFe->StatusNFe);
    }

    public function testQueryRps()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryNFe(null, 123, 1);
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);

        $this->assertEquals('123', $returned->RPS->ChaveRPS->NumeroRPS);
        $this->assertEquals('1', $returned->RPS->ChaveRPS->SerieRPS);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->RPS->StatusRPS);
    }

    public function testQueryNfesThatCnpjOrCcmCompanyReceivedFromOtherCompanies()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryNFeReceived(123, 456, date('Y-md H:i:s', strtotime('-1 day')), date('Y-m-d H:i:s'));
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);

        $this->assertEquals('123', $returned->NFe->ChaveNFe->NumeroNFe);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->NFe->StatusNFe);

        $this->assertEquals('321', $returned->RPS->ChaveRPS->NumeroRPS);
        $this->assertEquals('1', $returned->RPS->ChaveRPS->SerieRPS);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->RPS->StatusRPS);
    }

    public function testQueryNfesThatCnpjOrCcmCompanyIssuedToOtherCompanies()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryNFeIssued(123, 456, date('Y-md H:i:s', strtotime('-1 day')), date('Y-m-d H:i:s'));
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);

        $this->assertEquals('123', $returned->NFe->ChaveNFe->NumeroNFe);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->NFe->StatusNFe);

        $this->assertEquals('321', $returned->RPS->ChaveRPS->NumeroRPS);
        $this->assertEquals('1', $returned->RPS->ChaveRPS->SerieRPS);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->RPS->StatusRPS);
    }

    public function testQueryBatch()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryBatch(123);
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);

        $this->assertEquals('123', $returned->NFe->ChaveNFe->NumeroNFe);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->NFe->StatusNFe);

        $this->assertEquals('321', $returned->RPS->ChaveRPS->NumeroRPS);
        $this->assertEquals('1', $returned->RPS->ChaveRPS->SerieRPS);
        $this->assertEquals(NFSeSP::STATUS_NORMAL, $returned->RPS->StatusRPS);
    }

    public function testQueryBatchInfo()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryBatchInfo(123);
        $this->assertInstanceOf('SimpleXMLElement', $returned);
        $this->assertEquals('true', $returned->Cabecalho->Sucesso);

        $this->assertEquals('123', $returned->Cabecalho->InformacoesLote->NumeroLote);
    }

    public function testQueryCNPJ()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryCNPJ('00111222000100');
        $this->assertTrue(is_string($returned));
        $this->assertEquals('1234567890', $returned);
    }

    public function testQueryCPF()
    {
        $nfse = $this->getNFSeSPMock();
        $returned = $nfse->queryCPF('11122233344');
        $this->assertTrue(is_string($returned));
        $this->assertEquals('1234567890', $returned);
    }

    public function testCreateABatchFileWithNfeTextLayout()
    {
        $rps = $this->getNFSeRPS();
        $rps->valorServicos = 100.00;
        $rps->valorDeducoes = 10.00;
        $nfse = $this->getNFSeSPMock();
        $filePath = $nfse->textFile(
            array('inicio' => strtotime('-1 day'), 'fim' => time()),
            array('servicos' => 0.0, 'deducoes' => 0.00),
            array($rps)
        );
        $this->assertFileExists($filePath);
        unlink($filePath);
    }
}
