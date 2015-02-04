<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFe\Tools;

$nfe = new Tools('../../config/config.json');
$aResposta = array();

//$chave = '35150158716523000119550010000000071000000076';
$chave = '35150258716523000119550010000000091000000090';
$tpAmb = '2';
$aXml = file_get_contents("/var/www/nfe/homologacao/assinadas/$chave-nfe.xml");
$idLote = '';
$indSinc = '0';
$flagZip = false;
$retorno = $nfe->sefazAutoriza($aXml, $tpAmb, $idLote, $aResposta, $indSinc, $flagZip);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
