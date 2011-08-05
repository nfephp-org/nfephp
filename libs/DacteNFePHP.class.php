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
 * @name        DacteNFePHP.class.php
 * @version     1.0.0
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 * 
 *          Rodrigo Rysdyk <rodrigo_rysdyk at hotmail dot com>
 * 
 * 
 */

//comente a linha abaixo para nao permitir qualquer aviso no codigo pdf, a linha abaixo é utilizada para debug
//error_reporting(E_ALL);
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
define('FPDF_FONTPATH','font/');
//classe extendida da classe FPDF para montagem do arquivo pfd
require_once('PdfNFePHP.class.php');

//classe principal
class DacteNFePHP {
    //publicas
    public $logoAlign='C'; //alinhamento do logo
    public $yDados=0;
    //privadas
    private $pdf; // objeto fpdf()
    private $xml; // string XML NFe
    private $logomarca=''; // path para logomarca em jpg
    private $errMsg=''; // mesagens de erro
    private $errStatus=FALSE;// status de erro TRUE um erro ocorreu FALSE sem erros
    private $orientacao='P'; //orientação da DACTE P-Retrato ou L-Paisagem
    private $papel='A4'; //formato do papel
    private $destino = 'I'; //destivo do arquivo pdf I-borwser, S-retorna o arquivo, D-força download, F-salva em arquivo local
    private $pdfDir=''; //diretorio para salvar o pdf com a opção de destino = F
    private $fontePadrao='Helvetica'; //Nome da Fonte para gerar o DACTE
    private $version = '1.0.0';
    //private $textoAdic = '';
    //private $wAdic = 0;
    private $wPrint; //largura imprimivel
    private $hPrint; //comprimento imprimivel
    //private $wCanhoto; //largura do canhoto para a formatação paisagem
    //private $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";

    //objetos DOM da CTe
    private $dom;
    private $infCte;
    private $ide;
    private $emit;
    private $rem;
    private $dest;
    private $enderEmit;
    private $enderDest;
    private $enderReme;
    private $exped;
    private $enderExped;
    private $receb;
    private $infCarga;
    private $rodo;
    private $enderReceb;
    private $tpImp; //1-Retrato/ 2-Paisagem
    private $tpAmb;
    private $vPrest;
    private $Comp;
    private $ICMS;
    private $infNF;
    private $infNFe;
    private $compl;

    /**
     *__construct
     * @package NFePHP
     * @name __construct
     * @version 1.0
     * @param string $docXML Arquivo XML da CTe
     * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
     * @param string $sPapel Tamanho do papel (Ex. A4)
     * @param string $sPathLogo Caminho para o arquivo do logo
     * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
     * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
     * @param string $fonteDACTE Nome da fonte a ser utilizada
     */
    function __construct($docXML='', $sOrientacao='',$sPapel='',$sPathLogo='', $sDestino='I',$sDirPDF='',$fonteDACTE='') {
        $this->orientacao = $sOrientacao;
        $this->papel = $sPapel;
        $this->pdf = '';
        $this->xml = $docXML;
        $this->logomarca = $sPathLogo;
        $this->destino = $sDestino;
        $this->pdfDir = $sDirPDF;
        // verifica se foi passa a fonte a ser usada
        if (empty($fonteDACTE)) {
            $this->fontePadrao = 'Helvetica';
        } else {
            $this->fontePadrao = $fonteDACTE;
        }
        //se for passado o xml
        if ( !empty($this->xml) ) {
            $this->dom = new DomDocument;
            $this->dom->loadXML($this->xml);

            $this->infCte     = $this->dom->getElementsByTagName("infCte")->item(0);
            $this->ide        = $this->dom->getElementsByTagName("ide")->item(0);
            $this->emit       = $this->dom->getElementsByTagName("emit")->item(0);
            $this->enderEmit  = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->rem        = $this->dom->getElementsByTagName("rem")->item(0);
            $this->enderReme  = $this->dom->getElementsByTagName("enderReme")->item(0);
            $this->dest       = $this->dom->getElementsByTagName("dest")->item(0);
            $this->enderDest  = $this->dom->getElementsByTagName("enderDest")->item(0);
            $this->exped      = $this->dom->getElementsByTagName("exped")->item(0);
            $this->enderExped = $this->dom->getElementsByTagName("enderExped")->item(0);
            $this->receb      = $this->dom->getElementsByTagName("receb")->item(0);
            $this->enderReceb = $this->dom->getElementsByTagName("enderReceb")->item(0);
            $this->infCarga   = $this->dom->getElementsByTagName("infCarga")->item(0);
            $this->rodo       = $this->dom->getElementsByTagName("rodo")->item(0);
            $this->vPrest     = $this->dom->getElementsByTagName("vPrest")->item(0);
            $this->Comp       = $this->dom->getElementsByTagName("Comp");
            $this->infNF      = $this->dom->getElementsByTagName("infNF");
            $this->infNFe      = $this->dom->getElementsByTagName("infNFe");
            $this->compl      = $this->dom->getElementsByTagName("compl");
            $this->ICMS = $this->dom->getElementsByTagName("ICMS")->item(0);
            $this->imp  = $this->dom->getElementsByTagName("imp")->item(0);

            $toma = (!empty($this->ide->getElementsByTagName("toma")->item(0)->nodeValue)  || $this->ide->getElementsByTagName("toma")->item(0)->nodeValue==0 ) ? $this->ide->getElementsByTagName("toma")->item(0)->nodeValue : '';
            //0-Remetente;1-Expedidor;2-Recebedor;3-Destinatário;4 - Outros
            switch ($toma){
                case '0':
                    $this->toma      = $this->rem;
                    $this->enderToma = $this->enderReme;
                   break;
                case '1':
                    $this->toma      = $this->exped;
                    $this->enderToma = $this->enderExped;
                    break;
                case '2':
                    $this->toma      = $this->receb;
                    $this->enderToma = $this->enderReceb;
                    break;
                case '3':
                    $this->toma      = $this->dest;
                    $this->enderToma = $this->enderDest;
                    break;
                case '4':
                    $this->toma      = '??';
                    $this->enderToma = '??';
                    break;
                default:
                    $this->toma      = '??';
                    $this->enderToma = '??';
            }

            $this->tpImp      = $this->ide->getElementsByTagName("tpImp")->item(0)->nodeValue;
            $this->tpAmb      = $this->ide->getElementsByTagName("tpAmb")->item(0)->nodeValue;
            $this->protCTe    = $this->dom->getElementsByTagName("protCTe")->item(0);
        }
    } //fim construct

    /**
     * montaDACTE
     * Esta função monta a DACTE conforme as informações fornecidas para a classe
     * durante sua construção.
     * A definição de margens e posições iniciais para a impressão são estabelecidas no
     * pelo conteúdo da funçao e podem ser modificados.
     * @package NFePHP
     * @name montaDACTE
     * @version 1.00
     * @param string $orientacao (Opcional) Estabelece a orientação da impressão (ex. P-retrato), se nada for fornecido será usado o padrão da NFe
     * @param string $papel (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID da NFe numero de 44 digitos extraido do arquivo XML
     */
    public function montaDACTE($orientacao='',$papel='A4',$logoAlign='C'){
        //se a orientação estiver em branco utilizar o padrão estabelecido na NF
        if ($orientacao == ''){
            if($this->tpImp == '1'){
                $orientacao = 'P';
            } else {
                $orientacao = 'L';
            }
        }
        $this->orientacao = $orientacao;
        $this->papel = $papel;
        $this->logoAlign = $logoAlign;
        //instancia a classe pdf
        $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        if( $this->orientacao == 'P' ){
            // margens do PDF
            $margSup = 2;
            $margEsq = 2;
            $margDir = 2;
            // posição inicial do relatorio
            $xInic = 1;
            $yInic = 1;
            if($papel =='A4'){ //A4 210x297mm
                $maxW = 210;
                $maxH = 297;
            }    
        }else{
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
                $this->wCanhoto = 25;
            }
        }
        //total inicial de paginas
        $totPag = 1;
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
        //calculo do numero de páginas ???
        $totPag = 1;

        //montagem da primeira página
        $pag = 1;
        $x = $xInic;
        $y = $yInic;

        //coloca o cabeçalho
        $y = $this->__cabecalhoDACTE($x,$y,$pag,$totPag);

        $y+= 31;
        $r = $this->__remetenteDACFE($x ,$y);

        $x = $this->wPrint*0.5+2;
        $r = $this->__destinatarioDACFE($x ,$y);

        $y+= 20;
        $x = $xInic;
        $r = $this->__expedidorDACFE($x ,$y);

        $x = $this->wPrint*0.5+2;
        $r = $this->__recebedorDACFE($x ,$y);

        $y+= 20;
        $x = $xInic;
        $r = $this->__tomadorDACFE($x ,$y);

        $y+= 11;
        $x = $xInic;
        $r = $this->__descricaoCargaDACFE($x ,$y);

        $y+= 18;
        $x = $xInic;
        $r = $this->__componentesValorDACFE($x ,$y);

        $y+= 26;
        $x = $xInic;
        $r = $this->__impostosDACFE($x ,$y);

        $y+= 14;
        $x = $xInic;
        $r = $this->__documentosOriginariosDACFE($x ,$y);

        $y+= 44;
        $x = $xInic;
        $r = $this->__observacoesDACFE($x ,$y);

        $y+= 21;
        $x = $xInic;
        $r = $this->__modalRodoviarioDACFE($x ,$y);

        $y+= 14;
        $x = $xInic;
        $r = $this->__canhotoDACFE($x ,$y);

        //coloca o rodapé da página
        if( $this->orientacao == 'P' ){
            $this->__rodapeDACTE( 2 , $this->hPrint - 2 );
        }else{
            $this->__rodapeDACTE($xInic,$this->hPrint + 2.3);
        }
        //retorna o ID na CTe
        return '1234';
        //return str_replace('CTe', '', $this->infCTe->getAttribute("Id"));
        
    }//fim da função montaDACTE

    /**
     * printDACTE
     * Esta função envia a DACTE em PDF criada para o dispositivo informado.
     * O destino da impressão pode ser :
     * I-browser
     * D-browser com download
     * F-salva em um arquivo local com o nome informado
     * S-retorna o documento como uma string e o nome é ignorado.
     * Para enviar o pdf diretamente para uma impressora indique o
     * nome da impressora e o destino deve ser 'S'.
     * @package NFePHP
     * @name printDACTE
     * @version 1.0
     * @param string $nome Path completo com o nome do arquivo pdf
     * @param string $destino Direção do envio do PDF
     * @param string $printer Identificação da impressora no sistema
     * @return string Caso o destino seja S o pdf é retornado como uma string
     * @todo Rotina de impressão direta do arquivo pdf criado
     */
    public function printDACTE($nome='',$destino='I',$printer=''){
        $arq = $this->pdf->Output($nome,$destino);
        if ( $destino == 'S' ){
            //aqui pode entrar a rotina de impressão direta
        }
        return $arq;
    } //fim função printDACTE

    /**
     *__cabecalhoDACTE
     * Monta o cabelhalho da DACTE ( retrato e paisagem )
     * @package NFePHP
     * @name __cabecalhoDACTE
     * @version 1.00
     * @param number $x Posição horizontal inicial, canto esquerdo
     * @param number $y Posição vertical inicial, canto superior
     * @param number $pag Número da Página
     * @param number $totPag Total de páginas
     * @return number Posição vertical final
     */
    private function __cabecalhoDACTE($x=0,$y=0,$pag='1',$totPag='1'){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            if( $pag == 1 ){ // primeira página
                $maxW = $this->wPrint - $this->wCanhoto;
            }else{ // páginas seguintes
                $maxW = $this->wPrint;
            }
        }
        
        //####################################################################################
        //coluna esquerda identificação do emitente
        $w = round($maxW*0.50,0);
        if( $this->orientacao == 'P' ){
            $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        }else{
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        }
        $w1 = $w;
        $h = 42;
        $oldY += $h;
        //desenha a caixa
        $this->__textBox($x,$y,$w,$h);
        
        //$texto = 'IDENTIFICAÇÃO DO EMITENTE';
        // coloca o logo
        if (is_file($this->logomarca)){
            $logoInfo=getimagesize($this->logomarca);
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
        $texto = $this->emit->getElementsByTagName("xNome")->item(0)->nodeValue;
        $this->__textBox($x1,$y1,$tw,8,$texto,$aFont,'T','C',0,'');
        //endereço
        $y1 = $y1+5;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $fone = !empty($this->enderEmit->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $foneLen = strlen($fone);
        if ($foneLen > 0 ){
            $fone2 = substr($fone,0,$foneLen-4);
            $fone1 = substr($fone,0,$foneLen-8);
            $fone = '(' . $fone1 . ') ' . substr($fone2,-4) . '-' . substr($fone,-4);
        } else {
            $fone = '';
        }
        
        $lgr = !empty($this->enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
        $nro = !empty($this->enderEmit->getElementsByTagName("nro")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("nro")->item(0)->nodeValue : '';
        $cpl = !empty($this->enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
        $bairro = !empty($this->enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
        $CEP = !empty($this->enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue : ' ';
        $CEP = $this->__format($CEP,"#####-###");
        $mun = !empty($this->enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue : '';
        $UF = !empty($this->enderEmit->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("UF")->item(0)->nodeValue : '';
        $xPais = !empty($this->enderEmit->getElementsByTagName("xPais")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xPais")->item(0)->nodeValue : '';
        $texto = $lgr . "," . $nro . "  " . $cpl . "\n" . $bairro . " - " . $CEP . "\n" . $mun . " - " . $UF . " " . $xPais . "\n  Fone/Fax: " . $fone;
        $this->__textBox($x1,$y1,$tw,8,$texto,$aFont,'T','C',0,'');
        //CNPJ/CPF IE
        $cnpj = !empty($this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $this->__format($this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue,'###.###.###/####-##') : '';
        $cpf = !empty($this->emit->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->emit->getElementsByTagName("CPF")->item(0)->nodeValue,'###.###.###.###-##') : '';
        $ie = !empty($this->emit->getElementsByTagName("IE")->item(0)->nodeValue) ? $this->emit->getElementsByTagName("IE")->item(0)->nodeValue : '';
        $texto = 'CNPJ/CPF:  '.$cnpj.$cpf.'     Insc.Estadual: '.$ie;
        $this->__textBox($x1,$y1+14,$tw,8,$texto,$aFont,'T','C',0,'');
        
        //outra caixa
        $h1 = 11.2;
        $y1 = $y+$h+1;
        $this->__textBox($x,$y1,$w,$h1);
        //TIPO DO CT-E
        $texto = 'Tipo do CTe';
        $wa = 20;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y1,$wa,$h1,$texto,$aFont,'T','C',0,'');
        $tpCTe = (!empty($this->ide->getElementsByTagName("tpCTe")->item(0)->nodeValue) || $this->ide->getElementsByTagName("tpCTe")->item(0)->nodeValue==0) ? $this->ide->getElementsByTagName("tpCTe")->item(0)->nodeValue : '';
        //0 - CT-e Normal,1 - CT-e de Complemento de Valores,2 - CT-e de Anulação de Valores,3 - CT-e Substituto
        switch ($tpCTe){
            case '0':
                $texto = 'Normal';
                break;
            case '1':
                $texto = 'Complemento de Valores';
                break;
            case '2':
                $texto = 'Anulação de Valores';
                break;
            case '3':
                $texto = 'Substituto';
                break;
            default:
                $texto = 'ERRO'.$tpCTe.$tpServ;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y1+4,$wa,$h1,$texto,$aFont,'T','C',0,'',false);
        $this->pdf->Line($x+$wa,$y1,$x+$wa,$y1+$h1);
        
        //TIPO DO SERVIÇO
        $texto = 'Tipo do Serviço';
        $wb = 26;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x+$wa,$y1,$wb,$h1,$texto,$aFont,'T','C',0,'');
        $tpServ = (!empty($this->ide->getElementsByTagName("tpServ")->item(0)->nodeValue)  || $this->ide->getElementsByTagName("tpServ")->item(0)->nodeValue==0 ) ? $this->ide->getElementsByTagName("tpServ")->item(0)->nodeValue : '';
        //0 - Normal;1 - Subcontratação;2 - Redespacho;3 - Redespacho Intermediário
        switch ($tpServ){
            case '0':
                $texto = 'Normal';
                break;
            case '1':
                $texto = 'Subcontratação';
                break;
            case '2':
                $texto = 'Redespacho';
                break;
            case '3':
                $texto = 'Redespacho Intermediário';
            default:
                $texto = 'ERRO'.$tpServ;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x+$wa,$y1+4,$wb,$h1,$texto,$aFont,'T','C',0,'',false);
        $this->pdf->Line($x+$wa+$wb,$y1,$x+$wa+$wb,$y1+$h1);
        
        //TOMADOR DO SERVIÇO
        $texto = 'Tomador do Serviço';
        $wc = 26;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x+$wa+$wb,$y1,$wc,$h1,$texto,$aFont,'T','C',0,'');
        $this->pdf->Line($x+$wa+$wb+$wc,$y1,$x+$wa+$wb+$wc,$y1+$h1);
        
        $toma = (!empty($this->ide->getElementsByTagName("toma")->item(0)->nodeValue)  || $this->ide->getElementsByTagName("toma")->item(0)->nodeValue==0 ) ? $this->ide->getElementsByTagName("toma")->item(0)->nodeValue : '';
        //0-Remetente;1-Expedidor;2-Recebedor;3-Destinatário;4 - Outros
        switch ($toma){
            case '0':
                $texto = 'Remetente';
                break;
            case '1':
                $texto = 'Expedidor';
                break;
            case '2':
                $texto = 'Recebedor';
                break;
            case '3':
                $texto = 'Destinatário';
                break;
            case '4':
                $texto = 'Outros';
                break;
            default:
                $texto = 'ERRO'.$toma;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x+$wa+$wb,$y1+4,$wb,$h1,$texto,$aFont,'T','C',0,'',false);
        
        
        
        //FORMA DE PAGAMENTO
        $texto = 'Forma de Pagamento';
        $wd = 30;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x+$wa+$wb+$wc,$y1,$wd,$h1,$texto,$aFont,'T','C',0,'');
        
        $forma = (!empty($this->ide->getElementsByTagName("forPag")->item(0)->nodeValue)  || $this->ide->getElementsByTagName("forPag")->item(0)->nodeValue==0 ) ? $this->ide->getElementsByTagName("forPag")->item(0)->nodeValue : '';
        //0 - Pago;1 - A pagar;2 - outros
        switch ($forma){
            case '0':
                $texto = 'Pago';
                break;
            case '1':
                $texto = 'A pagar';
                break;
            case '2':
                $texto = 'Outros';
                break;
            default:
                $texto = 'ERRO'.$forma;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x+$wa+$wb+$wc+2,$y1+4,$wb,$h1,$texto,$aFont,'T','C',0,'',false);
        
        
        //####################################################################################
        //coluna direita
        $x += $w+2;
        $w=round($maxW * 0.25,0);
        $w1 = $w;
        $h = 11;
        $this->__textBox($x,$y,$w,$h);
        $texto = "DACTE";
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y+1,$w,$h,$texto,$aFont,'T','C',0,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $texto = "Documento Auxiliar do Conhecimento\nde Transporte Eletrônico";
        $h = 10;
        $this->__textBox($x,$y+4,$w,$h,$texto,$aFont,'T','C',0,'',FALSE);
        //$aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $x1 = $x+$w+2;
        $w=round($maxW * 0.22,0);
        $w2 = $w;
        $h = 11;
        $this->__textBox($x1,$y,$w,$h);
        $texto = "MODAL";
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x1,$y+1,$w,$h,$texto,$aFont,'T','C',0,'');

        //01-Rodoviário; //02-Aéreo; //03-Aquaviário; //04-Ferroviário;//05-Dutoviário
        $modal = !empty($this->ide->getElementsByTagName("modal")->item(0)->nodeValue) ? $this->ide->getElementsByTagName("modal")->item(0)->nodeValue : '';
        switch ($modal){
            case '1':
                $texto = 'Rodoviário';
                break;
            case '2':
                $texto = 'Aéreo';
                break;
            case '3':
                $texto = 'Aquaviário';
                break;
            case '4':
                $texto = 'Ferroviário';
                break;
            case '5':
                $texto = 'Dutoviário';
                break;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x1,$y+5,$w,$h,$texto,$aFont,'T','C',0,'');
        //outra caixa
        $y += 12;
        $h = 9;
        $w = $w1+$w2+2;
        $this->__textBox($x,$y,$w,$h);
        
        //modelo
        $wa = 12;
        $xa = $x;
        $texto= 'Modelo';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($xa,$y+1,$wa,$h,$texto,$aFont,'T','C',0,'');
        $texto = !empty($this->ide->getElementsByTagName("mod")->item(0)->nodeValue) ? $this->ide->getElementsByTagName("mod")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($xa,$y+5,$wa,$h,$texto,$aFont,'T','C',0,'');
        $this->pdf->Line($x+$wa, $y, $x+$wa, $y+$h);
        //serie
        $xa += $wa;
        $texto= 'Série';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($xa,$y+1,$wa,$h,$texto,$aFont,'T','C',0,'');
        $texto = ( !empty($this->ide->getElementsByTagName("serie")->item(0)->nodeValue) || $this->ide->getElementsByTagName("serie")->item(0)->nodeValue==0 )? $this->ide->getElementsByTagName("serie")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($xa,$y+5,$wa,$h,$texto,$aFont,'T','C',0,'');
        $this->pdf->Line($xa+$wa, $y, $xa+$wa, $y+$h);
        //numero
        $xa += $wa;
        $wa = 20;
        $texto= 'Número';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($xa,$y+1,$wa,$h,$texto,$aFont,'T','C',0,'');
        $texto = !empty($this->ide->getElementsByTagName("nCT")->item(0)->nodeValue) ? $this->ide->getElementsByTagName("nCT")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($xa,$y+5,$wa,$h,$texto,$aFont,'T','C',0,'');
        $this->pdf->Line($xa+$wa, $y, $xa+$wa, $y+$h);
        //folha
        $xa += $wa;
        $wa = 12;
        $texto= 'FL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($xa,$y+1,$wa,$h,$texto,$aFont,'T','C',0,'');
        $texto = '1/1';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($xa,$y+5,$wa,$h,$texto,$aFont,'T','C',0,'');
        $this->pdf->Line($xa+$wa, $y, $xa+$wa, $y+$h);
        //data  hora de emissão
        $xa += $wa;
        $wa = 40;
        $texto= 'Data e Hora de Emissão';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($xa,$y+1,$wa,$h,$texto,$aFont,'T','C',0,'');
        $texto = !empty($this->ide->getElementsByTagName("dhEmi")->item(0)->nodeValue) ? date('d/m/Y H:i:s',$this->__convertTime($this->ide->getElementsByTagName("dhEmi")->item(0)->nodeValue)) : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($xa,$y+5,$wa,$h,$texto,$aFont,'T','C',0,'');
        //outra caixa
        $y += $h+1;
        $h = 23;
        $this->__textBox($x,$y,$w,$h);
        $texto = 'CONTROLE DO FISCO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',0,'');
        
        //CODIGO DE BARRAS
        $chave_acesso = str_replace('CTe', '', $this->infCte->getAttribute("Id"));
        $bW = 85;
        $bH = 12;
        //codigo de barras
        $this->pdf->SetFillColor(0,0,0);
        $this->pdf->Code128($x+(($w-$bW)/2),$y+4,$chave_acesso,$bW,$bH);
        $texto = 'Chave de acesso para consulta de autenticidade no site www.cte.fazenda.gov.br ';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y+16,$w,$h,$texto,$aFont,'T','C',0,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $texto = $this->__format($chave_acesso,'##.####.##.###.###/####-##-##-###-###.###.###-###.###.###-#');
        $this->__textBox($x,$y+19,$w,$h,$texto,$aFont,'T','C',0,'');
        
        //outra caixa
        $y += $h+1;
        $h = 8;
        $wa = 30;
        $this->__textBox($x,$y,$w,$h);
        $texto = 'N. PROTOCOLO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y,$wa,$h,$texto,$aFont,'T','C',0,'');
        
        $texto = $this->protCTe->getElementsByTagName("nProt")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+4,$wa,$h,$texto,$aFont,'T','C',0,'');
        $this->pdf->Line($x+$wa, $y, $x+$wa, $y+$h);
        $wb = $w-$wa;
        $texto = 'INSC. SUFRAMA DO DESTINATÁRIO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x+$wa,$y,$wb,$h,$texto,$aFont,'T','C',0,'');
        
        $texto = 'xxxxxxxxxxxxxxxxxxxxxx';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x+$wa,$y+4,$wb,$h,$texto,$aFont,'T','C',0,'');
        
        
        //CFOP
        $y += $h+1;
        $x =  $oldX;
        $h = 8;
        $w = $maxW-0.7;
        $this->__textBox($x,$y,$w,$h);
        
        $texto = 'CFOP - Natureza da Prestação';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->ide->getElementsByTagName("nCT")->item(0)->nodeValue . ' - ' . $this->ide->getElementsByTagName("natOp")->item(0)->nodeValue;
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+3.5,$w,$h,$texto,$aFont,'T','L',0,'');
        
        //ORIGEM DA PRESTAÇÃO
        $y += $h+1;
        $x =  $oldX;
        $h = 8;
        $w = ($maxW*0.5);
        $this->__textBox($x,$y,$w,$h);
        
        $texto = 'Origem da Prestação';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->ide->getElementsByTagName("xMunIni")->item(0)->nodeValue . ' - ' . $this->ide->getElementsByTagName("UFEmi")->item(0)->nodeValue;
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+3.5,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        //DESTINO DA PRESTAÇÃO
        $x =  $oldX + $w + 1;
        $h = 8;
        $w = $w - 1.3;
        $this->__textBox($x,$y,$w,$h);
        
        $texto = 'Destino da Prestação';
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->ide->getElementsByTagName("xMunFim")->item(0)->nodeValue . ' - ' . $this->ide->getElementsByTagName("UFFim")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+3.5,$w,$h,$texto,$aFont,'T','L',0,'');
        
        return $oldY;
    } //fim __cabecalhoDANFE

    
     /**
     * __rodapeDACTE
     * Monta o rodape no final da DACTE ( retrato e paisagem )
     * @package NFePHP
     * @name __rodapeDACTEE
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param number $xInic Posição horizontal canto esquerdo
     * @param number $yFinal Posição vertical final para impressão
     */
    private function __rodapeDACTE($x,$y){
        $texto = "Impresso em  ". date('d/m/Y   H:i:s');
        $w = $this->wPrint-4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','L',0,'');
        $texto = "DacteNFePHP ver. " . $this->version .  "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','R',0,'http://www.nfephp.org');
    } //fim __rodapeDANFE
  
   
    /**
     * __remetenteDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __destinatarioDANFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __remetenteDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
                $maxW = $this->wPrint;
        }else{
                $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW*0.5;
        $h = 19;
        $texto = 'Remetente';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        $texto = $this->rem->getElementsByTagName("xNome")->item(0)->nodeValue;
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $y += 3;
        
        $texto = 'Endereço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        $texto = $this->enderReme->getElementsByTagName("xLgr")->item(0)->nodeValue . ',' . $this->enderReme->getElementsByTagName("nro")->item(0)->nodeValue;
        $texto = !empty($this->enderReme->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $texto . ' - '.$this->enderReme->getElementsByTagName("xCpl")->item(0)->nodeValue : $texto;
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        $texto = $this->enderReme->getElementsByTagName("xBairro")->item(0)->nodeValue;
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        $texto = 'Município';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->enderReme->getElementsByTagName("xMun")->item(0)->nodeValue . ' - ' . $this->enderReme->getElementsByTagName("UF")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x = $w - 18;
        $texto = 'CEP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->__format($this->enderReme->getElementsByTagName("CEP")->item(0)->nodeValue,"#####-###");
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+6,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        
        if ( !empty($this->rem->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
            $texto = $this->__format($this->rem->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
        } else {
            $texto = !empty($this->rem->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->rem->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
        }
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        
        $x = $w - 45;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->rem->getElementsByTagName("IE")->item(0)->nodeValue;;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+28,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'País';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->rem->getElementsByTagName("xPais")->item(0)->nodeValue;
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $w - 25;
        $texto = 'FONE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = !empty($this->rem->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->rem->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+8,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
    } //fim da função __remetenteDACFE
    
    
    /**
     * __destinatarioDACFE
     * Monta o campo com os dados do destinatário na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __destinatarioDANFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __destinatarioDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = ($maxW*0.5) - 1.2;
        $h = 19;
        $texto = 'Destinatário';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        $texto = $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue;
        $this->__textBox($x+14,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $y += 3;
        
        $texto = 'Endereço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        $texto = $this->enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue . ',' . $this->enderDest->getElementsByTagName("nro")->item(0)->nodeValue;
        $texto = !empty($this->enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $texto . ' - '.$this->enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue : $texto;
        $this->__textBox($x+14,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        $texto = $this->enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue;
        $this->__textBox($x+14,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        $texto = 'Município';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->enderDest->getElementsByTagName("xMun")->item(0)->nodeValue . ' - ' . $this->enderDest->getElementsByTagName("UF")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+14,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x = $w - 19 + $oldX;
        $texto = 'CEP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->__format($this->enderDest->getElementsByTagName("CEP")->item(0)->nodeValue,"#####-###");
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+6,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        
        if ( !empty($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
            $texto = $this->__format($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
        } else {
            $texto = !empty($this->dest->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->dest->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
        }
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+14,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        
        $x = $w - 47.5 + $oldX;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->dest->getElementsByTagName("IE")->item(0)->nodeValue;;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+28,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'País';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');

        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->dest->getElementsByTagName("xPais")->item(0)->nodeValue;
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+14,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $w - 27 + $oldX;
        $texto = 'FONE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = !empty($this->dest->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->dest->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+8,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
    } //fim da função __destinatarioDACFE
    
    
    
    /**
     * __expedidorDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __expedidorDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __expedidorDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
                $maxW = $this->wPrint;
        }else{
                $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW*0.5;
        $h = 19;
        $texto = 'Expedidor';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
    
        if(isset($this->exped))
            $texto = !empty($this->exped->getElementsByTagName("xNome")->item(0)->nodeValue) ? $this->exped->getElementsByTagName("xNome")->item(0)->nodeValue : '';
        else
            $texto ='';
        
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $y += 3;
        
        $texto = 'Endereço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        if(isset($this->enderExped)){
            $texto = $this->enderExped->getElementsByTagName("xLgr")->item(0)->nodeValue .  ', '.$this->enderExped->getElementsByTagName("nro")->item(0)->nodeValue;
                     
            $texto = !empty($this->enderExped->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $texto . ' - '.$this->enderExped->getElementsByTagName("xCpl")->item(0)->nodeValue : $texto;
        }
        else
            $texto = '';
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        if(isset($this->enderExped))
            $texto = !empty($this->enderExped->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $this->enderExped->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
        else
            $texto = '';
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        $texto = 'Município';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->enderExped))
            $texto = $this->enderExped->getElementsByTagName("xMun")->item(0)->nodeValue  .   ' - ' . $this->enderExped->getElementsByTagName("UF")->item(0)->nodeValue ;
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x = $w - 18;
        $texto = 'CEP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->enderExped))
            $texto = !empty($this->enderExped->getElementsByTagName("CEP")->item(0)->nodeValue) ? $this->__format($this->enderExped->getElementsByTagName("CEP")->item(0)->nodeValue,"#####-###") : '' ;
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+6,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->exped))
            if ( !empty($this->exped->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
                $texto = $this->__format($this->exped->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
            } else {
                $texto = !empty($this->exped->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->exped->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
            }
        else
            $texto = '';
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        
        $x = $w - 45;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->exped))
            $texto = !empty($this->exped->getElementsByTagName("IE")->item(0)->nodeValue) ? $this->exped->getElementsByTagName("IE")->item(0)->nodeValue : '';
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+28,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'País';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->exped))
            $texto = !empty($this->exped->getElementsByTagName("xPais")->item(0)->nodeValue) ? $this->exped->getElementsByTagName("xPais")->item(0)->nodeValue : '';
        else
            $texto = '';
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $w - 25;
        $texto = 'FONE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->exped))
            $texto = !empty($this->exped->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->exped->getElementsByTagName("fone")->item(0)->nodeValue : '';
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+8,$y,$w,$h,$texto,$aFont,'T','L',0,'');
    } //fim da função __remetenteDACFE
    
        /**
     * __recebedorDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __recebedorDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __recebedorDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
                $maxW = $this->wPrint;
        }else{
                $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = ($maxW*0.5) - 1.2;
        $h = 19;
        $texto = 'Expedidor';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
    
        if(isset($this->receb))
            $texto = !empty($this->receb->getElementsByTagName("xNome")->item(0)->nodeValue) ? $this->receb->getElementsByTagName("xNome")->item(0)->nodeValue : '';
        else
            $texto ='';
        
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $y += 3;
        
        $texto = 'Endereço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        if(isset($this->enderReceb)){
            $texto = $this->enderReceb->getElementsByTagName("xLgr")->item(0)->nodeValue . ', '.$this->enderReceb->getElementsByTagName("nro")->item(0)->nodeValue ;
            
            $texto = !empty($this->enderReceb->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $texto . ' - '.$this->enderReceb->getElementsByTagName("xCpl")->item(0)->nodeValue : $texto;
        }
        else
            $texto = '';
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        if(isset($this->enderReceb))
            $texto = !empty($this->enderReceb->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $this->enderReceb->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
        else
            $texto = '';
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 3;
        $texto = 'Município';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->enderReceb))
            $texto = $this->enderReceb->getElementsByTagName("xMun")->item(0)->nodeValue . ' - ' . $this->enderReceb->getElementsByTagName("UF")->item(0)->nodeValue ;
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x = $w - 19 + $oldX;
        $texto = 'CEP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->enderReceb))
            $texto = !empty($this->enderReceb->getElementsByTagName("CEP")->item(0)->nodeValue) ? $this->__format($this->enderReceb->getElementsByTagName("CEP")->item(0)->nodeValue,"#####-###") : '' ;
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+6,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->receb))
            if ( !empty($this->receb->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
                $texto = $this->__format($this->receb->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
            } else {
                $texto = !empty($this->receb->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->receb->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
            }
        else
            $texto = '';
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        
        $x = $w - 47 + $oldX;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->receb))
            $texto = !empty($this->receb->getElementsByTagName("IE")->item(0)->nodeValue) ? $this->receb->getElementsByTagName("IE")->item(0)->nodeValue : '';
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+28,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $oldX;
        $y += 3;
        $texto = 'País';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->receb))
            $texto = !empty($this->receb->getElementsByTagName("xPais")->item(0)->nodeValue) ? $this->receb->getElementsByTagName("xPais")->item(0)->nodeValue : '';
        else
            $texto = '';
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $w - 27 + $oldX;
        $texto = 'FONE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(isset($this->receb))
            $texto = !empty($this->receb->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->receb->getElementsByTagName("fone")->item(0)->nodeValue : '';
        else
            $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+8,$y,$w,$h,$texto,$aFont,'T','L',0,'');
    } //fim da função __recebedorDACFE
    
    /**
     * __tomadorDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __tomadorDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __tomadorDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
                $maxW = $this->wPrint;
        }else{
                $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 10;
        $texto = 'Tomador do Serviço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        $texto = $this->toma->getElementsByTagName("xNome")->item(0)->nodeValue;
        $this->__textBox($x+23,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $maxW*0.60;
        $texto = 'Município';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->enderToma->getElementsByTagName("xMun")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+11,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $maxW*0.85;
        $texto = 'UF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->enderToma->getElementsByTagName("UF")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+4,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w - 18;
        $texto = 'CEP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->__format($this->enderToma->getElementsByTagName("CEP")->item(0)->nodeValue,"#####-###");
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+6,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        
        
        $y += 3;
        $x = $oldX;
        $texto = 'Endereço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        
        $texto = $this->enderReme->getElementsByTagName("xLgr")->item(0)->nodeValue . ',' . $this->enderReme->getElementsByTagName("nro")->item(0)->nodeValue;
        $texto = !empty($this->enderReme->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $texto . ' - '.$this->enderReme->getElementsByTagName("xCpl")->item(0)->nodeValue : $texto;
        
        $texto .= ' - ' . $this->enderReme->getElementsByTagName("xBairro")->item(0)->nodeValue;
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');

        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        if ( !empty($this->rem->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
            $texto = $this->__format($this->rem->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
        } else {
            $texto = !empty($this->rem->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->rem->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
        }
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+13,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        
        $x = $x + 65;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->rem->getElementsByTagName("IE")->item(0)->nodeValue;;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+28,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $w * 0.75;
        $texto = 'País';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->rem->getElementsByTagName("xPais")->item(0)->nodeValue;
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+6,$y,$w,$h,$texto,$aFont,'T','L',0,'');    
        
        $x = $w - 27;
        $texto = 'FONE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = !empty($this->rem->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->rem->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x+8,$y,$w,$h,$texto,$aFont,'T','L',0,'');
    } //fim da função __tomadorDACFE
    
    
    /**
     * __descricaoCargaDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __tomadorDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __descricaoCargaDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
                $maxW = $this->wPrint;
        }else{
                $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 17;
        $texto = 'Produto Predominante';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        
        $texto = $this->infCarga->getElementsByTagName("proPred")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+2.8,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.60;
        $this->pdf->Line($x, $y, $x, $y+9);
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $texto = 'Outras Características da Carga';
        $this->__textBox($x+1,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->infCarga->getElementsByTagName("xOutCat")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x+1,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.80;
        $this->pdf->Line($x, $y, $x, $y+8);
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $texto = 'Valot Total da Mercadoria';
        $this->__textBox($x+1,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        
        $texto = $this->infCarga->getElementsByTagName("vMerc")->item(0)->nodeValue;
        $texto = number_format($texto, 2, ",", ".");
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x+1,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y += 8;
        $x = $oldX;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        
        $texto = 'QT./UN. Medida';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto  = number_format($this->infCarga->getElementsByTagName("qCarga")->item(0)->nodeValue, 3, ",", ".");
        $texto .= ' '.$this->infCarga->getElementsByTagName("tpMed")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.16;
        
        $this->pdf->Line($x, $y, $x, $y+9);
        
        $texto = 'QT./UN. Medida';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto  = number_format($this->infCarga->getElementsByTagName("qCarga")->item(1)->nodeValue, 3, ",", ".");
        $texto .= ' '.$this->infCarga->getElementsByTagName("tpMed")->item(1)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.32;
        
        $this->pdf->Line($x, $y, $x, $y+9);
        
        
        $texto = 'QT./UN. Medida';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        if(!empty($this->infCarga->getElementsByTagName("qCarga")->item(2)->nodeValue)){
            $texto  = number_format($this->infCarga->getElementsByTagName("qCarga")->item(2)->nodeValue, 3, ",", ".");
            $texto .= ' '.$this->infCarga->getElementsByTagName("tpMed")->item(2)->nodeValue;
        } else
            $texto = '';
            
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.48;
        
        $this->pdf->Line($x, $y, $x, $y+9);
        
        $texto = 'Nome da Seguradora';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $y +=3;
        
        $this->pdf->Line($x, $y, $w+1, $y);
        
        $texto = 'Responsável';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = '??????';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.64;
        
        $this->pdf->Line($x, $y, $x, $y+6);
        
        $texto = 'Número da Apólice';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = '??????';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.80;
        
        $this->pdf->Line($x, $y, $x, $y+6);
        
        $texto = 'Número da Averbação';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = '??????';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w,$h,$texto,$aFont,'T','L',0,'');
        

    } //fim da função __descricaoCargaDACFE
    
    
    
    /**
     * __componentesValorDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __componentesValorDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __componentesValorDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 25;
        $texto = 'Componentes do Valor da Prestação do Serviço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',1,'');
        
        $y += 3.4;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        
        $texto = 'Nome';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $yIniDados = $y;
        
        $x = $w * 0.14;
        
        $texto = 'Valor';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w *0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.28;
        $this->pdf->Line($x, $y, $x, $y + 21.5);
        
        
        $texto = 'Nome';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.42;
        
        $texto = 'Valor';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.56;
        $this->pdf->Line($x, $y, $x, $y + 21.5);
        
        
        
        $texto = 'Nome';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.70;
        
        $texto = 'Valor';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x = $w * 0.86;
        $this->pdf->Line($x, $y, $x, $y + 21.5);
        
        $y += 1;
        
        $texto = 'Valor Total do Serviço';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.14,$h,$texto,$aFont,'T','C',0,'');
        
        $texto = number_format($this->vPrest->getElementsByTagName("vTPrest")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+4,$w * 0.14,$h,$texto,$aFont,'T','C',0,'');
        
        $y += 10;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        $y += 1;
        
        $texto = 'Valor a Receber';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.14,$h,$texto,$aFont,'T','C',0,'');
        
        $texto = number_format($this->vPrest->getElementsByTagName("vRec")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $this->__textBox($x,$y+4,$w * 0.14,$h,$texto,$aFont,'T','C',0,'');
        
        $auxX = $oldX;
        $yIniDados += 4;
        foreach ($this->Comp as $k => $d) {
             $nome = $this->Comp->item($k)->getElementsByTagName('xNome')->item(0)->nodeValue;
             $valor = number_format($this->Comp->item($k)->getElementsByTagName('vComp')->item(0)->nodeValue, 2, ",", ".");
             
             if($auxX>$w*0.60){
                 $yIniDados = $yIniDados + 4;
                 $auxX = $oldX;
             }
             
             $texto = $nome;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.14,$h,$texto,$aFont,'T','L',0,'');
             
             $auxX += $w * 0.14;
             
             $texto = $valor;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.14,$h,$texto,$aFont,'T','L',0,'');
             
             $auxX += $w * 0.14;
        }
    } //fim da função __componentesValorDACFE
    
    
    /**
     * __impostosDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __impostosDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __impostosDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 13;
        $texto = 'Informações Relativas ao Imposto';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',1,'');
        
        $y += 3.4;
        $this->pdf->Line($x, $y, $w+1, $y);

                
        $texto = 'Situação Tributária';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.26,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.26;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        
        $texto = 'Base De Calculo';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        
        $texto = 'Alíq ICMS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x += $w*0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        
        $texto = 'Valor ICMS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x += $w*0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        
        $texto = '% Red. BC ICMS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        
        $texto = 'ICMS ST';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x = $oldX;
        $y = $y + 4;
        
        
        $texto =  $this->ICMS->getElementsByTagName("CST")->item(0)->nodeValue;
        switch ($texto){
            case '00':
                $texto = '00 - Tributação normal do ICMS';
                break;
            default:
                $texto = $texto . ' ? ';
        }
        
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w*0.26,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.26;
        
        $texto = number_format($this->ICMS->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.14;
        
        $texto = $texto = number_format($this->ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue, 2, ",", ".");;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x += $w*0.14;
        
        $texto = $texto = number_format($this->ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", ".");;;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x += $w*0.14;
        
        $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.14;
        
        $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w*0.14,$h,$texto,$aFont,'T','L',0,'');
        
        
    } //fim da função __componentesValorDACFE
    
    
    /**
     * __documentosOriginariosDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __documentosOriginariosDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __documentosOriginariosDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 40;
        $texto = 'Documentos Originários';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',1,'');
        
        $y += 3.4;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        $texto = 'Tipo DOC';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.23,$h,$texto,$aFont,'T','L',0,'');
        
        $yIniDados = $y;
        
        $x += $w*0.23;
        
        $texto = 'CNPJ/CPF Emitente';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.13,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x += $w*0.13;
        
        $texto = 'Série/Nro. Documento';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.13,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.13;
        $this->pdf->Line($x, $y, $x, $y+36.5);
        
        
        $texto = 'Tipo DOC';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.23,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.23;
        
        $texto = 'CNPJ/CPF Emitente';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.13,$h,$texto,$aFont,'T','L',0,'');
        
        
        $x += $w*0.13;
        
        $texto = 'Série/Nro. Documento';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.13,$h,$texto,$aFont,'T','L',0,'');
        
        $auxX = $oldX;
        $yIniDados += 4;
        if(count($this->infNFe) <= 0){
           foreach ($this->infNF as $k => $d) {
             $tp = 'NOTA FISCAL'; // ????????????
             if ( !empty($this->rem->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
                $cnpj = $this->__format($this->rem->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
            } else {
                $cnpj = !empty($this->rem->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->rem->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
            }
             $doc = $this->infNF->item($k)->getElementsByTagName('serie')->item(0)->nodeValue;
             $doc.= '/'.$this->infNF->item($k)->getElementsByTagName('nDoc')->item(0)->nodeValue;

             if($auxX>$w*0.90){
                 $yIniDados = $yIniDados + 4;
                 $auxX = $oldX;
             }

             $texto = $tp;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.23,$h,$texto,$aFont,'T','L',0,'');

             $auxX += $w * 0.23;

             $texto = $cnpj;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.13,$h,$texto,$aFont,'T','L',0,'');

             $auxX += $w * 0.13;

             $texto = $doc;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.13,$h,$texto,$aFont,'T','L',0,'');

             $auxX += $w * 0.13;
           }
        }else {
           foreach ($this->infNFe as $k => $d) {
             $tp = 'NF-E';

             $chaveNFe = $this->infNFe->item($k)->getElementsByTagName('chave')->item(0)->nodeValue;
             $numNFe = substr($chaveNFe, 25, 9);
             $serieNFe = substr($chaveNFe, 24, 3);
             $doc = $serieNFe.'/'.$numNFe;

             if($auxX>$w*0.90){
                 $yIniDados = $yIniDados + 4;
                 $auxX = $oldX;
             }

             $texto = $tp;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.23,$h,$texto,$aFont,'T','L',0,'');

             $auxX += $w * 0.23;

             $texto = $chaveNFe;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.13,$h,$texto,$aFont,'T','L',0,'');

             $auxX += $w * 0.13;

             $texto = $doc;
             $aFont = array('font'=>$this->fontePadrao,'size'=>8,'');
             $this->__textBox($auxX,$yIniDados,$w * 0.13,$h,$texto,$aFont,'T','L',0,'');

             $auxX += $w * 0.13;
           }
        }





    } //fim da função __componentesValorDACFE

    /**
     * __documentosOriginariosDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __documentosOriginariosDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __observacoesDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 20;
        $texto = 'Observações';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',1,'');
        
        $y += 3.4;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        $auxX = $oldX;
        $yIniDados += $y;
        $texto = '';
        foreach ($this->compl as $k => $d) {
             $xObs = $this->compl->item($k)->getElementsByTagName('xObs')->item(0)->nodeValue;
             $texto .=  "\r\n" . $xObs;
        }

        $texto .= "\r\n".$this->imp->getElementsByTagName('infAdFisco')->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');


        
    } //fim da função __componentesValorDACFE
    
    
    /**
     * __modalRodoviarioDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __modalRodoviarioDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __modalRodoviarioDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 12;
        $texto = 'DADOS ESPECÍFICOS DO MODAL RODOVIÁRIO - CARGA FRACIONADA';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',1,'');
        
        $y += 3.4;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        
        $texto = 'RNTRC Da Empresa';
         $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.23,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->rodo->getElementsByTagName("RNTRC")->item(0)->nodeValue;
         $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w * 0.23,$h,$texto,$aFont,'T','L',0,'');
             
        $x += $w * 0.23;
        $this->pdf->Line($x, $y, $x, $y+8.5);
        
        $texto = 'Lotação';
         $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.13,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->rodo->getElementsByTagName("lota")->item(0)->nodeValue;
        switch ($texto){
            case '0':
                $texto = 'Não';
                break;
            case '1':
                $texto = 'Sim';
                break;
            default:
                $texto = $texto . ' ? ';
        }
         $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w * 0.13,$h,$texto,$aFont,'T','L',0,'');
             
        $x += $w * 0.13;
        $this->pdf->Line($x, $y, $x, $y+8.5);
        
        
        
        $texto = 'Data Prevista de Entrega';
         $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w * 0.15,$h,$texto,$aFont,'T','L',0,'');
        
        $texto = $this->__ymd2dmy($this->rodo->getElementsByTagName("dPrev")->item(0)->nodeValue);
         $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y+3,$w * 0.15,$h,$texto,$aFont,'T','L',0,'');
             
        $x += $w * 0.15;
        $this->pdf->Line($x, $y, $x, $y+8.5);
        
        $texto = 'ESTE CONHECIMENTO DE TRANSPORTE ATENDE '."\r\n".' À LEGISLAÇÃO DE TRANSPORTE RODOVIÁRIO EM VIGOR';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'');
        $this->__textBox($x,$y+1,$w * 0.50,$h,$texto,$aFont,'T','C',0,'');
        
    } //fim da função __modalRodoviarioDACFE
    
    
    /**
     * __canhotoDACFE
     * Monta o campo com os dados do remetente na DACFE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __canhotoDACFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    private function __canhotoDACFE($x=0,$y=0){
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 22;
        $this->__hDashedLine($x,$y,$this->wPrint,0.1,80);
        
        $y = $y + 1;
        $texto = 'DECLARO QUE RECEBI OS VOLUMES DESTE CONHECIMENTO EM PERFEITO ESTADO PELO QUE DOU POR CUMPRIDO O PRESENTE CONTRATO DE TRANSPORTE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','C',1,'');
        
        $y += 3.4;
        $this->pdf->Line($x, $y, $w+1, $y);
        
        $texto = 'Nome';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.33,$h,$texto,$aFont,'T','L',0,'');
        
        $x += $w*0.33;
        $this->pdf->Line($x, $y, $x, $y+18.5);

        $texto = 'ASSINATURA / CARIMBO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.33,$h-3.4,$texto,$aFont,'B','C',0,'');
        

        $x += $w*0.33;
        $this->pdf->Line($x, $y, $x, $y+18.5);
        
        $texto = 'CHEGADA DATA/HORA'."\r\n" ."\r\n"."\r\n"."\r\n" .'SAÍDA DATA/HORA';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.33,$h-3.4,$texto,$aFont,'T','C',0,'');
        
        $x = $oldX;
        $y = $y+9;
        $this->pdf->Line($x, $y, $w*0.334, $y);
        
        $texto = 'RG';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w*0.33,$h,$texto,$aFont,'T','L',0,'');
        
        
    } //fim da função __canhotoDACFE
    /**
     * __format
     * Função de formatação de strings.
     * @package NFePHP
     * @name __format
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $campo String a ser formatada
     * @param string $mascara Regra de formatção da string (ex. ##.###.###/####-##)
     * @return string Retorna o campo formatado
     */
    private function __format($campo='',$mascara=''){
        //remove qualquer formatação que ainda exista
    $sLimpo = preg_replace("(/[' '-./ t]/)",'',$campo);
        // pega o tamanho da string e da mascara
        $tCampo = strlen($sLimpo);
        $tMask = strlen($mascara);
        if ( $tCampo > $tMask ) {
            $tMaior = $tCampo;
        } else {
            $tMaior = $tMask;
        }
    //contar o numero de cerquilhas da mascara
    $aMask = str_split($mascara);
    $z=0;
    $flag=FALSE;
    foreach ( $aMask as $letra ){
            if ($letra == '#'){
                $z++;
            }
    }
    if ( $z > $tCampo ) {
            //o campo é menor que esperado
            $flag=TRUE;
    }
        //cria uma variável grande o suficiente para conter os dados
        $sRetorno = '';
        $sRetorno = str_pad($sRetorno, $tCampo+$tMask, " ",STR_PAD_LEFT);
        //pega o tamanho da string de retorno
        $tRetorno = strlen($sRetorno);
        //se houve entrada de dados
        if( $sLimpo != '' && $mascara !='' ) {
            //inicia com a posição do ultimo digito da mascara
            $x = $tMask;
            $y = $tCampo;
            $cI = 0;
            for ( $i = $tMaior-1; $i >= 0; $i-- ) {
                if ($cI < $z){
                    // e o digito da mascara é # trocar pelo digito do campo
                    // se o inicio da string da mascara for atingido antes de terminar
                    // o campo considerar #
                    if ( $x > 0 ) {
                        $digMask = $mascara[--$x];
                    } else {
                        $digMask = '#';
                    }
                    //se o fim do campo for atingido antes do fim da mascara
                    //verificar se é ( se não for não use
                    if ( $digMask=='#' ) {
                        $cI++;
                        if ( $y > 0 ) {
                            $sRetorno[--$tRetorno] = $sLimpo[--$y];
                        } else {
                            //$sRetorno[--$tRetorno] = '';
                        }
                    } else {
                        if ( $y > 0 ) {
                            $sRetorno[--$tRetorno] = $mascara[$x];
                        } else {
                            if ($mascara[$x] =='('){
                                $sRetorno[--$tRetorno] = $mascara[$x];
                            }
                        }
                        $i++;
                    }
                }
            }
            if (!$flag){
                if ($mascara[0]!='#'){
                    $sRetorno = '(' . trim($sRetorno);
                }
            }
            return trim($sRetorno);
        } else {
            return '';
        }
    } //fim __format

    /**
     * __getNumLines
     * Obtem o numero de linhas usadas pelo texto usando a fonte especifidada
     * @package NFePHP
     * @name __getNumLines
     * @version 1.3
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $text
     * @param number $width
     * @param array $aFont
     * @return number numero de linhas
     */
    private function __getNumLines( $text , $width , $aFont=array('font'=>'Times','size'=>8,'style'=>'' ) ){
      $text=trim($text);
      $this->pdf->SetFont($aFont['font'],$aFont['style'],$aFont['size']);
      $n = $this->pdf->WordWrap($text,$width-0.2);
      return $n;
    } // fim __getNumLines


    /**
     *__textBox
     * Cria uma caixa de texto com ou sem bordas. Esta função perimite o alinhamento horizontal
     * ou vertical do texto dentro da caixa.
     * Atenção : Esta função é dependente de outras classes de FPDF
     * Ex. $this->__textBox(2,20,34,8,'Texto',array('fonte'=>$this->fontePadrao,'size'=>10,'style='B'),'C','L',FALSE,'http://www.nfephp.org')
     *
     * @package NFePHP
     * @name __textBox
     * @version 1.1
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param number $x Posição horizontal da caixa, canto esquerdo superior
     * @param number $y Posição vertical da caixa, canto esquerdo superior
     * @param number $w Largura da caixa
     * @param number $h Altura da caixa
     * @param string $text Conteúdo da caixa
     * @param array $aFont Matriz com as informações para formatação do texto com fonte, tamanho e estilo
     * @param string $vAlign Alinhamento vertical do texto, T-topo C-centro B-base
     * @param string $hAlign Alinhamento horizontal do texto, L-esquerda, C-centro, R-direita
     * @param boolean $border TRUE ou 1 desenha a borda, FALSE ou 0 Sem borda
     * @param string $link Insere um hiperlink
     * @param boolean $force Se for true força a caixa com uma unica linha e para isso atera o tamanho do fonte até caber no espaço, se falso mantem o tamanho do fonte e usa quantas linhas forem necessárias
     * @param number $hmax
     * @param number $vOffSet incremento forçado na na posição Y
     * @return number $height Qual a altura necessária para desenhar esta textBox
     */
    private function __textBox($x,$y,$w,$h,$text='',$aFont=array('font'=>'Times','size'=>8,'style'=>''),$vAlign='T',$hAlign='L',$border=1,$link='',$force=TRUE,$hmax=0,$vOffSet=0){
        $oldY = $y;
         $temObs = FALSE;
         $resetou = FALSE;
        if ($w < 0 ) {
            return $y;
        }
        //remover espaços desnecessários
        $text = trim($text);
        //converter o charset para o fpdf
        $text = utf8_decode($text);
        //desenhar a borda da caixa
        if ( $border ) {
            $this->pdf->RoundedRect($x,$y,$w,$h,2,'D');
        }
        //estabelecer o fonte
        $this->pdf->SetFont($aFont['font'],$aFont['style'],$aFont['size']);
        //calcular o incremento
        $incY = $this->pdf->FontSize; //tamanho da fonte na unidade definida
        if ( !$force ) {
            //verificar se o texto cabe no espaço
            $n = $this->pdf->WordWrap($text,$w);
        } else {
            $n = 1;
        }
        //calcular a altura do conjunto de texto
        $altText = $incY * $n;
        //separar o texto em linhas
        $lines = explode("\n", $text);
        //verificar o alinhamento vertical
        If ( $vAlign == 'T' ) {
            //alinhado ao topo
            $y1 = $y+$incY;
        }
        If ( $vAlign == 'C' ) {
            //alinhado ao centro
            $y1 = $y + $incY + (($h-$altText)/2);
        }
        If ( $vAlign == 'B' ) {
            //alinhado a base
            $y1 = ($y + $h)-0.5;
        }
        //para cada linha
        foreach( $lines as $line ) {
            //verificar o comprimento da frase
            $texto = trim($line);
            $comp = $this->pdf->GetStringWidth($texto);
            if ( $force ) {
                $newSize = $aFont['size'];
                while ( $comp > $w ) {
                    //estabelecer novo fonte
                    $this->pdf->SetFont($aFont['font'],$aFont['style'],--$newSize);
                    $comp = $this->pdf->GetStringWidth($texto);
                }
            }
            //ajustar ao alinhamento horizontal
            if ( $hAlign == 'L' ) {
                $x1 = $x+0.5;
            }
            if ( $hAlign == 'C' ) {
                $x1 = $x + (($w - $comp)/2);
            }
            if ( $hAlign == 'R' ) {
                $x1 = $x + $w - ($comp+0.5);
            }

            //escrever o texto
            if ($vOffSet >0){
               if ($y1 > ($oldY+$vOffSet)){
                    if (!$resetou){
                        $y1 = oldY;
                        $resetou = TRUE;
                    }
                    $this->pdf->Text($x1, $y1, $texto);
        }
            } else {
                $this->pdf->Text($x1, $y1, $texto);
            }
            //incrementar para escrever o proximo
            $y1 += $incY;
            if (($hmax > 0) && ($y1 > ($y+($hmax-1)))){
                $temObs = TRUE;
        break;
            }
        }
        return ($y1-$y)-$incY;
    } // fim função __textBox

    /**
     *__textBox90
     * Cria uma caixa de texto com ou sem bordas. Esta função perimite o alinhamento horizontal
     * ou vertical do texto dentro da caixa, rotacionando-o em 90 graus, essa função precisa que
     * a classe PDF contenha a função Rotate($angle,$x,$y);
     * Atenção : Esta função é dependente de outras classes de FPDF
     * Ex. $this->__textBox90(2,20,34,8,'Texto',array('fonte'=>$this->fontePadrao,'size'=>10,'style='B'),'C','L',FALSE,'http://www.nfephp.org')
     *
     * @package NFePHP
     * @name __textBox90
     * @version 1.1
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @author Guilherme Calabria Filho <guiga86 at gmail dot com>
     * @param number $x Posição horizontal da caixa, canto esquerdo superior
     * @param number $y Posição vertical da caixa, canto esquerdo superior
     * @param number $w Largura da caixa
     * @param number $h Altura da caixa
     * @param string $text Conteúdo da caixa
     * @param array $aFont Matriz com as informações para formatação do texto com fonte, tamanho e estilo
     * @param string $vAlign Alinhamento vertical do texto, T-topo C-centro B-base
     * @param string $hAlign Alinhamento horizontal do texto, L-esquerda, C-centro, R-direita
     * @param boolean $border TRUE ou 1 desenha a borda, FALSE ou 0 Sem borda
     * @param string $link Insere um hiperlink
     * @param boolean $force Se for true força a caixa com uma unica linha e para isso atera o tamanho do fonte até caber no espaço, se falso mantem o tamanho do fonte e usa quantas linhas forem necessárias
     * @param number $hmax
     * @param number $vOffSet incremento forçado na na posição Y
     * @return number $height Qual a altura necessária para desenhar esta textBox
     */
    private function __textBox90($x,$y,$w,$h,$text='',$aFont=array('font'=>'Times','size'=>8,'style'=>''),$vAlign='T',$hAlign='L',$border=1,$link='',$force=TRUE,$hmax=0,$vOffSet=0){
    /*Rotacionado*/
    $this->pdf->Rotate(90,$x,$y);
        $oldY = $y;
     $temObs = FALSE;
     $resetou = FALSE;
        if ($w < 0 ) {
            return $y;
        }
        //remover espaços desnecessários
        $text = trim($text);
        //converter o charset para o fpdf
        $text = utf8_decode($text);
        //desenhar a borda da caixa
        if ( $border ) {
            $this->pdf->RoundedRect($x,$y,$w,$h,0.8,'D');
        }
        //estabelecer o fonte
        $this->pdf->SetFont($aFont['font'],$aFont['style'],$aFont['size']);
        //calcular o incremento
        $incY = $this->pdf->FontSize; //tamanho da fonte na unidade definida
        if ( !$force ) {
            //verificar se o texto cabe no espaço
            $n = $this->pdf->WordWrap($text,$w);
        } else {
            $n = 1;
        }
        //calcular a altura do conjunto de texto
        $altText = $incY * $n;
        //separar o texto em linhas
        $lines = explode("\n", $text);
        //verificar o alinhamento vertical
        If ( $vAlign == 'T' ) {
            //alinhado ao topo
            $y1 = $y+$incY;
        }
        If ( $vAlign == 'C' ) {
            //alinhado ao centro
            $y1 = $y + $incY + (($h-$altText)/2);
        }
        If ( $vAlign == 'B' ) {
            //alinhado a base
            $y1 = ($y + $h)-0.5;
        }
        //para cada linha
        foreach( $lines as $line ) {
            //verificar o comprimento da frase
            $texto = trim($line);
            $comp = $this->pdf->GetStringWidth($texto);
            if ( $force ) {
                $newSize = $aFont['size'];
                while ( $comp > $w ) {
                    //estabelecer novo fonte
                    $this->pdf->SetFont($aFont['font'],$aFont['style'],--$newSize);
                    $comp = $this->pdf->GetStringWidth($texto);
                }
            }
            //ajustar ao alinhamento horizontal
            if ( $hAlign == 'L' ) {
                $x1 = $x+0.5;
            }
            if ( $hAlign == 'C' ) {
                $x1 = $x + (($w - $comp)/2);
            }
            if ( $hAlign == 'R' ) {
                $x1 = $x + $w - ($comp+0.5);
            }

            //escrever o texto
            if ($vOffSet >0){
               if ($y1 > ($oldY+$vOffSet)){
                    if (!$resetou){
                        $y1 = oldY;
                        $resetou = TRUE;
                    }
                    $this->pdf->Text($x1, $y1, $texto);
        }
            } else {
                $this->pdf->Text($x1, $y1, $texto);
            }
            //incrementar para escrever o proximo
            $y1 += $incY;
            if (($hmax > 0) && ($y1 > ($y+($hmax-1)))){
                $temObs = TRUE;
        break;
            }
        }
    /*Zerando rotação*/
    $this->pdf->Rotate(0,$x,$y);
        return ($y1-$y)-$incY;
    } // fim função __textBox90

    /**
     *__hDashedLine
     * Desenha uma linha horizontal tracejada com o FPDF
     * @package NFePHP
     * @name __hDashedLine
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param number $x Posição horizontal inicial, em mm
     * @param number $y Posição vertical inicial, em mm
     * @param number $w Comprimento da linha, em mm
     * @param number $h Espessura da linha, em mm
     * @param number $n Numero de traços na seção da linha com o comprimento $w
     * @return none
     */
    private function __hDashedLine($x,$y,$w,$h,$n) {
        $this->pdf->SetLineWidth($h);
        $wDash=($w/$n)/2; // comprimento dos traços
        for( $i=$x; $i<=$x+$w; $i += $wDash+$wDash ) {
            for( $j=$i; $j<= ($i+$wDash); $j++ ) {
                if( $j <= ($x+$w-1) ) {
                    $this->pdf->Line($j,$y,$j+1,$y);
                }
            }
        }
    } //fim função __hDashedLine
   /**
     *__hDashedVerticalLine
     * Desenha uma linha vertical tracejada com o FPDF
     * @package NFePHP
     * @name __hDashedVerticalLine
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @author Guilherme Calabria Filho <guiga86 at gmail dot com>
     * @param number $x Posição horizontal inicial, em mm
     * @param number $y Posição vertical inicial, em mm
     * @param number $w Comprimento da linha, em mm
     * @param number $yfinal Espessura da linha, em mm
     * @param number $n Numero de traços na seção da linha com o comprimento $w
     * @return none
     */
    private function __hDashedVerticalLine($x,$y,$w,$yfinal,$n) {
       $this->pdf->SetLineWidth($w);
    /*Organizando valores*/
    if($y>$yfinal)
    {
        $aux = $yfinal;
        $yfinal = $y;
        $y = $aux;
    }
    while($y<$yfinal&&$n>0)
    {
           $this->pdf->Line($x,$y,$x,$y+1);
           $y += 3;
           $n--;
    }

    } //fim função __hDashedVerticalLine

    /**
     * __simpleGetValue
     * Extrai o valor do node DOM
     * @package NFePHP
     * @version 1.0
     * @author Marcos Diez
     * @param DOM $theObj
     * @param string $keyName identificador da TAG do xml
     * @param string $extraText prefixo do retorno
     * @return string
     */
    private function __simpleGetValue( $theObj , $keyName , $extraText ){
        $vct = $theObj->getElementsByTagName( $keyName )->item(0);
        if( isset( $vct ) ){
            return $extraText . trim($vct->nodeValue);
        }
        return "";
    } //fim __simpleGetValue

    /**
     * __simpleGetDate
     * Recupera e reformata a data do padrão da NFe para dd/mm/aaaa
     * @package NFePHP
     * @version 1.0
     * @author Marcos Diez
     * @param DOM $theObj
     * @param string $keyName identificador da TAG do xml
     * @param string $extraText prefixo do retorno
     * @return string
     */
    private function __simpleGetDate( $theObj , $keyName , $extraText ){
        $vct = $theObj->getElementsByTagName( $keyName )->item(0);
        if( isset( $vct ) ){
            $theDate = explode( "-" , $vct->nodeValue );
            return $extraText . $theDate[2] . "/" . $theDate[1] . "/" . $theDate[0];
        }
        return "";
    } //fim __simpleGetDate

    /**
     * __modulo11
     *
     * @package NFePHP
     * @name __modulo11
     * @version 1.0
     * @author Marcos Diez
     * @param string $numero
     * @return integer modulo11 do numero passado
     */
    private function __modulo11($numero) {
        $numero = (string)$numero;
        $tamanho = strlen($numero);
        $soma = 0;
        $mult = 2;
        for ($i = $tamanho-1; $i >= 0; $i--):
            $digito = (int)$numero[$i];
            $r = $digito * $mult;
            $soma += $r;
            $mult++;
            if ($mult == 10) $mult = 2;
                endfor;
            $resto = ($soma * 10) % 11;
            return ($resto == 10 || $resto == 0) ? 1 : $resto;
    } //fim __modulo11

    /**
     *__ymd2dmy
     * Converte datas no formato YMD (ex. 2009-11-02) para o formato brasileiro 02/11/2009)
     * @package NFePHP
     * @name __ymd2dmy
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $data Parâmetro extraido da NFe
     * @return string Formatada para apresentação da data no padrão brasileiro
     */
    private function __ymd2dmy($data) {
        if (!empty($data)) {
            $needle = "/";
            if (strstr($data, "-")) {
                $needle = "-";
            }
            $dt = explode($needle, $data);
            return "$dt[2]/$dt[1]/$dt[0]";
        }
    } // fim da função __ymd2dmy

    /**
     * __convertTime
     * Converte a imformação de data e tempo contida na NFe
     * @package NFePHP
     * @name __convertTime
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $DH Informação de data e tempo extraida da NFe
     * @return timestamp UNIX Para uso com a funçao date do php
     */
    private function __convertTime($DH){
        if ($DH){
            $aDH = explode('T',$DH);
            $adDH = explode('-',$aDH[0]);
            $atDH = explode(':',$aDH[1]);
            $timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);
            return $timestampDH;
        }
    } //fim da função __convertTime
    
    

} //fim da classe DacteNFePHP


?>