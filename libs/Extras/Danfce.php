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
use NFePHP\NFe\ToolsNFe;

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
        }
        $this->qrCode = $this->dom->getElementsByTagName('qrCode')->item(0)->nodeValue;
        $this->infCpl = $this->dom->getElementsByTagName("infCpl")->item(0)->nodeValue;
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
        $toolsNFe = new ToolsNFe('../../config/config.json');
        //DADOS DA NF
        $dhRecbto = $nProt = '';
        if (isset($this->nfeProc)) {
            $nProt = $this->pSimpleGetValue($this->nfeProc, "nProt");
            $dhRecbto  = $this->pSimpleGetValue($this->nfeProc, "dhRecbto");
        }
        $digVal = $this->pSimpleGetValue($this->nfe, "DigestValue");
        $chNFe = str_replace('NFe', '', $this->infNFe->getAttribute("Id"));
        $chNFe = $this->pFormat($chNFe, "#### #### #### #### #### #### #### #### #### #### ####");
        $tpAmb = $this->pSimpleGetValue($this->ide, 'tpAmb');
        $tpEmis = $this->pSimpleGetValue($this->ide, 'tpEmis');
        $cUF = $this->pSimpleGetValue($this->ide, 'cUF');
        $nNF = $this->pSimpleGetValue($this->ide, 'nNF');
        $serieNF = str_pad($this->pSimpleGetValue($this->ide, "serie"), 3, "0", STR_PAD_LEFT);
        $dhEmi = $this->pSimpleGetValue($this->ide, "dhEmi");
        $vTotTrib = $this->pSimpleGetValue($this->ICMSTot, "vTotTrib");
        $vICMS = $this->pSimpleGetValue($this->ICMSTot, "vICMS");
        $vProd = $this->pSimpleGetValue($this->ICMSTot, "vProd");
        $vDesc  = $this->pSimpleGetValue($this->ICMSTot, "vDesc");
        $vOutro = $this->pSimpleGetValue($this->ICMSTot, "vOutro");
        $vNF = $this->pSimpleGetValue($this->ICMSTot, "vNF");
        $qtdItens = $this->det->length;
        if ($this->urlQR == '') {
            //Busca no XML a URL da Consulta
            $urlQR = $toolsNFe->zGetUrlQR($cUF, $tpAmb);
        } else {
            $urlQR = $this->urlQR;
        }
        //DADOS DO EMITENTE
        if (empty($this->logomarca)) {
            $image = $toolsNFe->aConfig['aDocFormat']->pathLogoNFCe;
            $imageData = base64_encode(file_get_contents($image));
            $this->logomarca = "data: ".mime_content_type($image).";base64,{$imageData}";
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
        
        $this->html .= "</body>\n</html>\n";
        return $chNFe;
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
            $itensHtml .=  "<td class=\"tLeft\">".htmlspecialchars($xProd)."</td>\n";
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
