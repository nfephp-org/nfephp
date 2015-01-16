<?php

/**
 * Este arquivo é parte do projeto NFFePHP - Nota Fiscal eletrônica em PHP.
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
 * Esta classe atende aos critérios estabelecidos no
 * Manual de Importação/Exportação TXT Notas Fiscais eletrônicas versão 2.0.0
 *
 * @package     NFFePHP
 * @name        ConvertMDFePHP
 * @version     1.0.0
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2014 &copy; MDFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * @author      Leandro C. Lopez <leandro.castoldi at gmail dot com>
 *
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 */

class ConvertMDFePHP
{

    /**
     * xml
     * XML da MDFe
     * @var string 
     */
    public $xml = '';

    /**
     * chave
     * ID da MDFe 44 digitos
     * @var string 
     */
    public $chave = '';

    /**
     * txt
     * @var string TXT com MDFe
     */
    public $txt = '';

    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg = '';

    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus = false;

    /**
     * tpAmb
     * Tipo de ambiente
     * @var string
     */
    public $tpAmb = '';
    
    /**
     * $tpEmis
     * Tipo de emissão
     * @var string
     */
    public $tpEmis = '';

    /**
     * contruct
     * Método contrutor da classe
     *
     * @name contruct
     * @param boolean $limparString Ativa flag para limpar os caracteres especiais e acentos
     * @return none
     */
    public function __construct()
    {
 
    } //fim __contruct

    /**
     * MDFetxt2xml
     * Converte o arquivo txt em um array para ser mais facilmente tratado
     *
     * @name MDFetxt2xml
     * @param mixed $txt Path para o arquivo txt, array ou o conteudo do txt em uma string
     * @return string xml construido
     */
    public function MDFetxt2xml($txt, $tpAmb, $tipEmiss)
    {
        if (is_file($txt)) {
            $aDados = file($txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT);
        } else {
            if (is_array($txt)) {
                $aDados = $txt;
            } else {
                if (strlen($txt) > 0) {
                    $aDados = explode("\n", $txt);
                }
            }
        }
        return $this->MDFeTxt2XmlArrayComLinhas($aDados, $tpAmb, $tipEmiss);
    } //fim MDFetxt2xml

    /**
     * MDFeTxt2XmlArrayComLinhas
     * Método de conversão das MDFe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas versão 2.0.0 (24/08/2010)
     *
     * @name MDFeTxt2XmlArrayComLinhas
     * @param string $arrayComAsLinhasDoArquivo Array de Strings onde cada elemento é uma linha do arquivo
     * @return string xml construido
     */
    protected function MDFeTxt2XmlArrayComLinhas($arrayComAsLinhasDoArquivo, $tpAmb, $tipEmiss)
    {
        $arquivo = $arrayComAsLinhasDoArquivo;
        $notas = array();
        $currnota = -1;
        

        //lê linha por linha do arquivo txt
        for ($l = 0; $l < count($arquivo); $l++) {
            //separa os elementos do arquivo txt usando o pipe "|"
            $dados = explode("|", $arquivo[$l]);
            //remove todos os espaços adicionais, tabs, linefeed, e CR
            //de todos os campos de dados retirados do TXT
            for ($x = 0; $x < count($dados); $x++) {
                if (!empty($dados[$x])) {
                    $dados[$x] = trim(preg_replace('/\s\s+/', " ", $dados[$x]));
                    if ($this->limparString) {
                        $dados[$x] = $this->limpaString($dados[$x]);
                    }
                } //end if
            } //end for
            //monta o dado conforme o tipo, inicia lendo o primeiro campo da matriz
            switch ($dados[0]) {
                case "NOTA FISCAL":
                    // primeiro elemento não faz nada, aqui é informado o
                    //número de NF contidas no TXT
                    break;
                case "A":
                    //atributos da MDFe, campos obrigatórios [MDFe]
                    //A|versão do schema|id
                    // cria nota no array
                    $currnota++;
                    unset($dom, $MDFe, $infMDFe);
                    /// limpar todas variaveis utilizadas por cada MDFe....
                    
                    unset($dom, $MDFe, $infMDFe, $ide, $nMDF, $cMDF, $cDV, $dhEmi, $tpEmis, $verProc, $UFIni, $UFFim, $UFPer, $cMunDescarga, $xMunDescarga, $chMDFe, $SegCodBarra, $tpUnidTransp, $idUnidTransp, $placa, $tara, $tpRod, $tpCar, $UF, $nome, $cpf, $placa, $tara, $capKG, $tpCar, $UF, $tpRod, $qMDFe, $vCarga, $cUnid, $qCarga);

                    $this->chave = '';
                    $this->tpAmb = $tpAmb;
                    $this->xml = '';
                    $this->tpEmis = $tipEmiss;

                    $notas[$currnota] = array(
                        'dom' => false,
                        'MDFe' => false,
                        'infMDFe' => false,
                        'chave' => '',
                        'tpAmb' => $this->tpAmb);

                    //cria o objeto DOM para o xml
                    $notas[$currnota]['dom'] = new DOMDocument('1.0', 'UTF-8');
                    $dom = & $notas[$currnota]['dom'];
                    $dom->formatOutput = false;
                    $dom->preserveWhiteSpace = false;
                    $notas[$currnota]['MDFe'] = $dom->createElement("MDFe");
                    $MDFe = & $notas[$currnota]['MDFe'];
                    $MDFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/mdfe");
                    $notas[$currnota]['infMDFe'] = $dom->createElement("infMDFe");
                    $infMDFe = &$notas[$currnota]['infMDFe'];
                    //$infMDFe->setAttribute("Id", $dados[2]);
                    //$infMDFe->setAttribute("versao", $dados[1]);
                    //pega a chave de 44 digitos excluindo o a sigla MDFe
                    //$this->chave = substr($dados[2], 3, 44);
                    //$notas[$currnota]['chave'] = $this->chave;
                    break;
                case "ide": //identificadores [infMDFe]
                    //infMDFe => ide|cUF|tpAmb|tpEmit|mod|serie|nMDF|cMDF|cDV|modal|dhEmi|tpEmis|procEmi|verProc|UFIni|UFFim
                    $ide = $dom->createElement("ide");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $ide->appendChild($cUF);
                    $tpAmb = $dom->createElement("tpAmb", $this->tpAmb);
                    $ide->appendChild($tpAmb);
                    $tpEmit = $dom->createElement("tpEmit", $dados[3]);
                    $ide->appendChild($tpEmit);
                    $mod = $dom->createElement("mod", $dados[4]);
                    $ide->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[5]);
                    $ide->appendChild($serie);
                    $nMDF = $dom->createElement("nMDF", $dados[6]);
                    $ide->appendChild($nMDF);
                    $cMDF = $dom->createElement("cMDF", $dados[7]);
                    $ide->appendChild($cMDF);
                    $cDV = $dom->createElement("cDV", $dados[8]);
                    $ide->appendChild($cDV);
                    $modal = $dom->createElement("modal", $dados[9]);
                    $ide->appendChild($modal);
                    $dhEmi = $dom->createElement("dhEmi", $dados[10]);
                    $ide->appendChild($dhEmi);
                    $tpEmis = $dom->createElement("tpEmis", $this->tpEmis);
                    $ide->appendChild($tpEmis);
                    $procEmi = $dom->createElement("procEmi", $dados[12]);
                    $ide->appendChild($procEmi);
                    $verProc = $dom->createElement("verProc", $dados[13]);
                    $ide->appendChild($verProc);
                    $UFIni = $dom->createElement("UFIni", $dados[14]);
                    $ide->appendChild($UFIni);
                    $UFFim = $dom->createElement("UFFim", $dados[15]);
                    $ide->appendChild($UFFim);
                    
                    $infMDFe->appendChild($ide);
                    break;
                case "infMunCarrega":
                    // infMDFe => ide => infMunCarrega|cMunCarrega|xMunCarrega
                    if (!isset($infMunCarrega)) {
                        $notas[$currnota]['infMunCarrega'] = $dom->createElement("infMunCarrega");
                        $infMunCarrega = & $notas[$currnota]['infMunCarrega'];
                        //$ide->insertBefore($ide->appendChild($infMunCarrega), $UFIni);
                        $ide->appendChild($infMunCarrega);
                    }
                    $cMunCarrega = $dom->createElement("cMunCarrega", $dados[1]);
                    $infMunCarrega->appendChild($cMunCarrega);
                    $xMunCarrega = $dom->createElement("xMunCarrega", $dados[2]);
                    $infMunCarrega->appendChild($xMunCarrega);
                    break;
                case "infPercurso":
                    // infMDFe => ide => infPercurso|UFPer
                    //if (!isset($infPercurso)) {
                        $infPercurso = $dom->createElement("infPercurso");
                        //$ide->insertBefore($ide->appendChild($infPercurso), $infMunCarrega);
                        $ide->appendChild($infPercurso);
                    //}
                    $UFPer = $dom->createElement("UFPer", $dados[1]);
                    $infPercurso->appendChild($UFPer);
                    break;
                case "emit":
                    // infMDFe => emit|CNPJ|IE|xNome|
                    $emit = $dom->createElement("emit");
                    $cnpj = $dom->createElement("CNPJ", $dados[1]);
                    $emit->appendChild($cnpj);
                    $IE = $dom->createElement("IE", $dados[2]);
                    $emit->appendChild($IE);
                    $xNome = $dom->createElement("xNome", $dados[3]);
                    $emit->appendChild($xNome);

                    $infMDFe->appendChild($emit);
                    break;
                case "enderEmit":
                    // infMDFe => emit => enderEmit|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF
                    $enderEmi = $dom->createElement("enderEmit");
                    $xLgr = $dom->createElement("xLgr", $dados[1]);
                    $enderEmi->appendChild($xLgr);
                    $dados[2] = abs((int) $dados[2]);
                    $nro = $dom->createElement("nro", $dados[2]);
                    $enderEmi->appendChild($nro);
                    if (!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $enderEmi->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[4]);
                    $enderEmi->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[5]);
                    $enderEmi->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[6]);
                    $enderEmi->appendChild($xMun);
                    if (!empty($dados[7])) {
                        $CEP = $dom->createElement("CEP", $dados[7]);
                        $enderEmi->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[8]);
                    $enderEmi->appendChild($UF);
                    
                    //$emit->insertBefore($emit->appendChild($enderEmi), $xNome);
                    
                    $emit->appendChild($enderEmi);
                    break;
                case "infModal":
                    // infMDFe => infModal|versaoModal
                    $infModal = $dom->createElement("infModal");
                    $infModal->setAttribute("versaoModal", $dados[1]);
                    
                    $infMDFe->appendChild($infModal);
                    break;
                case "veicTracao":
                    // infMDFe => infModal => rodo => veicTracao|placa|tara|tpRod|tpCar|UF
                    $rodo = $dom->createElement("rodo");
                    $infModal->appendChild($rodo);
                    
                    $veicTracao = $dom->createElement("veicTracao");
                    $placa = $dom->createElement("placa", $dados[1]);
                    $veicTracao->appendChild($placa);
                    $tara = $dom->createElement("tara", $dados[2]);
                    $veicTracao->appendChild($tara);
                    $tpRod = $dom->createElement("tpRod", $dados[3]);
                    $veicTracao->appendChild($tpRod);
                    $tpCar = $dom->createElement("tpCar", $dados[4]);
                    $veicTracao->appendChild($tpCar);
                    $UF = $dom->createElement("UF", $dados[5]);
                    $veicTracao->appendChild($UF);

                    $rodo->appendChild($veicTracao);
/*
                    if ($this->tpAmb == '2') {
                        if ($dados[1] != '') {
                            //operação nacional em ambiente homologação usar 99999999000191
                            $CNPJ = $dom->createElement("CNPJ", '99999999000191');
                        } else {
                            //operação com o exterior CNPJ vazio
                            $CNPJ = $dom->createElement("CNPJ", '');
                        }
                    } else {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                    }//fim teste ambiente
                    $dest->insertBefore($dest->appendChild($CNPJ), $xNome);
*/
                    break;
                case "condutor":
                    // infMDFe => infModal => rodo => veicTracao => condutor|xNome|CPF
                    $condutor = $dom->createElement("condutor");
                    $xNome = $dom->createElement("xNome", $dados[1]);
                    $condutor->appendChild($xNome);
                    $CPF = $dom->createElement("CPF", $dados[2]);
                    $condutor->appendChild($CPF);
                    
                    $veicTracao->insertBefore($veicTracao->appendChild($condutor), $tpRod);
                    break;
                case "veicReboque":
                    // infMDFe => infModal => rodo => veicReboque|placa|tara|capKG|tpCar|UF
                    $veicReboque = $dom->createElement("veicReboque");
                    $placa = $dom->createElement("placa", $dados[1]);
                    $veicReboque->appendChild($placa);
                    $tara = $dom->createElement("tara", $dados[2]);
                    $veicReboque->appendChild($tara);
                    $capKG = $dom->createElement("capKG", $dados[3]);
                    $veicReboque->appendChild($capKG);
                    $tpCar = $dom->createElement("tpCar", $dados[4]);
                    $veicReboque->appendChild($tpCar);
                    $UF = $dom->createElement("UF", $dados[5]);
                    $veicReboque->appendChild($UF);

                    $rodo->appendChild($veicReboque);
                    break;
                case "infMunDescarga": 
                    // infMDFe => infDoc => infMunDescarga|cMunDescarga|xMunDescarga
                    if (!isset($infDoc)) {
                        $infDoc = $dom->createElement("infDoc");
                        //$infMDFe->insertBefore($infMDFe->appendChild($infDoc), $infModal);
                        $infMDFe->appendChild($infDoc);
                    }
                    $infMunDescarga = $dom->createElement("infMunDescarga");
                    $infDoc->appendChild($infMunDescarga);
                    
                    $cMunDescarga = $dom->createElement("cMunDescarga", $dados[1]);
                    $infMunDescarga->appendChild($cMunDescarga);
                    $xMunDescarga = $dom->createElement("xMunDescarga", $dados[2]);
                    $infMunDescarga->appendChild($xMunDescarga);
                    
                    break;
                case "infNFe":
                    // infMDFe => infDoc => infMunDescarga => infNFe|chNFe|SegCodBarra
                                        
                    $infNFe = $dom->createElement("infNFe");
                    $infMunDescarga->appendChild($infNFe);
                    
                    $chNFe = $dom->createElement("chNFe", $dados[1]);
                    $infNFe->appendChild($chNFe);
                    
                    if (!empty($dados[2])) {
                        $SegCodBarra = $dom->createElement("SegCodBarra", $dados[2]);
                        $infNFe->appendChild($SegCodBarra);
                    }
                    break;
                case "tot":
                    // infMDFe => tot|qNFe|vCarga|cUnid|qCarga
                    $tot = $dom->createElement("tot");
                    
                    $qNFe = $dom->createElement("qNFe", $dados[1]);
                    $tot->appendChild($qNFe);
                    $vCarga = $dom->createElement("vCarga", $dados[2]);
                    $tot->appendChild($vCarga);
                    $cUnid = $dom->createElement("cUnid", $dados[3]);
                    $tot->appendChild($cUnid);
                    $qCarga = $dom->createElement("qCarga", $dados[4]);
                    $tot->appendChild($qCarga);

                    $infMDFe->appendChild($tot);
                    break;
            } //end switch
        } //end for
        $arquivos_xml = array();
        foreach ($notas as $nota) {
            unset($dom, $MDFe, $infMDFe);
            $dom = $nota['dom'];
            $MDFe = $nota['MDFe'];
            $infMDFe = $nota['infMDFe'];
            //$this->chave = $nota['chave'];
            //$this->tpAmb = $nota['tpAmb'];
            $this->xml = '';
            //salva o xml na variável se o txt não estiver em branco
            if (!empty($infMDFe)) {
                $MDFe->appendChild($infMDFe);
                $dom->appendChild($MDFe);
                $this->montaChaveXML($dom);
                $xml = $dom->saveXML();
                $this->xml = $dom->saveXML();
                $xml = str_replace('<?xml version="1.0" encoding="UTF-8  standalone="no"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
                //remove linefeed, carriage return, tabs e multiplos espaços
                $xml = preg_replace('/\s\s+/', ' ', $xml);
                $xml = str_replace("> <", "><", $xml);
                $arquivos_xml[] = $xml;
                unset($xml);
            }
        }
        return($arquivos_xml);
    }
    //end function

    /**
     * limpaString
     * Remove todos dos caracteres especiais do texto e os acentos
     * preservando apenas letras de A-Z numeros de 0-9 e os caracteres @ , - ; : / _
     * 
     * @name limpaString
     * @param string $texto String a ser limpa
     * @return  string Texto sem caractere especiais
     */
    private function limpaString($texto)
    {
        $aFind = array('&', 'á', 'à', 'ã', 'â', 'é', 'ê',
            'í', 'ó', 'ô', 'õ', 'ú', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â',
            'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ', 'Ú', 'Ü', 'Ç');
        $aSubs = array('e', 'a', 'a', 'a', 'a', 'e', 'e',
            'i', 'o', 'o', 'o', 'u', 'u', 'c', 'A', 'A', 'A', 'A',
            'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/_]/", "", $novoTexto);
        return $novoTexto;
    } //fim limpaString

    /**
     * calculaDV
     * Função para o calculo o digito verificador da chave da MDFe
     * 
     * @name calculaDV
     * @param string $chave43
     * @return string 
     */
    private function calculaDV($chave43)
    {
        $multiplicadores = array(2, 3, 4, 5, 6, 7, 8, 9);
        $i = 42;
        $soma_ponderada = 0;
        while ($i >= 0) {
            for ($m = 0; $m < count($multiplicadores) && $i >= 0; $m++) {
                $soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
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
    } //fim calculaDV

    /**
     * montaChaveXML
     * Monta a chave da MDFe de 44 digitos com base em seus dados
     * Isso é útil no caso da chave formada no txt estar errada
     * 
     * @name montaChaveXML
     * @param object $dom 
     */
    private function montaChaveXML($dom)
    {
        $ide = $dom->getElementsByTagName("ide")->item(0);
        $emit = $dom->getElementsByTagName("emit")->item(0);
        $cUF = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $dEmi = $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue;
        $CNPJ = $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $nMDF = $ide->getElementsByTagName('nMDF')->item(0)->nodeValue;
        $tpEmis = $this->tpEmis; // $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
        $cMDF = $ide->getElementsByTagName('cMDF')->item(0)->nodeValue;
        if (strlen($cMDF) != 8) {
            $cMDF = $ide->getElementsByTagName('cMDF')->item(0)->nodeValue = rand(10000001, 99999999);
        }
        $tmpData = explode("T", $dEmi);
        $tempData = $dt = explode("-", $tmpData[0]);
        $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d";
        $tempChave = sprintf($forma, $cUF, $tempData[0] - 2000, $tempData[1], $CNPJ, $mod, $serie, $nMDF, $tpEmis, $cMDF);
        $cDV = $ide->getElementsByTagName('cDV')->item(0)->nodeValue = $this->calculaDV($tempChave);
        $this->chave = $tempChave .= $cDV;
        $infMDFe = $dom->getElementsByTagName("infMDFe")->item(0);
        $infMDFe->setAttribute("Id", "MDFe" . $this->chave);
        $infMDFe->setAttribute("versao", '1.00');
    } //fim calculaChave
}//fim da classe
