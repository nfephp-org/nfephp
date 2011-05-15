<?php

require_once('../libs/ConvertNFePHP.class.php');

$arq = './0008.txt';

//instancia a classe
$nfe = new ConvertNFePHP();

if ( is_file($arq) ){
    $xml = $nfe->nfetxt2xml($arq);
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
