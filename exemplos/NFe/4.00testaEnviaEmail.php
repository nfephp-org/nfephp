<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

//NOTA: o envio de email com o DANFE somente funciona para modelo 55
//      o modelo 65 nunca requer o envio do DANFCE por email

use NFePHP\NFe\ToolsNFe;

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$chave = '52160500067985000172550010000000101000000100';
$nomeXml = "{$chave}-protNFe.xml"; //Evita que o path dos xmls seja exibido no anexo do e-mail
$nomePdf = "{$chave}-danfe.pdf"; //Evita que o path dos pdf seja exibido no anexo do e-mail
$pathXml = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/enviadas/aprovadas/201605/{$nomeXml}";
$pathPdf = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/pdf/201605/{$nomePdf}";

$aMails = array('nfe@chinnonsantos.com'); //se for um array vazio a classe Mail irá pegar os emails do xml
$templateFile = ''; //se vazio usará o template padrão da mensagem
$comPdf = true; //se true, anexa a DANFE no e-mail
try {
    $nfe->enviaMail($pathXml, $aMails, $templateFile, $comPdf, $pathPdf, $nomeXml, $nomePdf);
    echo "DANFE enviada com sucesso!!!";
} catch (NFePHP\Common\Exception\RuntimeException $e) {
    echo $e->getMessage();
}