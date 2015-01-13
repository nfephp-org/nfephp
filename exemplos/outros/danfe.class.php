<?
/**
 * DANFE
 * dfadel
 * 22/07/2009
 *
 * Classe PDF_Code128 obtida em: http://www.fpdf.org/en/script/script88.php
 *
 * TODO: expandir para múltiplas folhas
 */

include_once '../../include/config.inc.php';
include_once('fpdf/fpdf.php');
include_once('fpdf/code128.php');

if (!defined('FPDF_FONTPATH')) {
    define('FPDF_FONTPATH','fpdf/font/');
}

if (!function_exists('dmy2ymd')) {
    function dmy2ymd($data) {
        if (!empty($data)) {
            $needle = "/";
            if (strstr($data, "-")) {
                $needle = "-";
            }
            $dt = explode($needle, $data);
            return "$dt[2]-$dt[1]-$dt[0]";
        }
    }
}

class danfe {

    var $canhoto;   // se true, imprime campos do canhoto
    var $issqn;     // se true, imprime campos do issqn
    var $pdf;       // objeto fpdf()
    var $xml;       // string XML NFe
    var $logo;      // path para logomarca
    var $array_uf;  // usado para obter código do estado do destinatario qdo em contingencia
    var $protocolo; // protocolo de autorizacao deve sair em infCpl
    var $data_hora; // data e hora de autorizacao devem sair em infCpl


    function __construct($xml, $formato="P") {
        $this->canhoto   = true;
        $this->issqn     = false;
        $this->pdf       = new PDF_Code128($formato, 'mm', 'A4');
        $this->xml       = $xml;
        $this->logomarca = 'logomarca.jpg';
        $this->array_uf  = array('SP' => 35);
    }


    function mask_cnpj($c) {
        return sprintf("%s.%s.%s/%s-%s", $c[0].$c[1], $c[2].$c[3].$c[4], $c[5].$c[6].$c[7], $c[8].$c[9].$c[10].$c[11], $c[12].$c[13]);
    }


    function mask_cpf($c) {
        return sprintf("%s.%s.%s-%s", $c[0].$c[1].$c[2], $c[3].$c[4].$c[5], $c[6].$c[7].$c[8], $c[9].$c[10]);
    }


    function mask_nnf($n) {
        $n = sprintf("%09s", $n);
        return sprintf("%s.%s.%s", $n[0].$n[1].$n[2], $n[3].$n[4].$n[5], $n[6].$n[7].$n[8]);
    }

    function mask_chave($c) {
        for ($i=0; $i<=strlen($c); ++$i) {
            $cf.= $c[$i-1];
            if ($i % 4 == 0) {
                $cf.= " ";
            }
        }
        return $cf;
    }


    function calcula_dv($chave35) {
        $multiplicadores = array(2,3,4,5,6,7,8,9);
        $i = 34;
        while ($i >= 0) {
            for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
                $soma_ponderada+= $chave35[$i] * $multiplicadores[$m];
                $i--;
            }
        }
        $resto = $soma_ponderada % 11;
        if ($resto == '0' || $resto == '1') {
            $dv = 0;
        } else {
            $dv = 11 - $resto;
        }
        return $dv;
    }


    function linha($altura, $largura, $esquerda, $superior, $sbordas, $label="", $fonte="", $texto="", $tfonte="") {

        $bordas = array();
        for ($i=0; $i<strlen($sbordas); $i++) {
            $bordas[] = $sbordas[$i];
        }

        if (!$this->canhoto) {
            $superior-= 21;
        }

        // configura largura da linha N=negrito
        if (in_array("N", $bordas)) {
            $this->pdf->SetLineWidth(0.25);
        } else {
            $this->pdf->SetLineWidth(0.05);
        }

        if (in_array("T", $bordas)) {
            $this->pdf->line($esquerda, $superior, ($esquerda+$largura), $superior);
        }
        if (in_array("B", $bordas)) {
            $this->pdf->line($esquerda, ($superior+$altura), ($esquerda+$largura), ($superior+$altura));
        }
        if (in_array("L", $bordas)) {
            $this->pdf->line($esquerda, $superior, $esquerda, ($superior+$altura));
        }
        if (in_array("R", $bordas)) {
            $this->pdf->line(($esquerda+$largura), $superior, ($esquerda+$largura), ($superior+$altura));
        }

        // impressao das labels
        if (!empty($label)) {
            if (empty($fonte)) {
                $fonte = "Courier,B,5,L";
            }
            $f = split(",",$fonte);
            $this->pdf->SetFont($f[0], $f[1], $f[2]);
            $this->pdf->setXY($esquerda-($f[2]/10), $superior+($f[2]/4));
            $this->pdf->Cell($largura, 0, $label, 0, 0, $f[3]);
        }

        // impressao do texto
        if (!empty($texto)) {
            if (empty($tfonte)) {
                $tfonte = "Courier,,7,L";
            }
            $f = split(",",$tfonte);
            $this->pdf->SetFont($f[0], $f[1], $f[2]);
            $this->pdf->setXY($esquerda, $superior+5);
            $this->pdf->Cell($largura, 0, $texto, 0, 0, $f[3]);
        }
    }


    function gera() {

        if (empty($this->xml)) {
            return false;
        }

        $this->pdf->AddPage();

        $dom = new DomDocument;
        $dom->loadXML($this->xml);
        $infNFe     = $dom->getElementsByTagName("infNFe")->item(0);
        $ide        = $dom->getElementsByTagName("ide")->item(0);
        $emit       = $dom->getElementsByTagName("emit")->item(0);
        $dest       = $dom->getElementsByTagName("dest")->item(0);
        $enderEmit  = $dom->getElementsByTagName("enderEmit")->item(0);
        $enderDest  = $dom->getElementsByTagName("enderDest")->item(0);
        $det        = $dom->getElementsByTagName("det");
        $cobr       = $dom->getElementsByTagName("cobr")->item(0);
        $dup        = $dom->getElementsByTagName('dup');
        $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
        $transp     = $dom->getElementsByTagName("transp")->item(0);
        $transporta = $dom->getElementsByTagName("transporta")->item(0);
        $veicTransp = $dom->getElementsByTagName("veicTransp")->item(0);
        $vols       = $dom->getElementsByTagName("vol");
        $infAdic    = $dom->getElementsByTagName("infAdic")->item(0);

        if ($this->canhoto) {
            // canhoto
            $this->linha(8.5, 150.0,  10.0,  4.8, "TBRL", "RECEBI(EMOS) DE ".utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue).", OS PRODUTOS CONSTANTE DA NOTA FISCAL ELETRÔNICA INDICADA AO LADO,");
            $this->linha(5.5, 150.0,  10.0,  7.5,     "", "BEM COMO ATESTAMOS QUE OS MESMOS FORAM EXAMINADOS, SERVINDO O ACEITE DA PRESENTE PARA TODOS OS EFEITOS LEGAIS.");
            $this->linha( 17,  40.0, 160.0,  4.8, "TBRL", "NF-e", "Courier,B,7,C");
            $this->pdf->setXY(161, 11);
            $this->pdf->Cell(15, 2, "No.");
            $this->pdf->setXY(167, 9.8);
            $this->pdf->SetFillColor(230,230,230);
            $this->pdf->Cell(32, 3.8, "", 0, 0, "", true);
            $this->pdf->setXY(161, 17);
            $this->pdf->Cell(15, 0, "SÉRIE");

            $this->linha(8.5, 20, 168,  6.7, "", "", "", $this->mask_nnf($ide->getElementsByTagName('nNF')->item(0)->nodeValue),   "Courier,B,9,L");
            $this->linha(8.5, 20, 172, 11.9, "", "", "", $ide->getElementsByTagName('serie')->item(0)->nodeValue, "Courier,B,9,L");

            $this->linha(8.5,    40, 10.0, 13.3, "TBRL", "DATA DE RECEBIMENTO");
            $this->linha(8.5,   110, 50.0, 13.3, "TBRL", "IDENTIFICAÇÃO E ASSINATURA DO RECEBEDOR");

            $extra = 0;

        } else {
            $extra = 21;
        }


        $chave_acesso = str_replace('NFe', '', $infNFe->getAttribute("Id"));
        $this->pdf->SetFillColor(0,0,0);
        $this->pdf->Code128(125, 26.8, $chave_acesso, 70, 12);

        // dados da nfe
        $this->linha(39.2,  84.6,  10.0, 25.4, "B");    // identificacao emitente/logo
        if (!empty($this->logomarca) && is_file($this->logomarca)) {
            $this->pdf->Image($this->logomarca, 11, 28-$extra, 0, 10, 'jpeg');
        }
        $this->linha( 5.0,  84.6,  10.0, 40.0, "", "", "", utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue), "Courier,B,7,L");
        $endereco_emitente = $enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue;
        $endereco_emitente.= (!empty($enderEmit->getElementsByTagName("nro")->item(0)->nodeValue)) ? ", ".$enderEmit->getElementsByTagName("nro")->item(0)->nodeValue : null;
        $endereco_emitente.= (!empty($enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue)) ? " - ".$enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue : null;
        $this->linha( 5.0,  84.6,  10.0, 43.0, "", "", "", utf8_decode($endereco_emitente));
        $endereco_emitente2 = $enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue;
        $endereco_emitente2.= (!empty($enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue)) ? " - ".$enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue : null;
        $endereco_emitente2.= (!empty($enderEmit->getElementsByTagName("UF")->item(0)->nodeValue)) ? "/".$enderEmit->getElementsByTagName("UF")->item(0)->nodeValue : null;
        $this->linha( 5.0,  84.6,  10.0, 46.0, "", "", "", utf8_decode($endereco_emitente2));
        $this->linha( 5.0,  84.6,  10.0, 49.0, "", "", "", "CEP: ".utf8_decode($emit->getElementsByTagName("CEP")->item(0)->nodeValue)." - FONE: ".utf8_decode($emit->getElementsByTagName("fone")->item(0)->nodeValue));
        

        if ($ide->getElementsByTagName("tpEmis")->item(0)->nodeValue != 1) {

            // contingencia

            $this->pdf->SetTextColor(170,170,170);
            $this->linha( 5.0, 190.0,  10.0, 120.0, "", "", "", "DANFE em Contingência",   "Courier,B,30,C");
            $this->linha( 5.0, 190.0,  10.0, 140.0, "", "", "", "Impresso em decorrência", "Courier,B,30,C");
            $this->linha( 5.0, 190.0,  10.0, 160.0, "", "", "", "de problemas técnicos",   "Courier,B,30,C");
            $this->pdf->SetTextColor(0,0,0);

            $dados_nfe = $this->array_uf[$enderDest->getElementsByTagName("UF")->item(0)->nodeValue];
            $dados_nfe.= $ide->getElementsByTagName("tpEmis")->item(0)->nodeValue;
            $dados_nfe.= $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue;
            $dados_nfe.= sprintf("%014s", str_replace(',', '', number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", "")));
            $dados_nfe.= 1; // ICMSp
            $dados_nfe.= 2; // ICMSs
            $data_emissao = dmy2ymd($ide->getElementsByTagName("dEmi")->item(0)->nodeValue);
            $dados_nfe.= $data_emissao[0].$data_emissao[1];
            $dados_nfe.= $this->calcula_dv($dados_nfe);

            $this->pdf->Code128(125, 51.2, $dados_nfe, 70, 12); // codigo de barras

            $this->linha(14.8,    80, 120.0,  49.8,  "BTRL", ""); // codigo de barras dos dados
            $this->linha( 8.5,    80, 120.0,  64.6,  "BTRL",  "DADOS DA NF-e", "", $this->mask_chave($dados_nfe), "Courier,B,6.5,L");

        } else {

            // normal

            $prot_data_hora = $this->protocolo." ".$this->data_hora;
            $this->linha(14.8,    80, 120.0,  49.8,  "BTRL", "", "", "Consulta de autenticidade no portal nacional");
            $this->linha(14.8,    80, 120.0,  52.8,      "", "", "", "da NF-e www.nfe.fazenda.gov.br/portal ou");
            $this->linha(14.8,    80, 120.0,  55.8,      "", "", "", "no site da Sefaz Autorizadora");
            $this->linha( 8.5,    80, 120.0,  64.6,  "BTRL",  "PROTOCOLO DE AUTORIZAÇÃO DE USO", "", $prot_data_hora, "Courier,B,7,C");
        
        }


        // se for ambiente de homologação, escreve SEM VALOR FISCAL
        if ($ide->getElementsByTagName("tpAmb")->item(0)->nodeValue == 2) {
            $this->pdf->SetTextColor(170,170,170);
            $this->linha( 5.0, 190.0,  10.0, 210.0, "", "", "", "SEM VALOR FISCAL", "Courier,B,50,C");
            $this->pdf->SetTextColor(0,0,0);
        }


        $this->linha(39.2,  25.4,  94.6,  25.4,  "BTRL", "DANFE",              "Courier,B,12,C"); // DANFE
        $this->linha(39.2,  25.4,  94.6,    31,      "", "DOCUMENTO AUXILIAR", "Courier,B,6,C");
        $this->linha(39.2,  25.4,  94.6,    33,      "", "DA NOTA FISCAL",     "Courier,B,6,C");
        $this->linha(39.2,  25.4,  94.6,    35,      "", "ELETRÔNICA",         "Courier,B,6,C");

        $this->linha(39.2,  25.4,  95.6,    42,      "", "0 - ENTRADA",        "Courier,B,6,L");
        $this->linha(39.2,  25.4,  95.6,    45,      "", "1 - SAÍDA",          "Courier,B,6,L");
        $this->linha(   7,     5,   113,    41,  "BTRL", "", "", $ide->getElementsByTagName('tpNF')->item(0)->nodeValue, "Courier,B,10,C");

        $this->linha(39.2,  25.4,  95.6,    52,      "", "No.",   "Courier,B,6,L");
        $this->linha(39.2,  25.4,  95.6,    56,      "", "SÉRIE", "Courier,B,5,L");
        $this->linha(39.2,  25.4,  95.6,    60,      "", "FOLHA", "Courier,B,5,L");
        $this->linha(39.2,  25.4,   101,  48.5,      "", "", "", $this->mask_nnf($ide->getElementsByTagName('nNF')->item(0)->nodeValue));
        $this->linha(39.2,  25.4,   102,  52.2,      "", "", "", $ide->getElementsByTagName('serie')->item(0)->nodeValue);
        $this->linha(39.2,  25.4,   102,  56.0,      "", "", "", "1/1");

        $this->linha(14.8,    80, 120.0,  25.4,  "BTRL"); // codigo de barras da chave
        $this->linha( 9.6,    80, 120.0,  40.2,  "BTRL",  "CHAVE DE ACESSO", "", $this->mask_chave($chave_acesso), "Courier,B,6.5,L");
        $this->linha( 8.5, 110.0,  10.0,  64.6,  "BTRL",  "NATUREZA DA OPERAÇÃO", "", utf8_decode($ide->getElementsByTagName("natOp")->item(0)->nodeValue));

        $this->linha( 8.5,  63.4,  10.0,  73.1,  "BTRL", "INSCRIÇÃO ESTADUAL", "", utf8_decode($emit->getElementsByTagName("IE")->item(0)->nodeValue));
        $this->linha( 8.5,  63.3,  73.4,  73.1,  "BTRL", "INSCRIÇÃO ESTADUAL DO SUBST. TRIB.", "", utf8_decode($emit->getElementsByTagName("IEST")->item(0)->nodeValue));
        $this->linha( 8.5,  63.3, 136.7,  73.1,  "BTRL", "C.N.P.J.", "", $this->mask_cnpj($emit->getElementsByTagName("CNPJ")->item(0)->nodeValue));

        // destinatario/remetente
        $this->linha( 4.2,    33,  10.0,  82.6,      "", "DESTINATÁRIO/REMETENTE", "Courier,B,6,L");
        $this->linha( 8.5, 110.0,  10.0,  85.8,  "BTRL", "NOME/RAZÃO SOCIAL","", utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue));
        $dest_doc = (!empty($dest->getElementsByTagName("CNPJ")->item(0)->nodeValue)) ? $this->mask_cnpj($dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) : $this->mask_cpf($dest->getElementsByTagName("CPF")->item(0)->nodeValue);
        $this->linha( 8.5,  51.0, 120.0,  85.8, "BTRLN", "C.N.P.J./C.P.F.","",   $dest_doc);
        $this->linha( 8.5,  29.0,   171,  85.8,  "BTRL", "DATA DA EMISSÃO","",   dmy2ymd($ide->getElementsByTagName("dEmi")->item(0)->nodeValue));
        $dest_ender = utf8_decode($enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue);
        $dest_ender.= (!empty($enderDest->getElementsByTagName("nro")->item(0)->nodeValue)) ? ", ".utf8_decode($enderDest->getElementsByTagName("nro")->item(0)->nodeValue) : null;
        $this->linha( 8.5,  91.0,  10.0,  94.3,  "BTRL", "ENDEREÇO","",          $dest_ender);
        $this->linha( 8.5,  45.0, 101.0,  94.3,  "BTRL", "BAIRRO/DISTRITO","",   utf8_decode($enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue));
        $this->linha( 8.5,  25.0, 146.0,  94.3,  "BTRL", "CEP","",               utf8_decode($enderDest->getElementsByTagName("CEP")->item(0)->nodeValue));
        $this->linha( 8.5,  29.0,   171,  94.3, "BTRLN", "DATA DA SAÍDA/ENTRADA","",dmy2ymd($ide->getElementsByTagName("dSaiEnt")->item(0)->nodeValue));
        $this->linha( 8.5,  64.0,  10.0, 102.8,  "BTRL", "MUNICÍPIO","",         utf8_decode($enderDest->getElementsByTagName("xMun")->item(0)->nodeValue));
        $this->linha( 8.5,  35.0,  74.0, 102.8,  "BTRL", "FONE/FAX","",          utf8_decode($enderDest->getElementsByTagName("fone")->item(0)->nodeValue));
        $this->linha( 8.5,  11.0, 109.0, 102.8,  "BTRL", "UF","",                utf8_decode($enderDest->getElementsByTagName("UF")->item(0)->nodeValue));
        $this->linha( 8.5,  51.0, 120.0, 102.8,  "BTRL", "INSCRIÇÃO ESTADUAL","",utf8_decode($dest->getElementsByTagName("IE")->item(0)->nodeValue));
        $this->linha( 8.5,  29.0,   171, 102.8, "BTRLN", "HORA DA SAÍDA","",     date("H:i:s")); // BUG: como esta TAG não existe no XML, obter hora atual

        // faturas/duplicatas
        $this->linha( 4.2,    10,  10.0, 111.9,      "", "FATURA/DUPLICATAS", "Courier,B,6,L");
        // TODO: melhorar fatura aqui tb
        $dups = "";
        foreach ($dup as $k => $d) {
            $dups.= $dup->item($k)->getElementsByTagName('nDup')->item(0)->nodeValue." - ";
            $dups.= dmy2ymd($dup->item($k)->getElementsByTagName('dVenc')->item(0)->nodeValue);
            $dups.= " - R$ ".number_format($dup->item($k)->getElementsByTagName('vDup')->item(0)->nodeValue, 2, ",", ".")."    ";
        }
        $this->linha( 8.5, 190.0,  10.0, 115.1,  "BTRL", "", "", utf8_decode($dups));

        // calculo do imposto
        $this->linha( 4.2,    56,  10.0, 124.6,      "", "CÁLCULO DO IMPOSTO", "Courier,B,6,L");
        $this->linha( 8.5,  37.5,  10.0, 127.8,  "BTRL", "BASE DE CÁLCULO DO ICMS",     "", number_format($ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R");
        $this->linha( 8.5,  37.5,  47.5, 127.8,  "BTRL", "VALOR DO ICMS",               "", number_format($ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  37.5,  85.0, 127.8,  "BTRL", "BASE DE CÁLCULO DO ICMS ST",  "", number_format($ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  37.5, 122.5, 127.8,  "BTRL", "VALOR DO ICMS ST",            "", number_format($ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  40.0, 160.0, 127.8,  "BTRL", "VALOR TOTAL DOS PRODUTOS",    "", number_format($ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  30.0,  10.0, 136.3,  "BTRL", "VALOR DO FRETE",              "", number_format($ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  30.0,  40.0, 136.3,  "BTRL", "VALOR DO SEGURO",             "", number_format($ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  30.0,  70.0, 136.3,  "BTRL", "DESCONTO",                    "", number_format($ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  30.0, 100.0, 136.3,  "BTRL", "OUTRAS DESPESAS ACESSÓRIAS",  "", number_format($ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  30.0, 130.0, 136.3,  "BTRL", "VALOR DO IPI",                "", number_format($ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue, 2, ",", "."), "Courier,,7,R" );
        $this->linha( 8.5,  40.0, 160.0, 136.3, "BTRLN", "VALOR TOTAL DA NOTA",         "", number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", "."), "Courier,B,8,R" );

        // transportador/volumes transportados
        $this->linha( 4.2,    52,  10.0, 145.8,      "", "TRANSPORTADOR/VOLUMES TRANSPORTADOS", "Courier,B,6,L");
        if (is_object($transporta)) {
            $transp_doc     = (!empty($transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue)) ? $this->mask_cnpj($transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue) : $this->mask_cpf($transporta->getElementsByTagName("CPF")->item(0)->nodeValue);
            $transp_xNome   = utf8_decode($transporta->getElementsByTagName("xNome")->item(0)->nodeValue);
            $transp_xEnder  = utf8_decode($transporta->getElementsByTagName("xEnder")->item(0)->nodeValue);
            $transp_xMun    = utf8_decode($transporta->getElementsByTagName("xMun")->item(0)->nodeValue);
            $transp_UF      = utf8_decode($transporta->getElementsByTagName("UF")->item(0)->nodeValue);
            $transp_IE      = utf8_decode($transporta->getElementsByTagName("IE")->item(0)->nodeValue);
        }
        if (is_object($veicTransp)) {
            $veic_RNTC      = utf8_decode($veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue);
            $veic_placa     = utf8_decode($veicTransp->getElementsByTagName("placa")->item(0)->nodeValue);
            $veic_UF        = utf8_decode($veicTransp->getElementsByTagName("UF")->item(0)->nodeValue);
        }
        $this->linha( 8.5,  80.0,  10.0,   149,  "BTRL", "RAZÃO SOCIAL",        "", $transp_xNome);
        $this->linha( 8.5,  26.0,  90.0,   149,  "BTRL", "FRETE POR CONTA DE",  "");
        $this->linha( 8.5,  26.0,  90.0,   152,      "", "1 - EMITENTE",  "");
        $this->linha( 8.5,  26.0,  90.0,   154,      "", "2 - DESTINAT.",  "");
        $this->linha( 3.5,   4.0, 110.0, 152.5,  "BTRL", "");
        $this->linha( 3.5,   4.0, 110.2, 149.2,      "", "",                    "", $transp->getElementsByTagName("modFrete")->item(0)->nodeValue+1);
        $this->linha( 8.5,  16.0, 116.0,   149,  "BTRL", "CÓDIGO ANTT",         "", $veic_RNTC);
        $this->linha( 8.5,  21.0, 132.0,   149,  "BTRL", "PLACA DO VEÍCULO",    "", $veic_placa);
        $this->linha( 8.5,   8.0, 153.0,   149,  "BTRL", "UF",                  "", $veic_UF);
        $this->linha( 8.5,  39.0, 161.0,   149,  "BTRL", "C.N.P.J./C.P.F.",     "", $transp_doc);
        $this->linha( 8.5,  80.0,  10.0, 157.5,  "BTRL", "ENDEREÇO",            "", $transp_xEnder);
        $this->linha( 8.5,  63.0,  90.0, 157.5,  "BTRL", "MUNICÍPIO",           "", $transp_xMun);
        $this->linha( 8.5,   8.0, 153.0, 157.5,  "BTRL", "UF",                  "", $transp_UF);
        $this->linha( 8.5,  39.0, 161.0, 157.5,  "BTRL", "INSCRIÇÃO ESTADUAL",  "", $transp_IE);

        // faz somatorios/concatenacoes dos volumes e coloca nos campos
        foreach ($vols as $key => $v) {
            $barra   = ($key != 0) ? "/" : "";
            $qVol   += $v->getElementsByTagName("qVol")->item(0)->nodeValue;
            $esp    .= $barra.$v->getElementsByTagName("esp")->item(0)->nodeValue;
            $marca  .= $barra.$v->getElementsByTagName("marca")->item(0)->nodeValue;
            $nVol   .= $barra.$v->getElementsByTagName("nVol")->item(0)->nodeValue;
            $pesoB  += $v->getElementsByTagName("pesoB")->item(0)->nodeValue;
            $pesoL  += $v->getElementsByTagName("pesoL")->item(0)->nodeValue;
        }
        $this->linha( 8.5,  26.0,  10.0,   166,  "BTRL", "QUANTIDADE",          "", number_format($qVol, 2, ",", "."), "Courier,,7,R");
        $this->linha( 8.5,  27.0,  36.0,   166,  "BTRL", "ESPÉCIE",             "", utf8_decode($esp));
        $this->linha( 8.5,  27.0,  63.0,   166,  "BTRL", "MARCA",               "", utf8_decode($marca));
        $this->linha( 8.5,  46.0,  90.0,   166,  "BTRL", "NUMERAÇÃO",           "", utf8_decode($nVol));
        $this->linha( 8.5,  32.0,   136,   166,  "BTRL", "PESO BRUTO",          "", number_format($pesoB, 3, ",", "."), "Courier,,7,R");
        $this->linha( 8.5,  32.0, 168.0,   166,  "BTRL", "PESO LÍQUIDO",        "", number_format($pesoL, 3, ",", "."), "Courier,,7,R");

        $issqn_extra = 0;
        if (!$this->issqn) {
            $issqn_extra = 12.7;
        }

        // dados dos produtos/servicos
        $this->linha( 4.2+$extra+$issqn_extra,    40,  10.0, 175.5,     "", "DADOS DOS PRODUTOS/SERVIÇOS", "Courier,B,6,L");
        $this->linha(65.0+$extra+$issqn_extra, 190.0,  10.0, 178.7, "BTRL", "");
        $this->linha(2.6, 190.0, 10.0, 178.7, "BTRL",  "");
        $this->linha(65.0+$extra+$issqn_extra,  15.0,  10.0, 178.7, "BTRL", "CÓDIGO",    "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  62.0,  25.0, 178.7, "BTRL", "DESCRIÇÃO", "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,   9.5,  87.0, 178.7, "BTRL", "NCM/SH",    "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,   4.5,  96.5, 178.7, "BTRL", "CST",       "Courier,,5,L");
        $this->linha(65.0+$extra+$issqn_extra,   5.5, 101.0, 178.7, "BTRL", "CFOP",      "Courier,,5,L");
        $this->linha(65.0+$extra+$issqn_extra,   5.0, 106.5, 178.7, "BTRL", " UN.",      "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  13.0, 111.5, 178.7, "BTRL", "QUANT.",    "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  13.0, 124.5, 178.7, "BTRL", "V.UNIT.",   "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  14.0, 137.5, 178.7, "BTRL", "V.TOTAL",   "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  12.5, 151.5, 178.7, "BTRL", "BC.ICMS",   "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  11.0, 164.0, 178.7, "BTRL", "V.ICMS",    "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,  11.0, 175.0, 178.7, "BTRL", "V.IPI",     "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,   7.0, 186.0, 178.7, "BTRL", "%ICMS",     "Courier,,5,C");
        $this->linha(65.0+$extra+$issqn_extra,   7.0, 193.0, 178.7, "BTRL", "%IPI",      "Courier,,5,C");

        $i = 0;
        foreach ($det as $d) {

            $prod = $det->item($i)->getElementsByTagName("prod")->item(0);
            $imposto = $det->item($i)->getElementsByTagName("imposto")->item(0);
            $ICMS = $imposto->getElementsByTagName("ICMS")->item(0);
            $IPI  = $imposto->getElementsByTagName("IPI")->item(0);

            $i++;
            $this->linha(65.0+$extra+$issqn_extra,  15.0,  10.0, 178.9+($i*2.3), "", utf8_decode($prod->getElementsByTagName("cProd")->item(0)->nodeValue),     "Courier,,5,L");
            $this->linha(65.0+$extra+$issqn_extra,  62.0,  25.0, 178.9+($i*2.3), "", utf8_decode($prod->getElementsByTagName("xProd")->item(0)->nodeValue),     "Courier,,5,L");
            $NCM = (!empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue)) ? $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
            $this->linha(65.0+$extra+$issqn_extra,   9.5,  87.0, 178.9+($i*2.3), "", $NCM,                    "Courier,,5,L");
            $this->linha(65.0+$extra+$issqn_extra,   4.5,  96.5, 178.9+($i*2.3), "", $ICMS->getElementsByTagName("orig")->item(0)->nodeValue.$ICMS->getElementsByTagName("CST")->item(0)->nodeValue, "Courier,,5,L");
            $this->linha(65.0+$extra+$issqn_extra,   5.5, 101.0, 178.9+($i*2.3), "", $prod->getElementsByTagName("CFOP")->item(0)->nodeValue,                               "Courier,,5,L");
            $this->linha(65.0+$extra+$issqn_extra,   5.0, 106.5, 178.9+($i*2.3), "", utf8_decode($prod->getElementsByTagName("uCom")->item(0)->nodeValue),                  "Courier,,5,C");
            $this->linha(65.0+$extra+$issqn_extra,  13.5, 111.5, 178.9+($i*2.3), "", number_format($prod->getElementsByTagName("qCom")->item(0)->nodeValue, 4, ",", "."),   "Courier,,5,R");
            $this->linha(65.0+$extra+$issqn_extra,  13.5, 124.5, 178.9+($i*2.3), "", number_format($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue, 4, ",", "."), "Courier,,5,R");
            $this->linha(65.0+$extra+$issqn_extra,  14.5, 137.5, 178.9+($i*2.3), "", number_format($prod->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", "."),  "Courier,,5,R");
            $this->linha(65.0+$extra+$issqn_extra,  13.0, 151.5, 178.9+($i*2.3), "", number_format($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue, 2, ",", "."),    "Courier,,5,R");
            $this->linha(65.0+$extra+$issqn_extra,  11.5, 164.0, 178.9+($i*2.3), "", number_format($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue, 2, ",", "."),  "Courier,,5,R");
            $vIPI = (!empty($IPI)) ? $IPI->getElementsByTagName("vIPI")->item(0)->nodeValue : 0;
            $this->linha(65.0+$extra+$issqn_extra,  11.5, 175.0, 178.9+($i*2.3), "", number_format($vIPI, 2, ",", "."),    "Courier,,5,R");
            $this->linha(65.0+$extra+$issqn_extra,   7.5, 186.0, 178.9+($i*2.3), "", number_format($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue, 2, ",", "."),  "Courier,,5,R");
            $pIPI = (!empty($IPI)) ? $IPI->getElementsByTagName("pIPI")->item(0)->nodeValue : 0;
            $this->linha(65.0+$extra+$issqn_extra,   7.5, 193.0, 178.9+($i*2.3), "", number_format($pIPI, 2, ",", "."),    "Courier,,5,R");
        }


        if ($this->issqn) {
            // calculo do issqn
            $this->linha(4.2, 23.0,  10.0, 244.3+$extra,     "", "CÁLCULO DO ISSQN", "Courier,B,6,L");
            $this->linha(8.5, 46.0,  10.0, 247.9+$extra, "BTRL", "INSCRIÇÃO MUNICIPAL");
            $this->linha(8.5, 47.0,  56.0, 247.9+$extra, "BTRL", "VALOR TOTAL DOS SERVIÇOS");
            $this->linha(8.5, 47.0, 103.0, 247.9+$extra, "BTRL", "BASE DE CÁLCULO DO ISSQN");
            $this->linha(8.5, 50.0, 150.0, 247.9+$extra, "BTRL", "VALOR DO ISSQN");
        }

        // dados adicionais
        $this->linha( 4.2,  23.0,  10.0, 257.4+$extra,      "", "DADOS ADICIONAIS", "Courier,B,6,L");

        $this->linha(30.7, 113.0,  10.0, 260.6+$extra,  "BTRL", "INFORMAÇÕES COMPLEMENTARES");
        $this->linha(30.7,  77.0, 123.0, 260.6+$extra,  "BTRL", "RESERVADO AO FISCO");


        $this->pdf->setFont("Courier", "", "6");
        $this->pdf->SetAutopagebreak(false); 


        if (is_object($infAdic)) {
            $infCpl     = $infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue;
            $infAdFisco = $infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue;
        }

        // IMPRIME infCpl
        for ($i=0; $i<=strlen($infCpl); $i++) {
            $txt.= $infCpl[$i];
            if ($this->pdf->GetStringWidth($txt) >= 110 || $i >= strlen($infCpl)) {
                $this->pdf->setXY(10, 265+$extra+($l*3));
                $this->pdf->Cell(113, 0, $txt, 0, 0, 'L');
                $txt = '';
                $l++;
            }
        }

        // IMPRIME infAdFisco
        $l = 0;
        for ($i=0; $i<=strlen($infAdFisco); $i++) {
            $txt.= $infAdFisco[$i];
            if ($this->pdf->GetStringWidth($txt) >= 74 || $i >= strlen($infAdFisco)) {
                $this->pdf->setXY(123, 265+$extra+($l*3));
                $this->pdf->Cell(77, 0, $txt, 0, 0, 'L');
                $txt = '';
                $l++;
            }
        }
        


        //$this->pdf->SetDisplayMode(70);
        //$this->pdf->Output("danfe.pdf", "I");
        return $this->pdf;
    }

}

?>
