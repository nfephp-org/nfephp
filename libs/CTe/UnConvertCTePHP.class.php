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
 * Esta classe atende aos critérios estabelecidos no
 * Manual de Importação/Exportação TXT Notas Fiscais eletrônicas versão 2.0.0
 *
 * @package     NFePHP
 * @name        UnConvertCTePHP
 * @version     1.0.0
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * @author      Joao Eduardo Silva Correa <jcorrea at sucden dot com dot br>
 *
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              Daniel Batista Lemes <dlemes at gmail dot com>
 *              Lucimar A. Magalhaes <lucimar.magalhaes at assistsolucoes dot com dot br>
 *              Roberto Spadim <rspadim at gmail dot com>
 * 
 * TODO : terminar e ajustar para versão 2.00
 * 
 */

//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(dirname(__file__))).DIRECTORY_SEPARATOR);
}
require_once (PATH_ROOT.'libs/Common/CommonNFePHP.class.php');

class UnConvertCTePHP
{

    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg='';

    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus=false;
 

    /**
     * ctexml2txt
     * Método de conversão das CTe de xml para txt, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Conhecimento de Transporte Eletrônico versão 1.0.4 (25/05/2012)
     *
     * @name ctexml2txt
     * @param string $arq Path do arquivo xml
     * @return string txt
     */
    public function ctexml2txt($arq)
    {
        //variavel que irá conter o resultado
        $txt = "";
        //verificar se a string passada como parametro é um arquivo
        if (is_file($arq)) {
            $matriz[] = $arq;
        } else {
            if (is_array($arq)) {
                $matriz = $arq;
            } else {
                return false;
            }
        }
        $nctematriz = count($matriz);
        $txt = "REGISTROSCTE|$nctematriz\r\n";
        //para cada cte passada na matriz
        for ($x = 0; $x < $nctematriz; $x++) {
            //carregar o conteúdo do arquivo xml em uma string
            $xml = file_get_contents($matriz[$x]);
            //instanciar o ojeto DOM
            $dom = new DOMDocument();
            //carregar o xml no objeto DOM
            $dom->loadXML($xml);
            //carregar os grupos de dados possíveis da CTe
            $cteProc = $dom->getElementsByTagName("cteProc")->item(0);
            $infCte = $dom->getElementsByTagName("infCte")->item(0);
            $ide = $dom->getElementsByTagName("ide")->item(0);
            $emit = $dom->getElementsByTagName("emit")->item(0);
            $enderEmit = $dom->getElementsByTagName("enderEmit")->item(0);
            $rem = $dom->getElementsByTagName("rem")->item(0);
            $dest = $dom->getElementsByTagName("dest")->item(0);
            $locEnt = $dom->getElementsByTagName("locEnt")->item(0);
            $exped = $dom->getElementsByTagName("exped")->item(0);
            $receb = $dom->getElementsByTagName("receb")->item(0);
            $infCarga = $dom->getElementsByTagName("infCarga")->item(0);
            $infQ = $dom->getElementsByTagName("infQ");
            $seg = $dom->getElementsByTagName("seg")->item(0);
            $rodo = $dom->getElementsByTagName("rodo")->item(0);
            $occ = $dom->getElementsByTagName("occ");
            $valePed = $dom->getElementsByTagName("valePed");
            $moto = $dom->getElementsByTagName("moto");
            $veic = $dom->getElementsByTagName("veic");
            $prop = $dom->getElementsByTagName("prop")->item(0);
            $lacRodo = $dom->getElementsByTagName("lacRodo");
            $ferrov = $dom->getElementsByTagName("ferrov")->item(0);
            $detVag = $dom->getElementsByTagName("detVag");
            /*adicionar outros modais*/
            $vPrest = $dom->getElementsByTagName("vPrest")->item(0);
            $Comp = $dom->getElementsByTagName("Comp");
            $infNF = $dom->getElementsByTagName("infNF");
            $infNFe = $dom->getElementsByTagName("infNFe");
            $compl = $dom->getElementsByTagName("compl")->item(0);
            $ObsCont = $dom->getElementsByTagName("ObsCont");
            $Entrega = $dom->getElementsByTagName("Entrega")->item(0);
            $semData = $dom->getElementsByTagName("semData")->item(0);
            $comData = $dom->getElementsByTagName("comData")->item(0);
            $noPeriodo = $dom->getElementsByTagName("noPeriodo")->item(0);
            $semHora = $dom->getElementsByTagName("semHora")->item(0);
            $comHora = $dom->getElementsByTagName("comHora")->item(0);
            $noInter = $dom->getElementsByTagName("noInter")->item(0);
            $ICMS = $dom->getElementsByTagName("ICMS")->item(0);
            $ICMSSN = $dom->getElementsByTagName("ICMSSN")->item(0);
            $imp = $dom->getElementsByTagName("imp")->item(0);
            $toma4 = $dom->getElementsByTagName("toma4")->item(0);
            $toma03 = $dom->getElementsByTagName("toma03")->item(0);
            $infOutros = $dom->getElementsByTagName("infOutros");
            $peri = $dom->getElementsByTagName("peri");
            $cobr = $dom->getElementsByTagName("cobr")->item(0);
            $dup = $dom->getElementsByTagName("dup");
            $infCteComp = $dom->getElementsByTagName("infCteComp")->item(0);
            $compComp = $dom->getElementsByTagName("compComp");
            $ICMSComp = $dom->getElementsByTagName("ICMSComp")->item(0);
            $id = $infCte->getAttribute("Id") ? $infCte->getAttribute("Id") : '';
            $versao = $infCte->getAttribute("versao");
            $txt .= "CTE|$versao|$id\r\n";
            $cUF = $this->__simpleGetValue($ide, "cUF");
            $cCT = $this->__simpleGetValue($ide, "cCT");
            $CFOP = $this->__simpleGetValue($ide, "CFOP");
            $natOp = $this->__simpleGetValue($ide, "natOp");
            $forPag = $this->__simpleGetValue($ide, "forPag");
            $mod = $this->__simpleGetValue($ide, "mod");
            $serie = $this->__simpleGetValue($ide, "serie");
            $nCT = $this->__simpleGetValue($ide, "nCT");
            $dhEmi = $this->__simpleGetValue($ide, "dhEmi");
            $tpImp = $this->__simpleGetValue($ide, "tpImp");
            $tpEmis = $this->__simpleGetValue($ide, "tpEmis");
            $cDV = $this->__simpleGetValue($ide, "cDV");
            $tpAmb = $this->__simpleGetValue($ide, "tpAmb");
            $tpCTe = $this->__simpleGetValue($ide, "tpCTe");
            $procEmi = $this->__simpleGetValue($ide, "procEmi");
            $verProc = $this->__simpleGetValue($ide, "verProc");
            $refCTE = $this->__simpleGetValue($ide, 'refCTE');
            $cMunEnv = $this->__simpleGetValue($ide, "cMunEnv");
            $xMunEnv = $this->__simpleGetValue($ide, "xMunEnv");
            $UFEnv = $this->__simpleGetValue($ide, "UFEnv");
            $modal = $this->__simpleGetValue($ide, "modal");
            $tpServ = $this->__simpleGetValue($ide, "tpServ");
            $cMunIni = $this->__simpleGetValue($ide, "cMunIni");
            $xMunIni = $this->__simpleGetValue($ide, "xMunIni");
            $UFIni = $this->__simpleGetValue($ide, "UFIni");
            $cMunFim = $this->__simpleGetValue($ide, "cMunFim");
            $xMunFim = $this->__simpleGetValue($ide, "xMunFim");
            $UFFim = $this->__simpleGetValue($ide, "UFFim");
            $retira = $this->__simpleGetValue($ide, "retira");
            $xDetRetira = $this->__simpleGetValue($ide, "xDetRetira");
            $txt .= "IDE|$cUF|$cCT|$CFOP|$natOp|$forPag|$mod|$serie|$nCT|$dhEmi|$tpImp|$tpEmis|$cDV|$tpAmb|$tpCTe|$procEmi|$verProc|$refCTE|$cMunEnv|$xMunEnv|$UFEnv|$modal|$tpServ|$cMunIni|$xMunIni|$UFIni|$cMunFim|$xMunFim|$UFFim|$retira|$xDetRetira\r\n";

            if (!empty($toma03)) {
                $toma = $toma03->nodeValue;
                $txt .= "TOMA03|$toma\r\n";
            } else {
                $toma = 4;
                $CNPJ = $this->__simpleGetValue($toma4, "CNPJ");
                $CPF = $this->__simpleGetValue($toma4, "CPF");
                $IE = $this->__simpleGetValue($toma4, "IE");
                $xNome = $this->__simpleGetValue($toma4, "xNome");
                $xFant = $this->__simpleGetValue($toma4, "xFant");
                $xLgr = $this->__simpleGetValue($toma4, "xLgr");
                $nro = $this->__simpleGetValue($toma4, "nro");
                $xCpl = $this->__simpleGetValue($toma4, "xCpl");
                $xBairro = $this->__simpleGetValue($toma4, "xBairro");
                $cMun = $this->__simpleGetValue($toma4, "cMun");
                $xMun = $this->__simpleGetValue($toma4, "xMun");
                $CEP = $this->__simpleGetValue($toma4, "CEP");
                $UF = $this->__simpleGetValue($toma4, "UF");
                $cPais = $this->__simpleGetValue($toma4, "cPais");
                $xPais = $this->__simpleGetValue($toma4, "xPais");
                $email = $this->__simpleGetValue($toma4, "email");
                $dhCont = $this->__simpleGetValue($toma4, "dhCont");
                $xJust = $this->__simpleGetValue($toma4, "xJust");
                $txt .= "TOMA4|$toma|$CNPJ|$CPF|$IE|$xNome|$xFant|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$CEP|$UF|$cPais|$xPais|$email|$dhCont|$xJust\r\n";
            }

            //Dados complementares do CTe para fins operacionais ou comerciais
            if (!empty($compl)) {          
                $xCaracAd = $this->__simpleGetValue($compl, "xCaracAd");
                $xCaracSer = $this->__simpleGetValue($compl, "xCaracSer");
                $xEmi = $this->__simpleGetValue($compl, "xEmi");
                $origCalc = $this->__simpleGetValue($compl, "origCalc");
                $destCalc = $this->__simpleGetValue($compl, "destCalc");
                $xObs = $this->__simpleGetValue($compl, "xObs");
                $txt .= "COMPL|$xCaracAd|$xCaracSer|$xEmi|$origCalc|$destCalc|$xObs|\r\n";
                if (!empty($Entrega)) {
                    $txt .= "ENTREGA|\r\n";
                    $tpPer = $this->__simpleGetValue($Entrega, "tpPer");
                    $dProg = $this->__simpleGetValue($Entrega, "dProg");
                    $dIni = $this->__simpleGetValue($Entrega, "dIni");
                    $dFim = $this->__simpleGetValue($Entrega, "dFim");
                    $tpHor = $this->__simpleGetValue($Entrega, "tpHor");
                    $hProg = $this->__simpleGetValue($Entrega, "hProg");
                    $dIni = $this->__simpleGetValue($Entrega, "hIni");
                    $hFim = $this->__simpleGetValue($Entrega, "hFim");
                    if (!empty($comData)) {
                        $txt .= "COMDATA|$tpPer|$dProg\r\n";
                    }    
                    if (!empty($semData)) {
                        $txt .= "SEMDATA|$tpPer\r\n";
                    }    
                    if (!empty($noPeriodo)) {
                        $txt .= "NOPERIODO|$tpPer|$dIni|$dFim\r\n";
                    }    
                    if (!empty($semHora)) {
                        $txt .= "SEMHORA|$tpHor\r\n";
                    }    
                    if (!empty($comHora)) {
                        $txt .= "COMHORA|$tpHor|$hProg\r\n";
                    }    
                    if (!empty($noInter)) {
                        $txt .= "NOINTER|$tpHor|$hIni|$hFim\r\n";
                    }    
                }
                foreach ($ObsCont as $k => $d) {
                    $xCampo = $ObsCont->item($k)->getAttribute("xCampo");
                    $xTexto = $this->__simpleGetValue($ObsCont->item($k), 'xTexto');
                    $txt .= "OBSCONT|$xCampo|$xTexto\r\n";
                }
            }

            //To-do
            //$txt .= "FLUXO||||\r\n";

            $CNPJ = $this->__simpleGetValue($emit, "CNPJ");
            $IE = $this->__simpleGetValue($emit, "IE");
            $xNome = $this->__simpleGetValue($emit, "xNome");
            $xFant = $this->__simpleGetValue($emit, "xFant");
            $xLgr = $this->__simpleGetValue($emit, "xLgr");
            $nro = $this->__simpleGetValue($emit, "nro");
            $xCpl = $this->__simpleGetValue($emit, "xCpl");
            $xBairro = $this->__simpleGetValue($emit, "xBairro");
            $cMun = $this->__simpleGetValue($emit, "cMun");
            $xMun = $this->__simpleGetValue($emit, "xMun");
            $CEP = $this->__simpleGetValue($emit, "CEP");
            $UF = $this->__simpleGetValue($emit, "UF");
            $fone = $this->__simpleGetValue($emit, "fone");
            $txt .= "EMIT|$CNPJ|$IE|$xNome|$xFant|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$CEP|$UF|$fone\r\n";

            if (!empty($rem)) {
                $CNPJ = $this->__simpleGetValue($rem, "CNPJ");
                $CPF = $this->__simpleGetValue($rem, "CPF");
                $IE = $this->__simpleGetValue($rem, "IE");
                $xNome = $this->__simpleGetValue($rem, "xNome");
                $fone = $this->__simpleGetValue($rem, "fone");
                $xFant = $this->__simpleGetValue($rem, "xFant");
                $xLgr = $this->__simpleGetValue($rem, "xLgr");
                $nro = $this->__simpleGetValue($rem, "nro");
                $xCpl = $this->__simpleGetValue($rem, "xCpl");
                $xBairro = $this->__simpleGetValue($rem, "xBairro");
                $cMun = $this->__simpleGetValue($rem, "cMun");
                $xMun = $this->__simpleGetValue($rem, "xMun");
                $CEP = $this->__simpleGetValue($rem, "CEP");
                $UF = $this->__simpleGetValue($rem, "UF");
                $cPais = $this->__simpleGetValue($rem, "cPais");
                $xPais = $this->__simpleGetValue($rem, "xPais");
                $email = $this->__simpleGetValue($rem, "email");
                $txt .= "REM|$CNPJ|$CPF|$IE|$xNome|$xFant|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$CEP|$UF|$cPais|$xPais|$fone|$email\r\n";
            }

            //NFs
            if (!empty($infNF)) {
                foreach ($infNF as $k => $d) {
                    $nRoma = $this->__simpleGetValue($infNF->item($k), 'nRoma');
                    $nPed = $this->__simpleGetValue($infNF->item($k), 'nPed');
                    $mod = $this->__simpleGetValue($infNF->item($k), 'mod');
                    $serie = $this->__simpleGetValue($infNF->item($k), 'serie');
                    $nDoc = $this->__simpleGetValue($infNF->item($k), 'nDoc');
                    $dEmi = $this->__simpleGetValue($infNF->item($k), 'dEmi');
                    $vBC = $this->__simpleGetValue($infNF->item($k), 'vBC');
                    $vICMS = $this->__simpleGetValue($infNF->item($k), 'vICMS');
                    $vBCST = $this->__simpleGetValue($infNF->item($k), 'vBCST');
                    $vST = $this->__simpleGetValue($infNF->item($k), 'vST');
                    $vProd = $this->__simpleGetValue($infNF->item($k), 'vProd');
                    $vNF = $this->__simpleGetValue($infNF->item($k), 'vNF');
                    $nCFOP = $this->__simpleGetValue($infNF->item($k), 'nCFOP');
                    $nPeso = $this->__simpleGetValue($infNF->item($k), 'nPeso');
                    $PIN = $this->__simpleGetValue($infNF->item($k), 'PIN');
                    $txt .= "INFNF|$nRoma|$nPed|$mod|$serie|$nDoc|$dEmi|$vBC|$vICMS|$vBCST|$vST|$vProd|$vNF|$nCFOP|$nPeso|$PIN\r\n";
                    //LOCRET - todo
                }
            }

            //NFEs
            if (!empty($infNFe)) {
                foreach ($infNFe as $k => $d) {
                    $chaveNFe = $this->__simpleGetValue($infNFe->item($k), 'chave');
                    $PIN = $this->__simpleGetValue($infNFe->item($k), 'PIN');
                    $txt .= "INFNFE|$chaveNFe|$PIN\r\n";
                }
            }

            //infOutros
            if (!empty($infOutros)) {
                foreach ($infOutros as $k => $d) {
                    $tpDoc = $this->__simpleGetValue($infOutros->item($k), "tpDoc");
                    $descOutros = $this->__simpleGetValue($infOutros->item($k), "descOutros");
                    $nDoc = $this->__simpleGetValue($infOutros->item($k), "nDoc");
                    $dEmi = $this->__simpleGetValue($infOutros->item($k), "dEmi");
                    $vDocFisc = $this->__simpleGetValue($infOutros->item($k), "vDocFisc");
                    $txt .= "INFOUTROS|$tpDoc|$descOutros|$nDoc|$dEmi|$vDocFisc\r\n";
                }
            }

            //Expedidor
            if (!empty($exped)) {
                $CNPJ = $this->__simpleGetValue($exped, "CNPJ");
                $CPF = $this->__simpleGetValue($exped, "CPF");
                $IE = $this->__simpleGetValue($exped, "IE");
                $xNome = $this->__simpleGetValue($exped, "xNome");
                $fone = $this->__simpleGetValue($exped, "fone");
                $xLgr = $this->__simpleGetValue($exped, "xLgr");
                $nro = $this->__simpleGetValue($exped, "nro");
                $xCpl = $this->__simpleGetValue($exped, "xCpl");
                $xBairro = $this->__simpleGetValue($exped, "xBairro");
                $cMun = $this->__simpleGetValue($exped, "cMun");
                $xMun = $this->__simpleGetValue($exped, "xMun");
                $CEP = $this->__simpleGetValue($exped, "CEP");
                $UF = $this->__simpleGetValue($exped, "UF");
                $cPais = $this->__simpleGetValue($exped, "cPais");
                $xPais = $this->__simpleGetValue($exped, "xPais");
                $email = $this->__simpleGetValue($exped, "email");
                $txt .= "EXPED|$CNPJ|$CPF|$IE|$xNome|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$CEP|$UF|$cPais|$xPais|$fone|$email\r\n";
            }

            //RECEBEDOR
            if (!empty($receb)) {
                $CNPJ = $this->__simpleGetValue($receb, "CNPJ");
                $CPF = $this->__simpleGetValue($receb, "CPF");
                $IE = $this->__simpleGetValue($receb, "IE");
                $xNome = $this->__simpleGetValue($receb, "xNome");
                $fone = $this->__simpleGetValue($receb, "fone");
                $xLgr = $this->__simpleGetValue($receb, "xLgr");
                $nro = $this->__simpleGetValue($receb, "nro");
                $xCpl = $this->__simpleGetValue($receb, "xCpl");
                $xBairro = $this->__simpleGetValue($receb, "xBairro");
                $cMun = $this->__simpleGetValue($receb, "cMun");
                $xMun = $this->__simpleGetValue($receb, "xMun");
                $CEP = $this->__simpleGetValue($receb, "CEP");
                $UF = $this->__simpleGetValue($receb, "UF");
                $cPais = $this->__simpleGetValue($receb, "cPais");
                $xPais = $this->__simpleGetValue($receb, "xPais");
                $email = $this->__simpleGetValue($receb, "email");
                $txt .= "REC|$CNPJ|$CPF|$IE|$xNome|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$CEP|$UF|$cPais|$xPais|$fone|$email\r\n";
            }


            //DESTINATARIO
            if (!empty($dest)) {
                $CNPJ = $this->__simpleGetValue($dest, "CNPJ");
                $CPF = $this->__simpleGetValue($dest, "CPF");
                $IE = $this->__simpleGetValue($dest, "IE");
                $xNome = $this->__simpleGetValue($dest, "xNome");
                $fone = $this->__simpleGetValue($dest, "fone");
                $xLgr = $this->__simpleGetValue($dest, "xLgr");
                $ISUF = $this->__simpleGetValue($dest, "ISUF");
                $nro = $this->__simpleGetValue($dest, "nro");
                $xCpl = $this->__simpleGetValue($dest, "xCpl");
                $xBairro = $this->__simpleGetValue($dest, "xBairro");
                $cMun = $this->__simpleGetValue($dest, "cMun");
                $xMun = $this->__simpleGetValue($dest, "xMun");
                $CEP = $this->__simpleGetValue($dest, "CEP");
                $UF = $this->__simpleGetValue($dest, "UF");
                $cPais = $this->__simpleGetValue($dest, "cPais");
                $xPais = $this->__simpleGetValue($dest, "xPais");
                $email = $this->__simpleGetValue($dest, "email");
                $txt .= "DEST|$CNPJ|$CPF|$IE|$xNome|$ISUF|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$CEP|$UF|$cPais|$xPais|$fone|$email\r\n";
            }

            //Local de Entrega constante na Nota Fiscal
            if (!empty($locEnt)) {
                $CNPJ = $this->__simpleGetValue($locEnt, "CNPJ");
                $CPF = $this->__simpleGetValue($locEnt, "CPF");
                $xNome = $this->__simpleGetValue($locEnt, "xNome");
                $xLgr = $this->__simpleGetValue($locEnt, "xLgr");
                $nro = $this->__simpleGetValue($locEnt, "nro");
                $xCpl = $this->__simpleGetValue($locEnt, "xCpl");
                $xBairro = $this->__simpleGetValue($locEnt, "xBairro");
                $cMun = $this->__simpleGetValue($locEnt, "cMun");
                $xMun = $this->__simpleGetValue($locEnt, "xMun");
                $UF = $this->__simpleGetValue($locEnt, "UF");
                $txt .= "LOCENT|$CNPJ|$CPF|$xNome|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF\r\n";
            }

            //Valores da Prestação de Serviço
            $vTPrest = $this->__simpleGetValue($vPrest, "vTPrest");
            $vRec = $this->__simpleGetValue($vPrest, "vRec");
            $txt .= "VPREST|$vTPrest|$vRec\r\n";

            //Componentes do Valor da Prestação
            foreach ($Comp as $k => $d) {
                $xNome = $this->__simpleGetValue($Comp->item($k), "xNome");
                $vComp = $this->__simpleGetValue($Comp->item($k), "vComp");
                $txt .= "COMP|$xNome|$vComp\r\n";
            }

            //Informações relativas aos Impostos
            $txt .= "IMP||\r\n";
            $CST = $this->__simpleGetValue($ICMS, "CST");
            $vBC = $this->__simpleGetValue($ICMS, "vBC");
            $pICMS = $this->__simpleGetValue($ICMS, "pICMS");
            $vICMS = $this->__simpleGetValue($ICMS, "vICMS");
            //To-do...

            switch ($CST) {
                case '00':
                    $txt .= "ICMS00|$CST|$vBC|$pICMS|$vICMS\r\n";
                    break;
                case '20':
                    $txt .= "ICMS20|$CST|$pRedBC|$vBC|$pICMS|$vICMS\r\n";
                    break;
                case '40':
                case '41':
                case '51':
                    $txt .= "ICMS45|$CST\r\n";
                    break;
                case '60':
                    $txt .= "ICMS60|$CST|$vBCSTRet|$vICMSSTRet|$pICMSSTRet|$vCred\r\n";
                    break;
                case '90':
                    $txt .= "ICMS90|$CST|$pRedBC|$vBC|$pICMS|$vICMS|$vCred\r\n";
                    break;
            }

            //ICMS devido à UF de origem da prestação, quando diferente da UF do emitente
            if ($this->__simpleGetValue($ide, "UFIni") != $this->__simpleGetValue($emit, "UF")) {
                $txt .= "ICMSOutraUF|$CST|$pRedBCOutraUF|$vBCOutraUF|$pICMSOutraUF|$vICMSOutraUF\r\n";
            }


            if (!empty($ICMSSN)) {
                $indSN = $this->__simpleGetValue($ICMSSN, "indSN");
                $txt .= "ICMSSN|$indSN\r\n";
            }
            $infAdFisco = $this->__simpleGetValue($imp, "infAdFisco");

            if ($infAdFisco != '')
            {
                $txt .= "INFADFISCO|$infAdFisco\r\n";
            }

            //Grupo de informações do CT-e Normal e Substituto
            $txt .= "INFCTENORM|\r\n";

            //Informações da Carga do CT-e
            $vCarga = $this->__simpleGetValue($infCarga, "vCarga");
            $proPred = $this->__simpleGetValue($infCarga, "proPred");
            $xOutCat = $this->__simpleGetValue($infCarga, "xOutCat");

            $txt .= "INFCARGA|$vCarga|$proPred|$xOutCat\r\n";

            //Informações de quantidades da Carga do CT-e
            foreach ($infQ as $k => $d)
            {
                $cUnid = $this->__simpleGetValue($infQ->item($k), "cUnid");
                $tpMed = $this->__simpleGetValue($infQ->item($k), "tpMed");
                $qCarga = $this->__simpleGetValue($infQ->item($k), "qCarga");

                $txt .= "INFQ|$cUnid|$tpMed|$qCarga\r\n";
            }

            //Informações de Seguro da Carga
            if (!empty($seg)) {
                $respSeg = $this->__simpleGetValue($seg, "respSeg");
                $xSeg = $this->__simpleGetValue($seg, "xSeg");
                $nApol = $this->__simpleGetValue($seg, "nApol");
                $nAver = $this->__simpleGetValue($seg, "nAver");
                $vCarga = $this->__simpleGetValue($seg, "vCarga");
                $txt .= "SEG|$respSeg|$xSeg|$nApol|$nAver|$vCarga\r\n";
            }
            //INFMODAL|1.04|
            $txt .= "INFMODAL|1.04|\r\n";
            //Leiaute – Rodoviário
            if (!empty($rodo)) {
                $RNTRC = $this->__simpleGetValue($rodo, "RNTRC");
                $dPrev = $this->__simpleGetValue($rodo, "dPrev");
                $lota = $this->__simpleGetValue($rodo, "lota");
                $CIOT = $this->__simpleGetValue($rodo, "CIOT");
                $txt .= "RODO|$RNTRC|$dPrev|$lota|$CIOT\r\n";
                if (!empty($occ)) {
                    foreach ($occ as $k => $d) {
                        $serie = $this->__simpleGetValue($occ->item($k), "serie");
                        $nOcc = $this->__simpleGetValue($occ->item($k), "nOcc");
                        $dEmi = $this->__simpleGetValue($occ->item($k), "dEmi");
                        $txt .= "OCC|$serie|$nOcc|$dEmi\r\n";
                        $CNPJ = $this->__simpleGetValue($occ->item($k), "CNPJ");
                        $cInt = $this->__simpleGetValue($occ->item($k), "cInt");
                        $IE = $this->__simpleGetValue($occ->item($k), "IE");
                        $UF = $this->__simpleGetValue($occ->item($k), "UF");
                        $fone = $this->__simpleGetValue($occ->item($k), "fone");
                        $txt .= "EMIOCC|$CNPJ|$cInt|$IE|$UF|$fone\r\n";
                    }
                }
                if (!empty($valePed)) {
                    foreach ($valePed as $k => $d) {
                        $CNPJForm = $this->__simpleGetValue($valePed->item($k), "CNPJForm");
                        $nCompra = $this->__simpleGetValue($valePed->item($k), "nCompra");
                        $CNPJPg = $this->__simpleGetValue($valePed->item($k), "CNPJPg");
                        $txt .= "VALEPED|$CNPJForm|$nCompra|$CNPJPg\r\n";
                    }
                }
                foreach ($veic as $k => $d) {
                    $cInt = $this->__simpleGetValue($veic->item($k), "cInt");
                    $RENAVAM = $this->__simpleGetValue($veic->item($k), "RENAVAM");
                    $placa = $this->__simpleGetValue($veic->item($k), "placa");
                    $tara = $this->__simpleGetValue($veic->item($k), "tara");
                    $capKG = $this->__simpleGetValue($veic->item($k), "capKG");
                    $capM3 = $this->__simpleGetValue($veic->item($k), "capM3");
                    $tpProp = $this->__simpleGetValue($veic->item($k), "tpProp");
                    $tpVeic = $this->__simpleGetValue($veic->item($k), "tpVeic");
                    $tpRod = $this->__simpleGetValue($veic->item($k), "tpRod");
                    $tpCar = $this->__simpleGetValue($veic->item($k), "tpCar");
                    $UF = $this->__simpleGetValue($veic->item($k), "UF");
                    $txt .= "VEIC|$cInt|$RENAVAM|$placa|$tara|$capKG|$capM3|$tpProp|$tpVeic|$tpRod|$tpCar|$UF\r\n";
                }
                if (!empty($prop)) {
                    $CPF = $this->__simpleGetValue($prop, "CPF");
                    $CNPJ = $this->__simpleGetValue($prop, "CNPJ");
                    $RNTRC = $this->__simpleGetValue($prop, "RNTRC");
                    $xNome = $this->__simpleGetValue($prop, "xNome");
                    $IE = $this->__simpleGetValue($prop, "IE");
                    $UF = $this->__simpleGetValue($prop, "UF");
                    $tpProp = $this->__simpleGetValue($prop, "tpProp");
                    $txt .= "PROP|$CPF|$CNPJ|$RNTRC|$xNome|$IE|$UF|$tpProp\r\n";
                }
                if (!empty($lacRodo)) {
                    foreach ($lacRodo as $k => $d) {
                        $nLacre = $this->__simpleGetValue($lacRodo->item($k), "nLacre");
                        $txt .= "NLACRE|$nLacre\r\n";
                    }
                }
                if (!empty($moto)) {
                    foreach ($moto as $k => $d) {
                        $xNome = $this->__simpleGetValue($moto->item($k), "xNome");
                        $CPF = $this->__simpleGetValue($moto->item($k), "CPF");
                        $txt .= "MOTO|$xNome|$CPF\r\n";
                    }
                }
            }
            //Leiaute – Ferroviário
            if (!empty($ferrov)) {
                $tpTraf = $this->__simpleGetValue($ferrov, "tpTraf");
                $fluxo = $this->__simpleGetValue($ferrov, "fluxo");
                $idTrem = $this->__simpleGetValue($ferrov, "idTrem");
                $vFrete = $this->__simpleGetValue($ferrov, "vFrete");
                $txt .= "FERROV|$tpTraf|$fluxo|$idTrem|$vFrete\r\n";
                foreach ($detVag as $k => $d) {
                    $nVag = $this->__simpleGetValue($detVag->item($k), "nVag");
                    $cap = $this->__simpleGetValue($detVag->item($k), "cap");
                    $tpVag = $this->__simpleGetValue($detVag->item($k), "tpVag");
                    $pesoR = $this->__simpleGetValue($detVag->item($k), "pesoR");
                    $pesoBC = $this->__simpleGetValue($detVag->item($k), "pesoBC");
                    $txt .= "DETVAG|$nVag|$cap|$tpVag|$pesoR|$pesoBC\r\n";
                    $nCont = $this->__simpleGetValue($detVag->item($k), "nCont");
                    $dPrev = $this->__simpleGetValue($detVag->item($k), "dPrev");
                    $txt .= "CONTVAG|$nCont|$dPrev\r\n";
                }
            }
            //Preenchido quando for transporte de produtos classificados pela ONU como perigosos.
            if (!empty($peri)) {
                foreach ($peri as $k => $d) {
                    $nONU = $this->__simpleGetValue($peri->item($k), "nONU");
                    $xNomeAE = $this->__simpleGetValue($peri->item($k), "xNomeAE");
                    $xClaRisco = $this->__simpleGetValue($peri->item($k), "xClaRisco");
                    $grEmb = $this->__simpleGetValue($peri->item($k), "grEmb");
                    $qTotProd = $this->__simpleGetValue($peri->item($k), "qTotProd");
                    $qVolTipo = $this->__simpleGetValue($peri->item($k), "qVolTipo");
                    $pontoFulgor = $this->__simpleGetValue($peri->item($k), "pontoFulgor");
                    $txt .= "PERI|$nONU|$xNomeAE|$xClaRisco|$grEmb|$qTotProd|$qVolTipo|$pontoFulgor\r\n";
                }
            }
            if (!empty($cobr)) {
                $txt .= "COBR|\r\n";
                $nFat = $this->__simpleGetValue($cobr, "nFat");
                $vOrig = $this->__simpleGetValue($cobr, "vOrig");
                $vDesc = $this->__simpleGetValue($cobr, "vDesc");
                $vLiq = $this->__simpleGetValue($cobr, "vLiq");
                $txt .= "FAT|$nFat|$vOrig|$vDesc|$vLiq\r\n";
                foreach ($dup as $k => $d) {
                    $nDup = $this->__simpleGetValue($dup->item($k), "nDup");
                    $dVenc = $this->__simpleGetValue($dup->item($k), "dVenc");
                    $vDup = $this->__simpleGetValue($dup->item($k), "vDup");
                    $txt .= "DUP|$nDup|$dVenc|$vDup\r\n";
                }
            }
            if (!empty($infCteComp)) {
                $chave = $this->__simpleGetValue($infCteComp, "chave");
                $txt .= "INFCTECOMP|$chave\r\n";
                $vTPrest = $this->__simpleGetValue($infCteComp, "vTPrest");
                $txt .= "VPRESCOMP|$vTPrest\r\n";
                if (!empty($compComp)) {
                    foreach ($compComp as $k => $d) {
                        $xNome = $this->__simpleGetValue($compComp->item($k), "xNome");
                        $vComp = $this->__simpleGetValue($compComp->item($k), "vComp");
                        $txt .= "COMPCOMP|$xNome|$vComp\r\n";
                    }
                }
                $txt .= "IMPCOMP|\r\n";
                $CST = $this->__simpleGetValue($ICMSComp, "CST");
                $vBC = $this->__simpleGetValue($ICMSComp, "vBC");
                $pICMS = $this->__simpleGetValue($ICMSComp, "pICMS");
                $vICMS = $this->__simpleGetValue($ICMSComp, "vICMS");
                //to-do....
                switch ($CST) {
                    case '00':
                        $txt .= "ICMSCOMP00|$CST|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '20':
                        $txt .= "ICMSCOMP20|$CST|$pRedBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '40':
                    case '41':
                    case '51':
                        $txt .= "ICMSCOMP45|$CST\r\n";
                        break;
                    case '60':
                        $txt .= "ICMSCOMP60|$CST|$vBCSTRet|$vICMSSTRet|$pICMSSTRet|$vCred\r\n";
                        break;
                    case '90':
                        $txt .= "ICMSCOMP90|$CST|$pRedBC|$vBC|$pICMS|$vICMS|$vCred\r\n";
                        break;
                }

            }
        }
        $this->txt = $txt;
        return $txt;
    } // fim da função ctexml2txt
}//fim da classe
