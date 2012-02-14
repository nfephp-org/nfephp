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
 * @name        DanfeNFePHP.class.php
 * @version     2.13
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *              Abdenego Santos <abdenego at gmail dot com>
 *              André Ferreira de Morais <andrefmoraes at gmail dot com>
 *              Bruno J R Lima <brunofileh at gmail dot com>
 *              Chrystian Toigo <ctoigo at gmail dot com>
 *              Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 *              Faruk Mustafa Zahra < farukz at gmail dot com >
 *              Felipe Bonato <montanhats at gmail dot com>
 *              Guilherme Calabria Filho <guiga at gmail dot com>
 *              Leandro C. Lopez <leandro.castoldi at gmail dot com>
 *              Marcos Diez <marcos at unitron dot com dot br>
 *              Paulo Gabriel Coghi < paulocoghi at gmail dot com>
 *              Renato Zaccaron Gonzaga <renato at zaccaron dot com dot br>
 *              Vinicius Souza <vdssgmu at gmail dot com>
 *
 *
 * NOTA: De acordo com a ISO o formato OficioII não existe mais e portanto só devemos
 *       usar o padrão A4.
 *
 */

//a linha abaixo previne falhas caso mesnsagens de erro do php fossem enviadas
error_reporting(0);ini_set('display_errors', 'Off');
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
define('FPDF_FONTPATH','font/');
//classe extendida da classe FPDF para montagem do arquivo pdf
require_once('PdfNFePHP.class.php');
//classe com as funções communs entre DANFE e DACTE
require_once('CommonNFePHP.class.php');
//interface 
require_once('DocumentoNFePHP.interface.php');

//classe principal
class DanfeNFePHP extends CommonNFePHP implements DocumentoNFePHP {
    //publicas
    public $logoAlign='C'; //alinhamento do logo
    public $yDados=0;
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
    protected $version = '2.13';
    protected $textoAdic = '';
    protected $wAdic = 0;
    protected $wPrint; //largura imprimivel
    protected $hPrint; //comprimento imprimivel
    protected $wCanhoto; //largura do canhoto para a formatação paisagem
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    protected $exibirPIS=1; //1- exibe os valores do pis e cofins 0-não exibe os valores
    //objetos DOM da NFe
    protected $dom;
    protected $infNFe;
    protected $ide;
    protected $entrega;
    protected $retirada;
    protected $emit;
    protected $dest;
    protected $enderEmit;
    protected $enderDest;
    protected $det;
    protected $cobr;
    protected $dup;
    protected $ICMSTot;
    protected $ISSQNtot;
    protected $transp;
    protected $transporta;
    protected $veicTransp;
    protected $infAdic;
    protected $tpEmis;
    protected $tpImp; //1-Retrato/ 2-Paisagem
    protected $compra;

    /**
     *__construct
     * @package NFePHP
     * @name __construct
     * @version 1.01
     * @param string $docXML Arquivo XML da NFe (com ou sem a tag nfeProc)
     * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
     * @param string $sPapel Tamanho do papel (Ex. A4)
     * @param string $sPathLogo Caminho para o arquivo do logo
     * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
     * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
     * @param string $fonteDANFE Nome da fonte alternativa do DAnfe
     * @param number $exibirPIS 1-SIM e 0-Não
     */
    function __construct($docXML='', $sOrientacao='',$sPapel='',$sPathLogo='', $sDestino='I',$sDirPDF='',$fonteDANFE='',$exibirPIS=1) {
        $this->orientacao   = $sOrientacao;
        $this->papel        = $sPapel;
        $this->pdf          = '';
        $this->xml          = $docXML;
        $this->logomarca    = $sPathLogo;
        $this->destino      = $sDestino;
        $this->pdfDir       = $sDirPDF;
       // verifica se foi passa a fonte a ser usada
       if (empty($fonteDANFE)) {
            $this->fontePadrao = 'Times';
       } else {
            $this->fontePadrao = $fonteDANFE;
       }
       $this->exibirPIS = $exibirPIS;
       //se for passado o xml
       if ( !empty($this->xml) ) {
            $this->dom = new DomDocument;
            $this->dom->loadXML($this->xml);
            $this->nfeProc    = $this->dom->getElementsByTagName("nfeProc")->item(0);
            $this->infNFe     = $this->dom->getElementsByTagName("infNFe")->item(0);
            $this->ide        = $this->dom->getElementsByTagName("ide")->item(0);
            $this->entrega    = $this->dom->getElementsByTagName("entrega")->item(0);
            $this->retirada   = $this->dom->getElementsByTagName("retirada")->item(0);
            $this->emit       = $this->dom->getElementsByTagName("emit")->item(0);
            $this->dest       = $this->dom->getElementsByTagName("dest")->item(0);
            $this->enderEmit  = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->enderDest  = $this->dom->getElementsByTagName("enderDest")->item(0);
            $this->det        = $this->dom->getElementsByTagName("det");
            $this->cobr       = $this->dom->getElementsByTagName("cobr")->item(0);
            $this->dup        = $this->dom->getElementsByTagName('dup');
            $this->ICMSTot    = $this->dom->getElementsByTagName("ICMSTot")->item(0);
            $this->ISSQNtot   = $this->dom->getElementsByTagName("ISSQNtot")->item(0);
            $this->transp     = $this->dom->getElementsByTagName("transp")->item(0);
            $this->transporta = $this->dom->getElementsByTagName("transporta")->item(0);
            $this->veicTransp = $this->dom->getElementsByTagName("veicTransp")->item(0);
            $this->infAdic    = $this->dom->getElementsByTagName("infAdic")->item(0);
            $this->compra     = $this->dom->getElementsByTagName("compra")->item(0);
            $this->tpEmis     = $this->ide->getElementsByTagName("tpEmis")->item(0)->nodeValue;
            $this->tpImp      = $this->ide->getElementsByTagName("tpImp")->item(0)->nodeValue;
       }
    } //fim construct

    /**
     * simpleConsistencyCheck
     * @package NFePHP
     * @name SimpleConsistencyCheck()
     * @version 1.0
     * @author Marcos Diez
     * @return bool Retorna se o documenento se parece com um DANFE ( condicao necessaria porem nao suficiente )
    */
    public function simpleConsistencyCheck(){
		if( 1 == 2 
		|| $this->xml == null 
		// || $this->nfeProc == null 
		|| $this->infNFe == null 
		|| $this->ide == null 
		){ 
			return false; 
		}
		return true;
	}

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
    public function monta($orientacao='',$papel='A4',$logoAlign='C'){
        return $this->montaDANFE($orientacao,$papel,$logoAlign);
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
        return $this->printDANFE($nome,$destino,$printer);
    }//fim printDocument
	
    /**
     * montaDANFE
     * Esta função monta a DANFE conforme as informações fornecidas para a classe
     * durante sua construção.
     * Esta função constroi DANFE's com até 3 páginas podendo conter até 56 itens.
     * A definição de margens e posições iniciais para a impressão são estabelecidas no
     * pelo conteúdo da funçao e podem ser modificados.
     * @package NFePHP
     * @name montaDANFE
     * @version 2.5
     * @param string $orientacao (Opcional) Estabelece a orientação da impressão (ex. P-retrato), se nada for fornecido será usado o padrão da NFe
     * @param string $papel (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID da NFe numero de 44 digitos extraido do arquivo XML
     */
    public function montaDANFE($orientacao='',$papel='A4',$logoAlign='C'){
        //se a orientação estiver em branco utilizar o padrão estabelecido na NF
        if ($orientacao == ''){
            if($this->tpImp == '1'){
                $orientacao = 'P';
            } else {
                $orientacao = 'L';
            }
        }
        $this->orientacao = $orientacao;
        $this->__adicionaLogoPeloCnpj();
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

        //##################################################################
        // CALCULO DO NUMERO DE PAGINAS A SEREM IMPRESSAS
        //##################################################################
        //Verificando quantas linhas serão usadas para impressão das duplicatas
        $linhasDup = 0;
        if ( ($this->dup->length > 0) && ($this->dup->length <= 7) ) {
            $linhasDup = 1;
        } elseif ( ($this->dup->length > 7) && ($this->dup->length <= 14) ) {
            $linhasDup = 2;
        } elseif ( ($this->dup->length > 14) && ($this->dup->length <= 21) ) {
            $linhasDup = 3;
        } elseif ($this->dup->length > 21) {
            $linhasDup = 3;
        } else{
            $linhasDup = 0;
        }
        //verifica se será impresso a linha dos serviços ISSQN
        $linhaISSQN = 0;
        if ( isset($this->ISSQNtot) ){
            if ($this->ISSQNtot->getElementsByTagName("vServ")->item(0)->nodeValue > 0 ) {
                $linhaISSQN = 1;
            }
        }
        //calcular a altura necessária para os dados adicionais
        if( $this->orientacao == 'P' ){
            $this->wAdic = round($this->wPrint*0.66,0);
        }else{
            $this->wAdic = round(($this->wPrint-$this->wCanhoto)*0.5,0);
        }
        $fontProduto = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'');
        $this->textoAdic = '';
        if( isset($this->retirada) ){
            $txRetCNPJ = !empty($this->retirada->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
            $txRetxLgr = !empty($this->retirada->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
            $txRetnro = !empty($this->retirada->getElementsByTagName("nro")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("nro")->item(0)->nodeValue : 's/n';
            $txRetxCpl = !empty($this->retirada->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
            $txRetxBairro = !empty($this->retirada->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
            $txRetxMun = !empty($this->retirada->getElementsByTagName("xMun")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("xMun")->item(0)->nodeValue : '';
            $txRetUF = !empty($this->retirada->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->retirada->getElementsByTagName("UF")->item(0)->nodeValue : '';
            $this->textoAdic .= "LOCAL DE RETIRADA : " .$txRetCNPJ. '-' . $txRetxLgr . ',' . $txRetnro . ' ' . $txRetxCpl . ' - ' . $txRetxBairro . ' ' .$txRetxMun . ' - ' .$txRetUF . "\r\n";
        }
        //dados do local de entrega da mercadoria
        if( isset($this->entrega) ){
            $txRetCNPJ = !empty($this->entrega->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
            $txRetxLgr = !empty($this->entrega->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
            $txRetnro = !empty($this->entrega->getElementsByTagName("nro")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("nro")->item(0)->nodeValue : 's/n';
            $txRetxCpl = !empty($this->entrega->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
            $txRetxBairro = !empty($this->entrega->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
            $txRetxMun = !empty($this->entrega->getElementsByTagName("xMun")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("xMun")->item(0)->nodeValue : '';
            $txRetUF = !empty($this->entrega->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->entrega->getElementsByTagName("UF")->item(0)->nodeValue : '';
            if( $this->textoAdic != '' ){
                $this->textoAdic .= ". \r\n";
            }
            $this->textoAdic .= "LOCAL DE ENTREGA : " .$txRetCNPJ. '-' . $txRetxLgr . ',' . $txRetnro . ' ' . $txRetxCpl . ' - ' . $txRetxBairro . ' ' .$txRetxMun . ' - ' .$txRetUF . "\r\n";
        }
        //informações adicionais
        $this->textoAdic .= $this->__geraInformacoesDasNotasReferenciadas();
        if (isset($this->infAdic)){
            $i = 0;
            if( $this->textoAdic != '' ){
                $this->textoAdic .= ". \r\n";
            }
            $this->textoAdic .= !empty($this->infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue) ? 'Inf. Contribuinte: ' . trim($this->infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue) : '';
            $infPedido = $this->__geraInformacoesDaTagCompra();
            if( $infPedido != "" ){
                $this->textoAdic .= $infPedido;
            }
            
			$this->textoAdic .= $this->__simpleGetValue( $this->dest , "email" , ' Email do Destinatário: ');

            $this->textoAdic .= !empty($this->infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue) ? "\r\n Inf. fisco: " . trim($this->infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue) : '';
            $obsCont = $this->infAdic->getElementsByTagName("obsCont");
            if (isset($obsCont)){
                foreach ($obsCont as $obs){
                    $campo =  $obsCont->item($i)->getAttribute("xCampo");
                    $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                    $this->textoAdic .= "\r\n" . $campo . ':  ' . trim($xTexto);
                    $i++;
                }
            }
        }
        $this->textoAdic = str_replace( ";" , "\n" , $this->textoAdic );
        $alinhas = explode("\n",$this->textoAdic);
        $numlinhasdados = 0;
        foreach ($alinhas as $linha){
            $numlinhasdados += $this->__getNumLines($linha,$this->wAdic,$fontProduto);
        }
        $hdadosadic = round(($numlinhasdados+3) * $this->pdf->FontSize,0);
        if ($hdadosadic < 10 ){
            $hdadosadic = 10;
        }
        //altura disponivel para os campos da DANFE
        $hcabecalho = 47;//para cabeçalho
        $hdestinatario = 25;//para destinatario
        $hduplicatas = 12;//para cada grupo de 7 duplicatas
        $himposto = 18;// para imposto
        $htransporte = 25;// para transporte
        $hissqn = 11;// para issqn
        $hfooter = 5;// para rodape
        $hCabecItens = 4;//cabeçalho dos itens
        //alturas disponiveis para os dados
        if( $this->orientacao == 'P' ){
            $hcanhoto = 23;//para canhoto
            $hDispo1 = $this->hPrint - ($hcanhoto + $hcabecalho + $hdestinatario + ($linhasDup * $hduplicatas) + $himposto + $htransporte + ($linhaISSQN * $hissqn) + $hdadosadic + $hfooter + $hCabecItens);
        }else{
            $hcanhoto = $this->hPrint;//para canhoto
            $hDispo1 = $this->hPrint - ( $hcabecalho + $hdestinatario + ($linhasDup * $hduplicatas) + $himposto + $htransporte + ($linhaISSQN * $hissqn) + $hdadosadic + $hfooter + $hCabecItens);
        }
        $hDispo2 = $this->hPrint - ($hcabecalho + $hfooter + $hCabecItens);
        //Contagem da altura ocupada para impressão dos itens
        $fontProduto = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'');
        $i = 0;
        $numlinhas = 0;
        $hUsado = $hCabecItens;
	// $w2 = round($this->wPrint*0.356,0)-1;
        $w2 = round($this->wPrint*0.31,0);
        while ($i < $this->det->length){
            $texto = $this->__descricaoProduto( $this->det->item($i) ) ;
            $numlinhas = $this->__getNumLines($texto,$w2,$fontProduto);
            $hUsado += round(($numlinhas * $this->pdf->FontSize)+1,0);
            $i++;
        } //fim da soma das areas de itens usadas
        if($hUsado > $hDispo1){
            //serão necessárias mais paginas
            $hOutras = $hUsado - $hDispo1;
            $totPag = 1 + ceil($hOutras / $hDispo2);
        } else {
            //sera necessaria apenas uma pagina
            $totPag = 1;
        }
        //montagem da primeira página
        $pag = 1;
        $x = $xInic;
        $y = $yInic;
        //coloca o canhoto da NFe
        if( $this->orientacao == 'P' ){
            $y = $this->__canhotoDANFE($x,$y);
        } else {
            $this->__canhotoDANFE($x,$y);
            $x = 25;
            $y = $yInic;
        }
        //coloca o cabeçalho
        $y = $this->__cabecalhoDANFE($x,$y,$pag,$totPag);
        //coloca os dados do destinatário
        $y = $this->__destinatarioDANFE($x,$y+1);
        //coloca os dados das faturas
        $y = $this->__faturaDANFE($x,$y+1);
        //coloca os dados dos impostos e totais da NFe
        $y = $this->__impostoDANFE($x,$y+1);
        //coloca os dados do trasnporte
        $y = $this->__transporteDANFE($x,$y+1);
        //itens da DANFE
        $nInicial = 0;
        $y = $this->__itensDANFE($x,$y+1,$nInicial,$hDispo1,$pag,$totPag);
        //coloca os dados do ISSQN
        if ($linhaISSQN == 1) {
            $y = $this->__issqnDANFE($x,$y+4);
        } else {
           $y += 4;
        }
        //coloca os dados adicionais da NFe
        $y = $this->__dadosAdicionaisDANFE($x,$y,$hdadosadic);
        //coloca o rodapé da página
        if( $this->orientacao == 'P' ){
            $this->__rodapeDANFE( 2 , $this->hPrint - 2 );
        } else {
            $this->__rodapeDANFE($xInic,$this->hPrint + 3);
        }
        //loop para páginas seguintes
        for ( $n = 2; $n <= $totPag; $n++ ) {
            // fixa as margens
            $this->pdf->SetMargins($margEsq,$margSup,$margDir);
            //adiciona nova página
            $this->pdf->AddPage($this->orientacao, $this->papel);
            //ajusta espessura das linhas
            $this->pdf->SetLineWidth(0.1);
            //seta a cor do texto para petro
            $this->pdf->SetTextColor(0,0,0);
            // posição inicial do relatorio
            $x = $xInic;
            $y = $yInic;
            //coloca o cabeçalho na página adicional
            $y = $this->__cabecalhoDANFE($x,$y,$n,$totPag);
            //coloca os itens na página adicional
            $y = $this->__itensDANFE($x,$y+1,$nInicial,$hDispo2,$pag,$totPag);
            //coloca o rodapé da página
            if( $this->orientacao == 'P' ){
                   $this->__rodapeDANFE( 2 , $this->hPrint - 2 );
            }else{
                   $this->__rodapeDANFE($xInic,$this->hPrint + 3);
            }
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
            //aqui pode entrar a rotina de impressão direta
        }
        return $arq;

        /*
           Opção 1 - exemplo de script shell usando acroread
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
            Opção 3 -
            salvar pdf em arquivo temporario
            imprimir para printer usando lp ou lpr com system do php
            remover os arquivos temporarios pdf
        */
    } //fim função printDANFE

    /**
     *__cabecalhoDANFE
     * Monta o cabelhalho da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __cabecalhoDANFE
     * @version 1.22
     * @param number $x Posição horizontal inicial, canto esquerdo
     * @param number $y Posição vertical inicial, canto superior
     * @param number $pag Número da Página
     * @param number$totPag Total de páginas
     * @return number Posição vertical final
     */
    protected function __cabecalhoDANFE($x=0,$y=0,$pag='1',$totPag='1'){
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
        //estabelecer o alinhamento
        //pode ser left L , center C , right R
        //se for left separar 1/3 da largura para o tamanho da imagem
        //os outros 2/3 serão usados para os dados do emitente
        //se for center separar 1/2 da altura para o logo e 1/2 para os dados
        //se for right separa 2/3 para os dados e o terço seguinte para o logo
        //se não houver logo centraliza dos dados do emitente
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
        $texto = $lgr . "," . $nro . "  " . $cpl . "\n" . $bairro . " - " . $CEP . "\n" . $mun . " - " . $UF . " " . "Fone/Fax: " . $fone;
        $this->__textBox($x1,$y1,$tw,8,$texto,$aFont,'T','C',0,'');

        //####################################################################################
        //coluna central Danfe
        $x += $w;
        $w=round($maxW * 0.17,0);//35;
        $w2 = $w;
        $h = 32;
        $this->__textBox($x,$y,$w,$h);
        $texto = "DANFE";
        $aFont = array('font'=>$this->fontePadrao,'size'=>14,'style'=>'B');
        $this->__textBox($x,$y+1,$w,$h,$texto,$aFont,'T','C',0,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $texto = 'Documento Auxiliar da Nota Fiscal Eletrônica';
        $h = 20;
        $this->__textBox($x,$y+6,$w,$h,$texto,$aFont,'T','C',0,'',FALSE);
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $texto = '0 - ENTRADA';
        $y1 = $y + 14;
        $h = 8;
        $this->__textBox($x+2,$y1,$w,$h,$texto,$aFont,'T','L',0,'');
        $texto = '1 - SAÍDA';
        $y1 = $y + 17;
        $this->__textBox($x+2,$y1,$w,$h,$texto,$aFont,'T','L',0,'');
        //tipo de nF
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'B');
        $y1 = $y + 13;
        $h = 7;
        $texto = $this->ide->getElementsByTagName('tpNF')->item(0)->nodeValue;
        $this->__textBox($x+27,$y1,5,$h,$texto,$aFont,'C','C',1,'');
        //numero da NF
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $y1 = $y + 20;
        $numNF = str_pad($this->ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
        $numNF = $this->__format($numNF,"###.###.###");
        $texto = "Nº. " . $numNF;
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'C','C',0,'');
        //Série
        $y1 = $y + 23;
        $serie = str_pad($this->ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
        $texto = "Série " . $serie;
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'C','C',0,'');
        //numero paginas
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'I');
        $y1 = $y + 26;
        $texto = "Folha " . $pag . "/" . $totPag;
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'C','C',0,'');

        //####################################################################################
        //coluna codigo de barras
        $x += $w;
        $w = ($maxW-$w1-$w2);//85;
        $w3 = $w;
        $h = 32;
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
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $y1 = $y+4+$bH;
        $h = 7;
        $texto = 'CHAVE DE ACESSO';
        $this->__textBox($x,$y1,$w,$h,$texto,$aFont,'T','L',0,'');
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        $y1 = $y+8+$bH;
        $texto = $this->__format( $chave_acesso, $this->formatoChave );
        $this->__textBox($x+2,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'');
        $y1 = $y+12+$bH;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $chaveContingencia="";
        $cabecalhoProtoAutorizacao = 'PROTOCOLO DE AUTORIZAÇÃO DE USO';
        if( $this->tpEmis == 2 || $this->tpEmis == 5 ){
            $cabecalhoProtoAutorizacao = "DADOS DA NF-E";
            $chaveContingencia = $this->__geraChaveAdicionalDeContingencia();
            $this->pdf->SetFillColor(0,0,0);
            //codigo de barras
            $this->pdf->Code128($x+11,$y1+1,$chaveContingencia, $bW*.9 , $bH/2);
        }else{
            $texto = 'Consulta de autenticidade no portal nacional da NF-e';
            $this->__textBox($x+2,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'');
            $y1 = $y+16+$bH;
            $texto = 'www.nfe.fazenda.gov.br/portal ou no site da Sefaz Autorizadora';
            $this->__textBox($x+2,$y1,$w-2,$h,$texto,$aFont,'T','C',0,'http://www.nfe.fazenda.gov.br/portal ou no site da Sefaz Autorizadora');
        }

        //####################################################################################
        //Dados da NF do cabeçalho
        //natureza da operação
        $texto = 'NATUREZA DA OPERAÇÃO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $w = $w1+$w2;
        $y = $oldY;
        $oldY += $h;
        $x = $oldX;
        $h = 7;
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->ide->getElementsByTagName("natOp")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        $x += $w;
        $w = $w3;
        //PROTOCOLO DE AUTORIZAÇÃO DE USO ou DADOS da NF-E
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$cabecalhoProtoAutorizacao,$aFont,'T','L',1,'');
        // algumas NFe podem estar sem o protocolo de uso portanto sua existencia deve ser
        // testada antes de tentar obter a informação.
        // NOTA : DANFE sem protocolo deve existir somente no caso de contingência !!!
        // Além disso, existem várias NFes em contingência que eu recebo com protocolo de autorização.
        // Na minha opinião, deveríamos mostra-lo, mas o  manual  da NFe v4.01 diz outra coisa...
        if( $this->tpEmis == 2 || $this->tpEmis == 5 ){
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
            $texto = $this->__format( $chaveContingencia, "#### #### #### #### #### #### #### #### ####" );
            $cStat = '';
        }else{
            $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
            if( isset( $this->nfeProc ) ) {
                $texto = !empty($this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue) ? $this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue : '';
                $tsHora = $this->__convertTime($this->nfeProc->getElementsByTagName("dhRecbto")->item(0)->nodeValue);
                if ($texto != ''){
                    $texto .= "  -  " . date('d/m/Y   H:i:s',$tsHora);
                }
                $cStat = $this->nfeProc->getElementsByTagName("cStat")->item(0)->nodeValue;
            } else {
                $texto = '';
                $cStat = '';
            }
        }
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //####################################################################################
        //INSCRIÇÃO ESTADUAL
        $w = round($maxW * 0.333,0);
        $y += $h;
        $oldY += $h;
        $x = $oldX;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->__simpleGetValue( $this->emit , "IE");
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.
        $x += $w;
        $texto = 'INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->emit->getElementsByTagName("IEST")->item(0)->nodeValue) ? $this->emit->getElementsByTagName("IEST")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //CNPJ
        $x += $w;
        $w = ($maxW-(2*$w));
        $texto = 'CNPJ';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue;
        $texto = $this->__format($texto,"##.###.###/####-##");
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');

        //####################################################################################
        //Indicação de NF Homologação, cancelamento e falta de protocolo
        $tpAmb = $this->ide->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        //indicar cancelamento
        if ( $cStat == '101') {
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
        //indicar sem valor
        if ( $tpAmb != 1 ) {
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
        } else {
            $x = 10;
            if( $this->orientacao == 'P' ){
                $y = round($this->hPrint*2/3,0);
            } else {
                $y = round($this->hPrint/2,0);
            }//fim orientacao
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90,90,90);
            //indicar FALTA DO PROTOCOLO se NFe não for em contingência
            if( $this->tpEmis == 2 || $this->tpEmis == 5 ){
                //Contingência
                $texto = "DANFE Emitido em Contingência";
                $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
                $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
                $texto = "devido à problemas técnicos";
                $this->__textBox($x,$y+12,$w,$h,$texto,$aFont,'C','C',0,'');
            } else {    
                if ( !isset($this->nfeProc) ) {
                    $texto = "SEM VALOR FISCAL";
                    $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
                    $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','C',0,'');
                    $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
                    $texto = "FALTA PROTOCOLO DE APROVAÇÃO DA SEFAZ";
                    $this->__textBox($x,$y+12,$w,$h,$texto,$aFont,'C','C',0,'');
                }//fim nefProc
            }//fim tpEmis
            $this->pdf->SetTextColor(0,0,0);
        }
        return $oldY;
    } //fim __cabecalhoDANFE

    /**
     * __destinatarioDANFE
     * Monta o campo com os dados do destinatário na DANFE. ( retrato e paisagem )
     * @package NFePHP
     * @name __destinatarioDANFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function __destinatarioDANFE($x=0,$y=0){
        //####################################################################################
        //DESTINATÁRIO / REMETENTE
        $oldX = $x;
        $oldY = $y;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 7;
        $texto = 'DESTINATÁRIO / REMETENTE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        //NOME / RAZÃO SOCIAL
        $w = round($maxW*0.61,0);
        $w1 = $w;
        $y += 3;
        $texto = 'NOME / RAZÃO SOCIAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        if( $this->orientacao == 'P' ){
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','L',0,'');
        }else{
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','L',1,'');
        }
        //CNPJ / CPF
        $x += $w;
        $w = round($maxW*0.23,0);
        $w2 = $w;
        $texto = 'CNPJ / CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        //Pegando valor do CPF/CNPJ
        if ( !empty($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) ) {
            $texto = $this->__format($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue,"###.###.###/####-##");
        } else {
            $texto = !empty($this->dest->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->dest->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //DATA DA EMISSÃO
        $x += $w;
        $w = $maxW-($w1+$w2);
        $wx = $w;
        $texto = 'DATA DA EMISSÃO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->__ymd2dmy($this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue);
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        if( $this->orientacao == 'P' ){
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        }else{
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',1,'');
        }
        //ENDEREÇO
        $w = round($maxW*0.47,0);
        $w1 = $w;
        $y += $h;
        $x = $oldX;
        $texto = 'ENDEREÇO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->dest->getElementsByTagName("xLgr")->item(0)->nodeValue;
        $texto .= ', ' . $this->dest->getElementsByTagName("nro")->item(0)->nodeValue;
        $texto .= " " . $this->__simpleGetValue( $this->dest , "xCpl");
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','L',0,'',TRUE);
        //BAIRRO / DISTRITO
        $x += $w;
        $w = round($maxW*0.21,0);
        $w2 = $w;
        $texto = 'BAIRRO / DISTRITO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->dest->getElementsByTagName("xBairro")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //CEP
        $x += $w;
        $w = $maxW-$w1-$w2-$wx;
        $w2 = $w;
        $texto = 'CEP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->dest->getElementsByTagName("CEP")->item(0)->nodeValue) ? $this->dest->getElementsByTagName("CEP")->item(0)->nodeValue : '';
        $texto = $this->__format($texto,"#####-###");
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //DATA DA SAÍDA
        $x += $w;
        $w = $wx;
        $texto = 'DATA DA SAÍDA';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->ide->getElementsByTagName("dSaiEnt")->item(0)->nodeValue) ? $this->ide->getElementsByTagName("dSaiEnt")->item(0)->nodeValue:"";
        $texto = $this->__ymd2dmy($texto);
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //MUNICÍPIO
        $w = $w1;
        $y += $h;
        $x = $oldX;
        $texto = 'MUNICÍPIO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->dest->getElementsByTagName("xMun")->item(0)->nodeValue;
        if( strtoupper( trim( $texto ) ) == "EXTERIOR" ){
            $texto .= " - " .  $this->dest->getElementsByTagName("xPais")->item(0)->nodeValue;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','L',0,'');
        //UF
        $x += $w;
        $w = 8;
        $texto = 'UF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->dest->getElementsByTagName("UF")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //FONE / FAX
        $x += $w;
        $w = round(($maxW -$w1-$wx-8)/2,0);
        $w3 = $w;
        $texto = 'FONE / FAX';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->dest->getElementsByTagName("fone")->item(0)->nodeValue) ? $this->__format($this->dest->getElementsByTagName("fone")->item(0)->nodeValue,'(##) ####-####') : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //INSCRIÇÃO ESTADUAL
        $x += $w;
        $w = $maxW -$w1-$wx-8-$w3;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $this->dest->getElementsByTagName("IE")->item(0)->nodeValue;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //HORA DA SAÍDA
        $x += $w;
        $w = $wx;
        $texto = 'HORA DA SAÍDA';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->ide->getElementsByTagName("hSaiEnt")->item(0)->nodeValue) ? $this->ide->getElementsByTagName("hSaiEnt")->item(0)->nodeValue:"";
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        return ($y + $h);
    } //fim da função __destinatarioDANFE

    /**
     * __faturaDANFE
     * Monta o campo de duplicatas da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __faturaDANFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function __faturaDANFE($x,$y){
        $linha = 1;
        $h = 8+3;
        $oldx = $x;
        //verificar se existem duplicatas
        if ( $this->dup->length > 0 ) {
            //#####################################################################
            //FATURA / DUPLICATA
            $texto = "FATURA / DUPLICATA";
            $texto = $texto;
            if( $this->orientacao == 'P' ){
                $w = $this->wPrint;
            }else{
                $w = 271;
            }
            $h = 8;
            $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
            $y += 3;
            $dups = "";
            $dupcont = 0;
            $nFat = $this->dup->length;
            if( $this->orientacao == 'P' ){
                $w = round($this->wPrint/7.018,0)-1;
            }else{
                $w = 28;
            }
            $increm = 1;
            foreach ($this->dup as $k => $d) {
                $nDup = $this->dup->item($k)->getElementsByTagName('nDup')->item(0)->nodeValue;
                $dDup = $this->__ymd2dmy($this->dup->item($k)->getElementsByTagName('dVenc')->item(0)->nodeValue);
                $vDup = 'R$ ' . number_format($this->dup->item($k)->getElementsByTagName('vDup')->item(0)->nodeValue, 2, ",", ".");
                $h = 8;
                $texto = '';
                $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
                $this->__textBox($x,$y,$w,$h,'Num.',$aFont,'T','L',1,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
                $this->__textBox($x,$y,$w,$h,$nDup,$aFont,'T','R',0,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
                $this->__textBox($x,$y,$w,$h,'Venc.',$aFont,'C','L',0,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
                $this->__textBox($x,$y,$w,$h,$dDup,$aFont,'C','R',0,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
                $this->__textBox($x,$y,$w,$h,'Valor',$aFont,'B','L',0,'');
                $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
                $this->__textBox($x,$y,$w,$h,$vDup,$aFont,'B','R',0,'');
                $x += $w+$increm;
                $dupcont += 1;
                if( $this->orientacao == 'P' ){
                    $maxDupCont = 6;
                }else{
                    $maxDupCont = 8;
                }
                if ($dupcont > $maxDupCont) {
                    $y += 9;
                    $x = $oldx;
                    $dupcont = 0;
                    $linha += 1;
                }
                if ($linha == 4){
                    $linha = 3;
                    break;
                }
            }
            if ($dupcont == 0){
                $y = $y - 9;
                $linha = $linha -1;
            }
            return ($y+$h);
        } else {
            $linha = 0;
            return ($y-2);
       }
    } //fim da função __faturaDANFE

    /**
     * __impostoDanfeHelper
     * Auxilia a montagem dos campos de impostos e totais da DANFE
     * @package NFePHP
     * @name __impostoDanfeHelper
     * @version 1.0
     * @author Marcos Diez
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @param number $w Largura do campo
     * @param number $h Altura do campo
     * @param number $h Título do campo
     * @param number $h Valor do imposto
     */
    protected function __impostoDanfeHelper($x , $y , $w , $h , $titulo , $valorImposto ){
        $fontTitulo = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $fontValor = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$titulo,$fontTitulo,'T','L',1,'');
        $this->__textBox($x,$y,$w,$h,$valorImposto,$fontValor,'B','R',0,'');
    }

    /**
     * __impostoDANFE
     * Monta o campo de impostos e totais da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __impostoDANFE
     * @version 1.6
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function __impostoDANFE($x,$y){
        $oldX = $x;
        //#####################################################################
        $texto = "CÁLCULO DO IMPOSTO";
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
            $wPis = 18;
            $w1 = 31;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
            $wPis = 20;
            $w1 = 40;
        }
        if ($this->exibirPIS!='1'){
            $wPis = 0;
            if( $this->orientacao == 'P' ){
                $w1+= 2;
            } else {
                $w1+= 3;
            }
        }
        $w= $maxW;
        $w2 = $maxW-(5*$w1+$wPis);
        $w = $w1;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','L',0,'');
        //BASE DE CÁLCULO DO ICMS
        $y += 3;
        $h = 7;
        $texto = 'BASE DE CÁLCULO DO ICMS';
        $valorImposto = number_format($this->ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", ".");
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR DO ICMS
        $x += $w;
        $texto = 'VALOR DO ICMS';
        $valorImposto = number_format($this->ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", ".");
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //BASE DE CÁLCULO DO ICMS S.T.
        $x += $w;
        $texto = 'BASE DE CÁLC. ICMS S.T.';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR DO ICMS SUBSTITUIÇÃO
        $x += $w;
        $texto = 'VALOR DO ICMS SUBST.';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR II
        $x += $w;
        $texto = 'VALOR IMP. IMPORTAÇÃO';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vII")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vII")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR DO PIS
        if ($this->exibirPIS=='1'){
        $x += $w;
            $w=$wPis;
        $texto = 'VALOR DO PIS';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vPIS")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vPIS")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        } else {
            $texto = '';
            $valorImposto = '';
        }
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR TOTAL DOS PRODUTOS
        $x += $w;
        $w = $w2;
        $texto = 'VALOR TOTAL DOS PRODUTOS';
        $valorImposto = number_format($this->ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //#####################################################################
        //VALOR DO FRETE
        $w = $w1;
        $y += $h;
        $x = $oldX;
        $h = 7;
        $texto = 'VALOR DO FRETE';
        $valorImposto = number_format($this->ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue, 2, ",", ".");
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR DO SEGURO
        $x += $w;
        $texto = 'VALOR DO SEGURO';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //DESCONTO
        $x += $w;
        $texto = 'DESCONTO';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //OUTRAS DESPESAS
        $x += $w;
        $texto = 'OUTRAS DESPESAS';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR TOTAL DO IPI
        $x += $w;
        $texto = 'VALOR TOTAL DO IPI';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR DO COFINS
        if ($this->exibirPIS=='1'){
        $x += $w;
        $w = $wPis;
        $texto = 'VALOR DA COFINS';
        $valorImposto = !empty($this->ICMSTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? number_format($this->ICMSTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue, 2, ",", ".") : '0,00';
        } else {
            $texto = '';
            $valorImposto = '';
        }
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        //VALOR TOTAL DA NOTA
        $x += $w;
        $w = $w2;
        $texto = 'VALOR TOTAL DA NOTA';
        $valorImposto = number_format($this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
        $this->__impostoDanfeHelper( $x , $y , $w , $h , $texto , $valorImposto );
        return ($y+$h);
    } //fim __impostoDANFE

    /**
     * __transporteDANFE
     * Monta o campo de transportes da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __transporteDANFE
     * @version 1.2
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function __transporteDANFE($x,$y){
        $oldX = $x;
        if( $this->orientacao == 'P' ){
            $maxW = $this->wPrint;
        }else{
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        //#####################################################################
        //TRANSPORTADOR / VOLUMES TRANSPORTADOS
        $texto = "TRANSPORTADOR / VOLUMES TRANSPORTADOS";
        $w = $maxW;
        $h = 7;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        //NOME / RAZÃO SOCIAL
        $w1 = $maxW*0.29;
        $y += 3;
        $texto = 'NOME / RAZÃO SOCIAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ) {
            $texto = !empty($this->transporta->getElementsByTagName("xNome")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("xNome")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'B','L',0,'');
        //FRETE POR CONTA
        $x += $w1;
        $w2 = $maxW*0.15;
        $texto = 'FRETE POR CONTA';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        $tipoFrete = !empty($this->transp->getElementsByTagName("modFrete")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("modFrete")->item(0)->nodeValue : '0';
        switch( $tipoFrete ){
            case 0:
                default:
                $texto = "(0) Emitente";
                break;
            case 1:
                $texto = "(1) Dest/Rem";
                break;
            case 2:
                $texto = "(2) Terceiros";
                break;
            case 9:
                $texto = "(9) Sem Frete";
                break;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'C','C',1,'');
        //CÓDIGO ANTT
        $x += $w2;
        $texto = 'CÓDIGO ANTT';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->veicTransp) ){
            $texto = !empty($this->veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue) ? $this->veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'B','C',0,'');
        //PLACA DO VEÍC
        $x += $w2;
        $texto = 'PLACA DO VEÍCULO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->veicTransp) ){
            $texto = !empty($this->veicTransp->getElementsByTagName("placa")->item(0)->nodeValue) ? $this->veicTransp->getElementsByTagName("placa")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'B','C',0,'');
        //UF
        $x += $w2;
        $w3 = round($maxW*0.04,0);
        $texto = 'UF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->veicTransp) ){
            $texto = !empty($this->veicTransp->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->veicTransp->getElementsByTagName("UF")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'B','C',0,'');
        //CNPJ / CPF
        $x += $w3;
        $w = $maxW-($w1+3*$w2+$w3);
        $texto = 'CNPJ / CPF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $this->__format($this->transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue,"##.###.###/####-##") : '';
            if ($texto == ''){
                $texto = !empty($this->transporta->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($this->transporta->getElementsByTagName("CPF")->item(0)->nodeValue,"###.###.###-##") : '';
            }
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        //#####################################################################
        //ENDEREÇO
        $y += $h;
        $x = $oldX;
        $h = 7;
        $w1 = $maxW*0.44;
        $texto = 'ENDEREÇO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("xEnder")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("xEnder")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'B','L',0,'');
        //MUNICÍPIO
        $x += $w1;
        $w2 = round($maxW*0.30,0);
        $texto = 'MUNICÍPIO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("xMun")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("xMun")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'B','C',0,'');
        //UF
        $x += $w2;
        $w3 = round($maxW*0.04,0);
        $texto = 'UF';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->transporta) ){
            $texto = !empty($this->transporta->getElementsByTagName("UF")->item(0)->nodeValue) ? $this->transporta->getElementsByTagName("UF")->item(0)->nodeValue : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'B','C',0,'');
        //INSCRIÇÃO ESTADUAL
        $x += $w3;
        $w = $maxW-($w1+$w2+$w3);
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = '';
        if ( isset($this->transporta) ){
            if( !empty( $this->transporta->getElementsByTagName("IE")->item(0)->nodeValue )   ){
                $texto = $this->transporta->getElementsByTagName("IE")->item(0)->nodeValue;
            }
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','C',0,'');
        
        //Tratar Multiplos volumes 
        $volumes = $this->transp->getElementsByTagName('vol');
       	$quantidade = 0;
	$especie = '';
	$marca = '';
	$numero = '';
        $texto = '';
	$pesoBruto = 0;
	$pesoLiquido = 0;
        foreach($volumes as $volume){
            $quantidade += !empty($volume->getElementsByTagName("qVol")->item(0)->nodeValue) ? $volume->getElementsByTagName("qVol")->item(0)->nodeValue : 0;
            $pesoBruto += !empty($volume->getElementsByTagName("pesoB")->item(0)->nodeValue) ? $volume->getElementsByTagName("pesoB")->item(0)->nodeValue : 0;
            $pesoLiquido += !empty($volume->getElementsByTagName("pesoL")->item(0)->nodeValue) ? $volume->getElementsByTagName("pesoL")->item(0)->nodeValue : 0;
            $texto = !empty($this->transp->getElementsByTagName("esp")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("esp")->item(0)->nodeValue : '';
            if ($texto != $especie && $especie != ''){
                //tem várias especies 
                $especie = 'VARIAS';
            } else {
                $especie = $texto;
            }
            $texto = !empty($this->transp->getElementsByTagName("marca")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("marca")->item(0)->nodeValue : '';
            if ($texto != $marca && $marca != ''){
                //tem várias especies 
                $marca = 'VARIAS';
            } else {
                $marca = $texto;
            }
            $texto = !empty($this->transp->getElementsByTagName("nVol")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("nVol")->item(0)->nodeValue : '';    
            if ($texto != $numero && $numero != ''){
                //tem várias especies 
                $numero = 'VARIOS';
            } else {
                $numero = $texto;
            }
	}
        //#####################################################################
        //QUANTIDADE
        $y += $h;
        $x = $oldX;
        $h = 7;
        $w1 = round($maxW*0.10,0);
        $texto = 'QUANTIDADE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'T','L',1,'');
        $texto = $quantidade;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'B','C',0,'');
        //ESPÉCIE
        $x += $w1;
        $w2 = round($maxW*0.17,0);
        $texto = 'ESPÉCIE';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        $texto = $especie;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'B','C',0,'');
        //MARCA
        $x += $w2;
        $texto = 'MARCA';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        $texto = !empty($this->transp->getElementsByTagName("marca")->item(0)->nodeValue) ? $this->transp->getElementsByTagName("marca")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'B','C',0,'');
        //NÚMERO
        $x += $w2;
        $texto = 'NÚMERO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'T','L',1,'');
        $texto = $numero;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'B','C',0,'');
        //PESO BRUTO
        $x += $w2;
        $w3 = round($maxW*0.20,0);
        $texto = 'PESO BRUTO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'T','L',1,'');
        $texto = $pesoBruto;
        $texto = number_format($texto, 3, ",", ".");
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'B','R',0,'');
        //PESO LÍQUIDO
        $x += $w3;
        $w = $maxW -($w1+3*$w2+$w3);
        $texto = 'PESO LÍQUIDO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        $texto = $pesoLiquido;
        $texto = number_format($texto, 3, ",", ".");
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        return ($y+$h);
    } //fim __transporteDANFE

    /**
     * __descricaoProduto
     * Monta a string de descrição de cada Produto
     * @package NFePHP
     * @name __descricaoProduto
     * @version 1.0
     * @author Marcos Diez
     * @param DOM itemProd
     * @return string String com a descricao do produto
     */
    protected function __descricaoProduto( $itemProd ){
        $prod = $itemProd->getElementsByTagName('prod')->item(0);
        $ICMS = $itemProd->getElementsByTagName("ICMS")->item(0);
        $ivaTxt = '';
        if (!empty($ICMS)){
            $ivaTxt = !empty($ICMS->getElementsByTagName("pMVAST")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("pMVAST")->item(0)->nodeValue, 2, ",", ".") : '';
            if ($ivaTxt != ''){
                $ivaTxt = " IVA = $ivaTxt%";
            }
            $icmsStTxt = !empty($ICMS->getElementsByTagName("pICMSST")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("pICMSST")->item(0)->nodeValue, 2, ",", ".") : '';
            if ($icmsStTxt != ''){
                $ivaTxt .= " IcmsSt = $icmsStTxt%";
            }
        }
        $infAdProd = substr(!empty($itemProd->getElementsByTagName('infAdProd')->item(0)->nodeValue) ? $itemProd->getElementsByTagName('infAdProd')->item(0)->nodeValue : '',0,500);
        if (!empty($infAdProd)){
            $infAdProd = trim($infAdProd);
            $infAdProd .= ' ';
        }
        $medTxt='';
        $med = $prod->getElementsByTagName("med")->item(0);
        if( isset( $med ) ){
            $medTxt .= $this->__simpleGetValue( $med , 'nLote' , ' Lote: ');
            $medTxt .= $this->__simpleGetValue( $med , 'qLote' , ' Quant: ' );
            $medTxt .= $this->__simpleGetDate( $med , 'dFab'  , ' Fab: ' );
            $medTxt .= $this->__simpleGetDate( $med , 'dVal'  , ' Val: ' );
            $medTxt .= $this->__simpleGetValue( $med , 'vPMC'  , ' PMC: ' );
            if( $medTxt != '' ){
                $medTxt.= ' ';
            }
        }
        $texto = $prod->getElementsByTagName("xProd")->item(0)->nodeValue . ' ' . $infAdProd . $medTxt . $ivaTxt;
        $texto = str_replace( ";" , "\n" , $texto );
        return $texto;
    } //fim __descricaoProduto

    /**
     * __itensDANFE
     * Monta o campo de itens da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __itensDANFE
     * @version 1.8
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @param number $nInicio Número do item inicial
     * @param number $max Número do item final
     * @param number $hmax Altura máxima do campo de itens em mm
     * @return number Posição vertical final
     */
    protected function __itensDANFE($x,$y, &$nInicio,$hmax,$pag=0,$totpag=0) {
        $oldX = $x;
        $oldY = $y;
        $totItens = $this->det->length;
        //#####################################################################
        //DADOS DOS PRODUTOS / SERVIÇOS
        $texto = "DADOS DOS PRODUTOS / SERVIÇOS ";
        if( $this->orientacao == 'P' ){
            $w = $this->wPrint;
        }else{
            if( $nInicio < 2 ){ // primeira página
                $w = $this->wPrint - $this->wCanhoto;
            }else{ // páginas seguintes
                $w = $this->wPrint;
            }
        }
        $h = 4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        $y += 3;
        //desenha a caixa dos dados dos itens da NF
        $hmax += 1;
        $texto = '';
        $this->__textBox($x,$y,$w,$hmax);
        //##################################################################################
        // cabecalho LOOP COM OS DADOS DOS PRODUTOS
        //CÓDIGO PRODUTO
        $texto = "CÓDIGO PRODUTO";
        $w1 = round($w*0.09,0);
        $h = 4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w1,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w1, $y, $x+$w1, $y+$hmax);
        //DESCRIÇÃO DO PRODUTO / SERVIÇO
        $x += $w1;
        $w2 = round($w*0.31,0);
        $texto = 'DESCRIÇÃO DO PRODUTO / SERVIÇO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w2,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w2, $y, $x+$w2, $y+$hmax);
        //NCM/SH
        $x += $w2;
        $w3 = round($w*0.06,0);
        $texto = 'NCM/SH';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w3, $y, $x+$w3, $y+$hmax);
        //O/CST
        $x += $w3;
        $w4 = round($w*0.04,0);
        $texto = 'O/CST';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w4,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w4, $y, $x+$w4, $y+$hmax);
        //CFOP
        $x += $w4;
        $w5 = round($w*0.04,0);
        $texto = 'CFOP';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w5,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w5, $y, $x+$w5, $y+$hmax);
        //UN
        $x += $w5;
        $w6 = round($w*0.03,0);
        $texto = 'UN';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w6,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w6, $y, $x+$w6, $y+$hmax);
        //QUANT
        $x += $w6;
        $w7 = round($w*0.07,0);
        $texto = 'QUANT';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w7,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w7, $y, $x+$w7, $y+$hmax);
        //VALOR UNIT
        $x += $w7;
        $w8 = round($w*0.06,0);
        $texto = 'VALOR UNIT';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w8,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w8, $y, $x+$w8, $y+$hmax);
        //VALOR TOTAL
        $x += $w8;
        $w9 = round($w*0.06,0);
        $texto = 'VALOR TOTAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w9,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w9, $y, $x+$w9, $y+$hmax);
        //B.CÁLC ICMS
        $x += $w9;
        $w10 = round($w*0.06,0);
        $texto = 'B.CÁLC ICMS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w10,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w10, $y, $x+$w10, $y+$hmax);
        //VALOR ICMS
        $x += $w10;
        $w11 = round($w*0.06,0);
        $texto = 'VALOR ICMS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w11,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w11, $y, $x+$w11, $y+$hmax);
        //VALOR IPI
        $x += $w11;
        $w12 = round($w*0.05,0);
        $texto = 'VALOR IPI';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w12,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w12, $y, $x+$w12, $y+$hmax);
        //ALÍQ. ICMS
        $x += $w12;
        $w13 = round($w*0.035,0);
        $texto = 'ALÍQ. ICMS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w13,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($x+$w13, $y, $x+$w13, $y+$hmax);
        //ALÍQ. IPI
        $x += $w13;
        $w14 = $w-($w1+$w2+$w3+$w4+$w5+$w6+$w7+$w8+$w9+$w10+$w11+$w12+$w13);
        $texto = 'ALÍQ. IPI';
        $this->__textBox($x,$y,$w14,$h,$texto,$aFont,'C','C',0,'',FALSE);
        $this->pdf->Line($oldX, $y+$h+1, $oldX + $w, $y+$h+1);
        $y += 5;
        //##################################################################################
        // LOOP COM OS DADOS DOS PRODUTOS
        $i = 0;
        $hUsado = 4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'');
        foreach ($this->det as $d) {
            if ( $i >= $nInicio) {
                $thisItem = $this->det->item($i);
                //carrega as tags do item
                $prod = $thisItem->getElementsByTagName("prod")->item(0);
                $imposto = $this->det->item($i)->getElementsByTagName("imposto")->item(0);
                $ICMS = $imposto->getElementsByTagName("ICMS")->item(0);
                $IPI  = $imposto->getElementsByTagName("IPI")->item(0);
                $textoProduto = $this->__descricaoProduto( $thisItem );
                $linhaDescr = $this->__getNumLines($textoProduto,$w2,$aFont);
                $h = round(($linhaDescr * $this->pdf->FontSize)+1,0);
                $hUsado += $h;
                if ($hUsado > $hmax && $i < $totItens ){
                    //ultrapassa a capacidade para uma única página
                    //o restante dos dados serão usados nas proximas paginas
                    $nInicio = $i;
                    break;
                }
                //corrige o x
                $x=$oldX;
                //codigo do produto
                $texto = $prod->getElementsByTagName("cProd")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w1,$h,$texto ,$aFont,'T','C',0,'');
                $x += $w1;
                //DESCRIÇÃO
                if( $this->orientacao == 'P' ){
                    $this->__textBox($x,$y,$w2,$h,$textoProduto,$aFont,'T','L',0,'',FALSE);
                }else{
                    $this->__textBox($x,$y,$w2,$h,$textoProduto,$aFont,'T','C',0,'',FALSE);
                }
                $x += $w2;
                //NCM
                $texto = !empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue) ? $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
                $this->__textBox($x,$y,$w3,$h,$texto,$aFont,'T','C',0,'');
                $x += $w3;
                //CST
                if ( isset($ICMS) ){
                    $origem =  $this->__simpleGetValue( $ICMS , "orig" );
                    $cst =  $this->__simpleGetValue( $ICMS , "CST" );
                    $csosn =  $this->__simpleGetValue( $ICMS , "CSOSN" );                    
                    $texto = $origem.$cst.$csosn;
                    $this->__textBox($x,$y,$w4,$h,$texto,$aFont,'T','C',0,'');
                }
                //CFOP
                $x += $w4;
                $texto = $prod->getElementsByTagName("CFOP")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w5,$h,$texto,$aFont,'T','C',0,'');
                //Unidade
                $x += $w5;
                $texto = $prod->getElementsByTagName("uCom")->item(0)->nodeValue;
                $this->__textBox($x,$y,$w6,$h,$texto,$aFont,'T','C',0,'');
                $x += $w6;
                if( $this->orientacao == 'P' ){
                    $alinhamento = 'R';
                }else{
                    $alinhamento = 'C';
                }
                // QTDADE
                $texto = number_format($prod->getElementsByTagName("qCom")->item(0)->nodeValue, 4, ",", ".");
                $this->__textBox($x,$y,$w7,$h,$texto,$aFont,'T',$alinhamento,0,'');
                $x += $w7;
                // Valor Unitário
                $texto = number_format($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue, 4, ",", ".");
                $this->__textBox($x,$y,$w8,$h,$texto,$aFont,'T',$alinhamento,0,'');
                $x += $w8;
                // Valor do Produto
                $texto = number_format($prod->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
                $this->__textBox($x,$y,$w9,$h,$texto,$aFont,'T',$alinhamento,0,'');
                //Valor da Base de calculo
                $x += $w9;
                if ( isset($ICMS) ){
                    $texto = !empty($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", ".") : '0,00';
                    $this->__textBox($x,$y,$w10,$h,$texto,$aFont,'T',$alinhamento,0,'');
                }
                //Valor do ICMS
                $x += $w10;
                if (isset($ICMS)){
                    $texto = !empty($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", ".") : '0,00';
                    $this->__textBox($x,$y,$w11,$h,$texto,$aFont,'T',$alinhamento,0,'');
                }
                //Valor do IPI
                $x += $w11;
                if ( isset($IPI) ){
                    $texto = !empty($IPI->getElementsByTagName("vIPI")->item(0)->nodeValue) ? number_format($IPI->getElementsByTagName("vIPI")->item(0)->nodeValue, 2, ",", ".") :'';
                } else {
                    $texto = '';
                }
                $this->__textBox($x,$y,$w12,$h,$texto,$aFont,'T',$alinhamento,0,'');
                // %ICMS
                $x += $w12;
                if (isset($ICMS)){
                   $texto = !empty($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue) ? number_format($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue, 0, ",", ".") : '0,00';
                   $this->__textBox($x,$y,$w13,$h,$texto,$aFont,'T','C',0,'');
                }
                //%IPI
                $x += $w13;
                if ( isset($IPI) ){
                    $texto = !empty($IPI->getElementsByTagName("pIPI")->item(0)->nodeValue) ? number_format($IPI->getElementsByTagName("pIPI")->item(0)->nodeValue, 0, ",", ".") : '';
                } else {
                    $texto = '';
                }
                $this->__textBox($x,$y,$w14,$h,$texto,$aFont,'T','C',0,'');
                $y += $h;
                $i++;
            } else {
                $i++;
            }
        }
        return $oldY+$hmax;
    } // fim __itensDANFE

    /**
     * __issqnDANFE
     * Monta o campo de serviços do DANFE
     * @package NFePHP
     * @name __issqnDANFE ( retrato e paisagem )
     * @version 1.21
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function __issqnDANFE($x,$y){
        $oldX = $x;
        //#####################################################################
        //CÁLCULO DO ISSQN
        $texto = "CÁLCULO DO ISSQN";
        $w = $this->wPrint;
        $h = 7;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',0,'');
        //INSCRIÇÃO MUNICIPAL
        $y += 3;
        $w = round($this->wPrint*0.23,0);
        $texto = 'INSCRIÇÃO MUNICIPAL';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        //inscrição municipal
        $texto = !empty($this->emit->getElementsByTagName("im")->item(0)->nodeValue) ? $this->emit->getElementsByTagName("im")->item(0)->nodeValue : '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','L',0,'');
        //VALOR TOTAL DOS SERVIÇOS
        $x += $w;
        $texto = 'VALOR TOTAL DOS SERVIÇOS';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->ISSQNtot) ){
            $texto = !empty($this->ISSQNtot->getElementsByTagName("vServ")->item(0)->nodeValue) ? $this->ISSQNtot->getElementsByTagName("vServ")->item(0)->nodeValue : '';
            $texto = number_format($texto, 2, ",", ".");
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //BASE DE CÁLCULO DO ISSQN
        $x += $w;
        $texto = 'BASE DE CÁLCULO DO ISSQN';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->ISSQNtot) ){
            $texto = !empty($this->ISSQNtot->getElementsByTagName("vBC")->item(0)->nodeValue) ? $this->ISSQNtot->getElementsByTagName("vBC")->item(0)->nodeValue : '';
            $texto = !empty($text) ? number_format($texto, 2, ",", ".") : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        //VALOR TOTAL DO ISSQN
        $x += $w;
        if( $this->orientacao == 'P' ){
            $w = $this->wPrint - (3 * $w);
        }else{
            $w = $this->wPrint - (3 * $w)-$this->wCanhoto;
        }
        $texto = 'VALOR TOTAL DO ISSQN';
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        if ( isset($this->ISSQNtot) ){
            $texto = !empty($this->ISSQNtot->getElementsByTagName("vISS")->item(0)->nodeValue) ? $this->ISSQNtot->getElementsByTagName("vISS")->item(0)->nodeValue : '';
            $texto = !empty($texto) ? number_format($texto, 2, ",", ".") : '';
        } else {
            $texto = '';
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'B','R',0,'');
        return ($y+$h+1);
    } //fim __issqnDANFE

    /**
     *__dadosAdicionaisDANFE
     * Coloca o grupo de dados adicionais da NFe. ( retrato e paisagem )
     * @package NFePHP
     * @name __dadosAdicionaisDANFE
     * @version 1.2
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @author Guilherme Calabria Filho <guiga86 at gmail dot com>
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @param number $h altura do campo
     * @return number Posição vertical final
     */
    protected function __dadosAdicionaisDANFE($x,$y,$h){
        //##################################################################################
        //DADOS ADICIONAIS
        $texto = "DADOS ADICIONAIS";
        if( $this->orientacao == 'P' ){
              $w = $this->wPrint;
        }else{
              $w = $this->wPrint-$this->wCanhoto;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'B');
        $this->__textBox($x,$y,$w,8,$texto,$aFont,'T','L',0,'');
        //INFORMAÇÕES COMPLEMENTARES
        $texto = "INFORMAÇÕES COMPLEMENTARES";
        $y += 3;
        $w = $this->wAdic;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        //o texto com os dados adicionais foi obtido na função montaDANFE
        //e carregado em uma propriedade privada da classe
        //$this->wAdic com a largura do campo
        //$this->textoAdic com o texto completo do campo
        $y += 1;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'');
        $this->__textBox($x,$y+2,$w-2,$h-3, $this->textoAdic   ,$aFont,'T','L',0,'',FALSE);
        //RESERVADO AO FISCO
        $texto = "RESERVADO AO FISCO";
        $x += $w;
        $y -= 1;
        if( $this->orientacao == 'P' ){
            $w = $this->wPrint-$w;
        }else{
            $w = $this->wPrint-$w-$this->wCanhoto;
        }
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'B');
        $this->__textBox($x,$y,$w,$h,$texto,$aFont,'T','L',1,'');
        //inserir texto informando caso de contingência
        //1 – Normal – emissão normal;
        //2 – Contingência FS – emissão em contingência com impressão do DANFE em Formulário de Segurança;
        //3 – Contingência SCAN – emissão em contingência no Sistema de Contingência do Ambiente Nacional – SCAN;
        //4 – Contingência DPEC - emissão em contingência com envio da Declaração Prévia de Emissão em Contingência – DPEC;
        //5 – Contingência FS-DA - emissão em contingência com impressão do DANFE em Formulário de Segurança para Impressão de Documento Auxiliar de Documento Fiscal Eletrônico (FS-DA).
        $xJust = !empty($this->ide->getElementsByTagName("xJust")->item(0)->nodeValue) ? ' Justificativa: ' . $this->ide->getElementsByTagName("xJust")->item(0)->nodeValue : '';
        $dhCont = !empty($this->ide->getElementsByTagName("dhCont")->item(0)->nodeValue) ? ' Entrada em contingência : ' . $this->ide->getElementsByTagName("dhCont")->item(0)->nodeValue : '';
        $texto = '';
        switch($this->tpEmis){
            case 2:
                $texto = 'CONTINGÊNCIA FS' . $dhCont . $xJust;
                break;
            case 3:
                $texto = 'CONTINGÊNCIA SCAN' . $dhCont . $xJust;
                break;
            case 4:
                $texto = 'CONTINGÊNCIA DPEC' . $dhCont . $xJust;
                break;
            case 5:
                $texto = 'CONTINGÊNCIA FSDA' . $dhCont . $xJust;
                break;
        }
        $y += 2;
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'');
        $this->__textBox($x,$y,$w-2,$h-3,$texto,$aFont,'T','L',0,'',FALSE);
        return $y+$h;
    } //fim __dadosAdicionaisDANFE

    /**
     * __rodapeDANFE
     * Monta o rodape no final da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __rodapeDANFE
     * @version 1.1
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param number $xInic Posição horizontal canto esquerdo
     * @param number $yFinal Posição vertical final para impressão
     */
    protected function __rodapeDANFE($x,$y){
        $texto = "Impresso em  ". date('d/m/Y   H:i:s');
        $w = $this->wPrint-4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','L',0,'');
        $texto = "DanfeNFePHP ver. " . $this->version .  "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        //$this->__textBox($x, $y, $w, $h, $text, $aFont, $vAlign, $hAlign, $border, $link, $force, $hmax, $hini)
        $this->__textBox($x,$y,$w,4,$texto,$aFont,'T','R',0,'http://www.nfephp.org');
    } //fim __rodapeDANFE

    /**
     * __canhotoDANFE
     * Monta o canhoto da DANFE ( retrato e paisagem )
     * @package NFePHP
     * @name __canhotoDANFE
     * @version 1.2
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @author Guilherme Calabria Filho <guiga86 at gmail dot com>
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function __canhotoDANFE($x,$y) {
        $oldX = $x;
        $oldY = $y;
        //#################################################################################
        //canhoto
        //identificação do tipo de nf entrada ou saida
        $tpNF = $this->ide->getElementsByTagName('tpNF')->item(0)->nodeValue;
        if($tpNF == '0'){
            //NFe de Entrada
            $emitente = '';
            $emitente .= $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue . " - ";
            $emitente .= $this->enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue . ", ";
            $emitente .= $this->enderDest->getElementsByTagName("nro")->item(0)->nodeValue . " - ";
            $emitente .= !empty($this->enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $this->enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue . " " : '';
            $emitente .= $this->enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue . " ";
            $emitente .= $this->enderDest->getElementsByTagName("xMun")->item(0)->nodeValue . "-";
            $emitente .= $this->enderDest->getElementsByTagName("UF")->item(0)->nodeValue . "";
            $destinatario = $this->emit->getElementsByTagName("xNome")->item(0)->nodeValue . " ";
        } else {
            //NFe de Saída
            $emitente = $this->emit->getElementsByTagName("xNome")->item(0)->nodeValue . " ";
            $destinatario = '';
            $destinatario .= $this->dest->getElementsByTagName("xNome")->item(0)->nodeValue . " - ";
            $destinatario .= $this->enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue . ", ";
            $destinatario .= $this->enderDest->getElementsByTagName("nro")->item(0)->nodeValue . " - ";
            $destinatario .= !empty($this->enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $this->enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue . " " : '';
            $destinatario .= $this->enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue . " ";
            $destinatario .= $this->enderDest->getElementsByTagName("xMun")->item(0)->nodeValue . "-";
            $destinatario .= $this->enderDest->getElementsByTagName("UF")->item(0)->nodeValue . " ";
        }
        //identificação do sistema emissor
        //linha separadora do canhoto
        if( $this->orientacao == 'P' ){
            $w = round($this->wPrint * 0.81,0);
        }else{
            //linha separadora do canhoto - 238
            //posicao altura
            $y = $this->wPrint-85;
            //altura
            $w = $this->wPrint-85-24;
        }
        $h = 10;
        //desenha caixa
        $texto = '';
        $aFont = array('font'=>$this->fontePadrao,'size'=>7,'style'=>'');
        $aFontSmall = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
        if( $this->orientacao == 'P' ){
            $this->__textBox($x,$y,$w,$h,$texto,$aFont,'C','L',1,'',FALSE);
        }else{
            $this->__textBox90($x,$y,$w,$h,$texto,$aFont,'C','L',1,'',FALSE);
        }
        $numNF = str_pad($this->ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
        $serie = str_pad($this->ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
        $texto = "RECEBEMOS DE ";
        $texto .= $emitente;
        $texto .= " OS PRODUTOS E/OU SERVIÇOS CONSTANTES DA NOTA FISCAL ELETRÔNICA INDICADA AO LADO. EMISSÃO: ";
        $texto .= $this->__ymd2dmy($this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue) ." ";
        $texto .= "VALOR TOTAL: R$ ";
        $texto .= number_format($this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".") . " ";
        $texto .= "DESTINATÁRIO: ";
        $texto .= $destinatario;
        if( $this->orientacao == 'P' ){
            $this->__textBox($x,$y,$w-1,$h,$texto,$aFont,'C','L',0,'',FALSE);
            $x1 = $x + $w;
            $w1 = $this->wPrint - $w;
            $texto = "NF-e";
            $aFont = array('font'=>$this->fontePadrao,'size'=>14,'style'=>'B');
            $this->__textBox($x1,$y,$w1,18,$texto,$aFont,'T','C',0,'');
            $texto = "Nº. " . $this->__format($numNF,"###.###.###") . " \n";
            $texto .= "Série $serie";
            $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
            $this->__textBox($x1,$y,$w1,18,$texto,$aFont,'C','C',1,'');
            //DATA DO RECEBIMENTO
            $texto = "DATA DO RECEBIMENTO";
            $y += $h;
            $w2 = round($this->wPrint*0.17,0); //35;
            $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
            $this->__textBox($x,$y,$w2,8,$texto,$aFont,'T','L',1,'');
            //IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR
            $x += $w2;
            $w3 = $w-$w2;
            $texto = "IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR";
            $this->__textBox($x,$y,$w3,8,$texto,$aFont,'T','L',1,'');
            $x = $oldX;
            $y += 9;
            $this->pdf->DashedHLine($x,$y,$this->wPrint,0.1,80);
            $y += 2;
            return $y;
        } else {
            $x--;
            $x = $this->__textBox90($x,$y,$w-1,$h,$texto,$aFontSmall,'C','L',0,'',FALSE);
            //NUMERO DA NOTA FISCAL LOGO NFE
            $w1 = 16;
            $x1 = $oldX;
            $y = $oldY;
            $texto = "NF-e";
            $aFont = array('font'=>$this->fontePadrao,'size'=>14,'style'=>'B');
            $this->__textBox($x1,$y,$w1,18,$texto,$aFont,'T','C',0,'');
            $texto = "Nº.\n" . $this->__format($numNF,"###.###.###") . " \n";
            $texto .= "Série $serie";
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
            $this->__textBox($x1,$y,$w1,18,$texto,$aFont,'C','C',1,'');
            //DATA DO RECEBIMENTO
            $texto = "DATA DO RECEBIMENTO";
            $y = $this->wPrint-85;
            $x = 12;
            $w2 = round($this->wPrint*0.17,0); //35;
            $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'');
            $this->__textBox90($x,$y,$w2,8,$texto,$aFont,'T','L',1,'');
            //IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR
            $y -= $w2;
            $w3 = $w-$w2;
            $texto = "IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR";
            $aFont = array('font'=>$this->fontePadrao,'size'=>5.7,'style'=>'');
            $x = $this->__textBox90($x,$y,$w3,8,$texto,$aFont,'T','L',1,'');
            $this->pdf->DashedVLine(23,$oldY,0.1,$this->wPrint-20,67);
            return $x;
       }
    } //fim __canhotoDANFE

    /**
     * __geraInformacoesDaTagCompra
     * Devolve uma string contendo informação sobre as tag <compra><xNEmp>, <xPed> e <xCont> ou string vazia.
     * Aviso: Esta função não leva em consideração dados na tag xPed do item.
     *
     * @package NFePHP
     * @name __geraInformacoesDaTagCompra
     * @version 1.0
     * @author Marcos Diez
     * @return string com as informacoes dos pedidos.
     */
    protected function __geraInformacoesDaTagCompra(){
        $saida = "";
        if( isset($this->compra) ) {
            if( !empty($this->compra->getElementsByTagName("xNEmp")->item(0)->nodeValue)  ) {
                $saida .= " Nota de Empenho: " . $this->compra->getElementsByTagName("xNEmp")->item(0)->nodeValue;
            }
            if( !empty($this->compra->getElementsByTagName("xPed")->item(0)->nodeValue)  ) {
                $saida .= " Pedido: " . $this->compra->getElementsByTagName("xPed")->item(0)->nodeValue;
            }
            if( !empty($this->compra->getElementsByTagName("xCont")->item(0)->nodeValue)  ) {
                $saida .= " Contrato: " . $this->compra->getElementsByTagName("xCont")->item(0)->nodeValue;
            }
       }
       return $saida;
    } // fim __geraInformacoesDaTagCompra

    /**
     * __geraChaveAdicionalDeContingencia
     *
     * @package NFePHP
     * @name __geraChaveAdicionalDeContingencia
     * @version 1.0
     * @author Marcos Diez
     * @return string chave
     */
    protected function __geraChaveAdicionalDeContingencia() {
        //cUF tpEmis CNPJ vNF ICMSp ICMSs DD  DV
        // Quantidade de caracteres  02   01      14  14    01    01  02 01
        $forma  = "%02d%d%s%014d%01d%01d%02d";
        $cUF    = $this->ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $CNPJ   = "00000000000000" . $this->emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $CNPJ   = substr( $CNPJ , -14 );
        $vNF    = $this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue * 100;
        $vICMS  = $this->ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue;
        if( $vICMS > 0 ){
            $vICMS = 1;
        }
        $icmss  = $this->ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue;
        if( $icmss > 0 ){
            $icmss = 1;
        }
        $dd  = $this->ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
        $rpos = strrpos( $dd , '-' );
        $dd  = substr( $dd , $rpos +1 );
        $chave = sprintf( $forma ,$cUF , $this->tpEmis , $CNPJ , $vNF , $vICMS , $icmss , $dd );
        $chave = $chave . $this->__modulo11( $chave );
        return $chave;
    } //fim __geraChaveAdicionalDeContingencia

    /**
     * __geraInformacoesDasNotasReferenciadas
     * Devolve uma string contendo informação sobre as notas referenciadas. Suporta N notas, eletrônicas ou não
     * Exemplo: NFe Ref.: série: 01 número: 01 emit: 11.111.111/0001-01 em 10/2010 [0000 0000 0000 0000 0000 0000 0000 0000 0000 0000 0000]
     * @package NFePHP
     * @name __geraInformacoesDasNotasReferenciadas
     * @version 1.0
     * @author Marcos Diez
     * @return string Informacoes a serem adicionadas no rodapé sobre notas referenciadas.
     */
    protected function __geraInformacoesDasNotasReferenciadas(){
        $formaNfeRef = "\r\nNFe Ref.: série:%d número:%d emit:%s em %s [%s]";
        $formaNfRef = "\r\nNF  Ref.: série:%d numero:%d emit:%s em %s modelo: %d";
        $saida="";
        $nfRef = $this->ide->getElementsByTagName('NFref')->item(0);
        if( empty( $nfRef ) ){
            return $saida;
        }
        $refNFe = $nfRef->getElementsByTagName('refNFe');
        foreach ( $refNFe as $chave_acessoRef) {
            $chave_acesso = $chave_acessoRef->nodeValue;
            $chave_acessoF = $this->__format( $chave_acesso, $this->formatoChave );
            $data = substr($chave_acesso,4,2) . "/20" . substr($chave_acesso,2,2);
            $cnpj = $this->__format( substr($chave_acesso,6,14) , "##.###.###/####-##" );
            $serie  = substr($chave_acesso,22,3);
            $numero = substr($chave_acesso,25,9);
            $saida .= sprintf( $formaNfeRef , $serie, $numero , $cnpj , $data , $chave_acessoF );
        }
        $refNF = $nfRef->getElementsByTagName('refNF');
        foreach ( $refNF as $umaRefNFe) {
            $data = $umaRefNFe->getElementsByTagName('AAMM')->item(0)->nodeValue;
            $cnpj = $umaRefNFe->getElementsByTagName('CNPJ')->item(0)->nodeValue;
            $mod = $umaRefNFe->getElementsByTagName('mod')->item(0)->nodeValue;
            $serie = $umaRefNFe->getElementsByTagName('serie')->item(0)->nodeValue;
            $numero = $umaRefNFe->getElementsByTagName('nNF')->item(0)->nodeValue;
            $data = substr($data,2,2) . "/20" . substr($data,0,2);
            $cnpj = $this->__format( $cnpj , "##.###.###/####-##" );
            $saida .= sprintf( $formaNfRef , $serie, $numero , $cnpj , $data , $mod );
        }
        return $saida;
    } // fim __geraInformacoesDasNotasReferenciadas

} //fim da classe DanfeNFePHP
?>