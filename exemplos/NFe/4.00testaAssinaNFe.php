<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');

//$chave = '35150158716523000119550010000000061000000060';
//$chave = '35150158716523000119550010000000071000000076';
$chave = '35150258716523000119550010000000091000000090';
$filename = "/var/www/nfe/homologacao/entradas/$chave-nfe.xml";
$xml = file_get_contents($filename);
$xml = $nfe->assina($xml);
$filename = "/var/www/nfe/homologacao/assinadas/$chave-nfe.xml";
file_put_contents($filename, $xml);
chmod($filename, 0777);
echo $chave;
