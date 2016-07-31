<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$aResposta = array();

$indSinc = '1'; //0 - Assíncrono (Gera Recibo p/ Consulta), 1 - síncrono (Sem recibo, resposta imediata);
$chave = '52160700067985000172650010000002011000002015';
$recibo = '146971452557723';
$pathNFefile = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/assinadas/{$chave}-nfe.xml";
if (! $indSinc) {
    $pathProtfile = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/temporarias/201607/{$recibo}-retConsReciNFe.xml";
} else {
    $pathProtfile = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/temporarias/201607/{$recibo}-retEnviNFe.xml";
}
$saveFile = true;
$retorno = $nfe->addProtocolo($pathNFefile, $pathProtfile, $saveFile);
echo '<br><br><pre>';
echo htmlspecialchars($retorno);
echo "</pre><br>";
