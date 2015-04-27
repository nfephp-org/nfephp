<?php

namespace NFePHP\MDFe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\MDFe\ToolsMDFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Base\BaseTools;
use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\LotNumber\LotNumber;
use NFePHP\Common\Strings\Strings;
use NFePHP\Common\Files;
use NFePHP\Common\Exception;
use NFePHP\Common\Dom\Dom;
use NFePHP\MDFe\ReturnMDFe;
use NFePHP\MDFe\MailMDFe;
use NFePHP\MDFe\IdentifyMDFe;
use NFePHP\Common\Dom\ValidXsd;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class ToolsMDFe extends BaseTools
{
    /**
     * errrors
     * @var string
     */
    public $errors = array();
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
    protected $urlPortal = 'http://www.portalfiscal.inf.br/mdfe';
    /**
     * aLastRetEvent
     * @var array 
     */
    private $aLastRetEvent = array();
    
    
    /**
     * imprime
     * Imprime o documento eletrônico (MDFe, CCe, Inut.)
     * @param string $pathXml
     * @param string $pathDestino
     * @param string $printer
     * @return string
     */
    public function imprime($pathXml = '', $pathDestino = '', $printer = '')
    {
        //TODO : falta implementar esse método para isso é necessária a classe
        //PrintNFe
        return "$pathXml $pathDestino $printer";
    }
    
    /**
     * enviaMail
     * Envia a MDFe por email aos destinatários
     * Caso $aMails esteja vazio serão obtidos os email do destinatário  e 
     * os emails que estiverem registrados nos campos obsCont do xml
     * @param string $pathXml
     * @param array $aMails
     * @param string $templateFile path completo ao arquivo template html do corpo do email
     * @param boolean $comPdf se true o sistema irá renderizar o DANFE e anexa-lo a mensagem
     * @return boolean
     */
    public function enviaMail($pathXml = '', $aMails = array(), $templateFile = '', $comPdf = false)
    {
        $mail = new MailNFe($this->aMailConf);
        if ($templateFile != '') {
            $mail->setTemplate($templateFile);
        }
        return $mail->envia($pathXml, $aMails, $comPdf);
    }

    /**
     * addProtocolo
     * Adiciona o protocolo de autorização de uso da MDFe
     * NOTA: exigência da SEFAZ, a MDFe somente é válida com o seu respectivo protocolo
     * @param string $pathMDFefile
     * @param string $pathProtfile
     * @param boolean $saveFile
     * @return string
     * @throws Exception\RuntimeException
     */
    public function addProtocolo($pathMDFefile = '', $pathProtfile = '', $saveFile = false)
    {
        //carrega a MDFe
        $docnfe = new Dom();
        $docnfe->loadXMLFile($pathMDFefile);
        $nodemdfe = $docnfe->getNode('MDFe', 0);
        if ($nodemdfe == '') {
            $msg = "O arquivo indicado como MDFe não é um xml de MDFe!";
            throw new Exception\RuntimeException($msg);
        }
        if ($docmdfe->getNode('Signature') == '') {
            $msg = "O MDFe não está assinado!";
            throw new Exception\RuntimeException($msg);
        }
        //carrega o protocolo
        $docprot = new Dom();
        $docprot->loadXMLFile($pathProtfile);
        $nodeprots = $docprot->getElementsByTagName('protMDFe');
        if ($nodeprots->length == 0) {
            $msg = "O arquivo indicado não contêm um protocolo de autorização!";
            throw new Exception\RuntimeException($msg);
        }
        //carrega dados da NFe
        $tpAmb = $docnfe->getNodeValue('tpAmb');
        $anomes = date(
            'Ym',
            DateTime::convertSefazTimeToTimestamp($docmdfe->getNodeValue('dhEmi'))
        );
        $infMDFe = $docnfe->getNode("infMDFe", 0);
        $versao = $infMDFe->getAttribute("versao");
        $chaveId = $infMDFe->getAttribute("Id");
        $chaveMDFe = preg_replace('/[^0-9]/', '', $chaveId);
        $digValueMDFe = $docnfe->getNodeValue('DigestValue');
        //carrega os dados do protocolo
        for ($i = 0; $i < $nodeprots->length; $i++) {
            $nodeprot = $nodeprots->item($i);
            $protver = $nodeprot->getAttribute("versao");
            $chaveProt = $nodeprot->getElementsByTagName("chMDFe")->item(0)->nodeValue;
            $digValueProt = $nodeprot->getElementsByTagName("digVal")->item(0)->nodeValue;
            $infProt = $nodeprot->getElementsByTagName("infProt")->item(0);
            if ($digValueMDFe == $digValueProt && $chaveMDFe == $chaveProt) {
                break;
            }
        }
        if ($digValueMDFe != $digValueProt) {
            $msg = "Inconsistência! O DigestValue do MDFe não combina com o"
                . " do digVal do protocolo indicado!";
            throw new Exception\RuntimeException($msg);
        }
        if ($chaveMDFe != $chaveProt) {
            $msg = "O protocolo indicado pertence a outro MDFe. Os números das chaves não combinam !";
            throw new Exception\RuntimeException($msg);
        }
        //cria a NFe processada com a tag do protocolo
        $procmdfe = new \DOMDocument('1.0', 'utf-8');
        $procmdfe->formatOutput = false;
        $procmdfe->preserveWhiteSpace = false;
        //cria a tag nfeProc
        $mdfeProc = $procmdfe->createElement('mdfeProc');
        $procmdef->appendChild($mdfeProc);
        //estabele o atributo de versão
        $mdfeProcAtt1 = $mdfeProc->appendChild($procmdfe->createAttribute('versao'));
        $mdfeProcAtt1->appendChild($procmdfe->createTextNode($protver));
        //estabelece o atributo xmlns
        $mdfeProcAtt2 = $mdfeProc->appendChild($procmdfe->createAttribute('xmlns'));
        $mdfeProcAtt2->appendChild($procmdfe->createTextNode($this->urlPortal));
        //inclui a tag MDFe
        $node = $procmdef->importNode($nodemdfe, true);
        $mdfeProc->appendChild($node);
        //cria tag protNFe
        $protMDFe = $procmdfe->createElement('protMDFe');
        $mdfeProc->appendChild($protMDFe);
        //estabele o atributo de versão
        $protMDFeAtt1 = $protMDFe->appendChild($procmdfe->createAttribute('versao'));
        $protMDFeAtt1->appendChild($procmdef->createTextNode($versao));
        //cria tag infProt
        $nodep = $procmdfe->importNode($infProt, true);
        $protMDFe->appendChild($nodep);
        //salva o xml como string em uma variável
        $procXML = $procmdfe->saveXML();
        //remove as informações indesejadas
        $procXML = Strings::clearProt($procXML);
        if ($saveFile) {
            $filename = "$chaveMDFe-protMDFe.xml";
            $this->zGravaFile(
                'mdfe',
                $tpAmb,
                $filename,
                $procXML,
                'enviadas'.DIRECTORY_SEPARATOR.'aprovadas',
                $anomes
            );
        }
        return $procXML;
    }
    
    /**
     * addCancelamento
     * Adiciona a tga de cancelamento a uma MDFe já autorizada
     * NOTA: não é requisito da SEFAZ, mas auxilia na identificação das MDFe que foram canceladas
     * @param string $pathMDFefile
     * @param string $pathCancfile
     * @param bool $saveFile
     * @return string
     * @throws Exception\RuntimeException
     */
    public function addCancelamento($pathMDFefile = '', $pathCancfile = '', $saveFile = false)
    {
        $procXML = '';
        //carrega a NFe
        $docmdfe = new Dom();
        $docmdfe->loadXMLFile($pathMDFefile);
        $nodemdfe = $docmdfe->getNode('MDFe', 0);
        if ($nodemdfe == '') {
            $msg = "O arquivo indicado como MDFe não é um xml de MDFe!";
            throw new Exception\RuntimeException($msg);
        }
        $proMDFe = $docmdfe->getNode('protMDFe');
        if ($proMDFe == '') {
            $msg = "O MDFe não está protocolado ainda!!";
            throw new Exception\RuntimeException($msg);
        }
        $chaveMDFe = $proMDFe->getElementsByTagName('chMDFe')->item(0)->nodeValue;
        //$nProtNFe = $proNFe->getElementsByTagName('nProt')->item(0)->nodeValue;
        $tpAmb = $docmdfe->getNodeValue('tpAmb');
        $anomes = date(
            'Ym',
            DateTime::convertSefazTimeToTimestamp($docmdfe->getNodeValue('dhEmi'))
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
            //$nProtEvento = $evento->getElementsByTagName('nProt')->item(0)->nodeValue;
            //verifica se conferem os dados
            //cStat = 135 ==> evento homologado
            //tpEvento = 110111 ==> Cancelamento
            //chave do evento == chave da NFe
            //protocolo do evneto ==  protocolo da NFe
            if ($cStat == '135' &&
                $tpEvento == '110111' &&
                $chaveEvento == $chaveMDFe
            ) {
                $proMDFe->getElementsByTagName('cStat')->item(0)->nodeValue = '101';
                $proMDFe->getElementsByTagName('xMotivo')->item(0)->nodeValue = 'Cancelamento de NF-e homologado';
                $procXML = $docmdfe->saveXML();
                //remove as informações indesejadas
                $procXML = Strings::clearProt($procXML);
                if ($saveFile) {
                    $filename = "$chaveMDFe-protMDFe.xml";
                    $this->zGravaFile(
                        'mdfe',
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
        $this->oCertificate->verifySignature($xml, 'infMDFe');
        //obtem o chave da NFe
        $docnfe = new Dom();
        $docnfe->loadXMLFile($pathXmlFile);
        $tpAmb = $docnfe->getNodeValue('tpAmb');
        $chMDFe  = $docnfe->getChave('infMDFe');
        $this->sefazConsultaChave($chMDFe, $tpAmb, $aRetorno);
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
        return $this->assinaDoc($xml, 'mdfe', 'infMDFe', $saveFile);
    }

    /**
     * sefazEnviaLote
     * @param string $xml
     * @param string $tpAmb
     * @param string $idLote
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @internal function zLoadServico (Common\Base\BaseTools)
     */
    public function sefazEnviaLote(
        $xml,
        $tpAmb = '2',
        $idLote = '',
        &$aRetorno = array()
    ) {
        if (empty($xml)) {
            $msg = "Pelo menos uma MDFe deve ser informada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        $sxml = preg_replace("/<\?xml.*\?>/", "", $xml);
        $siglaUF = $this->aConfig['siglaUF'];
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($idLote == '') {
            $idLote = LotNumber::geraNumLote(15);
        }
        //carrega serviço
        $this->zLoadServico(
            'mdfe',
            'MDFeRecepcao',
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "O envio de lote não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        //montagem dos dados da mensagem SOAP
        $cons = "<enviMDFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
                . "<idLote>$idLote</idLote>$sxml</enviMDFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'mdfe', 'enviMDFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<mdfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</mdfeDadosMsg>";
        $method = $this->urlMethod;
        //envia a solicitação via SOAP
        $retorno = $this->oSoap->send($this->urlService, $this->urlNamespace, $this->urlHeader, $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$idLote-enviMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $lastMsg);
        $filename = "$idLote-retEnviMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnMDFe::readReturnSefaz($this->urlMethod, $retorno);
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
        $servico = 'MDFeRetRecepcao';
        $this->zLoadServico(
            'mdfe',
            $servico,
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A consulta de MDFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consReciMDFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<nRec>$recibo</nRec>"
            . "</consReciMDFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'mdfe', 'consReciMDFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<mdfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</mdfeDadosMsg>";
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
        $filename = "$recibo-consReciMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $lastMsg);
        $filename = "$recibo-retConsReciMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnMDFe::readReturnSefaz($servico, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazConsultaChave
     * Consulta o status da MDFe pela chave de 44 digitos
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
        $chMDFe = preg_replace('/[^0-9]/', '', $chave);
        if (strlen($chMDFe) != 44) {
            $msg = "Uma chave de 44 dígitos da MDFe deve ser passada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $cUF = substr($chMDFe, 0, 2);
        $siglaUF = self::zGetSigla($cUF);
        //carrega serviço
        $servico = 'MDFeConsulta';
        $this->zLoadServico(
            'mdfe',
            $servico,
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A consulta de MDFe não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consSitMDFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
                . "<tpAmb>$tpAmb</tpAmb>"
                . "<xServ>CONSULTAR</xServ>"
                . "<chMDFe>$chMDFe</chMDFe>"
                . "</consSitMDFe>";
        //valida a mensagem com o xsd
        //if (! $this->zValidMessage($cons, 'mdfe', 'consSitMDFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<mdfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</mdfeDadosMsg>";
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
        $filename = "$chMDFe-consSitMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $lastMsg);
        $filename = "$chMDFe-retConsSitMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnMDFe::readReturnSefaz($servico, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazStatus
     * Verifica o status do serviço da SEFAZ
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
        $servico = 'MDFeStatusServico';
        $this->zLoadServico(
            'mdfe',
            $servico,
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "O status não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consStatServMDFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<xServ>STATUS</xServ></consStatServMDFe>";
        //valida mensagem com xsd
        //if (! $this->zValidMessage($cons, 'mdfe', 'consStatServMDFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<mdefDadosMsg xmlns=\"$this->urlNamespace\">$cons</mdfeDadosMsg>";
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
        $this->zGravaFile('mdfe', $tpAmb, $filename, $lastMsg);
        $filename = $siglaUF."_"."$datahora-retConsStatServ.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnMDFe::readReturnSefaz($servico, $retorno);
        return (string) $retorno;
    }
    
    /**
     * sefazCancela
     * @param string $chave
     * @param string $tpAmb
     * @param string $xJust
     * @param string $nProt
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public function sefazCancela(
        $chave = '',
        $tpAmb = '2',
        $nSeqEvento = '1',
        $nProt = '',
        $xJust = '',
        &$aRetorno = array()
    ) {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $chMDFe = preg_replace('/[^0-9]/', '', $chave);
        $nProt = preg_replace('/[^0-9]/', '', $nProt);
        $xJust = Strings::cleanString($xJust);
        if (strlen($chMDFe) != 44) {
            $msg = "Uma chave de MDFe válida não foi passada como parâmetro $chMDFe.";
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
        $siglaUF = self::zGetSigla(substr($chMDFe, 0, 2));
        //estabelece o codigo do tipo de evento CANCELAMENTO
        $tpEvento = '110111';
        if ($nSeqEvento == '') {
            $nSeqEvento = '1';
        }
        $tagAdic = "<evCancMDFe><descEvento>Cancelamento</descEvento>"
                . "<nProt>$nProt</nProt><xJust>$xJust</xJust></evCancMDFe>";
        $retorno = $this->zSefazEvento($siglaUF, $chMDFe, $tpAmb, $tpEvento, $nSeqEvento, $tagAdic);
        $aRetorno = $this->aLastRetEvent;
        return $retorno;
    }
    
    /**
     * sefazEncerra
     * @param string $chave
     * @param string $tpAmb
     * @param string $nProt
     * @param string $cUF
     * @param string $cMun
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public function sefazEncerra(
        $chave = '',
        $tpAmb = '2',
        $nSeqEvento = '1',
        $nProt = '',
        $cUF = '',
        $cMun = '',
        &$aRetorno = array()
    ) {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $chMDFe = preg_replace('/[^0-9]/', '', $chave);
        $nProt = preg_replace('/[^0-9]/', '', $nProt);
        if (strlen($chMDFe) != 44) {
            $msg = "Uma chave de MDFe válida não foi passada como parâmetro $chMDFe.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nProt == '') {
            $msg = "Não foi passado o numero do protocolo!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        $siglaUF = self::zGetSigla(substr($chMDFe, 0, 2));
        //estabelece o codigo do tipo de evento CANCELAMENTO
        $tpEvento = '110112';
        if ($nSeqEvento == '') {
            $nSeqEvento = '1';
        }
        $dtEnc = date('Y-m-d');
        $tagAdic = "<evEncMDFe><descEvento>Encerramento</descEvento>"
                . "<nProt>$nProt</nProt><dtEnc>$dtEnc</dtEnc><cUF>$cUF</cUF>"
                . "<cMun>$cMun</cMun></evEncMDFe>";
        $retorno = $this->zSefazEvento($siglaUF, $chMDFe, $tpAmb, $tpEvento, $nSeqEvento, $tagAdic);
        $aRetorno = $this->aLastRetEvent;
        return $retorno;
    }
    
    /**
     * sefazIncluiCondutor
     * @param string $chave
     * @param string $tpAmb
     * @param string $nSeqEvento
     * @param string $xNome
     * @param string $cpf
     * @param array $aRetorno
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public function sefazIncluiCondutor(
        $chave = '',
        $tpAmb = '2',
        $nSeqEvento = '1',
        $xNome = '',
        $cpf = '',
        &$aRetorno = array()
    ) {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        $chMDFe = preg_replace('/[^0-9]/', '', $chave);
        $nProt = preg_replace('/[^0-9]/', '', $nProt);
        if (strlen($chMDFe) != 44) {
            $msg = "Uma chave de MDFe válida não foi passada como parâmetro $chMDFe.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if ($nProt == '') {
            $msg = "Não foi passado o numero do protocolo!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        $siglaUF = self::zGetSigla(substr($chMDFe, 0, 2));
        //estabelece o codigo do tipo de evento Inclusão de condutor
        $tpEvento = '110114';
        if ($nSeqEvento == '') {
            $nSeqEvento = '1';
        }
        //monta mensagem
        $tagAdic = "<evIncCondutorMDFe><descEvento>Inclusao Condutor</descEvento>"
                . "<Condutor><xNome>$xNome</xNome><CPF>$cpf</CPF></Condutor></evIncCondutorMDFe>";
        $retorno = $this->zSefazEvento($siglaUF, $chMDFe, $tpAmb, $tpEvento, $nSeqEvento, $tagAdic);
        $aRetorno = $this->aLastRetEvent;
        return $retorno;
    }
    
    /**
     * sefazConsultaNaoEncerrados
     * @param string $tpAmb
     * @param string $cnpj
     * @param array $aRetorno
     * @return string
     * @throws Exception\RuntimeException
     */
    public function sefazConsultaNaoEncerrados($tpAmb = '2', $cnpj = '', &$aRetorno = array())
    {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($cnpj == '') {
            $cnpj = $this->aConfig['cnpj'];
        }
        $siglaUF = $this->aConfig['siglaUF'];
        //carrega serviço
        $servico = 'MDFeConsNaoEnc';
        $this->zLoadServico(
            'mdfe',
            $servico,
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "O serviço não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $cons = "<consMDFeNaoEnc xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<xServ>CONSULTAR NÃO ENCERRADOS</xServ><CNPJ>$cnpj</CNPJ></consMDFeNaoEnc>";
        //valida mensagem com xsd
        //if (! $this->zValidMessage($cons, 'mdfe', 'consMDFeNaoEnc', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        //montagem dos dados da mensagem SOAP
        $body = "<mdefDadosMsg xmlns=\"$this->urlNamespace\">$cons</mdfeDadosMsg>";
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
        $filename = $siglaUF."_"."$datahora-consNaoEnc.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $lastMsg);
        $filename = $siglaUF."_"."$datahora-retConsNaoEnc.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $aRetorno = ReturnMDFe::readReturnSefaz($servico, $retorno);
        return (string) $retorno;
    }
    
    /**
     * zSefazEvento
     * @param string $siglaUF
     * @param string $chave
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
        $chave = '',
        $cOrgao = '',
        $tpAmb = '2',
        $tpEvento = '',
        $nSeqEvento = '1',
        $tagAdic = ''
    ) {
        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        //carrega serviço
        $servico = 'MDFeRecepcaoEvento';
        $this->zLoadServico(
            'mdfe',
            $servico,
            $siglaUF,
            $tpAmb
        );
        if ($this->urlService == '') {
            $msg = "A recepção de eventos não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }
        $aRet = $this->zTpEv($tpEvento);
        $aliasEvento = $aRet['alias'];
        $cnpj = $this->aConfig['cnpj'];
        $dhEvento = (string) str_replace(' ', 'T', date('Y-m-d H:i:sP'));
        $sSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
        $eventId = "ID".$tpEvento.$chave.$sSeqEvento;
        if ($cOrgao == '') {
            $cOrgao = $this->urlcUF;
        }
        $mensagem = "<eventoMDFe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<infEvento Id=\"$eventId\">"
            . "<cOrgao>$cOrgao</cOrgao>"
            . "<tpAmb>$tpAmb</tpAmb>"
            . "<CNPJ>$cnpj</CNPJ>"
            . "<chMDFe>$chave</chMDFe>"
            . "<dhEvento>$dhEvento</dhEvento>"
            . "<tpEvento>$tpEvento</tpEvento>"
            . "<nSeqEvento>$nSeqEvento</nSeqEvento>"
            . "<verEvento>$this->urlVersion</verEvento>"
            . "<detEvento versaoEvento=\"$this->urlVersion\">"
            . "$tagAdic"
            . "</detEvento>"
            . "</infEvento>"
            . "</eventoMDFe>";
        //assinatura dos dados
        $signedMsg = $this->oCertificate->signXML($mensagem, 'infEvento');
        $cons = Strings::clearXml($signedMsg, true);
        //valida mensagem com xsd
        //no caso do evento nao tem xsd organizado, esta fragmentado
        //e por vezes incorreto por isso essa validação está desabilitada
        //if (! $this->zValidMessage($cons, 'mdfe', 'eventoMDFe', $version)) {
        //    $msg = 'Falha na validação. '.$this->error;
        //    throw new Exception\RuntimeException($msg);
        //}
        $body = "<mdfeDadosMsg xmlns=\"$this->urlNamespace\">$cons</mdfeDadosMsg>";
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
        $filename = "$chMDFe-$aliasEvento-eventoMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $lastMsg);
        $filename = "$chMDFe-$aliasEvento-retEventoMDFe.xml";
        $this->zGravaFile('mdfe', $tpAmb, $filename, $retorno);
        //tratar dados de retorno
        $this->aLastRetEvent = ReturnMDFe::readReturnSefaz($servico, $retorno);
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
                $aliasEvento = 'CancMDFe';
                $descEvento = 'Cancelamento';
                break;
            case '110112':
                //encerramento
                $aliasEvento = 'EncMDFe';
                $descEvento = 'Encerramento';
                break;
            case '110114':
                //inclusao do condutor
                $aliasEvento = 'EvIncCondut';
                $descEvento = 'Inclusao Condutor';
                break;
            default:
                $msg = "O código do tipo de evento informado não corresponde a "
                   . "nenhum evento estabelecido.";
                throw new Exception\RuntimeException($msg);
        }
        return array('alias' => $aliasEvento, 'desc' => $descEvento);
    }
}
