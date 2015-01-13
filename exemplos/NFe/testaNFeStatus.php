<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');

$nfe = new ToolsNFePHP;
header('Content-type: text/html; charset=UTF-8');
$sUF = 'AC;AL;AM;AP;BA;CE;DF;ES;GO;MA;MG;MS;MT;PA;PB;PE;PI;PR;RJ;RN;RO;RR;RS;SC;SE;SP;TO';
//$sUF = 'SP'; //falha ao utilizar PR com SOAP nativo
$sUF = 'BA;CE;GO;MG;MS;MT;PE;PR';

//determina o ambiente 1-produção 2-homologação
$tpAmb= '2';
$aUF = explode(';', $sUF);
if ($tpAmb == 1) {
    $sAmb='Produção';
} else {
    $sAmb='Homologação';
}

foreach ($aUF as $UF) {
    $alias = $nfe->aliaslist[$UF];
    echo '<BR><HR/><BR>';
    echo "$UF [ $sAmb ] ==> $alias <BR>";
    $resp = $nfe->statusServico($UF, $tpAmb, $retorno);
    echo print_r($retorno);
    echo '<BR>';
    echo $nfe->errMsg.'<BR>';
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
    echo $UF . '[' . $sAmb . '] - ' . $retorno['xMotivo'] . '<BR><BR><HR/><BR>';
    flush();
}
//Contignecia SVCAN
$UF = 'SP';
$nfe->ativaContingencia('SVCAN');
$alias = 'SVCAN';
echo '<BR><HR/><BR>';
echo "$UF [ $sAmb ] ==> $alias <BR>";
$resp = $nfe->statusServico($UF, $tpAmb, $retorno);
echo print_r($retorno);
echo '<BR>';
echo $nfe->errMsg.'<BR>';
echo '<PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
echo $UF . '[' . $sAmb . '] - ' . $retorno['xMotivo'] . '<BR><BR><HR/><BR>';
flush();

//Contingecia SVCRS
$nfe->ativaContingencia('SVCRS');
$alias = 'SVCRS';
echo '<BR><HR/><BR>';
echo "$UF [ $sAmb ] ==> $alias <BR>";
$resp = $nfe->statusServico($UF, $tpAmb, $retorno);
echo print_r($retorno);
echo '<BR>';
echo $nfe->errMsg.'<BR>';
echo '<PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
echo $UF . '[' . $sAmb . '] - ' . $retorno['xMotivo'] . '<BR><BR><HR/><BR>';
flush();
