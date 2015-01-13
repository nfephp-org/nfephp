<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;

$file = 'xml/35130471780456000160550010000000411000000410-nfe.xml';
$arq = file_get_contents($file);

if ($xml = $nfe->signXML($arq, 'infNFe')){
    file_put_contents($file, $xml);
} else {
    echo $nfe->errMsg;
}


?>
