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
 *
 * @package   NFePHP
 * @name      NFSeSEGinfes
 * @version   0.0.1
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Giuliano Nascimento <giusoft at hotmail dot com>
 * @author    Hugo Cegana <cegana at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *            Renato Ricci <renatoricci23 at gmail dot com>
 *            Roberto Leite Machado <linux dot rlm at gamil dot com>
 * 
 * Testado nas prefeituras:
 * - jundiai.ginfes.com.br (SP)
 * - guarulhos.ginfes.com.br (SP)
 * 
 * 
 * ATENÇÃO : REQUER NuSOAP !!!! não incluso no repositório !!!
 * 
 * 
 * CONSIDERAÇÕES GERAIS
 * - CADA LOTE PODE POSSUIR VÁRIAS NFS-e
 * - CADA LOTE PODE POSSUIR VÁRIOS RPS
 * - CADA NOTA ESTÁ VINCULADA A UM UNICO RPS E VICE-VERSA
 * 
 */

if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
include_once 'NuSOAP/nusoap.php';
require_once "../NFe/ToolsNFePHP.class.php";
require_once "NFSePHPGinfesData.class.php";
require_once "NFSePHPGinfesPDF.class.php";


class NFSePHPGinfes extends ToolsNFePHP {
    
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
     * urlXmlns
     * @var string
     */
    protected $urlXmlns = "http://www.ginfes.com.br/";
    /**
     * urlXsdTipos
     * @var string
     */    
    protected $urlXsdTipos = "http://www.ginfes.com.br/tipos_v03.xsd";
    
    /**
     *
     * @var type 
     */
    public $nfsexml = '';
    /**
     *
     * @var type 
     */
    public $arqtxt = '';
    /**
     *
     * @var type 
     */
    public $errMsg = '';
    /**
     *
     * @var type 
     */
    public $errStatus = false;
    /**
     *
     * @var type 
     */
    public $mURL = '';
    /**
     *
     * @var type 
     */
    public $nfseDir = '';
    
    /**
     *
     * @var type 
     */
    private $save_files = true;

    /**
     * __contruct
     * @param array $aConfig
     * @param int $mododebug
     * @param boolean $exceptions
     * @param boolean $save_files : atributo que visa o salvamento automático das informações
     */
    public function __construct($aConfig = '', $mododebug = 2, $exceptions = false, $save_files = true) {
        
        parent::__construct($aConfig, $mododebug, $exceptions);

        $sAmb = ($this->tpAmb == 2) ? 'homologacao' : 'producao';

        $url_servico = "https://{$sAmb}.ginfes.com.br/ServiceGinfesImpl";

        /* - adequações de ambiente - */
        $this->nfseDir = $this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'nfse' . DIRECTORY_SEPARATOR;
        $this->schemeVer = "NFSe/ginfes";

        $this->save_files = $save_files;

        $this->__cria_estrutura_diretorios();

        //--------------------------------------------------------------------------------------------------//
        //DEFINIÇÃO DAS CHAMADAS AO SERVIÇOS
        //--------------------------------------------------------------------------------------------------//

        $this->mURL['EnviarLoteRpsEnvio']['url'] = $url_servico;
        $this->mURL['EnviarLoteRpsEnvio']['version'] = 'v03';
        $this->mURL['EnviarLoteRpsEnvio']['method'] = 'RecepcionarLoteRpsV3';
        $this->mURL['EnviarLoteRpsEnvio']['xsd'] = 'servico_enviar_lote_rps_envio_v03.xsd';

        $this->mURL['ConsultarLoteRpsEnvio']['url'] = $url_servico;
        $this->mURL['ConsultarLoteRpsEnvio']['version'] = 'v03';
        $this->mURL['ConsultarLoteRpsEnvio']['method'] = 'ConsultarLoteRpsV3';
        $this->mURL['ConsultarLoteRpsEnvio']['xsd'] = 'servico_consultar_lote_rps_envio_v03.xsd';

        $this->mURL['ConsultarSituacaoLoteRpsEnvio']['url'] = $url_servico;
        $this->mURL['ConsultarSituacaoLoteRpsEnvio']['version'] = 'v03';
        $this->mURL['ConsultarSituacaoLoteRpsEnvio']['method'] = 'ConsultarSituacaoLoteRpsV3';
        $this->mURL['ConsultarSituacaoLoteRpsEnvio']['xsd'] = 'servico_consultar_situacao_lote_rps_envio_v03.xsd';

        $this->mURL['ConsultarNfseRpsEnvio']['url'] = $url_servico;
        $this->mURL['ConsultarNfseRpsEnvio']['version'] = 'v03';
        $this->mURL['ConsultarNfseRpsEnvio']['method'] = 'ConsultarNfsePorRpsV3';
        $this->mURL['ConsultarNfseRpsEnvio']['xsd'] = 'servico_consultar_nfse_rps_envio_v03.xsd';

        $this->mURL['ConsultarNfseEnvio']['url'] = $url_servico;
        $this->mURL['ConsultarNfseEnvio']['version'] = 'v03';
        $this->mURL['ConsultarNfseEnvio']['method'] = 'ConsultarNfseV3';
        $this->mURL['ConsultarNfseEnvio']['xsd'] = 'servico_consultar_nfse_envio_v03.xsd';

        /*
          $this->mURL['CancelarNfseEnvio']['url']                = $url_servico;
          $this->mURL['CancelarNfseEnvio']['version']            = '3';
          $this->mURL['CancelarNfseEnvio']['method']             = 'CancelarNfseV3';
          $this->mURL['CancelarNfseEnvio']['xsd']                = 'servico_cancelar_nfse_envio_v03.xsd';
         */

        $this->mURL['CancelarNfseEnvio']['url'] = $url_servico;
        $this->mURL['CancelarNfseEnvio']['version'] = 'v02';
        $this->mURL['CancelarNfseEnvio']['method'] = 'CancelarNfse';
        $this->mURL['CancelarNfseEnvio']['xsd'] = 'servico_cancelar_nfse_envio_v02.xsd';
    }



    /**
     * Método responsável pela montagem do lote de serviços RPS. Cada nota lote pode ter "n" notas.
     * 
     * @param int $idLote : código sequencial para numeração do lote. Entretando, o lote definitivo passado ao Ginfes será 
     *      composto por [ date("ym") . sprintf("%011s", $idLote) ] ;
     * @param array $aNFSeLote : array contendo objetos  NFSePHPGinfesData() 
     * @return string contendo xml já assinado
     */
    public function montarLoteRps($idLote = "", $aNFSeLote = false) {

        $this->numeroLote = date("ym") . sprintf("%011s", $idLote);

        // Cria o objeto DOM para o xml
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $x = "p1:";


        $count_rps = count($aNFSeLote);

        $ListaRps = $dom->createElement($x . "ListaRps");
        for ($i = 0; $i < $count_rps; $i++) {

            $nf = $aNFSeLote[$i];


            $Rps = $dom->createElement($x . "Rps");
            $infRps = $dom->createElement($x . "InfRps");

            // Identificação

            $IdentificacaoRps = $dom->createElement($x . "IdentificacaoRps");
            $Numero = $dom->createElement($x . "Numero", $nf->get('numrps'));
            $Serie = $dom->createElement($x . "Serie", $nf->get('numSerie'));
            $Tipo = $dom->createElement($x . "Tipo", $nf->get('tipo'));

            $IdentificacaoRps->appendChild($Numero);
            $IdentificacaoRps->appendChild($Serie);
            $IdentificacaoRps->appendChild($Tipo);

            $infRps->appendChild($IdentificacaoRps);

            $infRps->appendChild($dom->createElement($x . "DataEmissao", date("Y-m-d") . "T" . "00:00:00"));
            
            //$infRps->appendChild($dom->createElement($x . "DataEmissao", date("Y-m-d") . "T" . date("H:i:s"))); // apresentando problemas de sincronia
            $infRps->appendChild($dom->createElement($x . "NaturezaOperacao", $nf->get('natOperacao')));
            $infRps->appendChild($dom->createElement($x . "RegimeEspecialTributacao", $nf->get('regimeEspecialTributacao')));
            $infRps->appendChild($dom->createElement($x . "OptanteSimplesNacional", $nf->get('optanteSimplesNacional')));
            $infRps->appendChild($dom->createElement($x . "IncentivadorCultural", $nf->get('incentivadorCultural')));
            $infRps->appendChild($dom->createElement($x . "Status", $nf->get('status')));


            $item = $nf->getArrayItem();
            $Servico = $dom->createElement($x . "Servico");
            $Valores = $dom->createElement($x . "Valores");

            $ValorServicos = $dom->createElement($x . "ValorServicos", number_format($item['valorServicos'], 2, '.', ''));
            $ValorDeducoes = $dom->createElement($x . "ValorDeducoes", number_format($item['valorDeducoes'], 2, '.', ''));
            $ValorPis = $dom->createElement($x . "ValorPis", number_format($item['valorPis'], 2, '.', ''));
            $ValorCofins = $dom->createElement($x . "ValorCofins", number_format($item['valorCofins'], 2, '.', ''));
            $ValorIr = $dom->createElement($x . "ValorIr", number_format($item['valorIr'], 2, '.', ''));
            $ValorInss = $dom->createElement($x . "ValorInss", number_format($item['valorInss'], 2, '.', ''));
            $ValorCsll = $dom->createElement($x . "ValorCsll", number_format($item['valorCsll'], 2, '.', ''));
            $IssRetido = $dom->createElement($x . "IssRetido", $item['issRetido']);
            $ValorIss = $dom->createElement($x . "ValorIss", number_format($item['valorIss'], 2, '.', ''));
            $ValorIssRetido = $dom->createElement($x . "ValorIssRetido", number_format($item['valorIssRetido'], 2, '.', ''));
            $OutrasRetencoes = $dom->createElement($x . "OutrasRetencoes", number_format($item['outrasRetencoes'], 2, '.', ''));
            $BaseCalculo = $dom->createElement($x . "BaseCalculo", number_format($item['baseCalculo'], 2, '.', ''));
            $Aliquota = $dom->createElement($x . "Aliquota", number_format($item['aliquota'], 3, '.', ''));
            $ValorLiquidoNfse = $dom->createElement($x . "ValorLiquidoNfse", number_format($item['valorLiquidoNfse'], 2, '.', ''));
            $DescontoIncondicionado = $dom->createElement($x . "DescontoIncondicionado", number_format($item['descontoIncondicionado'], 2, '.', ''));
            $DescontoCondicionado = $dom->createElement($x . "DescontoCondicionado", number_format($item['descontoCondicionado'], 2, '.', ''));

            $Valores->appendChild($ValorServicos);
            $Valores->appendChild($ValorDeducoes);
            $Valores->appendChild($ValorPis);
            $Valores->appendChild($ValorCofins);
            $Valores->appendChild($ValorInss);
            $Valores->appendChild($ValorIr);
            $Valores->appendChild($ValorCsll);
            $Valores->appendChild($IssRetido);
            $Valores->appendChild($ValorIss);
            $Valores->appendChild($ValorIssRetido);
            $Valores->appendChild($OutrasRetencoes);
            $Valores->appendChild($BaseCalculo);
            $Valores->appendChild($Aliquota);
            $Valores->appendChild($ValorLiquidoNfse);
            $Valores->appendChild($DescontoIncondicionado);
            $Valores->appendChild($DescontoCondicionado);

            // Detalhes do serviço
            $ItemListaServico = $dom->createElement($x . "ItemListaServico", trim($item['itemListaServico']));
            $CodigoTributacaoMunicipio = $dom->createElement($x . "CodigoTributacaoMunicipio", trim($item['codigoTributacaoMunicipio']));
            $Discriminacao = $dom->createElement($x . "Discriminacao", $this->limparString($item['discriminacao']));
            $CodigoMunicipio = $dom->createElement($x . "CodigoMunicipio", $nf->get('cMun'));

            $Servico->appendChild($Valores);
            $Servico->appendChild($ItemListaServico);
            $Servico->appendChild($CodigoTributacaoMunicipio);
            $Servico->appendChild($Discriminacao);
            $Servico->appendChild($CodigoMunicipio);
            $infRps->appendChild($Servico);

            // Prestador do Serviço
            $Prestador = $dom->createElement($x . "Prestador");
            $Cnpj = $dom->createElement($x . "Cnpj", $nf->CNPJ);
            $InscricaoMunicipal = $dom->createElement($x . "InscricaoMunicipal", $nf->IM);
            $Prestador->appendChild($Cnpj);
            $Prestador->appendChild($InscricaoMunicipal);

            // Tomador do Serviço
            $Tomador = $dom->createElement($x . "Tomador");
            $IdentificacaoTomador = $dom->createElement($x . "IdentificacaoTomador");
            $CpfCnpj = $dom->createElement($x . "CpfCnpj");

            $TomadorCpf = $dom->createElement($x . "Cpf", $nf->get('tomaCPF'));
            $TomadorCnpj = $dom->createElement($x . "Cnpj", $nf->get('tomaCNPJ'));

            if ($nf->get('tomaCPF') != '') {
                $CpfCnpj->appendChild($TomadorCpf);
            } else {
                $CpfCnpj->appendChild($TomadorCnpj);
            }

            $IdentificacaoTomador->appendChild($CpfCnpj);
            $RazaoSocial = $dom->createElement($x . "RazaoSocial", $nf->get('tomaRazaoSocial'));
            $EEndereco = $dom->createElement($x . "Endereco");
            $Endereco = $dom->createElement($x . "Endereco", $nf->get('tomaEndLogradouro'));
            $Numero = $dom->createElement($x . "Numero", $nf->get('tomaEndNumero'));
            $Bairro = $dom->createElement($x . "Bairro", $nf->get('tomaEndBairro'));
            $CodigoMunicipio = $dom->createElement($x . "CodigoMunicipio", $nf->get('tomaEndcMun'));
            $Uf = $dom->createElement($x . "Uf", $nf->get('tomaEndUF'));
            $Cep = $dom->createElement($x . "Cep", $nf->get('tomaEndCep'));
            $EEndereco->appendChild($Endereco);
            $EEndereco->appendChild($Numero);
            $EEndereco->appendChild($Bairro);
            $EEndereco->appendChild($CodigoMunicipio);
            $EEndereco->appendChild($Uf);
            $EEndereco->appendChild($Cep);
            $Tomador->appendChild($IdentificacaoTomador);
            $Tomador->appendChild($RazaoSocial);
            $Tomador->appendChild($EEndereco);

            $infRps->appendChild($Prestador);
            $infRps->appendChild($Tomador);

            $Rps->appendChild($infRps);
            $ListaRps->appendChild($Rps);
        }

        $LoteRps = $dom->createElement("p:LoteRps");
        $LoteRps->setAttribute("Id", $this->numeroLote);

        $NumeroLote = $dom->createElement($x . "NumeroLote", $this->numeroLote);
        $QuantidadeRps = $dom->createElement($x . "QuantidadeRps", $count_rps);
        $Cnpj = $dom->createElement($x . "Cnpj", $nf->get('CNPJ'));
        $InscricaoMunicipal = $dom->createElement($x . "InscricaoMunicipal", $nf->get('IM'));

        $EnviarLoteRpsEnvio = $dom->createElement("p:EnviarLoteRpsEnvio");
        $EnviarLoteRpsEnvio->setAttribute("xmlns:xsi", $this->URLxsi);
        $EnviarLoteRpsEnvio->setAttribute("xmlns:p", $this->urlXmlns . $this->mURL['EnviarLoteRpsEnvio']['xsd']);
        $EnviarLoteRpsEnvio->setAttribute("xmlns:" . str_replace(":", "", $x), $this->urlXsdTipos);

        $LoteRps->appendChild($NumeroLote);
        $LoteRps->appendChild($Cnpj);
        $LoteRps->appendChild($InscricaoMunicipal);
        $LoteRps->appendChild($QuantidadeRps);
        $LoteRps->appendChild($ListaRps);
        $EnviarLoteRpsEnvio->appendChild($LoteRps);

        $dom->appendChild($EnviarLoteRpsEnvio);
        $xml = $dom->saveXML();

        $xml = $this->limparXML($xml);

        $this->nfsexml = $this->signXML($xml, "LoteRps");

        $this->__save_xml_assinado($this->numeroLote . ".xml", $this->nfsexml);

        return $this->nfsexml;
    }
    
    /**
     * Método para sanitizar o xml conforme formato aceito pelo webservice
     * 
     * @param string $xml : contém string com xml a ser sanitizado
     * @return string com o xml sanitizado
     * 
     */
    private function limparXML($xml) {
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '<?xml version="1.0" encoding="UTF-8" standalone="no"?>', $xml);
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', '', $xml);
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
        $xml = str_replace("\n", "", $xml);
        $xml = str_replace("  ", " ", $xml);
        $xml = str_replace("  ", " ", $xml);
        $xml = str_replace("  ", " ", $xml);
        $xml = str_replace("  ", " ", $xml);
        $xml = str_replace("  ", " ", $xml);
        $xml = str_replace("> <", "><", $xml);
        $xml = trim(str_replace("\n", "", $xml));
        return $xml;
    } //fim limparXML

    
    /**
     * Método que formata e sanitiza os retornos soap, é chamado após qualquer chamada ao método __sendSOAPNFSe()
     * @param string $soap : string contendo retorno da chamada __sendSOAPNFSe()
     * @return string contendo o retorno soap sanitizado
     */
    private function limparRetornoSOAP($soap = '') {
        $soap = str_replace('&lt;', '<', $soap);
        $soap = str_replace('&gt;', '>', $soap);
        $soap = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $soap);
        $soap = utf8_encode($soap);
        return $soap;
    } //fim limparRetornoSOAP
    
    
    /**
     * Método que verifica o sucesso ou fracasso da chamada ao webservice 
     * 
     * @param string $soap
     * @return boolean : true para sucesso / false caso haja alguma mensagem de erro armazenando-a no atrbibuto "errMsg".
     * 
     */
    private function verificarProcessamentoOk($soap) {
        $this->errStatus = false;
        $this->errMsg = "";

        if ($soap == '') {
            //houve uma falha na comunicação SOAP
            $this->errStatus = TRUE;
            $this->errMsg = 'Houve uma falha na comunicação SOAP!!';
            return FALSE;
        }
        $doc = new DOMDocument(); //cria objeto DOM
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = FALSE;
        $doc->loadXML($soap, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        //status do recebimento ou mensagem de erro
        //$aRet['Numero'] = $doc->getElementsByTagName('Numero')->item(0)->nodeValue;
        //$aRet['CodigoVerificacao'] = $doc->getElementsByTagName('CodigoVerificacao')->item(0)->nodeValue;
        //$aRet['DataEmissao'] = $doc->getElementsByTagName('DataEmissao')->item(0)->nodeValue;

        $erros = $doc->getElementsByTagName('ListaMensagemRetorno');

        if ($erros->length > 0) {
            $this->errStatus = true;
            $this->errMsg = "Código de erro: " . $doc->getElementsByTagName('Codigo')->item(0)->nodeValue . "\n";
            $this->errMsg.= "Mensagem: " . $doc->getElementsByTagName('Mensagem')->item(0)->nodeValue . "\n";
            $this->errMsg.= "Correção: " . $doc->getElementsByTagName('Correcao')->item(0)->nodeValue . "\n";
            return false;
        }

        return true;
    }

    /**
     * Retorna valor de uma tag conforme parametros passados 
     *
     * @param string $tag : tag a ser procurada
     * @param string $xml : xml do conteúdo a ser extraído
     * @param string $i   : ordem da tag dentro do xml
     * @return string contendo o valor da tag requerida
     */
    public function getTagValue($tag, $xml, $i = 0) {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = FALSE;
        $doc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

        return $doc->getElementsByTagName($tag)->item($i)->nodeValue;
    }
    
    
    /**
     * Retorna string após remoção de acentos e caracteres que possam causar problemas na chamada soap.
     * @param string $texto
     * @return type : string limpa
     */
    private function limparString($texto) {
        $aFind = array('&', 'á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ', 'Ú', 'Ü', 'Ç');
        $aSubs = array('e', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'c', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/]/", "", $novoTexto);
        return $novoTexto;
    }


    /**
     * Valida XMl de acordo como serviço chamado
     * 
     * @param string $xml = xml a ser validado
     * @param string $servico = serviço para extração do xsd
     * @return boolean : true para validado com sucesso, false caso contrário
     * 
     */
    public function validarXML($xml, $servico) {
        $schema = $this->mURL[$servico]['xsd'];
        $version = $this->mURL[$servico]['version'];
        //$schema = (empty($schema)) ? $this->NFeSschema : $schema;
        $xsd = $this->xsdDir . $this->schemeVer . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $schema;
        $aErr = array();
        return $this->validXML($xml, $xsd, $aErr);
    }
   
   
    /**
     * Envia lote RPS 
     * 
     * @param string $xmlLote : xml montado a partir do método montarLoteRps() ou xml conforme especificado pelo manual ginfes
     * @return string/boolean : false caso tenha havido falha / xml contendo a resposta soap caso tudo tenha dado certo neste retorno haverá o número do protocolo necessário para a consulta de situação do mesmo através do método consultarSituacaoLoteRps()
     */
    public function enviarLoteRps($xmlLote = false) {

        if (!$xmlLote) {
            $xmlLote = $this->nfsexml;
        }


        if (!$xmlLote) {
            $xmlLote = $this->__get_xml_file($this->numeroLote . ".xml", "lotes");
        }

        if (!$xmlLote) {
            $this->errStatus = true;
            $this->errMsg = 'Conteudo do XML de envio nao definido!';
            return false;
        }


        if (!$this->validarXML($xmlLote, 'EnviarLoteRpsEnvio')) {
            return false;
        }

        //identificação do serviço
        $servico = 'EnviarLoteRpsEnvio';

        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];

        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['url'];

        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];

        //envia dados via SOAP
        //montagem dos dados da mensagem SOAP
        $dados = $this->limparXML($xmlLote);
        //$cabec = '<cabecalho xmlns="' . $namespace . '"><versaoDados>' . $versao . '</versaoDados></cabecalho>';
        //$cabec = '<ns2:cabecalho versao="' . $versao . '" xmlns:ns2="http://www.ginfes.com.br/cabecalho_v03.xsd" ><versaoDados>' . $versao . '</versaoDados></ns2:cabecalho>';

        $this->__save_xml_validado($this->numeroLote . ".xml", $xmlLote);

        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);

        if (!empty($retorno)) {

            $retorno = $this->limparRetornoSOAP($retorno);

            if (!$this->verificarProcessamentoOk($retorno)) {
                return false;
            }
            $this->__save_xml_enviado($this->numeroLote . ".xml", $xmlLote);

            $this->__save_xml_enviado($this->numeroLote . "-resp.xml", $retorno);
        } else {

            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return false;
        }

        return $retorno;
    }

    
     
         
     /**
      * 
      * Retorna xml todoas as notas pertencentes ao lote consultado através do protocolo obtido após o EnviarLoteRps  
      * 
      * @param string $protocolo : obtido após o envio
      * @param string $cnpj : cnpj do emissor
      * @param string $im : inscrição municipal do emissor
      * @return boolean ou string : retorna falso caso haja problemas ou xml contendo as NFS-e
      */    
    public function consultarLoteRps($protocolo, $cnpj = false, $im = false) {

        if (!$cnpj) {
            $cnpj = $this->CNPJ;
        }

        if (!$im) {
            $im = $this->IM;
        }

        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'ConsultarLoteRpsEnvio';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['url'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];

        $xml = "<p:ConsultarLoteRpsEnvio Id=\"$protocolo\" xmlns:p=\"http://www.ginfes.com.br/servico_consultar_lote_rps_envio_v03.xsd\" xmlns:p1=\"http://www.ginfes.com.br/tipos_v03.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ><p:Prestador><p1:Cnpj>" . $cnpj . "</p1:Cnpj><p1:InscricaoMunicipal>" . $im . "</p1:InscricaoMunicipal></p:Prestador><p:Protocolo>" . $protocolo . "</p:Protocolo></p:ConsultarLoteRpsEnvio>";
        $xml = $this->signXML($xml, 'ConsultarLoteRpsEnvio');

        if (!$this->validarXML($xml, $servico)) {
            return false;
        }

        

        $dados = $this->limparXML($xml);
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);

        if (!empty($retorno)) {

            $retorno = $this->limparRetornoSOAP($retorno);

            if (!$this->verificarProcessamentoOk($retorno)) {
                return false;
            }
        

            $this->extractNfse($retorno, $servico);
        } else {

            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return false;
        }

        return $retorno;
    }

    /**
     * 
     * Retorna xml contendo a situação do lote consultado através do protocolo obtido após o EnviarLoteRps 
     * As situações podem ser: 
                "1": Não recebido
                "2": Não Processado                    
                "3": Processado com erro
                "4": Processado com sucesso
     * 
     * @param string $protocolo : obtido após o envio do lote
     * @param string $cnpj
     * @param string $im
     * 
     */
    public function consultarSituacaoLoteRps($protocolo, $cnpj = false, $im = false) {

        if (!$cnpj) {
            $cnpj = $this->CNPJ;
        }

        if (!$im) {
            $im = $this->IM;
        }


        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'ConsultarSituacaoLoteRpsEnvio';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['url'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];

        $xml = "<p:ConsultarSituacaoLoteRpsEnvio Id=\"$protocolo\" xmlns:p=\"http://www.ginfes.com.br/servico_consultar_situacao_lote_rps_envio_v03.xsd\" xmlns:p1=\"http://www.ginfes.com.br/tipos_v03.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ><p:Prestador><p1:Cnpj>" . $cnpj . "</p1:Cnpj><p1:InscricaoMunicipal>" . $im . "</p1:InscricaoMunicipal></p:Prestador><p:Protocolo>" . $protocolo . "</p:Protocolo></p:ConsultarSituacaoLoteRpsEnvio>";
        $xml = $this->signXML($xml, 'ConsultarSituacaoLoteRpsEnvio');


        if (!$this->validarXML($xml, $servico)) {
            return false;
        }



        $dados = $this->limparXML($xml);
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);

        if (!empty($retorno)) {

            $retorno = $this->limparRetornoSOAP($retorno);

            if (!$this->verificarProcessamentoOk($retorno)) {
                return false;
            }

            $numeroLote = $this->getTagValue('NumeroLote', $retorno);
            $situacao = $this->getTagValue('Situacao', $retorno);


            $this->__save_xml_file("{$numeroLote}-{$protocolo}-sit-resp.xml", $retorno, "temp");
            switch ($situacao) {
                case "1": //Não recebido
                case "2": //Não Processado                    
                    break;
                case "3": //Processado com erro
                    $this->__save_lote_reprovado($numeroLote);
                    break;
                case "4": //Processado com sucesso
                    $this->__save_lote_aprovado($numeroLote);
                    break;
            }
        } else {

            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return false;
        }

        return $retorno;
    }

    /**
     * 
     * Retorna apenas uma nota mediante ao número do rps.
     * 
     * @param string $numrps
     * @param string $tipo
     * @param string $serie
     * @param string $cnpj
     * @param string $im
     * 
     * 
     */
    public function consultarNfseRps($numrps, $tipo, $serie, $cnpj = false, $im = false) {

        if (!$cnpj) {
            $cnpj = $this->CNPJ;
        }

        if (!$im) {
            $im = $this->IM;
        }

        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'ConsultarNfseRpsEnvio';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['url'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];

        //$xml = "<p:ConsultarSituacaoLoteRpsEnvio Id=\"$protocolo\" xmlns:p=\"http://www.ginfes.com.br/servico_consultar_situacao_lote_rps_envio_v03.xsd\" xmlns:p1=\"http://www.ginfes.com.br/tipos_v03.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ><p:Prestador><p1:Cnpj>".$cnpj."</p1:Cnpj><p1:InscricaoMunicipal>".$im."</p1:InscricaoMunicipal></p:Prestador><p:Protocolo>".$protocolo."</p:Protocolo></p:ConsultarSituacaoLoteRpsEnvio>";
        $xml = '<p:ConsultarNfseRpsEnvio xmlns:p="http://www.ginfes.com.br/servico_consultar_nfse_rps_envio_v03.xsd" xmlns:p1="http://www.ginfes.com.br/tipos_v03.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><p:IdentificacaoRps><p1:Numero>' . $numrps . '</p1:Numero><p1:Serie>' . $serie . '</p1:Serie><p1:Tipo>' . $tipo . '</p1:Tipo></p:IdentificacaoRps><p:Prestador><p1:Cnpj>' . $cnpj . '</p1:Cnpj><p1:InscricaoMunicipal>' . $im . '</p1:InscricaoMunicipal></p:Prestador></p:ConsultarNfseRpsEnvio>';

        $xml = $this->signXML($xml, 'ConsultarNfseRpsEnvio');


        if (!$this->validarXML($xml, $servico)) {
            return false;
        }
        

        $dados = $this->limparXML($xml);
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);

        if (!empty($retorno)) {

            $retorno = $this->limparRetornoSOAP($retorno);

            if (!$this->verificarProcessamentoOk($retorno)) {
                return false;
            }
            $this->extractNfse($retorno, $servico);
        } else {

            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return false;
        }

        return $retorno;
    }

    
    
    /**
     * 
     * Método que retorna xml contendo um lote de NFS-e de acordo com os parametros passados.
     * 
     * @param string $numNFSe : numero da NFS-e específica que deseja buscar, pode ser omitido caso deseja buscar por data
     * @param string $cnpj : cnpj do emissor
     * @param string $im : inscrição municipal do emissor
     * @param string $dtinicial : data inicial para filtragem de notas por periodo (pode ser omitido)
     * @param string $dtfinal : data final do periodo (pode ser omitido caso a primeira data tbm seja)
     * @param string $cpfcnpj_tomador : para pequisar pelo cnpj do tomador (ainda não implementado)
     * @param string $im_tomador : para pesquisar pela inscrição municipal do tomador (ainda não implementado)
     * @return boolean
     */
    public function consultarNfse($numNFSe = false, $cnpj = false, $im = false, $dtinicial = false, $dtfinal = false, $cpfcnpj_tomador = false, $im_tomador = false) {

        if (!$cnpj) {
            $cnpj = $this->CNPJ;
        }

        if (!$im) {
            $im = $this->IM;
        }

        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'ConsultarNfseEnvio';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['url'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];

        $id = date("YmdHis");

        if ($numNFSe) {
            $xml = '<p:ConsultarNfseEnvio Id="' . $id . '" xmlns:p="http://www.ginfes.com.br/servico_consultar_nfse_envio_v03.xsd" xmlns:p1="http://www.ginfes.com.br/tipos_v03.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><p:Prestador><p1:Cnpj>' . $cnpj . '</p1:Cnpj><p1:InscricaoMunicipal>' . $im . '</p1:InscricaoMunicipal></p:Prestador><p:NumeroNfse>' . $numNFSe . '</p:NumeroNfse></p:ConsultarNfseEnvio>';
        } else {

            if (!$dtinicial || !$dtfinal) {
                $this->errStatus = true;
                $this->errMsg = "É necessário informar o número ou periodo de emissão das notas!";
                return false;
            } else {
                $xml = '<p:ConsultarNfseEnvio Id="" xmlns="http://www.w3.org/2000/09/xmldsig#" xmlns:p="http://www.ginfes.com.br/servico_consultar_nfse_envio_v03.xsd" xmlns:p1="http://www.ginfes.com.br/tipos_v03.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><p:Prestador><p1:Cnpj>' . $cnpj . '</p1:Cnpj><p1:InscricaoMunicipal>' . $im . '</p1:InscricaoMunicipal></p:Prestador><p:PeriodoEmissao><p:DataInicial>' . $dtinicial . '</p:DataInicial><p:DataFinal>' . $dtfinal . '</p:DataFinal></p:PeriodoEmissao></p:ConsultarNfseEnvio>';
            }
        }


        $xml = $this->signXML($xml, 'ConsultarNfseEnvio');
        if (!$this->validarXML($xml, $servico)) {
            return false;
        }
        

        $dados = $this->limparXML($xml);
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);

        if (!empty($retorno)) {

            $retorno = $this->limparRetornoSOAP($retorno);

            if (!$this->verificarProcessamentoOk($retorno)) {
                return false;
            }


            $this->extractNfse($retorno, $servico);
        } else {

            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return false;
        }

        return $retorno;
    }
    
    
    
    /**
     * 
     * Cancelamento de uma NFS-e especifica. Funciona exclusivamente com a versão anterior dos schemas (v02).
     * 
     * @param string $numNFSe
     * @param string $cnpj
     * @param string $im
     * @param string $cMun
     * @return boolean
     */
    public function cancelarNfse($numNFSe, $cnpj = false, $im = false, $cMun = false) {

        if (!$cnpj) {
            $cnpj = $this->CNPJ;
        }

        if (!$im) {
            $im = $this->IM;
        }

        if (!$cMun) {
            $cMun = $this->cMun;
        }
        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'CancelarNfseEnvio';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['url'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];

        //$xml = '<p:CancelarNfseEnvio xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:p="http://www.ginfes.com.br/servico_cancelar_nfse_envio_v03.xsd" xmlns:p1="http://www.ginfes.com.br/tipos_v03.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><Pedido><p1:InfPedidoCancelamento Id="'.$numNFSe.'"><p1:IdentificacaoNfse><p1:Numero>'.$numNFSe.'</p1:Numero><p1:Cnpj>'.$cnpj.'</p1:Cnpj><p1:CodigoMunicipio>'.$cMun.'</p1:CodigoMunicipio></p1:IdentificacaoNfse><p1:CodigoCancelamento>2</p1:CodigoCancelamento></p1:InfPedidoCancelamento></Pedido></p:CancelarNfseEnvio>';

        $xml = '<tns:CancelarNfseEnvio xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:tipos="http://www.ginfes.com.br/tipos" xmlns:tns="http://www.ginfes.com.br/servico_cancelar_nfse_envio" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><tns:Prestador><tipos:Cnpj>' . $cnpj . '</tipos:Cnpj><tipos:InscricaoMunicipal>' . $im . '</tipos:InscricaoMunicipal></tns:Prestador><tns:NumeroNfse>' . $numNFSe . '</tns:NumeroNfse></tns:CancelarNfseEnvio>';

        /*
          $xml = $this->signXML($xml, 'InfPedidoCancelamento', 'Pedido', 'ds:');

          if (!$this->validarXML($xml, $servico)){
          return false;
          } */

        $xml = $this->signXML($xml, 'CancelarNfseEnvio', false, 'ds:');

        if (!$this->validarXML($xml, $servico)) {
            return false;
        }
        


        $dados = $this->limparXML($xml);

        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);

        if (!empty($retorno)) {

            $retorno = $this->limparRetornoSOAP($retorno);

            if (!$this->verificarProcessamentoOk($retorno)) {
                return false;
            }
            
        } else {

            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return false;
        }

        return $retorno;
    }

    /**
     * Método que recebe o xml de resposta das consultas de NFS-e e as separar, uma a uma, no diretório "aprovadas" o nome do arquivo é formado pelo número do rps + número da nota.
     * @param string $xml : contém os métodos xml 
     * @param string $servico : serviço no qual foi baseado a consulta
     */
    function extractNfse($xml, $servico) {
        if ($this->save_files) {
            $servico = str_replace("Envio", "Resposta", $servico);

            //$xml = file_get_contents($origem);
            //teste
            //$xml = file_get_contents("20121214175758__consultarNfse-20121214175756-resp-varios.xml");

            $doc = new DOMDocument();
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = FALSE;
            $doc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

            $nfse = explode("<ns3:CompNfse>", $xml);

            $multiplicadorPadrao = 4;

            for ($i = 1; $i < count($nfse); $i++) {

                $cancel = explode("<ns4:NfseCancelamento>", $nfse[$i]);

                $name = sprintf("%015s", $doc->getElementsByTagName("Numero")->item(1 + ($i - 1) * $multiplicadorPadrao + $mdfAdicional)->nodeValue) . "-" . sprintf("%08d", $doc->getElementsByTagName("Numero")->item(($i - 1) * $multiplicadorPadrao + $mdfAdicional)->nodeValue);

                if (count($cancel) > 1) {
                    $mdfAdicional+= 1;
                    $arquivo = $this->nfseDir . "canceladas/{$name}.xml";

                    @unlink($this->nfseDir . "aprovadas/{$name}.xml");
                } else {
                    $arquivo = $this->nfseDir . "aprovadas/{$name}.xml";
                }


                $ponteiro = fopen($arquivo, "w");

                fwrite($ponteiro, $nfse[0] . "<ns3:CompNfse>");
                fwrite($ponteiro, $nfse[$i]);

                if ($i <> count($nfse) - 1) {
                    fwrite($ponteiro, "</ns3:ListaNfse></ns3:$servico>");
                }

                fclose($ponteiro);
            }
        }
    }

    /**
     * gerarPDF
     * @param string $numNFSe : número da nfs-e a ser emitida em pdf
     * @param string $aParser : array contendo valores para substituição de informações que não constam no xml
     */
    public function gerarPDF($numNFSe, $aParser = false) {
        $aFile = $this->listDir($this->nfseDir . "aprovadas/", '*' . $numNFSe . '.xml', true);
        $arquivo_xml_origem = $aFile[0];
        $gif_brasao_prefeitura = $this->nfseDir . "logo_prefeitura.gif";
        if (!is_file($gif_brasao_prefeitura)) {
            $gif_brasao_prefeitura = '';
        }
        $gif_logo_empresa = $this->nfseDir . "logo_empresa.gif";
        if (!is_file($gif_logo_empresa)) {
            $gif_logo_empresa = '';
        }
        $pdf = new NFSePHPGinfesPDF('P', 'cm', 'A4', $arquivo_xml_origem, $gif_brasao_prefeitura, $gif_logo_empresa, $aParser);
        $pdf->printNFSe($this->nfseDir . "pdf/{$numNFSe}.pdf");
    }//fim gerarPDF

    
    /**
     * Estabelece comunicaçao com servidor SOAP da SEFAZ Municipal,
     * usando as chaves publica e privada parametrizadas na contrução da classe, utiliza-se da classe NuSOAP. 
     * (ainda enfrentando problemas ao tentar fazer via curl e soap nativo)
     * 
     * @param string $urlwebservice
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @return type
     */   
    private function __sendSOAPNFSe($urlwebservice, $cabecalho = '', $dados, $metodo) {
        if ($this->tpAmb == 1) {
            $ambiente = 'producao';
            $wsdl = $urlwebservice . '?wsdl';
        } else {
            $ambiente = 'homologacao';
            $wsdl = $urlwebservice . '?wsdl';
        }
        if (empty($cabecalho)) {
            $cabecalho = '<ns2:cabecalho versao="3" xmlns:ns2="http://www.ginfes.com.br/cabecalho_v03.xsd" ><versaoDados>3</versaoDados></ns2:cabecalho>';
        }
        $client = new nusoap_client($wsdl, True);
        $client->soap_defencoding = 'UTF-8';
        $client->authtype = 'certificate';
        $client->certRequest['sslcertfile'] = $this->certKEY;
        $client->certRequest['sslkeyfile'] = $this->priKEY;
        $client->certRequest['verifypeer'] = 0;
        $client->certRequest['verifyhost'] = 0;
        if ($metodo != 'CancelarNfse') {
            $param = array('arg0' => $cabecalho,'arg1' => $dados);
        } else {
            $param = array('arg0' => $dados);
        }
        $retorno = $client->call($metodo, $param);
        return $retorno;
    }// fim __sendSOAPNFSe

    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @param	string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @param   string $appendTag : tag onde será "pendurada" a assinatura
     * @param   string $ns : namespace utilizado, normalmente "p1"
     * @return	mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid = '', $appendTag = false, $ns = '') {
        if ($tagid == '') {
            $msg = "Uma tag deve ser indicada para que seja assinada!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if ($docxml == '') {
            $msg = "Um xml deve ser passado para que seja assinado!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
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
        $xmldoc = new DOMDocument('1.0', 'utf-8');
        $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
        $xmldoc->formatOutput = false;
        // muito importante deixar ativadas as opçoes para limpar os espacos em branco
        // e as tags vazias
        if ($xmldoc->loadXML($docxml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
            $root = $xmldoc->documentElement;
        } else {
            $msg = "Erro ao carregar XML, provavel erro na passagem do parâmetro docXML!!";
            $this->__setError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //extrair a tag com os dados a serem assinados
        $node = $xmldoc->getElementsByTagName($tagid)->item(0);
        $id = trim($node->getAttribute("Id"));
        $idnome = preg_replace('/[^0-9]/', '', $id);
        //extrai os dados da tag para uma string
        $dados = $node->C14N(false, false, NULL, NULL);
        //calcular o hash dos dados
        $hashValue = hash('sha1', $dados, true);
        //converte o valor para base64 para serem colocados no xml
        $digValue = base64_encode($hashValue);
        //monta a tag da assinatura digital
        $Signature = $xmldoc->createElementNS($this->URLdsig, 'Signature');
        if (!$appendTag) {
            $root->appendChild($Signature);
        } else {
            $appendNode = $xmldoc->getElementsByTagName($appendTag)->item(0);
            $appendNode->appendChild($Signature);
        }
        $SignedInfo = $xmldoc->createElement($ns . 'SignedInfo');
        $Signature->appendChild($SignedInfo);
        //Cannocalization
        $newNode = $xmldoc->createElement($ns . 'CanonicalizationMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
        //SignatureMethod
        $newNode = $xmldoc->createElement($ns . 'SignatureMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLSigMeth);
        //Reference
        $Reference = $xmldoc->createElement($ns . 'Reference');
        $SignedInfo->appendChild($Reference);
        if (empty($id)) {
            $Reference->setAttribute('URI', '');
        } else {
            $Reference->setAttribute('URI', '#' . $id);
        }
        //Transforms
        $Transforms = $xmldoc->createElement($ns . 'Transforms');
        $Reference->appendChild($Transforms);
        //Transform
        $newNode = $xmldoc->createElement($ns . 'Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
        //Transform
        $newNode = $xmldoc->createElement($ns . 'Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
        //DigestMethod
        $newNode = $xmldoc->createElement($ns . 'DigestMethod');
        $Reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
        //DigestValue
        $newNode = $xmldoc->createElement($ns . 'DigestValue', $digValue);
        $Reference->appendChild($newNode);
        // extrai os dados a serem assinados para uma string
        $dados = $SignedInfo->C14N(false, false, NULL, NULL);
        //inicializa a variavel que irá receber a assinatura
        $signature = '';
        //executa a assinatura digital usando o resource da chave privada
        $resp = openssl_sign($dados, $signature, $pkeyid);
        //codifica assinatura para o padrao base64
        $signatureValue = base64_encode($signature);
        //SignatureValue
        $newNode = $xmldoc->createElement($ns . 'SignatureValue', $signatureValue);
        $Signature->appendChild($newNode);
        //KeyInfo
        $KeyInfo = $xmldoc->createElement($ns . 'KeyInfo');
        $Signature->appendChild($KeyInfo);
        //X509Data
        $X509Data = $xmldoc->createElement($ns . 'X509Data');
        $KeyInfo->appendChild($X509Data);
        //carrega o certificado sem as tags de inicio e fim
        $cert = $this->__cleanCerts($this->pubKEY);
        //X509Certificate
        $newNode = $xmldoc->createElement($ns . 'X509Certificate', $cert);
        $X509Data->appendChild($newNode);
        //grava na string o objeto DOM
        $docxml = $xmldoc->saveXML();
        // libera a memoria
        openssl_free_key($pkeyid);
        //retorna o documento assinado
        return $docxml;
    }


    
    /**
     * __cria_estrutura_diretorios
     * cria toda a estrutura de diretórios necessarira à NFS-e
     * @return void
     */
    private function __cria_estrutura_diretorios(){
        
        if (!is_dir($this->nfseDir)) {
            mkdir($this->nfseDir, 0777);
        }
        
        $aSubDirs = array(
			"aprovadas",
			"canceladas",
        	"lotes",
			"lotes/assinados",  
			"lotes/enviados", 
			"lotes/enviados/aprovados",
			"lotes/enviados/reprovados",
			"lotes/validados",
			"pdf",
			"temp"
        );
        
        $count = count($aSubDirs);
        for ($i = 0 ; $i < $count ; $i++){
            $local = $aSubDirs[$i];
            if (!is_dir($this->nfseDir.$local)) {
                mkdir($this->nfseDir.$local, 0777);
            }
        }

    } //fim __cria_estrutura_diretorios
    
    /**
     * __save_xml_file
     * Salva arquivo em local especificado
     * 
     * @param string $file
     * @param string $content
     * @param string $local
     */
    private function __save_xml_file($file, $content = '', $local = 'temp') {

        if ($this->save_files) {
            if (!is_dir($this->nfseDir . $local)) {
                mkdir($this->nfseDir . $local, 0777, true);
            }

            $file = $this->nfseDir . $local . DIRECTORY_SEPARATOR . $file;

            if (file_exists($file)) {
                unlink($file);
            }
            file_put_contents($file, $content);
        }
    } //fim __save_xml_file

    
    /**
     * __save_xml_assinado
     * grava arquivo na pasta lotes/assinados
     * 
     * @param string $arquivo
     * @param string $conteudo
     */
    private function __save_xml_assinado($arquivo, $conteudo) {
        $this->__save_xml_file($arquivo, $conteudo, "lotes/assinados");
    } //fim __save_xml_assinado
    
    /**
     * __save_xml_validado
     * grava arquivo na pasta lotes/validado e remove da pasta assinado
     * 
     * @param string $arquivo
     * @param string $conteudo
     */
    private function __save_xml_validado($arquivo, $conteudo) {
        $this->__save_xml_file($arquivo, $conteudo, "lotes/validados");
        @unlink($this->nfseDir . 'lotes/assinados' . DIRECTORY_SEPARATOR . $arquivo);
    } //fim __save_xml_validado

    /**
     * __save_xml_enviado
     * grava arquivo na pasta lotes/enviado e remove da pasta validado
     * 
     * @param string $arquivo
     * @param string $conteudo
     */
    function __save_xml_enviado($arquivo, $conteudo) {
        $this->__save_xml_file($arquivo, $conteudo, "lotes/enviados");
        @unlink($this->nfseDir . 'lotes/validados' . DIRECTORY_SEPARATOR . $arquivo);
    } //fim __save_xml_enviado
    
    /**
     * __get_xml_file
     * retorna string contendo xml de um arquivo especificado
     * 
     * @param string $file
     * @param string $local
     * @return mixed String com o conteudo do arquivo ou false
     */
    private function __get_xml_file($file, $local = '') {

        if (is_file($this->nfseDir . $local . DIRECTORY_SEPARATOR . $file)) {
            $file = $this->nfseDir . $local . DIRECTORY_SEPARATOR . $file;
            return file_get_contents($file);
        } else {
            return false;
        }
    }// fim __get_xml_file
    
    
    /**
     * __save_lote_aprovado
     * salva arquivo de lote no diretorio de aprovados
     * 
     * @param string $numero
     * @return void
     */
    private function __save_lote_aprovado($numero) {
        $from = $this->nfseDir . "lotes/enviados" . DIRECTORY_SEPARATOR;
        $to = $this->nfseDir . "lotes/enviados/aprovados" . DIRECTORY_SEPARATOR;
        @rename($from . $numero . ".xml", $to . $numero . ".xml");
        @rename($from . $numero . "-resp.xml", $to . $numero . "-resp.xml");
    }//fim __save_lote_aprovado

    /**
     * __save_lote_reprovado
     * 
     * @param string $numero
     */
    private function __save_lote_reprovado($numero) {
        $from = $this->nfseDir . "lotes/enviados" . DIRECTORY_SEPARATOR;
        $to = $this->nfseDir . "lotes/enviados/reprovados" . DIRECTORY_SEPARATOR;

        @rename($from . $numero . ".xml", $to . $numero . ".xml");
        @rename($from . $numero . "-resp.xml", $to . $numero . "-resp.xml");
    }//fim __save_lote_reprovado

    /**
     * __view_xml_file
     * @param string $conteudo
     * @return header text/xml
     */
    private function __view_xml_file($conteudo) {
        header("Content-Type:text/xml");
        die($conteudo);
    }//__view_xml_file

}//fim da classe NFSe
