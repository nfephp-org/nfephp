<?php

require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$modSOAP = '2'; //usando cURL
$tpAmb = '1';//usando produção
$indNFe = '0';
$indEmi = '0';
$ultNSU = '';
$AN = true;

header('Content-type: text/html; charset=UTF-8');

if (!$xml = $nfe->getListNFe($AN, $indNFe, $indEmi, $ultNSU, $tpAmb, $modSOAP)){
    echo "Houve erro !! $nfe->errMsg";
    echo '<br><br><PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
} else {
    print_r($xml);
}
?>
