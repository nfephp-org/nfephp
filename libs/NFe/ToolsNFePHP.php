<?php

namespace NFe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\NFe\ToolsNFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Certificate\Pkcs12;
use Common\DateTime\DateTime;
use Common\LotNumber\LotNumber;
use Common\Soap\CurlSoap;
use Common\Strings\Strings;
use Common\Files;
use Common\Exception;
use Common\Dom\Dom;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class Tools
{
    /**
     * enableSVCRS
     * Habilita contingência ao serviço SVC-RS: Sefaz Virtual de Contingência Rio Grande do Sul
     * @var boolean
     */
    public $enableSVCRS = false;
    /**
     * enableSVCAN
     * Habilita contingência ao serviço SVC-AN: Sefaz Virtual de Contingência Ambiente Nacional
     * @var boolean
     */
    public $enableSVCAN = false;
    /**
     * motivoContingencia
     * Motivo por ter entrado em Contingencia
     * @var string 
     */
    public $motivoContingencia = '';
    /**
     * tsContingencia
     * Timestamp da hora de entrada em contingência
     * @var int
     */
    public $tsContingencia = '';
    /**
     * errror
     * @var string
     */
    public $error = '';
    /**
     * soapDebug
     * @var string 
     */
    public $soapDebug = '';
    /**
     * oCertificate
     * @var Object Class
     */
    protected $oCertificate;
    /**
     * oSoap
     * @var Object Class  
     */
    protected $oSoap;
    /**
     * aConfig
     * @var array
     */
    protected $aConfig = array();
    /**
     * aDocFormat
     * @var array 
     */
    protected $aDocFormat = array();
    /**
     * aProxyConf
     * @var array
     */
    protected $aProxyConf = array();
    /**
     * aMailConf
     * @var array
     */
    protected $aMailConf = array();
    /**
     * urlPortal
     * Instância do WebService
     * @var string
     */
    private $urlPortal = 'http://www.portalfiscal.inf.br/nfe';
    /**
     * aLastRetEvent
     * @var array 
     */
    private $aLastRetEvent = array();

    /**
     * __construct
     * @param string $configJson
     * @throws Exception\RuntimeException
     */
    public function __construct($configJson = '')
    {
        if (is_file($configJson)) {
            $configJson = Files\FilesFolders::readFile($configJson);
        }
        //carrega os dados de configuração
        $this->aConfig    = (array) json_decode($configJson);
        $this->aDocFormat = (array) $this->aConfig['aDocFormat'];
        $this->aProxyConf = (array) $this->aConfig['aProxyConf'];
        $this->aMailConf  = (array) $this->aConfig['aMailConf'];
        //seta o timezone
        DateTime::tzdBR($this->aConfig['siglaUF']);
        //carrega os certificados
        $this->oCertificate = new Pkcs12(
            $this->aConfig['pathCertsFiles'],
            $this->aConfig['cnpj']
        );
        if ($this->oCertificate->expireTimestamp == 0) {
            $msg = 'Não existe certificado válido disponível. Atualize o Certificado.';
            throw new Exception\RuntimeException($msg);
        }
        $this->zLoadSoapClass();
    }
    
    /**
     * atualizaCertificado
     * @param string $certpfx certificado pfx em string ou o path para o certificado
     * @param string $senha senha para abrir o certificado
     * @return boolean
     */
    public function atualizaCertificado($certpfx = '', $senha = '')
    {
        if ($certpfx == '' && $senha != '') {
            return false;
        }
        if (is_file($certpfx)) {
            $this->oCertificate->loadPfxFile($certpfx, $senha);
            return true;
        }
        $this->oCertificate->loadPfx($certpfx, $senha);
        $this->zLoadSoapClass();
        return true;
    }

    /**
     * ativaContingencia
     * Ativa a contingencia SVCAN ou SVCRS conforme a
     * sigla do estado
     * @param string $siglaUF
     * @param string $motivo
     * @return bool
     */
    public function ativaContingencia($siglaUF = '', $motivo = '')
    {
        if ($siglaUF == '' || $motivo == '') {
            return false;
        }
        $this->motivoContingencia = $motivo;
        $this->tsContingencia = time();
        $ctgList = array(
            'AC'=>'SVCAN',
            'AL'=>'SVCAN',
            'AM'=>'SVCAN',
            'AP'=>'SVCRS',
            'BA'=>'SVCRS',
            'CE'=>'SVCRS',
            'DF'=>'SVCAN',
            'ES'=>'SVCRS',
            'GO'=>'SVCRS',
            'MA'=>'SVSRS',
            'MG'=>'SVCAN',
            'MS'=>'SVCRS',
            'MT'=>'SVCRS',
            'PA'=>'SVCRS',
            'PB'=>'SVCAN',
            'PE'=>'SVCRS',
            'PI'=>'SVCRS',
            'PR'=>'SVCRS',
            'RJ'=>'SVCAN',
            'RN'=>'SVCRS',
            'RO'=>'SVCAN',
            'RR'=>'SVCAN',
            'RS'=>'SVCAN',
            'SC'=>'SVCAN',
            'SE'=>'SVCAN',
            'SP'=>'SVCAN',
            'TO'=>'SVCAN'
        );
        $ctg = $ctgList[$siglaUF];
        if ($ctg == 'SVCAN') {
            $this->enableSVCAN = true;
            $this->enableSVCRS = false;
        } elseif ($ctg == 'SVCRS') {
            $this->enableSVCAN = false;
            $this->enableSVCRS = true;
        }
        return true;
    }
    
    /**
     * desativaContingencia
     * Desliga opção de contingência 
     * @return boolean
     */
    public function desativaContingencia()
    {
        $this->enableSVCAN = false;
        $this->enableSVCRS = false;
        $this->tsContingencia = 0;
        $this->motivoContingencia = '';
        return true;
    }
    
    /**
     * printNFe
     * @param string $pathXml
     * @param string $pathDestino
     * @param string $printer
     * @return string
     */
    public function printNFe($pathXml = '', $pathDestino = '', $printer = '')
    {
        //TODO : falta implementar esse método
        return "$pathXml $pathDestino $printer";
    }
    
    /**
     * mailNFe
     * @param string $pathXml
     * @param array $aMails
     * @return boolean
     */
    public function mailNFe($pathXml = '', $aMails = array())
    {
        //TODO : falta implementar esse método
        $flag = false;
        if ($pathXml == '' && is_array($aMails)) {
            $flag = true;
        }
        return $flag;
    }
    
    /**
     * addB2B
     * @param string $pathNFefile
     * @param string $pathB2Bfile
     * @param string $tagB2B
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function addB2B($pathNFefile = '', $pathB2Bfile = '', $tagB2B = '')
    {
        if (! is_file($pathNFefile) || ! is_file($pathB2Bfile)) {
            $msg = "Algum dos arquivos não foi localizado no caminho indicado ! $pathNFefile ou $pathB2Bfile";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tagB2B == '') {
            //padrão anfavea
            $tagB2B = 'NFeB2BFin';
        }
        $docnfe = new Dom();
        $docnfe->loadXMLFile($pathNFefile);
        $nodenfe = $docnfe->getNode('nfeProc', 0);
        if ($nodenfe == '') {
            $msg = "O arquivo indicado como NFe não está protocolado ou não é uma NFe!!";
            throw new Exception\RuntimeException($msg);
        }
        //carrega o arquivo B2B
        $docb2b = new Dom();
        $docb2b->loadXMLFile($pathNFefile);
        $nodeb2b = $docnfe->getNode($tagB2B, 0);
        if ($nodeb2b == '') {
            $msg = "O arquivo indicado como B2B não contên a tag requerida!!";
            throw new Exception\RuntimeException($msg);
        }
        //cria a NFe processada com a tag do protocolo
        $procb2b = new Dom();
        //cria a tag nfeProc
        $nfeProcB2B = $procb2b->createElement('nfeProcB2B');
        $procb2b->appendChild($nfeProcB2B);
        //inclui a tag NFe
        $node = $procb2b->importNode($nodenfe, true);
        $nfeProcB2B->appendChild($node);
        //inclui a tag NFeB2BFin
        $node = $procb2b->importNode($nodeb2b, true);
        $nfeProcB2B->appendChild($node);
        //salva o xml como string em uma variável
        $nfeb2bXML = $procb2b->saveXML();
        //remove as informações indesejadas
        $nfeb2bXML = str_replace(array("\n","\r","\s"), '', $nfeb2bXML);
        return (string) $nfeb2bXML;
    }
    
    /**
     * addProtocolo
     * @param string $pathNFefile
     * @param string $pathProtfile
     * @param boolean $saveFile
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function addProtocolo($pathNFefile = '', $pathProtfile = '', $saveFile = false)
    {
        //carrega a NFe
        $docnfe = new Dom();
        $docnfe->loadXMLFile($pathNFefile);
        $nodenfe = $docnfe->getNode('NFe', 0);
        if ($nodenfe == '') {
            $msg = "O arquivo indicado como NFe não é um xml de NFe!";
            throw new Exception\RuntimeException($msg);
        }
        if ($docnfe->getNode('Signature') == '') {
            $msg = "A NFe não está assinada!";
            throw new Exception\RuntimeException($msg);
        }
        //carrega o protocolo
        $docprot = new Dom();
        $docprot->loadXMLFile($pathProtfile);
        $nodeprot = $docprot->getNode('protNFe', 0);
        if ($nodeprot == '') {
            $msg = "O arquivo indicado não contem um protocolo de autorização!";
            throw new Exception\RuntimeException($msg);
        }
        //carrega dados da NFe
        $tpAmb = $docnfe->getNodeValue('tpAmb');
        $anomes = date(
            'Ym',
            DateTime::convertSefazTimeToTimestamp($docnfe->getNodeValue('dhEmi'))
        );
        $infNFe = $docnfe->getNode("infNFe", 0);
        $versao = $infNFe->getAttribute("versao");
        $chaveId = $infNFe->getAttribute("Id");
        $chaveNFe = preg_replace('/[^0-9]/', '', $chaveId);
        $digValueNFe = $docnfe->getNodeValue('DigestValue');
        //carrega os dados do protocolo
        $protver     = $nodeprot->getAttribute("versao");
        $chaveProt   = $nodeprot->getElementsByTagName("chNFe")->item(0)->nodeValue;
        $digValueProt = $nodeprot->getElementsByTagName("digVal")->item(0)->nodeValue;
        if ($digValueNFe != $digValueProt) {
            $msg = "Inconsistência! O DigestValue da NFe não combina com o"
                . " do digVal do protocolo indicado!";
            throw new Exception\RuntimeException($msg);
        }
        if ($chaveNFe != $chaveProt) {
            $msg = "O protocolo indicado pertence a outra NFe. Os números das chaves não combinam !";
            throw new Exception\RuntimeException($msg);
        }
        $infProt = $nodeprot->getElementsByTagName("infProt")->item(0);
        //cria a NFe processada com a tag do protocolo
        $procnfe = new \DOMDocument('1.0', 'utf-8');
        $procnfe->formatOutput = false;
        $procnfe->preserveWhiteSpace = false;
        //cria a tag nfeProc
        $nfeProc = $procnfe->createElement('nfeProc');
        $procnfe->appendChild($nfeProc);
        //estabele o atributo de versão
        $nfeProcAtt1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
        $nfeProcAtt1->appendChild($procnfe->createTextNode($protver));
        //estabelece o atributo xmlns
        $nfeProcAtt2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
        $nfeProcAtt2->appendChild($procnfe->createTextNode($this->urlPortal));
        //inclui a tag NFe
        $node = $procnfe->importNode($nodenfe, true);
        $nfeProc->appendChild($node);
        //cria tag protNFe
        $protNFe = $procnfe->createElement('protNFe');
        $nfeProc->appendChild($protNFe);
        //estabele o atributo de versão
        $protNFeAtt1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
        $protNFeAtt1->appendChild($procnfe->createTextNode($versao));
        //cria tag infProt
        $nodep = $procnfe->importNode($infProt, true);
        $protNFe->appendChild($nodep);
        //salva o xml como string em uma variável
        $procXML = $procnfe->saveXML();
        //remove as informações indesejadas
        $procXML = str_replace(
            array('default:',':default',"\n","\r","\s"),
            '',
            $procXML
        );
        $procXML = str_replace(
            'NFe xmlns="http://www.portalfiscal.inf.br/nfe" xmlns="http://www.w3.org/2000/09/xmldsig#"',
            'NFe xmlns="http://www.portalfiscal.inf.br/nfe"',
            $procXML
        );
        if ($saveFile) {
            $filename = "$chaveNFe-protNFe.xml";
            $this->zGravaFile($tpAmb, $filename, $procXML, 'enviadas'.DIRECTORY_SEPARATOR.'aprovadas', $anomes);
        }
        return $procXML;
    }

    /**
     * addCancelamento
     * @param string $pathNFefile
     * @param string $pathCancfile
     * @param bool $saveFile
     * @return string
     */
    public function addCancelamento($pathNFefile = '', $pathCancfile = '', $saveFile = false)
    {
        $procXML = '';
        //carrega a NFe
        $docnfe = new Dom();
        $docnfe->loadXMLFile($pathNFefile);
        $nodenfe = $docnfe->getNode('NFe', 0);
        if ($nodenfe == '') {
            $msg = "O arquivo indicado como NFe não é um xml de NFe!";
            throw new Exception\RuntimeException($msg);
        }
        $proNFe = $docnfe->getNode('protNFe');
        if ($proNFe == '') {
            $msg = "A NFe não está protocolada ainda!!";
            throw new Exception\RuntimeException($msg);
        }
        $chaveNFe = $proNFe->getElementsByTagName('chNFe')->item(0)->nodeValue;
        $nProtNFe = $proNFe->getElementsByTagName('nProt')->item(0)->nodeValue;
        $tpAmb = $docnfe->getNodeValue('tpAmb');
        $anomes = date(
            'Ym',
            DateTime::convertSefazTimeToTimestamp($docnfe->getNodeValue('dhEmi'))
        );
        //carrega o cancelamento
        //pode ser um evento ou resultado de uma consulta com multiplos eventos
        $doccanc = new Dom();
        $doccanc->loadXMLFile($pathCancfile);
        $eventos = $doccanc->getElementsByTagName('infEvento');
        foreach ($eventos as $evento) {
            //evento
            $cStat = $evento->getElementsByTagName('cStat')->item(0)->nodeValue;
            $tpAmb = $evento->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $chaveEvento = $evento->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $tpEvento = $evento->getElementsByTagName('tpEvento')->item(0)->nodeValue;
            $nProtEvento = $evento->getElementsByTagName('nProt')->item(0)->nodeValue;
            //verifica se conferem os dados
            //cStat = 135 ==> evento homologado
            //tpEvento = 110111 ==> Cancelamento
            //chave do evento == chave da NFe
            //protocolo do evneto ==  protocolo da NFe
            if ($cStat == '135' &&
                $tpEvento == '110111' &&
                $chaveEvento == $chaveNFe &&
                $nProtEvento == $nProtNFe
            ) {
                $proNFe->getElementsByTagName('cStat')->item(0)->nodeValue = '101';
                $proNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue = 'Cancelamento de NF-e homologado';
                $procXML = $docnfe->saveXML();
                //remove as informações indesejadas
                $procXML = str_replace(
                    array('default:',':default',"\n","\r","\s"),
                    '',
                    $procXML
                );
                $procXML = str_replace(
                    'NFe xmlns="http://www.portalfiscal.inf.br/nfe" xmlns="http://www.w3.org/2000/09/xmldsig#"',
                    'NFe xmlns="http://www.portalfiscal.inf.br/nfe"',
                    $procXML
                );
                if ($saveFile) {
                    $filename = "$chaveNFe-protNFe.xml";
                    $this->zGravaFile($tpAmb, $filename, $procXML, 'enviadas'.DIRECTORY_SEPARATOR.'aprovadas', $anomes);
                }
                break;
            }
        }
        return (string) $procXML;
    }
    
    /**
     * verificaValidade
     * @param string $pathXmlFile
     * @param array $aRetorno
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function verificaValidade($pathXmlFile = '', &$aRetorno = array())
    {
        $aRetorno = array();
        if (!file_exists($pathXmlFile)) {
            $msg = "Arquivo não localizado!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        //carrega a NFe
        $xml = Files\FilesFolders::readFile($pathXmlFile);
        $this->oCertificate->verifySignature($xml, 'infNFe');
        //obtem o chave da NFe
        $docnfe = new Dom();
        $docnfe->loadXMLFile($pathXmlFile);
        $tpAmb = $docnfe->getNodeValue('tpAmb');
        $chNFe  = $docnfe->getChave('infNFe');
        $this->sefazConsultaChave($chNFe, $tpAmb, $aRetorno);
        if ($aRetorno['cStat'] != '100') {
            return false;
        }
        return true;
    }
    
    /**
     * assina
     * @param string $xml
     * @param boolean $saveFile
     * @return string
     * @throws Exception\RuntimeException
     */
    public function assina($xml = '', $saveFile = false)
    {
        if (is_file($xml)) {
            $xml = Files\FilesFolders::readFile($xml);
        }
        $sxml = $this->oCertificate->signXML($xml, 'infNFe');
        $dom = new Dom();
        $dom->loadXMLString($sxml);
        $versao = $dom->getElementsByTagName('infNFe')->item(0)->getAttribute('versao');
        if (! $this->zValidMessage($sxml, 'nfe', $versao)) {
            $msg = 'Falha na validação do NFe. '.$this->error;
            throw new Exception\RuntimeException($msg);
        }
        if ($saveFile) {
            $docnfe = new Dom();
            $docnfe->loadXMLString($sxml);
            $tpAmb = $docnfe->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $anomes = date(
                'Ym',
                DateTime::convertSefazTimeToTimestamp($docnfe->getElementsByTagName('dhEmi')->item(0)->nodeValue)
            );
            $chaveNFe = $docnfe->getChave('infNFe');
            $filename = "$chaveNFe-nfe.xml";
            $this->zGravaFile($tpAmb, $filename, $sxml, 'assinadas', $anomes);
        }
        return $sxml;
    }
    
    /**
     * sefazAutoriza
     * @param array $aXml
     * @param string $tpAmb
     * @param string $idLote
     * @param array $aRetorno
     * @param int $indSinc
     * @param boolean $compactarZip
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function sefazAutoriza(
        $aXml,
        $tpAmb = '2',
        $idLote = '',
        &$aRetorno = array(),
        $indSinc = 0,
        $compactarZip = false
    ) {
        $sxml = $aXml;
        if (empty($aXml)) {
            $msg = "Pelo menos uma NFe deve ser informada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if (is_array($aXml)) {
            foreach ($aXml as $xml) {
                $sxml .= $xml;
            }
            if (count($aXml) > 1) {
                //multiplas nfes, não pode ser sincrono
                $indSinc = 0;
            }
        }
        $sxml = preg_replace("/<\?xml.*\?>/", "", $sxml);
        $siglaUF = $this->aConfig['siglaUF'];
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($idLote == '') {
            $idLote = LotNumber::geraNumLote(15);
        }
        $cUF = '';
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'NfeAutorizacao',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A consulta de NFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        //montagem dos dados da mensagem SOAP
        $cons = "<enviNFe xmlns=\"$this->urlPortal\" versao=\"$version\">"
                . "<idLote>$idLote</idLote>"
                . "<indSinc>$indSinc</indSinc>"
                . "$sxml"
                . "</enviNFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'enviNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
        if ($compactarZip) {
            $gzdata = base64_encode(gzencode($cons, 9, FORCE_GZIP));
            $body = "<nfeDadosMsgZip xmlns=\"$namespace\">$gzdata</nfeDadosMsgZip>";
            $method = $method."Zip";
        }
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$idLote-enviNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$idLote-retEnviNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazConsultaRecibo
     * @param string $recibo
     * @param string $tpAmb
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function sefazConsultaRecibo($recibo = '', $tpAmb = '2', &$aRetorno = array())
    {
        if ($recibo == '') {
            $msg = "Deve ser informado um recibo.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $siglaUF = $this->aConfig['siglaUF'];
        $cUF = '';
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'NfeRetAutorizacao',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A consulta de NFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consReciNFe xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<nRec>$recibo</nRec>"
            . "</consReciNFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'consReciNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$recibo-consReciNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$recibo-retConsReciNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazConsultaChave
     * Consulta o status da NFe pela chave de 44 digitos
     * @param string $chNFe
     * @param string $tpAmb
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function sefazConsultaChave($chNFe = '', $tpAmb = '2', &$aRetorno = array())
    {
        $chNFe = preg_replace('/[^0-9]/', '', $chNFe);
        if (strlen($chNFe) != 44) {
            $msg = "Uma chave de 44 dígitos da NFe deve ser passada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $cUF = substr($chNFe, 0, 2);
        $siglaUF = self::zGetSigla($cUF);
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'NfeConsultaProtocolo',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A consulta de NFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consSitNFe xmlns=\"$this->urlPortal\" versao=\"$version\">"
                . "<tpAmb>$tpAmb</tpAmb>"
                . "<xServ>CONSULTAR</xServ>"
                . "<chNFe>$chNFe</chNFe>"
                . "</consSitNFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'consSitNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$chNFe-consSitNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$chNFe-retConsSitNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazInutiliza
     * Solicita a inutilização de uma ou uma sequencia de NFe
     * de uma determinada série
     * @param string $modelo
     * @param integer $nSerie
     * @param integer $nIni
     * @param integer $nFin
     * @param string $xJust
     * @param string $tpAmb
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function sefazInutiliza(
        $modelo = '55',
        $nSerie = 1,
        $nIni = 0,
        $nFin = 0,
        $xJust = '',
        $tpAmb = '2',
        &$aRetorno = array()
    ) {
        $xJust = Strings::cleanString($xJust);
        $nSerie = (integer) $nSerie;
        $nIni = (integer) $nIni;
        $nFin = (integer) $nFin;
        //valida dos dados de entrada
        if (strlen($xJust) < 15 || strlen($xJust) > 255) {
            $msg = "A justificativa deve ter entre 15 e 255 digitos!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nSerie < 0 || $nSerie > 999) {
            $msg = "O campo serie está errado: $nSerie!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nIni < 1 || $nIni > 1000000000) {
            $msg = "O campo numero inicial está errado: $nIni!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nFin < 1 || $nFin > 1000000000) {
            $msg = "O campo numero final está errado: $nFin!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($this->enableSVCRS || $this->enableSVCAN) {
            $msg = "A inutilização não pode ser feita em contingência!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($modelo != '65') {
            $modelo = '55';
        }
        //monta serviço
        $cUF = '';
        $siglaUF = $this->aConfig['siglaUF'];
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'NfeInutilizacao',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A inutilização não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        //montagem dos dados da mensagem SOAP
        $cnpj = $this->aConfig['cnpj'];
        $sAno = (string) date('y');
        $sSerie = str_pad($nSerie, 3, '0', STR_PAD_LEFT);
        $sInicio = str_pad($nIni, 9, '0', STR_PAD_LEFT);
        $sFinal = str_pad($nFin, 9, '0', STR_PAD_LEFT);
        $idInut = "ID".$cUF.$sAno.$cnpj.$modelo.$sSerie.$sInicio.$sFinal;
        //limpa os caracteres indesejados da justificativa
        $xJust = Strings::cleanString($xJust);
        //montagem do corpo da mensagem
        $cons = "<inutNFe xmlns=\"$this->urlPortal\" versao=\"$version\">"
                . "<infInut Id=\"$idInut\">"
                . "<tpAmb>$tpAmb</tpAmb>"
                . "<xServ>INUTILIZAR</xServ>"
                . "<cUF>$cUF</cUF>"
                . "<ano>$sAno</ano>"
                . "<CNPJ>$cnpj</CNPJ>"
                . "<mod>$modelo</mod>"
                . "<serie>$nSerie</serie>"
                . "<nNFIni>$nIni</nNFIni>"
                . "<nNFFin>$nFin</nNFFin>"
                . "<xJust>$xJust</xJust>"
                . "</infInut></inutNFe>";
        //assina a lsolicitação de inutilização
        $signedMsg = $this->oCertificate->signXML($cons, 'infInut');
        $signedMsg = Strings::clearXml($signedMsg, true);
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'inutNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$signedMsg</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$sAno-$modelo-$sSerie-".$sInicio."_".$sFinal."-inutNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$sAno-$modelo-$sSerie-".$sInicio."_".$sFinal."-retInutNFe.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazCadastro
     * Busca os dados cadastrais de um emitente de NFe
     * NOTA: Nem todas as Sefaz disponibilizam esse serviço
     * @param string $siglaUF sigla da UF da empresa que queremos consultar
     * @param string $tpAmb
     * @param string $cnpj numero do CNPJ da empresa a ser consultada
     * @param string $iest numero da Insc. Est. da empresa a ser consultada
     * @param string $cpf CPF da pessoa física a ser consultada
     * @param array $aRetorno aRetorno retorno da resposta da SEFAZ em array
     * @return string XML de retorno do SEFAZ
     */
    public function sefazCadastro($siglaUF = '', $tpAmb = '2', $cnpj = '', $iest = '', $cpf = '', &$aRetorno = array())
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $iest = trim($iest);
        //se nenhum critério é satisfeito
        if ($cnpj == '' && $iest == '' && $cpf == '') {
            //erro nao foi passado parametro de filtragem
            $msg = "Na consulta de cadastro, pelo menos um desses dados deve ser"
                    . " fornecido CNPJ, CPF ou IE !!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($cnpj != '') {
            $filtro = "<CNPJ>$cnpj</CNPJ>";
            $txtFile = "CNPJ_$cnpj";
        } elseif ($iest != '') {
            $filtro = "<IE>$iest</IE>";
            $txtFile = "IE_$iest";
        } else {
            $filtro = "<CPF>$cpf</CPF>";
            $txtFile = "CPF_$cpf";
        }
        $cUF = '';
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'NfeConsultaCadastro',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A consulta de Cadastros não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<ConsCad xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<infCons>"
            . "<xServ>CONS-CAD</xServ>"
            . "<UF>$siglaUF</UF>"
            . "$filtro</infCons></ConsCad>";
        //valida a mensagem com o xsd
        //não tem validação estavel para esse xml
        //if (! $this->zValidMessage($cons, 'ConsCad', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$txtFile-consCad.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$txtFile-retConsCad.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }

    /**
     * sefazStatus
     * Verifica o status do serviço da SEFAZ/SVC
     * NOTA : Este serviço será removido no futuro, segundo da Receita/SEFAZ devido
     * ao excesso de mau uso !!!
     * @param  string $siglaUF sigla da unidade da Federação
     * @param string $tpAmb tipo de ambiente 1-produção e 2-homologação
     * @param  array $aRetorno parametro passado por referencia contendo a resposta da consulta em um array
     * @return mixed string XML do retorno do webservice, ou false se ocorreu algum erro
     */
    public function sefazStatus($siglaUF = '', $tpAmb = '2', &$aRetorno = array())
    {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($siglaUF == '') {
            $siglaUF = $this->aConfig['siglaUF'];
        }
        $cUF = '';
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'NfeStatusServico',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "O status não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consStatServ xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<tpAmb>$tpAmb</tpAmb><cUF>$cUF</cUF>"
            . "<xServ>STATUS</xServ></consStatServ>";
        //valida mensagem com xsd
        //if (! $this->zValidMessage($cons, 'consStatServ', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
        //consome o webservice e verifica o retorno do SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        $datahora = date('Ymd_His');
        $filename = $siglaUF."_"."$datahora-consStatServ.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = $siglaUF."_"."$datahora-retConsStatServ.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }

    /**
     * sefazDistDFe
     * Serviço destinado à distribuição de informações 
     * resumidas e documentos fiscais eletrônicos de interesse de um
     * ator, seja este pessoa física ou jurídica.
     * @param string $fonte sigla da fonte dos dados 'AN' 
     *                      e para alguns casos pode ser 'RS'
     * @param string $tpAmb tiupo de ambiente
     * @param integer $ultNSU ultimo numero NSU que foi consultado
     * @param integer $numNSU numero de NSU que se quer consultar
     * @param array $aRetorno array com os dados do retorno
     * @param boolean $descompactar se true irá descompactar os dados retornados,
     *        se não os dados serão retornados da forma que foram recebidos
     * @return string contento o xml retornado pela SEFAZ
     * @throws Exception\RuntimeException
     */
    public function sefazDistDFe(
        $fonte = 'AN',
        $tpAmb = '2',
        $cnpj = '',
        $ultNSU = 0,
        $numNSU = 0,
        &$aRetorno = array(),
        $descompactar = false
    ) {
        if ($fonte != 'AN' && $fonte != 'RS') {
            $msg = "Somente os autorizadores AN e RS dispoem desse serviço!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $siglaUF = $this->aConfig['siglaUF'];
        if ($cnpj == '') {
            $cnpj = $this->aConfig['cnpj'];
        }
        //carrega serviço
        $this->zLoadServico(
            'NFeDistribuicaoDFe',
            $fonte,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A distribuição de documento DFe não está disponível na SEFAZ $fonte!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cUF = self::zGetcUF($siglaUF);
        $ultNSU = str_pad($ultNSU, 15, '0', STR_PAD_LEFT);
        $tagNSU = "<distNSU><ultNSU>$ultNSU</ultNSU></distNSU>";
        if ($numNSU != 0) {
            $numNSU = str_pad($numNSU, 15, '0', STR_PAD_LEFT);
            $tagNSU = "<consNSU><NSU>$numNSU</NSU></consNSU>";
        }
        //monta a consulta
        $cons = "<distDFeInt xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<cUFAutor>$cUF</cUFAutor>"
            . "<CNPJ>$cnpj</CNPJ>$tagNSU</distDFeInt>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'distDFeInt', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDistDFeInteresse xmlns=\"$namespace\">"
            . "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>"
            . "</nfeDistDFeInteresse>";
        //envia dados via SOAP e verifica o retorno este webservice não requer cabeçalho
        $header = "";
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $tipoNSU = (int) ($numNSU != 0 ? $numNSU : $ultNSU);
        $datahora = date('Ymd_His');
        $filename = "$tipoNSU-$datahora-distDFeInt.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$tipoNSU-$datahora-retDistDFeInt.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = self::zReadReturnSefaz($method, $retorno, $descompactar);
        return (string) $retorno;
    }

    /**
     * sefazCCe
     * @param string $chNFe
     * @param string $tpAmb
     * @param string $xCorrecao
     * @param int $nSeqEvento
     * @param array $aRetorno
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function sefazCCe($chNFe = '', $tpAmb = '2', $xCorrecao = '', $nSeqEvento = 1, &$aRetorno = array())
    {
        //limpa chave
        $chNFe = preg_replace('/[^0-9]/', '', $chNFe);
        $xCorrecao = Strings::cleanString($xCorrecao);
        $nSeqEvento = (integer) $nSeqEvento;
        if (strlen($chNFe) != 44) {
            $msg = "A chave deve ter 44 dígitos!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if (strlen($xCorrecao) < 15 || strlen($xCorrecao) > 1000) {
            $msg = "A correção deve ter entre 15 e 1000 caracteres!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nSeqEvento < 1 || $nSeqEvento > 20) {
            $msg = "O número sequencial do evento deve ser entre 1 e 20!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $siglaUF = self::zGetSigla(substr($chNFe, 0, 2));
        $tpEvento = '110110';
        $xCondUso = "A Carta de Correcao e disciplinada pelo paragrafo "
                . "1o-A do art. 7o do Convenio S/N, de 15 de dezembro de 1970 "
                . "e pode ser utilizada para regularizacao de erro ocorrido "
                . "na emissao de documento fiscal, desde que o erro nao esteja "
                . "relacionado com: I - as variaveis que determinam o valor "
                . "do imposto tais como: base de calculo, aliquota, diferenca "
                . "de preco, quantidade, valor da operacao ou da prestacao; "
                . "II - a correcao de dados cadastrais que implique mudanca "
                . "do remetente ou do destinatario; "
                . "III - a data de emissao ou de saida.";
        $tagAdic = "<xCorrecao>$xCorrecao</xCorrecao><xCondUso>$xCondUso</xCondUso>";
        $retorno = $this->zSefazEvento($siglaUF, $chNFe, $tpAmb, $tpEvento, $nSeqEvento, $tagAdic);
        $aRetorno = $this->aLastRetEvent;
        return $retorno;
    }
    
    /**
     * sefazCancela
     * @param string $chNFe
     * @param string $tpAmb
     * @param string $xJust
     * @param string $nProt
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public function sefazCancela($chNFe = '', $tpAmb = '2', $xJust = '', $nProt = '', &$aRetorno = array())
    {
        $chNFe = preg_replace('/[^0-9]/', '', $chNFe);
        $nProt = preg_replace('/[^0-9]/', '', $nProt);
        $xJust = Strings::cleanString($xJust);
        //validação dos dados de entrada
        if (strlen($chNFe) != 44) {
            $msg = "Uma chave de NFe válida não foi passada como parâmetro $chNFe.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nProt == '') {
            $msg = "Não foi passado o numero do protocolo!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if (strlen($xJust) < 15 || strlen($xJust) > 255) {
            $msg = "A justificativa deve ter pelo menos 15 digitos e no máximo 255!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        $siglaUF = self::zGetSigla(substr($chNFe, 0, 2));
        //estabelece o codigo do tipo de evento CANCELAMENTO
        $tpEvento = '110111';
        $nSeqEvento = 1;
        //monta mensagem
        $tagAdic = "<nProt>$nProt</nProt><xJust>$xJust</xJust>";
        $retorno = $this->zSefazEvento($siglaUF, $chNFe, $tpAmb, $tpEvento, $nSeqEvento, $tagAdic);
        $aRetorno = $this->aLastRetEvent;
        return $retorno;
    }
    
    /**
     * sefazManifesta
     * @param string $chNFe
     * @param string $tpAmb
     * @param string $xJust
     * @param string $tpEvento
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public function sefazManifesta($chNFe = '', $tpAmb = '2', $xJust = '', $tpEvento = '', &$aRetorno = array())
    {
        $chNFe = preg_replace('/[^0-9]/', '', $chNFe);
        $tpEvento = preg_replace('/[^0-9]/', '', $tpEvento);
        switch ($tpEvento) {
            case '210200':
                //210200 – Confirmação da Operação
                break;
            case '210210':
                //210210 – Ciência da Operação
                break;
            case '210220':
                //210220 – Desconhecimento da Operação
                break;
            case '210240':
                //210240 – Operação não Realizada
                if (strlen($xJust) < 15 ||  strlen($xJust) > 255) {
                    $msg = "É obrigatória uma justificativa com 15 até 255 caracteres!!";
                    throw new Exception\InvalidArgumentException($msg);
                }
                $xJust = Strings::cleanString($xJust);
                $tagAdic = "<xJust>$xJust</xJust>";
                break;
            default:
                $msg = "Esse código de tipo de evento não consta!! $tpEvento";
                throw new Exception\InvalidArgumentException($msg);
        }
        $siglaUF = 'AN';
        $nSeqEvento = '1';
        $retorno = $this->zSefazEvento($siglaUF, $chNFe, $tpAmb, $tpEvento, $nSeqEvento, $tagAdic);
        $aRetorno = $this->aLastRetEvent;
        return $retorno;
    }
    
    /**
     * zSefazEvento
     * @param string $siglaUF
     * @param string $chNFe
     * @param string $tpAmb
     * @param string $tpEvento
     * @param string $nSeqEvento
     * @param string $tagAdic
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function zSefazEvento(
        $siglaUF = '',
        $chNFe = '',
        $tpAmb = '2',
        $tpEvento = '',
        $nSeqEvento = '1',
        $tagAdic = ''
    ) {
        //montagem dos dados da mensagem SOAP
        switch ($tpEvento) {
            case '110111':
                //cancelamento
                $aliasEvento = 'CancNFe';
                $descEvento = 'Cancelamento';
                break;
            case '110110':
                //CCe
                $aliasEvento = 'CCe';
                $descEvento = 'Carta de Correcao';
                break;
            case '210200':
                //Confirmacao da Operacao
                $aliasEvento = 'EvConfirma';
                $descEvento = 'Confirmacao da Operacao';
                break;
            case '210210':
                //Ciencia da Operacao
                $aliasEvento = 'EvCiencia';
                $descEvento = 'Ciencia da Operacao';
                break;
            case '210220':
                //Desconhecimento da Operacao
                $aliasEvento = 'EvDesconh';
                $descEvento = 'Desconhecimento da Operacao';
                break;
            case '210240':
                //Operacao não Realizada
                $aliasEvento = 'EvNaoRealizada';
                $descEvento = 'Operacao não Realizada';
                break;
            default:
                $msg = "O código do tipo de evento informado não corresponde a "
                   . "nenhum evento estabelecido.";
                throw new Exception\RuntimeException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $cUF = '';
        $urlservice = '';
        $namespace = '';
        $header = '';
        $method = '';
        $version = '';
        //carrega serviço
        $this->zLoadServico(
            'RecepcaoEvento',
            $siglaUF,
            $tpAmb,
            $cUF,
            $urlservice,
            $namespace,
            $header,
            $method,
            $version
        );
        if ($urlservice == '') {
            $msg = "A recepção de eventos não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cnpj = $this->aConfig['cnpj'];
        $dhEvento = (string) str_replace(' ', 'T', date('Y-m-d H:i:sP'));
        $sSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
        $eventId = "ID".$tpEvento.$chNFe.$sSeqEvento;
        $cOrgao = $cUF;
        if ($siglaUF == 'AN') {
            $cOrgao = '91';
        }
        $mensagem = "<evento xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<infEvento Id=\"$eventId\">"
            . "<cOrgao>$cOrgao</cOrgao>"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<CNPJ>$cnpj</CNPJ>"
            . "<chNFe>$chNFe</chNFe>"
            . "<dhEvento>$dhEvento</dhEvento>"
            . "<tpEvento>$tpEvento</tpEvento>"
            . "<nSeqEvento>$nSeqEvento</nSeqEvento>"
            . "<verEvento>$version</verEvento>"
            . "<detEvento versao=\"$version\">"
            . "<descEvento>$descEvento</descEvento>"
            . "$tagAdic"
            . "</detEvento>"
            . "</infEvento>"
            . "</evento>";
        //assinatura dos dados
        $signedMsg = $this->oCertificate->signXML($mensagem, 'infEvento');
        $signedMsg = Strings::clearXml($signedMsg, true);
        $numLote = LotNumber::geraNumLote();
        $cons = "<envEvento xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<idLote>$numLote</idLote>"
            . "$signedMsg"
            . "</envEvento>";
        //valida mensagem com xsd
        //no caso do evento nao tem xsd organizado, esta fragmentado
        //e por vezes incorreto por isso essa validação está desabilitada
        //if (! $this->zValidMessage($cons, 'envEvento', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        $body = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$chNFe-$aliasEvento-envEvento.xml";
        $this->zGravaFile($tpAmb, $filename, $lastMsg);
        $filename = "$chNFe-$aliasEvento-retEnvEvento.xml";
        $this->zGravaFile($tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $this->aLastRetEvent = self::zReadReturnSefaz($method, $retorno);
        return (string) $retorno;
    }

    /**
     * zLoadServico
     * Monta o namespace e o cabecalho da comunicação SOAP
     * @param string $service
     * @param string $siglaUF
     * @param string $tpAmb
     * @param int $cUF
     * @param string $urlservice
     * @param string $namespace
     * @param string $header
     * @param string $method
     * @param string $version
     * @return bool
     * @internal param string $servico Identificação do Servico
     * @internal param array $aURL Dados das Urls do SEFAZ
     */
    private function zLoadServico(
        $service,
        $siglaUF,
        $tpAmb,
        &$cUF,
        &$urlservice,
        &$namespace,
        &$header,
        &$method,
        &$version
    ) {
        if (! isset($service) || ! isset($siglaUF)) {
            return false;
        }
        $cUF = self::zGetcUF($siglaUF);
        $pathXmlUrlFile = NFEPHP_ROOT
                . DIRECTORY_SEPARATOR
                . 'config'
                . DIRECTORY_SEPARATOR
                . $this->aConfig['pathXmlUrlFileNFe'];
        
        if ($this->enableSVCAN) {
            $aURL = self::zLoadSEFAZ($pathXmlUrlFile, $tpAmb, 'SVCAN');
        } elseif ($this->enableSVCRS) {
            $aURL = self::zLoadSEFAZ($pathXmlUrlFile, $tpAmb, 'SVCRS');
        } else {
            $aURL = self::zLoadSEFAZ($pathXmlUrlFile, $tpAmb, $siglaUF);
        }
        //recuperação da versão
        $version = $aURL[$service]['version'];
        //recuperação da url do serviço
        $urlservice = $aURL[$service]['URL'];
        //recuperação do método
        $method = $aURL[$service]['method'];
        //montagem do namespace do serviço
        $operation = $aURL[$service]['operation'];
        $namespace = sprintf("%s/wsdl/%s", $this->urlPortal, $operation);
        //montagem do cabeçalho da comunicação SOAP
        $header = "<nfeCabecMsg "
                . "xmlns=\"$namespace\">"
                . "<cUF>$cUF</cUF>"
                . "<versaoDados>$version</versaoDados>"
                . "</nfeCabecMsg>";
        return true;
    }

    /**
     * zLoadSEFAZ
     * Extrai o URL, nome do serviço e versão dos webservices das SEFAZ de
     * todos os Estados da Federação, a partir do arquivo XML de configurações,
     * onde este é estruturado para os modelos 55 (NF-e) e 65 (NFC-e) já que
     * os endereços dos webservices podem ser diferentes.
     * @param string $pathXmlUrlFile
     * @param  string $tpAmb Pode ser "2-homologacao" ou "1-producao"
     * @param string $siglaUF
     * @return mixed false se houve erro ou array com os dados dos URLs da SEFAZ
     * @internal param string $sUF Sigla da Unidade da Federação (ex. SP, RS, SVRS, etc..)
     * @see /config/nfe_ws3_modXX.xml
     */
    private function zLoadSEFAZ($pathXmlUrlFile = '', $tpAmb = '2', $siglaUF = 'SP')
    {
        //verifica se o arquivo xml pode ser encontrado no caminho indicado
        if (! file_exists($pathXmlUrlFile)) {
            throw new Exception\RuntimeException(
                "Arquivo $pathXmlUrlFile não encontrado."
            );
        }
        //carrega o xml
        if (!$xmlWS = simplexml_load_file($pathXmlUrlFile)) {
            throw new Exception\RuntimeException(
                "Arquivo $pathXmlUrlFile parece ser invalido ou está corrompido."
            );
        }
        $autorizadores = array(
            'AC'=>'SVRS',
            'AL'=>'SVRS',
            'AM'=>'AM',
            'AN'=>'AN',
            'AP'=>'SVRS',
            'BA'=>'BA',
            'CE'=>'CE',
            'DF'=>'SVRS',
            'ES'=>'SVRS',
            'GO'=>'GO',
            'MA'=>'SVAN',
            'MG'=>'MG',
            'MS'=>'MS',
            'MT'=>'MT',
            'PA'=>'SVAN',
            'PB'=>'SVRS',
            'PE'=>'PE',
            'PI'=>'SVAN',
            'PR'=>'PR',
            'RJ'=>'SVRS',
            'RN'=>'SVRS',
            'RO'=>'SVRS',
            'RR'=>'SVRS',
            'RS'=>'RS',
            'SC'=>'SVRS',
            'SE'=>'SVRS',
            'SP'=>'SP',
            'TO'=>'SVRS',
            'SVAN'=>'SVAN',
            'SVRS'=>'SVRS',
            'SVCAN'=>'SVCAN',
            'SVCRS'=>'SVCRS'
        );
        //variável de retorno do método
        $aUrl = array();
        //testa parametro tpAmb
        $sAmbiente = 'homologacao';
        if ($tpAmb == '1') {
            $sAmbiente = 'producao';
        }
        $alias = $autorizadores[$siglaUF];
        //estabelece a expressão xpath de busca
        $xpathExpression = "/WS/UF[sigla='$alias']/$sAmbiente";
        $aUrl = $this->zExtractUrl($xmlWS, $aUrl, $xpathExpression);
        //verifica se existem outros serviços exclusivos para esse estado
        if ($alias == 'SVAN' || $alias == 'SVRS') {
            $xpathExpression = "/WS/UF[sigla='$siglaUF']/$sAmbiente";
            $aUrl = $this->zExtractUrl($xmlWS, $aUrl, $xpathExpression);
        }
        return $aUrl;
    }
    
    /**
     * zExtractUrl
     * @param simplexml $xmlWS
     * @param array $aUrl
     * @param string $expression
     * @return array
     */
    private function zExtractUrl($xmlWS, $aUrl = array(), $expression = '')
    {
        //para cada "nó" no xml que atenda aos critérios estabelecidos
        foreach ($xmlWS->xpath($expression) as $gUF) {
            //para cada "nó filho" retonado
            foreach ($gUF->children() as $child) {
                $u = (string) $child[0];
                $aUrl[$child->getName()]['URL'] = $u;
                // em cada um desses nós pode haver atributos como a identificação
                // do nome do webservice e a sua versão
                foreach ($child->attributes() as $a => $b) {
                    $aUrl[$child->getName()][$a] = (string) $b;
                }
            }
        }
        return $aUrl;
    }
    
    /**
     * Carrega a classe SOAP e os certificados
     */
    private function zLoadSoapClass()
    {
        $this->oSoap = null;
        $this->oSoap = new CurlSoap(
            $this->oCertificate->priKeyFile,
            $this->oCertificate->pubKeyFile,
            $this->oCertificate->certKeyFile,
            '10'
        );
    }
    
    /**
     * zReadReturnSefaz
     * Trata o retorno da SEFAZ devolvendo o resultado em um array
     * @param string $method
     * @param string $xmlResp
     * @param mixed $parametro
     * @return array
     */
    private static function zReadReturnSefaz($method, $xmlResp, $parametro = false)
    {
        $dom = new Dom();
        $dom->loadXMLString($xmlResp);
        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'nfeAutorizacaoLote':
                return self::zReadAutorizacaoLote($dom);
                break;
            case 'nfeRetAutorizacaoLote':
                return self::zReadRetAutorizacaoLote($dom);
                break;
            case 'consultaCadastro2':
                return self::zReadConsultaCadastro2($dom);
                break;
            case 'nfeConsultaNF2':
                return self::zReadConsultaNF2($dom);
                break;
            case 'nfeInutilizacaoNF2':
                return self::zReadInutilizacaoNF2($dom);
                break;
            case 'nfeStatusServicoNF2':
                //NOTA: irá ser desativado
                return self::zReadStatusServico($dom);
                break;
            case 'nfeRecepcaoEvento':
                return self::zReadRecepcaoEvento($dom);
                break;
            case 'nfeDistDFeInteresse':
                return self::zReadDistDFeInteresse($dom, $parametro);
                break;
        }
        return array();
    }
    
    /**
     * zReadAutorizacaoLote
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadAutorizacaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'cUF' => '',
            'nRec' => '',
            'prot' => array()
        );
        $tag = $dom->getElementsByTagName('retEnviNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $dhRecbto = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $nRec = $tag->getElementsByTagName('nRec')->item(0)->nodeValue;
        $tMed = $tag->getElementsByTagName('tMed')->item(0)->nodeValue;
        $aProt = array();
        $infProt = $tag->getElementsByTagName('infProt')->item(0);
        if (! empty($infProt)) {
            $aProt[] = array(
                'chNFe' => $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'dhRecbto' => $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
                'nProt' => $infProt->getElementsByTagName('nProt')->item(0)->nodeValue,
                'digVal' => $infProt->getElementsByTagName('digVal')->item(0)->nodeValue,
                'cStat' => $infProt->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue
            );
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'tMed' => $tMed,
            'cUF' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'nRec' => $nRec,
            'prot' => $aProt
        );
        return $aResposta;
    }
    
    /**
     * zReadRetAutorizacaoLote
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadRetAutorizacaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat'=>false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'cUF' => '',
            'nRec' => '',
            'aProt' => array()
        );
        $tag = $dom->getElementsByTagName('retConsReciNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aProt = array();
        $dhRecbto = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $tagProt = $tag->getElementsByTagName('protNFe');
        foreach ($tagProt as $protocol) {
            $infProt = $protocol->getElementsByTagName('infProt')->item(0);
            $aProt[] = array(
                'chNFe' => $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'dhRecbto' => $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
                'nProt' => $infProt->getElementsByTagName('nProt')->item(0)->nodeValue,
                'digVal' => $infProt->getElementsByTagName('digVal')->item(0)->nodeValue,
                'cStat' => $infProt->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue
            );
        }
        $aResposta = array(
            'bStat'=>true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'cUF' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'nRec' => $tag->getElementsByTagName('nRec')->item(0)->nodeValue,
            'aProt' => $aProt
        );
        return $aResposta;
    }
    
    /**
     * zReadConsultaCadastro2
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadConsultaCadastro2($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'version' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'UF' => '',
            'IE' => '',
            'CNPJ' => '',
            'CPF' => '',
            'dhCons' => '',
            'cUF' => '',
            'aCad' => array()
        );
        $tag = $dom->getElementsByTagName('retConsCad')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infCons = $tag->getElementsByTagName('infCons')->item(0);
        $aResposta = array(
            'bStat' => true,
            'version' => $tag->getAttribute('versao'),
            'cStat' => $infCons->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $infCons->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'UF' => $infCons->getElementsByTagName('UF')->item(0)->nodeValue,
            'IE' => $infCons->getElementsByTagName('IE')->item(0)->nodeValue,
            'CNPJ' => $infCons->getElementsByTagName('CNPJ')->item(0)->nodeValue,
            'CPF' => $infCons->getElementsByTagName('CPF')->item(0)->nodeValue,
            'dhCons' => $infCons->getElementsByTagName('dhCons')->item(0)->nodeValue,
            'cUF' => $infCons->getElementsByTagName('cUF')->item(0)->nodeValue,
            'aCad' => array()
        );
        $infCad = $tag->getElementsByTagName('infCad');
        if (isset($infCad)) {
            foreach ($infCad as $cad) {
                $ender = $cad->getElementsByTagName('ender')->item(0);
                $aCad[] = array(
                    'IE' => $cad->getElementsByTagName('IE')->item(0)->nodeValue,
                    'CNPJ' => $cad->getElementsByTagName('CNPJ')->item(0)->nodeValue,
                    'UF' => $cad->getElementsByTagName('UF')->item(0)->nodeValue,
                    'cSit' => $cad->getElementsByTagName('cSit')->item(0)->nodeValue,
                    'indCredNFe' => $cad->getElementsByTagName('indCredNFe')->item(0)->nodeValue,
                    'indCredCTe' => $cad->getElementsByTagName('indCredCTe')->item(0)->nodeValue,
                    'xNome' => $cad->getElementsByTagName('xNome')->item(0)->nodeValue,
                    'xRegApur' => $cad->getElementsByTagName('xRegApur')->item(0)->nodeValue,
                    'CNAE' => $cad->getElementsByTagName('CNAE')->item(0)->nodeValue,
                    'dIniAtiv' => $cad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue,
                    'dUltSit' => $cad->getElementsByTagName('dUltSit')->item(0)->nodeValue,
                    'xLgr' => $ender->getElementsByTagName('xLgr')->item(0)->nodeValue,
                    'nro' => $ender->getElementsByTagName('nro')->item(0)->nodeValue,
                    'xCpl' => $ender->getElementsByTagName('xCpl')->item(0)->nodeValue,
                    'xBairro' => $ender->getElementsByTagName('xBairro')->item(0)->nodeValue,
                    'cMun' => $ender->getElementsByTagName('cMun')->item(0)->nodeValue,
                    'xMun' => $ender->getElementsByTagName('xMun')->item(0)->nodeValue,
                    'CEP' => $ender->getElementsByTagName('CEP')->item(0)->nodeValue
                );
            }
            $aResposta['aCad'] = $aCad;
        }
        return $aResposta;
    }

    /**
     * zReadConsultaNF2
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadConsultaNF2($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'cUF' => '',
            'chNFe' => '',
            'protNFe' => array(),
            'retCancNFe' => array(),
            'procEventoNFe' => array()
        );
        $tag = $dom->getElementsByTagName('retConsSitNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infProt = $tag->getElementsByTagName('infProt')->item(0);
        $infCanc = $tag->getElementsByTagName('infCanc')->item(0);
        $procEventoNFe = $tag->getElementsByTagName('procEventoNFe');
        $aProt = array();
        $aCanc = array();
        $aEvent = array();
        if (isset($infProt)) {
            $aProt['tpAmb'] = $infProt->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aProt['verAplic'] = $infProt->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aProt['chNFe'] = $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $aProt['dhRecbto'] = $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aProt['nProt'] = $infProt->getElementsByTagName('nProt')->item(0)->nodeValue;
            $aProt['digVal'] = $infProt->getElementsByTagName('digVal')->item(0)->nodeValue;
            $aProt['cStat'] = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aProt['xMotivo'] = $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        }
        if (isset($infCanc)) {
            $aCanc['tpAmb'] = $infCanc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aCanc['verAplic'] = $infCanc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aCanc['cStat'] = $infCanc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aCanc['xMotivo'] = $infCanc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $aCanc['cUF'] = $infCanc->getElementsByTagName('cUF')->item(0)->nodeValue;
            $aCanc['chNFe'] = $infCanc->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $aCanc['dhRecbto'] = $infCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aCanc['nProt'] = $infCanc->getElementsByTagName('nProt')->item(0)->nodeValue;
        }
        if (isset($procEventoNFe)) {
            foreach ($procEventoNFe as $kEli => $evento) {
                $infEvento = $evento->getElementsByTagName('infEvento');
                foreach ($infEvento as $iEv) {
                    if ($iEv->getElementsByTagName('detEvento')->item(0) != "") {
                        continue;
                    }
                    foreach ($iEv->childNodes as $tnodes) {
                        $aEvent[$kEli][$tnodes->nodeName] = $tnodes->nodeValue;
                    }
                }
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'chNFe' => $tag->getElementsByTagName('chNFe')->item(0)->nodeValue,
            'protNFe' => $aProt,
            'retCancNFe' => $aCanc,
            'procEventoNFe' => $aEvent
        );
        return $aResposta;
    }
    
    /**
     * zReadInutilizacaoNF2
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadInutilizacaoNF2($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'dhRecbto' => '',
            'ano' => '',
            'CNPJ' => '',
            'mod' => '',
            'serie' => '',
            'nNFIni' => '',
            'nNFFin' => '',
            'nProt' => ''
        );
        $tag = $dom->getElementsByTagName('retInutNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infInut = $tag->getElementsByTagName('infInut')->item(0);
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $infInut->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $infInut->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cStat' => $infInut->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $infInut->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'cUF' => $infInut->getElementsByTagName('cUF')->item(0)->nodeValue,
            'dhRecbto' => $infInut->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'ano' => $infInut->getElementsByTagName('ano')->item(0)->nodeValue,
            'CNPJ' => $infInut->getElementsByTagName('CNPJ')->item(0)->nodeValue,
            'mod' => $infInut->getElementsByTagName('mod')->item(0)->nodeValue,
            'serie' => $infInut->getElementsByTagName('serie')->item(0)->nodeValue,
            'nNFIni' => $infInut->getElementsByTagName('nNFIni')->item(0)->nodeValue,
            'nNFFin' => $infInut->getElementsByTagName('nNFFin')->item(0)->nodeValue,
            'nProt' => $infInut->getElementsByTagName('nProt')->item(0)->nodeValue
        );
        return $aResposta;
    }
    
    /**
     * zReadStatusServico
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadStatusServico($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'cUF' => ''
        );
        $tag = $dom->getElementsByTagName('retConsStatServ')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'tMed' => $tag->getElementsByTagName('tMed')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue
        );
        return $aResposta;
    }

    /**
     * zReadRecepcaoEvento
     * @param DOMDocument $dom
     * @return array
     */
    private static function zReadRecepcaoEvento($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'idLote' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cOrgao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'evento' => array()
        );
        $tag = $dom->getElementsByTagName('retEnvEvento')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infEvento = $tag->getElementsByTagName('infEvento')->item(0);
        $aEvent = array();
        if (isset($infEvento)) {
            $aEvent = array(
                'tpAmb' => $infEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue,
                'verAplic' => $infEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
                'cOrgao' => $infEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue,
                'cStat' => $infEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
                'chNFe' => $infEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'tpEvento' => $infEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
                'xEvento' => $infEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
                'nSeqEvento' => $infEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
                'CNPJDest' => $infEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue,
                'emailDest' => $infEvento->getElementsByTagName('emailDest')->item(0)->nodeValue,
                'dhRegEvento' => $infEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
                'nProt' => $infEvento->getElementsByTagName('nProt')->item(0)->nodeValue
            );
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'idLote' => $tag->getElementsByTagName('idLote')->item(0)->nodeValue,
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cOrgao' => $tag->getElementsByTagName('cOrgao')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'evento' => $aEvent
        );
        return $aResposta;
    }
    
    /**
     * zReadDistDFeInteresse
     * @param DOMDocument $dom
     * @param boolean $descompactar
     * @return array
     */
    private static function zReadDistDFeInteresse($dom, $descompactar = false)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'dhResp' => '',
            'ultNSU' => 0,
            'maxNSU' => 0,
            'docZip' => array()
        );
        $tag = $dom->getElementsByTagName('retDistDFeInt')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aDocZip = array();
        $docs = $tag->getElementsByTagName('docZip');
        foreach ($docs as $doc) {
            $xml = $doc->nodeValue;
            if ($descompactar) {
                $xml = gzdecode(base64_decode($xml));
            }
            $aDocZip[] = array(
              'NSU' => $doc->getAttribute('NSU'),
              'schema' => $doc->getAttribute('schema'),
              'docZip' => $xml
            );
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhResp' => $tag->getElementsByTagName('dhResp')->item(0)->nodeValue,
            'ultNSU' => $tag->getElementsByTagName('ultNSU')->item(0)->nodeValue,
            'maxNSU' => $tag->getElementsByTagName('maxNSU')->item(0)->nodeValue,
            'docZip' => $aDocZip
        );
        return $aResposta;
    }
    
    /**
     * zGetcUF
     * @param string $siglaUF
     * @return string numero cUF
     */
    private static function zGetcUF($siglaUF = '')
    {
        $cUFlist = array(
            'AC'=>'12',
            'AL'=>'27',
            'AM'=>'13',
            'AN'=>'91',
            'AP'=>'16',
            'BA'=>'29',
            'CE'=>'23',
            'DF'=>'53',
            'ES'=>'32',
            'GO'=>'52',
            'MA'=>'21',
            'MG'=>'31',
            'MS'=>'50',
            'MT'=>'51',
            'PA'=>'15',
            'PB'=>'25',
            'PE'=>'26',
            'PI'=>'22',
            'PR'=>'41',
            'RJ'=>'33',
            'RN'=>'24',
            'RO'=>'11',
            'RR'=>'14',
            'RS'=>'43',
            'SC'=>'42',
            'SE'=>'28',
            'SP'=>'35',
            'TO'=>'17'
        );
        return $cUFlist[$siglaUF];
    }
    
    /**
     * zGetSigla
     * @param string $cUF
     * @return string
     */
    private static function zGetSigla($cUF = '')
    {
        $aUFList = array(
            '11'=>'RO',
            '12'=>'AC',
            '13'=>'AM',
            '14'=>'RR',
            '15'=>'PA',
            '16'=>'AP',
            '17'=>'TO',
            '21'=>'MA',
            '22'=>'PI',
            '23'=>'CE',
            '24'=>'RN',
            '25'=>'PB',
            '26'=>'PE',
            '27'=>'AL',
            '28'=>'SE',
            '29'=>'BA',
            '31'=>'MG',
            '32'=>'ES',
            '33'=>'RJ',
            '35'=>'SP',
            '41'=>'PR',
            '42'=>'SC',
            '43'=>'RS',
            '50'=>'MS',
            '51'=>'MT',
            '52'=>'GO',
            '53'=>'DF',
            '91'=>'SVAN'
        );
        return $aUFList[$cUF];
    }
    
    /**
     * zValidMessage
     * @param string $xml
     * @param string $tipo
     * @param string $versao
     * @return boolean
     */
    private function zValidMessage($xml = '', $tipo = '', $versao = '3.10')
    {
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        //limpar erros anteriores que possam estar em memória
        libxml_clear_errors();
        $this->error = '';
        // instancia novo objeto DOM
        $dom = new Dom();
        $dom->loadXMLString($xml);
        $xsdPath = NFEPHP_ROOT
            . DIRECTORY_SEPARATOR
            . 'schemes'
            . DIRECTORY_SEPARATOR
            . $this->aConfig['schemesNFe']
            . DIRECTORY_SEPARATOR;
        $xsdFile = $xsdPath.$tipo.'_v'.$versao.'.xsd';
        if (! is_file($xsdFile)) {
            $this->error = 'Schema não localizado.';
            return true;
        }
        // valida o xml com o xsd
        if (! $dom->schemaValidate($xsdFile)) {
            // carrega os erros em um array
            $aIntErrors = libxml_get_errors();
            $msg = '';
            foreach ($aIntErrors as $intError) {
                $msg .= self::zTranslateError($intError->message);
            }
            $this->error = $msg;
            return false;
        }
        return true;
    }
    
    /**
     * zTranslateError
     * @param string $message
     * @return string
     */
    private static function zTranslateError($message = '')
    {
        $eEn = array(
            "{http://www.portalfiscal.inf.br/nfe}"
            ,"[facet 'pattern']"
            ,"The value"
            ,"is not accepted by the pattern"
            ,"has a length of"
            ,"[facet 'minLength']"
            ,"this underruns the allowed minimum length of"
            ,"[facet 'maxLength']"
            ,"this exceeds the allowed maximum length of"
            ,"Element"
            ,"attribute"
            ,"is not a valid value of the local atomic type"
            ,"is not a valid value of the atomic type"
            ,"Missing child element(s). Expected is"
            ,"The document has no document element"
            ,"[facet 'enumeration']"
            ,"one of"
            ,"failed to load external entity"
            ,"Failed to locate the main schema resource at"
            ,"This element is not expected. Expected is"
            ,"is not an element of the set");
        
        $ePt = array(""
            ,"[Erro 'Layout']"
            ,"O valor"
            ,"não é aceito para o padrão."
            ,"tem o tamanho"
            ,"[Erro 'Tam. Min']"
            ,"deve ter o tamanho mínimo de"
            ,"[Erro 'Tam. Max']"
            ,"Tamanho máximo permitido"
            ,"Elemento"
            ,"Atributo"
            ,"não é um valor válido"
            ,"não é um valor válido"
            ,"Elemento filho faltando. Era esperado"
            ,"Falta uma tag no documento"
            ,"[Erro 'Conteúdo']"
            ,"um de"
            ,"falha ao carregar entidade externa"
            ,"Falha ao tentar localizar o schema principal em"
            ,"Este elemento não é esperado. Esperado é"
            ,"não é um dos seguintes possiveis");
        return str_replace($eEn, $ePt, $message);
    }
    
    /**
     * zGravaFile
     * Grava os dados no diretorio das NFe
     * @param string $tpAmb ambiente
     * @param string $filename nome do arquivo
     * @param string $data dados a serem salvos
     * @param string $subFolder 
     * @param string $anomes 
     * @throws Exception\RuntimeException
     */
    private function zGravaFile($tpAmb = '2', $filename = '', $data = '', $subFolder = 'temporarias', $anomes = '')
    {
        if ($anomes == '') {
            $anomes = date('Ym');
        }
        $pathTemp = Files\FilesFolders::getFilePath($tpAmb, $this->aConfig['pathNFeFiles'], $subFolder)
            . DIRECTORY_SEPARATOR.$anomes;
        if (! Files\FilesFolders::saveFile($pathTemp, $filename, $data)) {
            $msg = 'Falha na gravação no diretório. '.$pathTemp;
            throw new Exception\RuntimeException($msg);
        }
    }
}
