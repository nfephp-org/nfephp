<?php
/**
 *
 * NFEclass
 *
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @version  0.2 (alfa teste)
 * @access   public
 *
 * ================================================
 * Asinador funcional 2009-07-04
 *
 *
 *
 *
**/

require_once('./libs/nusoap/nusoap.php');
require_once('./libs/fpdf/fpdf.php');
require_once('./libs/xmlseclibs.php');

define('NFEclassVer','0.1');
define('HOMOLOGACAO','2');
define('PRODUCAO','1');
define('CONTINGENCIAHOMOLOGACAO','4');
define('CONTINGENCIAPRODUCAO','3');


class NFEclass {

    //*************************
    // Propriedades
    //*************************

    /**
	 * Ambiente de conexao com o webservie
	 *
	 * @var string
	 * @access public
	**/
    var $ambiente = "2"; //homologaçao

    /**
	 * URLs dos webservice
	 *
	 * @var array
	 * @access public
	**/
    var $aURLwsdl=array(
                    'ConsultaCadastro'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/cadconsultacadastro.asmx',
                    'NfeRecepcao'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nferecepcao.asmx',
                    'NfeRetRecepcao'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nferetrecepcao.asmx',
                    'NfeCancelamento'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfecancelamento.asmx',
                    'NfeInutilizacao'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfeinutilizacao.asmx',
                    'NfeStatusServico'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfestatusservico.asmx',
                    'NfeConsultaNF'=>'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfeconsulta.asmx'
                    );

	/**
	* Funçoes do webservice
	*
	* @var      array
	* @access   public
	**/
    var $aFunctionWdsl=array(
                    'ConsultaCadastro'=>'CadConsultaCadastro',
                    'NfeRecepcao'=>'nfeRecepcaoLote',
                    'NfeRetRecepcao'=>'nfeRetRecepcao',
                    'NfeCancelamento'=>'nfeCancelamentoNF',
                    'NfeInutilizacao'=>'nfeInutilizacaoNF',
                    'NfeStatusServico'=>'nfeStatusServicoNF',
                    'NfeConsultaNF'=>'nfeConsultaNF'
                    );

	/**
	* Arquivos xsd das funcoes do webservice
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
	*
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

     var $URLxsi = 'http://www.w3.org/2001/XMLSchema-instance';
     var $URLxsd = 'http://www.w3.org/2001/XMLSchema';
     var $URLnfe = 'http://www.portalfiscal.inf.br/nfe';
     var $URLdsig='http://www.w3.org/2000/09/xmldsig#';
     var $response; //resposta do webservice


     var $UFcod = '35'; //codigo da UF da empresa emitente
     var $Id = ''; //Id da NFe com 47 digitos NFe83737377377373...


     // caminhos de acesso
     var $pathCertP12='';//caminho para o certificado p12
     var $pathCert = ''; //caminho da chave publica
     var $pathKey = ''; //caminho da chave privada
     var $passKey = ''; //senha da chave privada
     var $passPhrase=''; //senha de decriptaçao da chave privada, se houver
     var $pathNFe = ''; //caminho da NFe
     var $tmpDir = '/var/www/NFe/NFeFiles/tempFiles/';
     
     // retornos de erros do sistema
     var $errorStatus = false; //estado de erro
     var $errorMsg=array(); //mensagem de erro
     var $errorCod=array(); //codigo do erro

     // retornos dos serviços SEFAZ
     var $cStat = ''; //codigo de retorno do serviço
     var $verAplic=''; //versao do aplicativo
     var $xMotivo = ''; //motivo da parada ou funcionamento do servico
     var $xObs = ''; //obervaçoes enviadas pelo SEFAZ
     var $tMed = '1'; //tempo medio de resposta do SEFAZ
     var $dhRecbto = ''; //data e hora do retornoo da chamada do webservice
     var $tpAmb = ''; //tipo de ambiente da comunicaçao
     var $nRec = ''; //numero do recibo da SEFAZ
     var $cUF = ''; //codigo da UF retornado da SEFAZ
     var $UF = ''; //sigla da UF retornado da SEFAZ
     var $aNFe = array(); //dados de aceitaçao das NFe enviadas no lote
     var $nNFIni = ''; // numero inicial de NF inutilizada
     var $nNFFin = ''; // numero final de NF inutilizada
     var $nProt = ''; //numero do protocolo retornado do SEFAZ
     var $modelo= ''; //numero do modelo de NF retornado do SEFAZ
     var $serie = ''; // numero de serie da NF retornado do SEFAZ
     var $ano = ''; //ano da NF retornado do SEFAZ
     var $CNPJ = ''; //numero do CNPJ da empresa emitente pode ser retornado do SEFAZ
     var $CPF = ''; //numero do CPF utilizado na consulta retornado do SEFAZ
     var $IE = ''; //numero da inscriçao estadual utilizado na consulta retornado do SEFAZ
     var $chNFe = ''; //numero do ID da NFe 44 digitos retornado do SEFAZ
     var $digVal=''; //digest da assinatura digital da NFe retornado do SEFAZ
     var $dhCons = ''; //data e hora da consulta retornado do SEFAZ
     var $aCad = array(); //retorno do SEFAZ na consulta de cadastros

     // variaveis relativas ao certificado
     var $monthsToExpire=0; //numero de meses ate a expiraçao do certificado digital
     var $daysToExpire=0; //numero de dias ate a expiraçao do certificado digital

    //*************************
    // Variaveis Privadas
    //*************************


    //*************************
    // Metodos Publicos
    //*************************


    //construtor
    function __construct(){

       
    }

    //destrutor
    function __destruct(){

        
    }

    /**
     * Assinador TOTALMENTE baseado em PHP das NFe
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * FUNCIONAL !!!! resulta em arquvo identico ao assinadorRS
     *
     * @param	string $nfe
     * @param   string $tagid TAG que devera ser assinada
	 * @return	string XML assinada
	 * @access  public
    **/
    function assinaPHPNF($nfe, $tagid='infNFe', $outDir=''){
            //carrega as chaves do certifciado p12
            $key = file_get_contents($this->pathCertP12);
            $resp = openssl_pkcs12_read ($key,$x509certdata,'foam');
            $rPrivkey = openssl_pkey_get_private($x509certdata['pkey']);
            $rPubkey =  openssl_pkey_get_public($x509certdata['cert']);

            $certX509= '';
            $data = '';
            $arCert = explode("\n", $x509certdata['cert']);
            foreach ($arCert AS $curData) {
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                    $data .= trim($curData);
                }
            }
            // certificado que sera incluso no xml
            $certX509 = $data;

            // limeza do xml com a retirada dos CR e LF
            $order = array("\r\n", "\n", "\r");
            $replace = '';
            $nfe = str_replace($order, $replace, $nfe);
            // carrega o documento no DOM
            $xmldoc = new DOMDocument();
            $xmldoc->preservWhiteSpace = FALSE; //elimina espaços em branco
            $xmldoc->formatOutput = FALSE;
            // muito importante deixar ativadas as opçoes para limpar os espacos em branco
            // e as tags vazias
            $xmldoc->loadXML($nfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //$xmldoc->normalizeDocument();


            //extrair a tag com os dados a serem assinados
            $infNFe = $xmldoc->getElementsByTagName('infNFe')->item(0);
            $id = trim($infNFe->getAttribute("Id"));
            $outname = $outDir.$id.'.xml';
            //extrai os dados da tag para uma string
            $dados = $infNFe->C14N(FALSE,FALSE,NULL,NULL);
            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,TRUE);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $templsign = '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI="#'.$id.'"><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" /><Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" /><DigestValue>'.$digValue.'</DigestValue></Reference></SignedInfo><SignatureValue></SignatureValue><KeyInfo><X509Data><X509Certificate>'.$certX509.'</X509Certificate></X509Data></KeyInfo></Signature>';
            //salva o xml normalizado
            $nfe = $xmldoc->saveXML();
            //remove a tag de fechamento final da NFe </NFe>
            $nfe = substr($nfe,0,-7);
            //acrescenta o template da assinatura
            $nfe = $nfe . $templsign.'</NFe>';

            // limpa algumas variaveis da memoria
            unset($xmldoc);
            unset($dados);
            unset($hashValue);
            unset($digValue);
            unset($id);
            unset($infNFe);
            unset($order);
            unset($replace);
            unset($templsign);
            unset($data);

            //carrega novamente o DOM com a NFe
            $xmldoc = new DOMDocument();
            $xmldoc->preservWhiteSpace = FALSE; //elimina espaços em branco
            $xmldoc->formatOutput = FALSE;
            $xmldoc->loadXML($nfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //obtem a tag com as informaçoes da assinatura
            // esta informaçoes e que serao assinadas
            $SignedInfo = $xmldoc->getElementsByTagName('SignedInfo')->item(0);
            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(FALSE,FALSE,NULL,NULL);
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            $resp = openssl_sign($dados,$signature,$rPrivkey);
            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);
            $SignValue = $xmldoc->getElementsByTagName('SignatureValue')->item(0);
            $SignValue->nodeValue = $signatureValue;
            $nfe = $xmldoc->saveXML();

            //se for passado parametro de destino salvar o xml como arquvo
            if ($outdir != ''){
                $ret = $xmldoc->save($outname);
            }
            return $nfe;
    }



    /**
     * Assinador Alternativo das NFe
     * este assinador utilizao xmsec1 para assinar
     *
     * @param	string $nfe
     * @param   string $tagid TAG que devera ser assinada
	 * @return	string XML assinada
	 * @access  public
    **/
    function assinaAlterNF($nfe, $tagid='infNFe', $outDir='/var/www/NFe/NFeFiles/assinadasNF/'){
        
        $tmpINname  = tempnam($this->tmpDir , 'in');
        if (file_exists($tmpINname)) {
                unlink($tmpINname);
        }
        $tmpINname  = $tmpINname.'.xml';
        
        //limpa o arquivo dos LF e CR
        $nfe = preg_replace('/[\n\r\t]/', '', $nfe);

        //extrai o Id da NFe
        $xmldoc = new DOMDocument(); //inicia objeto DOM
    	$xmldoc->preservWhiteSpace = FALSE; //elimina espaços em branco
        $xmldoc->formatOutput = FALSE;
        $xmldoc->loadXML($nfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $infNFe = $xmldoc->getElementsByTagName('infNFe')->item(0); //cria um objeto DOM com o conteudo do node infNFe
        $id = trim($infNFe->getAttribute("Id")); //extrai o id da NF, será necessário adiante no node da assinatura
        $this->Id = $id;

        unset($infNFe); // limpa a variável

        $nfe = $xmldoc->saveXML();

        $tmpOUTname = $outDir . $id . '.xml';
        if (file_exists($tmpOUTname)) {
                unlink($tmpOUTname);
        }


        //limpa o arquivo dos LF e CR
       	$order = array("\r\n", "\n", "\r");
    	$replace = '';
        $nfe = str_replace($order, $replace, $nfe);

        //$nfe = preg_replace('/[\n\r\t]/', '', $nfe);
        
        // extrai o certificado
        $pkcs12 = file_get_contents($this->pathCertP12);
        $certs = array();
        $bResp = openssl_pkcs12_read($pkcs12, &$certs, $this->passKey);
        $certX509= '';
        $data = '';
        $arCert = explode("\n", $certs['cert']);
        foreach ($arCert AS $curData) {
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
            $data .= trim($curData)."\n";
            }
        }
        $certX509 = $data;
        $certX509 = substr($certX509,0,-1);

        //criação do template
        $tplsign = '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI="#' . $id . '"><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" /><Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" /><DigestValue/></Reference></SignedInfo><SignatureValue/><KeyInfo><X509Data><X509Certificate>'.$certX509.'</X509Certificate></X509Data></KeyInfo></Signature>';

        // remove a finalizaçao da tag </NFe> para inserir o template
        $nfe = substr($nfe,0,-6);
        $nfe = $nfe . $tplsign.'</NFe>';

        // salva a NFe com o template para a assinatura
        file_put_contents($tmpINname,$nfe);

      	//monta o comando para assinar
		$cmd = "xmlsec1 sign --id-attr:Id $tagid --output $tmpOUTname --pkcs12 $this->pathCertP12 --privkey $this->pathKey --pwd $this->passKey $tmpINname 2>&1";

        //executa o comando na shell e retorna o resultado em $read
        $read = shell_exec($cmd);
        
        if (file_exists($tmpINname)) {
                unlink($tmpINname);
        }

        return $tmpOUTname;

    }



    /**
     * Assinador das NFe
     *
     * @param	string  $nfe
     * @param   string $tagid TAG que devera ser assinada
	 * @return	string XML assinada
	 * @access  public
    **/
    function assinaNF($nfe, $tagid='infNFe',$outDir='/var/www/NFe/NFeFiles/assinadasNF/'){

      //cria objeto DOM
        $doc = new DOMDocument();
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;

        //carrega a NFe no objeto DOM
        $doc->loadXML($nfe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        //carrega o node que sera assinado
        $infNFe = $doc->getElementsByTagName($tagid)->item(0);

        //extrai o id da tag sera usado
        $id = trim($infNFe->getAttribute("Id"));
        // registra o Id na variavel de ambiente
        $this->Id = $id;

        // cria objeto de assinatura digital da lib xmlseclibs.php
        // esse objeto e que sera assinado
        $objDSig = new XMLSecurityDSig();

        // estabelece o metodo de canonizaçao
        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);

        //acrescenta a referencia, o node infNFe, os transforms e as opçoes
        $objDSig->addReference($infNFe, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature','http://www.w3.org/TR/2001/REC-xml-c14n-20010315'),array('prefix'=>'','id_name'=>'Id','overwrite'=>FALSE));

        // cria o objeto chave que ira conter as chaves e certificados
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));

        // carrega a senha para a chave privada, se houver
        $objKey->passphrase = $this->passPhrase;

        // carrega a chave privada
        $objKey->loadKey($this->pathKey, TRUE);

        // metodo sign, processa a assinatura
        $objDSig->sign($objKey);

        //metodo add509Cert adiciona o certificado digital
        $objDSig->add509Cert($this->pathCert,TRUE,TRUE);

        //insere a assinatura no objeto DOM
        $objDSig->appendSignature($doc->documentElement);
        
        // monta o nome do arquivo da NFe assinada
        $outfilename = $outDir.$id.'.xml';
        // salva o xml assinado em uma string
        $xml = $doc->save($outfilename);
        
        // salva o xml assinado em uma string
        $xml = $doc->saveXML();

        return $xml;
    }
    
    /**********************************************
     * Verificaçao da NF com base no xsd
     *
     * @param	string  $NFe 
     * @param   string  $xsd Path completo para o arquivo xsd
	 * @return	boolean TRUE se passou ou FALSE se foram detectados erros
	 * @access  public
     ***********************************************/
    function verificaNF($NFe, $xsd){
        
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);

        // instancia novo objeto DOM
        $xml = new DOMDocument();
        // carrega arquivo xml
        $xml->loadXML($NFe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $erromsg='';

        // valida o xml com o xsd
        if (!$xml->schemaValidate($xsd)) {

            // carrega os erros em um array
            $aIntErrors = libxml_get_errors();
            libxml_clear_errors();
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
     * Validaçao do cerificado digital, alem de indicar
     * a validade este metodo carrega a propriedade
     * mesesToexpire da classe quer indica o numero de
     * meses que faltam para expirar a validade do certificado
     * esta informacao pode ser utilizada para a gestao dos
     * certificados de forma a garantir que sempre estejam validos
     *
     * @param	string  $certfile Path completo do certificado digital no formato pem
	 * @return	boolean	True se o certificado estiver dentro do prazo de validade, e False se nao
	 * @access  public
    **/
    function validCert($certfile){

        $flagOK = true;
        $cert = file_get_contents($certfile);
        // extrai a data de validade do
        $data = openssl_x509_read($cert);
        $cert_data = openssl_x509_parse($cert);

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
            $this->errorStatus = true;
            $this->errorMsg = "Erro Certificado:  A Validade do certificado expirou em ["  . $dia.'/'.$mes.'/'.$ano . "] INVALIDO !!";
        } else {
            $flagOK = $flagOK && true;
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



    /**********************************************
     * Verifica o status do servico da SEFAZ
     *
     * Este metodo carrega a variavel
     * $this->cStat = 107 OK
     * cStat = 108 sitema paralizado momentaneamente, aguardar retorno
     * cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     * se SCAN estiver ativado usar, caso caontrario aguardar pacientemente
     * outros erros de xml, certificado ou comunicaçao
     *
     *
     * @param	none
	 * @return	boolean True se operacional e False se nao
	 * @access  public
     ***********************************************/
    function statusServico(){
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
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="http://www.portalfiscal.inf.br/nfe"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consStatServ xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'">'.'<tpAmb>'.$this->ambiente.'</tpAmb><cUF>'.$this->UFcod.'</cUF><xServ>STATUS</xServ></consStatServ>'
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);

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

            if ($this->cStat = '107'){
                $bRet = TRUE;
            }
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }

        return $bRet;
    }

    /**********************************************
     * Envia lote de Notas Fiscais
     * 
     * @param	array   $aNFe notas fiscais em xml uma em cada campo de uma string
     * @param   integer $idLote o id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma NFe usar banco
     *                          de dados
	 * @return	boolean	True se aceito o lote ou False de rejeitado
	 * @access  public
     ************************************************/
    function enviaNF($aNFe=array(),$idLote='1'){
        //variavel de retorno do metodo
        $bRet = false;

        // carga das variaveis da funçao do webservice
        $wsdl = 'NFeRecepcao';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        // limpa a variavel
        $sNFe = '';

        // monta string com as NFe enviadas
        $sNFe = implode('',$aNFe);

        //remover <?xml version="1.0" encoding=...
        $sNFe = str_replace('<?xml version="1.0" encoding="utf-8"?>','',$sNFe);

        //ATENÇAO $sNFe nao pode ultrapassar 500kBytes
        if (strlen($sNFe) > 470000) {
            //indicar erro e voltar
            return FALSE;
        }

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="http://www.portalfiscal.inf.br/nfe"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<enviNFe  xmlns="'.$this->URLnfe.'" xmlns:ds="'.$this->URLdsig.'" xmlns:xsi="'.$this->URLxsi.' versao="'.$dataVer.'"><idLote>'.$idLote.'</idLote>'.$sNFe
        );

        //retorno e um array contendo a mensagem do SEFAZ
        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);

            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            // status do recebimento ou mensagem de erro
            $this->cStat = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $this->tAmb = $doc->getElementsByTagName('tAmb')->item(0)->nodeValue;
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
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }

        return $bRet;
    }
    
    /**********************************************
     * Solicita resposta do lote de Notas Fiscais
     *
     * @param	string   $recibo numero do recibo do envio do lote
	 * @return	boolean  True se sucesso false se falha
	 * @access  public
     ************************************************/
    function retornoNF($recibo){

        //variavel de retorno do metodo
        $bRet = FALSE;

        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeRetRecepcao';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        $parm = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$this->cabecVersao.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consReciNFe xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'"><nRec>'.$recibo.'</nRec></consReciNFe>'
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {
            //extrair a resposta da matriz e garantir que os dados retornem como UTF-8
            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);
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
            if( $this->cStat == '104' ) {
                // houve retorno com notas aceitas
                $bRet = TRUE;
                // para controlar as interaçoes
                $n = 0;
                // vai haver um grupo protNFe para cada NF enviada no lote
                $protNFe = $doc->getElementsByTagName('protNFe');
                foreach ($protNFe as $pNFe) {
                    $versao = $pNFe->getElementsByTagName('versao')->item(0)->nodeValue;
                    $nNFe = $pNFe->getElementsByTagName('infProt')->item(0);
                    //extrai o id da tag
                    $id = trim($nNFe->getAttribute("Id"));
                    $tpAmb = $pNFe->getElementsByTagName('tpAmb')->item(0);
                    $verAplic = $pNFe->getElementsByTagName('verAplic')->item(0);
                    $chNFe = $pNFe->getElementsByTagName('chNFe')->item(0);
                    $dhRecbto = $pNFe->getElementsByTagName('dhRecbto')->item(0);
                    $nProt = $pNFe->getElementsByTagName('nProt')->item(0);
                    $digVal = $pNFe->getElementsByTagName('digVal')->item(0);
                    $cStat = $pNFe->getElementsByTagName('cStat')->item(0);
                    $xMotivo = $pNFe->getElementsByTagName('xMotivo')->item(0);
                    $aNFe = array($n, array('chNFe'=>$chNFe,'cStat'=>$cStat,'xMotivo'=>$Motivo,'nProt'=>$nProt,'digVal'=>digVal,'dhRecbto'=>$dhRecbto,'tpAmb'=>$tpAmb,'verAplic'=>$verAplic,'versao'=>$versao,'Id'=>$id));
                    $n ++;
                }
            }
        }  else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }

    /**********************************************
     * Solicita inutilizaçao de uma serie de numeros de NF
     *
     * @param	string  $ano
     * @param   string  $nfSerie
     * @param   integer $numIni
     * @param   integer $numFim
	 * @return	boolean TRUE se sucesso FALSE se falha
	 * @access  public
     ************************************************/
    function inutilizaNF($ano,$nfSerie,$modelo,$numIni,$numFim){
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
        $nfeDadosMsg = '<inutNFe xmlns="'.$this->URLnfe.'" versao="'.$dataVer.'"><infInut Id="'.$id.'"><xServ>INUTILIZAR</xServ><cUF>'.$this->UFcod.'</cUF><ano>'.$ano.'</ano><CNPJ>'.$this->CNPJ.'</CNPJ><mod>'.$modelo.'</mod><serie>'.$nfSerie.'</serie><nNFIni>'.$numIni.'</nNFIni><nNFFin>'.$numFim.'</nNFFin><xJust>'.$sJust.'</xJust></infInut></inutNFe>';

        //assinar a nfeDadosMsg
        $nfeDadosMsg = $this->assinaPHPNF($nfeDadosMsg, 'infInut');

        $parm = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$this->cabecVersao.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>$nfeDadosMsg
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            $this->versao = $doc->getElementsByTagName('versao')->item(0)->nodeValue;
            $infInut= $doc->getElementsByTagName('infInut');
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

        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }

    /**********************************************
     * Solicita o cancelamento de NF enviada
     *
     * @param	string  $idNFe ID da NFe com 44 digitos (sem o NFe na frente dos numeros)
     * @param   string  $protId Numero do protocolo de aceitaçao da NFe enviado anteriormente pelo SEFAZ
	 * @return	boolean TRUE se sucesso ou FALSE se falha
	 * @access  public
     ************************************************/
    function cancelaNF($idNFe,$protId){
        //variavel de retorno
        $bRet = FALSE;
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeCancelamento';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        $nfeDadosMsg = '<cancNFe xmlns="'.$this->URLnfe.'" versao="'.$dataVer.'"><infCanc Id=ID"'.$idNFe.'"><xServ>CANCELAR</xServ><chNFe>'.$nId.'</chNFe><nProt>'.$protId.'</nProt></infCanc></cancNFe>';
        $nfeDadosMsg = $this->assinaPHPNF($nfeDadosMsg, 'infCanc');
        
        $parm = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$this->cabecVersao.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>$nfeDadosMsg
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            $this->versao = $doc->getElementsByTagName('versao')->item(0)->nodeValue;
            $infCanc = $doc->getElementsByTagName('infCanc');
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

        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }

    /**
     * Solicita dados de situaçao de NF
     *
     * @param	string   $idNFe numerico com 44 digitos
	 * @return	mixed	response from SOAP call
	 * @access  public
     **/
    function consultaNF($idNFe){
        //variavelde retorno do metodo
        $bRet = FALSE;
        // carga das variaveis da funçao do webservice
        $wsdl = 'NfeConsultaNF';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wdsl];
        $dataVer        = $this->aVerxsd[$wdsl];

        $parm = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$this->cabecVersao.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consSitNFe xmlns:xsi="'.$this->URLxsi.'" xmlns:xsd="'.$this->URLxsd.'" versao="'.$dataVer.'" xmlns="'.$this->URLnfe.'"><xServ>CONSULTAR</xServ><chNFe>'.$idNFe.'</chNFe></consSitNFe>'
        );

        $retorno = $this->sendSOAP($param, $wsdl);

        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xmlresp,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            $this->versao = $doc->getElementsByTagName('versao')->item(0)->nodeValue;
            $infProt = $doc->getElementsByTagName('infProt');
            //extrai o id da tag
            $id = trim($infProt->getAttribute("Id"));

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
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }
    

    /**
     * Solicita dados de situaçao de Cadastro
     *
     * @param	string  $UF
     * @param   string  $IE
     * @param   string  $CNPJ
     * @param   string  $CPF
	 * @return	boolean TRUE se sucesso ou FALSE se falha
	 * @access  public
     **/
    function consultaCadastro($UF,$IE='',$CNPJ='',$CPF=''){
        //variavel de retorno do metodo
        $bRet = FALSE;
        //variaveis do webservice
        $wsdl = 'ConsultaCadastro';
        $cabecXsdfile   = $this->aFxsd['CabecMsg'];
        $cabecVer       = $this->aVerxsd['CabecMsg'];
        $dataXsdfile    = $this->aFxsd[$wsdl];
        $dataVer        = $this->aVerxsd[$wsdl];

        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($CNPJ != '') {
           $filtro = '<CNPJ>'.$CNPJ.'</CNPJ>';
        } else {
            if($CFP != '') {
                $filtro = '<CPF>'.$CPF.'</CPF>';
            } else {
                if ($IE != ''){
                    $filtro = '<IE>'.$IE.'</IE>';
                } else {
                    //erro nao foi passado parametro de filtragem
                    $this->errorStatus = TRUE;
                    $this->errorMsg = 'Um filtro deve ser indicado CNPJ, CPF ou IE !!!';
                    return FALSE;
                }
            }
        }

        $param = array(
            'nfeCabecMsg'=>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="'.$cabecVer.'" xmlns="'.$this->URLnfe.'"><versaoDados>'.$dataVer.'</versaoDados></cabecMsg>',
            'nfeDadosMsg'=>'<consCad><versao>'.$dataVer.'</versao><infCons><xServ>CONS-CAD</xServ><UF>'.$UF.'</UF>'.$filtro.'</infCons></consCad>'
        );

       $retorno = $this->sendSOAP($param, $wsdl);
        
        if (is_array($retorno)) {

            $xmlresp = utf8_encode($retorno[$this->aFunctionWdsl[$wsdl].'Result']);

            // tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
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
        } else {
            $this->errorStatus = true;
            $this->errorMsg = 'Nao houve retorno do NuSoap!!';
        }
        return $bRet;
    }

    /**
     * Gera arquivo pdf para impressao de NF-e
     *
     * @param	string   $idNFe
	 * @return	mixed	response from SOAP call
	 * @access  public
     **/
    function imprimeNF($idNFe){

    }


	/**
	* Retorna s string de erro se existir
    *
    * @param    none
	* @return   mixed String do erro ou false
	* @access   public
	**/
	function getError(){
		if($this->errorMsg != ''){
			return $this->errorMsg;
		}
		return false;
	}


	/**
	* Estabelece comunicaçao com servidor SOAP
    *
    * @param    array   $param
    * @param    string  $wsdl
	* @return   mixed  Array com a resposta do SOAP ou String do erro ou false
	* @access   public
	**/
    function sendSOAP($param,$wsdl){

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

        // retorna o resultado da comunicaçao
        return $result;
    }


	/**
	 * Converte o campo data time retornado pelo webservice
     * em um timestamp unix
     *
     * @param    string   $DH
	 * @return   timestamp
	 * @access   public
	**/
    function convertTime($DH){
        if ($DH){
            $aDH = split('T',$DH);
            $adDH = split('-',$aDH[0]);
            $atDH = split(':',$aDH[1]);
            $timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);
            return $timestampDH;
        }
    }

	/**
	 * Converte o xml em um array associativo
     *
     * @param    string   $xml
	 * @return   array
	 * @access   public
	**/
    function convXMLtoArray($xml){

        $p = xml_parser_create();
        xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);

        xml_parse_into_struct($p, $xml, $vals, $index);
        xml_parser_free($p);

        $levels = array(null);

        foreach ($vals as $val) {
            if ($val['type'] == 'open' || $val['type'] == 'complete') {
                if (!array_key_exists($val['level'], $levels)) {
                    $levels[$val['level']] = array();
                }
            }

            $prevLevel =& $levels[$val['level'] - 1];
            $parent = $prevLevel[sizeof($prevLevel)-1];

            if ($val['type'] == 'open') {
                $val['children'] = array();
                array_push(&$levels[$val['level']], $val);
                continue;
            } else if ($val['type'] == 'complete') {
                $parent['children'][$val['tag']] = $val['value'];
            } else if ($val['type'] == 'close') {
                $pop = array_pop($levels[$val['level']]);
                $tag = $pop['tag'];
                if ($parent) {
                    if (!array_key_exists($tag, $parent['children'])) {
                        $parent['children'][$tag] = $pop['children'];
                    } else if (is_array($parent['children'][$tag])) {
                        $parent['children'][$tag][] = $pop['children'];
                    }
                } else {
                    return(array($pop['tag'] => $pop['children']));
                }
            }

            $prevLevel[sizeof($prevLevel)-1] = $parent;
        }

    }




}
?>
