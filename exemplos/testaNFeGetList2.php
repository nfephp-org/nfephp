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
    if ($limite > 32) {
        break;
    }
    sleep(5);
}
$nxm .= '</root>';
header('Content-type: text/xml; charset=UTF-8');
echo $nxm;