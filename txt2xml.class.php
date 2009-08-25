<?php
/**
 * NF-e - Nota Fiscal eletrÃ´nica
 * Classes para geraÃ§Ã£o da NF-e e gravaÃ§Ã£o em DB
 * NFe layout 1.10
 *
 * @author  Daniel Batista Lemes <dlemes at gmail dot com >
 * @date    27/06/2009
 */

//print_r("<pre>");
header("Content-Type: text/xml");  
class NFeTxt2Xml{
	var $xml;
	
	function __construct($arquivo=NULL){
		$handle = @fopen($arquivo, "r");
		if ($handle) {
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = true;

			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				if(strpos($buffer, "|")===false)
					$dados[0] = $buffer;
					
				else		  
					$dados = (explode("|",$buffer));
				
				switch (strtoupper(trim($dados[0]))) {
					case "NOTA FISCAL": // primeiro elemento nÃ£o faz nada aqui Ã© informado o nÃºmero de NF do TXT
						break;
					case "A":
						$infNFe = $dom->createElement("infNFe"); 
						$infNFe->setAttribute("versao", $dados[1]);
						$infNFe->setAttribute("Id", $dados[2]);
						break;
					case "B":
                        
						$B = $dom->createElement("ide");
						if(!$this->vazio($dados[1])){
							$cUF = $dom->createElement("cUF", $dados[1]); 
							$B->appendChild($cUF);
						}
						if(!$this->vazio($dados[2])){
							$cNF = $dom->createElement("cNF", $dados[2]); 
							$B->appendChild($cNF);
						}
						if(!$this->vazio($dados[3])){
							$natOp = $dom->createElement("natOp", $dados[3]); 
							$B->appendChild($natOp);//alterado de NatOp para natOp conforme manual
						}
						if(!$this->vazio($dados[4])){
							$indPag = $dom->createElement("indPag", $dados[4]); 
							$B->appendChild($indPag);
						}
						if(!$this->vazio($dados[5])){
							$mod = $dom->createElement("mod", $dados[5]); 
							$B->appendChild($mod);
						}
						if(!$this->vazio($dados[6])){
							$serie = $dom->createElement("serie", $dados[6]); 
							$B->appendChild($serie);
						}
						if(!$this->vazio($dados[7])){
							$nNF = $dom->createElement("nNF", $dados[7]); 
							$B->appendChild($nNF);
						}
						if(!$this->vazio($dados[8])){
							$dEmi = $dom->createElement("dEmi", $dados[8]); 
							$B->appendChild($dEmi);
						}
						if(!$this->vazio($dados[9])){
							$dSaiEnt = $dom->createElement("dSaiEnt", $dados[9]); 
							$B->appendChild($dSaiEnt);
						}
						if(!$this->vazio($dados[10])){
							$tpNF = $dom->createElement("tpNF", $dados[10]); 
							$B->appendChild($tpNF);
						}
						if(!$this->vazio($dados[11])){
							$cMunFG = $dom->createElement("cMunFG", $dados[11]); 
							$B->appendChild($cMunFG);
						}
						if(!$this->vazio($dados[12])){
							$tpImp = $dom->createElement("tpImp", $dados[12]); 
							$B->appendChild($tpImp);
						}
						if(!$this->vazio($dados[13])){
							$tpEmis = $dom->createElement("tpEmis", $dados[13]); 
							$B->appendChild($tpEmis);
						}
						if(!$this->vazio($dados[14])){
							$CDV = $dom->createElement("cDV", $dados[14]); 
							$B->appendChild($CDV);
						}
						if(!$this->vazio($dados[15])){
							$tpAmb = $dom->createElement("tpAmb", $dados[15]); 
							$B->appendChild($tpAmb);
						}
						if(!$this->vazio($dados[16])){
							$finNFe = $dom->createElement("finNFe", $dados[16]);
							$B->appendChild($finNFe);
						}
						if(!$this->vazio($dados[17])){
							$procEmi = $dom->createElement("procEmi", $dados[17]); 
							$B->appendChild($procEmi);
						}
						if(!$this->vazio($dados[18])){
							$verProc = $dom->createElement("verProc", $dados[18]); 
							$B->appendChild($verProc);
						}
						$infNFe->appendChild($B);
						break;
					case "B13":  //VEREFICAR
					$enderEmi = $dom->createElement("enderEmi");
						if(!$this->vazio($dados[1])){
							$refNFe = $dom->createElement("refNFe", $dados[1]); 
							$B13->appendChild($refNFe);
						}
						$infNFe->appendChild($B13);
						break;
						
					case "B14": //VEREFICAR
						$enderEmi = $dom->createElement("enderEmi");
						if(!$this->vazio($dados[1])){
							$cUF = $dom->createElement("cUF", $dados[1]); 
							$B14->appendChild($cUF);
						}
						if(!$this->vazio($dados[2])){
							$AAMM = $dom->createElement("AAMM", $dados[2]); 
							$B14->appendChild($AAMM);
						}
						if(!$this->vazio($dados[3])){
							$CNPJ = $dom->createElement("CNPJ", $dados[1]); 
							$B14->appendChild($CNPJ);
						}
						if(!$this->vazio($dados[4])){
							$mod = $dom->createElement("mod", $dados[4]); 
							$B14->appendChild($mod);
						}
						if(!$this->vazio($dados[5])){
							$serie = $dom->createElement("serie", $dados[1]); 
							$B14->appendChild($serie);
						}
						if(!$this->vazio($dados[6])){
							$nNF = $dom->createElement("nNF", $dados[1]); 
							$B14->appendChild($nNF);
						}
						$infNFe->appendChild($B14);
						break;
						
					case "C":
						$C = $dom->createElement("emi");
						if(!$this->vazio($dados[1])){
							$xNome = $dom->createElement("xNome", $dados[1]); 
							$C->appendChild($xNome);
						}
						if(!$this->vazio($dados[2])){
							$xFant = $dom->createElement("xFant", $dados[2]); 
							$C->appendChild($xFant);
						}
						if(!$this->vazio($dados[3])){
							$ie = $dom->createElement("IE", $dados[3]); 
							$C->appendChild($ie);
						}
						if(!$this->vazio($dados[4])){
							$iest = $dom->createElement("IEST", $dados[4]); 
							$C->appendChild($iest);
						}
						if(!$this->vazio($dados[5])){
							$im = $dom->createElement("IM", $dados[5]); 
							$C->appendChild($im);
						}
						if(!$this->vazio($dados[6])){
							$cnae = $dom->createElement("CNAE", $dados[6]); 
							$C->appendChild($cnae);
						}
						$infNFe->appendChild($C);
						break;
					case "C02":
						if(!$this->vazio($dados[1])){
							$cnpj = $dom->createElement("CNPJ", $dados[1]); 
							$C->appendChild($cnpj);
						}
						$infNFe->appendChild($C);
						break;
					case "C02a": 	
						if(!$this->vazio($dados[1])){
							$cpf = $dom->createElement("CPF", $dados[1]); 
							$C->appendChild($cpf);
						}
						$infNFe->appendChild($C);
						break;
					case "C05":
						$enderEmi = $dom->createElement("enderEmi");  
						if(!$this->vazio($dados[1])){
							$xLgr = $dom->createElement("xLgr", $dados[1]);
							$enderEmi->appendChild($xLgr);
						}
						if(!$this->vazio($dados[2])){
							$nro = $dom->createElement("nro", $dados[2]);
							$enderEmi->appendChild($nro);
						}
						if(!$this->vazio($dados[4])){
							$xBairro = $dom->createElement("xBairro", $dados[4]);
							$enderEmi->appendChild($xBairro);
						}
						if(!$this->vazio($dados[5])){
							$cMun = $dom->createElement("cMun", $dados[5]);
							$enderEmi->appendChild($cMun);
						}
						if(!$this->vazio($dados[6])){
							$xMun = $dom->createElement("xMun", $dados[6]);
							$enderEmi->appendChild($xMun);
						}
						if(!$this->vazio($dados[7])){
							$UF = $dom->createElement("UF", $dados[7]);
							$enderEmi->appendChild($UF);
						}
						if(!$this->vazio($dados[8])){
							$CEP = $dom->createElement("CEP", $dados[8]);
							$enderEmi->appendChild($CEP);
						}
						if(!$this->vazio($dados[9])){
							$cPais = $dom->createElement("cPais", $dados[9]);
							$enderEmi->appendChild($cPais);
						}
						if(!$this->vazio($dados[10])){
							$xPais = $dom->createElement("xPais", $dados[10]);
							$enderEmi->appendChild($xPais);
						}
						if(!$this->vazio($dados[11])){
							$fone = $dom->createElement("fone", $dados[11]);
							$enderEmi->appendChild($fone);
						}
						$C->appendChild($enderEmi);
						$infNFe->appendChild($C);
						break;
					case "E":
						$E = $dom->createElement("dest");
						if(!$this->vazio($dados[1])){
							$xNome = $dom->createElement("xNome", $dados[1]); 
							$E->appendChild($xNome);
						}
						if(!$this->vazio($dados[2])){
							$IE = $dom->createElement("IE", $dados[2]); 
							$E->appendChild($IE);
						}
						if(!$this->vazio($dados[3])){
							$ISUF = $dom->createElement("ISUF", $dados[3]); 
							$E->appendChild($ISUF);
						}
						$infNFe->appendChild($E);
						break;
					case "E02":
						if(!$this->vazio($dados[1])){
							$CNPJ = $dom->createElement("CNPJ", $dados[1]); 
							$E->appendChild($CNPJ);
						}
						$infNFe->appendChild($E);
						break;
					case "E03":
						if(!$this->vazio($dados[1])){
							$CPF = $dom->createElement("CPF", $dados[1]); 
							$E->appendChild($CPF);
						}
						$infNFe->appendChild($E);
						break;
					case "E05":
						$enderDest = $dom->createElement("enderDest");
						if(!$this->vazio($dados[1])){
							$xLgr = $dom->createElement("xLgr", $dados[1]); 
							$enderDest->appendChild($xLgr);
						}
						if(!$this->vazio($dados[2])){
							$nro = $dom->createElement("nro", $dados[2]); 
							$enderDest->appendChild($nro);
						}
						if(!$this->vazio($dados[3])){
							$xCpl = $dom->createElement("xCpl", $dados[3]); 
							$enderDest->appendChild($xCpl);
						}
						if(!$this->vazio($dados[4])){
							$xBairro = $dom->createElement("xBairro", $dados[4]); 
							$enderDest->appendChild($xBairro);
						}
						if(!$this->vazio($dados[5])){
							$cMun = $dom->createElement("cMun", $dados[5]); 
							$enderDest->appendChild($cMun);
						}
						if(!$this->vazio($dados[6])){
							$xMun = $dom->createElement("xMun", $dados[6]); 
							$enderDest->appendChild($xMun);
						}
						if(!$this->vazio($dados[7])){
							$UF = $dom->createElement("UF", $dados[7]); 
							$enderDest->appendChild($UF);
						}
						if(!$this->vazio($dados[8])){
							$CEP = $dom->createElement("CEP", $dados[8]); 
							$enderDest->appendChild($CEP);
						}
						if(!$this->vazio($dados[9])){
							$cPais = $dom->createElement("cPais", $dados[9]); 
							$enderDest->appendChild($cPais);
						}
						if(!$this->vazio($dados[10])){
							$xPais = $dom->createElement("xPais", $dados[10]); 
							$enderDest->appendChild($xPais);
						}
						if(!$this->vazio($dados[11])){
							$fone = $dom->createElement("fone", $dados[11]); 
							$enderDest->appendChild($fone);
						}
						$E->appendChild($enderDest);
						$infNFe->appendChild($E);
						break;
					case "F":
						$retirada = $dom->createElement("retirada");
						if(!$this->vazio($dados[1])){
							$CNPJ = $dom->createElement("CNPJ", $dados[1]); 
							$retirada->appendChild($CNPJ);
						}
						if(!$this->vazio($dados[2])){
							$xLgr = $dom->createElement("xLgr", $dados[2]); 
							$retirada->appendChild($xLgr);
						}
						if(!$this->vazio($dados[3])){
							$nro = $dom->createElement("nro", $dados[3]); 
							$retirada->appendChild($nro);
						}
						if(!$this->vazio($dados[4])){
							$xCpl = $dom->createElement("xCpl", $dados[4]); 
							$retirada->appendChild($xCpl);
						}
						if(!$this->vazio($dados[5])){
							$xBairro = $dom->createElement("xBairro", $dados[5]); 
							$retirada->appendChild($xBairro);
						}
						if(!$this->vazio($dados[6])){
							$cMun = $dom->createElement("cMun", $dados[6]); 
							$retirada->appendChild($cMun);
						}
						if(!$this->vazio($dados[7])){
							$xMun = $dom->createElement("xMun", $dados[7]); 
							$retirada->appendChild($xMun);
						}
						if(!$this->vazio($dados[8])){
							$UF = $dom->createElement("UF", $dados[8]); 
							$retirada->appendChild($UF);
						}
						$E->appendChild($retirada);
						$infNFe->appendChild($E);
						break;	
					case "G":
						$entrega = $dom->createElement("entrega");
						if(!$this->vazio($dados[1])){
							$CNPJ = $dom->createElement("CNPJ", $dados[1]); 
							$entrega->appendChild($CNPJ);
						}
						if(!$this->vazio($dados[2])){
							$xLgr = $dom->createElement("xLgr", $dados[2]); 
							$entrega->appendChild($xLgr);
						}
						if(!$this->vazio($dados[3])){
							$nro = $dom->createElement("nro", $dados[3]); 
							$entrega->appendChild($nro);
						}
						if(!$this->vazio($dados[4])){
							$xCpl = $dom->createElement("xCpl", $dados[4]); 
							$entrega->appendChild($xCpl);
						}
						if(!$this->vazio($dados[5])){
							$xBairro = $dom->createElement("xBairro", $dados[5]); 
							$entrega->appendChild($xBairro);
						}
						if(!$this->vazio($dados[6])){
							$cMun = $dom->createElement("cMun", $dados[6]); 
							$entrega->appendChild($cMun);
						}
						if(!$this->vazio($dados[7])){
							$xMun = $dom->createElement("xMun", $dados[7]); 
							$entrega->appendChild($xMun);
						}
						if(!$this->vazio($dados[8])){
							$UF = $dom->createElement("UF", $dados[8]); 
							$entrega->appendChild($UF);
						}
						$E->appendChild($entrega);
						$infNFe->appendChild($E);
						break;
					case "H":
						$H = $dom->createElement("det");
						$H->setAttribute("nItem", $dados[1]);
						if(!$this->vazio($dados[2])){
							$infAdProd = $dom->createElement("infAdProd", $dados[2]); 
							$H->appendChild($infAdProd);
						}
						$infNFe->appendChild($H);
						break;
					case "I": 
						if(!$this->vazio($dados[1])){
							$cProd = $dom->createElement("cProd", $dados[1]); 
							$H->appendChild($cProd);
						}
						if(!$this->vazio($dados[2])){
							$cENAN = $dom->createElement("cENAN", $dados[2]); 
							$H->appendChild($cENAN);
						}
						if(!$this->vazio($dados[3])){
							$xProd = $dom->createElement("xProd", $dados[3]); 
							$H->appendChild($xProd);
						}
						if(!$this->vazio($dados[4])){
							$NCM = $dom->createElement("NCM", $dados[4]); 
							$H->appendChild($NCM);
						}
						if(!$this->vazio($dados[5])){
							$EXTIPI = $dom->createElement("EXTIPI", $dados[5]); 
							$H->appendChild($EXTIPI);
						}
						if(!$this->vazio($dados[6])){
							$genero = $dom->createElement("genero", $dados[6]); 
							$H->appendChild($genero);
						}
						if(!$this->vazio($dados[7])){
							$CFOP = $dom->createElement("CFOP", $dados[7]); 
							$H->appendChild($CFOP);
						}
						if(!$this->vazio($dados[8])){
							$uCom = $dom->createElement("uCom", $dados[8]); 
							$H->appendChild($uCom);
						}
						if(!$this->vazio($dados[9])){
							$qCom = $dom->createElement("qCom", $dados[9]); 
							$H->appendChild($qCom);
						}
						if(!$this->vazio($dados[10])){
							$vUnCom = $dom->createElement("vUnCom", $dados[10]); 
							$H->appendChild($vUnCom);
						}
						if(!$this->vazio($dados[11])){
							$vProd = $dom->createElement("vProd", $dados[11]); 
							$H->appendChild($vProd);
						}
						if(!$this->vazio($dados[12])){
							$cEANTrib = $dom->createElement("cEANTrib", $dados[12]); 
							$H->appendChild($cEANTrib);
						}
						if(!$this->vazio($dados[13])){
							$uTrib = $dom->createElement("uTrib", $dados[13]); 
							$H->appendChild($uTrib);
						}
						if(!$this->vazio($dados[14])){
							$qTrib = $dom->createElement("qTrib", $dados[14]); 
							$H->appendChild($qTrib);
						}
						if(!$this->vazio($dados[15])){
							$vUnTrib = $dom->createElement("vUnTrib", $dados[15]); 
							$H->appendChild($vUnTrib);
						}
						if(!$this->vazio($dados[16])){
							$vFrete = $dom->createElement("vFrete", $dados[16]); 
							$H->appendChild($vFrete);
						}
						if(!$this->vazio($dados[17])){
							$vSeg = $dom->createElement("vSeg", $dados[17]); 
							$H->appendChild($vSeg);
						}
						if(!$this->vazio($dados[18])){
							$vDesc = $dom->createElement("vDesc", $dados[18]); 
							$H->appendChild($vDesc);
						}
					case "I18":
						$DI = $dom->createElement("DI");
						if(!$this->vazio($dados[1])){
							$nDi = $dom->createElement("nDi", $dados[1]); 
							$DI->appendChild($nDi);
						}
						if(!$this->vazio($dados[2])){
							$dDi = $dom->createElement("dDi", $dados[2]); 
							$DI->appendChild($dDi);
						}
						if(!$this->vazio($dados[3])){
							$xLocDesemb = $dom->createElement("xLocDesemb", $dados[3]); 
							$DI->appendChild($xLocDesemb);
						}
						if(!$this->vazio($dados[4])){
							$UFDesemb = $dom->createElement("UFDesemb", $dados[4]); 
							$DI->appendChild($UFDesemb);
						}
						if(!$this->vazio($dados[5])){
							$dDesemb = $dom->createElement("dDesemb", $dados[5]); 
							$DI->appendChild($dDesemb);
						}
						if(!$this->vazio($dados[5])){
							$cExportador = $dom->createElement("cExportador", $dados[6]); 
							$DI->appendChild($cExportador);
						}
						$H->appendChild($DI);
						break;
					case "I25":
						$adi = $dom->createElement("adi");
						if(!$this->vazio($dados[1])){
							$nAdicao = $dom->createElement("nAdicao", $dados[1]); 
							$adi->appendChild($nAdicao);
						}
						if(!$this->vazio($dados[2])){
							$nSeqAdic = $dom->createElement("nSeqAdic", $dados[2]); 
							$adi->appendChild($nSeqAdic);
						}
						if(!$this->vazio($dados[3])){
							$cFabricante = $dom->createElement("cFabricante", $dados[3]); 
							$adi->appendChild($cFabricante);
						}
						if(!$this->vazio($dados[4])){
							$vDescDi = $dom->createElement("vDescDi", $dados[4]); 
							$adi->appendChild($vDescDi);
						}
						$DI->appendChild($adi);
						break;
					case "J":
						$veicProd = $dom->createElement("veicProd");
						if(!$this->vazio($dados[1])){
							$tpOP = $dom->createElement("tpOp", $dados[1]); 
							$veicProd->appendChild($tpOP);
						}
						if(!$this->vazio($dados[2])){
							$chassi = $dom->createElement("chassi", $dados[2]); 
							$veicProd->appendChild($chassi);
						}
						if(!$this->vazio($dados[3])){
							$cCor = $dom->createElement("cCor", $dados[3]); 
							$veicProd->appendChild($cCor);
						}
						if(!$this->vazio($dados[4])){
							$xCor = $dom->createElement("xCor", $dados[4]); 
							$veicProd->appendChild($dVal);
						}
						if(!$this->vazio($dados[5])){
							$pot = $dom->createElement("pot", $dados[5]); 
							$veicProd->appendChild($pot);
						}
						if(!$this->vazio($dados[6])){
							$CM3 = $dom->createElement("CM3", $dados[5]); 
							$veicProd->appendChild($CM3);
						}
						if(!$this->vazio($dados[7])){
							$pesoL = $dom->createElement("pesL", $dados[5]); 
							$veicProd->appendChild($pesoL);
						}
						if(!$this->vazio($dados[8])){
							$pesoB = $dom->createElement("pesoB", $dados[5]); 
							$veicProd->appendChild($pesoB);
						}
						if(!$this->vazio($dados[9])){
							$nSerie = $dom->createElement("nSerie", $dados[5]); 
							$veicProd->appendChild($nSerie);
						}
						if(!$this->vazio($dados[10])){
							$tpComb = $dom->createElement("tpComb", $dados[5]); 
							$veicProd->appendChild($tpComb);
						}
						if(!$this->vazio($dados[11])){
							$nMotor = $dom->createElement("nMotor", $dados[5]); 
							$veicProd->appendChild($nMotor);
						}
						if(!$this->vazio($dados[12])){
							$CMKG = $dom->createElement("CMKG", $dados[5]); 
							$veicProd->appendChild($CMKG);
						}
						if(!$this->vazio($dados[13])){
							$dist = $dom->createElement("dist", $dados[5]); 
							$veicProd->appendChild($dist);
						}
						if(!$this->vazio($dados[14])){
							$RENAVAM = $dom->createElement("RENAVAM", $dados[5]); 
							$veicProd->appendChild($RENAVAM);
						}
						if(!$this->vazio($dados[15])){
							$anoMod = $dom->createElement("anoMod", $dados[5]); 
							$veicProd->appendChild($anoMod);
						}
						if(!$this->vazio($dados[16])){
							$anoFab = $dom->createElement("anoFab", $dados[5]); 
							$veicProd->appendChild($anoFab);
						}
						if(!$this->vazio($dados[17])){
							$tpPint = $dom->createElement("tpPint", $dados[5]); 
							$veicProd->appendChild($tpPint);
						}
						if(!$this->vazio($dados[18])){
							$tpVeic = $dom->createElement("tpVeic", $dados[5]); 
							$veicProd->appendChild($tpVeic);
						}
						if(!$this->vazio($dados[19])){
							$espVeic = $dom->createElement("espVeic", $dados[5]); 
							$veicProd->appendChild($espVeic);
						}
						if(!$this->vazio($dados[20])){
							$VIN = $dom->createElement("VIN", $dados[5]); 
							$veicProd->appendChild($VIN);
						}
						if(!$this->vazio($dados[21])){
							$condVeic = $dom->createElement("conVeic", $dados[5]); 
							$veicProd->appendChild($condVeic);
						}
						if(!$this->vazio($dados[22])){
							$cMod = $dom->createElement("cMod", $dados[5]); 
							$veicProd->appendChild($cMod);
						}
						$H->appendChild($veicProd);
						break;
					case "K":
						$med = $dom->createElement("med");
						if(!$this->vazio($dados[1])){
							$nLote = $dom->createElement("nLote", $dados[1]); 
							$med->appendChild($nLote);
						}
						if(!$this->vazio($dados[2])){
							$qLote = $dom->createElement("qLote", $dados[2]); 
							$med->appendChild($qLote);
						}
						if(!$this->vazio($dados[3])){
							$dFab = $dom->createElement("dFab", $dados[3]); 
							$med->appendChild($dFab);
						}
						if(!$this->vazio($dados[4])){
							$dVal = $dom->createElement("nLote", $dados[4]); 
							$med->appendChild($dVal);
						}
						if(!$this->vazio($dados[5])){
							$vPMC = $dom->createElement("vPMC", $dados[5]); 
							$med->appendChild($vPMC);
						}
						$H->appendChild($med);
						break;

					case "L":
						$arma = $dom->createElement("arma");
						if(!$this->vazio($dados[1])){
							$tpArma = $dom->createElement("tpArma", $dados[1]); 
							$arma->appendChild($tpArma);
						}
						if(!$this->vazio($dados[2])){
							$nSerie = $dom->createElement("nSerie", $dados[2]); 
							$arma->appendChild($nSerie);
						}
						if(!$this->vazio($dados[3])){
							$nCano = $dom->createElement("nCano", $dados[3]); 
							$arma->appendChild($nCano);
						}
						if(!$this->vazio($dados[4])){
							$descr = $dom->createElement("descr", $dados[4]); 
							$arma->appendChild($descr);
						}
						$H->appendChild($arma);
						break;	
					case "L01":
						$comb = $dom->createElement("comb");
						if(!$this->vazio($dados[1])){
							$cProdANP = $dom->createElement("cProdANP", $dados[1]); 
							$comb->appendChild($cProdANP);
						}
						if(!$this->vazio($dados[2])){
							$CODIF = $dom->createElement("CODIF", $dados[2]); 
							$comb->appendChild($CODIF);
						}
						if(!$this->vazio($dados[3])){
							$qTemp = $dom->createElement("qTemp", $dados[3]); 
							$comb->appendChild($qTemp);
						}
						$H->appendChild($comb);
						break;
					case "L105":
						$CIDE = $dom->createElement("CIDE");
						if(!$this->vazio($dados[1])){
							$qBCprod = $dom->createElement("qBCprod", $dados[1]); 
							$CIDE->appendChild($qBCprod);
						}
						if(!$this->vazio($dados[2])){
							$vAliqProd = $dom->createElement("vAliqProd", $dados[2]); 
							$CIDE->appendChild($vAliqProd);
						}
						if(!$this->vazio($dados[3])){
							$vCIDE = $dom->createElement("vCIDE", $dados[3]); 
							$CIDE->appendChild($vCIDE);
						}
						$H->appendChild($CIDE);
						break;
					case "L109":
						$ICMSComb = $dom->createElement("ICMSComb");
						if(!$this->vazio($dados[1])){
							$vBCICMS = $dom->createElement("vBCICMS", $dados[1]); 
							$ICMSComb->appendChild($vBCICMS);
						}
						if(!$this->vazio($dados[2])){
							$vICMS = $dom->createElement("vICMS", $dados[2]); 
							$ICMSComb->appendChild($vICMS);
						}
						if(!$this->vazio($dados[3])){
							$vBCICMSST = $dom->createElement("vBCICMSST", $dados[3]); 
							$ICMSComb->appendChild($vBCICMSST);
						}
						if(!$this->vazio($dados[4])){
							$vICMSST = $dom->createElement("vICMSST", $dados[4]); 
							$ICMSComb->appendChild($vICMSST);
						}
						$H->appendChild($ICMSComb);
						break;	
					case "L114":
						$ICMSInter = $dom->createElement("ICMSInter");
						if(!$this->vazio($dados[1])){
							$vBCICMSSTDest = $dom->createElement("vBCICMSSTDest", $dados[1]); 
							$ICMSInter->appendChild($vBCICMSSTDest);
						}
						if(!$this->vazio($dados[2])){
							$vICMSSTDest = $dom->createElement("vICMSSTDest", $dados[2]); 
							$ICMSInter->appendChild($vICMSSTDest);
						}
						$H->appendChild($ICMSInter);
						break;
					case "L117":
						$ICMSCons = $dom->createElement("ICMSCons");
						if(!$this->vazio($dados[1])){
							$vBCICMSSTCons = $dom->createElement("vBCICMSSTCons", $dados[1]); 
							$ICMSCons->appendChild($vBCICMSSTCons);
						}
						if(!$this->vazio($dados[2])){
							$vICMSSTCons = $dom->createElement("vICMSSTCons", $dados[2]); 
							$ICMSCons->appendChild($vICMSSTCons);
						}
						if(!$this->vazio($dados[3])){
							$UFcons = $dom->createElement("UFcons", $dados[3]); 
							$ICMSCons->appendChild($UFcons);
						}
						$H->appendChild($ICMSCons);
						break;
					case "M":
						$imposto = $dom->createElement("imposto");
						$H->appendChild($imposto);
						break;
					case "N":
						$ICMS = $dom->createElement("ICMS");
						$imposto->appendChild($ICMS);
						break;
					case "N02":
						$ICMS00 = $dom->createElement("ICMS00");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS00->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS00->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBC = $dom->createElement("modBC", $dados[3]); 
							$ICMS00->appendChild($modBC);
						}
						if(!$this->vazio($dados[4])){
							$vBC = $dom->createElement("vBC", $dados[4]); 
							$ICMS00->appendChild($vBC);
						}
						if(!$this->vazio($dados[5])){
							$pICMS = $dom->createElement("pICMS", $dados[5]); 
							$ICMS00->appendChild($pICMS);
						}
						if(!$this->vazio($dados[6])){
							$vICMS = $dom->createElement("vICMS", $dados[6]); 
							$ICMS00->appendChild($vICMS);
						}
						$ICMS->appendChild($ICMS00);
						break;
					case "N03":
						$ICMS10 = $dom->createElement("ICMS10");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS10->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS10->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBC = $dom->createElement("modBC", $dados[3]); 
							$ICMS10->appendChild($modBC);
						}
						if(!$this->vazio($dados[4])){
							$vBC = $dom->createElement("vBC", $dados[4]); 
							$ICMS10->appendChild($vBC);
						}
						if(!$this->vazio($dados[5])){
							$pICMS = $dom->createElement("pICMS", $dados[5]); 
							$ICMS10->appendChild($pICMS);
						}
						if(!$this->vazio($dados[6])){
							$vICMS = $dom->createElement("vICMS", $dados[6]); 
							$ICMS10->appendChild($vICMS);
						}
						if(!$this->vazio($dados[7])){
							$modBCST = $dom->createElement("modBCST", $dados[7]);
							$ICMS10->appendChild($modBCST);
						}
						if(!$this->vazio($dados[8])){
							$pMVAST = $dom->createElement("pMVAST", $dados[8]);
							$ICMS10->appendChild($pMVAST);
						}
						if(!$this->vazio($dados[9])){
							$pRedBCST = $dom->createElement("pRedBCST", $dados[8]);
							$ICMS10->appendChild($pRedBCST);
						}
						if(!$this->vazio($dados[10])){
							$vBCST = $dom->createElement("vBCST", $dados[10]);
							$ICMS10->appendChild($vBCST);
						}
						if(!$this->vazio($dados[11])){
							$pICMSST = $dom->createElement("pICMSST", $dados[11]);
							$ICMS10->appendChild($pICMSST);
						}
						if(!$this->vazio($dados[12])){
							$vICMSST = $dom->createElement("vICMSST", $dados[12]);
							$ICMS10->appendChild($vICMSST);
						}
						$ICMS->appendChild($ICMS10);
						break;
						case "N04"://vereficar cst20
						$ICMS20 = $dom->createElement("ICMS20");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS20->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS20->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBC = $dom->createElement("modBC", $dados[3]); 
							$ICMS20->appendChild($modBC);
						}
						if(!$this->vazio($dados[4])){
							$pRedBC = $dom->createElement("pRedBC", $dados[4]); 
							$ICMS20->appendChild($pRedBC);
						}
						if(!$this->vazio($dados[5])){
							$vBC = $dom->createElement("vBC", $dados[5]); 
							$ICMS20->appendChild($vBC);
						}
						if(!$this->vazio($dados[6])){
							$pICMS = $dom->createElement("pICMS", $dados[6]); 
							$ICMS20->appendChild($pICMS);
						}
						if(!$this->vazio($dados[7])){
							$vICMS = $dom->createElement("vICMS", $dados[7]);
							$ICMS20->appendChild($vICMS);
						}
						$ICMS->appendChild($ICMS20);
						break;
					case "N05":
						$ICMS30 = $dom->createElement("ICMS30");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS30->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS30->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBCST = $dom->createElement("modBCST", $dados[3]); 
							$ICMS30->appendChild($modBCST);
						}
						if(!$this->vazio($dados[4])){
							$pMVAST = $dom->createElement("pMVAST", $dados[4]); 
							$ICMS30->appendChild($pMVAST);
						}
						if(!$this->vazio($dados[5])){
							$pRedBCST = $dom->createElement("pRedBCST", $dados[5]); 
							$ICMS30->appendChild($pRedBCST);
						}
						if(!$this->vazio($dados[6])){
							$vBCST = $dom->createElement("vBCST", $dados[6]); 
							$ICMS30->appendChild($vBCST);
						}
						if(!$this->vazio($dados[7])){
							$pICMSST = $dom->createElement("pICMSST", $dados[7]); 
							$ICMS30->appendChild($pICMSST);
						}
						if(!$this->vazio($dados[8])){
							$vICMSST = $dom->createElement("vICMSST", $dados[8]); 
							$ICMS30->appendChild($vICMSST);
						}
						$ICMS->appendChild($ICMS30);
						break;
					case "N06":
						$ICMS40 = $dom->createElement("ICMS40");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS40->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS40->appendChild($CST);
						}
						$ICMS->appendChild($ICMS40);
						break;
					case "N07":
						$ICMS51 = $dom->createElement("ICMS51");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS51->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS51->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBC = $dom->createElement("modBC", $dados[3]); 
							$ICMS51->appendChild($modBC);
						}
						if(!$this->vazio($dados[4])){
							$pRedBC = $dom->createElement("pRedBC", $dados[4]); 
							$ICMS51->appendChild($pRedBC);
						}
						if(!$this->vazio($dados[5])){
							$vBC = $dom->createElement("vBC", $dados[5]); 
							$ICMS51->appendChild($vBC);
						}
						if(!$this->vazio($dados[6])){
							$pICMS = $dom->createElement("pICMS", $dados[6]); 
							$ICMS51->appendChild($pICMS);
						}
						if(!$this->vazio($dados[7])){
							$vICMS = $dom->createElement("vICMS", $dados[7]); 
							$ICMS51->appendChild($vICMS);
						}
						$ICMS->appendChild($ICMS51);
						break;
					case "N08":
						$ICMS60 = $dom->createElement("ICMS60");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS60->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS60->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$vBCST = $dom->createElement("vBCST", $dados[3]); 
							$ICMS60->appendChild($vBCST);
						}
						if(!$this->vazio($dados[4])){
							$vICMSST = $dom->createElement("vICMSST", $dados[4]); 
							$ICMS60->appendChild($vICMSST);
						}
						$ICMS->appendChild($ICMS60);
						break;	
					case "N09": //CST - 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
						$ICMS70 = $dom->createElement("ICMS70");	
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS70->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS70->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBC = $dom->createElement("modBC", $dados[3]); 
							$ICMS70->appendChild($modBC);
						}
						if(!$this->vazio($dados[4])){
							$pRedBC = $dom->createElement("pRedBC", $dados[4]); 
							$ICMS70->appendChild($pRedBC);
						}
						if(!$this->vazio($dados[5])){
							$vBC = $dom->createElement("vBC", $dados[5]); 
							$ICMS70->appendChild($vBC);
						}
						if(!$this->vazio($dados[6])){
							$pICMS = $dom->createElement("pICMS", $dados[6]); 
							$ICMS70->appendChild($pICMS);
						}
						if(!$this->vazio($dados[7])){
							$vICMS = $dom->createElement("vICMS", $dados[7]); 
							$ICMS70->appendChild($vICMS);
						}
						if(!$this->vazio($dados[8])){
							$modBCST = $dom->createElement("modBCST", $dados[8]); 
							$ICMS70->appendChild($modBCST);
						}
						if(!$this->vazio($dados[9])){
							$pMVAST = $dom->createElement("pMVAST", $dados[9]); 
							$ICMS70->appendChild($pMVAST);
						}
						if(!$this->vazio($dados[10])){
							$pRedBCST = $dom->createElement("pRedBCST", $dados[10]); 
							$ICMS70->appendChild($pRedBCSt);
						}
						if(!$this->vazio($dados[11])){
							$vvBCST = $dom->createElement("vBCST", $dados[11]); 
							$ICMS70->appendChild($vBCST);
						}
						if(!$this->vazio($dados[12])){
							$pICMSST = $dom->createElement("pICMSST", $dados[12]); 
							$ICMS70->appendChild($pICMSST);
						}
						if(!$this->vazio($dados[13])){
							$vICMSST = $dom->createElement("vICMSST", $dados[13]); 
							$ICMS70->appendChild($vICMSST);
						}
						$ICMS->appendChild($ICMS70);
						break;
					case "N10": //CST - 90 Outros
						$ICMS90 = $dom->createElement("ICMS90");
						if(!$this->vazio($dados[1])){
							$orig = $dom->createElement("orig", $dados[1]); 
							$ICMS90->appendChild($orig);
						}
						if(!$this->vazio($dados[2])){
							$CST = $dom->createElement("CST", $dados[2]); 
							$ICMS90->appendChild($CST);
						}
						if(!$this->vazio($dados[3])){
							$modBC = $dom->createElement("modBC", $dados[3]); 
							$ICMS90->appendChild($modBC);
						}
						if(!$this->vazio($dados[4])){
							$pRedBC = $dom->createElement("pRedBC", $dados[4]); 
							$ICMS90->appendChild($pRedBC);
						}
						if(!$this->vazio($dados[5])){
							$vBC = $dom->createElement("vBC", $dados[5]); 
							$ICMS90->appendChild($vBC);
						}
						if(!$this->vazio($dados[6])){
							$pICMS = $dom->createElement("pICMS", $dados[6]); 
							$ICMS90->appendChild($pICMS);
						}
						if(!$this->vazio($dados[7])){
							$vICMS = $dom->createElement("vICMS", $dados[7]); 
							$ICMS90->appendChild($vICMS);
						}
						if(!$this->vazio($dados[8])){
							$modBCST = $dom->createElement("modBCST", $dados[8]); 
							$ICMS90->appendChild($modBCST);
						}
						if(!$this->vazio($dados[9])){
							$pMVAST = $dom->createElement("pMVAST", $dados[9]); 
							$ICMS90->appendChild($pMVAST);
						}
						if(!$this->vazio($dados[10])){
							$pRedBCST = $dom->createElement("pRedBCST", $dados[10]); 
							$ICMS90->appendChild($pRedBCSt);
						}
						if(!$this->vazio($dados[11])){
							$vvBCST = $dom->createElement("vBCST", $dados[11]); 
							$ICMS90->appendChild($vBCST);
						}
						if(!$this->vazio($dados[12])){
							$pICMSST = $dom->createElement("pICMSST", $dados[12]); 
							$ICMS90->appendChild($pICMSST);
						}
						if(!$this->vazio($dados[13])){
							$vICMSST = $dom->createElement("vICMSST", $dados[13]); 
							$ICMS90->appendChild($vICMSST);
						}						
						$ICMS->appendChild($ICMS90);
						break;
					case "O":
						$IPI = $dom->createElement("IPI");
						if(!$this->vazio($dados[1])){
							$clEnq = $dom->createElement("clEnq", $dados[1]); 
							$IPI->appendChild($clEnq);
						}
						if(!$this->vazio($dados[2])){
							$CNPJProd = $dom->createElement("CNPJProd", $dados[2]); 
							$IPI->appendChild($CNPJProd);
						}
						if(!$this->vazio($dados[3])){
							$cSelo = $dom->createElement("cSelo", $dados[3]); 
							$IPI->appendChild($cSelo);
						}
						if(!$this->vazio($dados[4])){
							$cEnq = $dom->createElement("cEnq", $dados[4]); 
							$IPI->appendChild($cEnq);
						}
						if(!$this->vazio($dados[5])){
							$cEnq = $dom->createElement("cEnq", $dados[5]); 
							$IPI->appendChild($cEnq);
						}
						$imposto->appendChild($IPI);
						break;
					case "O07":
						$IPITrib = $dom->createElement("IPITrib");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$IPITrib->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$vIPI = $dom->createElement("vIPI", $dados[2]); 
							$IPITrib->appendChild($vIPI);
						}
						$IPI->appendChild($IPITrib);
						break;
					case "O10":	
						if(!$this->vazio($dados[1])){
							$vBC = $dom->createElement("vBC", $dados[1]); 
							$IPITrib->appendChild($vBC);
						}
						if(!$this->vazio($dados[2])){
							$pIPI = $dom->createElement("pIPI", $dados[2]); 
							$IPITrib->appendChild($pIPI);
						}
						break;
					case "O11":	
						if(!$this->vazio($dados[1])){
							$vUnid = $dom->createElement("vUnid", $dados[1]); 
							$IPITrib->appendChild($vUnid);
						}
						if(!$this->vazio($dados[2])){
							$qUnid = $dom->createElement("qUnid", $dados[2]); 
							$IPITrib->appendChild($qUnid);
						}
						break;
					case "O08":
						$IPINT = $dom->createElement("IPINT");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$IPINT->appendChild($CST);
						}
						$IPI->appendChild($IPINT);
						break;
					case "P":
						$II = $dom->createElement("II");
						if(!$this->vazio($dados[1])){
							$vBC = $dom->createElement("vBC", $dados[1]); 
							$II->appendChild($vBC);
						}
						if(!$this->vazio($dados[2])){
							$vDespAdu = $dom->createElement("vDespAdu", $dados[2]); 
							$II->appendChild($vDespAdu);
						}
						if(!$this->vazio($dados[3])){
							$vII = $dom->createElement("vII", $dados[3]); 
							$II->appendChild($vII);
						}
						if(!$this->vazio($dados[4])){
							$vIOF = $dom->createElement("vIOF", $dados[4]); 
							$II->appendChild($vIOF);
						}
						$imposto->appendChild($II);
						break;
					case "Q":
						$PIS = $dom->createElement("PIS");
						$PISAliq = $dom->createElement("PISAliq");
						$PIS->appendChild($PISAliq);
						$imposto->appendChild($PIS);
						break;
					case "Q02":
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$PISAliq->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$vBC = $dom->createElement("vBC", $dados[2]); 
							$PISAliq->appendChild($vBC);
						}
						if(!$this->vazio($dados[3])){
							$pPIS = $dom->createElement("pPIS", $dados[3]); 
							$PISAliq->appendChild($pPIS);
						}
						if(!$this->vazio($dados[4])){
							$vPIS = $dom->createElement("vPIS", $dados[4]); 
							$PISAliq->appendChild($vPIS);
						}
					case "Q03":
						$PISQtde = $dom->createElement("PISQtde");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$PISQtde->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$qBCProd = $dom->createElement("qBCProd", $dados[2]); 
							$PISQtde->appendChild($qBCProd);
						}
						if(!$this->vazio($dados[3])){
							$vAliqProd = $dom->createElement("vAliqProd", $dados[3]); 
							$PISQtde->appendChild($vAliqProd);
						}
						if(!$this->vazio($dados[4])){
							$vPIS = $dom->createElement("vPIS", $dados[4]); 
							$PISQtde->appendChild($vPIS);
						}
						$PIS->appendChild($PISAliq);
						break;
					case "Q04": // PIS - grupo de PIS não tributado
						$PISNT = $dom->createElement("PISNT");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$PISNT->appendChild($CST);
						}
						$PIS->appendChild($PISNT);
						break;	
					case "Q05":
						$PISOutr = $dom->createElement("PISOutr");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$PISOutr->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$vPIS = $dom->createElement("vPIS", $dados[2]); 
							$PISOutr->appendChild($vPIS);
						}
						$PIS->appendChild($PISOutr);
						break;
					case "Q07":
						if(!$this->vazio($dados[1])){
							$vBC = $dom->createElement("vBC", $dados[1]); 
							$PISOutr->appendChild($vBC);
						}
						if(!$this->vazio($dados[2])){
							$pPIS = $dom->createElement("pPIS", $dados[2]); 
							$PISOutr->appendChild($pPIS);
						}
						$PIS->appendChild($PISOutr);
						break;
					case "Q10":
						if(!$this->vazio($dados[1])){
							$qBCProd = $dom->createElement("qBCProd", $dados[1]); 
							$PISOutr->appendChild($qBCProd);
						}
						if(!$this->vazio($dados[2])){
							$vAliqProd = $dom->createElement("vAliqProd", $dados[2]); 
							$PISOutr->appendChild($vAliqProd);
						}
						$PIS->appendChild($PISOutr);
						break;
					case "R":
						$PISST = $dom->createElement("PISST");
						if(!$this->vazio($dados[1])){
							$vPIS = $dom->createElement("vPIS", $dados[1]); 
							$PISST->appendChild($vPIS);
						}
						$imposto->appendChild($PISST);
						break;	
					case "R02":
						if(!$this->vazio($dados[1])){
							$vBC = $dom->createElement("vBC", $dados[1]); 
							$PISST->appendChild($vBC);
						}
						if(!$this->vazio($dados[1])){
							$pPIS = $dom->createElement("pPIS", $dados[1]); 
							$PISST->appendChild($pPIS);
						}
						break;
					case "R04":
						if(!$this->vazio($dados[1])){
							$qBCProd = $dom->createElement("qBCProd", $dados[1]); 
							$PISST->appendChild($qBCProd);
						}
						break;	
					case "S":
						$COFINS = $dom->createElement("COFINS");
						$imposto->appendChild($COFINS);
						break;
					case "S02":
						$COFINSAliq = $dom->createElement("COFINSAliq");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$COFINSAliq->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$vBC = $dom->createElement("vBC", $dados[2]); 
							$COFINSAliq->appendChild($vBC);
						}
						if(!$this->vazio($dados[3])){
							$pCOFINS = $dom->createElement("pCOFINS", $dados[3]); 
							$COFINSAliq->appendChild($pCOFINS);
						}
						if(!$this->vazio($dados[4])){
							$vCOFINS = $dom->createElement("vCOFINS", $dados[4]); 
							$COFINSAliq->appendChild($vCOFINS);
						}
						$COFINS->appendChild($COFINSAliq);
						break;
					case "S03":
						$COFINSQtde = $dom->createElement("COFINSQtde");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$COFINSAliq->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$qBCProd = $dom->createElement("qBCProd", $dados[2]); 
							$COFINSAliq->appendChild($qBCProd);
						}
						if(!$this->vazio($dados[3])){
							$vAliqProd = $dom->createElement("vAliqProd", $dados[3]); 
							$COFINSAliq->appendChild($vAliqProd);
						}
						if(!$this->vazio($dados[4])){
							$vCOFINS = $dom->createElement("vCOFINS", $dados[4]); 
							$COFINSAliq->appendChild($vCOFINS);
						}
						$COFINS->appendChild($COFINSQtde);
						break;
					case "S04":
						$COFINSNT = $dom->createElement("COFINSNT");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$COFINSNT->appendChild($CST);
						}
						$COFINS->appendChild($COFINSNT);
						break;
					case "S05":
						$COFINSOutr = $dom->createElement("COFINSOutr");
						if(!$this->vazio($dados[1])){
							$CST = $dom->createElement("CST", $dados[1]); 
							$COFINSOutr->appendChild($CST);
						}
						if(!$this->vazio($dados[2])){
							$vCOFINS = $dom->createElement("vCOFINS", $dados[2]); 
							$COFINSOutr->appendChild($vCOFINS);
						}
						$COFINS->appendChild($COFINSOutr);
						break;
					case "S07":	
						if(!$this->vazio($dados[1])){
							$vBC = $dom->createElement("vBC", $dados[3]); 
							$COFINSOutr->appendChild($vBC);
						}
						if(!$this->vazio($dados[2])){
							$pCOFINS = $dom->createElement("pCOFINS", $dados[4]); 
							$COFINSOutr->appendChild($pCOFINS);
						}
						break;
					case "S09":	
						if(!$this->vazio($dados[1])){
							$qBCProd = $dom->createElement("qBCProd", $dados[1]); 
							$COFINSOutr->appendChild($qBCProd);
						}
						if(!$this->vazio($dados[2])){
							$vAliqProd = $dom->createElement("vAliqProd", $dados[2]); 
							$COFINSOutr->appendChild($vAliqProd);
						}
						break;
					case "T": 	
						$COFINSST = $dom->createElement("COFINSST");
						if(!$this->vazio($dados[1])){
							$vCOFINS = $dom->createElement("vCOFINS", $dados[1]); 
							$COFINSST->appendChild($vCOFINS);
						}
						$imposto->appendChild($COFINSST);
						break;
					case "T02": 	
						if(!$this->vazio($dados[1])){
							$vBC = $dom->createElement("vBC", $dados[1]); 
							$COFINSST->appendChild($vBC);
						}
						if(!$this->vazio($dados[2])){
							$pCOFINS = $dom->createElement("pCOFINS", $dados[2]); 
							$COFINSST->appendChild($pCOFINS);
						}
						break;
					case "T04": 	
						if(!$this->vazio($dados[1])){
							$qBCProd = $dom->createElement("qBCProd", $dados[1]);
							$COFINSST->appendChild($qBCProd);
						}
						if(!$this->vazio($dados[2])){
							$vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
							$COFINSST->appendChild($vAliqProd);
						}
						break;
					case "U": // ISSQN
						break;
					case "W": // totais
						$total = $dom->createElement("total");
						$infNFe->appendChild($total);
						break;
					case "W02": // ICSM Total
						$ICMSTot = $dom->createElement("ICMSTot");
						// todos esses campos são obrigatórios
						$vBC = $dom->createElement("vBC", $dados[1]);
						$ICMSTot->appendChild($vBC);
						
						$vICMS = $dom->createElement("vICMS", $dados[2]);
						$ICMSTot->appendChild($vICMS);
						
						$vBCST = $dom->createElement("vBCST", $dados[3]);
						$ICMSTot->appendChild($vBCST);
						
						$vST = $dom->createElement("vST", $dados[4]);
						$ICMSTot->appendChild($vST);
						
						$vProd = $dom->createElement("vProd", $dados[5]);
						$ICMSTot->appendChild($vProd);
						
						$vFrete = $dom->createElement("vFrete", $dados[6]);
						$ICMSTot->appendChild($vFrete);
						
						$vSeg = $dom->createElement("vSeg", $dados[7]);
						$ICMSTot->appendChild($vSeg);
						
						$vDesc = $dom->createElement("vDesc", $dados[8]);
						$ICMSTot->appendChild($vDesc);
						
						$vII = $dom->createElement("vII", $dados[9]);
						$ICMSTot->appendChild($vII);
						
						$vIPI = $dom->createElement("vIPI", $dados[10]);
						$ICMSTot->appendChild($vIPI);
						
						$vPIS = $dom->createElement("vPIS", $dados[11]);
						$ICMSTot->appendChild($vPIS);
						
						$vCOFINS = $dom->createElement("vCOFINS", $dados[12]);
						$ICMSTot->appendChild($vCOFINS);
						
						$vOutro = $dom->createElement("vOutro", $dados[13]);
						$ICMSTot->appendChild($vOutro);
						
						$vNF = $dom->createElement("vNF", $dados[14]);
						$ICMSTot->appendChild($vNF);
						
						$total->appendChild($ICMSTot);
						break;
					case "W17": // TAG de grupo de Valores Totais referentes ao ISSQN
						$ISSQNtot = $dom->createElement("ISSQNtot");
						$total->appendChild($ISSQNtot);
						break;
					case "W23": //TAG de grupo de Retenções de Tributos
						$retTrib = $dom->createElement("retTrib");
						if(!$this->vazio($dados[1])){
							$vRetPIS = $dom->createElement("vRetPIS", $dados[1]);
							$retTrib->appendChild($vRetPIS);
						}
						if(!$this->vazio($dados[2])){
							$vRetCOFINS = $dom->createElement("vRetCOFINS", $dados[2]);
							$retTrib->appendChild($vRetCOFINS);
						}
						if(!$this->vazio($dados[3])){
							$vRetCSLL = $dom->createElement("vRetCSLL", $dados[3]);
							$retTrib->appendChild($vRetCSLL);
						}
						if(!$this->vazio($dados[4])){
							$vBCIRRF = $dom->createElement("vBCIRRF", $dados[4]);
							$retTrib->appendChild($vBCIRRF);
						}
						if(!$this->vazio($dados[5])){
							$vIRRF = $dom->createElement("vIRRF", $dados[5]);
							$retTrib->appendChild($vIRRF);
						}
						if(!$this->vazio($dados[6])){
							$vBCRetPrev = $dom->createElement("vBCRetPrev", $dados[6]);
							$retTrib->appendChild($vBCRetPrev);
						}
						if(!$this->vazio($dados[7])){
							$vRetPrev = $dom->createElement("vRetPrev", $dados[7]);
							$retTrib->appendChild($vRetPrev);
						}
						$total->appendChild($retTrib);
						break;
					case "X": // transporte
						$transp = $dom->createElement("transp");
						// todos esses campos são obrigatórios
						$modFrete = $dom->createElement("modFrete", $dados[1]);
						$transp->appendChild($modFrete);
						$infNFe->appendChild($transp);
						break;
					case "X03": 
						$transporta = $dom->createElement("transporta");
						$transp->appendChild($transporta);
						if(!$this->vazio($dados[1])){
							$xNome = $dom->createElement("xNome", $dados[1]);
							$transporta->appendChild($xNome);
						}
						if(!$this->vazio($dados[2])){
							$IE = $dom->createElement("IE", $dados[2]);
							$transporta->appendChild($IE);
						}
						if(!$this->vazio($dados[3])){
							$xEnder = $dom->createElement("xEnder", $dados[3]);
							$transporta->appendChild($xEnder);
						}
						if(!$this->vazio($dados[4])){
							$UF = $dom->createElement("UF", $dados[4]);
							$transporta->appendChild($UF);
						}
						if(!$this->vazio($dados[5])){
							$xMun = $dom->createElement("xMun", $dados[5]);
							$transporta->appendChild($xMun);
						}
						break;
					case "X04":	
						if(!$this->vazio($dados[1])){
							$CNPJ = $dom->createElement("CNPJ", $dados[1]);
							$transporta->appendChild($CNPJ);
						}
						break;
					case "X05":	
						if(!$this->vazio($dados[1])){
							$CPF = $dom->createElement("CPF", $dados[1]);
							$transporta->appendChild($CPF);
						}
						break;
					case "X11":
						$retTransp = $dom->createElement("retTransp");
						// todos esses campos são obrigatórios
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
						$veicTransp = $dom->createElement("veicTransp");
						// todos esses campos são obrigatórios
						$placa = $dom->createElement("placa", $dados[1]);
						$veicTransp->appendChild($placa);
						
						$UF = $dom->createElement("UF", $dados[2]);
						$veicTransp->appendChild($UF);
						
						$RNTC = $dom->createElement("RNTC", $dados[3]);
						$veicTransp->appendChild($RNTC);
						
						
						$transp->appendChild($veicTransp);
						break;
					case "X22":
						$reboque = $dom->createElement("reboque");
						
						$placa = $dom->createElement("placa", $dados[1]);
						$reboque->appendChild($placa);
						
						$UF = $dom->createElement("UF", $dados[2]);
						$reboque->appendChild($UF);
						if(!$this->vazio($dados[3])){
							$RNTC = $dom->createElement("RNTC", $dados[3]);
							$reboque->appendChild($RNTC);
						}
						$transp->appendChild($reboque);
						break;
					case "X26":
						$vol = $dom->createElement("vol");
						
						if(!$this->vazio($dados[1])){
							$qVol = $dom->createElement("qVol", $dados[1]);
							$vol->appendChild($qVol);
						}
						if(!$this->vazio($dados[2])){
							$esp = $dom->createElement("esp", $dados[2]);
							$vol->appendChild($esp);
						}
						if(!$this->vazio($dados[3])){
							$marca = $dom->createElement("marca", $dados[3]);
							$vol->appendChild($marca);
						}
						if(!$this->vazio($dados[4])){
							$nVol = $dom->createElement("nVol", $dados[4]);
							$vol->appendChild($nVol);
						}
						if(!$this->vazio($dados[5])){
							$pesoL = $dom->createElement("pesoL", $dados[5]);
							$vol->appendChild($pesoL);
						}
						if(!$this->vazio($dados[6])){
							$pesoB = $dom->createElement("pesoB", $dados[6]);
							$vol->appendChild($pesoB);
						}
						$transp->appendChild($vol);
						break;
					case "X33":
						$lacres = $dom->createElement("lacres");
						
						$lacre = $dom->createElement("lacre", $dados[1]);
						$lacres->appendChild($lacre);
						
						$vol->appendChild($lacres);
						break;
					case "Y":
						$cobr = $dom->createElement("cobr");
						$infNFe->appendChild($cobr);
					case "Y02":
						$fat = $dom->createElement("fat");
						if(!$this->vazio($dados[1])){
							$nFat = $dom->createElement("nFat", $dados[1]);
							$fat->appendChild($nFat);
						}
						if(!$this->vazio($dados[2])){
							$vOrig = $dom->createElement("vOrig", $dados[2]);
							$fat->appendChild($vOrig);
						}
						if(!$this->vazio($dados[3])){
							$vDesc = $dom->createElement("vDesc", $dados[3]);
							$fat->appendChild($vDesc);
						}
						if(!$this->vazio($dados[4])){
							$vLiq = $dom->createElement("vLiq", $dados[4]);
							$fat->appendChild($vLiq);
						}
						$cobr->appendChild($fat);
						break;
					case "Y07":
						$dup = $dom->createElement("dup");
						if(!$this->vazio($dados[1])){
							$nDup = $dom->createElement("nDup", $dados[1]);
							$dup->appendChild($nDup);
						}
						if(!$this->vazio($dados[2])){
							$dVenc = $dom->createElement("dVenc", $dados[2]);
							$dup->appendChild($dVenc);
						}
						if(!$this->vazio($dados[3])){
							$vDup = $dom->createElement("vDup", $dados[3]);
							$dup->appendChild($vDup);
						}
						$cobr->appendChild($dup);
						break;
					case "Z":
						$infAdic = $dom->createElement("infAdic");
						if(!$this->vazio($dados[1])){
							$infAdFisco = $dom->createElement("infAdFisco", $dados[1]);
							$infAdic->appendChild($infAdFisco);
						}
						if(!$this->vazio($dados[2])){
							$infCpl = $dom->createElement("infCpl", $dados[2]);
							$infAdic->appendChild($infCpl);
						}
						$infNFe->appendChild($infAdic);
						break;
					case "Z04":
						$obsCont = $dom->createElement("obsCont");
						if(!$this->vazio($dados[1])){
							$xCampo = $dom->createElement("xCampo", $dados[1]);
							$obsCont->appendChild($xCampo);
						}
						if(!$this->vazio($dados[2])){
							$xTexto = $dom->createElement("xTexto", $dados[2]);
							$obsCont->appendChild($xTexto);
						}
						$infNFe->appendChild($obsCont);
						break;
						
						case "Z10": //processo referenciado
						$procRef = $dom->createElement("procRef");
						if(!$this->vazio($dados[1])){
							$nProc = $dom->createElement("nProc", $dados[1]);
							$procRef->appendChild($nProc);
						}
						if(!$this->vazio($dados[2])){
							$procRef = $dom->createElement("indProc", $dados[2]);
							$procRef->appendChild($indProc);
						}
						//$infNFe->appendChild($proRef);// vereficar
						$infAdic->appendChild($proRef);//
						break;
						case "ZA"://exportacao
						$exporta = $dom->createElement("exportacao");
						if(!$this->vazio($dados[1])){
							$UFEmbarq = $dom->createElement("UFEmbarq", $dados[1]);
							$exporta->appendChild($UFEmbraq);
						}
						if(!$this->vazio($dados[2])){
							$xLocEmabarq = $dom->createElement("xLocEmabarq", $dados[2]);
							$exporta->appendChild($xLocEmabarq);
						}
						$infNFe->appendChild($exporta);
						break;
						case "ZB": //compra
						$compra = $dom->createElement("compra");
						if(!$this->vazio($dados[1])){
							$xNEmp = $dom->createElement("xNEmp", $dados[1]);
							$compra->appendChild($xNEmp);
						}
						if(!$this->vazio($dados[2])){
							$xPed = $dom->createElement("xPed", $dados[2]);
							$compra->appendChild($xPed);
						}
						if(!$this->vazio($dados[3])){
							$xCont = $dom->createElement("xCont", $dados[2]);
							$compra->appendChild($xCont);
						}
						$infNFe->appendChild($compra);
						break;
				}
				
			}
			
			$dom->appendChild($infNFe);
			$this->xml = $dom->saveXML();
			fclose($handle);
			
			
		}else{
			return "Não foi possível abrir o arquivo, ele existe?";	
		}
	}
	
	function getXML(){ // retorna o XML formatado
		return $this->xml;
	}
	
	function vazio($var){
		$var = trim($var);
		if(strlen($var)>0)
			return false;
		else
			return true;
	}
	
}


?>