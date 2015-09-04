<?php
// Passe para este script o arquivo da NFe
// Ex. testaDANFE.php?nfe=35100258716523000119550000000033453539003003-nfe.xml

require_once(dirname(__FILE__).'/../../libs/NFe/DanfeNFePHP.class.php');

//$arq = $_GET['nfe'];
$arq = dirname(__FILE__).'/../xml/35101158716523000119550010000000011003000000-nfe.xml';

if (is_file($arq)) {

    $docxml = file_get_contents($arq);
    $danfe  = new DanfeNFePHP($docxml, 'P', 'A4', dirname(__FILE__).'/../../images/logo.jpg', 'I', '');
    $id     = $danfe->montaDANFE();
    $teste  = $danfe->printDANFE($id.'.pdf', 'I');

}
