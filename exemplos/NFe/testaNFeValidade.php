<?php
/**
 * Este é um exemplo para verificação da validade de uma NFe recebida de terceiros
 * esta NFe deve ter o seu protocolo anexado
 * O método irá verificar a aasinatura digital, o protocolo e o digest fornecido pela SEFAZ
 * através de uma consulta ao SEFAZ do estado do emissor
 */
header('Content-type: text/html; charset=UTF-8');
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
//path para o arquivo da NFe recebida de terceiros que se quer verificar a validade
$fileNFe = "../xml/35101158716523000119550010000000011003000000-nfe.xml";
if( !$nfe->verifyNFe($fileNFe) ){
    echo $nfe->errMsg;
} else {
    echo "NFe APROVADA e VÁLIDA";
}
?>
