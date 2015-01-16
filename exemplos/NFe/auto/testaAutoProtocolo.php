<?php
/**
 * Exemplo de uso do método autoProtNFe()
 * Serão buscados os protocolos das NFes contidas na pasta "enviadas", 
 * as NFe aprovadas terão o protocolo adicionado as mesmas e serão movidas para a pasta "enviadas/aprovadas",
 * as Denegadas terão o protocolo adicionado as mesmas e serão movidas para a pasta "enviadas/denegadas",
 * e as que retornarem erros serão movidas para a pasta "enviadas/reprovadas".
 * 
 * ATENÇÃO: Este método somente obterá exito caso a nota tenha sido aprovada pela SEFAZ, 
 * se foi reprovada um erro será retornado, tipo "A nota não consta da base de dados", 
 * pois a mesma não foi aceita.
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
if (!$nfe->autoProtNFe()){
    echo $nfe->errMsg;
}
?>
