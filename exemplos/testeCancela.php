<?php
require_once('../libs/ToolsNFePHP.class.php');
header('Content-type: text/html; charset=UTF-8');
$nfe = new ToolsNFePHP;

$id = "35110358716523000119550000000103241701643172";
$protId = "135110002251645";
$xJust = "Teste de cancelamento da versao 2.0, release 2a.2.31";
$modSOAP = '2';

$resp = $nfe->cancelNF($id, $protId, $xJust, $modSOAP);

echo print_r($resp);
echo '<BR>';
echo $nfe->errMsg.'<BR>';
echo '<PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';

?>
