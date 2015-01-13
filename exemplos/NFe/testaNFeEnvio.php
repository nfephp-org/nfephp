<?php
/*
 * Exemplo de envio de Nfe já assinada e validada
 */
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$modSOAP = '2'; //usando cURL

//use isso, este é o modo manual voce tem mais controle sobre o que acontece
$filename = '../xml/35101158716523000119550010000000011003000000-nfe.xml';
//obter um numero de lote
$lote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
// montar o array com a NFe
$sNFe = file_get_contents($filename);
//enviar o lote
if ($aResp = $nfe->autoriza($sNFe, $lote)) {
    if ($aResp['bStat']) {
        echo "Numero do Recibo : " . $aResp['nRec'] .", use este numero para obter o protocolo ou informações de erro no xml com testaRecibo.php.";
    } else {
        echo "Houve erro !! $nfe->errMsg";
    }
} else {
    echo "houve erro !!  $nfe->errMsg";
}
echo '<BR><BR><h1>DEBUG DA COMUNICAÇÕO SOAP</h1><BR><BR>';
echo '<PRE>';
echo htmlspecialchars($nfe->soapDebug);
echo '</PRE><BR>';
