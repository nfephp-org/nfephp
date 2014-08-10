<?php
/*
 * Exemplo de solicitação da situação da NFe atraves do numero do
 * recibo de uma nota enviada e recebida com sucesso pelo SEFAZ
 */
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$chave = '';
$xCorrecao = 'NFe emitida em Ambiente de Homologação';
$nSeq = 1;
$tpAmb = '2'; //homologação

header('Content-type: text/xml; charset=UTF-8');
if ($aResp = $nfe->envCCe($chave, $xCorrecao, $nSeq, $tpAmb, $retorno)){
    //houve retorno mostrar dados
    print_r($aResp);
} else {
    //não houve retorno mostrar erro de comunicação
    echo "Houve erro !! $nfe->errMsg";
}

?>
