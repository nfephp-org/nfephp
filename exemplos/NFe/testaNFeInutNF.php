<?php

/*
 * Exemplo de solicitação de Inutilização de faixa de numeros
 * da NFe (modelo 55)
 */
require_once(dirname(__FILE__).'/../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('', 2, false);

$nAno    = '13';//ano atual com 2 digitos
$nSerie  = '1';//numero da serie da NFe que será inutilizada
$nIni    = '4';// numero inicial da faixa que será inutilizada
$nFin    = '6';// numero final da faixa que será inutilizada
$xJust   = 'Falha na gravação da base de doas do ERP'; // entre 15 e 255 dígitos
$tpAmb   = '2';//homologação
$modSOAP = '2';//usando cURL

header('Content-type: text/html; charset=UTF-8');

if ($xml = $nfe->inutNF($nAno, $nSerie, $nIni, $nFin, $xJust, $tpAmb, $modSOAP)) {

    //houve retorno mostrar dados
    echo '<br>';
    echo '<pre>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</pre>';
    echo '<br>';
    echo 'Retorno da Solicitação de Inutilização';
    echo '<br>';
    echo '<pre>';
    echo htmlspecialchars($xml);
    echo '</pre>';

} else {

    //não houve retorno mostrar erro de comunicação
    echo "Houve erro na comunicação !! $nfe->errMsg";
    echo '<br>';
    echo '<pre>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</pre>';
    echo '<br>';

}
