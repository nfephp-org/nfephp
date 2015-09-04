<?php

require_once(dirname(__FILE__).'/../../libs/NFe/ToolsNFePHP.class.php');

$nfe     = new ToolsNFePHP('', 1);
$UF      = 'SP';
$CNPJ    = '43651066000154';
$IE      = '';
$CPF     = '';
$tpAmb   = '2';
$modSOAP = '2';

if ($resposta = $nfe->consultaCadastro($UF, $CNPJ, $IE, $CPF, $tpAmb, $modSOAP)) {

    print_r($resposta);
    echo '<pre>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</pre><br>';

} else {

    echo "Houve erro !! $nfe->errMsg";
    echo '<pre>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</pre><br>';

}
