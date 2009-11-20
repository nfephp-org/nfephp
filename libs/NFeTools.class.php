<?php

require_once('nusoap/nusoap.php');
require_once('fpdf/fpdf.php');
//require('DanfeNFePHP.class');

/**
// NFe geradas pelo ERP e colocadas nestes diretorio para
// para validar, assinar e enviar ao SEFAZ
// ao detectar um documentos nesta pasta o sistema deve verificar o numero
// da nota e procurar por equivalentes nas pastas de reprovadas e rejeitadas
// caso encontre alguma com o mesmo numero deve remover-la da pasta de reprovadas ou
// da pasta de rejeitadas
$entradasDir="../../NFE_$ambiente/entradas/";

// NFe validadas e prontas para assinatura
// após a NFe ser assinada a NFe é removida deste diretório e
// gravada (com a ssinatura) no diretorio assinadasDir
$validadasDir="../../NFE_$ambiente/validadas/";

// NFe reprovadas que devem ser corrigidas
// após a NFe ter sido testada e houver erro na sua estrutura
// ela será removida da pasta de entradas e colocada na pasta de reprovadas
// o sistema deve informar o operador da ocorrencia da rejeição e seu motivo
// nada mais será feito com essas NFe !!!
// até que uma novaNFe com o numero desta reprovada seja colocada na pasta de entrada
// e neste caso esta NFe reprovada será removida
$reprovadasDir="../../NFE_$ambiente/reprovadas/";

// NFe assinadas e prontras para o envio ao SEFAZ
// após a transmissão ao SEFAZ com sucesso (com recibo) as NFe
// serão removidas deste diretorio e colocadas no diretorio de enviadas
$assinadasDir="../../NFE_$ambiente/assinadas/";

// NFe já enviadas (individualmente ou em lote) ao SEFAZ
// as NFe já transmitidas com sucesso ao SEFAZ (com recibo) serão
// removidas da pasta assinadas e colocadas neste diretorio
$enviadasDir="../../NFE_$ambiente/enviadas/";

// NFe consultadas quanto ao seu status e aprovadas status 100
// essas NFe dever complementadas com o numero do protocolo
// nesta pasta devem ser criados subdiretorios ex. 200910 para
// melhorar a visibilidade e o acesso as NFe por data de emissão
$aprovadasDir="../../NFE_$ambiente/enviadas/aprovadas/";

// NFe consultadas quanto ao seu status e denegadas status 110
// essas NFe dever complementadas com o numero do protocolo
// nesta pasta devem ser criados subdiretorios ex. 200910 para
// melhorar a visibilidade e o acesso as NFe por data de emissão
$denegadasDir="../../NFE_$ambiente/enviadas/denegadas/";

// NFe consultadas quanto ao seu status e rejeitadas status > 110
// após a NFe ter sido avaliada pelo SEFAZ e houver erro na sua estrutura
// ela será removida da pasta de enviadas e colocada na pasta de rejeitadas
// o sistema deve informar o operador da ocorrencia da rejeição e seu motivo
// nada mais será feito com essas NFe !!! até que uma nova NFe com o mesmo numero
// seja criada e colocada na pasta de entrada e neste caso essa NFe será removida
$rejeitadasDir="../../NFE_$ambiente/enviadas/rejeitadas/";

// Respostas as solicitações de cancelamento de NFe
// devem ser criadas subpastas com ANOMES ex. 200910
// para controle por período
$canceladasDir="../../NFE_$ambiente/canceladas/";

// Respostas as solicitações de inutilização de NFe
// devem ser criadas subpastas com ANOMES ex. 200910
// para controle por período
$inutilizadasDir="../../NFE_$ambiente/inutilizadas/";

// Arquivos temporários e de debug
// os arquivos desta pasta serão removidos periódicamente
$temporarioDir="../../NFE_$ambiente/temporarias/";

// NFe recebidas de terceiros
// nesta pasta devem ser colocadas as NFe recebidas de terceiros
// em subpastas identificadas pelo ANO e MES
$recebidasDir="../../NFE_$ambiente/recebidas/";

// NFe recebidas de terceiros e já consultadas para verificar sua validade
// nesta pasta devem ser movidas as NFe recebidas de terceiros e ja validadas
// na SEFAZ
// em subpastas identificadas pelo ANO e MES
$consultadas="../../NFE_$ambiente/consultadas/";

// Bibliotecas e classes
// diretorio que contêm as classes
$libDir='../libs/';

// Certificados e chaves
// diretorio que contêm os certificados
$certDir='../certs/';

// Esquemas
// diretorio que contem os esquemas de validação
// estes esquemas devem ser mantidos atualizados
$xsdDir='../schemes/';

**/
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
 * @package   NFePHP
 * @name      NFeTools
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 */
//define o caminho base da instalação do sistema
define( 'PATH_ROOT', realpath( dirname( basename( $_SERVER[ 'SCRIPT_NAME' ] ) ) ) . DIRECTORY_SEPARATOR );

class NFeTools {

    // propriedades da classe

    private $tpAmb='';
    private $arqDir='';
    private $pdfDir ='';
    private $entDir='';
    public  $valDir='';
    private $repDir='';
    private $assDir='';
    private $envDir='';
    private $aprDir='';
    private $denDir='';
    private $rejDir='';
    private $canDir='';
    private $inuDir='';
    private $temDir='';
    private $recDir='';
    private $conDir='';
    private $libsDir='';
    private $certsDir='';

    /**
     * xsdDir
     * diretorio que contem os esquemas de validação
     * estes esquemas devem ser mantidos atualizados
     *
     * @var string
     */
    public $xsdDir='';
    private $keyPass='';
    private $passPhrase='';
    private $certName='';
    private $empName='';
    private $cUF='';
    private $UF='';
    private $anoMes='';

    public $aURL='';
    public $aCabec='';
    public $errMsg='';
    public $errStatus=FALSE;
    public $nusoap_debug='';

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
     * @access private
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
     * Este método utiliza o arquivo de configuração para montar os diretórios
     * e várias propriedades interna da classe permitindo automatizar melhor o
     * processo de comunicação com o SEFAZ.
     *
     * @param  none
     * @return none
     */
    function __construct() {
        //testa a existencia do arquivo de configuração
        if ( is_file("config/config.php") ){
            include("config/config.php");

            // carrega propriedades da classe com os dados de configuração
            $this->tpAmb=$ambiente;
            if ( $ambiente % 2 == 0 ) {
                $sAmb='homologacao';
            } else {
                $sAmb='producao';
            }

            $this->empName=$empresa;
            $this->cUF=$cUF;
            $this->UF=$UF;
            $this->certName=$certName;
            $this->keyPass=$keyPass;
            $this->passPhrase=$passPhrase;
            $this->arqDir = $arquivosDir;

            //carrega propriedade com ano e mes ex. 200911
            $this->anoMes = date('Ym');

            $this->xsdDir = PATH_ROOT . 'schemes/';
            $this->certsDir =  PATH_ROOT . 'certs/';

            $this->aCabec = array('versao'=>'1.02','xsd'=>'cabecMsg_v1.02.xsd');
        
            // monta a estrutura de diretorios utilizados na manipulação das NFe
            $this->entDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'entradas' . DIRECTORY_SEPARATOR;
            $this->valDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'validadas' . DIRECTORY_SEPARATOR;
            $this->repDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'reprovadas' . DIRECTORY_SEPARATOR;
            $this->assDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'assinadas' . DIRECTORY_SEPARATOR;
            $this->envDir=$this->arqDir . "_$sAmb" . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR;
            $this->aprDir=$this->envDir . 'aprovadas' . DIRECTORY_SEPARATOR;
            $this->denDir=$this->envDir . 'denegadas' . DIRECTORY_SEPARATOR;
            $this->rejDir=$this->envDir . 'rejeitadas' . DIRECTORY_SEPARATOR;
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
            $this->aURL = $this->__loadSEFAZ('config/urlWebServicesNFe.xml',$sAmb,$this->UF);

            if ( !$retorno = $this->__loadCerts() ) {
                $this->errStatus = true;
            }
        } else {
            // não existe arquivo de configuração
            $this->errMsg = "Não foi localizado o arquivo de configuração.";
            $this->errStatus = true;
        }

    }

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
        //varre pasta "entradas" a procura de NFe
        $aName = $this->__listDir($this->entDir,'-nfe.xml');
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
        $aName = $this->__listDir($this->assDir,'-nfe.xml');
        // se foi retornado algum arquivo
        if ( count($aName) > 0 ){
            for ( $x=0; $x < count($aName); $x++ ) {
                //carrega nfe para assinar em uma strig
                $filename = $entradasDir.$aName[$x];

                if ( $nfefile = file_get_contents($filename) ) {
                    //validar
                    if ( $this->__validXML($nfefile,$this->xsdDir . 'nfe_v1.10.xsd') ) {
                        // validado => transferir para pasta validados
                        $file = $this->valDir . $aName[$x];
                        if ( !file_put_contents($file, $nfefile) ) {
                            $this->errStatus = TRUE;
                        } else {
                            unlink($filename);
                        }
                    } else {
                        //NFe com erros transferir de pasta reprovadas
                        $file = $this->repDir . $aName[$x];
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


    public function autoSendNFe(){
        //varre pasta "validadas"
        $aName = $this->__listDir($this->valDir,'-nfe.xml');
        //se houver algum arquivo xml continua, caso contrario sai
        // se foi retornado algum arquivo
        if ( count($aName) > 0 ){
            //pega numero do lote no arquivo
            //envia validadas para SEFAZ
            //se sucesso, salva retorno xml na pasta "temporario" e incrementa numero do lote
            //e move a nfe.xml para o diretorio "enviadas"
            //se fracasso, não faz nada e retorna o erro
        }
    }

    public function autoAuthorizeNFe(){
        //varre a pasta "temporarias"
        //se houver algum arquivo *-rec.xml continua, caso contrario sai
        //abre cada arquivo de recibo pega o numero do recibo e do lote e
        //solicita o status da NFe ao SEFAZ
        //se sucesso,
        //apaga o arquivo do recibo e retorna o xml com o protocolo
        //busta o xml da NFe correspondente
        //monta o novo arquivo xml com o protocolo como raiz e insere a nfe em seu interior
        //salva este novo arquivo na pasta "enviadas" na
        //sub-pasta referente ao "anomes" da emissão NFE e remove a NFe.xml da pasta "enviadas"
        //imprime o DANFE na impressora indicada
        //envia o e-mail ao destinatário da NFe
        //se fracasso, não faz nada e retorna codigo de erro
    }

    public function autoMailNFe(){
        //esta função depende de acesso ao banco de dados

    }


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
            $aRetorno['dhRecbto'] = date("d/m/Y H:i",$this->convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue));
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // obervaçoes opcional
            $aRetorno['xObs'] = $doc->getElementsByTagName('xObs')->item(0)->nodeValue;
        } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do NuSoap!!';
            return FALSE;
        }
        return $aRetorno;
    }


    /**
     * consultaCadastro
     * Solicita dados de situaçao de Cadastro
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
                    //$this->aCad = array($n,array('IE'=>$IE,'CNPJ'=>$CNPJ,'CPF'=>$CPF,'UF'=>$UF,'cSit'=>$cSit,'xNome'=>$xNome,'xFant'=>$xFant,'xRegApur'=>$xRegApur,'CNAE'=>$CNAE,'dIniAtiv'=>$dIniAtiv,'dUltSit'=>$dUltSit,'dBaixa'=>$dBaixa,'IEUnica'=>$IEUnica,'IEAtual'=>$IEAtual,'xLgr'=>$xLgr,'nro'=>$nro,'xCpl'=>$xCpl,'xBairro'=>$xBairro,'cMun'=>$cMun,'xMun'=>$xMun,'CEP'=>$CEP));
                    //$n ++;
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
    * __validXML
    * Verifica o xml com base no xsd
    * Esta função pode validar qualquer arquivo xml do sistema de NFe
    * Há um bug no libxml2 para versões anteriores a 2.7.3
    * que causa um falso erro na validação da NFe devido ao
    * uso de uma marcação no arquivo tiposBasico_v1.02.xsd
    * onde se le {0 , } substituir por *
    *
    * @name __validXML
    * @version 2.0
    * @package NFePHP
    * @param    string  $docxml  string contendo o arquivo xml a ser validado
    * @param    string  $xsdfile Path completo para o arquivo xsd
    * @return   array   ['staus','error']
    */
    private function __validXML($docXml, $xsdFile){

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
    * __loadSEFAZ
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
    * @name __loadSEFAZ
    * @version 1.1
    * @package NFePHP
    * @param  string $spathXML  Caminho completo para o arquivo xml
    * @param  string $sAmbiente Pode ser "homologacao" ou "producao"
    * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, etc..)
    * @return mixed             FALSE se houve erro ou array com os dado do URLs das SEFAZ
    */
    private function __loadSEFAZ($spathXML,$sAmbiente,$sUF) {

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
     * __loadCerts
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
     * @name __loadCerts
     * @version 1.0
     * @package NFePHP
     * @param	none
     * @return	boolean TRUE se o certificado foi carregado e FALSE se nao
    **/
    private function __loadCerts(){
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
        $aResp = $this->__validCerts($x509certdata['cert']);
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
    * __validCerts
    * Validaçao do cerificado digital, além de indicar
    * a validade, este metodo carrega a propriedade
    * mesesToexpire da classe que indica o numero de
    * meses que faltam para expirar a validade do mesmo
    * esta informacao pode ser utilizada para a gestao dos
    * certificados de forma a garantir que sempre estejam validos
    *
    * @name     __validCerts
    * @version  1.0
    * @package  NFePHP
    * @param    string  $cert Certificado digital no formato pem
    * @return	array ['status'=>TRUE,'meses'=>8,'dias'=>245]
    */
    private function __validCerts($cert){

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
     * __signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     * Dependência
     *      carregaCert()
     *
     * @name __signXML
     * @version 1.1
     * @package NFePHP
     * @param	string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return	mixed FALSE se houve erro ou string com o XML assinado
     */
    private function __signXML($docxml, $tagid=''){

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
            $idnome = ereg_replace('[^0-9]','', $id);
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
            $cert = $this->limpaCert($this->certsDir.'publickey.pem');

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
     * __sendLot
     * Envia lote de Notas Fiscais
     *
     * @name __sendLot
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	array   $aNFe notas fiscais em xml uma em cada campo de uma string
     * @param   integer $idLote o id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma NFe usar banco
     *                          de dados
     * @param   boolean $bSave indica se o xml de retorno deve ser salvo em arquivo
     * @return	boolean	True se aceito o lote ou False de rejeitado
     * @access  public
    **/
    public function __sendLot($aNFe=array(),$idLote='1',$bSave=TRUE){
        //variavel de retorno do metodo
        $bRet = false;
        //abre arquivo com o último numero de lote numero


        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeRecepcao';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        // limpa a variavel
        $sNFe = '';

        // monta string com as NFe enviadas
        $sNFe = implode('',$aNFe);

        //remover <?xml version="1.0" encoding=... das NFe pois somente
        // uma dessas tags pode exitir na mensagem
        $sNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>','',$sNFe);

        //ATENÇAO $sNFe nao pode ultrapassar 500kBytes
        if (strlen($sNFe) > 470000) {
            //indicar erro e voltar
            return FALSE;
        }

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="http://www.portalfiscal.inf.br/nfe"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<enviNFe xmlns="'.$this->URLnfe.'" xmlns:ds="'.$this->URLdsig.'" xmlns:xsi="'.$this->URLxsi.'" versao="'.$dataVer.'"><idLote>'.$idLote.'</idLote>'.$sNFe.'</enviNFe>'
        );

        //retorno e um array contendo a mensagem do SEFAZ
        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }

            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            // status do recebimento ou mensagem de erro
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->tpAmb = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $this->verAplic = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->xMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;

            // em caso de sucesso  cStat = 103
            if ($this->cStat == '103'){
                // tempo medio de processamento
                $this->tMed = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
                // data e hora da mensagem
                $this->dhRecbto = $doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                // numero do recibo, se o lote foi aceito
                // guardar o numero do recibo na base de dados pois devera ser usado
                // para verificar o estatus dos Lotes enviados
                $this->nRec = $doc->getElementsByTagName('nRec')->item(0)->nodeValue;
                $bRet = TRUE;
            }
            if ($bSave){
                //salvar o xml retornado do SEFAZ
                $nome = $this->enviadasNF.$idLote.'-rec.xml';
                $nome = $doc->save($nome);
            }
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }

        return $bRet;
    }

    /**
     * __getProtocol
     * Solicita resposta do lote de Notas Fiscais
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name retornoNF
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	string   $recibo numero do recibo do envio do lote
     * @param   boolean  $bSave indica se o xml de retorno deve ser salvo em arquivo
     * @return	boolean  True se sucesso false se falha
     * @access  public
    **/
    public function __getProtocol($recibo, $bSave=TRUE){

        //variavel de retorno do metodo
        $bRet = FALSE;

        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeRetRecepcao';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consReciNFe xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'"><tpAmb>'.$this->ambiente.'</tpAmb><nRec>'.$recibo.'</nRec></consReciNFe>'
        );

        $retorno = $this->__sendSOAP($param, $wsdl);

        if (is_array($retorno)) {
            //extrair a resposta da matriz e garantir que os dados retornem como UTF-8
            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }

            // houve retorno com notas aceitas ou não
            $bRet = TRUE;
            //terminação do arquivo em caso de insucesso
            $terminacao = "err";

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do recebimento ou mensagem de erro
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo do status
            $this->xMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // tipo de ambiente da comunicaçao
            $this->tpAmb = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            //versao do aplicativa que processou a mensagem
            $this->verAplic = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            // numero do recibo, consultado
            $this->nRec = $doc->getElementsByTagName('nRec')->item(0)->nodeValue;
            //caso o status da resposta seja 104 pegar os outros dados
            // caso o status seja 105 => em processamento consultar mais tarde
            // caso o status seja 106 => lote não localizado enviar novamente
            // caso o status seja 248 ou 233 recibo ou CNPJ com problemas (com erros) corrigir os dados
            if( $this->cStat == '104' ) {
                //terminação do arquivo em caso de sucesso
                $terminacao = "xml";
                // vai haver um grupo protNFe para cada NF enviada no lote
                $protNFe = $doc->getElementsByTagName('protNFe');

                foreach ($protNFe as $pNFe) {
                    $versao = $pNFe->getAttribute('versao');
                    $nNFe = $pNFe->getElementsByTagName('infProt')->item(0);
                    //extrai o id da tag
                    $id = trim($nNFe->getAttribute("Id"));  // não retornou pela SEFAZ-SP (dfadel)
                    $tpAmb = $pNFe->getElementsByTagName('tpAmb')->item(0)->nodeValue;
                    $verAplic = $pNFe->getElementsByTagName('verAplic')->item(0)->nodeValue;
                    $chNFe = $pNFe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $dhRecbto = $pNFe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $nProt = $pNFe->getElementsByTagName('nProt')->item(0)->nodeValue;
                    $digVal = $pNFe->getElementsByTagName('digVal')->item(0)->nodeValue;
                    $cStat = $pNFe->getElementsByTagName('cStat')->item(0)->nodeValue;
                    $xMotivo = $pNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue;
                    $this->aNFe[] = array('chNFe'=>$chNFe,'cStat'=>$cStat,'xMotivo'=>$xMotivo,'nProt'=>$nProt,'digVal'=>$digVal,'dhRecbto'=>$dhRecbto,'tpAmb'=>$tpAmb,'verAplic'=>$verAplic,'versao'=>$versao,'Id'=>$id);
                }
            }
            if ($bSave){
                //salvar o xml retornado do SEFAZ
                $nome = $this->validadasNF.$recibo.'-prot.'.$terminacao;
                $nome = $doc->save($nome);
            }
        }  else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }


    /**
     * __cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura
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
            $aDH = split('T',$DH);
            $adDH = split('-',$aDH[0]);
            $atDH = split(':',$aDH[1]);
            $timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);
            return $timestampDH;
        }
    }


    public function __listDir($dir,$fileMatch){
        $aName=array();
        $aFM = explode(".",$fileMatch);
        if ( is_dir($dir) ) {
            chdir($dir);
            $diretorio = getcwd();
            // abre o diretório
            $ponteiro  = opendir($diretorio);
            $x = 0;
            // monta os vetores com os itens encontrados na pasta
            while (false !== ($file = readdir($ponteiro))) {
                if ($file != "." && $file != ".." ) {
                    //testar se o nome do arquivo conten
                    $aFile = explode(".", $file);
                    if ( substr($aFile[0], -strlen($aFM[0])) == $aFM[0] && $aFile[1] == $aFT[1] ){
                         rename($file, strtolower($file)) ;
                         $aName[$x] = strtolower($file);
                         $x++;
                     }
                }
            }
            closedir($ponteiro);
        }
        return $aName;
    }


    /**
     * __sendSOAP
     * Estabelece comunicaçao com servidor SOAP
     *
     * @name __sendSOAP
     * @version 2.0
     * @package NFePHP
     * @param    array   $param Matriz com o cabeçalho e os dados da mensagem soap
     * @param    string  $wsdl Designaçao do Serviço SOAP
     * @return   array  Array com a resposta do SOAP ou String do erro ou false
     **/
    private function __sendSOAP($param,$urlsefaz,$service){

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
        $this->nusoap_debug = htmlspecialchars($client->debug_str);
        // retorna o resultado da comunicaçao
        return $result;
    }



}
