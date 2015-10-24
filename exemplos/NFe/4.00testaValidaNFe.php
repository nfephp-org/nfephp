<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$aResposta = array();
$pathXmlFile = '/var/www/nfephpdev/exemplos/xml/35150258716523000119550000000344861511504837-nfe.xml';
if (! $nfe->verificaValidade($pathXmlFile, $aResposta)) {
    echo "<h1>NFe INVÁLIDA!!</h1>";
} else {
    echo "<h1>NFe valida.</h1>";
}
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
