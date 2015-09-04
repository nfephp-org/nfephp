<?php

require_once(dirname(__FILE__).'/../../libs/NFe/ToolsNFePHP.class.php');

$nfe = new ToolsNFePHP;

header('Content-type: text/html; charset=UTF-8');

$sUF = 'AC;AL;AM;AP;BA;CE;DF;ES;GO;MA;MG;MS;MT;PA;PB;PE;PI;PR;RJ;RN;RO;RR;RS;SC;SE;SP;TO';
$sUF = 'SP';

//determina o ambiente 1-produção 2-homologação
$tpAmb = '2';
$aUF   = explode(';', $sUF);

if ($tpAmb == 1) {

    $sAmb = 'Produção';

} else {

    $sAmb = 'Homologação';

}

foreach ($aUF as $UF) {

    echo '<br><hr/><br>';
    echo "$UF [ $sAmb ] ==> $UF <br>";

    $resp = $nfe->statusServico($UF, $tpAmb, $retorno);

    echo print_r($retorno);
    echo '<br>';
    echo $nfe->errMsg.'<br>';
    echo '<pre>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</pre><br>';
    echo $UF . '[' . $sAmb . '] - ' . $retorno['xMotivo'] . '<br><br><hr/><br>';

    flush();

}

/*
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
*/
