<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFe\Tools;

$nfe = new Tools('../../config/config.json');
$aResposta = array();

$chave = '35150158716523000119550010000000071000000076';
$nProt = '135150000408219';
$tpAmb = '2';
$xJust = 'Teste de cancelamento em ambiente de homologação';
$retorno = $nfe->sefazCancela($chave, $tpAmb, $xJust, $nProt, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
