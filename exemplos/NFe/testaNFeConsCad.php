<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('',1);
$UF = 'SP';
$CNPJ = '43651066000154';
$IE = '';
$CPF = '';
$tpAmb = '2';
$modSOAP = '2';

if ($resposta = $nfe->consultaCadastro($UF, $CNPJ, $IE, $CPF, $tpAmb, $modSOAP) ){
    print_r($resposta);
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
} else {
    echo "Houve erro !! $nfe->errMsg";
    echo '<PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
}    

?>
