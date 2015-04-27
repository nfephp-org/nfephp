<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$aResposta = array();

$pathNFefile = '/var/www/nfe/homologacao/assinadas/35150158716523000119550010000000071000000076-nfe.xml';
$pathProtfile = '/var/www/nfe/homologacao/temporarias/201501/35150158716523000119550010000000071000000076-retConsSitNFe.xml';
$saveFile = true;
$retorno = $nfe->addProtocolo($pathNFefile, $pathProtfile, $saveFile);
echo '<br><br><PRE>';
echo htmlspecialchars($retorno);
echo '</PRE><BR>';
echo "<br>";
