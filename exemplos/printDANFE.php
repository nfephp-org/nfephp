<?php
// Passe para este script o arquivo da NFe
// Ex. printDANFE.php?nfe=35100258716523000119550000000033453539003003-nfe.xml

require_once('../libs/DanfeNFePHP.class.php');

//$arq = $_GET['nfe'];
//$arq='./35100406315070000115550010000000180199467603-nfe.xml';
//$arq='./35100258716523000119550000000033453539003003-nfe.xml';
//$arq='./35100459462366000125550010000013490224813007-nfe.xml';
$arq = './35100704374670000129550010000000390000065389-nfe.xml';

if ( is_file($arq) ){
    $docxml = file_get_contents($arq);
    $danfe = new DanfeNFePHP($docxml, 'P', 'A4','../images/logo.jpg','I','');
    $id = $danfe->montaDANFE();
    $teste = $danfe->printDANFE($id.'.pdf','I');
}
?>
