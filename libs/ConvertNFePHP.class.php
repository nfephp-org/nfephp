<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 *
 * @package     NFePHP
 * @name        ConvertNFePHP
 * @version     0.1
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright   2009 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <roberto.machado@superig.com.br>
 */

class ConvertNFePHP {


    /**
     * xml
     * @var string XML da NFe
     */
    var $xml;

    /**
     * chave
     * @var string ID da NFe 44 digitos
     */
    var $chave='';

    /**
     * txt
     * @var string TXT com NFe
     */
    var $txt='';

    function __construct(){

    }




    /**
     * nfetxt2xml
     * Método de conversão das NFe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas Versão 1.1.1 (29/10/2008)
     * Referente ao modelo de NFe contido na versão 3
     * do manual de integração da NFe (Março 2009)
     *
     * @param string $arq Path par a o arquivo txt
     * @return string xml construido
     */
    function nfetxt2xml($arq){

        if ( !is_file($arq) ){
            return FALSE;
        }
        //carregar o conteúdo do arquivo txt em uma string
        $arquivo = file($arq);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $NFe = $dom->createElement("NFe");
        $NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");

        //lê linha por linha do arquivo txt
        for($l = 0; $l < count($arquivo); $l++) {
            //separa os elementos do arquivo txt usando o pipe "|"
            $dados = explode("|",$arquivo[$l]);
            //monta o dado conforme o tipo
            switch (strtoupper(trim($dados[0]))) {
                case "NOTA FISCAL": // primeiro elemento não faz nada aqui é informado o número de NF do TXT
                    break;
                case "A":  //atributos da NFe
                    $infNFe = $dom->createElement("infNFe");
                    $infNFe->setAttribute("versao", trim($dados[1]));
                    $infNFe->setAttribute("Id", trim($dados[2]));
                    $this->chave = substr(trim($dados[2]),-44);
                    break;

                case "B"://identificadores
                    $B = $dom->createElement("ide");
                    if(!$this->vazio($dados[1])) {
                        $cUF = $dom->createElement("cUF", trim($dados[1]));
                        $B->appendChild($cUF);
                    }
                    if(!$this->vazio($dados[2])) {
                        $cNF = $dom->createElement("cNF", trim($dados[2]));
                        $B->appendChild($cNF);
                    }
                    if(!$this->vazio($dados[3])) {
                        $NatOp = $dom->createElement("natOp", trim($dados[3]));
                        $B->appendChild($NatOp);
                    }
                    if(!$this->vazio($dados[4])) {
                        $indPag = $dom->createElement("indPag", trim($dados[4]));
                        $B->appendChild($indPag);
                    }
                    if(!$this->vazio($dados[5])) {
                        $mod = $dom->createElement("mod", trim($dados[5]));
                        $B->appendChild($mod);
                    }
                    if(!$this->vazio($dados[6])) {
                        $serie = $dom->createElement("serie", trim($dados[6]));
                        $B->appendChild($serie);
                    }
                    if(!$this->vazio($dados[7])) {
                        $nNF = $dom->createElement("nNF", trim($dados[7]));
                        $B->appendChild($nNF);
                    }
                    if(!$this->vazio($dados[8])) {
                        $dEmi = $dom->createElement("dEmi", trim($dados[8]));
                        $B->appendChild($dEmi);
                    }
                    if(!$this->vazio($dados[9])) {
                        $dSaiEnt = $dom->createElement("dSaiEnt", trim($dados[9]));
                        $B->appendChild($dSaiEnt);
                    }
                    if(!$this->vazio($dados[10])) {
                        $tpNF = $dom->createElement("tpNF", trim($dados[10]));
                        $B->appendChild($tpNF);
                    }
                    if(!$this->vazio($dados[11])) {
                        $cMunFG = $dom->createElement("cMunFG", trim($dados[11]));
                        $B->appendChild($cMunFG);
                    }
                    if(!$this->vazio($dados[12])) {
                        $tpImp = $dom->createElement("tpImp", trim($dados[12]));
                        $B->appendChild($tpImp);
                    }
                    if(!$this->vazio($dados[13])) {
                       $tpEmis = $dom->createElement("tpEmis", trim($dados[13]));
                       $B->appendChild($tpEmis);
                    }
                    if(!$this->vazio($dados[14])) {
                        $CDV = $dom->createElement("cDV", trim($dados[14]));
                        $B->appendChild($CDV);
                    }
                    if(!$this->vazio($dados[15])) {
                        $tpAmb = $dom->createElement("tpAmb", trim($dados[15]));
                        $B->appendChild($tpAmb);
                    }
                    $finNFe = $dom->createElement("finNFe", trim($dados[16]));
                    $B->appendChild($finNFe);
                    if(!$this->vazio($dados[17])) {
                        $procEmi = $dom->createElement("procEmi", trim($dados[17]));
                        $B->appendChild($procEmi);
                    }
                    if(!$this->vazio($dados[18])) {
                        $verProc = $dom->createElement("verProc", trim($dados[18]));
                        $B->appendChild($verProc);
                    }
                    $infNFe->appendChild($B);
                    break;

                case "B13": //NFe referenciadas
                    $B13 = $dom->createElement("refNFe");
                    if(!$this->vazio($dados[1])) {
                       $refNFe = $dom->createElement("refNFe", trim($dados[1]));
                       $B13->appendChild($refNFe);
                    }
                    $ide->appendChild($B13);
                    break;

                case "B14": //NF referenciadas
                    $B14 = $dom->createElement("refNf");
                    if(!$this->vazio($dados[1])) {
                        $cUF = $dom->createElement("cUF", trim($dados[1]));
                        $B14->appendChild($cUF);
                    }
                    if(!$this->vazio($dados[2])) {
                        $AAMM = $dom->createElement("AAMM", trim($dados[2]));
                        $B14->appendChild($AAMM);
                    }
                    if(!$this->vazio($dados[3])) {
                        $CNPJ = $dom->createElement("CNPJ", trim($dados[1]));
                        $B14->appendChild($CNPJ);
                    }
                    if(!$this->vazio($dados[4])) {
                        $mod = $dom->createElement("mod", trim($dados[4]));
                        $B14->appendChild($mod);
                    }
                    if(!$this->vazio($dados[5])) {
                        $serie = $dom->createElement("serie", trim($dados[1]));
                        $B14->appendChild($serie);
                    }
                    if(!$this->vazio($dados[6])) {
                        $nNF = $dom->createElement("nNF", trim($dados[1]));
                        $B14->appendChild($nNF);
                    }
                    $ide->appendChild($B14);
                        break;

                case "C": //dados do emitente
                    $C = $dom->createElement("emit");
                    if(!$this->vazio($dados[1])) {
                        $xNome = $dom->createElement("xNome", trim($dados[1]));
                        $C->appendChild($xNome);
                     }
                     if(!$this->vazio($dados[2])) {
                        $xFant = $dom->createElement("xFant", trim($dados[2]));
                        $C->appendChild($xFant);
                     }
                        if(!$this->vazio($dados[4])) {
                            $IEST = $dom->createElement("IEST", trim($dados[4]));
                            $C->appendChild($IEST);

                        }
                        if(!$this->vazio($dados[5])) {
                            $IM = $dom->createElement("IM", trim($dados[5]));
                            $C->appendChild($IM);

                        }
                        if(!$this->vazio($dados[6])) {
                            $cnae = $dom->createElement("CNAE", trim($dados[6]));
                            $C->appendChild($cnae);

                        }
                        if(!$this->vazio($dados[3])) {
                            $IE = $dom->createElement("IE", trim($dados[3]));
                        }
                        $infNFe->appendChild($C);
                        break;
                    case "C02":
                        if(!$this->vazio($dados[1])) {
                            $cnpj = $dom->createElement("CNPJ", trim($dados[1]));
                            $C->appendChild($cnpj);
                        }
                        $infNFe->appendChild($C);
                        break;
                    case "C02a":
                        if(!$this->vazio($dados[1])) {
                            $cpf = $dom->createElement("CPF", trim($dados[1]));
                            $C->appendChild($cpf);
                        }
                        $infNFe->appendChild($C);
                        break;
                    case "C05"://ENDERECO DO EMITENTE
                        if(isset($xNome))
                            $C->appendChild($xNome);
                        if(isset($xFant))
                            $C->appendChild($xFant);
                        if(isset($im))
                            $C->appendChild($im);
                        if(isset($cnae))
                            $C->appendChild($cnae);

                        $enderEmi = $dom->createElement("enderEmit");
                        if(!$this->vazio($dados[1])) {
                            $xLgr = $dom->createElement("xLgr", trim($dados[1]));
                            $enderEmi->appendChild($xLgr);
                        }
                        if(!$this->vazio($dados[2])) {
                            $nro = $dom->createElement("nro", trim($dados[2]));
                            $enderEmi->appendChild($nro);
                        }
                        if(!$this->vazio($dados[4])) {
                            $xBairro = $dom->createElement("xBairro", trim($dados[4]));
                            $enderEmi->appendChild($xBairro);
                        }
                        if(!$this->vazio($dados[5])) {
                            $cMun = $dom->createElement("cMun", trim($dados[5]));
                            $enderEmi->appendChild($cMun);
                        }
                        if(!$this->vazio($dados[6])) {
                            $xMun = $dom->createElement("xMun", trim($dados[6]));
                            $enderEmi->appendChild($xMun);
                        }
                        if(!$this->vazio($dados[7])) {
                            $UF = $dom->createElement("UF", trim($dados[7]));
                            $enderEmi->appendChild($UF);
                        }
                        if(!$this->vazio($dados[8])) {
                            $CEP = $dom->createElement("CEP", trim($dados[8]));
                            $enderEmi->appendChild($CEP);
                        }
                        if(!$this->vazio($dados[9])) {
                            $cPais = $dom->createElement("cPais", trim($dados[9]));
                            $enderEmi->appendChild($cPais);
                        }
                        if(!$this->vazio($dados[10])) {
                            $xPais = $dom->createElement("xPais", trim($dados[10]));
                            $enderEmi->appendChild($xPais);
                        }
                        if(!$this->vazio($dados[11])) {
                            $fone = $dom->createElement("fone", trim($dados[11]));
                            $enderEmi->appendChild($fone);
                        }
                        $C->appendChild($enderEmi);
                        if(isset($IE))
                            $C->appendChild($IE);
                        if(isset($iest))
                            $C->appendChild($iest);
                        $infNFe->appendChild($C);
                        break;
                    case "E":   //DESTINATARIO
                        $E = $dom->createElement("dest");
                        if(!$this->vazio($dados[1])) {
                            $xNome = $dom->createElement("xNome", trim($dados[1]));
                            $E->appendChild($xNome);
                        }
                        if(!$this->vazio($dados[2])) {
                            $IE = $dom->createElement("IE", trim($dados[2]));
                            $E->appendChild($IE);
                        }
                        if(!$this->vazio($dados[3])) {
                            $ISUF = $dom->createElement("ISUF", trim($dados[3]));
                            $E->appendChild($ISUF);
                        }
                        break;
                    case "E02": //CASO DESTINATARIO PJ
                        if(!$this->vazio($dados[1])) {
                            $CNPJ = $dom->createElement("CNPJ", trim($dados[1]));
                            $E->appendChild($CNPJ);
                        }
                        $infNFe->appendChild($E);
                        break;
                    case "E03": //CASO DESTINATRIO PF
                        if(!$this->vazio($dados[1])) {
                            $CPF = $dom->createElement("CPF", trim($dados[1]));
                            $E->appendChild($CPF);
                        }
                        $infNFe->appendChild($E);
                        break;
                    case "E05"://ENDERECO DO DESTINATARIO
                        if(isset($xNome))
                            $E->appendChild($xNome);
                        $enderDest = $dom->createElement("enderDest");
                        if(!$this->vazio($dados[1])) {
                            $xLgr = $dom->createElement("xLgr", trim($dados[1]));
                            $enderDest->appendChild($xLgr);
                        }
                        if(!$this->vazio($dados[2])) {
                            $nro = $dom->createElement("nro", trim($dados[2]));
                            $enderDest->appendChild($nro);
                        }
                        if(!$this->vazio($dados[3])) {
                            $xCpl = $dom->createElement("xCpl", trim($dados[3]));
                            $enderDest->appendChild($xCpl);
                        }
                        if(!$this->vazio($dados[4])) {
                            $xBairro = $dom->createElement("xBairro", trim($dados[4]));
                            $enderDest->appendChild($xBairro);
                        }
                        if(!$this->vazio($dados[5])) {
                            $cMun = $dom->createElement("cMun", trim($dados[5]));
                            $enderDest->appendChild($cMun);
                        }
                        if(!$this->vazio($dados[6])) {
                            $xMun = $dom->createElement("xMun", trim($dados[6]));
                            $enderDest->appendChild($xMun);
                        }
                        if(!$this->vazio($dados[7])) {
                            $UF = $dom->createElement("UF", trim($dados[7]));
                            $enderDest->appendChild($UF);
                        }
                        if(!$this->vazio($dados[8])) {
                            $CEP = $dom->createElement("CEP", trim($dados[8]));
                            $enderDest->appendChild($CEP);
                        }
                        if(!$this->vazio($dados[9])) {
                            $cPais = $dom->createElement("cPais", trim($dados[9]));
                            $enderDest->appendChild($cPais);
                        }
                        if(!$this->vazio($dados[10])) {
                            $xPais = $dom->createElement("xPais", trim($dados[10]));
                            $enderDest->appendChild($xPais);
                        }
                        if(!$this->vazio($dados[11])) {
                            $fone = $dom->createElement("fone", trim($dados[11]));
                            $enderDest->appendChild($fone);
                        }
                        $E->appendChild($enderDest);
                        if(isset($IE))
                            $E->appendChild($IE);
                        if(isset($ISUF))
                            $E->appendChild($ISUF);
                        $infNFe->appendChild($E);
                        break;
                    case "F": //LOCAL DE RETIRADA APENAS QNDO FOR DIFERENTE DO END EMITENTE
                        $retirada = $dom->createElement("retirada");
                        if(!$this->vazio($dados[1])) {
                            $CNPJ = $dom->createElement("CNPJ", trim($dados[1]));
                            $retirada->appendChild($CNPJ);
                        }
                        if(!$this->vazio($dados[2])) {
                            $xLgr = $dom->createElement("xLgr", trim($dados[2]));
                            $retirada->appendChild($xLgr);
                        }
                        if(!$this->vazio($dados[3])) {
                            $nro = $dom->createElement("nro", trim($dados[3]));
                            $retirada->appendChild($nro);
                        }
                        if(!$this->vazio($dados[4])) {
                            $xCpl = $dom->createElement("xCpl", trim($dados[4]));
                            $retirada->appendChild($xCpl);
                        }
                        if(!$this->vazio($dados[5])) {
                            $xBairro = $dom->createElement("xBairro", trim($dados[5]));
                            $retirada->appendChild($xBairro);
                        }
                        if(!$this->vazio($dados[6])) {
                            $cMun = $dom->createElement("cMun", trim($dados[6]));
                            $retirada->appendChild($cMun);
                        }
                        if(!$this->vazio($dados[7])) {
                            $xMun = $dom->createElement("xMun", trim($dados[7]));
                            $retirada->appendChild($xMun);
                        }
                        if(!$this->vazio($dados[8])) {
                            $UF = $dom->createElement("UF", trim($dados[8]));
                            $retirada->appendChild($UF);
                        }
                        $E->appendChild($retirada);
                        $infNFe->appendChild($E);
                        break;
                    case "G"://LOCAL DE ENTREGA APENAS QNDO END DIF DO DESTINATARIO
                        $entrega = $dom->createElement("entrega");
                        if(!$this->vazio($dados[1])) {
                            $CNPJ = $dom->createElement("CNPJ", trim($dados[1]));
                            $entrega->appendChild($CNPJ);
                        }
                        if(!$this->vazio($dados[2])) {
                            $xLgr = $dom->createElement("xLgr", trim($dados[2]));
                            $entrega->appendChild($xLgr);
                        }
                        if(!$this->vazio($dados[3])) {
                            $nro = $dom->createElement("nro", trim($dados[3]));
                            $entrega->appendChild($nro);
                        }
                        if(!$this->vazio($dados[4])) {
                            $xCpl = $dom->createElement("xCpl", trim($dados[4]));
                            $entrega->appendChild($xCpl);
                        }
                        if(!$this->vazio($dados[5])) {
                            $xBairro = $dom->createElement("xBairro", trim($dados[5]));
                            $entrega->appendChild($xBairro);
                        }
                        if(!$this->vazio($dados[6])) {
                            $cMun = $dom->createElement("cMun", trim($dados[6]));
                            $entrega->appendChild($cMun);
                        }
                        if(!$this->vazio($dados[7])) {
                            $xMun = $dom->createElement("xMun", trim($dados[7]));
                            $entrega->appendChild($xMun);
                        }
                        if(!$this->vazio($dados[8])) {
                            $UF = $dom->createElement("UF", trim($dados[8]));
                            $entrega->appendChild($UF);
                        }
                        $E->appendChild($entrega);
                        $infNFe->appendChild($E);
                        break;
                    case "H":// DETALHEMENTO DE PRODUTOS E SERVICOS - MAXIMO 990
                        $H = $dom->createElement("det");
                        $H->setAttribute("nItem", trim($dados[1]));
                        if(!$this->vazio($dados[2])) {
                            $infAdProd = $dom->createElement("infAdProd", trim($dados[2]));
                            $H->appendChild($infAdProd);
                        }
                        $infNFe->appendChild($H);
                        break;
                    case "I": //PRODUTO SERVICO
                        $I = $dom->createElement("prod");

                        if(!$this->vazio($dados[1])) {
                            $cProd = $dom->createElement("cProd", trim($dados[1]));
                            $I->appendChild($cProd);
                        }
                        // obrigatório
                        $cEAN = $dom->createElement("cEAN", trim($dados[2]));
                        $I->appendChild($cEAN);
                        if(!$this->vazio($dados[3])) {
                            $xProd = $dom->createElement("xProd", trim($dados[3]));
                            $I->appendChild($xProd);
                        }
                        if(!$this->vazio($dados[4])) {
                            $NCM = $dom->createElement("NCM", trim($dados[4]));
                            $I->appendChild($NCM);
                        }
                        if(!$this->vazio($dados[5])) {
                            $EXTIPI = $dom->createElement("EXTIPI", trim($dados[5]));
                            $I->appendChild($EXTIPI);
                        }
                        if(!$this->vazio($dados[6])) {
                            $genero = $dom->createElement("genero", trim($dados[6]));
                            $I->appendChild($genero);
                        }
                        if(!$this->vazio($dados[7])) {
                            $CFOP = $dom->createElement("CFOP", trim($dados[7]));
                            $I->appendChild($CFOP);
                        }
                        if(!$this->vazio($dados[8])) {
                            $uCom = $dom->createElement("uCom", trim($dados[8]));
                            $I->appendChild($uCom);
                        }
                        if(!$this->vazio($dados[9])) {
                            $qCom = $dom->createElement("qCom", trim($dados[9]));
                            $I->appendChild($qCom);
                        }
                        if(!$this->vazio($dados[10])) {
                            $vUnCom = $dom->createElement("vUnCom", trim($dados[10]));
                            $I->appendChild($vUnCom);
                        }
                        if(!$this->vazio($dados[11])) {
                            $vProd = $dom->createElement("vProd", trim($dados[11]));
                            $I->appendChild($vProd);
                        }
                        $cEANTrib = $dom->createElement("cEANTrib", trim($dados[12]));
                        $I->appendChild($cEANTrib);
                        if(!$this->vazio($dados[13])) {
                            $uTrib = $dom->createElement("uTrib", trim($dados[13]));
                            $I->appendChild($uTrib);
                        }
                        if(!$this->vazio($dados[14])) {
                            $qTrib = $dom->createElement("qTrib", trim($dados[14]));
                            $I->appendChild($qTrib);
                        }
                        if(!$this->vazio($dados[15])) {
                            $vUnTrib = $dom->createElement("vUnTrib", trim($dados[15]));
                            $I->appendChild($vUnTrib);
                        }
                        if(!$this->vazio($dados[16])) {
                            $vFrete = $dom->createElement("vFrete", trim($dados[16]));
                            $I->appendChild($vFrete);
                        }
                        if(!$this->vazio($dados[17])) {
                            $vSeg = $dom->createElement("vSeg", trim($dados[17]));
                            $I->appendChild($vSeg);
                        }
                        if(!$this->vazio($dados[18])) {
                            $vDesc = $dom->createElement("vDesc", trim($dados[18]));
                            $I->appendChild($vDesc);
                        }
                        $H->appendChild($I);
                        break;
                    case "I18":// DECLARACAO DE IMPORTACAO
                        $DI = $dom->createElement("DI");
                        if(!$this->vazio($dados[1])) {
                            $nDi = $dom->createElement("nDi", trim($dados[1]));
                            $DI->appendChild($nDi);
                        }
                        if(!$this->vazio($dados[2])) {
                            $dDi = $dom->createElement("dDi", trim($dados[2]));
                            $DI->appendChild($dDi);
                        }
                        if(!$this->vazio($dados[3])) {
                            $xLocDesemb = $dom->createElement("xLocDesemb", trim($dados[3]));
                            $DI->appendChild($xLocDesemb);
                        }
                        if(!$this->vazio($dados[4])) {
                            $UFDesemb = $dom->createElement("UFDesemb", trim($dados[4]));
                            $DI->appendChild($UFDesemb);
                        }
                        if(!$this->vazio($dados[5])) {
                            $dDesemb = $dom->createElement("dDesemb", trim($dados[5]));
                            $DI->appendChild($dDesemb);
                        }
                        if(!$this->vazio($dados[5])) {
                            $cExportador = $dom->createElement("cExportador", trim($dados[6]));
                            $DI->appendChild($cExportador);
                        }
                        $I->appendChild($DI);// DECLARACAO DE IMPORTACAO FILHO DE PROD NAO DO DET
                        break;
                    case "I25":// ADICOES
                        $adi = $dom->createElement("adi");
                        if(!$this->vazio($dados[1])) {
                            $nAdicao = $dom->createElement("nAdicao", trim($dados[1]));
                            $adi->appendChild($nAdicao);
                        }
                        if(!$this->vazio($dados[2])) {
                            $nSeqAdic = $dom->createElement("nSeqAdic", trim($dados[2]));
                            $adi->appendChild($nSeqAdic);
                        }
                        if(!$this->vazio($dados[3])) {
                            $cFabricante = $dom->createElement("cFabricante", trim($dados[3]));
                            $adi->appendChild($cFabricante);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vDescDi = $dom->createElement("vDescDi", trim($dados[4]));
                            $adi->appendChild($vDescDi);
                        }
                        $DI->appendChild($adi);
                        break;

                    case "J": //veiculos novos
                        $veicProd = $dom->createElement("veicProd");
                        if(!$this->vazio($dados[1])) {
                            $tpOP = $dom->createElement("tpOp", trim($dados[1]));
                            $veicProd->appendChild($tpOP);
                        }
                        if(!$this->vazio($dados[2])) {
                            $chassi = $dom->createElement("chassi", trim($dados[2]));
                            $veicProd->appendChild($chassi);
                        }
                        if(!$this->vazio($dados[3])) {
                            $cCor = $dom->createElement("cCor", trim($dados[3]));
                            $veicProd->appendChild($cCor);
                        }
                        if(!$this->vazio($dados[4])) {
                            $xCor = $dom->createElement("xCor", trim($dados[4]));
                            $veicProd->appendChild($dVal);
                        }
                        if(!$this->vazio($dados[5])) {
                            $pot = $dom->createElement("pot", trim($dados[5]));
                            $veicProd->appendChild($pot);
                        }
                        if(!$this->vazio($dados[6])) {
                            $CM3 = $dom->createElement("CM3", trim($dados[5]));
                            $veicProd->appendChild($CM3);
                        }
                        if(!$this->vazio($dados[7])) {
                            $pesoL = $dom->createElement("pesL", trim($dados[5]));
                            $veicProd->appendChild($pesoL);
                        }
                        if(!$this->vazio($dados[8])) {
                            $pesoB = $dom->createElement("pesoB", trim($dados[5]));
                            $veicProd->appendChild($pesoB);
                        }
                        if(!$this->vazio($dados[9])) {
                            $nSerie = $dom->createElement("nSerie", trim($dados[5]));
                            $veicProd->appendChild($nSerie);
                        }
                        if(!$this->vazio($dados[10])) {
                            $tpComb = $dom->createElement("tpComb", trim($dados[5]));
                            $veicProd->appendChild($tpComb);
                        }
                        if(!$this->vazio($dados[11])) {
                            $nMotor = $dom->createElement("nMotor", trim($dados[5]));
                            $veicProd->appendChild($nMotor);
                        }
                        if(!$this->vazio($dados[12])) {
                            $CMKG = $dom->createElement("CMKG", trim($dados[5]));
                            $veicProd->appendChild($CMKG);
                        }
                        if(!$this->vazio($dados[13])) {
                            $dist = $dom->createElement("dist", trim($dados[5]));
                            $veicProd->appendChild($dist);
                        }
                        if(!$this->vazio($dados[14])) {
                            $RENAVAM = $dom->createElement("RENAVAM", trim($dados[5]));
                            $veicProd->appendChild($RENAVAM);
                        }
                        if(!$this->vazio($dados[15])) {
                            $anoMod = $dom->createElement("anoMod", trim($dados[5]));
                            $veicProd->appendChild($anoMod);
                        }
                        if(!$this->vazio($dados[16])) {
                            $anoFab = $dom->createElement("anoFab", trim($dados[5]));
                            $veicProd->appendChild($anoFab);
                        }
                        if(!$this->vazio($dados[17])) {
                            $tpPint = $dom->createElement("tpPint", trim($dados[5]));
                            $veicProd->appendChild($tpPint);
                        }
                        if(!$this->vazio($dados[18])) {
                            $tpVeic = $dom->createElement("tpVeic", trim($dados[5]));
                            $veicProd->appendChild($tpVeic);
                        }
                        if(!$this->vazio($dados[19])) {
                            $espVeic = $dom->createElement("espVeic", trim($dados[5]));
                            $veicProd->appendChild($espVeic);
                        }
                        if(!$this->vazio($dados[20])) {
                            $VIN = $dom->createElement("VIN", trim($dados[5]));
                            $veicProd->appendChild($VIN);
                        }
                        if(!$this->vazio($dados[21])) {
                            $condVeic = $dom->createElement("conVeic", trim($dados[5]));
                            $veicProd->appendChild($condVeic);
                        }
                        if(!$this->vazio($dados[22])) {
                            $cMod = $dom->createElement("cMod", trim($dados[5]));
                            $veicProd->appendChild($cMod);
                        }
                        $I->appendChild($veicProd);//elemento filho do prod
                        break;
                    case "K"://medicamento
                        $med = $dom->createElement("med");
                        if(!$this->vazio($dados[1])) {
                            $nLote = $dom->createElement("nLote", trim($dados[1]));
                            $med->appendChild($nLote);
                        }
                        if(!$this->vazio($dados[2])) {
                            $qLote = $dom->createElement("qLote", trim($dados[2]));
                            $med->appendChild($qLote);
                        }
                        if(!$this->vazio($dados[3])) {
                            $dFab = $dom->createElement("dFab", trim($dados[3]));
                            $med->appendChild($dFab);
                        }

                        $dVal = $dom->createElement("dVal", trim($dados[4]));
                        $med->appendChild($dVal);

                        if(!$this->vazio($dados[5])) {
                            $vPMC = $dom->createElement("vPMC", trim($dados[5]));
                            $med->appendChild($vPMC);
                        }
                        $I->appendChild($med);
                        break;
                    case "L": //armamento
                        $arma = $dom->createElement("arma");
                        if(!$this->vazio($dados[1])) {
                            $tpArma = $dom->createElement("tpArma", trim($dados[1]));
                            $arma->appendChild($tpArma);
                        }
                        if(!$this->vazio($dados[2])) {
                            $nSerie = $dom->createElement("nSerie", trim($dados[2]));
                            $arma->appendChild($nSerie);
                        }
                        if(!$this->vazio($dados[3])) {
                            $nCano = $dom->createElement("nCano", trim($dados[3]));
                            $arma->appendChild($nCano);
                        }
                        if(!$this->vazio($dados[4])) {
                            $descr = $dom->createElement("descr", trim($dados[4]));
                            $arma->appendChild($descr);
                        }
                        $I->appendChild($arma);
                        break;
                    case "L01":
                        $comb = $dom->createElement("comb");
                        if(!$this->vazio($dados[1])) {
                            $cProdANP = $dom->createElement("cProdANP", trim($dados[1]));
                            $comb->appendChild($cProdANP);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CODIF = $dom->createElement("CODIF", trim($dados[2]));
                            $comb->appendChild($CODIF);
                        }
                        if(!$this->vazio($dados[3])) {
                            $qTemp = $dom->createElement("qTemp", trim($dados[3]));
                            $comb->appendChild($qTemp);
                        }
                        $I->appendChild($comb);
                        break;
                    case "L105":
                        $CIDE = $dom->createElement("CIDE");
                        if(!$this->vazio($dados[1])) {
                            $qBCprod = $dom->createElement("qBCprod", trim($dados[1]));
                            $CIDE->appendChild($qBCprod);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vAliqProd = $dom->createElement("vAliqProd", trim($dados[2]));
                            $CIDE->appendChild($vAliqProd);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vCIDE = $dom->createElement("vCIDE", trim($dados[3]));
                            $CIDE->appendChild($vCIDE);
                        }
                        $I->appendChild($CIDE);
                        break;
                    case "L109":
                        $ICMSComb = $dom->createElement("ICMSComb");
                        if(!$this->vazio($dados[1])) {
                            $vBCICMS = $dom->createElement("vBCICMS", trim($dados[1]));
                            $ICMSComb->appendChild($vBCICMS);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[2]));
                            $ICMSComb->appendChild($vICMS);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vBCICMSST = $dom->createElement("vBCICMSST", trim($dados[3]));
                            $ICMSComb->appendChild($vBCICMSST);
                        }

                        $vICMSST = $dom->createElement("vICMSST", trim($dados[4]));
                        $ICMSComb->appendChild($vICMSST);

                        $I->appendChild($ICMSComb);
                        break;
                    case "L114":
                        $ICMSInter = $dom->createElement("ICMSInter");
                        if(!$this->vazio($dados[1])) {
                            $vBCICMSSTDest = $dom->createElement("vBCICMSSTDest", trim($dados[1]));
                            $ICMSInter->appendChild($vBCICMSSTDest);
                        }
                        $vICMSSTDest = $dom->createElement("vICMSSTDest", trim($dados[2]));
                        $ICMSInter->appendChild($vICMSSTDest);
                        $I->appendChild($ICMSInter);
                        break;
                    case "L117":
                        $ICMSCons = $dom->createElement("ICMSCons");
                        if(!$this->vazio($dados[1])) {
                            $vBCICMSSTCons = $dom->createElement("vBCICMSSTCons", trim($dados[1]));
                            $ICMSCons->appendChild($vBCICMSSTCons);
                        }

                        $vICMSSTCons = $dom->createElement("vICMSSTCons", trim($dados[2]));
                        $ICMSCons->appendChild($vICMSSTCons);

                        if(!$this->vazio($dados[3])) {
                            $UFcons = $dom->createElement("UFcons", trim($dados[3]));
                            $ICMSCons->appendChild($UFcons);
                        }
                        $I->appendChild($ICMSCons);
                        break;
                    case "M"://GRUPO DE TRIBUTOS INCIDENTES NO PRODUTO SERVICO
                        $imposto = $dom->createElement("imposto");
                        $H->appendChild($imposto);
                        break;
                    case "N"://ICMS
                        $ICMS = $dom->createElement("ICMS");
                        $imposto->appendChild($ICMS);
                        break;
                    case "N02"://CST 00 TRIBUTADO INTEGRALMENTE
                        $ICMS00 = $dom->createElement("ICMS00");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS00->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS00->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBC = $dom->createElement("modBC", trim($dados[3]));
                            $ICMS00->appendChild($modBC);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vBC = $dom->createElement("vBC", trim($dados[4]));
                            $ICMS00->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[5])) {
                            $pICMS = $dom->createElement("pICMS", trim($dados[5]));
                            $ICMS00->appendChild($pICMS);
                        }
                        if(!$this->vazio($dados[6])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[6]));
                            $ICMS00->appendChild($vICMS);
                        }
                        $ICMS->appendChild($ICMS00);
                        break;
                    case "N03"://CST 010 TRIBUTADO E COM COBRANCAO DE ICMS POR SUBSTUICAO TRIBUTARIA
                        $ICMS10 = $dom->createElement("ICMS10");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS10->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS10->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBC = $dom->createElement("modBC", trim($dados[3]));
                            $ICMS10->appendChild($modBC);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vBC = $dom->createElement("vBC", trim($dados[4]));
                            $ICMS10->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[5])) {
                            $pICMS = $dom->createElement("pICMS", trim($dados[5]));
                            $ICMS10->appendChild($pICMS);
                        }
                        if(!$this->vazio($dados[6])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[6]));
                            $ICMS10->appendChild($vICMS);
                        }
                        if(!$this->vazio($dados[7])) {
                            $modBCST = $dom->createElement("modBCST", trim($dados[7]));
                            $ICMS10->appendChild($modBCST);
                        }
                        if(!$this->vazio($dados[8])) {
                            $pMVAST = $dom->createElement("pMVAST", trim($dados[8]));
                            $ICMS10->appendChild($pMVAST);
                        }
                        if(!$this->vazio($dados[9])) {
                            $pRedBCST = $dom->createElement("pRedBCST", trim($dados[8]));
                            $ICMS10->appendChild($pRedBCST);
                        }
                        if(!$this->vazio($dados[10])) {
                            $vBCST = $dom->createElement("vBCST", trim($dados[10]));
                            $ICMS10->appendChild($vBCST);
                        }
                        if(!$this->vazio($dados[11])) {
                            $pICMSST = $dom->createElement("pICMSST", trim($dados[11]));
                            $ICMS10->appendChild($pICMSST);
                        }
                        if(!$this->vazio($dados[12])) {
                            $vICMSST = $dom->createElement("vICMSST", trim($dados[12]));
                            $ICMS10->appendChild($vICMSST);
                        }
                        $ICMS->appendChild($ICMS10);
                        break;
                    case "N04"://CST 020 COM REDUCAO DE BASE DE CALCULO
                        $ICMS20 = $dom->createElement("ICMS20");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS20->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS20->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBC = $dom->createElement("modBC", trim($dados[3]));
                            $ICMS20->appendChild($modBC);
                        }
                        if(!$this->vazio($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", trim($dados[4]));
                            $ICMS20->appendChild($pRedBC);
                        }
                        if(!$this->vazio($dados[5])) {
                            $vBC = $dom->createElement("vBC", trim($dados[5]));
                            $ICMS20->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[6])) {
                            $pICMS = $dom->createElement("pICMS", trim($dados[6]));
                            $ICMS20->appendChild($pICMS);
                        }
                        if(!$this->vazio($dados[7])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[7]));
                            $ICMS20->appendChild($vICMS);
                        }
                        $ICMS->appendChild($ICMS20);
                        break;
                    case "N05"://CST 030 ISENTA OU NAO TRIBUTADO E COM COBRANCA DO ICMS POR ST
                        $ICMS30 = $dom->createElement("ICMS30");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS30->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS30->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBCST = $dom->createElement("modBCST", trim($dados[3]));
                            $ICMS30->appendChild($modBCST);
                        }
                        if(!$this->vazio($dados[4])) {
                            $pMVAST = $dom->createElement("pMVAST", trim($dados[4]));
                            $ICMS30->appendChild($pMVAST);
                        }
                        if(!$this->vazio($dados[5])) {
                            $pRedBCST = $dom->createElement("pRedBCST", trim($dados[5]));
                            $ICMS30->appendChild($pRedBCST);
                        }
                        if(!$this->vazio($dados[6])) {
                            $vBCST = $dom->createElement("vBCST", trim($dados[6]));
                            $ICMS30->appendChild($vBCST);
                        }
                        if(!$this->vazio($dados[7])) {
                            $pICMSST = $dom->createElement("pICMSST", trim($dados[7]));
                            $ICMS30->appendChild($pICMSST);
                        }
                        if(!$this->vazio($dados[8])) {
                            $vICMSST = $dom->createElement("vICMSST", trim($dados[8]));
                            $ICMS30->appendChild($vICMSST);
                        }
                        $ICMS->appendChild($ICMS30);
                        break;
                    case "N06":// CST 040 ISETA 41 NAO TRIBUTADO E 50 SUSPENSAO
                        $ICMS40 = $dom->createElement("ICMS40");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS40->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS40->appendChild($CST);
                        }
                        $ICMS->appendChild($ICMS40);
                        break;
                    case "N07": // CST 051 DIFERIMENTO - A EXIGENCIA DO PREECNCHIMENTO DAS INFORMAS DO ICMS DIFERIDO FICA A CRITERIO DE CADA UF
                        $ICMS51 = $dom->createElement("ICMS51");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS51->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS51->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBC = $dom->createElement("modBC", trim($dados[3]));
                            $ICMS51->appendChild($modBC);
                        }
                        if(!$this->vazio($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", trim($dados[4]));
                            $ICMS51->appendChild($pRedBC);
                        }
                        if(!$this->vazio($dados[5])) {
                            $vBC = $dom->createElement("vBC", trim($dados[5]));
                            $ICMS51->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[6])) {
                            $pICMS = $dom->createElement("pICMS", trim($dados[6]));
                            $ICMS51->appendChild($pICMS);
                        }
                        if(!$this->vazio($dados[7])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[7]));
                            $ICMS51->appendChild($vICMS);
                        }
                        $ICMS->appendChild($ICMS51);
                        break;
                    case "N08":// CST 060 ICMS COBRADO ANTERIORMENTE POR ST
                        $ICMS60 = $dom->createElement("ICMS60");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS60->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS60->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vBCST = $dom->createElement("vBCST", trim($dados[3]));
                            $ICMS60->appendChild($vBCST);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vICMSST = $dom->createElement("vICMSST", trim($dados[4]));
                            $ICMS60->appendChild($vICMSST);
                        }
                        $ICMS->appendChild($ICMS60);
                        break;
                    case "N09": //CST - 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
                        $ICMS70 = $dom->createElement("ICMS70");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS70->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS70->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBC = $dom->createElement("modBC", trim($dados[3]));
                            $ICMS70->appendChild($modBC);
                        }
                        if(!$this->vazio($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", trim($dados[4]));
                            $ICMS70->appendChild($pRedBC);
                        }
                        if(!$this->vazio($dados[5])) {
                            $vBC = $dom->createElement("vBC", trim($dados[5]));
                            $ICMS70->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[6])) {
                            $pICMS = $dom->createElement("pICMS", trim($dados[6]));
                            $ICMS70->appendChild($pICMS);
                        }
                        if(!$this->vazio($dados[7])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[7]));
                            $ICMS70->appendChild($vICMS);
                        }
                        if(!$this->vazio($dados[8])) {
                            $modBCST = $dom->createElement("modBCST", trim($dados[8]));
                            $ICMS70->appendChild($modBCST);
                        }
                        if(!$this->vazio($dados[9])) {
                            $pMVAST = $dom->createElement("pMVAST", trim($dados[9]));
                            $ICMS70->appendChild($pMVAST);
                        }
                        if(!$this->vazio($dados[10])) {
                            $pRedBCST = $dom->createElement("pRedBCST", trim($dados[10]));
                            $ICMS70->appendChild($pRedBCSt);
                        }
                        if(!$this->vazio($dados[11])) {
                            $vvBCST = $dom->createElement("vBCST", trim($dados[11]));
                            $ICMS70->appendChild($vBCST);
                        }
                        if(!$this->vazio($dados[12])) {
                            $pICMSST = $dom->createElement("pICMSST", trim($dados[12]));
                            $ICMS70->appendChild($pICMSST);
                        }
                        if(!$this->vazio($dados[13])) {
                            $vICMSST = $dom->createElement("vICMSST", trim($dados[13]));
                            $ICMS70->appendChild($vICMSST);
                        }
                        $ICMS->appendChild($ICMS70);
                        break;
                    case "N10": //CST - 90 Outros

                        $ICMS90 = $dom->createElement("ICMS90");
                        if(!$this->vazio($dados[1])) {
                            $orig = $dom->createElement("orig", trim($dados[1]));
                            $ICMS90->appendChild($orig);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CST = $dom->createElement("CST", trim($dados[2]));
                            $ICMS90->appendChild($CST);
                        }
                        if(!$this->vazio($dados[3])) {
                            $modBC = $dom->createElement("modBC", trim($dados[3]));
                            $ICMS90->appendChild($modBC);
                        }
                        if(!$this->vazio($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", trim($dados[4]));
                            $ICMS90->appendChild($pRedBC);
                        }
                        if(!$this->vazio($dados[5])) {
                            $vBC = $dom->createElement("vBC", trim($dados[5]));
                            $ICMS90->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[6])) {
                            $pICMS = $dom->createElement("pICMS", trim($dados[6]));
                            $ICMS90->appendChild($pICMS);
                        }
                        if(!$this->vazio($dados[7])) {
                            $vICMS = $dom->createElement("vICMS", trim($dados[7]));
                            $ICMS90->appendChild($vICMS);
                        }
                        if(!$this->vazio($dados[8])) {
                            $modBCST = $dom->createElement("modBCST", trim($dados[8]));
                            $ICMS90->appendChild($modBCST);
                        }
                        if(!$this->vazio($dados[9])) {
                            $pMVAST = $dom->createElement("pMVAST", trim($dados[9]));
                            $ICMS90->appendChild($pMVAST);
                        }
                        if(!$this->vazio($dados[10])) {
                            $pRedBCST = $dom->createElement("pRedBCST", trim($dados[10]));
                            $ICMS90->appendChild($pRedBCSt);
                        }
                        if(!$this->vazio($dados[11])) {
                            $vvBCST = $dom->createElement("vBCST", trim($dados[11]));
                            $ICMS90->appendChild($vBCST);
                        }
                        if(!$this->vazio($dados[12])) {
                            $pICMSST = $dom->createElement("pICMSST", trim($dados[12]));
                            $ICMS90->appendChild($pICMSST);
                        }
                        if(!$this->vazio($dados[13])) {
                            $vICMSST = $dom->createElement("vICMSST", trim($dados[13]));
                            $ICMS90->appendChild($vICMSST);
                        }
                        $ICMS->appendChild($ICMS90);
                        break;
                    case "O": //IPI INFORMAR QNDO O ITEM FOR SUJEITO AO IPI
                        $IPI = $dom->createElement("IPI");
                        if(!$this->vazio($dados[1])) {
                            $clEnq = $dom->createElement("clEnq", trim($dados[1]));
                            $IPI->appendChild($clEnq);
                        }
                        if(!$this->vazio($dados[2])) {
                            $CNPJProd = $dom->createElement("CNPJProd", trim($dados[2]));
                            $IPI->appendChild($CNPJProd);
                        }
                        if(!$this->vazio($dados[3])) {
                            $cSelo = $dom->createElement("cSelo", trim($dados[3]));
                            $IPI->appendChild($cSelo);
                        }
                        if(!$this->vazio($dados[4])) {
                            $cEnq = $dom->createElement("cEnq", trim($dados[4]));
                            $IPI->appendChild($cEnq);
                        }
                        if(!$this->vazio($dados[5])) {
                            $cEnq = $dom->createElement("cEnq", trim($dados[5]));
                            $IPI->appendChild($cEnq);
                        }
                        $imposto->appendChild($IPI);
                        break;
                    case "O07":// IPI TRIBUTAVEL
                        $IPITrib = $dom->createElement("IPITrib");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $IPITrib->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vIPI = $dom->createElement("vIPI", trim($dados[2]));
                        }
                        $IPI->appendChild($IPITrib);
                        break;
                    case "O10"://
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[1]));
                            $IPITrib->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[2])) {
                            $pIPI = $dom->createElement("pIPI", trim($dados[2]));
                            $IPITrib->appendChild($pIPI);
                        }
                        $IPI->appendChild($IPITrib);
                        break;
                    case "O11":
                        if(!$this->vazio($dados[1])) {
                            $vUnid = $dom->createElement("vUnid", trim($dados[1]));
                            $IPITrib->appendChild($vUnid);
                        }
                        if(!$this->vazio($dados[2])) {
                            $qUnid = $dom->createElement("qUnid", trim($dados[2]));
                            $IPITrib->appendChild($qUnid);
                        }
                        $IPI->appendChild($IPITrib);
                        break;
                    case "O08"://IPI NAO TRIBUTAVEL
                        $IPINT = $dom->createElement("IPINT");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $IPINT->appendChild($CST);
                        }
                        $IPI->appendChild($IPINT);
                        break;
                    case "P"://INFORMAR APENAS QNDO O ITEM FOR SUJEITO A II
                        $II = $dom->createElement("II");
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[1]));
                            $II->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vDespAdu = $dom->createElement("vDespAdu", trim($dados[2]));
                            $II->appendChild($vDespAdu);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vII = $dom->createElement("vII", trim($dados[3]));
                            $II->appendChild($vII);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vIOF = $dom->createElement("vIOF", trim($dados[4]));
                            $II->appendChild($vIOF);
                        }
                        $imposto->appendChild($II);
                        break;
                    case "Q"://PIS
                        $PIS = $dom->createElement("PIS");
                        $imposto->appendChild($PIS);
                        break;
                    case "Q02":// PIS GRUPO DE PIS TRIBUTADO PELA ALIQUOTA
                        $PISAliq = $dom->createElement("PISAliq");

                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $PISAliq->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vBC = $dom->createElement("vBC", trim($dados[2]));
                            $PISAliq->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[3])) {
                            $pPIS = $dom->createElement("pPIS", trim($dados[3]));
                            $PISAliq->appendChild($pPIS);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vPIS = $dom->createElement("vPIS", trim($dados[4]));
                            $PISAliq->appendChild($vPIS);
                        }
                        $PIS->appendChild($PISAliq);
                        break;
                    case "Q03"://GRUPO DE PIS TRIBUTADO POR QTDE
                        $PISQtde = $dom->createElement("PISQtde");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $PISQtde->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $qBCProd = $dom->createElement("qBCProd", trim($dados[2]));
                            $PISQtde->appendChild($qBCProd);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vAliqProd = $dom->createElement("vAliqProd", trim($dados[3]));
                            $PISQtde->appendChild($vAliqProd);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vPIS = $dom->createElement("vPIS", trim($dados[4]));
                            $PISQtde->appendChild($vPIS);
                        }
                        $PIS->appendChild($PISAliq);
                        break;
                    case "Q04": // PIS - grupo de PIS n�o tributado
                        $PISNT = $dom->createElement("PISNT");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $PISNT->appendChild($CST);
                        }
                        $PIS->appendChild($PISNT);
                        break;
                    case "Q05"://GRUPO DE PIS OUTRAS OPERACOES
                        $PISOutr = $dom->createElement("PISOutr");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $PISOutr->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vPIS = $dom->createElement("vPIS", trim($dados[2]));
                        }
                        $PIS->appendChild($PISOutr);
                        break;
                    case "Q07":
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[1]));
                            $PISOutr->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[2])) {
                            $pPIS = $dom->createElement("pPIS", trim($dados[2]));
                            $PISOutr->appendChild($pPIS);
                        }
                        $PIS->appendChild($PISOutr);
                        break;
                    case "Q10":
                        if(!$this->vazio($dados[1])) {
                            $qBCProd = $dom->createElement("qBCProd", trim($dados[1]));
                            $PISOutr->appendChild($qBCProd);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vAliqProd = $dom->createElement("vAliqProd", trim($dados[2]));
                            $PISOutr->appendChild($vAliqProd);
                        }
                        $PISOutr->appendChild($vPIS);
                        $PIS->appendChild($PISOutr);
                        break;
                    case "R": //SUBSTUITICAO TRIBUTARIA
                        $PISST = $dom->createElement("PISST");
                        if(!$this->vazio($dados[1])) {
                            $vPIS = $dom->createElement("vPIS", trim($dados[1]));
                            $PISST->appendChild($vPIS);
                        }
                        $imposto->appendChild($PISST);
                        break;
                    case "R02":
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[1]));
                            $PISST->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[1])) {
                            $pPIS = $dom->createElement("pPIS", trim($dados[1]));
                            $PISST->appendChild($pPIS);
                        }
                        $imposto->appendChild($PISST);
                        break;
                    case "R04":
                        if(!$this->vazio($dados[1])) {
                            $qBCProd = $dom->createElement("qBCProd", trim($dados[1]));
                            $PISST->appendChild($qBCProd);
                        }
                        $imposto->appendChild($PISST);
                        break;
                    case "S"://COFINS
                        $COFINS = $dom->createElement("COFINS");
                        $imposto->appendChild($COFINS);
                        break;
                    case "S02"://COFINS GRUPO TRIBUTABEL PELA ALIQUOTA
                        $COFINSAliq = $dom->createElement("COFINSAliq");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $COFINSAliq->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vBC = $dom->createElement("vBC", trim($dados[2]));
                            $COFINSAliq->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[3])) {
                            $pCOFINS = $dom->createElement("pCOFINS", trim($dados[3]));
                            $COFINSAliq->appendChild($pCOFINS);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vCOFINS = $dom->createElement("vCOFINS", trim($dados[4]));
                            $COFINSAliq->appendChild($vCOFINS);
                        }
                        $COFINS->appendChild($COFINSAliq);
                        break;
                    case "S03"://GRUPO TRIBUTAVEL PELA QUANTIDDE
                        $COFINSQtde = $dom->createElement("COFINSQtde");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $COFINSAliq->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $qBCProd = $dom->createElement("qBCProd", trim($dados[2]));
                            $COFINSAliq->appendChild($qBCProd);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vAliqProd = $dom->createElement("vAliqProd", trim($dados[3]));
                            $COFINSAliq->appendChild($vAliqProd);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vCOFINS = $dom->createElement("vCOFINS", trim($dados[4]));
                            $COFINSAliq->appendChild($vCOFINS);
                        }
                        $COFINS->appendChild($COFINSQtde);
                        break;
                    case "S04"://GRUPO NAO TRIBUTADO
                        $COFINSNT = $dom->createElement("COFINSNT");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $COFINSNT->appendChild($CST);
                        }
                        $COFINS->appendChild($COFINSNT);
                        break;
                    case "S05"://GRUPO TRIBUTADO POR OUTRAS OPERACOES
                        $COFINSOutr = $dom->createElement("COFINSOutr");
                        if(!$this->vazio($dados[1])) {
                            $CST = $dom->createElement("CST", trim($dados[1]));
                            $COFINSOutr->appendChild($CST);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vCOFINS = $dom->createElement("vCOFINS", trim($dados[2]));
                        }
                        $COFINS->appendChild($COFINSOutr);
                        break;
                    case "S07":
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[3]));
                            $COFINSOutr->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[2])) {
                            $pCOFINS = $dom->createElement("pCOFINS", trim($dados[4]));
                            $COFINSOutr->appendChild($pCOFINS);
                        }
                        $COFINS->appendChild($COFINSOutr);
                        break;
                    case "S09":
                        if(!$this->vazio($dados[1])) {
                            $qBCProd = $dom->createElement("qBCProd", trim($dados[1]));
                            $COFINSOutr->appendChild($qBCProd);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vAliqProd = $dom->createElement("vAliqProd", trim($dados[2]));
                            $COFINSOutr->appendChild($vAliqProd);
                        }
                        $COFINSOutr->appendChild($vCOFINS);
                        break;
                    case "T": //COFINS ST
                        $COFINSST = $dom->createElement("COFINSST");
                        if(!$this->vazio($dados[1])) {
                            $vCOFINS = $dom->createElement("vCOFINS", trim($dados[1]));
                            $COFINSST->appendChild($vCOFINS);
                        }
                        $imposto->appendChild($COFINSST);
                        break;
                    case "T02":
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[1]));
                            $COFINSST->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[2])) {
                            $pCOFINS = $dom->createElement("pCOFINS", trim($dados[2]));
                            $COFINSST->appendChild($pCOFINS);
                        }
                        $imposto->appendChild($COFINSST);
                        break;
                    case "T04":
                        if(!$this->vazio($dados[1])) {
                            $qBCProd = $dom->createElement("qBCProd", trim($dados[1]));
                            $COFINSST->appendChild($qBCProd);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vAliqProd = $dom->createElement("vAliqProd", trim($dados[2]));
                            $COFINSST->appendChild($vAliqProd);
                        }
                        $imposto->appendChild($COFINSST);
                        break;
                    case "U": // ISS
                        $ISSQN = $dom->createElement("ISSQN");
                        if(!$this->vazio($dados[1])) {
                            $vBC = $dom->createElement("vBC", trim($dados[1]));
                            $ISSQN->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vAliq = $dom->createElement("vAliq", trim($dados[2]));
                            $ISSQN->appendChild($vAliq);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vISSQN = $dom->createElement("vISSQN", trim($dados[3]));
                            $ISSQN->appendChild($vISSQN);
                        }
                        if(!$this->vazio($dados[4])) {
                            $cMunFG = $dom->createElement("cMunFG", trim($dados[4]));
                            $ISSQN->appendChild($cMunFG);
                        }
                        if(!$this->vazio($dados[5])) {
                            $cListServ = $dom->createElement("cListServ", trim($dados[5]));
                            $ISSQN->appendChild($cListServ);
                        }
                        $imposto->appendChild($ISSQN);
                        break;
                    case "W": // totais
                        $total = $dom->createElement("total");
                        $infNFe->appendChild($total);
                        break;
                    case "W02": // ICSM Total
                        $ICMSTot = $dom->createElement("ICMSTot");
                        // todos esses campos s�o obrigat�rios
                        $vBC = $dom->createElement("vBC", trim($dados[1]));
                        $ICMSTot->appendChild($vBC);

                        $vICMS = $dom->createElement("vICMS", trim($dados[2]));
                        $ICMSTot->appendChild($vICMS);

                        $vBCST = $dom->createElement("vBCST", trim($dados[3]));
                        $ICMSTot->appendChild($vBCST);

                        $vST = $dom->createElement("vST", trim($dados[4]));
                        $ICMSTot->appendChild($vST);

                        $vProd = $dom->createElement("vProd", trim($dados[5]));
                        $ICMSTot->appendChild($vProd);

                        $vFrete = $dom->createElement("vFrete", trim($dados[6]));
                        $ICMSTot->appendChild($vFrete);

                        $vSeg = $dom->createElement("vSeg", trim($dados[7]));
                        $ICMSTot->appendChild($vSeg);

                        $vDesc = $dom->createElement("vDesc", trim($dados[8]));
                        $ICMSTot->appendChild($vDesc);

                        $vII = $dom->createElement("vII", trim($dados[9]));
                        $ICMSTot->appendChild($vII);

                        $vIPI = $dom->createElement("vIPI", trim($dados[10]));
                        $ICMSTot->appendChild($vIPI);

                        $vPIS = $dom->createElement("vPIS", trim($dados[11]));
                        $ICMSTot->appendChild($vPIS);

                        $vCOFINS = $dom->createElement("vCOFINS", trim($dados[12]));
                        $ICMSTot->appendChild($vCOFINS);

                        $vOutro = $dom->createElement("vOutro", trim($dados[13]));
                        $ICMSTot->appendChild($vOutro);

                        $vNF = $dom->createElement("vNF", trim($dados[14]));
                        $ICMSTot->appendChild($vNF);

                        $total->appendChild($ICMSTot);
                        break;
                    case "W17": // TAG de grupo de Valores Totais referentes ao ISSQN
                        $ISSQNtot = $dom->createElement("ISSQNtot");
                        if(!$this->vazio($dados[1])) {
                            $vServ = $dom->createElement("vServ", trim($dados[1]));
                            $ISSQNtot->appendChild($vServ);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vBC = $dom->createElement("vBC", trim($dados[2]));
                            $ISSQNtot->appendChild($vBC);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vServ = $dom->createElement("vServ", trim($dados[3]));
                            $ISSQNtot->appendChild($vServ);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vISS = $dom->createElement("vISS", trim($dados[4]));
                            $ISSQNtot->appendChild($vISS);
                        }
                        if(!$this->vazio($dados[5])) {
                            $vPIS = $dom->createElement("vPIS", trim($dados[5]));
                            $ISSQNtot->appendChild($vPIS);
                        }
                        if(!$this->vazio($dados[6])) {
                            $vCOFINS = $dom->createElement("vCOFINS", trim($dados[6]));
                            $ISSQNtot->appendChild($vCOFINS);
                        }
                        $total->appendChild($ISSQNtot);
                        break;
                    case "W23": //TAG de grupo de Reten��es de Tributos
                        $retTrib = $dom->createElement("retTrib");
                        if(!$this->vazio($dados[1])) {
                            $vRetPIS = $dom->createElement("vRetPIS", trim($dados[1]));
                            $retTrib->appendChild($vRetPIS);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vRetCOFINS = $dom->createElement("vRetCOFINS", trim($dados[2]));
                            $retTrib->appendChild($vRetCOFINS);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vRetCSLL = $dom->createElement("vRetCSLL", trim($dados[3]));
                            $retTrib->appendChild($vRetCSLL);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vBCIRRF = $dom->createElement("vBCIRRF", trim($dados[4]));
                            $retTrib->appendChild($vBCIRRF);
                        }
                        if(!$this->vazio($dados[5])) {
                            $vIRRF = $dom->createElement("vIRRF", trim($dados[5]));
                            $retTrib->appendChild($vIRRF);
                        }
                        if(!$this->vazio($dados[6])) {
                            $vBCRetPrev = $dom->createElement("vBCRetPrev", trim($dados[6]));
                            $retTrib->appendChild($vBCRetPrev);
                        }
                        if(!$this->vazio($dados[7])) {
                            $vRetPrev = $dom->createElement("vRetPrev", trim($dados[7]));
                            $retTrib->appendChild($vRetPrev);
                        }
                        $total->appendChild($retTrib);
                        break;
                    case "X": // transporte
                        $transp = $dom->createElement("transp");
                        // todos esses campos são obrigatórios
                        $modFrete = $dom->createElement("modFrete", trim($dados[1]));
                        $transp->appendChild($modFrete);
                        $infNFe->appendChild($transp);
                        break;

                    case "X03":
                        $transporta = $dom->createElement("transporta");

                        if(!$this->vazio($dados[1])) {
                            $xNome = $dom->createElement("xNome", trim($dados[1]));
                            $transporta->appendChild($xNome);
                        }
                        if(!$this->vazio($dados[2])) {
                            $IE = $dom->createElement("IE", trim($dados[2]));
                            $transporta->appendChild($IE);
                        }
                        if(!$this->vazio($dados[3])) {
                            $xEnder = $dom->createElement("xEnder", trim($dados[3]));
                            $transporta->appendChild($xEnder);
                        }
                        if(!$this->vazio($dados[5])) {
                            $xMun = $dom->createElement("xMun", trim($dados[5]));
                            $transporta->appendChild($xMun);
                        }
                        if(!$this->vazio($dados[4])) {
                            $UF = $dom->createElement("UF", trim($dados[4]));
                            $transporta->appendChild($UF);
                        }
                        $transp->appendChild($transporta);
                        break;
                    case "X04":
                        if(!$this->vazio($dados[1])) {
                            $CNPJ = $dom->createElement("CNPJ", trim($dados[1]));
                            //$transporta->appendChild($CNPJ);
                            $transporta->insertBefore($transporta->appendChild($CNPJ),$xNome);//FORCA ADCIONAR ANTES DA TAG NOME
                        }
                        break;
                    case "X05":
                        if(!$this->vazio($dados[1])) {
                            $CPF = $dom->createElement("CPF", trim($dados[1]));
                            //$transporta->appendChild($CPF);
                            $transporta->insertBefore($transporta->appendChild($CPF),$xNome);//FORCA ADCIONAR ANTES DA TAG NOME
                        }
                        break;


                    case "X11":
                        $retTransp = $dom->createElement("retTransp");
                        // todos esses campos s�o obrigat�rios
                        $vServ = $dom->createElement("vServ", trim($dados[1]));
                        $retTransp->appendChild($vServ);

                        $vBCRet = $dom->createElement("vBCRet", trim($dados[2]));
                        $retTransp->appendChild($vBCRet);

                        $pICMSRet = $dom->createElement("pICMSRet", trim($dados[3]));
                        $retTransp->appendChild($pICMSRet);

                        $vICMSRet = $dom->createElement("vICMSRet", trim($dados[4]));
                        $retTransp->appendChild($vICMSRet);

                        $CFOP = $dom->createElement("CFOP", trim($dados[5]));
                        $retTransp->appendChild($CFOP);

                        $cMunFG = $dom->createElement("cMunFG", trim($dados[6]));
                        $retTransp->appendChild($cMunFG);

                        $transp->appendChild($retTransp);
                        break;
                    case "X18":
                        $veicTransp = $dom->createElement("veicTransp");
                        // todos esses campos s�o obrigat�rios
                        $placa = $dom->createElement("placa", trim($dados[1]));
                        $veicTransp->appendChild($placa);

                        $UF = $dom->createElement("UF", trim($dados[2]));
                        $veicTransp->appendChild($UF);

                        $RNTC = $dom->createElement("RNTC", trim($dados[3]));
                        $veicTransp->appendChild($RNTC);


                        $transp->appendChild($veicTransp);
                        break;
                    case "X22":
                        $reboque = $dom->createElement("reboque");
                        if(!$this->vazio($dados[1])) {
                            $placa = $dom->createElement("placa", trim($dados[1]));
                            $reboque->appendChild($placa);
                        }

                        if(!$this->vazio($dados[1])) {
                            $UF = $dom->createElement("UF", trim($dados[2]));
                            $reboque->appendChild($UF);

                        }
                        if(!$this->vazio($dados[3])) {
                            $RNTC = $dom->createElement("RNTC", trim($dados[3]));
                            $reboque->appendChild($RNTC);
                        }
                        $transp->appendChild($reboque);
                        break;
                    case "X26":
                        $vol = $dom->createElement("vol");

                        if(!$this->vazio($dados[1])) {
                            $qVol = $dom->createElement("qVol", trim($dados[1]));
                            $vol->appendChild($qVol);
                        }
                        if(!$this->vazio($dados[2])) {
                            $esp = $dom->createElement("esp", trim($dados[2]));
                            $vol->appendChild($esp);
                        }
                        if(!$this->vazio($dados[3])) {
                            $marca = $dom->createElement("marca", trim($dados[3]));
                            $vol->appendChild($marca);
                        }
                        if(!$this->vazio($dados[4])) {
                            $nVol = $dom->createElement("nVol", trim($dados[4]));
                            $vol->appendChild($nVol);
                        }
                        if(!$this->vazio($dados[5])) {
                            $pesoL = $dom->createElement("pesoL", trim($dados[5]));
                            $vol->appendChild($pesoL);
                        }
                        if(!$this->vazio($dados[6])) {
                            $pesoB = $dom->createElement("pesoB", trim($dados[6]));
                            $vol->appendChild($pesoB);
                        }
                        $transp->appendChild($vol);
                        break;
                    case "X33":
                        $lacres = $dom->createElement("lacres");
                        if(!$this->vazio($dados[1])) {
                            $nLacre = $dom->createElement("nLacre", trim($dados[1]));
                            $lacres->appendChild($nLacre);
                        }
                        $vol->appendChild($lacres);
                        break;
                    case "Y":
                        $cobr = $dom->createElement("cobr");
                        $infNFe->appendChild($cobr);
                        break;
                    case "Y02"://TODO VEREFICAR PQ TAH CRIANDO TAG FEXADA ANTES DE ABRIR
                        $fat = $dom->createElement("fat");
                        if(!$this->vazio($dados[1])) {
                            $nFat = $dom->createElement("nFat", trim($dados[1]));
                            $fat->appendChild($nFat);
                        }
                        if(!$this->vazio($dados[2])) {
                            $vOrig = $dom->createElement("vOrig", trim($dados[2]));
                            $fat->appendChild($vOrig);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vDesc = $dom->createElement("vDesc", trim($dados[3]));
                            $fat->appendChild($vDesc);
                        }
                        if(!$this->vazio($dados[4])) {
                            $vLiq = $dom->createElement("vLiq", trim($dados[4]));
                            $fat->appendChild($vLiq);
                        }
                        $cobr->appendChild($fat);
                        break;
                    case "Y07":
                        $dup = $dom->createElement("dup");
                        if(!$this->vazio($dados[1])) {
                            $nDup = $dom->createElement("nDup", trim($dados[1]));
                            $dup->appendChild($nDup);
                        }
                        if(!$this->vazio($dados[2])) {
                            $dVenc = $dom->createElement("dVenc", trim($dados[2]));
                            $dup->appendChild($dVenc);
                        }
                        if(!$this->vazio($dados[3])) {
                            $vDup = $dom->createElement("vDup", trim($dados[3]));
                            $dup->appendChild($vDup);
                        }
                        $cobr->appendChild($dup);
                        break;
                    case "Z":
                        $infAdic = $dom->createElement("infAdic");
                        if(!$this->vazio($dados[1])) {
                            $infAdFisco = $dom->createElement("infAdFisco", trim($dados[1]));
                            $infAdic->appendChild($infAdFisco);
                        }
                        if(!$this->vazio($dados[2])) {
                            $infCpl = $dom->createElement("infCpl", trim($dados[2]));
                            $infAdic->appendChild($infCpl);
                        }
                        $infNFe->appendChild($infAdic);
                        break;
                    case "Z04":
                        $obsCont = $dom->createElement("obsCont");
                        if(!$this->vazio($dados[1])) {
                            $xCampo = $dom->createElement("xCampo", trim($dados[1]));
                            $obsCont->appendChild($xCampo);
                        }
                        if(!$this->vazio($dados[2])) {
                            $xTexto = $dom->createElement("xTexto", trim($dados[2]));
                            $obsCont->appendChild($xTexto);
                        }
                        $infNFe->appendChild($obsCont);
                        break;

                    case "Z10": //processo referenciado
                        $procRef = $dom->createElement("procRef");
                        if(!$this->vazio($dados[1])) {
                            $nProc = $dom->createElement("nProc", trim($dados[1]));
                            $procRef->appendChild($nProc);
                        }
                        if(!$this->vazio($dados[2])) {
                            $procRef = $dom->createElement("indProc", trim($dados[2]));
                            $procRef->appendChild($indProc);
                        }
                        $infAdic->appendChild($proRef);
                        break;
                    case "ZA"://exportacao
                        $exporta = $dom->createElement("exportacao");
                        if(!$this->vazio($dados[1])) {
                            $UFEmbarq = $dom->createElement("UFEmbarq", trim($dados[1]));
                            $exporta->appendChild($UFEmbraq);
                        }
                        if(!$this->vazio($dados[2])) {
                            $xLocEmabarq = $dom->createElement("xLocEmabarq", trim($dados[2]));
                            $exporta->appendChild($xLocEmabarq);
                        }
                        $infNFe->appendChild($exporta);
                        break;
                    case "ZB": //compra
                        $compra = $dom->createElement("compra");
                        if(!$this->vazio($dados[1])) {
                            $xNEmp = $dom->createElement("xNEmp", trim($dados[1]));
                            $compra->appendChild($xNEmp);
                        }
                        if(!$this->vazio($dados[2])) {
                            $xPed = $dom->createElement("xPed", trim($dados[2]));
                            $compra->appendChild($xPed);
                        }
                        if(!$this->vazio($dados[3])) {
                            $xCont = $dom->createElement("xCont", trim($dados[2]));
                            $compra->appendChild($xCont);
                        }
                        $infNFe->appendChild($compra);
                        break;
            } //end switch

        } //end for

        //salva o xml na variável
        $NFe->appendChild($infNFe);
        $dom->appendChild($NFe);
        $xml = $dom->saveXML();
        $this->xml = $dom->saveXML();
        return $xml;

    } //end function


    /**
     * nfexml2txt
     * Método de conversão das NFe de xml para txt, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas Versão 1.1.1 (29/10/2008)
     * Referente ao modelo de NFe contido na versão 3
     * do manual de integração da NFe (Março 2009)
     *
     * @param string $arq Path do arquivo xml
     * @return string
     */
    public function nfexml2txt($arq) {
        //variavel que irá conter o resultado
        $txt = "";

        //verificar se a string passada como parametro é um arquivo
        if ( is_file($arq) ){
            $matriz[0] = array($arq);
        }
        if ( is_array($arq) ){
            $nnfematriz = count($arq);
            $matriz = $arq;
            $txt = "NOTA FISCAL|$nnfematriz\r\n";
        }

        for ($x = 0; $x < $nnfematriz; $x++ ){
            //carregar o conteúdo do arquivo xml em uma string
            $xml = file_get_contents($matriz[$x]);
            //instanciar o ojeto DOM
            $dom = new DOMDocument();
            //carregar o xml no objeto DOM
            $dom->loadXML($xml);
            //carregar os grupos de dados possíveis da NFe
            $nfeProc    = $dom->getElementsByTagName("nfeProc")->item(0);
            $infNFe     = $dom->getElementsByTagName("infNFe")->item(0);
            $ide        = $dom->getElementsByTagName("ide")->item(0);
            $refNFe     = $dom->getElementsByTagName("refNFe");
            $refNF      = $dom->getElementsByTagName("refNF");
            $emit       = $dom->getElementsByTagName("emit")->item(0);
            $avulsa     = $dom->getElementsByTagName("avulsa")->item(0);
            $dest       = $dom->getElementsByTagName("dest")->item(0);
            $retirada   = $dom->getElementsByTagName("retirada")->item(0);
            $entrega    = $dom->getElementsByTagName("entrega")->item(0);
            $enderEmit  = $dom->getElementsByTagName("enderEmit")->item(0);
            $enderDest  = $dom->getElementsByTagName("enderDest")->item(0);
            $det        = $dom->getElementsByTagName("det");
            $cobr       = $dom->getElementsByTagName("cobr")->item(0);
            $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
            $ISSQNtot   = $dom->getElementsByTagName("ISSQNtot")->item(0);
            $retTrib    = $dom->getElementsByTagName("retTrib")->item(0);
            $transp     = $dom->getElementsByTagName("transp")->item(0);
            $infAdic    = $dom->getElementsByTagName("infAdic")->item(0);
            $procRef    = $dom->getElementsByTagName("procRef")->item(0);
            $exporta    = $dom->getElementsByTagName("exporta")->item(0);
            $compra     = $dom->getElementsByTagName("compra")->item(0);

            //A|versão do schema|id|
            $id = $infNFe->getAttribute("Id") ? $infNFe->getAttribute("Id") : '';
            $versao = $infNFe->getAttribute("versao");
            $txt .= "A|$versao|$id\r\n";
            $this->chave = substr($id,-44);

            //B|cUF|cNF|NatOp|intPag|mod|serie|nNF|dEmi|dSaiEnt|tpNF|cMunFG|TpImp|TpEmis|CDV|TpAmb|FinNFe|ProcEmi|VerProc|
            $cUF = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
            $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue;
            $natOp = $ide->getElementsByTagName('natOp')->item(0)->nodeValue;
            $indPag = $ide->getElementsByTagName('indPag')->item(0)->nodeValue;
            $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
            $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
            $nNF = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
            $dEmi = $ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
            $dSaiEnt = !empty($ide->getElementsByTagName('dSaiEnt')->item(0)->nodeValue) ? $ide->getElementsByTagName('dSaiEnt')->item(0)->nodeValue : '';
            $tpNF = $ide->getElementsByTagName('tpNF')->item(0)->nodeValue;
            $cMunFG = $ide->getElementsByTagName('cMunFG')->item(0)->nodeValue;
            $tpImp = $ide->getElementsByTagName('tpImp')->item(0)->nodeValue;
            $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
            $cDV = $ide->getElementsByTagName('cDV')->item(0)->nodeValue;
            $tpAmb = $ide->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $finNFe = $ide->getElementsByTagName('finNFe')->item(0)->nodeValue;
            $procEmi = $ide->getElementsByTagName('procEmi')->item(0)->nodeValue;
            $verProc = $ide->getElementsByTagName('verProc')->item(0)->nodeValue;
            $txt .= "B|$cUF|$cNF|$natOp|$indPag|$mod|$serie|$nNF|$dEmi|$dSaiEnt|$tpNF|$cMunFG|$tpImp|$tpEmis|$cDV|$tpAmb|$finNFe|$procEmi|$verProc\r\n";

            //B13|refNFe|
            if ( isset($refNFe) ) {
                foreach ( $refNFe as $n => $r){
                    $ref = !empty($refNFe->item($n)->nodeValue) ? $refNFe->item($n)->nodeValue : '';
                    $txt = "B13|$ref\r\n";
                }
            } //fim refNFe

            //B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF|
            if ( isset($refNF) ) {
                foreach ( $refNF as $x => $k){
                    $cUF = !empty($refNF->item($x)->getElementsByTagName('cUF')->nodeValue) ? $refNF->item($x)->getElementsByTagName('cUF')->nodeValue : '';
                    $AAMM = !empty($refNF->item($x)->getElementsByTagName('AAMM')->nodeValue) ? $refNF->item($x)->getElementsByTagName('AAMM')->nodeValue : '';
                    $CNPJ = !empty($refNF->item($x)->getElementsByTagName('CNPJ')->nodeValue) ? $refNF->item($x)->getElementsByTagName('CNPJ')->nodeValue : '';
                    $mod = !empty($refNF->item($x)->getElementsByTagName('mod')->nodeValue) ? $refNF->item($x)->getElementsByTagName('mod')->nodeValue : '';
                    $serie = !empty($refNF->item($x)->getElementsByTagName('serie')->nodeValue) ? $refNF->item($x)->getElementsByTagName('serie')->nodeValue : '';
                    $nUF = !empty($refNF->item($x)->getElementsByTagName('nUF')->nodeValue) ? $refNF->item($x)->getElementsByTagName('nUF')->nodeValue : '';
                    $txt .= "B14|$cUF|$AAMM|$CNPJ|$mod|$serie|$nNF\r\n";
                }
            } //fim refNF

            //C|xNome|xFant|IE|IEST|IM|CNAE|
            $xNome = !empty($emit->getElementsByTagName('xNome')->item(0)->nodeValue) ? $emit->getElementsByTagName('xNome')->item(0)->nodeValue : '';
            $xFant = !empty($emit->getElementsByTagName('xFant')->item(0)->nodeValue) ? $emit->getElementsByTagName('xFant')->item(0)->nodeValue : '';
            $IE = !empty($emit->getElementsByTagName('IE')->item(0)->nodeValue) ? $emit->getElementsByTagName('IE')->item(0)->nodeValue : '';
            $IEST = !empty($emit->getElementsByTagName('IEST')->item(0)->nodeValue) ? $emit->getElementsByTagName('IEST')->item(0)->nodeValue : '';
            $IM = !empty($emit->getElementsByTagName('IM')->item(0)->nodeValue) ? $emit->getElementsByTagName('IM')->item(0)->nodeValue : '';
            $CNAE = !empty($emit->getElementsByTagName('CNAE')->item(0)->nodeValue) ? $emit->getElementsByTagName('CNAE')->item(0)->nodeValue : '';
            $CNPJ = !empty($emit->getElementsByTagName('CNPJ')->item(0)->nodeValue) ? $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue : '';
            $CPF = !empty($emit->getElementsByTagName('CPF')->item(0)->nodeValue) ? $emit->getElementsByTagName('CPF')->item(0)->nodeValue : '';
            $txt .= "C|$xNome|$xFant|$IE|$IEST|$IM|$CNAE\r\n";

            //C02|CNPJ|
            //[ou]
            //C02a|CPF|
            if ( $CPF != '' ) {
                $txt .= "C02a|$CPF\r\n";
            }else {
                $txt .= "C02|$CNPJ\r\n";
            } //fim CPF ou CNPJ

            //C05|xLgr|nro|xCpl|bairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
            $xLgr = !empty($enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
            $nro = !empty($enderEmit->getElementsByTagName("nro")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("nro")->item(0)->nodeValue : '';
            $xCpl = !empty($enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
            $xBairro = !empty($enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
            $cMun = !empty($enderEmit->getElementsByTagName("cMun")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("cMun")->item(0)->nodeValue : '';
            $xMun = !empty($enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue : '';
            $UF = !empty($enderEmit->getElementsByTagName("UF")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("UF")->item(0)->nodeValue : '';
            $CEP = !empty($enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue : '';
            $cPais = !empty($enderEmit->getElementsByTagName("cPais")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("cPais")->item(0)->nodeValue : '';
            $fone = !empty($enderEmit->getElementsByTagName("fone")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("fone")->item(0)->nodeValue : '';
            $txt .= "C05|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF|$CEP|$cPais|$xPais|$fone\r\n";

            //D|CNPJ|xOrgao|matr|xAgente|fone|UF|nDAR|dEmi|vDAR|repEmi|dPag|
            if( isset($avulsa) ){
                $CNPJ = !empty($avulsa->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $xOrgao = !empty($avulsa->getElementsByTagName("xOrgao")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("xOrgao")->item(0)->nodeValue : '';
                $matr = !empty($avulsa->getElementsByTagName("matr")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("matr")->item(0)->nodeValue : '';
                $xAgente = !empty($avulsa->getElementsByTagName("xAgente")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("xAgente")->item(0)->nodeValue : '';
                $fone = !empty($avulsa->getElementsByTagName("fone")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("fone")->item(0)->nodeValue : '';
                $UF = !empty($avulsa->getElementsByTagName("UF")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("UF")->item(0)->nodeValue : '';
                $nDAR = !empty($avulsa->getElementsByTagName("nDAR")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("nDAR")->item(0)->nodeValue : '';
                $dEmi = !empty($avulsa->getElementsByTagName("dEmi")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("dEmi")->item(0)->nodeValue : '';
                $vDAR = !empty($avulsa->getElementsByTagName("vDAR")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("vDAR")->item(0)->nodeValue : '';
                $repEmi = !empty($avulsa->getElementsByTagName("repEmi")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("repEmi")->item(0)->nodeValue : '';
                $dPag = !empty($avulsa->getElementsByTagName("dPag")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("dPag")->item(0)->nodeValue : '';
                $txt .= "D|$CNPJ|$xOrgao|$matr|$xAgente|$fone|$UF|$nDAR|$dEmi|$vDAR|$repEmi|$dPag\r\n";
            } //fim avulsa

            //E|XNome|IE|ISUF|
            $xNome = !empty($dest->getElementsByTagName("xNome")->item(0)->nodeValue) ? $dest->getElementsByTagName("xNome")->item(0)->nodeValue : '';
            $IE = !empty($dest->getElementsByTagName("IE")->item(0)->nodeValue) ? $dest->getElementsByTagName("IE")->item(0)->nodeValue : '';
            $ISUF = !empty($dest->getElementsByTagName("ISUF")->item(0)->nodeValue) ? $dest->getElementsByTagName("ISUF")->item(0)->nodeValue : '';
            $txt .= "E|$xNome|$IE|$ISUF\r\n";
            $CNPJ = !empty($dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
            $CPF = !empty($dest->getElementsByTagName("CPF")->item(0)->nodeValue) ? $dest->getElementsByTagName("CPF")->item(0)->nodeValue : '';

            //E02|CNPJ|
            //[ou]
            //E03|CPF|
            if ($CPF != '' ) {
                $txt .= "E03|$CPF\r\n";
            } else {
                $txt .= "E02|$CNPJ\r\n";
            } //fim o CPF ou CNPJ

            //E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
            $xLgr = !empty($enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
            $nro = !empty($enderDest->getElementsByTagName("nro")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("nro")->item(0)->nodeValue : '';
            $xCpl = !empty($enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
            $xBairro = !empty($enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
            $cMun = !empty($enderDest->getElementsByTagName("cMun")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("cMun")->item(0)->nodeValue : '';
            $xMun = !empty($enderDest->getElementsByTagName("xMun")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xMun")->item(0)->nodeValue : '';
            $UF = !empty($enderDest->getElementsByTagName("UF")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("UF")->item(0)->nodeValue : '';
            $CEP = !empty($enderDest->getElementsByTagName("CEP")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("CEP")->item(0)->nodeValue : '';
            $cPais = !empty($enderDest->getElementsByTagName("cPais")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("cPais")->item(0)->nodeValue : '';
            $xPais = !empty($enderDest->getElementsByTagName("xPais")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xPais")->item(0)->nodeValue : '';
            $fone = !empty($enderDest->getElementsByTagName("fone")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("fone")->item(0)->nodeValue : '';
            $txt .= "E05|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF|$CEP|$cPais|$xPais|$fone\r\n";

            //F|CNPJ|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
            if( isset($retirada) ) {
                $CNPJ = !empty($retirada->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $retirada->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $xLgr = !empty($retirada->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
                $nro = !empty($retirada->getElementsByTagName("nro")->item(0)->nodeValue) ? $retirada->getElementsByTagName("nro")->item(0)->nodeValue : '';
                $xCpl = !empty($retirada->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
                $xBairro = !empty($retirada->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
                $cMun = !empty($retirada->getElementsByTagName("cMun")->item(0)->nodeValue) ? $retirada->getElementsByTagName("cMun")->item(0)->nodeValue : '';
                $xMun = !empty($retirada->getElementsByTagName("xMun")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xMun")->item(0)->nodeValue : '';
                $UF = !empty($retirada->getElementsByTagName("UF")->item(0)->nodeValue) ? $retirada->getElementsByTagName("UF")->item(0)->nodeValue : '';
                $txt .= "F|$CNPJ|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF\r\n";
            } //fim da retirada

            //G|CNPJ|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
            if( isset($entrega) ) {
                $CNPJ = !empty($entrega->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $entrega->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $xLgr = !empty($entrega->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
                $nro = !empty($entrega->getElementsByTagName("nro")->item(0)->nodeValue) ? $entrega->getElementsByTagName("nro")->item(0)->nodeValue : '';
                $xCpl = !empty($entrega->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
                $xBairro = !empty($entrega->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
                $cMun = !empty($entrega->getElementsByTagName("cMun")->item(0)->nodeValue) ? $entrega->getElementsByTagName("cMun")->item(0)->nodeValue : '';
                $xMun = !empty($entrega->getElementsByTagName("xMun")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xMun")->item(0)->nodeValue : '';
                $UF = !empty($entrega->getElementsByTagName("UF")->item(0)->nodeValue) ? $entrega->getElementsByTagName("UF")->item(0)->nodeValue : '';
                $txt .= "G|$CNPJ|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF\r\n";
            } //fim entrega

            //instaciar uma variável para contagem
            $i = 0;
            foreach ($det as $d){
                //H|nItem|infAdProd|
                $nItem = $det->item($i)->getAttribute("nItem");

                $infAdProd = !empty($det->item($i)->getElementsByTagName("infAdProd")->item(0)->nodeValue) ? $det->item($i)->getElementsByTagName("infAdProd")->item(0)->nodeValue : '';
                $txt .= "H|$nItem|$infAdProd\r\n";
                //instanciar os grupos de dados internos da tag det
        	$prod = $det->item($i)->getElementsByTagName("prod")->item(0);
		$imposto = $det->item($i)->getElementsByTagName("imposto")->item(0);
		$ICMS = $imposto->getElementsByTagName("ICMS")->item(0);
		$IPI  = $imposto->getElementsByTagName("IPI")->item(0);
                $DI =  $det->item($i)->getElementsByTagName("DI")->item(0);
                $adi =  $det->item($i)->getElementsByTagName("adi")->item(0);
                $veicProd = $det->item($i)->getElementsByTagName("veicProd")->item(0);
                $med = $det->item($i)->getElementsByTagName("med")->item(0);
                $arma = $det->item($i)->getElementsByTagName("arma")->item(0);
                $comb = $det->item($i)->getElementsByTagName("comb")->item(0);
                $II = $det->item($i)->getElementsByTagName("II")->item(0);
                $PIS = $det->item($i)->getElementsByTagName("PIS")->item(0);
                $PISST = $det->item($i)->getElementsByTagName("PISST")->item(0);
                $COFINS = $det->item($i)->getElementsByTagName("COFINS")->item(0);
                $COFINSST = $det->item($i)->getElementsByTagName("COFINSST")->item(0);
                $ISSQN = $det->item($i)->getElementsByTagName("ISSQN")->item(0);
                $i++;
                //I|cProd|cEAN|xProd|NCM|EXTIPI|genero|CFOP|uCom|qCom|vUnCom|vProd|cEANTrib|uTrib|qTrib|vUnTrib|vFrete|vSeg|vDesc|
                $cProd      =  !empty($prod->getElementsByTagName("cProd")->item(0)->nodeValue) ? $prod->getElementsByTagName("cProd")->item(0)->nodeValue : '';
                $cEAN       =  !empty($prod->getElementsByTagName("cEAN")->item(0)->nodeValue) ? $prod->getElementsByTagName("cEAN")->item(0)->nodeValue : '';
                $xProd      =  !empty($prod->getElementsByTagName("xProd")->item(0)->nodeValue) ? $prod->getElementsByTagName("xProd")->item(0)->nodeValue : '';
                $NCM        =  !empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue) ? $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
                $EXTIPI     =  !empty($prod->getElementsByTagName("EXTIPI")->item(0)->nodeValue) ? $prod->getElementsByTagName("EXTIPI")->item(0)->nodeValue : '';
                $genero     =  !empty($prod->getElementsByTagName("genero")->item(0)->nodeValue) ? $prod->getElementsByTagName("genero")->item(0)->nodeValue : '';
                $CFOP       =  !empty($prod->getElementsByTagName("CFOP")->item(0)->nodeValue) ? $prod->getElementsByTagName("CFOP")->item(0)->nodeValue : '';
                $uCom       =  !empty($prod->getElementsByTagName("uCom")->item(0)->nodeValue) ? $prod->getElementsByTagName("uCom")->item(0)->nodeValue : '';
                $qCom       =  !empty($prod->getElementsByTagName("qCom")->item(0)->nodeValue) ? $prod->getElementsByTagName("qCom")->item(0)->nodeValue : '';
                $vUnCom     =  !empty($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue) ? $prod->getElementsByTagName("vUnCom")->item(0)->nodeValue : '';
                $vProd      =  !empty($prod->getElementsByTagName("vProd")->item(0)->nodeValue) ? $prod->getElementsByTagName("vProd")->item(0)->nodeValue : '';
                $cEANTrib   =  !empty($prod->getElementsByTagName("cEANTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("cEANTrib")->item(0)->nodeValue : '';
                $uTrib      =  !empty($prod->getElementsByTagName("uTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("uTrib")->item(0)->nodeValue : '';
                $qTrib      =  !empty($prod->getElementsByTagName("qTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("qTrib")->item(0)->nodeValue : '';
                $vUnTrib    =  !empty($prod->getElementsByTagName("vUnTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("vUnTrib")->item(0)->nodeValue : '';
                $vFrete     =  !empty($prod->getElementsByTagName("vFrete")->item(0)->nodeValue) ? $prod->getElementsByTagName("vFrete")->item(0)->nodeValue : '';
                $vSeg       =  !empty($prod->getElementsByTagName("vSeg")->item(0)->nodeValue) ? $prod->getElementsByTagName("vSeg")->item(0)->nodeValue : '';
                $vDesc      =  !empty($prod->getElementsByTagName("vDesc")->item(0)->nodeValue) ? $prod->getElementsByTagName("vDesc")->item(0)->nodeValue : '';
                $txt .= "I|$cProd|$cEAN|$xProd|$NCM|$EXTIPI|$genero|$CFOP|$uCom|$qCom|$vUnCom|$vProd|$cEANTrib|$uTrib|$qTrib|$vUnTrib|$vFrete|$vSeg|$vDesc\r\n";

                //I18|nDI|dDI|xLocDesemb|UFDesemb|dDesemb|cExportador|
                if ( isset($DI) ){
                    $nDI = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $dDI = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $xLocDesemb = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $UFDesemb = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $dDesemb = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $cExportador = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $txt .= "I18|$nDI|$dDI|$xLocDesemb|$UFDesemb|$dDesemb|$cExportador\r\n";
                } //fim importação

                //I25|nAdicao|nSeqAdic|cFabricante|vDescDI|
                if ( isset($adi) ){
                    $nAdicao = !empty($adi->getElementsByTagName("nAdicao")->item(0)->nodeValue) ? $adi->getElementsByTagName("nAdicao")->item(0)->nodeValue : '';
                    $nSeqAdic = !empty($adi->getElementsByTagName("nSeqAdic")->item(0)->nodeValue) ? $adi->getElementsByTagName("nSeqAdic")->item(0)->nodeValue : '';
                    $cFabricante = !empty($adi->getElementsByTagName("cFabricante")->item(0)->nodeValue) ? $adi->getElementsByTagName("cFabricante")->item(0)->nodeValue : '';
                    $vDescDI = !empty($adi->getElementsByTagName("vDescDI")->item(0)->nodeValue) ? $adi->getElementsByTagName("vDescDI")->item(0)->nodeValue : '';
                    $txt .= "I25|$nAdicao|$nSeqAdic|$cFabricante|$vDescDI\r\n";
                } //fim adição

                //J|tpOp|chassi|cCor|xCor|pot|CM3|pesoL|pesoB|nSerie|tpComb|nMotor|CMKG|dist|RENAVAM|anoMod|anoFab|tpPint|tpVeic|espVeic|vIN|condVeic|cMod|
                if ( isset($veicProd) ){
                    $tpOp = !empty($veicProd->getElementsByTagName("tpOp")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpOp")->item(0)->nodeValue : '';
                    $chassi = !empty($veicProd->getElementsByTagName("chassi")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("chassi")->item(0)->nodeValue : '';
                    $cCor = !empty($veicProd->getElementsByTagName("cCor")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("cCor")->item(0)->nodeValue : '';
                    $xCor = !empty($veicProd->getElementsByTagName("xCor")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("xCor")->item(0)->nodeValue : '';
                    $pot = !empty($veicProd->getElementsByTagName("pot")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("pot")->item(0)->nodeValue : '';
                    $CM3 = !empty($veicProd->getElementsByTagName("CM3")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("CM3")->item(0)->nodeValue : '';
                    $pesoL = !empty($veicProd->getElementsByTagName("pesoL")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("pesoL")->item(0)->nodeValue : '';
                    $pesoB = !empty($veicProd->getElementsByTagName("pesoB")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("pesoB")->item(0)->nodeValue : '';
                    $nSerie = !empty($veicProd->getElementsByTagName("nSerie")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("nSerie")->item(0)->nodeValue : '';
                    $tpComb = !empty($veicProd->getElementsByTagName("tpComb")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpComb")->item(0)->nodeValue : '';
                    $nMotor = !empty($veicProd->getElementsByTagName("nMotor")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("nMotor")->item(0)->nodeValue : '';
                    $CMKG = !empty($veicProd->getElementsByTagName("CMKG")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("CMKG")->item(0)->nodeValue : '';
                    $dist = !empty($veicProd->getElementsByTagName("dist")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("dist")->item(0)->nodeValue : '';
                    $RENAVAM = !empty($veicProd->getElementsByTagName("RENAVAM")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("RENAVAM")->item(0)->nodeValue : '';
                    $anoMod = !empty($veicProd->getElementsByTagName("anoMod")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("anoMod")->item(0)->nodeValue : '';
                    $anoFab = !empty($veicProd->getElementsByTagName("anoFab")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("anoFab")->item(0)->nodeValue : '';
                    $tpPint = !empty($veicProd->getElementsByTagName("tpPint")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpPint")->item(0)->nodeValue : '';
                    $tpVeic = !empty($veicProd->getElementsByTagName("tpVeic")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpVeic")->item(0)->nodeValue : '';
                    $vIN = !empty($veicProd->getElementsByTagName("vIN")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("vIN")->item(0)->nodeValue : '';
                    $condVeic = !empty($veicProd->getElementsByTagName("condVeic")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("condVeic")->item(0)->nodeValue : '';
                    $cMod = !empty($veicProd->getElementsByTagName("cMod")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("cMod")->item(0)->nodeValue : '';
                    $txt .= "J|$tpOp|$chassi|$cCor|$xCor|$pot|$CM3|$pesoL|$pesoB|$nSerie|$tpComb|$nMotor|$CMKG|$dist|$RENAVAM|$anoMod|$anoFab|$tpPint|$tpVeic|$espVeic|$vIN|$condVeic|$cMod\r\n";
                } // fim veiculos novos

                //K|nLote|qLote|dFab|dVal|vPMC|
                if ( isset($med) ){
                    $nLote = !empty($med->getElementsByTagName("nLote")->item(0)->nodeValue) ? $med->getElementsByTagName("nLote")->item(0)->nodeValue : '';
                    $qLote = !empty($med->getElementsByTagName("qLote")->item(0)->nodeValue) ? $med->getElementsByTagName("qLote")->item(0)->nodeValue : '';
                    $dFab = !empty($med->getElementsByTagName("dFab")->item(0)->nodeValue) ? $med->getElementsByTagName("dFab")->item(0)->nodeValue : '';
                    $dVal = !empty($med->getElementsByTagName("dVal")->item(0)->nodeValue) ? $med->getElementsByTagName("dVal")->item(0)->nodeValue : '';
                    $vPMC = !empty($med->getElementsByTagName("vPMC")->item(0)->nodeValue) ? $med->getElementsByTagName("vPMC")->item(0)->nodeValue : '';
                    $txt .= "K|$nLote|$qLote|$dFab|$dVal|$vPMC\r\n";
                } // fim medicamentos

                //L|tpArma|nSerie|nCano|descr|
                if ( isset($arma) ){
                    $tpArma = !empty($arma->getElementsByTagName("tpArma")->item(0)->nodeValue) ? $arma->getElementsByTagName("tpArma")->item(0)->nodeValue : '';
                    $nSerie = !empty($arma->getElementsByTagName("nSerie")->item(0)->nodeValue) ? $arma->getElementsByTagName("nSerie")->item(0)->nodeValue : '';
                    $nCano = !empty($arma->getElementsByTagName("nCano")->item(0)->nodeValue) ? $arma->getElementsByTagName("nCano")->item(0)->nodeValue : '';
                    $descr = !empty($arma->getElementsByTagName("descr")->item(0)->nodeValue) ? $arma->getElementsByTagName("descr")->item(0)->nodeValue : '';
                    $txt .= "L|$tpArma|$nSerie|$nCano|$descr\r\n";
                } // fim armas

                //combustiveis
                if ( isset($comb) ){
                    //instanciar sub grups da tag comb
                    $CIDE = $comb->getElementsByTagName("CIDE")->item(0);
                    $ICMSComb = $comb->getElementsByTagName("ICMSComb")->item(0);
                    $ICMSInter = $comb->getElementsByTagName("ICMSInter")->item(0);
                    $ICMSCons = $comb->getElementsByTagName("ICMSCons")->item(0);

                    $cProdANP = !empty($comb->getElementsByTagName("cProdANP")->item(0)->nodeValue) ? $comb->getElementsByTagName("cProdANP")->item(0)->nodeValue : '';
                    $CODIF = !empty($comb->getElementsByTagName("CODIF")->item(0)->nodeValue) ? $comb->getElementsByTagName("CODIF")->item(0)->nodeValue : '';
                    $qTemp = !empty($comb->getElementsByTagName("qTemp")->item(0)->nodeValue) ? $comb->getElementsByTagName("qTemp")->item(0)->nodeValue : '';
                    //L01|cProdANP|CODIF|qTemp|
                    $txt .= "L01|$cProdANP|$CODIF|$qTemp\r\n";
                    //grupo CIDE
                    if ( isset($CIDE) ){
                        //L105|qBCProd|vAliqProd|vCIDE|
                        $qBCprod = !empty($CIDE->getElementsByTagName("qBCprod")->item(0)->nodeValue) ? $CIDE->getElementsByTagName("qBCprod")->item(0)->nodeValue : '';
                        $vAliqProd = !empty($CIDE->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $CIDE->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                        $vCIDE = !empty($CIDE->getElementsByTagName("vCIDE")->item(0)->nodeValue) ? $CIDE->getElementsByTagName("vCIDE")->item(0)->nodeValue : '';
                        $txt .= "L105|$qBCProd|$vAliqProd|$vCIDE\r\n";
                    } // fim grupo CIDE
                    //grupo ICMSComb
                    if ( isset($ICMSComb) ){
                        //L109|VBCICMS|VICMS|VBCICMSST|VICMSST|
                        $vBCICMS = !empty($ICMSComb->getElementsByTagName("vBCICMS")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vBCICMS")->item(0)->nodeValue : '';
                        $vICMS = !empty($ICMSComb->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
                        $vBCICMSST = !empty($ICMSComb->getElementsByTagName("vBCICMSST")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vBCICMSST")->item(0)->nodeValue : '';
                        $vICMSST = !empty($ICMSComb->getElementsByTagName("vICMSST")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vBCICMSST")->item(0)->nodeValue : '';
                        $txt .= "L109|$vBCICMS|$vICMS|$vBCICMSST|$vICMSST\r\n";
                    } // fim ICMSComb

                    //grupo ICMSInter
                    if ( isset($ICMSInter) ){
                         //L114|VBCICMSST|VICMSST|
                         $vBCICMSSTDest = !empty($ICMSInter->getElementsByTagName("vBCICMSSTDest")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vBCICMSST")->item(0)->nodeValue : '';
                         $vICMSSTDest = !empty($ICMSInter->getElementsByTagName("vICMSSTDest")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vICMSST")->item(0)->nodeValue : '';
                         $txt .= "L114|$vBCICMSSTDest|$vICMSSTDest|";
                    } //fim ICMSInter

                    //grupo ICMSCons
                    if ( isset($ICMSCons) ){
                        //L117|VBCICMSSTCons|VICMSSTCons|UFCons|
                        $vBCICMSSTCons = !empty($ICMSInter->getElementsByTagName("vBCICMSSTCons")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vBCICMSSTCons")->item(0)->nodeValue : '';
                        $vICMSSTCons = !empty($ICMSInter->getElementsByTagName("vICMSSTCons")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vICMSSTCons")->item(0)->nodeValue : '';
                        $UFCons = !empty($ICMSInter->getElementsByTagName("UFCons")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("UFCons")->item(0)->nodeValue : '';
                    } //fim ICMSCons


                } //fim combustiveis

                //M|
                $txt .= "M\r\n";
                //N|
                $txt .= "N\r\n";
                //N02|orig|CST|modBC|vBC|pICMS|vICMS|
                $orig = !empty($ICMS->getElementsByTagName("orig")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("orig")->item(0)->nodeValue : '';
                $CST = (string) !empty($ICMS->getElementsByTagName("CST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("CST")->item(0)->nodeValue : '';
                $modBC = !empty($ICMS->getElementsByTagName("modBC")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("modBC")->item(0)->nodeValue : '';
                $vBC = !empty($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                $pICMS = !empty($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue : '';
                $vICMS = !empty($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
                $modBCST = !empty($ICMS->getElementsByTagName("modBCST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("modBCST")->item(0)->nodeValue : '';
                $pMVAST = !empty($ICMS->getElementsByTagName("pMVAST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pMVAST")->item(0)->nodeValue : '';
                $pRedBCST = !empty($ICMS->getElementsByTagName("pRedBCST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pRedBCST")->item(0)->nodeValue : '';
                $vBCST = !empty($ICMS->getElementsByTagName("vBCST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vBCST")->item(0)->nodeValue : '';
                $pICMSST = !empty($ICMS->getElementsByTagName("pICMSST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pICMSST")->item(0)->nodeValue : '';
                $vICMSST = !empty($ICMS->getElementsByTagName("vICMSSTS")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vICMSST")->item(0)->nodeValue : '';
                switch ($CST) {
                    case '00': //CST 00 TRIBUTADO INTEGRALMENTE
                        $txt .= "N02|$orig|$CST|$modBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '10': //CST 10 TRIBUTADO E COM COBRANCA DE ICMS POR SUBSTUICAO TRIBUTARIA
                        $txt .= "N03|$orig|$CST|$modBC|$vBC|$pICMS|$vICMS|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                    case '20': //CST 20 COM REDUCAO DE BASE DE CALCULO
                        $txt .= "N04|$orig|$CST|$modBC|$pRedBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '30': //CST 30 ISENTA OU NAO TRIBUTADO E COM COBRANCA DO ICMS POR ST
                        $txt .= "N05|$orig|$CST|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                    case '40': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                        $txt .= "N06|$orig|$CST\r\n";
                        break;
                    case '41': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                        $txt .= "N06|$orig|$CST\r\n";
                        break;
                    case '50': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                        $txt .= "N06|$orig|$CST\r\n";
                        break;
                    case '51': //CST 51 DIFERIMENTO - A EXIGENCIA DO PREECNCHIMENTO DAS INFORMAS DO ICMS DIFERIDO FICA A CRITERIO DE CADA UF
                        $txt .= "N07|$orig|$CST|$modBC|$pRedBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '60': //CST 60 ICMS COBRADO ANTERIORMENTE POR ST
                        $txt .= "N08|$orig|$CST|$vBCST|$vICMSST\r\n";
                        break;
                    case '70': //CST 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
                        $txt .= "N09|$orig|$CST|$modBC|$pRedBC|$vBC|$pICMS|$vICMS|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                    case '90': //CST - 90 Outros
                        $txt .= "N10|$orig|$CST|$modBC|$vBC|$pRedBC|$pICMS|$vICMS|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                } // fim switch

                if ( isset($IPI) ){
                    //O|clEnq|CNPJProd|cSelo|qSelo|cEnq|
                    $clEnq = !empty($IPI->getElementsByTagName("clEnq")->item(0)->nodeValue) ? $IPI->getElementsByTagName("clEnq")->item(0)->nodeValue : '';
                    $CNPJProd = !empty($IPI->getElementsByTagName("CNPJProd")->item(0)->nodeValue) ? $IPI->getElementsByTagName("CNPJProd")->item(0)->nodeValue : '';
                    $cSelo = !empty($IPI->getElementsByTagName("clEnq")->item(0)->nodeValue) ? $IPI->getElementsByTagName("cSelo")->item(0)->nodeValue : '';
                    $qSelo = !empty($IPI->getElementsByTagName("qSelo")->item(0)->nodeValue) ? $IPI->getElementsByTagName("qSelo")->item(0)->nodeValue : '';
                    $cEnq = !empty($IPI->getElementsByTagName("cEnq")->item(0)->nodeValue) ? $IPI->getElementsByTagName("cEnq")->item(0)->nodeValue : '';
                    $txt .= "O|$clEnq|$CNPJProd|$cSelo|$qSelo|$cEnq\r\n";
                    //grupo de tributação de IPI
                    $IPITrib = $IPI->getElementsByTagName("IPITrib")->item(0);
                    if ( isset($IPITrib) ){
                        $CST = (string) !empty($IPITrib->getElementsByTagName("CST")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("CST")->item(0)->nodeValue : '';
                        $vIPI = !empty($IPITrib->getElementsByTagName("vIPI")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("vIPI")->item(0)->nodeValue : '';
                        $vBC = !empty($IPITrib->getElementsByTagName("vBC")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                        $pIPI = !empty($IPITrib->getElementsByTagName("pIPI")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("pIPI")->item(0)->nodeValue : '';
                        $qUnid = !empty($IPITrib->getElementsByTagName("qUnid")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("qUnid")->item(0)->nodeValue : '';
                        $vUnid = !empty($IPITrib->getElementsByTagName("vUnid")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("vUnid")->item(0)->nodeValue : '';
                        switch ($CST){
                            case '00': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '49': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '50': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '99': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '01': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '02': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '03': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '04': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '51': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '52': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '53': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                 $txtIPI = "O08|$CST\r\n";
                            break;
                            case '54': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '55': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                        } // fim switch
        		//
                        if (substr($txtIPI,0,3) == 'O007' ) {
                             if ( $pIPI != '' ) {
                                 //O10|VBC|PIPI|
                                 $txtIPI .= "O10|$vBC|$pIPI\r\n";
                             } else {
                                 //O11|QUnid|VUnid|
                                 $txtIPI .= "O11|$qUnid|$vUnid\r\n";
                             } //fim if
                        } //fim if
                    } //fim ipi trib
                } // fim IPI
                $txt .= $txtIPI;

                //P|vBC|vDespAdu|vII|vIOF|
                if ( isset($II) ) {
                    $vBC = !empty($II->getElementsByTagName("vBC")->item(0)->nodeValue) ? $II->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $vDespAdu = !empty($II->getElementsByTagName("vDespAdu")->item(0)->nodeValue) ? $II->getElementsByTagName("vDespAdu")->item(0)->nodeValue : '';
                    $vII = !empty($II->getElementsByTagName("vII")->item(0)->nodeValue) ? $II->getElementsByTagName("vII")->item(0)->nodeValue : '';
                    $vIOF = !empty($II->getElementsByTagName("vIOF")->item(0)->nodeValue) ? $II->getElementsByTagName("vIOF")->item(0)->nodeValue : '';
                    $txt .= "P|$vBC|$vDespAdu|$vII|$vIOF\r\n";
                } // fim II

    		//monta dados do PIS
                if ( isset($PIS) ) {
                    //Q|
                    $txt .= "Q\r\n";
                    $CST = !empty($PIS->getElementsByTagName("CST")->item(0)->nodeValue) ? $PIS->getElementsByTagName("CST")->item(0)->nodeValue : '';
                    $vBC = !empty($PIS->getElementsByTagName("vBC")->item(0)->nodeValue) ? $PIS->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pPIS = !empty($PIS->getElementsByTagName("pPIS")->item(0)->nodeValue) ? $PIS->getElementsByTagName("pPIS")->item(0)->nodeValue : '';
                    $vPIS = !empty($PIS->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $PIS->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($PIS->getElementsByTagName("qBCProd")->item(0)->nodeValue) ? $PIS->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($PIS->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $PIS->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    if ( $CST == '01' || $CST == '02'){
                        //Q02|CST|vBC|pPIS|vPIS| // PIS TRIBUTADO PELA ALIQUOTA
                        $txt .= "Q02|$CST|$vBC|$pPIS|$vPIS\r\n";
                    }
                    if ( $CST == '03' ) {
                        //Q03|CST|qBCProd|vAliqProd|vPIS| //PIS TRIBUTADO POR QTDE
                        $txt .= "Q03|$CST|$qBCProd|$vAliqProd|$vPIS\r\n";
                    }
                    if ( $CST == '04' || $CST == '06' || $CST == '07' || $CST == '08' || $CST == '09') {
                        //Q04|CST| //PIS não tributado
                        $txt .= "Q04|$CST\r\n";
                    }
                    if ( $CST == '99' ) {
                        //Q05|CST|vPIS| //PIS OUTRAS OPERACOES
                        $txt .= "Q05|$CST|$vPIS\r\n";
                        //Q07|vBC|pPIS|
                        $txt .= "Q07|$vBC|$pPIS\r\n";
                        //Q10|qBCProd|vAliqProd|
                        $txt .= "Q10|$qBCProd|$vAliqProd\r\n";
                    }
                } //fim PIS

                //monta dados do PIS em Substituição Tributária
                if ( isset($PISST) ) {
                    $vPIS = !empty($PISST->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $PISST->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
                    $vBC = !empty($PISST->getElementsByTagName("vBC")->item(0)->nodeValue) ? $PISST->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pPIS = !empty($PISST->getElementsByTagName("pPIS")->item(0)->nodeValue) ? $PISST->getElementsByTagName("pPIS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($PISST->getElementsByTagName("qBCProd")->item(0)->nodeValue) ? $PISST->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($PISST->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $PISST->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    //R|vPIS|
                    $txt .= "R|$vPIS\r\n";
                    //R02|vBC|pPIS|
                    $txt .= "R02|$vBC|$pPIS\r\n";
                    //R04|qBCProd|vAliqProd|
                    $txt .= "R04|$qBCProd|$vAliqProd\r\n";
                } //fim PISST

                //monta dados do COFINS
                if ( isset($COFINS) ) {
                    //S|
                    $txt .= "S\r\n";
                    $CST = !empty($COFINS->getElementsByTagName("CST")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("CST")->item(0)->nodeValue : '';
                    $vBC = !empty($COFINS->getElementsByTagName("vBC")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pCOFINS = !empty($COFINS->getElementsByTagName("pCOFINS")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("pCOFINS")->item(0)->nodeValue : '';
                    $vCOFINS = !empty($COFINS->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($COFINS->getElementsByTagName("qBCProdC")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($COFINS->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    if ($CST == '01' || $CST == '02' ){
                        //S02|CST|VBC|PCOFINS|VCOFINS|
                        $txt .= "S02|$CST|$vBC|$pCOFINS|$vCOFINS\r\n";
                    }
                    if ( $CST == '03'){
                        //S03|CST|QBCProd|VAliqProd|VCOFINS|
                        $txt .= "S03|$CST|$qBCProd|$vAliqProd|$vCOFINS\r\n";
                    }
                    if ( $CST == '04' || $CST == '06' || $CST == '07' || $CST == '08' || $CST == '09' ){
                        //S04|CST|
                        $txt .= "S04|$CST\r\n";
                    }
                    if ( $CST == '99' ){
                        //S05|CST|VCOFINS|
                        $txt .= "S05|$CST|$vCOFINS\r\n";
                        //S07|VBC|PCOFINS|
                        $txt .= "S07|$vBC|$pCOFINS\r\n";
                        //S09|QBCProd|VAliqProd|
                        $txt .= "S09|$qBCProd|$vAliqProd\r\n";
                    }
                } //fim COFINS

                //monta dados do COFINS em Substituição Tributária
                if ( isset($COFINSST) ) {
                    $vCOFINS = !empty($COFINSST->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
                    $vBC = !empty($COFINSST->getElementsByTagName("vBC")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pCOFINS = !empty($COFINSST->getElementsByTagName("pCOFINS")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("pCOFINS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($COFINSST->getElementsByTagName("qBCProd")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($COFINSST->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    //T|VCOFINS|
                    $txt .= "T|$vCOFINS\r\n";
                    //T02|VBC|PCOFINS|
                    $txt .= "T02|$vBC|$pCOFINS\r\n";
                    //T04|QBCProd|VAliqProd|
                    $txt .= "T04|$qBCProd|$vAliqProd\r\n";
                } //fim COFINSST

                //monta dados do ISS
                if ( isset($ISSQN) ) {
                    //U|vBC|vAliq|vISSQN|cMunFG|cListServ|
                    $vBC = !empty($ISSQN->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $vAliq = !empty($ISSQN->getElementsByTagName("vAliq")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("vAliq")->item(0)->nodeValue : '';
                    $vISSQN = !empty($ISSQN->getElementsByTagName("vISSQN")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("vISSQN")->item(0)->nodeValue : '';
                    $cMunFG = !empty($ISSQN->getElementsByTagName("cMunFG")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("cMunFG")->item(0)->nodeValue : '';
                    $cListServ = !empty($ISSQN->getElementsByTagName("cListServ")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("cListServ")->item(0)->nodeValue : '';
                    $txt .= "U|$vBC|$vAliq|$vISSQN|$cMunFG|$cListServ\r\n";
                } //fim ISSQN

        } // fim foreach produtos

        //W|
        $txt .= "W\r\n";
        $vBC = !empty($ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue : '';
        $vICMS = !empty($ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
        $vBCST = !empty($ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue : '';
        $vST = !empty($ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue : '';
        $vProd = !empty($ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue : '';
        $vFrete = !empty($ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue : '';
        $vSeg = !empty($ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue : '';
        $vDesc = !empty($ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue : '';
        $vII = !empty($ICMSTot->getElementsByTagName("vII")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vII")->item(0)->nodeValue : '';
        $vIPI = !empty($ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue : '';
        $vPIS = !empty($ICMSTot->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
        $vCOFINS = !empty($ICMSTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
        $vOutro = !empty($ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue : '';
        $vNF = !empty($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue : '';

        //W02|vBC|vICMS|vBCST|vST|vProd|vFrete|vSeg|vDesc|vII|vIPI|vPIS|vCOFINS|vOutro|vNF|
        $txt .= "W02|$vBC|$vICMS|$vBCST|$vST|$vProd|$vFrete|$vSeg|$vDesc|$vII|$vIPI|$vPIS|$vCOFINS|$vOutro|$vNF\r\n";

        // monta dados do total de ISS
        if (isset($ISSQNtot)){
            //W17|vServ|vBC|vISS|vPIS|vCOFINS|
            $vServ = !empty($ISSQNTot->getElementsByTagName("vServ")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vServ")->item(0)->nodeValue : '';
            $vBC = !empty($ISSQNTot->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vBC")->item(0)->nodeValue : '';
            $vISS = !empty($ISSQNTot->getElementsByTagName("vISS")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vISS")->item(0)->nodeValue : '';
            $vPIS = !empty($ISSQNTot->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
            $vCOFINS = !empty($ISSQNTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
            $txt .= "W17|$vServ|$vBC|$vISS|$vPIS|$vCOFINS\r\n";
        } //fim ISSQNtot

        //monta dados da Retenção de tributos
        if ( isset($retTrib) ) {
            //W23|VRetPIS|VRetCOFINS|VRetCSLL|VBCIRRF|VIRRF|VBCRetPrev|VRetPrev|
            $vRetPIS = !empty($retTrib->getElementsByTagName("vRetPIS")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetPIS")->item(0)->nodeValue : '';
            $vRetCOFINS = !empty($retTrib->getElementsByTagName("vRetCOFINS")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetCOFINS")->item(0)->nodeValue : '';
            $vRetCSLL = !empty($retTrib->getElementsByTagName("vRetCSLL")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetCSLL")->item(0)->nodeValue : '';
            $vBCIRRF = !empty($retTrib->getElementsByTagName("vBCIRRF")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vBCIRRF")->item(0)->nodeValue : '';
            $vIRRF = !empty($retTrib->getElementsByTagName("vIRRF")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vIRRF")->item(0)->nodeValue : '';
            $vBCRetPrev = !empty($retTrib->getElementsByTagName("vBCRetPrev")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vBCRetPrev")->item(0)->nodeValue : '';
            $vRetPrev = !empty($retTrib->getElementsByTagName("vRetPrev")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetPrev")->item(0)->nodeValue : '';
            $txt .= "W23|$vRetPIS|$vRetCOFINS|$vRetCSLL|$vBCIRRF|$vIRRF|$vBCRetPrev|$vRetPrev\r\n";
        }

        //monta dados de Transportes
        if ( isset($transp) ) {
            //instancia sub grupos da tag transp
            $transporta = $dom->getElementsByTagName("transporta")->item(0);
            $retTransp  = $dom->getElementsByTagName("retTransp")->item(0);
            $veicTransp = $dom->getElementsByTagName("veicTransp")->item(0);
            $reboque = $dom->getElementsByTagName("reboque");
            $vol = $dom->getElementsByTagName("vol");
            $lacres = $dom->getElementsByTagName("lacres");

            //X|ModFrete|
            $modFrete = !empty($transp->getElementsByTagName("modFrete")->item(0)->nodeValue) ? $transp->getElementsByTagName("modFrete")->item(0)->nodeValue : '';
            $txt .= "X|$modFrete\r\n";
            if ( isset($transporta) ){
                $CNPJ = !empty($transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $CPF = !empty($transporta->getElementsByTagName("CPF")->item(0)->nodeValue) ? $transporta->getElementsByTagName("CPF")->item(0)->nodeValue : '';
                $IE = !empty($transporta->getElementsByTagName("IE")->item(0)->nodeValue) ? $transporta->getElementsByTagName("IE")->item(0)->nodeValue : '';
                $xNome = !empty($transporta->getElementsByTagName("xNome")->item(0)->nodeValue) ? $transporta->getElementsByTagName("xNome")->item(0)->nodeValue : '';
                $xEnder = !empty($transporta->getElementsByTagName("xEnder")->item(0)->nodeValue) ? $transporta->getElementsByTagName("xEnder")->item(0)->nodeValue : '';
                $xMun = !empty($transporta->getElementsByTagName("xMun")->item(0)->nodeValue) ? $transporta->getElementsByTagName("xMun")->item(0)->nodeValue : '';
                $UF = !empty($transporta->getElementsByTagName("UF")->item(0)->nodeValue) ? $transporta->getElementsByTagName("UF")->item(0)->nodeValue : '';
                //X03|XNome|IE|XEnder|UF|XMun|
                $txt .= "X03|$xNome|$IE|$xEnder|$UF|$xMun\r\n";
                    if ( $CNPJ != '' ) {
                        //X04|CNPJ|
                        $txt .= "X04|$CNPJ\r\n";
                    } else {
                        //X05|CPF|
                        $txt .= "X05|$CPF\r\n";
                    } //fim if cpf ou cnpj
                } // fim transporta

                //monta dados da retenção tributária de transporte
                if ( isset($retTransp) ) {
                    $vServ = !empty($retTransp->getElementsByTagName("vServ")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("vServ")->item(0)->nodeValue : '';
                    $vBCRet = !empty($retTransp->getElementsByTagName("vBCRet")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("vBCRet")->item(0)->nodeValue : '';
                    $pICMSRet = !empty($retTransp->getElementsByTagName("pICMSRet")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("pICMSRet")->item(0)->nodeValue : '';
                    $vICMSRet = !empty($retTransp->getElementsByTagName("vICMSRet")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("vICMSRet")->item(0)->nodeValue : '';
                    $CFOP = !empty($retTransp->getElementsByTagName("CFOP")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("CFOP")->item(0)->nodeValue : '';
                    $cMunFG = !empty($retTransp->getElementsByTagName("cMunFG")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("cMunFG")->item(0)->nodeValue : '';
                    //X11|VServ|VBCRet|PICMSRet|VICMSRet|CFOP|CMunFG|
                    $txt .= "X11|$vServ|$vBCRet|$pICMSRet|$vICMSRet|$CFOP|$cMunFG\r\n";
                } // fim rettransp

                //monta dados de identificação dos veiculos utilizados no transporte
                if ( isset($veicTransp) ) {
                    //X18|Placa|UF|RNTC|
                    $placa = !empty($veicTransp->getElementsByTagName("placa")->item(0)->nodeValue) ? $veicTransp->getElementsByTagName("placa")->item(0)->nodeValue : '';
                    $UF = !empty($veicTransp->getElementsByTagName("UF")->item(0)->nodeValue) ? $veicTransp->getElementsByTagName("UF")->item(0)->nodeValue : '';
                    $RNTC = !empty($veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue) ? $veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue : '';
                    $txt .= "X18|$placa|$UF|$RNTC\r\n";
                } //fim veicTransp

                //monta dados de identificação dos reboques utilizados no transporte
                if ( isset($reboque) ){
                    foreach ($reboque as $n => $reb){
                        $placa = !empty($reboque->item($n)->getElementsByTagName("placa")->item(0)->nodeValue) ? $reboque->item($n)->getElementsByTagName("placa")->item(0)->nodeValue : '';
                        $UF = !empty($reboque->item($n)->getElementsByTagName("UF")->item(0)->nodeValue) ? $reboque->item($n)->getElementsByTagName("UF")->item(0)->nodeValue : '';
                        $RNTC = !empty($reboque->item($n)->getElementsByTagName("RNTC")->item(0)->nodeValue) ? $reboque->item($n)->getElementsByTagName("RNTC")->item(0)->nodeValue : '';
                        //X22|Placa|UF|RNTC|
                        $txt .= "X22|$placa|$UF|$RNTC\r\n";
                    } //fim foreach
                } //fim reboque

                //monta dados dos volumes transpotados
                if ( isset($vol) ){
                    foreach ($vol as $n => $volumes) {
                        //X26|QVol|Esp|Marca|NVol|PesoL|PesoB|
                        $qVol = !empty($vol->item($n)->getElementsByTagName("qVol")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("qVol")->item(0)->nodeValue : '';
                        $esp = !empty($vol->item($n)->getElementsByTagName("esp")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("esp")->item(0)->nodeValue : '';
                        $marca = !empty($vol->item($n)->getElementsByTagName("marca")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("marca")->item(0)->nodeValue : '';
                        $nVol = !empty($vol->item($n)->getElementsByTagName("nVol")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("nVol")->item(0)->nodeValue : '';
                        $pesoL = !empty($vol->item($n)->getElementsByTagName("pesoL")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("pesoL")->item(0)->nodeValue : '';
                        $pesoB = !empty($vol->item($n)->getElementsByTagName("pesoB")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("pesoB")->item(0)->nodeValue : '';
                        $txt .= "X26|$qVol|$esp|$marca|$nVol|$pesoL|$pesoB\r\n";
                    } //fim foreach volumes
                } //fim vol

                //monta dados dos lacres utilizados
                if ( isset($lacres) ){
                    foreach ($lacres as $n => $lac){
                        $nLacre = !empty($lacres->item($n)->getElementsByTagName("nLacre")->item(0)->nodeValue) ? $lacres->item($n)->getElementsByTagName("nLacre")->item(0)->nodeValue : '';
                        //X33|NLacre|
                        $txt .= "X33|$nLacre\r\n";
                    } //fim foreach lacre
                } //fim lacres
            } // fim transp

            //monta dados de cobrança
            if ( isset($cobr) ){
                //instancia sub grupos da tag cobr
                $fat = $dom->getElementsByTagName('fat')->item(0);
                $dup = $dom->getElementsByTagName('dup');
                $txt .= "Y\r\n";
                //monta dados da fatura
                if ( isset($fat) ){
                    //Y02|NFat|VOrig|VDesc|VLiq|
                    $nFat = !empty($fat->getElementsByTagName("nFat")->item(0)->nodeValue) ? $fat->getElementsByTagName("nFat")->item(0)->nodeValue : '';
                    $vOrig = !empty($fat->getElementsByTagName("vOrig")->item(0)->nodeValue) ? $fat->getElementsByTagName("vOrig")->item(0)->nodeValue : '';
                    $vDesc = !empty($fat->getElementsByTagName("vDesc")->item(0)->nodeValue) ? $fat->getElementsByTagName("vDesc")->item(0)->nodeValue : '';
                    $vLiq = !empty($fat->getElementsByTagName("vLiq")->item(0)->nodeValue) ? $fat->getElementsByTagName("vLiq")->item(0)->nodeValue : '';
                    $txt .= "Y02|$nFat|$vOrig|$vDesc|$vLiq\r\n";
                } //fim fat

                //monta dados das duplicatas
                if( isset($dup) ){
                   foreach ( $dup as $n => $duplicata ){
                        //Y07|NDup|DVenc|VDup|
                        $nDup = !empty($dup->item($n)->getElementsByTagName("nDup")->item(0)->nodeValue) ? $dup->item($n)->getElementsByTagName("nDup")->item(0)->nodeValue : '';
                        $dVenc = !empty($dup->item($n)->getElementsByTagName("dVenc")->item(0)->nodeValue) ? $dup->item($n)->getElementsByTagName("dVenc")->item(0)->nodeValue : '';
                        $vDup = !empty($dup->item($n)->getElementsByTagName("vDup")->item(0)->nodeValue) ? $dup->item($n)->getElementsByTagName("vDup")->item(0)->nodeValue : '';
                        $txt .= "Y07|$nDup|$dVenc|$vDup\r\n";
                    } //fim foreach
                } //fim dup
            } //fim cobr

            //monta dados das informações adicionais da NFe
            if ( isset($infAdic) ) {
                //instancia sub grupos da tag infAdic
                $obsCont = $dom->getElementsByTagName('obsCont');

                //Z|InfAdFisco|InfCpl|
                $infAdFisco = !empty($infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue) ? $infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue : '';
                $infCpl = !empty($infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue) ? $infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue : '';
                $txt .= "Z|$infAdFisco|$infCpl\r\n";

                //monta dados de observaçoes da NFe
                if ( isset($obsCont) ){
                    foreach ($obsCont as $n => $oC){
                        //Z04|XCampo|XTexto|
                        $xCampo = !empty($obsCont->item($n)->getElementsByTagName("xCampo")->item(0)->nodeValue) ? $obsCont->item($n)->getElementsByTagName("xCampo")->item(0)->nodeValue : '';
                        $xTexto = !empty($obsCont->item($n)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($n)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                        $txt .= "Z04|$xCampo|$xTexto\r\n";
                    } //fim foreach
                } //fim obsCont

            } //fim infAdic

            //monta dados dos processos
            if ( isset($procRef) ){
                foreach ($procRef as $n => $pR){
                    //Z10|NProc|IndProc|
                    $nProc = !empty($procRef->item($n)->getElementsByTagName("nProc")->item(0)->nodeValue) ? $procRef->item($n)->getElementsByTagName("nProc")->item(0)->nodeValue : '';
                    $indProc = !empty($procRef->item($n)->getElementsByTagName("infProc")->item(0)->nodeValue) ? $procRef->item($n)->getElementsByTagName("infProc")->item(0)->nodeValue : '';
                    $txt .= "Z10|$nProc|$indProc\r\n";
                } //fim foreach
            } //fim procRef

            //monta dados de exportação
            if ( isset($exporta) ){
                //ZA|UFEmbarq|XLocEmbarq|
                $UFEmbarq = !empty($exporta->getElementsByTagName("UFEmbarq")->item(0)->nodeValue) ? $exporta->getElementsByTagName("UFEmbarq")->item(0)->nodeValue : '';
                $xLocEmbarq = !empty($exporta->getElementsByTagName("xLocEmbarq")->item(0)->nodeValue) ? $exporta->getElementsByTagName("xLocEmbarq")->item(0)->nodeValue : '';
                $txt .= "ZA|$UFEmbarq|$xLocEmbarq\r\n";
            } //fim exporta

            //monta dados de compra
            if ( isset($compra) ){
                //ZB|XNEmp|XPed|XCont|
                $xNEmp = !empty($compra->getElementsByTagName("xNEmp")->item(0)->nodeValue) ? $compra->getElementsByTagName("xNEmp")->item(0)->nodeValue : '';
                $xPed = !empty($compra->getElementsByTagName("xPed")->item(0)->nodeValue) ? $compra->getElementsByTagName("xPed")->item(0)->nodeValue : '';
                $xCont = !empty($compra->getElementsByTagName("xCont")->item(0)->nodeValue) ? $compra->getElementsByTagName("xCont")->item(0)->nodeValue : '';
                $txt .= "ZB|$xNEmp|$xPed|$xCont\r\n";
            } //fim compra

        } //end for
        $this->txt = $txt;
        return $txt;
    }// fim da função nfexml2txt


    /**
     * vazio
     * Verifica se a string esta vazia
     * @param string $var
     * @return boolean
     */
    function vazio($var) {
        $var = trim($var);
        if( strlen($var) > 0 ) {
            return false;
        } else {
            return true;
        }
    } //end function



} //fim da classe

?>
