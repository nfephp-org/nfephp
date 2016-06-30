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
 * @package   NFePHP
 * @name      Dacce.php
 * @version   0.1.4
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright 2009-2012 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *              Fernando Mertins <fernando dot mertins at gmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Lucas Vaccaro <lucas-vaccaro at outlook dot com>
 */

namespace NFePHP\Extras;

use NFePHP\Extras\NfephpException;
use NFePHP\Extras\PdfNFePHP;
use NFePHP\Extras\CommonNFePHP;
use NFePHP\Extras\DocumentoNFePHP;
use NFePHP\Extras\DomDocumentNFePHP;

//definição do caminho para o diretorio com as fontes do FDPF
if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH', 'font/');
}

class Dacce extends CommonNFePHP implements DocumentoNFePHP
{
    public $logoAlign = 'C'; // alinhamento do logo
    public $yDados = 0;
    public $debugMode = 0; // ativa ou desativa o modo de debug
    public $aEnd = array();

    protected $pdf; // objeto fpdf()
    protected $xml; // string XML NFe
    protected $logomarca = ''; // path para logomarca em jpg
    protected $errMsg = ''; // mesagens de erro
    protected $errStatus = false; // status de erro TRUE um erro ocorreu FALSE sem erros
    protected $orientacao = 'P'; // orientação da DANFE P-Retrato ou L-Paisagem
    protected $papel = 'A4'; // formato do papel
    protected $destino = 'I'; // destino do arquivo pdf I-borwser, S-retorna o arquivo,
                              //D-força download, F-salva em arquivo local
    protected $pdfDir = ''; // diretorio para salvar o pdf com a opção de destino = F
    protected $fontePadrao = 'Times'; // Nome da Fonte para gerar o DANFE
    protected $version = '0.1.1';
    protected $wPrint; // largura imprimivel
    protected $hPrint; // comprimento imprimivel
    protected $wCanhoto; // largura do canhoto para a formatação paisagem
    protected $formatoChave = "#### #### #### #### #### #### #### #### #### #### ####";
    // variaveis da carta de correção
    public $id;
    public $chNFe;
    public $tpAmb;
    protected $cOrgao;
    protected $xCorrecao;
    protected $xCondUso;
    protected $dhEvento;
    protected $cStat;
    protected $xMotivo;
    protected $CNPJDest = '';
    protected $CPFDest = '';
    protected $dhRegEvento;
    protected $nProt;

    // objetos
    private $dom;
    private $procEventoNFe;
    private $evento;
    private $infEvento;
    private $retEvento;
    private $retInfEvento;

    /**
     * __construct
     *
     * @param string $docXML      Arquivo XML (diretório ou string)
     * @param string $sOrientacao (Opcional) Orientação da impressão P-retrato L-Paisagem
     * @param string $sPapel      Tamanho do papel (Ex. A4)
     * @param string $sPathLogo   Caminho para o arquivo do logo
     * @param string $sDestino    Destino do PDF I-browser D-download S-string F-salva
     * @param array  $aEnd        array com o endereço do emitente
     * @param string $sDirPDF     Caminho para o diretorio de armazenamento dos arquivos PDF
     * @param string $fonteDANFE  Nome da fonte alternativa do DAnfe
     * @param number $mododebug   0-Não 1-Sim e 2-nada (2 default)
     */
    public function __construct(
        $docXML = '',
        $sOrientacao = '',
        $sPapel = '',
        $sPathLogo = '',
        $sDestino = 'I',
        $aEnd = '',
        $sDirPDF = '',
        $fontePDF = '',
        $mododebug = 0
    ) {
        if (is_numeric($mododebug)) {
            $this->debugMode = (int) $mododebug;
        }
        if ($this->debugMode === 1) {
            // ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } elseif ($this->debugMode === 0) {
            // desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        if (is_array($aEnd)) {
            $this->aEnd = $aEnd;
        }
        $this->orientacao = $sOrientacao;
        $this->papel = $sPapel;
        $this->pdf = '';
        $this->xml = $docXML;
        $this->logomarca = $sPathLogo;
        $this->destino = $sDestino;
        $this->pdfDir = $sDirPDF;
        // verifica se foi passa a fonte a ser usada
        if (empty($fontePDF)) {
            $this->fontePadrao = 'Times';
        } else {
            $this->fontePadrao = $fontePDF;
        }
        // se for passado o xml
        if (!empty($this->xml)) {
            if (is_file($this->xml)) {
                $this->xml = file_get_contents($this->xml);
            }
            $this->dom = new DomDocumentNFePHP();
            $this->dom->loadXML($this->xml);
            $this->procEventoNFe = $this->dom->getElementsByTagName("procEventoNFe")->item(0);
            $this->evento = $this->procEventoNFe->getElementsByTagName("evento")->item(0);
            $this->retEvento = $this->procEventoNFe->getElementsByTagName("retEvento")->item(0);
            $this->infEvento = $this->evento->getElementsByTagName("infEvento")->item(0);
            $this->retInfEvento = $this->retEvento->getElementsByTagName("infEvento")->item(0);
            $tpEvento = $this->infEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue;
            if ($tpEvento != '110110') {
                $this->errMsg = 'Um evento de CC-e deve ser passado.';
                $this->errStatus = true;
                throw new NfephpException($this->errMsg);
            }
            $this->id = str_replace('ID', '', $this->infEvento->getAttribute("Id"));
            $this->chNFe = $this->infEvento->getElementsByTagName("chNFe")->item(0)->nodeValue;
            $this->tpAmb = $this->infEvento->getElementsByTagName("tpAmb")->item(0)->nodeValue;
            $this->cOrgao = $this->infEvento->getElementsByTagName("cOrgao")->item(0)->nodeValue;
            $this->xCorrecao = $this->infEvento->getElementsByTagName("xCorrecao")->item(0)->nodeValue;
            $this->xCondUso = $this->infEvento->getElementsByTagName("xCondUso")->item(0)->nodeValue;
            $this->dhEvento = $this->infEvento->getElementsByTagName("dhEvento")->item(0)->nodeValue;
            $this->cStat = $this->retInfEvento->getElementsByTagName("cStat")->item(0)->nodeValue;
            $this->xMotivo = $this->retInfEvento->getElementsByTagName("xMotivo")->item(0)->nodeValue;
            $this->CNPJDest = !empty($this->retInfEvento->getElementsByTagName("CNPJDest")->item(0)->nodeValue)
                ? $this->retInfEvento->getElementsByTagName("CNPJDest")->item(0)->nodeValue
                : '';
            $this->CPFDest = !empty($this->retInfEvento->getElementsByTagName("CPFDest")->item(0)->nodeValue)
                ? $this->retInfEvento->getElementsByTagName("CPFDest")->item(0)->nodeValue
                : '';
            $this->dhRegEvento = $this->retInfEvento->getElementsByTagName("dhRegEvento")->item(0)->nodeValue;
            $this->nProt = $this->retInfEvento->getElementsByTagName("nProt")->item(0)->nodeValue;
        }
    } // fim __construct

    /**
     * simpleConsistencyCheck
     *
     * @return bool Retorna se o documenento se parece com um Evento (condicao necessaria porem nao suficiente)
     */
    public function simpleConsistencyCheck()
    {
        if ($this->xml == null || $this->infEvento == null || $this->retEvento == null) {
            return false;
        }
        return true;
    }

    /**
     * monta
     *
     * @param string $orientacao
     * @param string $papel
     * @param string $logoAlign
     */
    public function monta($orientacao = '', $papel = 'A4', $logoAlign = 'C')
    {
        $this->orientacao = $orientacao;
        $this->papel = $papel;
        $this->logoAlign = $logoAlign;
        $this->pBuildDACCE();
    }

    /**
     * pBuildDACCE
     */
    private function pBuildDACCE()
    {
        $this->pdf = new PdfNFePHP($this->orientacao, 'mm', $this->papel);
        if ($this->orientacao == 'P') {
            // margens do PDF
            $margSup = 2;
            $margEsq = 2;
            $margDir = 2;
            // posição inicial do relatorio
            $xInic = 1;
            $yInic = 1;
            if ($this->papel == 'A4') { // A4 210x297mm
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
            if ($papel == 'A4') { // A4 210x297mm
                $maxH = 210;
                $maxW = 297;
            }
        } // orientação

        // largura imprimivel em mm
        $this->wPrint = $maxW - ($margEsq + $xInic);
        // comprimento imprimivel em mm
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
        // montagem da página
        $pag = 1;
        $x = $xInic;
        $y = $yInic;
        // coloca o cabeçalho
        $y = $this->pHeader($x, $y, $pag);
        // coloca os dados da CCe
        $y = $this->pBody($x, $y + 15);
        // coloca os dados da CCe
        $y = $this->pFooter($x, $y + $this->hPrint - 20);
    } // fim pBuildDACCE

    /**
     * pHeader
     *
     * @param  number $x
     * @param  number $y
     * @param  number $pag
     * @return number
     */
    private function pHeader($x, $y, $pag)
    {
        $oldX = $x;
        $oldY = $y;
        $maxW = $this->wPrint;

        // ####################################################################################
        // coluna esquerda identificação do emitente
        $w = round($maxW * 0.41, 0); // 80;
        if ($this->orientacao == 'P') {
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 6,
                'style' => 'I'
            );
        } else {
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 8,
                'style' => 'B'
            );
        }
        $w1 = $w;
        $h = 32;
        $oldY += $h;
        $this->pTextBox($x, $y, $w, $h);
        $texto = 'IDENTIFICAÇÃO DO EMITENTE';
        $this->pTextBox($x, $y, $w, 5, $texto, $aFont, 'T', 'C', 0, '');
        if (is_file($this->logomarca)) {
            $logoInfo = getimagesize($this->logomarca);
            // largura da imagem em mm
            $logoWmm = ($logoInfo[0] / 72) * 25.4;
            // altura da imagem em mm
            $logoHmm = ($logoInfo[1] / 72) * 25.4;
            if ($this->logoAlign == 'L') {
                $nImgW = round($w / 3, 0);
                $nImgH = round($logoHmm * ($nImgW / $logoWmm), 0);
                $xImg = $x + 1;
                $yImg = round(($h - $nImgH) / 2, 0) + $y;
                // estabelecer posições do texto
                $x1 = round($xImg + $nImgW + 1, 0);
                $y1 = round($h / 3 + $y, 0);
                $tw = round(2 * $w / 3, 0);
            }
            if ($this->logoAlign == 'C') {
                $nImgH = round($h / 3, 0);
                $nImgW = round($logoWmm * ($nImgH / $logoHmm), 0);
                $xImg = round(($w - $nImgW) / 2 + $x, 0);
                $yImg = $y + 3;
                $x1 = $x;
                $y1 = round($yImg + $nImgH + 1, 0);
                $tw = $w;
            }
            if ($this->logoAlign == 'R') {
                $nImgW = round($w / 3, 0);
                $nImgH = round($logoHmm * ($nImgW / $logoWmm), 0);
                $xImg = round($x + ($w - (1 + $nImgW)), 0);
                $yImg = round(($h - $nImgH) / 2, 0) + $y;
                $x1 = $x;
                $y1 = round($h / 3 + $y, 0);
                $tw = round(2 * $w / 3, 0);
            }
            $this->pdf->Image($this->logomarca, $xImg, $yImg, $nImgW, $nImgH);
        } else {
            $x1 = $x;
            $y1 = round($h / 3 + $y, 0);
            $tw = $w;
        }

        // Nome emitente
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'B'
        );
        $texto = $this->aEnd['razao'];
        $this->pTextBox($x1, $y1, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');

        // endereço
        $y1 = $y1 + 6;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => ''
        );
        $lgr = $this->aEnd['logradouro'];
        $nro = $this->aEnd['numero'];
        $cpl = $this->aEnd['complemento'];
        $bairro = $this->aEnd['bairro'];
        $CEP = $this->aEnd['CEP'];
        $CEP = $this->pFormat($CEP, "#####-###");
        $mun = $this->aEnd['municipio'];
        $UF = $this->aEnd['UF'];
        $fone = $this->aEnd['telefone'];
        $email = $this->aEnd['email'];
        $foneLen = strlen($fone);
        if ($foneLen > 0) {
            $fone2 = substr($fone, 0, $foneLen - 4);
            $fone1 = substr($fone, 0, $foneLen - 8);
            $fone = '(' . $fone1 . ') ' . substr($fone2, - 4) . '-' . substr($fone, - 4);
        } else {
            $fone = '';
        }
        if ($email != '') {
            $email = 'Email: ' . $email;
        }
        $texto = $lgr . ", " . $nro . $cpl . "\n" . $bairro . " - " . $CEP . "\n"
            . $mun . " - " . $UF . " " . $fone . "\n" . $email;
        $this->pTextBox($x1, $y1 - 2, $tw, 8, $texto, $aFont, 'T', 'C', 0, '');

        // ##################################################

        $w2 = round($maxW - $w, 0);
        $x += $w;
        $this->pTextBox($x, $y, $w2, $h);

        $y1 = $y + $h;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 16,
            'style' => 'B'
        );
        $this->pTextBox($x, $y + 2, $w2, 8, 'Representação Gráfica de CC-e', $aFont, 'T', 'C', 0, '');

        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'I'
        );
        $this->pTextBox($x, $y + 7, $w2, 8, '(Carta de Correção Eletrônica)', $aFont, 'T', 'C', 0, '');

        $texto = 'ID do Evento: ' . $this->id;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => ''
        );
        $this->pTextBox($x, $y + 15, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $tsHora = $this->pConvertTime($this->dhEvento);
        $texto = 'Criado em : ' . date('d/m/Y   H:i:s', $tsHora);
        $this->pTextBox($x, $y + 20, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $tsHora = $this->pConvertTime($this->dhRegEvento);
        $texto = 'Prococolo: ' . $this->nProt . '  -  Registrado na SEFAZ em: ' . date('d/m/Y   H:i:s', $tsHora);
        $this->pTextBox($x, $y + 25, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        // $cStat;
        // $tpAmb;
        // ####################################################

        $x = $oldX;
        $this->pTextBox($x, $y1, $maxW, 40);
        $sY = $y1 + 40;
        $texto = 'De acordo com as determinações legais vigentes, vimos por meio desta comunicar-lhe'.
            ' que a Nota Fiscal, abaixo referenciada, contêm irregularidades que estão destacadas e' .
            ' suas respectivas correções, solicitamos que sejam aplicadas essas correções ao executar'.
            ' seus lançamentos fiscais.';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => ''
        );
        $this->pTextBox($x + 5, $y1, $maxW - 5, 20, $texto, $aFont, 'T', 'L', 0, '', false);

        // ############################################
        $x = $oldX;
        $y = $y1;
        if ($this->CNPJDest != '') {
            $texto = 'CNPJ do Destinatário: ' . $this->pFormat($this->CNPJDest, "##.###.###/####-##");
        }
        if ($this->CPFDest != '') {
            $texto = 'CPF do Destinatário: ' . $this->pFormat($this->CPFDest, "###.###.###-##");
        }
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'B'
        );
        $this->pTextBox($x + 2, $y + 13, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $numNF = substr($this->chNFe, 25, 9);
        $serie = substr($this->chNFe, 22, 3);
        $numNF = $this->pFormat($numNF, "###.###.###");
        $texto = "Nota Fiscal: " . $numNF . '  -   Série: ' . $serie;
        $this->pTextBox($x + 2, $y + 19, $w2, 8, $texto, $aFont, 'T', 'L', 0, '');

        $bW = 87;
        $bH = 15;
        $x = 55;
        $y = $y1 + 13;
        $w = $maxW;
        $this->pdf->SetFillColor(0, 0, 0);
        $this->pdf->Code128($x + (($w - $bW) / 2), $y + 2, $this->chNFe, $bW, $bH);
        $this->pdf->SetFillColor(255, 255, 255);
        $y1 = $y + 2 + $bH;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => ''
        );
        $texto = $this->pFormat($this->chNFe, $this->formatoChave);
        $this->pTextBox($x, $y1, $w - 2, $h, $texto, $aFont, 'T', 'C', 0, '');

        // $sY += 1;
        $x = $oldX;
        $this->pTextBox($x, $sY, $maxW, 15);
        $texto = $this->xCondUso;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 8,
            'style' => 'I'
        );
        $this->pTextBox($x + 2, $sY + 2, $maxW - 2, 15, $texto, $aFont, 'T', 'L', 0, '', false);

        return $sY + 2;
    } // fim pHeader

    /**
     * pBody
     *
     * @param number $x
     * @param number $y
     */
    private function pBody($x, $y)
    {
        $maxW = $this->wPrint;
        $texto = 'CORREÇÕES A SEREM CONSIDERADAS';
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => 'B'
        );
        $this->pTextBox($x, $y, $maxW, 5, $texto, $aFont, 'T', 'L', 0, '', false);

        $y += 5;
        $this->pTextBox($x, $y, $maxW, 190);
        $texto = str_replace(";", PHP_EOL, $this->xCorrecao);
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 12,
            'style' => 'B'
        );
        $this->pTextBox($x + 2, $y + 2, $maxW - 2, 150, $texto, $aFont, 'T', 'L', 0, '', false);

        if ($this->tpAmb != 1) {
            $x = 10;
            if ($this->orientacao == 'P') {
                $y = round($this->hPrint * 2 / 3, 0);
            } else {
                $y = round($this->hPrint / 2, 0);
            }
            $h = 5;
            $w = $maxW - (2 * $x);
            $this->pdf->SetTextColor(90, 90, 90);
            $texto = "SEM VALOR FISCAL";
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 48,
                'style' => 'B'
            );
            $this->pTextBox($x, $y, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $aFont = array(
                'font' => $this->fontePadrao,
                'size' => 30,
                'style' => 'B'
            );
            $texto = "AMBIENTE DE HOMOLOGAÇÃO";
            $this->pTextBox($x, $y + 14, $w, $h, $texto, $aFont, 'C', 'C', 0, '');
            $this->pdf->SetTextColor(0, 0, 0);
        }
    } // fim pBody

    /**
     * pFooter
     *
     * @param number $x
     * @param number $y
     */
    protected function pFooter($x, $y)
    {
        $w = $this->wPrint;
        $texto = "Este documento é uma representação gráfica da CC-e e foi impresso apenas para sua"
            . " informação e não possue validade fiscal.\n A CC-e deve ser recebida e mantida em"
            . " arquivo eletrônico XML e pode ser consultada através dos Portais das SEFAZ.";
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 10,
            'style' => 'I'
        );
        $this->pTextBox($x, $y, $w, 20, $texto, $aFont, 'T', 'C', 0, '', false);

        $y = $this->hPrint - 4;
        $texto = "Impresso em  " . date('d/m/Y   H:i:s');
        $w = $this->wPrint - 4;
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'I'
        );
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'L', 0, '');

        $texto = "Dacce ver. " . $this->version . "  Powered by NFePHP (GNU/GPLv3 GNU/LGPLv3) © www.nfephp.org";
        $aFont = array(
            'font' => $this->fontePadrao,
            'size' => 6,
            'style' => 'I'
        );
        $this->pTextBox($x, $y, $w, 4, $texto, $aFont, 'T', 'R', 0, 'http://www.nfephp.org');
    } // fim pFooter

    /**
     * printDocument
     *
     * @param  string $nome
     * @param  string $destino
     * @param  string $printer
     * @return mixed
     */
    public function printDocument($nome = '', $destino = 'I', $printer = '')
    {
        return $this->printDACCE($nome, $destino, $printer);
    }

    /**
     * printDACCE
     *
     * @param  string $nome
     * @param  string $destino
     * @param  string $printer
     * @return mixed
     */
    public function printDACCE($nome = '', $destino = 'I', $printer = '')
    {
        if ($this->pdf == null) {
            $this->pBuildDACCE();
        }
        return $this->pdf->Output($nome, $destino);
    }
}
