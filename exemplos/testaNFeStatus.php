<?php
require_once('../libs/ToolsNFePHP.class.php');

$nfe = new ToolsNFePHP;
header('Content-type: text/html; charset=UTF-8');
$sUF = 'AC;AL;AM;AP;BA;CE;DF;ES;GO;MA;MG;MS;MT;PA;PB;PE;PI;PR;RJ;RN;RO;RR;RS;SC;SE;SP;TO';
//$sUF = 'PR'; //falha ao utilizar PR com SOAP nativo

//determina o tipo de conector 1-SOAP ou 2-cURL
$modSOAP = '2';
//determina o ambiente 1-produção 2-homologação
$tpAmb= '2';
//habilita uso do scan
//$nfe->enableSCAN = true;

$aUF = explode(';',$sUF);

if($tpAmb == 1){
    $sAmb='Produção';
} else {
    $sAmb='Homologação';
}
foreach ($aUF as $UF){
    echo '<BR><HR/><BR>';
    echo $UF . '[' . $sAmb . '] - modSOAP = ' . $modSOAP  . '<BR>';
    $resp = $nfe->statusServico($UF,$tpAmb,$modSOAP);
    
    echo print_r($resp);
    echo '<BR>';
    echo $nfe->errMsg.'<BR>';
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
    echo $UF . '[' . $sAmb . '] - ' . $resp['xMotivo'] . '<BR><BR><HR/><BR>';
    flush();
}
?>
