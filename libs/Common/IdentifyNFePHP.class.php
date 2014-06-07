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
 * @category  NFePHP
 * @package   NFePHP
 * @name      IdentifyNFePHP.class.php
 * @author    Roberto Spadim <roberto at spadim dot com dot br>
 * @copyright 2009-2013 &copy; NFePHP
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 *            http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @version   GIT: 2.13
 * @link      http://www.nfephp.org/
 *
 *
 * Este arquivo contem funções para identificar conteúdo de arquivos
 * 2.13 - PSR-2 (não vai ter namescape, tem linha grande)
 * 2.12 - Adicionado descrição das constantes para facilitar a interpretação do
 *        tipo do arquivo
 * 2.11 - faltou um ); na linha 446
 * 2.10 - adicionado novos tipos de TXT, erro no evento - !empty($chave) deveria
 *        ser empty($chave)
 * 2.09 - adicionado comentarios
 * 2.08 - adicionado arquivo tipo DPEC - CTE
 * 2.07 - adicionado arquivo tipo DPEC
 * 2.06 - uso de fileinfo e adição de boleto/contas
 * 2.05 - PSR-2 (não vai ter namescape)
 *           atenção NAS CONSTANTES!! NF>>e<< virou NF>>E<<
 * 2.04 - correção de bug para identificar nfe
 * 2.03 - só força https quando for nfc-e, se for nfe ou cte, pode ser um site
 *        do emitente do documento
 * 2.02 - evento de nfc-e (sabe se é nfe ou nfc-e pela chave de acesso)
 *        corrigido pdf/img que retornava sempre nfce nos modelos 55,65,57
 * 2.01 - adicionado evento de CTE (precisa ser testado, ainda não tem xml disso
 *        em teste, nem em produção)
 * 2.00 - alterado função IdentifyFile para retornar um array
 *            adicionado reconhecimento de pdf e imagens de danfes, precisa de
 *            dois executaveis externos:
 *                zbarimg (versão 0.10 testada e funcionando)
 *                imagemagick versão 9 testada e funcionando (pode ser
 *                      necessário ghostscript no windows)
 *            separado em varias funções para facilitar a leitura
 *                adicionado algumas funções para verificar digito verificador
 *                de chave de acesso
 *                    (verificação minima para os arquivos PDF e imagem)
 *            adicionado tipo array quando tem varios formatos dentro de um
 *               arquivo PDF (danfe+dacte por exemplo)
 * 1.04 - quando é NFe, verifica se é uma NFC-e
 * 1.03 - adicionado função para extrair do xml da nfeb2b, o dom da nfe
 *            (nfeProc) e o documento do b2b (NFeB2B)
 * 1.02 - adicionado nfeb2b
 *
 */
// tipos arquivos
if (!defined('NFEPHP_TIPO_ARQUIVO_DESCONHECIDO')) {
    /* -1 e 0 => tipos especiais (-1=array retorna varios documentos,
     *                             0=não reconhecido)
     */
    define('NFEPHP_TIPO_ARQUIVO_ARRAY', -1);
    define('NFEPHP_TIPO_ARQUIVO_DESCONHECIDO', 0);
    /* 1 a 99 => xml de documentos, autorizados ou sem protocolo */
    define('NFEPHP_TIPO_ARQUIVO_NFE', 1);
    define('NFEPHP_TIPO_ARQUIVO_NFE_SEM_PROTOCOLO', 2);
    define('NFEPHP_TIPO_ARQUIVO_CTE', 3);
    define('NFEPHP_TIPO_ARQUIVO_CTE_SEM_PROTOCOLO', 4);
    define('NFEPHP_TIPO_ARQUIVO_NFE_NFEB2B', 5);
    define('NFEPHP_TIPO_ARQUIVO_NFCE', 6);
    define('NFEPHP_TIPO_ARQUIVO_NFCE_SEM_PROTOCOLO', 7); // será que existe?!
    define('NFEPHP_TIPO_ARQUIVO_DPEC_NFE', 8); // dpec de nfe
    define('NFEPHP_TIPO_ARQUIVO_DPEC_CTE', 9); // dpec de cte

    /* 100 => eventos */
    define('NFEPHP_TIPO_ARQUIVO_EVENTONFE', 100);
    // modelo novo (v2 e v3) - modelo 55 -nfe
    define('NFEPHP_TIPO_ARQUIVO_NFE_PROCCANCNFE', 101);
    // modelo antigo de cancelamento (v1)
    define('NFEPHP_TIPO_ARQUIVO_CTE_PROCCANCCTE', 102); // modelo antigo (v1.04)
    define('NFEPHP_TIPO_ARQUIVO_EVENTOCTE', 103); // modelo novo (v2.0)
    define('NFEPHP_TIPO_ARQUIVO_EVENTONFCE', 104); // modelo novo (v2 e v3)
    // este é para documento modelo 65
    //    nfce(será q vai ser utilizado?)

    /* 200 => inutilizações e operações com a série do documento fiscal */
    define('NFEPHP_TIPO_ARQUIVO_NFE_INUTFAIXA', 200);
    define('NFEPHP_TIPO_ARQUIVO_CTE_INUTFAIXA', 201);

    /* 300 => arquivos TXT para conversão para documentos (normalmente XML) */
    define('NFEPHP_TIPO_ARQUIVO_TXT_NFE', 300);
    define('NFEPHP_TIPO_ARQUIVO_TXT_CTE', 301);
    define('NFEPHP_TIPO_ARQUIVO_TXT_NFE_EMITENTE', 302);
    define('NFEPHP_TIPO_ARQUIVO_TXT_NFE_CLIENTE', 303);
    define('NFEPHP_TIPO_ARQUIVO_TXT_NFE_PRODUTO', 304);
    define('NFEPHP_TIPO_ARQUIVO_TXT_NFE_TRANSPORTADORA', 305);

    /* 400 => arquivos que representam graficamente um documento,
              ou que contenham código de barra referente a
              documentos fiscais/boletos/contas
    */
    define('NFEPHP_TIPO_ARQUIVO_PDF_NFE', 400); // BARCODE NFE
    define('NFEPHP_TIPO_ARQUIVO_PDF_NFCE', 401); // QRCODE NFCE
    define('NFEPHP_TIPO_ARQUIVO_PDF_CTE', 402); // BARCODE CTE
    define('NFEPHP_TIPO_ARQUIVO_PDF_BOLETO', 403); // BOLETO DE BANCO
    define('NFEPHP_TIPO_ARQUIVO_PDF_CONTAS', 404); // CONTAS (AGUA LUZ TELEFONE)
}
if (!class_exists('IdentifyNFePHP')) {
    /**
     * Classe Identify
     *  Objetivo - identificar um arquivo qualquer (inclusive imagens)
     *             e obter dados que tenham relevancia com documentos fiscais
     *             em imagens/pdf é retirado os dados de codigo de barras
     */
    class IdentifyNFePHP
    {
        public $tipos_nomes = array(
            NFEPHP_TIPO_ARQUIVO_ARRAY => 'Array',
            NFEPHP_TIPO_ARQUIVO_DESCONHECIDO => 'Desconhecido',
            /* 1 a 99 => xml de documentos, autorizados ou sem protocolo */
            NFEPHP_TIPO_ARQUIVO_NFE => 'XML - NFe',
            NFEPHP_TIPO_ARQUIVO_NFE_SEM_PROTOCOLO => 'XML - NFe Sem protocolo',
            NFEPHP_TIPO_ARQUIVO_CTE => 'XML - CTe',
            NFEPHP_TIPO_ARQUIVO_CTE_SEM_PROTOCOLO => 'XML - CTe Sem protocolo',
            NFEPHP_TIPO_ARQUIVO_NFE_NFEB2B => 'XML - NFeB2B',
            NFEPHP_TIPO_ARQUIVO_NFCE => 'XML - NFCe',
            NFEPHP_TIPO_ARQUIVO_NFCE_SEM_PROTOCOLO => 'XML - NFCe Sem protocolo',
            NFEPHP_TIPO_ARQUIVO_DPEC_NFE => 'XML - DPEC NFe',
            NFEPHP_TIPO_ARQUIVO_DPEC_CTE => 'XML - DPEC CTe',

            /* 100 => eventos */
            NFEPHP_TIPO_ARQUIVO_EVENTONFE => 'XML - Evento NFe',
            NFEPHP_TIPO_ARQUIVO_NFE_PROCCANCNFE => 'XML - ProcCanc NFe',
            NFEPHP_TIPO_ARQUIVO_CTE_PROCCANCCTE => 'XML - ProcCanc CTe',
            NFEPHP_TIPO_ARQUIVO_EVENTOCTE => 'XML - Evento CTe',
            NFEPHP_TIPO_ARQUIVO_EVENTONFCE => 'XML - Evento NFCe',

            /* 200 => inutilizações e operações com a série do documento fiscal */
            NFEPHP_TIPO_ARQUIVO_NFE_INUTFAIXA => 'XML - Inutilização NFe',
            NFEPHP_TIPO_ARQUIVO_CTE_INUTFAIXA => 'XML - Inutilização CTe',

            /* 300 => arquivos TXT para conversão para documentos
                      (normalmente XML) */
            NFEPHP_TIPO_ARQUIVO_TXT_NFE => 'TXT - NFe',
            NFEPHP_TIPO_ARQUIVO_TXT_CTE => 'TXT - CTe',
            NFEPHP_TIPO_ARQUIVO_TXT_NFE_EMITENTE => 'TXT - Emitente',
            NFEPHP_TIPO_ARQUIVO_TXT_NFE_CLIENTE => 'TXT - Cliente',
            NFEPHP_TIPO_ARQUIVO_TXT_NFE_PRODUTO => 'TXT - Produto',
            NFEPHP_TIPO_ARQUIVO_TXT_NFE_TRANSPORTADORA => 'TXT - Transportadora',

            /* 400 => arquivos que representam graficamente um documento,
                      ou que contenham código de barra referente a
                      documentos fiscais/boletos/contas
            */
            NFEPHP_TIPO_ARQUIVO_PDF_NFE => 'Codigo Barra - NFe',
            NFEPHP_TIPO_ARQUIVO_PDF_NFCE => 'Codigo Barra - NFCe',
            NFEPHP_TIPO_ARQUIVO_PDF_CTE => 'Codigo Barra - CTe',
            NFEPHP_TIPO_ARQUIVO_PDF_BOLETO => 'Codigo Barra - Boleto Bancário',
            NFEPHP_TIPO_ARQUIVO_PDF_CONTAS => 'Codigo Barra - Contas'
        );
        public $path_zbarimg = '';
        public $path_convert = '';
        public $path_tmp = '';

        public function __construct($path_zbarimg = '', $path_convert = '', $path_tmp = '')
        {
            if ($path_zbarimg != '') {
                $this->path_zbarimg = $path_zbarimg;
            }
            if ($path_convert != '') {
                $this->path_convert = $path_convert;
            }
            if ($path_tmp != '') {
                $this->path_tmp = $path_tmp;
            }
        }

        /* boletos e contas */
        public function identifyBoletoContas($codigo_barra)
        {
            // pelo codigo de barra pega se é um boleto ou uma contas
            $codigo_barra = preg_replace("/[^0-9]/", "", $codigo_barra);
            // I2/5:03392526000002900229439278700000000003850101
            if (strlen($codigo_barra) == 44) {
                // boletos
                $dv = substr($codigo_barra, 4, 1);
                $dv_calc = $this->boletoModulo11CodigoBarra(
                    substr($codigo_barra, 0, 4) .
                    substr($codigo_barra, 5)
                );
                if ($dv_calc == $dv && $dv_calc !== false) {
                    $linha_digitavel = $this->linhaDigitavelBoleto($codigo_barra);
                    /*
                    $linha_digitavel = '03399.43920 78700.000009 00038.501011 2 52610000290022';
                    */
                    $data_vencimento = substr($linha_digitavel, 40, 4);
                    /*
                    $fator_vencimento = 
                        ((  mktime(0,0,0,$mes,$dia,$ano) -
                            mktime(0,0,0,"07","03","2000"))/86400)+1000;
                    */
                    if ($data_vencimento != '0000') {
                        $data_vencimento_ymd = ($data_vencimento - 1000) * 86400 +
                            gmmktime(0, 0, 0, "07", "03", "2000") + 5000;
                            // +5000 para não dar problema de fuso
                        $data_vencimento_ymd = gmdate('Y-m-d', $data_vencimento_ymd);
                    } else {
                        $data_vencimento_ymd = '0000-00-00';
                    }
                    $valor = substr($linha_digitavel, 44, 10) / 100;
                    return array('codigo_barra' => $codigo_barra,
                        'linha_digitavel' => $linha_digitavel,
                        'banco' => substr($linha_digitavel, 0, 3),
                        'moeda' => substr($linha_digitavel, 3, 1),
                        'valor' => $valor,
                        'data_vencimento_ymd' => $data_vencimento_ymd,
                        'data_vencimento' => $data_vencimento,
                        'tipo' => NFEPHP_TIPO_ARQUIVO_PDF_BOLETO);
                }
                /*
                contas http://www.febraban.org.br/7Rof7SWg6qmyvwJcFwF7I0aSDf9jyV/sitefebraban/Codbar4-v28052004.pdf
                */
                $tipo_dv = substr($codigo_barra, 2, 1);
                if ($tipo_dv == '6' || $tipo_dv == '7' || $tipo_dv == '8' ||
                    $tipo_dv == '9') {
                    $cod_sem_dv = substr($codigo_barra, 0, 3) .
                                  substr($codigo_barra, 4);
                    $dv = substr($codigo_barra, 3, 1);
                    if ($tipo_dv == '6' || $tipo_dv == '7') {
                        $dv_calc = $this->boletoModulo10LinhaDigitavel($cod_sem_dv);
                    } else {
                        $dv_calc = $this->boletoModulo11CodigoBarra($cod_sem_dv);
                    }
                    if ($dv_calc == $dv && $dv_calc !== false || true) {
                        #84640000003  87561029115  89415310390  05130130601 =>
                        #846400000036 875610291150 894153103904 051301306018
                        $p1 = substr($codigo_barra, 0, 11);
                        $p2 = substr($codigo_barra, 11, 11);
                        $p3 = substr($codigo_barra, 22, 11);
                        $p4 = substr($codigo_barra, 33, 11);
                        $valor = (substr($codigo_barra, 4, 11)) / 100;
                        $linha_digitavel =
                            $p1 . $this->boletoModulo11CodigoBarra($p1) . ' ' .
                            $p2 . $this->boletoModulo11CodigoBarra($p2) . ' ' .
                            $p3 . $this->boletoModulo11CodigoBarra($p3) . ' ' .
                            $p4 . $this->boletoModulo11CodigoBarra($p4);
                        $segmentos = array('1' => 'Prefeituras',
                            '2' => 'Saneamento',
                            '3' => 'Energia Elétrica e Gás',
                            '4' => 'Telecomunicações',
                            '5' => 'Órgãos Governamentais',
                            '6' => 'Carnes e Assemelhados ou demais Empresas / '.
                                   'Órgãos que serão identificadas através do CNPJ',
                            '7' => 'Multas de trânsito',
                            '9' => 'Uso exclusivo do banco');
                        $tipo_valores = array(
                            '6' => 'Valor a ser cobrado efetivamente em reais',
                            '7' => 'Quantidade de moeda',
                            '8' => 'Valor a ser cobrado efetivamente em reais',
                            '9' => 'Quantidade de moeda');
                        $segmento = substr($codigo_barra, 1, 1);
                        $tipo_valor = substr($codigo_barra, 2, 1);
                        return array('codigo_barra' => $codigo_barra,
                            'linha_digitavel' => $linha_digitavel,
                            'valor' => $valor,
                            'tipo_valor' => $tipo_valor,
                            'tipo_valor_nome' => $tipo_valores[$tipo_valor],
                            'segmento' => $segmento,
                            'segmento_nome' => $segmentos[$segmento],
                            'tipo' => NFEPHP_TIPO_ARQUIVO_PDF_CONTAS);
                    }
                }
                ////////////////
            }
            return false;
        }

        private function boletoModulo11CodigoBarra($numero = '', $peso = 2)
        {
            $numero = preg_replace("/[^0-9]/", "", $numero);
            #if (strlen($numero)!=43) return(false);
            //123456789.123456789.123456789.123456789.123
            //4329876543298765432987654329876543298765432
            #$peso=2;$soma=0;
            $soma = 0;
            for ($i = strlen($numero) - 1; $i >= 0; $i--) {
                #$tmp_peso=$peso.$tmp_peso;
                $parcial = (double)(substr($numero, $i, 1));
                $parcial = $parcial * $peso;
                $peso++;
                if ($peso == 10) {
                    $peso = 2;
                }
                $soma += $parcial;
            }
            #echo "$tmp_peso\nsoma=$soma\n";
            $resto = ($soma % 11);
            $sub_11 = 11 - $resto;
            if ($sub_11 == 1 || $sub_11 == 0 || $sub_11 > 9) {
                return (1);
            }
            return ($sub_11);
        }

        private function boletoModulo10LinhaDigitavel($numero, $fator = 2)
        {
            $num = $numero;
            $numtotal10 = 0;
            //$fator            =2;
            // Separacao dos numeros
            for ($i = strlen($num); $i > 0; $i--) {
                // pega cada numero isoladamente
                $numeros[$i] = substr($num, $i - 1, 1);
                // Efetua multiplicacao do numero pelo (falor 10)
                // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
                $temp = $numeros[$i] * $fator;
                $temp0 = 0;
                foreach (preg_split('//', $temp, -1, PREG_SPLIT_NO_EMPTY) as $v) {
                    $temp0 += $v;
                }
                $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
                // monta sequencia para soma dos digitos no (modulo 10)
                $numtotal10 += $parcial10[$i];
                if ($fator == 2) {
                    $fator = 1;
                } else {
                    $fator = 2; // intercala fator de multiplicacao (modulo 10)
                }
            }

            // várias linhas removidas, vide função original
            // Calculo do modulo 10
            $resto = $numtotal10 % 10;
            $digito = 10 - $resto;
            if ($resto == 0) {
                $digito = 0;
            }
            return ($digito);
        }

        private function linhaDigitavelBoleto($codigo)
        {
            if (strlen($codigo) != 44) {
                return ('');
            }
            $p1 = substr($codigo, 0, 4); // Numero do banco + moeda
            $p2 = substr($codigo, 19, 5); // 5 primeiras posições do campo livre
            $p3 = $this->boletoModulo10LinhaDigitavel("$p1$p2");
                // Digito do campo 1
            $p4 = "$p1$p2$p3"; // União
            $campo1 = substr($p4, 0, 5) . '.' . substr($p4, 5);
            $p1 = substr($codigo, 24, 10); //Posições de 6 a 15 do campo livre
            $p2 = $this->boletoModulo10LinhaDigitavel($p1); //Digito do campo 2
            $p3 = "$p1$p2";
            $campo2 = substr($p3, 0, 5) . '.' . substr($p3, 5);
            $p1 = substr($codigo, 34, 10); //Posições de 16 a 25 do campo livre
            $p2 = $this->boletoModulo10LinhaDigitavel($p1); //Digito do Campo 3
            $p3 = "$p1$p2";
            $campo3 = substr($p3, 0, 5) . '.' . substr($p3, 5);
            $campo4 = substr($codigo, 4, 1);
            $p1 = substr($codigo, 5, 4);
            $p2 = substr($codigo, 9, 10);
            $campo5 = "$p1$p2";
            return "$campo1 $campo2 $campo3 $campo4 $campo5";
        }

        /* nfe nfce cte */
        public function identifyQRCode($codigo_barra)
        {
            /*
            esta linha é grande pois a url é grande mesmo...
            QR-Code: https://nfce.set.rn.gov.br/consultarNFCe.aspx?chNFe=24130411982113000237650020000000071185945690&nVersao=100&tpAmb=2&dhEmi=323031332d30342d31355431353a32303a35352d30333a3030&vNF=13,90&vICMS=2,36&digVal=69466b66444662536161626c554539614f35476b4b48342f3964513d&cIdToken=000001&cHashQRCode=41799477BE9E40C0792C3B0E43094EA3CA4A2435
            */

            $tmp_url = parse_url($codigo_barra);
            parse_str($tmp_url['query'], $tmp_query);
            // verifica chNFe
            if (!isset($tmp_query['chNFe'])) {
                return (false);
            }
            $tmp_chave = $this->identifyChave($tmp_query['chNFe']);
            if ($tmp_chave !== false) {
                $tmp_query['url'] = $codigo_barra;
                $tmp_query['modelo'] = $tmp_chave;
                if ($tmp_query['modelo'] == '65') {
                    if ($tmp_url['scheme'] != 'https') {
                        // todos webservices do nfce são https, os demais é
                        // site pra baixa xml do emitente
                        return (false);
                    }
                    // verificar o hash do qrcode?
                    // se alguem descobrir =) agradeço
                    ///
                }
                return ($tmp_query);
            }
            return (false);
        }

        public function identifyChave($chave)
        {
            $chave_numeros = preg_replace("/[^0-9]/", "", $chave);
            if (strlen($chave_numeros) == 44) {
                // nfe, nfce e cte
                //                modelo                digito
                //                |                |
                //                vv                v
                //    00.20.00.00000000000000.00.000.000000000.0.18641952.6
                //    01 23 45 67890123456789 01 234 567890123 4 56789012 3
                if (substr($chave_numeros, 20, 2) == '55' ||
                    substr($chave_numeros, 20, 2) == '57' ||
                    substr($chave_numeros, 20, 2) == '65'
                ) {
                    // verifica digito
                    if (!$this->calculadv(substr($chave_numeros, 0, 43)) ==
                        substr($chave_numeros, 43, 1)) {
                        return (false);
                    }
                    return (substr($chave_numeros, 20, 2));
                }
            }
            return false;
        }

        private function calculadv($numero)
        {
            $chave43 = str_pad($numero, '0', STR_PAD_LEFT);
            $multiplicadores = array(2, 3, 4, 5, 6, 7, 8, 9);
            $i = 42;
            $soma_ponderada = 0;
            while ($i >= 0) {
                for ($m = 0; $m < count($multiplicadores) && $i >= 0; $m++) {
                    $soma_ponderada += ((int)substr($chave43, $i, 1)) *
                                        $multiplicadores[$m];
                    $i--;
                }
            }
            $resto = $soma_ponderada % 11;
            if ($resto == '0' || $resto == '1') {
                $cDV = 0;
            } else {
                $cDV = 11 - $resto;
            }
            return $cDV;
        } //fim calculadv

        /* nfeb2b */
        public function extractNFeB2B($parm, $b2b_nfe = 'b2b')
        {
            if (is_string($parm)) {
                if (@is_file($parm)) { // carrega arquivo...
                    $parm = file_get_contents($parm);
                }
            }
            if (!is_object($parm)) { // pode ser passado uma instancia de
                                     // DomDocument...
                #$dom = new DomDocument;
                $dom = new DOMDocument('1.0', 'utf-8');
                @$dom->loadXML($parm);
                if (!is_object($dom)) {
                    return false;
                }
            } else {
                $dom = $parm;
            }
            //die('aki'.get_class($dom));
            if (get_class($dom) != 'DOMDocument') {
                return false;
            }
            $procnfeProcB2B = $dom->getElementsByTagName("nfeProcB2B")->item(0);
            if (empty($procnfeProcB2B)) {
                return false;
            }
            if ($b2b_nfe == 'b2b') {
                // retorna parte do nfeb2b
                $NFeB2B = $dom->getElementsByTagName("NFeB2B")->item(0);
                if (empty($NFeB2B)) {
                    return false;
                }
                // retorna somente o nfeb2b
                $dom2 = new DOMDocument('1.0', 'utf-8');
                $dom2->formatOutput = true;
                $dom2->preserveWhiteSpace = false;
                $tmp_nfeb2b = $dom2->importNode($NFeB2B, true);
                #var_dump($tmp_nfeb2b);
                $dom2->appendChild($tmp_nfeb2b);
                return ($dom2);
            }
            // retorna parte da nfe
            $nfeProc = $dom->getElementsByTagName("nfeProc")->item(0);
            if (empty($nfeProc)) {
                return false;
            }
            $dom2 = new DOMDocument('1.0', 'utf-8');
            $dom2->formatOutput = true;
            $dom2->preserveWhiteSpace = false;
            $tmp_nfe = $dom2->importNode($nfeProc, true);
            $dom2->appendChild($tmp_nfe);
            return ($dom2);
        }


        /* identificação de arquivos */
        public function identifyFileTXT($parm)
        {
            // ARQUIVOS TXT
            if (is_string($parm)) {
                $TMP_TXT = substr($parm, 0, 20); // diminui o processamento de string grande
                // NOTAFISCAL|1|
                // NOTA FISCAL|1|
                if (strpos($TMP_TXT, 'NOTA FISCAL|') === 0 || strpos($TMP_TXT, 'NOTAFISCAL|') === 0) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_TXT_NFE));
                }
                // REGISTROSCTE|1|
                // REGISTROS CTE|1|
                if (strpos($TMP_TXT, 'REGISTROSCTE|') === 0 || strpos($TMP_TXT, 'REGISTROS CTE|') === 0) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_TXT_CTE));
                }
                /*
                TXT AUXILIARES DA NFE
                */
                if (strpos($TMP_TXT, 'EMITENTE|') === 0) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_TXT_NFE_EMITENTE));
                }
                if (strpos($TMP_TXT, 'CLIENTE|') === 0) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_TXT_NFE_CLIENTE));
                }
                if (strpos($TMP_TXT, 'PRODUTO|') === 0) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_TXT_NFE_PRODUTO));
                }
                if (strpos($TMP_TXT, 'TRANSPORTADORA|') === 0) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_TXT_NFE_TRANSPORTADORA));
                }
                unset($TMP_TXT);
            }
            return false;
        }

        public function identifyFileXML($parm)
        {
            // arquivos XML (no futuro retornar a versão do XML, nfe e cte tem versões...)
            if (!is_object($parm)) { // pode ser passado uma instancia de DomDocument...
                #$dom = new DomDocument;
                $dom = new DOMDocument('1.0', 'utf-8');
                @$dom->loadXML($parm);
                if (!is_object($dom)) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
            } else {
                $dom = $parm;
            }
            // die('aki'.get_class($dom));
            if (get_class($dom) != 'DOMDocument') {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
            }
            // DPEC NFE/CTE
            $retDPEC = $dom->getElementsByTagName("retDPEC")->item(0);
            $infDPECReg = $dom->getElementsByTagName("infDPECReg")->item(0);
            $infresCTe = $dom->getElementsByTagName("resCTe")->item(0);
            $infresNFe = $dom->getElementsByTagName("resNFe")->item(0);
            if (!empty($retDPEC) && !empty($infDPECReg)) {
                $tmp_DPECReg = $infDPECReg->getAttribute("Id");
                if (substr($tmp_DPECReg, 0, 7) == 'RETDPEC' && strlen($tmp_DPECReg) == 21) { //RETDPEC07668027000104
                    if (!empty($infresCTe)) {
                        return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DPEC_CTE));
                    }
                    if (!empty($infresNFe)) {
                        return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DPEC_NFE));
                    }
                }
            }


            // Eventos NFE
            $procEventoNFe = $dom->getElementsByTagName("procEventoNFe")->item(0);
            if (!empty($procEventoNFe)) {
                // verifica se não é nfeb2b
                $procnfeProcB2B = $dom->getElementsByTagName("nfeProcB2B")->item(0);
                $NFeB2B = $dom->getElementsByTagName("NFeB2B")->item(0);
                if (!empty($procnfeProcB2B) && !empty($NFeB2B)) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFE_NFEB2B));
                }
                // verifica se não é nfeb2b

                // verificar se é nfc-e (mod=65)    // verificar se está ok!
                $chave = $dom->getElementsByTagName("chNFe")->item(0);
                if (empty($chave)) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
                $chave = $chave->nodeValue;
                $mod = substr($chave, 20, 2);
                if ($mod != 55 && $mod != 65) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
                if ($mod == 65) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_EVENTONFCE));
                }
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_EVENTONFE));
            }
            // Eventos CTE
            //      verificar se é esta tag <procEventoCTe>
            $procEventoCTe = $dom->getElementsByTagName("procEventoCTe")->item(0);
            if (!empty($procEventoCTe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_EVENTOCTE));
            }


            // CTe
            $protCTe = $dom->getElementsByTagName("protCTe")->item(0);
            if (!empty($protCTe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_CTE));
            }

            $CTe = $dom->getElementsByTagName("CTe")->item(0);
            $infCTe = $dom->getElementsByTagName("infCTe")->item(0);
            if (!empty($CTe) && !empty($infCTe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_CTE_SEM_PROTOCOLO));
            }

            $procCancCTe = $dom->getElementsByTagName("procCancCTe")->item(0);
            $cancCTe = $dom->getElementsByTagName("cancCTe")->item(0);
            if (!empty($procCancCTe) && !empty($cancCTe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_CTE_PROCCANCCTE));
            }

            $infInut = $dom->getElementsByTagName("inutCTe")->item(0);
            $retInutCTe = $dom->getElementsByTagName("retInutCTe")->item(0);
            if (!empty($infInut) && !empty($retInutCTe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_CTE_INUTFAIXA));
            }
            unset($CTe, $infCTe, $protCTe,
            $procCancCTe, $cancCTe,
            $infInut, $retInutCTe);


            // NFe
            $nfeProc = $dom->getElementsByTagName("nfeProc")->item(0);
            if (!empty($nfeProc)) {
                // verificar se é nfc-e (mod=65)
                $mod = $nfeProc->getElementsByTagName("mod")->item(0);
                if (empty($mod)) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
                $mod = $mod->nodeValue;
                if ($mod != 55 && $mod != 65) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
                if ($mod == 65) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFCE));
                }
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFE));
            }

            $NFe = $dom->getElementsByTagName("NFe")->item(0);
            $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
            if (!empty($NFe) && !empty($infNFe)) {
                // verificar se é nfc-e (mod=65)
                $mod = $NFe->getElementsByTagName("mod")->item(0);
                if (empty($mod)) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
                $mod = $mod->nodeValue;
                if ($mod != 55 && $mod != 65) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
                }
                if ($mod == 65) {
                    return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFCE_SEM_PROTOCOLO)); // SERÁ QUE EXISTE?!
                }
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFE_SEM_PROTOCOLO));
            }

            $procCancNFe = $dom->getElementsByTagName("procCancNFe")->item(0);
            $cancNFe = $dom->getElementsByTagName("cancNFe")->item(0);
            if (!empty($procCancNFe) && !empty($cancNFe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFE_PROCCANCNFE));
            }

            $infInut = $dom->getElementsByTagName("inutNFe")->item(0);
            $retInutNFe = $dom->getElementsByTagName("retInutNFe")->item(0);
            if (!empty($infInut) && !empty($retInutNFe)) {
                return (array('tipo' => NFEPHP_TIPO_ARQUIVO_NFE_INUTFAIXA));
            }
            unset($nfeProc, $NFe, $infNFe,
            $procCancNFe, $cancNFe,
            $infInut, $retInutNFe,
            $dom);
            return false;
        }

        // esta função tenta ler uma imagem ou pdf e pegar as chaves quando validas, ou o qrcode quando nfce
        // ela vai criar arquivos temporarios e usar arquivos executaveis externos pela função exec,
        //     então fiquem atentos
        public function identifyFilePDFImage($parm, $force_imagem = false)
        {
            if (!function_exists('glob') || !function_exists('exec') ||
                !function_exists('unlink') || !function_exists('tempnam') ||
                !function_exists('file_get_contents') || !function_exists('file_put_contents') ||
                !function_exists('escapeshellarg')
            ) {
                // depois dar uma limpada no codigo, removendo a verificação das funções q tem em todos php
                return false;
            }
            if (!is_string($parm) && !is_resource($parm)) { // pode ser uma imagem $gd
                return false;
            }
            if (!@is_executable($this->path_zbarimg)) {
                return false;
            }

            if (is_string($parm)) {
                if (@is_file($parm)) { // carrega arquivo...
                    $parm = file_get_contents($parm);
                }
                // parm é o conteudo do arquivo e não o local do arquivo
                if (strlen($parm) < 256) { // pelomenos 256 bytes.. (verificar o menor possivel para uma imagem util)
                    return false;
                }
            } elseif (is_resource($parm)) {
                if (get_resource_type($parm) != 'gd') { // tem q ser gd
                    return false;
                }
            }


            $path = $this->path_tmp;
            if (!is_dir($path)) {
                $path = dirname(__FILE__); // tentativa de arrumar um path...
            }
            $path .= '/';
            /*
                procura codigos de barra e tenta identificar se existe alguma informação relevante
                esta função pode retornar um array com varios documentos, são separados cada um
                por um indice [0 .. n] e dentro de cada dele valores especificos
                $ret=array('tipo'=>NFEPHP_TIPO_ARQUIVO_ARRAY,
                    0=>array de retorno1,
                    1=>array de retorno2,
                    2=>array de retorno3,
                    ...
                    );
            */
            if (!$force_imagem) {
                // verifica se é imagem...
                if (!is_resource($parm)) {
                    if (function_exists('imagecreatefromstring')) {
                        $tmp = @imagecreatefromstring($parm);
                    } else {
                        $tmp = false;
                    }
                } else {
                    $tmp =& $parm; // referencia o resource
                }
                if ($tmp === false) {
                    // verifica se é pdf, se for converte pra varias imagens com 300dpi e abre cada um
                    if (!is_executable($this->path_convert)) {
                        return false;
                    }
                    $ret = false;
                    $arquivo_tmp = tempnam($path, "_IdentifyNFEPHP_convert");
                    file_put_contents($arquivo_tmp, $parm);
                    $cmd = escapeshellarg($this->path_convert) . " " .
                        "-density 300 " .
                        escapeshellarg($arquivo_tmp) . " " .
                        escapeshellarg($arquivo_tmp . '.jpg');
                    //echo "convert=> $cmd\n";
                    exec($cmd);
                    @unlink($arquivo_tmp);
                    // pega todos os arquivo .jpg
                    $GLOB = glob($arquivo_tmp . '*.jpg');
                    if (is_array($GLOB)) {
                        foreach ($GLOB as $filename) {
                            //echo "$filename\n";
                            $tmp = $this->identifyFilePDFImage($filename, true);
                            if ($tmp !== false) {
                                // achou algo...
                                if ($ret === false) {
                                    $ret = array('tipo' => NFEPHP_TIPO_ARQUIVO_ARRAY);
                                }
                                $ret[] = $tmp;
                            }
                            unlink($filename);
                        }
                    }
                    return ($ret);
                }
                // grava em um arquivo tmp
                $arquivo_tmp = tempnam($path, "_IdentifyNFEPHP_zbarimg");
                if ($tmp !== false) {
                    if (!function_exists('imagejpeg')) {
                        // vai com png sem compressão (0), as vezes não reconhece no zbarimg
                        imagepng($tmp, $arquivo_tmp, 0);
                    } else {
                        // jpg é mais facil de ser reconhecida mas precisa ter alta qualidade
                        imagejpeg($tmp, $arquivo_tmp, 100);
                    }
                    imagedestroy($tmp);
                } else {
                    file_put_contents($arquivo_tmp, $parm);
                }
            } else {
                $arquivo_tmp = $parm;
            }
            // aqui temos um arquivo ($arquivo_tmp)
            $cmd = escapeshellarg($this->path_zbarimg) . " " .
                "-D " .
                escapeshellarg($arquivo_tmp);
            #echo "zbarimg=> $cmd\n";
            @exec($cmd, $output);
            if (!$force_imagem) {
                @unlink($arquivo_tmp); // apaga arquivo tmp
            }
            #var_dump($output);
            $ret = false;
            foreach ($output as $v) {
                // pega o tipo e o codigo de barra do retorno do zbarimg (versão testada = 0.10)
                // procura codigos de barra que sejam chave de acesso de CTe ou NFe ou qrcode de NFCe
                $tipo_cod_barra = substr($v, 0, strpos($v, ':'));
                $cod_barra = substr($v, strpos($v, ':') + 1);
                if ($tipo_cod_barra == 'CODE-128') { // CHAVE DE DANFE / DACTE
                    $tmp_chave = $this->identifyChave($cod_barra);
                    if ($tmp_chave !== false) {
                        if ($tmp_chave == '55') {
                            if ($ret === false) {
                                $ret = array();
                            }
                            $ret[] = array('chave' => $cod_barra,
                                'tipo' => NFEPHP_TIPO_ARQUIVO_PDF_NFE);
                        } elseif ($tmp_chave == '57') {
                            if ($ret === false) {
                                $ret = array();
                            }
                            $ret[] = array('chave' => $cod_barra,
                                'tipo' => NFEPHP_TIPO_ARQUIVO_PDF_CTE);
                        } elseif ($tmp_chave == '65') {
                            if ($ret === false) {
                                $ret = array();
                            }
                            $ret[] = array('chave' => $cod_barra,
                                'tipo' => NFEPHP_TIPO_ARQUIVO_PDF_NFCE);
                        }
                    }
                } elseif ($tipo_cod_barra == 'QR-Code') { // CHAVE DE DANFE NFCe
                    // qrcode NFC-e
                    // QR-Code:https://nfce.set.rn.gov.br/consultarNFCe.aspx?
                    //     chNFe=24130411982113000237650020000000071185945690&nVersao=100&tpAmb=2&
                    //     dhEmi=323031332d30342d31355431353a32303a35352d30333a3030&vNF=13,90&vICMS=2,36&
                    //     digVal=69466b66444662536161626c554539614f35476b4b48342f3964513d&cIdToken=000001&
                    //     cHashQRCode=41799477BE9E40C0792C3B0E43094EA3CA4A2435

                    $tmp_chave = $this->identifyQRCode($cod_barra);
                    if ($tmp_chave !== false) {
                        if ($tmp_chave['modelo'] == '65') {
                            $tmp_chave['tipo'] = NFEPHP_TIPO_ARQUIVO_PDF_NFCE;
                            $ret[] = $tmp_chave;
                        } elseif ($tmp_chave['modelo'] == '55') {
                            $tmp_chave['tipo'] = NFEPHP_TIPO_ARQUIVO_PDF_NFE;
                            $ret[] = $tmp_chave;
                        } elseif ($tmp_chave['modelo'] == '57') {
                            $tmp_chave['tipo'] = NFEPHP_TIPO_ARQUIVO_PDF_CTE;
                            $ret[] = $tmp_chave;
                        }
                    }
                    unset($tmp_chave);
                } elseif ($tipo_cod_barra == 'I2/5') { // boletos / contas
                    $tmp_chave = $this->identifyBoletoContas($cod_barra);
                    if ($tmp_chave !== false) {
                        $ret[] = $tmp_chave;
                    }
                }
            }
            if (count($ret) == 1) {
                return ($ret[0]);
            }
            $ret['tipo'] = NFEPHP_TIPO_ARQUIVO_ARRAY;
            return $ret;
        }

        public function identifyFile($parm)
        {
            if (is_string($parm)) {
                if (@is_file($parm)) { // carrega arquivo...
                    $parm = file_get_contents($parm);
                }
                // parm é o conteudo do arquivo e não o local do arquivo
            }
            if (function_exists('finfo_buffer')) {
                // oba assim é mais rapido pra acha o formato do arquivo
                // $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = finfo_buffer($parm, FILEINFO_MIME_TYPE);
                if ($mime == 'application/pdf' || substr($mime, 0, 6) == 'image/') {
                    $tmp = $this->identifyFilePDFImage($parm);
                    if ($tmp !== false) {
                        return ($tmp);
                    }
                } elseif ($mime == 'application/xml') {
                    $tmp = $this->identifyFileXML($parm);
                    if ($tmp !== false) {
                        $tmp['mime'] = 'application/xml';
                        return ($tmp);
                    }
                } elseif ($mime == 'text/plain') {
                    // txt de importação de nfe,cte
                    $tmp = $this->identifyFileTXT($parm);
                    if ($tmp !== false) {
                        $tmp['mime'] = 'text/plain';
                        return ($tmp);
                    }
                }
            }

            // vai um por um... faze oq... melhor doque nada
            // txt de importação de nfe,cte
            $tmp = $this->identifyFileTXT($parm);
            if ($tmp !== false) {
                $tmp['mime'] = 'text/plain';
                return ($tmp);
            }

            // xml
            $tmp = $this->identifyFileXML($parm);
            if ($tmp !== false) {
                $tmp['mime'] = 'application/xml';
                return ($tmp);
            }

            // pdf e imagens de danfes,dactes,entre outras imagens
            $tmp = $this->identifyFilePDFImage($parm);
            if ($tmp !== false) {
                return ($tmp);
            }

            // não deu =(
            return (array('tipo' => NFEPHP_TIPO_ARQUIVO_DESCONHECIDO));
        }
    }
}


// teste
#$v=new IdentifyNFePHP('/usr/local/bin/zbarimg','/usr/bin/convert','/tmp');
#var_dump($v->identifyBoletoContas('84640000003875610291158941531039005130130601'));
#var_dump($v->identifyBoletoContas('03392526000002900229439278700000000003850101'));
#var_dump($v->identifyFilePDFImage(file_get_contents('../exemplos/boleto.png')));
