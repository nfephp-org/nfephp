<?php
require_once('../libs/CTeNFePHP.class.php');
$nfe = new CTeNFePHP;
$modSOAP = '2'; //usando cURL

//use isso, este é o modo manual voce tem mais controle sobre o que acontece
$filename = 'xml/0008-cte.xml';
//obter um numero de lote



$lote = substr(str_replace(array(',','.'),array('',''),number_format(microtime(true)*1000000,0)),0,15);
// montar o array com a NFe

echo "Lote: $lote<br>";


$aCTe = array(0=>file_get_contents($filename));

//echo $aCTe;

//enviar o lote


if ($aResp = $nfe->sendLot($aCTe, $lote, $modSOAP)){
    if ($aResp['bStat']){
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

//ou isso
//este é modo interno e vai enviar todoas as nf que estiverem na pasta validadas
/*
if ($recibo  = $nfe->autoEnvNFe()){
    echo "Numero do Recibo : " . $recibo .", use este numero para obter o protocolo ou informações de erro no xml.";
} else {
    echo "Houve erro !! $nfe->errMsg";
}
*/

?>
