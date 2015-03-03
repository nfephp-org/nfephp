<?php
/**
 * não se esqueça de manter o config/nfe_ws3_mode55.xml atualizado
 */

require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;

$fonte = 'AN';
$tpAmb = '1';
$ultNSU = 0;
$numNSU = 0;
$aResp = array();
$descompactar = false;

$resposta = $nfe->getDistDFe($fonte, $tpAmb, $ultNSU, $numNSU, $aResp, $descompactar);

echo "<pre>";
echo htmlentities($resposta);
echo "</pre>";
echo '<br><br><br>';
print_r($aResp);
echo '<br><br><br>';
echo "<pre>";
echo htmlentities($nfe->soapDebug);
echo "</pre>";
echo "";
