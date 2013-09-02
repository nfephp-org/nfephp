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
 *
 * Esta é a classe principal para a geração, controle e comunicação dos
 * Conhecimentos de Transporte Eletrônicos CTe
 *
 * @package   NFePHP
 * @name      CTeNFePHP
 * @version   1.0.19
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; CTePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @author    Fabrício Veiga <fabriciostuff at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *          Bernardo Silva <bernardo at datamex dot com dot br>
 *          Chrystian Toigo <ctoigo at gmail dot com>
 *          Fernando Mertins <fernando.mertins at gmail dot com>
 *          Herbert Silva <hebert2 at gmail dot com>
 *          Lucimar A. Magalhaes <lucimar.magalhaes at assistsolucoes dot com dot br>
 *          Matheus Marabesi <matheusmarabesi at gmail dot com>
 *          Roberto Spadim  <roberto at spadim dot com dot br>
 *          Rodrigo Rysdyk <rodrigo_rysdyk at hotmail dot com>
 *
 *
 *
 */
// Define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}

/**
 * Classe principal "CORE class"
 */
class CTeNFePHP {
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


    // propriedades da classe
    /**
     * raizDir
     * Diretorio raiz da API
     * @var string
     */
    public $raizDir = '';
    /**
     * pdfDir
     * Diretorio onde são armazenados temporariamente as notas em pdf
     * @var string
     */
    public $pdfDir = '';
    /**
     * entDir
     * Diretorio onde são armazenados temporariamente as CTe criadas (em txt ou xml)
     * @var string
     */
    public $entDir = '';
    /**
     * valDir
     * Diretorio onde são armazenados temporariamente as CTe já validadas pela API
     * @var string
     */
    public $valDir = '';
    /**
     * repDir
     * Diretorio onde são armazenados as CTe reprovadas na validação da API
     * @var string
     */
    public $repDir = '';
    /**
     * assDir
     * Diretorio onde são armazenados temporariamente as CTe já assinadas
     * @var string
     */
    public $assDir = '';
    /**
     * envDir
     * Diretorio onde são armazenados temporariamente as CTe enviadas
     * @var string
     */
    public $envDir = '';
    /**
     * aprDir
     * Diretorio onde são armazenados temporariamente as CTe aprovadas
     * @var string
     */
    public $aprDir = '';
    /**
     * denDir
     * Diretorio onde são armazenados as CTe denegadas
     * @var string
     */
    public $denDir = '';
    /**
     * rejDir
     * Diretorio onde são armazenados os retornos e as CTe com as rejeitadas após o envio do lote
     * @var string
     */
    public $rejDir = '';
    /**
     * canDir
     * Diretorio onde são armazenados os pedidos e respostas de cancelamento
     * @var string
     */
    public $canDir = '';
    /**
     * inuDir
     * Diretorio onde são armazenados os pedidos de inutilização de numeros de notas
     * @var string
     */
    public $inuDir = '';
    /**
     * tempDir
     * Diretorio de arquivos temporarios ou não significativos para a operação do sistema
     * @var string
     */
    public $temDir = '';
    /**
     * recDir
     * Diretorio de arquivos temporarios das CTe recebidas de terceiros
     * @var string
     */
    public $recDir = '';
    /**
     * conDir
     * Diretorio de arquivos das CTe recebidas de terceiros e já validadas
     * @var string
     */
    public $conDir = '';
    /**
     * libsDir
     * Diretorios onde estão as bibliotecas e outras classes
     * @var string
     */
    public $libsDir = '';
    /**
     * certsDir
     * Diretorio onde estão os certificados
     * @var string
     */
    public $certsDir = '';
    /**
     * imgDir
     * Diretorios com a imagens, fortos, logos, etc..
     * @var string
     */
    public $imgDir = '';
    /**
     * xsdDir
     * diretorio que contem os esquemas de validação
     * estes esquemas devem ser mantidos atualizados
     * @var string
     */
    public $xsdDir = '';
    /**
     * enableSCAN
     * Habilita o acesso ao serviço SCAN ao invés do webservice estadual
     * @var boolean
     */
    public $enableSCAN = false;
    /**
     * enableSVAN
     * Indica o acesso ao serviço SVAN
     * @var boolean
     */
    public $enableSVAN = false;
    /**
     * xmlURLfile
     * Arquivo xml com as URL do SEFAZ de todos dos Estados
     * @var string
     */
    public $xmlURLfile='';
    /**
     * modSOAP
     * Indica o metodo SOAP a usar 1-SOAP Nativo ou 2-cURL
     * @var string
     */
    public $modSOAP = '2';
    /**
     * tpAmb
     * Tipo de ambiente 1-produção 2-homologação
     * @var string
     */
    private $tpAmb = '';
    /**
     * schemeVer
     * String com o nome do subdiretorio onde se encontram os schemas
     * atenção é case sensitive
     * @var string
     */
    public $cteSchemeVer;
    /**
     * aProxy
     * Matriz com as informações sobre o proxy da rede para uso pelo SOAP
     * @var array IP PORT USER PASS
     */
    public $aProxy = '';
    /**
     * aMail
     * Matiz com os dados para envio de emails
     * FROM  HOST USER PASS
     * @var array
     */
    public $aMail = '';
    /**
     * keyPass
     * Senha de acesso a chave privada
     * @var string
     */
    private $keyPass = '';
    /**
     * passPhrase
     * palavra passe para acessar o certificado (normalmente não usada)
     * @var string
     */
    private $passPhrase = '';
    /**
     * certName
     * Nome do certificado digital
     * @var string
     */
    private $certName = '';
    /**
     * certMonthsToExpire
     * Meses que faltam para o certificado expirar
     * @var integer
     */
    public $certMonthsToExpire = 0;
    /**
     * certDaysToExpire
     * Dias que faltam para o certificado expirar
     * @var integer
     */
    public $certDaysToExpire = 0;
    /**
     * priKEY
     * Path completo para a chave privada em formato pem
     * @var string
     */
    private $priKEY = '';
    /**
     * pubKEY
     * Path completo para a chave public em formato pem
     * @var string
     */
    private $pubKEY = '';
    /**
     * certKEY
     * Path completo para o certificado (chave privada e publica) em formato pem
     * @var string
     */
    private $certKEY = '';
    /**
     * empName
     * Razão social da Empresa
     * @var string
     */
    private $empName = '';
    /**
     * cnpj
     * CNPJ do emitente
     * @var string
     */
    private $cnpj = '';
    /**
     * cUF
     * Código da unidade da Federação IBGE
     * @var string
     */
    private $cUF = '';
    /**
     * UF
     * Sigla da Unidade da Federação
     * @var string
     */
    private $UF = '';
     /**
     * timeZone
     * Zona de tempo GMT
     */
    protected $timeZone = '-03:00';
    /**
     * dactelogopath
     * Variável que contem o path completo para a logo a ser impressa na DACTE
     * @var string $logopath
     */
    public $dactelogopath = '';
    /**
     * dactelogopos
     * Estabelece a posição do logo no DACTE
     * L-Esquerda C-Centro e R-Direita
     * @var string
     */
    public $dactelogopos = 'C';
    /**
     * dacteform
     * Estabelece o formato do DACTE
     * P-Retrato L-Paisagem (NOTA: somente o formato P é funcional, por ora)
     * @var string P-retrato ou L-Paisagem
     */
    public $dacteform = 'P';
    /**
     * dactepaper
     * Estabelece o tamanho da página
     * NOTA: somente o A4 pode ser utilizado de acordo com a ISO
     * @var string
     */
    public $dactepaper = 'A4';
    /**
     * dactecanhoto
     * Estabelece se o canhoto será impresso ou não
     * @var boolean
     */
    public $dactecanhoto = true;
    /**
     * dactefont
     * Estabelece a fonte padrão a ser utilizada no DACTE
     * de acordo com o Manual da SEFAZ usar somente Times
     * @var string
     */
    public $dactefont = 'Times';
   /**
     * dacteprinter
     * Estabelece a printer padrão a ser utilizada na impressão da DACTE
     * @var string
     */
    public $dacteprinter = '';
    /**
     * anoMes
     * Variável que contem o ano com 4 digitos e o mes com 2 digitos
     * Ex. 201003
     * @var string
     */
    private $anoMes = '';
    /**
     * aURL
     * Array com as url dos webservices
     * @var array
     */
    public $aURL = '';
    /**
     * aCabec
     * @var array
     */
    public $aCabec = '';
    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg = '';
    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus = false;
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
    public $soapDebug = '';
    /**
     * classDebug
     * Mensagens de debug da classe
     * @var string
     */
    public $classDebug = '';
    /**
     * URLxsi
     * Instãncia do WebService
     * @var string
     */
    private $URLxsi = 'http://www.w3.org/2001/XMLSchema-instance';
    /**
     * URLxsd
     * Instância do WebService
     * @var string
     */
    private $URLxsd = 'http://www.w3.org/2001/XMLSchema';
    /**
     * URLcte
     * Instância do WebService
     * @var string
     */
    private $URLcte = 'http://www.portalfiscal.inf.br/cte';
    /**
     * URLdsig
     * Instância do WebService
     * @var string
     */
    private $URLdsig = 'http://www.w3.org/2000/09/xmldsig#';
    /**
     * URLCanonMeth
     * Instância do WebService
     * @var string
     */
    private $URLCanonMeth = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * URLSigMeth
     * Instância do WebService
     * @var string
     */
    private $URLSigMeth = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    /**
     * URLTransfMeth_1
     * Instância do WebService
     * @var string
     */
    private $URLTransfMeth_1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    /**
     * URLTransfMeth_2
     * Instância do WebService
     * @var string
     */
    private $URLTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * URLDigestMeth
     * Instância do WebService
     * @var string
     */
    private $URLDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';
    /**
     * URLPortal
     * Instância do WebService
     * @var string
     */
    private $URLPortal = 'http://www.portalfiscal.inf.br/cte';
    /**
     * aliaslist
     * Lista dos aliases para os estados que usam o SEFAZ VIRTUAL
     * @var array
     */
    private $aliaslist = array(
            'AC' => 'SVRS',
            'AL' => 'SVRS',
            'AM' => 'SVRS',
            'AP' => 'SVRS',
            'BA' => 'BA',
            'CE' => 'SVAN',
            'DF' => 'SVRS',
            'ES' => 'SVAN',
            'GO' => 'GO',
            'MA' => 'SVAN',
            'MG' => 'MG',
            'MS' => 'SVRS',
            'MT' => 'MT',
            'PA' => 'SVAN',
            'PB' => 'SVRS',
            'PE' => 'PE',
            'PI' => 'SVAN',
            'PR' => 'PR',
            'RJ' => 'SVRS',
            'RN' => 'SVAN',
            'RO' => 'SVRS',
            'RR' => 'SVRS',
            'RS' => 'RS',
            'SC' => 'SVRS',
            'SE' => 'SVRS',
            'SP' => 'SP',
            'TO' => 'SVRS',
            'SCAN' => 'SCAN'
        );

    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $cUFlist = array(
            'AC' => '12',
            'AL' => '27',
            'AM' => '13',
            'AP' => '16',
            'BA' => '29',
            'CE' => '23',
            'DF' => '53',
            'ES' => '32',
            'GO' => '52',
            'MA' => '21',
            'MG' => '31',
            'MS' => '50',
            'MT' => '51',
            'PA' => '15',
            'PB' => '25',
            'PE' => '26',
            'PI' => '22',
            'PR' => '41',
            'RJ' => '33',
            'RN' => '24',
            'RO' => '11',
            'RR' => '14',
            'RS' => '43',
            'SC' => '42',
            'SE' => '28',
            'SP' => '35',
            'TO' => '17'
        );

    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $UFList = array (
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
            '53'=>'DF'
        );

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
     * @param  array
     * @return boolean true sucesso false Erro
     */
    public function __construct($aConfig = '') {
        // Obtem o path da biblioteca
        $this->raizDir = dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR;
        // verifica se foi passado uma matriz de configuração na inicialização da classe
        if(is_array($aConfig)) {
            $this->tpAmb = $aConfig['ambiente'];
            $this->empName = $aConfig['empresa'];
            $this->UF = $aConfig['UF'];
            $this->cUF = $this->cUFlist[$aConfig['UF']];
            $this->cnpj = $aConfig['cnpj'];
            $this->certName = $aConfig['certName'];
            $this->keyPass = $aConfig['keyPass'];
            $this->passPhrase = $aConfig['passPhrase'];
            $this->arqDir = $aConfig['cteDir'];
            $this->URLbase = $aConfig['baseurl'];
            $this->dactelogopath = $aConfig['dacteLogo'];
            $this->dactelogopos = $aConfig['dacteLogoPos'];
            $this->dacteform = $aConfig['dacteFormato'];
            $this->dactepaper = $aConfig['dactePapel'];
            $this->dactecanhoto = $aConfig['dacteCanhoto'];
            $this->dactefont = $aConfig['dacteFonte'];
            $this->dacteprinter = $aConfig['dactePrinter'];
            $this->cteSchemeVer = $aConfig['schemesCTe'];
            if(isset($aConfig['arquivoURLxmlCTe'])){
                $this->xmlURLfile = $aConfig['arquivoURLxmlCTe'];
            }
            if ($aConfig['proxyIP'] != '') {
                $this->aProxy =
                    array(
                        'IP' => $aConfig['proxyIP'],
                        'PORT' => $aConfig['proxyPORT'],
                        'USER' => $aConfig['proxyUSER'],
                        'PASS' => $aConfig['proxyPASS']
                    );
            }
            if ($aConfig['mailFROM'] != '') {
                $this->aMAIL = array(
                        'mailFROM' => $aConfig['mailFROM'],
                        'mailHOST' => $aConfig['mailHOST'],
                        'mailUSER' => $aConfig['mailUSER'],
                        'mailPASS' => $aConfig['mailPASS'],
                        'mailPROTOCOL' => $aConfig['mailPROTOCOL'],
                        'mailFROMmail' => $aConfig['mailFROMmail'],
                        'mailFROMname' => $aConfig['mailFROMname'],
                        'mailREPLYTOmail' => $aConfig['mailREPLYTOmail'],
                        'mailREPLYTOname' => $aConfig['mailREPLYTOname']);
            }
        } else {
            // Testa a existencia do arquivo de configuração
            if (is_file($this->raizDir . 'config' . DIRECTORY_SEPARATOR . 'config.php')) {
                // Carrega o arquivo de configuração
                include($this->raizDir . 'config' . DIRECTORY_SEPARATOR . 'config.php');
                // Carrega propriedades da classe com os dados de configuração
                // a sring $sAmb será utilizada para a construção dos diretorios
                // dos arquivos de operação do sistema
                $this->tpAmb = $ambiente;
                // Carrega as propriedades da classe com as configurações
                $this->empName = $empresa;
                $this->UF = $UF;
                $this->cUF = $this->cUFlist[$UF];
                $this->cnpj = $cnpj;
                $this->certName = $certName;
                $this->keyPass = $keyPass;
                $this->passPhrase = $passPhrase;
                $this->arqDir = $arquivosDirCTe;
                $this->URLbase = $baseurl;
                $this->dactelogopath = $dacteLogo;
                $this->dactelogopos = $dacteLogoPos;
                $this->dacteform = $dacteFormato;
                $this->dactepaper = $dactePapel;
                $this->dactecanhoto = $dacteCanhoto;
                $this->dactefont = $dacteFonte;
                $this->dacteprinter = $dactePrinter;
                $this->cteSchemeVer = $schemesCTe;
                if(isset($arquivoURLxmlCTe)){
                    $this->xmlURLfile = $arquivoURLxmlCTe;
                }
                if ($proxyIP != '') {
                    $this->aProxy = array(
                            'IP' => $proxyIP,
                            'PORT' => $proxyPORT,
                            'USER' => $proxyUSER,
                            'PASS' => $proxyPASS
                        );
                }

                if ($mailFROM != '') {
                    $this->aMail = array(
                            'mailFROM' => $mailFROM,
                            'mailHOST' => $mailHOST,
                            'mailUSER' => $mailUSER,
                            'mailPASS' => $mailPASS,
                            'mailPROTOCOL' => $mailPROTOCOL,
                            'mailFROMmail' => $mailFROMmail,
                            'mailFROMname' => $mailFROMname,
                            'mailREPLYTOmail' => $mailREPLYTOmail,
                            'mailREPLYTOname' => $mailREPLYTOname
                        );
                }
            } else {
                // Caso não exista arquivo de configuração retorna erro
                $this->errMsg = "Não foi localizado o arquivo de configuração.";
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
        //carrega o caminho para as imagens
        $this->imgDir =  $this->raizDir . 'images'. DIRECTORY_SEPARATOR;
        // Verifica o ultimo caracter da variável $arqDir
        // se não for um DIRECTORY_SEPARATOR então colocar um
        if (substr($this->arqDir, -1, 1) != DIRECTORY_SEPARATOR){
            $this->arqDir .= DIRECTORY_SEPARATOR;
        }
        // monta a estrutura de diretorios utilizados na manipulação das CTe
        $this->entDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'entradas' . DIRECTORY_SEPARATOR;
        $this->assDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'assinadas' . DIRECTORY_SEPARATOR;
        $this->valDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'validadas' . DIRECTORY_SEPARATOR;
        $this->rejDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'rejeitadas' . DIRECTORY_SEPARATOR;
        $this->envDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR;
        $this->aprDir=$this->envDir . 'aprovadas'  . DIRECTORY_SEPARATOR;
        $this->denDir=$this->envDir . 'denegadas'  . DIRECTORY_SEPARATOR;
        $this->repDir=$this->envDir . 'reprovadas' . DIRECTORY_SEPARATOR;
        $this->canDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'canceladas' . DIRECTORY_SEPARATOR;
        $this->inuDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'inutilizadas' . DIRECTORY_SEPARATOR;
        $this->temDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'temporarias' . DIRECTORY_SEPARATOR;
        $this->recDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'recebidas' . DIRECTORY_SEPARATOR;
        $this->conDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'consultadas' . DIRECTORY_SEPARATOR;
        $this->pdfDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
        // Monta a arvore de diretórios necessária e estabelece permissões de acesso
        if (!is_dir($this->arqDir)){
            mkdir($this->arqDir, 0777);
        }
        if (!is_dir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb)){
            mkdir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb, 0777);
        }
        if (!is_dir($this->entDir)){
            mkdir($this->entDir, 0777);
        }
        if (!is_dir($this->assDir)){
            mkdir($this->assDir, 0777);
        }
        if (!is_dir($this->valDir)){
            mkdir($this->valDir, 0777);
        }
        if (!is_dir($this->rejDir)){
            mkdir($this->rejDir, 0777);
        }
        if (!is_dir($this->envDir)){
            mkdir($this->envDir, 0777);
        }
        if (!is_dir($this->aprDir)){
            mkdir($this->aprDir, 0777);
        }
        if (!is_dir($this->denDir)){
            mkdir($this->denDir, 0777);
        }
        if (!is_dir($this->repDir)){
            mkdir($this->repDir, 0777);
        }
        if (!is_dir($this->canDir)){
            mkdir($this->canDir, 0777);
        }
        if (!is_dir($this->inuDir)){
            mkdir($this->inuDir, 0777);
        }
        if (!is_dir($this->temDir)){
            mkdir($this->temDir, 0777);
        }
        if (!is_dir($this->recDir)){
            mkdir($this->recDir, 0777);
        }
        if (!is_dir($this->conDir)){
            mkdir($this->conDir, 0777);
        }
        if (!is_dir($this->pdfDir)){
            mkdir($this->pdfDir, 0777);
        }
        // Carregar uma matriz com os dados para acesso aos WebServices SEFAZ
        $this->aURL = $this->loadSEFAZ($this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $this->tpAmb, $this->UF);
        // Se houver erro no carregamento dos certificados passe para erro
        if (!$retorno = $this->__loadCerts()){
            $this->errStatus = true;
        }
        //estados que participam do horario de verão
        $aUFhv = array('BA','ES','GO','MG','MS','PR','RJ','RS','SP','SC','TO');
        //corrigir o timeZone
        if ($this->UF == 'AC' ||
            $this->UF == 'AM' ||
            $this->UF == 'MT' ||
            $this->UF == 'MS' ||
            $this->UF == 'RO' ||
            $this->UF == 'RR' ){
            $this->timeZone = '-04:00';
        }
        //verificar se estamos no horário de verão *** depende da configuração do servidor ***
        if (date('I') == 1){
            //estamos no horario de verão verificar se o estado está incluso
            if(in_array($this->UF, $aUFhv)) {
                $tz = (int) $this->timeZone;
                $tz++;
                $this->timeZone = '-'.sprintf("%02d",abs($tz)).':00'; //poderia ser obtido com date('P')
            }
        }//fim check horario verao
        return true;
    } //fim __construct

    /**
     * validXML
     * Verifica o xml com base no xsd
     * Esta função pode validar qualquer arquivo xml do sistema de CTe
     * Há um bug no libxml2 para versões anteriores a 2.7.3
     * que causa um falso erro na validação da CTe devido ao
     * uso de uma marcação no arquivo tiposBasico_v1.02.xsd
     * onde se le {0 , } substituir por *
     *
     * @name validXML
     * @param    string  $docxml  string contendo o arquivo xml a ser validado
     * @param    string  $xsdfile Path completo para o arquivo xsd
     * @return   array   ['status','error']
     */
    public function validXML($xml='', $xsdFile='', &$aError){
        $flagOK = true;
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        //verifica se foi passado o xml
        if(strlen($xml)==0){
            $msg = 'Você deve passar o conteudo do xml assinado como parâmetro.';
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            $aError[] = $msg;
            return false;
        }
        // instancia novo objeto DOM
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false; //elimina espaços em branco
        $dom->formatOutput = false;
        // carrega o xml tanto pelo string contento o xml como por um path
        libxml_clear_errors();
        if (is_file($xml)){
            $dom->load($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        } else {
            $dom->loadXML($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
        //recupera os erros da libxml
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            //o dado passado como $docXml não é um xml
            $msg = 'O dado informado não é um XML ou não foi encontrado. Você deve passar o conteudo de um arquivo xml assinado como parâmetro.';
            foreach ($errors as $k=>$intError){
                switch ($intError->level) {
                    case LIBXML_ERR_WARNING:
                        $aError[] = " Atençao $intError->code: " . $intError->message;
                        break;
                    case LIBXML_ERR_ERROR:
                        $aError[] = " Erro $intError->code: " . $intError->message;
                        break;
                    case LIBXML_ERR_FATAL:
                        $aError[] = " Erro Fatal $intError->code: " . $intError->message;
                        break;
                }
                $msg .= $intError->message;
            }
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg, self::STOP_MESSAGE);
            }
            $aError[] = $msg;
            return false;
        }
        //verificar se a nota contem o protocolo !!!
        $nfeProc = $dom->getElementsByTagName('cteProc')->item(0);
        $Signature = $dom->getElementsByTagName('Signature')->item(0);
        if (isset($nfeProc)){
            $msg = "Esse CTe já contêm o protocolo. Não é possivel continuar, como alternativa use a verificação de CTe completo.";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg, self::STOP_MESSAGE);
            }
            $aError[] = "";
            return true;
        }
        if($xsdFile==''){
            //não foi passado o xsd então determinar qual o arquivo de schema válido
            //buscar o nome do scheme
            //extrair a tag com o numero da versão da CTe
            $node = $dom->getElementsByTagName('infCte')->item(0);
            //obtem a versão do layout da NFe
            $ver = trim($node->getAttribute("versao"));
            $aFile = $this->listDir($this->xsdDir . $this->schemeVer. DIRECTORY_SEPARATOR,'cte_v*.xsd',true);
            if (!$aFile[0]) {
                $msg = "Erro na localização do schema xsd.\n";
                $this->__setError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg, self::STOP_CRITICAL);
                }
                $aError[] = "Erro na localização do schema xsd.";
                return false;
            } else {
                $xsdFile = $aFile[0];
            }
        }
        // VAMOS PEGAR O XSD DO MODAL BASEADO NO NOME DO ARQUIVO XSD QUE RECEBEMOS, ELE DEVE ESTAR NA MESMA PASTA COM NOME:
        //  cte_v1.04.xsd -> cteModalXXXXXXXX_v1.04.xsd
        //  ou seja 3 primeiras letras iguais, e ultimas 10 letras também, e no meio escrito Modal e o nome do modal...
        $tmp1=dirname($xsdFile);
        $tmp2=basename($xsdFile);
        $tmp_nome_modal=$nfeProc = $dom->getElementsByTagName('modal')->item(0);
        if(!empty($tmp_nome_modal)){
            $tmp_nome_modal=$tmp_nome_modal->nodeValue;
            if($tmp_nome_modal=='01')    $tmp_nome_modal='Rodoviario';
            elseif($tmp_nome_modal=='02')    $tmp_nome_modal='Aereo';
            elseif($tmp_nome_modal=='03')    $tmp_nome_modal='Aquaviario';
            elseif($tmp_nome_modal=='04')    $tmp_nome_modal='Ferroviario';
            elseif($tmp_nome_modal=='05')    $tmp_nome_modal='Dutoviario';
            else    $tmp_nome_modal='';
        }else{
            $tmp_nome_modal='';
        }
        $xsdFile_modal=$tmp1. DIRECTORY_SEPARATOR . substr($tmp2,0,3)."Modal".$tmp_nome_modal.substr($tmp2,-10);
        if(!is_file($xsdFile_modal)){
            $msg = "Erro na localização do schema xsd para o modal $tmp_nome_modal.\n";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg, self::STOP_CRITICAL);
            }
            $aError[] = "Erro na localização do schema xsd do modal.";
            return false;
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
                        continue;
                    }
                }
                reset($aIntErrors);
                $flagOK = true;
            }//fim teste Signature
            $msg = '';
            foreach ($aIntErrors as $intError){
                $flagOK = false;
                $en = array("{http://www.portalfiscal.inf.br/cte}"
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
            $this->__setError($msg, self::STOP_MESSAGE);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        // validar o schema do modal agora.....
        if($tmp_nome_modal=='Rodoviario')    $tmp_tag='rodo';
        elseif($tmp_nome_modal=='Aereo')    $tmp_tag='aereo';
        elseif($tmp_nome_modal=='Aquaviario')    $tmp_tag='aquav';
        elseif($tmp_nome_modal=='Ferroviario')    $tmp_tag='ferrov';
        elseif($tmp_nome_modal=='Dutoviario')    $tmp_tag='duto';
        $tmp_modal=$dom->getElementsByTagName('infModal')->item(0);
        if ( !empty($tmp_modal) ) {
            $tmp_modal2=$tmp_modal->getElementsByTagName($tmp_tag)->item(0);
            if(empty($tmp_modal2)){
                $msg = "Erro para localizar a tag do modal $tmp_tag no xml da CTe.\n";
                $this->__setError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg, self::STOP_CRITICAL);
                }
                $aError[] = "Erro para localizar a tag do modal $tmp_tag no xml da CTe.";
                return false;
            }
            // limpa dom antigo...
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->formatOutput = true;
            $dom->preserveWhiteSpace = false;
            $tmp_modal2=$dom->importNode( $tmp_modal2 ,true);
            $dom->appendChild($tmp_modal2);
            //limpa erros anteriores
            libxml_clear_errors();
            // valida o xml com o xsd
            if ( !$dom->schemaValidate($xsdFile_modal) ) {
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
                            // isso é inutil, mas é bom ter por via das duvidas....
                            // remove o erro da assinatura, se tiver outro meio melhor (atravez dos erros de codigo) e alguem souber como tratar por eles, por favor contribua...
                            unset($aIntErrors[$k]);
                            continue;
                         }
                    }
                    reset($aIntErrors);
                    $flagOK = true;
                 }//fim teste Signature
                 $msg = '';
                 foreach ($aIntErrors as $intError){
                    $flagOK = false;
                    $en = array("{http://www.portalfiscal.inf.br/cte}"
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
                $this->__setError($msg, self::STOP_MESSAGE);
                if ($this->exceptions) {
                    throw new nfephpException($msg);
                }
            }
        }
        return $flagOK;
    } //fim validXML

    /**
     * addProt
     * Este método adiciona a tag do protocolo o CTe, preparando a mesma
     * para impressão e envio ao destinatário.
     *
     * @name addProt
     * @param   string $ctefile path completo para o arquivo contendo a CTe
     * @param   string $protfile path completo para o arquivo contendo o protocolo
     * @return  mixed false se erro ou string Retorna a CTe com o protocolo
     */
    public function addProt($ctefile='', $protfile='') {
            if($ctefile == '' || $protfile=='' ){
                $this->errStatus = true;
                $this->errMsg = ' Não foi passado algum arquivo !! ';
                return false;
            }
            if (!is_file($ctefile) || !is_file($protfile)){
                $this->errStatus = true;
                $this->errMsg = ' Arquivo não localizado !! ';
                return false;
            }
            // Protocolo do lote enviado
            $prot = new DOMDocument();
            $prot->formatOutput = false;
            $prot->preserveWhiteSpace = false;

            // CTe enviada
            $doccte = new DOMDocument();
            $doccte->formatOutput = false;
            $doccte->preserveWhiteSpace = false;

            // Carrega o arquivo na veriável
            $xmlcte = file_get_contents($ctefile);
            $doccte->loadXML($xmlcte, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cte = $doccte->getElementsByTagName("CTe")->item(0);
            $infCTe = $doccte->getElementsByTagName("infCte")->item(0);
            $versao = trim($infCTe->getAttribute("versao"));
            // Carrega o protocolo e seus dados
            $xmlprot = file_get_contents($protfile);
            $prot->loadXML($xmlprot, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $protCTe = $prot->getElementsByTagName("protCTe")->item(0);
            $protver = trim($protCTe->getAttribute("versao"));
            $tpAmb = $prot->getElementsByTagName("tpAmb")->item(0)->nodeValue;
            $verAplic = $prot->getElementsByTagName("verAplic")->item(0)->nodeValue;
            $chCTe = $prot->getElementsByTagName("chCTe")->item(0)->nodeValue;
            $dhRecbto = $prot->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
            $nProt = $prot->getElementsByTagName("nProt")->item(0)->nodeValue;
            $digVal = $prot->getElementsByTagName("digVal")->item(0)->nodeValue;
            $cStat = $prot->getElementsByTagName("cStat")->item(0)->nodeValue;
            $xMotivo = $prot->getElementsByTagName("xMotivo")->item(0)->nodeValue;
            // Cria a CTe processada com a tag do protocolo
            $procCTe = new DOMDocument('1.0', 'utf-8');
            $procCTe->formatOutput = false;
            $procCTe->preserveWhiteSpace = false;
            // Cria a tag cteProc
            $cteProc = $procCTe->createElement('cteProc');
            $procCTe->appendChild($cteProc);
            // Estabele o atributo de versão
            $cteProc_att1 = $cteProc->appendChild($procCTe->createAttribute('versao'));
            $cteProc_att1->appendChild($procCTe->createTextNode($protver));
            // Estabelece o atributo xmlns
            $cteProc_att2 = $cteProc->appendChild($procCTe->createAttribute('xmlns'));
            $cteProc_att2->appendChild($procCTe->createTextNode($this->URLcte));
            // Inclui CTe
            $node = $procCTe->importNode($cte, true);
            $cteProc->appendChild($node);
            // Cria tag protCTe
            $protCTe = $procCTe->createElement('protCTe');
            $cteProc->appendChild($protCTe);
            // Estabele o atributo de versão
            $protCTe_att1 = $protCTe->appendChild($procCTe->createAttribute('versao'));
            $protCTe_att1->appendChild($procCTe->createTextNode($versao));
            // Cria tag infProt
            $infProt = $procCTe->createElement('infProt');
            $protCTe->appendChild($infProt);
            $infProt->appendChild($procCTe->createElement('tpAmb', $tpAmb));
            $infProt->appendChild($procCTe->createElement('verAplic', $verAplic));
            $infProt->appendChild($procCTe->createElement('chCTe', $chCTe));
            $infProt->appendChild($procCTe->createElement('dhRecbto', $dhRecbto));
            $infProt->appendChild($procCTe->createElement('nProt', $nProt));
            $infProt->appendChild($procCTe->createElement('digVal', $digVal));
            $infProt->appendChild($procCTe->createElement('cStat', $cStat));
            $infProt->appendChild($procCTe->createElement('xMotivo', $xMotivo));
            // Salva o xml como string em uma variável
            $procXML = $procCTe->saveXML();
            // Remove as informações indesejadas
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            $procXML = str_replace('CTe xmlns="http://www.portalfiscal.inf.br/cte" xmlns="http://www.w3.org/2000/09/xmldsig#"', 'CTe xmlns="http://www.portalfiscal.inf.br/cte"', $procXML);
            return $procXML;
    } // Fim addProt

    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @name signXML
     * @param    string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return    mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid=''){
        if(!function_exists('openssl_get_privatekey')){
            return false;
        }
        if ( $tagid == '' ){
            $this->errMsg = 'Uma tag deve ser indicada para que seja assinada!!';
            $this->errStatus = true;
            return false;
        }
        if ( $docxml == '' ){
            $this->errMsg = 'Um xml deve ser passado para que seja assinado!!';
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
     *        cStat = 108 sistema paralizado momentaneamente, aguardar retorno
     *        cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     *                    se SCAN estiver ativado usar, caso contrário aguardar pacientemente.
     * @name statusServico
     * @param string $UF sigla da Unidade da Federação
     * @param integer $tpAmb tipo de ambiente 1-produção e 2-homologação
     * @param integer 1 usa o __sendSOAP e 2 usa o __sendSOAP2
     * @return    mixed false ou array conforme exemplo abaixo:
     * array(10) {
     * ["bStat"]     =>  bool(true),
     * ["cStat"]     =>  string(3)  "107",
     * ["tMed"]      =>  string(1)  "1",
     * ["dhRecbto"]  =>  string(19) "20/02/2012 15:47:34",
     * ["xMotivo"]   =>  string(19) "Servico em Operacao",
     * ["xObs"]      =>  string(0)  "",
     * ["tpAmb"]     =>  string(1)  "1",
     * ["verAplic"]  =>  string(16) "RS20111213141015",
     * ["cUF"]       =>  string(2)  "43",
     * ["dhRetorno"] =>  string(0)  ""
     * }
    **/
    public function statusServico($UF = '', $tpAmb = '', $modSOAP = '2') {
        // Retorno da funçao
        $aRetorno = array('bStat' => false,'cStat' => '','tMed'  => '','dhRecbto' => '','xMotivo' => '','xObs' => '');
        // Caso o parametro tpAmb seja vazio
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        $aURL = $this->aURL;
        // Caso a sigla do estado esteja vazia
        if (empty($UF)) {
            $UF = $this->UF;
        } else {
            if ($UF != $this->UF || $tpAmb != $this->tpAmb) {
                // Recarrega as url referentes aos dados passados como parametros para a função
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "cte_ws1.xml", $tpAmb, $UF);
            }
        }
        // Busca o cUF
        $cUF = $this->cUFlist[$UF];
        // Identificação do serviço
        $servico = 'CteStatusServico';
        // Recuperação da versão
        $versao = $aURL[$servico]['version'];
        // Recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        // Recuperação do método
        $metodo = $aURL[$servico]['method'];
        // Montagem do namespace do serviço
        $namespace = $this->URLPortal . '/wsdl/' . $servico;
        // Montagem do cabeçalho da comunicação SOAP
        $cabec = '<cteCabecMsg xmlns="'. $namespace . '"><cUF>' . $cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
        // Montagem dos dados da mensagem SOAP
        $dados = '<cteDadosMsg xmlns="' . $namespace . '"><consStatServCte xmlns="' . $this->URLPortal . '" versao="' . $versao . '"><tpAmb>' . $tpAmb . '</tpAmb><xServ>STATUS</xServ></consStatServCte></cteDadosMsg>';
        if ($modSOAP == '2') {
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
        }
        // Verifica o retorno do SOAP
        if (isset($retorno)) {
            // Tratar dados de retorno
            $doc = new DOMDocument();
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            //certifica que existe o elemento "cStat" no XML de retortno da SEFAZ.
            if ($cStat == ''){
                $this->errStatus = true;
                $this->errMsg = 'Nao existe o elemento "cStat" no XML de retorno da SEFAZ, erro!!';
                return false;
            }
            $aRetorno['bStat'] = ($cStat == '107');
            // Tipo de ambiente
            $aRetorno['tpAmb'] = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            // Versão do aplicativo da SEFAZ
            $aRetorno['verAplic'] = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            // Status do serviço
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // Motivo da resposta
            $aRetorno['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // Código da UF que atendeu a solicitação
            $aRetorno['cUF'] = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
            // Data e hora da mensagem
            $aRetorno['dhRecbto'] = date('d/m/Y H:i:s', $this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue));
            // Tempo médio de resposta, em segundos (opcional)
            $aRetorno['tMed'] = !empty($doc->getElementsByTagName('tMed')->item(0)->nodeValue) ? $doc->getElementsByTagName('tMed')->item(0)->nodeValue : '';
            // Data e hora prevista para o retorno do webservice (opcional)
            $aRetorno['dhRetorno'] = !empty($doc->getElementsByTagName('dhRetorno')->item(0)->nodeValue) ? date('d/m/Y H:i:s', $this->__convertTime($doc->getElementsByTagName('dhRetorno')->item(0)->nodeValue)) : '';
            // Obervações (opcional)
            $aRetorno['xObs'] = !empty($doc->getElementsByTagName('xObs')->item(0)->nodeValue) ? $doc->getElementsByTagName('xObs')->item(0)->nodeValue : '';
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno Soap verifique a mensagem de erro e o debug!!';
            $aRetorno = false;
        }
        return $aRetorno;
    } // Fim statusServico

    /**
     * consultaCadastro
     * Solicita dados de situaçao de Cadastro, somente funciona para
     * cadastros de empresas localizadas no mesmo estado do solicitante e os dados
     * retornados podem não ser os mais atuais. Não é recomendado seu uso ainda.
     *
     * @name consultaCadastro
     * @param    string  $UF
     * @param   string  $IE
     * @param   string  $CNPJ
     * @param   string  $CPF
     * @param   string  $tpAmb
     * @param   integer $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return    mixed false se falha ou array se retornada informação
     **/
    public function consultaCadastro($UF, $CNPJ = '', $IE = '', $CPF = '', $tpAmp = '', $modSOAP = '2') {
        // Variavel de retorno do metodo
        $aRetorno = array('bStat' => false,'cStat' => '','dados' => array());
        $flagIE = false;
        $flagCNPJ = false;
        $flagCPF = false;
        $marca = '';

        // Selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($IE != '') {
            $flagIE = true;
            $marca = 'IE-' . $IE;
            $filtro = '<IE>' . $IE . '</IE>';
            $CNPJ = '';
            $CPF = '';
        }
        if ($CNPJ != '') {
            $flagCNPJ = true;
            $marca = 'CNPJ-' . $CNPJ;
            $filtro = '<CNPJ>' . $CNPJ . '</CNPJ>';
            $CPF = '';
            $IE = '';
        }
        if ($CPF != '') {
            $flagCPF = true;
            $filtro = '<CPF>' . $CPF . '</CPF>';
            $marca = 'CPF-' . $CPF;
            $CNPJ = '';
            $IE = '';
        }
        // Se nenhum critério é satisfeito
        if (!($flagIE || $flagCNPJ || $flagCPF)) {
            // Erro nao foi passado parametro de filtragem
            $this->errStatus = true;
            $this->errMsg = 'Um filtro deve ser indicado CNPJ, CPF ou IE !!!';
            return false;
        }
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        // Carrega as URLs
        $aURL = $this->aURL;
        // Caso a sigla do estado seja diferente do emitente ou o ambiente seja diferente
        if ($UF != $this->UF || $tpAmb != $this->tpAmb) {
            // Recarrega as url referentes aos dados passados como parametros para a função
            $aURL = $this->loadSEFAZ($this->raizDir . 'config' . DIRECTORY_SEPARATOR . "cte_ws1.xml", $tpAmb, $UF);
        }
        // Busca o cUF
        $cUF = $this->cUFlist[$UF];
        // Identificação do serviço
        $servico = 'CteConsultaCadastro';
        // Recuperação da versão
        $versao = $aURL[$servico]['version'];
        // Recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        // Recuperação do método
        $metodo = $aURL[$servico]['method'];
        // Montagem do namespace do serviço
        $namespace = $this->URLPortal . '/wsdl/' . $servico;
        if ($urlservico == '') {
            $this->errStatus = true;
            $this->errMsg = 'Este serviço não está disponível!!!';
            return false;
        }
        // Montagem do cabeçalho da comunicação SOAP
        $cabec = '<cteCabecMsg xmlns="' . $namespace . '"><cUF>' . $cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
        // Montagem dos dados da mensagem SOAP
        $dados = '<cteDadosMsg xmlns="' . $namespace . '"><consCad xmlns="' . $this->URLcte . '" versao="' . $versao . '"><infCons><xServ>CONS-CAD</xServ><uf>' . $UF . '</uf>' . $filtro . '</infCons></consCad><cteDadosMsg>';
        // Envia a solicitação via SOAP
        if ($modSOAP == 2){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
        }
        return $aRetorno;
    } //Fim consultaCadastro

   /**
     * sendLot
     * Envia lote de Conhecimento Eletronico para a SEFAZ.
     * Este método pode enviar uma ou mais CTe para o SEFAZ, desde que,
     * o tamanho do arquivo de envio não ultrapasse 500kBytes
     * Este processo enviará somente até 50 CTe em cada Lote
     *
     * @name sendLot
     * @param    array   $aCTe conhecimento de transporte em xml uma em cada campo do array unidimensional MAX 50
     * @param   integer $id     id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma CTe
     * @param   integer $modSOAP 1 usa __sendSOP e 2 usa __sendSOAP2
     * @return    mixed    false ou array ['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'']
     * @todo Incluir regra de validação para ambiente de homologação/produção vide NT2011.002
    **/
    public function sendLot($aCTe, $id, $modSOAP = '2') {
            // Variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'');
        // Verifica se o SCAN esta habilitado
            if (!$this->enableSCAN){
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "cte_ws1.xml",$this->tpAmb,'SCAN');
        }
        // Identificação do serviço
        $servico = 'CteRecepcao';
        //var_dump($aURL);
        // Recuperação da versão
        $versao = $aURL[$servico]['version'];
        // Recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        // Recuperação do método
        $metodo = $aURL[$servico]['method'];
        // Montagem do namespace do serviço
        $namespace = $this->URLPortal . '/wsdl/' . $servico;
        // Limpa a variavel
        $sCTe = '';

        // Verificar se foram passadas até 50 CTe
        if (count($aCTe) > 50) {
            $this->errStatus = true;
            $this->errMsg = 'No maximo 50 CTe devem compor um lote de envio!!';
            return false;
        }

        // Monta string com todas as CTe enviadas no array
        $sCTe = implode('', $aCTe);
            // Remover <?xml version="1.0" encoding=... das CTe pois somente uma dessas tags pode exitir na mensagem
        $sCTe = str_replace(array('<?xml version="1.0" encoding="utf-8"?>', '<?xml version="1.0" encoding="UTF-8"?>'), '', $sCTe);
        $sCTe = str_replace(array("\r", "\n", "\s"), "", $sCTe);
        // Montagem do cabeçalho da comunicação SOAP
        $cabec = '<cteCabecMsg xmlns="' . $namespace . '"><cUF>' . $this->cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
        // Montagem dos dados da mensagem SOAP
        $dados = '<cteDadosMsg xmlns="' . $namespace . '"><enviCTe xmlns="' . $this->URLPortal . '" versao="' . $versao . '"><idLote>' . $id . '</idLote>'. $sCTe . '</enviCTe></cteDadosMsg>';
        // Envia dados via SOAP
            if ($modSOAP == '2'){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb, $this->UF);
        }
        // Verifica o retorno

        if ($retorno) {
            // Tratar dados de retorno
            $doc = new DOMDocument();
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == ''){
                return false;
            }
            $aRetorno['bStat'] = ($cStat == '103');
            // Status do serviço
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // Motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // Data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i", $this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // Numero do recibo do lote enviado (opcional)
            $aRetorno['nRec'] = !empty($doc->getElementsByTagName('nRec')->item(0)->nodeValue) ? $doc->getElementsByTagName('nRec')->item(0)->nodeValue : '';
            // Grava o retorno na pasta temp
            $nome = $this->temDir . $id . '-rec.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno Soap verifique a mensagem de erro e o debug!!';
            $aRetorno = false;
        }
        return $aRetorno;
    } // Fim sendLot

    /**
     * getProtocol
     * Solicita resposta do lote de Conhecimentos de Transporte ou o protocolo de
     * autorização da CTe$tpAmb = $this->tpAmb;
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name getProtocol
     * @param    string   $recibo numero do recibo do envio do lote
     * @param    string   $chave  numero da chave da CTe de 44 digitos
     * @param   string   $tpAmb  numero do ambiente 1 - producao e 2 - homologação
     * @param   integer   $modSOAP 1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return    mixed     false ou array
    **/
    public function getProtocol($recibo = '', $chave = '', $tpAmb = '', $modSOAP = '2') {
        // Carrega defaults
        $i = 0;
        $aRetorno = array('bStat' => false,'cStat' => '','xMotivo' => '','aProt' => '','aCanc'=>'');
        $cUF = $this->cUF;
        $UF = $this->UF;

        if ($tpAmb != '1' && $tpAmb != '2' ) {
            $tpAmb = '2';
        }

        $tpAmb = $this->tpAmb;
        $aURL = $this->aURL;
        // Verifica se a chave foi passada
        $scan = '';
        if($chave != '') {
            // Se sim extrair o cUF da chave
            $cUF = substr($chave, 0, 2);
            // Testar para ver se é o mesmo do emitente
            if($cUF != $this->cUF || $tpAmb != $this->tpAmb) {
                // Se não for o mesmo carregar a sigla
                $UF = $this->UFList[$cUF];
                // Recarrega as url referentes aos dados passados como parametros para a função
                $aURL = $this->loadSEFAZ($this->raizDir . '/config' . DIRECTORY_SEPARATOR . "cte_ws1.xml", $tpAmb, $UF);
            }
            $scan = substr($chave,34,1);
        }
        //hambiente SCAN
        if($scan == 7 || $scan == 3){
            if($cUF == 35){
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SVSP');
            }else{
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SVRS');
            }
        }

        if ($recibo == '' && $chave == '') {
            $this->errStatus = true;
            $this->errMsg = 'ERRO. Favor indicar o numero do recibo ou a chave de acesso da CTe!!';
            return false;
        }
        if ($recibo != '' && $chave != '') {
            $this->errStatus = true;
            $this->errMsg = 'ERRO. Favor indicar somente um dos dois dados ou o numero do recibo ou a chave de acesso da CTe!!';
            return false;
        }
        // Consulta pelo recibo
        if ($recibo != '' && $chave == '') {
            // Buscar os protocolos pelo numero do recibo do lote
            // Identificação do serviço
            $servico = 'CteRetRecepcao';
            // Recuperação da versão
            $versao = $aURL[$servico]['version'];
            // Recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            // Recuperação do método
            $metodo = $aURL[$servico]['method'];
            // Montagem do namespace do serviço
            $namespace = $this->URLPortal . '/wsdl/' . $servico;
            // Montagem do cabeçalho da comunicação SOAP
            $cabec = '<cteCabecMsg xmlns="' . $namespace . '"><cUF>' . $cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
            // Montagem dos dados da mensagem SOAP
            $dados = '<cteDadosMsg xmlns="' . $namespace . '"><consReciCTe xmlns="' . $this->URLPortal . '" versao="' . $versao . '"><tpAmb>' .  $tpAmb . '</tpAmb><nRec>' . $recibo . '</nRec></consReciCTe></cteDadosMsg>';
            // Nome do arquivo
            $nomeArq = $recibo . '-protrec.xml';
        }
        // Consulta pela chave
        if ($recibo == '' &&  $chave != '') {
            // Buscar o protocolo pelo numero da chave de acesso
            // Identificação do serviço

            $servico = 'CteConsultaProtocolo';
            // Recuperação da versão

            $versao = $aURL[$servico]['version'];
            // Recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            // Recuperação do método
            $metodo = $aURL[$servico]['method'];

            // Montagem do namespace do serviço
            $servico = 'CteConsulta';
            $namespace = $this->URLPortal . '/wsdl/' . $servico;
            // Montagem do cabeçalho da comunicação SOAP
            $cabec = '<cteCabecMsg xmlns="' . $namespace . '"><cUF>' . $cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
            // Montagem dos dados da mensagem SOAP
            $dados = '<cteDadosMsg xmlns="' . $namespace . '"><consSitCTe xmlns="' . $this->URLPortal . '" versao="' . $versao . '"><tpAmb>' . $tpAmb . '</tpAmb><xServ>CONSULTAR</xServ><chCTe>' . $chave . '</chCTe></consSitCTe></cteDadosMsg>';
        }

        // Envia a solicitação via SOAP
        if ($modSOAP == 2){
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb,$UF);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
        }
        // Verifica o retorno
        if ($retorno) {
            // Tratar dados de retorno
            $doc = new DOMDocument();
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == '') {
                return false;
            }
            // O retorno vai variar se for buscado o protocolo ou recibo
            // Retorno nda consulta pela Chave do CTe
            // retConsSitCTe 100 aceita 110 denegada 101 cancelada ou outro recusada
            // cStat xMotivo cUF chCTe protCTe retCancCTe
            if ($chave != '') {
                $aRetorno['bStat'] = true;
                $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                $infProt = $doc->getElementsByTagName('infProt')->item($i);
                $infCanc = $doc->getElementsByTagName('infCanc')->item(0);
                $aProt = '';
                if (isset($infProt)){
                    foreach($infProt->childNodes as $t){
                        $aProt[$i][$t->nodeName] = $t->nodeValue;
                    }
                    $aProt['dhRecbto'] = !empty($aProt['dhRecbto']) ? date("d/m/Y H:i:s",$this->__convertTime($aProt['dhRecbto'])) : '';
                }else {
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
                // Gravar o retorno na pasta temp apenas se a nota foi aprovada, cancelada ou denegada
                if ( $aRetorno['cStat'] == 100 || $aRetorno['cStat'] == 101 || $aRetorno['cStat'] == 110 ) {
                    // Nome do arquivo
                    $nomeArq = $chave . '-prot.xml';
                    $nome = $this->temDir . $nomeArq;
                    $nome = $doc->save($nome);
                }
            }
            // Retorno da consulta pelo recibo
            // CTeRetRecepcao 104 tem retornos
            // nRec cStat xMotivo cUF cMsg xMsg protCte* infProt chCTe dhRecbto nProt cStat xMotivo
            if ($recibo != '') {
                $aRetorno['bStat'] = true;
                // status do serviço
                $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                // motivo da resposta (opcional)
                $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                if ($cStat == '104'){
                    $aProt = '';
                    //aqui podem ter varios retornos dependendo do numero de CTe enviados no Lote e já processadas
                    $protCTe = $doc->getElementsByTagName('protCTe');
                    foreach ($protCTe as $d){
                        $infProt = $d->getElementsByTagName('infProt')->item($i);
                        $protcStat = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
                        //pegar os dados do protolo para retornar
                        foreach($infProt->childNodes as $t) {
                            $aProt[$i][$t->nodeName] = $t->nodeValue;
                        }
                        $i++; //incluido increment para controlador de indice do array
                        //salvar o protocolo somente se a nota estiver approvada ou denegada
                        if ( $protcStat == 100 || $protcStat == 110 ){
                            $nomeprot = $this->temDir.$infProt->getElementsByTagName('chCTe')->item(0)->nodeValue.'-prot.xml';//id da nfe
                            //salvar o protocolo em arquivo
                            $novoprot = new DOMDocument('1.0', 'UTF-8');
                            $novoprot->formatOutput = true;
                            $novoprot->preserveWhiteSpace = false;
                            $pCTe = $novoprot->createElement("protCTe");
                            $pCTe->setAttribute("versao", "1.03");
                            // Importa o node e todo o seu conteudo
                            $node = $novoprot->importNode($infProt, true);
                            // acrescenta ao node principal
                            $pCTe->appendChild($node);
                            $novoprot->appendChild($pCTe);
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
                        }
                    }
                }
                $aRetorno['aProt'] = $aProt; //passa o valor de $aProt para o array de retorno
                $nomeArq = $recibo . '-recprot.xml';
                $nome = $this->temDir . $nomeArq;
                $nome = $doc->save($nome);
            }
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno Soap verifique a mensagem de erro e o debug!!';
            $aRetorno = false;
        }
        return $aRetorno;
    } //fim getProtocol

    /**
     * Solicita inutilizaçao de uma serie de numeros de CT
     *
     * @name inutNF
     * @param    string  $nAno       ano com 2 digitos
     * @param   string  $nSerie     serie do CT 1 até 3 digitos
     * @param   integer $nIni       numero inicial 1 até 9 digitos zero a esq
     * @param   integer $nFin       numero Final 1 até 9 digitos zero a esq
     * @param   string  $xJust      justificativa 15 até 255 digitos
     * @param   integer $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return    mixed false ou array ['bStat'=>boolean,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nProt'=>'']
     */
    public function inutCT($nAno = '', $nSerie = '1', $nIni = '', $nFin = '', $xJust = '', $modSOAP = '2') {
        // Variavel de retorno do metodo
        $aRetorno = array (
            'bStat' => false,
            'cStat' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'nProt' => '');
        // Valida dos dados de entrada
        if($nAno == '' || $nIni == '' || $nFin == '' || $xJust == '') {
            $this->errStatus = true;
            $this->errMsg = "Não foi passado algum dos parametos necessários ANO=$nAno inicio=$nIni fim=$nFin justificativa=$xJust.";
            return false;
        }

        // Verifica se o SCAN esta habilitado
        $aURL = $this->loadSEFAZ($this->raizDir . 'config' . DIRECTORY_SEPARATOR . "cte_ws1.xml", $this->tpAmb, 'SCAN');
        if (!$this->enableSCAN)
            $aURL = $this->aURL;

        // Valida o campo ano
        if(strlen($nAno) > 2) {
            $this->errStatus = true;
            $this->errMsg = 'O ano tem mais de 2 digitos. Corrija e refaça o processo!!';
            return false;
        } else {
            if (strlen($nAno) < 2) {
                $this->errStatus = true;
                $this->errMsg = 'O ano tem menos de 2 digitos. Corrija e refaça o processo!!';
                return false;
            }
        }
        // Valida o campo serie
        if( strlen($nSerie) == 0 || strlen($nSerie) > 3) {
            $this->errStatus = true;
            $this->errMsg = "O campo serie está errado: $nSerie. Corrija e refaça o processo!!";
            return false;
        }
        // Valida o campo numero inicial
        if (strlen($nIni) < 1 || strlen($nIni) > 9) {
            $this->errStatus = true;
            $this->errMsg = "O campo numero inicial está errado: $nIni. Corrija e refaça o processo!!";
            return false;
        }
        // Valida o campo numero final
        if (strlen($nFin) < 1 || strlen($nFin) > 9) {
            $this->errStatus = true;
            $this->errMsg = "O campo numero final está errado: $nFin. Corrija e refaça o processo!!";
            return false;
        }
        // Valida o campo justificativa
        $nL = strlen($xJust);
        if ($nL < 15) {
            $this->errStatus = true;
            $this->errMsg = "A justificativa é menor que o permitido, apenas $nL letras. Corrija e refaça o processo!!";
            return false;
        } else {
            if ($nL > 255) {
                $this->errStatus = true;
                $this->errMsg = "A justificativa é maior que o permitido, $nL letras, no máximo podem ser 255. Corrija e refaça o processo!!";
                return false;
            }
        }
        // Identificação do serviço
        $servico = 'CteInutilizacao';
        // Recuperação da versão
        $versao = $aURL[$servico]['version'];
        // Recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        // Recuperação do método
        $metodo = $aURL[$servico]['method'];
        // Montagem do namespace do serviço
        $namespace = $this->URLPortal . '/wsdl/' . $servico;
        // Identificador da TAG a ser assinada formada com Código da UF +
        // precedida do literal “ID”
        // 41 posições
        $id = 'ID' . $this->cUF . $this->cnpj . '57' . str_pad($nSerie, 3, '0', STR_PAD_LEFT) . str_pad($nIni, 9, '0', STR_PAD_LEFT) . str_pad($nFin, 9, '0', STR_PAD_LEFT);
        // Montagem do cabeçalho da comunicação SOAP
        $cabec = '<cteCabecMsg xmlns="' . $namespace . '"><cUF>' . $this->cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
        // Montagem do corpo da mensagem
        $dXML = '<inutCTe xmlns="' . $this->URLcte . '" versao="' . $versao . '">';
        $dXML .= '<infInut Id="' . $id . '">';
        $dXML .= '<tpAmb>' . $this->tpAmb . '</tpAmb>';
        $dXML .= '<xServ>INUTILIZAR</xServ>';
        $dXML .= '<cUF>' . $this->cUF . '</cUF>';
        $dXML .= '<ano>' . $nAno . '</ano>';
        $dXML .= '<CNPJ>' . $this->cnpj . '</CNPJ>';
        $dXML .= '<mod>57</mod>';
        $dXML .= '<serie>' . $nSerie . '</serie>';
        $dXML .= '<nCTIni>' . $nIni . '</nCTIni>';
        $dXML .= '<nCTFin>' . $nFin . '</nCTFin>';
        $dXML .= '<xJust>' . $xJust . '</xJust>';
        $dXML .= '</infInut>';
        $dXML .= '</inutCTe>';
        // Assina a lsolicitação de inutilização
        $dXML = $this->signXML($dXML, 'infInut');
        $dados = '<cteDadosMsg xmlns="' . $namespace . '">' . $dXML . '</cteDadosMsg>';
        // Remove as tags xml que porventura tenham sido inclusas
        $dados = str_replace('<?xml version="1.0"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
        $dados = str_replace(array("\r","\n","\s"),"", $dados);
        // Envia a solicitação via SOAP
        if ($modSOAP == '2') {
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb, $this->UF);
        }
        // Verifica o retorno
        if ($retorno) {
            //tratar dados de retorno
            $doc = new DOMDocument();
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
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

            // Status do serviço se 102 inutilização aceita
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // Motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // Data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // Numero do protocolo de aceitação da inutilização (opcional)
            $aRetorno['nProt'] = !empty($doc->getElementsByTagName('nProt')->item(0)->nodeValue) ? $doc->getElementsByTagName('nProt')->item(0)->nodeValue : '';
            // Grava o retorno na pasta temp
            $nome = $this->temDir . $id . '-inut.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno Soap verifique o debug!!';
            $aRetorno = false;
        }
        return $aRetorno;
    } //fim inutCTe


    /**
     * Solicita o cancelamento do CT enviado
     *
     * @name cancelCT
     * @param    string  $id      ID da CTe com 44 digitos (sem o CTe na frente dos numeros)
     * @param   string  $protId     Numero do protocolo de aceitaçao do lote de CTe enviado anteriormente pelo SEFAZ
     * @param   boolean $modSOAP    1 usa __sendSOAP e 2 usa __sendSOAP2
     * @return    mixed false se falha ou array [
    **/
    public function cancelCT($id, $protId, $xJust, $modSOAP = '2') {
        // Variável de retorno
        $aRetorno = array(
            'bStat' => false,
            'cStat' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'nProt' => '');
        // Validação dos dados de entrada
        if($id == '' || $protId == '' || $xJust == '') {
            $this->errStatus = true;
            $this->errMsg = "Não foi passado algum dos parâmetros necessários ID=$id ou protocolo=$protId ou justificativa=$xJust.";
            return $aRetorno;
        }
        // Verifica se o SCAN esta habilitado
        $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "cte_ws1.xml", $this->tpAmb, 'SCAN');
        if (!$this->enableSCAN){
            $aURL = $this->aURL;
        }
        // Identificação do serviço
        $servico = 'CteCancelamento';
        // Recuperação da versão
        $versao = $aURL[$servico]['version'];
        // Recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        // Recuperação do método
        $metodo = $aURL[$servico]['method'];
        // Montagem do namespace do serviço
        $namespace = $this->URLPortal . '/wsdl/' . $servico;
        // Montagem do cabeçalho da comunicação SOAP
        $cabec = '<cteCabecMsg xmlns="' . $namespace . '"><cUF>' . $this->cUF . '</cUF><versaoDados>' . $versao . '</versaoDados></cteCabecMsg>';
        // Montagem dos dados da mensagem SOAP
        $dXML = '<cancCTe xmlns="' . $this->URLcte . '" versao="' . $versao . '">';
        $dXML .= '<infCanc Id="ID' . $id . '"><tpAmb>' . $this->tpAmb . '</tpAmb><xServ>CANCELAR</xServ><chCTe>' . $id . '</chCTe><nProt>' . $protId . '</nProt><xJust>' . $xJust . '</xJust></infCanc></cancCTe>';
        // Assinar a mensagem
        $dXML = $this->signXML($dXML, 'infCanc');
        $dados = '<cteDadosMsg xmlns="' . $namespace . '">' . $dXML . '</cteDadosMsg>';
        // Remove as tags xml que porventura tenham sido inclusas ou quebas de linhas
        $dados = str_replace('<?xml version="1.0"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
        $dados = str_replace(array("\r","\n","\s"),"", $dados);
        // Envia a solicitação via SOAP
        if ($modSOAP == 2) {
            $retorno = $this->__sendSOAP2($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->__sendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb, $this->UF);
        }
        // Verifica o retorno
        if ($retorno) {
            // Tratar dados de retorno
            $doc = new DOMDocument();
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                return false;
            } else {
                if ($cStat == '101'){
                    $aRetorno['bStat'] = true;
                }
            }

            // Status do serviço se 101 cancelamento aceito
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // Motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // Data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // Numero do protocolo de cancelamento da CTe (opcional)
            $aRetorno['nProt'] = !empty($doc->getElementsByTagName('nProt')->item(0)->nodeValue) ? $doc->getElementsByTagName('nProt')->item(0)->nodeValue : '';
            // Gravar o retorno na pasta temp
            $nome = $this->temDir . $id . '-canc.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno Soap verifique a mensagem de erro e o debug!!';
            $aRetorno = false;
        }
        return $aRetorno;
    } // Fim cancelNF

    /**
     * verifySignatureXML
     * Verifica correção da assinatura no xml
     * @name verifySignatureXML
     * @param string $conteudoXML xml a ser verificado
     * @param string $tag tag que é assinada
     * @return boolean false se não confere e true se confere
     */
    public function verifySignatureXML($conteudoXML, $tag) {
    if(!function_exists('openssl_pkey_get_public'))
        return false;
        $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    $dom->loadXML($conteudoXML);
    $tagBase = $dom->getElementsByTagName($tag)->item(0);
    // validar digest value
    $tagInf = $tagBase->C14N(false, false, null, null);
    $tagInf = str_replace(' xmlns:ds="http://www.w3.org/2000/09/xmldsig#"', '', $tagInf);
        $digestCalculado = base64_encode(sha1($tagInf, true));
    $digestInformado = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;
    if ($digestCalculado != $digestInformado){
            $this->errStatus = true;
            $this->errMsg = "O conteúdo do XML não confere com o Digest Value.\nDigest calculado [{$digestCalculado}], informado no XML [{$digestInformado}].\nO arquivo pode estar corrompido ou ter sido adulterado.";
            return false;
        }
    // Remontando o certificado
    $X509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
    $X509Certificate =  "-----BEGIN CERTIFICATE-----\n".
    $this->__splitLines($X509Certificate)."\n-----END CERTIFICATE-----\n";
    $pubKey = openssl_pkey_get_public($X509Certificate);
    if ($pubKey === false){
            $this->errStatus = true;
            $this->errMsg = 'Ocorreram problemas ao remontar a chave pública. Certificado incorreto ou corrompido!!';
            return false;
        }
    // remontando conteudo que foi assinado
    $conteudoAssinado = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
    $conteudoAssinado = str_replace(array('xmlns:ds="http://www.w3.org/2000/09/xmldsig#"',' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'),'',$conteudoAssinado);
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
     * verifyCTe
     * Verifica a validade da CTe recebida de terceiros
     *
     * @name verifyCTe
     * @param string $file Path completo para o arquivo xml a ser verificado
     * @return boolean false se nÃ£o confere e true se confere
     */
    public function verifyCTe($file) {
        //verifica se o arquivo existe
        if (file_exists($file)) {
            //carrega a CTe
            $xml = file_get_contents($file);
            //testa a assinatura
            if ($this->verifySignatureXML($xml, 'infCte')) {
                //como a ssinatura confere, consultar o SEFAZ para verificar se a CT não foi cancelada ou é FALSA
                //carrega o documento no DOM
                $xmldoc = new DOMDocument();
                $xmldoc->preservWhiteSpace = false; //elimina espaÃ§os em branco
                $xmldoc->formatOutput = false;
                $xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                $root = $xmldoc->documentElement;
                $infCte = $xmldoc->getElementsByTagName('infCte')->item(0);
                //extrair a tag com os dados a serem assinados
                $id = trim($infCte->getAttribute("Id"));
                $chave = preg_replace('/[^0-9]/', '', $id);
                $digest = $xmldoc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                //ambiente da CTe sendo consultada
                $tpAmb = $infCte->getElementsByTagName('tpAmb')->item(0)->nodeValue;
                //verifica se existe o protocolo
                $protCTe = $xmldoc->getElementsByTagName('protCTe')->item(0);
                if (isset($protCTe)) {
                    $nProt = $xmldoc->getElementsByTagName('nProt')->item(0)->nodeValue;
                } else {
                    $nProt = '';
                }
                //busca o status da CTe na SEFAZ do estado do emitente
                $resp = $this->getProtocol('', $chave, $tpAmb, '2');
                if ($resp['cStat'] != '100') {
                    //ERRO! ct não aprovada
                    $this->errStatus = true;
                    $this->errMsg = "CT não aprovada no SEFAZ!! cStat =" . $resp['cStat'] . ' - ' . $resp['xMotivo'];
                    return false;
                } else {
                    if (is_array($resp['aProt'][0])) {
                        $nProtSefaz = $resp['aProt'][0]['nProt'];
                        $digestSefaz = $resp['aProt'][0]['digVal'];
                        //verificar numero do protocolo
                        if ($nProt != '') {
                            if ($nProtSefaz != $nProt) {
                                //ERRO !!!os numeros de protocolo não combinam
                                $this->errStatus = true;
                                $this->errMsg = "Os numeros dos protocolos não combinam!! nProtCT = " . $nProt . " <> nProtSefaz = " . $nProtSefaz;
                                return false;
                            } //fim teste do protocolo
                        } else {
                            $this->errStatus = true;
                            $this->errMsg = "A CTe enviada não comtêm o protocolo de aceitação !!";
                        }
                        //verifica o digest
                        if ($digestSefaz != $digest) {
                            //ERRO !!!os numeros digest não combinam
                            $this->errStatus = true;
                            $this->errMsg = "Os numeros digest não combinam!! digValSEFAZ = " . $digestSefaz . " <> DigestValue = " . $digest;
                            return false;
                        } //fim teste do digest value
                    } else {
                        //o retorno veio como 100 mas por algum motivo sem o protocolo
                        $this->errStatus = true;
                        $this->errMsg = "Falha no retorno dos dados, retornado sem o protocolo !! ";
                        return false;
                    }
                }
            } else {
                $this->errStatus = true;
                $this->errMsg = " Assinatura não confere!!";
                return false;
            } //fim verificação da assinatura
        } else {
            $this->errStatus = true;
            $this->errMsg = "Arquivo não localizado!!";
            return false;
        } //fim file_exists
        return true;
    } //fim verifyCTe

    /**
     * __splitLines
     * Divide a string do certificado publico em linhas com 76 caracteres (padrão original)
     * @name __splitLines
     * @param string $cnt certificado
     * @return string certificado reformatado
     */
    private function __splitLines($cnt){
        return rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cnt), 76, "\n"));
    } // Fim __splitLines

   /**
    * loadSEFAZ
    * Função para extrair o URL, nome do serviço e versão dos webservices das SEFAZ de
    * todos os Estados da Federação do arquivo urlWebServicesCTe.xml
    *
    * O arquivo xml é estruturado da seguinte forma :
    * <ws>
    *   <uf>
    *      <sigla>AC</sigla>
    *          <homologacao>
    *              <Recepcao service='CTeRecepcao' versao='1.10'>http:// .....
    *              ....
    *          </homologacao>
    *          <producao>
    *              <Recepcao service='CTeRecepcao' versao='1.10'>http:// ....
    *              ....
    *          </producao>
    *   </uf>
    *   <uf>
    *      ....
    * </ws>
    *
    * @name loadSEFAZ
    * @param  string $spathXML  Caminho completo para o arquivo xml
    * @param  string $tpAmb  Pode ser "2-homologacao" ou "1-producao"
    * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, etc..)
    * @return mixed             false se houve erro ou array com os dado do URLs das SEFAZ
    */
    public function loadSEFAZ($spathXML, $tpAmb = '', $sUF) {
        // Verifica se o arquivo xml pode ser encontrado no caminho indicado
        if (file_exists($spathXML)) {
            // Carrega o xml
            $xml = simplexml_load_file($spathXML);
        } else {
            // Sai caso não possa localizar o xml
            return false;
        }
        $aUrl = null;
        // Testa parametro tpAmb
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        if ($tpAmb == '1'){
            $sAmbiente = 'producao';
        } else {
            // Força homologação em qualquer outra situação
            $tpAmb = '2';
            $sAmbiente = 'homologacao';
        }
        // Extrai a variável cUF do lista
        $alias = $this->aliaslist[$sUF];
        $this->enableSVAN = ($alias == 'SVAN');
        // Estabelece a expressão xpath de busca
        $xpathExpression = "/WS/UF[sigla='" . $alias . "']/$sAmbiente";
        // Para cada "nó" no xml que atenda aos critérios estabelecidos
        foreach ($xml->xpath( $xpathExpression) as $gUF) {
            // Para cada "nó filho" retonado
            foreach ($gUF->children() as $child) {
                $u = (string) $child[0];
                $aUrl[$child->getName()]['URL'] = $u;
                // Em cada um desses nós pode haver atributos como a identificação
                // do nome do webservice e a sua versão
                foreach ($child->attributes() as $a => $b) {
                    $aUrl[$child->getName()][$a] = (string) $b;
                }
            }
        }
        return $aUrl;
    } // Fim loadSEFAZ

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
     * @param   none
     * @return    boolean true se o certificado foi carregado e false se nao
     **/
    protected function __loadCerts(){
    if(!function_exists('openssl_pkcs12_read')){
            $msg = "Função não existente: openssl_pkcs12_read!! ";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
    }
        // Monta o path completo com o nome da chave privada
        $this->priKEY = $this->certsDir . $this->cnpj . '_priKEY.pem';
        // Monta o path completo com o nome da chave publica
        $this->pubKEY = $this->certsDir . $this->cnpj . '_pubKEY.pem';
        // Monta o path completo com o nome do certificado (chave publica e privada) em formato pem
        $this->certKEY = $this->certsDir . $this->cnpj . '_certKEY.pem';
        // Verificar se o nome do certificado e
        // o path foram carregados nas variaveis da classe
        if ($this->certsDir == '' || $this->certName == '') {
            $this->errMsg = 'Um certificado deve ser passado para a classe!!';
            $this->errStatus = true;
            return false;
        }
        // Monta o caminho completo até o certificado pfx
        $pCert = $this->certsDir . $this->certName;
        // Verifica se o arquivo existe
        if(!file_exists($pCert)) {
            $this->errMsg = 'Certificado não encontrado!!';
            $this->errStatus = true;
            return false;
        }
        // Carrega o certificado em um string
        $key = file_get_contents($pCert);
        // Carrega os certificados e chaves para um array denominado $x509certdata
        if (!openssl_pkcs12_read($key, $x509certdata, $this->keyPass)) {
            $this->errMsg = 'O certificado não pode ser lido!! Provavelmente corrompido ou com formato inválido!!';
            $this->errStatus = true;
            return false;
        }
        // Verifica sua validade
        $aResp = $this->__validCerts($x509certdata['cert']);
        if ($aResp['error'] != '') {
            $this->errMsg = 'Certificado invalido!! - ' . $aResp['error'];
            $this->errStatus = true;
            return false;
        }
        // Verifica se arquivo já existe
        if (file_exists($this->priKEY)) {
            // Se existir verificar se é o mesmo
            $conteudo = file_get_contents($this->priKEY);
            // Comparar os primeiros 30 digitos
            if (!substr($conteudo, 0, 30) == substr($x509certdata['pkey'], 0, 30)) {
                 // Se diferentes gravar o novo
                if (!file_put_contents($this->priKEY,$x509certdata['pkey'])) {
                    $this->errMsg = 'Impossivel gravar no diretório!!! Permissão negada!!';
                    $this->errStatus = true;
                    return false;
                }
            }
        } else {
            // Salva a chave privada no formato pem para uso so SOAP
            if (!file_put_contents($this->priKEY, $x509certdata['pkey'])) {
                   $this->errMsg = 'Impossivel gravar no diretório!!! Permissão negada!!';
                   $this->errStatus = true;
                   return false;
            }
        }
        // Verifica se arquivo com a chave publica já existe
        if (file_exists($this->pubKEY)) {
            // Se existir verificar se é o mesmo atualmente instalado
            $conteudo = file_get_contents($this->pubKEY);
            // Comparar os primeiros 30 digitos
            if (!substr($conteudo, 0, 30) == substr($x509certdata['cert'], 0, 30)) {
                // Se diferentes gravar o novo
                $n = file_put_contents($this->pubKEY, $x509certdata['cert']);
                // Salva o certificado completo no formato pem
                $n = file_put_contents($this->certKEY, $x509certdata['pkey'] . "\r\n" . $x509certdata['cert']);
            }
        } else {
            // Se não existir salva a chave publica no formato pem para uso do SOAP
            $n = file_put_contents($this->pubKEY, $x509certdata['cert']);
            // Salva o certificado completo no formato pem
            $n = file_put_contents($this->certKEY, $x509certdata['pkey'] . "\r\n" . $x509certdata['cert']);
        }
        return true;
    } //Fim loadCerts


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
    * @return    array ['status'=>true,'meses'=>8,'dias'=>245]
    */
    protected function __validCerts($cert){
    if(!function_exists('openssl_x509_read'))
        return false;
        $flagOK = true;
        $errorMsg = "";
        $data = openssl_x509_read($cert);
        $cert_data = openssl_x509_parse($data);
        // Reformata a data de validade;
        $ano = substr($cert_data['validTo'], 0, 2);
        $mes = substr($cert_data['validTo'], 2, 2);
        $dia = substr($cert_data['validTo'], 4, 2);
        // Obtem o timeestamp da data de validade do certificado
        $dValid = gmmktime(0,0,0,$mes,$dia,$ano);
        // Obtem o timestamp da data de hoje
        $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
        // Compara a data de validade com a data atual
        if ($dValid < $dHoje) {
            $flagOK = false;
            $errorMsg = "A Validade do certificado expirou em [" . $dia . '/' . $mes . '/' . $ano . "]";
        } else {
            $flagOK = $flagOK && true;
        }
        // Diferença em segundos entre os timestamp
        $diferenca = $dValid - $dHoje;
        // Convertendo para dias
        $diferenca = round($diferenca / (60 * 60 * 24), 0);
        // Carregando a propriedade
        $daysToExpire = $diferenca;
        // Convertendo para meses e carregando a propriedade
        $m = ($ano * 12 + $mes);
        $n = (date("y") * 12 + date("m"));
        // Numero de meses até o certificado expirar
        $monthsToExpire = ($m - $n);
        $this->certMonthsToExpire = $monthsToExpire;
        $this->certDaysToExpire = $daysToExpire;
        return array(
            'status' => $flagOK,
            'error' => $errorMsg,
            'meses' => $monthsToExpire,
            'dias' => $daysToExpire);
    } //Fim validCerts


    /**
     * __cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura do xml
     *
     * @name __cleanCerts
     * @param    $certFile
     * @return   string contendo a chave digital limpa
     * @access   private
     **/
    protected function __cleanCerts($certFile){
        // Carregar a chave publica do arquivo pem
        $pubKey = file_get_contents($certFile);
        // Inicializa variavel
        $data = '';
        // Carrega o certificado em um array usando o LF como referencia
        $arCert = explode("\n", $pubKey);
        foreach ($arCert as $curData) {
            // Remove a tag de inicio e fim do certificado
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                // Carrega o resultado numa string
                $data .= trim($curData);
            }
        }
        return $data;
    }



    /**
     * listDir
     * Método para obter todo o conteúdo de um diretorio, e
     * que atendam ao critério indicado.
     * @param string $dir Diretorio a ser pesquisado
     * @param string $fileMatch Critério de seleção pode ser usados coringas como *-cte.xml
     * @param boolean $retpath se true retorna o path completo dos arquivos se false so retorna o nome dos arquivos
     * @return mixed Matriz com os nome dos arquivos que atendem ao critério estabelecido ou false
     */
    public function listDir($dir, $fileMatch, $retpath = false) {
        if (trim($fileMatch) != '' && trim($dir) != '') {
            // Passar o padrão para minúsculas
            $fileMatch = strtolower($fileMatch);
            // Cria um array limpo
            $aName = array();
            // Guarda o diretorio atual
            $oldDir = getcwd() . DIRECTORY_SEPARATOR;
            // Verifica se o parametro $dir define um diretorio real
            if (is_dir($dir)) {
                // Muda para o novo diretorio
                chdir($dir);
                // Pegue o diretorio
                $diretorio = getcwd() . DIRECTORY_SEPARATOR;
                if (strtolower($dir) != strtolower($diretorio)) {
                    $this->errMsg = 'Falha não há permissão de leitura no diretorio escolhido';
                    return false;
                }
                // Abra o diretório
                $ponteiro = opendir($diretorio);
                $x = 0;
                // Monta os vetores com os itens encontrados na pasta
                while (false !== ($file = readdir($ponteiro))) {
                    // Procure se não for diretorio
                    if ($file != "." && $file != ".." ) {
                        if (!is_dir($file)){
                            $tfile = strtolower($file);
                            // É um arquivo então
                            // verifica se combina com o $fileMatch
                            if (fnmatch($fileMatch, $tfile)) {
                                if ($retpath){
                                    $aName[$x] = $dir . $file;
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
        return $aName;
    } //fim da função

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
            case 'CTeRecepcao':
                $usef = "CteRecepcao";
                break;
            case 'CTeRetRecepcao':
                $usef = "CteRetRecepcao";
                break;
            case 'CTeCancelamento':
                $usef = "CteCancelamento";
                break;
            case 'CTeInutilizacao':
                $usef = "CteInutilizacao";
                break;
            case 'cteConsultaCT':
                $usef = "CteConsulta";
                break;
            case 'cteStatusServicoCT':
                $usef = "CteStatusServico";
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
            'exceptions'    => false,
            'cache_wsdl'    => WSDL_CACHE_NONE
        );
        //instancia a classe soap

        $oSoapClient = new CTeSOAP2Client($URL,$options);
        //monta o cabeçalho da mensagem
        $varCabec = new SoapVar($cabecalho,XSD_ANYXML);
        $header = new SoapHeader($namespace,'cteCabecMsg',$varCabec);
        //instancia o cabeçalho
        $oSoapClient->__setSoapHeaders($header);
        //monta o corpo da mensagem soap
        $varBody = new SoapVar($dados,XSD_ANYXML);
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
     * @param string $urlsefaz
     * @param string $namespace
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param numeric $ambiente
     * @param string $UF sem uso mantido apenas para compatibilidade com __sendSOAP
     * @return mixed false se houve falha ou o retorno em xml do SEFAZ
     */
    protected function __sendSOAP2($urlsefaz,$namespace,$cabecalho,$dados,$metodo,$ambiente,$UF=''){
        if ($urlsefaz == ''){
            //não houve retorno
            $this->errMsg = 'URL do webservice não disponível.';
            $this->errStatus = true;
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
        //Tabela de codigos HTTP
        $cCode['0']="Indefinido";
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
        //
        $tamanho = strlen($data);
        if($this->enableSCAN){
            //monta a terminação do URL
            switch ($metodo){
                case 'CTeRecepcao':
                    $servico = "CteRecepcao";
                    break;
                case 'CTeRetRecepcao':
                    $servico = "CteRetRecepcao";
                    break;
                case 'CTeCancelamento':
                    $servico = "CteCancelamento";
                    break;
                case 'CTeInutilizacao':
                    $servico = "CteInutilizacao";
                    break;
                case 'cteConsultaCT':
                    $servico = "CteConsulta";
                    break;
                case 'cteStatusServicoCT':
                    $servico = "CteStatusServico";
                    break;
            }
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "cte_ws1.xml",$ambiente,'SCAN');
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
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1); //apresenta informações de conexão na tela
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
//        $txtInfo .= "Certinfo=$info[certinfo]\n";
        $n = strlen($__xml);
        $x = stripos($__xml, "<");
        $xml = substr($__xml, $x, $n-$x);
        $this->soapDebug = $data."\n\n".$txtInfo."\n".$__xml;
        if ($__xml === false){
            //não houve retorno
            $this->errMsg = curl_error($oCurl) . $info['http_code'] . $cCode[$info['http_code']];
            $this->errStatus = true;
        } else {
            //houve retorno mas ainda pode ser uma mensagem de erro do webservice
            $this->errMsg = $info['http_code'] . ' ' . $cCode[$info['http_code']];
            $this->errStatus = false;
        }
        curl_close($oCurl);
        return $xml;
    } //fim __sendSOAP2

   /**
    * __convertTime
    * Converte o campo data time retornado pelo webservice
    * em um timestamp unix
    *
    * @name __convertTime
    * @param    string   $DH
    * @return   timestamp
    * @access   private
    **/
    protected function __convertTime($DH){
        if ($DH) {
            $aDH = explode('T', $DH);
            $adDH = explode('-', $aDH[0]);
            $atDH = explode(':', $aDH[1]);
            $timestampDH = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
            return $timestampDH;
        }
    } //fim __convertTime

    /**
     * __getNumLot
     * Obtêm o numero do último lote de envio
     *
     * @name __getNumLot
     * @param none
     * @return numeric Numero do Lote
     */
    protected function __getNumLot(){
         $lotfile = $this->raizDir . 'config\numloteCTE.xml';
         $domLot = new DomDocument;
         $domLot->load($lotfile);
         $num = $domLot->getElementsByTagName('num')->item(0)->nodeValue;
         if( is_numeric($num) ){
            return $num;
         } else {
             //arquivo não existe suponho que o numero então seja 1
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
            $lotfile = $this->raizDir . 'config\numloteCTE.xml';
            $numLot = '<?xml version="1.0" encoding="UTF-8"?><root><num>' . $num . '</num></root>';
            if (!file_put_contents($lotfile,$numLot) ) {
                return true;
            } else {
                return false;
            }
        }
    } //fim __putNumLot
    /**
     * __setError
     * Adiciona descrição do erro ao contenedor dos erros
     *
     * @name __setError
     * @param   string $msg Descrição do erro
     * @return  none
     */
    private function __setError($msg){
        $this->errMsg .= "$msg\n";
        $this->errStatus = true;
    }
} //fim classe CTeNFePHP
/**
 * Classe complementar
 * necessária para a comunicação SOAP 1.2
 * Remove algumas tags para adequar a comunicação
 * ao padrão Ruindows utilizado
 *
 * @version 1.2
 * @package CTePHP
 * @author  Roberto L. Machado <linux.rlm at gmail dot com>
 *
 */
if(class_exists("SoapClient")){
    class CTeSOAP2Client extends SoapClient {
        function __doRequest($request, $location, $action, $version) {
        $request = str_replace(':ns1', '', $request);
        $request = str_replace('ns1:', '', $request);
        $request = str_replace("\n", '', $request);
        $request = str_replace("\r", '', $request);
        return parent::__doRequest($request, $location, $action, $version);
        }
    } // Fim CTeSOAP2Client
}

/**
 * Classe complementar
 * necessária para extender a classe base Exception
 * Usada no tratamento de erros da API
 * @version 1.0.0
 * @package NFePHP
 *
 */
if(!class_exists('nfephpException')){
    class nfephpException extends Exception {
        public function errorMessage() {
        $errorMsg = $this->getMessage()."\n";
        return $errorMsg;
        }
    }
}

?>