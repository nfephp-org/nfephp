<?php

/*
 * Rotina para testar o tipo de XML dentre aqueles estabelecidos pelos
 * Manuais da SEFAZ
 * 
 */
$filename = '<nome do arquivo>';
$xmlfile = file_get_contents($filename);
$xml = simplexml_load_string($xmlfile);
$tagroot = $xml->getName();
switch ($tagroot){
    case 'NFe':
        //NFe sem protocolo
        break;
    case 'nfeProc':
        //NFe com o protocolo
        break;
    case 'CTe':
        //CTe sem protocolo
        break;
    case 'cteProc':
        //CTe com o protocolo
        break;
    case 'evento':
        //evento sem o protocolo
        break;
    case 'envEvento':
        //Envio de evento 
        break;
    case 'retEnvEvento':
        //Retorno de evento
        break;
    case 'procEventoNFe':
        //Evento com o protocolo
        break;
    case 'ConsCad':
        //consulta de cadastro
        break;
    case 'consReciNFe':
        //consulta recibo
        break;
    case 'retConsReciNFe':
        //retorno da consulta do recibo
        break;
    case 'cancNFe':
        //solicitação de cancelamento - DEPRECATE
        break;
    case 'retCancNFe':
        //retorno da solicitação de cancelamento - DEPRECATE
        break;
    case 'inutNFe':
        //solicitação de inutilização
        break;
    case 'retInutNFe':
        //retorno da solicitação de inutilização
        break;
    case 'consSitNFe':
        //consulta da situação da NFe
        break;
    case 'retConsSitNFe':
        //retorno da consulta da situação da NFe
        break;
    case 'consStatServ':
        //consulta do status do serviço
        break;
    case 'retConsStatServ':
        //retorno da consulta do status do serviço 
        break;
}



?>
