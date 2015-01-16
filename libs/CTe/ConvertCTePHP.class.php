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
 * @name        ConvertCTePHP
 * @version     1.0.2
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Lucimar A. Magalhaes <lucimar.magalhaes at assistsolucoes dot com dot br>
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              Daniel Batista Lemes <dlemes at gmail dot com>
 *              Joao Eduardo Silva Correa <jcorrea at sucden dot com dot br>
 *              Roberto Spadim <rspadim at gmail dot com>
 * 
 *<Nota de Lucimar>
 *    	Fiz esse conversor de CTe > TXT baseado no conversor NFe > TXT do projeto.
 *	Alguns campos e grupos eu não implementei, pois não foi necessário o uso no meu cliente.
 *	Então, criei um TODO List desses campos e grupos não implementados.
 *
 *	TODO List: (número - nome do campo, de acordo com o manual 1.0.4 de 25/05/2012.
 *                  O * depois do campo significa todo o grupo)
 *	63  - fluxo*
 *	290 - docAnt*
 *	323 - veicNovos*
 *	340 - infCteSub*
 *	396 - ICMSSN*
 *	399 - infCteAnu*
 * 
 *      TODO: Passar para a versão 2.00 do xml da CTe
 */

// Define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);
}

require_once(PATH_ROOT.'libs/Common/CommonNFePHP.class.php');

class ConvertCTePHP extends CommonNFePHP
{

    /**
     * xml
     * XML do CTe
     * @var string 
     */
    public $xml = '';

    /**
     * chave
     * ID do CTe 44 digitos
     * @var string 
     */
    public $chave = '';

    /**
     * txt
     * @var string TXT com CTe
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
     * limpar_string
     * Se for = true remove caracteres especiais na conversão de TXT pra XML
     * @var boolean
     */
    public $limpar_string = true;

    /**
     * __contruct
     * Método contrutor da classe
     *
     * @param boolean $limpar_string Ativa flag para limpar os caracteres especiais e acentos
     * @return none
     */
    function __construct($limpar_string = true)
    {
        $this->limpar_string = $limpar_string;
    }

    /**
     * nfetxt2xml
     * Método de conversão dos CTe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Conhecimento de Transporte Eletrônico versão 1.0.4 (25/05/2012)
     *
     * @param mixed $txt Path para o arquivo txt, array ou o conteudo do txt em uma string
     * @return string xml construido
     */
    public function ctetxt2xml($txt)
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
        return $this->zCtetxt2xmlArrayLinhas($aDados);
    } //fim ctetxt2xml

    /**
     * zCtetxt2xmlArrayLinhas
     * Método de conversão das CTe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas versão 1.0.4 (25/05/2012)
     *
     * @param string $arrayComAsLinhasDoArquivo Array de Strings onde cada elemento é uma linha do arquivo
     * @return string xml construido
     */
    protected function zCtetxt2xmlArrayLinhas($arrayComAsLinhasDoArquivo)
    {
        $arquivo = $arrayComAsLinhasDoArquivo;
        $ctes = array();
        $cur_cte = -1;

        //lê linha por linha do arquivo txt
        for ($l = 0; $l < count($arquivo); $l++) {
            //separa os elementos do arquivo txt usando o pipe "|"
            $dados = explode("|", $arquivo[$l]);
            //remove todos os espaços adicionais, tabs, linefeed, e CR
            //de todos os campos de dados retirados do TXT
            for ($x = 0; $x < count($dados); $x++) {
                if (!empty($dados[$x])) {
                    $dados[$x] = trim(preg_replace('/\s\s+/', " ", $dados[$x]));
                    if ($this->limpar_string) {
                        $dados[$x] = $this->zLimpaString($dados[$x]);
                    }
                } //end if
            } //end for

            //monta o dado conforme o tipo, inicia lendo o primeiro campo da matriz
            switch ($dados[0]) {
                case "REGISTROSCTE":
                    break;
                case "CTE":
                    $cur_cte++;
                    unset($dom, $CTe, $infCte, $versao, $id, $ide, $cUF, $cCT, $CFOP,
                        $natOp, $forPag, $mod, $serie, $nCT, $dhEmi, $tpImp, $tpEmis,
                        $cDV, $tpAmb, $tpCTe,
                        $procEmi, $verProc, $refCTE, $cMunEnv, $xMunEnv, $UFEnv, $modal,
                        $tpServ, $cMunIni, $xMunIni, $UFIni, $cMunFim, $xMunFim, $UFFim,
                        $retira, $xDetRetira,
                        $toma03, $toma, $toma4, $CNPJ, $CPF, $IE, $xNome, $xFant, $fone,
                        $enderToma, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $CEP,
                        $UF, $cPais, $xPais, $email,
                        $dhCont, $xJust, $compl, $xEmi, $xObs, $emit, $enderEmit, $rem,
                        $enderReme, $infNFe, $chave, $PIN, $infOutros, $tpDoc, $descOutros,
                        $nDoc, $dEmi, $vDocFisc,
                        $dest, $ISUF, $enderDest, $vPrest, $vTPrest, $vRec, $Comp, $xNome,
                        $vComp, $imp, $ICMS, $infAdFisco, $ICMS00, $CST, $vBC, $pICMS,
                        $vICMS, $ICMS45, $CST,
                        $infCTeNorm, $infCarga, $vCarga, $proPred, $xOutCat, $infQ, $cUnid,
                        $tpMed, $qCarga, $contQt, $nCont, $lacContQt, $nLacre, $dPrev,
                        $seg, $respSeg, $xSeg,
                        $nApol, $nAver, $vCarga, $infModal, $versaoModal, $rodo, $RNTRC,
                        $lota, $occ, $nOCC, $emiOcc, $cInt, $veic, $cInt, $RENAVAM, $placa,
                        $tara, $capKG, $capM3,
                        $tpProp, $tpVeic, $tpRod, $tpCar, $prop, $moto, $peri, $nONU,
                        $xNomeAE, $xClaRisco, $grEmb, $qTotProd, $qVolTipo, $pontoFulgor,
                        $cobr, $fat, $nFat, $vOrig,
                        $vDesc, $vLiq, $dup, $nDup, $dVenc, $vDup, $infCteComp,
                        $vPresComp, $compComp, $impComp, $ICMSComp);

                    $this->chave = '';
                    $this->tpAmb = '';
                    $this->xml = '';
                    $ctes[$cur_cte] = array(
                        'dom' => false,
                        'CTe' => false,
                        'infCte' => false,
                        'refCTE' => false,
                        'chave' => '',
                        'tpAmb' => '');
                    $ctes[$cur_cte]['dom'] = new DOMDocument('1.0', 'UTF-8');
                    $dom = &$ctes[$cur_cte]['dom'];
                    $dom->formatOutput = true;
                    $dom->preserveWhiteSpace = false;
                    $ctes[$cur_cte]['CTe'] = $dom->createElement("CTe");
                    $CTe = &$ctes[$cur_cte]['CTe'];
                    $CTe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/cte");
                    $ctes[$cur_cte]['infCte'] = $dom->createElement("infCte");
                    $infCte = &$ctes[$cur_cte]['infCte'];
                    $infCte->setAttribute("versao", $dados[1]);
                    $infCte->setAttribute("Id", $dados[2]);
                    $this->chave = substr($dados[2], 3, 44);
                    $ctes[$cur_cte]['chave'] = $this->chave;
                    break;
                case "IDE":
                    $ide = $dom->createElement("ide");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $ide->appendChild($cUF);
                    $cCT = $dom->createElement("cCT", $dados[2]);
                    $ide->appendChild($cCT);
                    $CFOP = $dom->createElement("CFOP", $dados[3]);
                    $ide->appendChild($CFOP);
                    $natOp = $dom->createElement("natOp", $dados[4]);
                    $ide->appendChild($natOp);
                    $forPag = $dom->createElement("forPag", $dados[5]);
                    $ide->appendChild($forPag);
                    $mod = $dom->createElement("mod", $dados[6]);
                    $ide->appendChild($mod);
                    $serie = $dom->createElement("serie", (int)$dados[7]);
                    $ide->appendChild($serie);
                    $nCT = $dom->createElement("nCT", $dados[8]);
                    $ide->appendChild($nCT);
                    $dhEmi = $dom->createElement("dhEmi", $dados[9]);
                    $ide->appendChild($dhEmi);
                    $tpImp = $dom->createElement("tpImp", $dados[10]);
                    $ide->appendChild($tpImp);
                    $tpEmis = $dom->createElement("tpEmis", $dados[11]);
                    $ide->appendChild($tpEmis);
                    $CDV = $dom->createElement("cDV", $dados[12]);
                    $ide->appendChild($CDV);
                    $tpAmb = $dom->createElement("tpAmb", $dados[13]);
                    //guardar a variavel para uso posterior
                    $this->tpAmb = $dados[13];
                    $ctes[$cur_cte]['tpAmb'] = $this->tpAmb;
                    $ide->appendChild($tpAmb);
                    $tpCTe = $dom->createElement("tpCTe", $dados[14]);
                    $ide->appendChild($tpCTe);
                    $procEmi = $dom->createElement("procEmi", $dados[15]);
                    $ide->appendChild($procEmi);
                    $verProc = $dom->createElement("verProc", $dados[16]);
                    $ide->appendChild($verProc);
                    if (empty($dados[17])) {
                        $dados[17] = "NfePHP";
                    }
                    $cMunEnv = $dom->createElement("cMunEnv", $dados[18]);
                    $ide->appendChild($cMunEnv);
                    $xMunEnv = $dom->createElement("xMunEnv", $dados[19]);
                    $ide->appendChild($xMunEnv);
                    $UFEnv = $dom->createElement("UFEnv", $dados[20]);
                    $ide->appendChild($UFEnv);
                    $modal = $dom->createElement("modal", $dados[21]);
                    $ide->appendChild($modal);
                    $tpServ = $dom->createElement("tpServ", $dados[22]);
                    $ide->appendChild($tpServ);
                    $cMunIni = $dom->createElement("cMunIni", $dados[23]);
                    $ide->appendChild($cMunIni);
                    $xMunIni = $dom->createElement("xMunIni", $dados[24]);
                    $ide->appendChild($xMunIni);
                    $UFIni = $dom->createElement("UFIni", $dados[25]);
                    $ide->appendChild($UFIni);
                    $cMunFim = $dom->createElement("cMunFim", $dados[26]);
                    $ide->appendChild($cMunFim);
                    $xMunFim = $dom->createElement("xMunFim", $dados[27]);
                    $ide->appendChild($xMunFim);
                    $UFFim = $dom->createElement("UFFim", $dados[28]);
                    $ide->appendChild($UFFim);
                    $retira = $dom->createElement("retira", $dados[29]);
                    $ide->appendChild($retira);
                    if (!empty($dados[30])) {
                        $xDetRetira = $dom->createElement("xDetRetira", $dados[30]);
                        $ide->appendChild($xDetRetira);
                    }
                    $infCte->appendChild($ide);
                    break;
                case "TOMA03":
                    $toma03 = $dom->createElement("toma03");
                    $toma = $dom->createElement("toma", $dados[1]);
                    $toma03->appendChild($toma);
                    $ide->appendChild($toma03);
                    break;
                case "TOMA4":
                    $toma4 = $dom->createElement("toma4");
                    $toma = $dom->createElement("toma", $dados[1]);
                    $toma4->appendChild($toma);
                    if (!empty($dados[2])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[2]);
                        $toma4->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[3]);
                        $toma4->appendChild($CPF);
                    }
                    if (!empty($dados[4])) {
                        $IE = $dom->createElement("IE", $dados[4]);
                        $toma4->appendChild($IE);
                    }
                    $xNome = $dom->createElement("xNome", $dados[5]);
                    $toma4->appendChild($xNome);
                    $xFant = $dom->createElement("xFant", $dados[6]);
                    $toma4->appendChild($xFant);
                    if (!empty($dados[17])) {
                        $fone = $dom->createElement("fone", $dados[17]);
                        $toma4->appendChild($fone);
                    }
                    $enderToma = $dom->createElement("enderToma");
                    $xLgr = $dom->createElement("xLgr", $dados[7]);
                    $enderToma->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[8]);
                    $enderToma->appendChild($nro);
                    if (!empty($dados[9])) {
                        $xCpl = $dom->createElement("xCpl", $dados[9]);
                        $enderToma->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[10]);
                    $enderToma->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[11]);
                    $enderToma->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[12]);
                    $enderToma->appendChild($xMun);
                    if (!empty($dados[13])) {
                        $CEP = $dom->createElement("CEP", $dados[13]);
                        $enderToma->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[14]);
                    $enderToma->appendChild($UF);
                    if (!empty($dados[15])) {
                        $cPais = $dom->createElement("cPais", $dados[15]);
                        $enderToma->appendChild($cPais);
                    }
                    if (!empty($dados[16])) {
                        $xPais = $dom->createElement("xPais", $dados[16]);
                        $enderToma->appendChild($xPais);
                    }
                    $toma4->appendChild($enderToma);
                    if (!empty($dados[18])) {
                        $email = $dom->createElement("email", $dados[18]);
                        $toma4->appendChild($email);
                    }
                    if (!empty($dados[19])) {
                        $dhCont = $dom->createElement("dhCont", $dados[19]);
                        $toma4->appendChild($dhCont);
                    }
                    if (!empty($dados[20])) {
                        $xJust = $dom->createElement("xJust", $dados[20]);
                        $toma4->appendChild($xJust);
                    }
                    $ide->appendChild($toma4);
                    break;
                case "COMPL":
                    $compl = $dom->createElement("compl");
                    if (!empty($dados[1])) {
                        $xCaracAd = $dom->createElement("xCaracAd", $dados[1]);
                        $compl->appendChild($xCaracAd);
                    }
                    if (!empty($dados[2])) {
                        $xCaracSer = $dom->createElement("xCaracSer", $dados[2]);
                        $compl->appendChild($xCaracSer);
                    }
                    if (!empty($dados[3])) {
                        $xEmi = $dom->createElement("xEmi", $dados[3]);
                        $compl->appendChild($xEmi);
                    }
                    if (!empty($dados[4])) {
                        $origCalc = $dom->createElement("origCalc", $dados[4]);
                        $compl->appendChild($origCalc);
                    }
                    if (!empty($dados[5])) {
                        $destCalc = $dom->createElement("destCalc", $dados[5]);
                        $compl->appendChild($destCalc);
                    }
                    if (!empty($dados[6])) {
                        $xObs = $dom->createElement("xObs", $dados[6]);
                        $compl->appendChild($xObs);
                    }
                    $infCte->appendChild($compl);
                    break;
                case "ENTREGA":
                    $Entrega = $dom->createElement("Entrega");
                    $compl->appendChild($Entrega);
                    break;
                case "COMDATA":
                    $comData = $dom->createElement("comData");
                    $tpPer = $dom->createElement("tpPer", $dados[1]);
                    $comData->appendChild($tpPer);
                    $dProg = $dom->createElement("dProg", $dados[2]);
                    $comData->appendChild($dProg);
                    $Entrega->appendChild($comData);
                    break;
                case "SEMDATA":
                    $semData = $dom->createElement("semData");
                    $tpPer = $dom->createElement("tpPer", $dados[1]);
                    $semData->appendChild($tpPer);
                    $Entrega->appendChild($semData);
                    break;
                case "NOPERIODO":
                    $noPeriodo = $dom->createElement("noPeriodo");
                    $tpPer = $dom->createElement("tpPer", $dados[1]);
                    $noPeriodo->appendChild($tpPer);
                    $dIni = $dom->createElement("dIni", $dados[2]);
                    $noPeriodo->appendChild($dIni);
                    $dFim = $dom->createElement("dFim", $dados[3]);
                    $noPeriodo->appendChild($dFim);
                    $Entrega->appendChild($noPeriodo);
                    break;
                case "SEMHORA":
                    $semHora = $dom->createElement("semHora");
                    $tpHor = $dom->createElement("tpHor", $dados[1]);
                    $semHora->appendChild($tpHor);
                    $Entrega->appendChild($semHora);
                    break;
                case "COMHORA":
                    $comHora = $dom->createElement("comHora");
                    $tpHor = $dom->createElement("tpHor", $dados[1]);
                    $comHora->appendChild($tpHor);
                    $hProg = $dom->createElement("hProg", $dados[2]);
                    $comHora->appendChild($hProg);
                    $Entrega->appendChild($comHora);
                    break;
                case "NOINTER":
                    $noInter = $dom->createElement("noInter");
                    $tpHor = $dom->createElement("tpHor", $dados[1]);
                    $noInter->appendChild($tpHor);
                    $hIni = $dom->createElement("hIni", $dados[2]);
                    $noInter->appendChild($hIni);
                    $hFim = $dom->createElement("hFim", $dados[3]);
                    $noInter->appendChild($hFim);
                    $Entrega->appendChild($noInter);
                    break;
                case "OBSCONT":
                    $obscont = $dom->createElement("ObsCont");
                    $obscont->setAttribute("xCampo", $dados[1]);
                    $xTexto = $dom->createElement("xTexto", $dados[2]);
                    $obscont->appendChild($xTexto);
                    $compl->appendChild($obscont);
                    break;
                case "EMIT":
                    $emit = $dom->createElement("emit");
                    $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                    $emit->appendChild($CNPJ);
                    $IE = $dom->createElement("IE", $dados[2]);
                    $emit->appendChild($IE);
                    $xNome = $dom->createElement("xNome", $dados[3]);
                    $emit->appendChild($xNome);
                    if (!empty($dados[4])) {
                        $xFant = $dom->createElement("xFant", $dados[4]);
                        $emit->appendChild($xFant);
                    }
                    $enderEmit = $dom->createElement("enderEmit");
                    $xLgr = $dom->createElement("xLgr", $dados[5]);
                    $enderEmit->appendChild($xLgr);
                    $dados[6] = abs((int)$dados[6]);
                    $nro = $dom->createElement("nro", $dados[6]);
                    $enderEmit->appendChild($nro);
                    if (!empty($dados[7])) {
                        $xCpl = $dom->createElement("xCpl", $dados[7]);
                        $enderEmit->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[8]);
                    $enderEmit->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[9]);
                    $enderEmit->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[10]);
                    $enderEmit->appendChild($xMun);
                    if (!empty($dados[11])) {
                        $CEP = $dom->createElement("CEP", $dados[11]);
                        $enderEmit->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[12]);
                    $enderEmit->appendChild($UF);
                    if (!empty($dados[13])) {
                        $fone = $dom->createElement("fone", $dados[13]);
                        $enderEmit->appendChild($fone);
                    }
                    $emit->appendChild($enderEmit);
                    $infCte->appendChild($emit);
                    break;
                case "REM":
                    $rem = $dom->createElement("rem");
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $rem->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[2]);
                        $rem->appendChild($CPF);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $rem->appendChild($IE);
                    $xNome = $dom->createElement("xNome", $dados[4]);
                    $rem->appendChild($xNome);
                    if (!empty($dados[5])) {
                        $xFant = $dom->createElement("xFant", $dados[5]);
                        $rem->appendChild($xFant);
                    }
                    if (!empty($dados[16])) {
                        $fone = $dom->createElement("fone", $dados[16]);
                        $rem->appendChild($fone);
                    }
                    $enderReme = $dom->createElement("enderReme");
                    $xLgr = $dom->createElement("xLgr", $dados[6]);
                    $enderReme->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[7]);
                    $enderReme->appendChild($nro);
                    if (!empty($dados[8])) {
                        $xCpl = $dom->createElement("xCpl", $dados[8]);
                        $enderReme->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[9]);
                    $enderReme->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[10]);
                    $enderReme->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[11]);
                    $enderReme->appendChild($xMun);
                    if (!empty($dados[12])) {
                        $CEP = $dom->createElement("CEP", $dados[12]);
                        $enderReme->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[13]);
                    $enderReme->appendChild($UF);
                    if (!empty($dados[14])) {
                        $cPais = $dom->createElement("cPais", $dados[14]);
                        $enderReme->appendChild($cPais);
                    }
                    if (!empty($dados[15])) {
                        $xPais = $dom->createElement("xPais", $dados[15]);
                        $enderReme->appendChild($xPais);
                    }
                    $rem->appendChild($enderReme);
                    if (!empty($dados[17])) {
                        $email = $dom->createElement("email", $dados[17]);
                        $rem->appendChild($email);
                    }
                    $infCte->appendChild($rem);
                    break;
                case "INFNF":
                    $infNF = $dom->createElement("infNF");
                    if (!empty($dados[1])) {
                        $nRoma = $dom->createElement("nRoma", $dados[1]);
                        $infNF->appendChild($nRoma);
                    }
                    if (!empty($dados[2])) {
                        $nPed = $dom->createElement("nPed", $dados[2]);
                        $infNF->appendChild($nPed);
                    }
                    $mod = $dom->createElement("mod", $dados[3]);
                    $infNF->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[4]);
                    $infNF->appendChild($serie);
                    $nDoc = $dom->createElement("nDoc", $dados[5]);
                    $infNF->appendChild($nDoc);
                    $dEmi = $dom->createElement("dEmi", $dados[6]);
                    $infNF->appendChild($dEmi);
                    $vBC = $dom->createElement("vBC", $dados[7]);
                    $infNF->appendChild($vBC);
                    $vICMS = $dom->createElement("vICMS", $dados[8]);
                    $infNF->appendChild($vICMS);
                    $vBCST = $dom->createElement("vBCST", $dados[9]);
                    $infNF->appendChild($vBCST);
                    $vST = $dom->createElement("vST", $dados[10]);
                    $infNF->appendChild($vST);
                    $vProd = $dom->createElement("vProd", $dados[11]);
                    $infNF->appendChild($vProd);
                    $vNF = $dom->createElement("vNF", $dados[12]);
                    $infNF->appendChild($vNF);
                    $nCFOP = $dom->createElement("nCFOP", $dados[13]);
                    $infNF->appendChild($nCFOP);
                    if (!empty($dados[14])) {
                        $nPeso = $dom->createElement("nPeso", $dados[14]);
                        $infNF->appendChild($nPeso);
                    }
                    if (!empty($dados[15])) {
                        $PIN = $dom->createElement("PIN", $dados[15]);
                        $infNF->appendChild($PIN);
                    }
                    $rem->appendChild($infNF);
                    break;
                case "INFNFE":
                    $infNFe = $dom->createElement("infNFe");
                    $chave = $dom->createElement("chave", $dados[1]);
                    $infNFe->appendChild($chave);
                    if (!empty($dados[2])) {
                        $PIN = $dom->createElement("PIN", $dados[2]);
                        $infNFe->appendChild($PIN);
                    }
                    $rem->appendChild($infNFe);
                    break;
                case "INFOUTROS":
                    $infOutros = $dom->createElement("infOutros");
                    $tpDoc = $dom->createElement("tpDoc", $dados[1]);
                    $infOutros->appendChild($tpDoc);
                    if (!empty($dados[2])) {
                        $descOutros = $dom->createElement("descOutros", $dados[2]);
                        $infOutros->appendChild($descOutros);
                    }
                    if (!empty($dados[3])) {
                        $nDoc = $dom->createElement("nDoc", $dados[3]);
                        $infOutros->appendChild($nDoc);
                    }
                    if (!empty($dados[4])) {
                        $dEmi = $dom->createElement("dEmi", $dados[4]);
                        $infOutros->appendChild($dEmi);
                    }
                    if (!empty($dados[5])) {
                        $vDocFisc = $dom->createElement("vDocFisc", $dados[5]);
                        $infOutros->appendChild($vDocFisc);
                    }
                    $rem->appendChild($infOutros);
                    break;
                case "EXPED":
                    $exped = $dom->createElement("exped");
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $exped->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[2]);
                        $exped->appendChild($CPF);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $exped->appendChild($IE);
                    $xNome = $dom->createElement("xNome", $dados[4]);
                    $exped->appendChild($xNome);
                    if (!empty($dados[15])) {
                        $fone = $dom->createElement("fone", $dados[15]);
                        $exped->appendChild($fone);
                    }
                    $enderExped = $dom->createElement("enderExped");
                    $xLgr = $dom->createElement("xLgr", $dados[5]);
                    $enderExped->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[6]);
                    $enderExped->appendChild($nro);
                    if (!empty($dados[7])) {
                        $xCpl = $dom->createElement("xCpl", $dados[7]);
                        $enderExped->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[8]);
                    $enderExped->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[9]);
                    $enderExped->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[10]);
                    $enderExped->appendChild($xMun);
                    if (!empty($dados[11])) {
                        $CEP = $dom->createElement("CEP", $dados[11]);
                        $enderExped->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[12]);
                    $enderExped->appendChild($UF);
                    if (!empty($dados[13])) {
                        $cPais = $dom->createElement("cPais", $dados[13]);
                        $enderExped->appendChild($cPais);
                    }
                    if (!empty($dados[14])) {
                        $xPais = $dom->createElement("xPais", $dados[14]);
                        $enderExped->appendChild($xPais);
                    }
                    $exped->appendChild($enderExped);
                    if (!empty($dados[16])) {
                        $email = $dom->createElement("email", $dados[16]);
                        $exped->appendChild($email);
                    }
                    $infCte->appendChild($exped);
                    break;
                case "RECEB":
                    $receb = $dom->createElement("receb");
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $receb->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[2]);
                        $receb->appendChild($CPF);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $receb->appendChild($IE);
                    $xNome = $dom->createElement("xNome", $dados[4]);
                    $receb->appendChild($xNome);
                    if (!empty($dados[15])) {
                        $fone = $dom->createElement("fone", $dados[15]);
                        $receb->appendChild($fone);
                    }
                    $enderReceb = $dom->createElement("enderReceb");
                    $xLgr = $dom->createElement("xLgr", $dados[5]);
                    $enderReceb->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[6]);
                    $enderReceb->appendChild($nro);
                    if (!empty($dados[7])) {
                        $xCpl = $dom->createElement("xCpl", $dados[7]);
                        $enderReceb->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[8]);
                    $enderReceb->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[9]);
                    $enderReceb->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[10]);
                    $enderReceb->appendChild($xMun);
                    if (!empty($dados[11])) {
                        $CEP = $dom->createElement("CEP", $dados[11]);
                        $enderReceb->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[12]);
                    $enderReceb->appendChild($UF);
                    if (!empty($dados[7])) {
                        $cPais = $dom->createElement("cPais", $dados[13]);
                        $enderReceb->appendChild($cPais);
                    }
                    if (!empty($dados[7])) {
                        $xPais = $dom->createElement("xPais", $dados[14]);
                        $enderReceb->appendChild($xPais);
                    }
                    $receb->appendChild($enderReceb);
                    if (!empty($dados[16])) {
                        $email = $dom->createElement("email", $dados[16]);
                        $dest->appendChild($email);
                    }
                    $infCte->appendChild($receb);
                    break;
                case "DEST":
                    $dest = $dom->createElement("dest");
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $dest->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[2]);
                        $dest->appendChild($CPF);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $dest->appendChild($IE);
                    $xNome = $dom->createElement("xNome", $dados[4]);
                    $dest->appendChild($xNome);
                    if ($dados[5] > 0) {
                        $ISUF = $dom->createElement("ISUF", $dados[5]);
                        $dest->appendChild($ISUF);
                    }
                    if (!empty($dados[16])) {
                        $fone = $dom->createElement("fone", $dados[16]);
                        $dest->appendChild($fone);
                    }
                    $enderDest = $dom->createElement("enderDest");
                    $xLgr = $dom->createElement("xLgr", $dados[6]);
                    $enderDest->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[7]);
                    $enderDest->appendChild($nro);
                    if (!empty($dados[8])) {
                        $xCpl = $dom->createElement("xCpl", $dados[8]);
                        $enderDest->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[9]);
                    $enderDest->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[10]);
                    $enderDest->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[11]);
                    $enderDest->appendChild($xMun);
                    if (!empty($dados[12])) {
                        $CEP = $dom->createElement("CEP", $dados[12]);
                        $enderDest->appendChild($CEP);
                    }
                    $UF = $dom->createElement("UF", $dados[13]);
                    $enderDest->appendChild($UF);
                    if (!empty($dados[14])) {
                        $cPais = $dom->createElement("cPais", $dados[14]);
                        $enderDest->appendChild($cPais);
                    }
                    if (!empty($dados[15])) {
                        $xPais = $dom->createElement("xPais", $dados[15]);
                        $enderDest->appendChild($xPais);
                    }
                    $dest->appendChild($enderDest);
                    if (!empty($dados[17])) {
                        $email = $dom->createElement("email", $dados[17]);
                        $dest->appendChild($email);
                    }
                    $infCte->appendChild($dest);
                    break;
                case "LOCENT":
                    $locEnt = $dom->createElement("locEnt");
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $locEnt->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[2]);
                        $locEnt->appendChild($CPF);
                    }
                    $xNome = $dom->createElement("xNome", $dados[3]);
                    $locEnt->appendChild($xNome);
                    $xLgr = $dom->createElement("xLgr", $dados[4]);
                    $locEnt->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[5]);
                    $locEnt->appendChild($nro);
                    if (!empty($dados[6])) {
                        $xCpl = $dom->createElement("xCpl", $dados[6]);
                        $locEnt->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[7]);
                    $locEnt->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[8]);
                    $locEnt->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[9]);
                    $locEnt->appendChild($xMun);
                    $UF = $dom->createElement("UF", $dados[10]);
                    $locEnt->appendChild($UF);
                    $dest->appendChild($locEnt);
                    break;
                case "VPREST":
                    $vPrest = $dom->createElement("vPrest");
                    $vTPrest = $dom->createElement("vTPrest", $dados[1]);
                    $vPrest->appendChild($vTPrest);
                    $vRec = $dom->createElement("vRec", $dados[2]);
                    $vPrest->appendChild($vRec);
                    $infCte->appendChild($vPrest);
                    break;
                case "COMP":
                    $Comp = $dom->createElement("Comp");
                    $xNome = $dom->createElement("xNome", $dados[1]);
                    $Comp->appendChild($xNome);
                    $vComp = $dom->createElement("vComp", $dados[2]);
                    $Comp->appendChild($vComp);
                    $vPrest->appendChild($Comp);
                    break;
                case "IMP":
                    $imp = $dom->createElement("imp");
                    $ICMS = $dom->createElement("ICMS");
                    $imp->appendChild($ICMS);
                    if (!empty($dados[1])) {
                        $infAdFisco = $dom->createElement("infAdFisco", $dados[1]);
                        $imp->appendChild($infAdFisco);
                    }
                    $infCte->appendChild($imp);
                    break;
                case "ICMS00":
                    $ICMS00 = $dom->createElement("ICMS00");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS00->appendChild($CST);
                    $vBC = $dom->createElement("vBC", $dados[2]);
                    $ICMS00->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[3]);
                    $ICMS00->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[4]);
                    $ICMS00->appendChild($vICMS);
                    $ICMS->appendChild($ICMS00);
                    break;
                case "ICMS20":
                    $ICMS20 = $dom->createElement("ICMS20");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS20->appendChild($CST);
                    $pRedBC = $dom->createElement("pRedBC", $dados[2]);
                    $ICMS20->appendChild($pRedBC);
                    $vBC = $dom->createElement("vBC", $dados[3]);
                    $ICMS20->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[4]);
                    $ICMS20->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[5]);
                    $ICMS20->appendChild($vICMS);
                    $ICMS->appendChild($ICMS20);
                    break;
                case "ICMS45":
                    $ICMS45 = $dom->createElement("ICMS45");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS45->appendChild($CST);
                    $ICMS->appendChild($ICMS45);
                    break;
                case "ICMS60":
                    $ICMS60 = $dom->createElement("ICMS60");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS60->appendChild($CST);
                    $vBCSTRet = $dom->createElement("vBCSTRet", $dados[2]);
                    $ICMS60->appendChild($vBCSTRet);
                    $vICMSSTRet = $dom->createElement("vICMSSTRet", $dados[3]);
                    $ICMS60->appendChild($vICMSSTRet);
                    $pICMSSTRet = $dom->createElement("pICMSSTRet", $dados[4]);
                    $ICMS60->appendChild($pICMSSTRet);
                    $vCred = $dom->createElement("vCred", $dados[5]);
                    $ICMS60->appendChild($vCred);
                    $ICMS->appendChild($ICMS60);
                    break;
                case "ICMS90":
                    $ICMS90 = $dom->createElement("ICMS90");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS90->appendChild($CST);
                    $pRedBC = $dom->createElement("pRedBC", $dados[2]);
                    $ICMS90->appendChild($pRedBC);
                    $vBC = $dom->createElement("vBC", $dados[3]);
                    $ICMS90->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[4]);
                    $ICMS90->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[5]);
                    $ICMS90->appendChild($vICMS);
                    $vCred = $dom->createElement("vCred", $dados[6]);
                    $ICMS90->appendChild($vCred);
                    $ICMS->appendChild($ICMS90);
                    break;
                case "ICMSSN":
                    $ICMSSN = $dom->createElement("ICMSSN");
                    $indSN = $dom->createElement("indSN", $dados[1]);
                    $ICMS->appendChild($indSN);
                    break;
                case "INFADFISCO":
                    $infAdFisco = $dom->createElement("infAdFisco", $dados[1]);
                    $imp->appendChild($infAdFisco);
                    break;
                case "INFCTENORM":
                    $infCTeNorm = $dom->createElement("infCTeNorm");
                    $infCte->appendChild($infCTeNorm);
                    break;
                case "INFCARGA":
                    $infCarga = $dom->createElement("infCarga");
                    $vCarga = $dom->createElement("vCarga", $dados[1]);
                    $infCarga->appendChild($vCarga);
                    $proPred = $dom->createElement("proPred", $dados[2]);
                    $infCarga->appendChild($proPred);
                    $xOutCat = $dom->createElement("xOutCat", $dados[3]);
                    $infCarga->appendChild($xOutCat);
                    $infCTeNorm->appendChild($infCarga);
                    break;
                case "INFQ":
                    $infQ = $dom->createElement("infQ");
                    $cUnid = $dom->createElement("cUnid", $dados[1]);
                    $infQ->appendChild($cUnid);
                    $tpMed = $dom->createElement("tpMed", $dados[2]);
                    $infQ->appendChild($tpMed);
                    $qCarga = $dom->createElement("qCarga", $dados[3]);
                    $infQ->appendChild($qCarga);
                    $infCarga->appendChild($infQ);
                    break;
                case "CONTQT":
                    $contQt = $dom->createElement("contQt");
                    $nCont = $dom->createElement("nCont", $dados[1]);
                    $contQt->appendChild($nCont);
                    if (!empty($dados[2])) {
                        $dPrev = $dom->createElement("dPrev", $dados[2]);
                        $contQt->appendChild($dPrev);
                    }
                    $infCTeNorm->appendChild($contQt);
                    break;
                case "LACCONTQT":
                    $lacContQt = $dom->createElement("lacContQt");
                    $nLacre = $dom->createElement("nLacre", $dados[1]);
                    $lacContQt->appendChild($nLacre);
                    $contQt->appendChild($lacContQt);
                    break;
                case "SEG":
                    $seg = $dom->createElement("seg");
                    $respSeg = $dom->createElement("respSeg", $dados[1]);
                    $seg->appendChild($respSeg);
                    if (!empty($dados[2])) {
                        $xSeg = $dom->createElement("xSeg", $dados[2]);
                        $seg->appendChild($xSeg);
                    }
                    if (!empty($dados[3])) {
                        $nApol = $dom->createElement("nApol", $dados[3]);
                        $seg->appendChild($nApol);
                    }
                    if (!empty($dados[4])) {
                        $nAver = $dom->createElement("nAver", $dados[4]);
                        $seg->appendChild($nAver);
                    }
                    if (!empty($dados[5])) {
                        $vCarga = $dom->createElement("vCarga", $dados[5]);
                        $seg->appendChild($vCarga);
                    }
                    $infCTeNorm->appendChild($seg);
                    break;
                case "INFMODAL":
                    $infModal = $dom->createElement("infModal");
                    $infModal->setAttribute("versaoModal", $dados[1]);
                    $infCTeNorm->appendChild($infModal);
                    break;
                case "RODO":
                    $rodo = $dom->createElement("rodo");
                    $rodo->setAttribute("xmlns", "http://www.portalfiscal.inf.br/cte");
                    $RNTRC = $dom->createElement("RNTRC", $dados[1]);
                    $rodo->appendChild($RNTRC);
                    $dPrev = $dom->createElement("dPrev", $dados[2]);
                    $rodo->appendChild($dPrev);
                    $lota = $dom->createElement("lota", $dados[3]);
                    $rodo->appendChild($lota);
                    $infModal->appendChild($rodo);
                    break;
                case "OCC":
                    $occ = $dom->createElement("occ");
                    if (!empty($dados[1])) {
                        $serie = $dom->createElement("serie", $dados[1]);
                        $occ->appendChild($serie);
                    }
                    $nOcc = $dom->createElement("nOcc", $dados[2]);
                    $occ->appendChild($nOcc);
                    $dEmi = $dom->createElement("dEmi", $dados[3]);
                    $occ->appendChild($dEmi);
                    $rodo->appendChild($occ);
                    break;
                case "EMIOCC":
                    $emiOcc = $dom->createElement("emiOcc");
                    $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                    $emiOcc->appendChild($CNPJ);
                    if (!empty($dados[2])) {
                        $cInt = $dom->createElement("cInt", $dados[2]);
                        $emiOcc->appendChild($cInt);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $emiOcc->appendChild($IE);
                    $UF = $dom->createElement("UF", $dados[4]);
                    $emiOcc->appendChild($UF);
                    if (!empty($dados[5])) {
                        $fone = $dom->createElement("fone", $dados[5]);
                        $emiOcc->appendChild($fone);
                    }
                    $occ->appendChild($emiOcc);
                    break;
                case "VEIC":
                    $veic = $dom->createElement("veic");
                    if (!empty($dados[1])) {
                        $cInt = $dom->createElement("cInt", $dados[1]);
                        $veic->appendChild($cInt);
                    }
                    $RENAVAM = $dom->createElement("RENAVAM", $dados[2]);
                    $veic->appendChild($RENAVAM);
                    $placa = $dom->createElement("placa", $dados[3]);
                    $veic->appendChild($placa);
                    $tara = $dom->createElement("tara", $dados[4]);
                    $veic->appendChild($tara);
                    $capKG = $dom->createElement("capKG", $dados[5]);
                    $veic->appendChild($capKG);
                    $capM3 = $dom->createElement("capM3", $dados[6]);
                    $veic->appendChild($capM3);
                    $tpProp = $dom->createElement("tpProp", $dados[7]);
                    $veic->appendChild($tpProp);
                    $tpVeic = $dom->createElement("tpVeic", $dados[8]);
                    $veic->appendChild($tpVeic);
                    $tpRod = $dom->createElement("tpRod", $dados[9]);
                    $veic->appendChild($tpRod);
                    $tpCar = $dom->createElement("tpCar", $dados[10]);
                    $veic->appendChild($tpCar);
                    $UF = $dom->createElement("UF", $dados[11]);
                    $veic->appendChild($UF);
                    $rodo->appendChild($veic);
                    break;
                case "PROP":
                    $prop = $dom->createElement("prop");
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $prop->appendChild($CNPJ);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[2]);
                        $prop->appendChild($CPF);
                    }
                    $RNTRC = $dom->createElement("RNTRC", $dados[3]);
                    $prop->appendChild($RNTRC);
                    $xNome = $dom->createElement("xNome", $dados[4]);
                    $prop->appendChild($xNome);
                    if (!empty($dados[5])) {
                        $IE = $dom->createElement("IE", $dados[5]);
                        $prop->appendChild($IE);
                    }
                    if (!empty($dados[6])) {
                        $UF = $dom->createElement("UF", $dados[6]);
                        $prop->appendChild($UF);
                    }
                    $tpProp = $dom->createElement("tpProp", $dados[7]);
                    $prop->appendChild($tpProp);
                    $veic->appendChild($prop);
                    break;
                case "MOTO":
                    $moto = $dom->createElement("moto");
                    $xNome = $dom->createElement("xNome", $dados[1]);
                    $moto->appendChild($xNome);
                    $CPF = $dom->createElement("CPF", $dados[2]);
                    $moto->appendChild($CPF);
                    $rodo->appendChild($moto);
                    break;
                case "FERROV":
                    $ferrov = $dom->createElement("ferrov");
                    $tpTraf = $dom->createElement("tpTraf", $dados[1]);
                    $ferrov->appendChild($tpTraf);
                    $fluxo = $dom->createElement("fluxo", $dados[2]);
                    $ferrov->appendChild($fluxo);
                    $idTrem = $dom->createElement("idTrem", $dados[3]);
                    $ferrov->appendChild($idTrem);
                    $vFrete = $dom->createElement("vFrete", $dados[4]);
                    $ferrov->appendChild($vFrete);
                    $infModal->appendChild($ferrov);
                    break;
                case "DETVAG":
                    $detVag = $dom->createElement("detVag");
                    $nVag = $dom->createElement("nVag", $dados[1]);
                    $detVag->appendChild($nVag);
                    if (!empty($dados[2])) {
                        $cap = $dom->createElement("cap", $dados[2]);
                        $detVag->appendChild($cap);
                    }
                    if (!empty($dados[3])) {
                        $tpVag = $dom->createElement("tpVag", $dados[3]);
                        $detVag->appendChild($tpVag);
                    }
                    $pesoR = $dom->createElement("pesoR", $dados[4]);
                    $detVag->appendChild($pesoR);
                    $pesoBC = $dom->createElement("pesoBC", $dados[5]);
                    $detVag->appendChild($pesoBC);
                    $ferrov->appendChild($detVag);
                    break;
                case "CONTVAG":
                    $contVag = $dom->createElement("contVag");
                    $nCont = $dom->createElement("nCont", $dados[1]);
                    $contVag->appendChild($nCont);
                    if (!empty($dados[2])) {
                        $dPrev = $dom->createElement("dPrev", $dados[2]);
                        $contVag->appendChild($dPrev);
                    }
                    $detVag->appendChild($contVag);
                    break;
                case "PERI":
                    $peri = $dom->createElement("peri");
                    $nONU = $dom->createElement("nONU", $dados[1]);
                    $peri->appendChild($nONU);
                    $xNomeAE = $dom->createElement("xNomeAE", $dados[2]);
                    $peri->appendChild($xNomeAE);
                    $xClaRisco = $dom->createElement("xClaRisco", $dados[3]);
                    $peri->appendChild($xClaRisco);
                    if (!empty($dados[4])) {
                        $grEmb = $dom->createElement("grEmb", $dados[4]);
                        $peri->appendChild($grEmb);
                    }
                    $qTotProd = $dom->createElement("qTotProd", $dados[5]);
                    $peri->appendChild($qTotProd);
                    if (!empty($dados[6])) {
                        $qVolTipo = $dom->createElement("qVolTipo", $dados[6]);
                        $peri->appendChild($qVolTipo);
                    }
                    if (!empty($dados[7])) {
                        $pontoFulgor = $dom->createElement("pontoFulgor", $dados[7]);
                        $peri->appendChild($pontoFulgor);
                    }
                    $infCTeNorm->appendChild($peri);
                    break;
                case "COBR":
                    $cobr = $dom->createElement("cobr");
                    $infCTeNorm->appendChild($cobr);
                    break;
                case "FAT":
                    $fat = $dom->createElement("fat");
                    $nFat = $dom->createElement("nFat", $dados[1]);
                    $fat->appendChild($nFat);
                    $vOrig = $dom->createElement("vOrig", $dados[2]);
                    $fat->appendChild($vOrig);
                    if (!empty($dados[3])) {
                        $vDesc = $dom->createElement("vDesc", $dados[3]);
                        $fat->appendChild($vDesc);
                    }
                    $vLiq = $dom->createElement("vLiq", $dados[4]);
                    $fat->appendChild($vLiq);
                    $cobr->appendChild($fat);
                    break;
                case "DUP":
                    $dup = $dom->createElement("dup");
                    $nDup = $dom->createElement("nDup", $dados[1]);
                    $dup->appendChild($nDup);
                    $dVenc = $dom->createElement("dVenc", $dados[2]);
                    $dup->appendChild($dVenc);
                    $vDup = $dom->createElement("vDup", $dados[3]);
                    $dup->appendChild($vDup);
                    $cobr->appendChild($dup);
                    break;
                case "INFCTECOMP":
                    $infCteComp = $dom->createElement("infCteComp");
                    $chave = $dom->createElement("chave", $dados[1]);
                    $infCteComp->appendChild($chave);
                    $infCte->appendChild($infCteComp);
                    break;
                case "VPRESCOMP":
                    $vPresComp = $dom->createElement("vPresComp");
                    $vTPrest = $dom->createElement("vTPrest", $dados[1]);
                    $vPresComp->appendChild($vTPrest);
                    $infCteComp->appendChild($vPresComp);
                    break;
                case "COMPCOMP":
                    $compComp = $dom->createElement("compComp");
                    $xNome = $dom->createElement("xNome", $dados[1]);
                    $compComp->appendChild($xNome);
                    $vComp = $dom->createElement("vComp", $dados[2]);
                    $compComp->appendChild($vComp);
                    $vPresComp->appendChild($compComp);
                    break;
                case "IMPCOMP":
                    $impComp = $dom->createElement("impComp");
                    $ICMSComp = $dom->createElement("ICMSComp");
                    $impComp->appendChild($ICMSComp);
                    $infCteComp->appendChild($impComp);
                    break;
                case "ICMSCOMP00":
                    $ICMS00 = $dom->createElement("ICMS00");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS00->appendChild($CST);
                    $vBC = $dom->createElement("vBC", $dados[2]);
                    $ICMS00->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[3]);
                    $ICMS00->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[4]);
                    $ICMS00->appendChild($vICMS);
                    $ICMSComp->appendChild($ICMS00);
                    break;
                case "ICMSCOMP20":
                    $ICMS20 = $dom->createElement("ICMS20");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS20->appendChild($CST);
                    $pRedBC = $dom->createElement("pRedBC", $dados[2]);
                    $ICMS20->appendChild($pRedBC);
                    $vBC = $dom->createElement("vBC", $dados[3]);
                    $ICMS20->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[4]);
                    $ICMS20->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[5]);
                    $ICMS20->appendChild($vICMS);
                    $ICMSComp->appendChild($ICMS20);
                    break;
                case "ICMSCOMP45":
                    $ICMS45 = $dom->createElement("ICMS45");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS45->appendChild($CST);
                    $ICMSComp->appendChild($ICMS45);
                    break;
                case "ICMSCOMP60":
                    $ICMS60 = $dom->createElement("ICMS60");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS60->appendChild($CST);
                    $vBCSTRet = $dom->createElement("vBCSTRet", $dados[2]);
                    $ICMS60->appendChild($vBCSTRet);
                    $vICMSSTRet = $dom->createElement("vICMSSTRet", $dados[3]);
                    $ICMS60->appendChild($vICMSSTRet);
                    $pICMSSTRet = $dom->createElement("pICMSSTRet", $dados[4]);
                    $ICMS60->appendChild($pICMSSTRet);
                    $vCred = $dom->createElement("vCred", $dados[5]);
                    $ICMS60->appendChild($vCred);
                    $ICMSComp->appendChild($ICMS60);
                    break;
                case "ICMSCOMP90":
                    $ICMS90 = $dom->createElement("ICMS90");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $ICMS90->appendChild($CST);
                    $pRedBC = $dom->createElement("pRedBC", $dados[2]);
                    $ICMS90->appendChild($pRedBC);
                    $vBC = $dom->createElement("vBC", $dados[3]);
                    $ICMS90->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[4]);
                    $ICMS90->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[5]);
                    $ICMS90->appendChild($vICMS);
                    $vCred = $dom->createElement("vCred", $dados[6]);
                    $ICMS90->appendChild($vCred);
                    $ICMSComp->appendChild($ICMS90);
                    break;
            } //end switch
        } //end for

        $arquivos_xml = array();
        foreach ($ctes as $cte) {
            unset($dom, $CTe, $infCte);
            $dom = $cte['dom'];
            $CTe = $cte['CTe'];
            $infCte = $cte['infCte'];
            $refCTE = $cte['refCTE'];
            $this->chave = $cte['chave'];
            $this->tpAmb = $cte['tpAmb'];
            $this->xml = '';
            //salva o xml na variável se o txt não estiver em branco
            if (!empty($infCte)) {
                $CTe->appendChild($infCte);
                $dom->appendChild($CTe);
                $this->zMontaChaveXML($dom);
                $xml = $dom->saveXML();
                $this->xml = $dom->saveXML();
                $xml = str_replace(
                    '<?xml version="1.0" encoding="UTF-8  standalone="no"?>',
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    $xml
                );
                //remove linefeed, carriage return, tabs e multiplos espaços
                $xml = preg_replace('/\s\s+/', ' ', $xml);
                $xml = str_replace("> <", "><", $xml);
                $arquivos_xml[] = $xml;
                unset($xml);
            }
        }
        return ($arquivos_xml);
    } //end function


    /**
     * zLimpaString
     * Remove todos dos caracteres especiais do texto e os acentos
     * preservando apenas letras de A-Z numeros de 0-9 e os caracteres @ , - ; : / _
     * @param   string $texto string a ser limpa 
     * @return  string Texto sem caractere especiais
     */
    private function zLimpaString($texto)
    {
        $aFind = array('&', 'á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ',
            'ú', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ',
            'Ú', 'Ü', 'Ç');
        $aSubs = array('e', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o',
            'u', 'u', 'c', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O',
            'U', 'U', 'C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/_]/", "", $novoTexto);
        return $novoTexto;
    } //fim zLimpaString

    /**
     * zCalculaDV
     * Função para o calculo o digito verificador da chave da CTe
     * @param string $chave43
     * @return string 
     */
    private function zCalculaDV($chave43)
    {
        $multiplicadores = array(2, 3, 4, 5, 6, 7, 8, 9);
        $i = 42;
        $soma_ponderada = 0;
        while ($i >= 0) {
            for ($m = 0; $m < count($multiplicadores) && $i >= 0; $m++) {
                $soma_ponderada += $chave43[$i] * $multiplicadores[$m];
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
    } //fim zCalculaDV

    /**
     * zMontaChaveXML
     * 
     * @param object $dom 
     */
    private function zMontaChaveXML($dom)
    {
        $ide = $dom->getElementsByTagName("ide")->item(0);
        $emit = $dom->getElementsByTagName("emit")->item(0);
        $cUF = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $dhEmi = $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue;
        $CNPJ = $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $nCT = $ide->getElementsByTagName('nCT')->item(0)->nodeValue;
        $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
        $cCT = $ide->getElementsByTagName('cCT')->item(0)->nodeValue;
        if (strlen($cCT) != 8) {
            $cCT = $ide->getElementsByTagName('cCT')->item(0)->nodeValue = rand(10000001, 99999999);
        }
        $tempData = $dt = explode("-", $dhEmi);
        $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d"; //%01d";
        $tempChave = sprintf($forma, $cUF, $tempData[0] - 2000, $tempData[1], $CNPJ, $mod, $serie, $nCT, $tpEmis, $cCT);
        $cDV = $ide->getElementsByTagName('cDV')->item(0)->nodeValue = $this->zCalculaDV($tempChave);
        $chave = $tempChave .= $cDV;
        $infCte = $dom->getElementsByTagName("infCte")->item(0);
        $infCte->setAttribute("Id", "CTe" . $chave);
    } //fim __calculaChave
}
