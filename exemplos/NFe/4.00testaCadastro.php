<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$aResposta = array();
$siglaUF = 'SP';
$tpAmb = '1';
$cnpj = '68252816000146';
$iest = '';
$cpf = '';
$retorno = $nfe->sefazCadastro($siglaUF, $tpAmb, $cnpj, $iest, $cpf, $aResposta);
echo '<br><br><PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
print_r($aResposta);
echo "<br>";
