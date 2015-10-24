<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$aResposta = array();
$tpAmb = '2';
$recibo = '351000087215190';
$retorno = $nfe->sefazConsultaRecibo($recibo, $tpAmb, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
