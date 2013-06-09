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
 * @name      DanfeNFCeNFePHP.class.php
 * @author    Roberto Spadim <roberto at spadim dot com dot br>
 * @copyright 2009-2013 &copy; NFePHP
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 *            http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @version   GIT: 1.00
 * @link      http://www.nfephp.org/
 *
 */



//ajuste do tempo limite de resposta do processo
require_once 'MPDF56'.DIRECTORY_SEPARATOR.'mpdf.php';
require_once 'qrcode'.DIRECTORY_SEPARATOR.'qrcode.class.php';
require_once 'CommonNFePHP.class.php';
require_once 'DocumentoNFePHP.interface.php';
//require dirname(__FILE__).DIRECTORY_SEPARATOR.'BarCode'.DIRECTORY_SEPARATOR.'php-barcode.php';

/**
 * Classe DanfeNFCeNFePHP
 *  Objetivo - impressão de NFC-e em uma unica pagina (bobina)
 */
class DanfeNFCeNFePHP extends CommonNFePHP implements DocumentoNFePHP
{
     //privadas
    protected $xml; // string XML NFe
    protected $logomarca=''; // path para logomarca em jpg
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    protected $debugMode=0; //ativa ou desativa o modo de debug



    protected $emit;
    protected $enderEmit;
    protected $det;
    protected $pag;
    protected $dest;
    protected $enderDest;
    protected $ICMSTot;
    protected $infNFe;
    protected $ide;
    protected $nfeProc;

    protected $mPDF;

    /**
     *__construct
     * @package NFePHP
     * @name __construct
     * @version 1.00
     * @param string $docXML Arquivo XML da NFe (com ou sem a tag nfeProc)
     * @param number $mododebug 0-Não 1-Sim e 2-nada (2 default)
     */
    public function __construct($docXML = '', $mododebug = 0)
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
        $this->xml          = $docXML;
        $this->logomarca    = $sPathLogo;
        if (!empty($this->xml)) {
            $this->dom = new DomDocument;
            $this->dom->loadXML($this->xml);
            $this->nfeProc    = $this->dom->getElementsByTagName("nfeProc")->item(0);
            $this->infNFe     = $this->dom->getElementsByTagName("infNFe")->item(0);
            $this->ide        = $this->dom->getElementsByTagName("ide")->item(0);
            $this->emit       = $this->dom->getElementsByTagName("emit")->item(0);
            $this->enderEmit  = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->det        = $this->dom->getElementsByTagName("det");
            $this->dest       = $this->dom->getElementsByTagName("dest")->item(0);
            $this->enderDest  = $this->dom->getElementsByTagName("enderDest")->item(0);
            $this->pag        = $this->dom->getElementsByTagName("pag");
            $this->ICMSTot    = $this->dom->getElementsByTagName("ICMSTot")->item(0);
        }
    } //fim __construct

    public function simpleConsistencyCheck()
    {
        return true;
    } //fim simpleConsistencyCheck
    public function monta(
        $orientacao = '',
        $papel = array(80, 'one-page'),
        $logoAlign = 'C',
        $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE,
        $CLASSE_PDF = false
    ) {
        return $this->montaDANFE($orientacao, $papel, $logoAlign, $situacao_externa, $CLASSE_PDF);
    }//fim monta
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDANFE($nome, $destino, $printer);
    }//fim printDocument
    public function montaDANFE(
        $orientacao = '',
        $papel = array(80,'one-page'),
        $logoAlign = 'C',
        $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE,
        $CLASSE_PDF = false
    ) {
        // o objetivo desta função é ler o XML e gerar o DANFE com auxilio de conversão HTML-PDF

        $chave_acesso   = str_replace('NFe', '', $this->infNFe->getAttribute("Id"));

        /*
            TODO: GERAR ENDEREÇO DO QRCODE
                VERIFICAR O TAMANHO NECESSÁRIO
        */
        //$IMG_QRCODE    ='data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADIAQMAAACXljzdAAAABlBMVEX///8AAABVwtN+AAAEn0lEQVRYhe2YsYrjOhSGjxGMmmC3LgR6hUmnacavEtgXkElrYhm3i/0CgXkVTxN3ySsIVKS1mUYLwuceJ3v3VpO9o23XVZwPYusc/f9/FIC/F0CBIYhN0nTPfODgLGuNrtFHEgVCCEjTej4pZZmUFlJN30YSFLY2KZQ4FCNKxLbtyuUPSGDfy0UnnhdcOof99D/IelPPQ4EoLWNBp39AQDCzb+bmcgKfW7A1FQ5iCXXBHfdd2W1PasydWG9+9efLhC7B2knD9gQjBsvsfvm1ST4hhReh0lTqc4EDoBMs1M1tpVFk/Umtk34psOAMqHRzM8USuGJgRmt4TnAExyywSacQSTi30jDsSnz3yAXCLuv72T8kVGtbbbIuO78MY07vZgDKBSKJyi1767t1ayvIIcijrk3mI0lhpZDtBCk88SJ3TL6ZPyHcsbY3aTZwVNbh2pLsfXhIFEnTTaVJi1OhciZpj036WUUS7i3Dqe5gO8Bwpc+2Kufbc2KI8sKCzno8PwGS1zE09ZLEEjIU1rbNckhfRn4NADuqwU1znxPAgDYz5ZIWA+SkErHHe61jCJfM2eqQpikfqIsgXT9321hSQGBtKA0UC/U3CGaz+V63zwmphKTVNHSnSCXSWV3OE0QSJaXDj371axilYPJIdcchktDSSPUUPwMhT1kkNnVz8bHES/dmKBuTETmuXahJMuohKZCEvknhkK4qocRocYLb3okiMtxMURcn5TkgiGyettEkt/K4ORz09sfguRDoTIK3LkQRkkaVLbq+jJSNQJb0PcPlN4TehzaSSUhZniNZ2nRILkMkUZLSOSTzAk/KQ7ACqrQ8q0hCXQi7qqRJ6AkG7qxs+y6ZYgnnVKm6q5t5xMGyIGD1E3hIAJnYsQ7Ky4kXVxqemMlM4mMJuVi1n+cpeaeyCZAUH/AKkYQSWVS0qcrhVZGtCXaEzBQ+llgkd9EJBW3hESxUNc73LnxKaMBwbTiQSoAP1gYX9kt9c6QYsj7H1jPlD93mIKH+nmZzLOGeVrpHyp/3kd5VoNWp3sYSdUWKHLJrUFzlwrqwqU3ymHAOpPu0bs6K5GcF4keDFx9LPJKpTklzflX+Sm2og67PKpIU1oKoIMHlabVbCd8+uvLdPyY5ecgmWQ5rrS3l4Y7195XGEAVOmn3fmGQcC3DB0SCjtz6S8Jx8Y3/Ln4LTDI1vBtagjiPFlbkjrPnzgpRmUtDQuNxnsc8Jl3jcm2SBFz/kCDR4z3h3pAiyzl8O0RyeXwYvA6lEH8p4Qg7b9hSHxY9B0bBhSfR3lcQQSmex20+ZIZX4dYoATUv5DfFByA+kWi+crwlIo/d9Uo0hKgch6SRRTq8F8uDkW6iXnzP51wm/UoSG+5nJW7IkNtV4108EWR3uWK1nJppUr4ya+kGD6mNC5xKxq/QhfX1VA3XRsn4pfyb618ntxM9mU+IJBhAWW5Mm8QTpDLlJU02TEFoS4H8zXxyhyZ2OcGeae70lsyOrWeB3xOE64iQ/6MzPBHwjB/j3H4yvE1od+5ixv5wUt3RGN1XZ/XSkrxPqAr4h5c92uAfGjl56G0v+Xp9f/wAB7INipCG5ogAAAABJRU5ErkJggg==';    /* "data:img/png;base64,arquivo" */
        $qr_code_url    = utf8_decode("https://nfce.set.rn.gov.br/consultarNFCe.aspx?chNFe=24130411982113000237650020000000071185945690&nVersao=100&tpAmb=2&dhEmi=323031332d30342d31355431353a32303a35352d30333a3030&vNF=13,90&vICMS=2,36&digVal=69466b66444662536161626c554539614f35476b4b48342f3964513d&cIdToken=000001&cHashQRCode=41799477BE9E40C0792C3B0E43094EA3CA4A2435");
        $IMG_QRCODE = new QRcode($qr_code_url, 'M');    // a classe qrcode funciona em latin1
        ob_start();
        $IMG_QRCODE->displayPNG(250, array(255,255,255), array(0,0,0), null, 0);
        $IMG_QRCODE = ob_get_clean();
        $IMG_QRCODE = 'data:img/png;base64,'.base64_encode($IMG_QRCODE);


        // usando a classe barcode (http://barcode-coder.com/en/barcode-php-class-203.html)
        #$IMG_CHAVE ='data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAAyAQMAAADcGHRpAAAABlBMVEX///8AAABVwtN+AAAAQUlEQVQ4jWNgGAWDAUgVnXPt02013zqnWyaaKz/bUsxim8i9a0U6hTmae9c8iTgR8XpU2aiyUWWjygaXslFAQwAA3QLlKoW/9MoAAAAASUVORK5CYII=';    /* "data:img/png;base64,arquivo" */
        #$IMG_CHAVE     = imagecreatetruecolor(580, 50);
        #$gd_white  = ImageColorAllocate($IMG_CHAVE,255,255,255);
        #$gd_black  = ImageColorAllocate($IMG_CHAVE,0,0,0);
        #imagefill($IMG_CHAVE, 0, 0, $gd_white);
        #Barcode::gd($IMG_CHAVE, $gd_black, 290, 25, 0, "code128", $chave_acesso, 2, 50);
        #ob_start();
        #imagepng($IMG_CHAVE);
        #$IMG_CHAVE = ob_get_clean();
        #unset($gd_white,$gd_black);
        #$IMG_CHAVE = 'data:img/png;base64,'.base64_encode($IMG_CHAVE);



        // CABEÇALHO
        // RAZÃO, CNPJ, IE
        $razao      = $this->emit->getElementsByTagName("xNome")->item(0)->nodeValue;
        $cnpj       = $this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue;
        $cnpj       = $this->__format($cnpj, "##.###.###/####-##");
        $ie     = $this->__simpleGetValue($this->emit, "IE");

        // ENDEREÇO
        $fone = !empty( $this->enderEmit->getElementsByTagName("fone")->item(0)->nodeValue) ?
                $this->enderEmit->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $foneLen = strlen($fone);
        if ($foneLen>0) {
            $fone2 = substr($fone, 0, $foneLen-4);
            $fone1 = substr($fone, 0, $foneLen-8);
            $fone = '('.$fone1.') '.substr($fone2, -4).'-'.substr($fone, -4);
        } else {
            $fone = '';
        }
        $lgr = $this->__simpleGetValue($this->enderEmit, "xLgr");
        $nro = $this->__simpleGetValue($this->enderEmit, "nro");
        $cpl = $this->__simpleGetValue($this->enderEmit, "xCpl", " - ");
        $bairro = $this->__simpleGetValue($this->enderEmit, "xBairro");
        $CEP = $this->__simpleGetValue($this->enderEmit, "CEP");
        $CEP = $this->__format($CEP, "#####-###");
        $mun = $this->__simpleGetValue($this->enderEmit, "xMun");
        $UF = $this->__simpleGetValue($this->enderEmit, "UF");

        $numNF = str_pad($this->ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
        $numNF = $this->__format($numNF, "###.###.###");
        $numNF = "Nº. ".$numNF;
        $serie = str_pad($this->ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
        $data_emissao = $this->__ymd2dmy($this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue);
        $protocolo_autorizacao = !empty($this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue) ?
                        $this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue : '';
        // cabeçalho
        $HTML=  "<html><head><style>".
                "td {text-align:center; font-size:10px;}".
                "</style></head>\n".
                "<body>";
        $HTML.= "<table width='100%'>\n".
                "<tr><td colspan='3' style='border-top:1px dotted black'>".htmlspecialchars($razao)."</td></tr>\n".
                "<tr><td colspan='3'>CNPJ - $cnpj I.E. - $ie</td></tr>\n".
                "<tr>".
                    "<td colspan='3'>".
                    htmlspecialchars($lgr . ", " . $nro . $cpl) . "<br>\n" .
                    htmlspecialchars($bairro . " - " . $CEP) . "<br>\n" .
                    htmlspecialchars($mun . " - " . $UF . " " . "Fone/Fax: " . $fone).
                    "</td>".
                "</tr>\n".
                "<tr>".
                    "<td style='border-bottom:1px dotted black'>".htmlspecialchars($numNF)."</td>".
                    "<td style='border-bottom:1px dotted black'>Série.: $serie</td>".
                    "<td style='border-bottom:1px dotted black'>$data_emissao</td>".
                "</tr>\n".
                "<tr>".
                    "<td colspan='3'>".htmlspecialchars("DANFE NFC-E - Nota Fiscal Eletrônica para Consumidor Final")."</td>".
                "</tr>".
                "<tr>".
                    "<td colspan='3'".($tpAmb==1?" style='border-bottom:1px dotted black'":'').">".
                    htmlspecialchars("NFC-e não permite aproveitamento de crédito de ICMS")."</td>".
                "</tr>\n".
                ($tpAmb!=1 ?
                    "<tr><td colspan='3' style='border-bottom:1px dotted black'><b>".
                    "NFC-e Emitida em Ambiente de Testes</b></td></tr>":'').
                "</table>\n";
        // chave de acesso
        $HTML.= "<table width='100%'>".
            "<tr>".
                "<td>Consulte pela Chave de Acesso em<br>".
                /* TODO : PEGAR O ENDEREÇO DO QRCODE */
                "<a href='http://www.nfe.fazenda.gov.br/portal'>www.nfe.fazenda.gov.br/portal</a><br>".
                "ou no site da Sefaz Autorizadora</td>".
            "</tr>".
            "<tr>".
                "<td><b>CHAVE DE ACESSO</b></td>".
            "</tr>".
            "<tr>".
                "<td>$chave_acesso</td>".
            "</tr>".
            "<tr>".
                //"<td style='border-bottom:1px dotted black'><img src=\"$IMG_CHAVE\"></td>".
                "<td style='border-bottom:1px dotted black'><barcode code='$chave_acesso' type='C128A' style='height:50px' text='1'/></td>".
            "</tr>".
            "<tr>".
                "<td><b>Consulta via leitor de QR-CODE</b></td>".
            "</tr>".
            "<tr>".
                "<td><img src=\"$IMG_QRCODE\"></td>".
            "</tr>".
            "<tr>".
                "<td style='border-bottom:1px dotted black'>".
                htmlspecialchars("Protocolo de Autorização: ")."$protocolo_autorizacao</td>".
            "</tr>".
            "</table>";
        // itens
        $HTML.= "<table width='100%'>".
            "<tr>".
                "<td>#</td>".
                "<td>".htmlspecialchars('CÓDIGO')."</td>".
                "<td>".htmlspecialchars('DESCRIÇÃO')."</td>".
                "<td>QUANT.</td>".
                "<td>UN.</td>".
                "<td>VL.UNIT.</td>".
                "<td>VL.TOTAL</td>".
            "</tr>\n";
        $i=0;
        foreach ($this->det as $d) {
            $thisItem   = $this->det->item($i);
            $i++;
            $prod       = $thisItem->getElementsByTagName("prod")->item(0);
            $nitem      = $thisItem->getAttribute("nItem");
            $codigo     = $prod->getElementsByTagName("cProd")->item(0)->nodeValue;
            $descricao  = $prod->getElementsByTagName("xProd")->item(0)->nodeValue;
            $quantidade = number_format($prod->getElementsByTagName("qCom")->item(0)->nodeValue, 4, ",", ".");
            $unidade    = $prod->getElementsByTagName("uCom")->item(0)->nodeValue;
            $valor_un   = number_format($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue, 4, ",", ".");
            $valor_tot  = number_format($prod->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
            $desconto   = !empty($prod->getElementsByTagName("vDesc")->item(0)->nodeValue)?
                         $prod->getElementsByTagName("vDesc")->item(0)->nodeValue : 0;
            $desconto   =($desconto<=0?'':number_format($desconto, 2, ",", "."));

            $HTML   .=  "<tr>".
                        "<td align='left'>".htmlspecialchars($nitem)."</td>".
                        "<td align='left'>".htmlspecialchars($codigo)."</td>".
                        "<td align='left'>".htmlspecialchars($descricao)."</td>".
                        "<td align='right'>".htmlspecialchars($quantidade)."</td>".
                        "<td>".htmlspecialchars($unidade)."</td>".
                        "<td align='right'>".htmlspecialchars($valor_un)."</td>".
                        "<td align='right'>".htmlspecialchars($valor_tot)."</td>".
                    "</tr>\n";  // colcoar desconto
        }
        $HTML.= "</table>\n";
        // impostos
        $valor_produtos =number_format($this->ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
        $descontos  =!empty($this->ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $valor_total    =number_format($this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
        $HTML.= "<table width='100%'>\n".
            "<tr>".
                "<td style='border-top:1px dotted black' align='left'>".htmlspecialchars('VALOR PRODUTOS/SERVIÇOS')."</td>".
                "<td style='border-top:1px dotted black' align='right'>".htmlspecialchars($valor_total)."</td>".
            "</tr>\n".
            "<tr>".
                "<td align='left'>DESCONTOS</td>".
                "<td align='right'>".htmlspecialchars($descontos)."</td>".
            "</tr>\n".
            "<tr>".
                "<td style='border-bottom:1px dotted black' align='left'>VALOR TOTAL</td>".
                "<td style='border-bottom:1px dotted black' align='right'>".htmlspecialchars($valor_total)."</td>".
            "</tr>\n".
            "</table>\n";
        // pagamento
        $i=0;
        foreach ($this->pag as $d) {
            $thisItem   = $this->pag->item($i);
            $i++;
            $tPag       = $thisItem->getElementsByTagName("tPag")->item(0);
            $tPag_nome  = ($tPag=='01'?'01-Dinheiro':'').
                        ($tPag=='02'?'02-Cheque':'').
                        ($tPag=='03'?'03-Cartão de Crédito':'').
                        ($tPag=='04'?'04-Cartão de Débito':'').
                        ($tPag=='05'?'05-Crédito Loja':'').
                        ($tPag=='10'?'10-Vale Alimentação':'').
                        ($tPag=='11'?'11-Vale Refeição':'').
                        ($tPag=='12'?'12-Vale Presente':'').
                        ($tPag=='13'?'13-Vale Combustível':'').
                        ($tPag=='99'?'99-Outros':'');
            $vPag       = number_format($thisItem->getElementsByTagName("vPag")->item(0), 2, ",", ".");

            $CNPJ       = $thisItem->getElementsByTagName("CNPJ")->item(0);
            $CNPJ_formatado = $this->__format($CNPJ, "##.###.###/####-##");

            $tBand      = $thisItem->getElementsByTagName("tBand")->item(0);
            $tBand_nome = ($tBand=='01'?'01-Visa':'').
                          ($tBand=='02'?'02-Mastercard':'').
                          ($tBand=='03'?'03-American Express':'').
                          ($tBand=='04'?'04-Sorocred':'').
                          ($tBand=='99'?'99-Outros':'');

            $cAut       = $thisItem->getElementsByTagName("cAut")->item(0);
            $HTML.=($i==1?
                "<table width='100%'>".
                "<tr><td>PAGAMENTOS</td></tr>\n":'').
                "<tr>".
                    "<td style='border-bottom:1px dotted black'>$i</td>".
                    "<td style='border-bottom:1px dotted black'>".htmlspecialchars($tPag_nome)."</td>".
                    "<td style='border-bottom:1px dotted black'>$vPag</td>".
                "</tr>".
                ($CNPJ!=''?
                    "<tr>".
                        "<td>".htmlspecialchars($tBand_nome)."</td>".
                        "<td>$CNPJ_formatado</td>".
                    "</tr>\n".
                    "<tr>".
                        "<td>Aut:</td>".
                        "<td>$cAut</td>".
                    "</tr>\n"
                    :'');
        }
        // consumidor
        $doc            ='';    /* TODO pega cpf,cnpj ou outro documento*/

        #var_dump($this->dest->getElementsByTagName("xNome"));
        $nome_consumidor    =
            !empty( $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue) ?
                    $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue : '';
        #echo 5;
        $fone = !empty( $this->enderDest->getElementsByTagName("fone")->item(0)->nodeValue) ?
                        $this->enderDest->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $foneLen = strlen($fone);
        if ($foneLen > 0) {
            $fone2 = substr($fone, 0, $foneLen-4);
            $fone1 = substr($fone, 0, $foneLen-8);
            $fone = '('.$fone1.') '.substr($fone2, -4).'-'.substr($fone, -4);
        } else {
            $fone = '';
        }
        $lgr = $this->__simpleGetValue($this->enderDest, "xLgr");
        $nro = $this->__simpleGetValue($this->enderDest, "nro");
        $cpl = $this->__simpleGetValue($this->enderDest, "xCpl", " - ");
        $bairro = $this->__simpleGetValue($this->enderDest, "xBairro");
        $CEP = $this->__simpleGetValue($this->enderDest, "CEP");
        $CEP = $this->__format($CEP, "#####-###");
        $mun = $this->__simpleGetValue($this->enderDest, "xMun");
        $UF = $this->__simpleGetValue($this->enderDest, "UF");
        $HTML.= ($doc!=''?
                "<table width='100%'>\n".
                "<tr><td>CONSUMIDOR</td></tr>\n".
                "<tr><td>CNPJ/CPF/ID Estrangeiro - ".htmlspecialchars($doc)."</td></tr>\n".
                "<tr><td>".htmlspecialchars($nome_consumidor)."</td></tr>\n".
                "<tr><td style='border-bottom:1px dotted black;width:100%'>".
                    htmlspecialchars($lgr.", ".$nro.$cpl)."<BR>\n".
                    htmlspecialchars($bairro." - ".$CEP)."<BR>\n".
                    htmlspecialchars($mun." - ".$UF." "."Fone/Fax: ".$fone)."</td></tr>\n".
                "</table>\n":'').
            "</body></html>\n";
        // converte html pra pdf usando MPDF (http://www.mpdf1.com/)

        /*
        TODO :  juntar varios PDF em um unico documento
            juntar inclusive documentos de danfe,dacte,etc (atenção!!! eles usam classe fpdf!!!)
        */
        if (is_array($papel) && strtolower($papel[1])=='one-page') {
            $mpdf=new mPDF('', array($papel[0], 841.89), 0, '', 0, 0, 0, 0, 0, 'P');
            $mpdf->WriteHTML($HTML, 0, true, false);
            $height=$mpdf->y;
            $papel=array($papel[0], $mpdf->y);
        }
        $this->mpdf=new mPDF('', $papel, 0, '', 0, 0, 0, 0, 0, 'P');
        $this->mpdf->WriteHTML($HTML);
            //retorna o ID na NFe
        if ($CLASSE_PDF!==false) {
            $aR = array(
            'id'            =>str_replace('NFe', '', $this->infNFe->getAttribute("Id")),
            'classe_mPDF'   =>$this->mpdf);
            return $aR;
        } else {
            return str_replace('NFe', '', $this->infNFe->getAttribute("Id"));
        }
    }//fim da função montaDANFE
    public function printDANFE($nome = '', $destino = 'I', $printer = '')
    {
        return $this->mpdf->Output($nome, $destino);
    }
}
