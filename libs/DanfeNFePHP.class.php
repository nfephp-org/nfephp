<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; sem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 * @package   NFePHP
 * @name      DanfeNFePHP.class.php
 * @version   1.3
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
 * @todo Formatação Paisagem
 * @todo Inclusão de campos para NF de serviços
 * @todo Adaptação para a nova versão 2.0 do manual SEFAZ
 */

//comente a linha abaixo para nao permitir qualquer aviso no codigo pdf, a linha abaixo é utilizada para debug
error_reporting(E_ALL);
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
define('FPDF_FONTPATH','font/');
//classe extendida da classe FPDF para montagem do arquivo pfd
require_once('FPDF/code128.php');
//require_once('LoadClasses.class.php');

class DanfeNFePHP {

    private $pdf; // objeto fpdf()
    private $xml; // string XML NFe
    private $logomarca=''; // path para logomarca em jpg
    private $errMsg=''; // mesagens de erro
    private $errStatus=FALSE;// status de erro TRUE um erro ocorreu FALSE sem erros
    private $orientacao='P'; //orientação da DANFE P-Retrato ou L-Paisagem
    private $papel='A4'; //formato do papel
    private $destino = 'I'; //destivo do arquivo pdf I-borwser, S-retorna o arquivo, D-força download, F-salva em arquivo local
    private $pdfDir=''; //diretorio para salvar o pdf com a opção de destino = F

    //objetos DOM da NFe
    private $dom;
    private $infNFe;
    private $ide;
    private $emit;
    private $dest;
    private $enderEmit;
    private $enderDest;
    private $det;
    private $cobr;
    private $dup;
    private $ICMSTot;
    private $transp;
    private $transporta;
    private $veicTransp;
    private $infAdic;

    /**
     *__construct
     * @package NFePHP
     * @name __construct
     * @version 1.0
     * @param string $docXML Arquivo XML da NFe (com ou sem a tag nfeProc)
     * @param string $sOrientacao Orientação da impressão P-retrato L-Paisagem
     * @param string $sPapel Tamanho do papel (Ex. A4)
     * @param string $sPathLogo Caminho para o arquivo do logo
     * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
     * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
     */
    function __construct($docXML='', $sOrientacao="P",$sPapel='A4',$sPathLogo='', $sDestino='I',$sDirPDF='') {
        $this->orientacao  = $sOrientacao;
        $this->papel    = $sPapel;
        $this->pdf      = '';
        $this->xml      = $docXML;
        $this->logomarca= $sPathLogo;
        $this->destino  = $sDestino;
        $this->pdfDir   = $sDirPDF;


        if ( !empty($this->xml) ) {
            $this->dom = new DomDocument;
            $this->dom->loadXML($this->xml);
            $this->nfeProc    = $this->dom->getElementsByTagName("nfeProc")->item(0);
            $this->infNFe     = $this->dom->getElementsByTagName("infNFe")->item(0);
            $this->ide        = $this->dom->getElementsByTagName("ide")->item(0);
            $this->emit       = $this->dom->getElementsByTagName("emit")->item(0);
            $this->dest       = $this->dom->getElementsByTagName("dest")->item(0);
            $this->enderEmit  = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->enderDest  = $this->dom->getElementsByTagName("enderDest")->item(0);
            $this->det        = $this->dom->getElementsByTagName("det");
            $this->cobr       = $this->dom->getElementsByTagName("cobr")->item(0);
            $this->dup        = $this->dom->getElementsByTagName('dup');
            $this->ICMSTot    = $this->dom->getElementsByTagName("ICMSTot")->item(0);
            $this->transp     = $this->dom->getElementsByTagName("transp")->item(0);
            $this->transporta = $this->dom->getElementsByTagName("transporta")->item(0);
            $this->veicTransp = $this->dom->getElementsByTagName("veicTransp")->item(0);
            $this->infAdic    = $this->dom->getElementsByTagName("infAdic")->item(0);
        }
    } //fim construct

    /**
     * montaDANFE
     * Esta função monta a DANFE conforme as informações fornecidas para a classe
     * durante sua construção.
     * Esta função constroi DANFE's com até 3 páginas podendo conter até 56 itens.
     * A definição de margens e posições iniciais para a impressão são estabelecidas no
     * pelo conteúdo da funçao e podem ser modificados.
     * @package NFePHP
     * @name montaDANFE
     * @version 1.1
     * @param string $orientacao (Opcional) Estabelece a orientação da impressão (ex. P-retrato)
     * @param string $papel (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID da NFe numero de 44 digitos extraido do arquivo XML
     * @todo Impressão paisagem
     * @todo Inclusão de campos de NFe de serviços
     */
    public function montaDANFE($orientacao='P',$papel='A4'){
        $this->orientacao = $orientacao;
        $this->papel = $papel;
        //instancia a classe pdf
        $this->pdf = new PDF_Code128($this->orientacao, 'mm', $this->papel);
        // margens do PDF
        $margSup = 2;
        $margEsq = 2;
        $margDir = 2;
        // posição inicial do relatorio
        $y = 5;
        $x = 5;
        // estabelece contagem de paginas
        $this->pdf->AliasNbPages();
        // fixa as margens
        $this->pdf->SetMargins($margEsq,$margSup,$margDir);
        $this->pdf->SetDrawColor(100,100,100);
        $this->pdf->SetFillColor(255,255,255);
        // inicia o documento
        $this->pdf->Open();
        // adiciona a primeira página
        $this->pdf->AddPage($this->orientacao, $this->papel);
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->SetTextColor(0,0,0);
        //verifica o numero e itens de produto na NFe
        //na primeira página são impressos até 10 itens e
        //até 23 em cada página adicional
        //e considera um máximo de 3 paginas com um total possivel de
        //de 56 itens
        $numItens = $this->det->length;
        if ( $numItens > 10 ){
            if ( ($numItens - 10) <= 23 ) {
                $totPag = 2;
            } else {
                //verifica o numero de páginas totais necessárias para a DANFE
                //10 itens na primeira página e 23 em cada pagina subsequente
                $resto = ($numItens - 10) / 23 - round(($numItens - 10) / 23,0);
                $pAdic = 0;
                if ( $resto > 0 ){
                    $pAdic = 1;
                }
                $totPag = round(($numItens - 10) / 23,0)+1+$pAdic;

            }
        } else {
            $totPag = 1;
        }
        //montagem da primeira página
        $pag = '1';
        //coloca o cabeçalho
        $y = $this->__cabecalhoDANFE($x,$y,$pag,$totPag);
        //coloca os dados do destinatário
        $y = $this->__destinatarioDANFE($x,$y+1);
        //coloca os dados das faturas
        $y = $this->__faturaDANFE($x,$y+1);
        //cploca os daods dos impostos e totais da NFe
        $y = $this->__impostoDANFE($x,$y+1);
        //coloca os dados do trasnporte
        $y = $this->__transporteDANFE($x,$y+1);
        //coloca os 10 primeiros itens da NFe
        $y = $this->__itensDANFE($x,$y+1,0,10,94);
        //coloca os dados adicionais da NFe
        $y = $this->__dadosAdicionaisDANFE($x,$y+4);
        //coloca o canhoto da NFe
        $y = $this->__canhotoDANFE($x,$y);
        // 10 itens na primeira página
        // 23 itens nas páginas subsequentes
        $nInicial = 0;
        for ( $n = 2; $n <= $totPag; $n++ ) {
            if ( $n == 2 ) {
                $nInicial += 10;
            }
            if ( $n > 2 ) {
                $nInicial += 23;
            }
            //adiciona nova página
            $this->pdf->AddPage($this->orientacao, $this->papel);
            //ajusta espessura das linhas
            $this->pdf->SetLineWidth(0.1);
            //seta a cor do texto para petro
            $this->pdf->SetTextColor(0,0,0);
            // posição inicial do relatorio
            $y = 5;
            $x = 5;
            //coloca o cabeçalho na página adicional
            $y = $this->__cabecalhoDANFE($x,$y,$n,$totPag);
            //coloca os itens na página adicional
            $y = $this->__itensDANFE($x,$y+1,$nInicial,23,225);
        }
        //retorna o ID na NFe
        return str_replace('NFe', '', $this->infNFe->getAttribute("Id"));

    }//fim da função montaDANFE

    /**
     * printDANFE
     * Esta função envia a DANFE em PDF criada para o dispositivo informado.
     * O destino da impressão pode ser :
     * I-browser
     * D-browser com download
     * F-salva em um arquivo local com o nome informado
     * S-retorna o documento como uma string e o nome é ignorado.
     * Para enviar o pdf diretamente para uma impressora indique o
     * nome da impressora e o destino deve ser 'S'.
     * @package NFePHP
     * @name printDANFE
     * @version 1.0
     * @param string $nome Path completo com o nome do arquivo pdf
     * @param string $destino Direção do envio do PDF
     * @param string $printer Identificação da impressora no sistema
     * @return string Caso o destino seja S o pdf é retornado como uma string
     * @todo Rotina de impressão direta do arquivo pdf criado
     */
    public function printDANFE($nome='',$destino='I',$printer=''){
        $arq = $this->pdf->Output($nome,$destino);
        if ( $destino == 'S' ){
            //aqui rotina de impressão direta

        }
        return $arq;

        /**
         * Opção 1 - exemplo de script shell usando acroread
             #!/bin/sh
            if ( $# == 2 ) then
                set printer=$2
            else
                set printer=$PRINTER
            fi
            if( $1 != "" ) then
                cat ${1} | acroread -toPostScript | lpr -P $printer
                echo ${1} sent to $printer ... OK!
            else
                echo PDF Print: No filename defined!
            fi

            Opção 2 -
            salvar pdf em arquivo temporario
            converter pdf para ps usando pdf2ps do linux
            imprimir ps para printer usando lp ou lpr
            remover os arquivos temporarios pdf e ps

        **/
    } //fim função printDANFE

    /**
     *__cabecalhoDANFE
     * Monta o cabelhalho da DANFE
     * @package NFePHP
     * @name __cabecalhoDANFE
     * @version 1.1
     * @param number $x Posição horizontal inicial, canto esquerdo
     * @param number $y Posição vertical inicial, canto superior
     * @param number $pag Número da Página
     * @param number$totPag Total de páginas
     * @return number Posição vertical final
     */
    private function __cabecalhoDANFE($x=0,$y=0,$pag='1',$totPag='1'){
        $oldX = $x;
        $oldY = $y;
        //####################################################################################
        //coluna esquerda identificação do emitente
        $w=80;
        $h=42;
        $oldY += $h;
        $this->__textBox($x,$y,$w,$h);
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'I');
        $texto = utf8_decode('IDENTIFICAÇÃO DO EMITENTE');
        $this->__textBox($x,$y,$w,5,$texto,$aFont,'T','C',0,'');
        // coloca o logo
        $logoInfo=getimagesize($this->logomarca);
        $logoW=$logoInfo[0];
        $logoH=$logoInfo[1];
        $logoWmm = ($logoW/72)*25.4;
        $imgW = $logoWmm;
        $logoHmm = ($logoH/72)*25.4;
        $imgH = $logoHmm;
        if ( $logoWmm > $w/2 ){
            $imgW = $w/2;
            $imgH = $logoHmm * ($imgW/$logoWmm);
        }
        $this->pdf->Image($this->logomarca,$x+($w/4),$y+($h/12),$imgW,0,'','jpeg');
        //Nome emitente
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'B');
        $texto = utf8_decode($this->emit->getElementsByTagName("xNome")->item(0)->nodeValue);
        $y1 = 30;//$y+$imgH*1.5;
        $this->__textBox($x,$y1,$w,8,$texto,$aFont,'T','C',0,'');
        //endereço
        $y1 = $y1+5;
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'');
        $fone = !empty($this->enderEmit->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $foneLen = strlen($fone);
        $fone2 = substr($fone,0,$foneLen-4);
        $fone1 = substr($fone,0,$foneLen-8);
        $fone = '(' . $fone1 . ') ' . substr($fone2,-4) . '-' . substr($fone,-4);
        $lgr = !empty($this->enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
	$nro = !empty($this->enderEmit->getElementsByTagName("nro")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("nro")->item(0)->nodeValue : '';
	$cpl = !empty($this->enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
	$bairro = !empty($this->enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
	$CEP = !empty($this->enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue : ' ';
	$CEP = $this->__format($CEP,"#####-###"); 
	$mun = !empty($this->enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue : ''; 
	$UF = !empty($this->enderEmit->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->enderEmit->getElementsByTagName("UF")->item(0)->nodeValue : ''; 
	$texto = $lgr . "," . $nro . "  " . $cpl . "\n" . $bairro . " - " . $CEP . "\n" . $mun . " - " . $UF . "\n" . "Fone/Fax: " . $fone;
        $texto = utf8_decode($texto);
        $this->__textBox($x,$y1,$w,8,$texto,$aFont,'T','C',0,'');

        //####################################################################################
        //coluna central Danfe
        $x += $w;
        $w=35;
        $h = 42;
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'');
        $texto = utf8_decode('DOCUMENTO AUXILIAR DA NOTA FISCAL ELETRÔNICA');
        $this->__textBox($x,$y,$w,$h);
        $h = 20;
        $this->__textBox($x,$y+7,$w,$h,$texto,$aFont,'T','C',0,'',FALSE);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetY($y);
        $this->pdf->SetX($x);
        $this->pdf->CellFitScaleForce($w,10,utf8_decode('DANFE'),0,0,'C',0,'');
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'');
        $texto = '0 - ENTRADA';
        $y1 = $y + 20;
        $h = 8;
        $this->__textBox($x+2,$y1,$w,$h,$texto,$aFont,'T','L',0,'');
        $texto = utf8_decode('1 - SAÍDA');
        $y1 = $y + 24;
        $this->__textBox($x+2,$y1,$w,$h,$texto,$aFont,'T','L',0,'');
        //tipo de nF
        $aFont = array('font'=>'Arial','size'=>12,'style'=>'B');
        $y1 = $y + 20;
        $h = 7;
        $texto = $this->ide->getElementsByTagName('tpNF')->item(0)->nodeValue;
        $this->__textBox($x+27,$y1,5,$h,$texto,$aFont,'C','C',1,'');
        //numero da NF
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $y1 = $y + 28;
        $numNF = str_pad($this->ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
        $numNF = $this->__format($numNF,"###.###.###");
        $texto = utf8_decode("Nº. " . $numNF);
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'C','C',0,'');
        //Série
        $y1 = $y + 32;
        $serie = str_pad($this->ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
        $texto = utf8_decode("Série " . $serie);
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'C','C',0,'');
        //numero paginas FOLHA
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'I');
        $y1 = $y + 36;
        $texto = "Folha " . $pag . "/" . $totPag;
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'C','C',0,'');

        //####################################################################################
        //coluna codigo de barras
        $x += $w;
        $w = 85;
        $h = 42;
        $this->__textBox($x,$y,$w,$h);
        $this->pdf->SetFillColor(0,0,0);
        $chave_acesso = str_replace('NFe', '', $this->infNFe->getAttribute("Id"));
        $bW = 75;
        $bH = 12;
        //codigo de barras
        $this->pdf->Code128($x+(($w-$bW)/2),$y+2,$chave_acesso,$bW,$bH);
        //linhas divisorias
        $this->pdf->Line($x,$y+4+$bH,$x+$w,$y+4+$bH);
        $this->pdf->Line($x,$y+12+$bH,$x+$w,$y+12+$bH);
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $y1 = $y+4+$bH;
        $h = 7;
        $texto = 'CHAVE DE ACESSO';
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'T','L',0,'');
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'B');
        $y1 = $y+8+$bH;
        $this->__textBox($x+2,$y1,$w-2,$h,$chave_acesso,$aFont,'T','C',0,'');
        $texto = 'Consulta de autenticidade no portal nacional da NF-e';
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'');
        $y1 = $y+15+$bH;
        $this->__textBox($x+2,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'');
        $texto = 'www.nfe.fazenda.gov.br/portal';
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'U');
        $y1 = $y+20+$bH;
        $this->__textBox($x+2,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'http://www.nfe.fazenda.gov.br/portal');
        $texto = 'ou no site da Sefaz Autorizadora';
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'');
        $y1 = $y+25+$bH;
        $this->__textBox($x+2,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'');

        //####################################################################################
        //natureza da operação
        $texto = utf8_decode('NATUREZA DA OPERAÇÃO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $w = 80+35;
        $y = $oldY;
        $oldY += $h;
        $x = $oldX;
        $h = 7;
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->ide->getElementsByTagName("natOp")->item(0)->nodeValue);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //PROTOCOLO DE AUTORIZAÇÃO DE USO
        $texto = utf8_decode('PROTOCOLO DE AUTORIZAÇÃO DE USO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $x += $w;
        $w = 85;
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        // algumas NFe podem estar sem o protocolo de uso portanto sua existencia deve ser
        // testada antes de tentar obter a informação
        if( isset($this->nfeProc) ) {
            $texto = utf8_decode($this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue);
            $tsHora = $this->__convertTime($this->nfeProc->getElementsByTagName("dhRecbto")->item(0)->nodeValue);
            $texto .= "  -  " . date('d/m/Y   H:i:s',$tsHora);
            $cStat = $this->nfeProc->getElementsByTagName("cStat")->item(0)->nodeValue;
        } else {
            $texto = '';
            $cStat = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');

        //####################################################################################
        //INSCRIÇÃO ESTADUAL
        $w = 67;
        $y += $h;
        $oldY += $h;
        $x = $oldX;
        $texto = utf8_decode('INSCRIÇÃO ESTADUAL');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->emit->getElementsByTagName("IE")->item(0)->nodeValue);
        $texto = $this->__format($texto,"###.###.###.###");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.
        $x += $w;
        $w = 67;
        $texto = utf8_decode('INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->emit->getElementsByTagName("IEST")->item(0)->nodeValue) ? $this->emit->getElementsByTagName("IEST")->item(0)->nodeValue : '';
        $texto = $this->__format($texto,"###.###.###.###");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //CNPJ
        $x += $w;
        $w = 66;
        $texto = utf8_decode('CNPJ');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue);
        $texto = $this->__format($texto,"##.###.###/####-##");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');

        //####################################################################################
        //Indicação de NF Homologação
        $tpAmb = $this->ide->getElementsByTagName('tpAmb')->item(0)->nodeValue;

        if ( $cStat == '101') {
            //101 Cancelamento
            $x = 5;
            $y = 160;
            $h = 25;
            $w = 200;
            $this->pdf->SetTextColor(70,70,70);
            $texto = "NFe CANCELADA";
            $aFont = array('font'=>'Arial','size'=>42,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
        }
			
        if ( $tpAmb != 1 ) {
            $x = 5;
            $y=120;
            $h = 5;
            $w = 200;
            $this->pdf->SetTextColor(70,70,70);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>'Arial','size'=>42,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
            $aFont = array('font'=>'Arial','size'=>24,'style'=>'B');
            $texto = utf8_decode("AMBIENTE DE HOMOLOGAÇÃO");
            $this->__textBox($x,$y+20,$w,$h,$texto,$aFont,'C','C',0,'');
        }

        return $oldY;
    }

    /**
     * __destinatarioDANFE
     * Monta o cmapo com os dados do destinatário na DANFE.
     * @package NFePHP
     * @name __destinatarioDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    private function __destinatarioDANFE($x=0,$y=0){
        //####################################################################################
        //DESTINATÁRIO / REMETENTE
        $oldX = $x;
        $oldY = $y;
        $w = 67;
        $h = 7;
        $texto = utf8_decode('DESTINATÁRIO / REMETENTE');
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        //NOME / RAZÃO SOCIAL
        $w = 120;
        $y += 3;
        $texto = utf8_decode('NOME / RAZÃO SOCIAL');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->dest->getElementsByTagName("xNome")->item(0)->nodeValue);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','L',0,'');
        //CNPJ / CPF
        $x += $w;
        $w = 46;
        $texto = utf8_decode('CNPJ / CPF');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->__format(utf8_decode($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue),"###.###.###/####-##");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //DATA DA EMISSÃO
        $x += $w;
        $w = 34;
        $texto = utf8_decode('DATA DA EMISSÃO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->__ymd2dmy($this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //ENDEREÇO
        $w = 98;
        $y += $h;
        $x = $oldX;
        $texto = utf8_decode('ENDEREÇO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->dest->getElementsByTagName("xLgr")->item(0)->nodeValue);
        $texto .= ', ' . $this->dest->getElementsByTagName("nro")->item(0)->nodeValue;
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','L',0,'',TRUE);
        //BAIRRO / DISTRITO
        $x += $w;
        $w = 45;
        $texto = utf8_decode('BAIRRO / DISTRITO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->dest->getElementsByTagName("xBairro")->item(0)->nodeValue);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //CEP
        $x += $w;
        $w = 23;
        $texto = utf8_decode('CEP');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
	$texto = !empty($this->dest->getElementsByTagName("CEP")->item(0)->nodeValue) ? $this->dest->getElementsByTagName("CEP")->item(0)->nodeValue : '';
        $texto = $this->__format(utf8_decode($texto),"#####-###");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //DATA DA SAÍDA
        $x += $w;
        $w = 34;
        $texto = utf8_decode('DATA DA SAÍDA');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->ide->getElementsByTagName("dSaiEnt")->item(0)->nodeValue) ? $this->ide->getElementsByTagName("dSaiEnt")->item(0)->nodeValue:"";
        $texto = $this->__ymd2dmy($texto);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //MUNICÍPIO
        $w = 94;
        $y += $h;
        $x = $oldX;
        $texto = utf8_decode('MUNICÍPIO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'T','L',1,'');
        $texto = utf8_decode($this->dest->getElementsByTagName("xMun")->item(0)->nodeValue);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','L',0,'');
        //UF
        $x += $w;
        $w = 8;
        $texto = utf8_decode('UF');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->dest->getElementsByTagName("UF")->item(0)->nodeValue);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //FONE / FAX
        $x += $w;
        $w = 34;
        $texto = utf8_decode('FONE / FAX');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->dest->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->__format($this->dest->getElementsByTagName("fone")->item(0)->nodeValue,'(##) ####-####') : '';
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        //$texto = '(234) 3456-3455';
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //INSCRIÇÃO ESTADUAL
        $x += $w;
        $w = 30;
        $texto = utf8_decode('INSCRIÇÃO ESTADUAL');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = utf8_decode($this->dest->getElementsByTagName("IE")->item(0)->nodeValue);
        $texto = $this->__format($texto,"###.###.###.###");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //HORA DA SAÍDA
        $x += $w;
        $w = 34;
        $texto = utf8_decode('HORA DA SAÍDA');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');

        return ($y + $h);
    } //fim da função __destinatarioDANFE

    /**
     * __faturaDANFE
     * Monta o campo de duplicatas da DANFE
     * @package NFePHP
     * @name __faturaDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    private function __faturaDANFE($x,$y){
        $h = 8+3;
        if ( $this->dup->length > 0 ) {
        
        //#####################################################################
        //FATURA / DUPLICATA
        $texto = "FATURA / DUPLICATA";
        $texto = utf8_decode($texto);
        $w = 80;
        $h = 8;
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        $y += 3;
        $dups = "";
        $nFat = $this->dup->length;
        foreach ($this->dup as $k => $d) {
            $nDup = $this->dup->item($k)->getElementsByTagName('nDup')->item(0)->nodeValue;
            $dDup = $this->__ymd2dmy($this->dup->item($k)->getElementsByTagName('dVenc')->item(0)->nodeValue);
            $vDup = 'R$ ' . number_format($this->dup->item($k)->getElementsByTagName('vDup')->item(0)->nodeValue, 2, ",", ".");
            $h = 8;
            $w = 28;
            $texto = '';
            $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
            $this->__textBox($x,$y,$w,$h,'Num.',$aFont,'T','L',1,'');
            $aFont = array('font'=>'Arial','size'=>7,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$nDup,$aFont,'T','R',0,'');
            $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
            $this->__textBox($x,$y,$w,$h,'Venc.',$aFont,'C','L',0,'');
            $aFont = array('font'=>'Arial','size'=>7,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$dDup,$aFont,'C','R',0,'');
            $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
            $this->__textBox($x,$y,$w,$h,'Valor',$aFont,'B','L',0,'');
            $aFont = array('font'=>'Arial','size'=>7,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$vDup,$aFont,'B','R',0,'');
            $x += $w+0.65;
        }

        if ( $nFat < 6 ){
            for ( $n=$k; $n < 6; $n++){
                $texto = '';
                $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
                $this->__textBox($x,$y,$w,$h,'Num.',$aFont,'T','L',1,'');
                $this->__textBox($x,$y,$w,$h,'Venc.',$aFont,'C','L',0,'');
                $this->__textBox($x,$y,$w,$h,'Valor',$aFont,'B','L',0,'');
                $x += $w+0.65;
            }
        }
       
        }
        return ($y+$h);
    }//fim da função __faturaDANFE

    /**
     * __impostoDANFE
     * Monta o campo de impostos e totais da DANFE
     * @package NFePHP
     * @name __impostoDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    private function __impostoDANFE($x,$y){
        $oldX = $x;
        //#####################################################################
        //CÁLCULO DO IMPOSTO
        $texto = "CÁLCULO DO IMPOSTO";
        $texto = utf8_decode($texto);
        $w = 80;
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','L',0,'');
        //BASE DE CÁLCULO DO ICMS
        $w = 43;
        $y += 3;
        $h = 7;
        $texto = utf8_decode('BASE DE CÁLCULO DO ICMS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','R',0,'');
        //VALOR DO ICMS
        $x += $w;
        $w = 38;
        $texto = utf8_decode('VALOR DO ICMS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //BASE DE CÁLCULO DO ICMS S.T.
        $x += $w;
        $w = 38;
        $texto = utf8_decode('BASE DE CÁLCULO DO ICMS S.T.');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //VALOR DO ICMS SUBSTITUIÇÃO
        $x += $w;
        $w = 38;
        $texto = utf8_decode('VALOR DO ICMS SUBSTITUIÇÃO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->ICMSTot->getElementsByTagName("vICMSST")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vICMSST")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //VALOR TOTAL DOS PRODUTOS
        $x += $w;
        $w = 43;
        $texto = utf8_decode('VALOR TOTAL DOS PRODUTOS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //#####################################################################
        //VALOR DO FRETE
        $w = 31;
        $y += $h;
        $x = $oldX;
        $h = 7;
        $texto = utf8_decode('VALOR DO FRETE');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','R',0,'');
        //VALOR DO SEGURO
        $x += $w;
        $w = 31;
        $texto = utf8_decode('VALOR DO SEGURO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //DESCONTO
        $x += $w;
        $w = 32;
        $texto = utf8_decode('DESCONTO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //OUTRAS DESPESAS
        $x += $w;
        $w = 31;
        $texto = utf8_decode('OUTRAS DESPESAS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //VALOR TOTAL DO IPI
        $x += $w;
        $w = 32;
        $texto = utf8_decode('VALOR TOTAL DO IPI');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //VALOR TOTAL DA NOTA
        $x += $w;
        $w = 43;
        $texto = utf8_decode('VALOR TOTAL DA NOTA');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = number_format($this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');

        return ($y+$h);
    } //fim __impostoDANFE

    /**
     * __transporteDANFE
     * Monta o campo de transportes da DANFE
     * @package NFePHP
     * @name __transporteDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    private function __transporteDANFE($x,$y){
        $oldX = $x;
        //#####################################################################
        //TRANSPORTADOR / VOLUMES TRANSPORTADOS
        $texto = "TRANSPORTADOR / VOLUMES TRANSPORTADOS";
        $texto = utf8_decode($texto);
        $w = 80;
        $h = 7;
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');

        //NOME / RAZÃO SOCIAL
        $w = 62;
        $y += 3;
        $texto = utf8_decode('NOME / RAZÃO SOCIAL');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta)) {
            $texto = !empty($this->transporta->getElementsByTagName("xNome")->item(0)->nodeValue) ? utf8_decode($this->transporta->getElementsByTagName("xNome")->item(0)->nodeValue) : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','L',0,'');

        //FRETE POR CONTA
        $x += $w;
        $w = 32;
        $texto = utf8_decode('FRETE POR CONTA');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $this->__textBox($x+1,$y+2,$w,$h-2,'0-EMITENTE',$aFont,'T','L',0,'');
        $this->__textBox($x+1,$y+4,$w,$h-4,'1-DESTINATARIO',$aFont,'T','L',0,'');
        $texto = !empty($this->transp->getElementsByTagName("modFrete")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("modFrete")->item(0)->nodeValue : '';
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x+25,$y+1,5,5,$texto,$aFont,'C','C',1,'');
        //CÓDIGO ANTT
        $x += $w;
        $w = 31;
        $texto = utf8_decode('CÓDIGO ANTT');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("RNTC")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("RNTC")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //PLACA DO VEÍC
        $x += $w;
        $w = 32;
        $texto = utf8_decode('PLACA DO VEÍCULO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("placa")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("placa")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //UF
        $x += $w;
        $w = 8;
        $texto = utf8_decode('UF');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("UF")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //CNPJ / CPF
        $x += $w;
        $w = 35;
        $texto = utf8_decode('CNPJ / CPF');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $this->__format($this->transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue,"##.###.###/####-##") : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //#####################################################################
        //ENDEREÇO
        $w = 94;
        $y += $h;
        $x = $oldX;
        $h = 7;
        $texto = utf8_decode('ENDEREÇO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("xEnder")->item(0)->nodeValue) ? utf8_decode($this->transporta->getElementsByTagName("xEnder")->item(0)->nodeValue) : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','L',0,'');
        //MUNICÍPIO
        $x += $w;
        $w = 63;
        $texto = utf8_decode('MUNICÍPIO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("xMun")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("xMun")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','C',0,'');
        //UF
        $x += $w;
        $w = 8;
        $texto = utf8_decode('UF');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("UF")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //INSCRIÇÃO ESTADUAL
        $x += $w;
        $w = 35;
        $texto = utf8_decode('INSCRIÇÃO ESTADUAL');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("IE")->item(0)->nodeValue) ? $this->__format($this->transporta->getElementsByTagName("IE")->item(0)->nodeValue,"###.###.###.###") : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //#####################################################################
        //QUANTIDADE
        $w = 20;
        $y += $h;
        $x = $oldX;
        $h = 7;
        $texto = utf8_decode('QUANTIDADE');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->transp->getElementsByTagName("qVol")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("qVol")->item(0)->nodeValue : '';
        $texto = utf8_decode($texto);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','C',0,'');
        //ESPÉCIE
        $x += $w;
        $w = 36;
        $texto = utf8_decode('ESPÉCIE');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->transp->getElementsByTagName("esp")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("esp")->item(0)->nodeValue : '';
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','C',0,'');
        //MARCA
        $x += $w;
        $w = 36;
        $texto = utf8_decode('MARCA');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->transp->getElementsByTagName("esp")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("esp")->item(0)->nodeValue : '';
        $texto = '';
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','C',0,'');
        //NÚMERO
        $x += $w;
        $w = 36;
        $texto = utf8_decode('NÚMERO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->transp->getElementsByTagName("esp")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("esp")->item(0)->nodeValue : '';
        $texto = '';
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','C',0,'');
        //PESO BRUTO
        $x += $w;
        $w = 36;
        $texto = utf8_decode('PESO BRUTO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
	$texto = !empty($this->transp->getElementsByTagName("pesoB")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("pesoB")->item(0)->nodeValue : '0.0';
        $texto = number_format($texto, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','R',0,'');
        //PESO LÍQUIDO
        $x += $w;
        $w = 36;
        $texto = utf8_decode('PESO LÍQUIDO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
	$texto = !empty($this->transp->getElementsByTagName("pesoL")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("pesoL")->item(0)->nodeValue : '0.0';
        $texto = number_format($texto, 2, ",", ".");
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,strtoupper($texto),$aFont,'B','R',0,'');

        return ($y+$h);
    } //fim __transporteDANFE

    /**
     * __itensDANFE
     * Monta o campo de itens da DANFE
     * @package NFePHP
     * @name __itensDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @param number $nInicio Número do item inicial
     * @param number $max Número do item final
     * @param number $hmax Haltura máxima do campo de itens em mm
     * @return number Posição vertical final
     */
    private function __itensDANFE($x,$y,$nInicio,$max,$hmax) {
        $oldX = $x;
        $oldY = $y;
        //#####################################################################
        //DADOS DOS PRODUTOS / SERVIÇOS
        $texto = "DADOS DOS PRODUTOS / SERVIÇOS";
        $texto = utf8_decode($texto);
        $w = 64;
        $h = 4;
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        $y += 3;
        $w = 200;
        //desenha a caixa dos dados dos itens da NF
        $texto = '';
        $this->__textBox($x,$y,$w,$hmax);
        //##################################################################################
        // cabecalho LOOP COM OS DADOS DOS PRODUTOS
        //CÓDIGO PRODUTO
        $texto = "CÓDIGO PRODUTO";
        $texto = utf8_decode($texto);
        $w = 14;
        $h = 4;
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //DESCRIÇÃO DO PRODUTO / SERVIÇO
        $x += $w;
        $w = 66;
        $texto = utf8_decode('DESCRIÇÃO DO PRODUTO / SERVIÇO');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //NCM/SH
        $x += $w;
        $w = 13;
        $texto = utf8_decode('NCM/SH');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //O/CST
        $x += $w;
        $w = 7;
        $texto = utf8_decode('O/CST');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //CFOP
        $x += $w;
        $w = 7;
        $texto = utf8_decode('CFOP');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //UN
        $x += $w;
        $w = 6;
        $texto = utf8_decode('UN');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //QUANT
        $x += $w;
        $w = 10;
        $texto = utf8_decode('QUANT');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //VALOR UNIT
        $x += $w;
        $w = 13;
        $texto = utf8_decode('VALOR UNIT');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //VALOR TOTAL
        $x += $w;
        $w = 13;
        $texto = utf8_decode('VALOR TOTAL');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //B.CÁLC ICMS
        $x += $w;
        $w = 13;
        $texto = utf8_decode('B.CÁLC ICMS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //VALOR ICMS
        $x += $w;
        $w = 13;
        $texto = utf8_decode('VALOR ICMS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //VALOR IPI
        $x += $w;
        $w = 13;
        $texto = utf8_decode('VALOR IPI');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //ALÍQ. ICMS
        $x += $w;
        $w = 6;
        $texto = utf8_decode('ALÍQ. ICMS');
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w, $y, $x+$w, $y+$hmax);
        //ALÍQ. IPI
        $x += $w;
        $w = 6;
        $texto = utf8_decode('ALÍQ. IPI');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($oldX, $y+$h+1, $oldX + 200, $y+$h+1);

        //##################################################################################
        // LOOP COM OS DADOS DOS PRODUTOS
        $i = 0;
        foreach ($this->det as $d) {
            if ( $i >= $nInicio && $i <  $nInicio+$max ) {
                $prod = $this->det->item($i)->getElementsByTagName("prod")->item(0);
		$infAdProd = !empty($this->det->item($i)->getElementsByTagName("infAdProd")->item(0)->nodeValue) ? $this->det->item($i)->getElementsByTagName("infAdProd")->item(0)->nodeValue : '';
		$imposto = $this->det->item($i)->getElementsByTagName("imposto")->item(0);
		$ICMS = $imposto->getElementsByTagName("ICMS")->item(0);
		$IPI  = $imposto->getElementsByTagName("IPI")->item(0);
                //codigo do produto
                $y += $h;
                $x = $oldX;
                $w = 14;
                $h = 9;
                $aFont = array('font'=>'Arial','size'=>7,'style'=>'');
                $texto = $prod->getElementsByTagName("cProd")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $x += $w;
                $w = 64;
                $texto = utf8_decode($prod->getElementsByTagName("xProd")->item(0)->nodeValue ."  ". $infAdProd);
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','L',0,'',FALSE);

                $x += $w+2;
                $w = 13;
                $texto = !empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue) ? $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $x += $w;
                $w = 7;
                $texto = $ICMS->getElementsByTagName("orig")->item(0)->nodeValue . $ICMS->getElementsByTagName("CST")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $x += $w;
                $w = 7;
                $texto = $prod->getElementsByTagName("CFOP")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $x += $w;
                $w = 6;
                $texto = $prod->getElementsByTagName("uCom")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $x += $w;
                $w = 10;
                $texto = number_format($prod->getElementsByTagName("qCom")->item(0)->nodeValue, 2, ",", ".");
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','R',0,'');

                $x += $w;
                $w = 13;
                $texto = number_format($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue, 4, ",", ".");
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','R',0,'');

                $x += $w;
                $w = 13;
                $texto = number_format($prod->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','R',0,'');

                $x += $w;
                $w = 13;
                $texto = !empty($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", ".") : '0,00';
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','R',0,'');

                $x += $w;
                $w = 13;
                $texto = !empty($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", ".") : '0,00';
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','R',0,'');

                $x += $w;
                $w = 13;
                $texto = !empty($IPI->getElementsByTagName("vIPI")->item(0)->nodeValue) ? number_format($IPI->getElementsByTagName("vIPI")->item(0)->nodeValue, 2, ",", ".") :'';
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','R',0,'');

                $x += $w;
                $w = 6;
                $texto = !empty($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue, 0, ",", ".") : '0,00';
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $x += $w;
                $w = 6;
                $texto = !empty($IPI->getElementsByTagName("pIPI")->item(0)->nodeValue) ? number_format($IPI->getElementsByTagName("pIPI")->item(0)->nodeValue, 2, ",", ".") : '';
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');

                $i++;

                if ( $i > ($nInicio+$max-1) ) {
                    //ultrapassa a capacidade para uma única página
                    //o restante dos dados serão usados nas proximas paginas
                    break;
                }
            } else{
                $i++;
            }
        }
        return $oldY+$hmax;
    } // fim __itensDANFE

    /**
     *__dadosAdicionaisDANFE
     * Coloca o grupo de ados adicionais da NFe.
     * @package NFePHP
     * @name __dadosAdicionaisDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    private function __dadosAdicionaisDANFE($x,$y){
        $oldX = $x;
        //##################################################################################
        //DADOS ADICIONAIS
        $texto = "DADOS ADICIONAIS";
        $texto = utf8_decode($texto);
        $w = 40;
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'I');
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','L',0,'');
        //INFORMAÇÕES COMPLEMENTARES
        $texto = "INFORMAÇÕES COMPLEMENTARES";
        $texto = utf8_decode($texto);
        $y += 3;
        $w = 140;
        $h = 25;
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue) ? $this->infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue : '';
        $texto = utf8_decode($texto);
        $aFont = array('font'=>'Arial','size'=>8,'style'=>'');
        $this->__textBox($x,$y+3,$w,$h-3,$texto,$aFont,'T','L',0,'',FALSE);
        //RESERVADO AO FISCO
        $texto = "RESERVADO AO FISCO";
        $texto = utf8_decode($texto);
        $x += $w;
        $w = 60;
        $h = 25;
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');

        return $y+$h;
    } //fim __dadosAdicionaisDANFE

    /**
     * __canhotoDANFE
     * Monta o canho no final da DANFE
     * @package NFePHP
     * @name __canhotoDANFE
     * @version 1.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    private function __canhotoDANFE($x,$y) {
        $oldX = $x;
        //#################################################################################
        //canhoto
        //identificação do sistema emissor
        //linha separadora do canhoto
        $texto = "Impresso com as bilbiotecas do NFePHP - Copyright © 2009 NFePHP - http://www.nfephp.org - Distribuído sob licença GNU/GPL v.3 (Open Source)";
        $texto = utf8_decode($texto);
        $w = 200;
        $aFont = array('font'=>'Arial','size'=>5,'style'=>'I');
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','R',0,'');
        $y += 4;
        $this->__HdashedLine($x,$y,201,0.1,80);
        $w = 160;
        $y += 2;
        $h = 10;
        $numNF = str_pad($this->ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
        $serie = str_pad($this->ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
        $texto = "RECEBEMOS DE ";
        $texto .= $this->emit->getElementsByTagName("xNome")->item(0)->nodeValue . " ";
        $texto .= "OS PRODUTOS E/OU SERVIÇOS CONSTANTES DA NOTA FISCAL ELETRÔNICA INDICADA AO LADO. EMISSÃO: ";
        $texto .= $this->__ymd2dmy($this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue) ." ";
        $texto .= "VALOR TOTAL: R$ ";
        $texto .= number_format($this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".") . " ";
        $texto .= "DESTINATÁRIO: ";
        $texto .= $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue . " - ";
        $texto .= $this->enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue . ", ";
        $texto .= $this->enderDest->getElementsByTagName("nro")->item(0)->nodeValue . " - ";
        $texto .= $this->enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue . " ";
        $texto .= $this->enderDest->getElementsByTagName("xMun")->item(0)->nodeValue . "-";
        $texto .= $this->enderDest->getElementsByTagName("UF")->item(0)->nodeValue . "";
        $texto = utf8_decode(strtoupper($texto));
        $aFont = array('font'=>'Arial','size'=>7,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','L',1,'',FALSE);
        $x1 = $x + $w;
        $texto = "DANFE";
        $aFont = array('font'=>'Arial','size'=>14,'style'=>'B');
        $this->__textBox($x1,$y,40,18,$texto,$aFont,'T','C',0,'');
        $texto = "NF-e \n";
        $texto = "Nº. " . $this->__format($numNF,"###.###.###") . " \n";
        $texto .= "Série $serie";
        $texto = utf8_decode($texto);
        $aFont = array('font'=>'Arial','size'=>10,'style'=>'B');
        $this->__textBox($x1,$y,40,18,$texto,$aFont,'C','C',1,'');
        //DATA DO RECEBIMENTO
        $texto = "DATA DO RECEBIMENTO";
        $y += $h;
        $w = 35;
        $aFont = array('font'=>'Arial','size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','L',1,'');
        //IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR
        $x += $w;
        $w = 125;
        $texto = utf8_decode("IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR");
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','L',1,'');

        return $y;
    } //fim __canhotoDANFE


    /**
     * __format
     * Função de formatação de strings.
     * @package NFePHP
     * @name __format
     * @version 1.0
     * @param string $campo String a ser formatada
     * @param string $mascara Regra de formatção da string (ex. ##.###.###/####-##)
     * @return string Retorna o campo formatado
     */
    private function __format($campo='',$mascara=''){
        //remove qualquer formatação que ainda exista
        $sLimpo = ereg_replace("/[' '-./ t]/",'',$campo);
        // pega o tamanho da string e da mascara
        $tCampo = strlen($sLimpo);
        $tMask = strlen($mascara);
        if ( $tCampo > $tMask ) {
            $tMaior = $tCampo;
        } else {
            $tMaior = $tMask;
        }
	//contar o numero de cerquilhas da marcara
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
            for ( $i = $tMaior-1; $i >= 0; $i-- ) {
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
            return trim($sRetorno);
        } else {
            return '';
        }
    } //fim __format

    /**
     *__textBox
     * Cria uma caixa de texto com ou sem bordas. Esta função perimite o alinhamento horizontal
     * ou vertical do texto dentro da caixa.
     * Atenção : Esta função é dependente de outras classes de FPDF
     *
     * Ex. $this->__textBox(2,20,34,8,'Texto',array('fonte'=>'Arial','size'=>10,'style='B'),'C','L',FALSE,'http://www.nfephp.org')
     *
     * @package NFePHP
     * @name __textBox
     * @version 1.0
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
     * @return none
     */
    private function __textBox($x,$y,$w,$h,$text='',$aFont=array('font'=>'Arial','size'=>8,'style'=>''),$vAlign='T',$hAlign='L',$border=1,$link='',$force=TRUE){
        //desenhar a borda
        if ( $border ) {
            $this->pdf->RoundedRect($x,$y,$w,$h,0.8,'D');
        }
        //estabelecer o fonte
        $this->pdf->SetFont($aFont['font'],$aFont['style'],$aFont['size']);
        //calcular o incremento
        $incY = $this->pdf->FontSize; //$aFont['size']/3;//$this->pdf->FontSize;
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
            $y1 = ($y + $h)-0.5; //- ($altText/2);
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
                $x1 = $x+1;
            }
            if ( $hAlign == 'C' ) {
                $x1 = $x + (($w - $comp)/2);
            }
            if ( $hAlign == 'R' ) {
                $x1 = $x + $w - ($comp+0.5);
            }
            //escrever o texto
            $this->pdf->Text($x1, $y1, $texto);
            //incrementar para escrever o proximo
            $y1 += $incY;
        }
    } // fim função __textBox

    /**
     *__HdashedLine
     * Desenha uma linha horizontal tracejada com o FPDF
     *
     * @package NFePHP
     * @name __HdashedLine
     * @version 1.0
     * @author Roberto L. Machado <roberto.machado@superig.com.br>
     * @param number $x Posição horizontal inicial, em mm
     * @param number $y Posição vertical inicial, em mm
     * @param number $w Comprimento da linha, em mm
     * @param number $h Espessura da linha, em mm
     * @param number $n Numero de traços na seção da linha com o comprimento $w
     * @return none
     */
    private function __HdashedLine($x,$y,$w,$h,$n) {
        $this->pdf->SetLineWidth($h);
        $wDash=($w/$n)/2; // comprimento dos traços
        for( $i=$x; $i<=$x+$w; $i += $wDash+$wDash ) {
            for( $j=$i; $j<= ($i+$wDash); $j++ ) {
                if( $j <= ($x+$w-1) ) {
                    $this->pdf->Line($j,$y,$j+1,$y);
                }
            }
        }
    } //fim função __HdashedLine

    /**
     *__ymd2dmy
     * Converte datas no formato YMD (ex. 2009-11-02) para o formato brasileiro 02/11/2009)
     *
     * @package NFePHP
     * @name __ymd2dmy
     * @version 1.0
     * @author Roberto L. Machado <roberto.machado@superig.com.br>
     * @param string $data Parâmetro extraido da NFe
     * @return string Formatada para apresnetação da data no padrão brasileiro
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
     * @author Roberto L. Machado <roberto.machado@superig.com.br>
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

} //fim da classe

?>
