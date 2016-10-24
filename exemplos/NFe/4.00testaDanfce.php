<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\Extras\Danfce;
use NFePHP\Common\Files\FilesFolders;

$saida = isset($_REQUEST['o']) ? $_REQUEST['o'] : 'pdf'; //pdf ou html


//$xmlProt = "../xml/xml-erro-montaDanfce.xml";
$xmlProt = "../xml/xml-problema-qrcode.xml";
$docxml = FilesFolders::readFile($xmlProt);

$pathLogo = '../../images/logo.jpg';
$danfce = new Danfce($docxml, $pathLogo, 2);

$ecoNFCe = false; //false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
$id = $danfce->montaDANFCE($ecoNFCe);

$pdfDanfe = "$id-danfce.pdf";
//$salva = $danfce->printDANFCE('pdf', $pdfDanfe, 'F'); //Salva na pasta pdf

$abre = $danfce->printDANFCE($saida, $pdfDanfe, 'I'); //Abre na tela