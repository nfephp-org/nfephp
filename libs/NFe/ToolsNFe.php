<?php

namespace NFe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\NFe\Tools
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Base\BaseTools;
use Common\DateTime\DateTime;
use Common\LotNumber\LotNumber;
use Common\Strings\Strings;
use Common\Files;
use Common\Exception;
use Common\Dom\Dom;
use Common\Dom\ReturnNFe;
use DOMDocument;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class ToolsNFe extends BaseTools
{
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
     * urlPortal
     * Instância do WebService
     * @var string
     */
    protected $urlPortal = 'http://www.portalfiscal.inf.br/nfe';
    /**
     * aLastRetEvent
     * @var array 
     */
    private $aLastRetEvent = array();
    
  
    /**
     * setModelo
     *
     * Ajusta o modelo da NFe 55 ou 65
     *
     * @param string $modelo
     */
    public function setModelo($modelo = '55')
    {
        //força pelo menos um modelo correto
        if ($modelo != '55' && $modelo != '65') {
            $modelo = '55';
        }
        $this->modelo = $modelo;
    }
    
    /**
     * getModelo
     * Retorna o modelo de NFe atualmente setado
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
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
        if ($this->enableSVCAN || $this->enableSVCRS) {
            return true;
        }
        $this->motivoContingencia = $motivo;
        $this->tsContingencia = mktime();
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
        $aCont = array(
            'motivo' => $this->motivoContingencia,
            'ts' => $this->tsContingencia,
            'SVCAN' => $this->enableSVCAN,
            'SCVRS' => $this->enableSVCRS
        );
        $strJson = json_encode($aCont);
        file_put_contents(NFEPHP_ROOT.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'contingencia.json', $strJson);
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
        unlink(NFEPHP_ROOT.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'contingencia.json');
        return true;
    }
    
    /**
     * imprime
     * @param string $pathXml
     * @param string $pathDestino
     * @param string $printer
     * @return string
     */
    public function imprime($pathXml = '', $pathDestino = '', $printer = '')
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
    public function enviaMail($pathXml = '', $aMails = array())
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
            $this->zGravaFile('nfe', $tpAmb, $filename, $procXML, 'enviadas'.DIRECTORY_SEPARATOR.'aprovadas', $anomes);
        }
        return $procXML;
    }

    /**
     * addCancelamento
     * @param string $pathNFefile
     * @param string $pathCancfile
     * @param bool $saveFile
     * @return string
     * @throws Exception\RuntimeException
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
                    $this->zGravaFile(
                        'nfe',
                        $tpAmb,
                        $filename,
                        $procXML,
                        'enviadas'.DIRECTORY_SEPARATOR.'aprovadas',
                        $anomes
                    );
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
        return $this->assinaDoc($xml, 'nfe', 'infNFe', $saveFile);
    }
    
    /**
     * sefazEnviaLote
     * @param array $aXml
     * @param string $tpAmb
     * @param string $idLote
     * @param array $aRetorno
     * @param int $indSinc
     * @param boolean $compactarZip
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @internal function zLoadServico (Common\Base\BaseTools)
     */
    public function sefazEnviaLote(
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
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NfeAutorizacao',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "O envio de lote não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        //montagem dos dados da mensagem SOAP
        $cons = "<enviNFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
                . "<idLote>$idLote</idLote>"
                . "<indSinc>$indSinc</indSinc>"
                . "$sxml"
                . "</enviNFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'nfe', 'enviNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>";
        $method = $this->urlMethod;
        if ($compactarZip) {
            $gzdata = base64_encode(gzencode($cons, 9, FORCE_GZIP));
            $body = "<nfeDadosMsgZip xmlns=\"$this->urlNamespace\">$gzdata</nfeDadosMsgZip>";
            $method = $this->urlMethod."Zip";
        }
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($this->urlService, $this->urlNamespace, $this->urlHeader, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$idLote-enviNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$idLote-retEnviNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
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
     * @internal function zLoadServico (Common\Base\BaseTools)
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
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NfeRetAutorizacao',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A consulta de NFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consReciNFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<nRec>$recibo</nRec>"
            . "</consReciNFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'nfe', 'consReciNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$recibo-consReciNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$recibo-retConsReciNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazConsultaChave
     * Consulta o status da NFe pela chave de 44 digitos
     * @param string $chave
     * @param string $tpAmb
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @internal function zLoadServico (Common\Base\BaseTools)
     */
    public function sefazConsultaChave($chave = '', $tpAmb = '2', &$aRetorno = array())
    {
        $chNFe = preg_replace('/[^0-9]/', '', $chave);
        if (strlen($chNFe) != 44) {
            $msg = "Uma chave de 44 dígitos da NFe deve ser passada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $cUF = substr($chNFe, 0, 2);
        $siglaUF = self::zGetSigla($cUF);
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NfeConsultaProtocolo',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A consulta de NFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consSitNFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
                . "<tpAmb>$tpAmb</tpAmb>"
                . "<xServ>CONSULTAR</xServ>"
                . "<chNFe>$chNFe</chNFe>"
                . "</consSitNFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'nfe', 'consSitNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$chNFe-consSitNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$chNFe-retConsSitNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
        return (string) $retorno;
    }

    /**
     * sefazInutiliza
     * Solicita a inutilização de uma ou uma sequencia de NFe
     * de uma determinada série
     * @param integer $nSerie
     * @param integer $nIni
     * @param integer $nFin
     * @param string $xJust
     * @param string $tpAmb
     * @param array $aRetorno
     * @return string
     * @internal param string $modelo
     * @internal function zLoadServico (Common\Base\BaseTools)
     */
    public function sefazInutiliza(
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
        $this->zValidParamInut($xJust, $nSerie, $nIni, $nFin);
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        //monta serviço
        $siglaUF = $this->aConfig['siglaUF'];
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NfeInutilizacao',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A inutilização não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        //montagem dos dados da mensagem SOAP
        $cnpj = $this->aConfig['cnpj'];
        $sAno = (string) date('y');
        $sSerie = str_pad($nSerie, 3, '0', STR_PAD_LEFT);
        $sInicio = str_pad($nIni, 9, '0', STR_PAD_LEFT);
        $sFinal = str_pad($nFin, 9, '0', STR_PAD_LEFT);
        $idInut = "ID".$this->urlcUF.$sAno.$cnpj.$this->modelo.$sSerie.$sInicio.$sFinal;
        //limpa os caracteres indesejados da justificativa
        $xJust = Strings::cleanString($xJust);
        //montagem do corpo da mensagem
        $cons = "<inutNFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
                . "<infInut Id=\"$idInut\">"
                . "<tpAmb>$tpAmb</tpAmb>"
                . "<xServ>INUTILIZAR</xServ>"
                . "<cUF>$this->urlcUF</cUF>"
                . "<ano>$sAno</ano>"
                . "<CNPJ>$cnpj</CNPJ>"
                . "<mod>$this->modelo</mod>"
                . "<serie>$nSerie</serie>"
                . "<nNFIni>$nIni</nNFIni>"
                . "<nNFFin>$nFin</nNFFin>"
                . "<xJust>$xJust</xJust>"
                . "</infInut></inutNFe>";
        //assina a lsolicitação de inutilização
        $signedMsg = $this->oCertificate->signXML($cons, 'infInut');
        $signedMsg = Strings::clearXml($signedMsg, true);
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'nfe', 'inutNFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$signedMsg</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$sAno-$this->modelo-$sSerie-".$sInicio."_".$sFinal."-inutNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$sAno-$this->modelo-$sSerie-".$sInicio."_".$sFinal."-retInutNFe.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
        return (string) $retorno;
    }
    
    /**
     * zValidParamInut
     * @param string $xJust
     * @param int $nSerie
     * @param int $nIni
     * @param int $nFin
     * @throws Exception\InvalidArgumentException
     */
    private function zValidParamInut($xJust, $nSerie, $nIni, $nFin)
    {
        $msg = '';
        //valida dos dados de entrada
        if (strlen($xJust) < 15 || strlen($xJust) > 255) {
            $msg = "A justificativa deve ter entre 15 e 255 digitos!!";
        } elseif ($nSerie < 0 || $nSerie > 999) {
            $msg = "O campo serie está errado: $nSerie!!";
        } elseif ($nIni < 1 || $nIni > 1000000000) {
            $msg = "O campo numero inicial está errado: $nIni!!";
        } elseif ($nFin < 1 || $nFin > 1000000000) {
            $msg = "O campo numero final está errado: $nFin!!";
        } elseif ($this->enableSVCRS || $this->enableSVCAN) {
            $msg = "A inutilização não pode ser feita em contingência!!";
        }
        if ($msg != '') {
            throw new Exception\InvalidArgumentException($msg);
        }
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
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @internal function zLoadServico (Common\Base\BaseTools)
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
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NfeConsultaCadastro',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A consulta de Cadastros não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<ConsCad xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<infCons>"
            . "<xServ>CONS-CAD</xServ>"
            . "<UF>$siglaUF</UF>"
            . "$filtro</infCons></ConsCad>";
        //valida a mensagem com o xsd
        //não tem validação estavel para esse xml
        //if (! $this->zValidMessage($cons, 'nfe', 'ConsCad', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$txtFile-consCad.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$txtFile-retConsCad.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
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
     * @throws Exception\RuntimeException
     * @internal function zLoadServico (Common\Base\BaseTools)
     */
    public function sefazStatus($siglaUF = '', $tpAmb = '2', &$aRetorno = array())
    {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($siglaUF == '') {
            $siglaUF = $this->aConfig['siglaUF'];
        }
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NfeStatusServico',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "O status não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consStatServ xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<tpAmb>$tpAmb</tpAmb><cUF>$this->urlcUF</cUF>"
            . "<xServ>STATUS</xServ></consStatServ>";
        //valida mensagem com xsd
        //if (! $this->zValidMessage($cons, 'nfe', 'consStatServ', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>";
        //consome o webservice e verifica o retorno do SOAP
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        $datahora = date('Ymd_His');
        $filename = $siglaUF."_"."$datahora-consStatServ.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = $siglaUF."_"."$datahora-retConsStatServ.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
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
     * @param string $cnpj
     * @param integer $ultNSU ultimo numero NSU que foi consultado
     * @param integer $numNSU numero de NSU que se quer consultar
     * @param array $aRetorno array com os dados do retorno
     * @param boolean $descompactar se true irá descompactar os dados retornados,
     *        se não os dados serão retornados da forma que foram recebidos
     * @return string contento o xml retornado pela SEFAZ
     * @internal function zLoadServico (Common\Base\BaseTools)
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
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $siglaUF = $this->aConfig['siglaUF'];
        if ($cnpj == '') {
            $cnpj = $this->aConfig['cnpj'];
        }
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'NFeDistribuicaoDFe',
            $fonte,
            $tpAmb
        );
        if ($this->urlService == '') {
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
        $cons = "<distDFeInt xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<cUFAutor>$cUF</cUFAutor>"
            . "<CNPJ>$cnpj</CNPJ>$tagNSU</distDFeInt>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'nfe', 'distDFeInt', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDistDFeInteresse xmlns=\"$this->urlNamespace\">"
            . "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>"
            . "</nfeDistDFeInteresse>";
        //envia dados via SOAP e verifica o retorno este webservice não requer cabeçalho
        $this->urlHeader = '';
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $tipoNSU = (int) ($numNSU != 0 ? $numNSU : $ultNSU);
        $datahora = date('Ymd_His');
        $filename = "$tipoNSU-$datahora-distDFeInt.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$tipoNSU-$datahora-retDistDFeInt.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno, $descompactar);
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
        $tagAdic = '';
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
     * @internal function zLoadServico (Common\Base\BaseTools)
     */
    protected function zSefazEvento(
        $siglaUF = '',
        $chNFe = '',
        $tpAmb = '2',
        $tpEvento = '',
        $nSeqEvento = '1',
        $tagAdic = ''
    ) {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        //carrega serviço
        $this->zLoadServico(
            'nfe',
            'RecepcaoEvento',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A recepção de eventos não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $aRet = $this->zTpEv($tpEvento);
        $aliasEvento = $aRet['alias'];
        $descEvento = $aRet['desc'];
        $cnpj = $this->aConfig['cnpj'];
        $dhEvento = (string) str_replace(' ', 'T', date('Y-m-d H:i:sP'));
        $sSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
        $eventId = "ID".$tpEvento.$chNFe.$sSeqEvento;
        $cOrgao = $this->urlcUF;
        if ($siglaUF == 'AN') {
            $cOrgao = '91';
        }
        $mensagem = "<evento xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<infEvento Id=\"$eventId\">"
            . "<cOrgao>$cOrgao</cOrgao>"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<CNPJ>$cnpj</CNPJ>"
            . "<chNFe>$chNFe</chNFe>"
            . "<dhEvento>$dhEvento</dhEvento>"
            . "<tpEvento>$tpEvento</tpEvento>"
            . "<nSeqEvento>$nSeqEvento</nSeqEvento>"
            . "<verEvento>$this->urlVersion</verEvento>"
            . "<detEvento versao=\"$this->urlVersion\">"
            . "<descEvento>$descEvento</descEvento>"
            . "$tagAdic"
            . "</detEvento>"
            . "</infEvento>"
            . "</evento>";
        //assinatura dos dados
        $signedMsg = $this->oCertificate->signXML($mensagem, 'infEvento');
        $signedMsg = Strings::clearXml($signedMsg, true);
        $numLote = LotNumber::geraNumLote();
        $cons = "<envEvento xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<idLote>$numLote</idLote>"
            . "$signedMsg"
            . "</envEvento>";
        //valida mensagem com xsd
        //no caso do evento nao tem xsd organizado, esta fragmentado
        //e por vezes incorreto por isso essa validação está desabilitada
        //if (! $this->zValidMessage($cons, 'nfe', 'envEvento', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        $body = "<nfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</nfeDadosMsg>";
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send(
            $this->urlService,
            $this->urlNamespace,
            $this->urlHeader,
            $body,
            $this->urlMethod
        );
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$chNFe-$aliasEvento-envEvento.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $lastMsg);
        $filename = "$chNFe-$aliasEvento-retEnvEvento.xml";
        $this->zGravaFile('nfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $this->aLastRetEvent = ReturnNFe::readReturnSefaz($this->urlMethod, $retorno);
        return (string) $retorno;
    }
    
    /**
     * zTpEv
     * @param string $tpEvento
     * @return array
     * @throws Exception\RuntimeException
     */
    private function zTpEv($tpEvento = '')
    {
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
        return array('alias' => $aliasEvento, 'desc' => $descEvento);
    }
}
