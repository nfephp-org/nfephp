<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

//NOTA: o envio de email com o DANFE somente funciona para modelo 55
//      o modelo 65 nunca requer o envio do DANFCE por email

use NFePHP\NFe\ToolsNFe;


$xml = __DIR__.'/../xml/35150300822602000124550010009923461099234656-nfe.xml';

$nfe = new ToolsNFe('../../config/config.json');
$nfe->setModelo('55');

$aMails = ['linux.rlm@gmail.com']; //se for um array vazio a classe Mail irá pegar os emails do xml
$templateFile = ''; //se vaizio usará o template padrão da mensagem
$comPdf = true;
try {
    $nfe->enviaMail($xml, $aMails, $templateFile, $comPdf);
} catch (NFePHP\Common\Exception\RuntimeException $e) {
    echo $e->getMessage();
}