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
    private $cnpjPrestador = 'xxxxxxxxxxxxx'; // Your CNPJ
    private $ccmPrestador = 'xxxxxxxxx'; // Your CCM
    private $passphrase = 'xxxxxxxxxx'; // Cert passphrase
    private $pkcs12  = 'caminho_completo_para_o_seu_certificado.pfx';
    private $certDir = 'diretorio_onde_esta_seu_certificado'; // Dir for .pem certs
    private $privateKey;
    public $certDaysToExpire=0;
    private $publicKey;
    private $X509Certificate;
    private $key;
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


    public function __construct()
    {
        $this->privateKey = $this->certDir . '/privatekey.pem';
        $this->publicKey = $this->certDir . '/publickey.pem';
        $this->key = $this->certDir . '/key.pem';
        if ($this->loadCert()) {
            error_log(__METHOD__ . ': Certificado OK!');
        } else {
            error_log(__METHOD__ . ': Certificado não OK!');
        }
    }

    private function validateCert($cert)
    {
        $data = openssl_x509_read($cert);
        $certData = openssl_x509_parse($data);

        $certValidDate = gmmktime(0, 0, 0, substr($certData['validTo'], 2, 2), substr($certData['validTo'], 4, 2), substr($certData['validTo'], 0, 2));
        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
        if ($certValidDate < time()) {
            error_log(__METHOD__ . ': Certificado expirado em ' . date('Y-m-d', $certValidDate));
            return false;
        }
        //diferença em segundos entre os timestamp
        $diferenca = $certValidDate - $dHoje;
        // convertendo para dias
        $diferenca = round($diferenca /(60*60*24), 0);
        //carregando a propriedade
        $this->certDaysToExpire = $diferenca;
        return true;
    }

    private function loadCert()
    {
        $x509CertData = array();
        if (! openssl_pkcs12_read(file_get_contents($this->pkcs12), $x509CertData, $this->passphrase)) {
            error_log(__METHOD__ . ': Certificado não pode ser lido. O arquivo esta corrompido ou em formato invalido.');
            return false;
        }
        $this->X509Certificate = preg_replace("/[\n]/", '', preg_replace('/\-\-\-\-\-[A-Z]+ CERTIFICATE\-\-\-\-\-/', '', $x509CertData['cert']));
        if (! self::validateCert($x509CertData['cert'])) {
            return false;
        }
        if (! is_dir($this->certDir)) {
            if (! mkdir($this->certDir, 0777)) {
                error_log(__METHOD__ . ': Falha ao criar o diretorio ' . $this->certDir);
                return false;
            }
        }
        if (! file_exists($this->privateKey)) {
            if (! file_put_contents($this->privateKey, $x509CertData['pkey'])) {
                error_log(__METHOD__ . ': Falha ao criar o arquivo ' . $this->privateKey);
                return false;
            }
        }
        if (! file_exists($this->publicKey)) {
            if (! file_put_contents($this->publicKey, $x509CertData['cert'])) {
                error_log(__METHOD__ . ': Falha ao criar o arquivo ' . $this->publicKey);
                return false;
            }
        }
        if (! file_exists($this->key)) {
            if (! file_put_contents($this->key, $x509CertData['cert'] . $x509CertData['pkey'])) {
                error_log(__METHOD__ . ': Falha ao criar o arquivo ' . $this->key);
                return false;
            }
        }
        return true;
    }

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
            'verifypeer'    => false,
            'verifyhost'    => false,
            'soap_version'  => $soapver,
            'trace'         => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        );

        try {
            $this->connectionSoap = new SoapClient($wsdl, $params);
        } catch (SoapFault $e) {
            error_log('Exception: ' . $e->getMessage());
            echo "erro de conexão soap. Tente novamente mais tarde !<br>\n";
            echo $e->getMessage();
            return false;
        }
    }

    private function send($operation, $xmlDoc)
    {
        self::start();
        $this->signXML($xmlDoc);
        $params = array(
            'VersaoSchema' => 1,
            'MensagemXML' => $xmlDoc->saveXML()
        );
        try {
            $result = $this->connectionSoap->$operation($params);
        } catch (SoapFault $e) {
            error_log('Exception: ' . $e->getMessage());
            echo "erro soap ".$e->getMessage();
            return false;
        }
        return new SimpleXMLElement($result->RetornoXML);
    }

    private function createXML($operation)
    {
        $xmlDoc = new DOMDocument('1.0', 'UTF-8');
        $xmlDoc->preservWhiteSpace = false;
        $xmlDoc->formatOutput = false;
        $data = '<?xml version="1.0" encoding="UTF-8"?><Pedido' . $operation . ' xmlns:xsd="' . $this->urlXsd .'" xmlns="' . $this->urlNfe . '" xmlns:xsi="' . $this->urlXsi . '"></Pedido' . $operation . '>';
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

    private function createXMLp1($operation)
    {
        $xmlDoc = new DOMDocument('1.0', 'UTF-8');
        $xmlDoc->preservWhiteSpace = false;
        $xmlDoc->formatOutput = false;
        $data = '<?xml version="1.0" encoding="UTF-8"?><Pedido'.$operation.' xmlns="' . $this->urlNfe . '" xmlns:xsi="' . $this->urlXsi . '"></Pedido' . $operation . '>';
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

    private function signXML(&$xmlDoc)
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

    private function signRPS(NFeRPS $rps, &$rpsNode)
    {
        $content = sprintf('%08s', $rps->CCM).
            sprintf('%-5s', $rps->serie).
            sprintf('%012s', $rps->numero).
            str_replace("-", "", $rps->dataEmissao).
            $rps->tributacao .
            $rps->status .
            (($rps->comISSRetido) ? 'S' : 'N') .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($rps->valorServicos, 2))).
            sprintf('%015s', str_replace(array('.', ','), '', number_format($rps->valorDeducoes, 2))).
            sprintf('%05s', $rps->codigoServico) .
            (($rps->contractorRPS->type == 'F') ? '1' : '2') .
            sprintf('%014s', $rps->contractorRPS->cnpjTomador);

        $signatureValue = '';
        $pkeyId = openssl_get_privatekey(file_get_contents($this->privateKey));
        openssl_sign($content, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);
        openssl_free_key($pkeyId);
        $rpsNode->appendChild(new DOMElement('Assinatura', base64_encode($signatureValue)));
    }

    private function insertRPS(NFeRPS $rps, &$xmlDoc)
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
    *
    * @param NFeRPS $rps
    */
    public function sendRPS(NFeRPS $rps)
    {
        $operation = 'EnvioRPS';
        $xmlDoc = $this->createXML($operation);
        $this->insertRPS($rps, $xmlDoc);
        $returnXmlDoc = $this->send($operation, $xmlDoc);
        return $returnXmlDoc;
    }



    /**
     * Send a batch of RPSs to replace for NF-e
     *
     * @param array $rangeDate ('start' => start date of RPSs, 'end' => end date of RPSs)
     * @param array $valorTotal ('servicos' => total value of RPSs, 'deducoes' => total deductions on values of RPSs)
     * @param array $rps Collection of NFeRPS
     */
    public function sendRPSBatch($rangeDate, $valorTotal, $rps)
    {
        $operation = 'EnvioLoteRPS';
        $xmlDoc = $this->createXML($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('transacao', 'false'));
        $header->appendChild($xmlDoc->createElement('dtInicio', $rangeDate['inicio']));
        $header->appendChild($xmlDoc->createElement('dtFim', $rangeDate['fim']));
        $header->appendChild($xmlDoc->createElement('QtdRPS', count($rps)));
        $header->appendChild($xmlDoc->createElement('ValorTotalServicos', $valorTotal['servicos']));
        $header->appendChild($xmlDoc->createElement('ValorTotalDeducoes', $valorTotal['deducoes']));
        foreach ($rps as $item) {
            $this->insertRPS($item, $xmlDoc);
        }
        return $this->send($operation, $xmlDoc);
    }

    /**
     * Send a batch of RPSs to replace for NF-e for test only
     *
     * @param array $rangeDate ('start' => start date of RPSs, 'end' => end date of RPSs)
     * @param array $valorTotal ('servicos' => total value of RPSs, 'deducoes' => total deductions on values of RPSs)
     * @param array $rps Collection of NFeRPS
     */
    public function sendRPSBatchTest($rangeDate, $valorTotal, $rps)
    {
        $operation = 'EnvioLoteRPS';
        $xmlDoc = $this->createXML($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('transacao', 'false'));
        $header->appendChild($xmlDoc->createElement('dtInicio', $rangeDate['inicio']));
        $header->appendChild($xmlDoc->createElement('dtFim', $rangeDate['fim']));
        $header->appendChild($xmlDoc->createElement('QtdRPS', count($rps)));
        $header->appendChild($xmlDoc->createElement('ValorTotalServicos', $valorTotal['servicos']));
        $header->appendChild($xmlDoc->createElement('ValorTotalDeducoes', $valorTotal['deducoes']));
        foreach ($rps as $item) {
            $this->insertRPS($item, $xmlDoc);
        }
        //    $docxml = $xmlDoc->saveXML();
        //    echo "xml gerado[<br>\n";
        //    print_r($docxml);
        //    echo "]<br>\n";
        //    exit();
        $return = $this->send('TesteEnvioLoteRPS', $xmlDoc);
        $xmlDoc->formatOutput = true;
        error_log(__METHOD__ . ': ' . $xmlDoc->saveXML());
        return $return;
    }

    /**
     *
     * @param array $nfe Array of NFe numbers
     */
    public function cancelNFe($nfeNumbers)
    {
        $operation = 'CancelamentoNFe';
        $xmlDoc = $this->createXML($operation);
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
            $digestValue = base64_encode(hash('sha1', $content, true));
            $pkeyId = openssl_get_privatekey(file_get_contents($this->privateKey));
            //      openssl_sign($digestValue, $signatureValue, $pkeyId);
            openssl_sign($content, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);
            openssl_free_key($pkeyId);
            $detail->appendChild(new DOMElement('AssinaturaCancelamento', base64_encode($signatureValue)));
        }
        $docxml = $xmlDoc->saveXML();
        return $this->send($operation, $xmlDoc);
    }

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
     *
     * @param string $cnpj
     * @param string $ccm
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     */
    private function queryNFeWithDateRange($cnpj, $ccm, $startDate, $endDate)
    {
        $operation = 'ConsultaNFePeriodo';
        $xmlDoc = $this->createXML($operation);
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
        $pageNumber = $xmlDoc->createElement('NumeroPagina', 1);
        $header->appendChild($pageNumber);
        return $xmlDoc;
    }

    /**
     * Query NF-e's that CNPJ/CCM company received from other companies
     *
     * @param string $cnpj
     * @param string $ccm
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     */
    public function queryNFeReceived($cnpj, $ccm, $startDate, $endDate)
    {
        $operation = 'ConsultaNFeRecebidas';
        $xmlDoc = $this->queryNFeWithDateRange($cnpj, $ccm, $startDate, $endDate);
        return $this->send($operation, $xmlDoc);
    }

    /**
     * Query NF-e's that CNPJ/CCM company issued to other companies
     *
     * @param string $cnpj
     * @param string $ccm
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     */
    public function queryNFeIssued($cnpj, $ccm, $startDate, $endDate)
    {
        $operation = 'ConsultaNFeEmitidas';
        $xmlDoc = $this->queryNFeWithDateRange($cnpj, $ccm, $startDate, $endDate);
        return $this->send($operation, $xmlDoc);
    }

    public function queryBatch($batchNumber)
    {
        $operation = 'ConsultaLote';
        $xmlDoc = $this->createXML($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('NumeroLote', $batchNumber));
        return $this->send($operation, $xmlDoc);
    }


    /**
     * If $batchNumber param is null, last match info will be returned
     *
     * @param integer $batchNumber
     */
    public function queryBatchInfo($batchNumber = null)
    {
        $operation = 'InformacoesLote';
        $xmlDoc = $this->createXML($operation);
        $header = $xmlDoc->documentElement->getElementsByTagName('Cabecalho')->item(0);
        $header->appendChild($xmlDoc->createElement('InscricaoPrestador', $this->ccmPrestador));
        if ($batchNumber) {
            $header->appendChild($xmlDoc->createElement('NumeroLote', $batchNumber));
        }
        return $this->send($operation, $xmlDoc);
    }

    /**
     * Returns CCM for given CNPJ
     *
     * @param string $cnpj
     */
    public function queryCNPJ($cnpj)
    {
        $operation = 'ConsultaCNPJ';
        $xmlDoc = $this->createXMLp1($operation);
        $root = $xmlDoc->documentElement;
        $cnpjTaxpayer = $xmlDoc->createElementNS('', 'CNPJContribuinte');
        if (strlen($cnpj) == 11) {
            $cnpjTaxpayer->appendChild($xmlDoc->createElement('CPF', (string) sprintf('%011s', $cnpj)));
        } else {
            $cnpjTaxpayer->appendChild($xmlDoc->createElement('CNPJ', (string) sprintf('%014s', $cnpj)));
        }
        $root->appendChild($cnpjTaxpayer);
        $docxml = $xmlDoc->saveXML();
        if ($return = $this->send($operation, $xmlDoc)) {
            if ($return->Detalhe->InscricaoMunicipal <> "") {
                return $return->Detalhe->InscricaoMunicipal;
            } else {
                if ($return->Alerta->Codigo <> "") {
                    return $return->Alerta->Descricao;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Create a line with RPS description for batch file
     *
     * @param unknown_type $rps
     * @param unknown_type $body
     */
    private function insertTextRPS(NFeRPS $rps, &$body)
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
    }

    /**
     * Create a batch file with NF-e text layout
     *
     * @param unknown_type $rangeDate
     * @param unknown_type $valorTotal
     * @param unknown_type $rps
     */
    public function textFile($rangeDate, $valorTotal, $rps)
    {
        $file = '';
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
        $rpsDir = '/patch/for/rps/batch/file';
        $rpsFileName = date("Y-m-d_Hi") . '.txt';
        $rpsFullPath = $rpsDir . '/' . $rpsFileName;
        if (! is_dir($rpsDir)) {
            if (! mkdir($rpsDir, 0777)) {

            }
        }
        if (! file_put_contents($rpsFullPath, $header . $body . $footer)) {
            error_log(__METHOD__ . ': Cannot create rps file ' . $rpsFullPath);
            return false;
        }
        return $rpsFullPath;
    }
}
