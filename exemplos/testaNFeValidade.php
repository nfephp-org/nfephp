<?php
/**
 * Este é um exemplo para verificação da validade de uma NFe recebida de terceiros
 * esta NFe deve ter o seu protocolo anexado
 * O método irá verificar a aasinatura digital, o protocolo e o digest fornecido pela SEFAZ
 * através de uma consulta ao SEFAZ do estado do emissor
 */

function e($number, $msg, $file, $line, $vars) {
   print_r(debug_backtrace());
   die();
}
set_error_handler('e');

function verifica($arquivo){
    echo "---INICIANDO VERIFICACAO DE [$arquivo]\n";
    $nfe = new ToolsNFePHP;
    $result = $nfe->verifyNFe($arquivo);
    echo "--FUNCAO EXECUTADA: [$result]\n";
    if( !$result ){
        echo "ERROR: [" . trim($nfe->errMsg) . "]\n";
    } else {
        echo "NFe APROVADA e VÁLIDA";
    }
    $nfe->errMsg="";
    echo "---FIM DA VERIFICACAO DE [$arquivo]\n";
}


header('Content-type: text/html; charset=UTF-8');
require_once('../libs/ToolsNFePHP.class.php');
//path para o arquivo da NFe recebida de terceiros que se quer verificar a validade

verifica("xml/nao_existe.xml");
verifica("xml/assinatura_invalida.xml");
verifica("xml/digest_invalido.xml");
verifica("xml/11101284613439000180550010000004881093997017-nfe.xml");

echo "FIM\n";

?>
