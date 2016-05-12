<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$aResposta = array();

$chave = '52160500067985000172550010000000101000000100';
$recibo = '521000011732162';
$pathNFefile = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/assinadas/{$chave}-nfe.xml";
$pathProtfile = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/temporarias/201605/{$recibo}-retConsReciNFe.xml";
$saveFile = true;
$retorno = $nfe->addProtocolo($pathNFefile, $pathProtfile, $saveFile);
echo '<br><br><pre>';
echo htmlspecialchars($retorno);
echo "</pre><br>";
