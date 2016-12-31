<?php

namespace NFePHP\Extras;

/**
 * Classe para a impressão em PDF do Docuimento Auxiliar de NFe Consumidor
 *
 * @category  NFePHP
 * @package   NFePHP\NFe\ConvertNFe
 * @copyright Copyright (c) 2008-2015
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto Spadim <roberto at spadim dot com dot br>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 *
 * CONTRIBUIDORES (por ordem alfabetica):
 *            Roberto L. Machado <linux dot rlm at gmail dot com>
 *            Mario Almeida <mario at grupopmz dot com dot br>
 */


//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}

//ajuste do tempo limite de resposta do processo
set_time_limit(1800);

//classes utilizadas
use mPDF;
use Endroid\QrCode\QrCode;
use NFePHP\Extras\CommonNFePHP;
use NFePHP\Extras\DocumentoNFePHP;
use NFePHP\Extras\DomDocumentNFePHP;

/**
 * Classe DanfceNFePHP
 * Objetivo - impressão de NFC-e em uma unica pagina (bobina)
 */
class Danfce extends CommonNFePHP implements DocumentoNFePHP
{
    //publicas
    public $idToken;
    public $emitToken;
    public $papel;
    
    //privadas
    protected $xml; // string XML NFe
    protected $logomarca=''; // path para logomarca em jpg
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    protected $debugMode=0; //ativa ou desativa o modo de debug
    protected $nfeProc;
    protected $nfe;
    protected $infNFe;
    protected $ide;
    protected $enderDest;
    protected $ICMSTot;
    protected $imposto;
    protected $emit;
    protected $enderEmit;
    protected $qrCode;
    protected $det;
    protected $pag;
    protected $dest;
    protected $infCpl;
    protected $infAdFisco;
    protected $mPDF;
    protected $html;
    protected $css = "<style>
        body {
            font-family: Times New Roman;
            font-size: 8pt;
            background: #FFF none repeat scroll 0 0;
            margin: 8px;
        }
        h5, p {
            margin: 0pt;
        }
        table {
            border-bottom: 1px dashed #000;
        }
        table.items {
            font-size: 8pt;
            border-collapse: collapse;
            border: 3px solid #880000;
        }
        td, th {
            font-size: 7pt;
            vertical-align: top;
            text-align: center;
        }
        th {
            font-weight: bold;
        }
        table thead td, table tfoot td {
            font-size: 7pt;
            vertical-align: center;
            text-align: center;
        }
        table thead td {
            font-weight: bold;
        }
        hr {
            border-top: medium none;
            border-left: medium none;
            border-right: medium none;
            border-bottom: 1px dotted #ccc;
            color: #ccc;
            margin: 0;
        }
        .noBorder {
            border: medium none !important;
        }
        .barcode {
            padding: 1.5mm;
            margin: 0;
            vertical-align: top;
            color: #000000;
        }
        .barcodecell {
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }
        .menor {
            font-size: 6.5pt;
        }        
        .rodape {
            font-size: 5.5pt;
        }
        .tLeft {
            text-align: left;
        }
        .tCenter {
            text-align: center;
        }
        .tRight {
            text-align: right;
        }
        </style>";
    protected $imgQRCode;
    protected $urlQR = '';
    protected $urlConsulta = array(
        '11' => 'http://www.nfce.sefin.ro.gov.br/',
        '12' => 'http://sefaznet.ac.gov.br/nfce/consulta.xhtml',
        '13' => 'http://sistemas.sefaz.am.gov.br/nfceweb/formConsulta.do',
        '14' => 'https://www.sefaz.rr.gov.br/nfce/servlet/wp_consulta_nfce',
        '15' => 'https://appnfc.sefa.pa.gov.br/portal/view/consultas/nfce/consultanfce.seam',
        '16' => 'https://www.sefaz.ap.gov.br/sate/seg/SEGf_AcessarFuncao.jsp?cdFuncao=FIS_1261',
        '17' => 'http://www.sefaz.to.gov.br/nfce-portal',
        '21' => 'http://www.nfce.sefaz.ma.gov.br/portal/consultaNFe.do?method=preFilterCupom',
        '22' => 'http://webas.sefaz.pi.gov.br/nfceweb/',
        '23' => '', // webservice nfce CE não encontrado
        '24' => 'http://nfce.set.rn.gov.br/portalDFE/NFCe/ConsultaNFCe.aspx',
        '25' => 'https://www.receita.pb.gov.br/ser/servirtual/documentos-fiscais/nfc-e/consultar-nfc-e',
        '26' => 'http://nfce.sefaz.pe.gov.br/nfce-web/entradaConsNfce',
        '27' => 'http://nfce.sefaz.al.gov.br/consultaNFCe.htm',
        '28' => 'http://www.nfe.se.gov.br/portal/consultarNFCe.jsp',
        '29' => 'http://nfe.sefaz.ba.gov.br/servicos/nfce/Modulos/Geral/NFCEC_consulta_chave_acesso.aspx',
        '31' => '',// webservice nfce MG não encontrado
        '32' => 'http://app.sefaz.es.gov.br/ConsultaNFCe',
        '33' => 'http://www4.fazenda.rj.gov.br/consultaDFe/paginas/consultaChaveAcesso.faces',
        '35' => 'https://www.nfce.fazenda.sp.gov.br/NFCeConsultaPublica/Paginas/ConsultaPublica.aspx',
        '41' => 'http://www.fazenda.pr.gov.br/',
        '42' => '', // webservice nfce SC não encontrado
        '43' => 'https://www.sefaz.rs.gov.br/NFCE/NFCE-COM.aspx',
        '50' => 'http://www.dfe.ms.gov.br/nfce/',
        '52' => 'http://www.nfce.go.gov.br/post/ver/214278/consumid',
        '51' => 'https://www.sefaz.mt.gov.br/nfce/consultanfce',
        '53' => 'http://dec.fazenda.df.gov.br/NFCE/'
    );

    /**
     * __contruct
     *
     * @param string $docXML
     * @param string $sPathLogo
     * @param string $mododebug
     * @param string $idToken
     * @param string $Token
     */
    public function __construct($docXML = '', $sPathLogo = '', $mododebug = 0, $idToken = '', $emitToken = '', $urlQR = '')
    {
        if (is_numeric($mododebug)) {
            $this->debugMode = $mododebug;
        }
        if ($this->debugMode) {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        $this->papel = array(80, 'one-page');
        $this->xml          = $docXML;
        
        $this->logomarca    = $sPathLogo;
        if (!empty($this->xml)) {
            $this->dom = new DomDocumentNFePHP();
            $this->dom->loadXML($this->xml);
            $this->nfeProc    = $this->dom->getElementsByTagName("nfeProc")->item(0);
            $this->nfe        = $this->dom->getElementsByTagName("NFe")->item(0);
            $this->infNFe     = $this->dom->getElementsByTagName("infNFe")->item(0);
            $this->ide        = $this->dom->getElementsByTagName("ide")->item(0);
            $this->emit       = $this->dom->getElementsByTagName("emit")->item(0);
            $this->enderEmit  = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->det        = $this->dom->getElementsByTagName("det");
            $this->dest       = $this->dom->getElementsByTagName("dest")->item(0);
            $this->pag        = $this->dom->getElementsByTagName("pag");
            $this->imposto    = $this->dom->getElementsByTagName("imposto")->item(0);
            $this->ICMSTot    = $this->dom->getElementsByTagName("ICMSTot")->item(0);
        }
        $this->idToken = $idToken;
        $this->emitToken = $emitToken;
        if ($urlQR != '') {
            $this->urlQR = $urlQR;
        }else{
            if (isset($this->urlConsulta[$this->pSimpleGetValue($this->ide, 'cUF')])){
                $this->urlQR = $this->urlConsulta[$this->pSimpleGetValue($this->ide, 'cUF')];
            }
        }
        $this->qrCode = $this->dom->getElementsByTagName('qrCode')->item(0)->nodeValue;
        if (isset($this->dom->getElementsByTagName("infCpl")->item(0)->nodeValue)) {
            $this->infCpl = $this->dom->getElementsByTagName("infCpl")->item(0)->nodeValue;
        }
        if (isset($this->dom->getElementsByTagName("infAdFisco")->item(0)->nodeValue)) {
            $this->infAdFisco = $this->dom->getElementsByTagName("infAdFisco")->item(0)->nodeValue;
        }
    }
    
    /**
     * Returns idToken
     *
     * @return string
     */
    public function getIdToken()
    {
        return $this->idToken;
    }
    
    /**
     * Set idToken
     *
     * @param string $str
     */
    public function setIdToken($str)
    {
        $this->idToken = $str;
    }
    
    /**
     * Returns emitToken
     *
     * @return string
     */
    public function getEmitToken()
    {
        return $this->emitToken;
    }
    
    /**
     * Set emitTokem
     *
     * @param string $str
     */
    public function setEmitToken($str)
    {
        $this->emitToken = $str;
    }
    
    /**
     * Return paper size
     *
     * @return string
     */
    public function getPapel()
    {
        return $this->papel;
    }
    
    /**
     * Set papaer size
     *
     * @param string $aPap
     */
    public function setPapel($aPap)
    {
        $this->papel = $aPap;
    }
    
    /**
     * Check if exist data to print
     *
     * @return boolean
     */
    public function simpleConsistencyCheck()
    {
        if (1 == 2 || $this->xml == null || $this->infNFe == null || $this->ide == null) {
            return false;
        }
        return true;
    }
    
    /**
     * monta
     *
     * @param  string  $orientacao
     * @param  string  $papel
     * @param  string  $logoAlign
     * @param  boolean $ecoNFCe    false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
     * @return string
     */
    public function monta($orientacao = '', $papel = array(80, 'one-page'), $logoAlign = 'C', $ecoNFCe = true)
    {
        return $this->montaDANFCE($ecoNFCe);
    }
    
    /**
     * printDocument
     *
     * @param  string $nome
     * @param  string $destino
     * @param  string $printer
     * @return string
     */
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDANFCE($nome, $destino, $printer);
    }
    
    /**
     * o objetivo desta função é ler o XML e gerar o DANFE NFC-e com auxilio de conversão HTML-PDF
     *
     * @param  boolean $ecoNFCe false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
     * @return string
     */
    public function montaDANFCE($ecoNFCe = true)
    {
        //DADOS DA NF
        $dhRecbto = $nProt = '';
        if (isset($this->nfeProc)) {
            $nProt = $this->pSimpleGetValue($this->nfeProc, "nProt");
            $dhRecbto  = $this->pSimpleGetValue($this->nfeProc, "dhRecbto");
        }
        $digVal = $this->pSimpleGetValue($this->nfe, "DigestValue");
        $id = str_replace('NFe', '', $this->infNFe->getAttribute("Id"));
        $chNFe = $this->pFormat($id, "#### #### #### #### #### #### #### #### #### #### ####");
        $tpAmb = $this->pSimpleGetValue($this->ide, 'tpAmb');
        $tpEmis = $this->pSimpleGetValue($this->ide, 'tpEmis');
        $cUF = $this->pSimpleGetValue($this->ide, 'cUF');
        $nNF = $this->pSimpleGetValue($this->ide, 'nNF');
        $serieNF = str_pad($this->pSimpleGetValue($this->ide, "serie"), 3, "0", STR_PAD_LEFT);
        $dhEmi = $this->pSimpleGetValue($this->ide, "dhEmi");
        $vTotTrib = !empty($this->pSimpleGetValue($this->ICMSTot, "vTotTrib")) ? $this->pSimpleGetValue($this->ICMSTot, "vTotTrib") : 0.00;
        $vICMS = $this->pSimpleGetValue($this->ICMSTot, "vICMS");
        $vProd = $this->pSimpleGetValue($this->ICMSTot, "vProd");
        $vDesc  = $this->pSimpleGetValue($this->ICMSTot, "vDesc");
        $vOutro = $this->pSimpleGetValue($this->ICMSTot, "vOutro");
        $vNF = $this->pSimpleGetValue($this->ICMSTot, "vNF");
        $qtdItens = $this->det->length;
        $urlQR = $this->urlQR;
        //DADOS DO EMITENTE
        if (empty($this->logomarca)) {
            $this->logomarca = "data:image/png;base64,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";
        }
        $emitRazao  = $this->pSimpleGetValue($this->emit, "xNome");
        $emitCnpj   = $this->pSimpleGetValue($this->emit, "CNPJ");
        $emitCnpj   = $this->pFormat($emitCnpj, "##.###.###/####-##");
        $emitIE     = $this->pSimpleGetValue($this->emit, "IE");
        $emitFone = $this->pSimpleGetValue($this->enderEmit, "fone");
        $foneLen = strlen($emitFone);
        if ($foneLen>0) {
            $fone2 = substr($emitFone, 0, $foneLen-4);
            $fone1 = substr($emitFone, 0, $foneLen-8);
            $emitFone = '('.$fone1.') '.substr($fone2, -4).'-'.substr($emitFone, -4);
        } else {
            $emitFone = '';
        }
        $emitLgr = $this->pSimpleGetValue($this->enderEmit, "xLgr");
        $emitNro = $this->pSimpleGetValue($this->enderEmit, "nro");
        $emitCpl = $this->pSimpleGetValue($this->enderEmit, "xCpl");
        $emitBairro = $this->pSimpleGetValue($this->enderEmit, "xBairro");
        $emitCEP = $this->pFormat($this->pSimpleGetValue($this->enderEmit, "CEP"), "#####-###");
        $emitMun = $this->pSimpleGetValue($this->enderEmit, "xMun");
        $emitUF = $this->pSimpleGetValue($this->enderEmit, "UF");
        //necessário para QRCode
        $cDest  = '';
        if (isset($this->dest)) {
            $considEstrangeiro = !empty($this->dest->getElementsByTagName("idEstrangeiro")->item(0)->nodeValue)
                    ? $this->dest->getElementsByTagName("idEstrangeiro")->item(0)->nodeValue
                    : '';
            $consCPF = !empty($this->dest->getElementsByTagName("CPF")->item(0)->nodeValue)
                    ? $this->dest->getElementsByTagName("CPF")->item(0)->nodeValue
                    : '';
            $consCNPJ = !empty($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue)
                    ? $this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue
                    : '';
            $cDest = $consCPF.$consCNPJ.$considEstrangeiro; //documentos do consumidor
        }
        //DADOS PARA QRCODE
        if (!empty($this->qrCode)) {
            $this->imgQRCode = self::imgQR($this->qrCode);
        } else {
            $this->imgQRCode = self::makeQRCode(
                $chNFe,
                $urlQR,
                $tpAmb,
                $cDest,
                $dhEmi,
                $vNF,
                $vICMS,
                $digVal,
                $this->idToken,
                $this->emitToken
            );
        }
        //FORMATAÇÃO DOS CAMPOS
        $numNF = "Número ".$this->pFormat($nNF, "###.###.###");
        $tsHora = $this->pConvertTime($dhEmi);
        if ($dhRecbto == '') {
            $dhRecbto = $dhEmi;
        }
        $tsProt = $this->pConvertTime($dhRecbto);
        //$valorProdutos = number_format($vProd, 2, ",", ".");
        //$valorTotal = number_format($vNF, 2, ",", ".");
        
        /*
         * Leiaute de Impressão DANFE NFC-e em acordo com 
         * Manual Padrões Técnicos do DANFE-NFC-e e QR Code
         * Versão 3.4
         * Outubro 2015
         * Refatorado por: Chinnon Santos - 05/2016
        */
        
        $this->html = "";
        $this->html .= "<html>\n";
        $this->html .= "<head>\n";
        $this->html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
        $this->html .= $this->css;
        $this->html .= "</head>\n";
        $this->html .= "<body>\n";
        
        // ***                                                    ***//
        // *** Via Única ou Via do Consumidor em Modo Contigência ***//
        // ***                                                    ***//
        
        // -- Divisão I - Informações do Cabeçalho
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td><img src=\"$this->logomarca\" width=\"82\" ></td>\n";
        $this->html .= "<td colspan=\"2\">".htmlspecialchars($emitRazao)."<br>CNPJ:$emitCnpj I.E.:$emitIE<br>".
                htmlspecialchars($emitLgr . ", nº" . $emitNro). "<br>".
                htmlspecialchars($emitCpl) . "<br>".
                htmlspecialchars($emitBairro . ", " . $emitMun . ", " . $emitUF) . "<br>CEP: $emitCEP $emitFone</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        
        // -- Divisão II – Identificação do DANFE NFC-e
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\" class=\"tCenter\"><strong>".
                htmlspecialchars("DANFE NFC-e - DOCUMENTO AUXILIAR DA NOTA FISCAL DE CONSUMIDOR ELETRÔNICA")."</strong></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        
        // -- Divisão III – Informações de detalhes de produtos/serviços
        if (! $ecoNFCe) {
            $this->html .= self::itens($this->det);
        }
        
        // -- Divisão IV – Informações de Totais do DANFE NFC-e
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td class=\"tLeft\">Qtde. Total de Itens</td>\n";
        $this->html .= "<td class=\"tRight\">{$qtdItens}</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td class=\"tLeft\">".htmlspecialchars('Valor Total R$')."</td>\n";
        $this->html .= "<td class=\"tRight\">".number_format($vProd, 2, ',', '.')."</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        // Acréscimos (frete, seguro e outras despesas)/Desconto R$ (Exibe somente se houver!)
        $hasAD = false;
        if ($vDesc != '0.00') {
            $this->html .= "<tr>\n";
            $this->html .= "<td class=\"tLeft\">".htmlspecialchars('Desconto R$')."</td>\n";
            $this->html .= "<td class=\"tRight\">-".number_format($vDesc, 2, ',', '.')."</td>\n";
            $this->html .= "</tr>\n";
            $this->html .= "<tr>\n";
            $hasAD = true;
        }
        if ($vOutro != '0.00') {
            $this->html .= "<tr>\n";
            $this->html .= "<td class=\"tLeft\">".htmlspecialchars('Acréscimos R$')."</td>\n";
            $this->html .= "<td class=\"tRight\">".number_format($vOutro, 2, ',', '.')."</td>\n";
            $this->html .= "</tr>\n";
            $this->html .= "<tr>\n";
            $hasAD = true;
        }
        // (Total Itens - Descontos + Acréscimos) deve ser impresso apenas se existir acréscimo ou desconto
        if ($hasAD) {
            $this->html .= "<tr>\n";
            $this->html .= "<td class=\"tLeft\">".htmlspecialchars('Valor a Pagar R$')."</td>\n";
            $this->html .= "<td class=\"tRight\">".number_format($vOutro, 2, ',', '.')."</td>\n";
            $this->html .= "</tr>\n";
        }
        // Formas de Pagamentos
        $this->html .= "<tr>\n";
        $this->html .= "<th class=\"tLeft\">FORMA DE PAGAMENTO</th>\n";
        $this->html .= "<th class=\"tRight\">VALOR PAGO</th>\n";
        $this->html .= "</tr>\n";
        $this->html .= self::pagamento($this->pag);        
        $this->html .= "</table>\n";
        
        // Valor aproximado dos produtos
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td class=\"tLeft\">Tributos totais incidentes (Lei Federal 12.741/2012)</td>\n";
        $this->html .= "<td class=\"tRight\">".number_format($vTotTrib, 2, ',', '.')."</td>\n";
        $this->html .= "</tr>\n";        
        $this->html .= "</table>\n";
        
        // -- Divisão V – Área de Mensagem Fiscal
        $this->html .= "<table width=\"100%\">\n";
        if ($tpEmis != 1) {
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\"><strong>".
                    htmlspecialchars("EMITIDA EM CONTINGÊNCIA")."</strong></td>\n";
            $this->html .= "</tr>\n";
        } elseif ($tpAmb == 2) {
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\"><strong>".
                    htmlspecialchars("EMITIDA EM AMBIENTE DE HOMOLOGAÇÃO – SEM VALOR FISCAL")."<strong></td>\n";
            $this->html .= "</tr>\n";
        } elseif (!empty($this->infAdFisco)) {
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\"><strong>".
                    htmlspecialchars("INFORMAÇÕES ADICIONAIS DE INTERESSE DO FISCO")."<strong></td>\n";
            $this->html .= "<td colspan=\"3\">{$this->infAdFisco}</td>\n".
            $this->html .= "</tr>\n";
        }
        $this->html .= "</table>\n";
        
        // -- Divisão VI – Informações de Identificação da NFC-e e do Protocolo de Autorização
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\">".htmlspecialchars($numNF);
        $this->html .= " ".htmlspecialchars('Série: ')."{$serieNF}";
        if ($tpEmis == 1) {
            $this->html .= " ".htmlspecialchars('Emissão: ').date('d/m/y H:i:s', $tsHora)."</td>\n";
        } else {
            $this->html .= " ".htmlspecialchars('Emissão: ').date('d/m/y H:i:s', $tsHora);
            $this->html .= "<br><strong>Via do Consumidor</strong></td>\n";
        }
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\">Consulte pela Chave de Acesso em <a href=\"$urlQR\">$urlQR</a></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\">Chave de Acesso<br>{$chNFe}</td>\n";
        $this->html .= "</tr>\n";
        if ($tpEmis == 1) {
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\">Protocolo de autorização: {$nProt} - ".date('d/m/y H:i:s', $tsProt)."</td>\n";
            $this->html .= "</tr>\n";
        }
        $this->html .= "</table>\n";
        
        // -- Divisão VII – Informações sobre o Consumidor
        $this->html .= self::consumidor($this->dest);
        
        // -- Divisão VIII – Informações da Consulta via QR Code
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\"><img src=\"{$this->imgQRCode}\" ></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        
        // -- Divisão IX – Mensagem de Interesse do Contribuinte
        $this->html .= "<table width=\"100%\" class=\"noBorder\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\" class=\"menor tCenter\"><strong>{$this->infCpl}</strong></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
                
        // ***                                            ***//
        // *** Via do Estabelecimento em Modo Contigência ***//
        // ***                                            ***//
        
        if ($tpEmis != 1) {
            $html2via    = str_replace('Via do Consumidor', 'Via do Estabelecimento', $this->html);
            $this->html .= "<br><hr><br>\n";
            $this->html .= $html2via;
        }
        
        $this->html .= "<table width=\"100%\" class=\"noBorder\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\" class=\"rodape tCenter\">Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        
        $this->html .= "</body>\n</html>\n";
        return $id;
    }
    
    /**
     * Make pagamento block
     *
     * @param  DomDocumentNFePHP $pag
     * @return string
     */
    protected function pagamento($pag)
    {
        if (!isset($pag)) {
            return '';
        }
        //DADOS DE PAGAMENTO
        $pagHtml = "</tr>\n";
        foreach ($pag as $pagI) {
            $tPag = $this->pSimpleGetValue($pagI, "tPag");
            $tPagNome = $this->tipoPag($tPag);
            $vPag = number_format($this->pSimpleGetValue($pagI, "vPag"), 2, ",", ".");
            $card = $pagI->getElementsByTagName("card")->item(0);
            //Informação da Bandeira caso seja cartão
            if (isset($card)) {
                $tBand     = $this->pSimpleGetValue($card, "tBand");
                $tBandNome = self::getCardName($tBand);
                $pagHtml .= "<tr>\n";
                $pagHtml .= "<td class=\"tLeft\">".htmlspecialchars($tPagNome)." ({$tBandNome})</td>\n";
                $pagHtml .= "<td class=\"tRight\">{$vPag}</td>\n";
                $pagHtml .= "</tr>\n";
            } else {
                $pagHtml .= "<tr>\n";
                $pagHtml .= "<td class=\"tLeft\">".htmlspecialchars($tPagNome)."</td>\n";
                $pagHtml .= "<td class=\"tRight\">{$vPag}</td>\n";
                $pagHtml .= "</tr>\n";
            }
        } //fim foreach
        return $pagHtml;
    }
    
    /**
     * Returns card operator name
     *
     * @param  string $tBand
     * @return string
     */
    protected static function getCardName($tBand)
    {
        switch ($tBand) {
            case '01':
                $tBandNome = 'Visa';
                break;
            case '02':
                $tBandNome = 'MasterCard';
                break;
            case '03':
                $tBandNome = 'American Express';
                break;
            case '04':
                $tBandNome = 'Sorocred';
                break;
            case '99':
                $tBandNome = 'Outros';
            default:
                $tBandNome = 'Outros';
        }
        return $tBandNome;
    }
    
    /**
     * Returns type of payment
     *
     * @param  string $tPag
     * @return string
     */
    protected function tipoPag($tPag)
    {
        switch ($tPag) {
            case '01':
                $tPagNome = 'Dinheiro';
                break;
            case '02':
                $tPagNome = 'Cheque';
                break;
            case '03':
                $tPagNome = 'Cartão de Crédito';
                break;
            case '04':
                $tPagNome = 'Cartão de Débito';
                break;
            case '05':
                $tPagNome = 'Crédito Loja';
                break;
            case '10':
                $tPagNome = 'Vale Alimentação';
                break;
            case '11':
                $tPagNome = 'Vale Refeição';
                break;
            case '12':
                $tPagNome = 'Vale Presente';
                break;
            case '13':
                $tPagNome = 'Vale Combustível';
                break;
            case '99':
                $tPagNome = 'Outros';
        }
        return $tPagNome;
    }
    
    /**
     * Make itens block
     *
     * @param  DomDocumentNFePHP $det
     * @return string
     */
    protected function itens($det)
    {
        if (!isset($det)) {
            return '';
        }
        //ITENS
        $itensHtml = "<table width='100%'>\n";
        $itensHtml .= "<thead>\n";
        $itensHtml .= "<tr>\n";
        $itensHtml .= "<td>#</td>\n";
        $itensHtml .= "<td>".htmlspecialchars('CÓDIGO')."</td>\n";
        $itensHtml .= "<td>".htmlspecialchars('DESCRIÇÃO')."</td>\n";
        $itensHtml .= "<td>QTDE.</td>\n";
        $itensHtml .= "<td>UN.</td>\n";
        $itensHtml .= "<td>VL.UNIT.</td>\n";
        $itensHtml .= "<td>VL.TOTAL</td>\n";
        $itensHtml .= "</tr>\n";
        $itensHtml .= "</thead>\n";
        foreach ($det as $detI) {
            $thisItem   = $detI;
            $prod       = $thisItem->getElementsByTagName("prod")->item(0);
            $nitem      = $thisItem->getAttribute("nItem");
            $cProd      = $this->pSimpleGetValue($prod, "cProd");
            $xProd      = $this->pSimpleGetValue($prod, "xProd");
            $qCom       = number_format($this->pSimpleGetValue($prod, "qCom"), 2, ",", ".");
            $uCom       = $this->pSimpleGetValue($prod, "uCom");
            $vUnCom     = number_format($this->pSimpleGetValue($prod, "vUnCom"), 2, ",", ".");
            $vProd      = number_format($this->pSimpleGetValue($prod, "vProd"), 2, ",", ".");
            $itensHtml .=  "<tr>\n";
            $itensHtml .=  "<td class=\"tLeft\">".htmlspecialchars($nitem)."</td>\n";
            $itensHtml .=  "<td class=\"tLeft\">".htmlspecialchars($cProd)."</td>\n";
            $itensHtml .=  "<td class=\"tLeft\" colspan=\"5\">".htmlspecialchars($xProd)."</td>\n";
            $itensHtml .=  "</tr>\n";
            $itensHtml .=  "<tr>\n";
            $itensHtml .=  "<td colspan=\"3\"></td>\n";
            $itensHtml .=  "<td class=\"tRight\">$qCom</td>\n";
            $itensHtml .=  "<td>$uCom</td>\n";
            $itensHtml .=  "<td class=\"tRight\">".htmlspecialchars($vUnCom)."</td>\n";
            $itensHtml .=  "<td class=\"tRight\">$vProd</td>\n";
            $itensHtml .=  "</tr>\n";
        }
        $itensHtml .= "</table>\n";
        return $itensHtml;
    }
    
    /**
     * Make consumidor block
     *
     * @param  DomDocumentNFePHP $dest
     * @return string
     */
    protected function consumidor($dest)
    {
        //DADOS DO CONSUMIDOR
        $consHtml = '';
        $consHtml .= "<table width='100%'>\n";
        if (!isset($dest)) {
            $consHtml .= "<tr><td colspan=\"3\">".htmlspecialchars('CONSUMIDOR NÃO IDENTIFICADO').".</td></tr>\n";
        } else {
            $enderDest = $dest->getElementsByTagName("enderDest")->item(0);
            $consNome = $this->pSimpleGetValue($dest, "xNome");
            $consFone = $this->pSimpleGetValue($enderDest, "fone");
            $foneLen = strlen($consFone);
            if ($foneLen == 10) {
                $consFone = $this->pFormat($consFone, "(##) ####-####");
            } elseif ($foneLen == 11) {
                $consFone = $this->pFormat($consFone, "(##) #####-####");
            }
            $consLgr = $this->pSimpleGetValue($enderDest, "xLgr");
            $consNro = $this->pSimpleGetValue($enderDest, "nro");
            $consCpl = $this->pSimpleGetValue($enderDest, "xCpl", " - ");
            $consBairro = $this->pSimpleGetValue($enderDest, "xBairro");
            $consCEP = $this->pFormat($this->pSimpleGetValue($enderDest, "CEP"));
            $consMun = $this->pSimpleGetValue($enderDest, "xMun");
            $consUF = $this->pSimpleGetValue($enderDest, "UF");
            $consCNPJ = $this->pSimpleGetValue($dest, "CNPJ");
            $consCPF = $this->pSimpleGetValue($dest, "CPF");
            $considEstrangeiro = $this->pSimpleGetValue($dest, "idEstrangeiro");
            
            //CNPJ, CPF ou ID Estrageiro
            if (!empty($consCNPJ)) {
                $consCNPJ = $this->pFormat($consCNPJ, "##.###.###/####-##");
                $consHtml .= "<tr><td colspan=\"3\">CONSUMIDOR CNPJ: {$consCNPJ} ".
                        htmlspecialchars($consNome)."</td></tr>\n";
            } elseif (!empty($consCPF)) {
                $consCPF = $this->pFormat($consCPF, "###.###.###-##");
                $consHtml .= "<tr><td colspan=\"3\">CONSUMIDOR CPF: {$consCPF} ".
                        htmlspecialchars($consNome)."</td></tr>\n";
            } elseif (!empty($considEstrangeiro)) {
                $consHtml .= "<tr><td colspan=\"3\">CONSUMIDOR Id. Estrangeiro: {$considEstrangeiro} ".
                        htmlspecialchars($consNome)."</td></tr>\n";
            }
            if (!empty($consLgr)&&!empty($consBairro)&&!empty($consMun)&&!empty($consUF)) {
                $consHtml .= "<tr>\n";
                $consHtml .= "<td colspan=\"3\">".
                        htmlspecialchars("{$consLgr}, {$consNro}, {$consCpl}, {$consBairro}")."<br>\n".
                        htmlspecialchars("{$consMun}-{$consUF}")."<br>\n".
                        htmlspecialchars("CEP: {$consCEP} - Tel.: {$consFone}")."</td>\n";
                $consHtml .= "</tr>\n";
            }
        }
        $consHtml .= "</table>\n";
        
        return $consHtml;
    }

    /**
     * Print DANFCE
     *
     * @param  string $nome
     * @param  string $destino
     * @return bool|string
     */
    public function printDANFCE($output = 'pdf', $nome = '', $destino = 'I')
    {
        if ($output == 'pdf') {
            //montagem do pdf
            $m = 2.1; //Margens 2.1mm = 8px do formato HTML
            if (is_array($this->papel) && strtolower($this->papel[1])=='one-page') {
                $mpdf=new mPDF('', array($this->papel[0], 841.89), 0, '', $m, $m, $m, 0, 0, 'P');
                $mpdf->useCoreFontsOnly = true;
                $mpdf->WriteHTML($this->html, 0, true, false);
                $this->papel=array($this->papel[0], $mpdf->y + $m);
            }
            $this->mpdf=new mPDF('', $this->papel, 0, '', $m, $m, $m, 0, 0, 'P');
            $this->mpdf->WriteHTML($this->html);
            return $this->mpdf->Output($nome, $destino);
        } else {
            echo $this->html;
        }
        return true;
    }
    
    /**
     * str2Hex
     * Converte string para haxadecimal ASCII
     *
     * @param  string $str
     * @return string
     */
    protected static function str2Hex($str)
    {
        if ($str == '') {
            return '';
        }
        $hex = "";
        $iCount = 0;
        do {
            $hex .= sprintf("%02x", ord($str{$iCount}));
            $iCount++;
        } while ($iCount < strlen($str));
        return $hex;
    }
    
    /**
     * hex2Str
     * Converte hexadecimal ASCII para string
     *
     * @param  string $str
     * @return string
     */
    protected static function hex2Str($str)
    {
        if ($str == '') {
            return '';
        }
        $bin = "";
        $iCount = 0;
        do {
            $bin .= chr(hexdec($str{$iCount}.$str{($iCount + 1)}));
            $iCount += 2;
        } while ($iCount < strlen($str));
        return $bin;
    }
    
    /**
     * Mount QRCode URL
     *
     * @param  string $chNFe
     * @param  string $url
     * @param  string $tpAmb
     * @param  string $cDest
     * @param  string $dhEmi
     * @param  string $vNF
     * @param  string $vICMS
     * @param  string $digVal
     * @param  string $idToken
     * @param  string $token
     * @return string
     */
    protected function makeQRCode(
        $chNFe,
        $url,
        $tpAmb,
        $cDest = '',
        $dhEmi = '',
        $vNF = '',
        $vICMS = '',
        $digVal = '',
        $idToken = '000001',
        $token = ''
    ) {
        $nVersao = '100'; //versão do QRCode
        $dhHex = self::str2Hex($dhEmi);
        $digHex = self::str2Hex($digVal);
        $seq = '';
        $seq .= 'chNFe=' . $chNFe;
        $seq .= '&nVersao=' . $nVersao;
        $seq .= '&tpAmb=' . $tpAmb;
        if ($cDest != '') {
            $seq .= '&cDest=' . $cDest;
        }
        $seq .= '&dhEmi=' . strtolower($dhHex);
        $seq .= '&vNF=' . $vNF;
        $seq .= '&vICMS=' . $vICMS;
        $seq .= '&digVal=' . strtolower($digHex);
        $seq .= '&cIdToken=' . $idToken;
        //o hash code é calculado com o Token incluso
        $hash = sha1($seq.$token);
        $seq .= '&cHashQRCode='. strtoupper($hash);
        if (strpos($url, '?') === false) {
            $seq = $url.'?'.$seq;
        } else {
            $seq = $url.''.$seq;
        }
        return self::imgQR($seq);
    }
    
    /**
     * Save QRCode image and returns path to file
     *
     * @param  string $seq
     * @return string
     */
    private function imgQR($seq, $dimensao = 165)
    {
        $dimensao = $dimensao<100?100:$dimensao; //Dimensão mínima para leitura 100px = 26.4mm
        $dimensao = $dimensao>230?230:$dimensao; //Dimensão máxima para layout 230px = 60.8mm
        $quietZone = $dimensao<=100?12:$dimensao*0.10; // Acima de 25mm quiet zone de 10%
        $qrCode = new QrCode();
        $qrCode->setText($seq)
            ->setSize($dimensao)
            ->setPadding($quietZone)
            ->setErrorCorrection('low')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('')
            ->setLabelFontSize(16);
        $img = $qrCode->get();
        
        //Retorno src em Base64 para melhor utilização em ambos os formatos (PDF/HTML)
        //evita falhas de endereço da imagem e reduz o I/O no disco porém
        //aumenta o uso de memoria do servidor...
        $src = "data: image/png;base64,".base64_encode($img);
        return $src;
        
        //$filename = PATH_ROOT.'../images/'.date('YmdHis').'.jpg';
        //file_put_contents($filename, $img);
        //return $filename;
    }
}
