<?php

/*
 * Exemplo de solicitação de Inutilização de faixa de numeros 
 * da NFe (modelo 55)
 */
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('', 2, false);

$nAno = '13';//ano atual com 2 digitos
$nSerie = '1';//numero da serie da NFe que será inutilizada
$nIni = '4';// numero inicial da faixa que será inutilizada
$nFin = '6';// numero final da faixa que será inutilizada
$xJust = 'Falha na gravação da base de doas do ERP'; // entre 15 e 255 dígitos
$tpAmb = '2';//homologação
$modSOAP = '2';//usando cURL

header('Content-type: text/html; charset=UTF-8');

if ($xml = $nfe->inutNF($nAno, $nSerie, $nIni, $nFin, $xJust, $tpAmb, $modSOAP)) {
    //houve retorno mostrar dados
    echo '<BR>';
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE>';
    echo '<BR>';
    echo 'Retorno da Solicitação de Inutilização';
    echo '<BR>';
    echo '<PRE>';
    echo htmlspecialchars($xml);
    echo '</PRE>';
} else {
    //não houve retorno mostrar erro de comunicação
    echo "Houve erro na comunicação !! $nfe->errMsg";
    echo '<BR>';
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE>';
    echo '<BR>';
}
       