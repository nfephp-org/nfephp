<?php
/**
 * NF-e - Nota Fiscal eletrônica
 * Classes para geração da NF-e e gravação em DB
 * NFe layout 1.10
 * @license GNU/GPL v.3
 * @author  Daniel Batista Lemes <dlemes at gmail dot com >
 * @date    27/06/2009
 * @author Beto ( ees.beto at gmail)
 * @date 01/09/2009
 */

//print_r("<pre>");
header("Content-Type: text/xml");  
class NFeTxt2Xml {
    var $xml;

    function __construct($arquivo=NULL, $saida=NULL) {
    //function __construct($arquivo=NULL){//para teste lembrar de apagar  essa linha e descomentar a de cima
        $handle = @fopen($arquivo, "r");
        if ($handle) {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;
            $NFe = $dom->createElement("NFe");
            $NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");


            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                if(strpos($buffer, "|")===false)
                    $dados[0] = $buffer;

                else
                    $dados = (explode("|",$buffer));

                switch (strtoupper(trim($dados[0]))) {
                    case "NOTA FISCAL": // primeiro elemento não faz nada aqui é informado o número de NF do TXT
                        break;
                    case "A":  //ATRIBUDOTOS NFE
                        $infNFe = $dom->createElement("infNFe");
                        $infNFe->setAttribute("versao", trim($dados[1]));
                        $infNFe->setAttribute("Id", trim($dados[2]));
                        break;
                    case "B"://IDENTIFICADORES DA NF-E

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
                    case "B13": //INFORMACAO DAS NF-E REFERENCIADAS

                        $B13 = $dom->createElement("refNFe");
                        if(!$this->vazio($dados[1])) {
                            $refNFe = $dom->createElement("refNFe", trim($dados[1]));
                            $B13->appendChild($refNFe);
                        }
                        $ide->appendChild($B13);
                        break;
                    case "B14": //INFORMACAO DAS NF REFERENCIADA - IDEM ANTERIRO, REFERENCIANDO NF MODELO 1/1A NORMAL
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

                    case "C": //EMITENTE
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
                        // obrigat�rio
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
                    case "N"://IMCS
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

                }

            }

            $NFe->appendChild($infNFe);
            $dom->appendChild($NFe);
            $this->xml = $dom->saveXML();
            if(!empty($saida)) {
            //print $saida;
                $dom->save($saida);
            }

            fclose($handle);


        }else {
            return "Não foi possível abrir o arquivo, ele existe?";
        }
    }

    function getXML() { // retorna o XML formatado
        return $this->xml;
    }

    function vazio($var) {
        $var = trim($var);
        if(strlen($var)>0)
            return false;
        else
            return true;
    }

}


?>