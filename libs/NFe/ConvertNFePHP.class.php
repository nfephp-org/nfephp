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
 * @name        ConvertNFePHP
 * @version     3.10.18
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * @author      Daniel Batista Lemes <dlemes at gmail dot com>
 *
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              Alberto  Leal <ees.beto at gmail dot com>
 *              Andre Noel <andrenoel at ubuntu dot com>
 *              Bruno Porto <brunotporto at gmail dot com>
 *              Clauber Santos <cload_info at yahoo dot com dot br>
 *              Crercio <crercio at terra dot com dot br>
 *              Diogo Mosela <diego dot caicai at gmail dot com>
 *              Eduardo Gusmão <eduardo dot intrasis at gmail dot com>
 *              Elton Nagai <eltaum at gmail dot com>
 *              Fabio Ananias Silva <binhoouropreto at gmail dot com>
 *              Giovani Paseto <giovaniw2 at gmail dot com>
 *              Giuliano Nascimento <giusoft at hotmail dot com>
 *              Guilherme Calabria Filho <guiga at gmail dot com>
 *              Helder Ferreira <helder.mauricicio at gmail dot com>
 *              João Eduardo Silva Corrêa <jscorrea2 at gmail dot com>
 *              Leandro C. Lopez <leandro.castoldi at gmail dot com>
 *              Leandro G. Santana <leandrosantana1 at gmail dot com>
 *              Marcos Diez <marcos at unitron dot com dot br>
 *              Renato Ricci <renatoricci at singlesoftware dot com dot br>
 *              Roberto Spadim <rspadim at gmail dot com>
 *              Rodrigo Rysdyk <rodrigo_rysdyk at hotmail dot com>
 *
 */

class ConvertNFePHP
{

    /**
     * xml
     * XML da NFe
     * @var string 
     */
    public $xml = '';

    /**
     * chave
     * ID da NFe 44 digitos
     * @var string 
     */
    public $chave = '';

    /**
     * txt
     * @var string TXT com NFe
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
     * limparString
     * Se for = true remove caracteres especiais na conversão de TXT pra XML
     * @var boolean
     */
    public $limparString = true;

    /**
     * contruct
     * Método contrutor da classe
     *
     * @name contruct
     * @param boolean $limparString Ativa flag para limpar os caracteres especiais e acentos
     * @return none
     */
    public function __construct($limparString = true)
    {
        $this->limparString = $limparString;
    } //fim __contruct

    /**
     * nfetxt2xml
     * Converte o arquivo txt em um array para ser mais facilmente tratado
     *
     * @name nfetxt2xml
     * @param mixed $txt Path para o arquivo txt, array ou o conteudo do txt em uma string
     * @return string xml construido
     */
    public function nfetxt2xml($txt)
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
        return $this->nfeTxt2XmlArrayComLinhas($aDados);
    } //fim nfetxt2xml

    /**
     * nfeTxt2XmlArrayComLinhas
     * Método de conversão das NFe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas versão 2.0.0 (24/08/2010)
     *
     * @name nfeTxt2XmlArrayComLinhas
     * @param string $arrayComAsLinhasDoArquivo Array de Strings onde cada elemento é uma linha do arquivo
     * @return string xml construido
     */
    protected function nfeTxt2XmlArrayComLinhas($arrayComAsLinhasDoArquivo)
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
                    //atributos da NFe, campos obrigatórios [NFe]
                    //A|versão do schema|id
                    // cria nota no array
                    $currnota++;
                    unset($dom, $NFe, $infNFe, $NFref, $refNFP);
                    /// limpar todas variaveis utilizadas por cada nota fiscal....
                    //(evitar que o produto entre 2 vezes ou q o endereço anterior seja usado, e coisas do tipo....)
                    unset($dom, $NFe, $infNFe, $ide, $cUF, $cNF, $natOp,
                          $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt,
                          $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV,
                          $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont,
                          $xJust, $NFref, $refNFe, $refNF, $AAMM, $CNPJ,
                          $refNFP, $IE, $CPF, $refCTe, $refECF, $nECF,
                          $nCOO, $emit, $xNome, $xFant, $IEST, $IM, $cnae,
                          $CRT, $cnpj, $cpf, $enderEmi, $xLgr, $nro, $xCpl,
                          $xBairro, $cMin, $xMin, $cMun, $xMun, $UF, $CEP,
                          $cPais, $xPais, $fone, $dest, $ISUF, $email,
                          $enderDest, $retirada, $entrega, $det, $infAdProd,
                          $prod, $cProd, $cEAN, $xProd, $NCM, $NVE, $EXTIPI, $CFOP,
                          $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib,
                          $qtrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro,
                          $indTot, $xPed, $nItemPed, $DI, $dDI, $xLocDesemb,
                          $UFDesemb, $dDesemb, $tpViaTransp, $vAFRMM, $tpIntermedio,
                          $UFTerceiro, $cExportador, $adi, $nAdicao,
                          $nSeqAdicC, $cFabricante, $vDescDI, $nDraw,
                          $detExport, $exportInd, $nRE, $chNFe, $qExport, $veicProd, $tpOP,
                          $chassi, $cCor, $xCor, $pot, $cilin, $pesoL, $pesoB,
                          $nSerie, $tpComb, $nMotor, $CMT, $dist, $anoMod,
                          $anoFab, $tpPint, $tpVeic, $espVeic, $VIN, $condVeic,
                          $cMod, $cCorDENATRAN, $lota, $tpRest, $med, $nLote,
                          $qLote, $dFab, $dVal, $vPMC, $arm, $tpArma, $nCano,
                          $descr, $comb, $cProdANP, $CODIG, $qTemp, $UFCons,
                          $CIDE, $qBCprod, $vAliqProd, $vCIDE, $imposto, $ICMS,
                          $ICMS00, $orig, $CST, $modBC, $vBC, $pICMS, $vICMS,
                          $ICMS10, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST,
                          $vICMSST, $ICMS20, $pRedBC, $ICMS30, $ICMS40, $motDesICMS,
                          $vICMSOp, $pDif, $vICMSDif, $vICMSDeson,
                          $dCompet, $vDeducao, $vDescIncond, $vDescCond, $vISSRet, $cRegTrib,
                          $ICMS51, $ICMS60, $ICMS70, $ICMS90, $ICMSPart,
                          $pBCOp, $UFST, $ICMSST, $vICMSSTRet, $vBCSTRet,
                          $vBCSTDest, $vICMSSTDest, $ICMSSN101, $CSOSN,
                          $pCredSN, $vCredICMSSN, $ICMSSN102, $ICMSSN201,
                          $ICMSSN202, $ICMSSN500, $ICMSSN900, $IPI, $clEnq,
                          $CNPJProd, $cSelo, $qSelo, $cEnq, $IPITrib, $vIPI,
                          $pIPI, $qUnid, $vUnid, $IPINT, $II, $vDespAdu,
                          $vII, $vIOF, $PIS, $PISAliq, $pPIS, $vPIS,
                          $PIDQtde, $qBCProd, $PISNT, $PISOutr, $pPIST,
                          $PISST, $COFINS, $COFINSAliq, $pCOFINS, $vCOFINS,
                          $COFINSQtde, $COFINSNT, $COFINSOutr, $COFINSST,
                          $ISSQN, $vISSQN, $cListServ, $cSitTrib, $total,
                          $ICMSTot, $vST, $vNF, $ISSQNtot, $vServ, $vISS,
                          $retTrib, $vRetPIS, $vRetCOFINS, $vRetCSLL,
                          $vBCIRRF, $vIRRF, $vBCRetPrev, $vRetPrev,
                          $transp, $modFrete, $transportadora, $xEnder,
                          $retTransp, $vBCRet, $pICMSRet, $veicTransp,
                          $placa, $RNTC, $reboque, $vagao, $balsa, $vol,
                          $qVol, $esp, $marca, $nVol, $lacres, $nLacres,
                          $cobr, $fat, $nFat, $vOrig, $vLiq, $dup, $nDup,
                          $dVenc, $vDup, $tPag, $vPag, $card, $tBand, $cAut,
                          $infAdic, $infAdFisco, $infCpl,
                          $infNFE, $obsCont, $xTexto, $obsFisco, $procRef,
                          $nProc, $exporta, $UFSaidaPais, $xLocExporta, $xLocDespacho, $compra,
                          $xNEmp, $xCont, $cana, $safra, $qTotMes, $qTotAnt,
                          $qTotGer, $vFor, $vTotDed, $vLiqFor, $forDia, $dia,
                          $qtde, $deduc, $xDed, $vDed, $vTotTrib);

                    $this->chave = '';
                    $this->tpAmb = '';
                    $this->xml = '';

                    $notas[$currnota] = array(
                        'dom' => false,
                        'NFe' => false,
                        'infNFe' => false,
                        'NFref' => false,
                        'chave' => '',
                        'tpAmb' => '');

                    //cria o objeto DOM para o xml
                    $notas[$currnota]['dom'] = new DOMDocument('1.0', 'UTF-8');
                    $dom = & $notas[$currnota]['dom'];
                    $dom->formatOutput = true;
                    $dom->preserveWhiteSpace = false;
                    $notas[$currnota]['NFe'] = $dom->createElement("NFe");
                    $NFe = & $notas[$currnota]['NFe'];
                    $NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
                    $notas[$currnota]['infNFe'] = $dom->createElement("infNFe");
                    $infNFe = &$notas[$currnota]['infNFe'];
                    $infNFe->setAttribute("Id", $dados[2]);
                    $infNFe->setAttribute("versao", $dados[1]);
                    //pega a chave de 44 digitos excluindo o a sigla NFe
                    $this->chave = substr($dados[2], 3, 44);
                    $notas[$currnota]['chave'] = $this->chave;
                    //$pk_nItem = $dom->createElement("pk_nItem");
                    //$infNFe->appendChild($pk_nItem);
                    break;
                case "B": //identificadores [infNFe]
                    //B|cUF|cNF|natOp|indPag|mod|serie|nNF|dhEmi|dhSaiEnt
                    //|tpNF|idDest|cMunFG|tpImp|tpEmis|cDV|tpAmb|finNFe|indFinal|indPres|procEmi|VerProc|dhCont|xJust
                    $ide = $dom->createElement("ide");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $ide->appendChild($cUF);
                    $cNF = $dom->createElement("cNF", $dados[2]);
                    $ide->appendChild($cNF);
                    $NatOp = $dom->createElement("natOp", $dados[3]);
                    $ide->appendChild($NatOp);
                    $indPag = $dom->createElement("indPag", $dados[4]);
                    $ide->appendChild($indPag);
                    $mod = $dom->createElement("mod", $dados[5]);
                    $ide->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[6]);
                    $ide->appendChild($serie);
                    $nNF = $dom->createElement("nNF", $dados[7]);
                    $ide->appendChild($nNF);
                    $dhEmi = $dom->createElement("dhEmi", $dados[8]);
                    $ide->appendChild($dhEmi);
                    if (!empty($dados[9])) {
                        $dhSaiEnt = $dom->createElement("dhSaiEnt", $dados[9]);
                        $ide->appendChild($dhSaiEnt);
                    }
                    $tpNF = $dom->createElement("tpNF", $dados[10]);
                    $ide->appendChild($tpNF);
                    $idDest = $dom->createElement("idDest", $dados[11]);
                    $ide->appendChild($idDest);
                    $cMunFG = $dom->createElement("cMunFG", $dados[12]);
                    $ide->appendChild($cMunFG);
                    $tpImp = $dom->createElement("tpImp", $dados[13]);
                    $ide->appendChild($tpImp);
                    $tpEmis = $dom->createElement("tpEmis", $dados[14]);
                    $ide->appendChild($tpEmis);
                    $cDV = $dom->createElement("cDV", $dados[15]);
                    $ide->appendChild($cDV);
                    $tpAmb = $dom->createElement("tpAmb", $dados[16]);
                    //guardar a variavel para uso posterior
                    $this->tpAmb = $dados[16];
                    $notas[$currnota]['tpAmb'] = $this->tpAmb;
                    $ide->appendChild($tpAmb);
                    $finNFe = $dom->createElement("finNFe", $dados[17]);
                    $ide->appendChild($finNFe);
                    $indFinal = $dom->createElement("indFinal", $dados[18]);
                    $ide->appendChild($indFinal);
                    $indPres = $dom->createElement("indPres", $dados[19]);
                    $ide->appendChild($indPres);
                    $procEmi = $dom->createElement("procEmi", $dados[20]);
                    $ide->appendChild($procEmi);
                    if (empty($dados[21])) {
                        $dados[21] = "NfePHP";
                    }
                    $verProc = $dom->createElement("verProc", $dados[21]);
                    $ide->appendChild($verProc);
                    if (!empty($dados[22]) || !empty($dados[23])) {
                        $dhCont = $dom->createElement("dhCont", $dados[22]);
                        $ide->appendChild($dhCont);
                        $xJust = $dom->createElement("xJust", $dados[23]);
                        $ide->appendChild($xJust);
                    }
                    $infNFe->appendChild($ide);
                    break;
                case "B13":
                    //NFe referenciadas [ide]
                    if (!isset($NFref)) {
                        $notas[$currnota]['NFref'] = $dom->createElement("NFref");
                        $NFref = & $notas[$currnota]['NFref'];
                        $ide->appendChild($NFref);
                    }
                    $refNFe = $dom->createElement("refNFe", $dados[1]);
                    $NFref->appendChild($refNFe);
                    break;
                case "B14":
                    //NF referenciadas [NFref]
                    //B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF|
                    if (!isset($NFref)) {
                        $notas[$currnota]['NFref'] = $dom->createElement("NFref");
                        $NFref = & $notas[$currnota]['NFref'];
                        $ide->appendChild($NFref);
                    }
                    $refNF = $dom->createElement("refNF");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $refNF->appendChild($cUF);
                    $AAMM = $dom->createElement("AAMM", $dados[2]);
                    $refNF->appendChild($AAMM);
                    $CNPJ = $dom->createElement("CNPJ", $dados[3]);
                    $refNF->appendChild($CNPJ);
                    $mod = $dom->createElement("mod", $dados[4]);
                    $refNF->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[5]);
                    $refNF->appendChild($serie);
                    $nNF = $dom->createElement("nNF", $dados[6]);
                    $refNF->appendChild($nNF);
                    $NFref->appendChild($refNF);
                    break;
                case "B20a":
                    //Grupo de informações da NF [NFref]
                    if (!isset($NFref)) {
                        $notas[$currnota]['NFref'] = $dom->createElement("NFref");
                        $NFref = & $notas[$currnota]['NFref'];
                        $ide->appendChild($NFref);
                    }
                    $refNFP = $dom->createElement("refNFP");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $refNFP->appendChild($cUF);
                    $AAMM = $dom->createElement("AAMM", $dados[2]);
                    $refNFP->appendChild($AAMM);
                    $IE = $dom->createElement("IE", $dados[3]);
                    $refNFP->appendChild($IE);
                    $mod = $dom->createElement("mod", $dados[4]);
                    $refNFP->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[5]);
                    $refNFP->appendChild($serie);
                    $nNF = $dom->createElement("nNF", $dados[6]);
                    $refNFP->appendChild($nNF);
                    $NFref->appendChild($refNFP);
                    break;
                case "B20d":
                    //CNPJ [refNFP]
                    //B20d|CNPJ
                    if (isset($refNFP)) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $refNFP->insertBefore($ide->appendChild($CNPJ), $IE);
                    }
                    break;
                case "B20e":
                    //CPF [refNFP]
                    //B20e|CPF
                    if (isset($refNFP)) {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $refNFP->insertBefore($ide->appendChild($CPF), $IE);
                    }
                    break;
                case "B20i":
                    // CTE [NFref]
                    if (!isset($NFref)) {
                        $notas[$currnota]['NFref'] = $dom->createElement("NFref");
                        $NFref = & $notas[$currnota]['NFref'];
                        $ide->appendChild($NFref);
                    }
                    //B20i|refCTe|
                    $refCTe = $dom->createElement("refCTe", $dados[1]);
                    $NFref->appendChild($refCTe);
                    break;
                case "B20j":
                    // ECF [NFref]
                    if (!isset($NFref)) {
                        $notas[$currnota]['NFref'] = $dom->createElement("NFref");
                        $NFref = & $notas[$currnota]['NFref'];
                        $ide->appendChild($NFref);
                    }
                    //B20j|mod|nECF|nCOO|
                    $refECF = $dom->createElement("refECF");
                    $mod = $dom->createElement("mod", $dados[1]);
                    $refECF->appendChild($mod);
                    $nECF = $dom->createElement("nECF", $dados[2]);
                    $refECF->appendChild($nECF);
                    $nCOO = $dom->createElement("nCOO", $dados[3]);
                    $refECF->appendChild($nCOO);
                    $NFref->appendChild($refECF);
                    break;
                case "C":
                    //dados do emitente [infNFe]
                    //C|XNome|XFant|IE|IEST|IM|CNAE|CRT|
                    $emit = $dom->createElement("emit");
                    $xNome = $dom->createElement("xNome", $dados[1]);
                    $emit->appendChild($xNome);
                    if (!empty($dados[2])) {
                        $xFant = $dom->createElement("xFant", $dados[2]);
                        $emit->appendChild($xFant);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $emit->appendChild($IE);
                    if (!empty($dados[4])) {
                        $IEST = $dom->createElement("IEST", $dados[4]);
                        $emit->appendChild($IEST);
                    }
                    if (!empty($dados[5])) {
                        $IM = $dom->createElement("IM", $dados[5]);
                        $emit->appendChild($IM);
                    }
                    if (!empty($dados[6])) {
                        $cnae = $dom->createElement("CNAE", $dados[6]);
                        $emit->appendChild($cnae);
                    }
                    if (!empty($dados[7])) {
                        $CRT = $dom->createElement("CRT", $dados[7]);
                        $emit->appendChild($CRT);
                    }
                    $infNFe->appendChild($emit);
                    break;
                case "C02": //CNPJ [emit]
                    $cnpj = $dom->createElement("CNPJ", $dados[1]);
                    $emit->insertBefore($emit->appendChild($cnpj), $xNome);
                    break;
                case "C02a": //CPF [emit]
                    $cpf = $dom->createElement("CPF", $dados[1]);
                    $emit->insertBefore($emit->appendChild($cpf), $xNome);
                    break;
                case "C05":
                    //Grupo do Endereço do emitente [emit]
                    //C05|XLgr|Nro|Cpl|Bairro|CMun|XMun|UF|CEP|cPais|xPais|fone|
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
                    $UF = $dom->createElement("UF", $dados[7]);
                    $enderEmi->appendChild($UF);
                    if (!empty($dados[8])) {
                        $CEP = $dom->createElement("CEP", $dados[8]);
                        $enderEmi->appendChild($CEP);
                    }
                    if (!empty($dados[9])) {
                        $cPais = $dom->createElement("cPais", $dados[9]);
                        $enderEmi->appendChild($cPais);
                    }
                    if (!empty($dados[10])) {
                        $xPais = $dom->createElement("xPais", $dados[10]);
                        $enderEmi->appendChild($xPais);
                    }
                    if (!empty($dados[11])) {
                        $fone = $dom->createElement("fone", $dados[11]);
                        $enderEmi->appendChild($fone);
                    }
                    $emit->insertBefore($emit->appendChild($enderEmi), $IE);
                    break;
                case "E":
                    //Grupo de identificação do Destinatário da NF-e [infNFe]
                    //E|xNome|indIEDest|IE|ISUF|IM|email|
                    $dest = $dom->createElement("dest");
                    //se ambiente homologação preencher conforme NT2011.002
                    //válida a partir de 01/05/2011
                    if ($this->tpAmb == '2') {
                        $xNome = $dom->createElement("xNome", 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL');
                        $dest->appendChild($xNome);
                        //Verificar a regra abaixo p/Homologação - 9=Nao contribuinte que pode ou não ter I.E.
                        $indIEDest = $dom->createElement("indIEDest", "9");
                        $dest->appendChild($indIEDest);
                        //$IE = $dom->createElement("IE", '');
                        //$dest->appendChild($IE);
                    } else {
                        $xNome = $dom->createElement("xNome", $dados[1]);
                        $dest->appendChild($xNome);
                        $indIEDest = $dom->createElement("indIEDest", $dados[2]);
                        $dest->appendChild($indIEDest);
                        if ($dados[2] != '2' && $dados[2] != '9') {
                            $IE = $dom->createElement("IE", $dados[3]);
                            $dest->appendChild($IE);
                        }
                    }
                    if (!empty($dados[4])) {
                        $ISUF = $dom->createElement("ISUF", $dados[4]);
                        $dest->appendChild($ISUF);
                    }
                    if (!empty($dados[5])) {
                        $IM = $dom->createElement("IM", $dados[5]);
                        $dest->appendChild($IM);
                    }
                    if (!empty($dados[6])) {
                        $email = $dom->createElement("email", $dados[6]);
                        $dest->appendChild($email);
                    }
                    $infNFe->appendChild($dest);
                    break;
                case "E02":
                    //CNPJ [dest]
                    //se ambiente homologação preencher conforme NT2011.002,
                    //válida a partir de 01/05/2011
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
                    break;
                case "E03":
                    //CPF [dest]
                    //se ambiente homologação preencher conforme NT2011.002,
                    //válida a partir de 01/05/2011
                    if ($this->tpAmb == '2') {
                        if ($dados[1] != '') {
                            //operação nacional em ambiente homologação usar 99999999000191
                            $CNPJ = $dom->createElement("CNPJ", '99999999000191');
                        } else {
                            //operação com o exterior CNPJ vazio
                            $CNPJ = $dom->createElement("CNPJ", '');
                        }
                        $dest->insertBefore($dest->appendChild($CNPJ), $xNome);
                    } else {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $dest->insertBefore($dest->appendChild($CPF), $xNome);
                    } //fim teste ambiente
                    break;
                case "E03a":
                    //idEstrangeiro [dest]
                    //Verificar se há NT que instrua preenchimento em ambiente homologação. Procurei e não encontrei restrição
                    //idEstrangeiro é aceito como NULL (Branco), por isso não há regra de !empty abaixo
                    $idEstrangeiro = $dom->createElement("idEstrangeiro", $dados[1]);
                    $dest->insertBefore($dest->appendChild($idEstrangeiro), $xNome);
                    break;
                case "E05":
                    //Grupo de endereço do Destinatário da NF-e [dest]
                    //E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
                    $enderDest = $dom->createElement("enderDest");
                    $xLgr = $dom->createElement("xLgr", $dados[1]);
                    $enderDest->appendChild($xLgr);
                    $dados[2] = abs((int) $dados[2]);
                    $nro = $dom->createElement("nro", $dados[2]);
                    $enderDest->appendChild($nro);
                    if (!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $enderDest->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[4]);
                    $enderDest->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[5]);
                    $enderDest->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[6]);
                    $enderDest->appendChild($xMun);
                    $UF = $dom->createElement("UF", $dados[7]);
                    $enderDest->appendChild($UF);
                    if (!empty($dados[8])) {
                        $CEP = $dom->createElement("CEP", $dados[8]);
                        $enderDest->appendChild($CEP);
                    }
                    if (!empty($dados[9])) {
                        $cPais = $dom->createElement("cPais", $dados[9]);
                        $enderDest->appendChild($cPais);
                    }
                    if (!empty($dados[10])) {
                        $xPais = $dom->createElement("xPais", $dados[10]);
                        $enderDest->appendChild($xPais);
                    }
                    if (!empty($dados[11])) {
                        $fone = $dom->createElement("fone", $dados[11]);
                        $enderDest->appendChild($fone);
                    }
                    $dest->insertBefore($dest->appendChild($enderDest), $indIEDest);
                    break;
                case "F":
                    //Grupo de identificação do Local de retirada [infNFe]
                    //F|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
                    $retirada = $dom->createElement("retirada");
                    if (!empty($dados[1])) {
                        $xLgr = $dom->createElement("xLgr", $dados[1]);
                        $retirada->appendChild($xLgr);
                    }
                    if (!empty($dados[2])) {
                        $dados[2] = abs((int) $dados[2]);
                        $nro = $dom->createElement("nro", $dados[2]);
                        $retirada->appendChild($nro);
                    }
                    if (!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $retirada->appendChild($xCpl);
                    }
                    if (!empty($dados[4])) {
                        $xBairro = $dom->createElement("xBairro", $dados[4]);
                        $retirada->appendChild($xBairro);
                    }
                    if (!empty($dados[5])) {
                        $cMun = $dom->createElement("cMun", $dados[5]);
                        $retirada->appendChild($cMun);
                    }
                    if (!empty($dados[6])) {
                        $xMun = $dom->createElement("xMun", $dados[6]);
                        $retirada->appendChild($xMun);
                    }
                    if (!empty($dados[7])) {
                        $UF = $dom->createElement("UF", $dados[7]);
                        $retirada->appendChild($UF);
                    }
                    $infNFe->appendChild($retirada);
                    break;
                case "F02": //CNPJ [retirada]
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $retirada->insertBefore($retirada->appendChild($CNPJ), $xLgr);
                    }
                    break;
                case "F02a":
                    //CPF [retirada]
                    if (!empty($dados[1])) {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $retirada->insertBefore($retirada->appendChild($CPF), $xLgr);
                    }
                    break;
                case "G":
                    // Grupo de identificação do Local de entrega [entrega]
                    //G|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
                    $entrega = $dom->createElement("entrega");
                    if (!empty($dados[1])) {
                        $xLgr = $dom->createElement("xLgr", $dados[1]);
                        $entrega->appendChild($xLgr);
                    }
                    if (!empty($dados[2])) {
                        $dados[2] = abs((int) $dados[2]);
                        $nro = $dom->createElement("nro", $dados[2]);
                        $entrega->appendChild($nro);
                    }
                    if (!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $entrega->appendChild($xCpl);
                    }
                    if (!empty($dados[4])) {
                        $xBairro = $dom->createElement("xBairro", $dados[4]);
                        $entrega->appendChild($xBairro);
                    }
                    if (!empty($dados[5])) {
                        $cMun = $dom->createElement("cMun", $dados[5]);
                        $entrega->appendChild($cMun);
                    }
                    if (!empty($dados[6])) {
                        $xMun = $dom->createElement("xMun", $dados[6]);
                        $entrega->appendChild($xMun);
                    }
                    if (!empty($dados[7])) {
                        $UF = $dom->createElement("UF", $dados[7]);
                        $entrega->appendChild($UF);
                    }
                    $infNFe->appendChild($entrega);
                    break;
                case "G02":
                    // CNPJ [entrega]
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $entrega->insertBefore($entrega->appendChild($CNPJ), $xLgr);
                    }
                    break;
                case "G02a":
                    // CPF [entrega]
                    if (!empty($dados[1])) {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $entrega->insertBefore($entrega->appendChild($CPF), $xLgr);
                    }
                    break;
                case "H":
                    // Grupo do detalhamento de Produtos e Serviços da NF-e [infNFe]
                    $det = $dom->createElement("det");
                    $det->setAttribute("nItem", $dados[1]);
                    if (!empty($dados[2])) {
                        $infAdProd = $dom->createElement("infAdProd", $dados[2]);
                        $det->appendChild($infAdProd);
                    }
                    $infNFe->appendChild($det);
                    break;
                case "I":
                    //PRODUTO SERVICO [det]
                    //I|cProd|cEAN|xProd|NCM|EXTIPI|CFOP|uCom|qCom|vUnCom|vProd|cEANTrib|uTrib|qTrib|vUnTrib
                    //|vFrete|vSeg|vDesc|vOutro|indTot|xPed|nItemPed|nFCI|
                    $prod = $dom->createElement("prod");
                    $cProd = $dom->createElement("cProd", $dados[1]);
                    $prod->appendChild($cProd);
                    $cEAN = $dom->createElement("cEAN", $dados[2]);
                    $prod->appendChild($cEAN);
                    $xProd = $dom->createElement("xProd", $dados[3]);
                    $prod->appendChild($xProd);
                    $NCM = $dom->createElement("NCM", $dados[4]);
                    $prod->appendChild($NCM);
                    if (!empty($dados[5])) {
                        //Esperar estabilidade do Emissor de NF-e 3.10 para saber se poderemos manter compatibilidade com o modo de importação do mesmo.
                        // São aceitos [0-8] NVE, mas o emissor EXPORTA apenas 1. Atualmente (15/09/14) ainda não é possível testar a importação do TXT no emissor.
                        $NVE = $dom->createElement("NVE", $dados[5]);
                        $prod->appendChild($NVE);
                    }
                    if (!empty($dados[6])) {
                        $EXTIPI = $dom->createElement("EXTIPI", $dados[6]);
                        $prod->appendChild($EXTIPI);
                    }
                    $CFOP = $dom->createElement("CFOP", $dados[7]);
                    $prod->appendChild($CFOP);
                    $uCom = $dom->createElement("uCom", $dados[8]);
                    $prod->appendChild($uCom);
                    $qCom = $dom->createElement("qCom", $dados[9]);
                    $prod->appendChild($qCom);
                    $vUnCom = $dom->createElement("vUnCom", $dados[10]);
                    $prod->appendChild($vUnCom);
                    $vProd = $dom->createElement("vProd", $dados[11]);
                    $prod->appendChild($vProd);
                    $cEANTrib = $dom->createElement("cEANTrib", $dados[12]);
                    $prod->appendChild($cEANTrib);
                    if (!empty($dados[13])) {
                        $uTrib = $dom->createElement("uTrib", $dados[13]);
                    } else {
                        $uTrib = $dom->createElement("uTrib", $dados[8]);
                    }
                    $prod->appendChild($uTrib);
                    if (!empty($dados[14])) {
                        $qTrib = $dom->createElement("qTrib", $dados[14]);
                    } else {
                        $qTrib = $dom->createElement("qTrib", $dados[9]);
                    }
                    $prod->appendChild($qTrib);
                    if (!empty($dados[15])) {
                        $vUnTrib = $dom->createElement("vUnTrib", $dados[15]);
                    } else {
                        $vUnTrib = $dom->createElement("vUnTrib", $dados[10]);
                    }
                    $prod->appendChild($vUnTrib);
                    if (!empty($dados[16])) {
                        $vFrete = $dom->createElement("vFrete", $dados[16]);
                        $prod->appendChild($vFrete);
                    }
                    if (!empty($dados[17])) {
                        $vSeg = $dom->createElement("vSeg", $dados[17]);
                        $prod->appendChild($vSeg);
                    }
                    if (!empty($dados[18])) {
                        $vDesc = $dom->createElement("vDesc", $dados[18]);
                        $prod->appendChild($vDesc);
                    }
                    if (!empty($dados[19])) {
                        $vOutro = $dom->createElement("vOutro", $dados[19]);
                        $prod->appendChild($vOutro);
                    }
                    if (!empty($dados[20]) || $dados[20] == 0) {
                        $indTot = $dom->createElement("indTot", $dados[20]);
                        $prod->appendChild($indTot);
                    } else {
                        $indTot = $dom->createElement("indTot", '0');
                        $prod->appendChild($indTot);
                    }
                    if (sizeof($dados) > 20) {
                        if (!empty($dados[21])) {
                            $xPed = $dom->createElement("xPed", $dados[21]);
                            $prod->appendChild($xPed);
                        }
                        if (!empty($dados[22])) {
                            $nItemPed = $dom->createElement("nItemPed", $dados[22]);
                            $prod->appendChild($nItemPed);
                        }
                        if (!empty($dados[23])) {
                            $nFCI = $dom->createElement("nFCI", $dados[23]);
                            $prod->appendChild($nFCI);
                        }
                    }
                    if (!isset($infAdProd)) {
                        $det->appendChild($prod);
                    } else {
                        $det->insertBefore($det->appendChild($prod), $infAdProd);
                    }
                    break;
                case "I18":
                    //Tag da Declaração de Importação [prod]
                    //I18|nDI|dDI|xLocDesemb|UFDesemb|dDesemb|tpViaTransp|vAFRMM|tpIntermedio|CNPJ|UFTerceiro|cExportador|
                    $DI = $dom->createElement("DI");
                    if (!empty($dados[1])) {
                        $nDI = $dom->createElement("nDI", $dados[1]);
                        $DI->appendChild($nDI);
                    }
                    if (!empty($dados[2])) {
                        $dDI = $dom->createElement("dDI", $dados[2]);
                        $DI->appendChild($dDI);
                    }
                    if (!empty($dados[3])) {
                        $xLocDesemb = $dom->createElement("xLocDesemb", $dados[3]);
                        $DI->appendChild($xLocDesemb);
                    }
                    if (!empty($dados[4])) {
                        $UFDesemb = $dom->createElement("UFDesemb", $dados[4]);
                        $DI->appendChild($UFDesemb);
                    }
                    if (!empty($dados[5])) {
                        $dDesemb = $dom->createElement("dDesemb", $dados[5]);
                        $DI->appendChild($dDesemb);
                    }
                    if (!empty($dados[6])) {
                        $tpViaTransp = $dom->createElement("tpViaTransp", $dados[6]);
                        $DI->appendChild($tpViaTransp);
                    }
                    if (!empty($dados[7])) {
                        $vAFRMM = $dom->createElement("vAFRMM", $dados[7]);
                        $DI->appendChild($vAFRMM);
                    }
                    if (!empty($dados[8])) {
                        $tpIntermedio = $dom->createElement("tpIntermedio", $dados[8]);
                        $DI->appendChild($tpIntermedio);
                    }
                    if (!empty($dados[9])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[9]);
                        $DI->appendChild($CNPJ);
                    }
                    if (!empty($dados[10])) {
                        $UFTerceiro = $dom->createElement("UFTerceiro", $dados[10]);
                        $DI->appendChild($UFTerceiro);
                    }
                    if (!empty($dados[11])) {
                        $cExportador = $dom->createElement("cExportador", $dados[11]);
                        $DI->appendChild($cExportador);
                    }
                    if (!isset($xPed) && !isset($nItemPed)) {
                        $prod->appendChild($DI);
                    } else {
                        if (!isset($xPed)) {
                            $prod->insertBefore($prod->appendChild($DI), $nItemPed);
                        } else {
                            $prod->insertBefore($prod->appendChild($DI), $xPed);
                        }
                    }
                    break;
                case "I25":
                    //Adições [DI]
                    //I25|nAdicao|nSeqAdicC|cFabricante|vDescDI|nDraw|
                    $adi = $dom->createElement("adi");
                    if (!empty($dados[1])) {
                        $nAdicao = $dom->createElement("nAdicao", $dados[1]);
                        $adi->appendChild($nAdicao);
                    }
                    if (!empty($dados[2])) {
                        $nSeqAdicC = $dom->createElement("nSeqAdicC", $dados[2]);
                        $adi->appendChild($nSeqAdicC);
                    }
                    if (!empty($dados[3])) {
                        $cFabricante = $dom->createElement("cFabricante", $dados[3]);
                        $adi->appendChild($cFabricante);
                    }
                    if (!empty($dados[4])) {
                        $vDescDI = $dom->createElement("vDescDI", $dados[4]);
                        $adi->appendChild($vDescDI);
                    }
                    if (!empty($dados[5])) {
                        $nDraw = $dom->createElement("nDraw", $dados[5]);
                        $adi->appendChild($nDraw);
                    }
                    $DI->appendChild($adi);
                    break;
                case "I50":
                    //Informações de exportação
                    //I50|nDraw|
                    $detExport = $dom->createElement("detExport");
                    if (!empty($dados[1])) {
                        $nDraw = $dom->createElement("nDraw", $dados[1]);
                        $detExport->appendChild($nDraw);
                    }
                    $DI->appendChild($detExport);
                    break;
                case "I52":
                    //Exportação indireta
                    //I52|nRE|chNFe|qExport|
                    $exportInd = $dom->createElement("exportInd");
                    if (!empty($dados[1])) {
                        $nRE = $dom->createElement("nRE", $dados[1]);
                        $exportInd->appendChild($nRE);
                    }
                    if (!empty($dados[2])) {
                        $chNFe = $dom->createElement("chNFe", $dados[2]);
                        $exportInd->appendChild($chNFe);
                    }
                    if (!empty($dados[3])) {
                        $qExport = $dom->createElement("qExport", $dados[3]);
                        $exportInd->appendChild($qExport);
                    }
                    $DI->appendChild($exportInd);
                    break;
                case "J":
                    //Grupo do detalhamento de veículos novos [prod]
                    //J|TpOp|Chassi|CCor|XCor|Pot|cilin|pesoL|pesoB|NSerie|TpComb|NMotor|CMT|Dist|anoMod
                    //|anoFab|tpPint|tpVeic|espVeic|VIN|condVeic|cMod|cCorDENATRAN|lota|tpRest|
                    $veicProd = $dom->createElement("veicProd");
                    if (!empty($dados[1])) {
                        $tpOP = $dom->createElement("tpOp", $dados[1]);
                        $veicProd->appendChild($tpOP);
                    }
                    if (!empty($dados[2])) {
                        $chassi = $dom->createElement("chassi", $dados[2]);
                        $veicProd->appendChild($chassi);
                    }
                    if (!empty($dados[3])) {
                        $cCor = $dom->createElement("cCor", $dados[3]);
                        $veicProd->appendChild($cCor);
                    }
                    if (!empty($dados[4])) {
                        $xCor = $dom->createElement("xCor", $dados[4]);
                        $veicProd->appendChild($dVal);
                    }
                    if (!empty($dados[5])) {
                        $pot = $dom->createElement("pot", $dados[5]);
                        $veicProd->appendChild($pot);
                    }
                    if (!empty($dados[6])) {
                        $cilin = $dom->createElement("cilin", $dados[6]);
                        $veicProd->appendChild($cilin);
                    }
                    if (!empty($dados[7])) {
                        $pesoL = $dom->createElement("pesL", $dados[7]);
                        $veicProd->appendChild($pesoL);
                    }
                    if (!empty($dados[8])) {
                        $pesoB = $dom->createElement("pesoB", $dados[8]);
                        $veicProd->appendChild($pesoB);
                    }
                    if (!empty($dados[9])) {
                        $nSerie = $dom->createElement("nSerie", $dados[9]);
                        $veicProd->appendChild($nSerie);
                    }
                    if (!empty($dados[10])) {
                        $tpComb = $dom->createElement("tpComb", $dados[10]);
                        $veicProd->appendChild($tpComb);
                    }
                    if (!empty($dados[11])) {
                        $nMotor = $dom->createElement("nMotor", $dados[11]);
                        $veicProd->appendChild($nMotor);
                    }
                    if (!empty($dados[12])) {
                        $CMT = $dom->createElement("CMT", $dados[12]);
                        $veicProd->appendChild($CMKG);
                    }
                    if (!empty($dados[13])) {
                        $dist = $dom->createElement("dist", $dados[13]);
                        $veicProd->appendChild($dist);
                    }
                    if (!empty($dados[14])) {
                        $anoMod = $dom->createElement("anoMod", $dados[14]);
                        $veicProd->appendChild($anoMod);
                    }
                    if (!empty($dados[15])) {
                        $anoFab = $dom->createElement("anoFab", $dados[15]);
                        $veicProd->appendChild($anoFab);
                    }
                    if (!empty($dados[16])) {
                        $tpPint = $dom->createElement("tpPint", $dados[16]);
                        $veicProd->appendChild($tpPint);
                    }
                    if (!empty($dados[17])) {
                        $tpVeic = $dom->createElement("tpVeic", $dados[17]);
                        $veicProd->appendChild($tpVeic);
                    }
                    if (!empty($dados[18])) {
                        $espVeic = $dom->createElement("espVeic", $dados[18]);
                        $veicProd->appendChild($espVeic);
                    }
                    if (!empty($dados[19])) {
                        $VIN = $dom->createElement("VIN", $dados[19]);
                        $veicProd->appendChild($VIN);
                    }
                    if (!empty($dados[20])) {
                        $condVeic = $dom->createElement("condVeic", $dados[20]);
                        $veicProd->appendChild($condVeic);
                    }
                    if (!empty($dados[21])) {
                        $cMod = $dom->createElement("cMod", $dados[21]);
                        $veicProd->appendChild($cMod);
                    }
                    if (!empty($dados[22])) {
                        $cCorDENATRAN = $dom->createElement("cCorDENATRAN", $dados[22]);
                        $veicProd->appendChild($cCorDENATRAN);
                    }
                    if (!empty($dados[23])) {
                        $lota = $dom->createElement("lota", $dados[23]);
                        $veicProd->appendChild($lota);
                    }
                    if (!empty($dados[24])) {
                        $tpRest = $dom->createElement("tpRest", $dados[24]);
                        $veicProd->appendChild($tpRest);
                    }
                    $prod->appendChild($veicProd);
                    break;
                case "K":
                    //Grupo do detalhamento de Medicamentos e de matériasprimas farmacêuticas [prod]
                    //K|NLote|QLote|DFab|DVal|VPMC|
                    $med = $dom->createElement("med");
                    if (!empty($dados[1])) {
                        $nLote = $dom->createElement("nLote", $dados[1]);
                        $med->appendChild($nLote);
                    }
                    if (!empty($dados[2])) {
                        $qLote = $dom->createElement("qLote", $dados[2]);
                        $med->appendChild($qLote);
                    }
                    if (!empty($dados[3])) {
                        $dFab = $dom->createElement("dFab", $dados[3]);
                        $med->appendChild($dFab);
                    }
                    $dVal = $dom->createElement("dVal", $dados[4]);
                    $med->appendChild($dVal);
                    if (!empty($dados[5])) {
                        $vPMC = $dom->createElement("vPMC", $dados[5]);
                        $med->appendChild($vPMC);
                    }
                    $prod->appendChild($med);
                    break;
                case "L":
                    //Grupo do detalhamento de Armamento [prod]
                    //L|TpArma|NSerie|NCano|Descr|
                    $arma = $dom->createElement("arma");
                    if (!empty($dados[1])) {
                        $tpArma = $dom->createElement("tpArma", $dados[1]);
                        $arma->appendChild($tpArma);
                    }
                    if (!empty($dados[2])) {
                        $nSerie = $dom->createElement("nSerie", $dados[2]);
                        $arma->appendChild($nSerie);
                    }
                    if (!empty($dados[3])) {
                        $nCano = $dom->createElement("nCano", $dados[3]);
                        $arma->appendChild($nCano);
                    }
                    if (!empty($dados[4])) {
                        $descr = $dom->createElement("descr", $dados[4]);
                        $arma->appendChild($descr);
                    }
                    $prod->appendChild($arma);
                    break;
                case "LA":
                    //Grupo de informações específicas para combustíveis líquidos e lubrificantes [prod]
                    //LA|cProdANP|pMixGN|CODIF|qTemp|UFCons|
                    $comb = $dom->createElement("comb");
                    $cProdANP = $dom->createElement("cProdANP", $dados[1]);
                    $comb->appendChild($cProdANP);
                    if (!empty($dados[2])) {
                        $pMixGN = $dom->createElement("pMixGN", $dados[2]);
                        $comb->appendChild($pMixGN);
                    }
                    if (!empty($dados[3])) {
                        $CODIF = $dom->createElement("CODIF", $dados[3]);
                        $comb->appendChild($CODIF);
                    }
                    if (!empty($dados[4])) {
                        $qTemp = $dom->createElement("qTemp", $dados[4]);
                        $comb->appendChild($qTemp);
                    }
                    $UFCons = $dom->createElement("UFCons", $dados[5]);
                    $comb->appendChild($UFCons);
                    $prod->appendChild($comb);
                    break;
                case "LA07":
                    //Grupo da CIDE [comb]
                    //LA07|qBCprod|vAliqProd|vCIDE|
                    $CIDE = $dom->createElement("CIDE");
                    $qBCprod = $dom->createElement("qBCprod", $dados[1]);
                    $CIDE->appendChild($qBCprod);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                    $CIDE->appendChild($vAliqProd);
                    $vCIDE = $dom->createElement("vCIDE", $dados[3]);
                    $CIDE->appendChild($vCIDE);
                    $comb->appendChild($CIDE);
                    break;
                case "LB":
                    //Número do RECOPI
                    $recopi = $dom->createElement("nRECOPI", $dados[1]);
                    $prod->appendChild($recopi);
                    break;
                case "M":
                    //GRUPO DE TRIBUTOS INCIDENTES NO PRODUTO SERVICO
                    $imposto = $dom->createElement("imposto");
                    //lei da transparencia 12.741/12
                    //Nota Técnica 2013/003
                    $vTotTrib=trim($dados[1]);
                    if (strlen($vTotTrib)>0) {
                        $vTotTrib = $dom->createElement("vTotTrib", $vTotTrib);
                        $imposto->appendChild($vTotTrib);
                    }
                    unset($vTotTrib);
                    if (!isset($infAdProd)) {
                        $det->appendChild($imposto);
                    } else {
                        $det->insertBefore($det->appendChild($imposto), $infAdProd);
                    }
                    $infAdProd = null;
                    break;
                case "N":
                    //ICMS
                    $ICMS = $dom->createElement("ICMS");
                    $imposto->appendChild($ICMS);
                    break;
                case "N02":
                    //CST 00 TRIBUTADO INTEGRALMENTE [ICMS]
                    // N02|orig|CST|modBC|vBC|pICMS|vICMS|
                    $ICMS00 = $dom->createElement("ICMS00");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS00->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS00->appendChild($CST);
                    $modBC = $dom->createElement("modBC", $dados[3]);
                    $ICMS00->appendChild($modBC);
                    $vBC = $dom->createElement("vBC", $dados[4]);
                    $ICMS00->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[5]);
                    $ICMS00->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[6]);
                    $ICMS00->appendChild($vICMS);
                    $ICMS->appendChild($ICMS00);
                    break;
                case "N03":
                    //CST 010 TRIBUTADO E COM COBRANCAO DE ICMS POR SUBSTUICAO TRIBUTARIA [ICMS]
                    //N03|orig|CST|modBC|vBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|
                    $ICMS10 = $dom->createElement("ICMS10");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS10->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS10->appendChild($CST);
                    $modBC = $dom->createElement("modBC", $dados[3]);
                    $ICMS10->appendChild($modBC);
                    $vBC = $dom->createElement("vBC", $dados[4]);
                    $ICMS10->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[5]);
                    $ICMS10->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[6]);
                    $ICMS10->appendChild($vICMS);
                    $modBCST = $dom->createElement("modBCST", $dados[7]);
                    $ICMS10->appendChild($modBCST);
                    if (!empty($dados[8])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[8]);
                        $ICMS10->appendChild($pMVAST);
                    }
                    if (!empty($dados[9])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[9]);
                        $ICMS10->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[10]);
                    $ICMS10->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[11]);
                    $ICMS10->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[12]);
                    $ICMS10->appendChild($vICMSST);
                    $ICMS->appendChild($ICMS10);
                    break;
                case "N04":
                    //CST 020 COM REDUCAO DE BASE DE CALCULO [ICMS]
                    //N04|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|vICMSDeson|motDesICMS|
                    $ICMS20 = $dom->createElement("ICMS20");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS20->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS20->appendChild($CST);
                    $modBC = $dom->createElement("modBC", $dados[3]);
                    $ICMS20->appendChild($modBC);
                    $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                    $ICMS20->appendChild($pRedBC);
                    $vBC = $dom->createElement("vBC", $dados[5]);
                    $ICMS20->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[6]);
                    $ICMS20->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[7]);
                    $ICMS20->appendChild($vICMS);
                    if (!empty($dados[8]) || !empty($dados[9])) {
                        $vICMSDeson = $dom->createElement("vICMSDeson",$dados[8]);
                        $ICMS20->appendChild($vICMSDeson);
                        $motDesICMS = $dom->createElement("motDesICMS",$dados[9]);
                        $ICMS20->appendChild($motDesICMS);
                    }
                    $ICMS->appendChild($ICMS20);
                    break;
                case "N05":
                    //CST 030 ISENTA OU NAO TRIBUTADO E COM COBRANCA DO ICMS POR ST [ICMS]
                    //N05|orig|CST|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|vICMSDeson|motDesICMS|
                    $ICMS30 = $dom->createElement("ICMS30");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS30->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS30->appendChild($CST);
                    $modBCST = $dom->createElement("modBCST", $dados[3]);
                    $ICMS30->appendChild($modBCST);
                    if (!empty($dados[4])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[4]);
                        $ICMS30->appendChild($pMVAST);
                    }
                    if (!empty($dados[5])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[5]);
                        $ICMS30->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[6]);
                    $ICMS30->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[7]);
                    $ICMS30->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[8]);
                    $ICMS30->appendChild($vICMSST);
                    if (!empty($dados[9]) || !empty($dados[10])) {
                        $vICMSDeson = $dom->createElement("vICMSDeson",$dados[9]);
                        $ICMS30->appendChild($vICMSDeson);
                        $motDesICMS = $dom->createElement("motDesICMS",$dados[10]);
                        $ICMS30->appendChild($motDesICMS);
                    }
                    $ICMS->appendChild($ICMS30);
                    break;
                case "N06":
                    //Grupo de Tributação do ICMS 40, 41 ou 50 [ICMS]
                    //N06|Orig|CST|vICMS|motDesICMS|
                    $ICMS40 = $dom->createElement("ICMS40");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS40->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS40->appendChild($CST);
                    if (!empty($dados[3])) {
                        $vICMS = $dom->createElement("vICMS", $dados[3]);
                        $ICMS40->appendChild($vICMS);
                    }
                    if (!empty($dados[4])) {
                        $motDesICMS = $dom->createElement("motDesICMS", $dados[4]);
                        $ICMS40->appendChild($motDesICMS);
                    }
                    $ICMS->appendChild($ICMS40);
                    break;
                case "N07":
                    //Grupo de Tributação do ICMS = 51 [ICMS]
                    //N07|Orig|CST|modBC|pRedBC|vBC|pICMS|vICMSOp|pDif|vICMSDif|*vICMS*|
                    //Parece haver um erro no manual de integração do TXT, pois falta o vICMS, que há na NT 2013/005
                    $ICMS51 = $dom->createElement("ICMS51");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS51->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS51->appendChild($CST);
                    if (!empty($dados[3])) {
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMS51->appendChild($modBC);
                    }
                    if (!empty($dados[4])) {
                        $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                        $ICMS51->appendChild($pRedBC);
                    }
                    if (!empty($dados[5])) {
                        $vBC = $dom->createElement("vBC", $dados[5]);
                        $ICMS51->appendChild($vBC);
                    }
                    if (!empty($dados[6])) {
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMS51->appendChild($pICMS);
                    }
                    if (!empty($dados[7])) {
                        $vICMSOp = $dom->createElement("vICMSOp", $dados[7]);
                        $ICMS51->appendChild($vICMSOp);
                    }
                    if (!empty($dados[8])) {
                        $pDif = $dom->createElement("pDif", $dados[8]);
                        $ICMS51->appendChild($pDif);
                    }
                    if (!empty($dados[9])) {
                        $vICMSDif = $dom->createElement("vICMSDif", $dados[9]);
                        $ICMS51->appendChild($vICMSDif);
                    }
                    if (!empty($dados[10])) {
                        $vICMS = $dom->createElement("vICMS", $dados[10]);
                        $ICMS51->appendChild($vICMS);
                    }
                    $ICMS->appendChild($ICMS51);
                    break;
                case "N08":
                    //Grupo de Tributação do ICMS = 60 [ICMS]
                    //N08|orig|CST|vBCSTRet|vICMSSTRet|
                    $ICMS60 = $dom->createElement("ICMS60");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS60->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS60->appendChild($CST);
                    $vBCST = $dom->createElement("vBCSTRet", $dados[3]);
                    $ICMS60->appendChild($vBCST);
                    $vICMSST = $dom->createElement("vICMSSTRet", $dados[4]);
                    $ICMS60->appendChild($vICMSST);
                    $ICMS->appendChild($ICMS60);
                    break;
                case "N09":
                    //Grupo de Tributação do ICMS 70 [ICMS]
                    //N09|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|vICMSDeson|motDesICMS|
                    $ICMS70 = $dom->createElement("ICMS70");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS70->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS70->appendChild($CST);
                    $modBC = $dom->createElement("modBC", $dados[3]);
                    $ICMS70->appendChild($modBC);
                    $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                    $ICMS70->appendChild($pRedBC);
                    $vBC = $dom->createElement("vBC", $dados[5]);
                    $ICMS70->appendChild($vBC);
                    $pICMS = $dom->createElement("pICMS", $dados[6]);
                    $ICMS70->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[7]);
                    $ICMS70->appendChild($vICMS);
                    $modBCST = $dom->createElement("modBCST", $dados[8]);
                    $ICMS70->appendChild($modBCST);
                    if (!empty($dados[9])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                        $ICMS70->appendChild($pMVAST);
                    }
                    if (!empty($dados[10])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                        $ICMS70->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[11]);
                    $ICMS70->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                    $ICMS70->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                    $ICMS70->appendChild($vICMSST);
                    if (!empty($dados[14]) || !empty($dados[15])) {
                        $vICMSDeson = $dom->createElement("vICMSDeson",$dados[14]);
                        $ICMS70->appendChild($vICMSDeson);
                        $motDesICMS = $dom->createElement("motDesICMS",$dados[15]);
                        $ICMS70->appendChild($motDesICMS);
                    }
                    $ICMS->appendChild($ICMS70);
                    break;
                case "N10":
                    //Grupo de Tributação do ICMS 90 Outros [ICMS]
                    //N10|orig|CST|modBC|vBC|pRedBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|vICMSDeson|motDesICMS|
                    $ICMS90 = $dom->createElement("ICMS90");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMS90->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMS90->appendChild($CST);
                    $modBC = $dom->createElement("modBC", $dados[3]);
                    $ICMS90->appendChild($modBC);
                    $vBC = $dom->createElement("vBC", $dados[4]);
                    $ICMS90->appendChild($vBC);
                    if (!empty($dados[5])) {
                        $pRedBC = $dom->createElement("pRedBC", $dados[5]);
                        $ICMS90->appendChild($pRedBC);
                    }
                    $pICMS = $dom->createElement("pICMS", $dados[6]);
                    $ICMS90->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[7]);
                    $ICMS90->appendChild($vICMS);
                    $modBCST = $dom->createElement("modBCST", $dados[8]);
                    $ICMS90->appendChild($modBCST);
                    if (!empty($dados[9])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                        $ICMS90->appendChild($pMVAST);
                    }
                    if (!empty($dados[10])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                        $ICMS90->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[11]);
                    $ICMS90->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                    $ICMS90->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                    $ICMS90->appendChild($vICMSST);
                    if (!empty($dados[14]) || !empty($dados[15])) {
                        $vICMSDeson = $dom->createElement("vICMSDeson",$dados[14]);
                        $ICMS90->appendChild($vICMSDeson);
                        $motDesICMS = $dom->createElement("motDesICMS",$dados[15]);
                        $ICMS90->appendChild($motDesICMS);
                    }
                    $ICMS->appendChild($ICMS90);
                    break;
                case "N10a":
                    //Partilha do ICMS entre a UF de origem e UF de destino ou a UF definida na legislação [ICMS]
                    //N10a|orig|CST|modBC|vBC|pRedBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST
                    //|vICMSST|pBCOp|UFST|
                    $ICMSPart = $dom->createElement("ICMSPart");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSPart->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMSPart->appendChild($CST);
                    $modBC = $dom->createElement("modBC", $dados[3]);
                    $ICMSPart->appendChild($modBC);
                    $vBC = $dom->createElement("vBC", $dados[4]);
                    $ICMSPart->appendChild($vBC);
                    if (!empty($dados[5])) {
                        $pRedBC = $dom->createElement("pRedBC", $dados[5]);
                        $ICMSPart->appendChild($pRedBC);
                    }
                    $pICMS = $dom->createElement("pICMS", $dados[6]);
                    $ICMSPart->appendChild($pICMS);
                    $vICMS = $dom->createElement("vICMS", $dados[7]);
                    $ICMSPart->appendChild($vICMS);
                    $modBCST = $dom->createElement("modBCST", $dados[8]);
                    $ICMSPart->appendChild($modBCST);
                    if (!empty($dados[9])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                        $ICMSPart->appendChild($pMVAST);
                    }
                    if (!empty($dados[10])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                        $ICMSPart->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[11]);
                    $ICMSPart->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                    $ICMSPart->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                    $ICMSPart->appendChild($vICMSST);
                    $pBCOp = $dom->createElement("pBCOp", $dados[14]);
                    $ICMSPart->appendChild($pBCOp);
                    $UFST = $dom->createElement("UFST", $dados[15]);
                    $ICMSPart->appendChild($UFST);
                    $ICMS->appendChild($ICMSPart);
                    break;
                case "N10b":
                    //ICMS ST repasse de ICMS ST retido anteriormente em operações interestaduais com repasses
                    //através do Substituto Tributário [ICMS]
                    //N10b|orig|CST|vBCSTRet|vICMSSTRet|vBCSTDest|vICMSSTDest|
                    $ICMSST = $dom->createElement("ICMSST");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSST->appendChild($orig);
                    $CST = $dom->createElement("CST", $dados[2]);
                    $ICMSST->appendChild($CST);
                    $vBCSTRet = $dom->createElement("vBCSTRet", $dados[3]);
                    $ICMSST->appendChild($vBCSTRet);
                    $vICMSSTRet = $dom->createElement("vICMSSTRet", $dados[4]);
                    $ICMSST->appendChild($vICMSSTRet);
                    $vBCSTDest = $dom->createElement("vBCSTDest", $dados[5]);
                    $ICMSST->appendChild($vBCSTDest);
                    $vICMSSTDest = $dom->createElement("vICMSSTDest", $dados[6]);
                    $ICMSST->appendChild($vICMSSTDest);
                    $ICMS->appendChild($ICMSST);
                    break;
                case "N10c":
                    //Grupo CRT=1 Simples Nacional e CSOSN=101 [ICMS]
                    //N10c|orig|CSOSN|pCredSN|vCredICMSSN|
                    $ICMSSN101 = $dom->createElement("ICMSSN101");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSSN101->appendChild($orig);
                    $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                    $ICMSSN101->appendChild($CSOSN);
                    $pCredSN = $dom->createElement("pCredSN", $dados[3]);
                    $ICMSSN101->appendChild($pCredSN);
                    $vCredICMSSN = $dom->createElement("vCredICMSSN", $dados[4]);
                    $ICMSSN101->appendChild($vCredICMSSN);
                    $ICMS->appendChild($ICMSSN101);
                    break;
                case "N10d":
                    //Grupo CRT=1 Simples Nacional e CSOSN=102, 103,300 ou 400 [ICMS]
                    //N10d|orig|CSOSN|
                    $ICMSSN102 = $dom->createElement("ICMSSN102");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSSN102->appendChild($orig);
                    $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                    $ICMSSN102->appendChild($CSOSN);
                    $ICMS->appendChild($ICMSSN102);
                    break;
                case "N10e":
                    //Grupo CRT=1 Simples Nacional e CSOSN=201 [ICMS]
                    //N10e|orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN|
                    $ICMSSN201 = $dom->createElement("ICMSSN201");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSSN201->appendChild($orig);
                    $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                    $ICMSSN201->appendChild($CSOSN);
                    $modBCST = $dom->createElement("modBCST", $dados[3]);
                    $ICMSSN201->appendChild($modBCST);
                    if (!empty($dados[4])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[4]);
                        $ICMSSN201->appendChild($pMVAST);
                    }
                    if (!empty($dados[5])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[5]);
                        $ICMSSN201->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[6]);
                    $ICMSSN201->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[7]);
                    $ICMSSN201->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[8]);
                    $ICMSSN201->appendChild($vICMSST);
                    $pCredSN = $dom->createElement("pCredSN", $dados[9]);
                    $ICMSSN201->appendChild($pCredSN);
                    $vCredICMSSN = $dom->createElement("vCredICMSSN", $dados[10]);
                    $ICMSSN201->appendChild($vCredICMSSN);
                    $ICMS->appendChild($ICMSSN201);
                    break;
                case "N10f":
                    //Grupo CRT=1 Simples Nacional e CSOSN=202 ou 203 [ICMS]
                    //N10f|orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|
                    $ICMSSN202 = $dom->createElement("ICMSSN202");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSSN202->appendChild($orig);
                    $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                    $ICMSSN202->appendChild($CSOSN);
                    $modBCST = $dom->createElement("modBCST", $dados[3]);
                    $ICMSSN202->appendChild($modBCST);
                    if (!empty($dados[4])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[4]);
                        $ICMSSN202->appendChild($pMVAST);
                    }
                    if (!empty($dados[5])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[5]);
                        $ICMSSN202->appendChild($pRedBCST);
                    }
                    $vBCST = $dom->createElement("vBCST", $dados[6]);
                    $ICMSSN202->appendChild($vBCST);
                    $pICMSST = $dom->createElement("pICMSST", $dados[7]);
                    $ICMSSN202->appendChild($pICMSST);
                    $vICMSST = $dom->createElement("vICMSST", $dados[8]);
                    $ICMSSN202->appendChild($vICMSST);
                    $ICMS->appendChild($ICMSSN202);
                    break;
                case "N10g":
                    //Grupo CRT=1 Simples Nacional e CSOSN = 500 [ICMS]
                    //N10g|orig|CSOSN|vBCSTRet|vICMSSTRet|
                    // todos esses campos sao obrigatorios
                    $ICMSSN500 = $dom->createElement("ICMSSN500");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSSN500->appendChild($orig);
                    $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                    $ICMSSN500->appendChild($CSOSN);
                    $vBCSTRet = $dom->createElement("vBCSTRet", $dados[3]);
                    $ICMSSN500->appendChild($vBCSTRet);
                    $vICMSSTRet = $dom->createElement("vICMSSTRet", $dados[4]);
                    $ICMSSN500->appendChild($vICMSSTRet);
                    $ICMS->appendChild($ICMSSN500);
                    break;
                case "N10h":
                    //TAG de Grupo CRT=1 Simples Nacional e CSOSN=900 [ICMS]
                    //N10h|orig|CSOSN|modBC|vBC|pRedBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST
                    //|pICMSST|vICMSST|pCredSN|vCredICMSSN|
                    $ICMSSN900 = $dom->createElement("ICMSSN900");
                    $orig = $dom->createElement("orig", $dados[1]);
                    $ICMSSN900->appendChild($orig);
                    $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                    $ICMSSN900->appendChild($CSOSN);
                    if (!empty($dados[3])) {
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMSSN900->appendChild($modBC);
                    }
                    if (!empty($dados[4])) {
                        $vBC = $dom->createElement("vBC", $dados[4]);
                        $ICMSSN900->appendChild($vBC);
                    }
                    if (!empty($dados[5])) {
                        $pRedBC = $dom->createElement("pRedBC", $dados[5]);
                        $ICMSSN900->appendChild($pRedBC);
                    }
                    if (!empty($dados[6])) {
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMSSN900->appendChild($pICMS);
                    }
                    if (!empty($dados[7])) {
                        $vICMS = $dom->createElement("vICMS", $dados[7]);
                        $ICMSSN900->appendChild($vICMS);
                    }
                    if (!empty($dados[8])) {
                        $modBCST = $dom->createElement("modBCST", $dados[8]);
                        $ICMSSN900->appendChild($modBCST);
                    }
                    if (!empty($dados[9])) {
                        $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                        $ICMSSN900->appendChild($pMVAST);
                    }
                    if (!empty($dados[10])) {
                        $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                        $ICMSSN900->appendChild($pRedBCST);
                    }
                    if (!empty($dados[11])) {
                        $vBCST = $dom->createElement("vBCST", $dados[11]);
                        $ICMSSN900->appendChild($vBCST);
                    }
                    if (!empty($dados[12])) {
                        $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                        $ICMSSN900->appendChild($pICMSST);
                    }
                    if (!empty($dados[13])) {
                        $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                        $ICMSSN900->appendChild($vICMSST);
                    }
                    if (!empty($dados[14])) {
                        $pCredSN = $dom->createElement("pCredSN", $dados[14]);
                        $ICMSSN900->appendChild($pCredSN);
                    }
                    if (!empty($dados[15])) {
                        $vCredICMSSN = $dom->createElement("vCredICMSSN", $dados[15]);
                        $ICMSSN900->appendChild($vCredICMSSN);
                    }
                    $ICMS->appendChild($ICMSSN900);
                    break;
                case "O":
                    //Grupo do IPI 0 ou 1 [imposto]
                    //O|clEnq|CNPJProd|cSelo|qSelo|cEnq|
                    $IPI = $dom->createElement("IPI");
                    if (!empty($dados[1])) {
                        $clEnq = $dom->createElement("clEnq", $dados[1]);
                        $IPI->appendChild($clEnq);
                    }
                    if (!empty($dados[2])) {
                        $CNPJProd = $dom->createElement("CNPJProd", $dados[2]);
                        $IPI->appendChild($CNPJProd);
                    }
                    if (!empty($dados[3])) {
                        $cSelo = $dom->createElement("cSelo", $dados[3]);
                        $IPI->appendChild($cSelo);
                    }
                    if (!empty($dados[4])) {
                        $qSelo = $dom->createElement("qSelo", $dados[4]);
                        $IPI->appendChild($qSelo);
                    }
                    if (!empty($dados[5])) {
                        $cEnq = $dom->createElement("cEnq", $dados[5]);
                        $IPI->appendChild($cEnq);
                    }
                    $imposto->appendChild($IPI);
                    break;
                case "O07":
                    //Grupo do IPITrib CST 00, 49, 50 e 99 0 ou 1 [IPI]
                    // todos esses campos sao obrigatorios
                    //O07|CST|vIPI|
                    $IPITrib = $dom->createElement("IPITrib");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $IPITrib->appendChild($CST);
                    $vIPI = $dom->createElement("vIPI", $dados[2]);
                    $IPITrib->appendChild($vIPI);
                    $IPI->appendChild($IPITrib);
                    break;
                case "O10":
                    //BC e Percentagem de IPI 0 ou 1 [IPITrib]
                    // todos esses campos sao obrigatorios
                    //O10|vBC|pIPI|
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $IPITrib->insertBefore($IPITrib->appendChild($vBC), $vIPI);
                    $pIPI = $dom->createElement("pIPI", $dados[2]);
                    $IPITrib->insertBefore($IPITrib->appendChild($pIPI), $vIPI);
                    break;
                case "O11":
                    //Quantidade total e Valor 0 ou 1 [IPITrib]
                    // todos esses campos sao obrigatorios
                    //O11|qUnid|vUnid|vIPI|
                    $qUnid = $dom->createElement("qUnid", $dados[1]);
                    $IPITrib->insertBefore($IPITrib->appendChild($qUnid), $vIPI);
                    $vUnid = $dom->createElement("vUnid", $dados[2]);
                    $IPITrib->insertBefore($IPITrib->appendChild($vUnid), $vIPI);
                    //$dados[3] seria vIPI novamente (mesmo da TAG PAI O07), mas não há esta informação na NT 2013/005 (xml)
                    break;
                case "O08":
                    //Grupo IPI Não tributavel 0 ou 1 [IPI]
                    // todos esses campos sao obrigatorios
                    //O08|CST|
                    $IPINT = $dom->createElement("IPINT");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $IPINT->appendChild($CST);
                    $IPI->appendChild($IPINT);
                    break;
                case "P":
                    //Grupo do Imposto de Importação 0 ou 1 [imposto]
                    // todos esses campos sao obrigatorios
                    //P|vBC|vDespAdu|vII|vIOF|
                    $II = $dom->createElement("II");
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $II->appendChild($vBC);
                    $vDespAdu = $dom->createElement("vDespAdu", $dados[2]);
                    $II->appendChild($vDespAdu);
                    $vII = $dom->createElement("vII", $dados[3]);
                    $II->appendChild($vII);
                    $vIOF = $dom->createElement("vIOF", $dados[4]);
                    $II->appendChild($vIOF);
                    $imposto->appendChild($II);
                    break;
                case "Q":
                    //Grupo do PIS obrigatorio [imposto]
                    //Q|
                    $PIS = $dom->createElement("PIS");
                    $imposto->appendChild($PIS);
                    break;
                case "Q02":
                    //Grupo de PIS tributado pela alíquota 0 pou 1 [PIS]
                    // todos esses campos sao obrigatorios
                    //Q02|CST|vBC|pPIS|vPIS|
                    $PISAliq = $dom->createElement("PISAliq");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $PISAliq->appendChild($CST);
                    $vBC = $dom->createElement("vBC", $dados[2]);
                    $PISAliq->appendChild($vBC);
                    $pPIS = $dom->createElement("pPIS", $dados[3]);
                    $PISAliq->appendChild($pPIS);
                    $vPIS = $dom->createElement("vPIS", $dados[4]);
                    $PISAliq->appendChild($vPIS);
                    $PIS->appendChild($PISAliq);
                    break;
                case "Q03":
                    //Grupo de PIS tributado por Qtde 0 ou 1 [PIS]
                    // todos esses campos sao obrigatorios
                    //Q03|CST|qBCProd|vAliqProd|vPIS|
                    $PISQtde = $dom->createElement("PISQtde");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $PISQtde->appendChild($CST);
                    $qBCProd = $dom->createElement("qBCProd", $dados[2]);
                    $PISQtde->appendChild($qBCProd);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[3]);
                    $PISQtde->appendChild($vAliqProd);
                    $vPIS = $dom->createElement("vPIS", $dados[4]);
                    $PISQtde->appendChild($vPIS);
                    $PIS->appendChild($PISQtde);
                    break;
                case "Q04":
                    //Grupo de PIS não tributado 0 ou 1 [PIS]
                    // todos esses campos sao obrigatorios
                    //Q04|CST|
                    $PISNT = $dom->createElement("PISNT");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $PISNT->appendChild($CST);
                    $PIS->appendChild($PISNT);
                    break;
                case "Q05":
                    //Grupo de PIS Outras Operações 0 ou 1 [PIS]
                    //Q05|CST|vPIS|
                    $PISOutr = $dom->createElement("PISOutr");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $PISOutr->appendChild($CST);
                    $vPIS = $dom->createElement("vPIS", $dados[2]);
                    $PISOutr->appendChild($vPIS);
                    $PIS->appendChild($PISOutr);
                    break;
                case "Q07":
                    //Valor da Base de Cálculo do PIS e Alíquota do PIS (em percentual) 0 pu 1 [PISOutr]
                    // todos esses campos sao obrigatorios
                    //Q07|vBC|pPIS|
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $PISOutr->insertBefore($vBC, $vPIS);
                    $pPIS = $dom->createElement("pPIS", $dados[2]);
                    $PISOutr->insertBefore($pPIS, $vPIS);
                    break;
                case "Q10":
                    //Quantidade Vendida e Alíquota do PIS (em reais) 0 ou 1 [PISOutr]
                    // todos esses campos sao obrigatorios
                    //Q10|qBCProd|vAliqProd|
                    $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                    $PISOutr->insertBefore($qBCProd, $vPIS);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                    $PISOutr->insertBefore($vAliqProd, $vPIS);
                    break;
                case "R":
                    //Grupo de PIS Substituição Tributária 0 ou 1 [imposto]
                    // todos esses campos sao obrigatorios
                    //R|vPIS|
                    $PISST = $dom->createElement("PISST");
                    $vPIS = $dom->createElement("vPIS", $dados[1]);
                    $PISST->appendChild($vPIS);
                    $imposto->appendChild($PISST);
                    break;
                case "R02": //Valor da Base de Cálculo do PIS e Alíquota do PIS (em percentual) 0 ou 1 [PISST]
                    // todos esses campos sao obrigatorios
                    //R02|vBC|pPIS|
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $PISST->appendChild($vBC);
                    $pPIS = $dom->createElement("pPIS", $dados[2]);
                    $PISST->appendChild($pPIS);
                    break;
                case "R04":
                    //Quantidade Vendida e Alíquota do PIS (em reais) 0 ou 1 [PISST]
                    // todos esses campos sao obrigatorios
                    //R04|qBCProd|vAliqProd|
                    $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                    $PISST->appendChild($qBCProd);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                    $PISST->appendChild($vAliqProd);
                    break;
                case "S":
                    //Grupo do COFINS obrigatório [imposto]
                    //S|
                    $COFINS = $dom->createElement("COFINS");
                    $imposto->appendChild($COFINS);
                    break;
                case "S02":
                    //Grupo de COFINS tributado pela alíquota 0 ou 1 [COFINS]
                    // todos esses campos sao obrigatorios
                    //S02|CST|vBC|pCOFINS|vCOFINS|
                    $COFINSAliq = $dom->createElement("COFINSAliq");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $COFINSAliq->appendChild($CST);
                    $vBC = $dom->createElement("vBC", $dados[2]);
                    $COFINSAliq->appendChild($vBC);
                    $pCOFINS = $dom->createElement("pCOFINS", $dados[3]);
                    $COFINSAliq->appendChild($pCOFINS);
                    $vCOFINS = $dom->createElement("vCOFINS", $dados[4]);
                    $COFINSAliq->appendChild($vCOFINS);
                    $COFINS->appendChild($COFINSAliq);
                    break;
                case "S03":
                    //Grupo de COFINS tributado por Qtde 0 ou 1 [COFINS]
                    // todos esses campos sao obrigatorios
                    //S03|CST|qBCProd|vAliqProd|vCOFINS|
                    $COFINSQtde = $dom->createElement("COFINSQtde");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $COFINSQtde->appendChild($CST);
                    $qBCProd = $dom->createElement("qBCProd", $dados[2]);
                    $COFINSQtde->appendChild($qBCProd);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[3]);
                    $COFINSQtde->appendChild($vAliqProd);
                    $vCOFINS = $dom->createElement("vCOFINS", $dados[4]);
                    $COFINSQtde->appendChild($vCOFINS);
                    $COFINS->appendChild($COFINSQtde);
                    break;
                case "S04":
                    //Grupo de COFINS não tributado 0 ou 1 [COFINS]
                    // todos esses campos sao obrigatorios
                    //S04|CST|
                    $COFINSNT = $dom->createElement("COFINSNT");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $COFINSNT->appendChild($CST);
                    $COFINS->appendChild($COFINSNT);
                    break;
                case "S05":
                    //Grupo de COFINS Outras Operações 0 ou 1 [COFINS]
                    //S05|CST|vCOFINS|
                    $COFINSOutr = $dom->createElement("COFINSOutr");
                    $CST = $dom->createElement("CST", $dados[1]);
                    $COFINSOutr->appendChild($CST);
                    $vCOFINS = $dom->createElement("vCOFINS", $dados[2]);
                    $COFINSOutr->appendChild($vCOFINS);
                    $COFINS->appendChild($COFINSOutr);
                    break;
                case "S07":
                    //Valor da Base de Cálculo da COFINS e Alíquota da COFINS
                    //(em percentual) 0 ou 1 [COFINSOutr]
                    // todos esses campos sao obrigatorios
                    //S07|CST|pCOFINS|
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $COFINSOutr->insertBefore($vBC, $vCOFINS);
                    $pCOFINS = $dom->createElement("pCOFINS", $dados[2]);
                    $COFINSOutr->insertBefore($pCOFINS, $vCOFINS);
                    break;
                case "S09": //Quantidade Vendida e Alíquota da COFINS (em reais) 0 ou 1 [COFINSOutr]
                    // todos esses campos sao obrigatorios
                    //S09|qBCProd|vAliqProd|
                    $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                    $COFINSOutr->insertBefore($qBCProd, $vCOFINS);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                    $COFINSOutr->insertBefore($vAliqProd, $vCOFINS);
                    break;
                case "T":
                    //Grupo de COFINS Substituição Tributária 0 ou 1 [imposto]
                    // todos esses campos sao obrigatorios
                    //T|vCOFINS|
                    $COFINSST = $dom->createElement("COFINSST");
                    $vCOFINS = $dom->createElement("vCOFINS", $dados[1]);
                    $COFINSST->appendChild($vCOFINS);
                    $imposto->appendChild($COFINSST);
                    break;
                case "T02":
                    //Valor da Base de Cálculo da COFINS e Alíquota da COFINS
                    //(em percentual) 0 ou 1 [COFINSST]
                    // todos esses campos sao obrigatorios
                    //T02|vBC|pCOFINS|
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $COFINSST->insertBefore($vBC, $vCOFINS);
                    $pCOFINS = $dom->createElement("pCOFINS", $dados[2]);
                    $COFINSST->insertBefore($pCOFINS, $vCOFINS);
                    break;
                case "T04":
                    //Quantidade Vendida e Alíquota da COFINS (em reais) 0 u 1 [COFINSST]
                    // todos esses campos sao obrigatorios
                    //T04|qBCProd|vAliqProd|
                    $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                    $COFINSST->appendChild($qBCProd);
                    $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                    $COFINSST->appendChild($vAliqProd);
                    break;
                case "U":
                    //Grupo do ISSQN 0 ou 1 [imposto]
                    // todos esses campos sao obrigatorios
                    //U|vBC|vAliq|vISSQN|cMunFG|cListServ|vDeducao|vOutro|vDescIncond|vDescCond|vISSRet|
                    $ISSQN = $dom->createElement("ISSQN");
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $ISSQN->appendChild($vBC);
                    $vAliq = $dom->createElement("vAliq", $dados[2]);
                    $ISSQN->appendChild($vAliq);
                    $vISSQN = $dom->createElement("vISSQN", $dados[3]);
                    $ISSQN->appendChild($vISSQN);
                    $cMunFG = $dom->createElement("cMunFG", $dados[4]);
                    $ISSQN->appendChild($cMunFG);
                    $cListServ = $dom->createElement("cListServ", $dados[5]);
                    $ISSQN->appendChild($cListServ);
                    $vDeducao = $dom->createElement("vDeducao", $dados[6]);
                    $ISSQN->appendChild($vDeducao);
                    $vOutro = $dom->createElement("vOutro", $dados[7]);
                    $ISSQN->appendChild($vOutro);
                    $vDescIncond = $dom->createElement("vDescIncond", $dados[8]);
                    $ISSQN->appendChild($vDescIncond);
                    $vDescCond = $dom->createElement("vDescCond", $dados[9]);
                    $ISSQN->appendChild($vDescCond);
                    $vISSRet = $dom->createElement("vISSRet", $dados[10]);
                    $ISSQN->appendChild($vISSRet);
                    $imposto->appendChild($ISSQN);
                    break;
                case "UA":
                    //Grupo de Tributos Devolvidos 0 ou 1 [det]
                    // campos [2-4] sao obrigatorios. ($dados[1] ficou "perdido", pois é o grupo "impostoDevol" - nao enviar nada).
                    //UA|impostoDevol|pDevol|IPI|vIPIDevol|
                    $impostoDevol = $dom->createElement("impostoDevol");
                    //Motivo da devolucao deve ir em "infAdProd"
                    $pDevol = $dom->createElement("pDevol",$dados[2]);
                    $impostoDevol->appendChild($pDevol);
                    $IPI = $dom->createElement("IPI",$dados[3]);
                    $impostoDevol->appendChild($IPI);
                    $vIPIDevol = $dom->createElement("vIPIDevol",$dados[4]);
                    $impostoDevol->appendChild($vIPIDevol);
                    $det->appendChild($impostoDevol);
                    break;
                case "W":
                    // Grupo de Valores Totais da NF-e obrigatorio [infNFe]
                    //W|
                    $total = $dom->createElement("total");
                    $infNFe->appendChild($total);
                    break;
                case "W02":
                    //Grupo de Valores Totais referentes ao ICMS obrigatorio [total]
                    // todos esses campos sao obrigatorios
                    //W02|vBC|vICMS|vICMSDeson|vBCST|vST|vProd|vFrete|vSeg|vDesc|vII|vIPI|vPIS|vCOFINS|vOutro|vNF|vTotTrib|
                    $ICMSTot = $dom->createElement("ICMSTot");
                    $vBC = $dom->createElement("vBC", $dados[1]);
                    $ICMSTot->appendChild($vBC);
                    $vICMS = $dom->createElement("vICMS", $dados[2]);
                    $ICMSTot->appendChild($vICMS);
                    $vICMSDeson = $dom->createElement("vICMSDeson", $dados[3]);
                    $ICMSTot->appendChild($vICMSDeson);
                    $vBCST = $dom->createElement("vBCST", $dados[4]);
                    $ICMSTot->appendChild($vBCST);
                    $vST = $dom->createElement("vST", $dados[5]);
                    $ICMSTot->appendChild($vST);
                    $vProd = $dom->createElement("vProd", $dados[6]);
                    $ICMSTot->appendChild($vProd);
                    $vFrete = $dom->createElement("vFrete", $dados[7]);
                    $ICMSTot->appendChild($vFrete);
                    $vSeg = $dom->createElement("vSeg", $dados[8]);
                    $ICMSTot->appendChild($vSeg);
                    $vDesc = $dom->createElement("vDesc", $dados[9]);
                    $ICMSTot->appendChild($vDesc);
                    $vII = $dom->createElement("vII", $dados[10]);
                    $ICMSTot->appendChild($vII);
                    $vIPI = $dom->createElement("vIPI", $dados[11]);
                    $ICMSTot->appendChild($vIPI);
                    $vPIS = $dom->createElement("vPIS", $dados[12]);
                    $ICMSTot->appendChild($vPIS);
                    $vCOFINS = $dom->createElement("vCOFINS", $dados[13]);
                    $ICMSTot->appendChild($vCOFINS);
                    $vOutro = $dom->createElement("vOutro", $dados[14]);
                    $ICMSTot->appendChild($vOutro);
                    $vNF = $dom->createElement("vNF", $dados[15]);
                    $ICMSTot->appendChild($vNF);
                    //lei da transparencia 12.741/12
                    //Nota Técnica 2013/003
                    $vTotTrib=trim($dados[16]);
                    if (strlen($vTotTrib)>0) {
                        $vTotTrib = $dom->createElement("vTotTrib", $vTotTrib);
                        $ICMSTot->appendChild($vTotTrib);
                    }
                    unset($vTotTrib);
                    $total->appendChild($ICMSTot);
                    break;
                case "W17":
                    // Grupo de Valores Totais referentes ao ISSQN 0 ou 1 [total] (Apenas "dCompet=data do serviço" obrigatório)
                    //W17|vServ|vBC|vISS|vPIS|vCOFINS|dCompet|vDeducao|vOutro|vDescIncond|vDescCond|vISSRet|cRegTrib|
                    $ISSQNtot = $dom->createElement("ISSQNtot");
                    if (!empty($dados[1])) {
                        $vServ = $dom->createElement("vServ", $dados[1]);
                        $ISSQNtot->appendChild($vServ);
                    }
                    if (!empty($dados[2])) {
                        $vBC = $dom->createElement("vBC", $dados[2]);
                        $ISSQNtot->appendChild($vBC);
                    }
                    if (!empty($dados[3])) {
                        $vISS = $dom->createElement("vISS", $dados[3]);
                        $ISSQNtot->appendChild($vISS);
                    }
                    if (!empty($dados[4])) {
                        $vPIS = $dom->createElement("vPIS", $dados[4]);
                        $ISSQNtot->appendChild($vPIS);
                    }
                    if (!empty($dados[5])) {
                        $vCOFINS = $dom->createElement("vCOFINS", $dados[5]);
                        $ISSQNtot->appendChild($vCOFINS);
                    }
                    $dCompet = $dom->createElement("dCompet", $dados[6]);
                    $ISSQNtot->appendChild($dCompet);
                    if (!empty($dados[7])) {
                        $vDeducao = $dom->createElement("vDeducao", $dados[7]);
                        $ISSQNtot->appendChild($vDeducao);
                    }
                    if (!empty($dados[8])) {
                        $vOutro = $dom->createElement("vOutro", $dados[8]);
                        $ISSQNtot->appendChild($vOutro);
                    }
                    if (!empty($dados[9])) {
                        $vDescIncond = $dom->createElement("vDescIncond", $dados[9]);
                        $ISSQNtot->appendChild($vDescIncond);
                    }
                    if (!empty($dados[10])) {
                        $vDescCond = $dom->createElement("vDescCond", $dados[10]);
                        $ISSQNtot->appendChild($vDescCond);
                    }
                    if (!empty($dados[11])) {
                        $vISSRet = $dom->createElement("vISSRet", $dados[11]);
                        $ISSQNtot->appendChild($vISSRet);
                    }
                    if (!empty($dados[12])) {
                        $cRegTrib = $dom->createElement("cRegTrib", $dados[12]);
                        $ISSQNtot->appendChild($cRegTrib);
                    }
                    $total->appendChild($ISSQNtot);
                    break;
                case "W23": //Grupo de Retenções de Tributos 0 ou 1 [total]
                    //W23|vRetPIS|vRetCOFINS|vRetCSLL|vBCIRRF|vIRRF|vBCRetPrev|vRetPrev|
                    $retTrib = $dom->createElement("retTrib");
                    if (!empty($dados[1])) {
                        $vRetPIS = $dom->createElement("vRetPIS", $dados[1]);
                        $retTrib->appendChild($vRetPIS);
                    }
                    if (!empty($dados[2])) {
                        $vRetCOFINS = $dom->createElement("vRetCOFINS", $dados[2]);
                        $retTrib->appendChild($vRetCOFINS);
                    }
                    if (!empty($dados[3])) {
                        $vRetCSLL = $dom->createElement("vRetCSLL", $dados[3]);
                        $retTrib->appendChild($vRetCSLL);
                    }
                    if (!empty($dados[4])) {
                        $vBCIRRF = $dom->createElement("vBCIRRF", $dados[4]);
                        $retTrib->appendChild($vBCIRRF);
                    }
                    if (!empty($dados[5])) {
                        $vIRRF = $dom->createElement("vIRRF", $dados[5]);
                        $retTrib->appendChild($vIRRF);
                    }
                    if (!empty($dados[6])) {
                        $vBCRetPrev = $dom->createElement("vBCRetPrev", $dados[6]);
                        $retTrib->appendChild($vBCRetPrev);
                    }
                    if (!empty($dados[7])) {
                        $vRetPrev = $dom->createElement("vRetPrev", $dados[7]);
                        $retTrib->appendChild($vRetPrev);
                    }
                    $total->appendChild($retTrib);
                    break;
                case "X":
                    // Grupo de Informações do Transporte da NF-e obrigatorio [infNFe]
                    // todos esses campos são obrigatórios
                    //X|modFrete|
                    $transp = $dom->createElement("transp");
                    $modFrete = $dom->createElement("modFrete", $dados[1]);
                    $transp->appendChild($modFrete);
                    $infNFe->appendChild($transp);
                    break;
                case "X03":
                    //Grupo Transportador 0 ou 1 [transp]
                    //X03|xNome|IE|xEnder|xMun|UF|
                    $transporta = $dom->createElement("transporta");
                    if (!empty($dados[1])) {
                        $xNome = $dom->createElement("xNome", $dados[1]);
                        $transporta->appendChild($xNome);
                    }
                    if (!empty($dados[2])) {
                        $IE = $dom->createElement("IE", $dados[2]);
                        $transporta->appendChild($IE);
                    }
                    if (!empty($dados[3])) {
                        $xEnder = $dom->createElement("xEnder", $dados[3]);
                        $transporta->appendChild($xEnder);
                    }
                    if (!empty($dados[4])) {
                        $xMun = $dom->createElement("xMun", $dados[4]);
                        $transporta->appendChild($xMun);
                    }
                    if (!empty($dados[5])) {
                        $UF = $dom->createElement("UF", $dados[5]);
                        $transporta->appendChild($UF);
                    }
                    $transp->appendChild($transporta);
                    break;
                case "X04":
                    //CNPJ 0 ou 1 [transporta]
                    //X04|CNPJ|
                    if (!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $transporta->insertBefore($transporta->appendChild($CNPJ), $xNome);
                    }
                    break;
                case "X05":
                    //CPF 0 ou 1 [transporta]
                    //X05|CPF|
                    if (!empty($dados[1])) {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $transporta->insertBefore($transporta->appendChild($CPF), $xNome);
                    }
                    break;
                case "X11":
                    //Grupo de Retenção do ICMS do transporte 0 ou 1 [transp]
                    // todos esses campos são obrigatórios
                    //X11|vServ|vBCRet|pICMSRet|vICMSRet|CFOP|cMunFG|
                    $retTransp = $dom->createElement("retTransp");
                    $vServ = $dom->createElement("vServ", $dados[1]);
                    $retTransp->appendChild($vServ);
                    $vBCRet = $dom->createElement("vBCRet", $dados[2]);
                    $retTransp->appendChild($vBCRet);
                    $pICMSRet = $dom->createElement("pICMSRet", $dados[3]);
                    $retTransp->appendChild($pICMSRet);
                    $vICMSRet = $dom->createElement("vICMSRet", $dados[4]);
                    $retTransp->appendChild($vICMSRet);
                    $CFOP = $dom->createElement("CFOP", $dados[5]);
                    $retTransp->appendChild($CFOP);
                    $cMunFG = $dom->createElement("cMunFG", $dados[6]);
                    $retTransp->appendChild($cMunFG);
                    $transp->appendChild($retTransp);
                    break;
                case "X18":
                    //Grupo Veículo 0 ou 1 [transp]
                    //X18|placa|UF|RNTC|
                    if (!empty($dados[1])) {
                        $veicTransp = $dom->createElement("veicTransp");
                        $placa = $dom->createElement("placa", $dados[1]);
                        $veicTransp->appendChild($placa);
                        $UF = $dom->createElement("UF", $dados[2]);
                        $veicTransp->appendChild($UF);
                        if (!empty($dados[3])) {
                            $RNTC = $dom->createElement("RNTC", $dados[3]);
                            $veicTransp->appendChild($RNTC);
                        }
                        $transp->appendChild($veicTransp);
                    }
                    break;
                case "X22":
                    //Grupo Reboque 0 a 5 [transp]
                    //X22|placa|UF|RNTC|vagao|balsa|
                    $reboque = $dom->createElement("reboque");
                    $placa = $dom->createElement("placa", $dados[1]);
                    $reboque->appendChild($placa);
                    $UF = $dom->createElement("UF", $dados[2]);
                    $reboque->appendChild($UF);
                    if (!empty($dados[3])) {
                        $RNTC = $dom->createElement("RNTC", $dados[3]);
                        $reboque->appendChild($RNTC);
                    }
                    if (!empty($dados[4])) {
                        $vagao = $dom->createElement("vagao", $dados[4]);
                        $reboque->appendChild($vagao);
                    }
                    if (!empty($dados[5])) {
                        $balsa = $dom->createElement("balsa", $dados[5]);
                        $reboque->appendChild($balsa);
                    }
                    $transp->appendChild($reboque);
                    break;
                case "X26":
                    //Grupo Volumes 0 a N [transp]
                    //X26|qVol|esp|marca|nVol|pesoL|pesoB|
                    if (!empty($dados[1])) {
                        $vol = $dom->createElement("vol");
                        $qVol = $dom->createElement("qVol", $dados[1]);
                        $vol->appendChild($qVol);

                        if (!empty($dados[2])) {
                            $esp = $dom->createElement("esp", $dados[2]);
                            $vol->appendChild($esp);
                        }
                        if (!empty($dados[3])) {
                            $marca = $dom->createElement("marca", $dados[3]);
                            $vol->appendChild($marca);
                        }
                        if (!empty($dados[4])) {
                            $nVol = $dom->createElement("nVol", $dados[4]);
                            $vol->appendChild($nVol);
                        }
                        if (!empty($dados[5])) {
                            $pesoL = $dom->createElement("pesoL", $dados[5]);
                            $vol->appendChild($pesoL);
                        }
                        if (!empty($dados[6])) {
                            $pesoB = $dom->createElement("pesoB", $dados[6]);
                            $vol->appendChild($pesoB);
                        }
                        $transp->appendChild($vol);
                    }
                    break;
                case "X33":
                    //Grupo de Lacres 0 a N [vol]
                    //todos os campos são obrigatorios
                    //X33|nLacre|
                    $lacres = $dom->createElement("lacres");
                    $nLacre = $dom->createElement("nLacre", $dados[1]);
                    $lacres->appendChild($nLacre);
                    $vol->appendChild($lacres);
                    break;
                case "Y":
                    //Grupo de Cobrança 0 ou 1 [infNFe]
                    $cobr = $dom->createElement("cobr");
                    $infNFe->appendChild($cobr);
                    break;
                case "Y02":
                    //Grupo da Fatura 0 ou 1 [cobr]
                    //Y02|nFat|vOrig|vDesc|vLiq|
                    if (!isset($cobr)) {
                        $cobr = $dom->createElement("cobr");
                        $infNFe->appendChild($cobr);
                    }
                    $fat = $dom->createElement("fat");
                    if (!empty($dados[1])) {
                        $nFat = $dom->createElement("nFat", $dados[1]);
                        $fat->appendChild($nFat);
                    }
                    if (!empty($dados[2])) {
                        $vOrig = $dom->createElement("vOrig", $dados[2]);
                        $fat->appendChild($vOrig);
                    }
                    if (!empty($dados[3])) {
                        $vDesc = $dom->createElement("vDesc", $dados[3]);
                        $fat->appendChild($vDesc);
                    }
                    if (!empty($dados[4])) {
                        $vLiq = $dom->createElement("vLiq", $dados[4]);
                        $fat->appendChild($vLiq);
                    }
                    $cobr->appendChild($fat);
                    break;
                case "Y07":
                    //Grupo da Duplicata 0 a N [cobr]
                    //Y07|nDup|dVenc|vDup|
                    if (!isset($cobr)) {
                        $cobr = $dom->createElement("cobr");
                        $infNFe->appendChild($cobr);
                    }
                    $dup = $dom->createElement("dup");
                    if (!empty($dados[1])) {
                        $nDup = $dom->createElement("nDup", $dados[1]);
                        $dup->appendChild($nDup);
                    }
                    if (!empty($dados[2])) {
                        $dVenc = $dom->createElement("dVenc", $dados[2]);
                        $dup->appendChild($dVenc);
                    }
                    if (!empty($dados[3])) {
                        $vDup = $dom->createElement("vDup", $dados[3]);
                        $dup->appendChild($vDup);
                    }
                    $cobr->appendChild($dup);
                    break;
                case "YA":
                    //Grupo de Formas de Pagamento 0 - 100 [infNFe]
                    //Obrigatório para NFC-e, a critério da UF. Não informar para NF-e.
                    //YA|tPag|vPag|card|CNPJ|tBand|cAut
                    $pag = $dom->createElement("pag");
                    $tPag = $dom->createElement("tPag",$dados[1]);
                    $pag->appendChild($tPag);
                    $vPag = $dom->createElement("vPag",$dados[2]);
                    $pag->appendChild($vPag);
                    //$dados[3] também é um grupo, assim como em "UA" o $dados[1] era o grupo. 
                    //Verifiquem se EU estou com o conceito errado, ou se o manual do Emissor está "disperdiçando" campos.
                    if(!empty($dados[4])){
                      $card = $dom->createElement("card");
                      $CNPJ = $dom->createElement("CNPJ",$dados[4]);
                      $card->appendChild($CNPJ);
                      $tBand = $dom->createElement("tBand",$dados[5]);
                      $card->appendChild($tBand);
                      $cAut = $dom->createElement("cAut",$dados[6]);
                      $card->appendChild($cAut);
                      $pag->appendChild($card);
                    }
                    $infNFe->appendChild($pag);
                    break;
                case "Z":
                    //Grupo de Informações Adicionais 0 ou 1 [infNFe]
                    //Z|infAdFisco|infCpl|
                    $infAdic = $dom->createElement("infAdic");
                    if (!empty($dados[1])) {
                        $infAdFisco = $dom->createElement("infAdFisco", $dados[1]);
                        $infAdic->appendChild($infAdFisco);
                    }
                    if (!empty($dados[2])) {
                        $infCpl = $dom->createElement("infCpl", $dados[2]);
                        $infAdic->appendChild($infCpl);
                    }
                    $infNFe->appendChild($infAdic);
                    break;
                case "Z04":
                    //Grupo do campo de uso livre do contribuinte 0-10 [infAdic]
                    //todos os campos são obrigatorios
                    //Z04|xCampo|xTexto|
                    $obsCont = $dom->createElement("obsCont");
                    $obsCont->setAttribute("xCampo", $dados[1]);
                    $xTexto = $dom->createElement("xTexto", $dados[2]);
                    $obsCont->appendChild($xTexto);
                    $infAdic->appendChild($obsCont);
                    break;
                case "Z07":
                    //Grupo do campo de uso livre do Fisco 0-10 [infAdic]
                    //todos os campos são obrigatorios
                    //Z07|xCampo|xTexto|
                    $obsFisco = $dom->createElement("obsFisco");
                    $obsFisco->setAttribute("xCampo", $dados[1]);
                    $xTexto = $dom->createElement("xTexto", $dados[2]);
                    $obsFisco->appendChild($xTexto);
                    $infAdic->appendChild($obsFisco);
                    break;
                case "Z10":
                    //Grupo do processo referenciado 0 ou N [infAdic]
                    //todos os campos são obrigatorios
                    //Z10|nProc|indProc|
                    $procRef = $dom->createElement("procRef");
                    $nProc = $dom->createElement("nProc", $dados[1]);
                    $procRef->appendChild($nProc);
                    $procRef = $dom->createElement("indProc", $dados[2]);
                    $procRef->appendChild($indProc);
                    $infAdic->appendChild($proRef);
                    break;
                case "ZA":
                    //Grupo de Exportação 0 ou 1 [infNFe]
                    //todos os campos são obrigatorios
                    //ZA|UFSaidaPais|xLocExporta|xLocDespacho|
                    $exporta = $dom->createElement("exporta");
                    $UFSaidaPais = $dom->createElement("UFSaidaPais", $dados[1]);
                    $exporta->appendChild($UFSaidaPais);
                    $xLocExporta = $dom->createElement("xLocExporta", $dados[2]);
                    $exporta->appendChild($xLocExporta);
                    $xLocDespacho = $dom->createElement("xLocDespacho", $dados[3]);
                    $exporta->appendChild($xLocDespacho);
                    $infNFe->appendChild($exporta);
                    break;
                case "ZB":
                    //Grupo de Compra 0 ou 1 [infNFe]
                    //ZB|xNEmp|xPed|xCont|
                    $compra = $dom->createElement("compra");
                    if (!empty($dados[1])) {
                        $xNEmp = $dom->createElement("xNEmp", $dados[1]);
                        $compra->appendChild($xNEmp);
                    }
                    if (!empty($dados[2])) {
                        $xPed = $dom->createElement("xPed", $dados[2]);
                        $compra->appendChild($xPed);
                    }
                    if (!empty($dados[3])) {
                        $xCont = $dom->createElement("xCont", $dados[3]);
                        $compra->appendChild($xCont);
                    }
                    $infNFe->appendChild($compra);
                    break;
                case "ZC01":
                    //0 ou 1 Grupo de Cana [infNFe]
                    //todos os campos são obrigatorios
                    //ZC01|safra|ref|qTotMes|qTotAnt|qTotGer|vFor|vTotDed|vLiqFor|
                    $cana = $dom->createElement("cana");
                    $safra = $dom->createElement("safra", $dados[1]);
                    $cana->appendChild($safra);
                    $ref = $dom->createElement("ref", $dados[2]);
                    $cana->appendChild($ref);
                    $qTotMes = $dom->createElement("qTotMes", $dados[3]);
                    $cana->appendChild($qTotMes);
                    $qTotAnt = $dom->createElement("qTotAnt", $dados[4]);
                    $cana->appendChild($qTotAnt);
                    $qTotGer = $dom->createElement("qTotGer", $dados[5]);
                    $cana->appendChild($qTotGer);
                    $vFor = $dom->createElement("vFor", $dados[6]);
                    $cana->appendChild($vFor);
                    $vTotDed = $dom->createElement("vTotDed", $dados[7]);
                    $cana->appendChild($vTotDed);
                    $vLiqFor = $dom->createElement("vLiqFor", $dados[8]);
                    $cana->appendChild($vLiqFor);
                    $infNFe->appendChild($cana);
                    break;
                case "ZC04":
                    //1 a 31 Grupo de Fornecimento diário de cana [cana]
                    //ZC04|dia|qtde|
                    //todos os campos são obrigatorios
                    $forDia = $dom->createElement("forDia");
                    $dia = $dom->createElement("dia", $dados[1]);
                    $forDia->appendChild($dia);
                    $qtde = $dom->createElement("qtde", $dados[2]);
                    $forDia->appendChild($qtde);
                    $cana->appendChild($forDia);
                    break;
                case "ZC10":
                    //0 a 10 Grupo de Deduções ? Taxas e Contribuições [cana]
                    //ZC10|xDed|vDed|
                    //todos os campos são obrigatorios
                    $deduc = $dom->createElement("deduc");
                    $xDed = $dom->createElement("xDed", $dados[1]);
                    $deduc->appendChild($xDed);
                    $vDed = $dom->createElement("vDed", $dados[2]);
                    $deduc->appendChild($vDed);
                    $cana->appendChild($deduc);
                    break;
            } //end switch
        } //end for
        $arquivos_xml = array();
        foreach ($notas as $nota) {
            unset($dom, $NFe, $infNFe);
            $dom = $nota['dom'];
            $NFe = $nota['NFe'];
            $infNFe = $nota['infNFe'];
            $NFref = $nota['NFref'];
            $this->chave = $nota['chave'];
            $this->tpAmb = $nota['tpAmb'];
            $this->xml = '';
            //salva o xml na variável se o txt não estiver em branco
            if (!empty($infNFe)) {
                $NFe->appendChild($infNFe);
                $dom->appendChild($NFe);
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
     * preservando apenas letras de A-Z numeros de 0-9 e 
     * os caracteres @ , - ; $ % : / _
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
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;$%:\/_]/", "", $novoTexto);
        return $novoTexto;
    } //fim limpaString

    /**
     * calculaDV
     * Função para o calculo o digito verificador da chave da NFe
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
     * Monta a chave da NFe de 44 digitos com base em seus dados
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
        $dhEmi = $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue;
        $cnpj = $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $nNF = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
        $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
        $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue;
        $cDV = $ide->getElementsByTagName('cDV')->item(0)->nodeValue;
        $tempData = $dt = explode("-", $dhEmi);
        $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d";
        $chaveMontada = sprintf(
            $forma,
            $cUF,
            $tempData[0] - 2000,
            $tempData[1],
            $cnpj,
            $mod,
            $serie,
            $nNF,
            $tpEmis,
            $cNF
        );
        $chaveMontada .= $this->calculaDV($chaveMontada);
        //caso a chave contida na NFe esteja errada
        //remontar a chave
        if ($chaveMontada != $this->chave) {
            if (strlen($cNF) != 8) {
                $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue = rand(10000001, 99999999);
            }
            $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d";
            $tempChave = sprintf(
                $forma,
                $cUF,
                $tempData[0] - 2000,
                $tempData[1],
                $cnpj,
                $mod,
                $serie,
                $nNF,
                $tpEmis,
                $cNF
            );
            $cDV = $ide->getElementsByTagName('cDV')->item(0)->nodeValue = $this->calculaDV($tempChave);
            $this->chave = $tempChave .= $cDV;
            $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
            $infNFe->setAttribute("Id", "NFe" . $this->chave);
        }
    } //fim calculaChave
}//fim da classe
