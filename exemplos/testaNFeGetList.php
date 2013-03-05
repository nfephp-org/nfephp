<?php
require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('',1,false);
$modSOAP = '2'; //usando cURL
$tpAmb = '1';//usando produção
$indNFe = '0';
$indEmi = '0';
$ultNSU = '';
$AN = true;
$retorno = array();

if (!$xml = $nfe->getListNFe($AN, $indNFe, $indEmi, $ultNSU, $tpAmb, $modSOAP, $retorno)){
    header('Content-type: text/html; charset=UTF-8');
    echo "Houve erro !! $nfe->errMsg";
    echo '<br><br><PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
} else {
    header('Content-type: text/xml; charset=UTF-8');
    print_r($xml);
}
?>