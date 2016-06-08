<?php

header('Content-Type: text/html; charset=utf-8');

if(isset($_POST['conteudoArquivo']) || strlen(trim($_POST['conteudoArquivo'])) > 0){


    //$config = configuracao da nfe
    $nfe = new \NFePHP\NFe\ToolsNFe(json_encode($config));

    $tpAmb = '2';
    $aXml = $_POST['conteudoArquivo'];
    $idLote = '';
    $indSinc = '0';
    $flagZip = false;
    $aResposta;

    //array vazio passado como referencia
    $aResp = array();

    //enviar o lote
    if ($resp = $nfe->sefazEnviaLote( $aXml, $tpAmb, $idLote, $aResposta, $indSinc, $flagZip)) {
        if ($aResp['bStat']) {
            echo("Nota transmitida com sucesso! Utilize o número [" . $aResp['infRec']['nRec'] . "] do recibo para obter o protocolo ou informações de erro no xml com testaRecibo.php.");
        } else {
            echo('ERRO: ' . $nfe->errMsg);
        }
    } else {
        echo('ERRO: ' . $nfe->errMsg);
    }
    echo('---------- BEGIN - SOAP DEBUG ----------');
    echo($nfe->soapDebug);
    echo('----------- END - SOAP DEBUG -----------');

    die();
}

echo('ERRO: O conteúdo está vazio!');

?>
