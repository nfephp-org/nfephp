<?php

require_once(dirname(__FILE__).'/../../libs/NFe/ToolsNFePHP.class.php');

$nfe  = new ToolsNFePHP;
$file = dirname(__FILE__).'/../xml/35101158716523000119550010000000011003000000-nfe.xml';
$arq  = file_get_contents($file);

if ($xml = $nfe->signXML($arq, 'infNFe')) {

    file_put_contents($file, $xml);

} else {

    echo $nfe->errMsg;

}
