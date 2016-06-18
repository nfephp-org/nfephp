<?php

namespace NFePHP\Common\Certificate;

/**
 * Classe para tratamento e uso dos certificados digitais modelo A1 (PKCS12)
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Certificate
 * @copyright Copyright (c) 2008-2014
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Certificate\Asn;
use NFePHP\Common\Exception;
use NFePHP\Common\Dom\Dom;

class Pkcs12
{
    /**
     * Path para o diretorio onde o arquivo pfx está localizado
     *
     * @var string
     */
    public $pathCerts = '';
    
    /**
     * Path para o arquivo pfx (certificado digital em formato de transporte)
     *
     * @var string
     */
    public $pfxFileName = '';
    
    /**
     * Conteudo do arquivo pfx
     *
     * @var string
     */
    public $pfxCert = '';
    
    /**
     * Numero do CNPJ do emitente
     *
     * @var string
     */
    public $cnpj = '';
    
    /**
     * String que contêm a chave publica em formato PEM
     *
     * @var string
     */
    public $pubKey = '';
    
    /**
     * String quem contêm a chave privada em formato PEM
     *
     * @var string
     */
    public $priKey = '';
    
    /**
     * String que conten a combinação da chave publica e privada em formato PEM
     * e a cadeida completa de certificação caso exista
     *
     * @var string
     */
    public $certKey = '';
    
    /**
     * Flag para ignorar testes de validade do certificado
     * isso é usado apenas para fins de testes
     *
     * @var boolean
     */
    public $ignoreValidCert = false;
    
    /**
     * Path para a chave publica em arquivo
     *
     * @var string
     */
    public $pubKeyFile = '';
    
    /**
     * Path para a chave privada em arquivo
     *
     * @var string
     */
    public $priKeyFile = '';
    
    /**
     * Path para o certificado em arquivo
     *
     * @var string
     */
    public $certKeyFile = '';
    
    /**
     * Timestamp da data de validade do certificado
     *
     * @var float
     */
    public $expireTimestamp = 0;
    
    /**
     * Mensagem de erro da classe
     *
     * @var string
     */
    public $error = '';
    
    /**
     * Id do docimento sendo assinado
     *
     * @var string
     */
    public $docId = '';

    /**
     * Método de construção da classe
     *
     * @param string  $pathCerts       Path para a pasta que contêm os certificados digitais
     * @param string  $cnpj            CNPJ do emitente, sem  ./-, apenas os numeros
     * @param string  $pubKey          Chave publica em formato PEM, não o path mas a chave em si
     * @param string  $priKey          Chave privada em formato PEM, não o path mas a chave em si
     * @param string  $certKey         Certificado em formato PEM, não o path mas a chave em si
     * @param bool    $ignoreValidCert
     * @param boolean $ignoreValidCert Ignora a validade do certificado, mais usado para fins de teste
     */
    public function __construct(
        $pathCerts = '',
        $cnpj = '',
        $pubKey = '',
        $priKey = '',
        $certKey = '',
        $ignoreValidCert = false
    ) {
        $ncnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (empty($pathCerts)) {
            //estabelecer diretorio default
            $pathCerts = dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'certs'.DIRECTORY_SEPARATOR;
        }
        if (! empty($pathCerts)) {
            if (!is_dir(trim($pathCerts))) {
                throw new Exception\InvalidArgumentException(
                    "Um path válido para os certificados deve ser passado."
                    . " Diretório [$pathCerts] não foi localizado."
                );
            }
            $this->pathCerts = trim($pathCerts);
        }
        $this->ignoreValidCert = $ignoreValidCert;
        $flagCert = false;
        if ($pubKey != '' && $priKey != '' && strlen($pubKey) > 500 && strlen($priKey) > 500) {
            $this->pubKey = $pubKey;
            $this->priKey = $priKey;
            $this->certKey = $priKey."\r\n".$pubKey;
            $flagCert = true;
        }
        if ($certKey != '') {
            $this->certKey = $certKey;
        }
        $this->cnpj = $ncnpj;
        if (! $this->zInit($flagCert)) {
            throw new Exception\RuntimeException($this->error);
        }
    }
    
    /**
     * zInit
     * Método de inicialização da classe irá verificar
     * os parâmetros, arquivos e validade dos mesmos
     * Em caso de erro o motivo da falha será indicada na parâmetro
     * error da classe, os outros parâmetros serão limpos e os
     * arquivos inválidos serão removidos da pasta
     *
     * @param  boolean $flagCert indica que as chaves já foram passas como strings
     * @return boolean
     */
    private function zInit($flagCert = false)
    {
        //se as chaves foram passadas na forma de strings então verificar a validade
        if ($flagCert) {
            //já que o certificado existe, verificar seu prazo de validade
            //o certificado será removido se estiver vencido
            if (!$this->ignoreValidCert) {
                return $this->zValidCerts($this->pubKey);
            }
        } else {
            if (substr($this->pathCerts, -1) !== DIRECTORY_SEPARATOR) {
                $this->pathCerts .= DIRECTORY_SEPARATOR;
            }
            //monta o path completo com o nome da chave privada
            $this->priKeyFile = $this->pathCerts.$this->cnpj.'_priKEY.pem';
            //monta o path completo com o nome da chave publica
            $this->pubKeyFile =  $this->pathCerts.$this->cnpj.'_pubKEY.pem';
            //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
            $this->certKeyFile = $this->pathCerts.$this->cnpj.'_certKEY.pem';
            //se as chaves não foram passadas em strings, verifica se os certificados existem
            if (is_file($this->priKeyFile) && is_file($this->pubKeyFile) && is_file($this->certKeyFile)) {
                //se as chaves existem deve ser verificado sua validade
                $this->pubKey = file_get_contents($this->pubKeyFile);
                $this->priKey = file_get_contents($this->priKeyFile);
                $this->certKey = file_get_contents($this->certKeyFile);
                //já que o certificado existe, verificar seu prazo de validade
                if (! $this->ignoreValidCert) {
                    return $this->zValidCerts($this->pubKey);
                }
            }
        }
        return true;
    }//fim init

    /**
     * loadPfxFile
     *
     * @param  string $pathPfx        caminho completo para o arquivo pfx
     * @param  string $password       senha para abrir o certificado pfx
     * @param  bool   $createFiles
     * @param  bool   $ignoreValidity
     * @param  bool   $ignoreOwner
     * @return bool
     */
    public function loadPfxFile(
        $pathPfx = '',
        $password = '',
        $createFiles = true,
        $ignoreValidity = false,
        $ignoreOwner = false
    ) {
        if (! is_file($pathPfx)) {
            throw new Exception\InvalidArgumentException(
                "O nome do arquivo PFX deve ser passado. Não foi localizado o arquivo [$pathPfx]."
            );
        }
        $this->pfxCert = file_get_contents($pathPfx);
        return $this->loadPfx($this->pfxCert, $password, $createFiles, $ignoreValidity, $ignoreOwner);
    }

    /**
     * loadPfx
     * Carrega um novo certificado no formato PFX
     * Isso deverá ocorrer a cada atualização do certificado digital, ou seja,
     * pelo menos uma vez por ano, uma vez que a validade do certificado
     * é anual.
     * Será verificado também se o certificado pertence realmente ao CNPJ
     * Essa verificação checa apenas se o certificado pertence a matriz ou filial
     * comparando apenas os primeiros 8 digitos do CNPJ, dessa forma ambas a
     * matriz e as filiais poderão usar o mesmo certificado indicado na instanciação
     * da classe, se não for um erro irá ocorrer e
     * o certificado não será convertido para o formato PEM.
     * Em caso de erros, será retornado false e o motivo será indicado no
     * parâmetro error da classe.
     * Os certificados serão armazenados como <CNPJ>-<tipo>.pem
     *
     * @param  string  $pfxContent     arquivo PFX
     * @param  string  $password       Senha de acesso ao certificado PFX
     * @param  boolean $createFiles    se true irá criar os arquivos pem das chaves digitais, caso contrario não
     * @param  bool    $ignoreValidity
     * @param  bool    $ignoreOwner
     * @return bool
     */
    public function loadPfx(
        $pfxContent = '',
        $password = '',
        $createFiles = true,
        $ignoreValidity = false,
        $ignoreOwner = false
    ) {
        if ($password == '') {
            throw new Exception\InvalidArgumentException(
                "A senha de acesso para o certificado pfx não pode ser vazia."
            );
        }
        //carrega os certificados e chaves para um array denominado $x509certdata
        $x509certdata = array();
        if (!openssl_pkcs12_read($pfxContent, $x509certdata, $password)) {
            throw new Exception\RuntimeException(
                "O certificado não pode ser lido!! Senha errada ou arquivo corrompido ou formato inválido!!"
            );
        }
        $this->pfxCert = $pfxContent;
        if (!$ignoreValidity) {
            //verifica sua data de validade
            if (! $this->zValidCerts($x509certdata['cert'])) {
                throw new Exception\RuntimeException($this->error);
            }
        }
        if (!$ignoreOwner) {
            $cnpjCert = Asn::getCNPJCert($x509certdata['cert']);
            if (substr($this->cnpj, 0, 8) != substr($cnpjCert, 0, 8)) {
                throw new Exception\InvalidArgumentException(
                    "O Certificado fornecido pertence a outro CNPJ!!"
                );
            }
        }
        //monta o path completo com o nome da chave privada
        $this->priKeyFile = $this->pathCerts.$this->cnpj.'_priKEY.pem';
        //monta o path completo com o nome da chave publica
        $this->pubKeyFile =  $this->pathCerts.$this->cnpj.'_pubKEY.pem';
        //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
        $this->certKeyFile = $this->pathCerts.$this->cnpj.'_certKEY.pem';
        $this->zRemovePemFiles();
        if ($createFiles) {
            $this->zSavePemFiles($x509certdata);
        }
        $this->pubKey=$x509certdata['cert'];
        $this->priKey=$x509certdata['pkey'];
        $this->certKey=$x509certdata['pkey']."\r\n".$x509certdata['cert'];
        return true;
    }
    
    /**
     * zSavePemFiles
     *
     * @param  array $x509certdata
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    private function zSavePemFiles($x509certdata)
    {
        if (empty($this->pathCerts)) {
            throw new Exception\InvalidArgumentException(
                "Não está definido o diretório para armazenar os certificados."
            );
        }
        if (! is_dir($this->pathCerts)) {
            throw new Exception\InvalidArgumentException(
                "Não existe o diretório para armazenar os certificados."
            );
        }
        //recriar os arquivos pem com o arquivo pfx
        if (!file_put_contents($this->priKeyFile, $x509certdata['pkey'])) {
            throw new Exception\RuntimeException(
                "Falha de permissão de escrita na pasta dos certificados!!"
            );
        }
        file_put_contents($this->pubKeyFile, $x509certdata['cert']);
        file_put_contents($this->certKeyFile, $x509certdata['pkey']."\r\n".$x509certdata['cert']);
    }
    
    /**
     * aadChain
     *
     * @param  array $aCerts Array com os caminhos completos para cada certificado da cadeia
     *                     ou um array com o conteúdo desses certificados
     * @return void
     */
    public function aadChain($aCerts = array())
    {
        $certificate = $this->certKey;
        foreach ($aCerts as $cert) {
            if (is_file($cert)) {
                $dados = file_get_contents($cert);
                $certificate .= "\r\n" . $dados;
            } else {
                $certificate .= "\r\n" . $cert;
            }
        }
        $this->certKey = $certificate;
        if (is_file($this->certKeyFile)) {
            file_put_contents($this->certKeyFile, $certificate);
        }
    }
    
    /**
     * signXML
     *
     * @param  string $docxml
     * @param  string $tagid
     * @return string xml assinado
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function signXML($docxml, $tagid = '')
    {
        //caso não tenha as chaves cai fora
        if ($this->pubKey == '' || $this->priKey == '') {
            $msg = "As chaves não estão disponíveis.";
            throw new Exception\InvalidArgumentException($msg);
        }
        //caso não seja informada a tag a ser assinada cai fora
        if ($tagid == '') {
            $msg = "A tag a ser assinada deve ser indicada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        //carrega a chave privada no openssl
        $objSSLPriKey = openssl_get_privatekey($this->priKey);
        if ($objSSLPriKey === false) {
            $msg = "Houve erro no carregamento da chave privada.";
            $this->zGetOpenSSLError($msg);
            //while ($erro = openssl_error_string()) {
            //    $msg .= $erro . "\n";
            //}
            //throw new Exception\RuntimeException($msg);
        }
        $xml = $docxml;
        if (is_file($docxml)) {
            $xml = file_get_contents($docxml);
        }
        //remove sujeiras do xml
        $order = array("\r\n", "\n", "\r", "\t");
        $xml = str_replace($order, '', $xml);
        $xmldoc = new Dom();
        $xmldoc->loadXMLString($xml);
        //coloca o node raiz em uma variável
        $root = $xmldoc->documentElement;
        //extrair a tag com os dados a serem assinados
        $node = $xmldoc->getElementsByTagName($tagid)->item(0);
        if (!isset($node)) {
            throw new Exception\RuntimeException(
                "A tag < $tagid > não existe no XML!!"
            );
        }
        $this->docId = $node->getAttribute('Id');
        $xmlResp = $xml;
        if (! $this->zSignatureExists($xmldoc)) {
            //executa a assinatura
            $xmlResp = $this->zSignXML($xmldoc, $root, $node, $objSSLPriKey);
        }
        //libera a chave privada
        openssl_free_key($objSSLPriKey);
        return $xmlResp;
    }

    /**
     * zSignXML
     * Método que provê a assinatura do xml conforme padrão SEFAZ
     *
     * @param    DOMDocument $xmldoc
     * @param    DOMElement  $root
     * @param    DOMElement  $node
     * @param    resource    $objSSLPriKey
     * @return   string xml assinado
     * @internal param DOMDocument $xmlDoc
     */
    private function zSignXML($xmldoc, $root, $node, $objSSLPriKey)
    {
        $nsDSIG = 'http://www.w3.org/2000/09/xmldsig#';
        $nsCannonMethod = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        $nsSignatureMethod = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
        $nsTransformMethod1 ='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
        $nsTransformMethod2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        $nsDigestMethod = 'http://www.w3.org/2000/09/xmldsig#sha1';
        //pega o atributo id do node a ser assinado
        $idSigned = trim($node->getAttribute("Id"));
        //extrai os dados da tag para uma string na forma canonica
        $dados = $node->C14N(true, false, null, null);
        //calcular o hash dos dados
        $hashValue = hash('sha1', $dados, true);
        //converter o hash para base64
        $digValue = base64_encode($hashValue);
        //cria o node <Signature>
        $signatureNode = $xmldoc->createElementNS($nsDSIG, 'Signature');
        //adiciona a tag <Signature> ao node raiz
        $root->appendChild($signatureNode);
        //cria o node <SignedInfo>
        $signedInfoNode = $xmldoc->createElement('SignedInfo');
        //adiciona o node <SignedInfo> ao <Signature>
        $signatureNode->appendChild($signedInfoNode);
        //cria no node com o método de canonização dos dados
        $canonicalNode = $xmldoc->createElement('CanonicalizationMethod');
        //adiona o <CanonicalizationMethod> ao node <SignedInfo>
        $signedInfoNode->appendChild($canonicalNode);
        //seta o atributo ao node <CanonicalizationMethod>
        $canonicalNode->setAttribute('Algorithm', $nsCannonMethod);
        //cria o node <SignatureMethod>
        $signatureMethodNode = $xmldoc->createElement('SignatureMethod');
        //adiciona o node <SignatureMethod> ao node <SignedInfo>
        $signedInfoNode->appendChild($signatureMethodNode);
        //seta o atributo Algorithm ao node <SignatureMethod>
        $signatureMethodNode->setAttribute('Algorithm', $nsSignatureMethod);
        //cria o node <Reference>
        $referenceNode = $xmldoc->createElement('Reference');
        //adiciona o node <Reference> ao node <SignedInfo>
        $signedInfoNode->appendChild($referenceNode);
        //seta o atributo URI a node <Reference>
        $referenceNode->setAttribute('URI', '#'.$idSigned);
        //cria o node <Transforms>
        $transformsNode = $xmldoc->createElement('Transforms');
        //adiciona o node <Transforms> ao node <Reference>
        $referenceNode->appendChild($transformsNode);
        //cria o primeiro node <Transform> OBS: no singular
        $transfNode1 = $xmldoc->createElement('Transform');
        //adiciona o primeiro node <Transform> ao node <Transforms>
        $transformsNode->appendChild($transfNode1);
        //set o atributo Algorithm ao primeiro node <Transform>
        $transfNode1->setAttribute('Algorithm', $nsTransformMethod1);
        //cria outro node <Transform> OBS: no singular
        $transfNode2 = $xmldoc->createElement('Transform');
        //adiciona o segundo node <Transform> ao node <Transforms>
        $transformsNode->appendChild($transfNode2);
        //set o atributo Algorithm ao segundo node <Transform>
        $transfNode2->setAttribute('Algorithm', $nsTransformMethod2);
        //cria o node <DigestMethod>
        $digestMethodNode = $xmldoc->createElement('DigestMethod');
        //adiciona o node <DigestMethod> ao node <Reference>
        $referenceNode->appendChild($digestMethodNode);
        //seta o atributo Algorithm ao node <DigestMethod>
        $digestMethodNode->setAttribute('Algorithm', $nsDigestMethod);
        //cria o node <DigestValue>
        $digestValueNode = $xmldoc->createElement('DigestValue', $digValue);
        //adiciona o node <DigestValue> ao node <Reference>
        $referenceNode->appendChild($digestValueNode);
        //extrai node <SignedInfo> para uma string na sua forma canonica
        $cnSignedInfoNode = $signedInfoNode->C14N(true, false, null, null);
        //cria uma variavel vazia que receberá a assinatura
        $signature = '';
        //calcula a assinatura do node canonizado <SignedInfo>
        //usando a chave privada em formato PEM
        if (! openssl_sign($cnSignedInfoNode, $signature, $objSSLPriKey)) {
            $msg = "Houve erro durante a assinatura digital.\n";
            $this->zGetOpenSSLError($msg);
            //while ($erro = openssl_error_string()) {
            //    $msg .= $erro . "\n";
            //}
            //throw new Exception\RuntimeException($msg);
        }
        //converte a assinatura em base64
        $signatureValue = base64_encode($signature);
        //cria o node <SignatureValue>
        $signatureValueNode = $xmldoc->createElement('SignatureValue', $signatureValue);
        //adiciona o node <SignatureValue> ao node <Signature>
        $signatureNode->appendChild($signatureValueNode);
        //cria o node <KeyInfo>
        $keyInfoNode = $xmldoc->createElement('KeyInfo');
        //adiciona o node <KeyInfo> ao node <Signature>
        $signatureNode->appendChild($keyInfoNode);
        //cria o node <X509Data>
        $x509DataNode = $xmldoc->createElement('X509Data');
        //adiciona o node <X509Data> ao node <KeyInfo>
        $keyInfoNode->appendChild($x509DataNode);
        //remove linhas desnecessárias do certificado
        $pubKeyClean = $this->zCleanPubKey();
        //cria o node <X509Certificate>
        $x509CertificateNode = $xmldoc->createElement('X509Certificate', $pubKeyClean);
        //adiciona o node <X509Certificate> ao node <X509Data>
        $x509DataNode->appendChild($x509CertificateNode);
        //salva o xml completo em uma string
        $xmlResp = $xmldoc->saveXML();
        //retorna o documento assinado
        return $xmlResp;
    }
   
    /**
     * signatureExists
     * Check se o xml possi a tag Signature
     *
     * @param  DOMDocument $dom
     * @return boolean
     */
    private function zSignatureExists($dom)
    {
        $signature = $dom->getElementsByTagName('Signature')->item(0);
        if (! isset($signature)) {
            return false;
        }
        return true;
    }
    
    /**
     * verifySignature
     * Verifica a validade da assinatura digital contida no xml
     *
     * @param  string $docxml conteudo do xml a ser verificado ou o path completo
     * @param  string $tagid  tag que foi assinada no documento xml
     * @return boolean
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function verifySignature($docxml = '', $tagid = '')
    {
        if ($docxml == '') {
            $msg = "Não foi passado um xml para a verificação.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tagid == '') {
            $msg = "Não foi indicada a TAG a ser verificada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        $xml = $docxml;
        if (is_file($docxml)) {
            $xml = file_get_contents($docxml);
        }
        $dom = new Dom();
        $dom->loadXMLString($xml);
        $flag = $this->zDigCheck($dom, $tagid);
        $flag = $this->zSignCheck($dom);
        return $flag;
    }
    
    /**
     * zSignCheck
     *
     * @param  DOMDocument $dom
     * @return boolean
     * @throws Exception\RuntimeException
     */
    private function zSignCheck($dom)
    {
        // Obter e remontar a chave publica do xml
        $x509Certificate = $dom->getNodeValue('X509Certificate');
        $x509Certificate =  "-----BEGIN CERTIFICATE-----\n"
            . $this->zSplitLines($x509Certificate)
            . "\n-----END CERTIFICATE-----\n";
        //carregar a chave publica remontada
        $objSSLPubKey = openssl_pkey_get_public($x509Certificate);
        if ($objSSLPubKey === false) {
            $msg = "Ocorreram problemas ao carregar a chave pública. Certificado incorreto ou corrompido!!";
            $this->zGetOpenSSLError($msg);
            //while ($erro = openssl_error_string()) {
            //    $msg .= $erro . "\n";
            //}
            //throw new Exception\RuntimeException($msg);
        }
        //remontando conteudo que foi assinado
        $signContent = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(true, false, null, null);
        // validando assinatura do conteudo
        $signatureValueXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
        $decodedSignature = base64_decode(str_replace(array("\r", "\n"), '', $signatureValueXML));
        $resp = openssl_verify($signContent, $decodedSignature, $objSSLPubKey);
        if ($resp != 1) {
            $msg = "Problema ({$resp}) ao verificar a assinatura do digital!!";
            $this->zGetOpenSSLError($msg);
            //while ($erro = openssl_error_string()) {
            //    $msg .= $erro . "\n";
            //}
            //throw new Exception\RuntimeException($msg);
        }
        return true;
    }
    
    /**
     * zDigCheck
     *
     * @param  DOMDocument $dom
     * @param  string      $tagid
     * @return boolean
     * @throws Exception\RuntimeException
     */
    private function zDigCheck($dom, $tagid = '')
    {
        $node = $dom->getNode($tagid, 0);
        if (empty($node)) {
            throw new Exception\RuntimeException(
                "A tag < $tagid > não existe no XML!!"
            );
        }
        if (! $this->zSignatureExists($dom)) {
            $msg = "O xml não contêm nenhuma assinatura para ser verificada.";
            throw new Exception\RuntimeException($msg);
        }
        //carregar o node em sua forma canonica
        $tagInf = $node->C14N(true, false, null, null);
        //calcular o hash sha1
        $hashValue = hash('sha1', $tagInf, true);
        //converter o hash para base64 para obter o digest do node
        $digestCalculado = base64_encode($hashValue);
        //pegar o digest informado no xml
        $digestInformado = $dom->getNodeValue('DigestValue');
        //compara os digests calculados e informados
        if ($digestCalculado != $digestInformado) {
            $msg = "O conteúdo do XML não confere com o Digest Value.\n
                Digest calculado [{$digestCalculado}], digest informado no XML [{$digestInformado}].\n
                O arquivo pode estar corrompido ou ter sido adulterado.";
            throw new Exception\RuntimeException($msg);
        }
        return true;
    }
    
    /**
     * zValidCerts
     * Verifica a data de validade do certificado digital
     * e compara com a data de hoje.
     * Caso o certificado tenha expirado o mesmo será removido das
     * pastas e o método irá retornar false.
     *
     * @param  string $pubKey chave publica
     * @return boolean
     */
    protected function zValidCerts($pubKey)
    {
        if (! $data = openssl_x509_read($pubKey)) {
                //o dado não é uma chave válida
                $this->zRemovePemFiles();
                $this->zLeaveParam();
                $this->error = "A chave passada está corrompida ou não é uma chave. Obtenha s chaves corretas!!";
                return false;
        }
        $certData = openssl_x509_parse($data);
        // reformata a data de validade;
        $ano = substr($certData['validTo'], 0, 2);
        $mes = substr($certData['validTo'], 2, 2);
        $dia = substr($certData['validTo'], 4, 2);
        //obtem o timestamp da data de validade do certificado
        $dValid = gmmktime(0, 0, 0, $mes, $dia, $ano);
        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
        // compara a data de validade com a data atual
        $this->expireTimestamp = $dValid;
        if ($dHoje > $dValid) {
            $this->zRemovePemFiles();
            $this->zLeaveParam();
            $msg = "Data de validade vencida! [Valido até $dia/$mes/$ano]";
            $this->error = $msg;
            return false;
        }
        return true;
    }
    
    /**
     * zCleanPubKey
     * Remove a informação de inicio e fim do certificado
     * contido no formato PEM, deixando o certificado (chave publica) pronta para ser
     * anexada ao xml da NFe
     *
     * @return string contendo o certificado limpo
     */
    protected function zCleanPubKey()
    {
        //inicializa variavel
        $data = '';
        //carregar a chave publica
        $pubKey = $this->pubKey;
        //carrega o certificado em um array usando o LF como referencia
        $arCert = explode("\n", $pubKey);
        foreach ($arCert as $curData) {
            //remove a tag de inicio e fim do certificado
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0
                && strncmp($curData, '-----END CERTIFICATE', 20) != 0
            ) {
                //carrega o resultado numa string
                $data .= trim($curData);
            }
        }
        return $data;
    }
    
    /**
     * zSplitLines
     * Divide a string do certificado publico em linhas
     * com 76 caracteres (padrão original)
     *
     * @param  string $cntIn certificado
     * @return string certificado reformatado
     */
    protected function zSplitLines($cntIn = '')
    {
        if ($cntIn != '') {
            $cnt = rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cntIn), 76, "\n"));
        } else {
            $cnt = $cntIn;
        }
        return $cnt;
    }
    
    /**
     * zRemovePemFiles
     * Apaga os arquivos PEM do diretório
     * Isso deve ser feito quando um novo certificado é carregado
     * ou quando a validade do certificado expirou.
     */
    private function zRemovePemFiles()
    {
        if (is_file($this->pubKeyFile)) {
            unlink($this->pubKeyFile);
        }
        if (is_file($this->priKeyFile)) {
            unlink($this->priKeyFile);
        }
        if (is_file($this->certKeyFile)) {
            unlink($this->certKeyFile);
        }
    }
    
    /**
     * zLeaveParam
     * Limpa os parametros da classe
     */
    private function zLeaveParam()
    {
        $this->pfxCert='';
        $this->pubKey='';
        $this->priKey='';
        $this->certKey='';
        $this->pubKeyFile='';
        $this->priKeyFile='';
        $this->certKeyFile='';
        $this->expireTimestamp='';
    }
    
    /**
     * zGetOpenSSLError
     *
     * @param  string $msg
     * @return string
     */
    protected function zGetOpenSSLError($msg = '')
    {
        while ($erro = openssl_error_string()) {
            $msg .= $erro . "\n";
        }
        throw new Exception\RuntimeException($msg);
    }
}
