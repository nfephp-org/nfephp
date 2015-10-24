<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$chave = '35150158716523000119550010000000071000000076';
$tpAmb = '2';
$aResposta = array();
$xml = $nfe->sefazConsultaChave($chave, $tpAmb, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
