<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');
$ncm = '60063100';
$exTarif = '0';
$siglaUF = 'SP';

$resp = $nfe->getImpostosIBPT($ncm, $exTarif, $siglaUF);

print_r($resp);
