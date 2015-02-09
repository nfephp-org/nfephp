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
 * @name        DaCancnfeNFePHP.class.php
 * @version     0.1.1
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2012 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *              Roberto Spadim <roberto at spadim dot com dot br>
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
}
//ajuste do tempo limite de resposta do processo
set_time_limit(1800);
//definição do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH', 'font/');
}
//situação externa do documento
if (!defined('NFEPHP_SITUACAO_EXTERNA_CANCELADA')) {
    define('NFEPHP_SITUACAO_EXTERNA_CANCELADA', 1);
    define('NFEPHP_SITUACAO_EXTERNA_DENEGADA', 2);
    define('NFEPHP_SITUACAO_EXTERNA_NONE', 0);
}
//classe extendida da classe FPDF para montagem do arquivo pdf
require_once('../Common/PdfNFePHP.class.php');
//classe com as funções communs entre DANFE e DACTE
require_once('../Common/CommonNFePHP.class.php');

class DaCancnfeNFePHP extends CommonNFePHP
{
    
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
    protected $errStatus = false;// status de erro TRUE um erro ocorreu FALSE sem erros
    protected $orientacao='P'; //orientação da DANFE P-Retrato ou L-Paisagem
    protected $papel='A4'; //formato do papel
    protected $destino = 'I'; //I-borwser, S-retorna o arquivo, D-força download, F-salva em arquivo local
    protected $pdfDir=''; //diretorio para salvar o pdf com a opção de destino = F
    protected $fontePadrao='Times'; //Nome da Fonte para gerar o DANFE
    protected $version = '0.1.2';
    protected $wPrint; //largura imprimivel
    protected $hPrint; //comprimento imprimivel
    protected $wCanhoto; //largura do canhoto para a formatação paisagem
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    //variaveis da carta de correção
    protected $idDoc;
    protected $chNFe;
    protected $tpAmb;
    protected $xJust;
    protected $dhEvento;
    protected $cStat;
    protected $xMotivo;
    protected $CNPJDest = '';
    protected $CPFDest = '';
    protected $dhRegEvento;
    protected $nProt;
    //objetos
    private $dom;
    private $infCanc;
    private $retCancNFe;
    
    
   /**
    *__construct
    * @param string $docXML Arquivo XML
    * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
    * @param string $sPapel Tamanho do papel (Ex. A4)
    * @param string $sPathLogo Caminho para o arquivo do logo
    * @param string $sDestino Estabelece a direção do envio do documento PDF I-browser D-browser com download S-
    * @param string $sDirPDF Caminho para o diretorio de armazenamento dos arquivos PDF
    * @param string $fonteDANFE Nome da fonte alternativa
    * @param array $aEnd array com o endereço do emitente
    * @param number $mododebug 1-SIM e 0-Não (0 default)
    */
    public function __construct(
        $docXML = '',
        $sOrientacao = 'P',
        $sPapel = 'A4',
        $sPathLogo = '',
        $sDestino = 'I',
        $sDirPDF = '',
        $fontePDF = '',
        $aEnd = array(),
        $mododebug = 0
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
        if (is_array($aEnd)) {
            $this->aEnd = $aEnd;
        }
        $this->orientacao   = $sOrientacao;
        $this->papel        = $sPapel;
        $this->pdf          = '';
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
        if (!is_file($docXML)) {
            if (empty($docXML)) {
                $this->errMsg = 'Um caminho ou um arquivo xml do protocolo de cancelamento deve ser passado!';
                $this->errStatus = true;
                return false;
            }
        } else {
            $docXML = file_get_contents($docXML);
        }
        $this->dom = new DomDocument;
        $this->dom->loadXML($docXML);
        $this->infCanc        = $this->dom->getElementsByTagName("infCanc")->item(0);
        $this->retCancNFe     = $this->dom->getElementsByTagName("retCancNFe")->item(0);
        if (empty($this->infCanc) && empty($this->retCancNFe)) {
            $this->errMsg = 'Um protocolo de cancelamento de NFe deve ser passado !!';
            $this->errStatus = true;
            return false;
        }
        $this->idDoc = str_replace('ID', '', $this->infCanc->getAttribute("Id"));
        $this->chNFe = $this->infCanc->getElementsByTagName("chNFe")->item(0)->nodeValue;
        $this->aEnd['CNPJ']=substr($this->chNFe, 6, 14);
        $this->tpAmb = $this->infCanc->getElementsByTagName("tpAmb")->item(0)->nodeValue;
        $this->xJust = $this->infCanc->getElementsByTagName("xJust")->item(0)->nodeValue;
        $this->dhEvento = $this->retCancNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
        $this->cStat = $this->retCancNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
        $this->xMotivo = $this->retCancNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
        $this->dhRegEvento = $this->retCancNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
        $this->nProt = $this->retCancNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
    }
  
    /**
     * monta
     * @name monta
     * @param type $orientacao
     * @param type $papel
     * @param type $logoAlign
     * @return type 
     */
    public function monta(
        $orientacao = 'P',
        $papel = 'A4',
        $logoAlign = 'C',
        $sitExterna = NFEPHP_SITUACAO_EXTERNA_NONE,
        $classPdf = false
    ) {
        return $this->montaDaCancnfe($orientacao, $papel, $logoAlign, $sitExterna, $classPdf);
    }
    
    /**
     * printDocument
     * @param type $nome 
     * @param type $destino
     * @param type $printer
     * @return object pdf 
     */
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDaCancnfe($nome, $destino, $printer);
    }

    /**
     * printDaCancnfe
     * @param type $nome
     * @param string $destino
     * @param type $printer
     * @return type
     */
    public function printDaCancnfe($nome = '', $destino = 'I', $printer = '')
    {
        $arq = $this->pdf->Output($nome, $destino);
        return $arq;
    }
    
    /**
     * montaDaCancnfe
     * @param string $orientacao (Opcional)
     * @param string $papel (Opcional) Estabelece o tamanho do papel (ex. A4)
     * @return string O ID do evento extraido do arquivo XML
     */
    public function montaDaCancnfe(
        $orientacao = 'P',
        $papel = 'A4',
        $logoAlign = 'C',
        $sitExterna = NFEPHP_SITUACAO_EXTERNA_NONE,
        $classPdf = false
    ) {
        $this->orientacao = $orientacao;
        if (isset($this->aEnd['CNPJ'])) {
            $this->pAdicionaLogoPeloCnpj($this->aEnd['CNPJ']);
        } else {
            $this->pAdicionaLogoPeloCnpj($this->aEnd['CPF']);
        }
        $this->papel = $papel;
        $this->logoAlign = $logoAlign;
        if ($classPdf !== false) {
            $this->pdf = $classPdf;
        } else {
            $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        }
        if ($this->orientacao == 'P') {
            // margens do PDF
            $margSup = 2;
            $margEsq = 2;
            $margDir = 2;
            // posição inicial do relatorio
            $xInic = 1;
            $yInic = 1;
            if ($this->papel =='A4') {
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
            if ($papel =='A4') {
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
        $xPos = $xInic;
        $yPos = $yInic;
        //coloca o cabeçalho
        $yPos = $this->zHeader($xPos, $yPos, $pag, $sitExterna);
        //coloca os dados da CCe
        $yPos = $this->zBody($xPos, $yPos+15);
        //coloca os dados da CCe
        $yPos = $this->zFooter($xPos, $yPos+$this->hPrint-20);
        //retorna o ID do evento
        if ($classPdf !== false) {
            $aRet = array('id' => $this->idDoc, 'classe_PDF' => $this->pdf);
            return $aRet;
        } else {
            return $this->idDoc;
        }
    }
    
    /**
     * zHeader
     * @param type $x
     * @param type $y
     * @param type $pag
     * @return type
     */
    private function zHeader(
        $x,
        $y,
        $pag,
        $sitExterna = NFEPHP_SITUACAO_EXTERNA_NONE
    ) {
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;
        $w = round($maxW*0.41, 0);
        if ($this->orientacao == 'P') {
            $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        } else {
            $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'B');
        }
        $w1 = $w;
        $h = 32;
        $oldY += $h;
        $this->pTextBox($x, $y, $w, $h);
        $texto = 'IDENTIFICAÇÃO DO EMITENTE';
        $this->pTextBox($x, $y, $w, 5, $texto, $aFont, 'T', 'C', 0, '');
        if (is_file($this->logomarca)) {
            $logoInfo = getimagesize($this->logomarca);
            //largura da imagem em mm
            $logoWmm = ($logoInfo[0]/72)*25.4;
            //altura da imagem em mm
            $logoHmm = ($logoInfo[1]/72)*25.4;
            if ($this->logoAlign=='L') {
                $nImgW = round($w/3, 0);
                $nImgH = round($logoHmm * ($nImgW/$logoWmm), 0);
                $xImg = $x+1;
                $yImg = round(($h-$nImgH)/2, 0)+$y;
                //estabelecer posições do texto
                $x1 = round($xImg + $nImgW +1, 0);
                $y1 = round($h/3+$y, 0);
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
        //Nome emitente
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'B');
        $texto = (isset($this->aEnd['razao'])?$this->aEnd['razao']:'');
        $this->pTextBox($x1, $y1, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');
        //endereço
        $y1 = $y1+6;
        $aFont = array('font'=>$this->fontePadrao,'size'=>8,'style'=>'');
        $lgr = (isset($this->aEnd['logradouro'])?$this->aEnd['logradouro']:'');
        $nro = (isset($this->aEnd['numero'])?$this->aEnd['numero']:'');
        $cpl = (isset($this->aEnd['complemento'])?$this->aEnd['complemento']:'');
        $bairro = (isset($this->aEnd['bairro'])?$this->aEnd['bairro']:'');
        $CEP = (isset($this->aEnd['CEP'])?$this->aEnd['CEP']:'');
        $CEP = $this->pFormat($CEP, "#####-###");
        $mun = (isset($this->aEnd['municipio'])?$this->aEnd['municipio']:'');
        $UF = isset($this->aEnd['UF']) ? $this->aEnd['UF'] : '';
        $fone = isset($this->aEnd['telefone']) ? $this->aEnd['telefone'] : '';
        $email = isset($this->aEnd['email']) ? $this->aEnd['email'] : '';
        if ($email != '') {
            $email = 'Email: '.$email;
        }
        $texto = "";
        $tmp_txt = trim(($lgr!=''?"$lgr, ":'').($nro!=0?$nro:"SN").($cpl!=''?" - $cpl":''));
        $tmp_txt = $tmp_txt == 'SN' ? '' : $tmp_txt;
        $texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
        $tmp_txt = trim($bairro . ($bairro!='' && $CEP!=''?" - ":'') . $CEP);
        $texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
        $tmp_txt = $mun;
        $tmp_txt.= ($tmp_txt!='' && $UF!=''?" - ":'').$UF;
        $tmp_txt.= ($tmp_txt!='' && $fone!=''?" - ":'').$fone;
        $texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
        $tmp_txt = $email;
        $texto .= ($texto!='' && $tmp_txt!=''?"\n":'').$tmp_txt;
        $this->pTextBox($x1, $y1-2, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');
        $w2 = round($maxW - $w, 0);
        $x += $w;
        $this->pTextBox($x, $y, $w2, $h);
        $y1 = $y + $h;
        $aFont = array('font'=>$this->fontePadrao,'size'=>16,'style'=>'B');
        $this->pTextBox($x, $y+2, $w2, 8, 'Representação Gráfica de ProtCancNfe', $aFont, 'T', 'C', 0, '');
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'I');
        $this->pTextBox($x, $y+7, $w2, 8, '(Protocolo Cancelamento de NFe)', $aFont, 'T', 'C', 0, '');
        $tsHora = $this->pConvertTime($this->dhEvento);
        $texto = 'Criado em : '. date('d/m/Y   H:i:s', $tsHora);
        $this->pTextBox($x, $y+20, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');
        $tsHora = $this->pConvertTime($this->dhRegEvento);
        $texto = 'Prococolo: '.$this->nProt.'  -  Registrado na SEFAZ em: '.date('d/m/Y   H:i:s', $tsHora);
        $this->pTextBox($x, $y+25, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');
        $x = $oldX;
        $this->pTextBox($x, $y1, $maxW, 33);
        $sY = $y1+23;
        $texto = 'De acordo com as determinações legais vigentes, vimos por meio desta comunicar-lhe que a Nota Fiscal, abaixo referenciada, encontra-se cancelada, solicitamos que sejam aplicadas essas correções ao executar seus lançamentos fiscais.';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $this->pTextBox($x+5, $y1, $maxW-5, 20, $texto, $aFont, 'T', 'L', 0, '', false);
        $x = $oldX;
        $y = $y1;
        $numNF = substr($this->chNFe, 25, 9);
        $serie = substr($this->chNFe, 22, 3);
        $numNF = $this->__format($numNF, "###.###.###");
        $texto = "Nota Fiscal: " . $numNF .'  -   Série: '.$serie;
        $this->pTextBox($x+2, $y+11, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');
        $bW = 87;
        $bH = 15;
        $x = 55;
        $y = $y1+10;
        $w = $maxW;
        $this->pdf->SetFillColor(0, 0, 0);
        $this->pdf->Code128($x+(($w-$bW)/2), $y+2, $this->chNFe, $bW, $bH);
        $this->pdf->SetFillColor(255, 255, 255);
        $y1 = $y+2+$bH;
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'');
        $texto = $this->pFormat($this->chNFe, $this->formatoChave);
        $this->pTextBox($x, $y1, $w-2, $h, $texto, $aFont, 'T', 'C', 0, '');
        $retValue = $sY;
        if ($this->tpAmb != 1) {
            $x = 10;
            if ($this->orientacao == 'P') {
                $y = round($this->hPrint*2/3, 0);
            } else {
                $y = round($this->hPrint/2, 0);
            }
            $h = 5;
            $w = $maxW-(2*$x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array('font'=>$this->fontePadrao,'size'=>48,'style'=>'B');
            $this->pTextBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $aFont = array('font'=>$this->fontePadrao,'size'=>30,'style'=>'B');
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->pTextBox($x, $y+14, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        return $retValue;
    }
    
    /**
     * zBody
     * @param type $x
     * @param int $y
     */
    private function zBody($x, $y)
    {
        $maxW = $this->wPrint;
        $texto = 'JUSTIFICATIVA DO CANCELAMENTO';
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'B');
        $this->pTextBox($x, $y, $maxW, 5, $texto, $aFont, 'T', 'L', 0, '', false);
        $y += 5;
        $this->pTextBox($x, $y, $maxW, 210);
        $texto = $this->xJust;
        $aFont = array('font'=>$this->fontePadrao,'size'=>12,'style'=>'B');
        $this->pTextBox($x+2, $y+2, $maxW-2, 150, $texto, $aFont, 'T', 'L', 0, '', false);
    }
    
    /**
     * zFooter
     * @param type $x
     * @param type $y
     */
    private function zFooter($x, $y)
    {
        $w = $this->wPrint;
        $texto = "Este documento é uma representação gráfica do Protocolo "
            . "de Cancelamento de Nota Fiscal Eletrônica e foi impresso apenas "
            . "para sua informação e não possue validade fiscal.\n O Protocolo deve ser recebido "
            . "e mantido em arquivo eletrônico XML e pode ser consultada através dos Portais das SEFAZ.";
        $aFont = array('font'=>$this->fontePadrao,'size'=>10,'style'=>'I');
        $this->pTextBox($x, $y, $w, 20, $texto, $aFont, 'T', 'C', 0, '', false);
        $y = $this->hPrint -4;
        $texto = "Impresso em  ". date('d/m/Y   H:i:s');
        $w = $this->wPrint-4;
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'L', 0, '');
        $texto = "DaCancnfeNFePHP ver. " . $this->version .  "  Powered by NFePHP"
            . " (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array('font'=>$this->fontePadrao,'size'=>6,'style'=>'I');
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'R', 0, 'http://www.nfephp.org');
    }
}
