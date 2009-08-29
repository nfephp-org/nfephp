<?
/**
 * Conjunto de classes para manipulação de NF-e
 * baseado na NFEtools de Roberto L. Machado
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 *
 * @date    20/Agosto/2009
 *
 * @license Creative Commons
 *
 * @version $Id
 *
 *
 * TODO
 *       - verificações, validações e tratamento de erros
 *       - comentários para phpdoc
 *       - flexibilização para várias versões de layout
 *       - separar em arquivos o config e cada conjunto de classes de um serviço web
 *       - fazer carregamento das URLs webservices conforme _NFE_TPAMB
 *
**/



/*************************************/
/**
 * configNFe.inc.php
 * Arquivo de configurações da NF-e
 * dfadel, 20.08.2009
**/

define ('_NFE_LIB_PATH',            './libs');                          // path do repositório de libs (dependências)

define ('_NFE_TPAMB',               '2');                               // tipo de ambiente: 1-producao, 2-homologacao
define ('_NFE_CUF',                 '35');                              // código da UF do emitente

define ('_NFE_CERTIFICATE_FILE',    './certificate/XXXXXXXXX.p12');     // path/file do certificado p12 (pfx)
define ('_NFE_PUBLICKEY_FILE',      './certificate/publickey.pem');     // path/file da chave publica
define ('_NFE_PRIVATEKEY_FILE',     './certificate/privatekey.pem');    // path/file da chave privada
define ('_NFE_PASSKEY',             'XXXXX');                           // senha da chave
define ('_NFE_PASSPHRASE',          '');                                // senha de decriptacao (normalmente não usado)


// PATHS DOS XMLS GERADOS E RECEBIDOS
define ('_NFE_NFE_PATH',            'xml/'.date('Y').'/'.date('m').'/nfe');             // path do XML da NFe

define ('_NFE_ENVNFE_PATH',         'xml/'.date('Y').'/'.date('m').'/enviadas');        // path do XML de envio de lote de NFe
define ('_NFE_RETENVNFE_PATH',      'xml/'.date('Y').'/'.date('m').'/enviadas');        // path do XML do recibo de envio de lote de NFe

define ('_NFE_CONSRECINFE_PATH',    'xml/'.date('Y').'/'.date('m').'/retorno');         // path do XML de consulta de lote de NFe
define ('_NFE_RETCONSRECINFE_PATH', 'xml/'.date('Y').'/'.date('m').'/retorno');         // path do XML do retorno de consulta de lote de NFe
define ('_NFE_PROTNFE_PATH',        'xml/'.date('Y').'/'.date('m').'/retorno');         // path do XML do protocolo de autorizacao da NFe

define ('_NFE_CANCNFE_PATH',        'xml/'.date('Y').'/'.date('m').'/canceladas');      // path do XML do pedido de cancelamento
define ('_NFE_RETCANCNFE_PATH',     'xml/'.date('Y').'/'.date('m').'/canceladas');      // path do XML do retorno de cancelamento

define ('_NFE_INUTNFE_PATH',        'xml/'.date('Y').'/'.date('m').'/inutilizadas');    // path do XML do pedido de inutilizacao de numeracao
define ('_NFE_RETINUTNFE_PATH',     'xml/'.date('Y').'/'.date('m').'/inutilizadas');    // path do XML do retorno de inutilizacao de numeracao

define ('_NFE_CONSSITNFE_PATH',     'xml/'.date('Y').'/'.date('m').'/situacao');        // path do XML da consulta de situacao da nfe
define ('_NFE_RETCONSSITNFE_PATH',  'xml/'.date('Y').'/'.date('m').'/situacao');        // path do XML do retorno de consulta de situacao da nfe

define ('_NFE_CONSSTATNFE_PATH',    'xml/'.date('Y').'/'.date('m').'/status');          // path do XML da consulta do status
define ('_NFE_RETCONSSTATNFE_PATH', 'xml/'.date('Y').'/'.date('m').'/status');          // path do XML do retorno da consulta do status

define ('_NFE_PROCNFE_PATH',        'xml/'.date('Y').'/'.date('m').'/nfe_proc');        // path do XML das NFe processadas (distribuicao)

umask(0);
(!is_dir(_NFE_NFE_PATH))            ? mkdir(_NFE_NFE_PATH,              0777, true) : null;
(!is_dir(_NFE_ENVNFE_PATH))         ? mkdir(_NFE_ENVNFE_PATH,           0777, true) : null;
(!is_dir(_NFE_RETENVNFE_PATH))      ? mkdir(_NFE_RETENVNFE_PATH,        0777, true) : null;
(!is_dir(_NFE_CONSRECINFE_PATH))    ? mkdir(_NFE_CONSRECINFE_PATH,      0777, true) : null;
(!is_dir(_NFE_RETCONSRECINFE_PATH)) ? mkdir(_NFE_RETCONSRECINFE_PATH,   0777, true) : null;
(!is_dir(_NFE_PROTNFE_PATH))        ? mkdir(_NFE_PROTNFE_PATH,          0777, true) : null;
(!is_dir(_NFE_CANCNFE_PATH))        ? mkdir(_NFE_CANCNFE_PATH,          0777, true) : null;
(!is_dir(_NFE_RETCANCNFE_PATH))     ? mkdir(_NFE_RETCANCNFE_PATH,       0777, true) : null;
(!is_dir(_NFE_INUTNFE_PATH))        ? mkdir(_NFE_INUTNFE_PATH,          0777, true) : null;
(!is_dir(_NFE_RETINUTNFE_PATH))     ? mkdir(_NFE_RETINUTNFE_PATH,       0777, true) : null;
(!is_dir(_NFE_CONSSITNFE_PATH))     ? mkdir(_NFE_CONSSITNFE_PATH,       0777, true) : null;
(!is_dir(_NFE_RETCONSSITNFE_PATH))  ? mkdir(_NFE_RETCONSSITNFE_PATH,    0777, true) : null;
(!is_dir(_NFE_CONSSTATNFE_PATH))    ? mkdir(_NFE_CONSSTATNFE_PATH,      0777, true) : null;
(!is_dir(_NFE_RETCONSSTATNFE_PATH)) ? mkdir(_NFE_RETCONSSTATNFE_PATH,   0777, true) : null;
(!is_dir(_NFE_PROCNFE_PATH))        ? mkdir(_NFE_PROCNFE_PATH,          0777, true) : null;


// URLS DOS WEBSERVICES

// HOMOLOGACAO
define ('_NFE_RECEPCAO_URL',            'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nferecepcao.asmx');
define ('_NFE_RETRECEPCAO_URL',         'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nferetrecepcao.asmx');
define ('_NFE_CANCELAMENTO_URL',        'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfecancelamento.asmx');
define ('_NFE_INUTILIZACAO_URL',        'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfeinutilizacao.asmx');
define ('_NFE_CONSULTANF_URL',          'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfeconsulta.asmx');
define ('_NFE_STATUSSERVICO_URL',       'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfestatusservico.asmx');
define ('_NFE_CONSULTACADASTRO_URL',    'https://homologacao.nfe.fazenda.sp.gov.br/nfeWEB/services/cadconsultacadastro.asmx');

/*
// PRODUCAO
define ('_NFE_RECEPCAO_URL',            'https://nfe.fazenda.sp.gov.br/nfeweb/services/nferecepcao.asmx');
define ('_NFE_RETRECEPCAO_URL',         'https://nfe.fazenda.sp.gov.br/nfeweb/services/nferetrecepcao.asmx');
define ('_NFE_CANCELAMENTO_URL',        'https://nfe.fazenda.sp.gov.br/nfeweb/services/nfecancelamento.asmx');
define ('_NFE_INUTILIZACAO_URL',        'https://nfe.fazenda.sp.gov.br/nfeweb/services/nfeinutilizacao.asmx');
define ('_NFE_CONSULTANF_URL',          'https://nfe.fazenda.sp.gov.br/nfeweb/services/nfeconsulta.asmx');
define ('_NFE_STATUSSERVICO_URL',       'https://nfe.fazenda.sp.gov.br/nfeweb/services/nfestatusservico.asmx');
define ('_NFE_CONSULTACADASTRO_URL',    'https://nfe.fazenda.sp.gov.br/nfeWEB/services/cadconsultacadastro.asmx');
*/


/*************************************/




class NFeSOAP {

    private $certificado;

    function __construct() {
        $this->certificado = new certificado();
    }

    function getCabec($versao) {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $raiz = $dom->appendChild($dom->createElement('cabecMsg'));

        $raiz_att1 = $raiz->appendChild($dom->createAttribute('versao'));
        $raiz_att1->appendChild($dom->createTextNode('1.02'));

        $raiz_att2 = $raiz->appendChild($dom->createAttribute('xmlns'));
        $raiz_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $raiz->appendChild($dom->createElement('versaoDados', $versao));

        return $this->XML = $dom->saveXML();
    }

    function send($URL, $metodo, $mensagem, $versao) {

        include_once (_NFE_LIB_PATH.'/nusoap/nusoap.php');

        $client = new nusoap_client($URL."?WSDL", true);
        $client->authtype         = 'certificate';
        $client->soap_defencoding = 'UTF-8';

        $client->certRequest['sslkeyfile']  = $this->certificado->privateKeyFile;
        $client->certRequest['sslcertfile'] = $this->certificado->publicKeyFile;
        $client->certRequest['passphrase']  = $this->certificado->passPhrase;
        $client->certRequest['verifypeer']  = false;
        $client->certRequest['verifyhost']  = false;
        $client->certRequest['trace']       = 1;

        $soapMsg['nfeCabecMsg'] = $this->getCabec($versao);
        $soapMsg['nfeDadosMsg'] = $mensagem;

        $result = $client->call($metodo, $soapMsg);

        return $result;

    }

}


class certificado {

    public $certificateFile;    // path/file do certificado p12 (pfx) tipo A1
    public $privateKeyFile;     // path/file da chave privada (nao precisa existir)
    public $publicKeyFile;      // path/file da chave publica (nao precisa existir)
    public $sPrivateKey;        // string da chave privada
    public $sPublicKey;         // string do certificado (chave publica)
    public $passKey;            // senha
    public $passPhrase;         // 

    function __construct($certificateFile=_NFE_CERTIFICATE_FILE) {

        $this->certificateFile  = $certificateFile;
        $this->privateKeyFile   = _NFE_PRIVATEKEY_FILE;
        $this->publicKeyFile    = _NFE_PUBLICKEY_FILE;

        $this->passKey          = _NFE_PASSKEY;
        $this->passPhrase       = _NFE_PASSPHRASE;

        openssl_pkcs12_read(file_get_contents($this->certificateFile), $x509cert, _NFE_PASSKEY);

        // chave publica (certificado)
        $aCert = explode("\n", $x509cert['cert']);
        foreach ($aCert as $curData) {
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                $this->sPublicKey.= trim($curData);
            }
        }

        // chave privada
        $this->sPrivateKey = $x509cert['pkey'];


        if (!file_exists($this->privateKeyFile)) {
            file_put_contents($this->privateKeyFile, $x509cert['pkey']);
        }

        if (!file_exists($this->publicKeyFile)) {
            file_put_contents($this->publicKeyFile, $x509cert['cert']);
        }
    }

    function isValid() {
    }

}


class assinatura {

    private $certificado;

    function __construct() {
        $this->certificado = new certificado();
    }

    /**
     * @param   string XML
     * @param   string tagID
     * @return  mixed (FALSE se erro, senão string XML assinado)
    **/
    function assinaXML($sXML, $tagID) {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->loadXML($sXML);

        $root = $dom->documentElement;
        $node = $dom->getElementsByTagName($tagID)->item(0);

        $Id = trim($node->getAttribute("Id"));

        $idnome = ereg_replace('[^0-9]', '', $Id);

        //extrai os dados da tag para uma string
        $dados = $node->C14N(FALSE, FALSE, NULL, NULL);

        //calcular o hash dos dados
        $hashValue = hash('sha1', $dados, TRUE);

        //converte o valor para base64 para serem colocados no xml
        $digValue = base64_encode($hashValue);

        //monta a tag da assinatura digital
        $Signature = $dom->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'Signature');
        $root->appendChild($Signature);
        $SignedInfo = $dom->createElement('SignedInfo');
        $Signature->appendChild($SignedInfo);

        //Cannocalization
        $newNode = $dom->createElement('CanonicalizationMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');

        //SignatureMethod
        $newNode = $dom->createElement('SignatureMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        //Reference
        $Reference = $dom->createElement('Reference');
        $SignedInfo->appendChild($Reference);
        $Reference->setAttribute('URI', '#'.$Id);

        //Transforms
        $Transforms = $dom->createElement('Transforms');
        $Reference->appendChild($Transforms);

        //Transform
        $newNode = $dom->createElement('Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');

        //Transform
        $newNode = $dom->createElement('Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');

        //DigestMethod
        $newNode = $dom->createElement('DigestMethod');
        $Reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');

        //DigestValue
        $newNode = $dom->createElement('DigestValue', $digValue);
        $Reference->appendChild($newNode);

        // extrai os dados a serem assinados para uma string
        $dados = $SignedInfo->C14N(FALSE, FALSE, NULL, NULL);

        //inicializa a variavel que vai receber a assinatura
        $signature = '';

        //executa a assinatura digital usando o resource da chave privada
        $resp = openssl_sign($dados, $signature, openssl_pkey_get_private($this->certificado->sPrivateKey));

        //codifica assinatura para o padrao base64
        $signatureValue = base64_encode($signature);

        //SignatureValue
        $newNode = $dom->createElement('SignatureValue', $signatureValue);
        $Signature->appendChild($newNode);

        //KeyInfo
        $KeyInfo = $dom->createElement('KeyInfo');
        $Signature->appendChild($KeyInfo);

        //X509Data
        $X509Data = $dom->createElement('X509Data');
        $KeyInfo->appendChild($X509Data);

        //X509Certificate
        $newNode = $dom->createElement('X509Certificate', $this->certificado->sPublicKey);
        $X509Data->appendChild($newNode);

        //grava na string o objeto DOM
        return $dom->saveXML();

    }

}


// ARQUIVO ENVIA NFE (INICIO) **************************************************

class envNFe {

    public $versao;     // versao do layout
    public $idLote;     // id do lote
    public $aNFe;       // array de NFe's
    public $XML;        // string XML

    public $retEnvNFe;  // 

    function __construct() {
        $this->versao   = '1.10';

        $this->retEnvNFe = null;
    }

    function addNFe($XML) {
        if (count($this->aNFe) >= 50) {
            return false;
        }
        $this->aNFe[] = $XML;
    }

    function geraXML() {

        /* USAR ASSIM NO FUTURO COM PHP >= 5.3
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $FP01 = $dom->appendChild($dom->createElement('enviNFe'));

        $FP01_att1 = $FP01->appendChild($dom->createAttribute('versao'));
                     $FP01_att1->appendChild($dom->createTextNode($this->versao));

        $FP01_att2 = $FP01->appendChild($dom->createAttribute('xmlns'));
                     $FP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $FP01_att3 = $FP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $FP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $FP01_att4 = $FP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $FP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $FP03 = $FP01->appendChild($dom->createElement('idLote', $this->idLote));

        // BUG no PHP < 5.3: http://bugs.php.net/bug.php?id=46185
        // cria uma tag xmlns:default indesejada no elemento <NFe>
        foreach ($this->aNFe as $NFe) {
            $ddd = new DOMDocument('1.0', 'utf-8');
            $ddd->formatOutput = false;
            $ddd->loadXML($NFe);
            $FP01->appendChild($dom->importNode($ddd->getElementsByTagName('NFe')->item(0), true));
        }

        return $this->XML = $dom->saveXML();
        */


        // workaround
        $sNFe = implode('', $this->aNFe);
        $sNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $sNFe);

        $xml = '<enviNFe versao="'.$this->versao.'" xmlns="http://www.portalfiscal.inf.br/nfe" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xml.= '<idLote>'.$this->idLote.'</idLote>';
        $xml.= str_replace("\n", "", $sNFe);
        $xml.= '</enviNFe>';

        return $this->XML = $xml;
    }

    function sendSOAP() {

        if (!is_array($this->aNFe) || !count($this->aNFe)) {
            return false;
        }

        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_RECEPCAO_URL, 'nfeRecepcaoLote', $this->geraXML(), $this->versao);

        if (!empty($result['nfeRecepcaoLoteResult'])) {
            $this->retEnvNFe = new retEnvNFe();
            $this->retEnvNFe->trataRetorno($result['nfeRecepcaoLoteResult']);
            $this->retEnvNFe->idLote = $this->idLote;
            return $this->retEnvNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_ENVNFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->idLote).'-env-lot.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}


class retEnvNFe {

    public $versao;     // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $infRec;     // 
    public $nRec;       // 
    public $dhRecbto;   // 
    public $tMed;       // 
    public $XML;        // string XML
    public $idLote;     // id do lote para gravar recibo com nome adequado

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retEnviNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->infRec       = $dom->getElementsByTagName('infRec')->item(0)->nodeValue;
        $this->nRec         = $dom->getElementsByTagName('nRec')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->tMed         = $dom->getElementsByTagName('tMed')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETENVNFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->idLote).'-rec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

// ENVIA NFE (FINAL) ***********************************************************




// ARQUIVO RETORNO NFE (INICIO) ************************************************

// pag.70 - o manual é incoerente quanto ao salvamento do XML de retorno.
// pedido de resultado de processamento de lote - $nRec-ped-rec.xml
// resultado de processamento de lote           - $nRec-pro-rec.xml
// se uso == denegado                           - $chNFe-den.xml   ?????? mas é um lote! pode ter autorizada, rejeitada e denegada juntas
// o manual não cita a gravação do XML do protNFe individual, portanto adotei o seguinte para salvar o protocolo
// rejeitada:  $chNFe-rej.xml
// denegada:   $chNFe-den.xml
// autorizada: $chNFe-aut.xml
// entretanto, podemos analisar a hipótese de não gravar rejeitadas.

class consReciNFe {

    public $versao;     // versao do layout
    public $tpAmb;      // 
    public $nRec;       // 
    public $XML;        // string XML

    public $retConsReciNFe;

    function __construct() {
        $this->versao   = '1.10';
        $this->tpAmb    = _NFE_TPAMB;

        $this->retConsReciNFe = null;
    }

    function geraXML() {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $BP01 = $dom->appendChild($dom->createElement('consReciNFe'));

        $BP01_att1 = $BP01->appendChild($dom->createAttribute('versao'));
                     $BP01_att1->appendChild($dom->createTextNode($this->versao));

        $BP01_att2 = $BP01->appendChild($dom->createAttribute('xmlns'));
                     $BP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $BP01_att3 = $BP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $BP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $BP01_att4 = $BP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $BP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $BP03 = $BP01->appendChild($dom->createElement('tpAmb', $this->tpAmb));
        $BP04 = $BP01->appendChild($dom->createElement('nRec',  $this->nRec));

        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {

        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_RETRECEPCAO_URL, 'nfeRetRecepcao', $this->geraXML(), $this->versao);

        if (!empty($result['nfeRetRecepcaoResult'])) {
            $this->retConsReciNFe = new retConsReciNFe();
            $this->retConsReciNFe->trataRetorno($result['nfeRetRecepcaoResult']);
            return $this->retConsReciNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CONSRECINFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->nRec).'-ped-rec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

class protNFe {

    public $versao;
    public $Id;
    public $tpAmb;
    public $verAplic;
    public $chNFe;
    public $dhRecbto;
    public $nProt;
    public $digVal;
    public $cStat;
    public $xMotivo;
    public $XML;

    function __construct() {
    }

    function gravaXML($path=_NFE_PROTNFE_PATH) {
        if ($this->cStat == 100) {
            $extensao = '-aut.xml';
        } else if ($this->cStat == 110) {
            $extensao = '-den.xml';
        } else {
            $extensao = '-rej.xml';
        }
        $filePath = $path.'/'.sprintf("%015s", $this->chNFe).$extensao;
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }
}

class retConsReciNFe {

    public $versao;     // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $nRec;       // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $protNFe;    // array de protocolos de NFe's processadas
    public $XML;        // string XML

    function __construct() {
        $this->protNFe = array();
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsReciNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->nRec         = $dom->getElementsByTagName('nRec')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

        foreach ($dom->getElementsByTagName('protNFe') as $key => $protNFe) {

            $domProt = new DOMDocument('1.0', 'utf-8');
            $domProt->formatOutput = false;
            $domProt->appendChild($domProt->importNode($protNFe, true));

            $this->protNFe[$key] = new protNFe();
            $this->protNFe[$key]->versao = $protNFe->getAttribute('versao');
            $infProt = $domProt->getElementsByTagName('infProt')->item(0);
            $this->protNFe[$key]->Id        = $infProt->getElementsByTagName('Id')->item(0)->nodeValue;
            $this->protNFe[$key]->tpAmb     = $infProt->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $this->protNFe[$key]->verAplic  = $infProt->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->protNFe[$key]->chNFe     = $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $this->protNFe[$key]->dhRecbto  = $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $this->protNFe[$key]->nProt     = $infProt->getElementsByTagName('nProt')->item(0)->nodeValue;
            $this->protNFe[$key]->digVal    = $infProt->getElementsByTagName('digVal')->item(0)->nodeValue;
            $this->protNFe[$key]->cStat     = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->protNFe[$key]->xMotivo   = $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $this->protNFe[$key]->XML       = $domProt->saveXML();
        }

    }

    function gravaXML($path=_NFE_RETCONSRECINFE_PATH) {
        $filePath = $path.'/'.sprintf("%015s", $this->nRec).'-pro-rec.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

// RETORNO NFE (FINAL) *********************************************************






// ARQUIVO CANCELAMENTO NFE (INICIO) *******************************************

class cancNFe {

    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $xServ;      // 
    public $chNFe;      // 
    public $nProt;      // 
    public $xJust;      // 
    public $XML;        // string XML

    public $retCancNFe; // objeto de retorno

    function __construct() {
        $this->versao   = '1.07';
        $this->tpAmb    = _NFE_TPAMB;
        $this->xServ    = 'CANCELAR';

        $this->retCancNFe = null;
    }

    function geraXML() {

        $this->Id = 'ID'.$this->chNFe;

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $CP01 = $dom->appendChild($dom->createElement('cancNFe'));

        $CP01_att1 = $CP01->appendChild($dom->createAttribute('versao'));
                     $CP01_att1->appendChild($dom->createTextNode($this->versao));

        $CP01_att2 = $CP01->appendChild($dom->createAttribute('xmlns'));
                     $CP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $CP03 = $CP01->appendChild($dom->createElement('infCanc'));
        $CP04 = $CP03->setAttribute('Id', $this->Id);
        $CP05 = $CP03->appendChild($dom->createElement('tpAmb', $this->tpAmb));
        $CP06 = $CP03->appendChild($dom->createElement('xServ', $this->xServ));
        $CP07 = $CP03->appendChild($dom->createElement('chNFe', $this->chNFe));
        $CP08 = $CP03->appendChild($dom->createElement('nProt', $this->nProt));
        $CP09 = $CP03->appendChild($dom->createElement('xJust', $this->xJust));

        $xml = $dom->saveXML();

        $assinatura = new assinatura();
        $this->XML = $assinatura->assinaXML($xml, 'infCanc');

        return $this->XML;        
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_CANCELAMENTO_URL, 'nfeCancelamentoNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeCancelamentoNFResult'])) {
            $this->retCancNFe = new retCancNFe();
            $this->retCancNFe->trataRetorno($result['nfeCancelamentoNFResult']);
            return $this->retCancNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CANCNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-ped-can.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

class retCancNFe {

    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $chNFe;      // 
    public $dhRecbto;   // 
    public $nProt;      // 
    public $XML;        // string XML

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retCancNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->chNFe        = $dom->getElementsByTagName('chNFe')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->nProt        = $dom->getElementsByTagName('nProt')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETCANCNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-can.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

// CANCELAMENTO NFE (FINAL) ****************************************************








// ARQUIVO INUTILIZACAO NFE (INICIO) *******************************************

class inutNFe {

    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $xServ;      // 
    public $cUF;        // 
    public $ano;        // 
    public $CNPJ;       // 
    public $mod;        // 
    public $serie;      // 
    public $nNFIni;     // 
    public $nNFFin;     // 
    public $xJust;      // 
    public $XML;        // string XML

    public $retInutNFe; // objeto de retorno

    function __construct() {
        $this->versao   = '1.07';
        $this->tpAmb    = _NFE_TPAMB;
        $this->xServ    = 'INUTILIZAR';
        $this->cUF      = _NFE_CUF;

        $this->retInutNFe = null;
    }

    function geraXML() {

        $this->Id = 'ID'.$this->cUF.$this->CNPJ.$this->mod.$this->serie.$this->nNFIni.$this->nNFFin;

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $DP01 = $dom->appendChild($dom->createElement('inutNFe'));

        $DP01_att1 = $DP01->appendChild($dom->createAttribute('versao'));
                     $DP01_att1->appendChild($dom->createTextNode($this->versao));

        $DP01_att2 = $DP01->appendChild($dom->createAttribute('xmlns'));
                     $DP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $DP03 = $DP01->appendChild($dom->createElement('infInut'));
        $DP04 = $DP03->setAttribute('Id', $this->Id);
        $DP05 = $DP03->appendChild($dom->createElement('tpAmb',     $this->tpAmb));
        $DP06 = $DP03->appendChild($dom->createElement('xServ',     $this->xServ));
        $DP07 = $DP03->appendChild($dom->createElement('cUF',       $this->cUF));
        $DP08 = $DP03->appendChild($dom->createElement('ano',       $this->ano));
        $DP09 = $DP03->appendChild($dom->createElement('CNPJ',      $this->CNPJ));
        $DP10 = $DP03->appendChild($dom->createElement('mod',       $this->mod));
        $DP11 = $DP03->appendChild($dom->createElement('serie',     $this->serie));
        $DP12 = $DP03->appendChild($dom->createElement('nNFIni',    $this->nNFIni));
        $DP13 = $DP03->appendChild($dom->createElement('nNFFin',    $this->nNFFin));
        $DP14 = $DP03->appendChild($dom->createElement('xJust',     $this->xJust));

        $xml = $dom->saveXML();

        $assinatura = new assinatura();
        $this->XML = $assinatura->assinaXML($xml, 'infInut');

        return $this->XML;        
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_INUTILIZACAO_URL, 'nfeInutilizacaoNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeInutilizacaoNFResult'])) {
            $this->retInutNFe = new retInutNFe();
            $this->retInutNFe->trataRetorno($result['nfeInutilizacaoNFResult']);
            return $this->retInutNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_INUTNFE_PATH) {
        $nome = $this->ano.$this->CNPJ.$this->mod.sprintf("%03s", $this->serie).sprintf("%09s", $this->nNFIni).sprintf("%09s", $this->nNFFin);
        $filePath = $path.'/'.$nome.'-ped-inu.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

class retInutNFe {

    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $ano;        // 
    public $CNPJ;       // 
    public $mod;        // 
    public $serie;      // 
    public $nNFIni;     // 
    public $nNFFin;     // 
    public $dhRecbto;   // 
    public $nProt;      // 
    public $XML;        // string XML

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retInutNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->ano          = $dom->getElementsByTagName('ano')->item(0)->nodeValue;
        $this->CNPJ         = $dom->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $this->mod          = $dom->getElementsByTagName('mod')->item(0)->nodeValue;
        $this->serie        = $dom->getElementsByTagName('serie')->item(0)->nodeValue;
        $this->nNFIni       = $dom->getElementsByTagName('nNFIni')->item(0)->nodeValue;
        $this->nNFFin       = $dom->getElementsByTagName('nNFFin')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->nProt        = $dom->getElementsByTagName('nProt')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETINUTNFE_PATH) {
        $nome = $this->ano.$this->CNPJ.$this->mod.sprintf("%03s", $this->serie).sprintf("%09s", $this->nNFIni).sprintf("%09s", $this->nNFFin);
        $filePath = $path.'/'.$nome.'-inu.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

// INUTILIZACAO NFE (IINAL) ****************************************************






// ARQUIVO DISTRIBUI NFE (INICIO) **********************************************

class procNFe {

    public $versao;     // versao do layout
    public $NFe;        // 
    public $protNFe;    // 
    public $XML;        // string XML

    function __construct() {
        $this->versao   = '1.10';
    }

    function geraXML() {

        $NFe     = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $this->NFe);
        $protNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $this->protNFe);

        // NÃO USADO DOM DEVIDO AO BUG NO PHP
        $nfeProc = '<nfeProc versao="'.$this->versao.'" xmlns="http://www.portalfiscal.inf.br/nfe">';
        $nfeProc.= $NFe;
        $nfeProc.= $protNFe;
        $nfeProc.= '</nfeProc>';

        return $this->XML = str_replace("\n", "", $nfeProc);
    }

    function gravaXML($path=_NFE_PROCNFE_PATH) {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($this->NFe);
        $infNFe = $dom->getElementsByTagName('infNFe')->item(0);
        $chNFe = str_replace('NFe', '', $infNFe->getAttribute('Id'));

        $filePath = $path.'/'.$chNFe.'-proc-nfe.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

// DISTRIBUI NFE (FINAL) *******************************************************








// ARQUIVO CONSULTA SITUACAO NFE (INICIO) **************************************

class consSitNFe {

    public $versao;         // versao do layout
    public $tpAmb;          // 
    public $xServ;          // 
    public $chNFe;          // 
    public $XML;            // string XML

    public $retConsSitNFe;  // objeto de retorno

    function __construct() {
        $this->versao        = '1.07';
        $this->tpAmb         = _NFE_TPAMB;
        $this->xServ         = 'CONSULTAR';

        $this->retConsSitNFe = null;
    }

    function geraXML() {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $EP01 = $dom->appendChild($dom->createElement('consSitNFe'));

        $EP01_att1 = $EP01->appendChild($dom->createAttribute('versao'));
                     $EP01_att1->appendChild($dom->createTextNode($this->versao));

        $EP01_att2 = $EP01->appendChild($dom->createAttribute('xmlns'));
                     $EP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $EP03 = $EP01->appendChild($dom->createElement('tpAmb',     $this->tpAmb));
        $EP04 = $EP01->appendChild($dom->createElement('xServ',     $this->xServ));
        $EP05 = $EP01->appendChild($dom->createElement('chNFe',     $this->chNFe));

        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_CONSULTANF_URL, 'nfeConsultaNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeConsultaNFResult'])) {
            $this->retConsSitNFe = new retConsSitNFe();
            $this->retConsSitNFe->trataRetorno($result['nfeConsultaNFResult']);
            return $this->retConsSitNFe;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CONSSITNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-ped-sit.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

class retConsSitNFe {

    public $versao;     // versao do layout
    public $Id;         // 
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $chNFe;      // 
    public $dhRecbto;   // 
    public $nProt;      // 
    public $digVal;     // 
    public $XML;        // 

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsSitNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->chNFe        = $dom->getElementsByTagName('chNFe')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->nProt        = $dom->getElementsByTagName('nProt')->item(0)->nodeValue;
        $this->digVal       = $dom->getElementsByTagName('digVal')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETCONSSITNFE_PATH) {
        $filePath = $path.'/'.$this->chNFe.'-sit.xml';
        file_put_contents($filePath, $this->XML);
        return $filePath;
    }

}

// CONSULTA SITUACAO NFE (FINAL) ***********************************************






// ARQUIVO STATUS SERVICO (INICIO) *********************************************

class consStatServ {

    public $versao;     // versao do layout
    public $tpAmb;      // 
    public $cUF;        // 
    public $xServ;      // 
    public $XML;        // string XML

    public $retConsStatServ;    // objeto de retorno

    function __construct() {
        $this->versao   = '1.07';
        $this->tpAmb    = _NFE_TPAMB;
        $this->cUF      = _NFE_CUF;
        $this->xServ    = 'STATUS';

        $this->retConsStatServ = null;
    }

    function geraXML() {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $FP01 = $dom->appendChild($dom->createElement('consStatServ'));

        $FP01_att1 = $FP01->appendChild($dom->createAttribute('versao'));
                     $FP01_att1->appendChild($dom->createTextNode($this->versao));

        $FP01_att2 = $FP01->appendChild($dom->createAttribute('xmlns'));
                     $FP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $FP01_att3 = $FP01->appendChild($dom->createAttribute('xmlns:xsd'));
                     $FP01_att3->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema'));

        $FP01_att4 = $FP01->appendChild($dom->createAttribute('xmlns:xsi'));
                     $FP01_att4->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $FP03 = $FP01->appendChild($dom->createElement('tpAmb', $this->tpAmb));
        $FP04 = $FP01->appendChild($dom->createElement('cUF',   $this->cUF));
        $FP05 = $FP01->appendChild($dom->createElement('xServ', $this->xServ));
        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_STATUSSERVICO_URL, 'nfeStatusServicoNF', $this->geraXML(), $this->versao);

        if (!empty($result['nfeStatusServicoNFResult'])) {
            $this->retConsStatServ = new retConsStatServ();
            $this->retConsStatServ->trataRetorno($result['nfeStatusServicoNFResult']);
            return $this->retConsStatServ;
        } else {
            return false;
        }
    }

    function gravaXML($path=_NFE_CONSSTATNFE_PATH) {
        if (!empty($this->retConsStatServ->XML)) {
            $filePath = $path.'/'.str_replace(array('-',':'), '', $this->retConsStatServ->dhRecbto).'-ped-sta.xml';
            file_put_contents($filePath, $this->XML);
            return $filePath;
        } else {
            return false;
        }
    }

}

class retConsStatServ {

    public $versao;     // versao do layout
    public $tpAmb;      // 
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $cUF;        // 
    public $dhRecbto;   // 
    public $tMed;       // 
    public $dhRetorno;  // 
    public $xObs;       // 
    public $XML;        // string XML

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsStatServ')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->tpAmb        = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->cUF          = $dom->getElementsByTagName('cUF')->item(0)->nodeValue;
        $this->dhRecbto     = $dom->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $this->tMed         = $dom->getElementsByTagName('tMed')->item(0)->nodeValue;
        $this->dhRetorno    = $dom->getElementsByTagName('dhRetorno')->item(0)->nodeValue;
        $this->xObs         = $dom->getElementsByTagName('xObs')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

    function gravaXML($path=_NFE_RETCONSSTATNFE_PATH) {
        if (!empty($this->XML)) {
            $filePath = $path.'/'.str_replace(array('-',':'), '', $this->dhRecbto).'-sta.xml';
            file_put_contents($filePath, $this->XML);
            return $filePath;
        } else {
            return false;
        }
    }

}

// STATUS SERVICO (FINAL) ******************************************************





// ARQUIVO CONSULTA CADASTRO (INICIO) ******************************************

/* BUG SEFAZ SP
   OBTENDO SEGUINTE RESULTADO
    [faultcode] => soap:Server
    [faultstring] => Server was unable to process request. ---> Object reference not set to an instance of an object.
*/
class consCad {

    public $versao;         // versao do layout
    public $xServ;          // 
    public $UF;             // 
    public $IE;             // 
    public $CNPJ;           // 
    public $CPF;            // 
    public $XML;            // string XML

    public $retConsCad;  // objeto de retorno

    function __construct() {
        $this->versao        = '1.01';
        $this->xServ         = 'CONS-CAD';

        $this->retConsCad = null;
    }

    function geraXML() {

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;

        $GP01 = $dom->appendChild($dom->createElement('consCad'));

        $GP01_att1 = $GP01->appendChild($dom->createAttribute('versao'));
                     $GP01_att1->appendChild($dom->createTextNode($this->versao));

        $GP01_att2 = $GP01->appendChild($dom->createAttribute('xmlns'));
                     $GP01_att2->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $GP03 = $GP01->appendChild($dom->createElement('infCons'));
        $GP04 = $GP03->appendChild($dom->createElement('xServ', $this->xServ));
        $GP05 = $GP03->appendChild($dom->createElement('UF',    $this->UF));
        //$GP06 = $GP03->appendChild($dom->createElement('IE',    $this->IE));
        $GP07 = $GP03->appendChild($dom->createElement('CNPJ',  $this->CNPJ));
        //$GP08 = $GP03->appendChild($dom->createElement('CPF',   $this->CPF));

        return $this->XML = $dom->saveXML();
    }

    function sendSOAP() {
        $ws = new NFeSOAP();
        $result = $ws->send(_NFE_CONSULTACADASTRO_URL, 'consultaCadastro', $this->geraXML(), $this->versao);

        if (!empty($result['consultaCadastroResult'])) {
            $this->retConsCad = new retConsCad();
            $this->retConsCad->trataRetorno($result['consultaCadastroResult']);
            return $this->retConsCad;
        } else {
            return false;
        }
    }

}

class retConsCad {

    public $versao;     // versao do layout
    public $verAplic;   // 
    public $cStat;      // 
    public $xMotivo;    // 
    public $UF;         // 
    public $XML;        // 

    function __construct() {
    }

    function trataRetorno($retornoSEFAZ) {

        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->loadXML(utf8_encode($retornoSEFAZ));

        $raiz               = $dom->getElementsByTagName('retConsSitNFe')->item(0);
        $this->versao       = $raiz->getAttribute('versao');
        $this->Id           = $raiz->getAttribute('Id');
        $this->verAplic     = $dom->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $this->cStat        = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
        $this->xMotivo      = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $this->UF           = $dom->getElementsByTagName('UF')->item(0)->nodeValue;
        $this->XML          = $dom->saveXML();

    }

}

// CONSULTA CADASTRO (FINAL) ***************************************************







//** ADAPTACAO PESSOAL
class NFeTools {

    function __construct() {
    }


    function assinaNFe($sXML) {
        $assinatura = new assinatura();
        $xml_assinado = $assinatura->assinaXML($sXML, 'infNFe');
        return $xml_assinado;
    }

    // criar obj validacao
    function validaXML($sXML, $xsdFile) {

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $xml = $dom->loadXML($sXML);

        $erromsg = '';

        if (!$dom->schemaValidate($xsdFile)) {

            $aIntErrors = libxml_get_errors();

            $flagOK = FALSE;

            foreach ($aIntErrors as $intError){
                switch ($intError->level) {
                    case LIBXML_ERR_WARNING:
                        $erromsg .= " Atenção $intError->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $erromsg .= " Erro $intError->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $erromsg .= " Erro fatal $intError->code: ";
                        break;
                }
                $erromsg .= $intError->message . ';';
            }
        } else {
            $flagOK = TRUE;
            $this->errorStatus = FALSE;
            $this->errorMsg = '';
        }

        if (!$flagOK){
            $this->errorStatus = TRUE;
            $this->errorMsg = $erromsg;
        }
        return $flagOK;

    }

    function enviaNFe($aNFe, $idLote) {

        $envNFe = new envNFe();

        foreach ($aNFe as $NFe) {
            $envNFe->addNFe($NFe);
        }
        $envNFe->idLote = $idLote;

        $envNFe->sendSOAP();
        
        return $envNFe;
    }

    function retornoNFe($nRec) {

        $consReciNFe = new consReciNFe();

        $consReciNFe->nRec = $nRec;

        $consReciNFe->sendSOAP();

        return $consReciNFe;

    }

    function imprimeNFe($xml, $formato="P", $path_logomarca="", $protocolo="", $data_hora=""){
        include_once ('danfe.class.php');
        $danfe = new danfe($xml, $formato);
        $danfe->protocolo = $protocolo;
        $danfe->data_hora = $data_hora;
        if (!empty($path_logomarca)) {
            $danfe->logomarca = $path_logomarca;
        }
        return $danfe->gera();
    }

    function cancelaNFe($chNFe, $nProt, $xJust) {
        $cancNFe = new cancNFe();
        $cancNFe->nProt = $nProt;
        $cancNFe->xJust = $xJust;
        $cancNFe->chNFe = $chNFe;
        $cancNFe->sendSOAP();
        return $cancNFe;
    }

    function inutilizaNFe($ano, $CNPJ, $mod, $serie, $nNFIni, $nNFFin, $xJust) {
        $inutNFe = new inutNFe();
        $inutNFe->ano       = $ano;
        $inutNFe->CNPJ      = $CNPJ;
        $inutNFe->mod       = $mod;
        $inutNFe->serie     = $serie;
        $inutNFe->nNFIni    = $nNFIni;
        $inutNFe->nNFFin    = $nNFFin;
        $inutNFe->xJust     = $xJust;
        $inutNFe->sendSOAP();
        return $inutNFe;
    }

    function consultaNFe() {
    }

    function consultaCadastro() {
    }

    function distribuiNFe($NFe, $protNFe) {
        $procNFe = new procNFe();
        $procNFe->NFe       = $NFe;
        $procNFe->protNFe   = $protNFe;
        $procNFe->geraXML();
        return $procNFe;
    }

    function statusServico() {
        $pedStatus = new consStatServ();
        $pedStatus->sendSOAP();
        if ($pedStatus->retConsStatServ) {
            $PedFilePath = $pedStatus->gravaXML();
            $RetFilePath = $pedStatus->retConsStatServ->gravaXML();
        }
        return $pedStatus;
    }
}





/*
// EXEMPLO: ENVIO DE LOTE DE NFE
$pedEnvioLote = new envNFe();
$pedEnvioLote->addNFe(file_get_contents('nfe.xml'));    // requer string XML
$pedEnvioLote->idLote = 'X';
$retEnvioLote = $pedEnvioLote->sendSOAP();
$pedEnvioLote->gravaXML();
$retEnvioLote->gravaXML();
*/



/*
// EXEMPLO: CONSULTA RETORNO DE LOTE
$pedConsReci = new consReciNFe();
$pedConsReci->nRec = 'XXXXXXXXXXXXXXX';
$retConsReci = $pedConsReci->sendSOAP();
$pedConsReci->gravaXML();
$retConsReci->gravaXML();
*/



/*
// EXEMPLO: CANCELAMENTO DE NFE
$pedCancNFe = new cancNFe();
$pedCancNFe->nProt = 'XXXXXXXXXXXXXXX';
$pedCancNFe->xJust = 'Cancelando para testar nova lib.';
$pedCancNFe->chNFe = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
$retCancNFe = $pedCancNFe->sendSOAP();
$pedCancNFe->gravaXML();
$retCancNFe->gravaXML();
*/



/*
// EXEMPLO: INUTILIZACAO DE FAIXA DE NUMERACAO DE NFE
$pedInut = new inutNFe();
$pedInut->ano       = '09';
$pedInut->CNPJ      = 'XXXXXXXXXXXXXX';
$pedInut->mod       = '55';
$pedInut->serie     = '1';
$pedInut->nNFIni    = 'XXXX;
$pedInut->nNFFin    = 'XXXX;
$pedInut->xJust     = 'Inutilizacao para testar servico.';
$retInut = $pedInut->sendSOAP();
$pedInut->gravaXML();
$retInut->gravaXML();
*/



/*
// EXEMPLO: CONSULTA SITUACAO DA NFE
$pedConsSit = new consSitNFe();
$pedConsSit->chNFe = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
$retConsSit = $pedConsSit->sendSOAP();
$pedConsSit->gravaXML();
$retConsSit->gravaXML();
*/



/*
// EXEMPLO: CONSULTA STATUS DO SERVICO
$pedStatus = new consStatServ();        // novo objeto de consulta status consStatServ
$retStatus = $pedStatus->sendSOAP();    // faz a consulta do status. o retorno é um objeto retConsStatServ
$pedStatus->gravaXML();                 // grava consulta status
$retStatus->gravaXML();                 // grava retorno status
*/



/*
// EXEMPLO: CONSULTA CADASTRO
// NÃO FUNCIONAL, POIS SEFAZ SP NÃO RESPONDE CORRETAMENTE.
$pedConsCad = new consCad();
$pedConsCad->UF = 'SP';
$pedConsCad->IE = '182148522112';
$pedConsCad->CNPJ = '35402759000690';   // bimbo
$pedConsCad->CNPJ = '02577923000136';   // helptech
$retConsCad = $pedConsCad->sendSOAP();
print_r($pedConsCad);
*/


?>
