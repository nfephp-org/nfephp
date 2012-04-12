<?php
/**
 * Exemplo de uso do método autoPrintSend()
 * Todas as NFes contidas na pasta "enviadas/aprovadas" terão o DANFE impresso na impressora definida, 
 * e será enviado um email contendo a NFe em xml e o Danfe em pdf ao destinatario com email indicado na NFe,
 * em caso de sucesso as NFe serão colocadas em um subdiretório denominado com "YYYYMM",
 * sendo "YYYY" igual o ano de emissao da NFe e "MM" o mes de emissão.
 * 
 * ATENÇÃO: Este método somente obterá exito caso o pear Mail esteja instalado e o sistema possa 
 * acessar a impressora estabelecida.
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
if (!$nfe->autoPrintSend()){
    echo $nfe->errMsg;
}
?>
