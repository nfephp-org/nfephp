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
 * @name        DaEventoNFeNFePHP.class.php
 * @version     0.1.3
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2012 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *		Roberto Spadim <roberto at spadim dot com dot br>
 */
//define o caminho base da instalação do sistema
#if (!defined('PATH_ROOT')) {
#   define('PATH_ROOT', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
#}
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH')) {
	define('FPDF_FONTPATH', 'font/');
}
//situação externa do documento
if(!defined('NFEPHP_SITUACAO_EXTERNA_CANCELADA')){
	define('NFEPHP_SITUACAO_EXTERNA_CANCELADA', 1);
	define('NFEPHP_SITUACAO_EXTERNA_DENEGADA', 2);
	define('NFEPHP_SITUACAO_EXTERNA_NONE', 0);
}
//classe extendida da classe FPDF para montagem do arquivo pdf
require_once(dirname(dirname(__FILE__)).'/Common/PdfNFePHP.class.php');
//classe com as funções communs entre DANFE e DACTE
require_once(dirname(dirname(__FILE__)).'/Common/CommonNFePHP.class.php');
//interface 
require_once(dirname(dirname(__FILE__)).'/Common/DocumentoNFePHP.interface.php');

class DaEventoNFeNFePHP extends CommonNFePHP implements DocumentoNFePHP {
    
    //publicas
    public $logoAlign='C'; //alinhamento do logo
    public $yDados=0;
    public $debugMode=0; //ativa ou desativa o modo de debug
    public $aEnd=array();
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
    protected $version = '0.1.1';
    protected $wPrint; //largura imprimivel
    protected $hPrint; //comprimento imprimivel
    protected $wCanhoto; //largura do canhoto para a formatação paisagem
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    //variaveis da carta de correção
    protected $id;
    protected $chNFe;
    protected $tpAmb;
    protected $cOrgao;
    protected $xCorrecao;
    protected $xCondUso;
    protected $dhEvento;
    protected $cStat;
    protected $xMotivo;
    protected $xJust;
    protected $CNPJDest = '';
    protected $CPFDest = '';
    protected $dhRegEvento;
    protected $nProt;
    protected $tpEvento;
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
    * @version 1.0.1
    * @param string $docXML Arquivo XML da cce
    * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
    * @param string $sPapel Tamanho do papel (Ex. A4)
    * @param string $sPathLogo Caminho para o arquivo do logo
    * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
    * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
    * @param string $fonteDANFE Nome da fonte alternativa do DAnfe
    * @param array $aEnd array com o endereço do emitente
    * @param number $mododebug 1-SIM e 0-Não (0 default)
    */
    function __construct($docXML='', $sOrientacao='',$sPapel='',$sPathLogo='', $sDestino='I',$sDirPDF='', $fontePDF='', $aEnd='', $mododebug=0) {
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
        if (is_array($aEnd)){
            $this->aEnd = $aEnd;
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
        if ( !is_file($docXML) ){
		if (empty($docXML)){
		    $this->errMsg = 'Um caminho ou um arquivo xml de evento de NFe deve ser passado!';
		    $this->errStatus = true;
		    return false;
		}
	}else{
		$docXML = file_get_contents($docXML);
        }
        $this->dom = new DomDocument;
        $this->dom->loadXML($docXML);
        $this->procEventoNFe    = $this->dom->getElementsByTagName("procEventoNFe")->item(0);
        $this->evento           = $this->dom->getElementsByTagName("evento")->item(0);
        $this->infEvento        = $this->evento->getElementsByTagName("infEvento")->item(0);
        $this->retEvento        = $this->dom->getElementsByTagName("retEvento")->item(0);
        $this->rinfEvento       = $this->retEvento->getElementsByTagName("infEvento")->item(0);
        $this->tpEvento         = $this->infEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue;
        if(!in_array($this->tpEvento,array('110110','110111'))){
            $this->errMsg = 'Evento não implementado '.$tpEvento.' !!';
            $this->errStatus = true;
            return false;
        }
	$this->id = str_replace('ID', '', $this->infEvento->getAttribute("Id"));
        $this->chNFe = $this->infEvento->getElementsByTagName("chNFe")->item(0)->nodeValue;
	$this->aEnd['CNPJ']=substr($this->chNFe,6,14);
        $this->tpAmb = $this->infEvento->getElementsByTagName("tpAmb")->item(0)->nodeValue;
        $this->cOrgao = $this->infEvento->getElementsByTagName("cOrgao")->item(0)->nodeValue;
        $this->xCorrecao = $this->infEvento->getElementsByTagName("xCorrecao")->item(0);
		$this->xCorrecao=(empty($this->xCorrecao)?'':$this->xCorrecao->nodeValue);
        $this->xCondUso = $this->infEvento->getElementsByTagName("xCondUso")->item(0);
		$this->xCondUso=(empty($this->xCondUso)?'':$this->xCondUso->nodeValue);
        $this->xJust =  $this->infEvento->getElementsByTagName("xJust")->item(0);
		$this->xJust=(empty($this->xJust)?'':$this->xJust->nodeValue);
	$this->dhEvento = $this->infEvento->getElementsByTagName("dhEvento")->item(0)->nodeValue;
        $this->cStat = $this->rinfEvento->getElementsByTagName("cStat")->item(0)->nodeValue;
        $this->xMotivo = $this->rinfEvento->getElementsByTagName("xMotivo")->item(0)->nodeValue;
	
        $this->CNPJDest = !empty($this->rinfEvento->getElementsByTagName("CNPJDest")->item(0)->nodeValue)? $this->rinfEvento->getElementsByTagName("CNPJDest")->item(0)->nodeValue:'';
        $this->CPFDest =  !empty($this->rinfEvento->getElementsByTagName("CPFDest")->item(0)->nodeValue)? $this->rinfEvento->getElementsByTagName("CPFDest")->item(0)->nodeValue:'';
        $this->dhRegEvento = $this->rinfEvento->getElementsByTagName("dhRegEvento")->item(0)->nodeValue;
        $this->nProt = $this->rinfEvento->getElementsByTagName("nProt")->item(0)->nodeValue;
    }//fim __construct
  
    /**
     * simpleConsistencyCheck
     * @package NFePHP
     * @name simpleConsistencyCheck()
     * @version 1.0.0
     * @author Marcos Diez
     * @return bool Retorna se o documenento se parece com um DACTE ( condicao necessaria porem nao suficiente )
    */
    public function simpleConsistencyCheck(){
        if( 1 == 2 
            || $this->xml == null 
            || $this->infEvento == null
            ){ 
            return false; 
	}
	return true;
    }//fim simpleConsistencyCheck
  
    /**
     * monta
     * 
     * @package NFePHP
     * @name monta
     * @version 1.0
     * @author Marcos Diez
     * @param type $orientacao
     * @param type $papel
     * @param type $logoAlign
     * @return type 
     */    
    public function monta($orientacao='',$papel='A4',$logoAlign='C',$situacao_externa=NFEPHP_SITUACAO_EXTERNA_NONE,$CLASSE_PDF=false){
        return $this->montaDAEventoNFe($orientacao,$papel,$logoAlign,$situacao_externa,$CLASSE_PDF);
    }//fim monta
    
    /**
     * printDocument
     * 
     * @package NFePHP
     * @name montaDANFE
     * @version 1.0
     * @author Marcos Diez
     * @param type $nome 
     * @param type $destino
     * @param type $printer
     * @return object pdf 
     */ 	
    public function printDocument($nome='',$destino='I',$printer=''){
        return $this->printDAEventoNFe($nome,$destino,$printer);
    }//fim printDocument

    /**
     * montaDAEventoNFe
     * Esta função monta a DAEventoNFe conforme as informações fornecidas para a classe
     * durante sua construção.
     * A definição de margens e posições iniciais para a impressão são estabelecidas no
     * pelo conteúdo da funçao e podem ser modificados.
     * @package NFePHP
     * @name montaDAEventoNFe
     * @version ????
     * @param string $orientacao (Opcional) Estabelece a orientação da impressão (ex. P-retrato), se nada for fornecido será usado o padrão da NFe
     * @param string $papel (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID do evento extraido do arquivo XML
     */
    public function montaDAEventoNFe($orientacao='',$papel='A4',$logoAlign='C',$situacao_externa=NFEPHP_SITUACAO_EXTERNA_NONE,$CLASSE_PDF=false){
        if ($orientacao == ''){
            $orientacao = 'P';
        }
        $this->orientacao = $orientacao;
        $this->__adicionaLogoPeloCnpj();
        $this->papel = $papel;
        $this->logoAlign = $logoAlign;

	if($CLASSE_PDF!==false){
		$this->pdf = $CLASSE_PDF;
	}else{
		$this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
	}
        if( $this->orientacao == 'P' ){
            // margens do PDF
            $margSup = 2;
            $margEsq = 2;
            $margDir = 2;
            // posição inicial do relatorio
            $xInic = 1;
            $yInic = 1;
            if($this->papel =='A4'){ //A4 210x297mm
                $maxW = 210;
                $maxH = 297;
            }    
        } else {
            // margens do PDF
            $margSup = 3;
            $margEsq = 3;
            $margDir = 3;
            // posição inicial do relatorio
            $xInic = 5;
            $yInic = 5;
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
        $y = $this->__headerCCe($x,$y,$pag,$situacao_externa);
        //coloca os dados da CCe
        $y = $this->__bodyCCe($x,$y+15);
        //coloca os dados da CCe
        $y = $this->__footerCCe($x,$y+$this->hPrint-20);

        //retorna o ID do evento
	if($CLASSE_PDF!==false){
            $aR = array(
		'id'=>$this->id,
		'classe_PDF'=>$this->pdf);
            return $aR;
        } else {
            return $this->id;
        }        
    } //fim __buildCCe
    
    /**
     * 
     * @param type $x
     * @param type $y
     * @param type $pag
     * @return type
     */
    private function __headerCCe($x,$y,$pag,$situacao_externa=NFEPHP_SITUACAO_EXTERNA_NONE){
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;

        //####################################################################################
        //coluna esquerda identificação do emitente
        $w = round($maxW*0.41,0);// 80;
        if( $this->orientacao == 'P' ){
            $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        }else{
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        }
        $w1 = $w;
        $h=32;
        $oldY += $h;
        $this->__textBox($x,$y,$w,$h);
        $texto = 'IDENTIFICAÇÃO DO EMITENTE';
        $this->__textBox($x,$y,$w,5,$texto,$aFont,'T','C',0,'');
        if (is_file($this->logomarca)){
            $logoInfo = getimagesize($this->logomarca);
            //largura da imagem em mm
            $logoWmm = ($logoInfo[0]/72)*25.4;
            //altura da imagem em mm
            $logoHmm = ($logoInfo[1]/72)*25.4;
            if ($this->logoAlign=='L'){
                $nImgW = round($w/3,0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm),0);
                $xImg = $x+1;
                $yImg = round(($h-$nImgH)/2,0)+$y;
                //estabelecer posições do texto
                $x1 = round($xImg + $nImgW +1,0);
                $y1 = round($h/3+$y,0);
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
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'B');
        $texto = (isset($this->aEnd['razao'])?$this->aEnd['razao']:'');
        $this->__textBox($x1,$y1,$tw,8,$texto,$aFont,'T','C',0,'');

        //endereço
        $y1 = $y1+6;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $lgr = (isset($this->aEnd['logradouro'])?$this->aEnd['logradouro']:'');
        $nro = (isset($this->aEnd['numero'])?$this->aEnd['numero']:'');
        $cpl = (isset($this->aEnd['complemento'])?$this->aEnd['complemento']:'');
        $bairro = (isset($this->aEnd['bairro'])?$this->aEnd['bairro']:'');
        $CEP = (isset($this->aEnd['CEP'])?$this->aEnd['CEP']:'');
        $CEP = $this->__format($CEP,"#####-###");
        $mun = (isset($this->aEnd['municipio'])?$this->aEnd['municipio']:'');
        $UF = (isset($this->aEnd['UF'])?$this->aEnd['UF']:'');
        $fone = (isset($this->aEnd['telefone'])?$this->aEnd['telefone']:'');
        $email = (isset($this->aEnd['email'])?$this->aEnd['email']:'');
        $foneLen = strlen($fone);
        if ($foneLen > 0 ){
            $fone2 = substr($fone,0,$foneLen-4);
            $fone1 = substr($fone,0,$foneLen-8);
            $fone = '(' . $fone1 . ') ' . substr($fone2,-4) . '-' . substr($fone,-4);
        } else {
            $fone = '';
        }
        if ($email != ''){
            $email = 'Email: '.$email;
        }
	$texto = "";
	$tmp_txt=trim(	($lgr!=''?"$lgr, ":'').($nro!=0?$nro:"SN").($cpl!=''?" - $cpl":'') );
	$tmp_txt=($tmp_txt=='SN'?'':$tmp_txt);
	$texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
	$tmp_txt=trim(	$bairro . ($bairro!='' && $CEP!=''?" - ":'') . $CEP);
	$texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
	$tmp_txt=$mun;
	$tmp_txt.=($tmp_txt!='' && $UF!=''?" - ":'').$UF;
	$tmp_txt.=($tmp_txt!='' && $fone!=''?" - ":'').$fone;
	$texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
	$tmp_txt=$email;
	$texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
	$this->__textBox($x1,$y1-2,$tw,8,$texto,$aFont,'T','C',0,'');

        //##################################################

        $w2 = round($maxW - $w,0);
        $x += $w;
        $this->__textBox($x,$y,$w2,$h);

        $y1 = $y + $h;
        $aFont = array('font'=>$this->fontePadrao,'size'=>16,'style'=>'B');
	if($this->tpEvento=='110110')
		$texto='Representação Gráfica de CCe';
	else
		$texto='Representação Gráfica de Evento';
        $this->__textBox($x,$y+2,$w2,8,$texto,$aFont,'T','C',0,'');

        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'I');
	if($this->tpEvento=='110110')
		$texto='(Carta de Correção Eletrônica)';
	elseif($this->tpEvento=='110111')
		$texto='(Cancelamento de NFe)';
        $this->__textBox($x,$y+7,$w2,8,$texto,$aFont,'T','C',0,'');
        
        $texto = 'ID do Evento: '.$this->id;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x,$y+15,$w2,8,$texto,$aFont,'T','L',0,'');

        $tsHora = $this->__convertTime($this->dhEvento);
        $texto = 'Criado em : '. date('d/m/Y   H:i:s',$tsHora);
        $this->__textBox($x,$y+20,$w2,8,$texto,$aFont,'T','L',0,'');

        $tsHora = $this->__convertTime($this->dhRegEvento);
        $texto = 'Prococolo: '.$this->nProt.'  -  Registrado na SEFAZ em: '.date('d/m/Y   H:i:s',$tsHora);
        $this->__textBox($x,$y+25,$w2,8,$texto,$aFont,'T','L',0,'');
        
        //$cStat;
        //$tpAmb;
        //####################################################

        $x = $oldX;
        $this->__textBox($x,$y1,$maxW,40);
        $sY = $y1+40;
        if($this->tpEvento=='110110')
		$texto = 'De acordo com as determinações legais vigentes, vimos por meio desta comunicar-lhe que a Nota Fiscal, abaixo referenciada, contêm irregularidades que estão destacadas e suas respectivas correções, solicitamos que sejam aplicadas essas correções ao executar seus lançamentos fiscais.';
	elseif($this->tpEvento=='110111')
		$texto = 'De acordo com as determinações legais vigentes, vimos por meio desta comunicar-lhe que a Nota Fiscal, abaixo referenciada, esta cancelada, solicitamos que sejam aplicadas essas correções ao executar seus lançamentos fiscais.';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->__textBox($x+5,$y1,$maxW-5,20,$texto,$aFont,'T','L',0,'',false);

        //############################################
        $x = $oldX;
        $y = $y1;
        if ($this->CNPJDest != ''){
            $texto = 'CNPJ do Destinatário: '.$this->__format($this->CNPJDest,"##.###.###/####-##");
        }
        if ($this->CPFDest != ''){
            $texto = 'CPF do Destinatário: '.$this->__format($this->CPFDest,"###.###.###-##");            
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'B');
        $this->__textBox($x+2,$y+13,$w2,8,$texto,$aFont,'T','L',0,'');

        $numNF = substr($this->chNFe,25,9);
        $serie = substr($this->chNFe,22,3);
        $numNF = $this->__format($numNF,"###.###.###");
        $texto = "Nota Fiscal: " . $numNF .'  -   Série: '.$serie;
        $this->__textBox($x+2,$y+19,$w2,8,$texto,$aFont,'T','L',0,'');
        
        $bW = 87;
        $bH = 15;
        $x = 55;
        $y = $y1+13;
        $w = $maxW;
        $this->pdf->SetFillColor(0,0,0);
        $this->pdf->Code128($x+(($w-$bW)/2),$y+2,$this->chNFe,$bW,$bH);
        $this->pdf->SetFillColor(255,255,255);
        $y1 = $y+2+$bH;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $texto = $this->__format( $this->chNFe, $this->formatoChave );
        $this->__textBox($x,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'');

	$RETURN_VALUE=$sY+2;
        //$sY += 1;
	if($this->tpEvento=='110110'){
		$x = $oldX;
		$this->__textBox($x,$sY,$maxW,15);
		$texto = $this->xCondUso;
		$aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
		$this->__textBox($x+2,$sY+2,$maxW-2,15,$texto,$aFont,'T','L',0,'',false);
		$RETURN_VALUE=$sY+2;
	}
	/////////////////
        if (	$situacao_externa==NFEPHP_SITUACAO_EXTERNA_CANCELADA && 
		$this->tpEvento!='110111') {	// não tem sentido colcoar cancelado no cancelamento...
            //101 Cancelamento
            $x = 10;
            $y = $this->hPrint-130;
            $h = 25;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90,90,90);
            $texto = "NFe CANCELADA";
            $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
            $this->pdf->SetTextColor(0,0,0);
        }
        if (	$situacao_externa==NFEPHP_SITUACAO_EXTERNA_DENEGADA){
		// no denegado tem sentido, pois não deveria haver este evento...
            //110 301 302 Denegada
            $x = 10;
            $y = $this->hPrint-130;
            $h = 25;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90,90,90);
            $texto = "NFe USO DENEGADO";
            $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
            $y += $h;
            $h = 5;
            $w = $maxW-(2*$x);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
            $this->pdf->SetTextColor(0,0,0);
        }
	//indicar sem valor
        if ( $this->tpAmb != 1 ) {
            $x = 10;
            if( $this->orientacao == 'P' ){
                $y = round($this->hPrint*2/3,0);
            }else{
                $y = round($this->hPrint/2,0);
            }
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90,90,90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
            $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->__textBox($x,$y+14,$w,$h,$texto,$aFont,'C','C',0,'');
            $this->pdf->SetTextColor(0,0,0);
        }
        return $RETURN_VALUE;
        
    }// fim __headerCCe
    
    /**
     * 
     * @param type $x
     * @param int $y
     */
    private function __bodyCCe($x,$y){
        $maxW = $this->wPrint;
	if($this->tpEvento=='110110')
		$texto = 'CORREÇÕES A SEREM CONSIDERADAS';
	else
		$texto = 'JUSTIFICATIVA DO CANCELAMENTO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$maxW,5,$texto,$aFont,'T','L',0,'',false);

        $y += 5;
        $this->__textBox($x,$y,$maxW,190);
	if($this->tpEvento=='110110')
		$texto = $this->xCorrecao;
	elseif($this->tpEvento=='110111')
		$texto = $this->xJust;
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'B');
        $this->__textBox($x+2,$y+2,$maxW-2,150,$texto,$aFont,'T','L',0,'',false);
        
        
    }//fim __bodyCCe
    
    
    /**
     * 
     * @param type $x
     * @param type $y
     */
    private function __footerCCe($x,$y){
        $w = $this->wPrint;
	if($this->tpEvento=='110110')
		$texto = "Este documento é uma representação gráfica da CCe e foi impresso apenas para sua informação e não possue validade fiscal.\n A CCe deve ser recebida e mantida em arquivo eletrônico XML e pode ser consultada através dos Portais das SEFAZ.";
	elseif($this->tpEvento=='110111')
		$texto = "Este documento é uma representação gráfica do evento de NFe e foi impresso apenas para sua informação e não possue validade fiscal.\n O Evento deve ser recebido e mantido em arquivo eletrônico XML e pode ser consultada através dos Portais das SEFAZ.";
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'I');
        $this->__textBox($x,$y,$w,20,$texto,$aFont,'T','C',0,'',false);

        $y = $this->hPrint -4;
        $texto = "Impresso em  ". date('d/m/Y   H:i:s');
        $w = $this->wPrint-4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','L',0,'');

        $texto = "DAEventoNFeNFePHP ver. " . $this->version .  "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','R',0,'http://www.nfephp.org');
    }//fim __footerCCe

    /**
     * 
     * @param type $nome
     * @param string $destino
     * @param type $printer
     * @return type
     */
    public function printDAEventoNFe($nome='',$destino='I',$printer=''){
        $arq = $this->pdf->Output($nome,$destino);
        if ( $destino == 'S' ){
            //aqui pode entrar a rotina de impressão direta
        }
        return $arq;

/*
	// removido - não entendi para que servia, copiei do CTE
        //monta
        $command = '';
        if ($nome == ''){
            $file = $this->pdfDir.'cce.pdf';
        } else {
            $file = $this->pdfDir.$nome;
        }
        if (($destino != 'I' || $destino != 'S') && $destino != 'F'){
            $destino = 'I';
        }
        if ($printer != ''){
            $command = "-P $printer";
        } 
        $arq = $this->pdf->Output($file,$destino);
        
        if ( $destino == 'S' ){
            //aqui pode entrar a rotina de impressão direta
            $command = "lpr $command $file";
            system($comando,$retorno);
        }
        return $arq;
*/
    }//fim printCCe
     
} //fim CCeNFePHP

?>