<?php
/*
 * Exemplo de solicitação da situação da NFe atraves do numero do
 * recibo de uma nota enviada e recebida com sucesso pelo SEFAZ
 */
require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$modSOAP = '2'; //usando cURL
$recibo = '351000598839931'; //este é o numero do seu recibo mude antes de executar este script
$chave = '';
$tpAmb = '2'; //homologação

header('Content-type: text/html; charset=UTF-8');
if ($aResp = $nfe->getProtocol($recibo, $chave, $tpAmb, $modSOAP)){
    //houve retorno mostrar dados
    print_r($aResp);
} else {
    //não houve retorno mostrar erro de comunicação
    echo "Houve erro !! $nfe->errMsg";
}

?>
