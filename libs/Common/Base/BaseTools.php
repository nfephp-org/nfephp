<?php
namespace Common\Base;

/**
 * Classe base das classes principais para a comunicação com a SEFAZ
 * 
 * @category   NFePHP
 * @package    NFePHP\Common\Base
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Certificate\Pkcs12;
use Common\DateTime\DateTime;
use Common\Dom\Dom;
use Common\Soap\CurlSoap;
use Common\Files;
use Common\Exception;

if (!defined('NFEPHP_ROOT')) {
    define('NFEPHP_ROOT', dirname(dirname(dirname(__FILE__))));
}

class BaseTools
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
     * Timestamp UNIX da data e hora de entrada em contingência
     * @var int
     */
    public $tsContingencia = '';
    
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
    protected $urlPortal = '';
    /**
     * urlcUF
     * @var string 
     */
    protected $urlcUF = '';
    /**
     * urlVersion
     * @var string 
     */
    protected $urlVersion = '';
    /**
     * urlService
     * @var string 
     */
    protected $urlService = '';
    /**
     * urlMethod
     * @var string
     */
    protected $urlMethod = '';
    /**
     * urlOperation
     * @var string 
     */
    protected $urlOperation = '';
    /**
     * urlNamespace
     * @var string 
     */
    protected $urlNamespace = '';
    /**
     * urlHeader
     * @var string 
     */
    protected $urlHeader = '';
    /**
     * modelo da NFe 55 ou 65
     * @var string
     */
    protected $modelo = '55';

    protected $cUFlist = array(
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
        'TO'=>'17',
        'SVAN' => '91'
    );
    
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
        if ($this->oCertificate->priKey == '') {
            //tentar carregar novo certificado
            $this->atualizaCertificado(
                $this->aConfig['pathCertsFiles'].$this->aConfig['certPfxName'],
                $this->aConfig['certPassword']
            );
            if ($this->oCertificate->expireTimestamp == 0) {
                $msg = 'Não existe certificado válido disponível. Atualize o Certificado.';
                throw new Exception\RuntimeException($msg);
            }
        }
        $this->zLoadSoapClass();
        //verifica se a contingência está ativada
        $pathContingencia = NFEPHP_ROOT.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'contingencia.json';
        if (is_file($pathContingencia)) {
            $contJson = file_get_contents($pathContingencia);
            if (! empty($contJson)) {
                 $aCont = (array) json_decode($contJson);
                 $this->motivoContingencia = $aCont['motivo'];
                 $this->tsContingencia = $aCont['ts'];
                 $this->enableSVCAN = $aCont['SVCAN'];
                 $this->enableSVCRS = $aCont['SVCRS'];
            }
        }
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
     * assinaDoc
     * @param string $xml
     * @param boolean $saveFile
     * @return string
     * @throws Exception\RuntimeException
     */
    public function assinaDoc($xml = '', $tipo = '', $tag = '', $saveFile = false)
    {
        if (is_file($xml)) {
            $xml = Files\FilesFolders::readFile($xml);
        }
        $sxml = $this->oCertificate->signXML($xml, $tag);
        $dom = new Dom();
        $dom->loadXMLString($sxml);
        $versao = $dom->getElementsByTagName($tag)->item(0)->getAttribute('versao');
        if (! $this->zValidMessage($sxml, $tipo, $versao)) {
            $msg = "Falha na validação do $tipo. ".$this->error;
            throw new Exception\RuntimeException($msg);
        }
        if ($saveFile) {
            $dom = new Dom();
            $dom->loadXMLString($sxml);
            $tpAmb = $dom->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $anomes = date(
                'Ym',
                DateTime::convertSefazTimeToTimestamp($dom->getElementsByTagName('dhEmi')->item(0)->nodeValue)
            );
            $chave = $dom->getChave($tag);
            $filename = "$chave-$tipo.xml";
            $this->zGravaFile($tipo, $tpAmb, $filename, $sxml, 'assinadas', $anomes);
        }
        return $sxml;
    }

    /**
     * Carrega a classe SOAP e os certificados
     */
    protected function zLoadSoapClass()
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
    protected function zLoadServico(
        $tipo,
        $service,
        $siglaUF,
        $tpAmb
    ) {
        if (empty($tipo) || empty($service) || empty($siglaUF)) {
            $this->urlVersion = '';
            $this->urlService = '';
            $this->urlMethod = '';
            $this->urlOperation = '';
            $this->urlNamespace = '';
            $this->urlHeader = '';
            return false;
        }
        $this->urlcUF = $this->zGetcUF($siglaUF);
        $pathXmlUrlFile = $this->zGetXmlUrlPath($tipo);
        
        if ($this->enableSVCAN) {
            $aURL = self::zLoadSEFAZ($pathXmlUrlFile, $tpAmb, 'SVCAN');
        } elseif ($this->enableSVCRS) {
            $aURL = self::zLoadSEFAZ($pathXmlUrlFile, $tpAmb, 'SVCRS');
        } else {
            $aURL = self::zLoadSEFAZ($pathXmlUrlFile, $tpAmb, $siglaUF, $tipo);
        }
        //recuperação da versão
        $this->urlVersion = $aURL[$service]['version'];
        //recuperação da url do serviço
        $this->urlService = $aURL[$service]['URL'];
        //recuperação do método
        $this->urlMethod = $aURL[$service]['method'];
        //montagem do namespace do serviço
        $this->urlOperation = $aURL[$service]['operation'];
        $this->urlNamespace = sprintf("%s/wsdl/%s", $this->urlPortal, $this->urlOperation);
        //montagem do cabeçalho da comunicação SOAP
        $this->urlHeader = $this->zMountHeader($tipo, $this->urlNamespace, $this->urlcUF, $this->urlVersion);
        return true;
    }
    
    /**
     * zGetXmlUrlPath
     * @param string $tipo
     * @return string
     */
    private function zGetXmlUrlPath($tipo)
    {
        $path = '';
        if ($tipo == 'nfe') {
            $path = $this->aConfig['pathXmlUrlFileNFe'];
            if ($this->modelo == '65') {
                $path = str_replace('55', '65', $path);
            } else {
                $path = str_replace('65', '55', $path);
            }
        } elseif ($tipo == 'cte') {
            $path = $this->aConfig['pathXmlUrlFileCTe'];
        } elseif ($tipo == 'mdfe') {
            $path = $this->aConfig['pathXmlUrlFileMDFe'];
        } elseif ($tipo == 'cle') {
            $path = $this->aConfig['pathXmlUrlFileCLe'];
        }
        
        $pathXmlUrlFile = NFEPHP_ROOT
            . DIRECTORY_SEPARATOR
            . 'config'
            . DIRECTORY_SEPARATOR
            . $path;
        
        return $pathXmlUrlFile;
    }
    
    /**
     * zMountHeader
     * @param string $tipo
     * @param string $namespace
     * @param string $cUF
     * @param string $version
     * @return string
     */
    private function zMountHeader($tipo, $namespace, $cUF, $version)
    {
        $header = '';
        if ($tipo == 'nfe') {
            $header = "<nfeCabecMsg "
                . "xmlns=\"$namespace\">"
                . "<cUF>$cUF</cUF>"
                . "<versaoDados>$version</versaoDados>"
                . "</nfeCabecMsg>";
        } elseif ($tipo == 'cte') {
            $header = "<cteCabecMsg "
                . "xmlns=\"$namespace\">"
                . "<cUF>$cUF</cUF>"
                . "<versaoDados>$version</versaoDados>"
                . "</cteCabecMsg>";
        } elseif ($tipo == 'mdfe') {
            $header = "<mdfeCabecMsg "
                . "xmlns=\"$namespace\">"
                . "<cUF>$cUF</cUF>"
                . "<versaoDados>$version</versaoDados>"
                . "</mdfeCabecMsg>";
        }
        return $header;
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
     * @param strign $tipo nfe, mdfe ou cte
     * @return mixed false se houve erro ou array com os dados dos URLs da SEFAZ
     * @internal param string $sUF Sigla da Unidade da Federação (ex. SP, RS, SVRS, etc..)
     * @see /config/nfe_ws3_modXX.xml
     */
    protected function zLoadSEFAZ($pathXmlUrlFile = '', $tpAmb = '2', $siglaUF = 'SP', $tipo = 'nfe')
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
        if ($tipo == 'mdfe') {
            $alias = 'RS';
        }
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
    protected function zExtractUrl($xmlWS, $aUrl = array(), $expression = '')
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
     * zGravaFile
     * Grava os dados no diretorio das NFe
     * @param string $tpAmb ambiente
     * @param string $filename nome do arquivo
     * @param string $data dados a serem salvos
     * @param string $subFolder 
     * @param string $anomes 
     * @throws Exception\RuntimeException
     */
    protected function zGravaFile(
        $tipo = '',
        $tpAmb = '2',
        $filename = '',
        $data = '',
        $subFolder = 'temporarias',
        $anomes = ''
    ) {
        if ($anomes == '') {
            $anomes = date('Ym');
        }
        $path = '';
        if ($tipo == 'nfe') {
            $path = $this->aConfig['pathNFeFiles'];
        } elseif ($tipo == 'cte') {
            $path = $this->aConfig['pathCTeFiles'];
        } elseif ($tipo == 'mdfe') {
            $path = $this->aConfig['pathMDFeFiles'];
        }
        $pathTemp = Files\FilesFolders::getFilePath($tpAmb, $path, $subFolder)
            . DIRECTORY_SEPARATOR.$anomes;
        if (! Files\FilesFolders::saveFile($pathTemp, $filename, $data)) {
            $msg = 'Falha na gravação no diretório. '.$pathTemp;
            throw new Exception\RuntimeException($msg);
        }
    }

    /**
     * zGetcUF
     * @param string $siglaUF
     * @return string numero cUF
     */
    protected function zGetcUF($siglaUF = '')
    {
        return $this->cUFlist[$siglaUF];
    }
    
    /**
     * zGetSigla
     * @param string $cUF
     * @return string
     */
    protected function zGetSigla($cUF = '')
    {
        return array_search($cUF, $this->cUFlist);
    }
}
