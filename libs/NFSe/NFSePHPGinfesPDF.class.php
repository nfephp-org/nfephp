<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou 
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da 
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>. 
 *
 * @package   NFePHP
 * @name      NFSeSEGinfesPDF
 * @version   0.0.1
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Hugo Cegana <cegana at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *            Roberto Leite Machado <linux dot rlm at gamil dot com>
 * 
 */

require_once('../External/FPDF/fpdf.php');


class NFSePHPGinfesPDF extends FPDF {
    
    
    protected $arquivo_xml_origem;
    protected $gif_brasao_prefeitura;
    protected $gif_logo_empresa;
    protected $aParser;
    
    public function NFSePHPGinfesPDF($orientation='P', $unit='mm', $format='A4', $arquivo_xml_origem='', $gif_brasao_prefeitura = '', $gif_logo_empresa='', $aParser=false){
        
        parent::__construct($orientation, $unit, $format);
        
        $this->gif_brasao_prefeitura = $gif_brasao_prefeitura;
        $this->gif_logo_empresa      = $gif_logo_empresa;
        $this->arquivo_xml_origem    = $arquivo_xml_origem;                
        $this->aParser               = $aParser;                
    }//fim NFSePHPGinfesPDF
    
    

    /**
     * 
     */
    public function Header() {

        $xml =  file_get_contents($this->arquivo_xml_origem) ;

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = FALSE;
        $doc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

        //DESENHA AS BORDAS DO HEADER
        $this->Line(0.5, 1, 20.5, 1);
        $this->Line(0.5, 1, 0.5, 10.9);


        // CABEÇALHO
        // LOGO
        if (is_file($this->gif_brasao_prefeitura)){
            $this->Image($this->gif_brasao_prefeitura, 1.1, 1.2, 2);
        }

        // TITULO
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(3.7, 1);
        $this->MultiCell(13.4, 0.7, $this->aParser["NomePrefeitura"], 1, "C");

        //NUMERO NFSE
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(17.1, 1);
        $this->MultiCell(3.4, 0.7, "Número da NFS-e:\n" . $doc->getElementsByTagName("Numero")->item(0)->nodeValue * 1 . "\nPag. " . $this->PageNo() . "/{nb}", 1, "C");

        // CAMPO DE INFORMAÇÕES
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(230, 230, 230);

        //LINHA 1
        $this->SetXY(0.5, 3.1);
        $this->Cell(3.2, 0.6, "Emissão", 1, 0, "C", 1);

        $DataEmissao = str_replace("T", " ", $doc->getElementsByTagName("DataEmissao")->item(0)->nodeValue);
        //$DataEmissao = _date_format($DataEmissao, DATE_DATETIME);
        
        //die($DataEmissao);

        $this->Cell(3.2, 0.6, $DataEmissao, 1, 0, "C", 0);
        $this->Cell(3.4, 0.6, "Competência", 1, 0, "C", 1);

        $Competencia = substr($doc->getElementsByTagName("Competencia")->item(0)->nodeValue, 4, 2) . "/" . substr($doc->getElementsByTagName("Competencia")->item(0)->nodeValue, 0, 4);

        $this->Cell(3.4, 0.6, $Competencia, 1, 0, "C");
        $this->Cell(3.4, 0.6, "Código de Verificação", 1, 0, "C", 1);
        $this->Cell(3.4, 0.6, $doc->getElementsByTagName("CodigoVerificacao")->item(0)->nodeValue, 1, 1, "C");

        //LINHA 2
        $this->SetXY(0.5, 3.7);
        $this->Cell(3.2, 0.6, "Nùmero RPS", 1, 0, "C", 1);
        $this->Cell(3.2, 0.6, $doc->getElementsByTagName("Numero")->item(1)->nodeValue, 1, 0, "C");
        $this->Cell(3.4, 0.6, "NFS-e Substituida", 1, 0, "C", 1);

        if ($doc->getElementsByTagName("NumeroNFSeSubstituida")->item(0)->nodeValue <> "") {
            $NFSubst = $doc->getElementsByTagName("NumeroNFSeSubstituida")->item(0)->nodeValue;
        } else {
            $NFSubst = "-";
        }

        $this->Cell(3.4, 0.6, $NFSubst, 1, 0, "C");
        $this->Cell(3.4, 0.6, "Local da Prestação", 1, 0, "C", 1);
        $this->Cell(3.4, 0.6, $this->aParser['Municipio'] . " - " . $this->aParser['UfMunicipio'], 1, 1, "C");
        //FIM CAMPO DE INFORMAÇÕES
        // FIM CABEÇALHO
        // INICIO PRESTADOR
        $this->SetFillColor(150, 150, 150);
        $this->SetXY(0.5, 4.3);
        $this->SetFont('Arial', '', 12);
        $this->Cell(20, 0.7, "Dados do Prestador de Serviços", 1, 1, "C", 1);

        // LOGO
        
        if (is_file($this->gif_logo_empresa)){
            $this->Image($this->gif_logo_empresa, 0.7, 5.2, 2.8);
        }

        // DADOS DOS PRESTADOR
        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Arial', '', 7);
        $this->setXY(3.7, 5);
        $this->Cell(2.6, 0.5, "Nome / Razão Social", 1, 0, "C", 1);
        $this->Cell(14.2, 0.5, $doc->getElementsByTagName("RazaoSocial")->item(0)->nodeValue, 1, 1);

        $this->setXY(3.7, 5.5);
        $this->Cell(2.6, 0.5, "Nome Fantasia", 1, 0, "C", 1);
        $this->Cell(14.2, 0.5, $this->aParser["FantasiaPrestador"], 1, 1);

        $this->setXY(3.7, 6);
        $this->Cell(2.6, 0.5, "CPF/CNPJ", 1, 0, "C", 1);

        $CnpjPrestador = $doc->getElementsByTagName("Cnpj")->item(0)->nodeValue;
        $CnpjPrestador = substr($CnpjPrestador, 0, 2) . "." . substr($CnpjPrestador, 2, 3) . "." . substr($CnpjPrestador, 5, 3) . "/" . substr($CnpjPrestador, 8, 4) . "-" . substr($CnpjPrestador, 12, 2);

        $this->Cell(3, 0.5, $CnpjPrestador, 1);
        $this->Cell(2.3, 0.5, "Inscrição Municipal", 1, 0, "C", 1);
        $this->Cell(2.6, 0.5, $doc->getElementsByTagName("InscricaoMunicipal")->item(0)->nodeValue, 1);
        $this->Cell(1.2, 0.5, "Município", 1, 0, "C", 1);
        $this->Cell(5.1, 0.5, $this->aParser["MunicipioPrestador"] . " - " . $this->aParser["UfPrestador"], 1, 1);

        $this->setXY(3.7, 6.5);
        $this->Cell(2.6, 0.5, "Endereço e CEP", 1, 0, "C", 1);

        $endereco = $doc->getElementsByTagName('Endereco')->item(1)->nodeValue . ", " . $doc->getElementsByTagName('Numero')->item(2)->nodeValue;
        $endereco.= " - " . $doc->getElementsByTagName("Bairro")->item(0)->nodeValue . " - " . $this->aParse['MunicipioPrestador'] . " - " . $this->aParse['UfPrestador'] . " CEP: " . $doc->getElementsByTagName("Cep")->item(0)->nodeValue;

        $this->Cell(14.2, 0.5, $endereco, 1, 1);

        $this->setXY(3.7, 7);
        $this->Cell(2.6, 0.5, "Complemento", 1, 0, "C", 1);
        $this->Cell(3, 0.5, $doc->getElementsByTagName("Complemento")->item(0)->nodeValue, 1);
        $this->Cell(2.3, 0.5, "Telefone", 1, 0, "C", 1);
        $this->Cell(2.6, 0.5, $doc->getElementsByTagName("Telefone")->item(0)->nodeValue, 1);
        $this->Cell(1.2, 0.5, "E-mail", 1, 0, "C", 1);
        $this->SetFont('Arial', '', 5);
        $this->Cell(5.1, 0.5, $this->aParser['EmailPrestador'], 1, 1);
        // FIM DADOS PRESTADOR
        // FIM PRESTADOR
        
        // INICIO TOMADOR
        $this->SetFillColor(150, 150, 150);
        $this->SetXY(0.5, 7.5);
        $this->SetFont('Arial', '', 12);
        $this->Cell(20, 0.7, "Dados do Tomador de Serviços", 1, 1, "C", 1);

        // DADOS TOMADOR
        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Arial', '', 7);

        $this->SetXY(0.5, 8.2);
        $this->Cell(3.2, 0.5, "Nome / Razão Social", 1, 0, "C", 1);
        $this->Cell(16.8, 0.5, $doc->getElementsByTagName("RazaoSocial")->item(1)->nodeValue, 1, 1);

        $this->SetXY(0.5, 8.7);
        $this->Cell(3.2, 0.5, "CPF/CNPJ", 1, 0, "C", 1);

        if (strlen($doc->getElementsByTagName("Cnpj")->item(1)->nodeValue) <> "") {
            $DocTomador = $doc->getElementsByTagName("Cnpj")->item(1)->nodeValue;
            $DocTomador = substr($DocTomador, 0, 2) . "." . substr($DocTomador, 2, 3) . "." . substr($DocTomador, 5, 3) . "/" . substr($DocTomador, 8, 4) . "-" . substr($DocTomador, 12, 2);
        } else {
            if (strlen($doc->getElementsByTagName("Cpf")->item(0)->nodeValue) <> "") {
                $DocTomador = $doc->getElementsByTagName("Cpf")->item(0)->nodeValue;
                $DocTomador = substr($DocTomador, 0, 3) . "." . substr($DocTomador, 3, 3) . "." . substr($DocTomador, 6, 3) . "-" . substr($DocTomador, 9, 2);
            } else {
                $DocTomador = "";
            }
        }

        $this->Cell(3, 0.5, $DocTomador, 1);
        $this->Cell(2.6, 0.5, "Inscrição Municipal", 1, 0, "C", 1);
        $this->Cell(2.3, 0.5, $this->aParser['InscricaoMunicipalTomador'], 1);
        $this->Cell(1.5, 0.5, "Município", 1, 0, "C", 1);
        $this->Cell(7.4, 0.5, $this->aParser['MunicipioTomador'] . " - " . $this->aParser['UfTomador'], 1, 1);

        $this->SetXY(0.5, 9.2);
        $this->Cell(3.2, 0.5, "Endereço e CEP", 1, 0, "C", 1);

        $endereco = $doc->getElementsByTagName('Endereco')->item(3)->nodeValue . ", " . $doc->getElementsByTagName('Numero')->item(3)->nodeValue;
        $endereco.= " - " . $doc->getElementsByTagName("Bairro")->item(1)->nodeValue . " - " . $this->aParse['MunicipioTomador'] . " - " . $this->aParse['UfTomador'] . " CEP: " . $doc->getElementsByTagName("Cep")->item(1)->nodeValue;

        $this->Cell(16.8, 0.5, $endereco, 1, 1);

        $this->SetXY(0.5, 9.7);
        $this->Cell(3.2, 0.5, "Complemento", 1, 0, "C", 1);
        $this->Cell(3, 0.5, $doc->getElementsByTagName("Complemento")->item(1)->nodeValue, 1);
        $this->Cell(2.6, 0.5, "Telefone", 1, 0, "C", 1);
        $this->Cell(2.3, 0.5, $this->aParser['TelefoneTomador'], 1);
        $this->Cell(1.5, 0.5, "E-mail", 1, 0, "C", 1);
        $this->Cell(7.4, 0.5, $this->aParser['EmailTomador'], 1, 1);
        // FIM DADOS TOMADOR
        // FIM TOMADOR
        // TITULO DISCRIMINAÇÃO DOS SERVIÇOS
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(150, 150, 150);
        $this->SetXY(0.5, 10.2);
        $this->Cell(20, 0.7, "Discriminação dos Serviços", 1, 1, "C", 1);
    }


    /**
     * @param string $arquivo_pdf_destino
     */
    public function printNFSe($arquivo_pdf_destino='') {
        $xml = file_get_contents($this->arquivo_xml_origem);
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = FALSE;
        $doc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $numNfse = $doc->getElementsByTagName("NumeroLote")->item(0)->nodeValue;        
        // Instanciation of inherited class
        $this->AliasNbPages();
        $this->SetAutoPageBreak(1, 1);
        $this->AddPage();
        $this->setMargins(0, 0, 0);
        $this->SetFont('Arial', '', 8);
        $servico = explode("|", $doc->getElementsByTagName("Discriminacao")->item(0)->nodeValue);
        $maxLineSize = 115;
        $lines = count($servico);
        $y = $this->getY();
        $z = 0;
        for ($i = 0; $i <= count($servico); $i++) {
            if (strlen($servico[$i]) / $maxLineSize > 0) {
                for ($k = 0; $k < strlen($servico[$i]) / $maxLineSize; $k++) {
                    $content[$z] = substr($servico[$i], $k * $maxLineSize, $maxLineSize);
                    $z++;
                }
                $lines+= $k;
            } else {
                $content[$z] = $servico[$i];
                $z++;
            }
        }
        $zNum = $z - 1;
        if ($zNum <= 18) {
            for ($z = 0; $z < $zNum; $z++) {
                $this->setX(0.5);
                $this->Cell(20, 0.4, $content[$z], 1, 1);
            }
        } else {
            for ($z = 0; $z < ($zNum); $z++) {
                $this->setX(0.5);
                $this->Cell(20, 0.4, $content[$z], 1, 1);
            }

            for ($j = 0; $j < 44 - $zNum; $j++) {
                if ($j == 43 - $zNum) {
                    $this->setX(0.5);
                    $this->Cell(20, 0.4, "CONTINUA NA PROXIMA PAGINA", 0, 1, "C");
                } else {
                    $this->setX(0.5);
                    $this->Cell(20, 0.4, " ", 0, 1);
                }
            }

            $this->setX(0.5);
            $this->Cell(20, 0.4, $content[$zNum], 1, 1);
        }
        if ($lines - count($servico) > 5) {
            $addLine = (($lines - count($servico)) * 0.4);
        } else {
            $addLine = 2.0;
        }

        // CODIGO DO SERVICO
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(150, 150, 150);
        $y = $this->getY();
        $this->SetXY(0.5, $y);
        $this->Cell(20, 0.7, "Código do Serviço / Atividade", 1, 1, "C", 1);
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(230, 230, 230);
        $this->setX(0.5);
        $this->Cell(20, 0.6, $doc->getElementsByTagName("ItemListaServico")->item(0)->nodeValue . " / " . $doc->getElementsByTagName("CodigoTributacaoMunicipio")->item(0)->nodeValue . " - " . $doc->aParser['ServicoPrestado'], 1, 1, 'C', 0);
        // FIM CODIGO DO SERVIÇO
         
        // OBRAS
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(150, 150, 150);
        $this->SetX(0.5);
        $this->Cell(20, 0.7, "Detalhamento Específico da Construção Civil", 1, 1, "C", 1);
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(230, 230, 230);
        $this->setX(0.5);
        $this->Cell(5, 0.6, 'Código da Obra', 1, 0, 'C', 1);
        $this->Cell(5, 0.6, $doc->getElementsByTagName("CodigoObra")->item(0)->nodeValue, 1, 0, 'C', 0);
        $this->Cell(5, 0.6, 'Código ART', 1, 0, 'C', 1);
        $this->Cell(5, 0.6, $doc->getElementsByTagName("Art")->item(0)->nodeValue, 1, 1, 'C', 0);
        // FIM OBRAS
        
        // TRIBUTOS FEDERAIS
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(150, 150, 150);
        $this->SetX(0.5);
        $this->Cell(20, 0.7, "Tributos Federais", 1, 1, "C", 1);
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(230, 230, 230);
        $this->setX(0.5);
        $this->Cell(2, 0.6, 'PIS', 1, 0, 'C', 1);
        $this->Cell(2, 0.6, number_format($doc->getElementsByTagName("ValorPis")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(2, 0.6, 'COFINS', 1, 0, 'C', 1);
        $this->Cell(2, 0.6, number_format($doc->getElementsByTagName("ValorCofins")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(2, 0.6, 'IR (R$)', 1, 0, 'C', 1);
        $this->Cell(2, 0.6, number_format($doc->getElementsByTagName("ValorIr")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(2, 0.6, 'INSS (R$)', 1, 0, 'C', 1);
        $this->Cell(2, 0.6, number_format($doc->getElementsByTagName("ValorInss")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(2, 0.6, 'CSLL (R$)', 1, 0, 'C', 1);
        $this->Cell(2, 0.6, number_format($doc->getElementsByTagName("ValorCsll")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        // FIM TRIBUTOS FEDERAIS
        
        // DETALHAMENTOS, RETENÇÕES E CALCULOS ISSQN
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(150, 150, 150);
        $this->SetX(0.5);
        $this->Cell(7.5, 0.7, "Detalhamento de valores - Prestador dos Serviços", 1, 0, "C", 1);
        $this->Cell(5.0, 0.7, "Outras Retenções", 1, 0, "C", 1);
        $this->Cell(7.5, 0.7, "Cálculo do ISSQN devido no Município", 1, 1, "C", 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(230, 230, 230);
        $this->setX(0.5);
        $this->Cell(4.5, 0.7, 'Valor dos Serviços', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("ValorServicos")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(5.0, 0.7, 'Natureza Operação', 1, 0, 'C', 1);
        $this->Cell(4.5, 0.7, 'Valor dos Serviços', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("ValorServicos")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        $this->setX(0.5);
        $this->Cell(4.5, 0.7, '(-) Descontos Incondicionados', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("DescontoIncondicionado")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(5.0, 0.7, number_format($doc->getElementsByTagName("NaturezaOperacao")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(4.5, 0.7, '(-) Deduções permitidas em lei', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("DescontoCondicionado")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0); //GABRIEL / HUGO VERIFICAR CONTEUDO
        $this->setX(0.5);
        $this->Cell(4.5, 0.7, '(-) Desconto condicionado', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("DescontoCondicionado")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(5.0, 0.7, 'Regime Especial Tributação', 1, 0, 'C', 1);
        $this->Cell(4.5, 0.7, '(-) Desconto Incondicionado', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("DescontoIncondicionado")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        $this->setX(0.5);
        $this->Cell(4.5, 0.7, 'Retenções Federais', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("RetencoesFederais")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0); //
        $this->Cell(5.0, 0.7, $doc->getElementsByTagName("RegimeEspecialTributacao")->item(0)->nodeValue, 1, 0, 'C', 0);
        $this->Cell(4.5, 0.7, 'Base de Cálculo', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("BaseCalculo")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        $this->setX(0.5);
        $this->Cell(4.5, 0.7, 'Outras Retenções', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("OutrasRetencoes")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $this->Cell(5.0, 0.7, 'Opção Simples Nacional', 1, 0, 'C', 1);
        $this->Cell(4.5, 0.7, '(x) Alíquota %', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("Aliquota")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        $this->setX(0.5);
        $this->Cell(4.5, 0.7, '(-) ISS Retido', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("IssRetido")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        if ($doc->getElementsByTagName("OptanteSimplesNacional")->item(0)->nodeValue == 0) {
            $optanteSimplesNacional = $doc->getElementsByTagName("OptanteSimplesNacional")->item(0)->nodeValue . " - Não";
        } else {
            $optanteSimplesNacional = $doc->getElementsByTagName("OptanteSimplesNacional")->item(0)->nodeValue . " - Sim";
        }
        $this->Cell(5.0, 0.7, $optanteSimplesNacional, 1, 0, 'C', 1);
        $this->Cell(4.5, 0.7, 'ISS a Reter', 1, 0, 'C', 1);
        $this->Cell(3.0, 0.7, number_format($doc->getElementsByTagName("ValorIssReter")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        $this->setX(0.5);
        $this->Cell(4.5, 1, '(=) Valor Líquido   R$', 1, 0, 'C', 1);
        $this->Cell(3.0, 1, number_format($doc->getElementsByTagName("ValorLiquidoNfse")->item(0)->nodeValue, 2, ",", "."), 1, 0, 'C', 0);
        $x = $this->getX();
        $y = $this->getY();
        $this->Cell(5.0, 0.5, 'Incentivador Cultural', 1, 1, 'C', 1);
        $this->setX($x);
        if ($doc->getElementsByTagName("IncentivadorCultural")->item(0)->nodeValue == 2) {
            $incentivadorCultural = $doc->getElementsByTagName("IncentivadorCultural")->item(0)->nodeValue . " - Não";
        } else {
            $incentivadorCultural = $doc->getElementsByTagName("IncentivadorCultural")->item(0)->nodeValue . " - Sim";
        }
        $this->Cell(5.0, 0.5, $incentivadorCultural, 1, 0, 'C', 0);
        $this->setXY($x + 5, $y);
        $this->Cell(4.5, 1, '(=) Valor do ISS     R$', 1, 0, 'C', 1);
        $this->Cell(3.0, 1, number_format($doc->getElementsByTagName("ValorIss")->item(0)->nodeValue, 2, ",", "."), 1, 1, 'C', 0);
        $this->setX(0.5);
        $this->Cell(1.5, 0.8, 'Avisos', 1, 0, 'C', 0);
        $this->SetFont("Arial", "", 6);
        $this->MultiCell(18.5, 0.4, "-\n ", 0);
        $y = $this->getY();
        $this->Line(0.5, $y, 20.5, $y);
        $this->Line(20.5, $y - 3, 20.5, $y);
        $this->Output($arquivo_pdf_destino,'F');
    } //fim printNFSe

    /**
     * @param $w
     * @param $h
     * @param $txt
     * @param int $border
     * @param string $align
     * @param bool $fill
     */
    public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false){
        parent::MultiCell($w, $h, utf8_decode($txt), $border, $align, $fill);
    }

    /**
     * @param int $w
     * @param int $h
     * @param string $txt
     * @param int $border
     * @param int $ln
     * @param string $align
     * @param bool $fill
     * @param string $link
     */
    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
         parent::Cell($w, $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
    }    

    
    /**
     * 
     * @param float $angle
     * @param int $x
     * @param int $y
     */
    protected function Rotate($angle, $x = -1, $y = -1) {
        if ($x == -1) {
            $x = $this->x;
        }
        if ($y == -1) {
            $y = $this->y;
        }
        if (isset($this->angle) && $this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angle*=M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    } //fim função rotate	
    
    
    
}//fim NFSePHPGinfesPDF
