<?php
/* 
 * 
 * 
 */

require_once('./libs/nusoap/nusoap.php');
require_once('config_inc.php');

$wsdl     = 'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfestatusservico.asmx?WSDL';
$xsi      = 'http://www.w3.org/2001/XMLSchema-instance';
$xsd      = 'http://www.w3.org/2001/XMLSchema';
$nfe      = 'http://www.portalfiscal.inf.br/nfe';

try {
    $client   = new nusoap_client($wsdl, true);

    $client->authtype         = 'certificate';
    $client->soap_defencoding = 'UTF-8';


    //Mostra o certificado e a senha para acesso:
    $client->certRequest['sslkeyfile'] = $certDir.$keyName;
    $client->certRequest['sslcertfile'] = $certDir.$certName;
    $client->certRequest['passphrase']  = $passPhrase;
    $client->certRequest['verifypeer']  = false;
    $client->certRequest['verifyhost']  = false;
    $client->certRequest['trace']       = 1;
}
catch (Exception $ex) {
    echo 'failed';
}

//header('Content-type: text/xml');
//print_r ($result);

//$erro = $client->getError();

//echo "<br><h2>Erro:</h2> {$erro} <br>";

/**
 * chamada do método SOAP
 * Obs: No xml não pode haver espaços entre as tags e nem < enter > entre as linhas*/

$param = array(
    "nfeCabecMsg" =>'<?xml version="1.0" encoding="utf-8"?><cabecMsg versao="1.02" xmlns="http://www.portalfiscal.inf.br/nfe"><versaoDados>1.07</versaoDados></cabecMsg>',
    "nfeDadosMsg" =>'<consStatServ xmlns:xsi="'.$xsi.'" xmlns:xsd="'.$xsd.'" versao="1.07" xmlns="'.$nfe.'">'.'<tpAmb>2</tpAmb><cUF>35</cUF><xServ>STATUS</xServ></consStatServ>'
);

$result = $client->call( "nfeStatusServicoNF", $param );

//print_r($result);

// OPCIONAL : exibe a requisição e a resposta
//echo '<h2>Requisicao</h2>';
echo '<pre>' . htmlspecialchars( $client->request ) . '</pre>';
//echo '<h2>Resposta</h2>';
echo '<pre>' . htmlspecialchars( $client->response ) . '</pre>';
// Exibe mensagens para debug
echo '<h1>Debug</h1>';
echo '<pre>' . htmlspecialchars( $client->debug_str ) . '</pre>';
/*********************************************************************************/

?>
