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
 * @version     1.0.0
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *              
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH','font/');
}
//classe extendida da classe FPDF para montagem do arquivo pdf
require_once('PdfNFePHP.class.php');
//classe com as funções communs entre DANFE e DACTE
require_once('CommonNFePHP.class.php');

class DamdfeNFePHP extends CommonNFePHP {

    //publicas
    public $logoAlign='L'; //alinhamento do logo
    public $yDados=0;
    public $debugMode=0; //ativa ou desativa o modo de debug
    //privadas
    protected $pdf; // objeto fpdf()
    protected $xml; // string XML NFe
    protected $logomarca=''; // path para logomarca em jpg
    protected $errMsg=''; // mesagens de erro
    protected $errStatus=FALSE;// status de erro TRUE um erro ocorreu FALSE sem erros
    protected $orientacao='P'; //orientação da DANFE P-Retrato ou L-Paisagem
    protected $papel='A4'; //formato do papel
    protected $destino = 'I'; //destivo do arquivo pdf I-borwser, S-retorna o arquivo, D-força download, F-salva em arquivo local
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
    *__construct
    * @package NFePHP
    * @name __construct
    * @version 1.0.0
    * @param string $xmlfile Arquivo XML da MDFe
    * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
    * @param string $sPapel Tamanho do papel (Ex. A4)
    * @param string $sPathLogo Caminho para o arquivo do logo
    * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
    * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
    * @param string $fonteDAMDFE Nome da fonte alternativa do DAnfe
    * @param number $mododebug 0-Não 1-Sim e 2-nada (2 default)
    */
    function __construct($xmlfile='', $sOrientacao='',$sPapel='',$sPathLogo='', $sDestino='I', $sDirPDF='',$fontePDF='',$mododebug=2) {
        if(is_numeric($mododebug)){
            $this->debugMode = $mododebug;
        }
        if($this->debugMode){
            //ativar modo debug
            error_reporting(E_ALL);ini_set('display_errors', 'On');
        } else {
            //desativar modo debug
            error_reporting(0);ini_set('display_errors', 'Off');
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
        if (empty($xmlfile)){
            $this->errMsg = 'Um caminho para o arquivo xml da MDFe deve ser passado!';
            $this->errStatus = true;
            exit();
        }
        if ( !is_file($xmlfile) ){
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
        $this->qNFe = $this->tot->getElementsByTagName("qNFe")->item(0)->nodeValue;
        $this->qCarga = $this->tot->getElementsByTagName("qCarga")->item(0)->nodeValue;
        
        $this->infModal  = $this->infMDFe->getElementsByTagName("infModal")->item(0);
        $this->rodo  = $this->infModal->getElementsByTagName("rodo")->item(0);
        $this->veicTracao  = $this->rodo->getElementsByTagName("veicTracao")->item(0);
        $this->placa  = $this->veicTracao->getElementsByTagName("placa")->item(0)->nodeValue;
        
        $this->chMDFe = str_replace('MDFe', '', $this->infMDFe->getAttribute("Id")); //$this->mdfeProc->getElementsByTagName("chMDFe")->item(0)->nodeValue;
        if(is_object($this->mdfeProc)){
            $this->nProt = (empty($this->mdfeProc->getElementsByTagName("nProt")->item(0)->nodeValue))? '': $this->mdfeProc->getElementsByTagName("nProt")->item(0)->nodeValue;
            $this->dhRecbto = $this->mdfeProc->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
        }
        

    }//fim __construct


    /**
     *
     */
    private function __buildMDFe(){
        $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        if( $this->orientacao == 'P' ){
            // margens do PDF
            $margSup = 7;
            $margEsq = 7;
            $margDir = 7;
            // posição inicial do relatorio
            $xInic = 7;
            $yInic = 7;
            if($this->papel =='A4'){ //A4 210x297mm
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
            if($papel =='A4'){ //A4 210x297mm
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
        $this->pdf->SetMargins($margEsq,$margSup,$margDir);
        $this->pdf->SetDrawColor(0,0,0);
        $this->pdf->SetFillColor(255,255,255);
        // inicia o documento
        $this->pdf->Open();
        // adiciona a primeira página
        $this->pdf->AddPage($this->orientacao, $this->papel);
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->SetTextColor(0,0,0);
        //montagem da página
        $pag = 1;
        $x = $xInic;
        $y = $yInic;
        //coloca o cabeçalho
        $y = $this->__headerMDFe($x,$y,$pag);
        //coloca os dados da MDFe
        $y = $this->__bodyMDFe($x,$y);
        //coloca os dados da MDFe
        $y = $this->__footerMDFe($x,$y);


    } //fim __buildCCe

    /**
     *
     * @param type $x
     * @param type $y
     * @param type $pag
     * @return type
     */
    private function __headerMDFe($x,$y,$pag){
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;

        //####################################################################################
        //coluna esquerda identificação do emitente
        $w = $maxW; //round($maxW*0.41,0);// 80;
        if( $this->orientacao == 'P' ){
            $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        }else{
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        }
        $w1 = $w;
        $h=20;
        $oldY += $h;
        $this->__textBox($x,$y,$w,$h);
        if (is_file($this->logomarca)){
            $logoInfo = getimagesize($this->logomarca);
            //largura da imagem em mm
            $logoWmm = ($logoInfo[0]/72)*25.4;
            //altura da imagem em mm
            $logoHmm = ($logoInfo[1]/72)*25.4;
            if ($this->logoAlign=='L'){
                $nImgW = round($w/4.5,0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm),0);
                $xImg = $x+1;
                $yImg = round(($h-$nImgH)/2,0)+$y;
                //estabelecer posições do texto
                $x1 = round($xImg + $nImgW +1,0);
                $y1 = round($y+2,0);
                $tw = round(2*$w/3,0);
            }
            if ($this->logoAlign=='C'){
                $nImgH = round($h/3,0);
                $nImgW = round($logoWmm * ($nImgH/$logoHmm),0);
                $xImg = round(($w-$nImgW)/2+$x,0);
                $yImg = $y+3;
                $x1 = $x;
                $y1 = round($yImg + $nImgH + 1,0);
                $tw = $w;
            }
            if($this->logoAlign=='R'){
                $nImgW = round($w/3,0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm),0);
                $xImg = round($x+($w-(1+$nImgW)),0);
                $yImg = round(($h-$nImgH)/2,0)+$y;
                $x1 = $x;
                $y1 = round($h/3+$y,0);
                $tw = round(2*$w/3,0);
            }
            $this->pdf->Image($this->logomarca, $xImg, $yImg, $nImgW, $nImgH, 'jpeg');
        } else {
            $x1 = $x;
            $y1 = round($h/3+$y,0);
            $tw = $w;
        }

        //Nome emitente
        //endereço
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $razao = $this->xNome;
        $cnpj = 'CNPJ: '.$this->__format($this->CNPJ,"###.###.###/####-##");
        $ie = 'IE: '.$this->__format($this->IE, '##/########');
        $lgr = 'Logradouro: '.$this->xLgr;
        $nro = 'Nº: '.$this->nro;
        //$cpl = $this->cpl;
        $bairro = 'Bairro: '.$this->xBairro;
        $CEP = $this->CEP;
        $CEP = 'CEP: '.$this->__format($CEP,"##.###-###");
        $mun = 'Municipio: '.$this->xMun;
        $UF = 'UF: '.$this->UF;
        
        $texto = $razao . "\n" . $cnpj . ' - ' . $ie . "\n";
        $texto .= $lgr . ' - ' . $nro . "\n";
        $texto .= $bairro . "\n";
        $texto .= $mun . ' - ' . $UF . ' - ' . $CEP;
        $this->__textBox($x1,$y1,$tw,8,$texto,$aFont,'T','L',0,'');

        //##################################################

        //$x += $w;
        $y = $h + 8;
        $this->__textBox($x,$y,$maxW,6);

        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'I');
        $this->__textBox($x,$y,$maxW,8,'DAMDFE - Documento Auxiliar de Manifesto Eletronico de Documentos Fiscais',$aFont,'T','C',0,'');

        $y = $y + 8;
        $this->__textBox($x,$y,$maxW,20);
        
        $bH = 15;
        $w = $maxW;
        $bW = round(($w / 3), 0);
        $this->pdf->SetFillColor(0,0,0);
        $this->pdf->Code128($x + $bW, $y+2, $this->chMDFe, $bW, $bH);
        $this->pdf->SetFillColor(255,255,255);
        
        $y = $y + 22;
        $this->__textBox($x,$y,$maxW,10);
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $tsHora = $this->__convertTime($this->dhEvento);
        $texto = 'CHAVE DE ACESSO';
        $this->__textBox($x,$y,$maxW,6,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $texto = $this->__format( $this->chMDFe, $this->formatoChave );
        $this->__textBox($x,$y+4,$maxW,6,$texto,$aFont,'T','C',0,'');

        $y = $y + 12;
        $this->__textBox($x,$y,$maxW,10);
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $texto = 'PROTOCOLO DE AUTORIZACAO DE USO';
        $this->__textBox($x,$y,$maxW,8,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        if(is_object($this->mdfeProc)){
            $tsHora = $this->__convertTime($this->dhRecbto);
            $texto = $this->nProt.' - '.date('d/m/Y   H:i:s',$tsHora);
        }else {
            $texto = 'DAMDFE impresso em contingência - '.date('d/m/Y   H:i:s');
        }
        $this->__textBox($x,$y+4,$maxW,8,$texto,$aFont,'T','C',0,'');

        /********************************************************************************/
        if ( $this->tpAmb != 1 ) {
            $x = 10;
            if( $this->orientacao == 'P' ){
                $yy = round($this->hPrint*2/3,0);
            }else{
                $yy = round($this->hPrint/2,0);
            }
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90,90,90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
            $this->__textBox($x,$yy,$w,$h,$texto,$aFont,'C','C',0,'');
            $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->__textBox($x,$yy+14,$w,$h,$texto,$aFont,'C','C',0,'');
            $this->pdf->SetTextColor(0,0,0);
        } else {
            $x = 10;
            if( $this->orientacao == 'P' ){
                $yy = round($this->hPrint*2/3,0);
            } else {
                $yy = round($this->hPrint/2,0);
            }//fim orientacao
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90,90,90);
            //indicar FALTA DO PROTOCOLO se MDFe não for em contingência
            if( ($this->tpEmis == 2 || $this->tpEmis == 5)){
                //Contingência
                $texto = "DAMDFE Emitido em Contingência";
                $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
                $this->__textBox($x,$yy,$w,$h,$texto,$aFont,'C','C',0,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
                $texto = "devido à problemas técnicos";
                $this->__textBox($x,$yy+12,$w,$h,$texto,$aFont,'C','C',0,'');
            }/* else {
                if ( !isset($this->nfeProc) ) {
		    if(!$this->__notaDPEC()){
                        $texto = "SEM VALOR FISCAL";
                        $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
                        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
		    }
                    $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
                    $texto = "FALTA PROTOCOLO DE APROVAÇÃO DA SEFAZ";
		    if(!$this->__notaDPEC()){
		        $this->__textBox($x,$y+12,$w,$h,$texto,$aFont,'C','C',0,'');
		    }else{
		        $this->__textBox($x,$y+25,$w,$h,$texto,$aFont,'C','C',0,'');
		    }
                }//fim nefProc
            }//fim tpEmis
            */
            $this->pdf->SetTextColor(0,0,0);
        }
        return $y+12;

    }// fim __headerMDFe

    /**
     *
     * @param type $x
     * @param int $y
     */
    /**
     * DamdfeNFePHP::__bodyMDFe()
     * 
     * @param mixed $x
     * @param mixed $y
     * @return void
     */
    private function __bodyMDFe($x,$y){

        $maxW = $this->wPrint;
        
        $x1 = $x + 20;
        $this->__textBox($x,$y,$x1,12);
        
        $texto = 'Modelo';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x,$y,$x1,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = $this->mod;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x,$y+4,$x1,10,$texto,$aFont,'T','C',0,'',false);

        $x1 += 7;
        $this->__textBox($x1,$y,$x1,12);
        
        $texto = 'Série';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x1,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = $this->serie;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x1,10,$texto,$aFont,'T','C',0,'',false);
        
        $x1 += 34;
        
        $this->__textBox($x1,$y,$x1 - 12,12);
        
        $texto = 'Número';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x1 - 12,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = $this->__format(str_pad($this->nMDF, 9, '0', STR_PAD_LEFT), '###.###.###');
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x1,10,$texto,$aFont,'T','C',0,'',false);
        
        $x1 += 56;
        $x2 = 15;
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'FL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = '1';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x2,10,$texto,$aFont,'T','C',0,'',false);
        
        $x1 += 15;
        $x2 = 40;
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'Data e Hora de Emissão';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $data = explode('T', $this->dhEmi);
        $texto = $this->__ymd2dmy($data[0]).' - '.$data[1];
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x2,10,$texto,$aFont,'T','C',0,'',false);
        
        $x1 += 40;
        $x2 = 24;
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'UF Carregamento';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = $this->UFIni;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x2,10,$texto,$aFont,'T','C',0,'',false);
        
        $x1 = $x;
        $x2 = $maxW;
        $y += 14;
        $this->__textBox($x1,$y,$x2,53);
        
        $texto = 'Modal Rodoviário de Carga';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x1,$y+1,$x2,8,$texto,$aFont,'T','C',0,'',false);
        
        $x1 = $x;
        $x2 = 30;
        $y += 6;
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'CIOT';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $x1 += 30;
        $x2 = 30;
        
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'Qtd. CT-e';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $x1 += 30;
        $x2 = 30;
        
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'Qtd. CTRC';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $x1 += 30;
        $x2 = 35;
        
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'Qtd. NF-e';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = str_pad($this->qNFe, 3, '0', STR_PAD_LEFT);
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x2,10,$texto,$aFont,'T','C',0,'',false);
        
        $x1 += 35;
        $x2 = 30;
        
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'Qtd. NF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);


        $x1 += 30;
        $x2 = 41;
        
        $this->__textBox($x1,$y,$x2,12);
        
        $texto = 'Peso Total (Kg)';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $texto = number_format($this->qCarga, 4, ',', '.');
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x2,10,$texto,$aFont,'T','C',0,'',false);
        
        
        $x1 = $x;
        $y += 12;
        $yold = $y;
        $x2 = round($maxW / 2, 0);
        
        $this->__textBox($x1,$y,$x2,35);
        
        $texto = 'Veículo';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $y += 5;
        $x2 = round($maxW / 4, 0);
        
        $this->__textBox($x1,$y,$x2,15);
        
        $texto = 'Placa';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
                
        $texto = $this->placa;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x1,$y+4,$x2,10,$texto,$aFont,'T','C',0,'',false);
        
        
        $x1 += $x2;
        $this->__textBox($x1,$y,$x2,15);
        
        $texto = 'RNTRC';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        
        $x1 = $x;
        $y += 15;
        $x2 = round($maxW / 2, 0);
        
        $this->__textBox($x1,$y,$x2,15);
        
        $texto = 'Vale Pedágio';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $y += 5;
        $x2 = round($x2 / 3, 0);
        $this->__textBox($x1,$y,$x2,10);
        
        $texto = 'Responsável CNPJ';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $x1 += $x2;
        $this->__textBox($x1,$y,$x2,10);
        
        $texto = 'Fornecedora CNPJ';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $x1 += $x2;
        $this->__textBox($x1,$y,$x2 - 1,10);
        
        $texto = 'Nº Comprovante';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2 - 1,8,$texto,$aFont,'T','L',0,'',false);
        
        
        $x1 = round($maxW / 2, 0) + 7;
        $y = $yold;
        $x2 = round($maxW / 2, 0);
        
        $this->__textBox($x1,$y,$x2,35);
        
        $texto = 'Condutor';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        
        $y += 5;
        $x2 = round($maxW / 4, 0);
        
        $this->__textBox($x1,$y,$x2,30);
        
        $texto = 'CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        $this->condutor = $this->veicTracao->getElementsByTagName('condutor');
        $yold = $y;
        for($i = 0; $i < $this->condutor->length; $i++){
            $y += (4 * ($i + 1));
            $texto = $this->condutor->item($i)->getElementsByTagName('CPF')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
            $this->__textBox($x1 + 1,$y,$x2 - 1,10,$texto,$aFont,'T','L',0,'',false);
        }
        
        $y = $yold;
        $x1 += $x2;
        
        $this->__textBox($x1,$y,$x2,30);
        
        $texto = 'Nome';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x1,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);
        
        for($i = 0; $i < $this->condutor->length; $i++){
            $y += (4 * ($i + 1));
            $texto = $this->condutor->item($i)->getElementsByTagName('xNome')->item(0)->nodeValue;
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
            $this->__textBox($x1 + 1,$y,$x2 - 1,8,$texto,$aFont,'T','L',0,'',false);
        }
        
        return $yold + 32;
        
    }//fim __bodyMDFe


    /**
     *
     * @param type $x
     * @param type $y
     */
    private function __footerMDFe($x,$y){
        $maxW = $this->wPrint;
        $x2 = $maxW;
        $this->__textBox($x,$y,$x2,60);
        
        $texto = 'Observações ';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $this->__textBox($x,$y,$x2,8,$texto,$aFont,'T','L',0,'',false);

        $y = $this->hPrint -4;
        $texto = "Impresso em  ". date('d/m/Y   H:i:s');
        $w = $this->wPrint-4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','L',0,'');

        /*
        $texto = "DamdfeNFePHP ver. " . $this->version .  "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','R',0,'http://www.nfephp.org');
        */

    }//fim __footerCCe

    /**
     *
     * @param type $nome
     * @param string $destino
     * @param type $printer
     * @return type
     */
    public function printMDFe($nome='',$destino='I',$printer=''){
        //monta
        $command = '';
        if ($nome == ''){
            $file = $this->pdfDir.'mdfe.pdf';
        } else {
            $file = $this->pdfDir.$nome;
        }
        if (($destino != 'I' || $destino != 'S') && $destino != 'F'){
            $destino = 'I';
        }
        if ($printer != ''){
            $command = "-P $printer";
        }
        $this->__buildMDFe();
        $arq = $this->pdf->Output($file,$destino);

        if ( $destino == 'S' ){
            //aqui pode entrar a rotina de impressão direta
            $command = "lpr $command $file";
            system($comando,$retorno);
        }
        return $arq;

    }//fim printMDFe

} //fim MDFeNFePHP

?>
