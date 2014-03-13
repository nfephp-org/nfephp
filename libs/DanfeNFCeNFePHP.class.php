<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela
 * Fundação para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @category  NFePHP
 * @package   NFePHP
 * @name      DanfceNFePHP.class.php
 * @copyright 2009-2013 &copy; NFePHP
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 *            http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @version   GIT: 1.11
 * @link      http://www.nfephp.org/
 * @author    Roberto Spadim <roberto at spadim dot com dot br>
 * 
 * 
 * CONTRIBUIDORES (por ordem alfabetica):
 *            Roberto L. Machado <linux dot rlm at gmail dot com>   
 *            Mario Almeida <mario at grupopmz dot com dot br>
 * 
 */

//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}

//ajuste do tempo limite de resposta do processo
set_time_limit(1800);

//classes utilizadas
require_once 'mpdf'.DIRECTORY_SEPARATOR.'mpdf.php';
require_once 'qrcode'.DIRECTORY_SEPARATOR.'qrcode.class.php';
require_once 'CommonNFePHP.class.php';
require_once 'DocumentoNFePHP.interface.php';

/**
 * Classe DanfceNFePHP
 * Objetivo - impressão de NFC-e em uma unica pagina (bobina)
 */
class DanfeNFCeNFePHP extends CommonNFePHP implements DocumentoNFePHP
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
    protected $det;
    protected $pag;
    protected $dest;
    protected $mPDF;
    protected $html;
    protected $imgQRCode;
    protected $urlConsulta = array(
        '1'=> array(
            '12'=> array('chave'=>'','QR'=>'http://www.sefaznet.ac.gov.br/nfe/NFe.jsp?opc=3'),
            '13'=> array(
                'chave'=>'http://sistemas.sefaz.am.gov.br/nfceweb/formConsulta.do',
                'QR'=>'http://sistemas.sefaz.am.gov.br/nfceweb/consultarNFCe.jsp'),
            '21'=> array('chave'=>'','QR'=>'http://www.nfe.ma.gov.br/portal/consultarNFCe.jsp'),
            '51'=> array('chave'=>'','QR'=>'http://www.nfe.sefaz.mt.gov.br/portal/consultarNFCe.jsp'),
            '24'=> array('chave'=>'','QR'=>'http://www.nfe.rn.gov.br/portal/consultarNFCe.jsp'),
            '43'=> array('chave'=>'','QR'=>'https://www.sefaz.rs.gov.br/NFCE/NFCE-COM.aspx'),
            '28'=> array('chave'=>'','QR'=>'http://www.nfe.se.gov.br/portal/consultarNFCe.jsp')
        ),
        '2' => array(
            '12'=> array('chave'=>'','QR'=>'http://hml.sefaznet.ac.gov.br'),
            '13'=> array(
                'chave'=>'http://sistemas.sefaz.am.gov.br/nfceweb/formConsulta.do',
                'QR'=>'http://homnfe.sefaz.am.gov.br/nfceweb/consultarNFCe.jsp'),
            '21'=> array('chave'=>'','QR'=>'http://www.hom.nfe.ma.gov.br/portal/consultarNFCe.jsp'),
            '51'=> array('chave'=>'','QR'=>'http://www.hom.nfe.sefaz.mt.gov.br/portal/consultarNFCe.jsp'),
            '24'=> array('chave'=>'','QR'=>'http://www.hom.nfe.rn.gov.br/portal/consultarNFCe.jsp'),
            '43'=> array('chave'=>'','QR'=>'https://www.sefaz.rs.gov.br/NFCE/NFCE-COM.aspx'),
            '28'=> array('chave'=>'','QR'=>'http://www.hom.nfe.se.gov.br/portal/consultarNFCe.jsp')
        ));

    /**
     * 
     * @param type $docXML
     * @param type $sPathLogo
     * @param type $mododebug
     * @param type $idToken
     * @param type $Token
     */
    public function __construct($docXML = '', $sPathLogo = '', $mododebug = 0, $idToken = '', $emitToken = '')
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
            $this->dom = new DomDocument;
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
        
    } //fim __construct

    public function getIdToken()
    {
        return $this->idToken;
    }
    
    public function setIdToken($str)
    {
        $this->idToken = $str;
    }
    
    public function getEmitToken()
    {
        return $this->emitToken;
    }
    
    public function setEmitToken($str)
    {
        $this->emitToken = $str;
    }
    
    public function getPapel()
    {
        return $this->papel;
    }
    
    public function setPapel($aPap)
    {
        $this->papel = $aPap;
    }
    
    public function simpleConsistencyCheck()
    {
        if (1 == 2 || $this->xml == null || $this->infNFe == null || $this->ide == null) {
            return false;
        }
        return true;
    } //fim simpleConsistencyCheck
    
    /**
     * 
     * @param type $orientacao
     * @param type $papel
     * @param type $logoAlign
     * @return type
     */
    public function monta ($orientacao = '', $papel = array(80, 'one-page'), $logoAlign = 'C', $detalhes = false)
    {
        return $this->montaDANFE($detalhes);
    }//fim monta
    
    /**
     * 
     * @param type $nome
     * @param type $destino
     * @param type $printer
     * @return type
     */
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDANFE($nome, $destino, $printer);
    }//fim printDocument
    
    /**
     * o objetivo desta função é ler o XML e gerar o DANFE com auxilio de conversão HTML-PDF
     * @param type $detalhes
     * @return type
     */
    public function montaDANFE($detalhes = false)
    {
        //DADOS DA NF
        $nProt = $this->__simpleGetValue($this->nfeProc, "nProt");
        $dhRecbto  = $this->__simpleGetValue($this->nfeProc, "dhRecbto");
        $digVal = $this->__simpleGetValue($this->nfe, "DigestValue");
        $chNFe = str_replace('NFe', '', $this->infNFe->getAttribute("Id"));
        $tpAmb = $this->__simpleGetValue($this->ide, 'tpAmb');
        $cUF = $this->__simpleGetValue($this->ide, 'cUF');
        $nNF = $this->__simpleGetValue($this->ide, 'nNF');
        $serieNF = str_pad($this->__simpleGetValue($this->ide, "serie"), 3, "0", STR_PAD_LEFT);
        $dhEmi = $this->__simpleGetValue($this->ide, "dhEmi");
        $vTotTrib = $this->__simpleGetValue($this->imposto, "vTotTrib");
        $vProd = $this->__simpleGetValue($this->ICMSTot, "vProd");
        $vNF = $this->__simpleGetValue($this->ICMSTot, "vNF");
        $vDesc  = $this->__simpleGetValue($this->ICMSTot, "vDesc");
        $vICMS = $this->__simpleGetValue($this->ICMSTot, "vICMS");
        $qtdItens = $this->det->length;
        $urlChave = $this->urlConsulta[$tpAmb][$cUF]['chave'];
        $urlQR = $this->urlConsulta[$tpAmb][$cUF]['QR'];
        
        //DADOS DO EMITENTE
        $emitRazao  = $this->__simpleGetValue($this->emit, "xNome");
        $emitCnpj   = $this->__simpleGetValue($this->emit, "CNPJ");
        $emitCnpj   = $this->__format($emitCnpj, "##.###.###/####-##");
        $emitIE     = $this->__simpleGetValue($this->emit, "IE");
        $emitFone = $this->__simpleGetValue($this->enderEmit, "fone");
        $foneLen = strlen($emitFone);
        if ($foneLen>0) {
            $fone2 = substr($emitFone, 0, $foneLen-4);
            $fone1 = substr($emitFone, 0, $foneLen-8);
            $emitFone = '('.$fone1.') '.substr($fone2, -4).'-'.substr($emitFone, -4);
        } else {
            $emitFone = '';
        }
        $emitLgr = $this->__simpleGetValue($this->enderEmit, "xLgr");
        $emitNro = $this->__simpleGetValue($this->enderEmit, "nro");
        $emitCpl = $this->__simpleGetValue($this->enderEmit, "xCpl", " - ");
        $emitBairro = $this->__simpleGetValue($this->enderEmit, "xBairro");
        $emitCEP = $this->__format($this->__simpleGetValue($this->enderEmit, "CEP"), "#####-###");
        $emitMun = $this->__simpleGetValue($this->enderEmit, "xMun");
        $emitUF = $this->__simpleGetValue($this->enderEmit, "UF");

        //pag
        $tPagNome = '';
        $pagHtml = $this->pagamento($this->pag, $tPagNome);
        
        //IMPOSTOS
        $impHtml = "<table width=\"100%\">\n";
        $impHtml .= "<tr>\n";
        $impHtml .= "<td align=\"left\">".htmlspecialchars('VALOR PRODUTOS/SERVIÇOS')."</td>\n";
        $impHtml .= "<td align=\"right\">".number_format($vProd, 2, ',', '.')."</td>\n";
        $impHtml .= "</tr>\n";
        $impHtml .= "<tr>\n";
        $impHtml .= "<td align=\"left\">DESCONTOS</td>\n";
        $impHtml .= "<td align=\"right\">".number_format($vDesc, 2, ',', '.')."</td>\n";
        $impHtml .= "</tr>\n";
        $impHtml .= "<tr>\n";
        $impHtml .= "<td align='left'>VALOR TOTAL</td>\n";
        $impHtml .= "<td align='right'>".number_format($vNF, 2, ',', '.')."</td>\n";
        $impHtml .= "</tr>\n";
        $impHtml .= "</table>\n";
        
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
        $idToken = '';
        $Token = '';
        $this->imgQRCode = $this->makeQRCode(
            $chNFe,
            $urlQR,
            $tpAmb,
            $cDest,
            $dhEmi,
            $vNF,
            $vICMS,
            $digVal,
            $idToken,
            $Token
        );

        //FORMATAÇÃO DOS CAMPOS
        $numNF = "NFCe nº ".$this->__format($nNF, "###.###.###");
        $tsHora = $this->__convertTime($dhEmi);
        $tsProt = $this->__convertTime($dhRecbto);
        $valorProdutos = number_format($vProd, 2, ",", ".");
        $valorTotal = number_format($vNF, 2, ",", ".");
        
        //CABEÇALHO
        $this->html = "";
        $this->html .= "<html>\n";
        $this->html .= "<head>\n";
        $this->html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
        $this->html .= "<style>";
        $this->html .= "body {";
        $this->html .= "    font-family: Times New Roman;";
        $this->html .= "    font-size: 8pt;";
        $this->html .= "    background: transparent url(\'bgbarcode.png\') repeat-y scroll left top;";
        $this->html .= "}";
        $this->html .= "h5, p {";
        $this->html .= "    margin: 0pt;";
        $this->html .= "}";
        $this->html .= "table.items {";
        $this->html .= "    font-size: 8pt;";
        $this->html .= "    border-collapse: collapse;";
        $this->html .= "    border: 3px solid #880000;";
        $this->html .= "}";
        $this->html .= "td {";
        $this->html .= "    font-size: 7pt;";
        $this->html .= "    vertical-align: top;";
        $this->html .= "    text-align: center;";
        $this->html .= "}";
        $this->html .= "table thead td {";
        $this->html .= "    background-color: #EEEEEE;";
        $this->html .= "    text-align: center;";
        $this->html .= "}";
        $this->html .= "table tfoot td { background-color: #AAFFEE;";
        $this->html .= "    text-align: center;";
        $this->html .= "}";
        $this->html .= ".barcode {";
        $this->html .= "    padding: 1.5mm;";
        $this->html .= "    margin: 0;";
        $this->html .= "    vertical-align: top;";
        $this->html .= "    color: #000000;";
        $this->html .= "}";
        $this->html .= ".barcodecell {";
        $this->html .= "    text-align: center;";
        $this->html .= "    vertical-align: middle;";
        $this->html .= "    padding: 0;";
        $this->html .= "}";
        $this->html .= ".menor { font-size: 7pt; text-align: center; }";
        $this->html .= "</style>\n";
        $this->html .= "</head>\n";
        $this->html .= "<body>\n";
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td><h2><i>NFC-e</i></h2><br><img src=\"../images/logo.jpg\" width=\"82\" ></td>\n";
        $this->html .= "<td colspan=\"2\">".htmlspecialchars($emitRazao)."<br>CNPJ:$emitCnpj I.E.:$emitIE<br>".
                htmlspecialchars($emitLgr . ", " . $emitNro). "<br>".
                htmlspecialchars($emitCpl) . "<br>".
                htmlspecialchars($emitBairro . ", " . $emitMun . ", " . $emitUF) . "<br>CEP: $emitCEP $emitFone</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td colspan=\"3\" class=\"menor\">".
                htmlspecialchars("DANFE NFC-E Nota Fiscal Eletrônica para Consumidor Final")."</td>\n";
        $this->html .= "</tr>\n";
        if ($tpAmb == 1) {
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\" class=\"menor\"><b>".
                    htmlspecialchars("NFC-e não permite aproveitamento de crédito de ICMS")."<b></td>\n";
            $this->html .= "</tr>\n";
        } else {
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\" class=\"menor\">NFC-e Emitida em Ambiente de Testes</td>\n";
            $this->html .= "</tr>\n";
            $this->html .= "<tr>\n";
            $this->html .= "<td colspan=\"3\" ><b>SEM VALOR FISCAL</b></td>\n";
            $this->html .= "</tr>\n";
        }
        $this->html .= "</table>\n";
        //DADOS DA NOTA
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td>".htmlspecialchars($numNF)."</td>\n";
        $this->html .= "<td>Série: $serieNF</td>\n";
        $this->html .= "<td>".date('d/m/Y H:i:s', $tsHora)."</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        //CHAVE DE ACESSO
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td  colspan=\"3\">Consulte pela Chave de Acesso em <a href=\"$urlChave\" >".
            "$urlChave</a></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td  colspan=\"3\">Chave de Acesso<br>$chNFe</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td  colspan=\"3\"><b>Consulta via leitor QR Code</b></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td  colspan=\"3\"><img src=\"./".$this->imgQRCode."\" ></td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        //TOTAIS DA NF
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td align=\"left\">QTDADE TOTAL DE ITENS</td>\n";
        $this->html .= "<td align=\"right\">$qtdItens</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td align=\"left\">VALOR TOTAL R$</td>\n";
        $this->html .= "<td align=\"right\">".number_format($vNF, 2, ',', '.')."</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td align=\"left\">FORMA DE PAGAMENTO</td>\n";
        $this->html .= "<td align=\"right\">VALOR PAGO</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td align=\"left\">$tPagNome</td>\n";
        $this->html .= "<td align=\"right\">".number_format($vNF, 2, ',', '.')."</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td align=\"left\">Informação dos Tributos Incidentes<br>(Lei Federal 12.741/2012) </td>\n";
        $this->html .= "<td align=\"right\">".number_format($vTotTrib, 2, ',', '.')."</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";

        //BLOCO DE DETALHES
        if ($detalhes) {
            //ITENS
            $this->html .= $this->itens($this->det);
            //IMPOSTOS
            $this->html .= $impHtml;
            //PAGAMENTOS
            $this->html .= $pagHtml;
            //CONSUMIDOR
            $this->html .= $this->consumidor($this->dest);
        }
        $this->html .= "<table width=\"100%\">\n";
        $this->html .= "<tr>\n";
        $this->html .= "<td>Prot. Autorização: $nProt - ".date('d/m/Y H:i:s', $tsProt)."</td>\n";
        $this->html .= "</tr>\n";
        $this->html .= "</table>\n";
        $this->html .= "</body>\n</html>\n";
        return $chNFe;
    }//fim da função montaDANFE

    protected function pagamento($pag, &$tPnome)
    {
        if (!isset($pag)) {
            return '';
        }
        //DADOS DE PAGAMENTO
        $oldtBand = '';
        $oldtPag = '';
        $pagHtml = "<table width=\"100%\">\n<tr>";
        $pagHtml .= "<td align=\"left\">PAGAMENTOS</td>\n";
        $pagHtml .= "<td></td></tr>\n";
        foreach ($pag as $pagI) {
            $tPag = $this->__simpleGetValue($pagI, "tPag");
            $tPagNome = $this->tipoPag($tPag);
            $tPnome = $tPagNome;
            $vPag = number_format($this->__simpleGetValue($pagI, "vPag"), 2, ",", ".");
            $card = $pagI->getElementsByTagName("card")->item(0);
            //cartão
            if (isset($card)) {
                $cardCNPJ = $this->__simpleGetValue($card, "CNPJ");
                $tBand    = $this->__simpleGetValue($card, "tBand");
                //$cAut = $this->__simpleGetValue($card, "cAut");
                $tBandNome = self::getCardName($tBand);
            }
            if ($tPag != $oldtPag) {
                $pagHtml .= "<tr>\n";
                $pagHtml .= "<td align=\"left\">".htmlspecialchars($tPagNome)."</td>\n";
                $pagHtml .= "<td align=\"right\">$vPag</td>\n";
                $pagHtml .= "</tr>\n";
                $oldtPag = $tPag;
            }
            if ($cardCNPJ != '' && $oldtBand != $tBand) {
                $pagHtml .= "<tr>\n";
                $pagHtml .= "<td align=\"left\">".htmlspecialchars($tBandNome)."</td>\n";
                $pagHtml .= "<td align=\"right\">".$this->__format($cardCNPJ, "##.###.###/####-##")."</td>\n";
                $pagHtml .= "</tr>\n";
                $oldtBand = $tBand;
            }
        } //fim foreach
        $pagHtml .= "</table>\n";
        return $pagHtml;
    }

    protected static function getCardName($tBand)
    {
        switch ($tBand) {
            case '01':
                $tBandNome = 'VISA';
                break;
            case '02':
                $tBandNome = 'MASTERCARD';
                break;
            case '03':
                $tBandNome = 'AMERICAM EXPRESS';
                break;
            case '04':
                $tBandNome = 'SOROCRED';
                break;
            case '99':
                $tBandNome = 'OUTROS';
        }
        return $tBandNome;
    }//fim getCardName
    
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
    
    protected function itens($det)
    {
        if (!isset($det)) {
            return '';
        }
        //ITENS
        $itensHtml = "<table width='100%'>\n";
        $itensHtml .= "<tr>\n";
        $itensHtml .= "<td>#</td>\n";
        $itensHtml .= "<td>".htmlspecialchars('CÓDIGO')."</td>\n";
        $itensHtml .= "<td>".htmlspecialchars('DESCRIÇÃO')."</td>\n";
        $itensHtml .= "<td>QUANT.</td>\n";
        $itensHtml .= "<td>UN.</td>\n";
        $itensHtml .= "<td>VL.UNIT.</td>\n";
        $itensHtml .= "<td>VL.TOTAL</td>\n";
        $itensHtml .= "</tr>\n";
        foreach ($det as $detI) {
            $thisItem   = $detI;
            $prod       = $thisItem->getElementsByTagName("prod")->item(0);
            $nitem      = $thisItem->getAttribute("nItem");
            $cProd      = $this->__simpleGetValue($prod, "cProd");
            $xProd      = $this->__simpleGetValue($prod, "xProd");
            $qCom       = number_format($this->__simpleGetValue($prod, "qCom"), 2, ",", ".");
            $uCom       = $this->__simpleGetValue($prod, "uCom");
            $vUnCom     = number_format($this->__simpleGetValue($prod, "vUnCom"), 2, ",", ".");
            $vProd      = number_format($this->__simpleGetValue($prod, "vProd"), 2, ",", ".");
            $itensHtml .=  "<tr>\n";
            $itensHtml .=  "<td align=\"left\">".htmlspecialchars($nitem)."</td>\n";
            $itensHtml .=  "<td align=\"left\">".htmlspecialchars($cProd)."</td>\n";
            $itensHtml .=  "<td align=\"left\">".htmlspecialchars($xProd)."</td>\n";
            $itensHtml .=  "<td align=\"right\">$qCom</td>\n";
            $itensHtml .=  "<td>$uCom</td>\n";
            $itensHtml .=  "<td align=\"right\">".htmlspecialchars($vUnCom)."</td>\n";
            $itensHtml .=  "<td align=\"right\">$vProd</td>\n";
            $itensHtml .=  "</tr>\n";
        }
        $itensHtml .= "</table>\n";
        return $itensHtml;
    }//fim itens

    protected function consumidor($dest)
    {
        //DADOS DO CONSUMIDOR
        if (!isset($dest)) {
            return '';
        }
        $enderDest = $dest->getElementsByTagName("enderDest")->item(0);
        $consNome = $this->__simpleGetValue($enderDest, "xNome");
        $consFone = $this->__simpleGetValue($enderDest, "fone");
        $foneLen = strlen($consFone);
        if ($foneLen > 0) {
            $fone2 = substr($consFone, 0, $foneLen-4);
            $fone1 = substr($consFone, 0, $foneLen-8);
            $consFone = '('.$fone1.') '.substr($fone2, -4).'-'.substr($consFone, -4);
        } else {
            $consFone = '';
        }
        $consLgr = $this->__simpleGetValue($enderDest, "xLgr");
        $consNro = $this->__simpleGetValue($enderDest, "nro");
        $consCpl = $this->__simpleGetValue($enderDest, "xCpl", " - ");
        $consBairro = $this->__simpleGetValue($enderDest, "xBairro");
        $consCEP = $this->__format($this->__simpleGetValue($enderDest, "CEP"));
        $consMun = $this->__simpleGetValue($enderDest, "xMun");
        $consUF = $this->__simpleGetValue($enderDest, "UF");
        $considEstrangeiro = $this->__simpleGetValue($dest, "idEstrangeiro");
        $consCPF = $this->__simpleGetValue($dest, "CPF");
        $consCNPJ = $this->__simpleGetValue($dest, "CNPJ");
        $consDoc = $consCPF.$consCNPJ.$considEstrangeiro; //documentos do consumidor
        
        $consHtml = '';
        $consHtml .= "<table width='100%'>\n";
        $consHtml .= "<tr>\n<td>CONSUMIDOR</td>\n</tr>\n";
        $consHtml .= "<tr>\n<td>CNPJ/CPF/ID Estrangeiro : ".htmlspecialchars($consDoc)."</td>\n</tr>\n";
        $consHtml .= "<tr>\n<td>".htmlspecialchars($consNome)."</td>\n</tr>\n";
        $consHtml .= "<tr>\n";
        $consHtml .= "<td>".htmlspecialchars($consLgr.", ".$consNro.$consCpl)."<BR>\n".
                htmlspecialchars($consBairro." - ".$consCEP)."<BR>\n".
                htmlspecialchars($consMun." - ".$consUF." "."Fone/Fax: ".$consFone)."</td>\n";
        $consHtml .= "</tr>\n";
        $consHtml .= "</table>\n";
        return $consHtml;
    }//fim consumidor

    /**
     * 
     * @param type $nome
     * @param type $destino
     * @return type
     */
    public function printDANFE($output = 'pdf', $nome = '', $destino = 'I')
    {
        
        if ($output == 'pdf') {
            //montagem do pdf
            if (is_array($this->papel) && strtolower($this->papel[1])=='one-page') {
                $mpdf=new mPDF('', array($this->papel[0], 841.89), 0, '', 0, 0, 0, 0, 0, 'P');
                $mpdf->useCoreFontsOnly = true;
                $mpdf->WriteHTML($this->html, 0, true, false);
                $this->papel=array($this->papel[0], $mpdf->y);
            }
            $this->mpdf=new mPDF('', $this->papel, 0, '', 0, 0, 0, 0, 0, 'P');
            $this->mpdf->WriteHTML($this->html);
            if (is_file($this->imgQRCode)) {
                unlink($this->imgQRCode);
            }
            $this->mpdf->Output($nome, $destino);
            
        } else {
            echo $this->html;
            if (is_file($this->imgQRCode)) {
                unlink($this->imgQRCode);
            }
        }
        return true;
    }//fim printDANFE
    
    /**
     * str2Hex
     * Converte string para haxadecimal ASCII
     * @param type $str
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
    }//fim str2Hex
    
    /**
     * hex2Str
     * Converte hexadecimal ASCII para string
     * @param type $str
     * @return type
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
    }//fim hex2Str

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
        $Token = ''
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
        $seq .= '&dhEmi=' . strtoupper($dhHex);
        $seq .= '&vNF=' . $vNF;
        $seq .= '&vICMS=' . $vICMS;
        $seq .= '&digVal=' . strtoupper($digHex);
        $seq .= '&cIdToken=' . $idToken.$Token;
        //o hash code é calculado com o Token incluso
        $hash = sha1($seq);
        $seq .= '&cHashQRCode='.$hash;
        if (strpos($url, '?') === false) {
            $seq = $url.'?'.$seq;
        } else {
            $seq = $url.'&'.$seq;
        }
        //remove o Token da mensagem
        $seq = str_replace($Token, '', $seq);
        $imgQrCode = new QRcode($seq, 'M');
        $filename = $chNFe.date('YmdHis').'.png';
        $imgQrCode->displayPNG(200, array(255,255,255), array(0,0,0), $filename, 0);
        return $filename;
        
    }//fim makeQRCode
}//fim Classe
