<?php
/**
  Ultima atualização = 21/04/2011
  Exemplo de TXT para nota 2.0
 
  Régis Matos
  Site       = http://www.gestorcustom.com.br
  E-Mail/MSN = regismatos@douradosvirtual.com.br
  skype      = regis_matos

**/

require_once("NFeTXT2.class.php");
   
$nfe = new  NFeTXT2;

$nfe->setVersao("2.00");
//$nfe->id = "NFe35101158716523000119550010000000011003000000"; // O id é calculado automaticamente
$nfe->setCUF("35");
$nfe->setCNF("00300000");
$nfe->setNatOp("VENDA");
$nfe->setIndPag("0");
$nfe->setMod("55");
$nfe->setSerie("1");
$nfe->setNNF("1");
$nfe->setDEmi("2010-11-02"); // ( aaaa-mm-dd ) ficar atento com o formato
$nfe->setDSaiEnt("");
$nfe->setHSaiEnt("");
$nfe->setTpNF("1");
$nfe->setCMunFG("3550308");
$nfe->setTpImp("1");
$nfe->setTpEmis("1");
//$nfe->setCDV("0"); // é gerado automaticamente
$nfe->setTpAmb("2"); // 1-Produção/ 2-Homologação
$nfe->setFinNFe("1");
$nfe->setProcEmi("3");
$nfe->setVerProc("2.0.3");
$nfe->setDhCont("");
$nfe->setXJust("");

//Dados do emitente
$emi[XNome]  = "FIMATEC TEXTIL LTDA";
$emi[XFant]  = "FIMATEC";
$emi[IE] 	 = "112006603110";
$emi[IEST]   = "";
$emi[IM]     = "95095870";
$emi[CNAE]   = "0131380";
$emi[CRT]    = "3";
$emi[CNPJ]   = "58716523000119";
//$emi[CPF]  = "";
$emi[xLgr]   = "RUA DOS PATRIOTAS";
$emi[nro]    = "897";
$emi[Cpl] 	 = "ARMAZEM 42";
$emi[Bairro] = "IPIRANGA";
$emi[CMun]   = "3550308";
$emi[XMun]   = "Sao Paulo";
$emi[UF]     = "SP";
$emi[CEP]    = "04207040";
$emi[cPais]  = "1058";
$emi[xPais]  = "BRASIL";
$emi[fone]   = "1120677300";

$nfe->setEmi($emi);


//destinatario
$dest[xNome]   = "WARDY CONFECCOES LTDA";
$dest[IE]      = "115399484115";
$dest[ISUF]    = "";
$dest[email]   = "wardy@wardy.com.br";
$dest[CNPJ]    = "02536490000170";
//$dest[CPF]   = "";
$dest[xLgr]    = "RUA PARAIBA";
$dest[nro]     = "73";
$dest[xCpl]    = "";
$dest[xBairro] = "BRAS";
$dest[cMun]    = "3550308";
$dest[xMun]    = "Sao Paulo";
$dest[UF]      = "SP";
$dest[CEP]     = "03013030";
$dest[cPais]   = "1058";
$dest[xPais]   = "BRASIL";
$dest[fone]    = "1122910590";

$nfe->setDest($dest);

/** // Informar apenas quando for diferente do endereço do remetente.
$retirada[CNPJ] = "";
$retirada[xLgr] = "";
$retirada[nro] = "";
$retirada[XCpl] = "";
$retirada[XBairro] = "";
$retirada[CMun] = "";
$retirada[XMun] = "";
$retirada[UF] = "";

$nfe->setRetirada($retirada);
**/

/** //Informar apenas quando for diferente do endereço do destinatário.
$entrega[CNPJ] = "";
$entrega[xLgr] = "";
$entrega[nro] = "";
$entrega[XCpl] = "";
$entrega[XBairro] = "";
$entrega[CMun] = "";
$entrega[XMun] = "";
$entrega[UF] = "";

$nfe->setEntrega($entrega);
**/



//produtos
//Obs. A variavel $i tem que ser iniciado com ( 0 ) zero 
for ($i = 0; $i < 1; $i++){
    $prod[$i][infAdProd] = "INFORMACOES ADICIONAIS DO PRODUTO";
    $prod[$i][CProd]     = "2470BCB90";
    $prod[$i][CEAN]      = "";
    $prod[$i][XProd]     = "DELFOS SND TINTO COM AMACIANTE SO TECIDO 1,80M";
    $prod[$i][NCM]       = "60063200";
    $prod[$i][EXTIPI]    = "";
    $prod[$i][CFOP]      = "5122";
    $prod[$i][UCom]      = "KG";
    $prod[$i][QCom]      = "46.4800";
    $prod[$i][VUnCom]    = "19.0000000000";
    $prod[$i][VProd]     = "833.12";
    $prod[$i][CEANTrib]  = "";
    $prod[$i][UTrib]     = "KG";
    $prod[$i][QTrib]     = "46.4800";
    $prod[$i][VUnTrib]   = "19.0000000000";
    $prod[$i][VFrete]    = "";
    $prod[$i][VSeg] 	 = "";
    $prod[$i][VDesc]     = "";
    $prod[$i][vOutro] 	 = "";
    $prod[$i][indTot] 	 = "1";
    $prod[$i][xPed] 	 = "060110-1030";
    $prod[$i][nItemPed]  = "1";

    //icms
    $icms[$i][Orig]    = "0";
    $icms[$i][CST]     = "00";
	//$icms[$i][CSOSN] = "101";
	$icms[$i][ModBC]   = "3";
    $icms[$i][VBC] 	   = "588.78";
    $icms[$i][PICMS]   = "18.00";
    $icms[$i][VICMS]   = "105.98";
	
    //ipi
    $ipi[$i][ClEnq]    = "";
    $ipi[$i][CNPJProd] = "";
    $ipi[$i][CSelo]    = "";
    $ipi[$i][QSelo]    = "";
    $ipi[$i][CEnq] 	   = "999";
    $ipi[$i][CST]      = "52";

    //pis
    $pis[$i][CST]  = "01";
    $pis[$i][VBC]  = "883.12";
    $pis[$i][PPIS] = "1.65";
    $pis[$i][VPIS] = "14.57";

    //cofins
    $cofins[$i][CST]     = "01";
    $cofins[$i][VBC]     = "883.12";
    $cofins[$i][PCOFINS] = "7.60";
    $cofins[$i][VCOFINS] = "67.11";

    //cofins st
    $cofinsst[$i][VCOFINS]   = "";
    $cofinsst[$i][VBC]       = "";
    $cofinsst[$i][PCOFINS]   = "";
    $cofinsst[$i][QBCProd]   = "";
    $cofinsst[$i][VAliqProd] = "";

} // fim dos produtos


$nfe->setProd($prod);
$nfe->setIcms($icms);
$nfe->setIpi($ipi);
$nfe->setPis($pis);
$nfe->setCofins($cofins);
$nfe->setCofinsst($cofinsst);



//totais
$total[vBC]     = "588.78";
$total[vICMS]   = "105.98";
$total[vBCST]   = "0.00";
$total[vST]     = "0.00";
$total[vProd]   = "833.12";
$total[vFrete]  = "0.00";
$total[vSeg]    = "0.00";
$total[vDesc]   = "0.00";
$total[vII]     = "0.00";
$total[vIPI]    = "0.00";
$total[vPIS]    = "14.57";
$total[vCOFINS] = "67.11";
$total[vOutro]  = "0.00";
$total[vNF]     = "833.12";
//$total[VRetPIS] = "";

$nfe->setTotal($total);


// Transporte
$transp[ModFrete] = "1";
$transp[XNome]    = "RETIRA";
$transp[CNPJ]     = "";
//$transp[CPF]    = "";
$transp[IE]       = "";
$transp[XEnder]   = "";
$transp[UF]       = "";
$transp[XMun]     = "";
$transp[QVol]     = "3";
$transp[Esp]      = "VOLUMES";
$transp[Marca]    = "";
$transp[NVol]     = "";
$transp[PesoL]    = "46.480";
$transp[PesoB]    = "50.000";

$nfe->setTransp($transp);



// dados da fatura
$fatura[NFat]  = "0001";
$fatura[VOrig] = "883.12";
$fatura[VDesc] = "";
$fatura[VLiq]  = "883.12";

$nfe->setFatura($fatura);


// dados da duplicata(s)
for ($i = 0; $i < 1; $i++){
	$parcela[$i][NDup]  = "0001-1";
	$parcela[$i][DVenc] = "2010-12-20";
	$parcela[$i][VDup]  = "883.12";
}

$nfe->setParcela($parcela);


$infoAdd[InfAdFisco] = "EMITIDO NOS TERMOS DO ARTIGO 400-C DO DECRETO 48042/03 SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
$infoAdd[InfCpl] = "";

$nfe->setInfoAdd($infoAdd);


if ($nfe->validaTxt() != "OK"){

    //imprime o erro na tela
	//$erro = $nfe->validaTxt();
    print $nfe->validaTxt();

}
else{

    print $nfe->montaTXT();

    //endereço onde o txt sera gravado
    $path = "/var/www";
    $nfe->geraArquivo($path);
}	
		
		
?>