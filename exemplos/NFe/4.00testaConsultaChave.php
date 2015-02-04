<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFe\Tools;

$nfe = new Tools('../../config/config.json');

//$chave = '35150158716523000119550000000340851645226073';
//$chave = '42150111703922000181550020000295141002241738';
//$chave = '35141258716523000119550000000337571254185445';
$chave = '35150158716523000119550010000000071000000076';
$tpAmb = '2';
$aResposta = array();
$xml = $nfe->sefazConsultaChave($chave, $tpAmb, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
