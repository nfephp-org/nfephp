<?php
/**
 *
 * NFEtools
 * Copyright (c) 2009 Roberto L. Machado
 *
 * AGRADECIMENTOS
 *      Sr. Dajalma Fadel Junior (por suas valiosas contribuições ao desenvolvimento deste projeto)
 *
 * Dependências
 *
 *      module PHP5-curl
 *      module OpenSSL
 *      class NUSoap
 *      class FPDF
 *      class danfe.class
 *
 * @name NFeTools
 * @version  0.1
 * @package NFePHP
 * @todo
 * @copyright 2009 Roberto L. Machado
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @access   public
**/

require_once('./libs/nusoap/nusoap.php');
require_once('./libs/fpdf/fpdf.php');


define('HOMOLOGACAO','2');
define('PRODUCAO','1');
define('CONTINGENCIAHOMOLOGACAO','4');
define('CONTINGENCIAPRODUCAO','3');


class NFEtools {

    //*************************
    // Propriedades
    //*************************

        /**
	 * Ambiente de conexao com o webservie
	 * Esta propriedade deve ser setada na inicialização da classe
	 * @var string $ambiente
	 * @access public
	 */
    var $ambiente="2"; //homologaçao

        /**
         * Contem os dados para debug do NuSoap
         * @var string $debug_str
         * @access public
         */
    var $debug_str = '';


        /**
    	 * URLs dos webservice
	 * NOTA : Estes dados deve ser dinâmicamente carregados pelo sistema
         * com base em um banco de dados para tornar o sistema mais flexível
	 *
	 * @var array $aURLwsdl
	 * @access public
	 */
    var $aURLwsdl=array(
                    'ConsultaCadastro'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeWEB/services/cadconsultacadastro.asmx',
                    'NfeRecepcao'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nferecepcao.asmx',
                    'NfeRetRecepcao'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nferetrecepcao.asmx',
                    'NfeCancelamento'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfecancelamento.asmx',
                    'NfeInutilizacao'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfeinutilizacao.asmx',
                    'NfeStatusServico'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfestatusservico.asmx',
                    'NfeConsultaNF'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfeconsulta.asmx'
                    );

	/**
	 * Funçoes do webservice
	 * NOTA : Estes dados deve ser dinâmicamente carregados pelo sistema
         * com base em um banco de dados para tornar o sistema mais flexível
	 *
	 * @var      array
	 * @access   public
	**/
    var $aFunctionWdsl=array(
                    'ConsultaCadastro'=>'consultaCadastro',
                    'NfeRecepcao'=>'nfeRecepcaoLote',
                    'NfeRetRecepcao'=>'nfeRetRecepcao',
                    'NfeCancelamento'=>'nfeCancelamentoNF',
                    'NfeInutilizacao'=>'nfeInutilizacaoNF',
                    'NfeStatusServico'=>'nfeStatusServicoNF',
                    'NfeConsultaNF'=>'nfeConsultaNF'
                    );

	/**
	 * Arquivos xsd das funcoes do webservice
	 * NOTA : Estes dados deve ser dinâmicamente carregados pelo sistema
         * com base em um banco de dados para tornar o sistema mais flexível
         *
	 * @var      array
	 * @access   public
	**/
     var $aFxsd=array(
                    'ConsultaCadastro'=>'consCad_v1.01.xsd',
                    'NfeRecepcao'=>'envNFe_v1.10.xsd',
                    'NfeRetRecepcao'=>'retEnviNFe_v1.10.xsd',
                    'NfeCancelamento'=>'cancNFe_v1.07.xsd',
                    'NfeInutilizacao'=>'inutNFe_v1.07.xsd',
                    'NfeStatusServico'=>'consStatServ_v1.07.xsd',
                    'NfeConsultaNF'=>'consSitNFe_v1.07.xsd',
                    'CabecMsg'=>'cabecMsg_v1.02.xsd'
                    );

	/**
	 * Versoes dos layouts xsd das funcoes do webservice
	 * NOTA : Estes dados deve ser dinâmicamente carregados pelo sistema
         * com base em um banco de dados para tornar o sistema mais flexível
	 * @var      array
	 * @access   public
	**/
     var $aVerxsd=array(
                    'ConsultaCadastro'=>'1.01',
                    'NfeRecepcao'=>'1.10',
                    'NfeRetRecepcao'=>'1.10',
                    'NfeCancelamento'=>'1.07',
                    'NfeInutilizacao'=>'1.07',
                    'NfeStatusServico'=>'1.07',
                    'NfeConsultaNF'=>'1.07',
                    'CabecMsg'=>'1.02'
                    );


        /**
         * Variaveis passadas para a operaçao do sistema
         *
         */

        /**
         * Codigo da UF da empresa emitente
         * @var string
         * @access public
         */
     var $UFcod='35';
        /**
         * Id da NFe com 47 digitos NFe83737377377373...
         * @var string
         * @access public
         */
     var $Id=''; 

        /**
         * Nome do certificado pfx
         * @var string $nameCert
         * @access public
         */
     var $nameCert='';
        /**
         * Senha da chave privada
         * @var string $passKey
         * @access public
         */
     var $passKey='';
        /**
         * Senha de decriptação da chave privada, se houver
         * Normalmente não é usado
         * @var string $passPhrase
         * @access public
         */
     var $passPhrase='';

        /**
         *  caminhos de acesso aos diretorios de armazenamento das comunicações
         *
        **/

        /**
         * Caminho completo para o diretorio que contêm os certificados,
         * em qualquer formato. Será o local onde serão gerados os certificados
         * em formato pem.
         * @var string $pathCerts
         * @access public
         */
     var $pathCerts='';
        /**
         * Caminho para os schemas xsd que serão utilizados para a validação
         * das NFe ou de mensagens
         * @var string
         * @access publico
         */
     var $pathXSD= ''; 
        /**
         * Caminho para o diretorio de arquivos temporarios.
         * Neste diretorio serão colocados arquivos sem necessidade de backup,
         * como  por exemplo as respostas as consultas de status ao SEFAZ
         * @var string
         * @access public
         */
     var $temporarioNF = '';
        /**
         * Caminho onde são postadas as NFe pelo sistema ERP ou as notas manuais, em xml ou txt
         * para serem assinadas, valclassNFEtoolsidadas e posteriormnte enviadas ao SEFAZ.
         * @var string
         * @access public
         */
     var $entradasNF='';
        /**
         * Caminho para onde são movidas as NFe que já foram assinadas.
         * @var string
         * @access public
         */
     var $assinadasNF='';
        /**
         * Caminho para onde são movidas as NFe assinadas, validadas internamente e já remetidas ao SEFAZ
         * para aprovação.
         * Neste diretorio também srão postadas os retornos do SEFAZ tanto o recibo do envio
         * quanto o retorno da consulta do recibo, que contêm o protocolo de aprovação da NFe
         * @var string
         * @access public
         */
     var $validadasNF='';
        /**
         * Caminho onde são colocadas as NFe retornadas do SEFAZ como aceitas.
         * Estas NFe podem ser impressas DANFE e preparadas para envio ao destinatário
         * @var string
         * @access public
         */
     var $aprovadasNF='';
        /**
         * Caminho onde são mantidas as NFe aprovadas, preparadas e já enviadas ao destinatário
         * @var string
         * @access public
         */
     var $enviadasNF='';
        /**
         * Caminho onde as solicitações de cancelamento e as respostas do cancelamento são armazenadas
         * @var string
         * @access public
         */
     var $canceladasNF='';
        /**
         * Caminho onde as solicitações e as respostas de inutilização são armazenadas
         * @var string
         * @access public
         */
     var $inutilizadasNF='';
        /**
         * Caminho onde são colocadas as NFe qunado são recebidas dos fornecedores
         * @var string
         * @access public
         */
     var $recebidasNF='';
        /**
         * Caminho onde são movidas as NFe recebidas dos fornecedores
         * e já validadas e aprovadas
         * @var string
         * @access public
         */
     var $consultadasNF='';

 
        /**
         *  Retornos de erros do sistema
         */

        /**
         * Estado de erro
         * @var boolean
         * @access public
         */
     var $errorStatus = false;
        /**
         * Mensagem de erro
         * @var string
         * @access public
         */
     var $errorMsg='';
        /**
         * Código do erro
         * @var string
         * @access public
         */
     var $errorCod=''; 

     // retornos dos serviços SEFAZ
        /**
         * Código de retorno do serviço retornado da SEFAZ
         * @var string
         * @access public
         */
     var $cStat = '';
        /**
         * Versão do aplicativo retornado da SEFAZ
         * @var string
         * @access public
         */
     var $verAplic='';
        /**
         * Motivo relacionado ao código de retorno, retornado da SEFAZ
         * @var string
         * @access public
         */
     var $xMotivo = '';
        /**
         * Cbervações retornado da SEFAZ
         * @var string
         * @access public
         */
     var $xObs = '';
        /**
         * Tempo médio de resposta retornado da SEFAZ
         * @var int
         * @access public
        **/
     var $tMed = '1';
        /**
         * Data e hora do retorno da chamada do webservice retornado da SEFAZ
         * @var string
         * @access public
        **/
     var $dhRecbto = '';
        /**
         * Tipo de ambiente da comunicaçao retornado da SEFAZ
         * @var int
         * @access public
         */
     var $tpAmb = ''; 
        /**
         * Número do recibo da SEFAZ
         * @var string
         * @access public
         */
     var $nRec = '';
        /**
         * Código da UF retornado da SEFAZ
         * @var int
         * @access public
         */
     var $cUF = '';
        /**
         * Sigla da UF retornado da SEFAZ
         * @var string
         * @access public
         */
     var $UF = ''; 
        /**
         * Dados de retorno das NFe enviadas no lote retornado da SEFAZ
         * @var array
         * @access public
         */
     var $aNFe = array();
        /**
         * Número inicial de NF inutilizada retornado da SEFAZ
         * @var string
         * @access public
         */
     var $nNFIni = '';
        /**
         * Número final de NF inutilizada retornado da SEFAZ
         * @var string
         * @access public
         */
     var $nNFFin = '';
        /**
         * Número do protocolo retornado do SEFAZ
         * @var string
         * @access public
         */
     var $nProt = '';
        /**
         * Número do modelo de NF retornado do SEFAZ
         * @var int
         * @access public
         */
     var $modelo= '';
        /**
         * Número de serie da NF retornado do SEFAZ
         * @var int
         * @access public
         */
     var $serie = '';
        /**
         * Ano da NF retornado do SEFAZ
         * @var int
         * @access public
         */
     var $ano = '';
        /**
         * Número do CNPJ da empresa emitente retornado pelo SEFAZ
         * @var string
         * @access public
         */
     var $CNPJ = ''; 
        /**
         * Número do CPF da consulta retornado da SEFAZ
         * @var string
         * @access public
         */
     var $CPF = '';
        /**
         * Número da inscriçao estadual retornado da SEFAZ
         *
         * @var string
         * @access public
         */
     var $IE = ''; 
        /**
         * Número do ID da NFe 44 digitos retornado do SEFAZ
         * @var string
         * @access public
         */
     var $chNFe = '';
        /**
         * Digest da assinatura digital da NFe retornado do SEFAZ
         * @var string
         * @access public
         */
     var $digVal='';
        /**
         * Data e hora da consulta retornado do SEFAZ
         *
         * @var string
         * @access public
         */
     var $dhCons = '';
        /**
         * Retorno do SEFAZ na consulta de cadastros retornado da SEFAZ
         * NOTA : Pode não haver este retorno
         * @var array
         * @access public
         */
     var $aCad = array();


     // variaveis relativas ao certificado
        /**
         * Número de meses até a expiração do certificado digital
         * @var int
         * @access public
         */
     var $monthsToExpire=0;
        /**
         * Número de dias até a expiração do certificado digital
         * @var int
         * @access public
         */
     var $daysToExpire=0;

    /**
     * Variáveis Privadas
     */
        /**
         * Caminho completo até o arquivo da chave publica
         * em formato pem
         * @var string $pathCert
         * @access private
         */
     private $pathCert = '';
        /**
         * Caminho completo até o arquivo da chave privada
         * em formato pem
         * @var string $pathKey
         * @access private
         */
     private $pathKey = '';
        /**
         * Resource que contêm a chave privada
         * @var resource $rPrivkey
         * @access private
         */
     private $rPrivkey='';
        /**
         * Resource que contêm a chave publica
         * @var resource $rPubkey
         * @access private
         */
     private $rPubkey='';

        /**
         * Indicadores de schemas utilizados na construçao das mensagens SOAP
         * e na assinatura digital
         *
         */

        /**
         * $URLxsi
         * @var string
         * @access private
         */
     private $URLxsi = 'http://www.w3.org/2001/XMLSchema-instance';
        /**
         * $URLxsd
         * @var string
         * @access private
         */
     private $URLxsd = 'http://www.w3.org/2001/XMLSchema';
        /**
         * $URLnfe
         * @var string
         * @access private
         */
     private $URLnfe = 'http://www.portalfiscal.inf.br/nfe';
        /**
         * $URLdsig
         * @var string
         * @access private
         */
     private $URLdsig = 'http://www.w3.org/2000/09/xmldsig#';
        /**
         * $URLCanonMeth
         * @var string
         * @access private
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
         * @access private
         */
     private $URLTransfMeth_1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
        /**
         * $URLTransfMeth_2
         * @var string
         * @access private
         */
     private $URLTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        /**
         * $URLDigestMeth
         * @var string
         * @access private
         */
     private $URLDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';

        /**
         * Resposta do webservice
         * @var object $response
         * @access private
         */
     private $response;



    /**
     * Metodos Publicos
     */

    /**
     * Método construtor da classe
     * verificar se existe o arquivo de configuração inicial
     * se existir carregas as variáveis de ambiente apartir desse arquivo
     * A ideia (iniciada pelo Djalma) é de não necessitar setar todas as variáveis
     * importantes toda vez que a classe seja invocada. Essas variáveis seriam setadas uma
     * vez e gravadas no diretorio a partir de uma função da classe, tipo um config_inc.php
     *
     * @name __construct
     * @version 1.0
     * @package classNFeTools
     * @todo
     * @param boolean none
     * @return none
     * @access Pivate
     */
    function __construct(){

        $fixfile = getcwd().'/libs/fixConfig.php';
        if (file_exists($fixfile)){
            include($fixfile);
            $this->ambiente = $tpAMB;
            $this->pathCerts = $certDIR;
            $this->nameCert = $nameCERT;
            $this->passKey = $passKEY;
            $this->passPhrase = $passPHRASE;
            $this->entradasNF = $DIRentradas;
            $this->assinadasNF = $DIRassinadas;
            $this->validadasNF = $DIRvalidadas;
            $this->aprovadasNF = $DIRaprovadas;
            $this->enviadasNF = $DIRenviadas;
            $this->canceladasNF = $DIRcanceladas;
            $this->inutilizadasNF = $DIRinutilizadas;
            $this->temporarioNF = $DIRtemporario;
            $this->recebidasNF = $DIRrecebidas;
            $this->consultadasNF = $DIRconsultadas;
        }
       
    }

    //destrutor
    function __destruct(){

        
    }

    /**
     * fixConfig
     * Esta função monta um arquivo de configuração que será utilizada
     * automaticamente na construção da classe para setar as propriedades mais
     * importantes da classe.
     * Deve ser invocada pelo menos uma vez.
     * Para seu uso deve-se antes instanciar todas as propriedades importantes da classe
     *
     * @name fixConfig
     * @version 1.0
     * @package NFePHP
     * @todo 
     * @param boolean $bSave se TRUE salva as propriedades em um arquivo para uso interno da classe se FALSE remove o arquivo
     * @return boolean TRUE se sucesso ou FALSE se falhou
     * @access Public
     */
    function fixConfig($bSave=TRUE){
        $texto = '<?php '."\n";
        $texto .= '$tpAMB ='."'".$this->ambiente."';\n";
        $texto .= '$certDIR='."'".$this->pathCerts."';\n";
        $texto .= '$nameCERT='."'".$this->nameCert."';\n";
        $texto .= '$passKEY='."'".$this->passKey."';\n";
        $texto .= '$passPHRASE='."'".$this->passPhrase."';\n";
        $texto .= '$DIRentradas='."'".$this->entradasNF."';\n";
        $texto .= '$DIRassinadas='."'".$this->assinadasNF."';\n";
        $texto .= '$DIRvalidadas='."'".$this->validadasNF."';\n";
        $texto .= '$DIRaprovadas='."'".$this->aprovadasNF."';\n";
        $texto .= '$DIRenviadas='."'".$this->enviadasNF."';\n";
        $texto .= '$DIRcanceladas='."'".$this->canceladasNF."';\n";
        $texto .= '$DIRinutilizadas='."'".$this->inutilizadasNF."';\n";
        $texto .= '$DIRtemporario='."'".$this->temporarioNF."';\n";
        $texto .= '$DIRrecebidas='."'".$this->recebidasNF."';\n";
        $texto .= '$DIRconsultadas='."'".$this->consultadasNF."';\n";
        $texto .= '?>'."\n";

        $fixfile = getcwd().'/libs/fixConfig.php';

        if ($bSave){
           $n = file_put_contents($fixfile,$texto);
        } else {
            unlink($fixfile);
        }

    }


    /**
     * carregaCert
     * Carrega o certificado pfx e gera as chaves privada e publica no
     * formato pem para uso do SOAP e registra as gvariaveis de ambiente
     * Esta função deve ser invocada enates das outras do sistema que
     * dependam do certificado
     * Resultado
     *  A função irá criar o certificado digital (chaves publicas e privadas)
     *  no formato pem e grava-los no diretorio indicado em $this->pathCerts
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
     * FUNCIONAL !!
     *
     * @name carregaCert
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	none
     * @return	boolean TRUE se o certificado foi carregado e FALSE se nao
     * @access  public
    **/
    public function carregaCert(){
        //verificar se o nome do certificado e
        //o path foram carregados nas variaveis da classe
        if ($this->pathCerts == '' || $this->nameCert == '') {
                $this->errorMsg = 'Um certificado deve ser passado para a classe!!';
                $this->errorCod = 'C1';
                $this->errorStatus = TRUE;
                return FALSE;
        }
        //monta o caminho completo até o certificado pfx
        $pCert = $this->pathCerts.$this->nameCert;
        //verifica se o arquivo existe
        if(!file_exists($pCert)){
                $this->errorMsg = 'Certificado não encontrado!!';
                $this->errorCod = 'C2';
                $this->errorStatus = TRUE;
                return FALSE;
        }
        //carrega o certificado em um string
        $key = file_get_contents($pCert);
        //carrega os certificados e chaves para um array denominado $x509certdata
        if (!openssl_pkcs12_read($key,$x509certdata,$this->passKey) ){
                $this->errorMsg = 'O certificado não pode ser lido!! Provavelmente corrompido ou com formato inválido!!';
                $this->errorCod = 'C3';
                $this->errorStatus = TRUE;
                return FALSE;
        }
        //verifica sua validade
        if ( !$this->validCert($x509certdata['cert']) ){
                $this->errorMsg = 'Certificado invalido!!';
                $this->errorCod = 'C4';
                $this->errorStatus = TRUE;
                return FALSE;
        }
        //carrega a chave privada em um resource para uso do assinador
        $this->rPrivkey = openssl_pkey_get_private($x509certdata['pkey']);
        //carrega o certificado em um resource para uso do assinador
        $this->rPubkey = openssl_pkey_get_public($x509certdata['cert']);
        //monta o path completo com o nome da chave privada
        $filePriv = $this->pathCerts.'privatekey.pem';
        //verifica se arquivo já existe
        if(file_exists($filePriv)){
            //se existir verificar se é o mesmo
            $conteudo = file_get_contents($filePriv);
            //comparar os primeiros 30 digitos
            if ( !substr($conteudo,0,30) == substr($x509certdata['pkey'],0,30) ) {
                 //se diferentes gravar o novo   
                if (!file_put_contents($filePriv,$x509certdata['pkey']) ){
                    $this->errorMsg = 'Impossivel gravar no diretório!!! Permissão negada!!';
                    $this->errorCod = 'F1';
                    $this->errorStatus = TRUE;
                    return FALSE;
                }
            }
        } else {
            //salva a chave privada no formato pem para uso so SOAP
            if ( !file_put_contents($filePriv,$x509certdata['pkey']) ){
                   $this->errorMsg = 'Impossivel gravar no diretório!!! Permissão negada!!';
                   $this->errorCod = 'F1';
                   $this->errorStatus = TRUE;
                   return FALSE;
            }
        }    
        //monta o path completo com o nome da chave prublica
        $filePub =  $this->pathCerts.'publickey.pem';
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
            //salva a chave prublica no formato pem para uso so SOAP
            $n = file_put_contents($filePub,$x509certdata['cert']);
        }
        //verifica que as propriedades do ambinte sejam setadas
        $this->pathCert = $filePub;
        $this->pathKey = $filePriv;
        return TRUE;
    }

    /**
     * Validaçao do cerificado digital, alem de indicar
     * a validade este metodo carrega a propriedade
     * mesesToexpire da classe quer indica o numero de
     * meses que faltam para expirar a validade do certificado
     * esta informacao pode ser utilizada para a gestao dos
     * certificados de forma a garantir que sempre estejam validos
     * FUNCIONAL !!
     *
     * @name validCert
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	string  $cert Certificado digital no formato pem
     * @return	boolean	True se o certificado estiver dentro do prazo de validade, e False se nao
     * @access  public
    **/
    public function validCert($cert){
        $flagOK = true;
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
            $this->errorStatus = TRUE;
            $this->errorMsg = "Erro Certificado:  A Validade do certificado expirou em ["  . $dia.'/'.$mes.'/'.$ano . "] INVALIDO !!";
            $this->errorCod = 'C3';
        } else {
            $flagOK = $flagOK && TRUE;
        }
        //diferença em segundos entre os timestamp
        $diferenca = $dValid - $dHoje;
        // convertendo para dias
        $diferenca = round($diferenca /(60*60*24),0);
        //carregando a propriedade
        $this->daysToExpire = $diferenca;
        // convertendo para meses e carregando a propriedade
        $m = ($ano * 12 + $mes);
        $n = (date("y") * 12 + date("m"));
        $this->monthsToExpire = ($m-$n);
        return $flagOK;
    }

    /**
     * Assinador TOTALMENTE baseado em PHP das NFe e xmls
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * FUNCIONAL !!!! resulta em arquivo idêntico ao assinadorRS
     * Resultado
     *      o arquivo xml com a assinatura será salvo em
     *      $outDir ou $this->assinadasNF, nessa ordem  e
     *      também retornada como uma string pela função ao chamador
     *      O arquivo tem sua denominação estabelecida como :
     *      ID-NFe.xml
     *  Onde o ID é o identificador de 44 digitos numéricos da NFe (sem a sigla NFe)
     *          ex. 35090671780456000160550010000000010000000017-nfe.xml
     *
     * Dependência
     *      carregaCert()
     *
     * @name assina
     * @version 1.1
     * @package NFePHP
     * @todo
     * @param	string $docxml
     * @param   string $tagid TAG que devera ser assinada
     * @return	mixed FALSE se houve erro ou string com o XML assinado
     * @access  public
    **/
    public function assina($docxml, $tagid='', $outDir=''){
            if ( $tagid == '' ){
                $this->errorMsg = 'Uma tag deve ser indicada para que seja assinada!!';
                $this->errorCod = 'A1';
                $this->errorStatus = TRUE;
                return FALSE;
            }

            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->limpaCert();
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
            $resp = openssl_sign($dados,$signature,$this->rPrivkey);
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
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);
            //grava na string o objeto DOM
            $docxml = $xmldoc->saveXML();
            //verifica o tipo de arquivo para assinar e estabelece o sulfixo para gravar o xml
            if ($tagid == 'infNFe'){
                $sulfix = '-nfe.xml';
            } else {
                if ($tagid == 'infInut') {
                    $sulfix = '-pedinut.xml';
                } else {
                    $sulfix = '-pedcanc.xml';
                }
            }
            //se for passado parametro de destino salvar o xml como arquvo
            if ($outDir != ''){
                $outname = $outDir.$idnome.$sulfix;
                $ret = $xmldoc->save($outname);
            } else {
                //verificar a propriedade da classe assinadasNF
                if ($this->assinadasNF != ''){
                    $outname = $this->assinadasNF.$idnome.$sulfix;
                    $ret = $xmldoc->save($outname);
                }
            }
            return $docxml;
    }


    
    /**
     * Verificaçao da NF com base no xsd
     * Há um bug no libxml2 para versões anteriores a 2.7.3
     * que causa um falso erro na validação da NFe devido ao
     * uso de uma marcação no arquivo tiposBasico_v1.02.xsd
     * onde se le {0 , } substituir por *
     * FUNCIONAL !!
     *
     * @name validaXML
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	string  $docxml  string contendo o arquivo xml a ser avaliado
     * @param   string  $xsdfile Path completo para o arquivo xsd
     * @return	boolean TRUE se passou ou FALSE se foram detectados erros
     * @access  public
    **/
    public function validaXML($docxml, $xsdfile){
        
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);

        // instancia novo objeto DOM
        $xmldoc = new DOMDocument();

        // carrega o xml
        $xml = $xmldoc->loadXML($docxml);

        $erromsg='';

        // valida o xml com o xsd
        if ( !$xmldoc->schemaValidate($xsdfile) ) {
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
                        $erromsg .= " Atençao $intError->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $erromsg .= " Erro $intError->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $erromsg .= " Erro Fatal $intError->code: ";
                        break;
                }
                $erromsg .= $intError->message . ';';
            }
        } else {
            $flagOK = TRUE;
            $this->errorStatus = FALSE;
            $this->errorMsg = '';
        }

        if (!$flagOK){
            $this->errorStatus = TRUE;
            $this->errorMsg = $erromsg;
        }
        return $flagOK;
    }





    /**
     * Verifica o status do servico da SEFAZ
     * 
     * Este metodo carrega a variavel
     * $this->cStat = 107 OK
     *        cStat = 108 sitema paralizado momentaneamente, aguardar retorno
     *        cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     * se SCAN estiver ativado usar, caso caontrario aguardar pacientemente.
     * 
     *
     * FUNCIONAL !!
     *
     * @name statusServico
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	boolean $bSave Indica se o xml da resposta deveser salvo em arquivo
     * @return	boolean True se operacional e False se nao
     * @access  public
    **/
    public function statusServico($bSave=TRUE){
        //retorno da funçao 
        $bRet = FALSE;
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeStatusServico';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];
        // array para comunicaçao soap
        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consStatServ xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'">'.'<tpAmb>'.$this->ambiente.'</tpAmb><cUF>'.$this->UFcod.'</cUF><xServ>STATUS</xServ></consStatServ>'
        );
        //envia o xml para o SOAP
        $retorno = $this->sendSOAP($param, $wsdl);
        //verifica o retorno do SOAP
        if (is_array($retorno)) {
            //pega os dados do array retornado pelo NuSoap
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
            // status do serviço
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // tempo medio de resposta
            $this->tMed = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
            // data e hora da mensagem
            $this->dhRecbto = $doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            //converter a informaçao de data hora para timestamp
            $this->dhRecbto = $this->convertTime($this->dhRecbto);
            // motivo da resposta (opcional)
            $this->xMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // obervaçoes opcional
            $this->xObs = $doc->getElementsByTagName('xObs')->item(0)->nodeValue;
            if ($this->cStat == '107'){
                $bRet = TRUE;
            }
            if ($bSave){
                //nome do arquivo de retorno da função SOAP
                $nome = $this->temporarioNF.date('Ymd').'T'.date('His').'-sta.xml';
                //salva o xml retornado na pasta temporarioNF
                $doc->save($nome);
            }
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }


    /**
     * Solicita dados de situaçao de Cadastro
     * Não FUNCIONA !! Não sei porque
     *
     * @name consultaCadastro
     * @version 1.1
     * @package NFePHP
     * @todo Verificar o motivo da falha na obtenção de resposta da SEFAZ
     * @param	string  $UF
     * @param   string  $IE
     * @param   string  $CNPJ
     * @param   string  $CPF
     * @param   boolean $bSave indica se o xml retornado deve ser salvo em arquivo
     * @return	boolean TRUE se sucesso ou FALSE se falha
     * @access  public
     **/
    public function consultaCadastro($UF,$IE='',$CNPJ='',$CPF='',$bSave=TRUE){
        //variavel de retorno do metodo
        $bRet = FALSE;
        //variaveis do webservice
        $wsdl = 'ConsultaCadastro';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

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
            $this->errorStatus = TRUE;
            $this->errorMsg = 'Um filtro deve ser indicado CNPJ, CPF ou IE !!!';
            return FALSE;
        }
        //preparação da mensagem SOAP
        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>1.07</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<ConsCad versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'"><infCons><xServ>CONS-CAD</xServ><UF>'.$UF.'</UF>'.$filtro.'</infCons></ConsCad>'
        );

        $x = file_put_contents($this->temporarioNF.'conscad.xml',$param);
        //envio da mensagem ao webservice
        $retorno = $this->sendSOAP($param, $wsdl);
        //se houve retorno
        if (is_array($retorno)) {
            //pegar o xml retornado do NuSoap
            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }
            // tratar dados de retorno
            $doc = new DOMDocument(); 
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //infCons o xml somente contera um grupo com essa tag
            $this->verAplic = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->xMotivo= $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $this->UF= $doc->getElementsByTagName('UF')->item(0)->nodeValue;
            $this->IE= $doc->getElementsByTagName('IE')->item(0)->nodeValue;
            $this->CNPJ= $doc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
            $this->CPF= $doc->getElementsByTagName('CPF')->item(0)->nodeValue;
            $this->dhCons= $doc->getElementsByTagName('dhCons')->item(0)->nodeValue;
            $this->cUF = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
            // se foi encontrado cStat = 111 ou 112 com varios estabelecimento com o mesmo IE
            if ($this->cStat == '111' || $this->cStat == '112') {
                $bRet = TRUE;
                $n = 0;
                //pode haver mais de um dado retornado
                $infCad = $doc->getElementsByTagName('infCad');
                foreach ($infCad as $iCad) {
                    $IE = $iCad->getElementsByTagName('IE')->item(0)->nodeValue;
                    $CNPJ = $iCad->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    $CPF = $iCad->getElementsByTagName('CPF')->item(0)->nodeValue;
                    $UF = $iCad->getElementsByTagName('UF')->item(0)->nodeValue;
                    $cSit = $iCad->getElementsByTagName('cSit')->item(0)->nodeValue;
                    $xNome = $iCad->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $xFant = $iCad->getElementsByTagName('xFant')->item(0)->nodeValue;
                    $xRegApur = $iCad->getElementsByTagName('xRegApur')->item(0)->nodeValue;
                    $CNAE = $iCad->getElementsByTagName('CNAE')->item(0)->nodeValue;
                    $dIniAtiv = $iCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue;
                    $dUltSit = $iCad->getElementsByTagName('dUltSit')->item(0)->nodeValue;
                    $dBaixa = $iCad->getElementsByTagName('dBaixa')->item(0)->nodeValue;
                    $IEUnica = $iCad->getElementsByTagName('IEUnica')->item(0)->nodeValue;
                    $IEAtual = $iCad->getElementsByTagName('IEAtual')->item(0)->nodeValue;
                    $xLgr = $iCad->getElementsByTagName('xLgr')->item(0)->nodeValue;
                    $nro = $iCad->getElementsByTagName('nro')->item(0)->nodeValue;
                    $xCpl = $iCad->getElementsByTagName('xCpl')->item(0)->nodeValue;
                    $xBairro = $iCad->getElementsByTagName('xBairro')->item(0)->nodeValue;
                    $cMun = $iCad->getElementsByTagName('cMun')->item(0)->nodeValue;
                    $xMun = $iCad->getElementsByTagName('xMun')->item(0)->nodeValue;
                    $CEP = $iCad->getElementsByTagName('CEP')->item(0)->nodeValue;
                    $this->aCad = array($n,array('IE'=>$IE,'CNPJ'=>$CNPJ,'CPF'=>$CPF,'UF'=>$UF,'cSit'=>$cSit,'xNome'=>$xNome,'xFant'=>$xFant,'xRegApur'=>$xRegApur,'CNAE'=>$CNAE,'dIniAtiv'=>$dIniAtiv,'dUltSit'=>$dUltSit,'dBaixa'=>$dBaixa,'IEUnica'=>$IEUnica,'IEAtual'=>$IEAtual,'xLgr'=>$xLgr,'nro'=>$nro,'xCpl'=>$xCpl,'xBairro'=>$xBairro,'cMun'=>$cMun,'xMun'=>$xMun,'CEP'=>$CEP));
                    $n ++;
                }
            }
            if($bSave){
                //salvar o xml retornado do SEFAZ
                $nome = $this->temporarioNF.$marca.'.xml';
                $nome = $doc->save($nome);
            }
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }


    /**
     * Envia lote de Notas Fiscais
     *
     * @name enviaNF
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
    public function enviaNF($aNFe=array(),$idLote='1',$bSave=TRUE){
        //variavel de retorno do metodo
        $bRet = false;

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
     * Solicita resposta do lote de Notas Fiscais
     * FUNCIONAL!!
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
    public function retornoNF($recibo, $bSave=TRUE){

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

        $retorno = $this->sendSOAP($param, $wsdl);

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
     * Solicita inutilizaçao de uma serie de numeros de NF
     * FUNCIONAL !!
     *
     * @name inutilizaNF
     * @version 1.0
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
    public function inutilizaNF($ano,$nfSerie,$modelo,$numIni,$numFim,$xJust,$bSave=TRUE){
        //variavel de retorno
        $bRet = FALSE;
        // carga das variaveis da funçao do webservice
        $wsdl='NfeInutilizacao';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];
        
        //Identificador da TAG a ser assinada formada
        //com Código da UF + CNPJ + modelo + série +
        //nro inicial e nro final precedida do literal “ID”
        $id = 'ID'.$this->UFcod.$this->CNPJ.$modelo.$nfSerie.$numIni.$numFim;
        //dados da mensagem
        $nfeDadosMsg = '<inutNFe xmlns="'.$this->URLnfe.'" versao="'.$dataVer.'"><infInut Id="'.$id.'"><tpAmb>'.$this->ambiente.'</tpAmb><xServ>INUTILIZAR</xServ><cUF>'.$this->UFcod.'</cUF><ano>'.$ano.'</ano><CNPJ>'.$this->CNPJ.'</CNPJ><mod>'.$modelo.'</mod><serie>'.$nfSerie.'</serie><nNFIni>'.$numIni.'</nNFIni><nNFFin>'.$numFim.'</nNFFin><xJust>'.$xJust.'</xJust></infInut></inutNFe>';

        //assinar a nfeDadosMsg
        $nfeDadosMsg = $this->assina($nfeDadosMsg, 'infInut', $this->inutilizadasNF);

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>$nfeDadosMsg
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            $this->versao = $doc->getElementsByTagName('versao')->item(0)->nodeValue;
            $infInut= $doc->getElementsByTagName('infInut')->item(0);
            //extrai o id da tag
            $id = trim($infInut->getAttribute("Id"));

            $this->tpAmb = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $this->verAplic = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->xMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $this->cUF = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;

            if ($this->cStat == '102') {
                $bRet = TRUE;
                $this->ano = $doc->getElementsByTagName('ano')->item(0)->nodeValue;
                $this->CNPJ = $doc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                $this->modelo = $doc->getElementsByTagName('mod')->item(0)->nodeValue;
                $this->serie = $doc->getElementsByTagName('serie')->item(0)->nodeValue;
                $this->nNFIni = $doc->getElementsByTagName('nNFIni')->item(0)->nodeValue;
                $this->nNFFin = $doc->getElementsByTagName('nNFFin')->item(0)->nodeValue;
                $this->dhRecbto = $doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                $this->nProt = $doc->getElementsByTagName('nProt')->item(0)->nodeValue;
            }

            if ($bSave){
                //salvar o xml retornado do SEFAZ
                $nome = $this->inutilizadasNF.$id.'-inut.xml';
                $nome = $doc->save($nome);
            }

        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }

    /**
     * Solicita o cancelamento de NF enviada
     * FUNCIONAL !!!
     *
     * @name cancelaNF
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	string  $idNFe ID da NFe com 44 digitos (sem o NFe na frente dos numeros)
     * @param   string  $protId Numero do protocolo de aceitaçao da NFe enviado anteriormente pelo SEFAZ
     * @param   boolean $bSave
     * @return	boolean TRUE se sucesso ou FALSE se falha
     * @access  public
    **/
    public function cancelaNF($idNFe,$protId, $xJust, $bSave=TRUE){
        //variavel de retorno
        $bRet = FALSE;
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeCancelamento';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];


        $nfeDadosMsg = '<cancNFe xmlns="'.$this->URLnfe.'" versao="'.$dataVer.'"><infCanc Id="ID'.$idNFe.'"><tpAmb>'.$this->ambiente.'</tpAmb><xServ>CANCELAR</xServ><chNFe>'.$idNFe.'</chNFe><nProt>'.$protId.'</nProt><xJust>'.$xJust.'</xJust></infCanc></cancNFe>';
        $nfeDadosMsg = $this->assina($nfeDadosMsg, 'infCanc', $this->canceladasNF);
        
        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>$nfeDadosMsg
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            $this->versao = $doc->getElementsByTagName('versao')->item(0)->nodeValue;
            $infCanc = $doc->getElementsByTagName('infCanc')->item(0);
            //extrai o id da tag
            $id = trim($infCanc->getAttribute("Id"));

            $this->tpAmb = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $this->verAplic = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->xMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $this->cUF = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;

            if ( $this->cStat == '101' ) {
                $bRet = TRUE;
                $this->chNFe = $doc->getElementsByTagName('chNFe')->item(0)->nodeValue;
                $this->dhRecbto = $doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                $this->nProt = $doc->getElementsByTagName('nProt')->item(0)->nodeValue;
            }

            if($bSave){
                //salvar o xml retornado do SEFAZ
                $nome = $this->canceladasNF.$id.'-canc.xml';
                $nome = $doc->save($nome);
            }

        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }

    /**
     * Solicita dados de situaçao de NF
     * FUNCIONAL !!
     *
     * @name consultaNF
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	string   $idNFe numerico com 44 digitos
     * @param   boolean  $bSave
     * @return	mixed	response from SOAP call
     * @access  public
     **/
    public function consultaNF($idNFe, $bSave=TRUE){
        //variavelde retorno do metodo
        $bRet = FALSE;
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeConsultaNF';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consSitNFe xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'"><tpAmb>'.$this->ambiente.'</tpAmb><xServ>CONSULTAR</xServ><chNFe>'.$idNFe.'</chNFe></consSitNFe>'
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                return FALSE;
            }

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $this->tpAmb = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $this->verAplic = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->xMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $this->cUF = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;

            if ( $this->cStat == '100' || $this->cStat == '101' || $this->cStat == '110' ) {
                $bRet = TRUE;
                $this->chNFe = $doc->getElementsByTagName('chNFe')->item(0)->nodeValue;
                $this->digVal = $doc->getElementsByTagName('digVal')->item(0)->nodeValue;
                $this->dhRecbto = $doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                $this->nProt = $doc->getElementsByTagName('nProt')->item(0)->nodeValue;
            }
            if($bSave){
                //salvar o xml retornado do SEFAZ
                $nome = $this->consultadasNF.$idNFe.'-sit.xml';
                $nome = $doc->save($nome);
            }
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }
    

    /**
     * Gera arquivo pdf para impressao de NF-e
     * Requer danfe.class.php
     *
     * @name imprimeNF
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param	string  $xml
     * @param   string  $formato P=portrait L=landscape
     * @param   sring   $path_logomarca
     * @param   string  $protocolo
     * @param   string  $data_hora
     * @return
     * @access  public
     * @author Djalma Fadel Junior
     **/
    public function imprimeNF($xml, $formato="P", $path_logomarca="", $protocolo="", $data_hora=""){
        include_once ('danfe.class.php');
        $danfe = new danfe($xml, $formato);
        $danfe->protocolo = $protocolo;
        $danfe->data_hora = $data_hora;
        if (!empty($path_logomarca)) {
            $danfe->logomarca = $path_logomarca;
        }
        return $danfe->gera();
    }


    /**
     * Métodos Privados da Classe
     */

    /**
     * Estabelece comunicaçao com servidor SOAP
     * FUNCIONAL !!!
     * 
     * @name sendSOAP
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param    array   $param Matriz com o cabeçalho e os dados da mensagem soap
     * @param    string  $wsdl Designaçao do Serviço SOAP
     * @return   mixed  Array com a resposta do SOAP ou String do erro ou false
     * @access   private
     **/
    private function sendSOAP($param,$wsdl){

        try {

            //monta a url do serviço
            $URL = $this->aURLwsdl[$wsdl].'?WSDL';
            //inicia a conexao SOAP
            $client = new nusoap_client($URL, true);
            $client->authtype         = 'certificate';
            $client->soap_defencoding = 'UTF-8';

            //Seta parametros para a conexao segura
            $client->certRequest['sslkeyfile']  = $this->pathKey;
            $client->certRequest['sslcertfile'] = $this->pathCert;
            $client->certRequest['passphrase']  = $this->passPhrase;
            $client->certRequest['verifypeer']  = false;
            $client->certRequest['verifyhost']  = false;
            $client->certRequest['trace']       = 1;
        }

        //em caso de erro retorne o mesmo
        catch (Exception $ex) {
            if (is_bool($client->getError())){
                $this->errorStatus = False;
                $this->errorMsg = '';
            } else {
                $this->errorStatus = True;
                $this->errorMsg = $client->getError();
            }

        }

        // chama a funçao do webservice, passando os parametros
        $result = $client->call($this->aFunctionWdsl[$wsdl], $param);
        $this->debug_str = htmlspecialchars($client->debug_str);
        // retorna o resultado da comunicaçao
        return $result;
    }


    /**
     * Converte o campo data time retornado pelo webservice
     * em um timestamp unix
     *
     * @name convertTime
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param    string   $DH
     * @return   timestamp
     * @access   private
     **/
    private function convertTime($DH){
        if ($DH){
            $aDH = split('T',$DH);
            $adDH = split('-',$aDH[0]);
            $atDH = split(':',$aDH[1]);
            $timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);
            return $timestampDH;
        }
    }

    /**
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura
     *
     * @name limpaCert
     * @version 1.0
     * @package NFePHP
     * @todo
     * @param    none
     * @return   string contendo a chave digital limpa
     * @access   private
     **/
    private function limpaCert(){
        //carregar a chave publica do arquivo pem
        $pubKey = file_get_contents($this->pathCert);
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

}
?>
