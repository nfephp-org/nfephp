<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$aResposta = array();
$chave = '52160700067985000172650010000002011000002015';
$tpAmb = '2';
// $aXml = file_get_contents("/var/www/nfe/homologacao/assinadas/{$chave}-nfe.xml"); // Ambiente Linux
$aXml = file_get_contents("D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/assinadas/{$chave}-nfe.xml"); // Ambiente Windows
$idLote = '';
$indSinc = '1'; //0 - Assíncrono (Gera Recibo p/ Consulta), 1 - síncrono (Sem recibo, resposta imediata);
$flagZip = false;
$retorno = $nfe->sefazEnviaLote($aXml, $tpAmb, $idLote, $aResposta, $indSinc, $flagZip);
echo '<br><br><pre>';
echo htmlspecialchars($nfe->soapDebug);
echo '</pre><br><br><pre>';
print_r($aResposta);
echo "</pre><br>";
