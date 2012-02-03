<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou 
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da 
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>. 
 *
 * Está atualizada para :
 *      PHP 5.3
 *      Versão 2 dos webservices da SEFAZ com comunicação via SOAP 1.2
 *      e conforme Manual de Integração Versão 4.0.1 NT2009.006 Dezembro 2009
 *
 * Atenção: Esta classe não mantêm a compatibilidade com a versão 1.10 da SEFAZ !!!
 *
 * @package   NFePHP
 * @name      ToolsNFePHP
 * @version   2.80
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Antonio Neykson Turbano de Souza <neykson at gmail dot com>
 *              Bernardo Silva <bernardo at datamex dot com dot br>
 *              Bruno Bastos <brunomauro at gmail dot com>
 *              Bruno Lima <brunofileh at gmail.com>
 *              Daniel Viana <daniellista at gmail dot com>
 *              Diego Mosela <diego dot caicai at gmail dot com>
 *              Edilson Carlos Belluomini <edilson at maxihelp dot com dot br>
 *              Eduardo Pacheco <eduardo at onlyone dot com dot br>
 *				Fabio A. Silva <binhoouropreto at gmail dot com>
 *              Fabricio Veiga <fabriciostuff at gmail dot com>              
 *              Felipe Bonato <montanhats at gmail dot com>
 *              Fernando Mertins <fernando dot mertins at gmail dot com>
 *              Gilmar de Paula Fiocca <gilmar at tecnixinfo dot com dot br>
 *              Giovani Paseto <giovaniw2 at gmail dot com>
 *              Giuliano <giusoft at hotmail dot com>
 *              Glauber <glaubercini at gmail dot com>
 *              Guilherme Filippo <guilherme at macromind dot com dot br>
 *              Jorge Luiz Rodrigues Tomé <jlrodriguestome at hotmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Odair Jose Santos Junior <odairsantosjunior at gmail dot com>
 *              Paulo Gabriel Coghi <paulocoghi at gmail dot com>
 *              Paulo Henrique Demori <phdemori at hotmail dot com>
 *              Vini Lazev <vinilazev at gmail dot com>
 *              Walber da Silva Sales <eng dot walber at gmail dot com>
 *
 */

//define o caminho base da instalação do sistema
define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
//carrega a classe de conversões de txt para xml e vice-versa
require_once('ConvertNFePHP.class.php');
//carrega a classe de impressao da DANFE
require_once('DanfeNFePHP.class.php');
//carrega a classe de envio de emails
require_once('MailNFePHP.class.php');

/**
 * Classe principal "CORE class"
 */
class ToolsNFePHP {

    // propriedades da classe
    /**
    * raizDir
    * Diretorio raiz da API
    * @var string
    */
    public $raizDir='';
    /**
     * arqDir
     * Diretorio raiz de armazenamento das notas
     * @var string
     */
    public $arqDir='';
    /**
     * pdfDir
     * Diretorio onde são armazenados temporariamente as notas em pdf
     * @var string
     */
    public $pdfDir ='';
    /**
     * entDir
     * Diretorio onde são armazenados temporariamente as notas criadas (em txt ou xml)
     * @var string
     */
    public $entDir='';
    /**
     * valDir
     * Diretorio onde são armazenados temporariamente as notas já validadas pela API
     * @var string
     */
    public $valDir='';
    /**
     * repDir
     * Diretorio onde são armazenados as notas reprovadas na validação da API
     * @var string
     */
    public $repDir='';
    /**
     * assDir
     * Diretorio onde são armazenados temporariamente as notas já assinadas
     * @var string
     */
    public $assDir='';
    /**
     * envDir
     * Diretorio onde são armazenados temporariamente as notas enviadas
     * @var string
     */
    public $envDir='';
    /**
     * aprDir
     * Diretorio onde são armazenados temporariamente as notas aprovadas
     * @var string
     */
    public $aprDir='';
    /**
     * denDir
     * Diretorio onde são armazenados as notas denegadas
     * @var string
     */
    public $denDir='';
    /**
     * rejDir
     * Diretorio onde são armazenados os retornos e as notas com as rejeitadas após o envio do lote
     * @var string
     */
    public $rejDir='';
    /**
     * canDir
     * Diretorio onde são armazenados os pedidos e respostas de cancelamento
     * @var string
     */
    public $canDir='';
    /**
     * inuDir
     * Diretorio onde são armazenados os pedidos de inutilização de numeros de notas
     * @var string
     */
    public $inuDir='';
    /**
     * tempDir
     * Diretorio de arquivos temporarios ou não significativos para a operação do sistema
     * @var string
     */
    public $temDir='';
    /**
     * recDir
     * Diretorio de arquivos temporarios das NFe recebidas de terceiros
     * @var string
     */
    public $recDir='';
    /**
     * conDir
     * Diretorio de arquivos das notas recebidas de terceiros e já validadas
     * @var string
     */
    public $conDir='';
    /**
     * libsDir
     * Diretorios onde estão as bibliotecas e outras classes
     * @var string
     */
    public $libsDir='';
    /**
     * certsDir
     * Diretorio onde estão os certificados
     * @var string
     */
    public $certsDir='';
    /**
     * imgDir
     * Diretorios com a imagens, fortos, logos, etc..
     * @var string
     */
    public $imgDir='';
    /**
     * xsdDir
     * diretorio que contem os esquemas de validação
     * estes esquemas devem ser mantidos atualizados
     * @var string
     */
    public $xsdDir='';
    /**
     * xmlURLfile
     * Arquivo xml com as URL do SEFAZ de todos dos Estados
     * @var string
     */
    public $xmlURLfile='';
    /**
     * enableSCAN
     * Habilita o acesso ao serviço SCAN ao invés do webservice estadual
     * @var boolean
     */
    public $enableSCAN=false;
    /**
     * enableSVAN
     * Indica o acesso ao serviço SVAN
     * @var boolean
     */
    public $enableSVAN=false;
    /**
     * modSOAP
     * Indica o metodo SOAP a usar 1-SOAP Nativo ou 2-cURL
     * @var string
     */
    public $modSOAP='2';
    /**
     * tpAmb
     * Tipo de ambiente 1-produção 2-homologação
     * @var string
     */
    private $tpAmb='';
    /**
     * schemeVer
     * String com o nome do subdiretorio onde se encontram os schemas 
     * atenção é case sensitive
     * @var string
     */
    private $schemeVer;
    /**
     * aProxy
     * Matriz com as informações sobre o proxy da rede para uso pelo SOAP
     * @var array IP PORT USER PASS
     */
    public $aProxy='';
    /**
     * aMail
     * Matiz com os dados para envio de emails
     * FROM  HOST USER PASS
     * @var array 
     */
    public $aMail='';
    /**
     * keyPass
     * Senha de acesso a chave privada
     * @var string
     */
    private $keyPass='';
    /**
     * passPhrase
     * palavra passe para acessar o certificado (normalmente não usada)
     * @var string
     */
    private $passPhrase='';
    /**
     * certName
     * Nome do certificado digital
     * @var string
     */
    private $certName='';
    /**
     * certMonthsToExpire
     * Meses que faltam para o certificado expirar
     * @var integer
     */
    public $certMonthsToExpire=0;
    /**
     * certDaysToExpire
     * Dias que faltam para o certificado expirar
     * @var integer
     */
    public $certDaysToExpire=0;    
    /**
     * priKEY
     * Path completo para a chave privada em formato pem
     * @var string 
     */
    private $priKEY='';
    /**
     * pubKEY
     * Path completo para a chave public em formato pem
     * @var string 
     */
    private $pubKEY='';
    /**
     * certKEY
     * Path completo para o certificado (chave privada e publica) em formato pem
     * @var string 
     */
    private $certKEY='';
    /**
     * empName
     * Razão social da Empresa
     * @var string
     */
    private $empName='';
    /**
     * cnpj
     * CNPJ do emitente
     * @var string
     */
    private $cnpj='';
    /**
     * cUF
     * Código da unidade da Federação IBGE
     * @var string
     */
    private $cUF='';
    /**
     * UF
     * Sigla da Unidade da Federação
     * @var string
     */
    private $UF='';
    /**
     * timeZone
     * Zona de tempo GMT
     */
    private $timeZone = '-03:00';
    /**
     * logopath
     * Variável que contem o path completo para a logo a ser impressa na DANFE
     * @var string $logopath
     */
    public $danfelogopath = '';
    /**
     * danfelogopos
     * Estabelece a posição do logo no DANFE
     * L-Esquerda C-Centro e R-Direita
     * @var string
     */
    public $danfelogopos = 'C';
    /**
     * danfeform
     * Estabelece o formato do DANFE
     * P-Retrato L-Paisagem (NOTA: somente o formato P é funcional, por ora)
     * @var string P-retrato ou L-Paisagem
     */
    public $danfeform = 'P';
    /**
     * danfepaper
     * Estabelece o tamanho da página
     * NOTA: somente o A4 pode ser utilizado de acordo com a ISO
     * @var string
     */
    public $danfepaper = 'A4';
    /**
     * danfecanhoto
     * Estabelece se o canhoto será impresso ou não
     * @var boolean
     */
    public $danfecanhoto = true;
    /**
     * danfefont
     * Estabelece a fonte padrão a ser utilizada no DANFE
     * de acordo com o Manual da SEFAZ usar somente Times
     * @var string
     */
    public $danfefont = 'Times';
   /**
     * danfeprinter
     * Estabelece a printer padrão a ser utilizada na impressão da DANFE
     * @var string
     */
    public $danfeprinter = '';
    /**
     * anoMes
     * Variável que contem o ano com 4 digitos e o mes com 2 digitos
     * Ex. 201003
     * @var string
     */
    private $anoMes='';
    /**
     * aURL
     * Array com as url dos webservices
     * @var array
     */
    public $aURL='';
    /**
     * aCabec
     * @var array
     */
    public $aCabec='';
    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg='';
    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus=false;
    /**
     * URLbase
     * Base da API
     * @var string
     */
    public $URLbase = '';
    /**
     * soapDebug
     * Mensagens de debug da comunicação SOAP
     * @var string
     */
    public $soapDebug='';
    /**
     * classDebug
     * Mensagens de debug da classe
     * @var string
     */
    public $classDebug='';
    /**
     * URLxsi
     * Instãncia do WebService
     * @var string
     */
    private $URLxsi='http://www.w3.org/2001/XMLSchema-instance';
    /**
     * URLxsd
     * Instância do WebService
     * @var string
     */
    private $URLxsd='http://www.w3.org/2001/XMLSchema';
    /**
     * URLnfe
     * Instância do WebService
     * @var string
     */
    private $URLnfe='http://www.portalfiscal.inf.br/nfe';
    /**
     * URLdsig
     * Instância do WebService
     * @var string
     */
    private $URLdsig='http://www.w3.org/2000/09/xmldsig#';
    /**
     * URLCanonMeth
     * Instância do WebService
     * @var string
     */
    private $URLCanonMeth='http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * URLSigMeth
     * Instância do WebService
     * @var string
     */
    private $URLSigMeth='http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    /**
     * URLTransfMeth_1
     * Instância do WebService
     * @var string
     */
    private $URLTransfMeth_1='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    /**
     * URLTransfMeth_2
     * Instância do WebService
     * @var string
     */
    private $URLTransfMeth_2='http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * URLDigestMeth
     * Instância do WebService
     * @var string
     */
    private $URLDigestMeth='http://www.w3.org/2000/09/xmldsig#sha1';
    /**
     * URLPortal
     * Instância do WebService
     * @var string
     */
    private $URLPortal='http://www.portalfiscal.inf.br/nfe';
    /**
     * aliaslist
     * Lista dos aliases para os estados que usam o SEFAZ VIRTUAL
     * @var array
     */
    private $aliaslist = array('AC'=>'SVRS',
                               'AL'=>'SVRS',
                               'AM'=>'AM',
                               'AP'=>'SVRS',
                               'BA'=>'BA',
                               'CE'=>'CE',
                               'DF'=>'SVRS',
                               'ES'=>'SVAN',
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
                               'RN'=>'SVAN',
                               'RO'=>'SVRS',
                               'RR'=>'SVRS',
                               'RS'=>'RS',
                               'SC'=>'SVRS',
                               'SE'=>'SVRS',
                               'SP'=>'SP',
                               'TO'=>'SVRS',
                               'SCAN'=>'SCAN');
    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $cUFlist = array('AC'=>'12',
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
                             'TO'=>'17');
    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $UFList=array('11'=>'RO',
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
                          '53'=>'DF');
 
    /**
     * __construct
     * Método construtor da classe
     * Este método utiliza o arquivo de configuração localizado no diretorio config
     * para montar os diretórios e várias propriedades internas da classe, permitindo
     * automatizar melhor o processo de comunicação com o SEFAZ.
     * 
     * Este metodo pode estabelecer as configurações a partir do arquivo config.php ou 
     * através de um array passado na instanciação da classe.
     * 
     * @version 2.13
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param   array 
     * @return  boolean true sucesso false Erro
     */
    function __construct($aConfig='') {
        //obtem o path da biblioteca
        $this->raizDir = dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR;
        //verifica se foi passado uma matriz de configuração na inicialização da classe
        if(is_array($aConfig)) {
            $this->tpAmb=$aConfig['ambiente'];
            $this->empName=$aConfig['empresa'];
            $this->UF=$aConfig['UF'];
            $this->cUF=$this->cUFlist[$aConfig['UF']];
            $this->cnpj=$aConfig['cnpj'];
            $this->certName=$aConfig['certName'];
            $this->keyPass=$aConfig['keyPass'];
            $this->passPhrase=$aConfig['passPhrase'];
            $this->arqDir = $aConfig['arquivosDir'];
            $this->xmlURLfile = $aConfig['arquivoURLxml'];
            $this->URLbase = $aConfig['baseurl'];
            $this->danfelogopath = $aConfig['danfeLogo'];
            $this->danfelogopos = $aConfig['danfeLogoPos'];
            $this->danfeform = $aConfig['danfeFormato'];
            $this->danfepaper = $aConfig['danfePapel'];
            $this->danfecanhoto = $aConfig['danfeCanhoto'];
            $this->danfefont = $aConfig['danfeFonte'];
            $this->danfeprinter = $aConfig['danfePrinter'];
            $this->schemeVer = $aConfig['schemes'];
            if ($aConfig['proxyIP'] != ''){
                $this->aProxy = array('IP'=>$aConfig['proxyIP'],'PORT'=>$aConfig['proxyPORT'],'USER'=>$aConfig['proxyUSER'],'PASS'=>$aConfig['proxyPASS']);
            }
            if ($aConfig['mailFROM'] != ''){
                $this->aMAIL = array('mailFROM'=>$aConfig['mailFROM'],'mailHOST'=>$aConfig['mailHOST'],'mailUSER'=>$aConfig['mailUSER'],'mailPASS'=>$aConfig['mailPASS'],'mailPROTOCOL'=>$aConfig['mailPROTOCOL'],'mailFROMmail'=>$aConfig['mailFROMmail'],'mailFROMname'=>$aConfig['mailFROMname'],'mailREPLYTOmail'=>$aConfig['mailREPLYTOmail'],'mailREPLYTOname'=>$aConfig['mailREPLYTOname']);
            }        } else {
            //testa a existencia do arquivo de configuração
            if ( is_file($this->raizDir . 'config' . DIRECTORY_SEPARATOR . 'config.php') ){
                //carrega o arquivo de configuração
                include($this->raizDir . 'config' . DIRECTORY_SEPARATOR . 'config.php');
                // carrega propriedades da classe com os dados de configuração
                // a sring $sAmb será utilizada para a construção dos diretorios
                // dos arquivos de operação do sistema
                $this->tpAmb=$ambiente;
                //carrega as propriedades da classe com as configurações
                $this->empName=$empresa;
                $this->UF=$UF;
                $this->cUF=$this->cUFlist[$UF];
                $this->cnpj=$cnpj;
                $this->certName=$certName;
                $this->keyPass=$keyPass;
                $this->passPhrase=$passPhrase;
                $this->arqDir = $arquivosDir;
                $this->xmlURLfile = $arquivoURLxml;
                $this->URLbase = $baseurl;
                $this->danfelogopath = $danfeLogo;
                $this->danfelogopos = $danfeLogoPos;
                $this->danfeform = $danfeFormato;
                $this->danfepaper = $danfePapel;
                $this->danfecanhoto = $danfeCanhoto;
                $this->danfefont = $danfeFonte;
                $this->danfeprinter = $danfePrinter;
                $this->schemeVer = $schemes;
                if ($proxyIP != ''){
                    $this->aProxy = array('IP'=>$proxyIP,'PORT'=>$proxyPORT,'USER'=>$proxyUSER,'PASS'=>$proxyPASS);
                }
                if ($mailFROM != ''){
                    $this->aMail = array('mailFROM'=>$mailFROM,'mailHOST'=>$mailHOST,'mailUSER'=>$mailUSER,'mailPASS'=>$mailPASS,'mailPROTOCOL'=>$mailPROTOCOL,'mailFROMmail'=>$mailFROMmail,'mailFROMname'=>$mailFROMname,'mailREPLYTOmail'=>$mailREPLYTOmail,'mailREPLYTOname'=>$mailREPLYTOname);
                }
            } else {
                // caso não exista arquivo de configuração retorna erro
                $this->errMsg = "Não foi localizado o arquivo de configuração.\n";
                $this->errStatus = true;
                return false;
            }
        }
        //estabelece o ambiente
        $sAmb = ($this->tpAmb == 2) ? 'homologacao' : 'producao'; 
        //carrega propriedade com ano e mes ex. 200911
        $this->anoMes = date('Ym');
        //carrega o caminho para os schemas
        $this->xsdDir = $this->raizDir . 'schemes'. DIRECTORY_SEPARATOR;
        //carrega o caminho para os certificados
        $this->certsDir =  $this->raizDir . 'certs'. DIRECTORY_SEPARATOR;
        //carrega o caminho para as imegens
        $this->imgDir =  $this->raizDir . 'images'. DIRECTORY_SEPARATOR;
        //verifica o ultimo caracter da variável $arqDir
        // se não for um DIRECTORY_SEPARATOR então colocar um
        if (substr($this->arqDir, -1, 1) != DIRECTORY_SEPARATOR){
            $this->arqDir .= DIRECTORY_SEPARATOR;
        }
        // monta a estrutura de diretorios utilizados na manipulação das NFe
        $this->entDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'entradas' . DIRECTORY_SEPARATOR;
        $this->assDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'assinadas' . DIRECTORY_SEPARATOR;        
        $this->valDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'validadas' . DIRECTORY_SEPARATOR;
        $this->rejDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'rejeitadas' . DIRECTORY_SEPARATOR;
        $this->envDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR;
        $this->aprDir=$this->envDir . 'aprovadas' . DIRECTORY_SEPARATOR;
        $this->denDir=$this->envDir . 'denegadas' . DIRECTORY_SEPARATOR;
        $this->repDir=$this->envDir . 'reprovadas' . DIRECTORY_SEPARATOR;
        $this->canDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'canceladas' . DIRECTORY_SEPARATOR;
        $this->inuDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'inutilizadas' . DIRECTORY_SEPARATOR;
        $this->temDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'temporarias' . DIRECTORY_SEPARATOR;
        $this->recDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'recebidas' . DIRECTORY_SEPARATOR;
        $this->conDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'consultadas' . DIRECTORY_SEPARATOR;
        $this->pdfDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
        //monta a arvore de diretórios necessária e estabelece permissões de acesso
        if ( !is_dir($this->arqDir) ){
            mkdir($this->arqDir, 0777);
        }
        if ( !is_dir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb) ){
            mkdir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb, 0777);
        }
        if ( !is_dir($this->entDir) ){
            mkdir($this->entDir, 0777);
        }
        if ( !is_dir($this->assDir) ){
            mkdir($this->assDir, 0777);
        }
        if ( !is_dir($this->valDir) ){
            mkdir($this->valDir, 0777);
        }
        if ( !is_dir($this->rejDir) ){
            mkdir($this->rejDir, 0777);
        }
        if ( !is_dir($this->envDir) ){
            mkdir($this->envDir, 0777);
        }
        if ( !is_dir($this->aprDir) ){
            mkdir($this->aprDir, 0777);
        }
        if ( !is_dir($this->denDir) ){
            mkdir($this->denDir, 0777);
        }
        if ( !is_dir($this->repDir) ){
            mkdir($this->repDir, 0777);
        }
        if ( !is_dir($this->canDir) ){
            mkdir($this->canDir, 0777);
        }
        if ( !is_dir($this->inuDir) ){
            mkdir($this->inuDir, 0777);
        }
        if ( !is_dir($this->temDir) ){
            mkdir($this->temDir, 0777);
        }
        if ( !is_dir($this->recDir) ){
            mkdir($this->recDir, 0777);
        }
        if ( !is_dir($this->conDir) ){
            mkdir($this->conDir, 0777);
        }
        if ( !is_dir($this->pdfDir) ){
            mkdir($this->pdfDir, 0777);
        }
        //carregar uma matriz com os dados para acesso aos WebServices SEFAZ
        $this->aURL = $this->loadSEFAZ($this->raizDir . 'config'. DIRECTORY_SEPARATOR . $this->xmlURLfile,$this->tpAmb,$this->UF);
        //se houver erro no carregamento dos certificados passe para erro
        if ( !$retorno = $this->__loadCerts() ) {
            $this->errStatus = true;
        }
        //corrigir o timeZone
        if ($this->UF == 'AC' ||
            $this->UF == 'AM' ||   
            $this->UF == 'MT' ||
            $this->UF == 'MS' ||
            $this->UF == 'RO' ||
            $this->UF == 'RR' ){
            $this->timeZone = '-04:00';
        }
        return true;
    } //fim __construct

    /**
     * autoTXTtoXML
     * Método para converter todas as nf em formato txt para o formato xml
     * localizadas na pasta "entradas". Os arquivos txt apoś terem sido
     * convertidos com sucesso são removidos da pasta.
     * Os arquivos txt devem ser nomeados como "<qualquer coisa>-nfe.txt"
     * @version 2.02
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param none
     * @return boolean true sucesso false Erro
     */
    public function autoTXTtoXML(){
        //varre pasta "entradas" a procura de NFes em txt
        $aName = $this->listDir($this->entDir,'*-nfe.txt',false);
        // se foi retornado algum arquivo
        $totTXT = count($aName);
        if ( $totTXT > 0){
            for ( $x=0; $x < $totTXT; $x++ ) {
                //carrega nfe em txt para converter em xml
                $filename = $this->entDir.$aName[$x];
                $newname = $this->temDir.$aName[$x];
                //instancia a classe de conversão
                $oXML = new ConvertNFePHP();
                //convere o arquivo
                $xml = $oXML->nfetxt2xml($filename);
                //salvar o xml
                $xmlname = $this->entDir.$oXML->chave.'-nfe.xml';
                if ( !file_put_contents($xmlname, $xml) ){
                    $this->errStatus = true;
                    $this->errMsg .= "FALHA na gravação da NFe em xml.\n";
                    return false;
                } else {
                    //mover o txt para pasta temp
                    rename($filename,$newname);
                } //fim do teste de gravação
            } // fim do for
        } //fim do teste de contagem
        return true;
    } //fim autoTXTtoXML

    /**
     * autoSignNFe
     * Método para assinatura em lote das NFe em XML
     * Este método verifica todas as NFe existentes na pasta de ENTRADAS e as assina
     * após a assinatura ser feita com sucesso o arquivo XML assinado é movido para a pasta
     * ASSINADAS.
     * IMPORTANTE : Em ambiente Linux manter os nomes dos arquivos e terminações em LowerCase.
     *
     * @version 2.11
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  none
     * @return boolean true sucesso false Erro
     */
    public function autoSignNFe() {
        //varre pasta "entradas" a procura de NFes
        $aName = $this->listDir($this->entDir,'*-nfe.xml',false);
        // se foi retornado algum arquivo
        $totXML = count($aName);
        if ( $totXML > 0){
            for ( $x=0; $x < $totXML; $x++ ) {
                //carrega nfe para assinar em uma strig
                $filename = $this->entDir.$aName[$x];
                //mantenha desse jeito mesmo com um unico =
                //a atribuição da variavel e o teste são simultâneos
                if ( $nfefile = file_get_contents($filename) ) {
                    //assinador usando somente o PHP da classe classNFe
                    //mantenha desse jeito mesmo com um unico =
                    //a atribuição da variavel e o teste são simultâneos
                    if ( $signn = $this->signXML($nfefile, 'infNFe') ) {
                        //xml retornado gravar
                        $file = $this->assDir . $aName[$x];
                        if ( !file_put_contents($file, $signn) ) {
                            $this->errStatus = true;
                            return false;
                        } else {
                            unlink($filename);
                        } //fim do teste de gravação
                    } //fim do teste da assinatura
                } //fim do teste de leitura
            } //fim do for
        } //fim do teste de contagem
        return true;
    } //fim autoSignNFe

    /**
     * autoValidNFe
     * Método validador em lote das NFe em XML já assinadas.
     * As NFe são validadas somente após que a TAG assinatura seja postada no XML, caso contrario
     * gerará um erro.
     *
     * As NFes, em principio podem ser assinadas sem grande perda de performance do sistema,
     * desde que o numero de NFe geradas seja relativamente pequeno.
     * Caso o numero seja muito grande (acima de 50 NFe de cada por minuto) talvez seja
     * interessante fazer alterações no sistema para incluir a TAG de assinatura em branco
     * e validar antes de assinar.
     *
     * Este método verifica todas as NFe que existem na pasta de ASSINADAS e processa a validação
     * com o shema XSD. Caso a NFe seja valida será movida para a pasta VALIDADAS, caso contrario
     * será movida para a pasta REPROVADAS.
     *
     * @version 2.02
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  none
     * @return boolean true sucesso false Erro
     */
    public function autoValidNFe() {
        //varre pasta "assinadas"
        $aName = $this->listDir($this->assDir,'*-nfe.xml',false);
        // se foi retornado algum arquivo
        $totXML = count($aName);
        if ( $totXML > 0 ){
            for ( $x=0; $x < $totXML; $x++ ) {
                //carrega nfe para validar em uma string
                $filename = $this->assDir.$aName[$x];
                if ( $nfefile = file_get_contents($filename) ) {
                    //validar
                    //como os arquivos xsd são atualizados e tem sua verção alterada
                    //devemos burcar este arquivo da versão correta
                    //para isso temos que obter o numero da versão na própria nfe
                    $xmldoc = new DOMDocument();
                    $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
                    $xmldoc->formatOutput = false;
                    $xmldoc->loadXML($nfefile,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                    $root = $xmldoc->documentElement;
                    //extrair a tag com o numero da versão da NFe
                    $node = $xmldoc->getElementsByTagName('infNFe')->item(0);
                    //obtem a versão do layout da NFe
                    $ver = trim($node->getAttribute("versao"));
                    //buscar o nome do scheme
                    $filexsd = $this->listDir($this->xsdDir . $this->schemeVer. DIRECTORY_SEPARATOR,'nfe_v*.xsd',true);
                    if (!$filexsd[0]) {
                        $this->errMsg = "Erro na localização do shema xsd.\n";
                        $this->errStatus = true;
                        return false;
                    }
                    $aRet = $this->validXML($nfefile,$filexsd[0]);
                    if ( $aRet['status'] ) {
                        // validado => transferir para pasta validados
                        $file = $this->valDir . $aName[$x];
                        if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = false;
                        } else {
                            unlink($filename);
                        }
                    } else {
                        //NFe com erros transferir de pasta rejeitadas
                        $file = $this->rejDir . $aName[$x];
                        $this->errStatus = true;
                        $this->errMsg .= $aName[$x].' ... '.$aRet['error']."\n";
                       if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = true;
                        } else {
                            unlink($filename);
                        } //fim teste de gravação
                        return false;
                    } //fim validação
                } //fim teste de leitura
            }//fim for
        } // fim do teste de contagem
        return true;
    } //fim autoValidNFe

    /**
     * autoEnvNFe
     * Este método procura por NFe's na pasta VALIDADAS, se houver alguma envia para a SEFAZ
     * e em saso de sucesso no envio move o arquivo para a pasta das enviadas
     * ATENÇÃO : Existe um limite para o tamanho do arquivo a ser enviado ao SEFAZ
     *
     * @version 2.04
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param none
     * @return mixed false erro ou string $recibo
     */
    public function autoEnvNFe(){
        //estabelece condição inicial do retorno
        $recibo = '';
        //varre a pasta de validadas
        $aName = $this->listDir($this->valDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        $n = count($aName);
        if ( $n > 0 ) {
            //determina o numero de grupos de envio com 10 notas por grupo
            $k = ceil($n/10);
            // as notas localizadas na pasta validadas serão enviadas em
            // grupos de até 10 notas de cada vez, sim este é um valor arbritrario eu quis até 10
            for ($i = 0 ; $i < $k ; $i++) {
                //limpa a matriz com as notas fiscais
                $aNFe= null;
                for ( $x = $i*10 ; $x < (($i+1)*10) ;$x++ ){
                    if ($x < $n ){
                        $filename = $this->valDir.$aName[$x];
                        $nfefile = file_get_contents($filename);
                        $aNFe[] = $nfefile;
                    }
                }
                //obter o numero do ultimo lote enviado
                $num = $this->__getNumLot();
                //incrementa o numero
                $num++;
                //enviar as notas
                if ($ret = $this->sendLot($aNFe,$num,$this->modSOAP)){
                    //incrementa o numero do lote no controle
                    if (!$this->__putNumLot($num)){
                        $this->errStatus = true;
                        $this->errMsg .= "Falha na Gravação do numero do lote de envio!!\n";
                        return false;
                    }
                    //['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'']                    
                    //verificar o status do envio
                    if ($ret['bStat']){
                        //pegar o numero do recibo da SEFAZ
                        $recibo = $ret['nRec'];
                        //mover as notas do lote para o diretorio de enviadas
                        //para cada em $aNames[] mover para $this->envDir
                        for ( $x = $i*10 ; $x < (($i+1)*10) ;$x++ ){
                            if ($x < $n ){
                               if( !rename($this->valDir.$aName[$x],$this->envDir.$aName[$x]) ){
                                    $this->errStatus = true;
                                    $this->errMsg .= "Falha na movimentação da NFe das validadas para enviadas !!\n";
                                    return false;
                                }
                            }
                        } //fim for rename
                    } else {
                        $this->errStatus = true;
                        return false;
                    }
                 } else {
                    $this->errStatus = true;
                    $this->errMsg .= "Erro no envio do lote de NFe!!\n";
                    return false;
                 }
            }
        } else {
            //não há notas para enviar
            $this->errStatus = true;
            $this->errMsg = "Não existem notas prontas para envio na pasta das validadas!!\n";
            return false;
        }
        return $recibo;
    } // fim autoEnvNFe
    
    /**
     * autoEnvNFeImproved
     * Este método localiza as NFe validadas e efetua a montagem dos lotes de envio
     * envia esses lotes para a SEFAZ, move essas NFe para a pasta de enviadas, 
     * aguarda alguns segundos para permitir o processamento na SEFAZ e em seguida
     * requisita a situação das NFe pelo numero do recibo. Em caso de sucesso anexa o 
     * protocolo e coloca a NF na pasta de aprovadas, reprovadas ou denegadas, conforme o 
     * status de cada uma.
     *      
     * @version 1.03
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param integer $maxLoop Numero maximo de tentativas para buscar o staus da NFe
     * @param integer $minSec Tempo de pausa entre cada consulta em segundos
     * @return mixed false se ocorrer erro ou array('chave','cStat','xMotivo')
     */
    public function autoEnvNFeImproved($maxLoop=10,$minSec=2){
        $aResp = array();
        //busca as NFe e monta o lote
        //varre a pasta de validadas
        $aName = $this->listDir($this->valDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        $n = count($aName);
        if ( $n == 0 ) {
            $this->errStatus = true;
            $this->errMsg .= "Não há NFe validadas e prontas para envio!!\n";
            return false;
        }
        //existem notas para enviar
        //iniciar o loop de envio para cada NFe encontrada
        for ( $x=0 ; $x<$n ; $x++ ){
            $aNFe = null;
            $filename = $this->valDir.$aName[$x];
            $chaveNFe = substr($filename, strripos($filename, DIRECTORY_SEPARATOR) + 1, 44);
            $nfefile = file_get_contents($filename);
            $aNFe[] = $nfefile;
            //obter o numero do ultimo lote enviado
            $num = $this->__getNumLot();
            //incrementa o numero
            $num++;
            //alternativamente pode ser usado o script abaixo para geração do numero de lote
            //$num = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
            //enviar as notas
            if ($ret = $this->sendLot($aNFe,$num,$this->modSOAP)){
                //verificar o status do envio
                if ($ret['bStat'] == true && $ret['cStat'] == 103){
                    //pegar o numero do recibo da SEFAZ
                    $recibo = $ret['nRec'];
                    //incrementa o numero do lote no controle
                    //comentar caso não use o controle de lote por arquivo
                    if ($this->__putNumLot($num)){
                        $this->errStatus = true;
                        $this->errMsg .= "Falha na Gravação do numero do lote de envio!!\n";
                        return false;
                    }
                    //como temos o recibo iniciar o loop
                    $loop = 0;
                    while ($loop < $maxLoop){
                        //incrementar contagem
                        $loop++;
                        //atrasar a consulta para permitir processamento da SEFAZ
                        sleep($minSec);
                        //consultar o ststus da NFe
                        if($retProt = $this->getProtocol($recibo,'', $this->tpAmb, $this->modSOAP)){
                            if ($retProt['bStat'] == true){
                                if ($retProt['cStat'] == 106){
                                    $this->errStatus = true;
                                    $this->errMsg .= "Lote não localizado !!\n";
                                    return false;
                                }
                                if ($retProt['cStat'] > 106){
                                    $this->errStatus = true;
                                    $this->errMsg .= "Erro na consulta do lote !!\n" . $retProt['cStat'];
                                    return false;
                                }
                                if ($retProt['cStat'] == 104){
                                    //o protocolo foi retornado
                                    $aProt = $retProt['aProt'][$x];
                                    $protFile = $this->temDir.$chaveNFe.'-prot.xml';
                                    $loop = $maxLoop+1; //sair do laço
                                    $aResp[$x] = array();
                                    $aResp[$x]['chave'] = $chaveNFe;
                                    $aResp[$x]['cStat'] = $aProt['cStat'];
                                    $aResp[$x]['xMotivo'] = $aProt['xMotivo'];
                                    //pegar o cStat
                                    if ($aProt['cStat'] == 100){
                                        //aprovado
                                        $dirDest = $this->aprDir;
                                    }
                                    if ($aProt['cStat'] == 110){
                                        //denegado
                                        $dirDest = $this->denDir;
                                    }
                                    if ($aProt['cStat'] > 110){
                                        //reprovado
                                        $dirDest = $this->repDir;
                                        //mover a nota para reprovadas
                                        rename($filename, $dirDest.$chaveNFe.'-nfe.xml');
                                    } else {
                                        //não reprovado
                                        if (file_exists($protFile)){
                                            $procnfe = $this->addProt($filename,$protFile);
                                            //salvar a NFe com o protocolo na pasta destino
                                            if ( file_put_contents($dirDest.$chaveNFe.'-nfe.xml', $procnfe) ) {
                                                //remover o arquivo antigo sem o protocolo
                                                unlink($filename);
                                            }
                                        }
                                    }    
                                }
                            } else {
                                $this->errStatus = true;
                                $this->errMsg .= "Falha na consulta do protocolo!!\n";
                                return false;
                            }//fim bStat
                        } else {
                            $this->errStatus = true;
                            $this->errMsg .= "Falha na consulta do protocolo!!\n";
                            return false;
                        }//fim consulta protocolo
                    }//fim do loop de consulta do status da nfe
                } else {
                    $this->errStatus = true;
                    $this->errMsg .= "Erro no envio do lote de NFe!!\n";
                    return false;
                }//fim if verifica retorno envio lote
            } else {
                $this->errStatus = true;
                $this->errMsg .= "Erro no envio do lote de NFe!!\n";
                return false;
            }//fim envio lote
        } //fim for das NFe
        return $aResp;
    } //fim autoEnvNFeImproved    

    /**
     * autoProtNFe
     * Este método localiza as NFe enviadas na pasta ENVIADAS e solicita o prococolo
     * de autorização destas NFe's
     *
     * Caso haja resposta (aprovando, denegando ou rejeitando) o método usa os dados de
     * retorno para localizar a NFe em xml na pasta de ENVIADAS e inclui no XML a tag nfeProc com os dados
     * do protocolo de autorização.
     *  - Em caso de aprovação as coloca na subpasta APROVADAS e remove tanto o xml da NFe
     *    da pasta ENVIADAS como o retorno da consulta em TEMPORARIAS.
     *  - Em caso de rejeição coloca as coloca na subpasta REJEITADAS e remove da pasta ENVIADAS e TEMPORARIAS.
     *  - Em caso de denegação coloca as coloca na subpasta DENEGADAS e remove da pasta ENVIADAS e TEMPORARIAS.
     *
     * Caso não haja resposta ainda não faz nada.
     *
     * @version 2.04
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  none
     * @return array   [n]['cStat']['xMotivo']['nfepath']
     */
    public function autoProtNFe(){
        //condição inicial da variável de retorno
        $aRetorno = array(0=>array('cStat'=>'','xMotivo'=>'','nfepath'=>''));
        $n = 0;
        //varre a pasta de enviadas
        $aName = $this->listDir($this->envDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        if ( count($aName) > 0 ) {
            //para cada arquivo nesta pasta solicitar o protocolo
            foreach ( $aName as $file ) {
                $idNFe = substr($file,0,44);
                $nfeFile = $this->envDir.$file;
                //primeiro verificar se o protocolo existe na pasta temporarias
                $protFile = $this->temDir.$idNFe.'-prot.xml';
                if (file_exists($protFile)){
                    $docxml = file_get_contents($protFile);
                    $dom = new DOMDocument(); //cria objeto DOM
                    $dom->formatOutput = false;
                    $dom->preserveWhiteSpace = false;
                    $dom->loadXML($docxml);
                    //pagar o cStat e xMotivo do protocolo
                    $aRet['cStat'] = $dom->getElementsByTagName('cStat')->item(0)->nodeValue;
                    $aRet['xMotivo'] = $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue;
                    $aRet['bStat'] = true;
                } else {
                    //caso não exista então buscar pela chave da NFe
                    $aRet = $this->getProtocol('',$idNFe,$this->tpAmb,$this->modSOAP);
                }    
                if ( $aRet['cStat'] == 100) {
                    //NFe aprovada
                    $pasta = $this->aprDir;
                }//endif
                if ( $aRet['cStat'] == 110) {
                    //NFe denegada
                    $pasta = $this->denDir;
                }//endif
                if ( $aRet['cStat'] > 199 ) {
                    //NFe reprovada
                    $pasta = $this->repDir;
                    //mover a NFe para a pasta repovadas
                    rename($nfeFile, $pasta.$idNFe.'-nfe.xml');
                }//endif
                if ( $aRet['bStat'] ) {
                    //montar a NFe com o protocolo
                    if ( is_file($protFile) && is_file($nfeFile) ) {
                        //se aprovada ou denegada adicionar o protocolo e mover o arquivo
                        if ($aRet['cStat'] == 100 || $aRet['cStat'] == 110 ) {
                            $procnfe = $this->addProt($nfeFile,$protFile);
                            //salvar a NFe com o protocolo na pasta
                            if ( file_put_contents($pasta.$idNFe.'-nfe.xml', $procnfe) ) {
                                //se o arquivo foi gravado na pasta destino com sucesso
                                //remover os arquivos das outras pastas
                                unlink($nfeFile);
                            } //endif
                        }//endif cStat   
                    } //endif is_file
                } //endif bStat
                $aRetorno[$n]['cStat'] = $aRet['cStat'];
                $aRetorno[$n]['xMotivo'] = $aRet['xMotivo'];
                $aRetorno[$n]['nfepath'] = $pasta.$idNFe.'-nfe.xml';
                $n++;
            }//endforeach
        } else {
            //não há notas para protocolar na pasta de enviadas
            $this->errStatus = true;
            $this->errMsg = "Não existem notas prontas para protocolar na pasta enviadas!!\n";
        }//endif
        return $aRetorno;
    }//fim autoProtNFe

    /**
     * autoPrintSend
     * Este método deve imprmir automaticamente as DANFEs de todas as
     * NFe localizadas na pasta aprovadas, enviar o email ao destinatário
     * com a DANFE em pdf e o arquivo xml e ainda mover a NFe em xml
     * para o diretorio de armazenamento com o ANOMES da nota para facilitação
     * da adminstração e backup
     *
     * @version   2.07
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  boolean $fixpdf Indica de deve ser gravado ou não o pdf da NFe
     * @return boolean True se OK ou False se falha
    */
    public function autoPrintSend($fixpdf=false){
        //varre a pasta de enviadas/aprovadas
        $aNApr = $this->listDir($this->aprDir,'*-nfe.xml',false);
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        if ( count($aNApr) > 0 ) {
            //para cada arquivo nesta pasta imprimir a DANFE em pdf
            // e enviar para a printer
            // SÓ FUNCIONA ATÉ 2099 !!!!!
            foreach ( $aNApr as $file ) {
                $anomes = '20'.substr($file,2,4);
                $docxml = file_get_contents($this->aprDir.$file);
                $danfe = new DanfeNFePHP($docxml,$this->danfepaper,$this->danfeform,$this->danfelogopath,'I','',$this->danfefont);
                $id = (string) $danfe->montaDANFE();
                $pdfName = $id.'.pdf';
  				//carrega a DANFE como uma string para gravação no diretorio escolhido
                $pdfFile = (string) $danfe->printDANFE($this->pdfDir.$pdfName,'S');
                if ($pdfFile != ''){
                    //grava a DANFE como pdf no diretorio
                    if (!file_put_contents($this->pdfDir.$pdfName,$pdfFile)){
                        //houve falha na gravação
                        $this->errMsg = "Falha na gravação do pdf.\n";
                        $this->errStatus = true;
                    } else {
                        //em caso de sucesso, verificar se foi definida a printer se sim imprimir
                        //este comando de impressão funciona tanto em linux como em wndows se o 
                        //ambiente estiver corretaente preparado
			if ( $this->danfeprinter != '' ) {
                            $command = "lpr -P $this->danfeprinter $this->pdfDir$pdfName";
                            system($command);
                        }
                    }
		} else {
                    //houve falha na geração da DANFE
                    $this->errMsg = "Falha na geração da DANFE.\n";
                    $this->errStatus = true;
		}
                //arquivo da NFe com o protocolo
                $dom = new DOMDocument(); //cria objeto DOM
                $dom->formatOutput = false;
                $dom->preserveWhiteSpace = false;
                $dom->loadXML($docxml);
                $ide        = $dom->getElementsByTagName("ide")->item(0);
                $emit       = $dom->getElementsByTagName("emit")->item(0);
                $dest       = $dom->getElementsByTagName("dest")->item(0);
                $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
                $obsCont    = $dom->getElementsByTagName("obsCont")->item(0);
                $infNFe     = $dom->getElementsByTagName('infNFe')->item(0);
                //obtem a versão do layout da NFe
                $ver = trim($infNFe->getAttribute("versao"));
                $razao = utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue);
                $numero = str_pad($ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
                $serie = str_pad($ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
                $emitente = utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue);
                $vtotal = number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
                $email = '';
                $contato = '';
                //NFe ver 1.10 - obter o email de envio
                //na proxima revisão não será mais inclusa a verificação da
                //versão 1.10 que passará a ser resconsiderada
                if($ver =='1.10'){
                    if (isset($obsCont)){
                        foreach ($obsCont as $obs){
                            $campo =  $obsCont->item($i)->getAttribute("xCampo");
                            $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                            if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                                $email .= $xTexto.',';
                            }
                            $i++;
                        }
                    }
                } else {
                    //NFe ver 2.00
                    $email = !empty($dest->getElementsByTagName("email")->item(0)->nodeValue) ? utf8_decode($dest->getElementsByTagName("email")->item(0)->nodeValue) : '';
                    if (isset($obsCont)){
                        foreach ($obsCont as $obs){
                            $campo =  $obsCont->item($i)->getAttribute("xCampo");
                            $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                            if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                                $email .= $xTexto.',';
                            }
                            $i++;
                        }
                    }
               } //endif
               if ($email != '' ) {
                    //montar a matriz de dados para envio do email
                    $aEMail = array('emitente'=>$emitente,'para'=>$email,'contato'=>'','razao'=>$razao,'numero'=>$numero,'serie'=>$serie,'vtotal'=>$vtotal);
                    //inicalizar a classe de envio
                    $nfeMail = new MailNFePHP($this->aMail);
                    if ( !$nfeMail->sendNFe($docxml,$pdfFile,$file,$pdfName,$aEMail) ){
                        $this->errMsg = "Falha no envio do email ao destinatário!!\n";
                        $this->errStatus = true;
                    }
                }
                //mover o arquivo xml para a pasta de arquivamento identificada com o ANOMES
                $diretorio = $this->aprDir.$anomes.DIRECTORY_SEPARATOR;
                if ( !is_dir($diretorio) ) {
                      mkdir($diretorio, 0777);
                }
                rename($this->aprDir.$file,$diretorio.$file);
                //apagar o pdf criado para envio
                if (!$fixpdf){
                    if (is_file($this->pdfDir.$pdfName)){
                        unlink($this->pdfDir.$pdfName);
                    }
                }    
            } //end foreach
        } //endif
        return true;
    } //fim da função autoPrintDANFE

    /**
     * addProtCanc
     * Este método adiciona a tag do protocolo a NFe cancelada,preparando a mesma
     * para envio ao destinatário.
     *
     * @name addProtCanc, baseado na funcao addProt
     * @version 1.1
     * @package NFePHP
     * @author Leandro C. Lopez <leandro dot castoldi at gmail dot com>
     * @param string $nfefile path completo para o arquivo contendo os dados do cancelamento
     * @param string $protfile path completo para o arquivo contendo o protocolo
     * @return string Retorna a NFe de cancelamento com o protocolo
    **/
    public function addProtCanc($nfefile, $protfile) {
        //protocolo do lote enviado
        $prot = new DOMDocument(); //cria objeto DOM
        $prot->formatOutput = false;
        $prot->preserveWhiteSpace = false;
        //NFe enviada
        $docnfe = new DOMDocument(); //cria objeto DOM
        $docnfe->formatOutput = false;
        $docnfe->preserveWhiteSpace = false;
        //carrega o arquivo na veriável
        $xmlnfe = file_get_contents($nfefile);
        $docnfe->loadXML($xmlnfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $nfe = $docnfe->getElementsByTagName("cancNFe")->item(0);
        $infNfe = $docnfe->getElementsByTagName("infCanc")->item(0);
        $versao = trim($nfe->getAttribute("versao"));
        //carrega o protocolo e seus dados
        $xmlprot = file_get_contents($protfile);
        $prot->loadXML($xmlprot,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $protNFe = $prot->getElementsByTagName("retCancNFe")->item(0);
        $protver = trim($protNFe->getAttribute("versao"));
        $tpAmb = $prot->getElementsByTagName("tpAmb")->item(0)->nodeValue;
        $verAplic =
        $prot->getElementsByTagName("verAplic")->item(0)->nodeValue;
        $cUF = $prot->getElementsByTagName("cUF")->item(0)->nodeValue;
        $chNFe=$prot->getElementsByTagName("chNFe")->item(0)->nodeValue;
        $dhRecbto=$prot->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
        $nProt=$prot->getElementsByTagName("nProt")->item(0)->nodeValue;
        $cStat=$prot->getElementsByTagName("cStat")->item(0)->nodeValue;
        $xMotivo=$prot->getElementsByTagName("xMotivo")->item(0)->nodeValue;
        //cria a NFe processada com a tag do protocolo
        $procnfe = new DOMDocument('1.0', 'utf-8');
        $procnfe->formatOutput = false;
        $procnfe->preserveWhiteSpace = false;
        //cria a tag nfeProc
        $nfeProc = $procnfe->createElement('procCancNFe');
        $procnfe->appendChild($nfeProc);
        //estabele o atributo de versão
        $nfeProc_att1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
        $nfeProc_att1->appendChild($procnfe->createTextNode($protver));
        //estabelece o atributo xmlns
        $nfeProc_att2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
        $nfeProc_att2->appendChild($procnfe->createTextNode($this->URLnfe));
        //inclui NFe
        $node = $procnfe->importNode($nfe, true);
        $nfeProc->appendChild($node);
        //cria tag protNFe
        $protNFe = $procnfe->createElement('retCancNFe');
        $nfeProc->appendChild($protNFe);
        //estabele o atributo de versão
        $protNFe_att1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
        $protNFe_att1->appendChild($procnfe->createTextNode($versao));
        $protNFe_att2 = $protNFe->appendChild($procnfe->createAttribute('xmlns'));
        $protNFe_att2->appendChild($procnfe->createTextNode($this->URLnfe));
        //cria tag infProt
        $infProt = $procnfe->createElement('infCanc');
        $infProt_att1 = $infProt->appendChild($procnfe->createAttribute('Id'));
        $infProt_att1->appendChild($procnfe->createTextNode('ID'.$nProt));
        $protNFe->appendChild($infProt);
        $infProt->appendChild($procnfe->createElement('tpAmb',$tpAmb));
        $infProt->appendChild($procnfe->createElement('verAplic',$verAplic));
        $infProt->appendChild($procnfe->createElement('cStat',$cStat));
        $infProt->appendChild($procnfe->createElement('xMotivo',$xMotivo));
        $infProt->appendChild($procnfe->createElement('cUF',$cUF));
        $infProt->appendChild($procnfe->createElement('chNFe',$chNFe));
        $infProt->appendChild($procnfe->createElement('dhRecbto',$dhRecbto));
        $infProt->appendChild($procnfe->createElement('nProt',$nProt));
        //salva o xml como string em uma variável
        $procXML = $procnfe->saveXML();
        //remove as informações indesejadas
        $procXML = str_replace('default:','',$procXML);
        $procXML = str_replace(':default','',$procXML);
        $procXML = str_replace("\n",'',$procXML);
        $procXML = str_replace("\r",'',$procXML);
        $procXML = str_replace("\s",'',$procXML);
        $procXML = str_replace('cancNFe xmlns="http://www.portalfiscal.inf.br/nfe" xmlns="http://www.w3.org/2000/09/xmldsig#"','cancNFe xmlns="http://www.portalfiscal.inf.br/nfe"',$procXML);
        return $procXML;
    } //fim addProtCanc

   /**
    * validXML
    * Verifica o xml com base no xsd
    * Esta função pode validar qualquer arquivo xml do sistema de NFe
    * Há um bug no libxml2 para versões anteriores a 2.7.3
    * que causa um falso erro na validação da NFe devido ao
    * uso de uma marcação no arquivo tiposBasico_v1.02.xsd
    * onde se le {0 , } substituir por *
    *
    * @name validXML
    * @version 2.02
    * @package NFePHP
    * @author Roberto L. Machado <linux.rlm at gmail dot com>
    * @param    string  $docxml  string contendo o arquivo xml a ser validado
    * @param    string  $xsdfile Path completo para o arquivo xsd
    * @return   array   ['status','error']
    */
    public function validXML($docXml, $xsdFile){
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        // instancia novo objeto DOM
        $xmldoc = new DOMDocument();
        // carrega o xml
        $xml = $xmldoc->loadXML($docXml);
        $errorMsg='';
        // valida o xml com o xsd
        if ( !$xmldoc->schemaValidate($xsdFile) ) {
            /**
             * Se não foi possível validar, você pode capturar
             * todos os erros em um array
             * Cada elemento do array $arrayErrors
             * será um objeto do tipo LibXmlError
             */
            // carrega os erros em um array
            $aIntErrors = libxml_get_errors();
            //libxml_clear_errors();
            $flagOK = false;
            foreach ($aIntErrors as $intError){
                switch ($intError->level) {
                    case LIBXML_ERR_WARNING:
                        $errorMsg .= " Atençao $intError->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $errorMsg .= " Erro $intError->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $errorMsg .= " Erro Fatal $intError->code: ";
                        break;
                }
                $errorMsg .= $intError->message . ';';
            }
        } else {
            $flagOK = true;
            $errorMsg = '';
        }
        return array('status'=>$flagOK, 'error'=>$errorMsg);
    } //fim validXML
	
    /**
     * addProt
     * Este método adiciona a tag do protocolo a NFe, preparando a mesma
     * para impressão e envio ao destinatário.
     *
     * @name addProt
     * @version 2.03
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $nfefile path completo para o arquivo contendo a NFe
     * @param string $protfile path completo para o arquivo contendo o protocolo
     * @return string Retorna a NFe com o protocolo
     */
    public function addProt($nfefile, $protfile) {
            //protocolo do lote enviado
            $prot = new DOMDocument(); //cria objeto DOM
            $prot->formatOutput = false;
            $prot->preserveWhiteSpace = false;
            //NFe enviada
            $docnfe = new DOMDocument(); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            //carrega o arquivo na veriável
            $xmlnfe = file_get_contents($nfefile);
            $docnfe->loadXML($xmlnfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $nfe = $docnfe->getElementsByTagName("NFe")->item(0);
            $infNfe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNfe->getAttribute("versao"));
            //carrega o protocolo e seus dados
            $xmlprot = file_get_contents($protfile);
            $prot->loadXML($xmlprot,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $protNFe = $prot->getElementsByTagName("protNFe")->item(0);
            $protver = trim($protNFe->getAttribute("versao"));
            $tpAmb = $prot->getElementsByTagName("tpAmb")->item(0)->nodeValue;
            $verAplic = $prot->getElementsByTagName("verAplic")->item(0)->nodeValue;
            $chNFe=$prot->getElementsByTagName("chNFe")->item(0)->nodeValue;
            $dhRecbto=$prot->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
            $nProt=$prot->getElementsByTagName("nProt")->item(0)->nodeValue;
            $digVal=$prot->getElementsByTagName("digVal")->item(0)->nodeValue;
            $cStat=$prot->getElementsByTagName("cStat")->item(0)->nodeValue;
            $xMotivo=$prot->getElementsByTagName("xMotivo")->item(0)->nodeValue;
            //cria a NFe processada com a tag do protocolo
            $procnfe = new DOMDocument('1.0', 'utf-8');
            $procnfe->formatOutput = false;
            $procnfe->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProc = $procnfe->createElement('nfeProc');
            $procnfe->appendChild($nfeProc);
            //estabele o atributo de versão
            $nfeProc_att1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
            $nfeProc_att1->appendChild($procnfe->createTextNode($protver));
            //estabelece o atributo xmlns
            $nfeProc_att2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
            $nfeProc_att2->appendChild($procnfe->createTextNode($this->URLnfe));
            //inclui NFe
            $node = $procnfe->importNode($nfe, true);
            $nfeProc->appendChild($node);
            //cria tag protNFe
            $protNFe = $procnfe->createElement('protNFe');
            $nfeProc->appendChild($protNFe);
            //estabele o atributo de versão
            $protNFe_att1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
            $protNFe_att1->appendChild($procnfe->createTextNode($versao));
            //cria tag infProt
            $infProt = $procnfe->createElement('infProt');
            $protNFe->appendChild($infProt);
            $infProt->appendChild($procnfe->createElement('tpAmb',$tpAmb));
            $infProt->appendChild($procnfe->createElement('verAplic',$verAplic));
            $infProt->appendChild($procnfe->createElement('chNFe',$chNFe));
            $infProt->appendChild($procnfe->createElement('dhRecbto',$dhRecbto));
            $infProt->appendChild($procnfe->createElement('nProt',$nProt));
            $infProt->appendChild($procnfe->createElement('digVal',$digVal));
            $infProt->appendChild($procnfe->createElement('cStat',$cStat));
            $infProt->appendChild($procnfe->createElement('xMotivo',$xMotivo));
            //salva o xml como string em uma variável
            $procXML = $procnfe->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace('default:','',$procXML);
            $procXML = str_replace(':default','',$procXML);
            $procXML = str_replace("\n",'',$procXML);
            $procXML = str_replace("\r",'',$procXML);
            $procXML = str_replace("\s",'',$procXML);
            $procXML = str_replace('NFe xmlns="http://www.portalfiscal.inf.br/nfe" xmlns="http://www.w3.org/2000/09/xmldsig#"','NFe xmlns="http://www.portalfiscal.inf.br/nfe"',$procXML);
            return $procXML;
    } //fim addProt

    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @name signXML
     * @version 2.10
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return	mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid=''){
            if ( $tagid == '' ){
                $this->errMsg = "Uma tag deve ser indicada para que seja assinada!!\n";
                $this->errStatus = true;
                return false;
            }
            if ( $docxml == '' ){
                $this->errMsg = "Um xml deve ser passado para que seja assinado!!\n";
                $this->errStatus = true;
                return false;
            }
            // obter o chave privada para a ssinatura
            $fp = fopen($this->priKEY, "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);
            // limpeza do xml com a retirada dos CR, LF e TAB
            $order = array("\r\n", "\n", "\r", "\t");
            $replace = '';
            $docxml = str_replace($order, $replace, $docxml);
            // carrega o documento no DOM
            $xmldoc = new DOMDocument();
            $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
            $xmldoc->formatOutput = false;
            // muito importante deixar ativadas as opçoes para limpar os espacos em branco
            // e as tags vazias
            $xmldoc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $root = $xmldoc->documentElement;
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            $id = trim($node->getAttribute("Id"));
            $idnome = preg_replace('/[^0-9]/','', $id);
            //extrai os dados da tag para uma string
            $dados = $node->C14N(false,false,NULL,NULL);
            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,true);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $Signature = $xmldoc->createElementNS($this->URLdsig,'Signature');
            $root->appendChild($Signature);
            $SignedInfo = $xmldoc->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);
            //Cannocalization
            $newNode = $xmldoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
            //SignatureMethod
            $newNode = $xmldoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);
            //Reference
            $Reference = $xmldoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$id);
            //Transforms
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
            //DigestMethod
            $newNode = $xmldoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
            //DigestValue
            $newNode = $xmldoc->createElement('DigestValue',$digValue);
            $Reference->appendChild($newNode);
            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false,false,NULL,NULL);
            //inicializa a variavel que irá receber a assinatura
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            $resp = openssl_sign($dados,$signature,$pkeyid);
            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);
            //SignatureValue
            $newNode = $xmldoc->createElement('SignatureValue',$signatureValue);
            $Signature->appendChild($newNode);
            //KeyInfo
            $KeyInfo = $xmldoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);
            //X509Data
            $X509Data = $xmldoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);
            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->__cleanCerts($this->pubKEY);
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);
            //grava na string o objeto DOM
            $docxml = $xmldoc->saveXML();
            // libera a memoria
            openssl_free_key($pkeyid);
            //retorna o documento assinado
            return $docxml;
    } //fim signXML

    /**
     * statusServico
     * Verifica o status do servico da SEFAZ
     *
     * $this->cStat = 107 OK
     *        cStat = 108 sitema paralizado momentaneamente, aguardar retorno
     *        cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     *        cStat = 113 SCAN operando mas irá parar use o serviço Normal
     *        cStat = 114 SCAN dasativado pela SEFAZ de origem    
     * se SCAN estiver ativado usar, caso contrario aguardar pacientemente.
     * @name statusServico
     * @version 2.03
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	string $UF sigla da unidade da Federação
     * @param   integer $tpAmb tipo de ambiente 1-produção e 2-homologação
     * @param   integer 1 usa o __sendSOAP e 2 usa o __sendSOAP2
     * @return	mixed false ou array ['bStat'=>boolean,'cStat'=>107,'tMed'=>1,'dhRecbto'=>'12/12/2009','xMotivo'=>'Serviço em operação','xObs'=>'']
    **/
    public function statusServico($UF='',$tpAmb='',$modSOAP='2'){
        //retorno da funçao
        $aRetorno = array('bStat'=>false,'cStat'=>'','tMed'=>'','dhRecbto'=>'','xMotivo'=>'','xObs'=>'');
        // caso o parametro tpAmb seja vazio
        if ( $tpAmb == '' ){
            $tpAmb = $this->tpAmb;
        }
        // caso a sigla do estado esteja vazia
        if ( $UF =='' ){
            $UF = $this->UF;
        }
        //busca o cUF
        $cUF = $this->cUFlist[$UF];
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN){
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$tpAmb,$UF);
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$tpAmb,'SCAN');
        }
        //identificação do serviço
        $servico = 'NfeStatusServico';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'. $namespace . '"><consStatServ xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><cUF>'.$cUF.'</cUF><xServ>STATUS</xServ></consStatServ></nfeDadosMsg>';
        if ($modSOAP == '2'){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$UF);
        }
        //verifica o retorno do SOAP
        if ( isset($retorno) ) {
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro 
                return false;
            } else {
                if ($cStat == '107'){
                    $aRetorno['bStat'] = true;
                }
            }
            // status do serviço
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // tempo medio de resposta
            $aRetorno['tMed'] = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // obervaçoes (opcional)
            $aRetorno['xObs'] = !empty($doc->getElementsByTagName('xObs')->item(0)->nodeValue) ? $doc->getElementsByTagName('xObs')->item(0)->nodeValue : '';
        } else {
            $this->errStatus = true;
            $this->errMsg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!\n";
            $aRetorno = false;
        }
        return $aRetorno;
    } //fim statusServico

    /**
     * consultaCadastro
     * Solicita dados de situaçao de Cadastro, somente funciona para
     * cadastros de empresas localizadas no mesmo estado do solicitante e os dados
     * retornados podem não ser os mais atuais. Não é recomendado seu uso ainda.
     *
     * @name consultaCadastro
     * @version 2.15
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	string  $UF
     * @param   string  $IE
     * @param   string  $CNPJ
     * @param   string  $CPF
     * @param   string  $tpAmb
     * @param   integer $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return	mixed false se falha ou array se retornada informação
     **/
    public function consultaCadastro($UF,$CNPJ='',$IE='',$CPF='',$tpAmb='',$modSOAP='2'){
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','dados'=>array());
        $flagIE = false;
        $flagCNPJ = false;
        $flagCPF = false;
        $marca = '';
        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($IE != ''){
            $flagIE = true;
            $marca = 'IE-'.$IE;
            $filtro = "<IE>".$IE."</IE>";
            $CNPJ = '';
            $CPF = '';
        }
        if ($CNPJ != '') {
            $flagCNPJ = true;
            $marca = 'CNPJ-'.$CNPJ;
            $filtro = "<CNPJ>".$CNPJ."</CNPJ>";
            $CPF = '';
            $IE = '';
        }
        if($CPF != '') {
            $flagCPF = true;
            $filtro = "<CPF>".$CPF."</CPF>";
            $marca = 'CPF-'.$CPF;
            $CNPJ = '';
            $IE = '';
        }
        //se nenhum critério é satisfeito
        if ( !($flagIE || $flagCNPJ || $flagCPF) ){
            //erro nao foi passado parametro de filtragem
            $this->errStatus = true;
            $this->errMsg = "Uma opção deve ser indicada CNPJ, CPF ou IE !!!\n";
            return false;
        }
        if ($tpAmb == '' ){
            $tpAmb = $this->tpAmb;
        }
        //carrega as URLs
        $aURL = $this->aURL;
        // caso a sigla do estado seja diferente do emitente ou o ambiente seja diferente
        if ($UF != $this->UF || $tpAmb != $this->tpAmb){
            //recarrega as url referentes aos dados passados como parametros para a função
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$tpAmb,$UF);
        }
        //busca o cUF
        $cUF = $this->cUFlist[$UF];
        //identificação do serviço
        $servico = 'NFeConsultaCadastro';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        if($urlservico==''){
            $this->errStatus = true;
            $this->errMsg = "Este serviço não está disponível!!!\n";
            return false;
        }
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'. $namespace . '"><consCad xmlns="'.$this->URLnfe.'" versao="'.$versao.'"><infCons><xServ>CONS-CAD</xServ><UF>'.$UF.'</UF>'.$filtro.'</infCons></consCad><nfeDadosMsg>';
        //envia a solicitação via SOAP
        if ($modSOAP == 2){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$UF);
        }
        return $aRetorno;
    } //fim consultaCadastro

    /**
     * sendLot
     * Envia lote de Notas Fiscais para a SEFAZ.
     * Este método pode enviar uma ou mais NFe para o SEFAZ, desde que,
     * o tamanho do arquivo de envio não ultrapasse 500kBytes
     * Este processo enviará somente até 50 NFe em cada Lote
     *
     * @name sendLot
     * @version 2.16
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	array   $aNFe notas fiscais em xml uma em cada campo do array unidimensional MAX 50
     * @param   integer $id     id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma NFe
     * @param   integer $modSOAP 1 usa __sendSOP e 2 usa __sendSOAP2
     * @return	mixed	false ou array ['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'','tMed'=>'','tpAmb'=>'','verAplic'=>'','cUF'=>'']
     * @todo Incluir regra de validação para ambiente de homologação/produção vide NT2011.002
    **/
    public function sendLot($aNFe,$id,$modSOAP='2') {
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'','tMed'=>'','tpAmb'=>'','verAplic'=>'','cUF'=>'');
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN){
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$this->tpAmb,'SCAN');
        }
        //identificação do serviço
        $servico = 'NfeRecepcao';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        // limpa a variavel
        $sNFe = '';
        // verificar se foram passadas até 50 NFe
        if ( count($aNFe) > 50 ) {
            $this->errStatus = true;
            $this->errMsg = "No maximo 50 NFe devem compor um lote de envio!!\n";
            return false;
        }
        // monta string com todas as NFe enviadas no array
        $sNFe = implode('',$aNFe);
        //remover <?xml version="1.0" encoding=... das NFe pois somente uma dessas tags pode exitir na mensagem
        $sNFe = str_replace(array('<?xml version="1.0" encoding="utf-8"?>','<?xml version="1.0" encoding="UTF-8"?>'),'',$sNFe);
        $sNFe = str_replace(array("\r","\n","\s"),"",$sNFe);
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><enviNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><idLote>'.$id.'</idLote>'.$sNFe.'</enviNFe></nfeDadosMsg>';
        //envia dados via SOAP
        if ($modSOAP == '2'){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb,$this->UF);
        }
        //verifica o retorno
        if ($retorno){
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                $this->errStatus = true;
                $this->errMsg = "O retorno não contêm cStat verifique o debug do soap !!\n";
                return false;
            } else {
                if ($cStat == '103'){
                    $aRetorno['bStat'] = true;
                }
            }
            // status do serviço  
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // numero do recibo do lote enviado (opcional)
            $aRetorno['nRec'] = !empty($doc->getElementsByTagName('nRec')->item(0)->nodeValue) ? $doc->getElementsByTagName('nRec')->item(0)->nodeValue : '';
            //outras informações 
            $aRetorno['tMed'] = !empty($doc->getElementsByTagName('tMed')->item(0)->nodeValue) ? $doc->getElementsByTagName('tMed')->item(0)->nodeValue : '';
            $aRetorno['tpAmb'] = !empty($doc->getElementsByTagName('tpAmb')->item(0)->nodeValue) ? $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
            $aRetorno['verAplic'] = !empty($doc->getElementsByTagName('verAplic')->item(0)->nodeValue) ? $doc->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
            $aRetorno['cUF'] = !empty($doc->getElementsByTagName('cUF')->item(0)->nodeValue) ? $doc->getElementsByTagName('cUF')->item(0)->nodeValue : '';
            //gravar o retorno na pasta temp
            $nome = $this->temDir.$id.'-rec.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errStatus = true;
            $this->errMsg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!\n";
            $aRetorno = false;
        }
        return $aRetorno;
    }// fim sendLot

    /**
     * sendEvent
     * Envia lote de eventos da Nota Fiscal para a SEFAZ.
     * Este método pode enviar uma ou mais correções e um evento por vez
     * até o limite de 20 correções
     *
     * @name sendEvent
     * @version 1.01
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param   array   $aEvento Matriz contendo os dados dos eventos
     * @param   integer $tpAmb Tipo de ambiente 
     * @param   integer $modSOAP 1 usa __sendSOP e 2 usa __sendSOAP2
     * @return	mixed	false ou array ['bStat'=>false,'cStat'=>'','xMotivo'=>'']
     * @todo DEIXAR FUNCIONAL
     */
    public function sendEvent($aEvento,$tpAmb='',$modSOAP='2'){
        //testa se os dados do evento foram passados como array
        if (!is_array($aEvento)){
            $this->errStatus = true;
            $this->errMsg = "Dados dos eventos devem ser passados como array";            
            return false;
        }
        //tipos de eventos
        $aTEvent = array('10201'=>'Carta de Correcao',
                         '10202'=>'Registros de saida',
                         '10203'=>'Roubo de Carga',
                         '30401'=>'Confirmacao de recebimento',
                         '30402'=>'Desconhecimento da operacao',
                         '30403'=>'Devolucao de mercadoria');
        if ($tpAmb == ''){
            $tpAmb = $this->tpAmb;
        }
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN){
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$this->tpAmb,'SCAN');
        }
        $numLote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
        //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
        $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
        //se o envio for para svan mudar o numero no orgão para 90
        if ($this->enableSVAN){
            $cOrgao='90';
        } else {
            $cOrgao=$this->cUF;
        }
        //montagem do namespace do serviço
        $servico = 'RecepcaoEvento';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico;
        if (count($aEvento) > 20){
            $this->errStatus = true;
            $this->errMsg = "O limite é de 20 eventos por lote.";            
            return false;
        }
        $i = 0;
        foreach ($aEvento as $e){
            //limpa a variável do evento
            $Ev="";
            //extrair os dados do array
            $chNFe = $aEvento[$i]['chNFe'];
            $tpEvento = $aEvento[$i]['tpEvento'];
            $xCorrecao = $aEvento[$i]['xCorrecao'];
            $nSeqEvento = $aEvento[$i]['nSeqEvento'];
            //verificar se a chave foi passada
            if ($chNFe == '' || strlen($chNFe) != 44){
                $this->errStatus = true;
                $this->errMsg = "Uma chave de NFe válida não foi passada como parâmetro.";
                return false;
            }
            //se o numero do evento informado não estiver na lista retorna false
            if ($aTEvent[$tpEvento]==''){
                $this->errStatus = true;
                $this->errMsg .= "O Tipo de evento está vazio ou não foi encontrado [$tpEvento]";            
                return false;
            }
            //se for carta de correção e a correção não foram passadas retorne false
            if ($tpEvento=='10201' && $xCorrecao == ''){
                $this->errStatus = true;
                $this->errMsg .= "Falta a descrição da correção a ser aplicada.";            
                return false;
            }
            //se o numero sequencial do eveno não foi informado ou se for maior que 1 dgito
            if ($nSeqEvento == '' || strlen($nSeqEvento) > 1 || !is_numeric($nSeqEvento)){
                $this->errStatus = true;
                $this->errMsg .= "Número sequencial do evento não encontrado ou é maior que 9 ou contêm caracteres não numéricos [$nSeqEvento]";            
                return false;
            }
            //de acordo com o manual da CC-e de junho 2010 54 digitos
            // 2   +    5     +  2  +    44         +   1
            //“ID” + tpEvento + cUF + chave da NF-e + nSeqEvento
            $id = "ID$tpEvento$this->cUF$chNFe$nSeqEvento";
            $descEvento = $aTEvent[$tpEvento];
            if ($tpEvento == '10201'){
                $xCondUso = 'A Carta de Correcao e disciplinada pelo paragrafo 1o-A do art. 7o do Convenio S/N, de 15 de dezembro de 1970 e pode ser utilizada para regularizacao de erro ocorrido na emissao de documento fiscal, desde que o erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da operacao ou da prestacao; II - a correcao de dados cadastrais que implique mudanca do remetente ou do destinatario; III - a data de emissao ou de saida.';
            } else {
                $xCondUso = '';
            }
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento$this->cUF</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$verEvento\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            $Ev .= "<xCorrecao>$xCorrecao</xCorrecao>";
            $Ev .= "<xCondUso>$xCondUso</xCondUso>";
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = str_replace('<?xml version="1.0"?>','', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $Ev);
            $Ev = str_replace(array("\r","\n","\s"),"", $Ev);
            //carrega uma matriz temporária com os eventos assinados
            $aEv[$i] = $Ev;
            $i++;
        } //fim foreach    
        //montagem dos dados 
        $dados = "";
        $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
        $dados .= "<idLote>$numLote</idLote>";
        foreach ($aEv as $v){
            $dados .= $v;
        }    
        $dados .= "</envEvento>";
        //montagem da mensagem
        $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
        $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
        return $dados;
        /**
        //envia dados via SOAP
        if ($modSOAP == '2'){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$this->UF);
        }
        //verifica o retorno
        if ($retorno){
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                return false;
            }
            //o lote foi processado cStat=128
            //se cStat > 128 houve erro e o lote foi rejeitado
            // carregar as respostas
            $retEvento = $doc->getElementsByTagName('retEvento');
            foreach ($retEvento as $rv){
                $infEvento = $rv->getElementsByTagName('infEvento')->item(0);
                $chNFe = $infEvento->getElementsByTagName('chNFe')->item(0)->nodeValue;
                $cStat = $infEvento->getElementsByTagName('cStat')->item(0)->nodeValue;
                $xMotivo = $infEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue;
                //gravar o retorno na pasta temp
                $nome = $this->temDir.$chNFe.'-'.$nSeqEvento'-evento.xml';
                $nome = $rv->save($nome);
  
                // (cStat=135)
                //Recebido pelo Sistema de Registro de Eventos, com vinculação do evento na
                //NF-e, o Evento será armazenado no repositório do Sistema de Registro 
                //de Eventos com a vinculação do Evento à respectiva NF-e (cStat=135);

                //(cStat=136)
                //Recebido pelo Sistema de Registro de Eventos – vinculação do evento à
                //respectiva NF-e prejudicada – o Evento será armazenado no 
                //repositório do Sistema de Registro de Eventos, a vinculação do evento à 
                //respectiva NF-e fica prejudicada face a inexistência da NF-e no momento
                //do recebimento do Evento (cStat=136);

                //(cStat>136)
                //Rejeição – o Evento será descartado, com retorno do código do 
                //status do motivo da rejeição;
 
                //montar array de retorno    
                
  
            }
        } else {
            $this->errStatus = true;
            $this->errMsg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!\n";
            $aRetorno = false;
        }
        return $aRetorno;    
        * 
        */

    }//fim sendEvent
    
    /**
     * getProtocol
     * Solicita resposta do lote de Notas Fiscais ou o protocolo de
     * autorização da NFe
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name getProtocol
     * @version 2.28
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	string   $recibo numero do recibo do envio do lote
     * @param	string   $chave  numero da chave da NFe de 44 digitos
     * @param   string   $tpAmb  numero do ambiente 1-producao e 2-homologação
     * @param   integer   $modSOAP 1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return	mixed     false ou array
    **/
    public function getProtocol($recibo='',$chave='',$tpAmb='',$modSOAP='2'){
        //carrega defaults
        $i = 0;
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','aProt'=>'','aCanc'=>'');
        $cUF = $this->cUF;
        $UF = $this->UF;
        if ($tpAmb == ''){
            $tpAmb = $this->tpAmb;
        }
        if ($tpAmb != '1' && $tpAmb != '2' ){
            $tpAmb = '2';
        }
        $aURL = $this->aURL;
        //verifica se a chave foi passada
        if($chave != ''){
            //se sim extrair o cUF da chave
            $cUF = substr($chave,0,2);
            //testar para ver se é o mesmo do emitente
            if($cUF != $this->cUF || $tpAmb != $this->tpAmb){
                //se não for o mesmo carregar a sigla
                $UF = $this->UFList[$cUF];
                //recarrega as url referentes aos dados passados como parametros para a função
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$tpAmb,$UF);
            }
        }
        //verifica se o SCAN esta habilitado
        if ($this->enableSCAN){
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$tpAmb,'SCAN');
        }        
        if ($recibo == '' && $chave == '') {
            $this->errStatus = true;
            $this->errMsg = "ERRO. Favor indicar o numero do recibo ou a chave de acesso da NFe!!\n";
            return false;
        }
        if ($recibo != '' && $chave != '') {
            $this->errStatus = true;
            $this->errMsg = "ERRO. Favor indicar somente um dos dois dados ou o numero do recibo ou a chave de acesso da NFe!!\n";
            return false;
        }
        //consulta pelo recibo
        if ($recibo != '' && $chave == '') {
            //buscar os protocolos pelo numero do recibo do lote
            //identificação do serviço
            $servico = 'NfeRetRecepcao';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><consReciNFe xmlns="'.$this->URLPortal.'" versao="'. $versao.'"><tpAmb>'. $tpAmb.'</tpAmb><nRec>'.$recibo .'</nRec></consReciNFe></nfeDadosMsg>';
            //nome do arquivo
            $nomeArq = $recibo.'-protrec.xml';
        }
        //consulta pela chave
        if ($recibo == '' &&  $chave != ''){
            //buscar o protocolo pelo numero da chave de acesso
            //identificação do serviço
            $servico = 'NfeConsulta';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><consSitNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><xServ>CONSULTAR</xServ><chNFe>'.$chave .'</chNFe></consSitNFe></nfeDadosMsg>';
        }
        //envia a solicitação via SOAP
        if ($modSOAP == 2){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$UF);
        }
        //verifica o retorno
        if ($retorno){
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                return false;
            } 
            //o retorno vai variar se for buscado o protocolo ou recibo
            //Retorno nda consulta pela Chave da NFe
            //retConsSitNFe 100 aceita 110 denegada 101 cancelada ou outro recusada
            // cStat xMotivo cUF chNFe protNFe retCancNFe
            if ($chave != '') {
                $aRetorno['bStat'] = true;
                $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                $infProt = $doc->getElementsByTagName('infProt')->item(0);
                $infCanc = $doc->getElementsByTagName('infCanc')->item(0);
                if(isset($infProt)){
                    foreach($infProt->childNodes as $t) {
                        $aProt[$t->nodeName] = $t->nodeValue;
                    }
                    $aProt['dhRecbto'] = !empty($aProt['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($aProt['dhRecbto'])) : '';
                } else {
                    $aProt = '';
                }
                if(isset($infCanc)){
                    foreach($infCanc->childNodes as $t) {
                        $aCanc[$t->nodeName] = $t->nodeValue;
                    }
                    $aCanc['dhRecbto'] = !empty($aCanc['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($aCanc['dhRecbto'])) : '';
                } else {
                    $aCanc = '';
                }
                $aRetorno['aProt'] = $aProt;
                $aRetorno['aCanc'] = $aCanc;
                //gravar o retorno na pasta temp apenas se a nota foi aprovada ou denegada
                if ( $aRetorno['cStat'] == 100 || $aRetorno['cStat'] == 101 || $aRetorno['cStat'] == 110 ){
                    //nome do arquivo
                    $nomeArq = $chave.'-prot.xml';
                    $nome = $this->temDir.$nomeArq;
                    $nome = $doc->save($nome);
                }
            }
            //Retorno da consulta pelo recibo
            //NFeRetRecepcao 104 tem retornos
            //nRec cStat xMotivo cUF cMsg xMsg protNfe* infProt chNFe dhRecbto nProt cStat xMotivo
            if ($recibo != ''){
                $aRetorno['bStat'] = true;
                // status do serviço
                $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                // motivo da resposta (opcional)
                $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                // numero do recibo consultado
                $aRetorno['nRec'] = !empty($doc->getElementsByTagName('nRec')->item(0)->nodeValue) ? $doc->getElementsByTagName('nRec')->item(0)->nodeValue : '';
                // tipo de ambiente
                $aRetorno['tpAmb'] = !empty($doc->getElementsByTagName('tpAmb')->item(0)->nodeValue) ? $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
                // versao do aplicativo que recebeu a consulta
                $aRetorno['verAplic'] = !empty($doc->getElementsByTagName('verAplic')->item(0)->nodeValue) ? $doc->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
                // codigo da UF que atendeu a solicitacao
                $aRetorno['cUF'] = !empty($doc->getElementsByTagName('cUF')->item(0)->nodeValue) ? $doc->getElementsByTagName('cUF')->item(0)->nodeValue : '';
                // codigo da mensagem da SEFAZ para o emissor (opcional)
                $aRetorno['cMsg'] = !empty($doc->getElementsByTagName('cMsg')->item(0)->nodeValue) ? $doc->getElementsByTagName('cMsg')->item(0)->nodeValue : '';
                // texto da mensagem da SEFAZ para o emissor (opcional)
                $aRetorno['xMsg'] = !empty($doc->getElementsByTagName('xMsg')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMsg')->item(0)->nodeValue : '';
                if ($cStat == '104'){
                    //aqui podem ter varios retornos dependendo do numero de NFe enviadas no Lote e já processadas
                    $protNfe = $doc->getElementsByTagName('protNFe');
                    foreach ($protNfe as $d){
                        $infProt = $d->getElementsByTagName('infProt')->item(0);
                        $protcStat = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;//cStat
                        //pegar os dados do protolo para retornar
                        foreach($infProt->childNodes as $t) {
                            $aProt[$i][$t->nodeName] = $t->nodeValue;
                        }
                        $i++; //incluido increment para controlador de indice do array
                        //salvar o protocolo somente se a nota estiver approvada ou denegada
                        if ( $protcStat == 100 || $protcStat == 110 ){
                            $nomeprot = $this->temDir.$infProt->getElementsByTagName('chNFe')->item(0)->nodeValue.'-prot.xml';//id da nfe
                            //salvar o protocolo em arquivo
                            $novoprot = new DOMDocument('1.0', 'UTF-8');
                            $novoprot->formatOutput = true;
                            $novoprot->preserveWhiteSpace = false;
                            $pNFe = $novoprot->createElement("protNFe");
                            $pNFe->setAttribute("versao", "2.00");
                            // Importa o node e todo o seu conteudo
                            $node = $novoprot->importNode($infProt, true);
                            // acrescenta ao node principal
                            $pNFe->appendChild($node);
                            $novoprot->appendChild($pNFe);
                            $xml = $novoprot->saveXML();
                            $xml = str_replace('<?xml version="1.0" encoding="UTF-8  standalone="no"?>','<?xml version="1.0" encoding="UTF-8"?>',$xml);
                            $xml = str_replace(array("default:",":default"),"",$xml);
                            $xml = str_replace("\n","",$xml);
                            $xml = str_replace("  "," ",$xml);
                            $xml = str_replace("  "," ",$xml);
                            $xml = str_replace("  "," ",$xml);
                            $xml = str_replace("  "," ",$xml);
                            $xml = str_replace("  "," ",$xml);
                            $xml = str_replace("> <","><",$xml);
                            file_put_contents($nomeprot, $xml);
                        } //fim protcSat
                    } //fim foreach
                }//fim cStat
                //converter o horário do recebimento retornado pela SEFAZ em formato padrão
                foreach ($aProt as &$p){
                    $p['dhRecbto'] = !empty($p['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($p['dhRecbto'])) : '';
                }
                $aRetorno['aProt'] = $aProt; //passa o valor de $aProt para o array de retorno
                $nomeArq = $recibo.'-recprot.xml';
                $nome = $this->temDir.$nomeArq;
                $nome = $doc->save($nome);
            } //fim recibo
        } else {
            $this->errStatus = true;
            $this->errMsg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!\n";
            $aRetorno = false;
        } //fim retorno
        return $aRetorno;
    } //fim getProtocol

    /**
     * Solicita inutilizaçao de uma serie de numeros de NF
     *
     * @name inutNF
     * @version 2.15
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	string  $nAno       ano com 2 digitos
     * @param   string  $nSerie     serie da NF 1 até 3 digitos
     * @param   integer $nIni       numero inicial 1 até 9 digitos zero a esq
     * @param   integer $nFin       numero Final 1 até 9 digitos zero a esq
     * @param   string  $xJust      justificativa 15 até 255 digitos
     * @param   integer $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return	mixed false ou array ['bStat'=>boolean,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nProt'=>'']
    **/
    public function inutNF($nAno='',$nSerie='1',$nIni='',$nFin='',$xJust='',$modSOAP='2'){
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nProt'=>'');
        //valida dos dados de entrada
        if($nAno == '' || $nIni == '' || $nFin == '' || $xJust == '' ){
            $this->errStatus = true;
            $this->errMsg = "Não foi passado algum dos parametos necessários ANO=$nAno inicio=$nIni fim=$nFin justificativa=$xJust.\n";
            return false;
        }
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN){
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$this->tpAmb,'SCAN');
        }
        // valida o campo ano
        if( strlen($nAno) > 2 ){
            $this->errStatus = true;
            $this->errMsg = "O ano tem mais de 2 digitos. Corrija e refaça o processo!!\n";
            return false; 
        } else {
            if (strlen($nAno) < 2 ){
                $this->errStatus = true;
                $this->errMsg = "O ano tem menos de 2 digitos. Corrija e refaça o processo!!\n";
                return false; 
            }
        }
        //valida o campo serie
        if( strlen($nSerie) == 0 || strlen($nSerie) > 3){
            $this->errStatus = true;
            $this->errMsg = "O campo serie está errado: $nSerie. Corrija e refaça o processo!!\n";
            return false; 
        }
        //valida o campo numero inicial
        if (strlen($nIni) < 1 || strlen($nIni) > 9){
            $this->errStatus = true;
            $this->errMsg = "O campo numero inicial está errado: $nIni. Corrija e refaça o processo!!\n";
            return false; 
        }
        //valida o campo numero final
        if (strlen($nFin) < 1 || strlen($nFin) > 9){
            $this->errStatus = true;
            $this->errMsg = "O campo numero final está errado: $nFin. Corrija e refaça o processo!!\n";
            return false; 
        }
        //valida o campo justificativa
        $nL = strlen($xJust);
        if ($nL < 15 ) {
            $this->errStatus = true;
            $this->errMsg = "A justificativa é menor que o permitido, apenas $nL letras. Corrija e refaça o processo!!\n";
            return false; 
        } else {
            if ($nL > 255 ) {
                $this->errStatus = true;
                $this->errMsg = "A justificativa é maior que o permitido, $nL letras, no mácximo podem ser 255. Corrija e refaça o processo!!\n";
                return false; 
            }
        }
        //identificação do serviço
        $servico = 'NfeInutilizacao';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        //Identificador da TAG a ser assinada formada com Código da UF + 
        //Ano (2 posições) + CNPJ + modelo + série + nro inicial e nro final
        //precedida do literal “ID”
        // 43 posições
        //     2      4       6       20      22    25       34      43
        //     2      2       2       14       2     3        9       9            
        $id = 'ID'.$this->cUF.$nAno.$this->cnpj.'55'.str_pad($nSerie,3,'0',STR_PAD_LEFT).str_pad($nIni,9,'0',STR_PAD_LEFT).str_pad($nFin,9,'0',STR_PAD_LEFT);
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem do corpo da mensagem
        $dXML = '<inutNFe xmlns="'.$this->URLnfe.'" versao="'.$versao.'">';
        $dXML .= '<infInut Id="'.$id.'">';
        $dXML .= '<tpAmb>'.$this->tpAmb.'</tpAmb>';
        $dXML .= '<xServ>INUTILIZAR</xServ>';
        $dXML .= '<cUF>'.$this->cUF.'</cUF>';
        $dXML .= '<ano>'.$nAno.'</ano>';
        $dXML .= '<CNPJ>'.$this->cnpj.'</CNPJ>';
        $dXML .= '<mod>55</mod>';
        $dXML .= '<serie>'.$nSerie.'</serie>';
        $dXML .= '<nNFIni>'.$nIni.'</nNFIni>';
        $dXML .= '<nNFFin>'.$nFin.'</nNFFin>';
        $dXML .= '<xJust>'.$xJust.'</xJust>';
        $dXML .= '</infInut>';
        $dXML .= '</inutNFe>';
        //assina a lsolicitação de inutilização
        $dXML = $this->signXML($dXML,'infInut');
        $dados = '<nfeDadosMsg xmlns="'.$namespace.'">'.$dXML.'</nfeDadosMsg>';
        //remove as tags xml que porventura tenham sido inclusas
        $dados = str_replace('<?xml version="1.0"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
        $dados = str_replace(array("\r","\n","\s"),"", $dados);
        //envia a solicitação via SOAP
        if ($modSOAP == '2'){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb,$this->UF);
        }
        //verifica o retorno
        if ($retorno){
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro 
                return false;
            } else {
                //verificar o status da solicitação
                if ($cStat == '102'){
                    $aRetorno['bStat'] = true;
                }
            }
            // status do serviço se 102 inutilização aceita
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // numero do protocolo de aceitação da inutilização (opcional)
            $aRetorno['nProt'] = !empty($doc->getElementsByTagName('nProt')->item(0)->nodeValue) ? $doc->getElementsByTagName('nProt')->item(0)->nodeValue : '';
            //gravar o retorno na pasta temp
            $nome = $this->temDir.$id.'-inut.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errStatus = true;
            $this->errMsg = "Nao houve retorno Soap verifique o debug!!\n";
            $aRetorno = false;
        }
        return $aRetorno;
    } //fim inutNFe

    /**
     * Solicita o cancelamento de NF enviada
     *
     * @name cancelNF
     * @version 2.18
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	string  $id      ID da NFe com 44 digitos (sem o NFe na frente dos numeros)
     * @param   string  $protId     Numero do protocolo de aceitaçao do lote de NFe enviado anteriormente pelo SEFAZ
     * @param   boolean $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return	mixed false se falha ou array [
    **/
    public function cancelNF($id,$protId,$xJust,$modSOAP='2'){
        //variável de retorno
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nProt'=>'');
        //validação dos dados de entrada
        if($id == '' || $protId == '' || $xJust == ''){
            $this->errStatus = true;
            $this->errMsg = "Não foi passado algum dos parâmetros necessários ID=$id ou protocolo=$protId ou justificativa=$xJust.\n";
            return $aRetorno;            
        }
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN){
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$this->tpAmb,'SCAN');
        }
        //identificação do serviço
        $servico = 'NfeCancelamento';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dXML = '<cancNFe xmlns="'.$this->URLnfe.'" versao="'.$versao.'">';
        $dXML .= '<infCanc Id="ID'.$id.'"><tpAmb>'.$this->tpAmb.'</tpAmb><xServ>CANCELAR</xServ><chNFe>'.$id.'</chNFe><nProt>'.$protId.'</nProt><xJust>'.$xJust.'</xJust></infCanc></cancNFe>';
        //assinar a mensagem
        $dXML = $this->signXML($dXML, 'infCanc');
        $dados = '<nfeDadosMsg xmlns="'. $namespace . '">'.$dXML.'</nfeDadosMsg>';
        //remove as tags xml que porventura tenham sido inclusas ou quebas de linhas
        $dados = str_replace('<?xml version="1.0"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
        $dados = str_replace(array("\r","\n","\s"),"", $dados);
        //envia a solicitação via SOAP
        if ($modSOAP == 2){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb,$this->UF);
        }
        //verifica o retorno
        if ($retorno){
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                return false;
            } else {
                if ($cStat == '101'){
                    $aRetorno['bStat'] = true;
                }
            }
            // status do serviço se 101 cancelamento aceito
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // numero do protocolo de cancelamento da NFe (opcional)
            $aRetorno['nProt'] = !empty($doc->getElementsByTagName('nProt')->item(0)->nodeValue) ? $doc->getElementsByTagName('nProt')->item(0)->nodeValue : '';
            //gravar o retorno na pasta temp
            $nome = $this->temDir.$id.'-canc.xml';
            $nome = $doc->save($nome);
            //anexa o protocolo e salva em canceladas
            if ( file_put_contents($this->temDir.$id.'-procCancNFe.xml', $dXML) ) {
                $protCan = $this->addProtCanc($this->temDir.$id.'-procCancNFe.xml', $this->temDir.$id.'-canc.xml');
                if ( file_put_contents($this->canDir.$id.'-procCanc.xml', $protCan) ) {
                    unlink($this->temDir.$id.'-procCancNFe.xml');
                }
            }
        } else {
            $this->errStatus = true;
            $this->errMsg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!\n";
            $aRetorno = false;
        }
        return $aRetorno;
    } // fim cancelNF

    /**
     * verifySignatureXML
     * Verifica correção da assinatura no xml
     * 
     * @version 1.01
     * @package NFePHP
     * @author Bernardo Silva <bernardo at datamex dot com dot br>
     * @param string $conteudoXML xml a ser verificado 
     * @param string $tag tag que é assinada
     * @return boolean false se não confere e true se confere
     */
    public function verifySignatureXML($conteudoXML, $tag){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($conteudoXML);
        $tagBase = $dom->getElementsByTagName($tag)->item(0);
		$retXML = array(' xmlns:ds="http://www.w3.org/2000/09/xmldsig#"', ' xmlns:xsd="http://www.w3.org/2001/XMLSchema"', ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"');
        // validar digest value 
        $tagInf = $tagBase->C14N(false, false, null, null);
        $tagInf = str_replace($retXML, '', $tagInf);
        $digestCalculado = base64_encode(sha1($tagInf, true));
        $digestInformado = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;		
        if ($digestCalculado != $digestInformado){
            $this->errStatus = true;
            $this->errMsg = "O conteúdo do XML não confere com o Digest Value.\nDigest calculado [{$digestCalculado}], informado no XML [{$digestInformado}].\nO arquivo pode estar corrompido ou ter sido adulterado.\n";
            return false;
        }
        // Remontando o certificado 
        $X509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        $X509Certificate =  "-----BEGIN CERTIFICATE-----\n".
        $this->__splitLines($X509Certificate)."\n-----END CERTIFICATE-----\n";
        $pubKey = openssl_pkey_get_public($X509Certificate);
        if ($pubKey === false){
            $this->errStatus = true;
            $this->errMsg = "Ocorreram problemas ao remontar a chave pública. Certificado incorreto ou corrompido!!\n";
            return false;
        }                
        // remontando conteudo que foi assinado 
        $conteudoAssinado = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
	// Retirar itens das tags da assinatura da nota 
	$conteudoAssinado = str_replace($retXML, '', $conteudoAssinado);
	// validando assinatura do conteudo 
	$conteudoAssinadoNoXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
	$conteudoAssinadoNoXML = base64_decode(str_replace(array("\r", "\n"), '', $conteudoAssinadoNoXML));
	$ok = openssl_verify($conteudoAssinado, $conteudoAssinadoNoXML, $pubKey);
	if ($ok != 1){
            $this->errStatus = true;
            $this->errMsg = "Problema ({$ok}) ao verificar a assinatura do digital!!";
            return false;
	}
        $this->errStatus = false;
        $this->errMsg = "";
        return true;
    } // fim verifySignatureXML

    /**
     * verifyNFe
     * Verifica a validade da NFe recebida de terceiros
     *
     * @version 1.03
     * @package NFePHP
     * @author Roberto L. Machado <linux dot rlm at gmail dot com>
     * @param string $file Path completo para o arquivo xml a ser verificado
     * @return boolean false se não confere e true se confere
     */
    public function verifyNFe($file){
        //verifica se o arquivo existe
        if ( file_exists($file) ){
            //carrega a NFe
            $xml = file_get_contents($file);
            //testa a assinatura
            if ($this->verifySignatureXML($xml,'infNFe')){
                //como a ssinatura confere, consultar o SEFAZ para verificar se a NF não foi cancelada ou é FALSA
                //carrega o documento no DOM
                $xmldoc = new DOMDocument();
                $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
                $xmldoc->formatOutput = false;
                $xmldoc->loadXML($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                $root = $xmldoc->documentElement;
                $infNFe = $xmldoc->getElementsByTagName('infNFe')->item(0);
                //extrair a tag com os dados a serem assinados
                $id = trim($infNFe->getAttribute("Id"));
                $chave = preg_replace('/[^0-9]/','', $id);
                $digest = $xmldoc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                //ambiente da NFe sendo consultada
                $tpAmb = $infNFe->getElementsByTagName('tpAmb')->item(0)->nodeValue;
                //verifica se existe o protocolo
                $protNFe = $xmldoc->getElementsByTagName('protNFe')->item(0);
                if (isset($protNFe)){
                    $nProt = $xmldoc->getElementsByTagName('nProt')->item(0)->nodeValue;
                } else {
                    $nProt = '';
                }
                //busca o status da NFe na SEFAZ do estado do emitente
                $resp = $this->getProtocol('',$chave,$tpAmb,'2');
                if ($resp['cStat']!='100'){
                    //ERRO! nf não aprovada
                    $this->errStatus = true;
                    $this->errMsg = "NF não aprovada no SEFAZ!! cStat =" . $resp['cStat'] .' - '.$resp['xMotivo'] ."\n";
                    return false;
                } else {
                    if ( is_array($resp['aProt'])){
                        $nProtSefaz = $resp['aProt']['nProt'];
                        $digestSefaz = $resp['aProt']['digVal'];
                        //verificar numero do protocolo
                        if ($nProt != '') {
                            if ($nProtSefaz != $nProt){
                                //ERRO !!!os numeros de protocolo não combinam
                                $this->errStatus = true;
                                $this->errMsg = "Os numeros dos protocolos não combinam!! nProtNF = " . $nProt . " <> nProtSefaz = " . $nProtSefaz."\n";
                                return false;
                            } //fim teste do protocolo
                        } else {
                                $this->errStatus = true;
                                $this->errMsg = "A NFe enviada não contêm o protocolo de aceitação !!";
                        }
                        //verifica o digest
                        if ($digestSefaz != $digest){
                            //ERRO !!!os numeros digest não combinam
                            $this->errStatus = true;
                            $this->errMsg = "Os numeros digest não combinam!! digValSEFAZ = " . $digestSefaz . " <> DigestValue = " . $digest."\n";
                            return false;
                        } //fim teste do digest value
                    } else {
                        //o retorno veio como 100 mas por algum motivo sem o protocolo
                        $this->errStatus = true;
                        $this->errMsg = "Falha no retorno dos dados, retornado sem o protocolo !!\n";
                        return false;
                    }
                }
            } else {
                $this->errStatus = true;
                $this->errMsg = " Assinatura não confere!!\n";
                return false;
            } //fim verificação da assinatura
        } else {
            $this->errStatus = true;
            $this->errMsg = "Arquivo não localizado!!\n";
            return false;
        } //fim file_exists
        return true;
    } // fim verifyNFe

    /**
     * __splitLines
     * Divide a string do certificado publico em linhas com 76 caracteres (padrão original)
     * @version 1.00
     * @package NFePHP
     * @author Bernardo Silva <bernardo at datamex dot com dot br>
     * @param string $cnt certificado
     * @return string certificado reformatado 
     */
    private function __splitLines($cnt){
        return rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cnt), 76, "\n"));
    }//fim __splitLines

   /**
    * loadSEFAZ
    * Função para extrair o URL, nome do serviço e versão dos webservices das SEFAZ de
    * todos os Estados da Federação do arquivo urlWebServicesNFe.xml
    *
    * O arquivo xml é estruturado da seguinte forma :
    * <WS>
    *   <UF>
    *      <sigla>AC</sigla>
    *          <homologacao>
    *              <Recepcao service='nfeRecepcao' versao='1.10'>http:// .....
    *              ....
    *          </homologacao>
    *          <producao>
    *              <Recepcao service='nfeRecepcao' versao='1.10'>http:// ....
    *              ....
    *          </producao>
    *   </UF>
    *   <UF>
    *      ....
    * </WS>
    *
    * @name loadSEFAZ
    * @version 1.12
    * @package NFePHP
    * @author Roberto L. Machado <linux.rlm at gmail dot com>
    * @param  string $spathXML  Caminho completo para o arquivo xml
    * @param  string $tpAmb  Pode ser "2-homologacao" ou "1-producao"
    * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, etc..)
    * @return mixed             false se houve erro ou array com os dado do URLs das SEFAZ
    */
    public function loadSEFAZ($spathXML,$tpAmb='',$sUF) {
        //verifica se o arquivo xml pode ser encontrado no caminho indicado
        if ( file_exists($spathXML) ) {
            //carrega o xml
            $xml = simplexml_load_file($spathXML);
        } else {
            //sai caso não possa localizar o xml
            return false;
        }
        $aUrl = null;
        //testa parametro tpAmb
        if ($tpAmb == ''){
            $tpAmb = $this->tpAmb;
        }
        if ($tpAmb == '1'){
            $sAmbiente = 'producao';
        } else {
            //força homologação em qualquer outra situação
            $tpAmb = '2';
            $sAmbiente = 'homologacao';
        }
        //extrai a variável cUF do lista
        //$this->cUF = $this->cUFlist[$sUF];
        $alias = $this->aliaslist[$sUF];
        if ($alias == 'SVAN'){
            $this->enableSVAN = true;
        } else {
            $this->enableSVAN = false;
        }
        //estabelece a expressão xpath de busca
        $xpathExpression = "/WS/UF[sigla='" . $alias . "']/$sAmbiente";
        //para cada "nó" no xml que atenda aos critérios estabelecidos
        foreach ( $xml->xpath( $xpathExpression ) as $gUF ) {
            //para cada "nó filho" retonado
            foreach ( $gUF->children() as $child ) {
                $u = (string) $child[0];
                $aUrl[$child->getName()]['URL'] = $u;
                // em cada um desses nós pode haver atributos como a identificação
                // do nome do webservice e a sua versão
                foreach ( $child->attributes() as $a => $b) {
                    $aUrl[$child->getName()][$a] = (string) $b;
                }
            }
        }
        return $aUrl;
    } //fim loadSEFAZ

    /**
     * __loadCerts
     * Carrega o certificado pfx e gera as chaves privada e publica no
     * formato pem para a assinatura e para uso do SOAP e registra as
     * variaveis de ambiente.
     * Esta função deve ser invocada antes das outras do sistema que
     * dependam do certificado.
     * Além disso esta função também avalia a validade do certificado.
     * Os certificados padrão A1 (que são usados pelo sistema) tem validade
     * limitada à 1 ano e caso esteja vencido a função retornará false.
     *
     * Resultado
     *  A função irá criar o certificado digital (chaves publicas e privadas)
     *  no formato pem e grava-los no diretorio indicado em $this->certsDir
     *  com os nomes :
     *     CNPJ_priKEY.pem
     *     CNPJ_pubKEY.pem
     *     CNPJ_certKEY.pem
     *  Estes arquivos tanbém serão carregados nas variáveis da classe
     *  $this->priKEY (com o caminho completo para o arquivo CNPJ_priKEY.pem)
     *  $this->pubKEY (com o caminho completo para o arquivo CNPJ_pubKEY.pem)
     *  $this->certKEY (com o caminho completo para o arquivo CNPJ_certKEY.pem)
     * Dependencias
     *   $this->pathCerts
     *   $this->nameCert
     *   $this->passKey
     *
     * @name __loadCerts
     * @version 2.11
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param	none
     * @return	boolean true se o certificado foi carregado e false se nao
     **/
    protected function __loadCerts(){
        //monta o path completo com o nome da chave privada
        $this->priKEY = $this->certsDir.$this->cnpj.'_priKEY.pem';
        //monta o path completo com o nome da chave prublica
        $this->pubKEY =  $this->certsDir.$this->cnpj.'_pubKEY.pem';
        //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
        $this->certKEY = $this->certsDir.$this->cnpj.'_certKEY.pem';
        //verificar se o nome do certificado e
        //o path foram carregados nas variaveis da classe
        if ($this->certsDir == '' || $this->certName == '') {
            $this->errMsg = "Um certificado deve ser passado para a classe!!\n";
            $this->errStatus = true;
            return false;
        }
        //monta o caminho completo até o certificado pfx
        $pCert = $this->certsDir.$this->certName;
        //verifica se o arquivo existe
        if(!file_exists($pCert)){
            $this->errMsg = "Certificado não encontrado!!\n";
            $this->errStatus = true;
            return false;
        }
        //carrega o certificado em um string
        $key = file_get_contents($pCert);
        //carrega os certificados e chaves para um array denominado $x509certdata
        if (!openssl_pkcs12_read($key,$x509certdata,$this->keyPass) ){
            $this->errMsg = "O certificado não pode ser lido!! Provavelmente corrompido ou com formato inválido!!\n";
            $this->errStatus = true;
            return false;
        }
        //verifica sua validade
        $aResp = $this->__validCerts($x509certdata['cert']);
        if ($aResp['error'] != ''){
            $this->errMsg = "Certificado invalido!! - " . $aResp['error']."\n";
            $this->errStatus = true;
            return false;
        }
        //verifica se arquivo já existe
        if(file_exists($this->priKEY)){
            //se existir verificar se é o mesmo
            $conteudo = file_get_contents($this->priKEY);
            //comparar os primeiros 100 digitos
            if ( !substr($conteudo,0,100) == substr($x509certdata['pkey'],0,100) ) {
                 //se diferentes gravar o novo
                if (!file_put_contents($this->priKEY,$x509certdata['pkey']) ){
                    $this->errMsg = "Impossivel gravar no diretório!!! Permissão negada!!\n";
                    $this->errStatus = true;
                    return false;
                }
            }
        } else {
            //salva a chave privada no formato pem para uso so SOAP
            if ( !file_put_contents($this->priKEY,$x509certdata['pkey']) ){
                   $this->errMsg = "Impossivel gravar no diretório!!! Permissão negada!!\n";
                   $this->errStatus = true;
                   return false;
            }
        }
        //verifica se arquivo com a chave publica já existe
        if(file_exists($this->pubKEY)){
            //se existir verificar se é o mesmo atualmente instalado
            $conteudo = file_get_contents($this->pubKEY);
            //comparar os primeiros 100 digitos
            if ( !substr($conteudo,0,100) == substr($x509certdata['cert'],0,100) ) {
                //se diferentes gravar o novo
                $n = file_put_contents($this->pubKEY,$x509certdata['cert']);
                //salva o certificado completo no formato pem
                $n = file_put_contents($this->certKEY,$x509certdata['pkey']."\r\n".$x509certdata['cert']);
            }
        } else {
            //se não existir salva a chave publica no formato pem para uso do SOAP
            $n = file_put_contents($this->pubKEY,$x509certdata['cert']);
            //salva o certificado completo no formato pem
            $n = file_put_contents($this->certKEY,$x509certdata['pkey']."\r\n".$x509certdata['cert']);
        }
        return true;
    } //fim __loadCerts

   /**
    * __validCerts
    * Validaçao do cerificado digital, além de indicar
    * a validade, este metodo carrega a propriedade
    * mesesToexpire da classe que indica o numero de
    * meses que faltam para expirar a validade do mesmo
    * esta informacao pode ser utilizada para a gestao dos
    * certificados de forma a garantir que sempre estejam validos
    *
    * @name __validCerts
    * @version  1.00
    * @package  NFePHP
    * @author Roberto L. Machado <linux.rlm at gmail dot com>
    * @param    string  $cert Certificado digital no formato pem
    * @return	array ['status'=>true,'meses'=>8,'dias'=>245]
    */
    protected function __validCerts($cert){
        $flagOK = true;
        $errorMsg = "";
        $data = openssl_x509_read($cert);
        $cert_data = openssl_x509_parse($data);
        // reformata a data de validade;
        $ano = substr($cert_data['validTo'],0,2);
        $mes = substr($cert_data['validTo'],2,2);
        $dia = substr($cert_data['validTo'],4,2);
        //obtem o timeestamp da data de validade do certificado
        $dValid = gmmktime(0,0,0,$mes,$dia,$ano);
        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0,0,0,date("m"),date("d"),date("Y"));
        // compara a data de validade com a data atual
        if ($dValid < $dHoje ){
            $flagOK = false;
            $errorMsg = "A Validade do certificado expirou em ["  . $dia.'/'.$mes.'/'.$ano . "]";
        } else {
            $flagOK = $flagOK && true;
        }
        //diferença em segundos entre os timestamp
        $diferenca = $dValid - $dHoje;
        // convertendo para dias
        $diferenca = round($diferenca /(60*60*24),0);
        //carregando a propriedade
        $daysToExpire = $diferenca;
        // convertendo para meses e carregando a propriedade
        $m = ($ano * 12 + $mes);
        $n = (date("y") * 12 + date("m"));
        //numero de meses até o certificado expirar
        $monthsToExpire = ($m-$n);
        $this->certMonthsToExpire = $monthsToExpire;
        $this->certDaysToExpire = $daysToExpire;
        return array('status'=>$flagOK,'error'=>$errorMsg,'meses'=>$monthsToExpire,'dias'=>$daysToExpire);
    } //fim __validCerts

    /**
     * __cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura do xml
     *
     * @name __cleanCerts
     * @version 1.00
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param    $certFile
     * @return   string contendo a chave digital limpa
     * @access   private
     **/
    protected function __cleanCerts($certFile){
        //carregar a chave publica do arquivo pem
        $pubKey = file_get_contents($certFile);
        //inicializa variavel
        $data = '';
        //carrega o certificado em um array usando o LF como referencia
        $arCert = explode("\n", $pubKey);
        foreach ($arCert AS $curData) {
            //remove a tag de inicio e fim do certificado
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                //carrega o resultado numa string
                $data .= trim($curData);
            }
        }
        return $data;
    }//fim __cleanCerts

   /**
    * __convertTime
    * Converte o campo data time retornado pelo webservice
    * em um timestamp unix
    *
    * @name __convertTime
    * @version 1.00
    * @package NFePHP
    * @author Roberto L. Machado <linux.rlm at gmail dot com>
    * @param    string   $DH
    * @return   timestamp
    * @access   private
    **/
    protected function __convertTime($DH){
        if ($DH){
            $aDH = explode('T',$DH);
            $adDH = explode('-',$aDH[0]);
            $atDH = explode(':',$aDH[1]);
            $timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);
            return $timestampDH;
        }
    } //fim __convertTime

    /**
     * listDir
     * Método para obter todo o conteúdo de um diretorio, e
     * que atendam ao critério indicado.
     * @version 2.12
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $dir Diretorio a ser pesquisado
     * @param string $fileMatch Critério de seleção pode ser usados coringas como *-nfe.xml
     * @param boolean $retpath se true retorna o path completo dos arquivos se false so retorna o nome dos arquivos
     * @return mixed Matriz com os nome dos arquivos que atendem ao critério estabelecido ou false
     */
    public function listDir($dir,$fileMatch,$retpath=false){
        if ( trim($fileMatch) != '' && trim($dir) != '' ) {
            //passar o padrão para minúsculas
            $fileMatch = strtolower($fileMatch);
            //cria um array limpo
            $aName=array();
            //guarda o diretorio atual
            $oldDir = getcwd().DIRECTORY_SEPARATOR;
            //verifica se o parametro $dir define um diretorio real
            if ( is_dir($dir) ) {
                //mude para o novo diretorio
                chdir($dir);
                //pegue o diretorio
                $diretorio = getcwd().DIRECTORY_SEPARATOR;
                if (strtolower($dir) != strtolower($diretorio)) {
                    $this->errMsg = "Falha não há permissão de leitura no diretorio escolhido\n";
                    return false;
                }
                //abra o diretório
                $ponteiro  = opendir($diretorio);
                $x = 0;
                // monta os vetores com os itens encontrados na pasta
                while (false !== ($file = readdir($ponteiro))) {
                    //procure se não for diretorio
                    if ($file != "." && $file != ".." ) {
                        if ( !is_dir($file) ){
                            $tfile = strtolower($file);
                            //é um arquivo então
                            //verifique se combina com o $fileMatch
                            if (fnmatch($fileMatch, $tfile)){
                                if ($retpath){
                                    $aName[$x] = $dir.$file;
                                } else {
                                    $aName[$x] = $file;
                                }
                                $x++;
                            }
                        } //endif é diretorio
                    } //endif é  . ou ..
                }//endwhile
                closedir($ponteiro);
                //volte para o diretorio anterior
                chdir($oldDir);
            }//endif do teste se é um diretorio
        }//endif
	sort($aName);
        return $aName;
    } //fim listDir

    /**
     * __sendSOAP
     * Estabelece comunicaçao com servidor SOAP 1.1 ou 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1 
     *
     * @name __sendSOAP
     * @version 2.11
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $urlsefaz
     * @param string $namespace
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param numeric $ambiente  tipo de ambiente 1 - produção e 2 - homologação
     * @param string $UF unidade da federação, necessário para diferenciar AM, MT e PR
     * @return mixed false se houve falha ou o retorno em xml do SEFAZ
     */
    protected function __sendSOAP($urlsefaz,$namespace,$cabecalho,$dados,$metodo,$ambiente,$UF=''){
        //ativa retorno de erros soap
        use_soap_error_handler(true);
        //versão do SOAP
        $soapver = SOAP_1_2;
        if($ambiente == 1){
            $ambiente = 'producao';
        } else {
            $ambiente = 'homologacao';
        }
        //monta a terminação do URL
        switch ($metodo){
                case 'nfeRecepcaoLote2':
                    $usef = "_NFeRecepcao2.asmx";
                    break;
                case 'nfeRetRecepcao2':
                    $usef = "_NFeRetRecepcao2.asmx";
                    break;
                case 'nfeCancelamentoNF2':
                    $usef = "_NFeCancelamento2.asmx";
                    break;
                case 'nfeInutilizacaoNF2':
                    $usef = "_NFeInutilizacao2.asmx";
                    break;
                case 'nfeConsultaNF2':
                    $usef = "_NFeConsulta2.asmx";
                    break;
                case 'nfeStatusServicoNF2':
                    $usef = "_NFeStatusServico2.asmx";
                    break;
                case 'consultaCadastro':
                    $usef = "";
                    break;
        }
        //para os estados de AM, MT e PR é necessário usar wsdl baixado para acesso ao webservice
        if ($UF=='AM' || $UF=='MT' || $UF=='PR'){
            $urlsefaz = "$this->URLbase/wsdl/2.00/$ambiente/$UF$usef";
        }
       if ($this->enableSVAN){
            //se for SVAN montar o URL baseado no metodo e ambiente
            $urlsefaz = "$this->URLbase/wsdl/2.00/$ambiente/SVAN$usef";
        } 
        //verificar se SCAN ou SVAN
        if ($this->enableSCAN){
            //se for SCAN montar o URL baseado no metodo e ambiente
            $urlsefaz = "$this->URLbase/wsdl/2.00/$ambiente/SCAN$usef";
        }
        //completa a url do serviço para baixar o arquivo WSDL
        $URL = $urlsefaz.'?WSDL';
        $this->soapDebug = $urlsefaz;
        $options = array(
            'encoding'      => 'UTF-8',
            'verifypeer'    => false,
            'verifyhost'    => false,
            'soap_version'  => $soapver,
            'style'         => SOAP_DOCUMENT,
            'use'           => SOAP_LITERAL,
            'local_cert'    => $this->certKEY,
            'trace'         => true,
            'compression'   => 0,
            'exceptions'    => true,
            'cache_wsdl'    => WSDL_CACHE_NONE 
        );
        //instancia a classe soap
        $oSoapClient = new NFeSOAP2Client($URL,$options);
        //monta o cabeçalho da mensagem
        $varCabec = new SoapVar($cabecalho,XSD_ANYXML);
        $header = new SoapHeader($namespace,'nfeCabecMsg',$varCabec);
        //instancia o cabeçalho
        $oSoapClient->__setSoapHeaders($header);
        //monta o corpo da mensagem soap
        $varBody = new SoapVar($dados,XSD_ANYXML);
        //faz a chamada ao metodo do webservices
        $resp = $oSoapClient->__soapCall($metodo, array($varBody) );
        if (is_soap_fault($resp)) {
           $soapFault = "SOAP Fault: (faultcode: {$resp->faultcode}, faultstring: {$resp->faultstring})";
        }
        $resposta = $oSoapClient->__getLastResponse();
        $this->soapDebug .= "\n" . $soapFault;
        $this->soapDebug .= "\n" . $oSoapClient->__getLastRequestHeaders();
        $this->soapDebug .= "\n" . $oSoapClient->__getLastRequest();
        $this->soapDebug .= "\n" . $oSoapClient->__getLastResponseHeaders();
        $this->soapDebug .= "\n" . $oSoapClient->__getLastResponse();
        return $resposta;
    } //fim __sendSOAP

    /**
     * __sendSOAP2
     * Função alternativa para estabelecer comunicaçao com servidor SOAP 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1 Utilizando cURL e não o SOAP nativo
     *
     * @name __sendSOAP2
     * @version 2.15
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @author Jorge Luiz Rodrigues Tomé <jlrodriguestome at hotmail dot com>
     * @param string $urlsefaz
     * @param string $namespace
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param numeric $ambiente
     * @param string $UF sem uso mantido apenas para compatibilidade com __sendSOAP
     * @return mixed false se houve falha ou o retorno em xml do SEFAZ
     */
    protected function __sendSOAP2($urlsefaz,$namespace,$cabecalho,$dados,$metodo,$ambiente='',$UF=''){
        if ($urlsefaz == ''){
            //não houve retorno
            $this->errMsg = "URL do webservice não disponível.\n";
            $this->errStatus = true;
        }
        if ($ambiente == ''){
            $ambiente = $this->tpAmb;
        }
        $data = '';
        $data .= '<?xml version="1.0" encoding="utf-8"?>';
        $data .= '<soap12:Envelope ';
        $data .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $data .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
        $data .= 'xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">';
        $data .= '<soap12:Header>';
        $data .= $cabecalho;
        $data .= '</soap12:Header>';
        $data .= '<soap12:Body>';
        $data .= $dados;
        $data .= '</soap12:Body>';
        $data .= '</soap12:Envelope>';
        //[Informational 1xx]
        $cCode['100']="Continue";
        $cCode['101']="Switching Protocols";
        //[Successful 2xx]
        $cCode['200']="OK";
        $cCode['201']="Created";
        $cCode['202']="Accepted";
        $cCode['203']="Non-Authoritative Information";
        $cCode['204']="No Content";
        $cCode['205']="Reset Content";
        $cCode['206']="Partial Content";
        //[Redirection 3xx]
        $cCode['300']="Multiple Choices";
        $cCode['301']="Moved Permanently";
        $cCode['302']="Found";
        $cCode['303']="See Other";
        $cCode['304']="Not Modified";
        $cCode['305']="Use Proxy";
        $cCode['306']="(Unused)";
        $cCode['307']="Temporary Redirect";
        //[Client Error 4xx]
        $cCode['400']="Bad Request";
        $cCode['401']="Unauthorized";
        $cCode['402']="Payment Required";
        $cCode['403']="Forbidden";
        $cCode['404']="Not Found";
        $cCode['405']="Method Not Allowed";
        $cCode['406']="Not Acceptable";
        $cCode['407']="Proxy Authentication Required";
        $cCode['408']="Request Timeout";
        $cCode['409']="Conflict";
        $cCode['410']="Gone";
        $cCode['411']="Length Required";
        $cCode['412']="Precondition Failed";
        $cCode['413']="Request Entity Too Large";
        $cCode['414']="Request-URI Too Long";
        $cCode['415']="Unsupported Media Type";
        $cCode['416']="Requested Range Not Satisfiable";
        $cCode['417']="Expectation Failed";
        //[Server Error 5xx]
        $cCode['500']="Internal Server Error";
        $cCode['501']="Not Implemented";
        $cCode['502']="Bad Gateway";
        $cCode['503']="Service Unavailable";
        $cCode['504']="Gateway Timeout";
        $cCode['505']="HTTP Version Not Supported";
        $tamanho = strlen($data);
        if($this->enableSCAN){
            //monta a terminação do URL
            switch ($metodo){
                case 'nfeRecepcaoLote2':
                    $servico = "NfeRecepcao";
                    break;
                case 'nfeRetRecepcao2':
                    $servico = "NfeRetRecepcao";
                    break;
                case 'nfeCancelamentoNF2':
                    $servico = "NfeCancelamento";
                    break;
                case 'nfeInutilizacaoNF2':
                    $servico = "NfeInutilizacao";
                    break;
                case 'nfeConsultaNF2':
                    $servico = "NfeConsulta";
                    break;
                case 'nfeStatusServicoNF2':
                    $servico = "NfeStatusServico";
                    break;
                case 'consultaCadastro':
                    $servico = "CadConsultaCadastro";
                    break;
            }
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$ambiente,'SCAN');
            $urlsefaz = $aURL[$servico]['URL'];
        } 
        $parametros = Array('Content-Type: application/soap+xml;charset=utf-8;action="'.$namespace."/".$metodo.'"','SOAPAction: "'.$metodo.'"',"Content-length: $tamanho");
        $_aspa = '"';
        $oCurl = curl_init();
        if(is_array($this->aProxy)){
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, "CURLPROXY_HTTP");
            curl_setopt($oCurl, CURLOPT_PROXY, $this->aProxy['IP'].':'.$this->aProxy['PORT']);
            if( $this->aProxy['PASS'] != '' ){
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->aProxy['USER'].':'.$this->aProxy['PASS']);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, "CURLAUTH_BASIC");
            } //fim if senha proxy
        }//fim if aProxy
        curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
        curl_setopt($oCurl, CURLOPT_PORT , 443);
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_SSLCERT, $this->pubKEY);
        curl_setopt($oCurl, CURLOPT_SSLKEY, $this->priKEY);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER,$parametros);
        $__xml = curl_exec($oCurl);
        $info = curl_getinfo($oCurl); //informações da conexão
        $txtInfo ="";
        $txtInfo .= "URL=$info[url]\n";
        $txtInfo .= "Content type=$info[content_type]\n";
        $txtInfo .= "Http Code=$info[http_code]\n";
        $txtInfo .= "Header Size=$info[header_size]\n";
        $txtInfo .= "Request Size=$info[request_size]\n";
        $txtInfo .= "Filetime=$info[filetime]\n";
        $txtInfo .= "SSL Verify Result=$info[ssl_verify_result]\n";
        $txtInfo .= "Redirect Count=$info[redirect_count]\n";
        $txtInfo .= "Total Time=$info[total_time]\n";
        $txtInfo .= "Namelookup=$info[namelookup_time]\n";
        $txtInfo .= "Connect Time=$info[connect_time]\n";
        $txtInfo .= "Pretransfer Time=$info[pretransfer_time]\n";
        $txtInfo .= "Size Upload=$info[size_upload]\n";
        $txtInfo .= "Size Download=$info[size_download]\n";
        $txtInfo .= "Speed Download=$info[speed_download]\n";
        $txtInfo .= "Speed Upload=$info[speed_upload]\n";
        $txtInfo .= "Download Content Length=$info[download_content_length]\n";
        $txtInfo .= "Upload Content Length=$info[upload_content_length]\n";
        $txtInfo .= "Start Transfer Time=$info[starttransfer_time]\n";
        $txtInfo .= "Redirect Time=$info[redirect_time]\n";
        $txtInfo .= "Certinfo=$info[certinfo]\n";
        $n = strlen($__xml);
        $x = stripos($__xml, "<");
        $xml = substr($__xml, $x, $n-$x);
        $this->soapDebug = $data."\n\n".$txtInfo."\n".$__xml;
        if ($__xml === false){
            //não houve retorno
            $this->errMsg = curl_error($oCurl) . $info['http_code'] . $cCode[$info['http_code']]."\n";
            $this->errStatus = true;
        } else {
            //houve retorno mas ainda pode ser uma mensagem de erro do webservice
            $this->errMsg = $info['http_code'] . $cCode[$info['http_code']]."\n";
            $this->errStatus = false;
        }
        curl_close($oCurl);
        return $xml;
    } //fim __sendSOAP2

    /**
     * __getNumLot
     * Obtêm o numero do último lote de envio
     *  
     * @version 1.01
     * @package NFePHP
     * @author    Roberto L. Machado <linux.rlm at gmail dot com>
     * @return numeric Numero do Lote
     */
    protected function __getNumLot(){
         $lotfile = $this->raizDir . 'config/numloteenvio.xml';
         $domLot = new DomDocument;
         $domLot->load($lotfile);
         $num = $domLot->getElementsByTagName('num')->item(0)->nodeValue;
         if( is_numeric($num) ){
            return $num;
         } else {
             //arquivo não existe, então suponho que o numero seja 1
             return 1;
         }
    }//fim __getNumLot

    /**
     * __putNumLot
     * Grava o numero do lote de envio usado
     *
     * @version 1.02
     * @package NFePHP
     * @author    Roberto L. Machado <linux.rlm at gmail dot com>
     * @param numeric $num Inteiro com o numero do lote enviado
     * @return boolean true sucesso ou FALSO erro
     */
    protected function __putNumLot($num){
        if ( is_numeric($num) ){
            $lotfile = $this->raizDir . 'config/numloteenvio.xml';
            $numLot = '<?xml version="1.0" encoding="UTF-8"?><root><num>' . $num . '</num></root>';
            if (!file_put_contents($lotfile,$numLot)) {
			    //em caso de falha retorna falso
                return false;
            } else {
				//em caso de sucesso retorna true
                return true;
            }
        }
    } //fim __putNumLot
    
} //fim classe ToolsNFePHP

/**
 * Classe complementar
 * necessária para a comunicação SOAP 1.2
 * Remove algumas tags para adequar a comunicação
 * ao padrão "esquisito" utilizado pelas SEFAZ
 *
 * @version 1.4
 * @package NFePHP
 * @author  Roberto L. Machado <linux.rlm at gmail dot com>
 *
 */
class NFeSOAP2Client extends SoapClient {
    function __doRequest($request, $location, $action, $version,$one_way = 0) {
        $request = str_replace(':ns1', '', $request);
        $request = str_replace('ns1:', '', $request);
        $request = str_replace("\n", '', $request);
        $request = str_replace("\r", '', $request);
        return parent::__doRequest($request, $location, $action, $version);
    }
} //fim NFeSOAP2Client