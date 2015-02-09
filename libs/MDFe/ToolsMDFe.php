<?php
namespace MDFe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\MDFe\Tools
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Base\BaseTools;
use Common\Certificate\Pkcs12;
use Common\DateTime\DateTime;
use Common\LotNumber\LotNumber;
use Common\Soap\CurlSoap;
use Common\Strings\Strings;
use Common\Files;
use Common\Exception;
use Common\Dom\Dom;
use Common\Dom\ReturnMDFe;
use DOMDocument;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class ToolsMDFe extends BaseTools
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
    protected $urlPortal = 'http://www.portalfiscal.inf.br/mdfe';
    /**
     * aLastRetEvent
     * @var array 
     */
    private $aLastRetEvent = array();
    
    
    public function printMDFe()
    {
        
    }
    
    public function mailMDFe()
    {
        
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
        $this->zLoadServico(
            'mdfe',
            'MDFeRetRecepcao',
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
        $aRetorno = ReturnMDFe::readReturnSefaz($this->urlMethod, $retorno);
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
        $this->zLoadServico(
            'mdfe',
            'MDFeConsulta',
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
        $aRetorno = ReturnMDFe::readReturnSefaz($this->urlMethod, $retorno);
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
        $this->zLoadServico(
            'mdfe',
            'MDFeStatusServico',
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
        $aRetorno = ReturnMDFe::readReturnSefaz($this->urlMethod, $retorno);
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
        $this->zLoadServico(
            'mdfe',
            'MDFeConsNaoEnc',
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
        $aRetorno = ReturnMDFe::readReturnSefaz($this->urlMethod, $retorno);
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
        $this->zLoadServico(
            'mdfe',
            'MDFeRecepcaoEvento',
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
        $this->aLastRetEvent = ReturnMDFe::readReturnSefaz($this->urlMethod, $retorno);
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
