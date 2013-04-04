<?php
/**
 * Exemplo de uso do método autoValidNFe()
 * Serão validadas contra o XSD as NFe já assinadas contidas na pasta "assinadas", 
 * caso a validação seja bem sucedida os arquivos serão movidos para a pasta "validadas",
 * caso contrario serão movidos para a pasta "rejeitadas".
 *   
 * As funções auto contidas na classe seguem uma determinada lógica
 * e movimentam as NFes pelos diretorios da estrutura.
 * 
 * Estas são funções simplificadas que podem ser utilizadas em linha de comando com 
 * o CRON para automatizar as tarefas de gestão das NFe.
 * 
 * As funções auto não são muito adequadas para o tratamento de erros !!!
 * Portanto é desaconselhado seu uso em ambiente Produção, sem outras 
 * ações que permitam o tratamento dos erros.
 * 
 * Recomenda-se o teste e leitura atenta das mesmas antes de tentar por em uso.
 * 
 */
error_reporting(E_ALL);
require_once('../libs/AutoToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
if (!$nfe->autoValidNFe()){
    echo $nfe->errMsg;
}
?>
