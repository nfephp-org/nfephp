<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$aResposta = array();
$siglaUF = 'MG';
$tpAmb = '2';
$retorno = $nfe->sefazStatus($siglaUF, $tpAmb, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
