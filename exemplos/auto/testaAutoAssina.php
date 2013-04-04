<?php
/**
 * Exemplo de uso do método autoSignNFe()
 * Serão assinadas todas as NFe, em xml, que se encontrarem na pasta "entradas", com a terminação "*-nfe.xml",
 * caso a assinatura seja bem sucedida os xml serão movidos para a pasta "assinadas".
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
if (!$nfe->autoSignNFe()){
    echo $nfe->errMsg;
}
?>
