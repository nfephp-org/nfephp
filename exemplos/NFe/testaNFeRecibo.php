<?php
/*
 * Exemplo de solicitação da situação da NFe atraves do numero do
 * recibo de uma nota enviada e recebida com sucesso pelo SEFAZ
 */
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$modSOAP = '2'; //usando cURL
$recibo = ''; //este é o numero do seu recibo mude antes de executar este script
$chave = '41140806161494000172550010000000221324615473';
$tpAmb = '2'; //homologação

header('Content-type: text/xml; charset=UTF-8');
if ($aResp = $nfe->getProtocol($recibo, $chave, $tpAmb, $retorno)){
    //houve retorno mostrar dados
    print_r($aResp);
} else {
    //não houve retorno mostrar erro de comunicação
    echo "Houve erro !! $nfe->errMsg";
}

?>
