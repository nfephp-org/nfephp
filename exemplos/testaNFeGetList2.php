<?php

require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('',1,false);
$modSOAP = '2'; //usando cURL
$tpAmb = '1';//usando produção
$indNFe = '0';
$indEmi = '0';
$ultNSU = '';
$AN = true;
$retorno = array();
$indCont = 1;
$limite = 1;
$nxm = '<?xml version="1.0" encoding="utf-8"?><root>';
while ($indCont != 0) { 
    if (!$xml = $nfe->getListNFe($AN, $indNFe, $indEmi, $ultNSU, $tpAmb, $modSOAP, $retorno)) {
        echo "Houve erro !! $nfe->errMsg";
        echo '<br><br><PRE>';
        echo htmlspecialchars($nfe->soapDebug);
        echo '</PRE><BR>';
        exit;
    } else {
        //carrega o retorno 
        $indCont = $retorno['indCont'];
        $nxm .= '<pesquisa num="'.$limite.'">';
        $nxm .= str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $xml);
        $nxm .= '</pesquisa>';
    }
    $limite++;
    //atençao o tempo de execução desse loop pode exceder 
    //o tempo limite de processamento do php e o script pode ser interrompido
    //é recomendável que a pesquisa seja feita em etapas usando o numero do ultNSU
    //registrado 
    if ($limite > 130) {
        break;
    }
    //tem de haver um intervalo de tempo entre cada pesquisa caso contrario o 
    //webservice pode parar de responder, considerando ou um excesso de consultas
    //ou um ataque DoS
    sleep(5);
}
$nxm .= '</root>';
header('Content-type: text/xml; charset=UTF-8');
echo $nxm;