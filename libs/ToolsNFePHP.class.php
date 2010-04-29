<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 * Esta classe substitui a antiga NFeTools.class
 *
 * @package   NFePHP
 * @name      ToolsNFePHP
 * @version   1.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto.machado@superig.com.br>
 */

//carrega a classe nusoap para comunicação como o webservice
require_once('NuSoap/nusoap.php');
//carrega a classe de conversões de txt para xml e vice-versa
require_once('ConvertNFePHP.class.php');

//define o caminho base da instalação do sistema
//define( 'PATH_ROOT', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

class ToolsNFePHP {

    // propriedades da classe

    public $tpAmb='';
    public $arqDir='';
    public $pdfDir ='';
    public $entDir='';
    public $valDir='';
    public $repDir='';
    public $assDir='';
    public $envDir='';
    public $aprDir='';
    public $denDir='';
    public $rejDir='';
    public $canDir='';
    public $inuDir='';
    public $temDir='';
    public $recDir='';
    public $conDir='';
    public $libsDir='';
    public $certsDir='';

    /**
     * xsdDir
     * diretorio que contem os esquemas de validação
     * estes esquemas devem ser mantidos atualizados
     *
     * @var string
     */
    public $xsdDir='';
    /**
     *
     * @var <type>
     */
    private $keyPass='';
    /**
     *
     * @var <type>
     */
    private $passPhrase='';
    /**
     *
     * @var <type>
     */
    private $certName='';
    /**
     *
     * @var <type>
     */
    private $empName='';
    /**
     *
     * @var <type>
     */
    private $cUF='';
    /**
     *
     * @var <type>
     */
    private $UF='';
    /**
     *
     * @var <type>
     */
    private $anoMes='';
    /**
     *
     * @var <type>
     */
    public $aURL='';
    /**
     *
     * @var <type>
     */
    public $aCabec='';
    /**
     *
     * @var <type>
     */
    public $errMsg='';
    /**
     *
     * @var <type>
     */
    public $errStatus=FALSE;
    /**
     *
     * @var <type>
     */
    public $soapDebug='';

    /**
     * $URLxsi
     * @var string
     */
    private $URLxsi = 'http://www.w3.org/2001/XMLSchema-instance';
    /**
     * $URLxsd
     * @var string
     */
    private $URLxsd = 'http://www.w3.org/2001/XMLSchema';
    /**
     * $URLnfe
     * @var string
     */
    private $URLnfe = 'http://www.portalfiscal.inf.br/nfe';
    /**
     * $URLdsig
     * @var string
     */
    private $URLdsig = 'http://www.w3.org/2000/09/xmldsig#';
    /**
     * $URLCanonMeth
     * @var string
     */
    private $URLCanonMeth = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * $URLSigMeth
     * @var string
     */
    private $URLSigMeth = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    /**
     * $URLTransfMeth_1
     * @var string
     */
    private $URLTransfMeth_1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    /**
     * $URLTransfMeth_2
     * @var string
     */
    private $URLTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * $URLDigestMeth
     * @var string
     */
    private $URLDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';

    /**
     * __construct
     * Método construtor da classe
     * Este método utiliza o arquivo de configuração localizado no diretorio config
     * para montar os diretórios e várias propriedades internaS da classe, permitindo
     * automatizar melhor o processo de comunicação com o SEFAZ.
     *
     * #################################
     * PASTA ENTRADAS
     * #################################
     *      $entradasDir="../../NFE_$ambiente/entradas/"
     * As NFe's geradas pelo ERP devem ser colocadas neste diretorio para
     * posterior assinatura.
     * Ao detectar um documentos nesta pasta o sistema ira verificar o numero
     * da nota e procurar por equivalentes nas pastas de reprovadas e rejeitadas
     * caso encontre alguma com o mesmo numero irá remover-las dessas pastas.
     * Isto funciona para que possamos reemitir as NFe que posuiam erros, e
     * que foram corrigidos, mantendo o sistema limpo.
     * Em seguida irá processar esses arquivos com a assinatura.
     *
     * #################################
     * PASTA ASSINADAS
     * #################################
     *      $assinadasDir="../../NFE_$ambiente/assinadas/";
     * As NFe's já assinadas colocadas nesta pasta e estarão disponíveis para a validação.
     *
     * #################################
     * PASTA VALIDADAS
     * #################################
     *      $validadasDir="../../NFE_$ambiente/validadas/";
     * As NFe's já assinadas são verificadas contra o esquema xsd e as validadas são
     * colocadas nesta pasta para posterior envio ao SEFAZ.
     *
     * #################################
     * PASTA REJEITADAS
     * #################################
     *      $rejeidadasDir="../../NFE_$ambiente/rejeitadas/";
     * As NFe's já assinadas e que não passaram pela validação (rejeitadas) são colocadas neste
     * diretorio para que sejam corrigidas. As NFe's são rejeitadas quando, após ter sido
     * testada e houverem erros em sua estrutura. O sistema deverá informar o operador da
     * ocorrência dessa rejeição e seu motivo e nada mais será feito com estas NFe's até que
     * uma nova NFe com o mesmo número desta que foi rejeitada seja colocada na pasta de entrada
     * e neste caso a NFe rejeitada será removida.
     *
     * #################################
     * PASTA ENVIADAS
     * #################################
     *      $enviadasDir="../../NFE_$ambiente/enviadas/";
     * Após a NFe haver sido enviada ao SEFAZ com sucesso ela é removida da pasta VALIDADAS
     * e colocada nesta pasta e o arquivo do recibo do SEFAZ é colocado na pasta TEMPORARIAS.
     *
     * #################################
     * PASTA TEMPORARIAS
     * #################################
     *      $temporarioDir="../../NFE_$ambiente/temporarias/";
     * Nesta pasta são colocados todos os arquivos temporários e de debug
     * estes arquivos serão removidos periódicamente
     *
     * #################################
     * PASTA APROVADAS
     * #################################
     *      $aprovadasDir="../../NFE_$ambiente/enviadas/aprovadas/";
     * As NFe's consultadas quanto ao seu status junto ao SEFAZ e aprovadas (status 100)
     * são complementadas com a tag do protocolo e colocadas nesta pasta em subdiretorios
     * que indicam o ano e mês da emissão da NFe ex. 200910. Isto é feito apenas para
     * melhorar a visibilidade e o acesso as NFe por data de emissão e facilitar o backup.
     *
     * #################################
     * PASTA DENEGADAS
     * #################################
     *      $denegadasDir="../../NFE_$ambiente/enviadas/denegadas/";
     * As NFe's consultadas quanto ao seu status junto e denegadas (status 110)
     * são complementadas com a tag do protocolo e colocadas nesta pasta em subdiretorios
     * que indicam o ano e mês da emissão da NFe ex. 200910.
     * Isto é feito apenas para melhorar a visibilidade e o acesso as NFe por data de emissão
     * e facilitar o backup.
     *
     * #################################
     * PASTA REPROVADAS
     * #################################
     *      $reprovadasDir="../../NFE_$ambiente/enviadas/rereprovadas/";
     * As NFe's consultadas quanto ao seu status e rejeitadas (status > 110)
     * são movidas para este subdiretorio, para posterior analise e correção.
     * Neste diretorio também é colocado no retorno do SEFAZ que indica o motivo da rejeição,
     * para facilitar a apresentação dessa informação ao usurário, nada mais será feito
     * com essas NFe !!! até que uma nova NFe com o mesmo numero seja criada e colocada
     * na pasta de ENTRADAS e neste caso essa NFe e a resposta da SEFAZ serão removidas.
     *
     * #################################
     * PASTA CANCELADAS
     * #################################
     *      $canceladasDir="../../NFE_$ambiente/canceladas/";
     * As respostas positivas as solicitações de cancelamento de NFe's
     * são colocadas neste diretorio em subpastas identificadas com ANOMES ex. 200910
     * Isto é feito apenas para melhorar a visibilidade e o acesso dos cancelamentos
     * das NFe por data de emissão e facilitar o backup.
     *
     * #################################
     * PASTA INUTILIZADAS
     * #################################
     *      $inutilizadasDir="../../NFE_$ambiente/inutilizadas/";
     * As respostas positivas as solicitações de inutilização de faixa de numeros de NFe's
     * são colocadas neste diretorio em subpastas identificadas com ANOMES ex. 200910
     * Isto é feito apenas para melhorar a visibilidade e o acesso as inutilizações de numeros
     * das NFe por data de emissão e facilitar o backup.
     *
     * #################################
     * PASTA RECEBIDAS
     * #################################
     *      $recebidasDir="../../NFE_$ambiente/recebidas/";
     * Nesta pasta devem ser colocadas as NFe's recebidas de terceiros.
     *
     * #################################
     * PASTA CONSULTADAS
     * #################################
     *      $consultadas="../../NFE_$ambiente/consultadas/";
     * As NFe's recebidas de terceiros e já consultadas para verificar sua validade
     * na SEFAZ serão colocadas em subpastas identificadas pelo ANOMES nesta pasta.
     * Isto é feito apenas para melhorar a visibilidade e o acesso das consultas das entradas
     * das NFe por data de emissão e facilitar o backup.
     *
     * #################################
     * PASTA PDF
     * #################################
     *      $pdf="../../NFE_$ambiente/pdf/";
     * Nesta pasta serão colocados dos arquivos pdf que serão mantidos no sistema.
     * Normalmente não é necessário o uso desta pasta!!!
     *
     * #################################
     * PASTA CERTIFICADOS
     * #################################
     *      $certDir='../certs/';
     * Nesta pasta devem ser colocados os certificados padrão A1, é recomentdado que o
     * certificado seja nomeado apelas com letras minusculas e incluir em seu nome a data de
     * validade do mesmo (ex. cert_empresa_20100414.pfx) isto ajuda na manipulação dos certificados.
     *
     * #################################
     * PASTA CONFIGURAÇÃO
     * #################################
     *      $configDir = '../config/';
     * Nesta pasta devera estar o arquivo de configuração do sistema
     * "config.php" e o arquivo xml que contêm os padrões de acesso aos URL da SEFAZ de todos os
     * estados "urlWebServicesNFe.xml".
     * Além disso este diretorio també será utilizado para armazenar o arquivo de
     * controle do numero de lotes de envio de NFe ao SEFAZ "lotes.php".
     *
     * #################################
     * PASTA BIBLIOTECAS
     * #################################
     *      $libDir='../libs/';
     * Esta pasta contêm as bibliotecas e classes do sistema
     *
     * #################################
     * PASTA ESQUEMAS
     * #################################
     *      $schemas='../schemas/'";
     * Esta pasta contêm os esquemas de construção e validação dos arquivos de
     * comunicação xml com o SEFAZ.
     * Estes arquivos devem ser mantidos atualizados!!! Podem ocorrer revisões nestes
     * padrões que deverão ser refletidos nessta pasta e no próprio sistema.
     *
     * #################################
     * PASTA IMAGENS
     * #################################
     *      $images='../images/';
     * Esta pasta contêm as imagens (jpg, gif, png, etc.) utilizadas pelo sistema
     * a principal imagem é o logo da empresa que deve estar no formato JPG.
     *
     * @param  none
     * @return none
     */
    function __construct() {
        //testa a existencia do arquivo de configuração
        $P_ROOT = dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR;
        if ( is_file( $P_ROOT . "config/config.php") ){
            //carrega o arquivo de configuração
            include( $P_ROOT . "config/config.php");
            // carrega propriedades da classe com os dados de configuração
            // a sring $sAmb será utilizada para a construção dos diretorios
            // dos arquivos de operação do sistema
            $this->tpAmb=$ambiente;
            if ( $ambiente % 2 == 0 ) {
                $sAmb='homologacao';
            } else {
                $sAmb='producao';
            }
            //
            $this->empName=$empresa;
            $this->cUF=$cUF;
            $this->UF=$UF;
            $this->certName=$certName;
            $this->keyPass=$keyPass;
            $this->passPhrase=$passPhrase;
            $this->arqDir = $arquivosDir;
            //carrega propriedade com ano e mes ex. 200911
            $this->anoMes = date('Ym');
            //
            $this->xsdDir = $P_ROOT . 'schemes/';
            //
            $this->certsDir = $P_ROOT . 'certs/';
            //
            $this->aCabec = array('versao'=>'1.02','xsd'=>'cabecMsg_v1.02.xsd');
            // monta a estrutura de diretorios utilizados na manipulação das NFe
            $this->entDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'entradas' . DIRECTORY_SEPARATOR;
            $this->valDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'validadas' . DIRECTORY_SEPARATOR;
            $this->rejDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'rejeitadas' . DIRECTORY_SEPARATOR;
            $this->assDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'assinadas' . DIRECTORY_SEPARATOR;
            $this->envDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR;
            $this->aprDir=$this->envDir . 'aprovadas' . DIRECTORY_SEPARATOR;
            $this->denDir=$this->envDir . 'denegadas' . DIRECTORY_SEPARATOR;
            $this->repDir=$this->envDir . 'reprovadas' . DIRECTORY_SEPARATOR;
            $this->canDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'canceladas' . DIRECTORY_SEPARATOR;
            $this->inuDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'inutilizadas' . DIRECTORY_SEPARATOR;
            $this->temDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'temporarias' . DIRECTORY_SEPARATOR;
            $this->recDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'recebidas' . DIRECTORY_SEPARATOR;
            $this->conDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'consultadas' . DIRECTORY_SEPARATOR;
            $this->pdfDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
            //monta a arvore de diretórios necessária
            if ( !is_dir($this->arqDir . "_$sAmb") ){
                mkdir($this->arqDir . "_$sAmb", 0777);
            }
            if ( !is_dir($this->entDir) ){
                mkdir($this->entDir, 0777);
            }
            if ( !is_dir($this->valDir) ){
                mkdir($this->valDir, 0777);
            }
            if ( !is_dir($this->repDir) ){
                mkdir($this->repDir, 0777);
            }
            if ( !is_dir($this->assDir) ){
                mkdir($this->assDir, 0777);
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
            if ( !is_dir($this->rejDir) ){
                mkdir($this->rejDir, 0777);
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
            if ( !is_dir($this->recDir) ){
                mkdir($this->conDir, 0777);
            }
            if ( !is_dir($this->pdfDir) ){
                mkdir($this->pdfDir, 0777);
            }
            //carregar a matriz com os dados para acesso aos WebServices SEFAZ
            $this->aURL = $this->loadSEFAZ( $P_ROOT . 'config/urlWebServicesNFe.xml',$sAmb,$this->UF);
            //se houver erro no carregamento dos certificados
            if ( !$retorno = $this->loadCerts() ) {
                $this->errStatus = true;
            }
        } else {
            // não existe arquivo de configuração
            $this->errMsg = "Não foi localizado o arquivo de configuração.";
            $this->errStatus = true;
        }

    }

    /**
     * autoTXTtoXML
     * Método para converter todas as nf em formato txt para o formato xml
     * localizadas na pasta "entradas". Os arquivos txt apoś terem sido
     * convertidos com sucesso são removidos da pasta.
     * Os arquivos txt devem ser nomeados como "<qualquer coisa>-nfe.txt"
     *
     * @param none
     * @return boolean TRUE sucesso FALSE Erro
     */
    public function autoTXTtoXML(){
        //varre pasta "entradas" a procura de NFes em txt
        $aName = $this->listDir($this->entDir,'-nfe.txt');
        // se foi retornado algum arquivo
        if ( count($aName ) > 0){
            for ( $x=0; $x < count($aName); $x++ ) {
                //carrega nfe em txt para converter em xml
                $filename = $this->entDir.$aName[$x];
                //instancia a classe de conversão
                $oXML = new ConvertNFePHP;
                //convere o arquivo
                $xml = $oXML->nfetxt2xml($filename);
                //salvar o xml
                $xmlname = $this->entDir.$oXML->chave.'-nfe.xml';
                if ( !file_put_contents($xmlname, $xml) ){
                    $this->errStatus = TRUE;
                    $this->errMsg .= 'FALHA na gravação da NFe em xml.';
                    return FALSE;
                } else {
                    //remover o arquivo txt
                    unlink($filename);
                }
            }
        }
        return TRUE;
    } //fim autoTXTtoXML


    /**
     * autoAssinaNFe
     * Método para assinatura em lote das NFe em XML
     * Este método verifica todas as NFe que existem na pasta de ENTRADAS e as assina
     * após a assinatura ser feita com sucesso o arquivo XML assinado é movido para a pasta
     * ASSINADAS.
     * IMPORTANTE : Em ambiente Linux manter os nomes dos arquivos e terminações em LowerCase.
     *
     * @param  none
     * @return none
     */
    public function autoAssinaNFe() {
        //varre pasta "entradas" a procura de NFes
        $aName = $this->listDir($this->entDir,'-nfe.xml');
        // se foi retornado algum arquivo
        if ( count($aName ) > 0){
            for ( $x=0; $x < count($aName); $x++ ) {
                //carrega nfe para assinar em uma strig
                $filename = $this->entDir.$aName[$x];
                if ( $nfefile = file_get_contents($filename) ) {
                    //assinador usando somente o PHP da classe classNFe
                    if ( $signn = $this->signXML($nfefile, 'infNFe') ) {
                        //xml retornado gravar
                        $file = $this->assDir . $aName[$x];
                        if ( !file_put_contents($file, $signn) ) {
                            $this->errStatus = TRUE;
                            return FALSE;
                        } else {
                            unlink($filename);
                        }
                    }

                }
            }
        }
        return TRUE;
    }

    /**
     * autoValidNFe
     * Método validador em lote das NFe em XML já assinadas.
     * As NFe são validadas somente após que a TAG assinatura seja postada no XML, caso contrario
     * gerará um erro.
     *
     * As NFes, em principio podem ser assinadas sem grande perda de performance do sistema,
     * desde que o numero de NFe geradas seja relativamente pequeno.
     * Caso o numero seja muito grande (acima de 50 NFe de cada por minuto) talvéz seja
     * interessante fazer alterações no sistema para incluir a TAG de assinatura em branco
     * e validar antes de assinar.
     *
     * Este método verifica todas as NFe que existem na pasta de ASSINADAS e processa a validação
     * com o shema XSD. Caso a NFe seja valida será movida para a pasta VALIDADAS, caso contrario
     * será movida para a pasta REPROVADAS.
     *
     * @param  none
     * @return none
     */
    public function autoValidNFe() {
        //varre pasta "assinadas"
        $aName = $this->listDir($this->assDir,'-nfe.xml');
        // se foi retornado algum arquivo
        if ( count($aName) > 0 ){
            for ( $x=0; $x < count($aName); $x++ ) {
                //carrega nfe para validar em uma strig
                $filename = $this->assDir.$aName[$x];
                if ( $nfefile = file_get_contents($filename) ) {
                    //validar
                    if ( $this->validXML($nfefile,$this->xsdDir . 'nfe_v1.10.xsd') ) {
                        // validado => transferir para pasta validados
                        $file = $this->valDir . $aName[$x];
                        if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = TRUE;
                        } else {
                            unlink($filename);
                        }
                    } else {
                        //NFe com erros transferir de pasta rejeitadas
                        $file = $this->rejDir . $aName[$x];
                        if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = TRUE;
                        } else {
                            unlink($filename);
                        }
                    }

                }
            }
        }
    }

    /**
     * autoSend
     * Este método procura por NFe's na pasta VALIDADAS, se houver alguma, envia para a SEFAZ
     * e em saso de sucesso no envio move o arquivo para a pasta das enviadas
     * ATENÇÃO : Existe um limite para o tamanho do arquivo a ser enviado ao SEFAZ
     * FUNÇÂO Não TESTADA
     *
     * @param none
     * @return boolean TRUE sucesso ou FALSE erro
     */
    public function autoSendNFe(){
        //varre a pasta de validadas
        $aName = $this->listDir($this->valDir,'-nfe.xml');
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        $n = count($aName);
        if ( $n > 0 ) {
            //determina o numero de grupos de envio com 10 notas por grupo
            $k = intval($n/10);
            $y = $n % 10;
            if ($y > 0){
                $k++;
            }
            // as notas localizadas na pasta validadas serão enviadas em
            // grupos de 10 notas de cada vez
            for ($i = 0 ; $i < $k ; $i++) {
                //limpa a matriz com as notas fiscais
                $aNFE= null;
                for ( $x = $i*10 ; $x < (($i+1)*10) ;$x++ ){
                    if ($x < $n ){
                        $filename = $this->valDir.$aName[$x];
                        $nfefile = file_get_contents($filename);
                        $aNFE[] = $nfefile;
                    }
                }
                //obter o numero do ultimo lote enviado
                $num = $this->__getNumLot();
                //incrementa o numero
                $num++;
                //enviar as notas
                if ($ret = $this->sendLot($aNFE,$num)){
                    //incrementa o numero do lote no controle
                    if ($this->__putNumLot($num)){
                        $this->errStatus = TRUE;
                        $this->errMsg .= ' Falha na Gravação do numero do lote de envio!! ';
                        return FALSE;
                    }
                    //mover as notas do lote para o diretorio de enviadas
                    //para cada em $aNames[] mover para $this->envDir
                    for ( $x = $i*10 ; $x < (($i+1)*10) ;$x++ ){
                        if ($x < $n ){
                           if( !rename($this->valDir.$aName[$x],$this->envDir.$aName[$x]) ){
                                $this->errStatus = TRUE;
                                $this->errMsg .= ' Falha na movimentação da NFe das "validadas" para "enviadas"!! ';
                           }
                        }
                    } //fim for rename

                 } else {
                        $this->errStatus = TRUE;
                        $this->errMsg .= ' Erro no envio do lote de NFe!! ';
                        return FALSE;
                 }
            }
        }
        return TRUE;
    }



    /**
     * autoAuthNFe
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
     * @param  none
     * @return none
     */
    public function autoAuthNFe(){
        //varre a pasta de enviadas
        $aName = $this->listDir($this->envDir,'-nfe.xml');
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        if ( count($aName) > 0 ) {
            //para cada arquivo nesta pasta solicitar o protocolo
            foreach ( $aName as $file ) {
                $idNFe = substr($file,0,44);
                $aRet = $this->getNFeProtocol($idNFe);
                if ( $aRet['cStat'] == 100) {
                    //NFe aprovada
                    $pasta = $this->aprDir;
                }//endif
                if ( $aRet['cStat'] == 110) {
                    //NFe denegada
                    $pasta = $this->denDir;
                }//endif
                if ( $aRet['cStat'] > 200 ) {
                    //NFe reprovada
                    $pasta = $this->repDir;
                }//endif
                if ( $aRet['status'] ) {
                    //montar a NFe com o protocolo
                    $protFile = $this->temDir.$idNFe.'-prot.xml';
                    $nfeFile = $this->envDir.$file;
                    if ( is_file($protFile) && is_file($nfeFile) ) {
                        $procnfe = $this->addProt($nfeFile,$protFile);
                        //arquivo da NFe com o protocolo
                        $prot = new DOMDocument(); //cria objeto DOM
                        $prot->formatOutput = false;
                        $prot->preserveWhiteSpace = false;
                        $prot->loadXML($procnfe);
                        //salvar a NFe com o protocolo na pasta
                        if ( $prot->save($pasta.$idNFe.'-nfe.xml') ) {
                            //se o arquivo foi gravado na pasta destino com sucesso
                            //remover os arquivos das outras pastas
                            unlink($nfeFile);
                            unlink($protFile);
                        } //endif
                    } //endif
                } //endif
            }//endforeach
        } //endif
    }//fim da função

    /**
     * autoPrintMail
     * @param <type> $para
     * @param <type> $contato
     * @param <type> $printer
     * @return <type>
     */
    public function autoPrintMail($para='',$contato='',$printer=''){
        //varre a pasta de enviadas/aprovadas
        $aNApr = $this->listDir($this->aprDir,'-nfe.xml');
        //se houver algum arquivo *-nfe.xml continua, caso contrario sai
        $i = 0;
        if ( count($aNApr) > 0 ) {
            //para cada arquivo nesta pasta imprimir a DANFE em pdf
            // e enviar para a printer
            foreach ( $aNApr as $file ) {
                $anomes = '20'.substr($file,2,4);
                $docxml = file_get_contents($this->aprDir.$file);
                $danfe = new DanfeNFePHP($docxml, 'P', 'A4','/var/www/trunkNFe/images/logo.jpg','I','');
                $id = (string) $danfe->montaDANFE();
                $pdfName = $id.'.pdf';
                $pdfFile = (string) $danfe->printDANFE($this->pdfDir.$pdfName,'S');
                $handle = fopen($this->pdfDir.$pdfName, "w+");
                $ret = fwrite($handle,$pdfFile);
                $ret = fclose($handle);
                $command = "pdf2ps $this->pdfDir$pdfName $this->pdfDir$id.ps";
                system($command,$ret);
                if ( $printer != '' ) {
                    $command = "lpr -P $printer $this->pdfDir$id.ps";
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
                $obsCont = $dom->getElementsByTagName("obsCont")->item(0);

                $razao = utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue);
                $numero = str_pad($ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
                $serie = str_pad($ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
                $emitente = utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue);
                $vtotal = number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");

                if ( isset($obsCont) ){
                    //verificar que tem o mailto
                    $campo = $obsCont->item(0)->getAttribute("xCampo");
                    if ( $campo == 'mailto' ) {
                        $para = $obsCont->item(0)->getElementsByTagName('xTexto')->item(0)->nodeValue;
                    }//endif
                    $campo = $obsCont->item(1)->getAttribute("xCampo");
                    if ( !empty($campo) ) {
                        if ( $campo == 'contact' ){
                            $contato = $obsCont->item(1)->getElementsByTagName('xTexto')->item(0)->nodeValue;
                        }//endif
                    }//endif
                } //endif

                if ($para != '' ) {
                    //montar a matriz de dados para envio do email
                    $aMail = array('emitente'=>$emitente,'para'=>$para,'contato'=>$contato,'razao'=>$razao,'numero'=>$numero,'serie'=>$serie,'vtotal'=>$vtotal);
                    //inicalizar a classe de envio
                    $nfeMail = new MailNFePHP();
                    //enviar o email e testar
                    //o erp pode colocar esta informação no campo obsCont child de infAdic
                    //<infAdic>
                    //  <obsCont xCampo='mailto'>
                    //      <xTexto>roberto@plastfoam.com.br<xTexto>
                    //  <obsCont xCampo='contact'>
                    //      <xTexto>Roberto L. Machado<xTexto>
                    if ( $nfeMail->sendNFe($docxml,$pdfFile,$file,$pdfName,$aMail) ){
                        //echo '<p>E-mail enviado com sucesso!!</p>';
                        //gravar no banco de dados
                        //mover o arquivo xml para a pasta de arquivamento identificada com o ANOMES
                        //Ex. /var/www/xmlNFE_homologacao/enviadas/aprovadas/200911/
                        $diretorio = $this->aprDir.$anomes.DIRECTORY_SEPARATOR;
                        if ( !is_dir($diretorio) ) {
                            mkdir($diretorio, 0777);
                        }
                        rename($this->aprDir.$file,$diretorio.$file);
                    } else {
                           //echo "<p>$nfeMail->mailERROR</p>";
                    }
                }
                unlink($this->pdfDir.$pdfName);
                unlink($this->pdfDir.$id.'.ps');

                if ( $i == 0 ) {
                    return TRUE;
                } //endif
            } //end foreach
        } //endif
    } //fim da função autoPrintDANFE

    /**
     * statusServico
     * Verifica o status do servico da SEFAZ
     *
     * $this->cStat = 107 OK
     *        cStat = 108 sitema paralizado momentaneamente, aguardar retorno
     *        cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     * se SCAN estiver ativado usar, caso caontrario aguardar pacientemente.
     *
     *
     * @name statusServico
     * @version 1.1
     * @package NFePHP
     * @param	boolean $bSave Indica se o xml da resposta deveser salvo em arquivo
     * @return	mixed False ou array ['cStat'=>107,'tMed'=>1,'dhRecbto'=>'12/12/2009','xMotivo'=>'Serviço em operação',''xObs'=>'']
    **/
    public function statusServico(){

        //retorno da funçao
        $bRet = FALSE;

        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeStatusServico';
        $cabecXsdfile   = $this->xsdDir . $this->aCabec['xsd'];
        $cabecVer       = $this->aCabec['versao'];
        //$dataXsdfile    = $this->aURL[$wsdl][];
        $dataVer        = $this->aURL[$wsdl]['versao'];
        // array para comunicaçao soap
        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consStatServ xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'">'.'<tpAmb>'.$this->tpAmb.'</tpAmb><cUF>'.$this->cUF.'</cUF><xServ>STATUS</xServ></consStatServ>'
        );
        //envia o xml para o SOAP
        $retorno = $this->__sendSOAP($param, $this->aURL[$wsdl]['URL'],$this->aURL[$wsdl]['service'] );

        //verifica o retorno do SOAP
        if ( is_array($retorno) ) {
            //pega os dados do array retornado pelo NuSoap
            $xmlresp = utf8_encode($retorno[$this->aURL[$wsdl]['service'].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do serviço
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // tempo medio de resposta
            $aRetorno['tMed'] = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
            // data e hora da mensagem
            $aRetorno['dhRecbto'] = date("d/m/Y H:i",$this->__convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue));
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // obervaçoes opcional
            $aRetorno['xObs'] = !empty($doc->getElementsByTagName('xObs')->item(0)->nodeValue) ? $doc->getElementsByTagName('xObs')->item(0)->nodeValue : '';
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do NuSoap!!';
            return FALSE;
        }
        return $aRetorno;
    }


    /**
     * consultaCadastro
     * Solicita dados de situaçao de Cadastro, somente funciona para
     * cadastros de empresas localizadas no mesmo estado do solicitante e os dados
     * retornados podem não ser os mais atuais. Não é recomendado seu uso ainda.
     *
     * @name consultaCadastro
     * @version 1.1
     * @package NFePHP
     * @param	string  $UF
     * @param   string  $IE
     * @param   string  $CNPJ
     * @param   string  $CPF
     * @return	mixed FALSE se falha ou array se retornada informação
     **/
    public function consultaCadastro($UF,$CNPJ='',$IE='',$CPF=''){

        //variavel de retorno do metodo
        $bRet = FALSE;
        //variaveis do webservice
        $wsdl = 'ConsultaCadastro';
        //$cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aCabec['versao'];
        //$dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aURL[$wsdl]['versao'];

        $flagIE = FALSE;
        $flagCNPJ = FALSE;
        $flagCPF = FALSE;
        $marca = '';

        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($IE != ''){
            $flagIE = TRUE;
            $marca = 'IE-'.$IE;
            $filtro = "<IE>".$IE."</IE>";
            $CNPJ = '';
            $CPF='';
        }
        if ($CNPJ != '') {
            $flagCNPJ = TRUE;
            $marca = 'CNPJ-'.$CNPJ;
            $filtro = "<CNPJ>".$CNPJ."</CNPJ>";
            $CPF='';
        }
        if($CFP != '') {
            $flagCPF = TRUE;
            $filtro = "<CPF>".$CPF."</CPF>";
            $marca = 'CPF-'.$CPF;
        }
        //se nenhum critério é satisfeito
        if ( !($flagIE || $flagCNPJ || $flagCPF) ){
            //erro nao foi passado parametro de filtragem
            $this->errStatus = TRUE;
            $this->errMsg = 'Um filtro deve ser indicado CNPJ, CPF ou IE !!!';
            return FALSE;
        }
        //preparação da mensagem SOAP
        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="' . $cabecVer . '" xmlns="' . $this->URLnfe . '"><versaoDados>' . $dataVer . '</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<ConsCad versao="' . $dataVer . '" xmlns="'. $this->URLnfe . '"><infCons><xServ>CONS-CAD</xServ><UF>' . $UF . '</UF>' . $filtro . '</infCons></ConsCad>'
        );

        //envio da mensagem ao webservice
        $retorno = $this->__sendSOAP($param, $this->aURL[$wsdl]['URL'],$this->aURL[$wsdl]['service']);
        //se houve retorno
        if ( is_array($retorno) ) {
            //pegar o xml retornado do NuSoap
            $xmlresp = utf8_encode($retorno[$this->aURL[$wsdl]['service'].'Result']);
            if ( $xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }
            // tratar dados de retorno
            $doc = new DOMDocument();
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //infCons o xml somente contera um grupo com essa tag
            $aRetorno['verAplic'] = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aRetorno['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $aRetorno['UF'] = $doc->getElementsByTagName('UF')->item(0)->nodeValue;
            $aRetorno['IE'] = $doc->getElementsByTagName('IE')->item(0)->nodeValue;
            $aRetorno['CNPJ'] = $doc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
            $aRetorno['CPF'] = $doc->getElementsByTagName('CPF')->item(0)->nodeValue;
            $aRetorno['dhCons'] = $doc->getElementsByTagName('dhCons')->item(0)->nodeValue;
            $aRetorno['cUF'] = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
            // se foi encontrado cStat = 111 ou 112 com varios estabelecimento com o mesmo IE
            if ( $aRetorno['cStat'] == '112' ) {
                $bRet = TRUE;
                $n = 0;
                //pode haver mais de um dado retornado
                $infCad = $doc->getElementsByTagName('infCad');
                foreach ($infCad as $iCad) {
                    $aMulti[]['IE'] = $iCad->getElementsByTagName('IE')->item(0)->nodeValue;
                    $aMulti[]['CNPJ'] = $iCad->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    $aMulti[]['CPF'] = $iCad->getElementsByTagName('CPF')->item(0)->nodeValue;
                    $aMulti[]['UF'] = $iCad->getElementsByTagName('UF')->item(0)->nodeValue;
                    $aMulti[]['cSit'] = $iCad->getElementsByTagName('cSit')->item(0)->nodeValue;
                    $aMulti[]['xNome'] = $iCad->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $aMulti[]['xFant'] = $iCad->getElementsByTagName('xFant')->item(0)->nodeValue;
                    $aMulti[]['xRegApur'] = $iCad->getElementsByTagName('xRegApur')->item(0)->nodeValue;
                    $aMulti[]['CNAE'] = $iCad->getElementsByTagName('CNAE')->item(0)->nodeValue;
                    $aMulti[]['dIniAtiv'] = $iCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue;
                    $aMulti[]['dUltSit'] = $iCad->getElementsByTagName('dUltSit')->item(0)->nodeValue;
                    $aMulti[]['dBaixa'] = $iCad->getElementsByTagName('dBaixa')->item(0)->nodeValue;
                    $aMulti[]['IEUnica'] = $iCad->getElementsByTagName('IEUnica')->item(0)->nodeValue;
                    $aMulti[]['IEAtual'] = $iCad->getElementsByTagName('IEAtual')->item(0)->nodeValue;
                    $aMulti[]['xLgr'] = $iCad->getElementsByTagName('xLgr')->item(0)->nodeValue;
                    $aMulti[]['nro'] = $iCad->getElementsByTagName('nro')->item(0)->nodeValue;
                    $aMulti[]['xCpl'] = $iCad->getElementsByTagName('xCpl')->item(0)->nodeValue;
                    $aMulti[]['xBairro'] = $iCad->getElementsByTagName('xBairro')->item(0)->nodeValue;
                    $aMulti[]['cMun'] = $iCad->getElementsByTagName('cMun')->item(0)->nodeValue;
                    $aMulti[]['xMun'] = $iCad->getElementsByTagName('xMun')->item(0)->nodeValue;
                    $aMulti[]['CEP'] = $iCad->getElementsByTagName('CEP')->item(0)->nodeValue;
                }

                $aRetorno['mult'] = $aMulti;
            }
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do NuSoap!!';
            return FALSE;
        }
        return $aRetorno;
    }


    /**
     * addProt
     * Este método adiciona a tag do protocolo a NFe, preparando a mesma
     * para impressão e envio ao destinatário.
     *
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

            $xmlnfe = file_get_contents($nfefile);
            $docnfe->loadXML($xmlnfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $nfe = $docnfe->getElementsByTagName("NFe")->item(0);

            //carrega o protocolo e seus dados
            $xmlprot = file_get_contents($protfile);
            $prot->loadXML($xmlprot,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $tpAmb = $prot->getElementsByTagName("tpAmb")->item(0)->nodeValue;
            $verAplic = $prot->getElementsByTagName("verAplic")->item(0)->nodeValue;
            $chNFe=$prot->getElementsByTagName("chNFe")->item(0)->nodeValue;
            $dhRecbto=$prot->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
            $nProt=$prot->getElementsByTagName("nProt")->item(0)->nodeValue;
            $digVal=$prot->getElementsByTagName("digVal")->item(0)->nodeValue;
            $cStat=$prot->getElementsByTagName("cStat")->item(0)->nodeValue;
            $xMotivo=$prot->getElementsByTagName("xMotivo")->item(0)->nodeValue;

            //NFe processada com a tag do protocolo
            $procnfe = new DOMDocument('1.0', 'utf-8');
            $procnfe->formatOutput = false;
            $procnfe->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProc = $procnfe->appendChild($procnfe->createElement('nfeProc'));
            //estabele o atributo de versão
            $nfeProc_att1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
                $nfeProc_att1->appendChild($procnfe->createTextNode('1.10'));
            //estabelece o atributo xmlns
            $nfeProc_att2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
                $nfeProc_att2->appendChild($procnfe->createTextNode($this->URLnfe));

            //inclui NFe
            $node = $procnfe->importNode($nfe, true);
            $nfeProc = $nfeProc->appendChild($node);

            //cria tag protNFe
            $protNFe = $nfeProc->appendChild($procnfe->createElement('protNFe'));
            //estabele o atributo de versão
            $protNFe_att1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
            $protNFe_att1->appendChild($procnfe->createTextNode('1.10'));
            //cria tag infProt
            $infProt = $protNFe->appendChild($procnfe->createElement('infProt'));
            $infProt->appendChild($procnfe->createElement('tpAmb',$tpAmb));
            $infProt->appendChild($procnfe->createElement('verAplic',$verAplic));
            $infProt->appendChild($procnfe->createElement('chNFe',$chNFe));
            $infProt->appendChild($procnfe->createElement('dhRecbto',$dhRecbto));
            $infProt->appendChild($procnfe->createElement('nProt',$nProt));
            $infProt->appendChild($procnfe->createElement('digVal',$digVal));
            $infProt->appendChild($procnfe->createElement('cStat',$cStat));
            $infProt->appendChild($procnfe->createElement('xMotivo',$xMotivo));

            return $procnfe->saveXML();

    } //fim da função


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
    * @version 2.0
    * @package NFePHP
    * @param    string  $docxml  string contendo o arquivo xml a ser validado
    * @param    string  $xsdfile Path completo para o arquivo xsd
    * @return   array   ['staus','error']
    */
    public function validXML($docXml, $xsdFile){
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        // instancia novo objeto DOM
        $xmldoc = new DOMDocument();
        // carrega o xml
        $xml = $xmldoc->loadXML($docXml);
        $erromsg='';
        // valida o xml com o xsd
        if ( !$xmldoc->schemaValidate($xsdFile) ) {
            /**
             * Se não foi possível validar, você pode capturar
             * todos os erros em um array
             * Cada elemento do array $arrayErrors
             * será um objeto do tipo LibXmlError
             *
             */
            // carrega os erros em um array
            $aIntErrors = libxml_get_errors();
            //libxml_clear_errors();
            $flagOK = FALSE;
            foreach ($aIntErrors as $intError){
                switch ($intError->level) {
                    case LIBXML_ERR_WARNING:
                        $erroMsg .= " Atençao $intError->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $erroMsg .= " Erro $intError->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $erroMsg .= " Erro Fatal $intError->code: ";
                        break;
                }
                $erroMsg .= $intError->message . ';';
            }
        } else {
            $flagOK = TRUE;
            $errorMsg = '';
        }
        return array('status'=>$flagOK, 'error'=>$erroMsg);
    }



    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     * 
     * @name signXML
     * @version 1.1
     * @package NFePHP
     * @param	string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return	mixed FALSE se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid=''){
            if ( $tagid == '' ){
                $this->errMsg = 'Uma tag deve ser indicada para que seja assinada!!';
                $this->errStatus = TRUE;
                return FALSE;
            }
            if ( $docxml == '' ){
                $this->errMsg = 'Um xml deve ser passado para que seja assinado!!';
                $this->errStatus = TRUE;
                return FALSE;
            }
            // obter o chave privada para a ssinatura
            $fp = fopen($this->certsDir.'privatekey.pem', "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);
            // limpeza do xml com a retirada dos CR e LF
            $order = array("\r\n", "\n", "\r");
            $replace = '';
            $docxml = str_replace($order, $replace, $docxml);
            // carrega o documento no DOM
            $xmldoc = new DOMDocument();
            $xmldoc->preservWhiteSpace = FALSE; //elimina espaços em branco
            $xmldoc->formatOutput = FALSE;
            // muito importante deixar ativadas as opçoes para limpar os espacos em branco
            // e as tags vazias
            $xmldoc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $root = $xmldoc->documentElement;
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            $id = trim($node->getAttribute("Id"));
            $idnome = preg_replace('/[^0-9]/','', $id);
            //extrai os dados da tag para uma string
            $dados = $node->C14N(FALSE,FALSE,NULL,NULL);
            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,TRUE);
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
            $dados = $SignedInfo->C14N(FALSE,FALSE,NULL,NULL);
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
            $cert = $this->__cleanCerts($this->certsDir.'publickey.pem');
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);
            //grava na string o objeto DOM
            $docxml = $xmldoc->saveXML();
            // libera a memoria
            openssl_free_key($pkeyid);
            return $docxml;
    }

    /**
     * sendLot
     * Envia lote de Notas Fiscais para a SEFAZ.
     * Este método pode enviar uma ou mais NFe para o SEFAZ, desde que,
     * o tamanho do arquivo de envio não ultrapasse 500kBytes é recomendável
     * enviar no maximo 10 NFe por vez
     *
     * @name sendLot
     * @version 1.1
     * @package NFePHP
     * @param	array   $aNFe notas fiscais em xml uma em cada campo de uma string
     * @param   integer $idLote o id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma NFe usar banco
     *                          de dados
     * @return	array	True se aceito o lote ou False de rejeitado
    **/
    public function sendLot($aNFe,$idLote) {
        //variavel de retorno do metodo
        $aRet = array('status'=>FALSE,'cStat'=>'','xMotivo'=>'');
        // carga das variaveis da funçao do webservice
        //envio de Ne em lote
        $wsdl = 'NfeRecepcao';
        $cabecVer       = $this->aCabec['versao'];
        $cabecXsdfile   = $this->aCabec['xsd'];
        $servURL        = $this->aURL[$wsdl]['URL'];
        $servName       = $this->aURL[$wsdl]['service'];
        $servVer        = $this->aURL[$wsdl]['versao'];

        // limpa a variavel
        $sNFe = '';
        // monta string com as NFe enviadas
        $sNFe = implode('',$aNFe);
        //remover <?xml version="1.0" encoding=... das NFe pois somente
        // uma dessas tags pode exitir na mensagem
        $sNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>','',$sNFe);
        $sNFe = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$sNFe);

        //ATENÇAO $sNFe nao pode ultrapassar 500kBytes
        if (strlen($sNFe) > 470000) {
            //indicar erro e voltar
            return $aRet;
        }

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="http://www.portalfiscal.inf.br/nfe"><versaoDados>' . $servVer . '</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<enviNFe xmlns="' . $this->URLnfe . '" xmlns:ds="' . $this->URLdsig . '" xmlns:xsi="' . $this->URLxsi . '" versao="' . $servVer . '"><idLote>' . $idLote . '</idLote>' . $sNFe . '</enviNFe>'
        );
        //retorno e um array contendo a mensagem do SEFAZ
        $retorno = $this->__sendSOAP($param,$servURL,$servName);
        if (is_array($retorno)) {
            $xmlresp = utf8_encode($retorno[$servName.'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $aRet['xMotivo'] = 'Falha na comunicação SOAP';
                return $aRet;
            }
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do recebimento ou mensagem de erro
            $aRet['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aRet['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // em caso de sucesso  cStat = 103
            if ( $aRet['cStat'] == '103'){
                $aRet['status'] = TRUE;
            }
           //salvar o xml retornado do SEFAZ
           //na pasta de arquivos temporários
           $nome = $this->temDir.$idLote.'-rec.xml';
           $nome = $doc->save($nome);

         } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $aRet;
    }// fim sendLot

    /**
     * getProtocol
     * Solicita resposta do lote de Notas Fiscais
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name getProtocol
     * @version 1.0
     * @package NFePHP
     * @param	string   $recibo numero do recibo do envio do lote
     * @return	array
    **/
    public function getProtocol($recibo){
        //variavel de retorno do metodo
        $aRet = array('status'=>FALSE,'cStat'=>'','xMotivo'=>'');
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeRetRecepcao';
        $cabecVer       = $this->aCabec['versao'];
        $cabecXsdfile   = $this->aCabec['xsd'];
        $servURL        = $this->aURL[$wsdl]['URL'];
        $servName       = $this->aURL[$wsdl]['service'];
        $servVer        = $this->aURL[$wsdl]['version'];
        //monta dados para comunicação SOAP
        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="' . $cabecVer . '" xmlns="' . $this->URLnfe . '"><versaoDados>' . $servVer . '</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consReciNFe xmlns:xsi="'. $this->URLxsi . '" xmlns:xsd="' . $this->URLxsd . '" versao="' . $servVer . '" xmlns="' . $this->URLnfe . '"><tpAmb>' . $this->tpAmb . '</tpAmb><nRec>' . $recibo . '</nRec></consReciNFe>'
        );
        $retorno = $this->__sendSOAP($param, $servURL, $servName);
        if (is_array($retorno)) {
            //extrair a resposta da matriz e garantir que os dados retornem como UTF-8
            $xmlresp = utf8_encode($retorno[$servName.'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $aRet['xMotivo']='Houve uma falha na comunicação SOAP!!';
                return $aRet;
            }
            // houve retorno com notas aceitas ou não
            $aRet['status'] = TRUE;
            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do recebimento ou mensagem de erro
            $aRet['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo do status
            $aRet['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            //salvar o xml retornado do SEFAZ
            $nome = $this->temDir.$recibo.'-prot.xml';
            $nome = $doc->save($nome);
        }  else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $aRet;
    }


    /**
     * getNFeProtocol
     * Este método obtem o protocolo da NFe pelo Id da mesma
     *
     * Solicita dados de situaçao de NF
     * @name     getNFeProtocol
     * @package  NFePHP
     * @version  1.1
     * @param	 string   $idNFe numerico com 44 digitos
     * @return	 array
     **/
    public function getNFeProtocol($idNFe){
        //variavel de retorno do metodo
        $aRet = array('status'=>FALSE,'cStat'=>'','xMotivo'=>'');
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeConsultaNF';
        $cabecVer       = $this->aCabec['versao'];
        $cabecXsdfile   = $this->aCabec['xsd'];
        $servURL        = $this->aURL[$wsdl]['URL'];
        $servName       = $this->aURL[$wsdl]['service'];
        $servVer        = $this->aURL[$wsdl]['versao'];

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer . '" xmlns="' . $this->URLnfe . '"><versaoDados>' . $servVer . '</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consSitNFe xmlns:xsi="' . $this->URLxsi . '" xmlns:xsd="' . $this->URLxsd . '" versao="' . $servVer . '" xmlns="' . $this->URLnfe . '"><tpAmb>' . $this->tpAmb . '</tpAmb><xServ>CONSULTAR</xServ><chNFe>' . $idNFe . '</chNFe></consSitNFe>'
        );

        $retorno = $this->__sendSOAP($param, $servURL, $servName);
        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$servName.'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $aRet['xMotivo'] = 'Houve uma falha na comunicação SOAP';
                return $aRet;
            }

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $aRet['status'] = TRUE;
            $aRet['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aRet['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            //salvar o xml retornado do SEFAZ
            $nome = $this->temDir.$idNFe.'-prot.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do NuSoap!!';
        } //endif
        return $aRet;
    } //fim da função __getNFeProtocol


     /**
      * cancelNFe
      * Solicita o cancelamento de NF enviada
     *
     * @name cancelNFe
     * @version 1.1
     * @package NFePHP
     * @todo
     * @param	string  $idNFe ID da NFe com 44 digitos (sem o NFe na frente dos numeros)
     * @param   string  $protId Numero do protocolo de aceitaçao da NFe enviado anteriormente pelo SEFAZ
     * @param   string  $xJust Descrição da justificativa para o cancelamento da NFe
     * @return	Array   array('status'=>FALSE,'cStat'=>'','xMotivo'=>'');
     * @access  public
    **/
    public function cancelNFe($idNFe,$protId, $xJust){
        //variavel de retorno do metodo
        $aRet = array('status'=>FALSE,'cStat'=>'','xMotivo'=>'');

        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeCancelamento';
        $cabecVer       = $this->aCabec['versao'];
        $cabecXsdfile   = $this->aCabec['xsd'];
        $servURL        = $this->aURL[$wsdl]['URL'];
        $servName       = $this->aURL[$wsdl]['service'];
        $servVer        = $this->aURL[$wsdl]['versao'];

        $nfeDadosMsg = '<cancNFe xmlns="'.$this->URLnfe.'" versao="'.$servVer.'"><infCanc Id="ID'.$idNFe.'"><tpAmb>'.$this->tpAmb.'</tpAmb><xServ>CANCELAR</xServ><chNFe>'.$idNFe.'</chNFe><nProt>'.$protId.'</nProt><xJust>'.$xJust.'</xJust></infCanc></cancNFe>';
        $nfeDadosMsg = $this->signXML($nfeDadosMsg, 'infCanc');

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$servVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>$nfeDadosMsg
        );

        $retorno = $this->__sendSOAP($param, $servURL, $servName);
        if (is_array($retorno)) {

           $xmlresp = utf8_encode($retorno[$servName.'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $aRet['xMotivo'] = 'Houve uma falha na comunicação SOAP';
                return $aRet;
            }
            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $aRet['status'] = TRUE;
            $aRet['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aRet['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            //salvar o xml retornado do SEFAZ
            $nome = $this->temDir.$idNFe.'-canc.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $aRet;
    } //fim cancelNFe


    /**
     * inutNFe
     * Solicita inutilizaçao de uma serie de numeros de NF
     *
     * @name inutNFe
     * @version 1.2
     * @package NFePHP
     * @todo
     * @param	string  $ano
     * @param   string  $nfSerie
     * @param   integer $numIni
     * @param   integer $numFim
     * @param   boolean $bSave
     * @return	boolean TRUE se sucesso FALSE se falha
     * @access  public
    **/
    public function inutNFe($ano,$nfSerie,$modelo,$numIni,$numFim,$xJust){
        //variavel de retorno do metodo
        $aRet = array('status'=>FALSE,'cStat'=>'','xMotivo'=>'');

        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeInutilizacao';
        $cabecVer       = $this->aCabec['versao'];
        $cabecXsdfile   = $this->aCabec['xsd'];
        $servURL        = $this->aURL[$wsdl]['URL'];
        $servName       = $this->aURL[$wsdl]['service'];
        $servVer        = $this->aURL[$wsdl]['versao'];

        //Identificador da TAG a ser assinada formada
        //com Código da UF + CNPJ + modelo + série +
        //nro inicial e nro final precedida do literal “ID”
        $id = 'ID'.$this->cUF.$this->CNPJ.$modelo.$nfSerie.$numIni.$numFim;
        //dados da mensagem
        $nfeDadosMsg = '<inutNFe xmlns="'.$this->URLnfe.'" versao="'.$servVer.'"><infInut Id="'.$id.'"><tpAmb>'.$this->tpAmb.'</tpAmb><xServ>INUTILIZAR</xServ><cUF>'.$this->cUF.'</cUF><ano>'.$ano.'</ano><CNPJ>'.$this->CNPJ.'</CNPJ><mod>'.$modelo.'</mod><serie>'.$nfSerie.'</serie><nNFIni>'.$numIni.'</nNFIni><nNFFin>'.$numFim.'</nNFFin><xJust>'.$xJust.'</xJust></infInut></inutNFe>';
        //assinar a nfeDadosMsg
        $nfeDadosMsg = $this->signXML($nfeDadosMsg, 'infInut');

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$servVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>$nfeDadosMsg
        );

        $retorno = $this->__sendSOAP($param, $servURL, $servName);
        if (is_array($retorno)) {

           $xmlresp = utf8_encode($retorno[$servName.'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $aRet['xMotivo'] = 'Houve uma falha na comunicação SOAP';
                return $aRet;
            }
            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $aRet['status'] = TRUE;
            $aRet['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aRet['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            //salvar o xml retornado do SEFAZ
            $nome = $this->temDir.$idNFe.'-inut.xml';
            $nome = $doc->save($nome);
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $aRet;
    } //fim inutNFe

    

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
    * @version 1.1
    * @package NFePHP
    * @param  string $spathXML  Caminho completo para o arquivo xml
    * @param  string $sAmbiente Pode ser "homologacao" ou "producao"
    * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, etc..)
    * @return mixed             FALSE se houve erro ou array com os dado do URLs das SEFAZ
    */
    public function loadSEFAZ($spathXML,$sAmbiente,$sUF) {

        //verifica se o arquivo xml pode ser encontrado no caminho indicado
        if ( file_exists($spathXML) ) {
            //carrega o xml
            $xml = simplexml_load_file($spathXML);
        } else {
            //sai caso não possa localizar o xml
            return FALSE;
        }

        //estabelece a expressão xpath de busca
        $xpathExpression = "/WS/UF[sigla='$sUF']/$sAmbiente";
        //para cada "nó" no xml que atenda aos critérios estabelecidos
        foreach ( $xml->xpath( $xpathExpression ) as $gUF ) {
            //para cada "nó filho" retonado
            foreach ( $gUF->children() as $child ) {
                $aUrl[$child->getName()]['URL'] = (string) $child[0];
                // em cada um desses nós pode haver atributos como a identificação
                //do nome do webservice e a sua versão
                foreach ( $child->attributes() as $a => $b) {
                    $aUrl[$child->getName()][$a] = (string) $b;
                }
            }
        }
        return $aUrl;
    }

    /**
     * loadCerts
     * Carrega o certificado pfx e gera as chaves privada e publica no
     * formato pem para uso do SOAP e registra as variaveis de ambiente
     * Esta função deve ser invocada enates das outras do sistema que
     * dependam do certificado.
     * Além disso esta função també avalia a validade do certificado.
     * Os certificados padrão A1 (que são usados pelo sistema) tem validade
     * limitada à 1 ano e caso esteja vencido a função retornará FALSE.
     *
     * Resultado
     *  A função irá criar o certificado digital (chaves publicas e privadas)
     *  no formato pem e grava-los no diretorio indicado em $this->certsDir
     *  com os nomes :
     *     privatekey.pem
     *     publickey.pem
     *  Estes arquivos tabém serão carregados nas variáveis da classe
     *  $this->pathCert (com o caminho completo para o arquivo publickey.pem)
     *  $this->pathKey (com o caminho completo para o arquivo privatekey.pem)
     * Dependencias
     *   $this->pathCerts
     *   $this->nameCert
     *   $this->passKey
     *
     * @name loadCerts
     * @version 1.0
     * @package NFePHP
     * @param	none
     * @return	boolean TRUE se o certificado foi carregado e FALSE se nao
    **/
    public function loadCerts(){
        //verificar se o nome do certificado e
        //o path foram carregados nas variaveis da classe
        if ($this->certsDir == '' || $this->certName == '') {
                $this->errMsg = 'Um certificado deve ser passado para a classe!!';
                $this->errStatus = TRUE;
                return FALSE;
        }
        //monta o caminho completo até o certificado pfx
        $pCert = $this->certsDir.$this->certName;

        //verifica se o arquivo existe
        if(!file_exists($pCert)){
                $this->errMsg = 'Certificado não encontrado!!';
                $this->errStatus = TRUE;
                return FALSE;
        }
        //carrega o certificado em um string
        $key = file_get_contents($pCert);
        //carrega os certificados e chaves para um array denominado $x509certdata
        if (!openssl_pkcs12_read($key,$x509certdata,$this->keyPass) ){
                $this->errMsg = 'O certificado não pode ser lido!! Provavelmente corrompido ou com formato inválido!!';
                $this->errStatus = TRUE;
                return FALSE;
        }
        //verifica sua validade
        $aResp = $this->validCerts($x509certdata['cert']);
        if ( $aResp['error'] != '' ){
                $this->errMsg = 'Certificado invalido!! - ' . $aResp['error'];
                $this->errStatus = TRUE;
                return FALSE;
        }
        //monta o path completo com o nome da chave privada
        $filePriv = $this->certsDir.'privatekey.pem';
        //verifica se arquivo já existe
        if(file_exists($filePriv)){
            //se existir verificar se é o mesmo
            $conteudo = file_get_contents($filePriv);
            //comparar os primeiros 30 digitos
            if ( !substr($conteudo,0,30) == substr($x509certdata['pkey'],0,30) ) {
                 //se diferentes gravar o novo
                if (!file_put_contents($filePriv,$x509certdata['pkey']) ){
                    $this->errMsg = 'Impossivel gravar no diretório!!! Permissão negada!!';
                    $this->errStatus = TRUE;
                    return FALSE;
                }
            }
        } else {
            //salva a chave privada no formato pem para uso so SOAP
            if ( !file_put_contents($filePriv,$x509certdata['pkey']) ){
                   $this->errMsg = 'Impossivel gravar no diretório!!! Permissão negada!!';
                   $this->errStatus = TRUE;
                   return FALSE;
            }
        }
        //monta o path completo com o nome da chave prublica
        $filePub =  $this->certsDir.'publickey.pem';
        //verifica se arquivo já existe
        if(file_exists($filePub)){
            //se existir
            //se existir verificar se é o mesmo
            $conteudo = file_get_contents($filePub);
            //comparar os primeiros 30 digitos
            if ( !substr($conteudo,0,30) == substr($x509certdata['cert'],0,30) ) {
                 //se diferentes gravar o novo
                $n = file_put_contents($filePub,$x509certdata['cert']);
            }
        } else {
            //salva a chave publica no formato pem para uso so SOAP
            $n = file_put_contents($filePub,$x509certdata['cert']);
        }
        return TRUE;
    }

   /**
    * validCerts
    * Validaçao do cerificado digital, além de indicar
    * a validade, este metodo carrega a propriedade
    * mesesToexpire da classe que indica o numero de
    * meses que faltam para expirar a validade do mesmo
    * esta informacao pode ser utilizada para a gestao dos
    * certificados de forma a garantir que sempre estejam validos
    *
    * @name     validCerts
    * @version  1.0
    * @package  NFePHP
    * @param    string  $cert Certificado digital no formato pem
    * @return	array ['status'=>TRUE,'meses'=>8,'dias'=>245]
    */
    public function validCerts($cert){

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
            $flagOK = $flagOK && TRUE;
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
        $monthsToExpire = ($m-$n);
        return array('status'=>$flagOK,'error'=>$errorMsg,'meses'=>$monthsToExpire,'dias'=>$daysToExpire);
    }

    /**
     * __cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura do xml
     *
     * @name __cleanCerts
     * @version 1.0
     * @package NFePHP
     * @param    $certFile
     * @return   string contendo a chave digital limpa
     * @access   private
     **/
    private function __cleanCerts($certFile){
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
    }

   /**
    * __convertTime
    * Converte o campo data time retornado pelo webservice
    * em um timestamp unix
    *
    * @name __convertTime
    * @version 1.0
    * @package NFePHP
    * @param    string   $DH
    * @return   timestamp
    * @access   private
    **/
    private function __convertTime($DH){
        if ($DH){
            $aDH = explode('T',$DH);
            $adDH = explode('-',$aDH[0]);
            $atDH = explode(':',$aDH[1]);
            $timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);
            return $timestampDH;
        }
    }

    /**
     * listDir
     * Método para obter todo o conteudo de um diretorio, que atendam
     * ao critério indicado.
     *
     * @param string $dir Diretorio a ser pesquisado
     * @param string $fileMatch Critério de seleção
     * @return array Matriz com os nome dos arquivos que atendem ao critério estabelecido
     */
     public function listDir($dir,$fileMatch){
        if ( trim($fileMatch) != '' && trim($dir) != '' ) {
            $fileMatch = strtolower($fileMatch);
            $aFM = explode(".",$fileMatch);
            //cria um array limpo
            $aName=array();
            //guarda o diretorio atual
            $oldDir = getcwd();
            //verifica se o parametro $dir define um diretorio real
            if ( is_dir($dir) ) {
                //mude para o novo diretorio
                chdir($dir);
                //pegue o diretorio
                $diretorio = getcwd();
                //abra o diretório
                $ponteiro  = opendir($diretorio);
                $x = 0;
                //comprimento do critério de pesquisa antes do ponto
                $n = strlen($aFM[0]);
                // monta os vetores com os itens encontrados na pasta
                while (false !== ($file = readdir($ponteiro))) {
                    //procure se não for diretorio
                    if ($file != "." && $file != ".." ) {
                        if ( !is_dir($file) ){
                            //dividir o nime do arquivo antes e depois do ponto
                            $aFile = explode(".", $file);
                            //podem haver varios pontos no nome do arquivo
                            //melhorar para dividir somente a terminação
                            //pegar os ultimos n digitos do nome do arquivo
                            //que representa
                            $c = count($aFile);
                            //echo $c.' - '.$file.'<BR>';
                            $nomeArq = strtolower(substr($aFile[$c-2], -$n));
                            if ( $nomeArq == $aFM[0] && $aFile[$c-1] == $aFM[1] ){
                                 //rename($file, strtolower($file)) ;
                                 $aName[$x] = strtolower($file);
                                 $x++;
                            }//endif
                        } //endif
                    } //endif
                }//endwhile
                closedir($ponteiro);
                //mude para o diretorio anterior
                chdir($oldDir);
            }//endif
        }//endif
        return $aName;
    } //fim da função


    /**
     * __sendSOAP
     * Estabelece comunicaçao com servidor SOAP da SEFAZ, usando as chaves publica e privada
     * parametrizadas na contrução da classe.
     *
     * @name __sendSOAP
     * @version 1.2
     * @package NFePHP
     * @param    array   $param Matriz com o cabeçalho e os dados da mensagem soap
     * @param    string  $urlsefaz Designaçao do URL do serviço SOAP
     * @param    string  nome do método do serviço SOAP desejado
     * @return   array  Array com a resposta do SOAP ou String do erro ou false
     **/
    public function __sendSOAP($param,$urlsefaz,$service){
        try {
            //monta a url do serviço
            $URL = $urlsefaz.'?WSDL';
            //inicia a conexao SOAP
            $client = new nusoap_client($URL, true);
            $client->authtype         = 'certificate';
            $client->soap_defencoding = 'UTF-8';
            //Seta parametros para a conexao segura
            $client->certRequest['sslkeyfile']  = $this->certsDir . 'privatekey.pem';
            $client->certRequest['sslcertfile'] = $this->certsDir . 'publickey.pem';
            $client->certRequest['passphrase']  = $this->passPhrase;
            $client->certRequest['verifypeer']  = false;
            $client->certRequest['verifyhost']  = false;
            $client->certRequest['trace']       = 1;
        }
        //em caso de erro retorne o mesmo
        catch (Exception $ex) {
            if ( is_bool($client->getError()) ){
                $this->errStatus = False;
                $this->errMsg = '';
            } else {
                $this->errStatus = True;
                $this->errMsg = $client->getError();
            }
        }
        // chama a funçao do webservice, passando os parametros
        $result = $client->call($service, $param);
        $this->soapDebug = htmlspecialchars($client->debug_str, ENT_QUOTES);
        // retorna o resultado da comunicaçao
        return $result;
    } //fim __sendSOAP


    /**
     * __getNumLot
     * Obtêm o numero do último lote de envio
     *
     * @return numeric Numero do Lote ou 1
     */
    private function __getNumLot(){
         $lotfile = "config/numloteenvio.xml";
         $domLot = new DomDocument;
         $domLot->load($lotfile);
         $num = $domLot->getElementsByTagName('num')->item(0)->nodeValue;
         if( is_numeric($num) ){
            return $num;
         } else {
            //arquivo não existe suponho que o numero então seja 1
            return 1;
         }
    }

    /**
     * __putNumLot
     * Grava o numero do lote de envio usado
     *
     * @param numeric $num Inteiro com o numero do lote enviado
     * @return boolean TRUE sucesso ou FALSO erro
     */
    private function __putNumLot($num){
        if ( is_numeric($num) ){
            $lotfile = "config/numloteenvio.xml";
            $numLot = '<?xml version="1.0" encoding="UTF-8"?><root><num>' . $num . '</num></root>';
            if (!file_put_contents($lotfile,$numLot) ) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }


} //fim da classe
