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
 *      e conforme Manual de Integração Versão 5
 *
 * Atenção: Esta classe não mantêm a compatibilidade com a versão 1.10 da SEFAZ !!!
 *
 * @package   NFePHP
 * @name      ToolsNFePHP
 * @version   3.0.79
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Allan Rett <allanlimao at gmail dot com>
 *              Antonio Neykson Turbano de Souza <neykson at gmail dot com>
 *              Bernardo Silva <bernardo at datamex dot com dot br>
 *              Bruno Bastos <brunomauro at gmail dot com>
 *              Bruno Lima <brunofileh at gmail.com>
 *              Bruno Tadeu Porto <brunotporto at gmail dot com>
 *              Cleiton Perin <cperin20 at gmail dot com>
 *              Daniel Viana <daniellista at gmail dot com>
 *              Diego Mosela <diego dot caicai at gmail dot com>
 *              Edilson Carlos Belluomini <edilson at maxihelp dot com dot br>
 *              Eduardo Gusmão <eduardo.intrasis at gmail dot com>
 *              Eduardo Pacheco <eduardo at onlyone dot com dot br>
 *              Fabio A. Silva <binhoouropreto at gmail dot com>
 *              Fabricio Veiga <fabriciostuff at gmail dot com>
 *              Felipe Bonato <montanhats at gmail dot com>
 *              Fernando Mertins <fernando dot mertins at gmail dot com>
 *              Gilmar de Paula Fiocca <gilmar at tecnixinfo dot com dot br>
 *              Giovani Paseto <giovaniw2 at gmail dot com>
 *              Giuliano Nascimento <giusoft at hotmail dot com>
 *              Glauber Cini <glaubercini at gmail dot com>
 *              Guilherme Filippo <guilherme at macromind dot com dot br>
 *              Jorge Luiz Rodrigues Tomé <jlrodriguestome at hotmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Mario Almeida <prog dot almeida at gmail.com>
 *              Nataniel Fiuza <natan at laxus dot com dot br>
 *              Odair Jose Santos Junior <odairsantosjunior at gmail dot com>
 *              Paulo Gabriel Coghi <paulocoghi at gmail dot com>
 *              Paulo Henrique Demori <phdemori at hotmail dot com>
 *              Rafael Stavarengo <faelsta at gmail dot com>
 *              Roberto Spadim <rspadim at gmail dot com>
 *              Romulo Cordeiro <rrromulo at gmail dot com>
 *              Vinicius L. Azevedo <vinilazev at gmail dot com>
 *              Walber da Silva Sales <eng dot walber at gmail dot com>
 *
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}
/**
 * Classe principal "CORE class"
 */
class ToolsNFePHP
{

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
     * cccDir
     * Diretorio onde são armazenados os pedidos das cartas de correção
     * @var string
     */
    public $cccDir='';
    /**
     * evtDir
     * Diretorio de arquivos dos eventos como as Manuifetações do Destinatário
     * @var string
     */
    public $evtDir='';
    /**
     * dpcDir
     * Diretorio de arquivos dos DPEC
     * @var string
     */
    public $dpcDir='';
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
    public $xmlURLfile='nfe_ws2.xml';
    /**
     * enableSCAN
     * Habilita contingência ao serviço SCAN ao invés do webservice estadual
     * @var boolean
     */
    public $enableSCAN=false;
    /**
     * enableDEPC
     * Habilita contingência por serviço DPEC ao invés do webservice estadual
     * @var boolean
     */
    public $enableDPEC=false;
    /**
     * enableSVAN
     * Indica o acesso ao serviço SVAN
     * @var boolean
     */
    public $enableSVAN=false;
    /**
     * enableSVRS
     * Indica o acesso ao serviço SVRS
     * @var boolean
     */
    public $enableSVRS=false;
    /**
     * modSOAP
     * Indica o metodo SOAP a usar 1-SOAP Nativo ou 2-cURL
     * @var string
     */
    public $modSOAP='2';
    /**
     * soapTimeout
     * Limite de tempo que o SOAP aguarda por uma conexão
     * @var integer 0-indefinidamente ou numero de segundos
     */
    public $soapTimeout = 10;
    /**
     * tpAmb
     * Tipo de ambiente 1-produção 2-homologação
     * @var string
     */
    protected $tpAmb='';
    /**
     * schemeVer
     * String com o nome do subdiretorio onde se encontram os schemas
     * atenção é case sensitive
     * @var string
     */
    protected $schemeVer;
    /**
     * aProxy
     * Matriz com as informações sobre o proxy da rede para uso pelo SOAP
     * @var array IP PORT USER PASS
     */
    public $aProxy='';
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
     * pfxTimeStamp
     * Timestamp da validade do certificado A1 PKCS12 .pfx
     * @var timestamp
     */
    private $pfxTimestamp=0;
    /**
     * priKEY
     * Path completo para a chave privada em formato pem
     * @var string
     */
    protected $priKEY='';
    /**
     * pubKEY
     * Path completo para a chave public em formato pem
     * @var string
     */
    protected $pubKEY='';
    /**
     * certKEY
     * Path completo para o certificado (chave privada e publica) em formato pem
     * @var string
     */
    protected $certKEY='';
    /**
     * empName
     * Razão social da Empresa
     * @var string
     */
    protected $empName='';
    /**
     * cnpj
     * CNPJ do emitente
     * @var string
     */
    protected $cnpj='';
    /**
     * cUF
     * Código da unidade da Federação IBGE
     * @var string
     */
    protected $cUF='';
    /**
     * UF
     * Sigla da Unidade da Federação
     * @var string
     */
    protected $UF='';
    /**
     * timeZone
     * Zona de tempo GMT
     */
    protected $timeZone = '-03:00';
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
     * debugMode
     * Ativa ou desativa as mensagens de debug da classe
     * @var string
     */
    protected $debugMode=2;
     /**
     * URLxsi
     * Instância do WebService
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
                               'SCAN'=>'SCAN',
                               'SVAN'=>'SVAN',
                               'SVRS'=>'SVRS',
                               'DPEC'=>'DPEC');
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
                             'TO'=>'17',
                             'SVAN'=>'91');

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
                          '53'=>'DF',
                          '91'=>'SVAN');

    /**
     * tzUFlist
     * Lista das zonas de tempo para os estados brasileiros
     * @var array
     */
    private $tzUFlist = array('AC'=>'America/Rio_Branco',
                              'AL'=>'America/Sao_Paulo',
                              'AM'=>'America/Manaus',
                              'AP'=>'America/Sao_Paulo',
                              'BA'=>'America/Bahia',
                              'CE'=>'America/Fortaleza',
                              'DF'=>'America/Sao_Paulo',
                              'ES'=>'America/Sao_Paulo',
                              'GO'=>'America/Sao_Paulo',
                              'MA'=>'America/Sao_Paulo',
                              'MG'=>'America/Sao_Paulo',
                              'MS'=>'America/Campo_Grande',
                              'MT'=>'America/Cuiaba',
                              'PA'=>'America/Belem',
                              'PB'=>'America/Sao_Paulo',
                              'PE'=>'America/Recife',
                              'PI'=>'America/Sao_Paulo',
                              'PR'=>'America/Sao_Paulo',
                              'RJ'=>'America/Sao_Paulo',
                              'RN'=>'America/Sao_Paulo',
                              'RO'=>'America/Porto_Velho',
                              'RR'=>'America/Boa_Vista',
                              'RS'=>'America/Sao_Paulo',
                              'SC'=>'America/Sao_Paulo',
                              'SE'=>'America/Sao_Paulo',
                              'SP'=>'America/Sao_Paulo',
                              'TO'=>'America/Sao_Paulo');

    /**
     * aMail
     * Matriz com os dados para envio de emails
     * FROM HOST USER PASS
     * @var array
     */
    public $aMail='';
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
     * exceptions
     * Ativa ou desativa o uso de exceções para transporte de erros
     * @var boolean
     */
    protected $exceptions = false;

    /////////////////////////////////////////////////
    // CONSTANTES usadas no controle das exceções
    /////////////////////////////////////////////////
    const STOP_MESSAGE  = 0; // apenas um aviso, o processamento continua
    const STOP_CONTINUE = 1; // quationamento ?, perecido com OK para continuar o processamento
    const STOP_CRITICAL = 2; // Erro critico, interrupção total

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
     * @param array $aConfig Opcional dados de configuração
     * @param number $mododebug Opcional 2-Não altera nenhum parâmetro 1-SIM ou 0-NÃO (2 default)
     * @return  boolean true sucesso false Erro
     */
    function __construct($aConfig = '', $mododebug = 2, $exceptions = false)
    {
        if (is_numeric($mododebug)) {
            $this->debugMode = $mododebug;
        }
        if ($mododebug == 1) {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }
        if ($mododebug == 0) {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        if ($exceptions) {
            $this->exceptions = true;
        }
        //obtem o path da biblioteca
        $this->raizDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
        //verifica se foi passado uma matriz de configuração na inicialização da classe
        if (is_array($aConfig)) {
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
            if (isset($aConfig['certsDir'])) {
                $this->certsDir =  $aConfig['certsDir'];
            }
            if ($aConfig['proxyIP'] != '') {
                $this->aProxy = array('IP'=>$aConfig['proxyIP'],'PORT'=>$aConfig['proxyPORT'],'USER'=>$aConfig['proxyUSER'],'PASS'=>$aConfig['proxyPASS']);
            }
            if ($aConfig['mailFROM'] != '') {
                $this->aMail = array('mailFROM'=>$aConfig['mailFROM'],'mailHOST'=>$aConfig['mailHOST'],'mailUSER'=>$aConfig['mailUSER'],'mailPASS'=>$aConfig['mailPASS'],'mailPROTOCOL'=>$aConfig['mailPROTOCOL'],'mailFROMmail'=>$aConfig['mailFROMmail'],'mailFROMname'=>$aConfig['mailFROMname'],'mailREPLYTOmail'=>$aConfig['mailREPLYTOmail'],'mailREPLYTOname'=>$aConfig['mailREPLYTOname']);
            }
        } else {
            //testa a existencia do arquivo de configuração
            if (is_file($this->raizDir . 'config' . DIRECTORY_SEPARATOR . 'config.php')) {
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
                if (isset($certsDir)) {
                    $this->certsDir =  $certsDir;
                }
                if ($proxyIP != '') {
                    $this->aProxy = array('IP'=>$proxyIP,'PORT'=>$proxyPORT,'USER'=>$proxyUSER,'PASS'=>$proxyPASS);
                }
                if ($mailFROM != '') {
                    $this->aMail = array('mailFROM'=>$mailFROM,'mailHOST'=>$mailHOST,'mailUSER'=>$mailUSER,'mailPASS'=>$mailPASS,'mailPROTOCOL'=>$mailPROTOCOL,'mailFROMmail'=>$mailFROMmail,'mailFROMname'=>$mailFROMname,'mailREPLYTOmail'=>$mailREPLYTOmail,'mailREPLYTOname'=>$mailREPLYTOname);
                }
            } else {
                // caso não exista arquivo de configuração retorna erro
                $msg = "Não foi localizado o arquivo de configuração.\n";
                $this->__setError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg, self::STOP_CRITICAL);
                }
                return false;
            }
        }
        //estabelece o ambiente
        $sAmb = ($this->tpAmb == 2) ? 'homologacao' : 'producao';
        //carrega propriedade com ano e mes ex. 200911
        $this->anoMes = date('Ym');
        //carrega o caminho para os schemas
        $this->xsdDir = $this->raizDir . 'schemes'. DIRECTORY_SEPARATOR;
        //carrega o caminho para os certificados caso não tenha sido passado por config
        if (empty($this->certsDir)) {
            $this->certsDir =  $this->raizDir . 'certs'. DIRECTORY_SEPARATOR;
        }
        //carrega o caminho para as imegens
        $this->imgDir =  $this->raizDir . 'images'. DIRECTORY_SEPARATOR;
        //verifica o ultimo caracter da variável $arqDir
        // se não for um DIRECTORY_SEPARATOR então colocar um
        if (substr($this->arqDir, -1, 1) != DIRECTORY_SEPARATOR) {
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
        $this->cccDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'cartacorrecao' . DIRECTORY_SEPARATOR;
        $this->evtDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'eventos' . DIRECTORY_SEPARATOR;
        $this->dpcDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'dpec' . DIRECTORY_SEPARATOR;
        $this->temDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'temporarias' . DIRECTORY_SEPARATOR;
        $this->recDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'recebidas' . DIRECTORY_SEPARATOR;
        $this->conDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'consultadas' . DIRECTORY_SEPARATOR;
        $this->pdfDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
        //monta a arvore de diretórios necessária e estabelece permissões de acesso
        if (!is_dir($this->arqDir)) {
            mkdir($this->arqDir, 0777);
        }
        if (!is_dir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb)) {
            mkdir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb, 0777);
        }
        if (!is_dir($this->entDir)) {
            mkdir($this->entDir, 0777);
        }
        if (!is_dir($this->assDir)) {
            mkdir($this->assDir, 0777);
        }
        if (!is_dir($this->valDir)) {
            mkdir($this->valDir, 0777);
        }
        if (!is_dir($this->rejDir)) {
            mkdir($this->rejDir, 0777);
        }
        if (!is_dir($this->envDir)) {
            mkdir($this->envDir, 0777);
        }
        if (!is_dir($this->aprDir)) {
            mkdir($this->aprDir, 0777);
        }
        if (!is_dir($this->denDir)) {
            mkdir($this->denDir, 0777);
        }
        if (!is_dir($this->repDir)) {
            mkdir($this->repDir, 0777);
        }
        if (!is_dir($this->canDir)) {
            mkdir($this->canDir, 0777);
        }
        if (!is_dir($this->inuDir)) {
            mkdir($this->inuDir, 0777);
        }
        if (!is_dir($this->cccDir)) {
            mkdir($this->cccDir, 0777);
        }
        if (!is_dir($this->evtDir)) {
            mkdir($this->evtDir, 0777);
        }
        if (!is_dir($this->dpcDir)) {
            mkdir($this->dpcDir, 0777);
        }
        if (!is_dir($this->temDir)) {
            mkdir($this->temDir, 0777);
        }
        if (!is_dir($this->recDir)) {
            mkdir($this->recDir, 0777);
        }
        if (!is_dir($this->conDir)) {
            mkdir($this->conDir, 0777);
        }
        if (!is_dir($this->pdfDir)) {
            mkdir($this->pdfDir, 0777);
        }
        //carregar uma matriz com os dados para acesso aos WebServices SEFAZ
        $this->aURL = $this->loadSEFAZ($this->raizDir . 'config'. DIRECTORY_SEPARATOR . $this->xmlURLfile,$this->tpAmb,$this->UF);
        //se houver erro no carregamento dos certificados passe para erro
        if (!$retorno = $this->__loadCerts()) {
            $msg = "Erro no carregamento dos certificados.";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        //definir o timezone default para o estado do emitente
        $timezone = $this->tzUFlist[$this->UF];
        date_default_timezone_set($timezone);
        //estados que participam do horario de verão
        $aUFhv = array('ES','GO','MG','MS','PR','RJ','RS','SP','SC');
        //corrigir o timeZone
        if ($this->UF == 'AC' ||
            $this->UF == 'AM' ||
            $this->UF == 'MT' ||
            $this->UF == 'MS' ||
            $this->UF == 'RO' ||
            $this->UF == 'RR' ) {
            $this->timeZone = '-04:00';
        }
        //verificar se estamos no horário de verão *** depende da configuração do servidor ***
        if (date('I') == 1) {
            //estamos no horario de verão verificar se o estado está incluso
            if (in_array($this->UF, $aUFhv)) {
                $itz = (int) $this->timeZone;
                $itz++;
                $this->timeZone = '-'.sprintf("%02d",abs($itz)).':00'; //poderia ser obtido com date('P')
            }
        }//fim check horario verao
        return true;
    } //fim __construct

   /**
    * validXML
    * Verifica o xml com base no xsd
    * Esta função pode validar qualquer arquivo xml do sistema de NFe
    * Há um bug no libxml2 para versões anteriores a 2.7.3
    * que causa um falso erro na validação da NFe devido ao
    * uso de uma marcação no arquivo tiposBasico_v1.02.xsd
    * onde se le {0 , } substituir por *
    * A validação não deve ser feita após a inclusão do protocolo !!!
    * Caso seja passado uma NFe ainda não assinada a falta da assinatura será desconsiderada.
    * @name validXML
    * @author Roberto L. Machado <linux.rlm at gmail dot com>
    * @param    string  $xml  string contendo o arquivo xml a ser validado ou seu path
    * @param    string  $xsdfile Path completo para o arquivo xsd
    * @param    array   $aError Variável passada como referencia irá conter as mensagens de erro se houverem
    * @return   boolean
    */
    public function validXML($xml='', $xsdFile='', &$aError){
        try{
            $flagOK = true;
            if(strlen($xml)==0){
                $msg = 'Você deve passar o conteudo do xml assinado como parâmetro ou o caminho completo até o arquivo.';
                $aError[] = $msg;
                throw new nfephpException($msg);
            }
            $err = "";
            $dom = $this->__getXmlDom($xml, $err);
            // pega a assinatura
            $Signature = $dom->getElementsByTagName('Signature')->item(0);
            //recupera os erros da libxml
            $errors = libxml_get_errors();
            if (!empty($errors)) {
                //o dado passado como $docXml não é um xml
                $msg = 'O dado informado não é um XML ou não foi encontrado. Você deve passar o conteudo de um arquivo xml assinado como parâmetro.';
                $aError[] = $msg;
                throw new nfephpException($msg);
            }
            if($xsdFile==''){
                if (is_file($xml)){
                    $contents = file_get_contents($xml);
                } else {
                    $contents = $xml;
                }
                $sxml = simplexml_load_string($contents);
                $nome = $sxml->getName();
                $sxml = null;
                //determinar qual o arquivo de schema válido
                //buscar o nome do scheme
                switch ($nome){
                    case 'evento':
                        //obtem o node com a versão
                        $node = $dom->documentElement;
                        //obtem a versão do layout
                        $ver = trim($node->getAttribute("versao"));
                        $tpEvento = $node->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                        switch ($tpEvento){
                            case '110110':
                                //carta de correção
                                $xsdFile = "CCe_v$ver.xsd";
                                break;
                            default:
                                $xsdFile = "";
                                break;
                        }
                        break;
                    case 'envEvento':
                        //obtem o node com a versão
                        $node = $dom->getElementsByTagName('evento')->item(0);
                        //obtem a versão do layout
                        $ver = trim($node->getAttribute("versao"));
                        $tpEvento = $node->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                        switch ($tpEvento){
                            case '110110':
                                //carta de correção
                                $xsdFile = "envCCe_v$ver.xsd";
                                break;
                            default:
                                $xsdFile = "envEvento_v$ver.xsd";
                                break;
                        }
                        break;
                    case 'NFe':
                        //obtem o node com a versão
                        $node = $dom->getElementsByTagName('infNFe')->item(0);
                        //obtem a versão do layout
                        $ver = trim($node->getAttribute("versao"));
                        $xsdFile = "nfe_v$ver.xsd";
                        break;
                    case 'nfeProc':
                        //obtem o node com a versão
                        $node = $dom->documentElement;
                        //obtem a versão do layout
                        $ver = trim($node->getAttribute("versao"));
                        $xsdFile = "procNFe_v$ver.xsd";
                        break;
                    default:
                        //obtem o node com a versão
                        $node = $dom->documentElement;
                        //obtem a versão do layout
                        $ver = trim($node->getAttribute("versao"));
                        $xsdFile = $nome."_v".$ver.".xsd";
                        break;
                }
                $aFile = $this->listDir($this->xsdDir . $this->schemeVer. DIRECTORY_SEPARATOR,$xsdFile,true);
                if (isset($aFile[0]) && !$aFile[0]) {
                    $msg = "Erro na localização do schema xsd.\n";
                    $aError[] = $msg;
                    throw new nfephpException($msg);
                } else {
                    $xsdFile = $aFile[0];
                }
            }
            //limpa erros anteriores
            libxml_clear_errors();
            // valida o xml com o xsd
            if ( !$dom->schemaValidate($xsdFile) ) {
                /**
                 * Se não foi possível validar, você pode capturar
                 * todos os erros em um array
                 * Cada elemento do array $arrayErrors
                 * será um objeto do tipo LibXmlError
                 */
                // carrega os erros em um array
                $aIntErrors = libxml_get_errors();
                $flagOK = false;
                if (!isset($Signature)){
                    // remove o erro de falta de assinatura
                    foreach ($aIntErrors as $k=>$intError){
                        if(strpos($intError->message,'( {http://www.w3.org/2000/09/xmldsig#}Signature )')!==false){
                            // remove o erro da assinatura, se tiver outro meio melhor (atravez dos erros de codigo) e alguem souber como tratar por eles, por favor contribua...
                            unset($aIntErrors[$k]);
                        }
                    }
                    reset($aIntErrors);
                    $flagOK = true;
                }//fim teste Signature
                $msg = '';
                foreach ($aIntErrors as $intError){
                    $flagOK = false;
                    $en = array("{http://www.portalfiscal.inf.br/nfe}"
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

                    $pt = array(""
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

                    switch ($intError->level) {
                        case LIBXML_ERR_WARNING:
                            $aError[] = " Atençao $intError->code: " . str_replace($en,$pt,$intError->message);
                            break;
                        case LIBXML_ERR_ERROR:
                            $aError[] = " Erro $intError->code: " . str_replace($en,$pt,$intError->message);
                            break;
                        case LIBXML_ERR_FATAL:
                            $aError[] = " Erro Fatal $intError->code: " . str_replace($en,$pt,$intError->message);
                            break;
                    }
                    $msg .= str_replace($en,$pt,$intError->message);
                }
            } else {
                $flagOK = true;
            }
            if(!$flagOK){
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } //fim validXML

    /**
     * addProt
     * Este método adiciona a tag do protocolo a NFe, preparando a mesma
     * para impressão e envio ao destinatário.
     * Também pode ser usada para substituir o protocolo de autorização
     * pelo protocolo de cancelamento, nesse caso apenas para a gestão interna
     * na empresa, esse arquivo com o cancelamento não deve ser enviado ao cliente.
     *
     * @name addProt
     * @param string $nfefile path completo para o arquivo contendo a NFe
     * @param string $protfile path completo para o arquivo contendo o protocolo, cancelamento ou evento de cancelamento
     * @return string Retorna a NFe com o protocolo
     */
    public function addProt($nfefile='', $protfile='') {
        try {
            if($nfefile == '' || $protfile == ''){
                $msg = 'Para adicionar o protocolo, ambos os caminhos devem ser passados. Para a nota e para o protocolo!';
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if(!is_file($nfefile) || !is_file($protfile) ){
                $msg = 'Algum dos arquivos não foi localizado no caminho indicado ! ' . $nfefile. ' ou ' .$protfile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //carrega o arquivo na variável
            $docnfe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            $xmlnfe = file_get_contents($nfefile);
            if (!$docnfe->loadXML($xmlnfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)){
                $msg = 'O arquivo indicado como NFe não é um XML! ' . $nfefile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $nfe = $docnfe->getElementsByTagName("NFe")->item(0);
            if(!isset($nfe)){
                $msg = 'O arquivo indicado como NFe não é um xml de NFe! ' . $nfefile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $infNFe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNFe->getAttribute("versao"));
            $id = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/','', $id);
            $DigestValue = !empty($docnfe->getElementsByTagName('DigestValue')->item(0)->nodeValue) ? $docnfe->getElementsByTagName('DigestValue')->item(0)->nodeValue : '';
            if ($DigestValue == ''){
                $msg = 'O XML da NFe não está assinado! ' . $nfefile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //carrega o protocolo e seus dados
            //protocolo do lote enviado
            $prot = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $prot->formatOutput = false;
            $prot->preserveWhiteSpace = false;
            $xmlprot = file_get_contents($protfile);
            if (!$prot->loadXML($xmlprot,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)){
                $msg = 'O arquivo indicado para ser protocolado na NFe é um XML! ' . $protfile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //protocolo de autorização
            $protNFe = $prot->getElementsByTagName("protNFe")->item(0);
            if (isset($protNFe)){
                $protver     = trim($protNFe->getAttribute("versao"));
                $tpAmb       = $protNFe->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $protNFe->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $protNFe->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $protNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
                $nProt       = $protNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
                $digVal      = $protNFe->getElementsByTagName("digVal")->item(0)->nodeValue;
                $cStat       = $protNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
                $xMotivo     = $protNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                if ($DigestValue != $digVal){
                    $msg = 'Inconsistência! O DigestValue da NFe não combina com o do digVal do protocolo indicado!';
                    throw new nfephpException($msg, self::STOP_CRITICAL);
                }
            }
            //cancelamento antigo
            $retCancNFe = $prot->getElementsByTagName("retCancNFe")->item(0);
            if (isset($retCancNFe)){
                $protver     = trim($retCancNFe->getAttribute("versao"));
                $tpAmb       = $retCancNFe->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $retCancNFe->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $retCancNFe->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $retCancNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
                $nProt       = $retCancNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
                $cStat       = $retCancNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
                $xMotivo     = $retCancNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                $digVal      = $DigestValue;
            }
            //cancelamento por evento NOVO
            $retEvento = $prot->getElementsByTagName("retEvento")->item(0);
            if (isset($retEvento)){
                $protver     = trim($retEvento->getAttribute("versao"));
                $tpAmb       = $retEvento->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $retEvento->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $retEvento->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $retEvento->getElementsByTagName("dhRegEvento")->item(0)->nodeValue;
                $nProt       = $retEvento->getElementsByTagName("nProt")->item(0)->nodeValue;
                $cStat       = $retEvento->getElementsByTagName("cStat")->item(0)->nodeValue;
                $tpEvento    = $retEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue;
                $xMotivo     = $retEvento->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                $digVal      = $DigestValue;
                if ($tpEvento != '110111'){
                    $msg = 'O arquivo indicado para ser anexado não é um evento de cancelamento! ' . $protfile;
                    throw new nfephpException($msg, self::STOP_CRITICAL);
                }
            }
            if(!isset($protNFe) && !isset($retCancNFe) && !isset($retEvento)){
                $msg = 'O arquivo indicado para ser protocolado a NFe não é um protocolo nem de cancelamento! ' . $protfile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if ($chNFe != $chave){
                $msg = 'O protocolo indicado pertence a outra NFe ... os numertos das chaves não combinam !';
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
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
            //inclui a tag NFe
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
            $infProt_att1 = $infProt->appendChild($procnfe->createAttribute('Id'));
            $infProt_att1->appendChild($procnfe->createTextNode('ID'.$nProt));
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
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $procXML;
    } //fim addProt

    /**
     * addB2B
     * Adiciona o xml referente a comunicação B2B à NFe, conforme padrão ANFAVEA+GS1
     *
     * @param string $nfefile path para o arquivo com a nfe protocolada e autorizada
     * @param string $b2bfile path para o arquivo xml padrão ANFAVEA+GS1 e NT2013_002
     * @param string $tagB2B Tag principar do xml B2B pode ser NFeB2B ou NFeB2BFin
     * @return mixed FALSE se houve erro ou xml com a nfe+b2b
     */
    public function addB2B($nfefile='',$b2bfile='',$tagB2B=''){
        try {
            if($nfefile == '' || $b2bfile == ''){
                $msg = 'Para adicionar o arquivo B2B, ambos os caminhos devem ser passados. Para a nota e para o B2B!';
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if(!is_file($nfefile) || !is_file($b2bfile) ){
                $msg = 'Algum dos arquivos não foi localizado no caminho indicado ! ' . $nfefile. ' ou ' .$b2bfile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if ($tagB2B == ''){
                $tagB2B = 'NFeB2BFin'; //padrão anfavea
            }
            //carrega o arquivo na variável
            $docnfe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            $xmlnfe = file_get_contents($nfefile);
            if (!$docnfe->loadXML($xmlnfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)){
                $msg = 'O arquivo indicado como NFe não é um XML! ' . $nfefile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $nfeProc = $docnfe->getElementsByTagName("nfeProc")->item(0);
            if(!isset($nfeProc)){
                $msg = 'O arquivo indicado como NFe não é um xml de NFe ou não contêm o protocolo! ' . $nfefile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $infNFe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNFe->getAttribute("versao"));
            $id = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/','', $id);
            //carrega o arquivo B2B e seus dados
            //protocolo do lote enviado
            $b2b = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $b2b->formatOutput = false;
            $b2b->preserveWhiteSpace = false;
            $xmlb2b = file_get_contents($b2bfile);
            if (!$b2b->loadXML($xmlb2b,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)){
                $msg = 'O arquivo indicado como Protocolo não é um XML! ' . $protfile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $NFeB2BFin = $b2b->getElementsByTagName($tagB2B)->item(0);
            if (!isset($NFeB2BFin)){
                $msg = 'O arquivo indicado como B2B não é um XML de B2B! ' . $b2bfile;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //cria a NFe processada com a tag do protocolo
            $procb2b = new DOMDocument('1.0', 'utf-8');
            $procb2b->formatOutput = false;
            $procb2b->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProcB2B = $procb2b->createElement('nfeProcB2B');
            $procb2b->appendChild($nfeProcB2B);
            //inclui a tag NFe
            $node = $procb2b->importNode($nfeProc, true);
            $nfeProcB2B->appendChild($node);
            //inclui a tag NFeB2BFin
            $node = $procb2b->importNode($NFeB2BFin, true);
            $nfeProcB2B->appendChild($node);
            //salva o xml como string em uma variável
            $nfeb2bXML = $procb2b->saveXML();
            //remove as informações indesejadas
            $nfeb2bXML = str_replace("\n",'',$nfeb2bXML);
            $nfeb2bXML = str_replace("\r",'',$nfeb2bXML);
            $nfeb2bXML = str_replace("\s",'',$nfeb2bXML);
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $nfeb2bXML;
    }//fim addB2B

    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @name signXML
     * @param  mixed $docxml Path para o arquivo xml ou String contendo o arquivo XML a ser assinado
     * @param  string $tagid TAG do XML que devera ser assinada
     * @return mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid = '')
    {
        try {
            if ($tagid == '') {
                $msg = "Uma tag deve ser indicada para que seja assinada!!";
                throw new nfephpException($msg);
            }
            if ($docxml == '') {
                $msg = "Um xml deve ser passado para que seja assinado!!";
                    throw new nfephpException($msg);
            }
            if (is_file($docxml)) {
                $xml = file_get_contents($docxml);
            } else {
                $xml = $docxml;
            }
            //obter o chave privada para a assinatura
            //modificado para permitir a leitura de arquivos maiores
            //que o normal que é cerca de 2kBytes.
            $fp = fopen($this->priKEY, "r");
            $priv_key = '';
            while (!feof($fp)) {
                $priv_key .= fread($fp, 8192);
            }
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);
            //limpeza do xml com a retirada dos CR, LF e TAB
            $order = array("\r\n", "\n", "\r", "\t");
            $replace = '';
            $xml = str_replace($order, $replace, $xml);
            // Habilita a manipulaçao de erros da libxml
            libxml_use_internal_errors(true);
            //limpa erros anteriores que possam estar em memória
            libxml_clear_errors();
            //carrega o documento DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
            $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
            $xmldoc->formatOutput = false;
            //é muito importante deixar ativadas as opçoes para limpar os espacos em branco
            //e as tags vazias
            if ($xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $root = $xmldoc->documentElement;
            } else {
                $msg = "Erro ao carregar XML, provavel erro na passagem do parâmetro docxml ou no próprio xml!!";
                $errors = libxml_get_errors();
                if (!empty($errors)) {
                    $i = 1;
                    foreach ($errors as $error) {
                        $msg .= "\n  [$i]-" . trim($error->message);
                    }
                    libxml_clear_errors();
                }
                throw new nfephpException($msg);
            }
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            if (!isset($node)) {
                $msg = "A tag < $tagid > não existe no XML!!";
                throw new nfephpException($msg);
            }
            //extrai o atributo ID com o numero da NFe de 44 digitos
            $id = trim($node->getAttribute("Id"));
            $idnome = preg_replace('/[^0-9]/', '', $id);
            //extrai e canoniza os dados da tag para uma string
            $dados = $node->C14N(false, false, null, null);
            //calcular o hash dos dados
            $hashValue = hash('sha1', $dados, true);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $Signature = $xmldoc->createElementNS($this->URLdsig, 'Signature');
            $root->appendChild($Signature);
            $SignedInfo = $xmldoc->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);
            //estabelece o método de canonização
            $newNode = $xmldoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
            //estabelece o método de assinatura
            $newNode = $xmldoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);
            //indica a referencia da assinatura
            $Reference = $xmldoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$id);
            //estabelece as tranformações
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
            //estabelece o método de calculo do hash
            $newNode = $xmldoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
            //carrega o valor do hash
            $newNode = $xmldoc->createElement('DigestValue', $digValue);
            $Reference->appendChild($newNode);
            //extrai e canoniza os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false, false, null, null);
            //inicializa a variavel que irá receber a assinatura
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            $resp = openssl_sign($dados, $signature, $pkeyid);
            //codifica assinatura para o padrão base64
            $signatureValue = base64_encode($signature);
            //insere o valor da assinatura digtal
            $newNode = $xmldoc->createElement('SignatureValue', $signatureValue);
            $Signature->appendChild($newNode);
            //insere a chave publica usada para conferencia da assinatura digital
            $KeyInfo = $xmldoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);
            //X509Data
            $X509Data = $xmldoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);
            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->__cleanCerts($this->pubKEY);
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate', $cert);
            $X509Data->appendChild($newNode);
            //grava em uma string o objeto DOM
            $xml = $xmldoc->saveXML();
            //libera a chave privada da memoria
            openssl_free_key($pkeyid);
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        //retorna o documento xml assinado
        return $xml;
    } //fim signXML

    /**
     * statusServico
     * Verifica o status do servico da SEFAZ
     *
     * $this->cStat = 107 OK
     *        cStat = 108 sitema paralizado momentaneamente, aguardar retorno
     *        cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     *        cStat = 113 SCAN operando mas irá parar, use o serviço Normal
     *        cStat = 114 SCAN dasativado pela SEFAZ de origem, use o serviço Normal
     * se SCAN estiver ativado usar, caso contrario aguardar pacientemente.
     * @name statusServico
     * @param  string $UF sigla da unidade da Federação
     * @param   integer $tpAmb tipo de ambiente 1-produção e 2-homologação
     * @param   integer 1 usa o __sendSOAP e 2 usa o __sendSOAP2
     * @param  array $aRetorno parametro passado por referencia irá conter a resposta da consulta em um array
     * @return mixed false ou array ['bStat'=>boolean,'cStat'=>107,'tMed'=>1,'dhRecbto'=>'12/12/2009','xMotivo'=>'Serviço em operação','xObs'=>'']
     */
    public function statusServico($UF = '', $tpAmb = '', $modSOAP = '2', &$aRetorno = '')
    {
        try {
            //retorno da funçao
            $aRetorno = array('bStat'=>false,'tpAmb'=>'','verAplic'=>'','cUF'=>'','cStat'=>'','tMed'=>'','dhRetorno'=>'','dhRecbto'=>'','xMotivo'=>'','xObs'=>'');
            // caso o parametro tpAmb seja vazio
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            // caso a sigla do estado esteja vazia
            if ($UF == '') {
                $UF = $this->UF;
            }
            //busca o cUF
            $cUF = $this->cUFlist[$UF];
            //verifica se o SCAN esta habilitado
            if (!$this->enableSCAN) {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $tpAmb, $UF);
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $tpAmb, 'SCAN');
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
            if ($modSOAP == '2') {
                $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
            }
            //verifica o retorno do SOAP
            if ($retorno) {
                //tratar dados de retorno
                $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
                $doc->formatOutput = false;
                $doc->preserveWhiteSpace = false;
                @$doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                if ($cStat == '') {
                    $msg = "Não houve retorno Soap verifique a mensagem de erro e o debug!!";
                    throw new nfephpException($msg);
                } else {
                    if ($cStat == '107') {
                        $aRetorno['bStat'] = true;
                    }
                }
                // tipo de ambiente
                $aRetorno['tpAmb'] = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
                // versão do aplicativo
                $aRetorno['verAplic'] = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
                // Código da UF que atendeu a solicitação
                $aRetorno['cUF'] = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
                // status do serviço
                $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                // tempo medio de resposta
                $aRetorno['tMed'] = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
                 // data e hora do retorno a operação (opcional)
                $aRetorno['dhRetorno'] = !empty($doc->getElementsByTagName('dhRetorno')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->__convertTime($doc->getElementsByTagName('dhRetorno')->item(0)->nodeValue)) : '';
                // data e hora da mensagem (opcional)
                $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
                // motivo da resposta (opcional)
                $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                // obervaçoes (opcional)
                $aRetorno['xObs'] = !empty($doc->getElementsByTagName('xObs')->item(0)->nodeValue) ? $doc->getElementsByTagName('xObs')->item(0)->nodeValue : '';
            } else {
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $aRetorno; //irá mudar para o xml passado pelo sefaz
    } //fim statusServico

    /**
     * consultaCadastro
     * Solicita dados de situaçao de Cadastro, somente funciona para
     * cadastros de empresas localizadas no mesmo estado do solicitante e os dados
     * retornados podem ser bastante incompletos. Não é recomendado seu uso.
     *
     * @name consultaCadastro
     * @param  string  $UF sigla da unidade da federação
     * @param   string  $IE opcional numero da inscrição estadual
     * @param   string  $CNPJ opcional numero do cnpj
     * @param   string  $CPF opcional numero do cpf
     * @param   string  $tpAmb tipo de ambiente se não informado será usado o ambiente default
     * @param   integer $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return mixed false se falha ou array se retornada informação
     */
    public function consultaCadastro($UF,$CNPJ='',$IE='',$CPF='',$tpAmb='',$modSOAP='2'){
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dados'=>array());
        $flagIE = false;
        $flagCNPJ = false;
        $flagCPF = false;
        $marca = '';
        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($CNPJ != '') {
            $flagCNPJ = true;
            $marca = 'CNPJ-'.$CNPJ;
            $filtro = "<CNPJ>".$CNPJ."</CNPJ>";
            $CPF = '';
            $IE = '';
        }
        if ($IE != ''){
            $flagIE = true;
            $marca = 'IE-'.$IE;
            $filtro = "<IE>".$IE."</IE>";
            $CNPJ = '';
            $CPF = '';
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
            $msg = "Pelo menos uma e somente uma opção deve ser indicada CNPJ, CPF ou IE !!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
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
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$UF);
        }
        //busca o cUF
        $cUF = $this->cUFlist[$UF];
        //identificação do serviço
        $servico = 'CadConsultaCadastro';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        if($urlservico==''){
            $msg = "Este serviço não está disponível para a SEFAZ $UF!!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'. $namespace . '"><ConsCad xmlns="'.$this->URLnfe.'" versao="'.$versao.'"><infCons><xServ>CONS-CAD</xServ><UF>'.$UF.'</UF>'.$filtro.'</infCons></ConsCad></nfeDadosMsg>';
        //envia a solicitação via SOAP
        if ($modSOAP == 2){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$UF);
        }
        //verifica o retorno
        if (!$retorno){
            //não houve retorno
            $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //tratar dados de retorno
        $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $infCons = $doc->getElementsByTagName('infCons')->item(0);
        $cStat = !empty($infCons->getElementsByTagName('cStat')->item(0)->nodeValue) ? $infCons->getElementsByTagName('cStat')->item(0)->nodeValue : '';
        $xMotivo = !empty($infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
        $infCad = $infCons->getElementsByTagName('infCad');
        if ($cStat == ''){
            //houve erro
            $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //tratar erro 239 Versão do arquivo XML não suportada
        if ($cStat == '239'){
            $this->__trata239($retorno, $this->UF, $tpAmb, $servico, $versao);
            $msg = "Versão do arquivo XML não suportada!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if ($cStat <> '111'){
            $msg = "Retorno de ERRO: $cStat - $xMotivo";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }

        if (isset($infCad)){
            $aRetorno['bStat'] = true;
            //existem dados do cadastro e podem ser multiplos
            $i =0;
            foreach ($infCad as $dCad){
                $ender = $dCad->getElementsByTagName('ender')->item(0);
                $aCad[$i]['CNPJ'] = !empty($dCad->getElementsByTagName('CNPJ')->item(0)->nodeValue) ? $dCad->getElementsByTagName('CNPJ')->item(0)->nodeValue : '';
                $aCad[$i]['CPF'] = !empty($dCad->getElementsByTagName('CPF')->item(0)->nodeValue) ? $dCad->getElementsByTagName('CPF')->item(0)->nodeValue : '';
                $aCad[$i]['IE'] = !empty($dCad->getElementsByTagName('IE')->item(0)->nodeValue) ? $dCad->getElementsByTagName('IE')->item(0)->nodeValue : '';
                $aCad[$i]['UF'] = !empty($dCad->getElementsByTagName('UF')->item(0)->nodeValue) ? $dCad->getElementsByTagName('UF')->item(0)->nodeValue : '';
                $aCad[$i]['cSit'] = !empty($dCad->getElementsByTagName('cSit')->item(0)->nodeValue) ? $dCad->getElementsByTagName('cSit')->item(0)->nodeValue : '';
                $aCad[$i]['indCredNFe'] = !empty($dCad->getElementsByTagName('indCredNFe')->item(0)->nodeValue) ? $dCad->getElementsByTagName('indCredNFe')->item(0)->nodeValue : '';
                $aCad[$i]['indCredCTe'] = !empty($dCad->getElementsByTagName('indCredCTe')->item(0)->nodeValue) ? $dCad->getElementsByTagName('indCredCTe')->item(0)->nodeValue : '';
                $aCad[$i]['xNome'] = !empty($dCad->getElementsByTagName('xNome')->item(0)->nodeValue) ? $dCad->getElementsByTagName('xNome')->item(0)->nodeValue : '';
                $aCad[$i]['xRegApur'] = !empty($dCad->getElementsByTagName('xRegApur')->item(0)->nodeValue) ? $dCad->getElementsByTagName('xRegApur')->item(0)->nodeValue : '';
                $aCad[$i]['CNAE'] = !empty($dCad->getElementsByTagName('CNAE')->item($i)->nodeValue) ? $dCad->getElementsByTagName('CNAE')->item($i)->nodeValue : '';
                $aCad[$i]['dIniAtiv'] = !empty($dCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue) ? $dCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue : '';
                $aCad[$i]['dUltSit'] = !empty($dCad->getElementsByTagName('dUltSit')->item(0)->nodeValue) ? $dCad->getElementsByTagName('dUltSit')->item(0)->nodeValue : '';
                if ( isset($ender) ){
                    $aCad[$i]['xLgr'] = !empty($ender->getElementsByTagName('xLgr')->item(0)->nodeValue) ? $ender->getElementsByTagName('xLgr')->item(0)->nodeValue : '';
                    $aCad[$i]['nro'] = !empty($ender->getElementsByTagName('nro')->item(0)->nodeValue) ? $ender->getElementsByTagName('nro')->item(0)->nodeValue : '';
                    $aCad[$i]['xCpl'] = !empty($ender->getElementsByTagName('xCpl')->item(0)->nodeValue) ? $ender->getElementsByTagName('xCpl')->item(0)->nodeValue : '';
                    $aCad[$i]['xBairro'] = !empty($ender->getElementsByTagName('xBairro')->item(0)->nodeValue) ? $ender->getElementsByTagName('xBairro')->item(0)->nodeValue : '';
                    $aCad[$i]['cMun'] = !empty($ender->getElementsByTagName('cMun')->item(0)->nodeValue) ? $ender->getElementsByTagName('cMun')->item(0)->nodeValue : '';
                    $aCad[$i]['xMun'] = !empty($ender->getElementsByTagName('xMun')->item(0)->nodeValue) ? $ender->getElementsByTagName('xMun')->item(0)->nodeValue : '';
                    $aCad[$i]['CEP'] = !empty($ender->getElementsByTagName('CEP')->item(0)->nodeValue) ? $ender->getElementsByTagName('CEP')->item(0)->nodeValue : '';
                }
                $i++;
            } //fim foreach
        }
        $aRetorno['cStat'] = $cStat;
        $aRetorno['xMotivo'] = $xMotivo;
        $aRetorno['dados'] = $aCad;
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
     * @version 2.1.11
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param  mixed    $mNFe string com uma nota fiscal em xml ou um array com as NFe em xml, uma em cada campo do array unidimensional MAX 50
     * @param   integer $idLote     id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma NFe
     * @param   integer $modSOAP 1 usa __sendSOP e 2 usa __sendSOAP2
     * @return mixed false ou array ['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'','tMed'=>'','tpAmb'=>'','verAplic'=>'','cUF'=>'']
     * @todo Incluir regra de validação para ambiente de homologação/produção vide NT2011.002
     */
    public function sendLot($mNFe,$idLote,$modSOAP='2')
    {
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'','tMed'=>'','tpAmb'=>'','verAplic'=>'','cUF'=>'');
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN) {
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$this->tpAmb,'SCAN');
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
        if (empty($mNFe)) {
            $msg = "Pelo menos uma NFe deve ser passada no parâmetro!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if (is_array($mNFe)){
            // verificar se foram passadas até 50 NFe
            if ( count($mNFe) > 50 ) {
                $msg = "No maximo 50 NFe devem compor um lote de envio!!";
                $this->__setError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg);
                }
                return false;
            }
            // monta string com todas as NFe enviadas no array
            $sNFe = implode('',$mNFe);
        } else {
            $sNFe = $mNFe;
        }
        //remover <?xml version="1.0" encoding=... das NFe pois somente uma dessas tags pode existir na mensagem
        /*$sNFe = str_replace(array('<?xml version="1.0" encoding="utf-8"?>','<?xml version="1.0" encoding="UTF-8"?>'),'',$sNFe);*/
        $sNFe = preg_replace("/<\?xml.*\?>/", "", $sNFe);
        $sNFe = str_replace(array("\r","\n","\s"),"",$sNFe);
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><enviNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><idLote>'.$idLote.'</idLote>'.$sNFe.'</enviNFe></nfeDadosMsg>';
        //envia dados via SOAP
        if ($modSOAP == '2'){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb,$this->UF);
        }
        //verifica o retorno
        if ($retorno){
            //tratar dados de retorno
            $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                $msg = "O retorno não contêm cStat verifique o debug do soap !!";
                $this->__setError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg);
                }
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
            $nome = $this->temDir.$idLote.'-rec.xml';
            $nome = $doc->save($nome);
        } else {
            $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            $aRetorno = false;
        }
        return $aRetorno;
    }// fim sendLot


    /**
     * getProtocol
     * Solicita resposta do lote de Notas Fiscais ou o protocolo de
     * autorização da NFe
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name getProtocol
     * @param  string   $recibo numero do recibo do envio do lote
     * @param  string   $chave  numero da chave da NFe de 44 digitos
     * @param   string   $tpAmb  numero do ambiente 1-producao e 2-homologação
     * @param   integer  $modSOAP 1 usa __sendSOAP e 2 usa __sendSOAP2
     * @param   array    $aRetorno Array com os dados do protocolo
     * @return mixed    false ou xml
     */
    public function getProtocol($recibo='',$chave='',$tpAmb='',$modSOAP='2',&$aRetorno=''){
        try {
            //carrega defaults
            $i = 0;
            $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','aProt'=>'','aCanc'=>'','xmlRetorno'=>'');
            $cUF = $this->cUF;
            $UF = $this->UF;
            if ($tpAmb == ''){
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb != '1' && $tpAmb != '2' ){
                $tpAmb = '2';
            }
            $aURL = $this->aURL;
            $ctpEmissao = '';
            //verifica se a chave foi passada
            if($chave != ''){
                //se sim extrair o cUF da chave
                $cUF = substr($chave,0,2);
                $ctpEmissao = substr($chave,34,1);
                //testar para ver se é o mesmo do emitente
                if($cUF != $this->cUF || $tpAmb != $this->tpAmb){
                    //se não for o mesmo carregar a sigla
                    $UF = $this->UFList[$cUF];
                    //recarrega as url referentes aos dados passados como parametros para a função
                    $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$UF);
                }
            }
            //verifica se o SCAN esta habilitado
            if ($this->enableSCAN || $ctpEmissao == '3'){
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SCAN');
            }
            if ($recibo == '' && $chave == '') {
                $msg = "ERRO. Favor indicar o numero do recibo ou a chave de acesso da NFe!!";
                throw new nfephpException($msg);
            }
            if ($recibo != '' && $chave != '') {
                $msg = "ERRO. Favor indicar somente um dos dois dados ou o numero do recibo ou a chave de acesso da NFe!!";
                throw new nfephpException($msg);
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
                $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
                $doc->formatOutput = false;
                $doc->preserveWhiteSpace = false;
                $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                if ($cStat == ''){
                    //houve erro
                    $msg = "Erro cStat está vazio.";
                    throw new nfephpException($msg);
                }
                $envelopeBodyNode = $doc->getElementsByTagNameNS('http://www.w3.org/2003/05/soap-envelope', 'Body')->item(0)->childNodes->item(0);
                //Disponibiliza o conteúdo xml do pacote de resposta (soap:Body) através do array de retorno
                $aRetorno['xmlRetorno'] = $doc->saveXML($envelopeBodyNode);
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
                    $procEventoNFe = $doc->getElementsByTagName('procEventoNFe');
                    $aProt = '';
                    if(isset($infProt)){
                        foreach($infProt->childNodes as $t) {
                            $aProt[$t->nodeName] = $t->nodeValue;
                        }
                        $aProt['dhRecbto'] = !empty($aProt['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($aProt['dhRecbto'])) : '';
                    }
                    $aCanc = '';
                    if(isset($infCanc)){
                        foreach($infCanc->childNodes as $t) {
                            $aCanc[$t->nodeName] = $t->nodeValue;
                        }
                        $aCanc['dhRecbto'] = !empty($aCanc['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($aCanc['dhRecbto'])) : '';
                    }
                    $aEventos = '';
                    if( !empty( $procEventoNFe ) ){
                        foreach($procEventoNFe as $i => $evento) {
                            $infEvento = $evento->getElementsByTagName('infEvento')->item(0);
                            foreach($infEvento->childNodes as $t) {
                                if( 'detEvento' == $t->nodeName ) {
                                    foreach( $t->childNodes as $t2 ){
                                        $aEventos[$i][$t->nodeName][$t2->nodeName] = $t2->nodeValue;
                                    }
                                    continue;
                                }
                                $aEventos[$i][$t->nodeName] = $t->nodeValue;
                            }
                            $aEventos[$i]['id'] = $infEvento->getAttribute('Id');
                        }
                    }
                    $aRetorno['aProt'] = $aProt;
                    $aRetorno['aCanc'] = $aCanc;
                    $aRetorno['aEventos'] = $aEventos;
                    //gravar o retorno na pasta temp apenas se a nota foi aprovada ou denegada
                    if ( $aRetorno['cStat'] == 100 || $aRetorno['cStat'] == 101 || $aRetorno['cStat'] == 110 || $aRetorno['cStat'] == 301 || $aRetorno['cStat'] == 302 ){
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
                            if ( $protcStat == 100 || $protcStat == 110 || $protcStat == 301 || $protcStat == 302 ){
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
                    if (isset($aProt)) {
                        foreach ($aProt as &$p){
                            $p['dhRecbto'] = !empty($p['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($p['dhRecbto'])) : '';
                        }
                    } else {
                        $aProt = array();
                    }
                    $aRetorno['aProt'] = $aProt; //passa o valor de $aProt para o array de retorno
                    $nomeArq = $recibo.'-recprot.xml';
                    $nome = $this->temDir.$nomeArq;
                    $nome = $doc->save($nome);
                } //fim recibo
            } else {
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            } //fim retorno
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        return $retorno;
    } //fim getProtocol

    /**
     * getListNFe
     * Consulta da Relação de Documentos Destinados
     * para um determinado CNPJ de destinatário informado na NF-e.
     *
     * Este serviço não suporta SCAN !!!
     *
     * @name getListNFe
     * @param boolean $ambNac TRUE - usa ambiente Nacional para buscar a lista de NFe, FALSE usa sua própria SEFAZ
     * @param string $indNFe Indicador de NF-e consultada: 0=Todas as NF-e; 1=Somente as NF-e que ainda não tiveram manifestação do destinatário (Desconhecimento da operação, Operação não Realizada ou Confirmação da Operação); 2=Idem anterior, incluindo as NF-e que também não tiveram a Ciência da Operação
     * @param string $indEmi Indicador do Emissor da NF-e: 0=Todos os Emitentes / Remetentes; 1=Somente as NF-e emitidas por emissores / remetentes que não tenham a mesma raiz do CNPJ do destinatário (para excluir as notas fiscais de transferência entre filiais).
     * @param string $ultNSU Último NSU recebido pela Empresa. Caso seja informado com zero, ou com um NSU muito antigo, a consulta retornará unicamente as notas fiscais que tenham sido recepcionadas nos últimos 15 dias.
     * @param string $tpAmb Tipo de ambiente 1=Produção 2=Homologação
     * @param string $modSOAP
     * @param array $resp Array com os retornos parametro passado por REFRENCIA
     * @return mixed False ou xml com os dados
     */
    public function getListNFe($ambNac = true, $indNFe = '0', $indEmi = '0', $ultNSU = '', $tpAmb = '', $modSOAP = '2', &$resp = '')
    {
        try {
            $datahora = date('Ymd_His');
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (!$ambNac) {
                $aURL = $this->loadSEFAZ(
                    $this->raizDir.'config'.DIRECTORY_SEPARATOR.$this->xmlURLfile,
                    $tpAmb,
                    $this->UF
                );
                $sigla = $this->UF;
            } else {
                $aURL = $this->loadSEFAZ(
                    $this->raizDir.'config'.DIRECTORY_SEPARATOR.$this->xmlURLfile,
                    $tpAmb,
                    'AN'
                );
                $sigla = 'AN';
            }
            if ($ultNSU == '') {
                //buscar o último NSU no xml
                $ultNSU = $this->__getUltNSU($sigla, $tpAmb);
            }
            if ($indNFe == '') {
                $indNFe = '0';
            }
            if ($indEmi == '') {
                $indEmi = '0';
            }
            //identificação do serviço
            $servico = 'NfeConsultaDest';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            //monta a consulta
            $cons = '';
            $cons .= '<consNFeDest xmlns="'.$this->URLPortal.'" versao="'.$versao.'">';
            $cons .= '<tpAmb>'.$tpAmb.'</tpAmb><xServ>CONSULTAR NFE DEST</xServ>';
            $cons .= '<CNPJ>'.$this->cnpj.'</CNPJ><indNFe>'.$indNFe.'</indNFe>';
            $cons .= '<indEmi>'.$indEmi.'</indEmi><ultNSU>'.$ultNSU.'</ultNSU></consNFeDest>';
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$this->cUF.'</cUF>';
            $cabec .= '<versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'.$namespace.'">'.$cons.'</nfeDadosMsg>';
            //grava solicitação em temp
            if (!file_put_contents($this->temDir."$this->cnpj-$ultNSU-$datahora-LNFe.xml", $cons)) {
                $msg = "Falha na gravação do arquivo LNFe (Lista de NFe)!!";
                $this->__setError($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $indCont = 0;
            $xmlLNFe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlLNFe->formatOutput = false;
            $xmlLNFe->preserveWhiteSpace = false;
            $xmlLNFe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retConsNFeDest = $xmlLNFe->getElementsByTagName("retConsNFeDest")->item(0);
            if (isset($retConsNFeDest)) {
                $cStat = !empty($retConsNFeDest->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('cStat')->item(0)->nodeValue :
                    '';
                $xMotivo = !empty($retConsNFeDest->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('xMotivo')->item(0)->nodeValue :
                    '';
                $ultNSU  = !empty($retConsNFeDest->getElementsByTagName('ultNSU')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('ultNSU')->item(0)->nodeValue :
                    '';
                $indCont = !empty($retConsNFeDest->getElementsByTagName('indCont')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('indCont')->item(0)->nodeValue :
                    0;
            } else {
                $cStat = '';
            }
            if ($cStat == '') {
                //houve erro
                $msg = "cStat está em branco, ";
                $msg .= "houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '137' && $cStat != '138') {
                //se cStat <> 137 ou 138 houve erro e o lote foi rejeitado
                $msg = "A requisição foi rejeitada : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            //podem existir NFe emitidas para este destinatário
            $aNFe = array();
            $aCanc = array();
            $aCCe = array();
            $ret =  $xmlLNFe->getElementsByTagName("ret");
            foreach ($ret as $k => $d) {
                $resNFe = $ret->item($k)->getElementsByTagName('resNFe')->item(0);
                $resCanc = $ret->item($k)->getElementsByTagName('resCanc')->item(0);
                $resCCe = $ret->item($k)->getElementsByTagName('resCCe')->item(0);
                if (isset($resNFe)) {
                    //existem notas emitidas para esse cnpj
                    $nsu = $resNFe->getAttribute('NSU');
                    $chNFe = $resNFe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $CNPJ = null;
                    if ($resNFe->getElementsByTagName('CNPJ') !== null && $resNFe->getElementsByTagName('CNPJ')->item(0) !== null){
                        $CNPJ = $resNFe->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    }
                    $CPF = null;
                    if ($resNFe->getElementsByTagName('CPF') !== null && $resNFe->getElementsByTagName('CPF')->item(0) !== null){
                        $CPF = $resNFe->getElementsByTagName('CPF')->item(0)->nodeValue;
                    }
                    $xNome = $resNFe->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $dEmi = $resNFe->getElementsByTagName('dEmi')->item(0)->nodeValue;
                    $dhRecbto= $resNFe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $tpNF = $resNFe->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $cSitNFe = $resNFe->getElementsByTagName('cSitNFe')->item(0)->nodeValue;
                    $cSitConf = $resNFe->getElementsByTagName('cSitConf')->item(0)->nodeValue;
                    $vNF = $resNFe->getElementsByTagName('vNF')->item(0)->nodeValue;
                    $IE = null;
                    if ($resNFe->getElementsByTagName('IE') !== null) {
                        $IE = $resNFe->getElementsByTagName('IE')->item(0)->nodeValue;
                    }

                    $aNFe[] = array(
                        'chNFe'=>$chNFe,
                        'NSU'=>$nsu,
                        'CNPJ'=>$CNPJ,
                        'CPF'=>$CPF,
                        'xNome'=>$xNome,
                        'dEmi'=>$dEmi,
                        'dhRecbto'=>$dhRecbto,
                        'tpNF'=>$tpNF,
                        'cSitNFe'=>$cSitNFe,
                        'cSitconf'=>$cSitConf,
                        'vNF'=>$vNF,
                        'IE'=>$IE
                    );
                }//fim resNFe
                if (isset($resCanc)) {
                    //existem notas canceladas para esse cnpj
                    $nsu = $resCanc->getAttribute('NSU');
                    $chNFe = $resCanc->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $CNPJ = null;
                    if ($resCanc->getElementsByTagName('CNPJ') !== null && $resCanc->getElementsByTagName('CNPJ')->item(0) !== null){
                        $CNPJ = $resCanc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    }
                    $CPF = null;
                    if ($resCanc->getElementsByTagName('CPF') !== null && $resCanc->getElementsByTagName('CPF')->item(0) !== null){
                        $CPF = $resCanc->getElementsByTagName('CPF')->item(0)->nodeValue;
                    }
                    $xNome = $resCanc->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $dEmi = $resCanc->getElementsByTagName('dEmi')->item(0)->nodeValue;
                    $dhRecbto= $resCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $tpNF = $resCanc->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $cSitNFe = $resCanc->getElementsByTagName('cSitNFe')->item(0)->nodeValue;
                    $cSitConf = $resCanc->getElementsByTagName('cSitConf')->item(0)->nodeValue;
                    $vNF = $resCanc->getElementsByTagName('vNF')->item(0)->nodeValue;
                    $IE = null;
                    if ($resCanc->getElementsByTagName('IE') !== null) {
                        $IE = $resCanc->getElementsByTagName('IE')->item(0)->nodeValue;
                    }
                    $aCanc[] = array(
                        'chNFe'=>$chNFe,
                        'NSU'=>$nsu,
                        'CNPJ'=>$CNPJ,
                        'CPF'=>$CPF,
                        'xNome'=>$xNome,
                        'dEmi'=>$dEmi,
                        'dhRecbto'=>$dhRecbto,
                        'tpNF'=>$tpNF,
                        'cSitNFe'=>$cSitNFe,
                        'cSitconf'=>$cSitConf,
                        'vNF'=>$vNF,
                        'IE'=>$IE
                    );
                }//fim resCanc
                if (isset($resCCe)) {
                    //existem cartas de correção emitidas para esse cnpj
                    $nsu = $resCCe->getAttribute('NSU');
                    $chNFe = $resCCe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $tpEvento = $resCCe->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                    $nSeqEvento = $resCCe->getElementsByTagName('nSeqEvento')->item(0)->nodeValue;
                    $dhEvento = $resCCe->getElementsByTagName('dhEvento')->item(0)->nodeValue;
                    $dhRecbto= $resCCe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $descEvento = $resCCe->getElementsByTagName('descEvento')->item(0)->nodeValue;
                    $xCorrecao = $resCCe->getElementsByTagName('xCorrecao')->item(0)->nodeValue;
                    $tpNF = $resCCe->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $aCCe[] = array(
                        'chNFe'=>$chNFe,
                        'NSU'=>$nsu,
                        'tpEvento'=>$tpEvento,
                        'nSeqEvento'=>$nSeqEvento,
                        'dhEvento'=>$dhEvento,
                        'dhRecbto'=>$dhRecbto,
                        'descEvento'=>$descEvento,
                        'xCorrecao'=>$xCorrecao,
                        'tpNF'=>$tpNF
                    );
                }//fim resCCe
            }//fim foreach ret
            //salva o arquivo xml
            if (!file_put_contents($this->temDir."$this->cnpj-$ultNSU-$datahora-resLNFe.xml", $retorno)) {
                $msg = "Falha na gravação do arquivo resLNFe!!";
                $this->__setError($msg);
            }
            if ($ultNSU != '' && $indCont == 1) {
                //grava o ultimo NSU informado no arquivo
                $this->__putUltNSU($sigla, $tpAmb, $ultNSU);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        $resp = array('indCont'=>$indCont,'ultNSU'=>$ultNSU,'NFe'=>$aNFe,'Canc'=>$aCanc,'CCe'=>$aCCe);
        return $retorno;
    }//fim getListNFe

    /**
     * getNFe
     * Download da NF-e para uma determinada Chave de Acesso informada,
     * para as NF-e confirmadas pelo destinatário. As NFe baixadas serão salvas
     * na pasta de recebidas
     *
     * ESSE SEVIÇO NÃO ESTÁ TOTALMENTE OPERACIONAL EXISTE APENAS NO SEFAZ DO RS E SVAN
     *
     * Este serviço não suporta SCAN !!
     *
     * @name getNFe
     * @param boolean $AN   true usa ambiente nacional, false usa o SEFAZ do emitente da NF
     * @param string $chNFe chave da NFe
     * @param string $tpAmb tipo de ambiente
     * @param string $modSOAP modo do SOAP
     * @param string $xml variável passada como referência e irá conter o conteúdo XML que é salvo em arquivo
     * @return mixed FALSE ou xml de retorno
     *
     * TODO: quando o serviço estiver funcional extrair o xml da NFe e colocar
     * no diretorio correto
     */
    public function getNFe($AN=true,$chNFe='',$tpAmb='',$modSOAP='2', &$xml=null){
        try{
            if($chNFe == ''){
                $msg = 'Uma chave de NFe deve ser passada como parâmetro da função.';
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if($tpAmb == ''){
                $tpAmb = $this->tpAmb;
            }
            if ($AN){
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'AN');
            } else {
                //deve se verificado se NFe emitidas em SCAN, com séries começando com 9
                //podem ser obtidas no sefaz do emitente DUVIDA!!!
                //obtem a SEFAZ do emissor
                $cUF = substr($chNFe,0,2);
                $UF = $this->UFList[$cUF];
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$UF);
            }
            //identificação do serviço
            $servico = 'NfeDownloadNF';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            if ($urlservico == ''){
                $msg = 'Não existe esse serviço na SEFAZ consultada.';
                throw new nfephpException($msg,self::STOP_CRITICAL);
            }
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><downloadNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><xServ>DOWNLOAD NFE</xServ><CNPJ>'.$this->cnpj.'</CNPJ><chNFe>'.$chNFe.'</chNFe></downloadNFe></nfeDadosMsg>';
            //envia dados via SOAP
            if ($modSOAP == '2'){
                $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$this->UF);
            }
            //verifica o retorno
            if (!$retorno){
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //salva arquivo de retorno contendo todo o XML da SEFAZ
            $fileName  = $this->temDir."$chNFe-resDWNFe.xml";
            if (!file_put_contents($fileName, $retorno)){
                $msg = "Falha na gravação do arquivo $fileName!!";
                $this->__setError($msg);
            }
            //tratar dados de retorno
            $xmlDNFe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlDNFe->formatOutput = false;
            $xmlDNFe->preserveWhiteSpace = false;
            $xmlDNFe->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retDownloadNFe = $xmlDNFe->getElementsByTagName("retDownloadNFe")->item(0);
            if (isset($retDownloadNFe)){
                $cStat = !empty($retDownloadNFe->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retDownloadNFe->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                $xMotivo = !empty($retDownloadNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retDownloadNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                $dhResp = !empty($retDownloadNFe->getElementsByTagName('dhResp')->item(0)->nodeValue) ? $retDownloadNFe->getElementsByTagName('dhResp')->item(0)->nodeValue : '';
                //existem 2 cStat, um com nó pai retDownloadNFe ($cStat) e outro no
                //nó filho retNFe($cStatRetorno)
                //para que o download seja efetuado corretamente o $cStat deve vir com valor 139-Pedido de download Processado
                //e o $cStatRetorno com valor 140-Download disponibilizado
                $retNFe = $xmlDNFe->getElementsByTagName("retNFe")->item(0);
                if (isset($retNFe)){
                    $cStatRetorno = !empty($retNFe->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retNFe->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                    $xMotivoRetorno = !empty($retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                } else {
                    $cStatRetorno = '';
                    $xMotivoRetorno = '';
                }
            } else {
                $cStat = '';
            }
            //status de retorno nao podem vir vazios
            if (empty($cStat)){
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação verifique a mensagem de erro!";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //erro no processamento
            if ($cStat != '139' ){
                //se cStat <> 139 ou 140 houve erro e o lote foi rejeitado
                $msg = "A requisição foi rejeitada : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            if ( $cStatRetorno != '140' ){
                //pega o motivo do nó retNFe, com a descriçao da rejeiçao
                $msg = "Não houve o download da NF : $cStatRetorno - $xMotivoRetorno\n";
                throw new nfephpException($msg);
            }
            //verifica como deve extrair o XML da NF-e, pois existem 3 possibilidades:
            //JR13-procNFeZip ~ou~ JR14-procNFe ~ou~ JR17-procNFeGrupoZip onde JR13 e JR14
            //são elementos e JR17 é um grupo
            $retNFe_procNFeZip = $retNFe->getElementsByTagName('procNFeZip')->item(0);
            $retNFe_procNFe = $retNFe->getElementsByTagName('procNFe')->item(0);
            $retNFe_procNFeGrupoZip = $retNFe->getElementsByTagName('procNFeGrupoZip')->item(0);
            if (isset($retNFe_procNFeZip)) {
               $xml = ''; //implementar...
            } else if (isset($retNFe_procNFe)) {
               //elemento "JR14_procNFe" contendo a estrutura “nfeProc”, já descompactada.
               $nfeProc = $xmlDNFe->getElementsByTagName("nfeProc")->item(0);
               //cria novo documento DOM para importar e adicionar o elemento
               $dom = new DOMDocument('1.0', 'UTF-8');
               $dom->formatOutput = false;
               $dom->preserveWhiteSpace = false;
               // Importa o node e todo o seu conteudo e acrescenta ao node principal
               $node = $dom->importNode($nfeProc, true);
               $dom->appendChild($node);
               $xml = $dom->saveXML();
            } else if (isset($retNFe_procNFeGrupoZip)) {
               //grupo contendo a NF-e compactada e o Protocolo de Autorização compactado (padrão gZip).
               //extrai a NF-e do elemento JR18_NFeZip e extrai o protocolo de autorização de uso do elemento
               //JR19_protNFeZip (ambos são obrigatórios)
               $nfe = $this->__gunzip2(base64_decode($retNFe_procNFeGrupoZip->getElementsByTagName('NFeZip')->item(0)->nodeValue));
               $prot = $this->__gunzip2(base64_decode($retNFe_procNFeGrupoZip->getElementsByTagName('protNFeZip')->item(0)->nodeValue));
               //tem a NF-e e o protocolo de autorização, agora adiciona o protocolo; para isso,
               //cria dois arquivos temporários e chama o addProt()
               $nfeTempFile = file_put_contents($this->temDir . $chNFe . '-nfe.xml', $nfe);
               $protTempFile = file_put_contents($this->temDir . $chNFe . '-prot.xml', $prot);
               $xml = $this->addProt($this->temDir . $chNFe . '-nfe.xml', $this->temDir . $chNFe . '-prot.xml');
            }
            $fileName = $this->recDir."$chNFe-procNFe.xml";
            if (!file_put_contents($fileName, $xml)){
                $msg = "Falha na gravação do arquivo NFe $fileName!!";
                $this->__setError($msg);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        return $retorno;
    }//fim getNFe

    /**
     * Solicita inutilizaçao de uma serie de numeros de NF
     * - o processo de inutilização será gravado na pasta Inutilizadas
     *
     * @name inutNF
     * @param  string  $nAno       ano com 2 digitos
     * @param  string  $nSerie     serie da NF 1 até 3 digitos
     * @param  integer $nIni       numero inicial 1 até 9 digitos zero a esq
     * @param  integer $nFin       numero Final 1 até 9 digitos zero a esq
     * @param  string  $xJust      justificativa 15 até 255 digitos
     * @param  string  $tpAmb      Tipo de ambiente 1-produção ou 2 homologação
     * @param  integer $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @param  array $aRetorno     Array passada como referencia, irá conter o retorna da SEFAZ
     * @return mixed false ou string com o xml do processo de inutilização
     */
    public function inutNF($nAno = '', $nSerie = '1', $nIni = '', $nFin = '', $xJust = '', $tpAmb = '', $modSOAP = '2', &$aRetorno = array()) {
        $aRetorno = array(
            'bStat'=>false,
            'tpAmb'=>'',
            'verAplic'=>'',
            'cStat'=>'',
            'xMotivo'=>'',
            'cUF'=>'',
            'ano'=>'',
            'CNPJ'=>'',
            'mod'=>'',
            'serie'=>'',
            'nNFIni'=>'',
            'nNFFin'=>'',
            'dhRecbto'=>'',
            'nProt'=>'');
        //valida dos dados de entrada
        if ($nAno == '' || $nIni == '' || $nFin == '' || $xJust == '') {
            $msg = "Não foi passado algum dos parametos necessários ANO=$nAno inicio=$nIni fim=$nFin justificativa=$xJust.\n";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida justificativa
        if (strlen($xJust) < 15) {
            $msg = "A justificativa deve ter pelo menos 15 digitos!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if (strlen($xJust) > 255) {
            $msg = "A justificativa deve ter no máximo 255 digitos!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //remove acentos e outros caracteres da justificativa
        $xJust = $this->__cleanString($xJust);
        // valida o campo ano
        if (strlen($nAno) > 2) {
            $msg = "O ano tem mais de 2 digitos. Corrija e refaça o processo!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        } else {
            if (strlen($nAno) < 2) {
                $msg = "O ano tem menos de 2 digitos. Corrija e refaça o processo!!";
                $this->__setError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg);
                }
                return false;
            }
        }
        //valida o campo serie
        if (strlen($nSerie) == 0 || strlen($nSerie) > 3) {
            $msg = "O campo serie está errado: $nSerie. Corrija e refaça o processo!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida o campo numero inicial
        if (strlen($nIni) < 1 || strlen($nIni) > 9) {
            $msg = "O campo numero inicial está errado: $nIni. Corrija e refaça o processo!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida o campo numero final
        if (strlen($nFin) < 1 || strlen($nFin) > 9) {
            $msg = "O campo numero final está errado: $nFin. Corrija e refaça o processo!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida tipo de ambiente
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN) {
            if ($tpAmb == $this->tpAmb) {
                $aURL = $this->aURL;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$this->UF);
            }
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$this->tpAmb,'SCAN');
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
        $dXML .= '<tpAmb>'.$tpAmb.'</tpAmb>';
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
        $dXML = $this->signXML($dXML, 'infInut');
        $dados = '<nfeDadosMsg xmlns="'.$namespace.'">'.$dXML.'</nfeDadosMsg>';
        //remove as tags xml que porventura tenham sido inclusas
        $dados = str_replace('<?xml version="1.0"?>', '', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $dados);
        $dados = str_replace(array("\r","\n","\s"), "", $dados);
        //grava a solicitação de inutilização
        if (!file_put_contents($this->temDir.$id.'-pedInut.xml', $dXML)) {
            $msg = "Falha na gravação do pedido de inutilização!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        //envia a solicitação via SOAP
        if ($modSOAP == '2') {
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb, $this->UF);
        }
        //verifica o retorno
        if (!$retorno) {
            $msg = "Nao houve retorno Soap verifique o debug!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //tratar dados de retorno
        $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
        $xMotivo = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
        if ($cStat == '') {
            //houve erro
            $msg = "Nao houve retorno Soap verifique o debug!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //verificar o status da solicitação
        if ($cStat != '102') {
            //houve erro
            $msg = "Rejeição : $cStat - $xMotivo";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //gravar o retorno na pasta temp
        $nome = $this->temDir.$id.'-retInut.xml';
        $nome = $doc->save($nome);
        $aRetorno['bStat'] = true;
        // tipo de ambiente
        $aRetorno['tpAmb'] = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        // versÃ£o do aplicativo
        $aRetorno['verAplic'] = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
        // status do serviço
        $aRetorno['cStat'] = $cStat;
        // motivo da resposta (opcional)
        $aRetorno['xMotivo'] = $xMotivo;
        // Código da UF que atendeu a solicitação
        $aRetorno['cUF'] = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
        // Ano de inutilização da numeração
        $aRetorno['ano'] = $doc->getElementsByTagName('ano')->item(0)->nodeValue;
        // CNPJ do emitente
        $aRetorno['CNPJ'] = $doc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        // Modelo da NF-e
        $aRetorno['mod'] = $doc->getElementsByTagName('mod')->item(0)->nodeValue;
        // Série da NF-e
        $aRetorno['serie'] = $doc->getElementsByTagName('serie')->item(0)->nodeValue;
        // Número da NF-e inicial a ser inutilizada
        $aRetorno['nNFIni'] = $doc->getElementsByTagName('nNFIni')->item(0)->nodeValue;
        // Número da NF-e final a ser inutilizada
        $aRetorno['nNFFin'] = $doc->getElementsByTagName('nNFFin')->item(0)->nodeValue;
        // data e hora do retorno a operaÃ§Ã£o (opcional)
        $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ?
        date("d/m/Y H:i:s", $this->pConvertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
        // Número do Protocolo de Inutilização
        $aRetorno['nProt'] = $doc->getElementsByTagName('nProt')->item(0)->nodeValue;
        $retInutNFe = $doc->getElementsByTagName("retInutNFe")->item(0);
        //preparar o processo de inutilização
        $inut = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $inut->formatOutput = false;
        $inut->preserveWhiteSpace = false;
        $inut->loadXML($dXML, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $inutNFe = $inut->getElementsByTagName("inutNFe")->item(0);
        //Processo completo solicitação + protocolo
        $procInut = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $procInut->formatOutput = false;
        $procInut->preserveWhiteSpace = false;
        //cria a tag procInutNFe
        $procInutNFe = $procInut->createElement('procInutNFe');
        $procInut->appendChild($procInutNFe);
        //estabele o atributo de versão
        $inutProc_att1 = $procInutNFe->appendChild($procInut->createAttribute('versao'));
        $inutProc_att1->appendChild($procInut->createTextNode($versao));
        //estabelece o atributo xmlns
        $inutProc_att2 = $procInutNFe->appendChild($procInut->createAttribute('xmlns'));
        $inutProc_att2->appendChild($procInut->createTextNode($this->URLPortal));
        //carrega o node cancNFe
        $node1 = $procInut->importNode($inutNFe, true);
        $procInutNFe->appendChild($node1);
        //carrega o node retEvento
        $node2 = $procInut->importNode($retInutNFe, true);
        $procInutNFe->appendChild($node2);
        //salva o xml como string em uma variável
        $procXML = $procInut->saveXML();
        //remove as informações indesejadas
        $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
        $procXML = str_replace('default:', '', $procXML);
        $procXML = str_replace(':default', '', $procXML);
        $procXML = str_replace("\n", '', $procXML);
        $procXML = str_replace("\r", '', $procXML);
        $procXML = str_replace("\s", '', $procXML);
        //salva o arquivo xml
        if (!file_put_contents($this->inuDir."$id-procInut.xml", $procXML)) {
            $msg = "Falha na gravação da procInut!!\n";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        return $procXML;
    } //fim inutNFe

    /**
     * cancelEvent
     * Solicita o cancelamento de NFe autorizada
     * - O xml do evento de cancelamento será salvo na pasta Canceladas
     *
     * @name cancelEvent
     * @param string $chNFe
     * @param string $nProt
     * @param string $xJust
     * @param number $tpAmb
     * @param number $modSOAP
     * @param array $aRetorno
     */
    public function cancelEvent($chNFe = '', $nProt = '', $xJust = '', $tpAmb = '', $modSOAP = '2', &$aRetorno = array()){
        try {
            $aRetorno = array(
                'bStat'=>false,
                'tpAmb'=>'',
                'verAplic'=>'',
                'cStat'=>'',
                'xMotivo'=>'',
                'nProt'=>'',
                'chNFe'=>'',
                'dhRecbto'=>'');
            //validação dos dados de entrada
            if ($chNFe == '' || $nProt == '' || $xJust == '') {
                $msg = "Não foi passado algum dos parâmetros necessários ID=$chNFe ou protocolo=$nProt ou justificativa=$xJust.";
                throw new nfephpException($msg);
            }
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (strlen($xJust) < 15) {
                $msg = "A justificativa deve ter pelo menos 15 digitos!!";
                throw new nfephpException($msg);
            }
            if (strlen($xJust) > 255) {
                $msg = "A justificativa deve ter no máximo 255 digitos!!";
                throw new nfephpException($msg);
            }
            if (strlen($chNFe) != 44) {
                $msg = "Uma chave de NFe válida não foi passada como parâmetro $chNFe.";
                throw new nfephpException($msg);
            }
            //estabelece o codigo do tipo de evento CANCELAMENTO
            $tpEvento = '110111';
            $descEvento = 'Cancelamento';
            //para cancelamento o numero sequencia do evento sempre será 1
            $nSeqEvento = '1';
            //remove qualquer caracter especial
            $xJust = $this->__cleanString($xJust);
            //decompor a chNFe e pegar o tipo de emissão
            //$tpEmiss = substr($chNFe, 34, 1);
            //verifica se o SCAN esta habilitado
            if (!$this->enableSCAN) {
                $aURL = $this->aURL;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SCAN');
            }
            $numLote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //se o envio for para svan mudar o numero no orgão para 91
            if ($this->enableSVAN) {
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
            //de acordo com o manual versão 5 de março de 2012
            // 2   +    6     +    44         +   2  = 54 digitos
            //“ID” + tpEvento + chave da NF-e + nSeqEvento
            //garantir que existam 2 digitos em nSeqEvento para montar o ID com 54 digitos
            if (strlen(trim($nSeqEvento))==1) {
                $zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
            } else {
                $zenSeqEvento = trim($nSeqEvento);
            }
            $id = "ID".$tpEvento.$chNFe.$zenSeqEvento;
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            $Ev .= "<nProt>$nProt</nProt>";
            $Ev .= "<xJust>$xJust</xJust>";
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = str_replace('<?xml version="1.0"?>','', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $Ev);
            $Ev = str_replace(array("\r","\n","\s"),"", $Ev);
            //carrega uma matriz temporária com os eventos assinados
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicitação em temp
            $arqName = $this->temDir."$chNFe-$nSeqEvento-eventCanc.xml";
            if (!file_put_contents($arqName, $Ev)) {
                $msg = "Falha na gravação do arquivo $arqName";
                $this->__setError($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $xmlretEvent = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlretEvent->formatOutput = false;
            $xmlretEvent->preserveWhiteSpace = false;
            $xmlretEvent->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEnvEvento = $xmlretEvent->getElementsByTagName("retEnvEvento")->item(0);
            $cStat = !empty($retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar erro de versão do XML
            if ($cStat == '238' || $cStat == '239') {
                $this->__trata239($retorno, $this->UF, $tpAmb, $servico, $versao);
                $msg = "Versão do arquivo XML não suportada no webservice!!";
                throw new nfephpException($msg);
            }
            //erro no processamento cStat <> 128
            if ($cStat != 128) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new nfephpException($msg);
            }
            //o lote foi processado cStat == 128
            $retEvento = $xmlretEvent->getElementsByTagName("retEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat != 135 && $cStat != 155) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new nfephpException($msg);
            }
            $aRetorno['bStat'] = true;
            // tipo de ambiente
            $aRetorno['tpAmb'] = $retEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            // versÃ£o do aplicativo
            $aRetorno['verAplic'] = $retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue;
            // status do serviÃ§o
            $aRetorno['cStat'] = $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // Numero de Protocolo
            $aRetorno['nProt'] = $retEvento->getElementsByTagName('nProt')->item(0)->nodeValue;
            // Chave
            $aRetorno['chNFe'] = $retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue;
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue) ?
            date("d/m/Y H:i:s", $this->pConvertTime($retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue)) : '';
            //o evento foi aceito cStat == 135 ou cStat == 155
            //carregar o evento
            $xmlenvEvento = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvEvento->formatOutput = false;
            $xmlenvEvento->preserveWhiteSpace = false;
            $xmlenvEvento->loadXML($Ev, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvEvento->getElementsByTagName("evento")->item(0);
            //Processo completo solicitação + protocolo
            $xmlprocEvento = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlprocEvento->formatOutput = false;
            $xmlprocEvento->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocEvento->createElement('procEventoNFe');
            $xmlprocEvento->appendChild($procEventoNFe);
            //estabele o atributo de versão
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocEvento->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocEvento->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocEvento->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocEvento->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocEvento->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocEvento->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma variável
            $procXML = $xmlprocEvento->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            //salva o arquivo xml
            $arqName = $this->canDir."$chNFe-$nSeqEvento-procCanc.xml";
            if (!file_put_contents($arqName, $procXML)) {
                $msg = "Falha na gravação do arquivo $arqName";
                $this->__setError($msg);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $procXML;
    } //fim cancEvent

    /**
     * envCCe
     * Envia carta de correção da Nota Fiscal para a SEFAZ.
     *
     * @name envCCe
     * @param   string $chNFe Chave da NFe
     * @param   string $xCorrecao Descrição da Correção entre 15 e 1000 caracteres
     * @param   string $nSeqEvento numero sequencial da correção d 1 até 20
     *                             isso deve ser mantido na base de dados e
     *                             as correções consolidadas, isto é a cada nova correção
     *                             devem ser inclusas as anteriores no texto.
     *                             O Web Service não permite a duplicidade de numeração
     *                             e nem controla a ordem crescente
     * @param   integer $tpAmb Tipo de ambiente
     * @param   integer $modSOAP 1 usa __sendSOP e 2 usa __sendSOAP2
     * @param   array    $aResp Array com os dados do protocolo
     * @return mixed false ou xml com a CCe
     */
    public function envCCe($chNFe='',$xCorrecao='',$nSeqEvento='1',$tpAmb='',$modSOAP='2',&$aResp=''){
       $aResp = array(
       'versao' => NULL,
       'idLote' => NULL,
       'tpAmb' => NULL,
       'verAplic' => NULL,
       'cOrgao' => NULL,
       'cStat' => NULL,
       'xMotivo' => NULL,
       'retEvento' => array(
          'versao' => NULL,
          'xMotivo' => NULL,
          'infEvento' => array(
             'id' => NULL,
             'tpAmb' => NULL,
             'verAplic' => NULL,
             'cOrgao' => NULL,
             'cStat' => NULL,
             'xMotivo' => NULL,
             'chNFe' => NULL,
             'tpEvento' => NULL,
             'xEvento' => NULL,
             'nSeqEvento' => NULL,
             'CNPJDest' => NULL,
             'CPFDest' => NULL,
             'emailDest' => NULL,
             'dhRegEvento' => NULL,
             'nProt' => NULL)));

        try {
            //testa se os dados da carta de correção foram passados
            if ($chNFe == '' || $xCorrecao == '' ){
                $msg = "Dados para a carta de correção não podem ser vazios.";
                throw new nfephpException($msg);
            }
            if (strlen($chNFe) != 44){
                $msg = "Uma chave de NFe válida não foi passada como parâmetro $chNFe.";
                throw new nfephpException($msg);
            }
            //se o numero sequencial do evento não foi informado ou se for invalido
            if ($nSeqEvento == '' || strlen($nSeqEvento) > 2 || !is_numeric($nSeqEvento) || $nSeqEvento < 1){
                $msg = "Número sequencial da correção não encontrado ou é maior que 99 ou contêm caracteres não numéricos [$nSeqEvento]";
                throw new nfephpException($msg);
            }
            if (strlen($xCorrecao) < 15 || strlen($xCorrecao) > 1000){
                $msg = "O texto da correção deve ter entre 15 e 1000 caracteres!";
                throw new nfephpException($msg);
            }
            //limpa o texto de correção para evitar surpresas
            $xCorrecao = $this->__cleanString($xCorrecao);
            //ajusta ambiente
            if ($tpAmb == ''){
                $tpAmb = $this->tpAmb;
            }
            //decompor a chNFe e pegar o tipo de emissão
            $tpEmiss = substr($chNFe, 34, 1);
            //verifica se o SCAN esta habilitado
            if (!$this->enableSCAN){
                $aURL = $this->aURL;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SCAN');
            }
            $numLote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //se o envio for para svan mudar o numero no orgão para 91
            if ($this->enableSVAN){
                $cOrgao='91';
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
            //estabelece o codigo do tipo de evento
            $tpEvento = '110110';
            //de acordo com o manual versão 5 de março de 2012
            // 2   +    6     +    44         +   2  = 54 digitos
            //“ID” + tpEvento + chave da NF-e + nSeqEvento
            //garantir que existam 2 digitos em nSeqEvento para montar o ID com 54 digitos
            if (strlen(trim($nSeqEvento))==1){
                $zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
            } else {
                $zenSeqEvento = trim($nSeqEvento);
            }
            $id = "ID".$tpEvento.$chNFe.$zenSeqEvento;
            $descEvento = 'Carta de Correcao';
            $xCondUso = 'A Carta de Correcao e disciplinada pelo paragrafo 1o-A do art. 7o do Convenio S/N, de 15 de dezembro de 1970 e pode ser utilizada para regularizacao de erro ocorrido na emissao de documento fiscal, desde que o erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da operacao ou da prestacao; II - a correcao de dados cadastrais que implique mudanca do remetente ou do destinatario; III - a data de emissao ou de saida.';
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
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
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicitação em temp
            if (!file_put_contents($this->temDir."$chNFe-$nSeqEvento-envCCe.xml",$Ev)){
                $msg = "Falha na gravação do arquivo envCCe!!";
                throw new nfephpException($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2'){
                $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$this->UF);
            }
            //verifica o retorno
            if (!$retorno){
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $xmlretCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlretCCe->formatOutput = false;
            $xmlretCCe->preserveWhiteSpace = false;
            $xmlretCCe->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEnvEvento = $xmlretCCe->getElementsByTagName('retEnvEvento')->item(0);
            $retEvento = $xmlretCCe->getElementsByTagName("retEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //erro no processamento cStat <> 128
            if ($cStat != 135 ){
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new nfephpException($msg);
            }
            //a correção foi aceita cStat == 135
            //carregar a CCe
            $xmlenvCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvCCe->formatOutput = false;
            $xmlenvCCe->preserveWhiteSpace = false;
            $xmlenvCCe->loadXML($Ev,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvCCe->getElementsByTagName("evento")->item(0);
            //Processo completo solicitação + protocolo
            $xmlprocCCe = new DOMDocument('1.0', 'utf-8');; //cria objeto DOM
            $xmlprocCCe->formatOutput = false;
            $xmlprocCCe->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocCCe->createElement('procEventoNFe');
            $xmlprocCCe->appendChild($procEventoNFe);
            //estabele o atributo de versão
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocCCe->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocCCe->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocCCe->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocCCe->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocCCe->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocCCe->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma variável
            $procXML = $xmlprocCCe->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"",'',$procXML);
            $procXML = str_replace('default:','',$procXML);
            $procXML = str_replace(':default','',$procXML);
            $procXML = str_replace("\n",'',$procXML);
            $procXML = str_replace("\r",'',$procXML);
            $procXML = str_replace("\s",'',$procXML);
            //estrutura "retEnvEvento"
            $aRespVersao = $retEnvEvento->getAttribute('versao');
            $aResp['versao'] = !empty($aRespVersao) ? $retEnvEvento->getAttribute('versao') : '';
            $aResp['idLote'] = !empty($retEnvEvento->getElementsByTagName('idLote')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('idLote')->item(0)->nodeValue : '';
            $aResp['tpAmb'] = !empty($retEnvEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
            $aResp['verAplic'] = !empty($retEnvEvento->getElementsByTagName('verAplic')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
            $aResp['cOrgao'] = !empty($retEnvEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue : '';
            $aResp['cStat'] = !empty($retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $aResp['xMotivo'] = !empty($retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            //estrutura "retEvento"/"infEvento"
            $aRetEvento = array();
            $aInfEvento = array();
            $aRetEventoVersao = $retEvento->getAttribute('versao');
            $aInfEventoId = $retEvento->getElementsByTagName('infEvento')->item(0)->getAttribute('id');
            $aRetEvento['versao'] = !empty($aRetEventoVersao) ? $aRetEventoVersao : '';
            $aInfEvento['id'] = !empty($aInfEventoId) ? $aInfEventoId : '';
            $aInfEvento['tpAmb'] = !empty($retEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
            $aInfEvento['verAplic'] = !empty($retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
            $aInfEvento['cOrgao'] = !empty($retEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue : '';
            $aInfEvento['cStat'] = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $aInfEvento['xMotivo'] = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            $aInfEvento['chNFe'] = !empty($retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue : '';
            $aInfEvento['tpEvento'] = !empty($retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue : '';
            $aInfEvento['nSeqEvento'] = !empty($retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue : '';
            $aInfEvento['CNPJDest'] = !empty($retEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue : '';
            $aInfEvento['CPFDest'] = !empty($retEvento->getElementsByTagName('CPFDest')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('CPFDest')->item(0)->nodeValue : '';
            $aInfEvento['emailDest'] = !empty($retEvento->getElementsByTagName('emailDest')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('emailDest')->item(0)->nodeValue : '';
            $aInfEvento['dhRegEvento'] = !empty($retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue : '';
            $aInfEvento['nProt'] = !empty($retEvento->getElementsByTagName('nProt')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('nProt')->item(0)->nodeValue : '';
            //adiciona os arrays na estrutura de retorno ficando retorno = array('retEvento'=>array('infEvento'=>array()))
            $aRetEvento['infEvento'] = $aInfEvento;
            $aResp['retEvento'] = $aRetEvento;
            //salva o arquivo xml
            if (!file_put_contents($this->cccDir."$chNFe-$nSeqEvento-procCCe.xml", $procXML)){
                $msg = "Falha na gravação da procCCe!!";
                $this->__setError($msg);
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $procXML;
    }//fim envCCe

    /**
     * manifDest
     * Manifestação do detinatário NT2012-002.
     *     210200 – Confirmação da Operação
     *     210210 – Ciência da Operação
     *     210220 – Desconhecimento da Operação
     *     210240 – Operação não Realizada
     *
     * @name manifDest
     * @param   string $chNFe Chave da NFe
     * @param   string $tpEvento Tipo do evento pode conter 2 ou 6 digitos ex. 00 ou 210200
     * @param   string $xJust Justificativa quando tpEvento = 40 ou 210240
     * @param   integer $tpAmb Tipo de ambiente
     * @param   integer $modSOAP 1 usa __sendSOP e 2 usa __sendSOAP2
     * @param   mixed  $resp variável passada como referencia e irá conter o retorno da função em um array
     * @return mixed false
     *
     * TODO : terminar o código não funcional e não testado
     */
    public function manifDest($chNFe='',$tpEvento='',$xJust='',$tpAmb='',$modSOAP='2',&$resp=''){
        try {
            if ($chNFe == ''){
                $msg = "A chave da NFe recebida é obrigatória.";
                throw new nfephpException($msg);
            }
            if ($tpEvento == ''){
                $msg = "O tipo de evento não pode ser vazio.";
                throw new nfephpException($msg);
            }
            if (strlen($tpEvento) == 2){
                $tpEvento = "2102$tpEvento";
            }
            if (strlen($tpEvento) != 6){
                $msg = "O comprimento do código do tipo de evento está errado.";
                throw new nfephpException($msg);
            }
            switch ($tpEvento){
                case '210200':
                    $descEvento = 'Confirmacao da Operacao'; //confirma a operação e o recebimento da mercadoria (para as operações com circulação de mercadoria)
                    //Após a Confirmação da Operação pelo destinatário, a empresa emitente fica automaticamente impedida de cancelar a NF-e
                    break;
                case '210210':
                    $descEvento = 'Ciencia da Operacao'; //encrenca !!! Não usar
                    //O evento de “Ciência da Operação” é um evento opcional e pode ser evitado
                    //Após um período determinado, todas as operações com “Ciência da Operação” deverão
                    //obrigatoriamente ter a manifestação final do destinatário declarada em um dos eventos de
                    //Confirmação da Operação, Desconhecimento ou Operação não Realizada
                    break;
                case '210220':
                    $descEvento = 'Desconhecimento da Operacao';
                    //Uma empresa pode ficar sabendo das operações destinadas a um determinado CNPJ
                    //consultando o “Serviço de Consulta da Relação de Documentos Destinados” ao seu CNPJ.
                    //O evento de “Desconhecimento da Operação” permite ao destinatário informar o seu
                    //desconhecimento de uma determinada operação que conste nesta relação, por exemplo
                    break;
                case '210240':
                    $descEvento = 'Operacao nao Realizada'; //não aceitação no recebimento que antes se fazia com apenas um carimbo na NF
                    //operação não foi realizada (com Recusa de Recebimento da mercadoria e outros motivos),
                    //não cabendo neste caso a emissão de uma Nota Fiscal de devolução.
                    break;
                default:
                    $msg = "O código do tipo de evento informado não corresponde a nenhum evento de manifestação de destinatário.";
                    throw new nfephpException($msg);
            }
            $resp = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','arquivo'=>'');
            if ($tpEvento == '210240' && $xJust == ''){
                    $msg = "Uma Justificativa é obrigatória para o evento de Operação não Realizada.";
                    throw new nfephpException($msg);
            }
            //limpa o texto de correção para evitar surpresas
            $xJust = $this->__cleanString($xJust);
            //ajusta ambiente
            if ($tpAmb == ''){
                $tpAmb = $this->tpAmb;
            }
            //utilizar AN para enviar o manifesto
            $sigla = 'AN';
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$sigla);
            $cOrgao='91';
            $numLote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
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
            // 2   +    6     +    44         +   2  = 54 digitos
            //“ID” + tpEvento + chave da NF-e + nSeqEvento
            $nSeqEvento = '1';
            $id = "ID".$tpEvento.$chNFe.'0'.$nSeqEvento;
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            if($xJust != ''){
                $Ev .= "<xJust>$xJust</xJust>";
            }
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = str_replace('<?xml version="1.0"?>','', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $Ev);
            $Ev = str_replace(array("\r","\n","\s"),"", $Ev);
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicitação em temp
            if (!file_put_contents($this->temDir."$chNFe-$nSeqEvento-envMDe.xml",$Ev)){
                $msg = "Falha na gravação do aruqivo envMDe!!";
                throw new nfephpException($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2'){
                $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$this->UF);
            }
            //verifica o retorno
            if (!$retorno){
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $xmlMDe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlMDe->formatOutput = false;
            $xmlMDe->preserveWhiteSpace = false;
            $xmlMDe->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEvento = $xmlMDe->getElementsByTagName("retEvento")->item(0);
            $infEvento = $xmlMDe->getElementsByTagName("infEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == ''){
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar erro de versão do XML
            if ($cStat == '238' || $cStat == '239'){
                $this->__trata239($retorno, $sigla, $tpAmb, $servico, $versao);
                $msg = "Versão do arquivo XML não suportada no webservice!!";
                throw new nfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '135' && $cStat != '136' ){
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "O Lote foi rejeitado : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            if ($cStat == '136'){
                $msg = "O Evento foi registrado mas a NFe não foi localizada : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            //o evento foi aceito
            $xmlenvMDe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvMDe->formatOutput = false;
            $xmlenvMDe->preserveWhiteSpace = false;
            $xmlenvMDe->loadXML($Ev,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvMDe->getElementsByTagName("evento")->item(0);
            //Processo completo solicitação + protocolo
            $xmlprocMDe = new DOMDocument('1.0', 'utf-8');; //cria objeto DOM
            $xmlprocMDe->formatOutput = false;
            $xmlprocMDe->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocMDe->createElement('procEventoNFe');
            $xmlprocMDe->appendChild($procEventoNFe);
            //estabele o atributo de versão
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocMDe->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocMDe->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocMDe->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocMDe->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocMDe->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocMDe->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma variável
            $procXML = $xmlprocMDe->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"",'',$procXML);
            $procXML = str_replace('default:','',$procXML);
            $procXML = str_replace(':default','',$procXML);
            $procXML = str_replace("\n",'',$procXML);
            $procXML = str_replace("\r",'',$procXML);
            $procXML = str_replace("\s",'',$procXML);
            $filename = $this->evtDir."$chNFe-$tpEvento-$nSeqEvento-procMDe.xml";
            $resp = array('bStat'=>true,'cStat'=>$cStat,'dhEvento'=>$dhEvento,'nSeqEvento'=>$nSeqEvento,'xMotivo'=>$xMotivo,'arquivo'=>$filename);
            //salva o arquivo xml
            if (!file_put_contents($filename, $procXML)){
                $msg = "Falha na gravação do arquivo procMDe!!";
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            $resp = array('bStat'=>true,'cStat'=>$cStat,'dhEvento'=>$dhEvento,'nSeqEvento'=>$nSeqEvento,'xMotivo'=>$xMotivo,'arquivo'=>'');
            return false;
        }
        return $retorno;
    } //fim manifDest

    /**
     * envDPEC
     * Apenas para teste não funcional
     *
     */
    public function envDPEC($aNFe='',$tpAmb='',$modSOAP='2'){
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        try {
            if($aNFe == ''){
                $msg = "Pelo menos uma NFe deve ser passada como parâmetro!!";
                throw new nfephpException($msg);
            }
            if($tpAmb == ''){
                $tpAmb = $this->tpAmb;
            }
            if (is_array($aNFe)){
                $matriz = $aNFe;
            } else {
                $matriz[]=$aNFe;
            }
            $i = 0;

            foreach($matriz as $n){
                $errors = null;
                $dom = null;
                $dom = new DomDocument; //cria objeto DOM
                if (is_file($n)){
                    $dom->load($n,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                } else {
                    $dom->loadXML($n,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                }
                $errors = libxml_get_errors();
                if (!empty($errors)) {
                    //o dado passado como $docXml não é um xml
                    $msg = "O dado informado não é um XML. $n " . implode('; ',$erros);
                    throw new nfephpException($msg);
                } else {
                    //pegar os dados necessários para DPEC
                    $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
                    $ide = $dom->getElementsByTagName("ide")->item(0);

                    $xtpAmb = $ide->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                    $tpEmis = $ide->getElementsByTagName("tpEmis")->item(0)->nodeValue;
                    $dhCont = !empty($dom->getElementsByTagName("dhCont")->item(0)->nodeValue) ? $dom->getElementsByTagName("dhCont")->item(0)->nodeValue : '';
                    $xJust = !empty($dom->getElementsByTagName("xJust")->item(0)->nodeValue) ? $dom->getElementsByTagName("xJust")->item(0)->nodeValue : '';
                    $verProc = !empty($dom->getElementsByTagName("verProc")->item(0)->nodeValue) ? $dom->getElementsByTagName("verProc")->item(0)->nodeValue : '';
                    $chNFe = preg_replace('/[^0-9]/','', trim($infNFe->getAttribute("Id")));
                    if ($tpEmis != '4'){
                        $msg = "O tipo de emissão deve ser igual a 4 e não $tpEmiss!!";
                        throw new nfephpException($msg);
                    }
                    if ($xJust == '' || strlen($xJust) < 15 || strlen($xJust > 256) ){
                        $msg = "A NFe deve conter uma justificativa com 15 até 256 dígitos. Sua justificativa está com " . strlen($xJust). " dígitos";
                        throw new nfephpException($msg);
                    }
                    if ($xtpAmb != $tpAmb){
                        $msg = "O tipo de ambiente indicado na NFe deve ser o mesmo da chamada do método e estão diferentes!!";
                        throw new nfephpException($msg);
                    }
                    if ($verProc == ''){
                        $msg = "O campo verProc não pode estar vazio !!";
                        throw new nfephpException($msg);
                    }

                    $dest = $dom->getElementsByTagName("dest")->item(0);
                    $destCNPJ = !empty($dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                    $destCPF  = !empty($dest->getElementsByTagName("CPF")->item(0)->nodeValue) ? $dest->getElementsByTagName("CPF")->item(0)->nodeValue : '';
                    $destUF = !empty($dest->getElementsByTagName("UF")->item(0)->nodeValue) ? $dest->getElementsByTagName("UF")->item(0)->nodeValue : '';
                    $ICMSTot = $dom->getElementsByTagName("ICMSTot")->item(0);
                    $vNF = !empty($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue : '';
                    $vICMS = !empty($ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
                    $vST = !empty($ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue : '';
                    $aD[$i]['tpAmb'] = $xtpAmb;
                    $aD[$i]['tpEmiss'] = $tpEmiss;
                    $aD[$i]['dhCont'] = $dhCont;
                    $aD[$i]['xJust'] = $xJust;
                    $aD[$i]['chNFe'] = $chNFe;
                    $aD[$i]['CNPJ'] = $destCNPJ;
                    $aD[$i]['CPF'] = $destCPF;
                    $aD[$i]['UF'] = $destUF;
                    $aD[$i]['vNF'] = $vNF;
                    $aD[$i]['vICMS'] = $vICMS;
                    $aD[$i]['vST'] = $vST;
                    $i++;
                } //fim errors
            }//fim foreach
            //com a matriz de dados montada criar o arquivo DPEC para as NFe que atendem os critérios
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'DPEC');
            //identificação do serviço
            $servico = 'SCERecepcaoRFB';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico.'';
            $dpec = '';
            $dpec .= "<envDPEC xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dpec .= "<infDPEC><id>DPEC$this->CNPJ</id>";
            $dpec .= "<ideDec><cUF>$this->cUF</cUF><tpAmb>$tpAmb</tpAmb><verProc>$verProc</verProc><CNPJ>$this->CNPJ</CNPJ><IE>$this->IE</IE></ideDec>";
            foreach($aD as $d){
                if ($d['CPF'] != ''){
                    $cnpj = "<CPF>".$d['CPF']."</CPF>";
                } else {
                    $cnpj = "<CNPJ>".$d['CNPJ']."</CNPJ>";
                }
                $dpec .= "<resNFe><chNFe>".$d['chNFe']."</chNFe>$cnpj<UF>".$d['UF']."</UF><vNF>".$d['vNF']."</vNF><vICMS>".$d['vICMS']."</vICMS><vST>".$d['vST']."</vST></resNFe>";
            }
            $dpec .= "</infDPEC></envDPEC>";
            //assinar a mensagem
            $dpec = $this->signXML($dpec, 'infDPEC');
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<sceCabecMsg xmlns="'. $namespace . '"><versaoDados>'.$versao.'</versaoDados></sceCabecMsg>';
            //montagem dos dados da cumunicação SOAP
            $dados = '<sceDadosMsg xmlns="'. $namespace . '">'.$dpec.'</sceDadosMsg>';
            //remove as tags xml que porventura tenham sido inclusas ou quebas de linhas
            $dados = str_replace('<?xml version="1.0"?>','', $dados);
            $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
            $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
            $dados = str_replace(array("\r","\n","\s"),"", $dados);
            //grava a solicitação na pasta depec
            if( !file_put_contents($this->dpcDir.$this->CNPJ.'-depc.xml', '<?xml version="1.0" encoding="utf-8"?>'.$dpec)){
                $msg = "Falha na gravação do pedido contingencia DPEC.";
                throw new nfephpException($msg);
            }
            //..... continua ainda falta bastante coisa
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $dados;
    }//fim envDPEC


    /**
     * __getXmlDom
     * Gera um $dom a partir de um $conteudoXML
     *
     * @param mixed conteudo ou caminho do XML a ser validado
     * @param string $err variavel passada como referencia onde são retornados os erros
     * @return boolean false se não confere, $dom caso o XML seja válido
     */
    protected function __getXmlDom($xml, &$err){
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        //limpar erros anteriores que possam estar em memória
        libxml_clear_errors();
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        // carrega o xml tanto pelo string contento o xml como por um path
        if (is_file($xml)){
            $dom->load($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        } else {
            $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            $msg = "O arquivo informado não é um xml.";
            $err = $msg;
            return false;
        }
        return $dom;
    }


    /**
     * __getX509Certificate
     * Extrai o certificado X509 de um $dom
     *
     * @param type $dom que possui o certificado
     * @return string o certificado
     */
    function __getX509Certificate($dom){
        // Remontando o certificado
        $X509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        $X509Certificate =  "-----BEGIN CERTIFICATE-----\n" .
                            $this->__splitLines($X509Certificate) .
                            "\n-----END CERTIFICATE-----\n";
        return $X509Certificate;
    }

    /**
     * __verifySignatureXML
     * Verifica correção da assinatura no xml
     *
     * @param string $conteudoXML xml a ser verificado
     * @param string $tag tag que é assinada
     * @param string $err variavel passada como referencia onde são retornados os erros
     * @return mixed false se não confere e true se confere
     */
    protected function __verifySignatureXML($conteudoXML, $tag, &$err){
        $dom = $this->__getXmlDom($conteudoXML, $err);

        if( $dom === false ){
            return false;
        }
        return $this->__verifySignatureXMLDom($dom, $tag, $err);
    } // fim __verifySignatureXML



    /**
     * __verifySignatureXMLDom
     * Verifica correção da assinatura no xml
     *
     * @param type $dom xml a ser verificado
     * @param string $tag tag que é assinada
     * @param string $err variavel passada como referencia onde são retornados os erros
     * @return boolean false se não confere e true se confere
     */
    protected function __verifySignatureXMLDom($dom, $tag, &$err){
        if( $dom === false ){
            return false;
        }
        $tagBase = $dom->getElementsByTagName($tag)->item(0);
        // validar digest value
        $tagInf = $tagBase->C14N(false, false, NULL, NULL);
        $hashValue = hash('sha1',$tagInf,true);
        $digestCalculado = base64_encode($hashValue);
        $digestInformado = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        if ($digestCalculado != $digestInformado){
            $msg = "O conteúdo do XML não confere com o Digest Value.\nDigest calculado [{$digestCalculado}], informado no XML [{$digestInformado}].\nO arquivo pode estar corrompido ou ter sido adulterado.";
            $err = $msg;
            return false;
        }

        $X509Certificate = $this->__getX509Certificate($dom);

        $pubKey = openssl_pkey_get_public($X509Certificate);
        if ($pubKey === false){
            $msg = "Ocorreram problemas ao remontar a chave pública. Certificado incorreto ou corrompido!!";
            $err = $msg;
            return false;
        }
        // remontando conteudo que foi assinado
        $conteudoAssinado = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
        // validando assinatura do conteudo
        $conteudoAssinadoNoXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
        $conteudoAssinadoNoXML = base64_decode(str_replace(array("\r", "\n"), '', $conteudoAssinadoNoXML));
        $ok = openssl_verify($conteudoAssinado, $conteudoAssinadoNoXML, $pubKey);
        if ($ok != 1){
            $msg = "Problema ({$ok}) ao verificar a assinatura do digital!!";
            $err = $msg;
            return false;
        }
        return true;
    } // fim __verifySignatureXMLDom

    /**
     * verifyNFe
     * Verifica a validade da NFe recebida de terceiros
     *
     * @param string $file Path completo para o arquivo xml a ser verificado
     * @param integer $modSOAP 1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return boolean false se não confere e true se confere
     */
    public function verifyNFe($file, $modSOAP=2){
        try{
            //verifica se o arquivo existe
            if (!file_exists($file)){
                $msg = "Arquivo não localizado!!";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //carrega a NFe
            $xml = file_get_contents($file);
            //testa a assinatura
            if (!$this->__verifySignatureXML($xml,'infNFe',$err)){
                $msg = "Assinatura não confere!! ".$err;
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //como a ssinatura confere, consultar o SEFAZ para verificar se a NF não foi cancelada ou é FALSA
            //carrega o documento no DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
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
            $resp = array();
            $this->getProtocol('', $chave, $tpAmb, $modSOAP, $resp);

            if ($resp['cStat']!='100'){
                $msg = "NF não aprovada no SEFAZ!! cStat =" . $resp['cStat'] .' - '.$resp['xMotivo'] ."";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if ( !is_array($resp['aProt'])){
               $msg = "Falha no retorno dos dados, retornado sem o protocolo !!";
               throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $nProtSefaz = $resp['aProt']['nProt'];
            $digestSefaz = $resp['aProt']['digVal'];
            //verificar numero do protocolo
            if ($nProt == '') {
                $msg = "A NFe enviada não contêm o protocolo de aceitação !!";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            if ($nProtSefaz != $nProt){
               $msg = "Os numeros dos protocolos não combinam!! nProtNF = " . $nProt . " <> nProtSefaz = " . $nProtSefaz."";
               throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //verifica o digest
            if ($digestSefaz != $digest){
               $msg = "Os numeros digest não combinam!! digValSEFAZ = " . $digestSefaz . " <> DigestValue = " . $digest."";
               throw new nfephpException($msg, self::STOP_CRITICAL);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } // fim verifyNFe


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
     * @param  string $spathXML  Caminho completo para o arquivo xml
     * @param  string $tpAmb  Pode ser "2-homologacao" ou "1-producao"
     * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, etc..)
     * @return mixed             false se houve erro ou array com os dado do URLs das SEFAZ
     */
    public function loadSEFAZ($spathXML, $tpAmb = '', $sUF = '')
    {
        try {
            //verifica se o arquivo xml pode ser encontrado no caminho indicado
            if (file_exists($spathXML)) {
                //carrega o xml
                $xml = simplexml_load_file($spathXML);
            } else {
                //sai caso não possa localizar o xml
                $msg = "O arquivo xml não pode ser encontrado no caminho indicado $spathXML.";
                throw new nfephpException($msg);
            }
            $aUrl = null;
            //testa parametro tpAmb
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb == '1') {
                $sAmbiente = 'producao';
            } else {
                //força homologação em qualquer outra situação
                $tpAmb = '2';
                $sAmbiente = 'homologacao';
            }
            //extrai a variável cUF do lista
            $alias = $this->aliaslist[$sUF];
            if ($alias == 'SVAN') {
                $this->enableSVAN = true;
            } else {
                $this->enableSVAN = false;
            }
            if ($alias == 'SVRS') {
                $this->enableSVRS = true;
            } else {
                $this->enableSVRS = false;
            }
            //estabelece a expressão xpath de busca
            $xpathExpression = "/WS/UF[sigla='" . $alias . "']/$sAmbiente";
            //para cada "nó" no xml que atenda aos critérios estabelecidos
            foreach ($xml->xpath($xpathExpression) as $gUF) {
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
            if ($alias == 'SVAN' || $alias == 'SVRS') {
                $xpathExpression = "/WS/UF[sigla='" . $sUF . "']/$sAmbiente";
                //para cada "nó" no xml que atenda aos critérios estabelecidos
                foreach ($xml->xpath($xpathExpression) as $gUF) {
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
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
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
     * @param  boolean $testaVal True testa a validade do certificado ou false não testa
     * @return boolean true se o certificado foi carregado e false se não
     */
    protected function __loadCerts($testaVal=true){
        try {
            if(!function_exists('openssl_pkcs12_read')){
                $msg = "Função não existente: openssl_pkcs12_read!!";
                throw new nfephpException($msg);
            }
            //monta o path completo com o nome da chave privada
            $this->priKEY = $this->certsDir.$this->cnpj.'_priKEY.pem';
            //monta o path completo com o nome da chave prublica
            $this->pubKEY =  $this->certsDir.$this->cnpj.'_pubKEY.pem';
            //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
            $this->certKEY = $this->certsDir.$this->cnpj.'_certKEY.pem';
            //verificar se o nome do certificado e
            //o path foram carregados nas variaveis da classe
            if ($this->certsDir == '' || $this->certName == '') {
                $msg = "Um certificado deve ser passado para a classe pelo arquivo de configuração!! ";
                throw new nfephpException($msg);
            }
            //monta o caminho completo até o certificado pfx
            $pfxCert = $this->certsDir.$this->certName;
            //verifica se o arquivo existe
            if(!file_exists($pfxCert)){
                $msg = "Certificado não encontrado!! $pfxCert";
                throw new nfephpException($msg);
            }
            //carrega o certificado em um string
            $pfxContent = file_get_contents($pfxCert);
            //carrega os certificados e chaves para um array denominado $x509certdata
            if (!openssl_pkcs12_read($pfxContent,$x509certdata,$this->keyPass) ){
                $msg = "O certificado não pode ser lido!! Provavelmente corrompido ou com formato inválido!!";
                throw new nfephpException($msg);
            }
            if ($testaVal){
                //verifica sua validade
                if ( !$aResp = $this->__validCerts($x509certdata['cert']) ){
                     $msg = "Certificado invalido!! - " . $aResp['error'];
                    throw new nfephpException($msg);
                }
            }
            //aqui verifica se existem as chaves em formato PEM
            //se existirem pega a data da validade dos arquivos PEM
            //e compara com a data de validade do PFX
            //caso a data de validade do PFX for maior que a data do PEM
            //deleta dos arquivos PEM, recria e prossegue
            $flagNovo = false;
            if(file_exists($this->pubKEY)){
                $cert = file_get_contents($this->pubKEY);
                if (!$data = openssl_x509_read($cert)){
                    //arquivo não pode ser lido como um certificado
                    //então deletar
                    $flagNovo = true;
                } else {
                    //pegar a data de validade do mesmo
                    $cert_data = openssl_x509_parse($data);
                    // reformata a data de validade;
                    $ano = substr($cert_data['validTo'],0,2);
                    $mes = substr($cert_data['validTo'],2,2);
                    $dia = substr($cert_data['validTo'],4,2);
                    //obtem o timeestamp da data de validade do certificado
                    $dValPubKey = gmmktime(0,0,0,$mes,$dia,$ano);
                    //compara esse timestamp com o do pfx que foi carregado
                    if( $dValPubKey < $this->pfxTimestamp){
                        //o arquivo PEM é de um certificado anterior
                        //então apagar os arquivos PEM
                        $flagNovo = true;
                    }//fim teste timestamp
                }//fim read pubkey
            } else {
                //arquivo não localizado
                $flagNovo = true;
            }//fim if file pubkey
            //verificar a chave privada em PEM
            if(!file_exists($this->priKEY)){
                //arquivo não encontrado
                $flagNovo = true;
            }
            //verificar o certificado em PEM
            if(!file_exists($this->certKEY)){
                //arquivo não encontrado
                $flagNovo = true;
            }
            //criar novos arquivos PEM
            if ($flagNovo){
                if(file_exists($this->pubKEY)){
                    unlink($this->pubKEY);
                }
                if (file_exists($this->priKEY)){
                    unlink($this->priKEY);
                }
                if (file_exists($this->certKEY)){
                    unlink($this->certKEY);
                }
                //recriar os arquivos pem com o arquivo pfx
                if (!file_put_contents($this->priKEY,$x509certdata['pkey'])) {
                    $msg = "Impossivel gravar no diretório!!! Permissão negada!!";
                    throw new nfephpException($msg);
                }
                $n = file_put_contents($this->pubKEY,$x509certdata['cert']);
                $n = file_put_contents($this->certKEY,$x509certdata['pkey']."\r\n".$x509certdata['cert']);
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
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
    * @param    string  $cert Certificado digital no formato pem
    * @param    array   $aRetorno variavel passa por referência Array com os dados do certificado
    * @return  boolean true ou false
    */
    protected function __validCerts($cert='',&$aRetorno=''){
        try{
            if ($cert == ''){
                $msg = "O certificado é um parâmetro obrigatorio.";
                throw new nfephpException($msg);
            }
            if (!$data = openssl_x509_read($cert)){
                $msg = "O certificado não pode ser lido pelo SSL - $cert .";
                throw new nfephpException($msg);
            }
            $flagOK = true;
            $errorMsg = "";
            $cert_data = openssl_x509_parse($data);
            // reformata a data de validade;
            $ano = substr($cert_data['validTo'],0,2);
            $mes = substr($cert_data['validTo'],2,2);
            $dia = substr($cert_data['validTo'],4,2);
            //obtem o timestamp da data de validade do certificado
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
            $this->pfxTimestamp = $dValid;
            $aRetorno = array('status'=>$flagOK,'error'=>$errorMsg,'meses'=>$monthsToExpire,'dias'=>$daysToExpire);
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } //fim __validCerts

    /**
     * __cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura do xml
     *
     * @name __cleanCerts
     * @param    $certFile
     * @return   mixed false ou string contendo a chave digital limpa
     */
    protected function __cleanCerts($certFile){
        try {
            //inicializa variavel
            $data = '';
            //carregar a chave publica do arquivo pem
            if (!$pubKey = file_get_contents($certFile)){
                $msg = "Arquivo não encontrado - $certFile .";
                throw new nfephpException($msg);
            }
            //carrega o certificado em um array usando o LF como referencia
            $arCert = explode("\n", $pubKey);
            foreach ($arCert AS $curData) {
                //remove a tag de inicio e fim do certificado
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                    //carrega o resultado numa string
                    $data .= trim($curData);
                }
            }
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $data;
    }//fim __cleanCerts


    /**
     * listDir
     * Método para obter todo o conteúdo de um diretorio, e
     * que atendam ao critério indicado.
     * @version 2.1.3
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
                    $msg = "Falha! sem permissão de leitura no diretorio escolhido.";
                    $this->__setError($msg);
                    if ($this->exceptions) {
                        throw new nfephpException($msg);
                    }
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
        try {
            if(!class_exists("SoapClient")){
                $msg = "A classe SOAP não está disponível no PHP, veja a configuração.";
                throw new nfephpException($msg);
            }
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
            if ($this->soapTimeout == 0){
                $tout = 999999;
            } else {
                $tout = $this->soapTimeout;
            }
            //completa a url do serviço para baixar o arquivo WSDL
            $URL = $urlsefaz.'?WSDL';
            $this->soapDebug = $urlsefaz;
            $options = array(
                'encoding'      => 'UTF-8',
                'verifypeer'    => false,
                'verifyhost'    => true,
                'soap_version'  => $soapver,
                'style'         => SOAP_DOCUMENT,
                'use'           => SOAP_LITERAL,
                'local_cert'    => $this->certKEY,
                'trace'         => true,
                'compression'   => 0,
                'exceptions'    => true,
                'connection_timeout' => $tout,
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
            } else {
                $soapFault = '';
            }
            $resposta = $oSoapClient->__getLastResponse();
            $this->soapDebug .= "\n" . $soapFault;
            $this->soapDebug .= "\n" . $oSoapClient->__getLastRequestHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastRequest();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastResponseHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->__getLastResponse();
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $resposta;
    } //fim __sendSOAP

    /**
     * __sendSOAP2
     * Função alternativa para estabelecer comunicaçao com servidor SOAP 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1 Utilizando cURL e não o SOAP nativo
     *
     * @name __sendSOAP2
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
        try {
            if ($urlsefaz == ''){
                $msg = "URL do webservice não disponível no arquivo xml das URLs da SEFAZ.";
                throw new nfephpException($msg, self::STOP_CRITICAL);
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
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
            curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
            curl_setopt($oCurl, CURLOPT_PORT , 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2); // verifica o host evita MITM
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
            $txtInfo .= "Certinfo=".print_r($info['certinfo'], true)."\n";
            $n = strlen($__xml);
            $x = stripos($__xml, "<");
            if ($x !== false){
                $xml = substr($__xml, $x, $n-$x);
            } else {
                $xml = '';
            }
            $this->soapDebug = $data."\n\n".$txtInfo."\n".$__xml;
            if ($__xml === false || $x === false){
                //não houve retorno
                $msg = curl_error($oCurl) . $info['http_code'] . $cCode[$info['http_code']];
                throw new nfephpException($msg,self::STOP_CRITICAL);
            } else {
                //houve retorno mas ainda pode ser uma mensagem de erro do webservice
                if($info['http_code'] > 300) {
                    $msg = $info['http_code'] . $cCode[$info['http_code']];
                    $this->__setError($msg);
                }
            }
            curl_close($oCurl);
            return $xml;
        } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
    } //fim __sendSOAP2

    /**
     * __getNumLot
     * Obtêm o numero do último lote de envio
     *
     * @name __getNumLot
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
     * @name __putNumLot
     * @param numeric $num Inteiro com o numero do lote enviado
     * @return boolean true sucesso ou FALSO erro
     */
    protected function __putNumLot($num){
        if ( is_numeric($num) ){
            $lotfile = $this->raizDir . 'config/numloteenvio.xml';
            $numLot = '<?xml version="1.0" encoding="UTF-8"?><root><num>' . $num . '</num></root>';
            if (!file_put_contents($lotfile,$numLot)) {
                //em caso de falha retorna falso
                $msg = "Falha ao tentar gravar o arquivo numloteenvio.xml.";
                $this->__setError($msg);
                return false;
            }
        }
        return true;
    } //fim __putNumLot

    /**
     * __getUltNSU
     * Pega o ultimo numero NSU gravado no arquivo numNSU.xml
     *
     * @name __getUltNSU
     * @param type $sigla sigla do estado (UF)
     * @param type $tpAmb tipo de ambiente 1-produção ou 2 homologação
     * @return mixed o numero encontrado no arquivo ou false em qualquer outro caso
     */
    private function __getUltNSU($sigla='',$tpAmb=''){
        try {
            if ($sigla=='' || $tpAmb==''){
                $msg = "Tanto a sigla do estado como o ambiente devem ser informados.";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $nsufile = $this->raizDir . 'config/numNSU.xml';
            if (!is_file($nsufile)){
                $msg = "O arquivo numNSU.xml não está na pasta config/.";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //buscar o ultimo NSU no xml
            $xml = new SimpleXMLElement($nsufile,null,true);
            $searchString = '/NSU/UF[@sigla="'.$sigla.'" and @tpAmb="'.$tpAmb.'"]';
            $ufn = $xml->xpath($searchString);
            $ultNSU = (string) $ufn[0]->ultNSU[0];
            if ($ultNSU == ''){
                $ultNSU = '0';
            }
         } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $ultNSU;
    }//fim __getUltNSU

    /**
     * __putUltNSU
     * Grava o ultNSU fornecido pela SEFAZ
     *
     * @name __putUltNSU
     * @param type $sigla sigla do estado (UF)
     * @param type $tpAmb tipo de ambiente
     * @param type $ultNSU Valor retornado da consulta a SEFAZ
     * @return boolean true gravado ou false falha
     */
    private function __putUltNSU($sigla,$tpAmb,$ultNSU=''){
        try {
            if ($sigla=='' || $tpAmb=='' || $ultNSU==''){
                $msg = "A sigla do estado, o tipo de ambiente e o numero do ultimo NSU são obrigatórios.";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $nsufile = $this->raizDir . 'config/numNSU.xml';
            if (!is_file($nsufile)){
                $msg = "O arquivo numNSU.xml não está na pasta config/.";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            //buscar o ultimo NSU no xml
            $xml = new SimpleXMLElement($nsufile,null,true);
            $searchString = '/NSU/UF[@sigla="'.$sigla.'" and @tpAmb="'.$tpAmb.'"]';
            $ufn = $xml->xpath($searchString);
            if ($ufn[0]->ultNSU[0] != ''){
                $ufn[0]->ultNSU[0] = $ultNSU;
            }
            if(!file_put_contents($nsufile, $xml->asXML())){
                $msg = "O arquivo não pode ser gravado na pasta config/.";
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
         } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    }//fim __putUltNSU

    /**
     * __trata239
     * Esta função corrige automaticamente todas as versões dos
     * webservices sempre que ocorrer o erro 238 ou 239
     * no retorno de qualquer requisição aos webservices
     *
     * @name __trata239
     * @param string $xml xml retornado da SEFAZ
     * @param string $UF sigla do estado
     * @param numeric $tpAmb tipo do ambiente
     * @param string $metodo método
     */
    private function __trata239($xml='',$UF='',$tpAmb='',$servico='',$versaodefault=''){
        //quando ocorre esse erro o que está errado é a versão indicada no arquivo nfe_ws2.xml
        // para esse método, então nos resta ler o retorno pegar o numero correto da versão,
        // comparar com o default e caso sejam diferentes corrigir o arquivo nfe_ws2.xml
        try {
            if ($tpAmb == ''){
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb == '1'){
                $sAmbiente = 'producao';
            } else {
                //força homologação em qualquer outra situação
                $sAmbiente = 'homologacao';
            }
            if($this->enableSCAN){
                $UF = 'SCAN';
            }
            //habilita verificação de erros
            libxml_use_internal_errors(true);
            //limpar erros anteriores que possam estar em memória
            libxml_clear_errors();
            //carrega o xml de retorno com o erro 239
            $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //recupera os erros da libxml
            $errors = libxml_get_errors();
            if (!empty($errors)) {
                //houveram erros no xml ou o arquivo não é um xml
                $msg = "O xml retornado possue erros ou não é um xml.";
                throw new nfephpException($msg, self::STOP_MESSAGE);
            }
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $versao= !empty($doc->getElementsByTagName('versaoDados')->item(0)->nodeValue) ? $doc->getElementsByTagName('versaoDados')->item(0)->nodeValue : '';
            if (($cStat == '239' || $cStat == '238') && $versao != $versaodefault){
                //realmente as versões estão diferentes => corrigir
                $nfews = $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile;
                if (is_file($nfews)){
                    //carregar o xml com os webservices
                    $objxml = new SimpleXMLElement($nfews,null,true);
                    foreach($objxml->UF as $objElem){
                        //procura dados do UF
                        if ($objElem->sigla == $UF){
                            //altera o numero da versão
                            $objElem->$sAmbiente->$servico->attributes()->version = "$versao";
                            //grava o xml alterado
                            if (!file_put_contents($nfews, $objxml->asXML())){
                                $msg = "A versão do serviço $servico de $UF [$sAmbiente] no arquivo $nfews não foi corrigida.";
                                throw new nfephpException($msg, self::STOP_MESSAGE);
                            } else {
                                break;
                            }//fim file_put
                        }//fim elem UF
                    }//fim foreach
                }//fim is file
            }//fim cStat ver=ver
         } catch (nfephpException $e) {
            $this->__setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    }//fim trata 239


    /**
     * __gunzip2
     * Descompacta strings GZIP usando arquivo temporário
     *
     * @name __gunzip2
     * @param string $data Dados compactados com gzip
     * @return string xml descompactado
     * @throws Exception
     */
    private function __gunzip2($data) {
        //cria um nome para o arquivo temporario
        do {
            $tempName = uniqid('temp ');
        } while (file_exists($tempName));
        //grava a string compactada no arquivo temporário
        if (file_put_contents($tempName, $data)) {
            try {
                ob_start();
                //efetua a leitura do arquivo descompactando e jogando o resultado
                //bo cache
                @readgzfile($tempName);
                //descarrega o cache na variável
                $uncompressed = ob_get_clean();
            } catch (Exception $e) {
                $ex = $e;
            }
            //remove o arquivo temporário
            if (file_exists($tempName)){
                unlink($tempName);
            }
            if (isset($ex)) {
                throw $ex;
            }
            //retorna a string descomprimida
            return $uncompressed;
        }
    }//fim __gunzip2

    /**
     * __gunzip1
     * Descompacta strings GZIP
     *
     * @name __gunzip1
     * @param string $data Dados compactados com gzip
     * @return mixed
     */
    private function __gunzip1($data){
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
            $msg = "Não é dado no formato GZIP.";
            $this->__setError($msg);
            return false;
        }
        $method = ord(substr($data,2,1));  // metodo de compressão
        $flags  = ord(substr($data,3,1));  // Flags
        if ($flags & 31 != $flags) {
            $msg = "Não são permitidos bits reservados.";
            $this->__setError($msg);
            return false;
        }
        // NOTA: $mtime pode ser negativo (limitações nos inteiros do PHP)
        $mtime = unpack("V", substr($data,4,4));
        $mtime = $mtime[1];
        $xfl   = substr($data,8,1);
        $os    = substr($data,8,1);
        $headerlen = 10;
        $extralen  = 0;
        $extra     = "";
        if ($flags & 4) {
            // dados estras prefixados de 2-byte no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inválidos.";
                $this->__setError($msg);
                return false;
            }
            $extralen = unpack("v",substr($data,8,2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                $msg = "Dados inválidos.";
                $this->__setError($msg);
                return false;
            }
            $extra = substr($data,10,$extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->__setError($msg);
                return false;
            }
            $filenamelen = strpos(substr($data,$headerlen),chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->__setError($msg);
                return false;
            }
            $filename = substr($data,$headerlen,$filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data no cabeçalho
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->__setError($msg);
                return false;
            }
            $commentlen = strpos(substr($data,$headerlen),chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                $msg = "Formato de cabeçalho inválido.";
                $this->__setError($msg);
                return false;
            }
            $comment = substr($data,$headerlen,$commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes de menor ordem do CRC32 esta presente no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inválidos.";
                $this->__setError($msg);
                return false;
            }
            $calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data,$headerlen,2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                $msg = "Checksum do cabeçalho falhou.";
                $this->__setError($msg);
                return false;
            }
            $headerlen += 2;
        }
        // Rodapé GZIP
        $datacrc = unpack("V",substr($data,-8,4));
        $datacrc = sprintf('%u',$datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V",substr($data,-4));
        $isize = $isize[1];
        // decompressão
        $bodylen = $len-$headerlen-8;
        if ($bodylen < 1) {
            $msg = "BUG da implementação.";
            $this->__setError($msg);
            return false;
        }
        $body = substr($data,$headerlen,$bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
            case 8:
                // Por hora somente é suportado esse metodo de compressão
                $data = gzinflate($body,null);
                break;
            default:
                $msg = "Método de compressão desconhecido (não suportado).";
                $this->__setError($msg);
                return false;
            }
        }  // conteudo zero-byte é permitido
        // Verificar CRC32
        $crc   = sprintf("%u",crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $msg = ( $lenOK ? '' : 'Verificação do comprimento FALHOU. ') . ( $crcOK ? '' : 'Checksum FALHOU.');
            $this->__setError($msg);
            return false;
        }
        return $data;
    }//fim __gunzip1

   /**
    * __convertTime
    * Converte o campo data time retornado pelo webservice
    * em um timestamp unix
    *
    * @name __convertTime
    * @param    string   $DH
    * @return   timestamp
    */
    protected function __convertTime($DH)
    {
        if ($DH) {
            $aDH = explode('T', $DH);
            $adDH = explode('-', $aDH[0]);
            $atDH = explode(':', $aDH[1]);
            $timestampDH = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
            return $timestampDH;
        }
    } //fim __convertTime

    /**
     * __splitLines
     * Divide a string do chave publica em linhas com 76 caracteres (padrão original)
     *
     * @name __splitLines
     * @param string $cnt certificado
     * @return string certificado reformatado
     */
    private function __splitLines($cnt='')
    {
        if ($cnt != '') {
            $cnt = rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cnt), 76, "\n"));
        }
        return $cnt;
    }//fim __splitLines

    /**
     * __cleanString
     * Remove todos dos caracteres espceiais do texto e os acentos
     *
     * @name __cleanString
     * @return  string Texto sem caractere especiais
     */
    private function __cleanString($texto)
    {
        $aFind = array('&','á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü','ç','Á','À','Ã','Â','É','Ê','Í','Ó','Ô','Õ','Ú','Ü','Ç');
        $aSubs = array('e','a','a','a','a','e','e','i','o','o','o','u','u','c','A','A','A','A','E','E','I','O','O','O','U','U','C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/]/", "", $novoTexto);
        return $novoTexto;
    }//fim __cleanString

    /**
     * __setError
     * Adiciona descrição do erro ao contenedor dos erros
     *
     * @name __setError
     * @param   string $msg Descrição do erro
     * @return  none
     */
    private function __setError($msg)
    {
        $this->errMsg .= "$msg\n";
        $this->errStatus = true;
    }
} //fim classe ToolsNFePHP

/**
 * Classe complementar
 * necessária para a comunicação SOAP 1.2
 * Remove algumas tags para adequar a comunicação
 * ao padrão "esquisito" utilizado pelas SEFAZ
 *
 * @version 1.0.4
 * @package NFePHP
 * @name NFeSOAP2Client
 *
 */
if (class_exists("SoapClient")) {
    class NFeSOAP2Client extends SoapClient
    {
        function __doRequest($request, $location, $action, $version, $one_way = 0)
        {
            $request = str_replace(':ns1', '', $request);
            $request = str_replace('ns1:', '', $request);
            $request = str_replace("\n", '', $request);
            $request = str_replace("\r", '', $request);
            return parent::__doRequest($request, $location, $action, $version);
        }
    }//fim NFeSOAP2Client
}//fim class exists

/**
 * Classe complementar
 * necessária para extender a classe base Exception
 * Usada no tratamento de erros da API
 *
 * @version 1.0.0
 * @package NFePHP
 * @name nfephpException
 *
 */
class nfephpException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = $this->getMessage()."\n";
        return $errorMsg;
    }
}
