<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;
use NFePHP\Extras\Danfce;
use NFePHP\Common\Files\FilesFolders;

$nfe = new ToolsNFe('../../config/config.json');
//$nfe->aConfig['aDocFormat']->pathLogoFile // Logo em config

$saida = isset($_REQUEST['o']) ? $_REQUEST['o'] : 'pdf'; //pdf ou html

$ecoNFCe = false; //false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
$chave = '52160522234907000158650010000002001000002009';
$xmlProt = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/enviadas/aprovadas/201605/{$chave}-protNFe.xml";
// Uso da nomeclatura '-danfce.pdf' para facilitar a diferenciação entre PDFs DANFE e DANFCE salvos na mesma pasta...
$pdfDanfe = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/pdf/201605/{$chave}-danfce.pdf";

$docxml = FilesFolders::readFile($xmlProt);
$danfce = new Danfce($docxml, '', 2);
$id = $danfce->montaDANFCE($ecoNFCe);
$salva = $danfce->printDANFCE('pdf', $pdfDanfe, 'F'); //Salva na pasta pdf
$abre = $danfce->printDANFCE($saida, $pdfDanfe, 'I'); //Abre na tela