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
class NFSeSP
{
    /**
     * Provisional Receipt of Services
     */
    const TYPE_RPS = 'RPS';
    /**
     * Provisional Receipt of Services from Combined Invoice (mixed)
     */
    const TYPE_RPSM = 'RPS-M';
    /**
     * Coupon
     */
    const TYPE_RPSC = 'RPS-C';
    /**
     * Document Ok
     */
    const STATUS_NORMAL = 'N';
    /**
     * Document is canceled
     */
    const STATUS_CANCELED = 'C';
    /**
     * Document lost
     */
    const STATUS_LOST = 'E';
    /**
     * Taxation in Sao Paulo
     */
    const TAXATION_IN_SAO_PAULO = 'T';
    /**
     * Taxation out of Sao Paulo
     */
    const TAXATION_OUT_SAO_PAULO = 'F';
    /**
     * Taxation free
     */
    const TAXATION_FREE = 'I';
    /**
     * Service Tax Suspended for Judicial Decision
     */
    const TAXATION_SERVICE_TAX_SUSPENDED = 'J';

    private $cnpjPrestador = 'xxxxxxxxxxxxx'; // Your CNPJ
    private $ccmPrestador = 'xxxxxxxxx'; // Your CCM
    private $passphrase = 'xxxxxxxxxx'; // Cert passphrase
    private $pkcs12 = 'caminho_completo_para_o_seu_certificado.pfx';
    private $certDir = 'diretorio_onde_esta_seu_certificado'; // Dir for .pem certs
    private $rpsDirectory = '/patch/for/rps/batch/file';
    private $privateKey = 'privatekey.pem';
    public $certDaysToExpire = 0;
    private $ignoreCertExpired = false;
    private $publicKey = 'publickey.pem';
    private $X509Certificate;
    private $key = 'key.pem';
    private $connectionSoap;
    private $urlXsi = 'http://www.w3.org/2001/XMLSchema-instance';
    private $urlXsd = 'http://www.w3.org/2001/XMLSchema';
    private $urlNfe = 'http://www.prefeitura.sp.gov.br/nfe';
    private $urlDsig = 'http://www.w3.org/2000/09/xmldsig#';
    private $urlCanonMeth = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    private $urlSigMeth = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    private $urlTransfMeth_1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    private $urlTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    private $urlDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';


    public function __construct(array $config = array())
    {
        $this->loadConfiguration($config);
        $this->privateKey = $this->certDir . DIRECTORY_SEPARATOR . $this->privateKey;
        $this->publicKey = $this->certDir . DIRECTORY_SEPARATOR . $this->publicKey;
        $this->key = $this->certDir . DIRECTORY_SEPARATOR . $this->key;
        $this->loadCert();
    }

    /**
     * Load given configuration
     *
     * @param array $config
     * @return void
     */
    private function loadConfiguration(array $config)
    {
        foreach ($config as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Validate if certificate is expired.
     *
     * @param string $cert
     * @return void
     */
    private function validateCert($cert)
    {
        $data = openssl_x509_read($cert);
        $certData = openssl_x509_parse($data);

        $validTo = \DateTime::createFromFormat('ymd', substr($certData['validTo'], 0, 6));
        $today = new \DateTime('now');
        if (!$this->ignoreCertExpired AND $validTo < $today) {
            throw new \Common\Exception\RuntimeException('Certificado expirado em ' . $validTo->format('Y-m-d'));
        }
    }

    /**
     * Load certificate from file.
     *
     * @return void
     */
    private function loadCert()
    {
        $x509CertData = array();
        if (!openssl_pkcs12_read(file_get_contents($this->pkcs12), $x509CertData, $this->passphrase)) {
            throw new \Common\Exception\IOException(
                'Certificado não pode ser lido. O arquivo esta corrompido ou em formato invalido.'
            );
        }
        $this->X509Certificate = preg_replace("/[\n]/", '', preg_replace('/\-\-\-\-\-[A-Z]+ CERTIFICATE\-\-\-\-\-/', '', $x509CertData['cert']));
        $this->validateCert($x509CertData['cert']);
    }

    /**
     * Start a connection with webservice.
     *
     * @return void
     */
    public function start()
    {
        //versão do SOAP
        $soapver = SOAP_1_2;
        $wsdl = 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx?WSDL';
        $params = array(
            'local_cert' => $this->key,
            'passphrase' => $this->passphrase,
            'connection_timeout' => 300,
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => $soapver,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        );

        try {
            $this->connectionSoap = new SoapClient($wsdl, $params);
        } catch (SoapFault $e) {
            throw new \Common\Exception\RuntimeException($e->getMessage());
        } catch (Exception $e) {
            throw new $e;
        }
    }

    /**
     * Call method from webservice.
     *
     * @param string $operation Method's name to call.
     * @param DOMDocument $xmlDoc Message to be sent.
     * @return bool|SimpleXMLElement Returns a XML when communication is successful, otherwise false when get error.
     */
    private function send($operation, DOMDocument $xmlDoc)
    {
        $this->start();
        $this->signXML($xmlDoc);
        $xmlDoc->formatOutput = true;
        $params = array(
            'VersaoSchema' => 1,
            'MensagemXML' => $xmlDoc->saveXML()
        );
        try {
            $result = $this->connectionSoap->$operation($params);
        } catch (SoapFault $e) {
            throw new \Common\Exception\RuntimeException($e->getMessage());
        } catch (Exception $e) {
            throw new $e;
        }
        return new SimpleXMLElement($result->RetornoXML);
    }

    /**
     * Create a XML Header Message
     *
     * @param string $operation Method's name
     * @return DOMDocument Returns a XML based on $operation.xsd schema
     */
    private function makeXmlHeader($operation)
    {
        $xmlDoc = new DOMDocument('1.0', 'UTF-8');
        $xmlDoc->preserveWhiteSpace = false;
        $xmlDoc->formatOutput = false;
        $data = '<?xml version="1.0" encoding="UTF-8"?><Pedido' . $operation . ' xmlns:xsd="' . $this->urlXsd . '" xmlns="' . $this->urlNfe . '" xmlns:xsi="' . $this->urlXsi . '"></Pedido' . $operation . '>';
        $xmlDoc->loadXML(str_replace(array("\r\n", "\n", "\r"), '', $data), LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $root = $xmlDoc->documentElement;
        $header = $xmlDoc->createElementNS('', 'Cabecalho');
        $root->appendChild($header);
        $header->setAttribute('Versao', 1);
        $cnpjSender = $xmlDoc->createElement('CPFCNPJRemetente');
        $cnpjSender->appendChild($xmlDoc->createElement('CNPJ', $this->cnpjPrestador));
        $header->appendChild($cnpjSender);
        return $xmlDoc;
    }

    /**
     * Create a XML Header Message
     *
     * @param string $operation Method's name
     * @return DOMDocument Returns a XML based on $operation.xsd schema
     */
    private function createXMLp1($operation)
    {
        $xmlDoc = new DOMDocument('1.0', 'UTF-8');
        $xmlDoc->preserveWhiteSpace = false;
        $xmlDoc->formatOutput = false;
        $data = '<?xml version="1.0" encoding="UTF-8"?><Pedido' . $operation . ' xmlns="' . $this->urlNfe . '" xmlns:xsi="' . $this->urlXsi . '"></Pedido' . $operation . '>';
        $xmlDoc->loadXML(str_replace(array("\r\n", "\n", "\r"), '', $data), LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $root = $xmlDoc->documentElement;
        $header = $xmlDoc->createElementNS('', 'Cabecalho');
        $root->appendChild($header);
        $header->setAttribute('Versao', 1);
        $cnpjSender = $xmlDoc->createElement('CPFCNPJRemetente');
        $cnpjSender->appendChild($xmlDoc->createElement('CNPJ', $this->cnpjPrestador));
        $header->appendChild($cnpjSender);
        return $xmlDoc;
    }

    /**
     * Sign XML with certificate.
     * @param DOMDocument $xmlDoc Returns Signature node based on xmldsig-core-schema.xsd schema
     */
    private function signXML(DOMDocument $xmlDoc)
    {
        $root = $xmlDoc->documentElement;
        // DigestValue is a base64 sha1 hash with root tag content without Signature tag
        $digestValue = base64_encode(hash('sha1', $root->C14N(false, false, null, null), true));
        $signature = $xmlDoc->createElementNS($this->urlDsig, 'Signature');
        $root->appendChild($signature);
        $signedInfo = $xmlDoc->createElement('SignedInfo');
        $signature->appendChild($signedInfo);
        $newNode = $xmlDoc->createElement('CanonicalizationMethod');
        $signedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlCanonMeth);
        $newNode = $xmlDoc->createElement('SignatureMethod');
        $signedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlSigMeth);
        $reference = $xmlDoc->createElement('Reference');
        $signedInfo->appendChild($reference);
        $reference->setAttribute('URI', '');
        $transforms = $xmlDoc->createElement('Transforms');
        $reference->appendChild($transforms);
        $newNode = $xmlDoc->createElement('Transform');
        $transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlTransfMeth_1);
        $newNode = $xmlDoc->createElement('Transform');
        $transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlTransfMeth_2);
        $newNode = $xmlDoc->createElement('DigestMethod');
        $reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->urlDigestMeth);
        $newNode = $xmlDoc->createElement('DigestValue', $digestValue);
        $reference->appendChild($newNode);
        // SignedInfo Canonicalization (Canonical XML)
        $signedInfoC14n = $signedInfo->C14N(false, false, null, null);
        // SignatureValue is a base64 SignedInfo tag content
        $signatureValue = '';
        $pkeyId = openssl_get_privatekey(file_get_contents($this->privateKey));
        openssl_sign($signedInfoC14n, $signatureValue, $pkeyId);
        $newNode = $xmlDoc->createElement('SignatureValue', base64_encode($signatureValue));
        $signature->appendChild($newNode);
        $keyInfo = $xmlDoc->createElement('KeyInfo');
        $signature->appendChild($keyInfo);
        $x509Data = $xmlDoc->createElement('X509Data');
        $keyInfo->appendChild($x509Data);
        $newNode = $xmlDoc->createElement('X509Certificate', $this->X509Certificate);
        $x509Data->appendChild($newNode);
        openssl_free_key($pkeyId);
    }

    /**
     * Sign XML with certificate.
     * @param NFeRPS $rps RPS Document
     * @param DOMElement $rpsNode Returns Assinatura node based on $operation.xsd schema
     */
    private function signRPS(NFeRPS $rps, DOMElement $rpsNode)
    {
        $content = sprintf('%08s', $rps->CCM) .
            sprintf('%-5s', $rps->serie) .
            sprintf('%012s', $rps->numero) .
            str_replace("-", "", $rps->dataEmissao) .
            $rps->tributacao .
            $rps->status .
            (($rps->comISSRetido) ? 'S' : 'N') .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($rps->valorServicos, 2))) .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($rps->valorDeducoes, 2))) .
            sprintf('%05s', $rps->codigoServico) .
            (($rps->contractorRPS->type == 'F') ? '1' : '2') .
            sprintf('%014s', $rps->contractorRPS->cnpjTomador);

        $signatureValue = '';
        $pkeyId = openssl_get_privatekey(file_get_contents($this->privateKey));
        openssl_sign($content, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);
        openssl_free_key($pkeyId);
        $rpsNode->appendChild(new DOMElement('Assinatura', base64_encode($signatureValue)));
    }

    /**
     * Makes a XML Object based on given RPS.
     * @param NFeRPS $rps RPS Document
     * @param DOMDocument $xmlDoc Returns xml based on RetornoEnvioRPS.xsd schema
     */
    private function makeRPSXml(NFeRPS $rps, DOMDocument $xmlDoc)
    {
        $rpsNode = $xmlDoc->createElementNS('', 'RPS');
        $xmlDoc->documentElement->appendChild($rpsNode);
        $this->signRPS($rps, $rpsNode);
        $rpsKey = $xmlDoc->createElement('ChaveRPS'); // 1-1
        $rpsKey->appendChild($xmlDoc->createElement('InscricaoPrestador', $rps->CCM)); // 1-1
        $rpsKey->appendChild($xmlDoc->createElement('SerieRPS', $rps->serie)); // 1-1 DHC AAAAA / alog AAAAB
        $rpsKey->appendChild($xmlDoc->createElement('NumeroRPS', $rps->numero)); // 1-1
        $rpsNode->appendChild($rpsKey);
        /* RPS ­ Recibo Provisório de Serviços
         * RPS-M ­ Recibo Provisório de Serviços proveniente de Nota Fiscal Conjugada (Mista)
        * RPS-C ­ Cupom */
        $rpsNode->appendChild($xmlDoc->createElement('TipoRPS', $rps->type)); // 1-1
        $rpsNode->appendChild($xmlDoc->createElement('DataEmissao', $rps->dataEmissao)); // 1-1
        /* N ­ Normal
        * C ­ Cancelada
        * E ­ Extraviada */
        $rpsNode->appendChild($xmlDoc->createElement('StatusRPS', $rps->status)); // 1-1
        /* T - Tributação no município de São Paulo
         * F - Tributação fora do município de São Paulo
         * I ­- Isento
         * J - ISS Suspenso por Decisão Judicial */
        $rpsNode->appendChild($xmlDoc->createElement('TributacaoRPS', $rps->tributacao)); // 1-1
        $rpsNode->appendChild($xmlDoc->createElement('ValorServicos', sprintf("%s", $rps->valorServicos))); // 1-1
        $rpsNode->appendChild($xmlDoc->createElement('ValorDeducoes', sprintf("%s", $rps->valorDeducoes))); // 1-1
        $rpsNode->appendChild($xmlDoc->createElement('CodigoServico', $rps->codigoServico)); // 1-1
        $rpsNode->appendChild($xmlDoc->createElement('AliquotaServicos', $rps->aliquotaServicos)); // 1-1
        $rpsNode->appendChild($xmlDoc->createElement('ISSRetido', (($rps->comISSRetido) ? 'true' : 'false'))); // 1-1
        $cnpj = $xmlDoc->createElement('CPFCNPJTomador'); // 0-1
        if ($rps->contractorRPS->type == "F") {
            $cnpj->appendChild($xmlDoc->createElement('CPF', sprintf('%011s', $rps->contractorRPS->cnpjTomador)));
        } else {
            $cnpj->appendChild($xmlDoc->createElement('CNPJ', sprintf('%014s', $rps->contractorRPS->cnpjTomador)));
        }
        $rpsNode->appendChild($cnpj);
        if ($rps->contractorRPS->ccmTomador <> "") {
            $rpsNode->appendChild($xmlDoc->createElement('InscricaoMunicipalTomador', $rps->contractorRPS->ccmTomador)); // 0-1
        }
        $rpsNode->appendChild($xmlDoc->createElement('RazaoSocialTomador', $rps->contractorRPS->name)); // 0-1
        $address = $xmlDoc->createElement('EnderecoTomador'); // 0-1
        $address->appendChild($xmlDoc->createElement('TipoLogradouro', $rps->contractorRPS->tipoEndereco));
        $address->appendChild($xmlDoc->createElement('Logradouro', $rps->contractorRPS->endereco));
        $address->appendChild($xmlDoc->createElement('NumeroEndereco', $rps->contractorRPS->enderecoNumero));
        if (trim($rps->contractorRPS->complemento) != "") {
            $address->appendChild($xmlDoc->createElement('ComplementoEndereco', $rps->contractorRPS->complemento));
        }
        $address->appendChild($xmlDoc->createElement('Bairro', $rps->contractorRPS->bairro));
        $address->appendChild($xmlDoc->createElement('Cidade', $rps->contractorRPS->cidade));
        $address->appendChild($xmlDoc->createElement('UF', $rps->contractorRPS->estado));
        $address->appendChild($xmlDoc->createElement('CEP', $rps->contractorRPS->cep));
        $rpsNode->appendChild($address);
        $rpsNode->appendChild($xmlDoc->createElement('EmailTomador', $rps->contractorRPS->email)); // 0-1
        $rpsNode->appendChild($xmlDoc->createElement('Discriminacao', $rps->discriminacao)); // 1-1
    }

    /**
     * Send a RPS to replace for NF-e
     * Message is based on PedidoEnvioRPS.xsd schema
     *
     * @param NFeRPS $rps
     * @return bool|\SimpleXMLElement Returns xml based on RetornoEnvioRPS.xsd schema
     */
    public function sendRPS(NFeRPS $rps)
    {
        $operation = 'EnvioRPS';
        $xmlDoc = $this->makeXmlHeader($operation);
        $this->makeRPSXml($rps, $xmlDoc);
        $returnXmlDoc = $this->send($operation, $xmlDoc);
        return $returnXmlDoc;
    }

    /**
     * Send a batch of RPSs to replace for NF-e
     * Message is based on PedidoEnvioLoteRPS.xsd schema
     *
     * @param array $rangeDate ('start' => start date of RPSs, 'end' => end date of RPSs)
     * @param array $valorTotal ('servicos' => total value of RPSs, 'deducoes' => total deductions on values of RPSs)
     * @param array $rps Collection of NFeRPS
     * @return bool|\SimpleXMLElement Returns xml based on RetornoEnvioLoteRPS.xsd schema
     */
    public function sendRPSBatch($rangeDate, $valorTotal, $rps)
    {
        $xmlDoc = $this->makeBatchMessage($rangeDate, $valorTotal, $rps);
        return $this->send('EnvioLoteRPS', $xmlDoc);
    }

    /**
     * Send a batch of RPSs to replace for NF-e for test only.
     * Message is based on PedidoEnvioLoteRPS.xsd schema
     *
     * @param array $rangeDate ('start' => start date of RPSs, 'end' => end date of RPSs)
     * @param array $valorTotal ('servicos' => total value of RPSs, 'deducoes' => total deductions on values of RPSs)
     * @param array $rps Collection of NFeRPS
     * @return bool|\SimpleXMLElement Returns xml based on RetornoEnvioLoteRPS.xsd schema
     */
    public function sendRPSBatchTest(array $rangeDate, array $valorTotal, array $rps)
    {
        $xmlDoc = $this->makeBatchMessage($rangeDate, $valorTotal, $rps);
        $return = $this->send('TesteEnvioLoteRPS', $xmlDoc);
        return $return;
    }

    /**
     * Makes xml batch message.
     * @param array $rangeDate
     * @param array $valorTotal
     * @param array $rps
     * @return DOMDocument
     */
    private function makeBatchMessage(array $rangeDate, array $valorTotal, array $rps)
    {
        $xmlDoc = $this->makeXmlHeader('EnvioLoteRPS');
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('transacao', 'false'));
        $header->appendChild($xmlDoc->createElement('dtInicio', $rangeDate['inicio']));
        $header->appendChild($xmlDoc->createElement('dtFim', $rangeDate['fim']));
        $header->appendChild($xmlDoc->createElement('QtdRPS', count($rps)));
        $header->appendChild($xmlDoc->createElement('ValorTotalServicos', $valorTotal['servicos']));
        $header->appendChild($xmlDoc->createElement('ValorTotalDeducoes', $valorTotal['deducoes']));
        foreach ($rps as $item) {
            $this->makeRPSXml($item, $xmlDoc);
        }
        return $xmlDoc;
    }

    /**
     * Has responsible to cancel NFe numbers created from sendRPSBatch method
     * Message is based on PedidoConsultaNFe.xsd schema
     *
     * @param array $nfeNumbers Array of NFe numbers
     * @return bool|\SimpleXMLElement Returns xml based on RetornoCancelamentoNFe.xsd schema
     */
    public function cancelNFe(array $nfeNumbers)
    {
        $operation = 'CancelamentoNFe';
        $xmlDoc = $this->makeXmlHeader($operation);
        $root = $xmlDoc->documentElement;
        $header = $root->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('transacao', 'false'));
        foreach ($nfeNumbers as $nfeNumber) {
            $detail = $xmlDoc->createElementNS('', 'Detalhe');
            $root->appendChild($detail);
            $nfeKey = $xmlDoc->createElement('ChaveNFe'); // 1-1
            $nfeKey->appendChild($xmlDoc->createElement('InscricaoPrestador', $this->ccmPrestador)); // 1-1
            $nfeKey->appendChild($xmlDoc->createElement('NumeroNFe', $nfeNumber)); // 1-1
            $detail->appendChild($nfeKey);
            $content = sprintf('%08s', $this->ccmPrestador) .
                sprintf('%012s', $nfeNumber);
            $signatureValue = '';
            $pkeyId = openssl_get_privatekey(file_get_contents($this->privateKey));
            openssl_sign($content, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);
            openssl_free_key($pkeyId);
            $detail->appendChild(new DOMElement('AssinaturaCancelamento', base64_encode($signatureValue)));
        }
        return $this->send($operation, $xmlDoc);
    }

    /**
     * It will find a NFe document from given number or
     * RPS document when given $rpsNumber and $rpsSerie
     * Message is based on PedidoConsultaNFe.xsd schema
     *
     * @param string $nfeNumber NFe Number
     * @param string $rpsNumber RPS Number
     * @param string $rpsSerie RPS Serie
     * @return bool|SimpleXMLElement Returns a XML based on RetornoConsulta.xsd schema.
     */
    public function queryNFe($nfeNumber, $rpsNumber, $rpsSerie)
    {
        $operation = 'ConsultaNFe';
        $xmlDoc = $this->createXMLp1($operation);
        $root = $xmlDoc->documentElement;
        if ($nfeNumber > 0) {
            $detailNfe = $xmlDoc->createElementNS('', 'Detalhe');
            $root->appendChild($detailNfe);
            $nfeKey = $xmlDoc->createElement('ChaveNFe'); // 1-1
            $nfeKey->appendChild($xmlDoc->createElement('InscricaoPrestador', $this->ccmPrestador)); // 1-1
            $nfeKey->appendChild($xmlDoc->createElement('NumeroNFe', $nfeNumber)); // 1-1
            $detailNfe->appendChild($nfeKey);
        }
        if ($rpsNumber > 0) {
            //$detailRps = $xmlDoc->createElement('Detalhe');
            $detailRps = $xmlDoc->createElementNS('', 'Detalhe');
            $root->appendChild($detailRps);
            $rpsKey = $xmlDoc->createElement('ChaveRPS'); // 1-1
            $rpsKey->appendChild($xmlDoc->createElement('InscricaoPrestador', $this->ccmPrestador)); // 1-1
            $rpsKey->appendChild($xmlDoc->createElement('SerieRPS', $rpsSerie)); // 1-1 DHC AAAAA / alog AAAAB
            $rpsKey->appendChild($xmlDoc->createElement('NumeroRPS', $rpsNumber)); // 1-1
            $detailRps->appendChild($rpsKey);
        }
        return $this->send($operation, $xmlDoc);
    }

    /**
     * queryNFeReceived and queryNFeIssued have the same XML request model
     * Message is based on PedidoConsultaNFePeriodo.xsd schema
     *
     * @param string $cnpj CNPJ to find
     * @param string $ccm State Registration
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     * @param int $pageNumber Number of page to query results, by default the webservice given 50 documents per page
     * @return \DOMDocument Returns xml based on RetornoConsulta.xsd schema
     */
    private function queryNFeWithDateRange($cnpj, $ccm, $startDate, $endDate, $pageNumber = 1)
    {
        $operation = 'ConsultaNFePeriodo';
        $xmlDoc = $this->makeXmlHeader($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $cnpjTaxpayer = $xmlDoc->createElement('CPFCNPJ');
        $cnpjTaxpayer->appendChild($xmlDoc->createElement('CNPJ', $cnpj));
        $header->appendChild($cnpjTaxpayer);
        $ccmTaxpayer = $xmlDoc->createElement('Inscricao', $ccm);
        $header->appendChild($ccmTaxpayer);
        $startDateNode = $xmlDoc->createElement('dtInicio', $startDate);
        $header->appendChild($startDateNode);
        $endDateNode = $xmlDoc->createElement('dtFim', $endDate);
        $header->appendChild($endDateNode);
        $pageNumber = $xmlDoc->createElement('NumeroPagina', $pageNumber);
        $header->appendChild($pageNumber);
        return $xmlDoc;
    }

    /**
     * Query NF-e's that CNPJ/CCM company received from other companies
     * Message is based on PedidoConsultaNFePeriodo.xsd schema
     *
     * @param string $cnpj CNPJ to find
     * @param string $ccm State Registration
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     * @param int $pageNumber
     * @return bool|\SimpleXMLElement Returns xml based on RetornoConsulta.xsd schema
     */
    public function queryNFeReceived($cnpj, $ccm, $startDate, $endDate, $pageNumber = 1)
    {
        $operation = 'ConsultaNFeRecebidas';
        $xmlDoc = $this->queryNFeWithDateRange($cnpj, $ccm, $startDate, $endDate, $pageNumber);
        return $this->send($operation, $xmlDoc);
    }

    /**
     * Query NF-e's that CNPJ/CCM company issued to other companies
     * Message is based on PedidoConsultaNFePeriodo.xsd schema
     *
     * @param string $cnpj
     * @param string $ccm
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     * @param int $pageNumber
     * @return bool|\SimpleXMLElement Returns xml based on RetornoConsulta.xsd schema
     */
    public function queryNFeIssued($cnpj, $ccm, $startDate, $endDate, $pageNumber = 1)
    {
        $operation = 'ConsultaNFeEmitidas';
        $xmlDoc = $this->queryNFeWithDateRange($cnpj, $ccm, $startDate, $endDate, $pageNumber);
        return $this->send($operation, $xmlDoc);
    }

    /**
     * Get NF-e's with this batch number
     * Message is based on PedidoConsultaLote.xsd schema
     *
     * @param $batchNumber
     * @return bool|SimpleXMLElement Returns xml based on schema RetornoConsulta.xsd
     */
    public function queryBatch($batchNumber)
    {
        $operation = 'ConsultaLote';
        $xmlDoc = $this->makeXmlHeader($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('NumeroLote', $batchNumber));
        return $this->send($operation, $xmlDoc);
    }

    /**
     * If $batchNumber param is null, last match info will be returned
     * Message is based on PedidoInformacoesLote.xsd schema
     *
     * @param integer $batchNumber
     * @return bool|SimpleXMLElement Returns xml based on schema RetornoInformacoesLote.xsd
     */
    public function queryBatchInfo($batchNumber = null)
    {
        $operation = 'InformacoesLote';
        $xmlDoc = $this->makeXmlHeader($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('InscricaoPrestador', $this->ccmPrestador));
        if ($batchNumber) {
            $header->appendChild($xmlDoc->createElement('NumeroLote', $batchNumber));
        }
        return $this->send($operation, $xmlDoc);
    }

    /**
     * Returns CCM for given CNPJ
     * Message is based on PedidoConsultaCNPJ.xsd schema and
     * response is based on RetornoConsultaCNPJ.xsd schema
     *
     * @param string $cnpj
     * @return bool|string Returns the taxpayer register number for given CNPJ
     */
    public function queryCNPJ($cnpj)
    {
        $operation = 'ConsultaCNPJ';
        $xmlDoc = $this->createXMLp1($operation);
        $root = $xmlDoc->documentElement;
        $cnpjTaxpayer = $xmlDoc->createElementNS('', 'CNPJContribuinte');
        if (strlen($cnpj) == 11) {
            $cnpjTaxpayer->appendChild($xmlDoc->createElement('CPF', (string)sprintf('%011s', $cnpj)));
        } else {
            $cnpjTaxpayer->appendChild($xmlDoc->createElement('CNPJ', (string)sprintf('%014s', $cnpj)));
        }
        $root->appendChild($cnpjTaxpayer);
        $return = $this->send($operation, $xmlDoc);

        $isSuccess = ($return && (string)$return->Cabecalho->Sucesso == 'true');

        if ($isSuccess && (string)$return->Detalhe->InscricaoMunicipal != "") {
            return (string)$return->Detalhe->InscricaoMunicipal;
        }
        if (!$isSuccess && (string)$return->Alerta->Codigo != "") {
            return (string)$return->Alerta->Descricao;
        }
        return false;
    }

    /**
     * Returns CCM for given CNPJ
     * Message is based on PedidoConsultaCNPJ.xsd schema and
     * response is based on RetornoConsultaCNPJ.xsd schema
     *
     * @param string $cnpj
     * @return bool|string Returns the taxpayer register number for given CNPJ
     */
    public function queryCPF($cnpj)
    {
        $operation = 'ConsultaCNPJ';
        $xmlDoc = $this->createXMLp1($operation);
        $root = $xmlDoc->documentElement;
        $cnpjTaxpayer = $xmlDoc->createElementNS('', 'CNPJContribuinte');
        $cnpjTaxpayer->appendChild($xmlDoc->createElement('CPF', (string)sprintf('%011s', $cnpj)));
        $root->appendChild($cnpjTaxpayer);
        $xmlResponse = $this->send($operation, $xmlDoc);
        if ($xmlResponse) {
            return $this->getIncricaoMunicipal($xmlResponse);
        }
        return false;
    }

    /**
     * Gets Taxpayer register.
     *
     * @param SimpleXMLElement $xmlResponse
     * @return string Returns
     */
    private function getIncricaoMunicipal(SimpleXMLElement $xmlResponse)
    {
        $isSuccess = ($xmlResponse && (string)$xmlResponse->Cabecalho->Sucesso == 'true');

        if (!$isSuccess) {
            throw new \Common\Exception\RuntimeException($xmlResponse->Alerta->Descricao, $xmlResponse->Alerta->Codigo);
        }

        return (string)$xmlResponse->Detalhe->InscricaoMunicipal;
    }

    /**
     * Create a line with RPS description for batch file
     *
     * @param NFeRPS $rps
     * @param string $body
     * @return string
     */
    private function insertTextRPS(NFeRPS $rps, $body)
    {
        if ($rps->valorServicos > 0) {
            $line = "2" .
                sprintf("%-5s", $rps->type) .
                sprintf("%-5s", $rps->serie) .
                sprintf('%012s', $rps->numero) .
                str_replace("-", "", $rps->dataEmissao) .
                $rps->tributacao .
                sprintf('%015s', str_replace('.', '', sprintf('%.2f', $rps->valorServicos))) .
                sprintf('%015s', str_replace('.', '', sprintf('%.2f', $rps->valorDeducoes))) .
                sprintf('%05s', $rps->codigoServico) .
                sprintf('%04s', str_replace('.', '', $rps->aliquotaServicos)) .
                (($rps->comISSRetido) ? '1' : '2') .
                (($rps->contractorRPS->type == 'F') ? '1' : '2') .
                sprintf('%014s', $rps->contractorRPS->cnpjTomador) .
                sprintf('%08s', $rps->contractorRPS->ccmTomador) .
                sprintf('%012s', '') .
                sprintf('%-75s', mb_convert_encoding($rps->contractorRPS->name, 'ISO-8859-1', 'UTF-8')) .
                sprintf('%3s', (($rps->contractorRPS->tipoEndereco == 'R') ? 'Rua' : '')) .
                sprintf('%-50s', mb_convert_encoding($rps->contractorRPS->endereco, 'ISO-8859-1', 'UTF-8')) .
                sprintf('%-10s', $rps->contractorRPS->enderecoNumero) .
                sprintf('%-30s', mb_convert_encoding($rps->contractorRPS->complemento, 'ISO-8859-1', 'UTF-8')) .
                sprintf('%-30s', mb_convert_encoding($rps->contractorRPS->bairro, 'ISO-8859-1', 'UTF-8')) .
                sprintf('%-50s', mb_convert_encoding($rps->contractorRPS->cidade, 'ISO-8859-1', 'UTF-8')) .
                sprintf('%-2s', $rps->contractorRPS->estado) .
                sprintf('%08s', $rps->contractorRPS->cep) .
                sprintf('%-75s', $rps->contractorRPS->email) .
                str_replace("\n", '|', mb_convert_encoding($rps->discriminacao, 'ISO-8859-1', 'UTF-8'));
            $body .= $line . chr(13) . chr(10);
        }
        return $body;
    }

    /**
     * Create a batch file with NF-e text layout
     *
     * @param array $rangeDate
     * @param array $valorTotal
     * @param array $rps
     * @return bool|string
     */
    public function textFile($rangeDate, $valorTotal, $rps)
    {
        $header = "1" .
            "001" .
            $this->ccmPrestador .
            date("Ymd", $rangeDate['inicio']) .
            date("Ymd", $rangeDate['fim']) .
            chr(13) . chr(10);
        $body = '';
        foreach ($rps as $item) {
            $this->insertTextRPS($item, $body);
        }
        $footer = "9" .
            sprintf("%07s", count($rps)) .
            sprintf("%015s", str_replace('.', '', sprintf('%.2f', $valorTotal['servicos']))) .
            sprintf("%015s", str_replace('.', '', sprintf('%.2f', $valorTotal['deducoes']))) .
            chr(13) . chr(10);
        $rpsFileName = date("Y-m-d_Hi") . '.txt';
        $rpsFullPath = $this->rpsDirectory . DIRECTORY_SEPARATOR . $rpsFileName;
        if (!is_dir($this->rpsDirectory)) {
            mkdir($this->rpsDirectory, 0777, true);
        }
        if (!file_put_contents($rpsFullPath, $header . $body . $footer)) {
            throw new \Common\Exception\IOException(sprintf('Cannot create RPS file (%s)', $rpsFullPath));
        }
        return $rpsFullPath;
    }
}
