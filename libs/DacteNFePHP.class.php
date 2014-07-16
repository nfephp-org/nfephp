<?php

/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela
 * Fundação para o Software Livre, na versão 3 da licença, ou qualquer versão
 * posterior.
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
 * PHP version 5
 *
 * @category  Impressão
 * @package   NFePHP
 * @name      DacteNFePHP.class.php
 * @author    Roberto L. Machado <linux.rlm@gmail.com>
 * @copyright 2009-2013 &copy; NFePHP
 * @license   GNU/GPL v.3 or GNU/LGPL v.3
 * @version   GIT: 1.2.14
 * @link      http://www.nfephp.org/
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *          Fernando Mertins <fernando dot mertins at gmail dot com>
 *          Joao Eduardo Silva Correa <jcorrea at sucden dot com dot br>
 *          Lucas Dias                <diasnlucas at gmail dot com>
 *          Marcos Diez               <marcos at unitron dot com dot br>
 *          Rodrigo Rysdyk            <rodrigo_rysdyk at hotmail dot com>
 *          Roberto Spadim            <roberto at spadim dot com dot br>
 *
 *
 */

//namespace nfephp\DacteNFePHP;

//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT'))
{
    define('PATH_ROOT', dirname(dirname(__file__)) . DIRECTORY_SEPARATOR);
}
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH'))
{
    define('FPDF_FONTPATH', 'font/');
}
//situação externa do documento
if (!defined('NFEPHP_SITUACAO_EXTERNA_CANCELADA'))
{
    define('NFEPHP_SITUACAO_EXTERNA_CANCELADA', 1);
    define('NFEPHP_SITUACAO_EXTERNA_DENEGADA', 2);
    define('NFEPHP_SITUACAO_EXTERNA_DPEC', 3);
    define('NFEPHP_SITUACAO_EXTERNA_NONE', 0);
}
//classe extendida da classe FPDF para montagem do arquivo pfd
require_once 'PdfNFePHP.class.php';
require_once 'CommonNFePHP.class.php';
require_once 'DocumentoNFePHP.interface.php';

/**
 * Classe Dacte
 *
 * @category Impressão
 * @package  NFePHP
 * @author   Roberto L. Machado <linux.rlm@gmail.com>
 * @license  GPL ou LGPL
 * @link     http://www.nfephp.org
 */
class DacteNFePHP extends CommonNFePHP implements DocumentoNFePHP
{
    //publicas
    public $logoAlign = 'C'; //alinhamento do logo
    public $yDados = 0;
    public $situacao_externa = 0;
    public $numero_registro_dpec = '';
    //privadas
    protected $pdf; // objeto fpdf()
    protected $xml; // string XML NFe
    protected $logomarca = ''; // path para logomarca em jpg
    protected $errMsg = ''; // mensagens de erro
    protected $errStatus = false; // status de erro TRUE um erro ocorreu FALSE
    //sem erros
    protected $orientacao = 'P'; //orientação da DACTE P-Retrato ou L-Paisagem
    protected $papel = 'A4'; //formato do papel
    protected $destino = 'I'; //destivo do arquivo pdf I-borwser,
    //S-retorna o arquivo,
    //D-força download,
    //F-salva em arquivo local
    protected $pdfDir = ''; //diretorio para salvar o pdf com a opção de
    //destino = F
    protected $fontePadrao = 'Times'; //Nome da Fonte para gerar o DACTE
    protected $version = '1.2.13';
    protected $wPrint; //largura imprimivel
    protected $hPrint; //comprimento imprimivel
    //objetos DOM da CTe
    protected $dom;
    protected $infCte;
    protected $infCteComp;
    protected $chaveCTeRef;
    protected $tpCTe;
    protected $ide;
    protected $emit;
    protected $enderEmit;
    protected $rem;
    protected $enderReme;
    protected $dest;
    protected $enderDest;
    protected $exped;
    protected $enderExped;
    protected $receb;
    protected $enderReceb;
    protected $infCarga;
    protected $infQ;
    protected $seg;
    protected $modal;
    protected $rodo;
    protected $moto;
    protected $veic;
    protected $ferrov;
    protected $Comp;
    protected $infNF;
    protected $infOutros;
    protected $infNFe;
    protected $compl;
    protected $ICMS;
    protected $imp;
    protected $toma4;
    protected $toma03;
    protected $tpEmis;
    protected $tpImp; //1-Retrato/ 2-Paisagem
    protected $tpAmb;
    protected $vPrest;
    protected $wAdic = 150;
    protected $textoAdic = '';
    protected $debugMode = 2;
    protected $formatPadrao;
    protected $formatNegrito;


    /**
     * __construct
     *
     * @param string $docXML      Arquivo XML da CTe
     * @param string $sOrientacao (Opcional) Orientação da impressão P ou L
     * @param string $sPapel      Tamanho do papel (Ex. A4)
     * @param string $sPathLogo   Caminho para o arquivo do logo
     * @param string $sDestino    Estabelece a direção do envio do documento PDF
     * @param string $sDirPDF     Caminho para o diretorio de armaz. dos PDF
     * @param string $fonteDACTE  Nome da fonte a ser utilizada
     * @param number $mododebug   0-Não 1-Sim e 2-nada (2 default)
     */
    public function __construct($docXML = '', $sOrientacao = '', $sPapel = '', $sPathLogo = '', $sDestino = 'I', $sDirPDF = '', $fonteDACTE = '', $mododebug =
        2)
    {
        if (is_numeric($mododebug))
        {
            $this->debugMode = $mododebug;
        }
        if ($mododebug == 1)
        {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }
        if ($mododebug == 0)
        {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        $this->orientacao = $sOrientacao;
        $this->papel = $sPapel;
        $this->pdf = '';
        $this->xml = $docXML;
        $this->logomarca = $sPathLogo;
        $this->destino = $sDestino;
        $this->pdfDir = $sDirPDF;
        // verifica se foi passa a fonte a ser usada
        if (!empty($fonteDACTE))
        {
            $this->fontePadrao = $fonteDACTE;
        }
        $this->formatPadrao = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => '');
        $this->formatNegrito = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => 'B');
        //se for passado o xml
        if (!empty($this->xml))
        {
            $this->dom = new DomDocument;
            $this->dom->loadXML($this->xml);
            $this->cteProc = $this->dom->getElementsByTagName("cteProc")->item(0);
            $this->infCte = $this->dom->getElementsByTagName("infCte")->item(0);
            $this->ide = $this->dom->getElementsByTagName("ide")->item(0);
            $this->emit = $this->dom->getElementsByTagName("emit")->item(0);
            $this->enderEmit = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->rem = $this->dom->getElementsByTagName("rem")->item(0);
            $this->enderReme = $this->dom->getElementsByTagName("enderReme")->item(0);
            $this->dest = $this->dom->getElementsByTagName("dest")->item(0);
            $this->enderDest = $this->dom->getElementsByTagName("enderDest")->item(0);
            $this->exped = $this->dom->getElementsByTagName("exped")->item(0);
            $this->enderExped = $this->dom->getElementsByTagName("enderExped")->item(0);
            $this->receb = $this->dom->getElementsByTagName("receb")->item(0);
            $this->enderReceb = $this->dom->getElementsByTagName("enderReceb")->item(0);
            $this->infCarga = $this->dom->getElementsByTagName("infCarga")->item(0);
            $this->infQ = $this->dom->getElementsByTagName("infQ");
            $this->seg = $this->dom->getElementsByTagName("seg")->item(0);
            $this->rodo = $this->dom->getElementsByTagName("rodo")->item(0);
            $this->lota = $this->__simpleGetValue($this->rodo, "lota");
            $this->moto = $this->dom->getElementsByTagName("moto")->item(0);
            $this->veic = $this->dom->getElementsByTagName("veic");
            $this->ferrov = $this->dom->getElementsByTagName("ferrov")->item(0);
            // adicionar outros modais
            $this->infCteComp = $this->dom->getElementsByTagName("infCteComp")->item(0);
            $this->chaveCTeRef = $this->__simpleGetValue($this->infCteComp, "chave");
            $this->vPrest = $this->dom->getElementsByTagName("vPrest")->item(0);
            $this->Comp = $this->dom->getElementsByTagName("Comp");
            $this->infNF = $this->dom->getElementsByTagName("infNF");
            $this->infOutros = $this->dom->getElementsByTagName("infOutros");
            $this->infNFe = $this->dom->getElementsByTagName("infNFe");
            $this->compl = $this->dom->getElementsByTagName("compl");
            $this->ICMS = $this->dom->getElementsByTagName("ICMS")->item(0);
            $this->ICMSSN = $this->dom->getElementsByTagName("ICMSSN")->item(0);
            $this->imp = $this->dom->getElementsByTagName("imp")->item(0);
            $this->toma4 = $this->dom->getElementsByTagName("toma4")->item(0);
            $this->toma03 = $this->dom->getElementsByTagName("toma03")->item(0);
            $tomador = $this->__simpleGetValue($this->toma03, "toma");
            //0-Remetente;1-Expedidor;2-Recebedor;3-Destinatário;4-Outros
            switch ($tomador)
            {
                case '0':
                    $this->toma = $this->rem;
                    $this->enderToma = $this->enderReme;
                    break;
                case '1':
                    $this->toma = $this->exped;
                    $this->enderToma = $this->enderExped;
                    break;
                case '2':
                    $this->toma = $this->receb;
                    $this->enderToma = $this->enderReceb;
                    break;
                case '3':
                    $this->toma = $this->dest;
                    $this->enderToma = $this->enderDest;
                    break;
                default:
                    $this->toma = $this->toma4;
                    $this->enderToma = $this->__simpleGetValue($this->toma4, "enderToma");
                    break;
            }
            $seguro = $this->__simpleGetValue($this->seg, "respSeg");
            switch ($seguro)
            {
                case '0':
                    $this->respSeg = 'Remetente';
                    break;
                case '1':
                    $this->respSeg = 'Expedidor';
                    break;
                case '2':
                    $this->respSeg = 'Recebedor';
                    break;
                case '3':
                    $this->respSeg = 'Destinatário';
                    break;
                case '4':
                    $this->respSeg = 'Emitente';
                    break;
                case '5':
                    $this->respSeg = 'Tomador';
                    break;
                default:
                    $this->respSeg = '';
                    break;
            }
            $this->tpEmis = $this->__simpleGetValue($this->ide, "tpEmis");
            $this->tpImp = $this->__simpleGetValue($this->ide, "tpImp");
            $this->tpAmb = $this->__simpleGetValue($this->ide, "tpAmb");
            $this->tpCTe = $this->__simpleGetValue($this->ide, "tpCTe");
            $this->protCTe = $this->dom->getElementsByTagName("protCTe")->item(0);
        }
    } //fim construct

    /**
     * simpleConsistencyCheck
     *
     * @return bool Retorna se o documenento se parece com um DACTE ( condicao necessaria porem nao suficiente )
     */
    public function simpleConsistencyCheck()
    {
        if (1 == 2 || $this->xml == null || $this->infCte == null || $this->ide == null)
        {
            return false;
        }

        return true;
    } //fim simpleConsistencyCheck

    /**
     * monta
     *
     * @param  string $orientacao L ou P
     * @param  string $papel      A4
     * @param  string $logoAlign  C, L ou R
     * @return string montagem
     */
    public function monta($orientacao = '', $papel = 'A4', $logoAlign = 'C', $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE, $CLASSE_PDF = false, $DPEC_NUMERO_REGISTRO =
        '')
    {
        return $this->montaDACTE($orientacao, $papel, $logoAlign, $situacao_externa, $CLASSE_PDF);
    }

    /**
     * printDocument
     *
     * @param  type $nome
     * @param  type $destino
     * @param  type $printer
     * @return type
     */
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDACTE($nome, $destino, $printer);
    }

    protected function __cteCancelada()
    {
        /* NÃO ERA NECESSÁRIO ESSA FUNÇÃO POIS SÓ SE USA 1 VEZ NO ARQUIVO INTEIRO) */
        $cStat = $this->__simpleGetValue($this->cteProc, "cStat");
        return $cStat == '101' || $cStat == '135' || $this->situacao_externa == NFEPHP_SITUACAO_EXTERNA_CANCELADA;
    }

    protected function __cteDPEC()
    {
        return $this->situacao_externa == NFEPHP_SITUACAO_EXTERNA_DPEC && $this->numero_registro_dpec != '';
    }

    protected function __cteDenegada()
    {
        /* NÃO ERA NECESSÁRIO ESSA FUNÇÃO POIS SÓ SE USA 1 VEZ NO ARQUIVO INTEIRO) */
        $cStat = $this->__simpleGetValue($this->cteProc, "cStat");
        return $cStat == '110' || $cStat == '301' || $cStat == '302' || $this->situacao_externa == NFEPHP_SITUACAO_EXTERNA_DENEGADA;
    }

    /**
     * montaDACTE
     * Esta função monta a DACTE conforme as informações fornecidas para a classe
     * durante sua construção.
     * A definição de margens e posições iniciais para a impressão são estabelecidas no
     * pelo conteúdo da funçao e podem ser modificados.
     *
     * @param  string $orientacao (Opcional) Estabelece a orientação da
     *                impressão (ex. P-retrato), se nada for fornecido será
     *                usado o padrão da NFe
     * @param  string $papel      (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID da NFe numero de 44 digitos extraido do arquivo XML
     */
    public function montaDACTE($orientacao = '', $papel = 'A4', $logoAlign = 'C', $situacao_externa = NFEPHP_SITUACAO_EXTERNA_NONE, $CLASSE_PDF = false, $DPEC_NUMERO_REGISTRO =
        '')
    {
        //se a orientação estiver em branco utilizar o padrão estabelecido na NF
        if ($orientacao == '')
        {
            if ($this->tpImp == '1')
            {
                $orientacao = 'P';
            } else
            {
                $orientacao = 'P'; //TO-DO L
            }
        }
        $this->orientacao = $orientacao;
        $this->__adicionaLogoPeloCnpj();
        $this->papel = $papel;
        $this->logoAlign = $logoAlign;
        $this->situacao_externa = $situacao_externa;
        $this->numero_registro_dpec = $DPEC_NUMERO_REGISTRO;
        //instancia a classe pdf
        if ($CLASSE_PDF !== false)
        {
            $this->pdf = $CLASSE_PDF;
        } else
        {
            $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        }
        if ($this->orientacao == 'P')
        {
            // margens do PDF
            $margSup = 2;
            $margEsq = 2;
            $margDir = 2;
            // posição inicial do relatorio
            $xInic = 1;
            $yInic = 1;
            if ($papel == 'A4')
            { //A4 210x297mm
                $maxW = 210;
                $maxH = 297;
            }
        } else
        {
            // margens do PDF
            $margSup = 3;
            $margEsq = 3;
            $margDir = 3;
            // posição inicial do relatorio
            $xInic = 5;
            $yInic = 5;
            if ($papel == 'A4')
            { //A4 210x297mm
                $maxH = 210;
                $maxW = 297;
                $this->wCanhoto = 25;
            }
        }
        //total inicial de paginas
        $totPag = 1;
        //largura imprimivel em mm
        $this->wPrint = $maxW - ($margEsq + $xInic);
        //comprimento imprimivel em mm
        $this->hPrint = $maxH - ($margSup + $yInic);
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
        //calculo do numero de páginas ???
        $totPag = 1;
        //montagem da primeira página
        $pag = 1;
        $x = $xInic;
        $y = $yInic;
        //coloca o cabeçalho
        $y = $this->canhotoDACTE($x, $y);
        $y += 19;
        $r = $this->cabecalhoDACTE($x, $y, $pag, $totPag);
        $y += 70;
        $r = $this->remetenteDACTE($x, $y);
        $x = $this->wPrint * 0.5 + 2;
        $r = $this->destinatarioDACTE($x, $y);
        $y += 19;
        $x = $xInic;
        $r = $this->expedidorDACTE($x, $y);
        $x = $this->wPrint * 0.5 + 2;
        $r = $this->recebedorDACTE($x, $y);
        $y += 19;
        $x = $xInic;
        $r = $this->tomadorDACTE($x, $y);

        if ($this->tpCTe == '0') //Normal
        {

            $y += 10;
            $x = $xInic;
            $r = $this->descricaoCargaDACTE($x, $y);
            $y += 17;
            $x = $xInic;
            $r = $this->compValorDACTE($x, $y);
            $y += 25;
            $x = $xInic;
            $r = $this->impostosDACTE($x, $y);
            $y += 13;
            $x = $xInic;
            $r = $this->docOrigDACTE($x, $y);
            if ($this->modal == '1')
            {
                if ($this->lota == 1)
                {
                    $y += 24.95;
                } else
                {
                    $y += 53;
                }
            } else
            {
                $y += 24.95;
            }
            $x = $xInic;
            $r = $this->obsDACTE($x, $y);
            switch ($this->modal)
            {
                case '1':
                    $y += 17.9;
                    $x = $xInic;
                    $r = $this->modalRodDACTE($x, $y);
                    break;
                case '2':
                    $y += 17.9;
                    $x = $xInic;
                    $r = $this->__modalAereoDACTE($x, $y);
                    break;
                case '3':
                    $y += 17.9;
                    $x = $xInic;
                    $r = $this->__modalAquaviarioDACTE($x, $y);
                    break;
                case '4':
                    $y += 17.9;
                    $x = $xInic;
                    $r = $this->modalFerrDACTE($x, $y);
                    break;
                case '5':
                    $y += 17.9;
                    $x = $xInic;
                    $r = $this->__modalDutoviarioDACTE($x, $y);
                    break;
            }
            if ($this->modal == '1')
            {
                if ($this->lota == 1)
                {
                    $y += 37;
                } else
                {
                    $y += 8.9;
                }
            } else
            {
                $y += 37;
            }
        } else //Complementado
        {
            $y += 10;
            $x = $xInic;
            $r = $this->__documentosComplementadosDACTE($x, $y);

            $y += 80;
            $x = $xInic;
            $r = $this->compValorDACTE($x, $y);

            $y += 25;
            $x = $xInic;
            $r = $this->impostosDACTE($x, $y);

            $y += 13;

            $x = $xInic;
            $r = $this->obsDACTE($x, $y);

            $y += 15;

        }
        $x = $xInic;
        $r = $this->dadosAdicDACTE($x, $y, $pag, $totPag);
        //coloca o rodapé da página
        if ($this->orientacao == 'P')
        {
            $this->rodapeDACTE(2, $this->hPrint - 2);
        } else
        {
            $this->rodapeDACTE($xInic, $this->hPrint + 2.3);
        }
        //retorna o ID na CTe
        if ($CLASSE_PDF !== false)
        {
            $aR = array('id' => str_replace('CTe', '', $this->infCte->getAttribute("Id")), 'classe_PDF' => $this->pdf);
            return $aR;
        } else
        {
            return str_replace('CTe', '', $this->infCte->getAttribute("Id"));
        }
    } //fim da função montaDACTE

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
     *
     * @param  string $nome    Path completo com o nome do arquivo pdf
     * @param  string $destino Direção do envio do PDF
     * @param  string $printer Identificação da impressora no sistema
     * @return string Caso o destino seja S o pdf é retornado como uma string
     * @todo Rotina de impressão direta do arquivo pdf criado
     */
    public function printDACTE($nome = '', $destino = 'I', $printer = '')
    {
        $arq = $this->pdf->Output($nome, $destino);
        if ($destino == 'S')
        {
            //aqui pode entrar a rotina de impressão direta
        }

        return $arq;
    } //fim função printDACTE

    /**
     * cabecalhoDACTE
     * Monta o cabelhalho da DACTE ( retrato e paisagem )
     *
     * @param  number $x      Posição horizontal inicial, canto esquerdo
     * @param  number $y      Posição vertical inicial, canto superior
     * @param  number $pag    Número da Página
     * @param  number $totPag Total de páginas
     * @return number Posição vertical final
     */
    protected function cabecalhoDACTE($x = 0, $y = 0, $pag = '1', $totPag = '1')
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            if ($pag == 1)
            { // primeira página
                $maxW = $this->wPrint - $this->wCanhoto;
            } else
            { // páginas seguintes
                $maxW = $this->wPrint;
            }
        }
        //##################################################################
        //coluna esquerda identificação do emitente
        $w = round($maxW * 0.42);
        if ($this->orientacao == 'P')
        {
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 6,
                'style' => '');
        } else
        {
            $aFont = $this->formatNegrito;
        }
        $w1 = $w;
        $h = 35;
        $oldY += $h;
        //desenha a caixa
        $this->__textBox($x, $y, $w + 2, $h + 1);
        // coloca o logo
        if (is_file($this->logomarca))
        {
            $logoInfo = getimagesize($this->logomarca);
            //largura da imagem em mm
            $logoWmm = ($logoInfo[0] / 72) * 25.4;
            //altura da imagem em mm
            $logoHmm = ($logoInfo[1] / 72) * 25.4;
            if ($this->logoAlign == 'L')
            {
                $nImgW = round($w / 3, 0);
                $nImgH = round($logoHmm * ($nImgW / $logoWmm), 0);
                $xImg = $x + 1;
                $yImg = round(($h - $nImgH) / 2, 0) + $y;
                //estabelecer posições do texto
                $x1 = round($xImg + $nImgW + 1, 0);
                $y1 = round($h / 3 + $y, 0);
                $tw = round(2 * $w / 3, 0);
            }
            if ($this->logoAlign == 'C')
            {
                $nImgH = round($h / 3, 0);
                $nImgW = round($logoWmm * ($nImgH / $logoHmm), 0);
                $xImg = round(($w - $nImgW) / 2 + $x, 0);
                $yImg = $y + 3;
                $x1 = $x;
                $y1 = round($yImg + $nImgH + 1, 0);
                $tw = $w;
            }
            if ($this->logoAlign == 'R')
            {
                $nImgW = round($w / 3, 0);
                $nImgH = round($logoHmm * ($nImgW / $logoWmm), 0);
                $xImg = round($x + ($w - (1 + $nImgW)), 0);
                $yImg = round(($h - $nImgH) / 2, 0) + $y;
                $x1 = $x;
                $y1 = round($h / 3 + $y, 0);
                $tw = round(2 * $w / 3, 0);
            }
            $this->pdf->Image($this->logomarca, $xImg, $yImg, $nImgW, $nImgH, 'jpeg');
        } else
        {
            $x1 = $x;
            $y1 = round($h / 3 + $y, 0);
            $tw = $w;
        }
        //Nome emitente
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 9,
            'style' => 'B');
        $texto = $this->__simpleGetValue($this->emit, "xNome");
        $this->__textBox($x1, $y1, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');
        //endereço
        $y1 = $y1 + 3;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => '');
        $fone = $this->formatFone($this->enderEmit);
        $lgr = $this->__simpleGetValue($this->enderEmit, "xLgr");
        $nro = $this->__simpleGetValue($this->enderEmit, "nro");
        $cpl = $this->__simpleGetValue($this->enderEmit, "xCpl");
        $bairro = $this->__simpleGetValue($this->enderEmit, "xBairro");
        $CEP = $this->__simpleGetValue($this->enderEmit, "CEP");
        $CEP = $this->__format($CEP, "#####-###");
        $mun = $this->__simpleGetValue($this->enderEmit, "xMun");
        $UF = $this->__simpleGetValue($this->enderEmit, "UF");
        $xPais = $this->__simpleGetValue($this->enderEmit, "xPais");
        $texto = $lgr . "," . $nro . "\n" . $bairro . " - " . $CEP . " - " . $mun . " - " . $UF . " " . $xPais . "\n  Fone/Fax: " . $fone;
        $this->__textBox($x1 - 5, $y1 + 2, $tw + 5, 8, $texto, $aFont, 'T', 'C', 0, '');
        //CNPJ/CPF IE
        $cpfCnpj = $this->formatCNPJCPF($this->emit);
        $ie = $this->__simpleGetValue($this->emit, "IE");
        $texto = 'CNPJ/CPF:  ' . $cpfCnpj . '     Insc.Estadual: ' . $ie;
        $this->__textBox($x1 - 1, $y1 + 12, $tw + 5, 8, $texto, $aFont, 'T', 'C', 0, '');
        //outra caixa
        $h1 = 17.5;
        $y1 = $y + $h + 1;
        $this->__textBox($x, $y1, $w + 2, $h1);
        //TIPO DO CT-E
        $texto = 'TIPO DO CTE';
        $wa = 37;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x, $y1, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '');
        $tpCTe = $this->__simpleGetValue($this->ide, "tpCTe");
        //0 - CT-e Normal,1 - CT-e de Complemento de Valores,
        //2 - CT-e de Anulação de Valores,3 - CT-e Substituto
        switch ($tpCTe)
        {
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
                $texto = 'ERRO' . $tpCTe . $tpServ;
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y1 + 3, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '', false);
        //TIPO DO SERVIÇO
        $texto = 'TIPO DO SERVIÇO';
        $wb = 36;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x + $wa + 4.5, $y1, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '');
        $tpServ = $this->__simpleGetValue($this->ide, "tpServ");
        //0 - Normal;1 - Subcontratação;2 - Redespacho;3 - Redespacho Intermediário
        switch ($tpServ)
        {
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
                break;
            default:
                $texto = 'ERRO' . $tpServ;
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x + $wa + 4.5, $y1 + 3, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '', false);
        $this->pdf->Line($w * 0.5, $y1, $w * 0.5, $y1 + $h1);
        //TOMADOR DO SERVIÇO
        $texto = 'TOMADOR DO SERVIÇO';
        $wc = 37;
        $y2 = $y1 + 8;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x, $y2, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '');

        $this->pdf->Line($x, $y1 + 8, $w + 3, $y1 + 8);
        $toma = $this->__simpleGetValue($this->ide, "toma");
        //0-Remetente;1-Expedidor;2-Recebedor;3-Destinatário;4 - Outros
        switch ($toma)
        {
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
                $texto = 'ERRO' . $toma;
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y2 + 3, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '', false);
        //FORMA DE PAGAMENTO
        $texto = 'FORMA DE PAGAMENTO';
        $wd = 36;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x + $wa + 4.5, $y2, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '');
        $forma = $this->__simpleGetValue($this->ide, "forPag");
        //0 - Pago;1 - A pagar;2 - outros
        switch ($forma)
        {
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
                $texto = 'ERRO' . $forma;
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x + $wa + 4.5, $y2 + 3, $w * 0.5, $h1, $texto, $aFont, 'T', 'C', 0, '', false);
        //##################################################################
        //coluna direita
        $x += $w + 2;
        $w = round($maxW * 0.335);
        $w1 = $w;
        $h = 11;
        $this->__textBox($x, $y, $w + 2, $h + 1);
        $texto = "DACTE";
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => 'B');
        $this->__textBox($x, $y + 1, $w, $h, $texto, $aFont, 'T', 'C', 0, '');
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $texto = "Documento Auxiliar do Conhecimento\nde Transporte Eletrônico";
        $h = 10;
        $this->__textBox($x, $y + 4, $w, $h, $texto, $aFont, 'T', 'C', 0, '', false);
        //$aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $x1 = $x + $w + 2;
        $w = round($maxW * 0.22, 0);
        $w2 = $w;
        $h = 11;
        $this->__textBox($x1, $y, $w + 0.5, $h + 1);
        $texto = "MODAL";
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x1, $y + 1, $w, $h, $texto, $aFont, 'T', 'C', 0, '');
        //01-Rodoviário; //02-Aéreo; //03-Aquaviário; //04-Ferroviário;//05-Dutoviário
        $modal = $this->__simpleGetValue($this->ide, "modal");
        $this->modal = $modal;
        switch ($modal)
        {
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
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => 'B');
        $this->__textBox($x1, $y + 5, $w, $h, $texto, $aFont, 'T', 'C', 0, '');
        //outra caixa
        $y += 12;
        $h = 9;
        $w = $w1 + $w2 + 2;
        $this->__textBox($x, $y, $w + 0.5, $h + 1);
        //modelo
        $wa = 12;
        $xa = $x;
        $texto = 'MODELO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($xa, $y + 1, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = $this->__simpleGetValue($this->ide, "mod");
        $aFont = $this->formatNegrito;
        $this->__textBox($xa, $y + 5, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->pdf->Line($x + $wa, $y, $x + $wa, $y + $h + 1);
        //serie
        $xa += $wa;
        $texto = 'SÉRIE';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($xa, $y + 1, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = $this->__simpleGetValue($this->ide, "serie");
        $aFont = $this->formatNegrito;
        $this->__textBox($xa, $y + 5, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->pdf->Line($xa + $wa, $y, $xa + $wa, $y + $h + 1);
        //numero
        $xa += $wa;
        $wa = 20;
        $texto = 'NÚMERO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($xa, $y + 1, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = $this->__simpleGetValue($this->ide, "nCT");
        $aFont = $this->formatNegrito;
        $this->__textBox($xa, $y + 5, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->pdf->Line($xa + $wa, $y, $xa + $wa, $y + $h + 1);
        //folha
        $xa += $wa;
        $wa = 12;
        $texto = 'FL';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($xa, $y + 1, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = '1/1';
        $aFont = $this->formatNegrito;
        $this->__textBox($xa, $y + 5, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->pdf->Line($xa + $wa, $y, $xa + $wa, $y + $h + 1);
        //data  hora de emissão
        $xa += $wa;
        $wa = 30;
        $texto = 'DATA E HORA DE EMISSÃO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($xa, $y + 1, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = !empty($this->ide->getElementsByTagName("dhEmi")->item(0)->nodeValue) ? date('d/m/Y H:i:s', $this->__convertTime($this->__simpleGetValue($this->
            ide, "dhEmi"))) : '';
        $aFont = $this->formatNegrito;
        $this->__textBox($xa, $y + 5, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->pdf->Line($xa + $wa, $y, $xa + $wa, $y + $h + 1);
        //ISUF
        $xa += $wa;
        $wa = 32;
        $texto = 'INSC. SUFRAMA DO DESTINATÁRIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($xa, $y + 1, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = $this->__simpleGetValue($this->dest, "ISUF");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($xa, $y + 5, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        //outra caixa
        $y += $h + 1;
        $h = 23;
        $h1 = 14;
        $this->__textBox($x, $y, $w + 0.5, $h1);
        //CODIGO DE BARRAS
        $chave_acesso = str_replace('CTe', '', $this->infCte->getAttribute("Id"));
        $bW = 85;
        $bH = 10;
        //codigo de barras
        $this->pdf->SetFillColor(0, 0, 0);
        $this->pdf->Code128($x + (($w - $bW) / 2), $y + 2, $chave_acesso, $bW, $bH);
        $this->__textBox($x, $y + $h1, $w + 0.5, $h1 - 6);
        $texto = 'CHAVE DE ACESSO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y + $h1, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__format($chave_acesso, '##.####.##.###.###/####-##-##-###-###.###.###-###.###.###-#');
        $this->__textBox($x, $y + $h1 + 3, $w, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->__textBox($x, $y + $h1 + 8, $w + 0.5, $h1 - 4.5);
        $texto = "Consulta de autenticidade no portal nacional do CT-e, ";
        $texto .= "no site da Sefaz Autorizadora, \r\n ou em http://www.cte.fazenda.gov.br";
        if ($this->tpEmis == 5 || $this->tpEmis == 7 || $this->tpEmis == 8)
        {
            $texto = "";
            $this->pdf->SetFillColor(0, 0, 0);
            if ($this->tpEmis == 5)
            {

                $chaveContingencia = $this->__geraChaveAdicionalDeContingencia();
                $this->pdf->Code128($x + 20, $y1 + 10, $chaveContingencia, $bW * .9, $bH / 2);

            } else
            {
                $chaveContingencia = $this->__simpleGetValue($this->protCTe, "nProt");
                $this->pdf->Code128($x + 40, $y1 + 10, $chaveContingencia, $bW * .4 , $bH / 2);
            }


            //codigo de barras

        }

        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x, $y + $h1 + 9, $w, $h, $texto, $aFont, 'T', 'C', 0, '');
        //outra caixa
        $y += $h + 1;
        $h = 8.5;
        $wa = $w;
        $this->__textBox($x, $y + 7.5, $w + 0.5, $h);
        if ($this->__cteDPEC())
        {
            $texto = 'NÚMERO DE REGISTRO DPEC';
        } elseif ($this->tpEmis == 5 || $this->tpEmis == 7 || $this->tpEmis == 8)
        {
            $texto = "DADOS DO CT-E";
        } else
        {
            $texto = 'PROTOCOLO DE AUTORIZAÇÃO DE USO';
        }
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y + 7.5, $wa, $h, $texto, $aFont, 'T', 'L', 0, '');
        if ($this->__cteDPEC())
        {
            $texto = $this->numero_registro_dpec;

        } elseif ($this->tpEmis == 5)
        {
            $chaveContingencia = $this->__geraChaveAdicionalDeContingencia();
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => 'B');
            $texto = $this->__format($chaveContingencia, "#### #### #### #### #### #### #### #### ####");
            $cStat = '';

        } else
        {
            $texto = $this->__simpleGetValue($this->protCTe, "nProt") . " - ";
            $texto .= date('d/m/Y   H:i:s', $this->__convertTime($this->__simpleGetValue($this->protCTe, "dhRecbto")));
            $texto = $this->__simpleGetValue($this->protCTe, "nProt") == '' ? '' : $texto;
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 12, $wa, $h, $texto, $aFont, 'T', 'C', 0, '');
        //CFOP
        $x = $oldX;
        $h = 8.5;
        $w = round($maxW * 0.42);
        $y1 = $y + 7.5;
        $this->__textBox($x, $y1, $w + 2, $h);
        $texto = 'CFOP - NATUREZA DA PRESTAÇÃO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x, $y1, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ide, "CFOP") . ' - ' . $this->__simpleGetValue($this->ide, "natOp");
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y1 + 3.5, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        //ORIGEM DA PRESTAÇÃO
        $y += $h + 7.5;
        $x = $oldX;
        $h = 8;
        $w = ($maxW * 0.5);
        $this->__textBox($x, $y, $w + 0.5, $h);
        $texto = 'INÍCIO DA PRESTAÇÃO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ide, "xMunIni") . ' - ' . $this->__simpleGetValue($this->ide, "UFIni");
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 3.5, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        //DESTINO DA PRESTAÇÃO
        $x = $oldX + $w + 1;
        $h = 8;
        $w = $w - 1.3;
        $this->__textBox($x - 0.5, $y, $w + 0.5, $h);
        $texto = 'TÉRMINO DA PRESTAÇÃO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ide, "xMunFim") . ' - ' . $this->__simpleGetValue($this->ide, "UFFim");
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 3.5, $w, $h, $texto, $aFont, 'T', 'L', 0, '');

        //#########################################################################
        //Indicação de CTe Homologação, cancelamento e falta de protocolo
        $tpAmb = $this->ide->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        //indicar cancelamento
        if ($this->__cteCancelada())
        {
            //101 Cancelamento
            $x = 10;
            $y = $this->hPrint - 130;
            $h = 25;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "CTe CANCELADO";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        if ($this->__cteDenegada())
        {
            //110 Denegada
            $x = 10;
            $y = $this->hPrint - 130;
            $h = 25;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "CTe USO DENEGADO";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $y += $h;
            $h = 5;
            $w = $maxW - (2 * $x);
            $texto = "SEM VALOR FISCAL";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        //indicar sem valor
        if ($tpAmb != 1)
        {
            $x = 10;
            if ($this->orientacao == 'P')
            {
                $y = round($this->hPrint * 2 / 3, 0);
            } else
            {
                $y = round($this->hPrint / 2, 0);
            }
            $h = 5;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 30,
                'style' => 'B');
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->__textBox($x, $y + 14, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        } else
        {
            $x = 10;
            if ($this->orientacao == 'P')
            {
                $y = round($this->hPrint * 2 / 3, 0);
            } else
            {
                $y = round($this->hPrint / 2, 0);
            } //fim orientacao
            $h = 5;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            //indicar FALTA DO PROTOCOLO se NFe não for em contingência
            if (($this->tpEmis == 5 || $this->tpEmis == 7 || $this->tpEmis == 8) && !$this->__cteDPEC())
            {
                //Contingência
                $texto = "DACTE Emitido em Contingência";
                $aFont = array(
                    'font' => $this->fontePadrao,
                    'size' => 48,
                    'style' => 'B');
                $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                $aFont = array(
                    'font' => $this->fontePadrao,
                    'size' => 30,
                    'style' => 'B');
                $texto = "devido à problemas técnicos";
                $this->__textBox($x, $y + 12, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            } else
            {
                if (!isset($this->cteProc))
                {
                    if (!$this->__cteDPEC())
                    {
                        $texto = "SEM VALOR FISCAL";
                        $aFont = array(
                            'font' => $this->fontePadrao,
                            'size' => 48,
                            'style' => 'B');
                        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                    }
                    $aFont = array(
                        'font' => $this->fontePadrao,
                        'size' => 30,
                        'style' => 'B');
                    $texto = "FALTA PROTOCOLO DE APROVAÇÃO DA SEFAZ";
                    if (!$this->__cteDPEC())
                    {
                        $this->__textBox($x, $y + 12, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                    } else
                    {
                        $this->__textBox($x, $y + 25, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                    }
                } //fim cteProc
                if ($this->tpEmis == 4)
                {
                    //DPEC
                    $x = 10;
                    $y = $this->hPrint - 130;
                    $h = 25;
                    $w = $maxW - (2 * $x);
                    $this->pdf->SetTextColor(200, 200, 200); // 90,90,90 é muito escuro
                    $texto = "DANFE impresso em contingência -\n" . "DPEC regularmente recebido pela Receita\n" . "Federal do Brasil";
                    $aFont = array(
                        'font' => $this->fontePadrao,
                        'size' => 48,
                        'style' => 'B');
                    $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
                    $this->pdf->SetTextColor(0, 0, 0);
                }
            } //fim tpEmis
            $this->pdf->SetTextColor(0, 0, 0);
        }
        return $oldY;
    } //fim __cabecalhoDANFE

    /**
     * rodapeDACTE
     * Monta o rodape no final da DACTE ( retrato e paisagem )
     *
     * @param number $xInic  Posição horizontal canto esquerdo
     * @param number $yFinal Posição vertical final para impressão
     */
    private function rodapeDACTE($x, $y)
    {
        $texto = "Impresso em  " . date('d/m/Y   H:i:s');
        $w = $this->wPrint - 4;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, 4, $texto, $aFont, 'T', 'L', 0, '');
        $texto = "DacteNFePHP ver. " . $this->version . "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, 4, $texto, $aFont, 'T', 'R', 0, 'http://www.nfephp.org');
    } //fim __rodapeDANFE

    /**
     * remetenteDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function remetenteDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW * 0.5 + 0.5;
        $h = 19;
        $x1 = $x + 16;
        $texto = 'REMETENTE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 1, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->rem, "xNome");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'ENDEREÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->enderReme, "xLgr") . ',';
        $texto .= $this->__simpleGetValue($this->enderReme, "nro");
        $texto .= ($this->__simpleGetValue($this->enderReme, "xCpl") != "") ? ' - ' . $this->__simpleGetValue($this->enderReme, "xCpl") : '';
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = $this->__simpleGetValue($this->enderReme, "xBairro");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'MUNICÍPIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->enderReme, "xMun") . ' - ';
        $texto .= $this->__simpleGetValue($this->enderReme, "UF");
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 18;
        $texto = 'CEP';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__format($this->__simpleGetValue($this->enderReme, "CEP"), "#####-###");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 6, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $cpfCnpj = $this->formatCNPJCPF($this->rem);
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $cpfCnpj, $aFont, 'T', 'L', 0, '');
        $x = $w - 45;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->rem, "IE");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 28, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'PAÍS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->rem, "xPais") != "" ? $this->__simpleGetValue($this->rem, "xPais") : 'BRASIL';
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 25;
        $texto = 'FONE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->formatFone($this->rem);
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
    } //fim da função remetenteDACTE

    /**
     * destinatarioDACTE
     * Monta o campo com os dados do destinatário na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function destinatarioDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = ($maxW * 0.5) - 0.7;
        $h = 19;
        $x1 = $x + 19;
        $texto = 'DESTINATÁRIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x - 0.5, $y, $w, $h, $texto, $aFont, 'T', 'L', 1, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->dest, "xNome");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'ENDEREÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->enderDest, "xLgr") . ',';
        $texto .= $this->__simpleGetValue($this->enderDest, "nro");
        $texto .= $this->__simpleGetValue($this->enderDest, "xCpl") != "" ? ' - ' . $this->__simpleGetValue($this->enderDest, "xCpl") : '';
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = $this->__simpleGetValue($this->enderDest, "xBairro");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'MUNICÍPIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->enderDest, "xMun") . ' - ';
        $texto .= $this->__simpleGetValue($this->enderDest, "UF");
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 19 + $oldX;
        $texto = 'CEP';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__format($this->__simpleGetValue($this->enderDest, "CEP"), "#####-###");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 5, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $cpfCnpj = $this->formatCNPJCPF($this->dest);
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $cpfCnpj, $aFont, 'T', 'L', 0, '');
        $x = $w - 47.5 + $oldX;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->dest, "IE");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 28, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'PAÍS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->dest, "xPais");
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 27 + $oldX;
        $texto = 'FONE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->formatFone($this->dest);
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
    } //fim da função destinatarioDACTE

    /**
     * expedidorDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function expedidorDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW * 0.5 + 0.5;
        $h = 19;
        $x1 = $x + 16;
        $texto = 'EXPEDIDOR';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 1, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->exped, "xNome");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'ENDEREÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = $this->formatNegrito;
        if (isset($this->enderExped))
        {
            $texto = $this->__simpleGetValue($this->enderExped, "xLgr") . ', ';
            $texto .= $this->__simpleGetValue($this->enderExped, "nro");
            $texto .= $this->__simpleGetValue($this->enderExped, "xCpl") != "" ? ' - ' . $this->__simpleGetValue($this->enderExped, "xCpl") : '';
        } else
        {
            $texto = '';
        }
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = $this->__simpleGetValue($this->enderExped, "xBairro");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'MUNICÍPIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        if (isset($this->enderExped))
        {
            $texto = $this->__simpleGetValue($this->enderExped, "xMun") . ' - ';
            $texto .= $this->__simpleGetValue($this->enderExped, "UF");
        } else
        {
            $texto = '';
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 18;
        $texto = 'CEP';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__format($this->__simpleGetValue($this->enderExped, "CEP"), "#####-###");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 6, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $cpfCnpj = $this->formatCNPJCPF($this->exped);
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $cpfCnpj, $aFont, 'T', 'L', 0, '');
        $x = $w - 45;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->exped, "IE");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 28, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'PAÍS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->exped, "xPais");
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 25;
        $texto = 'FONE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        if (isset($this->exped))
        {
            $texto = $this->formatFone($this->exped);
            $aFont = $this->formatNegrito;
            $this->__textBox($x + 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        }
    } //fim da função remetenteDACTE

    /**
     * recebedorDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function recebedorDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = ($maxW * 0.5) - 0.7;
        $h = 19;
        $x1 = $x + 19;
        $texto = 'RECEBEDOR';
        $aFont = $this->formatPadrao;
        $this->__textBox($x - 0.5, $y, $w, $h, $texto, $aFont, 'T', 'L', 1, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->receb, "xNome");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'ENDEREÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = $this->formatNegrito;
        if (isset($this->enderReceb))
        {
            $texto = $this->__simpleGetValue($this->enderReceb, "xLgr") . ', ';
            $texto .= $this->__simpleGetValue($this->enderReceb, "nro");
            $texto .= ($this->__simpleGetValue($this->enderReceb, "xCpl") != "") ? ' - ' . $this->__simpleGetValue($this->enderReceb, "xCpl") : '';
        } else
        {
            $texto = '';
        }
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = $this->__simpleGetValue($this->enderReceb, "xBairro");
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'MUNICÍPIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        if (isset($this->enderReceb))
        {
            $texto = $this->__simpleGetValue($this->enderReceb, "xMun") . ' - ';
            $texto .= $this->__simpleGetValue($this->enderReceb, "UF");
        } else
        {
            $texto = '';
        }
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 19 + $oldX;
        $texto = 'CEP';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__format($this->__simpleGetValue($this->enderReceb, "CEP"), "#####-###");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 5, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->formatCNPJCPF($this->receb);
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 47 + $oldX;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->receb, "IE");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 28, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y += 3;
        $texto = 'PAÍS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->receb, "xPais");
        $aFont = $this->formatNegrito;
        $this->__textBox($x1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 27 + $oldX;
        $texto = 'FONE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        if (isset($this->receb))
        {
            $texto = $this->formatFone($this->receb);
            $aFont = $this->formatNegrito;
            $this->__textBox($x + 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        }
    } //fim da função recebedorDACTE

    /**
     * tomadorDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function tomadorDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P') {
            $maxW = $this->wPrint;
        } else {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 10;
        $texto = 'TOMADOR DO SERVIÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 1, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->toma, "xNome");
        $this->__textBox($x + 29, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $maxW * 0.60;
        $texto = 'MUNICÍPIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->toma, "xMun");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 15, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $maxW * 0.85;
        $texto = 'UF';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->toma, "UF");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 4, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 18;
        $texto = 'CEP';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__format($this->__simpleGetValue($this->toma, "CEP"), "#####-###");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 6, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $x = $oldX;
        $texto = 'ENDEREÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $aFont = $this->formatNegrito;
        $texto = $this->__simpleGetValue($this->toma, "xLgr") . ',';
        $texto .= $this->__simpleGetValue($this->toma, "nro");
        $texto .= ($this->__simpleGetValue($this->toma, "xCpl") != "") ? ' - ' . $this->__simpleGetValue($this->toma, "xCpl") : '';
        $texto .= ' - ' . $this->__simpleGetValue($this->toma, "xBairro");
        $this->__textBox($x + 16, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $texto = 'CNPJ/CPF';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->formatCNPJCPF($this->toma);
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 13, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $x + 65;
        $texto = 'INSCRIÇÃO ESTADUAL';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->toma, "IE");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 28, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.75;
        $texto = 'PAÍS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->toma, "xPais") != "" ? $this->__simpleGetValue($this->toma, "xPais") : 'BRASIL';
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 6, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w - 27;
        $texto = 'FONE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->formatFone($this->toma);
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
    } //fim da função tomadorDACTE

    /**
     * descricaoCargaDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function descricaoCargaDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 17;
        $texto = 'PRODUTO PREDOMINANTE';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 1, '');
        $texto = $this->__simpleGetValue($this->infCarga, "proPred");
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 2.8, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.56;
        $this->pdf->Line($x, $y, $x, $y + 8);
        $aFont = $this->formatPadrao;
        $texto = 'OUTRAS CARACTERÍSTICAS DA CARGA';
        $this->__textBox($x + 1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->infCarga, "xOutCat");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 1, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.8;
        $this->pdf->Line($x, $y, $x, $y + 8);
        $aFont = $this->formatPadrao;
        $texto = 'VALOR TOTAL DA MERCADORIA';
        $this->__textBox($x + 1, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->infCarga, "vCarga") == "" ? $this->__simpleGetValue($this->infCarga, "vMerc") : $this->__simpleGetValue($this->
            infCarga, "vCarga");
        $texto = number_format($texto, 2, ",", ".");
        $aFont = $this->formatNegrito;
        $this->__textBox($x + 1, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 8;
        $x = $oldX;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'TP MED /UN. MED';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 5,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->infQ->item(0), "tpMed") . "\r\n";
        $texto .= number_format($this->__simpleGetValue($this->infQ->item(0), "qCarga") / $this->multiUniPeso($this->__simpleGetValue($this->infQ->item(0),
            "cUnid")), 3, ".", "");
        $texto .= ' ' . $this->unidade($this->__simpleGetValue($this->infQ->item(0), "cUnid"));
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.12;
        $this->pdf->Line($x, $y, $x, $y + 9);
        $texto = 'TP MED /UN. MED';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 5,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->infQ->item(1), "tpMed") . "\r\n";
        $texto .= number_format($this->__simpleGetValue($this->infQ->item(1), "qCarga") / $this->multiUniPeso($this->__simpleGetValue($this->infQ->item(1),
            "cUnid")), 3, ".", "");
        $texto = $this->__simpleGetValue($this->infQ->item(1), "qCarga") == '' ? '' : $texto;
        $texto .= ' ' . $this->unidade($this->__simpleGetValue($this->infQ->item(1), "cUnid"));
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.24;
        $this->pdf->Line($x, $y, $x, $y + 9);
        $texto = 'TP MED /UN. MED';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 5,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->infQ->item(2), "tpMed") . "\r\n";
        $qCarga = $this->__simpleGetValue($this->infQ->item(2), "qCarga");
        $texto .= !empty($qCarga) ? number_format($qCarga / $this->multiUniPeso($this->__simpleGetValue($this->infQ->item(2),"cUnid")), 3, ".", "") : '';
        $texto = $this->__simpleGetValue($this->infQ->item(2), "qCarga") == '' ? '' : $texto;
        $texto .= ' ' . $this->unidade($this->__simpleGetValue($this->infQ->item(2), "cUnid"));
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.36;
        $this->pdf->Line($x, $y, $x, $y + 9);
        $texto = 'CUBAGEM(M3)';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        if ($this->__simpleGetValue($this->infQ->item(0), "cUnid") == '00') {
            $qCarga = $this->__simpleGetValue($this->infQ->item(0), "qCarga");
            $texto = !empty($qCarga) ? number_format($qCarga, 3, ",", ".") : '';
        } else {
            $texto = '';
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.45;
        $this->pdf->Line($x, $y, $x, $y + 9);
        $texto = 'QTDE(VOL)';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $qCarga = $this->__simpleGetValue($this->infQ->item(3), "qCarga");
        $texto = !empty($qCarga) ? number_format($qCarga, 3, ",", ".") : '';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.53;
        $this->pdf->Line($x, $y, $x, $y + 9);
        $texto = 'NOME DA SEGURADORA';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->seg, "xSeg");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x + 31, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'RESPONSÁVEL';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->respSeg;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.68;
        $this->pdf->Line($x, $y, $x, $y + 6);
        $texto = 'NÚMERO DA APOLICE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->seg, "nApol");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.85;
        $this->pdf->Line($x, $y, $x, $y + 6);
        $texto = 'NÚMERO DA AVERBAÇÃO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->seg, "nAver");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
    } //fim da função descricaoCargaDACTE

    /**
     * compValorDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function compValorDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 25;
        $texto = 'COMPONENTES DO VALOR DA PRESTAÇÃO DO SERVIÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'NOME';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $yIniDados = $y;
        $x = $w * 0.14;
        $texto = 'VALOR';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.28;
        $this->pdf->Line($x, $y, $x, $y + 21.5);
        $texto = 'NOME';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.42;
        $texto = 'VALOR';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.56;
        $this->pdf->Line($x, $y, $x, $y + 21.5);
        $texto = 'NOME';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.70;
        $texto = 'VALOR';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $w * 0.86;
        $this->pdf->Line($x, $y, $x, $y + 21.5);
        $y += 1;
        $texto = 'VALOR TOTAL DO SERVIÇO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = number_format($this->__simpleGetValue($this->vPrest, "vTPrest"), 2, ",", ".");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 9,
            'style' => 'B');
        $this->__textBox($x, $y + 4, $w * 0.14, $h, $texto, $aFont, 'T', 'C', 0, '');
        $y += 10;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $y += 1;
        $texto = 'VALOR A RECEBER';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = number_format($this->__simpleGetValue($this->vPrest, "vRec"), 2, ",", ".");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 9,
            'style' => 'B');
        $this->__textBox($x, $y + 4, $w * 0.14, $h, $texto, $aFont, 'T', 'C', 0, '');
        $auxX = $oldX;
        $yIniDados += 4;
        foreach ($this->Comp as $k => $d)
        {
            $nome = $this->Comp->item($k)->getElementsByTagName('xNome')->item(0)->nodeValue;
            $valor = number_format($this->Comp->item($k)->getElementsByTagName('vComp')->item(0)->nodeValue, 2, ",", ".");
            if ($auxX > $w * 0.60)
            {
                $yIniDados = $yIniDados + 4;
                $auxX = $oldX;
            }
            $texto = $nome;
            $aFont = $this->formatPadrao;
            $this->__textBox($auxX, $yIniDados, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.14;
            $texto = $valor;
            $aFont = $this->formatPadrao;
            $this->__textBox($auxX, $yIniDados, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.14;
        }
    } //fim da função compValorDACTE

    /**
     * impostosDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function impostosDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 13;
        $texto = 'INFORMAÇÕES RELATIVAS AO IMPOSTO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'SITUAÇÃO TRIBUTÁRIA';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.26, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.26;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        $texto = 'BASE DE CALCULO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        $texto = 'ALÍQ ICMS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        $texto = 'VALOR ICMS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        $texto = '% RED. BC ICMS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $this->pdf->Line($x, $y, $x, $y + 9.5);
        $texto = 'ICMS ST';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $y = $y + 4;
        $texto = $this->__simpleGetValue($this->ICMS, "CST");
        switch ($texto)
        {
            case '00':
                $texto = "00 - Tributação normal ICMS";
                break;
            case '20':
                $texto = "20 - Tributação com BC reduzida do ICMS";
                break;
            case '40':
                $texto = "40 - ICMS isenção";
                break;
            case '41':
                $texto = "41 - ICMS não tributada";
                break;
            case '51':
                $texto = "51 - ICMS diferido";
                break;
            case '60':
                $texto = "60 - ICMS cobrado anteriormente por substituição tributária";
                break;
            case '90':
                $texto = "90 - ICMS outros";
                break;
        }
        $texto .= $this->__simpleGetValue($this->ICMSSN, "indSN");
        $texto = $texto == 1 ? 'Simples Nacional' : $texto;
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y, $w * 0.26, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.26;
        $texto = !empty($this->ICMS->getElementsByTagName("vBC")->item(0)->nodeValue) ? number_format($this->__simpleGetValue($this->ICMS, "vBC"), 2, ",", ".") :
            '';
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $texto = !empty($this->ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue) ? number_format($this->__simpleGetValue($this->ICMS, "pICMS"), 2, ",",
            ".") : '';
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $texto = !empty($this->ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue) ? number_format($this->__simpleGetValue($this->ICMS, "vICMS"), 2, ",",
            ".") : '';
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $texto = '';
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.14;
        $texto = '';
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y, $w * 0.14, $h, $texto, $aFont, 'T', 'L', 0, '');
    } //fim da função compValorDACTE

    /**
     * __geraChaveAdicionalDeContingencia
     *   
     * @name __geraChaveAdicionalDeContingencia
     * @version 1.0
     * @author Marcos Diez (NFe)
     * @return string chave
     */
    protected function __geraChaveAdicionalDeContingencia()
    {
        //cUF tpEmis CNPJ vNF ICMSp ICMSs DD  DV
        // Quantidade de caracteres  02   01      14  14    01    01  02 01
        $forma = "%02d%d%s%014d%01d%01d%02d";
        $cUF = $this->ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $CNPJ = "00000000000000" . $this->emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $CNPJ = substr($CNPJ, -14);
        $vCT = number_format($this->__simpleGetValue($this->vPrest, "vRec"), 2, "", "") * 100;
        $ICMS_CST = $this->__simpleGetValue($this->ICMS, "CST");
        switch ($ICMS_CST)
        {
            case '00':
            case '20':
                $ICMSp = '1';
                $ICMSs = '2';
                break;
            case '40':
            case '41':
            case '51':
            case '90':
                $ICMSp = '2';
                $ICMSs = '2';
                break;
            case '60':
                $ICMSp = '2';
                $ICMSs = '1';
                break;
        }

        $dd = $this->ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
        $rpos = strrpos($dd, '-');
        $dd = substr($dd, $rpos + 1);
        $chave = sprintf($forma, $cUF, $this->tpEmis, $CNPJ, $vCT, $ICMSp, $ICMSs, $dd);
        $chave = $chave . $this->__modulo11($chave);
        return $chave;
    } //fim __geraChaveAdicionalDeContingencia

    /**
     * docOrigDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function docOrigDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;

        if ($this->modal == '1')
        {
            $h = $this->lota == 1 ? 25 : 53;
        } else
        {
            $h = 25;
        }
        $texto = 'DOCUMENTOS ORIGINÁRIOS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        $descr1 = 'TIPO DOC';
        $descr2 = 'CNPJ/CHAVE';
        $descr3 = 'SÉRIE/NRO. DOCUMENTO';
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = $descr1;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $yIniDados = $y;
        $x += $w * 0.09;
        $texto = $descr2;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.23, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.28;
        $texto = $descr3;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.13, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.13;
        if ($this->modal == '1')
        {
            if ($this->lota == 1)
            {
                $this->pdf->Line($x, $y, $x, $y + 21.5);
            } else
            {
                $this->pdf->Line($x, $y, $x, $y + 49.5);
            }
        } else
        {
            $this->pdf->Line($x, $y, $x, $y + 21.5);
        }
        $texto = $descr1;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.10;
        $texto = $descr2;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.23, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.27;
        $texto = $descr3;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.13, $h, $texto, $aFont, 'T', 'L', 0, '');
        $auxX = $oldX;
        $yIniDados += 4;
        foreach ($this->infNF as $k => $d)
        {
            $tp = $this->infNF->item($k)->getElementsByTagName('mod')->item(0)->nodeValue;
            $cnpj = $this->formatCNPJCPF($this->rem);
            $doc = $this->infNF->item($k)->getElementsByTagName('serie')->item(0)->nodeValue;
            $doc .= '/' . $this->infNF->item($k)->getElementsByTagName('nDoc')->item(0)->nodeValue;
            if ($auxX > $w * 0.90)
            {
                $yIniDados = $yIniDados + 4;
                $auxX = $oldX;
            }
            $texto = $tp;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.09;
            $texto = $cnpj;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.23, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.28;
            $texto = $doc;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.13, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.14;
        }
        // Usa o mesmo cabeçalho de infNF e infNFe:
		// TIPO DOC-> 00, 10 ou 99; CNPJ/CHAVE-> Remetente; SÉRIE/NRO. DOCUMENTO-> nDoc somente.
		foreach ($this->infOutros as $k => $d)
        {
            $tp = $this->infOutros->item($k)->getElementsByTagName('tpDoc')->item(0)->nodeValue;
            $cnpj = $this->formatCNPJCPF($this->rem);
            $doc = $this->infOutros->item($k)->getElementsByTagName('nDoc')->item(0)->nodeValue;
            if ($auxX > $w * 0.90)
            {
                $yIniDados = $yIniDados + 4;
                $auxX = $oldX;
            }
            $texto = $tp;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.09;
            $texto = $cnpj;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.23, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.28;
            $texto = $doc;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.13, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.14;
        }
        foreach ($this->infNFe as $k => $d)
        {
            $tp = 'NFE';
            $chaveNFe = $this->infNFe->item($k)->getElementsByTagName('chave')->item(0)->nodeValue;
            $numNFe = substr($chaveNFe, 25, 9);
            $serieNFe = substr($chaveNFe, 22, 3);
            $doc = $serieNFe . '/' . $numNFe;
            if ($auxX > $w * 0.90)
            {
                $yIniDados = $yIniDados + 4;
                $auxX = $oldX;
            }
            $texto = $tp;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.09;
            $texto = $chaveNFe;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.27, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.28;
            $texto = $doc;
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => '');
            $this->__textBox($auxX, $yIniDados, $w * 0.30, $h, $texto, $aFont, 'T', 'L', 0, '');
            $auxX += $w * 0.14;
        }
    } //fim da função docOrigDACTE


    /**
     * __documentosComplementadosDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     * @package NFePHP
     * @name __documentosComplementadosDACTE
     * @version 1.2.1
     * @param number $x Posição horizontal canto esquerdo
     * @param number $y Posição vertical canto superior
     * @return number Posição vertical final 
     */
    protected function __documentosComplementadosDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;

        $h = 80;

        $texto = 'DETALHAMENTO DO CT-E COMPLEMENTADO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');


        $descr1 = 'CHAVE DO CT-E COMPLEMENTADO';
        $descr2 = 'VALOR COMPLEMENTADO';


        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);

        $texto = $descr1;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');

        $yIniDados = $y;

        $x += $w * 0.37;

        $texto = $descr2;
        $aFont = $this->formatPadrao;
        $this->__textBox($x - 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');


        $x += $w * 0.13;

        $this->pdf->Line($x, $y, $x, $y + 76.5);

        $texto = $descr1;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');

        $x += $w * 0.3;
        $texto = $descr2;
        $aFont = $this->formatPadrao;
        $this->__textBox($x + 8, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');

        $auxX = $oldX;
        $yIniDados += 4;


        if ($auxX > $w * 0.90)
        {
            $yIniDados = $yIniDados + 4;
            $auxX = $oldX;
        }
        $texto = $this->chaveCTeRef;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($auxX, $yIniDados, $w, $h, $texto, $aFont, 'T', 'L', 0, '');

        $texto = number_format($this->__simpleGetValue($this->vPrest, "vTPrest"), 2, ",", ".");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => '');
        $this->__textBox($w * 0.40, $yIniDados, $w, $h, $texto, $aFont, 'T', 'L', 0, '');


    } //fim da função __documentosComplementadosDACTE

    /**
     * obsDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function obsDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 18;
        $texto = 'OBSERVAÇÕES';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');

        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);

        $auxX = $oldX;
        $yIniDados = $y;
        $texto = '';
        foreach ($this->compl as $k => $d)
        {
            $xObs = $this->__simpleGetValue($this->compl->item($k), "xObs");
            $texto .= "\r\n" . $xObs;
        }
        $texto .= $this->__simpleGetValue($this->imp, "infAdFisco", "\r\n");
        $texto .= $this->localEntregaAdic();
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7.5,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '', false);
    } //fim da função obsDACTE

    /**
     * localEntregaAdic
     *
     * @return string
     */
    protected function localEntregaAdic()
    {
        $locEntX = $this->dest->getElementsByTagName('locEnt');
        if ($locEntX->length > 0)
        {
            $locEnt = $locEntX->item(0);
            $output = "Entrega: " . $output = $this->formatCNPJCPF($locEnt);
            $output .= $this->__simpleGetValue($locEnt, "CPF") . " ";
            $output .= $this->__simpleGetValue($locEnt, "xNome") . " ";
            $output .= $this->__simpleGetValue($locEnt, "xLgr") . " ";
            $output .= $this->__simpleGetValue($locEnt, "nro ") . " ";
            $output .= $this->__simpleGetValue($locEnt, "xCpl") . " ";
            $output .= $this->__simpleGetValue($locEnt, "xBairro") . " ";
            $output .= $this->__simpleGetValue($locEnt, "xMun") . " ";
            $output .= $this->__simpleGetValue($locEnt, "UF") . " ";
            return $output;
        }
        return "";
    } //fim localEntregaAdic

    /**
     * modalRodDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function modalRodDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        if ($this->modal == '1')
        {
            $h = $this->lota == 1 ? 12.5 : 3.7;
        } else
        {
            $h = 12.5;
        }
        $textolota = $this->lota == 1 ? 'LOTAÇÃO' : 'CARGA FRACIONADA';
        $texto = 'DADOS ESPECÍFICOS DO MODAL RODOVIÁRIO - ' . $textolota;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h * 3.2, $texto, $aFont, 'T', 'C', 1, '');
        if ($this->lota == 1)
        {
            $this->pdf->Line($x, $y + 12, $w + 1, $y + 12);
        }
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'RNTRC DA EMPRESA';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.23, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->rodo, "RNTRC");
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 3, $w * 0.23, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.23;
        $this->pdf->Line($x, $y, $x, $y + 8.5);
        $texto = 'CIOT';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.13, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->rodo, "CIOT");
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 3, $w * 0.13, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.13;
        $this->pdf->Line($x, $y, $x, $y + 8.5);
        $texto = 'DATA PREVISTA DE ENTREGA';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w * 0.15, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__ymd2dmy($this->__simpleGetValue($this->rodo, "dPrev"));
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y + 3, $w * 0.15, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.15;
        $this->pdf->Line($x, $y, $x, $y + 8.5);
        $h = 25;
        $texto = 'ESTE CONHECIMENTO DE TRANSPORTE ATENDE ' . "\r\n";
        $texto .= ' À LEGISLAÇÃO DE TRANSPORTE RODOVIÁRIO EM VIGOR';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y + 1, $w * 0.50, $h, $texto, $aFont, 'T', 'C', 0, '');
        if ($this->lota == 1)
        {
            $y += 10;
            $x = 1;
            $texto = 'IDENTIFICAÇÃO DO CONJUNTO TRANSPORTADOR';
            $aFont = $this->formatPadrao;
            $this->__textBox($x, $y, $w * 0.465, $h, $texto, $aFont, 'T', 'C', 0, '');
            $this->pdf->Line($x, $y + 3.5, $w * 0.465, $y + 3.5);
            $y += 3.5;
            $texto = 'TIPO';
            $aFont = $this->formatPadrao;
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $yIniDados = $y;
            if (count($this->veic) >= 0)
            {
                foreach ($this->veic as $k => $d)
                {
                    $yIniDados = $yIniDados + 3;
                    $texto = $this->__simpleGetValue($this->veic->item($k), "tpVeic");
                    switch ($texto)
                    {
                        case '0':
                            $texto = 'Tração';
                            break;
                        case '1':
                            $texto = 'Reboque';
                            break;
                        default:
                            $texto = ' ';
                    }
                    $aFont = array(
                        'font' => $this->fontePadrao,
                        'size' => 6,
                        'style' => 'B');
                    $this->__textBox($x, $yIniDados, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
                } //fim foreach
            }
            $x += $w * 0.10;
            $texto = 'PLACA';
            $aFont = $this->formatPadrao;
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y, $x, $y + 14);
            $yIniDados = $y;
            if (count($this->veic) >= 0)
            {
                foreach ($this->veic as $k => $d)
                {
                    $yIniDados = $yIniDados + 3;
                    $texto = $this->__simpleGetValue($this->veic->item($k), "placa");
                    $aFont = array(
                        'font' => $this->fontePadrao,
                        'size' => 6,
                        'style' => 'B');
                    $this->__textBox($x, $yIniDados, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
                }
            }
            $x += $w * 0.13;
            $texto = 'UF';
            $aFont = $this->formatPadrao;
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y, $x, $y + 23);
            $yIniDados = $y;
            if (count($this->veic) >= 0)
            {
                foreach ($this->veic as $k => $d)
                {
                    $yIniDados = $yIniDados + 3;
                    $texto = $this->__simpleGetValue($this->veic->item($k), "UF");
                    $aFont = array(
                        'font' => $this->fontePadrao,
                        'size' => 6,
                        'style' => 'B');
                    $this->__textBox($x, $yIniDados, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
                }
            }
            $x += $w * 0.03;
            $texto = 'RNTRC';
            $aFont = $this->formatPadrao;
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y, $x, $y + 14);
            $yIniDados = $y;
            if (count($this->veic) >= 0)
            {
                foreach ($this->veic as $k => $d)
                {
                    $yIniDados = $yIniDados + 3;
                    $texto = $this->__simpleGetValue($this->veic->item($k), "RNTRC");
                    $aFont = array(
                        'font' => $this->fontePadrao,
                        'size' => 6,
                        'style' => 'B');
                    $this->__textBox($x, $yIniDados, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
                }
            }
            $y += 14;
            $x = 1;
            $texto = 'NOME DO MOTORISTA';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 5,
                'style' => '');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y, $w + 1, $y);
            $texto = !empty($this->moto) ? $this->__simpleGetValue($this->moto, "xNome") : '';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 7,
                'style' => 'B');
            $this->__textBox($x, $y + 3, $w * 0.25, $h, $texto, $aFont, 'T', 'L', 0, '');
            $x += $w * 0.23;
            $texto = 'CPF MOTORISTA';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 5,
                'style' => '');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $texto = !empty($this->moto) ? $this->__simpleGetValue($this->moto, "CPF") : '';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 7,
                'style' => 'B');
            $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $x += $w * 0.23;
            $texto = 'IDENTIFICAÇÃO DOS LACRES EM TRANSITO';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 5,
                'style' => '');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y, $x, $y - 18.7);
            $this->pdf->Line($x, $y, $x, $y + 9);
            $x = $w * 0.465;
            $y -= 16;
            $texto = 'INFORMAÇÕES REFERENTES AO VALE PEDÁGIO';
            $aFont = $this->formatPadrao;
            $this->__textBox($x, $y, $w * 0.5, $h, $texto, $aFont, 'T', 'C', 0, '');
            $this->pdf->Line($x, $y + 4, $w + 1, $y + 4);
            $y += 4;
            $texto = 'CNPJ FORNECEDOR';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 5,
                'style' => '');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y + 4, $w + 1, $y + 4);
            $y += 4;
            $texto = 'NUMERO COMPROVANTE';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 5,
                'style' => '');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $this->pdf->Line($x, $y + 4, $w + 1, $y + 4);
            $y += 4;
            $texto = 'CNPJ RESPONSÁVEL';
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 5,
                'style' => '');
            $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        }
    } //fim da função modalRodDACTE

    /**
     * modalFerrDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function modalFerrDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW;
        $h = 19.6;
        $texto = 'DADOS ESPECÍFICOS DO MODAL FERROVIÁRIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'DCL';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y, $w * 0.25, $h, $texto, $aFont, 'T', 'C', 0, '');
        $this->pdf->Line($x + 49.6, $y, $x + 49.6, $y + 3.5);
        $texto = 'VAGÕES';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x + 50, $y, $w * 0.5, $h, $texto, $aFont, 'T', 'C', 0, '');
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        // DCL
        $texto = 'ID TREM';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "idTrem");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.06;
        $y1 = $y + 12.5;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'NUM';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->rem, "nDoc");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.06;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'SÉRIE';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->rem, "serie");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.06;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'EMISSÃO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__ymd2dmy($this->__simpleGetValue($this->rem, "dEmi"));
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        // VAGOES
        $x += $w * 0.06;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'NUM';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "nVag");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.06;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'TIPO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "tpVag");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.06;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'CAPACIDADE';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "cap");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.08;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'PESO REAL/TON';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "pesoR");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.09;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'PESO BRUTO/TON';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "pesoBC");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w * 0.10, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.1;
        $this->pdf->Line($x, $y, $x, $y1);
        $texto = 'IDENTIFICAÇÃO DOS CONTÊINERES';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "nCont");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        // FLUXO
        $x = 1;
        $y += 12.9;
        $h1 = $h * 0.5 + 0.27;
        $wa = round($w * 0.103) + 0.5;
        $texto = 'FLUXO FERROVIARIO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $wa, $h1, $texto, $aFont, 'T', 'C', 1, '');
        $texto = $this->__simpleGetValue($this->ferrov, "fluxo");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $wa, $h1, $texto, $aFont, 'T', 'C', 0, '');
        $y += 10;
        $texto = 'TIPO DE TRÁFEGO';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $wa, $h1, $texto, $aFont, 'T', 'C', 1, '');
        $texto = $this->convertUnidTrafego($this->__simpleGetValue($this->ferrov, "tpTraf"));
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 7,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $wa, $h1, $texto, $aFont, 'T', 'C', 0, '');
        // Novo Box Relativo a Modal Ferroviário
        $x = 22.5;
        $y += -10.2;
        $texto = 'INFORMAÇÕES DAS FERROVIAS ENVOLVIDAS';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w - 21.5, $h1 * 2.019, $texto, $aFont, 'T', 'C', 1, '');
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $w = $w * 0.2;
        $h = $h * 1.04;
        $texto = 'CÓDIGO INTERNO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "cInt");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = 'CNPJ';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y + 6, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "CNPJ");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 9, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += 50;
        $texto = 'NOME';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "xNome");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 3, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = 'INSCRICAO ESTADUAL';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y + 6, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = $this->__simpleGetValue($this->ferrov, "IE");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 9, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += 50;
        $texto = 'PARTICIPAÇÃO OUTRA FERROVIA';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y + 6, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $texto = '';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'B');
        $this->__textBox($x, $y + 9, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
    } //fim da função modalFerrDACTE

    /**
     * canhotoDACTE
     * Monta o campo com os dados do remetente na DACTE. ( retrato  e paisagem  )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @return number Posição vertical final
     */
    protected function canhotoDACTE($x = 0, $y = 0)
    {
        $oldX = $x;
        $oldY = $y;
        if ($this->orientacao == 'P')
        {
            $maxW = $this->wPrint;
        } else
        {
            $maxW = $this->wPrint - $this->wCanhoto;
        }
        $w = $maxW - 1;
        $h = 15;
        $y = $y + 1;
        $texto = 'DECLARO QUE RECEBI OS VOLUMES DESTE CONHECIMENTO EM PERFEITO ESTADO ';
        $texto .= 'PELO QUE DOU POR CUMPRIDO O PRESENTE CONTRATO DE TRANSPORTE';
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        $y += 3.4;
        $this->pdf->Line($x, $y, $w + 1, $y);
        $texto = 'NOME';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.25, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.25;
        $this->pdf->Line($x, $y, $x, $y + 11.5);
        $texto = 'ASSINATURA / CARIMBO';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.25, $h - 3.4, $texto, $aFont, 'B', 'C', 0, '');
        $x += $w * 0.25;
        $this->pdf->Line($x, $y, $x, $y + 11.5);
        $texto = 'TÉRMINO DA PRESTAÇÃO - DATA/HORA' . "\r\n" . "\r\n" . "\r\n";
        $texto .= ' INÍCIO DA PRESTAÇÃO - DATA/HORA';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x + 10, $y, $w * 0.25, $h - 3.4, $texto, $aFont, 'T', 'C', 0, '');
        $x = $oldX;
        $y = $y + 5;
        $this->pdf->Line($x, $y, $w * 0.255, $y);
        $texto = 'RG';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w * 0.33, $h, $texto, $aFont, 'T', 'L', 0, '');
        $x += $w * 0.85;
        $this->pdf->Line($x, $y + 6.4, $x, $y - 5);
        $texto = "CT-E";
        $aFont = $this->formatNegrito;
        $this->__textBox($x, $y - 5, $w * 0.15, $h, $texto, $aFont, 'T', 'C', 0, '');
        $texto = "\r\n Nº. DOCUMENTO  " . $this->__simpleGetValue($this->ide, "nCT") . " \n";
        $texto .= "\r\n SÉRIE  " . $this->__simpleGetValue($this->ide, "serie");
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y - 8, $w * 0.15, $h, $texto, $aFont, 'C', 'C', 0, '');
        $x = $oldX;
        $this->hDashedLine($x, $y + 7.5, $this->wPrint, 0.1, 80);
    } //fim da função canhotoDACTE

    /**
     * __dadosAdicionaisDANFE
     * Coloca o grupo de dados adicionais da DACTE. ( retrato e paisagem )
     *
     * @param  number $x Posição horizontal canto esquerdo
     * @param  number $y Posição vertical canto superior
     * @param  number $h altura do campo
     * @return number Posição vertical final
     */
    protected function dadosAdicDACTE($x, $y, $pag, $h)
    {
        $oldX = $x;
        //###########################################################################
        //DADOS ADICIONAIS DACTE
        if ($this->orientacao == 'P')
        {
            $w = $this->wPrint;
        } else
        {
            $w = $this->wPrint - $this->wCanhoto;
        }
        //INFORMAÇÕES COMPLEMENTARES
        $texto = "USO EXCLUSIVO DO EMISSOR DO CT-E";
        $y += 3;
        $w = $this->wAdic;
        $h = 17; //mudar
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        $this->pdf->Line($x, $y + 3, $w * 1.385, $y + 3);
        //o texto com os dados adicionais foi obtido na função xxxxxx
        //e carregado em uma propriedade privada da classe
        //$this->wAdic com a largura do campo
        //$this->textoAdic com o texto completo do campo
        $y += 1;
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y + 2, $w - 2, $h - 3, $this->textoAdic, $aFont, 'T', 'L', 0, '', false);
        //RESERVADO AO FISCO
        $texto = "RESERVADO AO FISCO";
        $x += $w;
        $y -= 1;
        if ($this->orientacao == 'P')
        {
            $w = $this->wPrint - $w;
        } else
        {
            $w = $this->wPrint - $w - $this->wCanhoto;
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => '');
        $this->__textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'C', 1, '');
        //inserir texto informando caso de contingência
        //1 – Normal – emissão normal;
        //2 – Contingência FS – emissão em contingência com impressão do DACTE em Formulário de Segurança;
        //3 – Contingência SCAN – emissão em contingência  – SCAN;
        //4 – Contingência DPEC - emissão em contingência com envio da Declaração Prévia de
        //Emissão em Contingência – DPEC;
        //5 – Contingência FS-DA - emissão em contingência com impressão do DACTE em Formulário de
        //Segurança para Impressão de Documento Auxiliar de Documento Fiscal Eletrônico (FS-DA).
        $xJust = $this->__simpleGetValue($this->ide, "xJust", $extraBefore = ' Justificativa: ');
        $dhCont = date('d/m/Y H:i:s', $this->__convertTime($this->__simpleGetValue($this->ide, "dhCont")));
        $dhCont = ' Entrada em contingência : ' . $dhCont;
        $texto = '';
        switch ($this->tpEmis)
        {
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
        $aFont = $this->formatPadrao;
        $this->__textBox($x, $y + 2, $w - 2, $h - 3, $texto, $aFont, 'T', 'L', 0, '', false);

        return $y + $h;
    } //fim dadosAdicDACTE

    /**
     * hDashedLine
     * Desenha uma linha horizontal tracejada com o FPDF
     *
     * @param  number $x Posição horizontal inicial, em mm
     * @param  number $y Posição vertical inicial, em mm
     * @param  number $w Comprimento da linha, em mm
     * @param  number $h Espessura da linha, em mm
     * @param  number $n Numero de traços na seção da linha com o comprimento $w
     * @return none
     */
    protected function hDashedLine($x, $y, $w, $h, $n)
    {
        $this->pdf->SetLineWidth($h);
        $wDash = ($w / $n) / 2; // comprimento dos traços
        for ($i = $x; $i <= $x + $w; $i += $wDash + $wDash)
        {
            for ($j = $i; $j <= ($i + $wDash); $j++)
            {
                if ($j <= ($x + $w - 1))
                {
                    $this->pdf->Line($j, $y, $j + 1, $y);
                }
            }
        }
    } //fim função hDashedLine

    /**
     * hDashedVerticalLine
     * Desenha uma linha vertical tracejada com o FPDF
     *
     * @param  number $x      Posição horizontal inicial, em mm
     * @param  number $y      Posição vertical inicial, em mm
     * @param  number $w      Comprimento da linha, em mm
     * @param  number $yfinal Espessura da linha, em mm
     * @param  number $n      Numero de traços na seção da linha com o comprimento $w
     * @return none
     */
    protected function hDashedVerticalLine($x, $y, $w, $yfinal, $n)
    {
        $this->pdf->SetLineWidth($w);
        /* Organizando valores */
        if ($y > $yfinal)
        {
            $aux = $yfinal;
            $yfinal = $y;
            $y = $aux;
        }
        while ($y < $yfinal && $n > 0)
        {
            $this->pdf->Line($x, $y, $x, $y + 1);
            $y += 3;
            $n--;
        }
    } //fim função hDashedVerticalLine

    /**
     * formatCNPJCPF
     * Formata campo CnpjCpf contida na CTe
     *
     * @param  string $field campo cnpjCpf da CT-e
     * @return string
     */
    protected function formatCNPJCPF($field)
    {
        if (!isset($field))
        {
            return '';
        }
        $cnpj = !empty($field->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $field->getElementsByTagName("CNPJ")->item(0)->nodeValue : "";
        if ($cnpj != "" && $cnpj != "00000000000000")
        {
            $cnpj = $this->__format($cnpj, '###.###.###/####-##');
        } else
        {
            $cnpj = !empty($field->getElementsByTagName("CPF")->item(0)->nodeValue) ? $this->__format($field->getElementsByTagName("CPF")->item(0)->nodeValue,
                '###.###.###.###-##') : '';
        }
        return $cnpj;
    } //fim formatCNPJCPF

    /**
     * formatFone
     * Formata campo fone contida na CTe
     *
     * @param  string $field campo fone da CT-e
     * @return string
     */
    protected function formatFone($field)
    {
        $fone = !empty($field->getElementsByTagName("fone")->item(0)->nodeValue) ? $field->getElementsByTagName("fone")->item(0)->nodeValue : '';
        $foneLen = strlen($fone);
        if ($foneLen > 0)
        {
            $fone2 = substr($fone, 0, $foneLen - 4);
            $fone1 = substr($fone, 0, $foneLen - 8);
            $fone = '(' . $fone1 . ') ' . substr($fone2, -4) . '-' . substr($fone, -4);
        } else
        {
            $fone = '';
        }

        return $fone;
    } //fim formatFone

    /**
     * unidade
     * Converte a imformação de peso contida na CTe
     *
     * @param  string $c unidade de trafego extraida da CTe
     * @return string
     */
    protected function unidade($c = '')
    {
        switch ($c)
        {
            case '00':
                $r = 'M3';
                break;
            case '01':
                $r = 'KG';
                break;
            case '02':
                $r = 'TON';
                break;
            case '03':
                $r = 'UN';
                break;
            case '04':
                $r = 'LT';
                break;
            case '05':
                $r = 'MMBTU';
                break;
            default:
                $r = '';
        }

        return $r;
    } //fim unidade

    /**
     * convertUnidTrafego
     * Converte a imformação de peso contida na CTe
     *
     * @param  string $U Informação de trafego extraida da CTe
     * @return string
     */
    protected function convertUnidTrafego($U = '')
    {
        if ($U)
        {
            switch ($U)
            {
                case '0':
                    $stringU = 'Próprio';
                    break;
                case '1':
                    $stringU = 'Mútuo';
                    break;
                case '2':
                    $stringU = 'Rodoferroviário';
                    break;
                case '3':
                    $stringU = 'Rodoviário';
                    break;
            }

            return $stringU;
        }
    } //fim da função convertUnidTrafego

    /**
     * multiUniPeso
     * Fornece a imformação multiplicação de peso contida na CTe
     *
     * @param  interger $U Informação de peso extraida da CTe
     * @return interger
     */
    protected function multiUniPeso($U = '')
    {
        if ($U === "01")
        {
            // tonelada
            //return 1000;
            return 1;
        }

        return 1; // M3, KG, Unidade, litros, mmbtu
    } //fim da função multiUniPeso
}
//fim da classe DacteNFePHP
