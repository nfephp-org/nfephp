<?php
/**
 Exemplo de TXT para nota 2.0
 Régis Matos
 http://www.gestorcustom.com.br
 E-Mail/MSN = regismatos@douradosvirtual.com.br
 skype = regis_matos

**/

require_once("NFeTXT2.php");
   
$nfe = new  NFeTXT2;
$nfe->versao = "2.00";
//$nfe->id = "NFe35101158716523000119550010000000011003000000"; // O id é calculado automaticamente
$nfe->cUF = "35";
$nfe->cNF = "00300000";
$nfe->NatOp = "VENDA";
$nfe->intPag = "0";
$nfe->mod = "55";
$nfe->serie = "1";
$nfe->nNF = "1";
$nfe->dEmi = "2010-11-02"; // ( aaaa-mm-dd ) ficar atento com o formato
$nfe->dSaiEnt = "";
$nfe->hSaiEnt = "";
$nfe->tpNF = "1";
$nfe->cMunFG = "3550308";
$nfe->TpImp = "1";
$nfe->TpEmis = "1";
//$nfe->cDV = "0"; // é gerado automaticamente
$nfe->tpAmb = "2"; // 1-Produção/ 2-Homologação
$nfe->finNFe = "1";
$nfe->procEmi = "3";
$nfe->VerProc = "2.0.3";
$nfe->dhCont = "";
$nfe->xJust = "";

//Dados do emitente
$nfe->emi[XNome] = "FIMATEC TEXTIL LTDA";
$nfe->emi[XFant] = "FIMATEC";
$nfe->emi[IE] = "112006603110";
$nfe->emi[IEST] = "";
$nfe->emi[IM] = "95095870";
$nfe->emi[CNAE] = "0131380";
$nfe->emi[CRT] = "3";
$nfe->emi[CNPJ] = "58716523000119";
//$nfe->emi[CPF] = "";
$nfe->emi[XLgr] = "RUA DOS PATRIOTAS";
$nfe->emi[Nro] = "897";
$nfe->emi[Cpl] = "ARMAZEM 42";
$nfe->emi[Bairro] = "IPIRANGA";
$nfe->emi[CMun] = "3550308";
$nfe->emi[XMun] = "Sao Paulo";
$nfe->emi[UF] = "SP";
$nfe->emi[CEP] = "04207040";
$nfe->emi[cPais] = "1058";
$nfe->emi[xPais] = "BRASIL";
$nfe->emi[fone] = "1120677300";



//destinatario
$nfe->dest[xNome] = "WARDY CONFECCOES LTDA";
$nfe->dest[IE] = "115399484115";
$nfe->dest[ISUF] = "";
$nfe->dest[email] = "wardy@wardy.com.br";
$nfe->dest[CNPJ] = "02536490000170";
//$nfe->dest[CPF] = "";
$nfe->dest[xLgr] = "RUA PARAIBA";
$nfe->dest[nro] = "73";
$nfe->dest[xCpl] = "";
$nfe->dest[xBairro] = "BRAS";
$nfe->dest[cMun] = "3550308";
$nfe->dest[xMun] = "Sao Paulo";
$nfe->dest[UF] = "SP";
$nfe->dest[CEP] = "03013030";
$nfe->dest[cPais] = "1058";
$nfe->dest[xPais] = "BRASIL";
$nfe->dest[fone] = "1122910590";



/* // Informar apenas quando for diferente do endereço do remetente.
$nfe->retirada[CNPJ] = "";
$nfe->retirada[XLgr] = "";
$nfe->retirada[Nro] = "";
$nfe->retirada[XCpl] = "";
$nfe->retirada[XBairro] = "";
$nfe->retirada[CMun] = "";
$nfe->retirada[XMun] = "";
$nfe->retirada[UF] = "";
*/

/*//Informar apenas quando for diferente do endereço do destinatário.
$nfe->entrega[CNPJ] = "";
$nfe->entrega[XLgr] = "";
$nfe->entrega[Nro] = "";
$nfe->entrega[XCpl] = "";
$nfe->entrega[XBairro] = "";
$nfe->entrega[CMun] = "";
$nfe->entrega[XMun] = "";
$nfe->entrega[UF] = "";
*/


//produtos
for ($i = 0; $i < 1; $i++){
	$nfe->prod[$i][infAdProd] = "INFORMACOES ADICIONAIS DO PRODUTO";	
	$nfe->prod[$i][CProd] = "2470BCB90";
	$nfe->prod[$i][CEAN] = "";
	$nfe->prod[$i][XProd] = "DELFOS SND TINTO COM AMACIANTE SO TECIDO 1,80M";
	$nfe->prod[$i][NCM] = "60063200";
	$nfe->prod[$i][EXTIPI] = "";
	$nfe->prod[$i][CFOP] = "5122";
	$nfe->prod[$i][UCom] = KG; 
	$nfe->prod[$i][QCom] = "46.4800";
	$nfe->prod[$i][VUnCom] = "19.0000000000";
	$nfe->prod[$i][VProd] = "833.12";
	$nfe->prod[$i][CEANTrib] = "";
	$nfe->prod[$i][UTrib] = "KG";
	$nfe->prod[$i][QTrib] = "46.4800";
	$nfe->prod[$i][VUnTrib] = "19.0000000000";
    $nfe->prod[$i][VFrete] = "";
	$nfe->prod[$i][VSeg] 	= "";
    $nfe->prod[$i][VDesc] 	= "";
	$nfe->prod[$i][vOutro] 	= "";
	$nfe->prod[$i][indTot] 	= "1";
	$nfe->prod[$i][xPed] 	= "060110-1030";
	$nfe->prod[$i][nItemPed] 	= "1";	
	
	
	//icms
	$nfe->icms[$i][Orig] 	= "0";	
	$nfe->icms[$i][CST] 	= "00";	
	$nfe->icms[$i][ModBC] 	= "3";	
	$nfe->icms[$i][VBC] 	= "588.78";	
	$nfe->icms[$i][PICMS] 	= "18.00";	
	$nfe->icms[$i][VICMS] 	= "105.98";	
	
	//ipi
	$nfe->ipi[$i][ClEnq] 	 = "";	
	$nfe->ipi[$i][CNPJProd] = "";	
	$nfe->ipi[$i][CSelo] 	 = "";	
	$nfe->ipi[$i][QSelo]  	 = "";	
	$nfe->ipi[$i][CEnq] 	 = "999";	
	$nfe->ipi[$i][CST]  = "52";
	
	//pis
	$nfe->pis[$i][CST]  = "01";
	$nfe->pis[$i][VBC]  = "883.12";
	$nfe->pis[$i][PPIS]  = "1.65";
	$nfe->pis[$i][VPIS]  = "14.57";
	
	
	//cofins
	$nfe->cofins[$i][CST]  = "01";
	$nfe->cofins[$i][VBC]  = "883.12";
	$nfe->cofins[$i][PCOFINS]  = "7.60";
	$nfe->cofins[$i][VCOFINS]  = "67.11";
	
	//cofins st
	$nfe->cofinsst[$i][VCOFINS] = "";
	
	$nfe->cofinsst[$i][VBC] = "";
	$nfe->cofinsst[$i][PCOFINS] = "";
	
	$nfe->cofinsst[$i][QBCProd] = "";
	$nfe->cofinsst[$i][VAliqProd] = "";
	
	
	
} // fim dos produtos

//totais
$nfe->total[vBC] = "588.78";
$nfe->total[vICMS] = "105.98";
$nfe->total[vBCST] = "0.00";
$nfe->total[vST] = "0.00";
$nfe->total[vProd] = "833.12";
$nfe->total[vFrete] = "0.00";
$nfe->total[vSeg] = "0.00";
$nfe->total[vDesc] = "0.00";
$nfe->total[vII] = "0.00";
$nfe->total[vIPI] = "0.00";
$nfe->total[vPIS] = "14.57";
$nfe->total[vCOFINS] = "67.11";
$nfe->total[vOutro] = "0.00";
$nfe->total[vNF] = "833.12";
//$nfe->total[VRetPIS] = "";

// Transporte
$nfe->transp[ModFrete] = "1";
$nfe->transp[XNome] = "RETIRA";
$nfe->transp[CNPJ] = "";
//$nfe->transp[CPF] = "";
$nfe->transp[IE] = "";
$nfe->transp[XEnder] = "";
$nfe->transp[UF] = "";
$nfe->transp[XMun] = "";

$nfe->transp[QVol] = "3";
$nfe->transp[Esp] = "VOLUMES";
$nfe->transp[Marca] = "";
$nfe->transp[NVol] = "";
$nfe->transp[PesoL] = "46.480";
$nfe->transp[PesoB] = "50.000";

// dados da fatura
$nfe->fatura[NFat] = "0001";
$nfe->fatura[VOrig] = "883.12";
$nfe->fatura[VDesc] = "";
$nfe->fatura[VLiq] = "883.12";


// dados da duplicata(s)
for ($i = 0; $i < 1; $i++){
	$nfe->parcela[$i][NDup] = "0001-1";
	$nfe->parcela[$i][DVenc] = "2010-12-20";
	$nfe->parcela[$i][VDup] = "883.12";
}

$nfe->infoAdd[InfAdFisco] = "EMITIDO NOS TERMOS DO ARTIGO 400-C DO DECRETO 48042/03 SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
$nfe->infoAdd[InfCpl] = "";



if ($nfe->validaTxt() != "OK"){

//imprime o erro na tela
print $nfe->validaTxt();

}
else{

	//print $nfe->montaTXT();

	//endereço onde o txt sera gravado
	$path = "/var/www";
	$nfe->geraArquivo($path);
}	
		
		
?>