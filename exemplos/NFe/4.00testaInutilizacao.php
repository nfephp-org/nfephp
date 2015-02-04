<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFe\Tools;

$nfe = new Tools('../../config/config.json');

$modelo = '55';
$nSerie = 1;
$nIni = 8;
$nFin = 8;
$xJust = 'teste de inutilização de notas fiscais em homologacao';
$tpAmb = '2';
$aResposta = array();

$xml = $nfe->sefazInutiliza($modelo, $nSerie, $nIni, $nFin, $xJust, $tpAmb, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
