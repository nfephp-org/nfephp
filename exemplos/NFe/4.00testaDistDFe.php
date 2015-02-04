<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFe\Tools;

$nfe = new Tools('../../config/config.json');

$ultNSU = 0;
$numNSU = 165;
$tpAmb = '1';
$cnpj = ''; //deixando vazio irá pegar o CNPJ default do config
$descompactar = true;
$aResposta = array();
$xml = $nfe->sefazDistDFe('AN', $tpAmb, $cnpj, $ultNSU, $numNSU, $aResposta, $descompactar);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
