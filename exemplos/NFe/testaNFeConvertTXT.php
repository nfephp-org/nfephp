<?php

require_once(dirname(__FILE__).'/../../libs/NFe/ConvertNFePHP.class.php');

$dir = dirname(__FILE__).'/../xml/';
$arq = '0008.txt';

//instancia a classe
$nfe = new ConvertNFePHP();

if (is_file($arq)) {

    $xml = $nfe->nfetxt2xml($arq);
    $xml = $xml[0];

    if ($xml != '') {

        echo '<pre>';
        echo htmlspecialchars($xml);
        echo '</pre><br>';

        if (!file_put_contents($dir.'0008-nfe.xml', $xml)) {

            echo "ERRO na gravação";

        }

    }

}
