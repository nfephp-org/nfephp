<?php

require_once('../../libs/NFe/ConvertNFePHP.class.php');

$arq = 'xml/0008.txt';

//instancia a classe
$nfe = new ConvertNFePHP();


if ( is_file($arq) ){
    $xml = $nfe->nfetxt2xml($arq);
    $xml = $xml[0];

    if ($xml != ''){
        echo '<PRE>';
        echo htmlspecialchars($xml);
        echo '</PRE><BR>';
        if (!file_put_contents('0008-nfe.xml',$xml)){
            echo "ERRO na gravação";
        }
    }
}


?>
