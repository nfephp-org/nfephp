<?php

require_once('../libs/ToolsNFePHP.class.php');

$tpEvento = '10201';

$chaveNFe='35101158716523000119550010000000011003000000';
$nSeqEvento=1;
$tpAmb = '2';
$xCorrecao='Endereço correto Rua x numero 2222';
$modSOAP = '2';
header('Content-type: text/xml; charset=UTF-8');

echo sendEvent($tpEvento,$chaveNFe,$nSeqEvento,$tpAmb,$xCorrecao,$modSOAP='2');

function sendEvent($tpEvento,$chNFe='',$nSeqEvento=1,$tpAmb='',$xCorrecao='',$modSOAP='2'){
    
    $nfe = new ToolsNFePHP;
    
    $aEvent = array('10201'=>'Carta de Correcao',
                    '10202'=>'Registros de saida',
                    '10203'=>'Roubo de Carga',
                    '30401'=>'Confirmacao de recebimento',
                    '30402'=>'Desconhecimento da operacao',
                    '30403'=>'Devolucao de mercadoria');
    
    if ($aEvent[$tpEvento]==''){
        return false;
    }
    //novo campo ver onde colocar na classe
    $timeZone = '-03:00';
    $descEvento = $aEvent[$tpEvento];
    $numLote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
    $cUF = '35';
    $CNPJ = '58716523000119';
    //$nSeqEvento = str_pad($nSeqEvento,3,'0',STR_PAD_LEFT);
    $enableSVAN = false;
    //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
    $dhEvento = date('Y-m-d').'T'.date('H:i:s');
    //se o envio for para svan mudar o numero no orgão para 90
    if ($enableSVAN){
        $cOrgao='90';
    } else {
        $cOrgao=$cUF;
    }
    //ver como burcar a versão do evento
    $verEvento = '1.00';
    $URLPortal = 'http://www.portalfiscal.inf.br/nfe';
    
    //################ PROBLEMA ####################################
    //ESSES CAMARADAS DA SEFAZ SÃO DOIDOS E NÃO SABE O QUE QUEREM!!!
    //ID do evento ?????
    //de acordo com o manual da CC-e de junho 2010 54 digitos
    //“ID” + tpEvento + chave da NF-e + nSeqEvento
    $id = "ID$tpEvento$cUF$chNFe$nSeqEvento";
    //OU
    //de acordo com o manual de enventos de amosto 2009 67 digitos
    //“ID” + cdEvento (9999999) + data e hora do evento(aaaammddhhmmss)+ chave da NF-e
    //$dhID = str_replace(array('T',':','-'),'',$dhEvento);
    //$id = "ID$tpEvento$cUF$dhID$chNFe";
    //################ PROBLEMA ####################################
    
    //montagem do namespace do serviço
    $servico = 'RecepcaoEvento';
    $namespace = $URLPortal.'/wsdl/'.$servico;

    //montagem dos dados 
    $dados = "";
    $dados .= "<envEvento xmlns=\"$URLPortal\" versao=\"$verEvento\">";
    $dados .= "<idLote>$numLote</idLote>";
    $dados .= "<evento xmlns=\"$URLPortal\" versao=\"$verEvento\">";
    $dados .= "<infEvento Id=\"$id\">";
    $dados .= "<cOrgao>$cOrgao</cOrgao>";
    $dados .= "<tpAmb>$tpAmb</tpAmb>";
    $dados .= "<CNPJ>$CNPJ</CNPJ>";
    $dados .= "<chNFe>$chNFe</chNFe>";
    $dados .= "<dhEvento>$dhEvento$timeZone</dhEvento>";
    $dados .= "<tpEvento>$tpEvento$cUF</tpEvento>";
    $dados .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
    $dados .= "<verEvento>$verEvento</verEvento>";
    $dados .= "<detEvento versao=\"$verEvento\">";
    $dados .= "<descEvento>$descEvento</descEvento>";
    $dados .= "<xCorrecao>$xCorrecao</xCorrecao>";
    $dados .= "<xCondUso>A Carta de Correcao e disciplinada pelo paragrafo 1o-A do art. 7o do Convenio S/N, de 15 de dezembro de 1970 e pode ser utilizada para regularizacao de erro ocorrido na emissao de documento fiscal, desde que o erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da operacao ou da prestacao; II - a correcao de dados cadastrais que implique mudanca do remetente ou do destinatario; III - a data de emissao ou de saida.</xCondUso>";
    $dados .= "</detEvento></infEvento></evento></envEvento>";
    //assinatura dos dados
    $tagid = 'infEvento';
    $dados = $nfe->signXML($dados, $tagid);
    $dados = str_replace('<?xml version="1.0"?>','', $dados);
    $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
    $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
    $dados = str_replace(array("\r","\n","\s"),"", $dados);
    //montagem da mensagem
    $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
    $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
    
    
    return $dados;
    

} //fim sendEvent

?>
