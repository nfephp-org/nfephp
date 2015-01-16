<?php
/**
 * testaCancelaEvent
 *
 * Rotina de teste de cancelamento por evento
 *
 * Corrija os dados para o cancelamento antes de testar
 */
require_once('../../libs/NFe/ToolsNFePHP.class.php');

$nfe = new ToolsNFePHP;
$chNFe = "<ID da NFe>";
$nProt = "<ID do protocolo de aprovação>";
$xJust = "<descrição do motivo de cancelamento>";
$tpAmb = '2';
$modSOAP = '2';

if ($resp = $nfe->cancelEvent($chNFe,$nProt,$xJust,$tpAmb,$modSOAP)){
    header('Content-type: text/xml; charset=UTF-8');
    echo $resp;
} else {
    header('Content-type: text/html; charset=UTF-8');
    echo '<BR>';
    echo $nfe->errMsg.'<BR>';
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
}
?>
