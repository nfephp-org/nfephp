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
 * @package     NFePHP
 * @name        DamdfeNFePHP.class.php
 * @version     1.0.2
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *      Cleiton Perin <cperin20 at gmail dot com>
 *      Roberto L. Machado <linux dot rml at gmail dot com>
 *              
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(FILE)).DIRECTORY_SEPARATOR);
}
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH', 'font/');
}
//classe extendida da classe FPDF para montagem do arquivo pdf
require_once('../Common/PdfNFePHP.class.php');
//classe com as funções communs entre DANFE e DACTE
require_once('../Common/CommonNFePHP.class.php');

class DamdfeNFePHP extends CommonNFePHP
{

    //publicas
    public $logoAlign='L'; //alinhamento do logo
    public $yDados=0;
    public $debugMode=0; //ativa ou desativa o modo de debug
    //privadas
    protected $pdf; // objeto fpdf()
    protected $xml; // string XML NFe
    protected $logomarca=''; // path para logomarca em jpg
    protected $errMsg=''; // mesagens de erro
    protected $errStatus=false;// status de erro TRUE um erro ocorreu false sem erros
    protected $orientacao='P'; //orientação da DANFE P-Retrato ou L-Paisagem
    protected $papel='A4'; //formato do papel
    //destivo do arquivo pdf I-borwser, S-retorna o arquivo, D-força download, F-salva em arquivo local
    protected $destino = 'I';
    protected $pdfDir=''; //diretorio para salvar o pdf com a opção de destino = F
    protected $fontePadrao='Times'; //Nome da Fonte para gerar o DANFE
    protected $version = '1.0.0';
    protected $wPrint; //largura imprimivel
    protected $hPrint; //comprimento imprimivel
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    //variaveis da carta de correção
    protected $id;
    protected $chMDFe;
    protected $tpAmb;
    protected $cOrgao;
    protected $xCondUso;
    protected $dhEvento;
    protected $cStat;
    protected $xMotivo;
    protected $CNPJDest = '';
    protected $dhRegEvento;
    protected $nProt;
    protected $tpEmis;
    //objetos
    private $dom;
    private $procEventoNFe;
    private $evento;
    private $infEvento;
    private $retEvento;
    private $rinfEvento;


    /**
     * __construct
     * 
     * @param string $xmlfile Arquivo XML da MDFe
     * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
     * @param string $sPapel Tamanho do papel (Ex. A4)
     * @param string $sPathLogo Caminho para o arquivo do logo
     * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
     * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
     * @param string $fonteDAMDFE Nome da fonte alternativa do DAnfe
     * @param integer $mododebug 0-Não 1-Sim e 2-nada (2 default)
     */
    public function __construct(
        $xmlfile = '',
        $sOrientacao = '',
        $sPapel = '',
        $sPathLogo = '',
        $sDestino = 'I',
        $sDirPDF = '',
        $fontePDF = '',
        $mododebug = 2
    ) {
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
        $this->orientacao   = $sOrientacao;
        $this->papel        = $sPapel;
        $this->pdf          = '';
        //$this->xml          = $xmlfile;
        $this->logomarca    = $sPathLogo;
        $this->destino      = $sDestino;
        $this->pdfDir       = $sDirPDF;
        // verifica se foi passa a fonte a ser usada
        if (empty($fontePDF)) {
            $this->fontePadrao = 'Times';
        } else {
            $this->fontePadrao = $fontePDF;
        }
        //se for passado o xml
        if (empty($xmlfile)) {
            $this->errMsg = 'Um caminho para o arquivo xml da MDFe deve ser passado!';
            $this->errStatus = true;
            exit();
        }
        if (!is_file($xmlfile)) {
            $this->errMsg = 'Um caminho para o arquivo xml da MDFe deve ser passado!';
            $this->errStatus = true;
            exit();
        }
        $docxml = file_get_contents($xmlfile);
        $this->dom = new DomDocument;
        $this->dom->loadXML($docxml);
        $this->mdfeProc = $this->dom->getElementsByTagName("mdfeProc")->item(0);
        $this->infMDFe = $this->dom->getElementsByTagName("infMDFe")->item(0);
        $this->emit = $this->infMDFe->getElementsByTagName("emit")->item(0);
        $this->CNPJ = $this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue;
        $this->IE = $this->emit->getElementsByTagName("IE")->item(0)->nodeValue;
        $this->xNome = $this->emit->getElementsByTagName("xNome")->item(0)->nodeValue;
        $this->enderEmit = $this->emit->getElementsByTagName("enderEmit")->item(0);
        $this->xLgr = $this->enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue;
        $this->nro = $this->enderEmit->getElementsByTagName("nro")->item(0)->nodeValue;
        $this->xBairro = $this->enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue;
        $this->UF = $this->enderEmit->getElementsByTagName("UF")->item(0)->nodeValue;
        $this->xMun = $this->enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue;
        $this->CEP = $this->enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue;
        $this->ide = $this->infMDFe->getElementsByTagName("ide")->item(0);
        $this->tpAmb = $this->ide->getElementsByTagName("tpAmb")->item(0)->nodeValue;
        $this->mod = $this->ide->getElementsByTagName("mod")->item(0)->nodeValue;
        $this->serie = $this->ide->getElementsByTagName("serie")->item(0)->nodeValue;
        $this->dhEmi = $this->ide->getElementsByTagName("dhEmi")->item(0)->nodeValue;
        $this->UFIni = $this->ide->getElementsByTagName("UFIni")->item(0)->nodeValue;
        $this->nMDF = $this->ide->getElementsByTagName("nMDF")->item(0)->nodeValue;
        $this->tpEmis = $this->ide->getElementsByTagName("tpEmis")->item(0)->nodeValue;
        $this->tot = $this->infMDFe->getElementsByTagName("tot")->item(0);
        $this->qNFe = "";
        if ($this->tot->getElementsByTagName("qNFe")->item(0) != "") {
            $this->qNFe = $this->tot->getElementsByTagName("qNFe")->item(0)->nodeValue;
        }
        $this->qNF = "";
        if ($this->tot->getElementsByTagName("qNF")->item(0) != "") {
            $this->qNF = $this->tot->getElementsByTagName("qNF")->item(0)->nodeValue;
        }
        $this->qCTe = "";
        if ($this->tot->getElementsByTagName("qCTe")->item(0) != "") {
            $this->qCTe = $this->tot->getElementsByTagName("qCTe")->item(0)->nodeValue;
        }
        $this->qCT = "";
        if ($this->tot->getElementsByTagName("qCT")->item(0) != "") {
            $this->qCT = $this->tot->getElementsByTagName("qCT")->item(0)->nodeValue;
        }
        $this->qCarga = $this->tot->getElementsByTagName("qCarga")->item(0)->nodeValue;
        $this->infModal = $this->infMDFe->getElementsByTagName("infModal")->item(0);
        $this->rodo = $this->infModal->getElementsByTagName("rodo")->item(0);
        $this->ciot = "";
        if ($this->rodo->getElementsByTagName('CIOT')->item(0) != "") {
            $this->ciot = $this->rodo->getElementsByTagName('CIOT')->item(0)->nodeValue;
        }
        $this->veicTracao = $this->rodo->getElementsByTagName("veicTracao")->item(0);
        $this->veicReboque = $this->rodo->getElementsByTagName("veicReboque");
        $this->valePed = "";
        if ($this->rodo->getElementsByTagName("valePed")->item(0) != "") {
            $this->valePed = $this->rodo->getElementsByTagName("valePed")->item(0)->getElementsByTagName("disp");
        }
        $this->chMDFe = str_replace(
            'MDFe',
            '',
            $this->infMDFe->getAttribute("Id")
        );
        if (is_object($this->mdfeProc)) {
            $this->nProt = ! empty($this->mdfeProc->getElementsByTagName("nProt")->item(0)->nodeValue) ?
                    $this->mdfeProc->getElementsByTagName("nProt")->item(0)->nodeValue : '';
            $this->dhRecbto = $this->mdfeProc->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
        }
    }//fim construct

    /**
     *buildMDFe
     * 
     */
    private function buildMDFe()
    {
        $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        if ($this->orientacao == 'P') {
            // margens do PDF
            $margSup = 7;
            $margEsq = 7;
            $margDir = 7;
            // posição inicial do relatorio
            $xInic = 7;
            $yInic = 7;
            if ($this->papel =='A4') { //A4 210x297mm
                $maxW = 210;
                $maxH = 297;
            }
        } else {
            // margens do PDF
            $margSup = 7;
            $margEsq = 7;
            $margDir = 7;
            // posição inicial do relatorio
            $xInic = 7;
            $yInic = 7;
            if ($this->papel =='A4') { //A4 210x297mm
                $maxH = 210;
                $maxW = 297;
            }
        }//orientação
        //largura imprimivel em mm
        $this->wPrint = $maxW-($margEsq+$xInic);
        //comprimento imprimivel em mm
        $this->hPrint = $maxH-($margSup+$yInic);
        // estabelece contagem de paginas
        $this->pdf->AliasNbPages();
        // fixa as margens
        $this->pdf->SetMargins($margEsq, $margSup, $margDir);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetFillColor(255, 255, 255);
        // inicia o documento
        $this->pdf->Open();
        // adiciona a primeira página
        $this->pdf->AddPage($this->orientacao, $this->papel);
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->SetTextColor(0, 0, 0);
        //montagem da página
        $pag = 1;
        $x = $xInic;
        $y = $yInic;
        //coloca o cabeçalho Paisagem
        if ($this->orientacao == 'P') {
            $y = $this->headerMDFeRetrato($x, $y, $pag);
        } else {
            $y = $this->headerMDFePaisagem($x, $y, $pag);
        }
        //coloca os dados da MDFe
        $y = $this->bodyMDFe($x, $y);
        //coloca os dados da MDFe
        $y = $this->footerMDFe($x, $y);
    } //fim buildCCe

    /**
     * headerMDFePaisagem
     * @param float $x
     * @param float $y
     * @param integer $pag
     * @return string
     */
    private function headerMDFePaisagem($x, $y, $pag)
    {
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;
        //####################################################################################
        //coluna esquerda identificação do emitente
        $w = $maxW; //round($maxW*0.41, 0);// 80;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'B');
        $w1 = $w;
        $h=30;
        $oldY += $h;
        $this->pTextBox($x, $y, $w, $h);
        if (is_file($this->logomarca)) {
            $logoInfo = getimagesize($this->logomarca);
            //largura da imagem em mm
            $logoWmm = ($logoInfo[0]/72)*25.4;
            //altura da imagem em mm
            $logoHmm = ($logoInfo[1]/72)*25.4;
            if ($this->logoAlign=='L') {
                $nImgW = round($w/4.5, 0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm), 0);
                $xImg = $x+1;
                $yImg = round(($h-$nImgH)/2, 0)+$y;
                //estabelecer posições do texto
                $x1 = round($xImg + $nImgW +1, 0);
                $y1 = round($y+2, 0);
                $tw = round(2*$w/3, 0);
            }
            if ($this->logoAlign=='C') {
                $nImgH = round($h/3, 0);
                $nImgW = round($logoWmm * ($nImgH/$logoHmm), 0);
                $xImg = round(($w-$nImgW)/2+$x, 0);
                $yImg = $y+3;
                $x1 = $x;
                $y1 = round($yImg + $nImgH + 1, 0);
                $tw = $w;
            }
            if ($this->logoAlign=='R') {
                $nImgW = round($w/3, 0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm), 0);
                $xImg = round($x+($w-(1+$nImgW)), 0);
                $yImg = round(($h-$nImgH)/2, 0)+$y;
                $x1 = $x;
                $y1 = round($h/3+$y, 0);
                $tw = round(2*$w/3, 0);
            }
            $this->pdf->Image($this->logomarca, $xImg, $yImg, $nImgW, $nImgH, 'jpeg');
        } else {
            $x1 = $x;
            $y1 = round($h/3+$y, 0);
            $tw = $w;
        }
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $razao = $this->xNome;
        $cnpj = 'CNPJ: '.$this->pFormat($this->CNPJ, "###.###.###/####-##");
        $ie = 'IE: '.$this->pFormat($this->IE, '##/########');
        $lgr = 'Logradouro: '.$this->xLgr;
        $nro = 'Nº: '.$this->nro;
        $bairro = 'Bairro: '.$this->xBairro;
        $CEP = $this->CEP;
        $CEP = 'CEP: '.$this->pFormat($CEP, "##.###-###");
        $mun = 'Municipio: '.$this->xMun;
        $UF = 'UF: '.$this->UF;
        
        $texto = $razao . "\n" . $cnpj . ' - ' . $ie . "\n";
        $texto .= $lgr . ' - ' . $nro . "\n";
        $texto .= $bairro . "\n";
        $texto .= $mun . ' - ' . $UF . ' - ' . $CEP;
        $this->pTextBox($x1, $y1+5, $tw, 8, $texto, $aFont, 'T', 'L', 0, '');
        $x = $x+$maxW/2;
        $w = $maxW / 2;
        $this->pTextBox($x, $y, $w, $h);

        $aFont = array('font'=>$this->fontePadrao, 'size'=>12, 'style'=>'I');
        $this->pTextBox(
            $x,
            $y,
            $w,
            8,
            'DAMDFE - Documento Auxiliar de Manifesto Eletronico de Documentos Fiscais',
            $aFont,
            'T',
            'C',
            0,
            ''
        );
        $this->pTextBox($x, $y, $w, 6);
        $bH = 13;
        $bW = round(($w), 0);
        $this->pdf->SetFillColor(0, 0, 0);
        $this->pdf->Code128($x+5, $y+7.5, $this->chMDFe, $bW-10, $bH);
        $this->pdf->SetFillColor(255, 255, 255);
        $y = $y + 22;
        $this->pTextBox($x, $y, $w, 8);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'I');
        $tsHora = $this->pConvertTime($this->dhEvento);
        $texto = 'CHAVE DE ACESSO';
        $this->pTextBox($x, $y, $maxW, 6, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $texto = $this->pFormat($this->chMDFe, $this->formatoChave);
        $this->pTextBox($x, $y+3, $w, 6, $texto, $aFont, 'T', 'C', 0, '');
        $y = $y + 11;
        $this->pTextBox($x, $y, $w, 12);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'I');
        $texto = 'PROTOCOLO DE AUTORIZACAO DE USO';
        $this->pTextBox($x, $y, $w, 8, $texto, $aFont, 'T', 'L', 0, '');
        
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        if (is_object($this->mdfeProc)) {
            $tsHora = $this->pConvertTime($this->dhRecbto);
            $texto = $this->nProt.' - '.date('d/m/Y   H:i:s', $tsHora);
        } else {
            $texto = 'DAMDFE impresso em contingência - '.date('d/m/Y   H:i:s');
        }
        $this->pTextBox($x, $y+4, $w, 8, $texto, $aFont, 'T', 'C', 0, '');
        if ($this->tpAmb != 1) {
            $x = 10;
            if ($this->orientacao == 'P') {
                $yy = round($this->hPrint*2/3, 0);
            } else {
                $yy = round($this->hPrint/2, 0);
            }
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>$this->fontePadrao, 'size'=>48, 'style'=>'B');
            $this->pTextBox($x, $yy, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $aFont = array('font'=>$this->fontePadrao, 'size'=>30, 'style'=>'B');
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->pTextBox($x, $yy+14, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        } else {
            $x = 10;
            if ($this->orientacao == 'P') {
                $yy = round($this->hPrint*2/3, 0);
            } else {
                $yy = round($this->hPrint/2, 0);
            }//fim orientacao
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90, 90, 90);
            //indicar FALTA DO PROTOCOLO se MDFe não for em contingência
            if (($this->tpEmis == 2 || $this->tpEmis == 5)) {
                //Contingência
                $texto = "DAMDFE Emitido em Contingência";
                $aFont = array('font'=>$this->fontePadrao, 'size'=>48, 'style'=>'B');
                $this->pTextBox($x, $yy, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                $aFont = array('font'=>$this->fontePadrao, 'size'=>30, 'style'=>'B');
                $texto = "devido à problemas técnicos";
                $this->pTextBox($x, $yy+12, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            }
            $this->pdf->SetTextColor(0, 0, 0);
        }
        return $y;
    }// fim headerMDFe
    
    /**
     * headerMDFeRetrato
     * 
     * @param float $x
     * @param float $y
     * @param integer $pag
     * @return string
     */
    private function headerMDFeRetrato($x, $y, $pag)
    {
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;
        //####################################################################################
        //coluna esquerda identificação do emitente
        $w = $maxW; //round($maxW*0.41, 0);// 80;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>6, 'style'=>'I');
        $w1 = $w;
        $h=20;
        $oldY += $h;
        $this->pTextBox($x, $y, $w, $h);
        if (is_file($this->logomarca)) {
            $logoInfo = getimagesize($this->logomarca);
            //largura da imagem em mm
            $logoWmm = ($logoInfo[0]/72)*25.4;
            //altura da imagem em mm
            $logoHmm = ($logoInfo[1]/72)*25.4;
            if ($this->logoAlign=='L') {
                $nImgW = round($w/4.5, 0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm), 0);
                $xImg = $x+1;
                $yImg = round(($h-$nImgH)/2, 0)+$y;
                //estabelecer posições do texto
                $x1 = round($xImg + $nImgW +1, 0);
                $y1 = round($y+2, 0);
                $tw = round(2*$w/3, 0);
            }
            if ($this->logoAlign=='C') {
                $nImgH = round($h/3, 0);
                $nImgW = round($logoWmm * ($nImgH/$logoHmm), 0);
                $xImg = round(($w-$nImgW)/2+$x, 0);
                $yImg = $y+3;
                $x1 = $x;
                $y1 = round($yImg + $nImgH + 1, 0);
                $tw = $w;
            }
            if ($this->logoAlign=='R') {
                $nImgW = round($w/3, 0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm), 0);
                $xImg = round($x+($w-(1+$nImgW)), 0);
                $yImg = round(($h-$nImgH)/2, 0)+$y;
                $x1 = $x;
                $y1 = round($h/3+$y, 0);
                $tw = round(2*$w/3, 0);
            }
            $this->pdf->Image($this->logomarca, $xImg, $yImg, $nImgW, $nImgH, 'jpeg');
        } else {
            $x1 = $x;
            $y1 = round($h/3+$y, 0);
            $tw = $w;
        }
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $razao = $this->xNome;
        $cnpj = 'CNPJ: '.$this->pFormat($this->CNPJ, "###.###.###/####-##");
        $ie = 'IE: '.$this->pFormat($this->IE, '##/########');
        $lgr = 'Logradouro: '.$this->xLgr;
        $nro = 'Nº: '.$this->nro;
        $bairro = 'Bairro: '.$this->xBairro;
        $CEP = $this->CEP;
        $CEP = 'CEP: '.$this->pFormat($CEP, "##.###-###");
        $mun = 'Municipio: '.$this->xMun;
        $UF = 'UF: '.$this->UF;
        $texto = $razao . "\n" . $cnpj . ' - ' . $ie . "\n";
        $texto .= $lgr . ' - ' . $nro . "\n";
        $texto .= $bairro . "\n";
        $texto .= $mun . ' - ' . $UF . ' - ' . $CEP;
        $this->pTextBox($x1, $y1, $tw, 8, $texto, $aFont, 'T', 'L', 0, '');
        //##################################################
        $y = $h + 8;
        $this->pTextBox($x, $y, $maxW, 6);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>12, 'style'=>'I');
        $this->pTextBox(
            $x,
            $y,
            $maxW,
            8,
            'DAMDFE - Documento Auxiliar de Manifesto Eletronico de Documentos Fiscais',
            $aFont,
            'T',
            'C',
            0,
            ''
        );
        $y = $y + 8;
        $this->pTextBox($x, $y, $maxW, 20);
        $bH = 16;
        $w = $maxW;
        $this->pdf->SetFillColor(0, 0, 0);
        $this->pdf->Code128($x + 5, $y+2, $this->chMDFe, $maxW - 10, $bH);
        $this->pdf->SetFillColor(255, 255, 255);
        $y = $y + 22;
        $this->pTextBox($x, $y, $maxW, 10);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'I');
        $tsHora = $this->pConvertTime($this->dhEvento);
        $texto = 'CHAVE DE ACESSO';
        $this->pTextBox($x, $y, $maxW, 6, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $texto = $this->pFormat($this->chMDFe, $this->formatoChave);
        $this->pTextBox($x, $y+4, $maxW, 6, $texto, $aFont, 'T', 'C', 0, '');
        $y = $y + 12;
        $this->pTextBox($x, $y, $maxW, 10);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'I');
        $texto = 'PROTOCOLO DE AUTORIZACAO DE USO';
        $this->pTextBox($x, $y, $maxW, 8, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        if (is_object($this->mdfeProc)) {
            $tsHora = $this->pConvertTime($this->dhRecbto);
            $texto = $this->nProt.' - '.date('d/m/Y   H:i:s', $tsHora);
        } else {
            $texto = 'DAMDFE impresso em contingência - '.date('d/m/Y   H:i:s');
        }
        $this->pTextBox($x, $y+4, $maxW, 8, $texto, $aFont, 'T', 'C', 0, '');
        if ($this->tpAmb != 1) {
            $x = 10;
            if ($this->orientacao == 'P') {
                $yy = round($this->hPrint*2/3, 0);
            } else {
                $yy = round($this->hPrint/2, 0);
            }
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>$this->fontePadrao, 'size'=>48, 'style'=>'B');
            $this->pTextBox($x, $yy, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $aFont = array('font'=>$this->fontePadrao, 'size'=>30, 'style'=>'B');
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->pTextBox($x, $yy+14, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        } else {
            $x = 10;
            if ($this->orientacao == 'P') {
                $yy = round($this->hPrint*2/3, 0);
            } else {
                $yy = round($this->hPrint/2, 0);
            }//fim orientacao
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90, 90, 90);
            //indicar FALTA DO PROTOCOLO se MDFe não for em contingência
            if (($this->tpEmis == 2 || $this->tpEmis == 5)) {
                //Contingência
                $texto = "DAMDFE Emitido em Contingência";
                $aFont = array('font'=>$this->fontePadrao, 'size'=>48, 'style'=>'B');
                $this->pTextBox($x, $yy, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                $aFont = array('font'=>$this->fontePadrao, 'size'=>30, 'style'=>'B');
                $texto = "devido à problemas técnicos";
                $this->pTextBox($x, $yy+12, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            }
            $this->pdf->SetTextColor(0, 0, 0);
        }
        return $y+12;
    }// fim headerMDFe
    

    /**
     * bodyMDFe
     * 
     * @param float $x
     * @param float $y
     * @return void
     */
    private function bodyMDFe($x, $y)
    {
        if ($this->orientacao == 'P') {
            $maxW = $this->wPrint;
        } else {
            $maxW = $this->wPrint / 2;
        }
        $x2 = ($maxW / 6);
        $x1 = $x2;
        $this->pTextBox($x, $y, $x2-7, 12);
        $texto = 'Modelo';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x, $y, $x2-7, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = $this->mod;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x, $y+4, $x2-7, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 = $x2;
        $this->pTextBox($x1, $y, $x2-7, 12);
        $texto = 'Série';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2-7, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = $this->serie;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2-7, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2-7;
        $this->pTextBox($x1, $y, $x2+5, 12);
        $texto = 'Número';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2+5, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = $this->pFormat(str_pad($this->nMDF, 9, '0', STR_PAD_LEFT), '###.###.###');
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2+5, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2+5;
        $this->pTextBox($x1, $y, $x2-7, 12);
        $texto = 'FL';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2-7, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = '1';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2-7, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2-7;
        $this->pTextBox($x1, $y, $x2+11, 12);
        $texto = 'Data e Hora de Emissão';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2+11, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $data = explode('T', $this->dhEmi);
        $texto = $this->pYmd2dmy($data[0]).' - '.$data[1];
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2+11, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2+11;
        $this->pTextBox($x1, $y, $x2+5, 12);
        $texto = 'UF Carregamento';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2+5, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = $this->UFIni;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2+5, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $maxW = $this->wPrint;
        $x1 = $x;
        $x2 = $maxW;
        $y += 14;
        $this->pTextBox($x1, $y, $x2, 23);
        $texto = 'Modal Rodoviário de Carga';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'B');
        $this->pTextBox($x1, $y+1, $x2, 8, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 = $x;
        $x2 = ($maxW / 6);
        $y += 6;
        $this->pTextBox($x1, $y, $x2, 12);
        $texto = 'CIOT';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = str_pad($this->ciot, 3, '0', STR_PAD_LEFT);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, 12);
        $texto = 'Qtd. CT-e';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = str_pad($this->qCTe, 3, '0', STR_PAD_LEFT);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, 12);
        $texto = 'Qtd. CTRC';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = str_pad($this->qCT, 3, '0', STR_PAD_LEFT);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, 12);
        $texto = 'Qtd. NF-e';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = str_pad($this->qNFe, 3, '0', STR_PAD_LEFT);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, 12);
        $texto = 'Qtd. NF';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = str_pad($this->qNF, 3, '0', STR_PAD_LEFT);
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, 12);
        $texto = 'Peso Total (Kg)';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = number_format($this->qCarga, 4, ', ', '.');
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $x1 = $x;
        $y += 12;
        $yold = $y;
        $x2 = round($maxW / 2, 0);
        $texto = 'Veículo';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $y += 5;
        $x2 = round($maxW / 4, 0);
        $tamanho = 22;
        $this->pTextBox($x1, $y, $x2, $tamanho);
        $texto = 'Placa';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = $this->veicTracao->getElementsByTagName("placa")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $altura = $y + 4;
        for ($i = 0; $i < $this->veicReboque->length; $i++) {
            $altura += 4;
            $texto = $this->veicReboque->item($i)->getElementsByTagName('placa')->item(0)->nodeValue;
            $this->pTextBox($x1, $altura, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        }
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, $tamanho);
        $texto = 'RNTRC';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $texto = $this->rodo->getElementsByTagName("RNTRC")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
        $this->pTextBox($x1, $y+4, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        $altura = $y + 4;
        for ($i = 0; $i < $this->veicReboque->length; $i++) {
            $altura += 4;
            $texto = $this->veicReboque->item($i)->getElementsByTagName('RNTRC')->item(0)->nodeValue;
            $this->pTextBox($x1, $altura, $x2, 10, $texto, $aFont, 'T', 'C', 0, '', false);
        }
        $x1 = $x;
        $y += 22;
        $x2 = round($maxW / 2, 0);
        $valesPedagios = 1;
        $temVales = false;
        if ($this->valePed != "" && $this->valePed->length > 0) {
            $valesPedagios = $this->valePed->length;
            $temVales = true;
        }
        $tamanho = ($valesPedagios * 7.5);
        if (!$temVales) {
            $valesPedagios = 0;
        }
        $this->pTextBox($x1, $y, $x2, 11+$tamanho/2);
        $texto = 'Vale Pedágio';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $y += 5;
        $x2 = ($x2 / 3);
        $this->pTextBox($x1, $y, $x2-3, 6+($tamanho/2));
        $texto = 'Responsável CNPJ';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2-4, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $altura = $y;
        for ($i = 0; $i < $valesPedagios; $i++) {
            $altura += 4;
            $texto = $this->valePed->item($i)->getElementsByTagName('CNPJForn')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
            $this->pTextBox($x1 + 1, $altura, $x2-5, 10, $texto, $aFont, 'T', 'L', 0, '', false);
        }
        $x1 += $x2-3;
        $this->pTextBox($x1, $y, $x2-3, 6+($tamanho/2));
        $texto = 'Fornecedora CNPJ';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2-4, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $altura = $y;
        for ($i = 0; $i < $valesPedagios; $i++) {
            $altura += 4;
            $texto = $this->valePed->item($i)->getElementsByTagName('CNPJPg')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
            $this->pTextBox($x1 + 1, $altura, $x2-5, 10, $texto, $aFont, 'T', 'L', 0, '', false);
        }
        $x1 += $x2-3;
        $this->pTextBox($x1, $y, $x2+6, 6+($tamanho/2));
        $texto = 'Nº Comprovante';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2+6, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $altura = $y;
        for ($i = 0; $i < $valesPedagios; $i++) {
            $altura += 4;
            $texto = $this->valePed->item($i)->getElementsByTagName('nCompra')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
            $this->pTextBox($x1 + 1, $altura, $x2+5, 10, $texto, $aFont, 'T', 'L', 0, '', false);
        }
        if (!$temVales) {
            $altura += 4;
        }
        $this->condutor = $this->veicTracao->getElementsByTagName('condutor');
        $x1 = round($maxW / 2, 0) + 7;
        $y = $yold;
        $x2 = round($maxW / 2, 0);
        $texto = 'Condutor';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $y += 5;
        $x2 = ($maxW / 4);
        $this->pTextBox($x1, $y, $x2, 33+($tamanho/2));
        $texto = 'CPF';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        $yold = $y;
        for ($i = 0; $i < $this->condutor->length; $i++) {
            $y += 4;
            $texto = $this->condutor->item($i)->getElementsByTagName('CPF')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao, 'size'=>10, 'style'=>'');
            $this->pTextBox($x1 + 1, $y, $x2 - 1, 10, $texto, $aFont, 'T', 'L', 0, '', false);
        }
        $y = $yold;
        $x1 += $x2;
        $this->pTextBox($x1, $y, $x2, 33+($tamanho/2));
        $texto = 'Nome';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x1, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        for ($i = 0; $i < $this->condutor->length; $i++) {
            $y += 4;
            $texto = $this->condutor->item($i)->getElementsByTagName('xNome')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
            $this->pTextBox($x1 + 1, $y, $x2 - 1, 8, $texto, $aFont, 'T', 'L', 0, '', false);
        }
        return $altura + 7;
    }//fim bodyMDFe

    /**
     * footerMDFe
     * 
     * @param float $x
     * @param float $y
     */
    private function footerMDFe($x, $y)
    {
        $maxW = $this->wPrint;
        $x2 = $maxW;
        $this->pTextBox($x, $y, $x2, 30);
        $texto = 'Observações ';
        $aFont = array('font'=>$this->fontePadrao, 'size'=>8, 'style'=>'');
        $this->pTextBox($x, $y, $x2, 8, $texto, $aFont, 'T', 'L', 0, '', false);

        $y = $this->hPrint -4;
        $texto = "Impresso em  ". date('d/m/Y   H:i:s');
        $w = $this->wPrint-4;
        $aFont = array('font'=>$this->fontePadrao, 'size'=>6, 'style'=>'I');
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'L', 0, '');
    }//fim footerCCe

    /**
     * printMDFe
     * 
     * @param string $nome
     * @param string $destino
     * @param string $printer
     * @return string
     */
    public function printMDFe($nome = '', $destino = 'I', $printer = '')
    {
        //monta
        $command = '';
        if ($nome == '') {
            $file = $this->pdfDir.'mdfe.pdf';
        } else {
            $file = $this->pdfDir.$nome;
        }
        if (($destino != 'I' || $destino != 'S') && $destino != 'F') {
            $destino = 'I';
        }
        if ($printer != '') {
            $command = "-P $printer";
        }
        $this->buildMDFe();
        $arq = $this->pdf->Output($file, $destino);

        if ($destino == 'S') {
            //aqui pode entrar a rotina de impressão direta
            $command = "lpr $command $file";
            system($comando, $retorno);
        }
        return $arq;

    }//fim printMDFe
}
