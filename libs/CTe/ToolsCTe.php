<?php

namespace NFePHP\CTe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\CTe\ToolsCTe
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
use \DOMDocument;
use NFePHP\CTe\ReturnCTe;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class ToolsCTe extends BaseTools
{
    /**
     * urlPortal
     * Instância do WebService
     * @var string
     */
    protected $urlPortal = 'http://www.portalfiscal.inf.br/cte';

    public function printCTe()
    {
        
    }
    
    public function mailCTe()
    {
        
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
        return $this->assinaDoc($xml, 'CTe', 'infCte', $saveFile);
    }


    public function sefazEnvia(
        $aXml,
        $tpAmb = '2',
        $idLote = '',
        &$aRetorno = array(),
        $indSinc = 0,
        $compactarZip = false
    ) {
        $this->modelo = '65';
        $sxml = $aXml;
        if (empty($aXml)) {
            $msg = "Pelo menos uma NFe deve ser informada.";
            throw new Exception\InvalidArgumentException($msg);
        }
        if (is_array($aXml)) {
            if (count($aXml) > 1) {
                //multiplas nfes, não pode ser sincrono
                $indSinc = 0;
            }
            $sxml = implode("", $sxml);
        }
        $sxml = preg_replace("/<\?xml.*\?>/", "", $sxml);
        $siglaUF = $this->aConfig['siglaUF'];
        $cUF = $this->getcUF($siglaUF);

        if ($tpAmb == '') {
            $tpAmb = $this->aConfig['tpAmb'];
        }
        if ($idLote == '') {
            $idLote = LotNumber::geraNumLote(15);
        }
        //carrega serviço
        $servico = 'CteRecepcao';
        $this->zLoadServico(
            'cte',
            $servico,
            $siglaUF,
            $tpAmb
        );

        if ($this->urlService == '') {
            $msg = "O envio de lote não está disponível na SEFAZ $siglaUF!!!";
            throw new Exception\RuntimeException($msg);
        }

        // Montagem do cabeçalho da comunicação SOAP
        $cabec = "<cteCabecMsg xmlns=\"$this->urlNamespace\">"
            . "<cUF> $cUF </cUF>"
            . "<versaoDados>$this->urlVersion</versaoDados>"
            . "</cteCabecMsg>";

        // Montagem dos dados da mensagem SOAP
        $dados = "<cteDadosMsg xmlns=\"$this->urlNamespace\">"
            . "<enviCTe xmlns=\"$this->urlPortal\" versao=\"$this->urlVersion\">"
            . "<idLote>$idLote</idLote>"
            . "$sxml"
            . "</enviCTe>"
            . "</cteDadosMsg>";

        // Envia dados via SOAP
        $retorno = $this->oSoap->send($this->urlService, $this->urlNamespace, $cabec, $dados, $this->urlMethod);

//        if ($compactarZip) {
//            $gzdata = base64_encode(gzencode($cons, 9, FORCE_GZIP));
//            $body = "<cteDadosMsgZip xmlns=\"$this->urlNamespace\">$gzdata</cteDadosMsgZip>";
//            $method = $this->urlMethod."Zip";
//        }


        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva mensagens
        $filename = "$idLote-enviCTe.xml";
        $this->zGravaFile('cte', $tpAmb, $filename, $lastMsg);
        $filename = "$idLote-retEnviCTe.xml";
        $this->zGravaFile('cte', $tpAmb, $filename, $retorno);
        //tratar dados de retorno

        $aRetorno = ReturnCTe::readReturnSefaz($servico, $retorno);
        //caso o envio seja recebido com sucesso mover a NFe da pasta
        //das assinadas para a pasta das enviadas
        return (string) $retorno;

    }
    
    public function sefazConsultaRecibo()
    {
        
    }
    
    public function sefazConsultaChave()
    {
        
    }
    
    public function sefazStatus()
    {
        
    }
    
    public function sefazCancela()
    {
        
    }
}
