<?php

namespace NFe;

use Common\DateTime\DateTime;
use Common\Certificate\Pkcs12;
use Common\Soap\CurlSoap;
use Common\Soap\NatSoap;
use Common\Files\FilesFolders;

use Common\Exception\IOException;
use Common\Exception\InvalidArgumentException;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use Common\Exception\UnexpectedValueException;

class Tools
{
    /**
     * Tipo de ambiente produção
     */
    const AMBIENTE_PRODUCAO = 1;
    /**
     * Tipo de ambiente homologação
     */
    const AMBIENTE_HOMOLOGACAO = 2;
    /**
     * Sefaz Virtual Ambiente Nacional (SVAN), alguns estados utilizam esta Sefaz Virtual.
     */
    const SVAN = 'SVAN';
    /**
     * Sefaz Virtual Rio Grande do Sul (SVRS), alguns estados utilizam esta Sefaz Virtual.
     */
    const SVRS = 'SVRS';
    /**
     * Sefaz Virtual de Contingência Ambiente Nacional (SVC-AN)
     */
    const CONTINGENCIA_SVCAN = 'SVCAN';
    /**
     * Sefaz Virtual de Contingência Rio Grande do Sul (SVC-RS)
     */
    const CONTINGENCIA_SVCRS = 'SVCRS';
    
    /**
     * Sigla da Unidade da Federação
     * @var string
     */
    public $siglaUF = 'SP';
    
    /**
     * Time Zone Designator
     * @var string
     */
    public $timeZone = '-3.00';

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
     * @var timestamp
     */
    public $tsContingencia = '';
    
    public $error = '';
    
    public $pathXmlUrlFile = '';
    
 
    protected $oCertificate;
    protected $oSoap;
    
    protected $aConfig = array();
    protected $aDocFormat = array();
    protected $aProxyConf = array();
    protected $aMailConf = array();
    
    /**
     * urlPortal
     * Instância do WebService
     * @var string
     */
    private $urlPortal = 'http://www.portalfiscal.inf.br/nfe';


    public function __construct($configJson = '')
    {
        if (is_file($config)) {
            $configJson = file_get_contents($configJson);
        }
        //carrega os dados de configuração
        $this->aConfig = (array) json_decode($configJson);
        $this->aDocFormat = (array) $aConfig['aDocFormat'];
        $this->aProxyConf = (array) $aConfig['aProxyConf'];
        $this->aMailConf = (array) $aConfig['aMailConf'];
    
        /*
        $aConfig = array(
            'tpAmb' => '2',
            'pathXmlUrlFileNFe' => 'nfe_ws3_mod55.xml',
            'pathXmlUrlFileCTe' => 'cte_ws1.xml',
            'pathXmlUrlFileMDFe' => 'mdfe_ws1.xml',
            'pathXmlUrlFileCLe' => 'cle_ws1.xml',
            'pathNFeFiles' => '/var/www/nfe',
            'pathCTeFiles'=> '/var/www/cte',
            'pathMDFeFiles'=> '/var/www/mdfe',
            'pathCLeFiles'=> '/var/www/cle',
            'pathCertsFiles' => '/var/www/nfephp/certs/',
            'siteUrl' => 'http://localhost/nfephp',
            'schemesNFe' => 'PL_008c',
            'schemesCTe' => 'PL_CTE_104',
            'schemesMDFe' => 'MDFe_100',
            'schemesCLe' => 'CLe_100',
            'razaosocial' => 'Sua Empresa Ltda',
            'siglaUF'=> 'SP',
            'cnpj' => '9999999999',
            'certPfxName' => 'certificado.pfx',
            'certPassword' => 'senha',
            'certPhrase' => '',
            'aDocFormat' => $aDocFormat,
            'aMailConf' => $aMailConf,
            'aProxyConf' => $aProxyConf
         );
          
         $aDocFormat = array(
            'format'=>'P',
            'paper' => 'A4',
            'southpaw' => true,
            'pathLogoFile' => '/var/www/nfephp/images/logo.jpg',
            'logoPosition' => 'L',
            'font' => 'Times',
            'printer' => 'hpteste'
         );

         $aMailConf = array(
            'mailAuth' => true,
            'mailFrom' => 'nfe@suaempresa.com.br',
            'mailSmtp' => 'smtp.suaempresa.com.br',
            'mailUuser'=>'nfe@suaempresa.com.br',
            'mailPass'=>'senha',
            'mailProtocol'=>'',
            'mailPort'=>'587',
            'mailFromMail'=>'nfe@suaempresa.com.br',
            'mailFromName'=>'NFe',
            'mailReplyToMail'=> 'nfe@suaempresa.com.br',
            'mailReplyToName' => 'NFe',
            'mailImapHost' => 'imap.suaempresa.com.br',
            'mailImapPort' => '143',
            'mailImapSecurity'=> 'tls',
            'mailImapNcerts'=> 'novalidate-cert',
            'mailImapBox'=>'INBOX'
         );

         $aProxyConf = array(
            'proxyIp'=>'',
            'proxyPort'=>'',
            'proxyUser'=>'',
            'proxyPass'=>''
         ); 
         */
        //set o timezone
        $this->timeZone = DateTime::tzdBR($this->aConfig['siglaUF']);
        //carrega os certificados
        $this->oCertificate = new Pkcs12(
            $this->aConfig['pathCertsFiles'],
            $this->aConfig['cnpj']
        );
        if ($this->oCertificate->expireTimestamp == 0) {
            $this->error = 'Não existe certificado válido disponível. Atualize o Certificado.';
            return false;
        }
        $this->zLoadSoapClass();
    }
    
    public function __destruct()
    {
        
    }
    
    public function atualizaA1($certpfx = '', $senha = '')
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
     * @return void
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
            'TO'=>'SVCAN');
        
        $ctg = $ctgList[$siglaUF];
        
        if ($ctg == self::CONTINGENCIA_SVCAN) {
            $this->enableSVCAN = true;
            $this->enableSVCRS = false;
        } elseif ($ctg == self::CONTINGENCIA_SVCRS) {
            $this->enableSVCAN = false;
            $this->enableSVCRS = true;
        }
    }
    
    /**
     * desativaContingencia
     * Desliga opção de contingência 
     * 
     * @return void
     */
    public function desativaContingencia()
    {
        $this->enableSVCAN = false;
        $this->enableSVCRS = false;
        $this->tsContingencia = 0;
        $this->motivoContingencia = '';
    }
  
    /**
     * statusServico
     * Verifica o status do serviço da SEFAZ/SVC
     * NOTA : Este serviço será removido no futuro, segundo da Receita/SEFAZ devido
     * ao excesso de mau uso !!!
     *
     * $this->cStat = 107 - "Serviço em Operação"
     *        cStat = 108 - "Serviço Paralisado Momentaneamente (curto prazo)"
     *        cStat = 109 - "Serviço Paralisado sem Previsão"
     *        cStat = 113 - "SVC em processo de desativação. SVC será desabilitada 
     *                       para a SEFAZ-XX em dd/mm/aa às hh:mm horas"
     *        cStat = 114 - "SVC desabilitada pela SEFAZ Origem"
     *        
     * @name statusServico
     * @param  string $siglaUF sigla da unidade da Federação
     * @param  integer $tpAmb tipo de ambiente 1-produção e 2-homologação
     * @param  array $aRetorno parametro passado por referencia contendo a resposta da consulta em um array
     * @return mixed string XML do retorno do webservice, ou false se ocorreu algum erro
     */
    public function statusServico($siglaUF = '', $tpAmb = '', &$aRetorno = array())
    {
        //retorno da funçao
        $aRetorno = array(
            'bStat'=>false,
            'tpAmb'=>'',
            'verAplic'=>'',
            'cUF'=>'',
            'cStat'=>'',
            'tMed'=>'',
            'dhRetorno'=>'',
            'dhRecbto'=>'',
            'xMotivo'=>'',
            'xObs'=>'');
        
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
        //montagem dos dados da mensagem SOAP
        $body = "<nfeDadosMsg xmlns=\"$namespace\">"
            . "<consStatServ xmlns=\"$this->urlPortal\" versao=\"$version\">"
            . "<tpAmb>$tpAmb</tpAmb><cUF>$cUF</cUF>"
            . "<xServ>STATUS</xServ></consStatServ></nfeDadosMsg>";
        //consome o webservice e verifica o retorno do SOAP
        $retorno = $this->oSoap->send($urlservice, $namespace, $header, $body, $method);
        if (! $retorno) {
            $msg = $this->oSoap->error;
            throw new Exception\RuntimeException($msg);
        }
        $aRetorno = $this->zReadReturnSefaz($method, $retorno);
        return $retorno;
    }

    /**
     * zLoadServico
     * Monta o namespace e o cabecalho da comunicação SOAP
     * @param string $servico Identificação do Servico
     * @param array $aURL Dados das Urls do SEFAZ
     * @return void
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
        $cUFlist = array(
            'AC'=>'12',
            'AL'=>'27',
            'AM'=>'13',
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
            'TO'=>'17',
            'SVAN'=>'91');
   
        $cUF = $cUFlist[$siglaUF];
        if ($this->enableSVCAN) {
            $aURL = $this->zLoadSEFAZ($pathXmlUrlFile, $tpAmb, self::CONTINGENCIA_SVCAN);
        } elseif ($this->enableSVCRS) {
            $aURL = $this->zLoadSEFAZ($pathXmlUrlFile, $tpAmb, self::CONTINGENCIA_SVCRS);
        } else {
            $aURL = $this->zLoadSEFAZ($pathXmlUrlFile, $tpAmb, $siglaUF);
        }
        //recuperação da versão
        $version = $aURL[$service]['version'];
        //recuperação da url do serviço
        $urlservice = $aURL[$servico]['URL'];
        //recuperação do método
        $method = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $operation = $aURL[$servico]['operation'];
        $namespace = sprintf("%s/wsdl/%s", $this->urlPortal, $operation);
        //montagem do cabeçalho da comunicação SOAP
        $header = "<nfeCabecMsg "
                . "xmlns=\"$namespace\">"
                . "<cUF>$cUF</cUF>"
                . "<versaoDados>$version</versaoDados>"
                . "</nfeCabecMsg>";
    }
    
    /**
     * zLoadSEFAZ
     * Extrai o URL, nome do serviço e versão dos webservices das SEFAZ de
     * todos os Estados da Federação, a partir do arquivo XML de configurações,
     * onde este é estruturado para os modelos 55 (NF-e) e 65 (NFC-e) já que
     * os endereços dos webservices podem ser diferentes.
     *
     * @name zLoadSEFAZ
     * @param  string $tpAmb     Pode ser "2-homologacao" ou "1-producao"
     * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, SVRS, etc..)
     * @return mixed             false se houve erro ou array com os dados dos URLs da SEFAZ
     * @see /config/nfe_ws3_modXX.xml
     */
    protected function zLoadSEFAZ($pathXmlUrlFile = '', $tpAmb = '', $siglaUF = '')
    {
        if ($pathXmlUrlFile = '' || $tpAmb == '' || $siglaUF == '') {
            throw new Exception\InvalidArgumentException(
                "Arquivo $pathXmlUrlFile não encontrado."
            );
        }
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
        //valida e extrai a variável cUF da lista
        if (! isset($autorizadores[$siglaUF])) {
            throw new Exception\InvalidArgumentException(
                "UF \"$siglaUF\" nao encontrada na lista dos autorizadores."
            );
        }
        //variável de retorno do método
        $aUrl = array();
        //testa parametro tpAmb
        if ($tpAmb == self::AMBIENTE_PRODUCAO) {
            $sAmbiente = 'producao';
        } else {
            //força homologação em qualquer outra situação
            $tpAmb = self::AMBIENTE_HOMOLOGACAO;
            $sAmbiente = 'homologacao';
        }
        $alias = $autorizadores[$siglaUF];
        //verifica se deve habilitar SVAN ou SVRS (ambos por padrão iniciam desabilitados)
        if ($alias == self::SVAN) {
            $this->enableSVAN = true;
        } elseif ($alias == self::SVRS) {
            $this->enableSVRS = true;
        }
        //estabelece a expressão xpath de busca
        $xpathExpression = "/WS/UF[sigla='$alias']/$sAmbiente";
        //para cada "nó" no xml que atenda aos critérios estabelecidos
        foreach ($xmlWS->xpath($xpathExpression) as $gUF) {
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
        //verifica se existem outros serviços exclusivos para esse estado
        if ($alias == self::SVAN || $alias == self::SVRS) {
            //para cada "nó" no xml que atenda aos critérios estabelecidos
            foreach ($xmlWS->xpath($xpathExpression) as $gUF) {
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
        }
        return $aUrl;
    }
    
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
    
    private function zReadReturnSefaz($method, $xmlresp)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false; //elimina espaços em branco
        $dom->formatOutput = false;
        $dom->loadXML($xmlResp, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'nfeAutorizacaoLote':
                break;
            case 'nfeRetAutorizacaoLote':
                break;
            case 'consultaCadastro2':
                break;
            case 'nfeConsultaNF2':
                break;
            case 'nfeInutilizacaoNF2':
                break;
            case 'nfeStatusServicoNF2':
                return $this->zReadStatusServico($dom);
                break;
            case 'nfeRecepcaoEvento':
                break;
            case 'nfeDistDFeInteresse':
                break;
            case 'nfeConsultaNFDest':
                break;
            case 'nfeDownloadNF':
                break;
        }
    }
    
    private function zReadStatusServico($dom)
    {
         $retConsStatServ = $dom->getElementsByTagName('retConsStatServ')->item(0);
         $cStat = $retConsStatServ->getElementsByTagName('cStat')->item(0)->nodeValue;
         $verAplic = $retConsStatServ->getElementsByTagName('verAplic')->item(0)->nodeValue;
         $xMotivo = $retConsStatServ->getElementsByTagName('xMotivo')->item(0)->nodeValue;
         $cUF = $retConsStatServ->getElementsByTagName('cUF')->item(0)->nodeValue;
         $dhRecbto = $retConsStatServ->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
         $tMed = ! empty($retConsStatServ->getElementsByTagName('tMed')->item(0)->nodeValue) ?
                 $retConsStatServ->getElementsByTagName('tMed')->item(0)->nodeValue : '';
         
         $aResposta = array(
             'bStat' => true,
             'cStat' => $cStat,
             'verAplic' => $verAplic,
             'xMotivo' => $xMotivo,
             'dhRecbto' => $dhRecbto,
             'tMed' => $tMed,
             'cUF' => $cUF
         );
        /*
        * <?xml version="1.0" encoding="utf-8"?>
        * <soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        * <soap:Body>
        * <nfeStatusServicoNF2Result xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2">
        * <retConsStatServ versao="3.10" xmlns="http://www.portalfiscal.inf.br/nfe">
        * <tpAmb>1</tpAmb>
        * <verAplic>SVC_3.1.0</verAplic>
        * <cStat>114</cStat>
        * <xMotivo>SVC desabilitado pela SEFAZ-Origem SP</xMotivo>
        * <cUF>35</cUF>
        * <dhRecbto>2014-12-04T16:45:48-02:00</dhRecbto>
        * </retConsStatServ>
        * </nfeStatusServicoNF2Result>
        * </soap:Body>
        * </soap:Envelope>
        *
        * <?xml version="1.0" encoding="utf-8"?>
        * <soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        * <soapenv:Body>
        * <nfeStatusServicoNF2Result xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2">
        * <retConsStatServ versao="3.10" xmlns="http://www.portalfiscal.inf.br/nfe">
        * <tpAmb>1</tpAmb>
        * <verAplic>NFEPE_P_09.05.11.062</verAplic>
        * <cStat>107</cStat>
        * <xMotivo>Servi&#xE7;o em Opera&#xE7;&#xE3;o</xMotivo>
        * <cUF>26</cUF> 
        * <dhRecbto>2014-12-04T15:45:54-03:00</dhRecbto>
        * <tMed>1</tMed>
        * </retConsStatServ>
        * </nfeStatusServicoNF2Result>
        * </soapenv:Body>
        * </soapenv:Envelope> 
        */
        return $aResposta;
    }
}
