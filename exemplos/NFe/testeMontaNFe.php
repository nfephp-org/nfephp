<?php

require_once('/var/www/nfephp3/libs/NFe/MakeNFePHP.class.php');

$nfe = new MakeNFe();

//criar as tags em sequencia 

//Numero e versão da NFe (infNFe)
$chave = '35140258716523000119550000000280051760377394';
$versao = '3.10';
$resp = $nfe->taginfNFe($chave, $versao);

//Dados da NFe (ide)
$cUF = '35'; //codigo numerico do estado
$cNF = '76037739'; //numero aleatório da NF
$natOp = 'VENDA DE PRODUTO'; //natureza da operação
$indPag = '0'; //0=Pagamento à vista; 1=Pagamento a prazo; 2=Outros
$mod = '65'; //modelo da NFe 55 ou 65 essa última NFCe
$serie = '0'; //serie da NFe
$nNF = '28005'; // numero da NFe
$dhEmi = '2014-02-03';  //para versão 3.00 '2014-02-03T13:22:42-3.00' não informar para NFCe
$dhSaiEnt = '2014-02-03'; //versão 2.00, 3.00 e 3.10
$tpNF = '1';
$idDest = ''; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
$cMunFG = '3550308';
$tpImp = '1'; //0=Sem geração de DANFE; 1=DANFE normal, Retrato; 2=DANFE normal, Paisagem;
              //3=DANFE Simplificado; 4=DANFE NFC-e; 5=DANFE NFC-e em mensagem eletrônica
              //(o envio de mensagem eletrônica pode ser feita de forma simultânea com a impressão do DANFE;
              //usar o tpImp=5 quando esta for a única forma de disponibilização do DANFE).
$tpEmis = '1'; //1=Emissão normal (não em contingência);
               //2=Contingência FS-IA, com impressão do DANFE em formulário de segurança;
               //3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional);
               //4=Contingência DPEC (Declaração Prévia da Emissão em Contingência);
               //5=Contingência FS-DA, com impressão do DANFE em formulário de segurança;
               //6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN);
               //7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS);
               //9=Contingência off-line da NFC-e (as demais opções de contingência são válidas também para a NFC-e);
               //Nota: Para a NFC-e somente estão disponíveis e são válidas as opções de contingência 5 e 9.
$cDV = '4'; //digito verificador
$tpAmb = '1'; //1=Produção; 2=Homologação
$finNFe = '1'; //1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devolução/Retorno.
$indFinal = ''; //0=Não; 1=Consumidor final;
$indPres = ''; //0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
               //1=Operação presencial;
               //2=Operação não presencial, pela Internet;
               //3=Operação não presencial, Teleatendimento;
               //4=NFC-e em operação com entrega a domicílio;
               //9=Operação não presencial, outros.
$procEmi = '0'; //0=Emissão de NF-e com aplicativo do contribuinte;
                //1=Emissão de NF-e avulsa pelo Fisco;
                //2=Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco;
                //3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco.
$verProc = '3.22.8'; //versão do aplicativo emissor
$dhCont = ''; //entrada em contingência AAAA-MM-DDThh:mm:ssTZD
$xJust = ''; //Justificativa da entrada em contingência

$resp = $nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);

//refNFe NFe referenciada  
$refNFe = '12345678901234567890123456789012345678901234';
$resp = $nfe->tagrefNFe($refNFe);

//refNF Nota Fiscal 1A referenciada
$cUF = '35';
$AAMM = '1312';
$CNPJ = '12345678901234';
$mod = '1A';
$serie = '0';
$nNF = '1234';
$resp = $nfe->tagrefNF($cUF, $AAMM, $CNPJ, $mod, $serie, $nNF);

//NFPref Nota Fiscal Produtor Rural referenciada
$cUF = '35';
$AAMM = '1312';
$CNPJ = '12345678901234';
$CPF = '123456789';
$IE = '123456';
$mod = '1';
$serie = '0';
$nNF = '1234';
$resp = $nfe->tagrefNFP($cUF, $AAMM, $CNPJ, $CPF, $IE, $mod, $serie, $nNF);

//CTeref CTe referenciada
$refCTe = '12345678901234567890123456789012345678901234';
$resp = $nfe->tagrefCTe($refCTe);

//ECFref ECF referenciada
$mod = '90';
$nECF = '12243';
$nCOO = '111';
$resp = $nfe->tagrefECF($mod, $nECF, $nCOO);

//Dados do emitente
$CNPJ = '58716523000119';
$CPF = '';
$xNome = 'FIMATEC TEXTIL LTDA';
$xFant = 'FIMATEC';
$IE = '112006603110';
$IEST = '';
$IM = '95095870';
$CNAE = '0131380';
$CRT = '3';
$resp = $nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);

//endereço do emitente
$xLgr = 'RUA DOS PATRIOTAS';
$nro = '897';
$xCpl = 'ARMAZEM 42';
$xBairro = 'IPIRANGA';
$cMun = '3550308';
$xMun = 'Sao Paulo';
$UF = 'SP';
$CEP = '04207040';
$cPais = '1058';
$xPais = 'BRASIL';
$fone = '1120677300';
$resp = $nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
        
//destinatário
$CNPJ = '10702368000155';
$CPF = '';
$idEstrangeiro = '';
$xNome = 'M R SANTOS DE PAULA TECIDOS ME';
$indIEDest = '';
$IE = '244827055110';
$ISUF = '';
$IM = '';
$email = 'mrsptm@seuemail.com.br';
$resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);

//Endereço do destinatário
$xLgr = 'RUA LUZITANIA';
$nro = '1163';
$xCpl = '';
$xBairro = 'CENTRO';
$cMun = '3509502';
$xMun = 'Campinas';
$UF = 'SP';
$CEP = '13015121';
$cPais = '1058';
$xPais = 'BRASIL';
$fone = '1932740607';
$resp = $nfe->tagenderDest($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);

//Identificação do local de retirada (se diferente do emitente)
$CNPJ = '12345678901234';
$CPF = '';
$xLgr = 'Rua Vanish';
$nro = '000';
$xCpl = 'Ghost';
$xBairro = 'Assombrado';
$cMun = '3509502';
$xMun = 'Campinas';
$UF = 'SP';
$resp = $nfe->tagretirada($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

//Identificação do local de Entrega (se diferente do destinatário)
$CNPJ = '12345678901234';
$CPF = '';
$xLgr = 'Viela Mixuruca';
$nro = '2';
$xCpl = 'Quabrada do malandro';
$xBairro = 'Favela Mau Olhado';
$cMun = '3509502';
$xMun = 'Campinas';
$UF = 'SP';
$resp = $nfe->tagentrega($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

//Identificação dos autorizados para fazer o download da NFe (somente versão 3.1)
$aAut = array('11111111111111','2222222','33333333333333');
foreach ($aAut as $aut) {
    if (strlen($aut) == 14) {
        $resp = $nfe->tagautXML($aut);
    } else {
        $resp = $nfe->tagautXML('', $aut);
    }
}

//produtos
$aP[] = array(
        'nItem' => '1',
        'cProd' => '22314',
        'cEAN' => '478901542312456',
        'xProd' => 'Teletransporte para longe',
        'NCM' => '1224.10.10',
        'NVE' => '',
        'EXTIPI' => '',
        'CFOP' => '5101',
        'uCom' => 'UN',
        'qCom' => '1',
        'vUnCom' => '10.00',
        'vProd' => '10.00',
        'cEANTrib' => '',
        'uTrib' => 'U',
        'qTrib' => '',
        'vUnTrib' => '',
        'vFrete' => '',
        'vSeg' => '',
        'vDesc' => '',
        'vOutro' => '',
        'indTot' => '1',
        'xPed' => '111',
        'nItemPed' => '3',
        'nFCI' => 'AEC0BE59-FE89-4AA3-B976-AA24F9C00280');
$aP[] = array(
        'nItem' => '2',
        'cProd' => '1222',
        'cEAN' => '4789015423121222',
        'xProd' => 'Queda de cabelo',
        'NCM' => '1224.10.10',
        'NVE' => '',
        'EXTIPI' => '',
        'CFOP' => '5101',
        'uCom' => 'UN',
        'qCom' => '1',
        'vUnCom' => '20.00',
        'vProd' => '20.00',
        'cEANTrib' => '',
        'uTrib' => 'UN',
        'qTrib' => '1',
        'vUnTrib' => '',
        'vFrete' => '10.00',
        'vSeg' => '1.00',
        'vDesc' => '1.00',
        'vOutro' => '4.00',
        'indTot' => '1',
        'xPed' => '111',
        'nItemPed' => '2',
        'nFCI' => 'AE324E22-FFFF-32AC-XX44-AA24F9C12345');

foreach ($aP as $prod) {
    $nItem = $prod['nItem'];
    $cProd = $prod['cProd'];
    $cEAN = $prod['cEAN'];
    $xProd = $prod['xProd'];
    $NCM = $prod['NCM'];
    $NVE = $prod['NVE'];
    $EXTIPI = $prod['EXTIPI'];
    $CFOP = $prod['CFOP'];
    $uCom = $prod['uCom'];
    $qCom = $prod['qCom'];
    $vUnCom = $prod['vUnCom'];
    $vProd = $prod['vProd'];
    $cEANTrib = $prod['cEANTrib'];
    $uTrib = $prod['uTrib'];
    $qTrib = $prod['qTrib'];
    $vUnTrib = $prod['vUnTrib'];
    $vFrete = $prod['vFrete'];
    $vSeg = $prod['vSeg'];
    $vDesc = $prod['vDesc'];
    $vOutro = $prod['vOutro'];
    $indTot = $prod['indTot'];
    $xPed = $prod['xPed'];
    $nItemPed = $prod['nItemPed'];
    $nFCI = $prod['nFCI'];
    $resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $NVE, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);
}

//DI
$nItem = '1';
$nDI = '234556786';
$dDI = '22/12/2013';
$xLocDesemb = 'SANTOS';
$UFDesemb = 'SP';
$dDesemb = '22/12/2013';
$tpViaTransp = '1';
$vAFRMM = '1.00';
$tpIntermedio = '0';
$resp = $nfe->tagDI($nItem, $nDI, $dDI, $xLocDesemb, $UFDesemb, $dDesemb, $tpViaTransp, $vAFRMM, $tpIntermedio);


//adi
$nItem = '1';
$nDI = '234556786';
$nAdicao = '1';
$nSeqAdicC = '1111';
$cFabricante = 'seila';
$vDescDI = '0.00';
$nDraw = '9393939';
$resp = $nfe->tagadi($nItem, $nDI, $nAdicao, $nSeqAdicC, $cFabricante, $vDescDI, $nDraw);
        

//detExport
$nItem = '2';
$nDraw = '9393939';
$exportInd = '1';
$nRE = '2222';
$chNFe = '1234567890123456789012345678901234';
$qExport = '100';
$resp = $nfe->tagdetExport($nItem, $nDraw, $exportInd, $nRE, $chNFe, $qExport);

//impostos dos produtos


//impostos totais


//frete
$modFrete = '0'; //0=Por conta do emitente; 1=Por conta do destinatário/remetente; 2=Por conta de terceiros;
$resp = $nfe->tagtransp($modFrete);

//transportadora
$CNPJ = '';
$CPF = '12345678901';
$xNome = 'Ze da Carroca';
$IE = '';
$xEnder = 'Beco Escuro';
$xMun = 'Campinas';
$UF = 'SP';
$resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);

//valores retidos para transporte
$vServ = '258,69'; //Valor do Serviço
$vBCRet = '258,69'; //BC da Retenção do ICMS
$pICMSRet = '10,00'; //Alíquota da Retenção
$vICMSRet = '25,87'; //Valor do ICMS Retido
$CFOP = '5352';
$cMunFG = '3509502'; //Código do município de ocorrência do fato gerador do ICMS do transporte
$resp = $nfe->tagretTransp($vServ, $vBCRet, $pICMSRet, $vICMSRet, $CFOP, $cMunFG);

//dados dos veiculos de transporte
$placa = 'AAA1212';
$UF = 'SP';
$RNTC = '12345678';
$resp = $nfe->tagveicTransp($placa, $UF, $RNTC);

//dados dos reboques
$aReboque = array(
    array('ZZQ9999', 'SP', '', '', ''),
    array('QZQ2323', 'SP', '', '', '')
);
foreach ($aReboque as $reb) {
    $placa = $reb[0];
    $UF = $reb[1];
    $RNTC = $reb[2];
    $vagao = $reb[3];
    $balsa = $reb[4];
    $resp = $nfe->tagreboque($placa, $UF, $RNTC, $vagao, $balsa);
}

//dados dos volumes transportados
$aVol = array(
    array('1','caixa','','12345','12,20','12,78',array('A1','A2','A3','A4')),
    array('1','caixa','','34567','23,67','24,12','')
);
foreach ($aVol as $vol) {
    $qVol = $vol[0]; //Quantidade de volumes transportados
    $esp = $vol[1]; //Espécie dos volumes transportados
    $marca = $vol[2]; //Marca dos volumes transportados
    $nVol = $vol[3]; //Numeração dos volume
    $pesoL = $vol[4];
    $pesoB = $vol[5];
    $aLacres = $vol[6];
    $resp = $nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);
}

//dados da fatura
$nFat = '1111';
$vOrig = '6000,00';
$vDesc = '';
$vLiq = '';
$resp = $nfe->tagfat($nFat, $vOrig, $vDesc, $vLiq);

//dados das duplicadas
$aDup = array(
    array('1111-1','2014-01-10','1000,00'),
    array('1111-2','2014-02-10','1000,00'),
    array('1111-3','2014-03-10','1000,00'),
    array('1111-4','2014-04-10','1000,00'),
    array('1111-5','2014-05-10','1000,00'),
    array('1111-6','2014-06-10','1000,00')
    );
foreach ($aDup as $dup) {
    $nDup = $dup[0];
    $dVenc = $dup[1];
    $vDup = $dup[2];
    $resp = $nfe->tagdup($nDup, $dVenc, $vDup);
}


//*************************************************************
//Grupo obrigatório para a NFC-e. Não informar para a NF-e.
$tPag = '03'; //01=Dinheiro 02=Cheque 03=Cartão de Crédito 04=Cartão de Débito 05=Crédito Loja 10=Vale Alimentação 11=Vale Refeição 12=Vale Presente 13=Vale Combustível 99=Outros
$vPag = '1452,33';
$resp = $nfe->tagpag($tPag, $vPag);

//se a operção for com cartão de crédito essa informação é obrigatória
$CNPJ = '31551765000143'; //CNPJ da operadora de cartão
$tBand = '01'; //01=Visa 02=Mastercard 03=American Express 04=Sorocred 99=Outros
$cAut = 'AB254FC79001'; //número da autorização da transação
$resp = $nfe->tagcard($CNPJ, $tBand, $cAut);
//**************************************************************

//informações Adicionais
$infAdFisco = 'Informacao adicional do fisco';
$infCpl = 'Informacoes complementares do emitente';
$resp = $nfe->taginfAdic($infAdFisco, $infCpl);

//observações emitente
$aObsC = array(
    array('email','roberto@x.com.br'),
    array('email','rodrigo@y.com.br'),
    array('email','rogerio@w.com.br'));
foreach ($aObsC as $obs) {
    $xCampo = $obs[0];
    $xTexto = $obs[1];
    $resp = $nfe->tagobsCont($xCampo, $xTexto);
}

//observações fisco
$aObsF = array(
    array('email','roberto@x.com.br'),
    array('email','rodrigo@y.com.br'),
    array('email','rogerio@w.com.br'));
foreach ($aObsF as $obs) {
    $xCampo = $obs[0];
    $xTexto = $obs[1];
    $resp = $nfe->tagobsFisco($xCampo, $xTexto);
}

//Dados do processo
//0=SEFAZ; 1=Justiça Federal; 2=Justiça Estadual; 3=Secex/RFB; 9=Outros
$aProcRef = array(
    array('nProc1','0'),
    array('nProc2','1'),
    array('nProc3','2'),
    array('nProc4','3'),
    array('nProc5','9')
);
foreach ($aProcRef as $proc) {
    $nProc = $proc[0];
    $indProc = $proc[1];
    $resp = $nfe->tagprocRef($nProc, $indProc);
}

//dados exportação
$UFSaidaPais = 'SP';
$xLocExporta = 'Maritimo';
$xLocDespacho = 'Porto Santos';
$resp = $nfe->tagexporta($UFSaidaPais, $xLocExporta, $xLocDespacho);

//dados de compras
$xNEmp = '';
$xPed = '12345';
$xCont = 'A342212';
$resp = $nfe->tagcompra($xNEmp, $xPed, $xCont);

//dados da colheita de cana
$safra = '2014';
$ref = '01/2014';
$resp = $nfe->tagcana($safra, $ref);

$aForDia = array(
    array('1', '100', '1400', '1000', '1400'),
    array('2', '100', '1400', '1000', '1400'),
    array('3', '100', '1400', '1000', '1400'),
    array('4', '100', '1400', '1000', '1400'),
    array('5', '100', '1400', '1000', '1400'),
    array('6', '100', '1400', '1000', '1400'),
    array('7', '100', '1400', '1000', '1400'),
    array('8', '100', '1400', '1000', '1400'),
    array('9', '100', '1400', '1000', '1400'),
    array('10', '100', '1400', '1000', '1400'),
    array('11', '100', '1400', '1000', '1400'),
    array('12', '100', '1400', '1000', '1400'),
    array('13', '100', '1400', '1000', '1400'),
    array('14', '100', '1400', '1000', '1400')
);
foreach ($aForDia as $forDia) {
    $dia = $forDia[0];
    $qtde = $forDia[1];
    $qTotMes = $forDia[2];
    $qTotAnt = $forDia[3];
    $qTotGer = $forDia[4];
    $resp = $nfe->tagforDia($dia, $qtde, $qTotMes, $qTotAnt, $qTotGer);
}

//monta a NFe e retorna na tela
$xml = $nfe->montaNFe();

header('Content-type: text/xml; charset=UTF-8');
echo $xml;
