<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('',1,false);
$modSOAP = '2'; //usando cURL
$tpAmb = '2';//usando produção
$chNFe = '<chave de 44 digitos>';

/*
$tpEvento = '210200';//Confirmacao da Operacao //confirma a operação e o recebimento da mercadoria (para as operações com circulação de mercadoria)
                    //Após a Confirmação da Operação pelo destinatário, a empresa emitente fica automaticamente impedida de cancelar a NF-e
                    
$tpEvento = '210210'; //Ciencia da Operacao //encrenca !!! Não usar
                    //O evento de “Ciência da Operação” é um evento opcional e pode ser evitado
                    //Após um período determinado, todas as operações com “Ciência da Operação” deverão
                    //obrigatoriamente ter a manifestação final do destinatário declarada em um dos eventos de
                    //Confirmação da Operação, Desconhecimento ou Operação não Realizada
                    
$tpEvento = '210220'; //Desconhecimento da Operacao
                    //Uma empresa pode ficar sabendo das operações destinadas a um determinado CNPJ
                    //consultando o “Serviço de Consulta da Relação de Documentos Destinados” ao seu CNPJ.
                    //O evento de “Desconhecimento da Operação” permite ao destinatário informar o seu
                    //desconhecimento de uma determinada operação que conste nesta relação, por exemplo
                    
$tpEvento = '210240'; //Operacao nao Realizada 
                      //não aceitação no recebimento que antes se fazia com apenas um carimbo na NF
 */

$tpEvento = '210200';
$resp = '';
if (!$xml = $nfe->manifDest($chNFe,$tpEvento,'',$tpAmb,$modSOAP,$resp)){
    header('Content-type: text/html; charset=UTF-8');
    echo "Houve erro !! $nfe->errMsg";
    echo '<br><br><PRE>';
    echo htmlspecialchars($nfe->soapDebug);
    echo '</PRE><BR>';
} else {
    header('Content-type: text/xml; charset=UTF-8');
    print_r($xml);
    //echo '<BR><BR><BR><BR><BR>';
    //print_r($resp);
}

?>
