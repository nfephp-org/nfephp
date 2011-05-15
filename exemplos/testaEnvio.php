<?php
/*
 * Exemplo de envio de Nfe já assinada e validada
 */
require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$modSOAP = '2'; //usando cURL

//use isso, este é o modo manual voce tem mais controle sobre o que acontece  
/*
$filename = './35101158716523000119550010000000011003000000-nfe.xml';
$lote = '123456';//numero que não pode repetir (de 1 a 15 digitos)
$aNFe = array(0=>file_get_contents($filename));
if ($aResp = $nfe->sendLot($aNFe, $lote, $modSOAP)){
    //$aResp = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'');
    if ($aResp['bStat']){
        echo "Numero do Recibo : " . $aResp['nRec'] .", use este numero para obter o protocolo ou informações de erro no xml com testaRecibo.php.";  
    } else {
        echo "Houve erro !! $nfe->errMsg";
    }
} else {
    echo "houve erro !!  $nfe->errMsg";
}
*/

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
