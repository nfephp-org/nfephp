<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$chave = '35150458716523000119550010000000131000000139';
$tpAmb = '2';
$xml = "/var/www/nfe/homologacao/assinadas/$chave-nfe.xml";

if (! $nfe->validarXml($xml)) {
    echo "Eita !?! Tem bicho na linha .... <br>";
    foreach ($nfe->errors as $erro) {
        echo "$erro <br>";
    }
    exit();
}
echo "NFe Valida !";
